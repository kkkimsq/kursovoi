<aside class="admin-sidebar">
    <h2>🏥 <?= SITE_NAME ?></h2>
    <ul class="admin-menu">
        <li><a href="index.php" class="<?= basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : '' ?>">📊 Главная</a></li>
        <li><a href="appointments.php" class="<?= basename($_SERVER['PHP_SELF']) == 'appointments.php' ? 'active' : '' ?>">📅 Записи</a></li>
        <li><a href="doctors.php" class="<?= basename($_SERVER['PHP_SELF']) == 'doctors.php' ? 'active' : '' ?>">👨‍⚕️ Врачи</a></li>
        <li><a href="services.php" class="<?= basename($_SERVER['PHP_SELF']) == 'services.php' ? 'active' : '' ?>">💊 Услуги</a></li>
        <li><a href="patients.php" class="<?= basename($_SERVER['PHP_SELF']) == 'patients.php' ? 'active' : '' ?>">👥 Пациенты</a></li>
        <?php if (isSystemAdmin()): ?>
        <li><a href="admins.php" class="<?= basename($_SERVER['PHP_SELF']) == 'admins.php' ? 'active' : '' ?>">🔐 Администраторы</a></li>
        <li><a href="reports.php" class="<?= basename($_SERVER['PHP_SELF']) == 'reports.php' ? 'active' : '' ?>">📈 Отчеты</a></li>
        <?php endif; ?>
        <li><a href="../index.php" target="_blank">🌐 На сайт</a></li>
        <li><a href="logout.php">🚪 Выход</a></li>
    </ul>
</aside>
