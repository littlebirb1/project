CREATE DATABASE IF NOT EXISTS time_management;
USE time_management;

CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    full_name VARCHAR(100),
    timezone VARCHAR(50) DEFAULT 'UTC',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Calendars table (users can have multiple calendars)
CREATE TABLE calendars (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    color VARCHAR(7) DEFAULT '#3788d8', -- hex color code
    is_default BOOLEAN DEFAULT FALSE,
    is_visible BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_user_id (user_id)
);

-- Events table
CREATE TABLE events (
    id INT PRIMARY KEY AUTO_INCREMENT,
    calendar_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    location VARCHAR(255),
    start_datetime DATETIME NOT NULL,
    end_datetime DATETIME NOT NULL,
    is_all_day BOOLEAN DEFAULT FALSE,
    color VARCHAR(7), -- optional override of calendar color
    status ENUM('confirmed', 'tentative', 'cancelled') DEFAULT 'confirmed',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (calendar_id) REFERENCES calendars(id) ON DELETE CASCADE,
    INDEX idx_calendar_id (calendar_id),
    INDEX idx_datetime_range (start_datetime, end_datetime)
);

-- Recurring events pattern
CREATE TABLE recurring_patterns (
    id INT PRIMARY KEY AUTO_INCREMENT,
    event_id INT NOT NULL,
    recurrence_type ENUM('daily', 'weekly', 'monthly', 'yearly') NOT NULL,
    interval_value INT DEFAULT 1, -- every X days/weeks/months
    days_of_week SET('monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'),
    day_of_month INT, -- for monthly recurrence
    month_of_year INT, -- for yearly recurrence
    recurrence_end_date DATE,
    max_occurrences INT,
    FOREIGN KEY (event_id) REFERENCES events(id) ON DELETE CASCADE,
    INDEX idx_event_id (event_id)
);

-- Event exceptions (for edited/deleted instances of recurring events)
CREATE TABLE recurring_exceptions (
    id INT PRIMARY KEY AUTO_INCREMENT,
    recurring_pattern_id INT NOT NULL,
    exception_date DATE NOT NULL,
    is_deleted BOOLEAN DEFAULT FALSE,
    modified_event_id INT, -- links to modified version if edited
    FOREIGN KEY (recurring_pattern_id) REFERENCES recurring_patterns(id) ON DELETE CASCADE,
    FOREIGN KEY (modified_event_id) REFERENCES events(id) ON DELETE SET NULL,
    UNIQUE KEY unique_exception (recurring_pattern_id, exception_date)
);

-- Event reminders/notifications
CREATE TABLE event_reminders (
    id INT PRIMARY KEY AUTO_INCREMENT,
    event_id INT NOT NULL,
    reminder_type ENUM('notification', 'email', 'sms') DEFAULT 'notification',
    minutes_before INT NOT NULL, -- e.g., 15, 30, 60, 1440 (24 hours)
    is_sent BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (event_id) REFERENCES events(id) ON DELETE CASCADE,
    INDEX idx_event_id (event_id)
);

-- Shared calendars (for collaboration)
CREATE TABLE calendar_shares (
    id INT PRIMARY KEY AUTO_INCREMENT,
    calendar_id INT NOT NULL,
    shared_with_user_id INT NOT NULL,
    permission_level ENUM('view', 'edit', 'admin') DEFAULT 'view',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (calendar_id) REFERENCES calendars(id) ON DELETE CASCADE,
    FOREIGN KEY (shared_with_user_id) REFERENCES users(id) ON DELETE CASCADE,
    UNIQUE KEY unique_share (calendar_id, shared_with_user_id),
    INDEX idx_shared_user (shared_with_user_id)
);

-- Event categories/tags
CREATE TABLE categories (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    name VARCHAR(50) NOT NULL,
    color VARCHAR(7),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    UNIQUE KEY unique_category (user_id, name)
);

CREATE TABLE event_categories (
    event_id INT NOT NULL,
    category_id INT NOT NULL,
    PRIMARY KEY (event_id, category_id),
    FOREIGN KEY (event_id) REFERENCES events(id) ON DELETE CASCADE,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE
);
