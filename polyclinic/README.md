# Сайт платной поликлиники

Курсовой проект по дисциплине МДК 02.01 "Технология разработки программного обеспечения"

## Описание

Полнофункциональный веб-сайт для платной поликлиники с возможностью онлайн-записи на прием.

## Функционал

### Для пациентов:
- Просмотр списка врачей и их специализаций
- Просмотр расписания врачей со свободными окнами
- Онлайн-запись на прием
- Получение email-подтверждения записи
- Отмена/перенос записи через личный кабинет
- Просмотр информации об услугах и ценах

### Для администратора:
- Управление информацией о врачах (CRUD)
- Управление медицинскими услугами и ценами
- Управление расписанием врачей
- Просмотр и управление записями пациентов

### Для системного администратора:
- Управление учетными записями администраторов
- Формирование отчетов и статистики
- Визуализация данных в виде диаграмм

## Технологии

- **Backend:** PHP 7.4+
- **Database:** SQL Server (MedicalClinicDB)
- **Frontend:** HTML5, CSS3, JavaScript (ES6+)
- **Architecture:** MVC-подобная структура
- **Расширение PHP:** sqlsrv (Microsoft Drivers for PHP for SQL Server)

## Установка

1. Скопируйте файлы проекта в директорию веб-сервера

2. Убедитесь, что база данных MedicalClinicDB существует на сервере WIN-E35DNA7AABF\SQLEXPRESS

3. Импортируйте схему базы данных и начальные данные:
```sql
-- Выполните скрипт database/init_data.sql в SQL Server Management Studio
-- или через sqlcmd:
sqlcmd -S WIN-E35DNA7AABF\SQLEXPRESS -d MedicalClinicDB -i database/init_data.sql
```

4. Настройка подключения к базе данных уже выполнена в файле `config.php`:
```php
define('DB_SERVER', 'WIN-E35DNA7AABF\SQLEXPRESS');
define('DB_NAME', 'MedicalClinicDB');
```

5. Установите расширение Microsoft Drivers for PHP for SQL Server:
   - Скачайте с https://docs.microsoft.com/en-us/sql/connect/php/download-drivers-php-sql-server
   - Установите согласно инструкции для вашей версии PHP

6. Убедитесь, что в php.ini подключено расширение:
```ini
extension=php_sqlsrv.dll
extension=php_pdo_sqlsrv.dll
```

## Доступы по умолчанию

### Администратор:
- Логин: `admin`
- Пароль: `admin123`

## Структура проекта

```
polyclinic/
├── admin/                  # Админ-панель
│   ├── includes/
│   │   └── sidebar.php
│   ├── index.php          # Главная админки
│   ├── login.php          # Вход
│   └── logout.php         # Выход
├── api/                    # API эндпоинты
│   ├── create_appointment.php
│   └── get_data.php
├── database/
│   ├── schema.sql         # Схема БД (MySQL, для справки)
│   └── init_data.sql      # Скрипт инициализации данных (SQL Server)
├── includes/
│   ├── footer.php
│   └── header.php
├── static/
│   ├── css/
│   │   └── style.css
│   ├── js/
│   │   └── appointment.js
│   └── images/
├── about.php              # О клинике
├── appointment.php        # Запись на прием
├── config.php             # Конфигурация
├── contacts.php           # Контакты
├── doctors.php            # Врачи
├── index.php              # Главная страница
└── services.php           # Услуги
```

## Структура базы данных

### Таблицы:
- `specialties` - Специальности врачей
- `doctors` - Врачи
- `services` - Медицинские услуги
- `doctor_schedule` - Расписание врачей
- `patients` - Пациенты
- `appointments` - Записи на прием
- `admins` - Администраторы
- `promotions` - Акции и скидки

## Требования

- PHP 7.4 или выше
- SQL Server 2016 или выше / SQL Server Express
- Веб-сервер (Apache/Nginx/IIS)
- Расширение Microsoft Drivers for PHP for SQL Server (sqlsrv)

## Безопасность

- Хеширование паролей (bcrypt/password_hash)
- Защита от SQL-инъекций (параметризированные запросы)
- Защита от XSS (htmlspecialchars)
- CSRF-токены для форм
- Разделение ролей пользователей

## Примечания

- Проект использует Windows Authentication (Trusted_Connection=True)
- Для работы транзакций используются функции sqlsrv_begin_transaction, sqlsrv_commit, sqlsrv_rollback
- Даты в SQL Server обрабатываются функциями GETDATE(), CONVERT(DATE, GETDATE())
- Для ограничения количества записей используется TOP N вместо LIMIT N

## Автор

Смолина Ольга Олеговна
Группа 22290907/1095
СПбПУ Петра Великого

## Год

2026
