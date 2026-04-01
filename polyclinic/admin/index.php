<?php
session_start();
require_once '../config.php';

// Проверка авторизации
if (!isAdminLoggedIn()) {
    redirect('login.php');
}

// Получаем статистику
$stats = [];

// Всего записей на сегодня
$stmt = dbQuery("SELECT COUNT(*) as cnt FROM appointments WHERE appointment_date = CONVERT(DATE, GETDATE())");
$row = dbFetchOne($stmt);
$stats['today_appointments'] = $row['cnt'] ?? 0;

// Всего активных записей
$stmt = dbQuery("SELECT COUNT(*) as cnt FROM appointments WHERE status = 'active'");
$row = dbFetchOne($stmt);
$stats['active_appointments'] = $row['cnt'] ?? 0;

// Всего врачей
$stmt = dbQuery("SELECT COUNT(*) as cnt FROM doctors WHERE is_active = 1");
$row = dbFetchOne($stmt);
$stats['doctors_count'] = $row['cnt'] ?? 0;

// Всего пациентов
$stmt = dbQuery("SELECT COUNT(*) as cnt FROM patients");
$row = dbFetchOne($stmt);
$stats['patients_count'] = $row['cnt'] ?? 0;

// Последние записи
$sql = "
    SELECT TOP 10 a.*, 
           p.last_name + ' ' + p.first_name as patient_name,
           d.last_name + ' ' + d.first_name + ' ' + ISNULL(d.middle_name, '') as doctor_name,
           s.name as service_name
    FROM appointments a
    INNER JOIN patients p ON a.patient_id = p.id
    INNER JOIN doctors d ON a.doctor_id = d.id
    LEFT JOIN services s ON a.service_id = s.id
    ORDER BY a.created_at DESC
";
$stmt = dbQuery($sql);
$recentAppointments = dbFetchAll($stmt);
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Админ-панель - <?= e(SITE_NAME) ?></title>
    <link rel="stylesheet" href="../static/css/style.css">
</head>
<body>
    <div class="admin-panel">
        <?php include 'includes/sidebar.php'; ?>
        
        <div class="admin-content">
            <div class="admin-header">
                <h1>Панель управления</h1>
                <div>
                    <span style="margin-right: 20px;">👤 <?= e($_SESSION['admin_name']) ?></span>
                    <a href="logout.php" class="btn btn-outline" style="padding: 8px 15px;">Выход</a>
                </div>
            </div>
            
            <!-- Статистика -->
            <div class="stats-grid">
                <div class="stat-card">
                    <h3>Записей на сегодня</h3>
                    <div class="value"><?= $stats['today_appointments'] ?></div>
                </div>
                <div class="stat-card">
                    <h3>Активных записей</h3>
                    <div class="value"><?= $stats['active_appointments'] ?></div>
                </div>
                <div class="stat-card">
                    <h3>Врачей</h3>
                    <div class="value"><?= $stats['doctors_count'] ?></div>
                </div>
                <div class="stat-card">
                    <h3>Пациентов</h3>
                    <div class="value"><?= $stats['patients_count'] ?></div>
                </div>
            </div>
            
            <!-- Последние записи -->
            <div class="section-title" style="text-align: left; margin-top: 40px;">
                <h2>Последние записи</h2>
            </div>
            
            <div class="table-responsive">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Пациент</th>
                            <th>Врач</th>
                            <th>Дата и время</th>
                            <th>Статус</th>
                            <th>Действия</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recentAppointments as $apt): ?>
                        <tr>
                            <td><?= $apt['id'] ?></td>
                            <td><?= e($apt['patient_name']) ?></td>
                            <td><?= e($apt['doctor_name']) ?></td>
                            <td><?= date('d.m.Y H:i', strtotime($apt['appointment_date'] . ' ' . $apt['appointment_time'])) ?></td>
                            <td>
                                <?php
                                $statusLabels = [
                                    'active' => ['Активная', 'success'],
                                    'completed' => ['Завершена', 'secondary'],
                                    'cancelled_by_patient' => ['Отменена пациентом', 'danger'],
                                    'cancelled_by_admin' => ['Отменена админом', 'danger'],
                                    'no_show' => ['Не явился', 'warning']
                                ];
                                $status = $statusLabels[$apt['status']] ?? ['Неизвестно', 'secondary'];
                                ?>
                                <span style="color: var(--<?= $status[1] ?>-color); font-weight: 600;"><?= $status[0] ?></span>
                            </td>
                            <td>
                                <a href="appointments.php?action=view&id=<?= $apt['id'] ?>" class="action-btn btn-view">Просмотр</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            
            <div style="margin-top: 30px;">
                <a href="appointments.php" class="btn btn-primary">Все записи</a>
            </div>
        </div>
    </div>
</body>
</html>
