<?php
require_once '../config.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Метод не разрешен']);
    exit;
}

try {
    // Получаем данные из формы
    $patientFirstName = trim($_POST['first_name'] ?? '');
    $patientLastName = trim($_POST['last_name'] ?? '');
    $patientMiddleName = trim($_POST['middle_name'] ?? '');
    $patientEmail = trim($_POST['email'] ?? '');
    $patientPhone = trim($_POST['phone'] ?? '');
    $doctorId = $_POST['doctor_id'] ?? null;
    $appointmentDate = $_POST['appointment_date'] ?? '';
    $appointmentTime = $_POST['appointment_time'] ?? '';
    $serviceId = $_POST['service_id'] ?? null;
    $notes = trim($_POST['notes'] ?? '');
    
    // Валидация
    $errors = [];
    
    if (empty($patientFirstName)) $errors[] = 'Введите имя';
    if (empty($patientLastName)) $errors[] = 'Введите фамилию';
    if (empty($patientEmail) || !filter_var($patientEmail, FILTER_VALIDATE_EMAIL)) $errors[] = 'Введите корректный email';
    if (empty($patientPhone)) $errors[] = 'Введите телефон';
    if (!$doctorId) $errors[] = 'Выберите врача';
    if (empty($appointmentDate)) $errors[] = 'Выберите дату';
    if (empty($appointmentTime)) $errors[] = 'Выберите время';
    
    if (!empty($errors)) {
        throw new Exception(implode(', ', $errors));
    }
    
    // Проверка на двойное бронирование
    $sql = "
        SELECT COUNT(*) as cnt FROM appointments 
        WHERE doctor_id = ? AND appointment_date = ? AND appointment_time = ? AND status = 'active'
    ";
    $stmt = dbQuery($sql, [$doctorId, $appointmentDate, $appointmentTime]);
    $row = dbFetchOne($stmt);
    $count = $row['cnt'] ?? 0;
    
    if ($count > 0) {
        throw new Exception('Это время уже забронировано. Пожалуйста, выберите другое время.');
    }
    
    // Начинаем транзакцию - в SQL Server через sqlsrv_begin_transaction
    $conn = getDBConnection();
    sqlsrv_begin_transaction($conn);
    
    // Ищем или создаем пациента
    $sql = "SELECT id FROM patients WHERE email = ?";
    $stmt = dbQuery($sql, [$patientEmail]);
    $row = dbFetchOne($stmt);
    $patientId = $row['id'] ?? null;
    
    if (!$patientId) {
        $sql = "
            INSERT INTO patients (first_name, last_name, middle_name, email, phone) 
            VALUES (?, ?, ?, ?, ?)
        ";
        dbQuery($sql, [$patientFirstName, $patientLastName, $patientMiddleName, $patientEmail, $patientPhone]);
        
        // Получаем последний ID
        $sql = "SELECT SCOPE_IDENTITY() as id";
        $stmt = dbQuery($sql);
        $row = dbFetchOne($stmt);
        $patientId = $row['id'];
    } else {
        // Обновляем данные пациента
        $sql = "
            UPDATE patients 
            SET first_name = ?, last_name = ?, middle_name = ?, phone = ? 
            WHERE id = ?
        ";
        dbQuery($sql, [$patientFirstName, $patientLastName, $patientMiddleName, $patientPhone, $patientId]);
    }
    
    // Создаем запись
    $sql = "
        INSERT INTO appointments (patient_id, doctor_id, service_id, appointment_date, appointment_time, notes) 
        VALUES (?, ?, ?, ?, ?, ?)
    ";
    dbQuery($sql, [$patientId, $doctorId, $serviceId, $appointmentDate, $appointmentTime, $notes]);
    
    // Получаем ID записи
    $sql = "SELECT SCOPE_IDENTITY() as id";
    $stmt = dbQuery($sql);
    $row = dbFetchOne($stmt);
    $appointmentId = $row['id'];
    
    // Коммит транзакции
    sqlsrv_commit($conn);
    
    // Отправка email подтверждения (в реальном проекте настроить SMTP)
    // mail($patientEmail, 'Подтверждение записи', ...);
    
    echo json_encode([
        'success' => true, 
        'message' => 'Вы успешно записаны на прием!',
        'appointment_id' => $appointmentId
    ]);
    
} catch (Exception $e) {
    $conn = getDBConnection();
    if ($conn) {
        @sqlsrv_rollback($conn);
    }
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
