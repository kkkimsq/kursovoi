<?php
require_once 'config.php';
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Врачи - <?= e(SITE_NAME) ?></title>
    <link rel="stylesheet" href="static/css/style.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <section class="section">
        <div class="container">
            <div class="section-title">
                <h2>Наши специалисты</h2>
                <p>Опытные врачи высшей категории готовы помочь вам</p>
            </div>
            
            <div class="doctors-grid">
                <?php
                $sql = "
                    SELECT d.*, s.name as specialty_name 
                    FROM doctors d 
                    INNER JOIN specialties s ON d.specialty_id = s.id 
                    WHERE d.is_active = 1 
                    ORDER BY s.name, d.last_name
                ";
                $stmt = dbQuery($sql);
                while ($doctor = dbFetchOne($stmt)) {
                    if (!$doctor) break;
                ?>
                <div class="doctor-card">
                    <div class="doctor-photo">👨‍⚕️</div>
                    <div class="doctor-info">
                        <h3><?= e($doctor['last_name'] . ' ' . $doctor['first_name'] . ' ' . $doctor['middle_name']) ?></h3>
                        <p class="doctor-specialty"><?= e($doctor['specialty_name']) ?></p>
                        <p class="doctor-experience">Стаж: <?= $doctor['experience_years'] ?> лет</p>
                        <?php if ($doctor['bio']): ?>
                        <p style="margin-top: 10px; font-size: 14px; color: var(--text-light);"><?= e($doctor['bio']) ?></p>
                        <?php endif; ?>
                        <a href="appointment.php?doctor=<?= $doctor['id'] ?>" class="btn btn-primary">Записаться</a>
                    </div>
                </div>
                <?php } ?>
            </div>
        </div>
    </section>

    <?php include 'includes/footer.php'; ?>
</body>
</html>
