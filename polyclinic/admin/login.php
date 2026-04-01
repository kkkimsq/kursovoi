<?php
session_start();
require_once '../config.php';

// Если уже авторизован, перенаправляем в админку
if (isAdminLoggedIn()) {
    redirect('index.php');
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    
    if (empty($username) || empty($password)) {
        $error = 'Введите логин и пароль';
    } else {
        try {
            $sql = "SELECT * FROM admins WHERE username = ? AND is_active = 1";
            $stmt = dbQuery($sql, [$username]);
            $admin = dbFetchOne($stmt);
            
            if ($admin && password_verify($password, $admin['password_hash'])) {
                $_SESSION['admin_id'] = $admin['id'];
                $_SESSION['admin_username'] = $admin['username'];
                $_SESSION['admin_role'] = $admin['role'];
                $_SESSION['admin_name'] = $admin['first_name'] . ' ' . $admin['last_name'];
                
                // Обновляем время последнего входа
                $sql = "UPDATE admins SET last_login = GETDATE() WHERE id = ?";
                dbQuery($sql, [$admin['id']]);
                
                redirect('index.php');
            } else {
                $error = 'Неверный логин или пароль';
            }
        } catch (Exception $e) {
            $error = 'Ошибка при входе: ' . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Вход в админ-панель - <?= e(SITE_NAME) ?></title>
    <link rel="stylesheet" href="../static/css/style.css">
</head>
<body style="background: var(--bg-light); display: flex; align-items: center; justify-content: center; min-height: 100vh;">
    <div class="form-container" style="max-width: 400px;">
        <h2 style="text-align: center; margin-bottom: 30px; color: var(--primary-color);">Вход для сотрудников</h2>
        
        <?php if ($error): ?>
            <div class="alert alert-error"><?= e($error) ?></div>
        <?php endif; ?>
        
        <form method="POST">
            <div class="form-group">
                <label for="username">Логин</label>
                <input type="text" id="username" name="username" class="form-control" required autofocus>
            </div>
            
            <div class="form-group">
                <label for="password">Пароль</label>
                <input type="password" id="password" name="password" class="form-control" required>
            </div>
            
            <button type="submit" class="btn btn-primary" style="width: 100%;">Войти</button>
        </form>
        
        <p style="text-align: center; margin-top: 20px; color: var(--text-light);">
            <a href="../index.php" style="color: var(--primary-color);">← Вернуться на сайт</a>
        </p>
    </div>
</body>
</html>
