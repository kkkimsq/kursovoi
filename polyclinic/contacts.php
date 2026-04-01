<?php
require_once 'config.php';
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Контакты - <?= e(SITE_NAME) ?></title>
    <link rel="stylesheet" href="static/css/style.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <section class="section">
        <div class="container">
            <div class="section-title">
                <h2>Контакты</h2>
                <p>Свяжитесь с нами любым удобным способом</p>
            </div>
            
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 40px; margin-top: 40px;">
                <div>
                    <h3 style="margin-bottom: 20px; color: var(--primary-color);">📍 Адрес</h3>
                    <p style="font-size: 18px; margin-bottom: 30px;">г. Санкт-Петербург, ул. Медицинская, д. 1</p>
                    
                    <h3 style="margin-bottom: 20px; color: var(--primary-color);">📞 Телефоны</h3>
                    <p style="font-size: 18px; margin-bottom: 10px;"><a href="tel:+78121234567" style="color: var(--text-color);">+7 (812) 123-45-67</a></p>
                    <p style="font-size: 18px; margin-bottom: 30px;"><a href="tel:+78121234568" style="color: var(--text-color);">+7 (812) 123-45-68</a></p>
                    
                    <h3 style="margin-bottom: 20px; color: var(--primary-color);">✉️ Email</h3>
                    <p style="font-size: 18px; margin-bottom: 30px;"><a href="mailto:info@polyclinic.ru" style="color: var(--text-color);">info@polyclinic.ru</a></p>
                    
                    <h3 style="margin-bottom: 20px; color: var(--primary-color);">🕐 Режим работы</h3>
                    <p style="font-size: 18px; margin-bottom: 10px;">Понедельник — Воскресенье</p>
                    <p style="font-size: 24px; font-weight: bold; color: var(--primary-color); margin-bottom: 30px;">с 8:00 до 21:00</p>
                </div>
                
                <div>
                    <h3 style="margin-bottom: 20px; color: var(--primary-color);">Как добраться</h3>
                    <div style="background: var(--bg-light); padding: 30px; border-radius: 10px; height: 400px; display: flex; align-items: center; justify-content: center;">
                        <div style="text-align: center; color: var(--text-light);">
                            <p style="font-size: 48px; margin-bottom: 20px;">🗺️</p>
                            <p>Здесь будет карта</p>
                            <p style="font-size: 14px;">(Яндекс.Карты или Google Maps)</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div style="margin-top: 50px; background: var(--bg-light); padding: 40px; border-radius: 10px;">
                <h3 style="margin-bottom: 20px; color: var(--primary-color); text-align: center;">Обратная связь</h3>
                <form style="max-width: 500px; margin: 0 auto;" onsubmit="event.preventDefault(); alert('Сообщение отправлено!'); this.reset();">
                    <div class="form-group">
                        <label for="name">Ваше имя</label>
                        <input type="text" id="name" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="contact-email">Email</label>
                        <input type="email" id="contact-email" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="message">Сообщение</label>
                        <textarea id="message" rows="4" class="form-control" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary" style="width: 100%;">Отправить</button>
                </form>
            </div>
        </div>
    </section>

    <?php include 'includes/footer.php'; ?>
</body>
</html>
