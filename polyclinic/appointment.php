<?php
require_once 'config.php';
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Запись на прием - <?= e(SITE_NAME) ?></title>
    <link rel="stylesheet" href="static/css/style.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <section class="section appointment-section">
        <div class="container">
            <div class="section-title">
                <h2>Запись на прием</h2>
                <p>Заполните форму для онлайн-записи к врачу</p>
            </div>

            <div class="form-container">
                <div id="alert-container"></div>

                <form id="appointment-form">
                    <div class="form-group">
                        <label for="specialty">Специальность врача *</label>
                        <select id="specialty" class="form-control" required>
                            <option value="">Выберите специальность</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="doctor">Врач *</label>
                        <select id="doctor" class="form-control" required disabled>
                            <option value="">Сначала выберите специальность</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="service">Услуга</label>
                        <select id="service" class="form-control">
                            <option value="">Не выбрано</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="date">Дата приема *</label>
                        <input type="date" id="date" class="form-control" min="<?= date('Y-m-d') ?>" required>
                    </div>

                    <div class="form-group">
                        <label>Время приема *</label>
                        <div id="time-slots" class="time-slots">
                            <p style="color: var(--text-light);">Выберите дату и врача</p>
                        </div>
                        <input type="hidden" id="time" name="time" required>
                    </div>

                    <hr style="margin: 30px 0; border: none; border-top: 1px solid var(--border-color);">

                    <div class="form-group">
                        <label for="last_name">Фамилия *</label>
                        <input type="text" id="last_name" name="last_name" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="first_name">Имя *</label>
                        <input type="text" id="first_name" name="first_name" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="middle_name">Отчество</label>
                        <input type="text" id="middle_name" name="middle_name" class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="email">Email *</label>
                        <input type="email" id="email" name="email" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="phone">Телефон *</label>
                        <input type="tel" id="phone" name="phone" class="form-control" required placeholder="+7 (___) ___-__-__">
                    </div>

                    <div class="form-group">
                        <label for="notes">Комментарий</label>
                        <textarea id="notes" name="notes" class="form-control" rows="3" placeholder="Дополнительная информация"></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary" style="width: 100%;">Записаться на прием</button>
                </form>
            </div>
        </div>
    </section>

    <?php include 'includes/footer.php'; ?>

    <script src="static/js/appointment.js"></script>
</body>
</html>
