CREATE DATABASE IF NOT EXISTS ad_astra_observatory
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE ad_astra_observatory;

CREATE TABLE IF NOT EXISTS users (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    name VARCHAR(120) NOT NULL,
    role ENUM('owner', 'admin') NOT NULL DEFAULT 'owner',
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS stations (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED NOT NULL UNIQUE,
    name VARCHAR(150) NOT NULL,
    latitude DECIMAL(10, 7) NOT NULL,
    longitude DECIMAL(10, 7) NOT NULL,
    altitude_m INT UNSIGNED DEFAULT 0,
    equipment TEXT NULL,
    description TEXT NULL,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_stations_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS celestial_objects (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    designation VARCHAR(80) NOT NULL UNIQUE,
    name VARCHAR(120) NULL,
    type ENUM('asteroid', 'star', 'other') NOT NULL DEFAULT 'asteroid',
    magnitude DECIMAL(5, 2) NULL,
    diameter_km DECIMAL(10, 3) NULL,
    notes TEXT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS occultation_events (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    object_id INT UNSIGNED NOT NULL,
    star_designation VARCHAR(80) NOT NULL,
    star_magnitude DECIMAL(5, 2) NULL,
    predicted_time_utc DATETIME NOT NULL,
    duration_ms INT UNSIGNED NOT NULL DEFAULT 5000,
    path_uncertainty_km DECIMAL(8, 2) NULL,
    magnitude_drop DECIMAL(4, 2) NULL,
    shadow_path_json TEXT NULL,
    status ENUM('upcoming', 'completed', 'cancelled') NOT NULL DEFAULT 'upcoming',
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_events_object FOREIGN KEY (object_id) REFERENCES celestial_objects(id) ON DELETE CASCADE,
    INDEX idx_events_time (predicted_time_utc)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS observation_plans (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    station_id INT UNSIGNED NOT NULL,
    event_id INT UNSIGNED NOT NULL,
    night_date DATE NOT NULL,
    planned_start_utc DATETIME NOT NULL,
    planned_end_utc DATETIME NOT NULL,
    status ENUM('planned', 'confirmed', 'completed', 'cancelled') NOT NULL DEFAULT 'planned',
    notes TEXT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_plans_station FOREIGN KEY (station_id) REFERENCES stations(id) ON DELETE CASCADE,
    CONSTRAINT fk_plans_event FOREIGN KEY (event_id) REFERENCES occultation_events(id) ON DELETE CASCADE,
    INDEX idx_plans_night (night_date),
    INDEX idx_plans_station_night (station_id, night_date)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS observations (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    station_id INT UNSIGNED NOT NULL,
    object_id INT UNSIGNED NOT NULL,
    plan_id INT UNSIGNED NULL,
    observed_at_utc DATETIME NOT NULL,
    result ENUM('positive', 'negative', 'clouded', 'no_data') NOT NULL DEFAULT 'no_data',
    duration_ms INT UNSIGNED NULL,
    notes TEXT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_obs_station FOREIGN KEY (station_id) REFERENCES stations(id) ON DELETE CASCADE,
    CONSTRAINT fk_obs_object FOREIGN KEY (object_id) REFERENCES celestial_objects(id) ON DELETE CASCADE,
    CONSTRAINT fk_obs_plan FOREIGN KEY (plan_id) REFERENCES observation_plans(id) ON DELETE SET NULL,
    INDEX idx_obs_object (object_id),
    INDEX idx_obs_station (station_id)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS photos (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    observation_id INT UNSIGNED NULL,
    sprint_id INT UNSIGNED NULL,
    station_id INT UNSIGNED NULL,
    file_path VARCHAR(255) NOT NULL,
    caption VARCHAR(255) NULL,
    uploaded_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_photos_observation FOREIGN KEY (observation_id) REFERENCES observations(id) ON DELETE CASCADE,
    INDEX idx_photos_sprint (sprint_id),
    INDEX idx_photos_station (station_id)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS sprints (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    creator_id INT UNSIGNED NOT NULL,
    target_object_id INT UNSIGNED NOT NULL,
    title VARCHAR(200) NOT NULL,
    description TEXT NULL,
    start_utc DATETIME NOT NULL,
    end_utc DATETIME NOT NULL,
    status ENUM('upcoming', 'active', 'completed') NOT NULL DEFAULT 'upcoming',
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_sprints_creator FOREIGN KEY (creator_id) REFERENCES users(id) ON DELETE CASCADE,
    CONSTRAINT fk_sprints_object FOREIGN KEY (target_object_id) REFERENCES celestial_objects(id) ON DELETE CASCADE,
    INDEX idx_sprints_status (status),
    INDEX idx_sprints_dates (start_utc, end_utc)
) ENGINE=InnoDB;

ALTER TABLE photos
    ADD CONSTRAINT fk_photos_sprint FOREIGN KEY (sprint_id) REFERENCES sprints(id) ON DELETE CASCADE,
    ADD CONSTRAINT fk_photos_station FOREIGN KEY (station_id) REFERENCES stations(id) ON DELETE SET NULL;

CREATE TABLE IF NOT EXISTS sprint_registrations (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    sprint_id INT UNSIGNED NOT NULL,
    station_id INT UNSIGNED NOT NULL,
    registered_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_sprint_reg_sprint FOREIGN KEY (sprint_id) REFERENCES sprints(id) ON DELETE CASCADE,
    CONSTRAINT fk_sprint_reg_station FOREIGN KEY (station_id) REFERENCES stations(id) ON DELETE CASCADE,
    UNIQUE KEY uniq_sprint_station (sprint_id, station_id)
) ENGINE=InnoDB;
