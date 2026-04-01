<?php
session_start();
require_once 'config.php';
?>
<header>
    <div class="header-top">
        <div class="container">
            <div>📍 г. Санкт-Петербург, ул. Медицинская, д. 1</div>
            <div class="header-contact">
                <a href="tel:+78121234567">📞 +7 (812) 123-45-67</a>
                <a href="mailto:info@polyclinic.ru">✉️ info@polyclinic.ru</a>
            </div>
        </div>
    </div>
    <nav>
        <div class="container">
            <a href="index.php" class="logo">🏥 <span><?= SITE_NAME ?></span></a>
            <ul class="nav-menu">
                <li><a href="index.php">Главная</a></li>
                <li><a href="services.php">Услуги</a></li>
                <li><a href="doctors.php">Врачи</a></li>
                <li><a href="appointment.php">Запись на прием</a></li>
                <li><a href="about.php">О клинике</a></li>
                <li><a href="contacts.php">Контакты</a></li>
                <?php if (isAdminLoggedIn()): ?>
                    <li><a href="admin/index.php" style="color: var(--primary-color);">Админ-панель</a></li>
                    <li><a href="admin/logout.php">Выход</a></li>
                <?php endif; ?>
            </ul>
            <a href="appointment.php" class="btn btn-primary">Записаться онлайн</a>
        </div>
    </nav>
</header>
