-- База данных для медицинской информационной системы расписания приёмов
-- Скрипт можно выполнить из консоли MySQL:
--   mysql -u root -p < database/schema.sql

DROP DATABASE IF EXISTS mvc;
CREATE DATABASE mvc CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE mvc;

-- ----------------------------------------
-- Таблица пользователей
-- ----------------------------------------
DROP TABLE IF EXISTS users;
CREATE TABLE users (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    login VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(32) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    role ENUM('Администратор', 'Врач', 'Пациент') NOT NULL DEFAULT 'Пациент',
    birth_date DATE NULL,
    policy_number VARCHAR(64) NULL,
    specialization VARCHAR(255) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT NULL,
    INDEX idx_users_role (role)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------------------
-- Таблица записей на приём
-- ----------------------------------------
DROP TABLE IF EXISTS appointments;
CREATE TABLE appointments (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    patient_id INT UNSIGNED NOT NULL,
    doctor_id INT UNSIGNED NOT NULL,
    service_type VARCHAR(255) NOT NULL,
    appointment_date DATE NOT NULL,
    appointment_time TIME NOT NULL,
    status ENUM('Запланировано', 'Подтверждено', 'Завершено', 'Отменено') NOT NULL DEFAULT 'Запланировано',
    complaint TEXT NULL,
    notes TEXT NULL,
    room VARCHAR(32) NULL,
    attachment_path VARCHAR(255) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT NULL,
    CONSTRAINT fk_appointments_patient FOREIGN KEY (patient_id) REFERENCES users(id) ON DELETE CASCADE,
    CONSTRAINT fk_appointments_doctor FOREIGN KEY (doctor_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_appointments_date (appointment_date, appointment_time),
    INDEX idx_appointments_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------------------
-- Таблица новостей/объявлений (опционально для главной страницы)
-- ----------------------------------------
DROP TABLE IF EXISTS posts;
CREATE TABLE posts (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    body TEXT NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------------------
-- Начальные данные
-- ----------------------------------------
INSERT INTO users (name, login, password, phone, email, role, birth_date, policy_number, specialization)
VALUES
    ('Администратор системы', 'admin', MD5('admin123'), '+7 (000) 000-00-00', 'admin@clinic.local', 'Администратор', NULL, NULL, NULL),
    ('Др. Мария Смирнова', 'dr-smirnova', MD5('doctor123'), '+7 (999) 111-22-33', 'smirnova@clinic.local', 'Врач', '1986-03-10', NULL, 'Терапевт'),
    ('Иван Петров', 'patient-petrov', MD5('patient123'), '+7 (999) 555-77-88', 'petrov@clinic.local', 'Пациент', '1994-08-22', '77-45-998877', NULL);

INSERT INTO appointments (patient_id, doctor_id, service_type, appointment_date, appointment_time, status, complaint, notes, room)
VALUES
    (3, 2, 'Первичная консультация', DATE_ADD(CURDATE(), INTERVAL 1 DAY), '10:30:00', 'Запланировано', 'Повышенная температура, слабость', 'Подготовить результаты анализов', '302А'),
    (3, 2, 'Повторный приём', DATE_ADD(CURDATE(), INTERVAL 7 DAY), '09:00:00', 'Подтверждено', 'Контроль после лечения', 'Уточнить дозировку лекарств', '302А');


