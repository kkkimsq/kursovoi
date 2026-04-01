<?php
require_once '../config.php';
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Услуги - <?= e(SITE_NAME) ?></title>
    <link rel="stylesheet" href="static/css/style.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <section class="section">
        <div class="container">
            <div class="section-title">
                <h2>Наши услуги</h2>
                <p>Полный спектр медицинских услуг для всей семьи</p>
            </div>
            
            <div class="services-grid">
                <?php
                $stmt = dbQuery("SELECT * FROM services WHERE is_active = 1 ORDER BY price DESC");
                while ($service = dbFetchOne($stmt)) {
                    if (!$service) break;
                ?>
                <div class="service-card">
                    <div class="service-icon">🏥</div>
                    <h3><?= e($service['name']) ?></h3>
                    <p><?= e($service['description']) ?></p>
                    <?php if ($service['duration_minutes'] > 0): ?>
                    <p style="color: var(--text-light); margin: 10px 0;">⏱️ Длительность: <?= $service['duration_minutes'] ?> мин.</p>
                    <?php endif; ?>
                    <div class="price"><?= number_format($service['price'], 0, '.', ' ') ?> ₽</div>
                    <a href="appointment.php" class="btn btn-primary">Записаться</a>
                </div>
                <?php } ?>
            </div>
        </div>
    </section>

    <?php include 'includes/footer.php'; ?>
</body>
</html>
