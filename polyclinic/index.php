<?php
session_start();
require_once 'config.php';
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e(SITE_NAME) ?> - Главная</title>
    <link rel="stylesheet" href="static/css/style.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <h1>Забота о вашем здоровье - наш приоритет</h1>
            <p>Современная медицина, опытные врачи и комфортные условия</p>
            <div class="hero-buttons">
                <a href="appointment.php" class="btn btn-secondary">Записаться на прием</a>
                <a href="#services" class="btn btn-outline" style="border-color: #fff; color: #fff;">Наши услуги</a>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section id="services" class="section section-bg">
        <div class="container">
            <div class="section-title">
                <h2>Наши услуги</h2>
                <p>Широкий спектр медицинских услуг для всей семьи</p>
            </div>
            <div class="services-grid">
                <?php
                $stmt = dbQuery("SELECT TOP 6 * FROM services WHERE is_active = 1 ORDER BY id");
                while ($service = dbFetchOne($stmt)) {
                    if (!$service) break;
                ?>
                <div class="service-card">
                    <div class="service-icon">🏥</div>
                    <h3><?= e($service['name']) ?></h3>
                    <p><?= e($service['description']) ?></p>
                    <div class="price"><?= number_format($service['price'], 0, '.', ' ') ?> ₽</div>
                    <a href="appointment.php" class="btn btn-primary">Записаться</a>
                </div>
                <?php } ?>
            </div>
            <div style="text-align: center; margin-top: 40px;">
                <a href="services.php" class="btn btn-outline">Все услуги</a>
            </div>
        </div>
    </section>

    <!-- Doctors Section -->
    <section class="section">
        <div class="container">
            <div class="section-title">
                <h2>Наши специалисты</h2>
                <p>Опытные врачи высшей категории</p>
            </div>
            <div class="doctors-grid">
                <?php
                $sql = "
                    SELECT TOP 6 d.*, s.name as specialty_name 
                    FROM doctors d 
                    INNER JOIN specialties s ON d.specialty_id = s.id 
                    WHERE d.is_active = 1 
                    ORDER BY d.last_name
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
                        <a href="appointment.php?doctor=<?= $doctor['id'] ?>" class="btn btn-primary">Записаться</a>
                    </div>
                </div>
                <?php } ?>
            </div>
            <div style="text-align: center; margin-top: 40px;">
                <a href="doctors.php" class="btn btn-outline">Все врачи</a>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section class="section section-bg">
        <div class="container">
            <div class="section-title">
                <h2>Почему выбирают нас</h2>
            </div>
            <div class="services-grid">
                <div class="service-card">
                    <div class="service-icon">⭐</div>
                    <h3>Опытные врачи</h3>
                    <p>Специалисты высшей категории с многолетним стажем</p>
                </div>
                <div class="service-card">
                    <div class="service-icon">🕐</div>
                    <h3>Удобное время</h3>
                    <p>Работаем ежедневно с 8:00 до 21:00 без выходных</p>
                </div>
                <div class="service-card">
                    <div class="service-icon">💻</div>
                    <h3>Онлайн-запись</h3>
                    <p>Запишитесь на прием в любое время через сайт</p>
                </div>
            </div>
        </div>
    </section>

    <?php include 'includes/footer.php'; ?>
</body>
</html>
