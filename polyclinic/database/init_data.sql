-- Скрипт для проверки и создания начальных данных в базе данных MedicalClinicDB
-- Для SQL Server

-- Проверка существования таблиц и создание начальных данных

-- 1. Администраторы (по умолчанию логин: admin, пароль: admin123)
IF NOT EXISTS (SELECT 1 FROM admins WHERE username = 'admin')
BEGIN
    INSERT INTO admins (username, password_hash, first_name, last_name, role, is_active, created_at)
    VALUES ('admin', HASHBYTES('SHA2_256', 'admin123'), 'Админ', 'Админов', 'admin', 1, GETDATE());
    PRINT 'Создан администратор: admin / admin123';
END

-- 2. Специальности врачей
IF NOT EXISTS (SELECT 1 FROM specialties)
BEGIN
    INSERT INTO specialties (name, description) VALUES
    ('Терапевт', 'Врач общей практики'),
    ('Хирург', 'Хирургические операции'),
    ('Кардиолог', 'Заболевания сердечно-сосудистой системы'),
    ('Невролог', 'Заболевания нервной системы'),
    ('Офтальмолог', 'Заболевания глаз'),
    ('Отоларинголог', 'Заболевания ЛОР-органов'),
    ('Дерматолог', 'Заболевания кожи'),
    ('Гинеколог', 'Женское здоровье');
    PRINT 'Созданы специальности';
END

-- 3. Врачи
IF NOT EXISTS (SELECT 1 FROM doctors)
BEGIN
    DECLARE @therapist_id INT = (SELECT id FROM specialties WHERE name = 'Терапевт');
    DECLARE @surgeon_id INT = (SELECT id FROM specialties WHERE name = 'Хирург');
    DECLARE @cardiologist_id INT = (SELECT id FROM specialties WHERE name = 'Кардиолог');
    DECLARE @neurologist_id INT = (SELECT id FROM specialties WHERE name = 'Невролог');
    
    INSERT INTO doctors (last_name, first_name, middle_name, specialty_id, experience_years, bio, is_active) VALUES
    ('Иванов', 'Иван', 'Иванович', @therapist_id, 10, 'Врач высшей категории', 1),
    ('Петров', 'Петр', 'Петрович', @surgeon_id, 15, 'Хирург с большим опытом', 1),
    ('Сидоров', 'Сидор', 'Сидорович', @cardiologist_id, 12, 'Специалист по сердечным заболеваниям', 1),
    ('Кузнецов', 'Кузьма', 'Кузьмич', @neurologist_id, 8, 'Невролог', 1),
    ('Попов', 'Павел', 'Павлович', @therapist_id, 6, 'Терапевт', 1),
    ('Смирнов', 'Сергей', 'Сергеевич', @cardiologist_id, 10, 'Кардиолог', 1),
    ('Васильев', 'Василий', 'Васильевич', @surgeon_id, 14, 'Хирург', 1),
    ('Михайлов', 'Михаил', 'Михайлович', @neurologist_id, 9, 'Невролог', 1);
    PRINT 'Созданы врачи';
END

-- 4. Услуги
IF NOT EXISTS (SELECT 1 FROM services)
BEGIN
    INSERT INTO services (name, description, price, duration_minutes, is_active) VALUES
    ('Консультация терапевта', 'Первичный прием врача-терапевта', 1500, 30, 1),
    ('Консультация хирурга', 'Первичный прием хирурга', 2000, 30, 1),
    ('Консультация кардиолога', 'Первичный прием кардиолога', 2500, 40, 1),
    ('Консультация невролога', 'Первичный прием невролога', 2000, 30, 1),
    ('ЭКГ', 'Электрокардиограмма', 800, 15, 1),
    ('УЗИ органов брюшной полости', 'Ультразвуковое исследование', 2500, 30, 1),
    ('Общий анализ крови', 'Лабораторное исследование', 500, 10, 1),
    ('Биохимический анализ крови', 'Расширенное лабораторное исследование', 1200, 10, 1),
    ('Рентгенография', 'Рентгеновское исследование', 1000, 20, 1),
    ('Массаж лечебный', 'Курс лечебного массажа (1 сеанс)', 1500, 45, 1),
    ('Физиотерапия', 'Физиотерапевтические процедуры', 1000, 30, 1),
    ('Вакцинация', 'Прививки по календарю', 800, 15, 1),
    ('Оформление медицинской книжки', 'Оформление документов', 500, 10, 1),
    ('Справка 086/у', 'Медицинская справка для поступления', 1000, 20, 1);
    PRINT 'Созданы услуги';
END

-- 5. Расписание врачей (на ближайшую неделю)
IF NOT EXISTS (SELECT 1 FROM doctor_schedule WHERE work_date >= CAST(GETDATE() AS DATE))
BEGIN
    DECLARE @current_date DATE = CAST(GETDATE() AS DATE);
    DECLARE @doctor_ids TABLE (id INT);
    INSERT INTO @doctor_ids SELECT id FROM doctors WHERE is_active = 1;
    
    DECLARE @i INT = 0;
    WHILE @i < 7
    BEGIN
        DECLARE @work_date DATE = DATEADD(DAY, @i, @current_date);
        -- Не добавляем расписание на воскресенье (день недели 7)
        IF DATEPART(WEEKDAY, @work_date) <> 7
        BEGIN
            INSERT INTO doctor_schedule (doctor_id, work_date, start_time, end_time, is_available)
            SELECT d.id, @work_date, '09:00', '13:00', 1 FROM @doctor_ids d;
            
            INSERT INTO doctor_schedule (doctor_id, work_date, start_time, end_time, is_available)
            SELECT d.id, @work_date, '14:00', '18:00', 1 FROM @doctor_ids d;
        END
        SET @i = @i + 1;
    END
    PRINT 'Создано расписание врачей';
END

PRINT 'Все данные успешно созданы!';
