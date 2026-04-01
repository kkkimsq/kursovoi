<?php
require_once 'config.php';
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>О клинике - <?= e(SITE_NAME) ?></title>
    <link rel="stylesheet" href="static/css/style.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <section class="section">
        <div class="container">
            <div class="section-title">
                <h2>О нашей клинике</h2>
            </div>
            
            <div style="max-width: 800px; margin: 0 auto; line-height: 1.8;">
                <p style="margin-bottom: 20px;">
                    <strong><?= SITE_NAME ?></strong> — современное медицинское учреждение, 
                    предоставляющее широкий спектр платных медицинских услуг для взрослых и детей.
                </p>
                
                <h3 style="margin: 30px 0 15px; color: var(--primary-color);">Наши преимущества</h3>
                <ul style="margin-bottom: 30px; padding-left: 20px;">
                    <li>Опытные врачи высшей категории</li>
                    <li>Современное диагностическое оборудование</li>
                    <li>Комфортные условия приема</li>
                    <li>Индивидуальный подход к каждому пациенту</li>
                    <li>Удобное расположение и график работы</li>
                    <li>Возможность онлайн-записи на прием</li>
                </ul>
                
                <h3 style="margin: 30px 0 15px; color: var(--primary-color);">Наши специалисты</h3>
                <p style="margin-bottom: 20px;">
                    В нашей поликлинике работают квалифицированные специалисты различных профилей: 
                    терапевты, педиатры, гинекологи, кардиологи, неврологи, эндокринологи, хирурги 
                    и врачи ультразвуковой диагностики. Все врачи регулярно проходят повышение квалификации.
                </p>
                
                <h3 style="margin: 30px 0 15px; color: var(--primary-color);">Услуги</h3>
                <p style="margin-bottom: 20px;">
                    Мы предлагаем широкий спектр медицинских услуг: консультации специалистов, 
                    диагностические исследования (УЗИ, ЭКГ), лабораторные анализы, 
                    профилактические осмотры и оформление медицинской документации.
                </p>
                
                <h3 style="margin: 30px 0 15px; color: var(--primary-color);">Режим работы</h3>
                <p style="margin-bottom: 20px;">
                    🕐 Понедельник — Воскресенье: с 8:00 до 21:00<br>
                    📍 Адрес: г. Санкт-Петербург, ул. Медицинская, д. 1<br>
                    📞 Телефон: +7 (812) 123-45-67<br>
                    ✉️ Email: info@polyclinic.ru
                </p>
            </div>
        </div>
    </section>

    <?php include 'includes/footer.php'; ?>
</body>
</html>
