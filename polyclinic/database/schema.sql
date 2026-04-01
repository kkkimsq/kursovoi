-- База данных для сайта платной поликлиники

CREATE DATABASE IF NOT EXISTS polyclinic_db;
USE polyclinic_db;

-- Таблица специальностей врачей
CREATE TABLE specialties (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL UNIQUE,
    description TEXT
);

-- Таблица врачей
CREATE TABLE doctors (
    id INT PRIMARY KEY AUTO_INCREMENT,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    middle_name VARCHAR(50),
    specialty_id INT NOT NULL,
    experience_years INT DEFAULT 0,
    phone VARCHAR(20),
    email VARCHAR(100),
    photo_path VARCHAR(255),
    bio TEXT,
    is_active BOOLEAN DEFAULT TRUE,
    FOREIGN KEY (specialty_id) REFERENCES specialties(id) ON DELETE CASCADE
);

-- Таблица медицинских услуг
CREATE TABLE services (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(200) NOT NULL,
    description TEXT,
    price DECIMAL(10, 2) NOT NULL,
    duration_minutes INT DEFAULT 30,
    is_active BOOLEAN DEFAULT TRUE
);

-- Таблица расписания врачей
CREATE TABLE doctor_schedule (
    id INT PRIMARY KEY AUTO_INCREMENT,
    doctor_id INT NOT NULL,
    work_date DATE NOT NULL,
    start_time TIME NOT NULL,
    end_time TIME NOT NULL,
    is_available BOOLEAN DEFAULT TRUE,
    FOREIGN KEY (doctor_id) REFERENCES doctors(id) ON DELETE CASCADE,
    UNIQUE KEY unique_schedule (doctor_id, work_date, start_time, end_time)
);

-- Таблица пациентов
CREATE TABLE patients (
    id INT PRIMARY KEY AUTO_INCREMENT,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    middle_name VARCHAR(50),
    email VARCHAR(100) NOT NULL UNIQUE,
    phone VARCHAR(20) NOT NULL,
    date_of_birth DATE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    is_active BOOLEAN DEFAULT TRUE
);

-- Таблица записей на прием
CREATE TABLE appointments (
    id INT PRIMARY KEY AUTO_INCREMENT,
    patient_id INT NOT NULL,
    doctor_id INT NOT NULL,
    service_id INT,
    appointment_date DATE NOT NULL,
    appointment_time TIME NOT NULL,
    status ENUM('active', 'completed', 'cancelled_by_patient', 'cancelled_by_admin', 'no_show') DEFAULT 'active',
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (patient_id) REFERENCES patients(id) ON DELETE CASCADE,
    FOREIGN KEY (doctor_id) REFERENCES doctors(id) ON DELETE CASCADE,
    FOREIGN KEY (service_id) REFERENCES services(id) ON DELETE SET NULL
);

-- Таблица администраторов
CREATE TABLE admins (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    role ENUM('admin', 'system_admin') DEFAULT 'admin',
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    last_login TIMESTAMP NULL
);

-- Таблица акций и скидок
CREATE TABLE promotions (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(200) NOT NULL,
    description TEXT,
    discount_percent DECIMAL(5, 2) DEFAULT 0,
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    is_active BOOLEAN DEFAULT TRUE
);

-- Индексы для оптимизации поиска
CREATE INDEX idx_doctors_specialty ON doctors(specialty_id);
CREATE INDEX idx_appointments_patient ON appointments(patient_id);
CREATE INDEX idx_appointments_doctor ON appointments(doctor_id);
CREATE INDEX idx_appointments_date ON appointments(appointment_date);
CREATE INDEX idx_appointments_status ON appointments(status);
CREATE INDEX idx_doctor_schedule_date ON doctor_schedule(work_date);

-- Начальные данные: специальности
INSERT INTO specialties (name, description) VALUES
('Терапевт', 'Врач общей практики, первичная диагностика'),
('Педиатр', 'Детский врач для пациентов до 18 лет'),
('Гинеколог', 'Женское здоровье и репродуктивная медицина'),
('Кардиолог', 'Заболевания сердечно-сосудистой системы'),
('Невролог', 'Заболевания нервной системы'),
('Эндокринолог', 'Заболевания эндокринной системы'),
('Хирург', 'Хирургическое лечение заболеваний'),
('УЗИ специалист', 'Ультразвуковая диагностика');

-- Начальные данные: врачи
INSERT INTO doctors (first_name, last_name, middle_name, specialty_id, experience_years, phone, email, bio) VALUES
('Иван', 'Петров', 'Сергеевич', 1, 15, '+7 (495) 123-45-67', 'petrov@polyclinic.ru', 'Врач высшей категории, специализация на заболеваниях органов дыхания'),
('Мария', 'Сидорова', 'Александровна', 2, 10, '+7 (495) 123-45-68', 'sidorova@polyclinic.ru', 'Опытный педиатр, работа с детьми всех возрастов'),
('Елена', 'Козлова', 'Дмитриевна', 3, 12, '+7 (495) 123-45-69', 'kozlova@polyclinic.ru', 'Специалист по планированию беременности и ведению'),
('Александр', 'Новиков', 'Викторович', 4, 20, '+7 (495) 123-45-70', 'novikov@polyclinic.ru', 'Профессор, эксперт в области кардиохирургии'),
('Ольга', 'Морозова', 'Игоревна', 5, 8, '+7 (495) 123-45-71', 'morozova@polyclinic.ru', 'Лечение головных болей, остеохондроза, невралгий'),
('Дмитрий', 'Волков', 'Андреевич', 6, 14, '+7 (495) 123-45-72', 'volkov@polyclinic.ru', 'Диагностика и лечение диабета, заболеваний щитовидной железы'),
('Сергей', 'Лебедев', 'Николаевич', 7, 18, '+7 (495) 123-45-73', 'lebedev@polyclinic.ru', 'Общая хирургия, малоинвазивные операции'),
('Анна', 'Павлова', 'Евгеньевна', 8, 11, '+7 (495) 123-45-74', 'pavlova@polyclinic.ru', 'УЗИ всех органов и систем, беременность');

-- Начальные данные: услуги
INSERT INTO services (name, description, price, duration_minutes) VALUES
('Первичный прием терапевта', 'Консультация и первичная диагностика', 2500.00, 30),
('Повторный прием терапевта', 'Контрольная консультация', 1500.00, 20),
('Первичный прием педиатра', 'Консультация детского врача', 2500.00, 30),
('Прием гинеколога', 'Консультация и осмотр', 3000.00, 40),
('Прием кардиолога', 'Консультация специалиста', 3500.00, 40),
('ЭКГ', 'Электрокардиограмма', 1200.00, 15),
('УЗИ органов брюшной полости', 'Комплексное УЗИ', 2500.00, 30),
('УЗИ сердца', 'Эхокардиография', 3000.00, 30),
('Забор анализов', 'Забор крови из вены', 500.00, 10),
('Общий анализ крови', 'Лабораторное исследование', 800.00, 0),
('Биохимический анализ крови', 'Расширенное исследование', 2000.00, 0),
('Прием невролога', 'Консультация специалиста', 3000.00, 40),
('Прием эндокринолога', 'Консультация специалиста', 3000.00, 40),
('Хирургическая консультация', 'Осмотр хирурга', 2500.00, 30);

-- Начальные данные: администратор (пароль: admin123)
INSERT INTO admins (username, password_hash, email, first_name, last_name, role) VALUES
('admin', '$2b$12$LQv3c1yqBWVHxkd0LHAkCOYz6TtxMQJqhN8/LewY5GyYzS3MebAJu', 'admin@polyclinic.ru', 'Системный', 'Администратор', 'system_admin'),
('manager', '$2b$12$LQv3c1yqBWVHxkd0LHAkCOYz6TtxMQJqhN8/LewY5GyYzS3MebAJu', 'manager@polyclinic.ru', 'Админ', 'Менеджер', 'admin');
