<?php
require_once '../config.php';

header('Content-Type: application/json');

$action = $_GET['action'] ?? '';

try {
    switch ($action) {
        case 'specialties':
            $stmt = dbQuery("SELECT * FROM specialties ORDER BY name");
            echo json_encode(['success' => true, 'data' => dbFetchAll($stmt)]);
            break;
            
        case 'doctors':
            $specialtyId = $_GET['specialty_id'] ?? null;
            if ($specialtyId) {
                $sql = "
                    SELECT d.*, s.name as specialty_name 
                    FROM doctors d 
                    INNER JOIN specialties s ON d.specialty_id = s.id 
                    WHERE d.is_active = 1 AND d.specialty_id = ?
                    ORDER BY d.last_name
                ";
                $stmt = dbQuery($sql, [$specialtyId]);
            } else {
                $sql = "
                    SELECT d.*, s.name as specialty_name 
                    FROM doctors d 
                    INNER JOIN specialties s ON d.specialty_id = s.id 
                    WHERE d.is_active = 1 
                    ORDER BY s.name, d.last_name
                ";
                $stmt = dbQuery($sql);
            }
            echo json_encode(['success' => true, 'data' => dbFetchAll($stmt)]);
            break;
            
        case 'time_slots':
            $doctorId = $_GET['doctor_id'] ?? null;
            $date = $_GET['date'] ?? date('Y-m-d');
            
            if (!$doctorId) {
                throw new Exception('Не указан врач');
            }
            
            // Получаем расписание врача
            $sql = "
                SELECT start_time, end_time, is_available 
                FROM doctor_schedule 
                WHERE doctor_id = ? AND work_date = ? AND is_available = 1
                ORDER BY start_time
            ";
            $stmt = dbQuery($sql, [$doctorId, $date]);
            $schedule = dbFetchAll($stmt);
            
            // Получаем уже забронированные слоты
            $sql = "
                SELECT appointment_time 
                FROM appointments 
                WHERE doctor_id = ? AND appointment_date = ? AND status = 'active'
            ";
            $stmt = dbQuery($sql, [$doctorId, $date]);
            $rows = dbFetchAll($stmt);
            $booked = array_column($rows, 'appointment_time');
            
            $slots = [];
            foreach ($schedule as $slot) {
                $time = $slot['start_time'];
                $isBooked = in_array($time, $booked);
                $slots[] = [
                    'time' => $time,
                    'available' => !$isBooked
                ];
            }
            
            // Если расписание пустое, генерируем стандартные слоты
            if (empty($slots)) {
                $standardTimes = ['09:00', '09:30', '10:00', '10:30', '11:00', '11:30', 
                                  '14:00', '14:30', '15:00', '15:30', '16:00', '16:30', '17:00'];
                foreach ($standardTimes as $time) {
                    $slots[] = [
                        'time' => $time,
                        'available' => !in_array($time, $booked)
                    ];
                }
            }
            
            echo json_encode(['success' => true, 'data' => $slots]);
            break;
            
        default:
            throw new Exception('Неизвестное действие');
    }
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
