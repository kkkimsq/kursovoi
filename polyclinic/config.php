<?php
// Конфигурация базы данных - SQL Server
define('DB_SERVER', 'WIN-E35DNA7AABF\SQLEXPRESS');
define('DB_NAME', 'MedicalClinicDB');
define('DB_CONNECTION_STRING', 'Server=WIN-E35DNA7AABF\SQLEXPRESS;Database=MedicalClinicDB;Trusted_Connection=True;');

// Настройки сайта
define('SITE_NAME', 'Платная Поликлиника');
define('SITE_URL', 'http://localhost/polyclinic');
define('ADMIN_EMAIL', 'admin@polyclinic.ru');

// Настройки сессии
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_secure', 0); // Установить 1 для HTTPS

// Отображение ошибок (отключить на production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Timezone
date_default_timezone_set('Europe/Moscow');

// Подключение к базе данных - SQL Server
function getDBConnection() {
    static $conn = null;
    
    if ($conn === null) {
        try {
            $conn = sqlsrv_connect(DB_SERVER, [
                'Database' => DB_NAME,
                'ConnectionPooling' => true,
                'CharacterSet' => 'UTF-8',
                'ReturnDatesAsStrings' => true
            ]);
            
            if ($conn === false) {
                $errors = sqlsrv_errors();
                $errorMsg = '';
                foreach ($errors as $error) {
                    $errorMsg .= $error['message'] . "\n";
                }
                throw new Exception($errorMsg);
            }
        } catch (Exception $e) {
            die("Ошибка подключения к базе данных: " . $e->getMessage());
        }
    }
    
    return $conn;
}

// Функция для выполнения запроса и получения результата
function dbQuery($sql, $params = []) {
    $conn = getDBConnection();
    $stmt = sqlsrv_query($conn, $sql, $params);
    
    if ($stmt === false) {
        $errors = sqlsrv_errors();
        $errorMsg = '';
        foreach ($errors as $error) {
            $errorMsg .= $error['message'] . "\n";
        }
        throw new Exception("Ошибка выполнения запроса: " . $errorMsg);
    }
    
    return $stmt;
}

// Функция для получения всех результатов
function dbFetchAll($stmt) {
    $results = [];
    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        // Преобразуем даты в строки
        foreach ($row as $key => $value) {
            if ($value instanceof DateTime) {
                $row[$key] = $value->format('Y-m-d H:i:s');
            }
        }
        $results[] = $row;
    }
    return $results;
}

// Функция для получения одной записи
function dbFetchOne($stmt) {
    $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
    if ($row) {
        foreach ($row as $key => $value) {
            if ($value instanceof DateTime) {
                $row[$key] = $value->format('Y-m-d H:i:s');
            }
        }
    }
    return $row;
}

// Функция для безопасного вывода
function e($string) {
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}

// Проверка авторизации администратора
function isAdminLoggedIn() {
    return isset($_SESSION['admin_id']) && isset($_SESSION['admin_role']);
}

// Проверка роли системного администратора
function isSystemAdmin() {
    return isset($_SESSION['admin_role']) && $_SESSION['admin_role'] === 'system_admin';
}

// Перенаправление
function redirect($url) {
    header("Location: " . $url);
    exit;
}

// Генерация CSRF токена
function generateCSRFToken() {
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

// Проверка CSRF токена
function validateCSRFToken($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}
