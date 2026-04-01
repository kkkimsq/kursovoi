<?php
// Конфигурация базы данных - SQL Server
define('DB_SERVER', 'WIN-E35DNA7AABF\\SQLEXPRESS');
define('DB_NAME', 'MedicalClinicDB');

// Настройки сайта
define('SITE_NAME', 'Платная Поликлиника');
define('SITE_URL', 'http://localhost/polyclinic');
define('ADMIN_EMAIL', 'admin@polyclinic.ru');

// Отображение ошибок (отключить на production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Timezone
date_default_timezone_set('Europe/Moscow');

// Глобальная переменная подключения
$pdo = null;

// Попытка подключения тремя способами
function connectToDatabase() {
    global $pdo;
    
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ];
    
    $server = DB_SERVER;
    $database = DB_NAME;
    
    // Способ 1: sqlsrv драйвер
    if (extension_loaded('sqlsrv')) {
        try {
            $dsn = "sqlsrv:Server=$server;Database=$database";
            $pdo = new PDO($dsn, null, null, $options);
            return true;
        } catch (PDOException $e) {
            // Пробуем дальше
        }
    }
    
    // Способ 2: ODBC Driver 17
    try {
        $dsn = "odbc:Driver={ODBC Driver 17 for SQL Server};Server=$server;Database=$database;Trusted_Connection=yes;";
        $pdo = new PDO($dsn, "", "", $options);
        return true;
    } catch (PDOException $e) {
        // Пробуем дальше
    }
    
    // Способ 3: SQL Server Native Client
    try {
        $dsn = "odbc:Driver={SQL Server Native Client 11.0};Server=$server;Database=$database;Trusted_Connection=yes;";
        $pdo = new PDO($dsn, "", "", $options);
        return true;
    } catch (PDOException $e) {
        // Пробуем дальше
    }
    
    // Если ничего не сработало - выводим инструкцию
    die("<h1>Ошибка подключения к базе данных</h1>" .
        "<p>Автоматическое подключение не удалось.</p>" .
        "<p><b>Доступные расширения PHP:</b> " . implode(', ', get_loaded_extensions()) . "</p>" .
        "<hr>" .
        "<h3>Что делать:</h3>" .
        "<p><b>Вариант 1 (Рекомендуемый): Установить драйверы Microsoft для PHP</b></p>" .
        "<ol>" .
        "<li>Скачайте установщик <a href='https://aka.ms/download-php-sqlsrv' target='_blank'>Microsoft Drivers for PHP for SQL Server</a>.</li>" .
        "<li>Запустите установщик, он сам найдет ваш PHP и предложит установить драйверы.</li>" .
        "<li>Выберите версию под ваш PHP (скорее всего 7.2 или 7.4).</li>" .
        "<li>После установки перезапустите OpenServer.</li>" .
        "</ol>" .
        "<p><b>Вариант 2: Проверить ODBC драйверы Windows</b></p>" .
        "<ol>" .
        "<li>Откройте Панель управления -> Администрирование -> Источники данных ODBC (64-бит).</li>" .
        "<li>Проверьте, есть ли в списке драйвер 'ODBC Driver 17 for SQL Server' или 'SQL Server Native Client 11.0'.</li>" .
        "<li>Если нет - скачайте и установите <a href='https://go.microsoft.com/fwlink/?linkid=2182774' target='_blank'>ODBC Driver 17</a>.</li>" .
        "</ol>"
    );
}

// Подключаемся при первом вызове
connectToDatabase();

// Функция для выполнения запроса
function dbQuery($sql, $params = []) {
    global $pdo;
    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    } catch (PDOException $e) {
        die("Ошибка БД: " . $e->getMessage());
    }
}

// Функция для получения всех результатов
function dbFetchAll($stmt) {
    return $stmt->fetchAll();
}

// Функция для получения одной записи
function dbFetchOne($stmt) {
    return $stmt->fetch();
}

// Функция для безопасного вывода
function e($string) {
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}

// Старт сессии если не запущена
if (session_status() === PHP_SESSION_NONE) {
    session_start();
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
