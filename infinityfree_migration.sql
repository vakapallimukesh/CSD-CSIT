-- ============================================================
-- InfinityFree Database Migration Script
-- Run this in phpMyAdmin → SQL tab on InfinityFree
-- Safe to run multiple times — uses column-existence checks
-- Does NOT delete or modify any existing data
-- ============================================================

-- ────────────────────────────────────────────────────
-- 1. EVENTS TABLE — new point columns + registration toggle
-- ────────────────────────────────────────────────────

-- Add accept_registrations column (controls whether event accepts sign-ups)
SET @col_exists = (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS 
    WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'events' AND COLUMN_NAME = 'accept_registrations');
SET @sql = IF(@col_exists = 0, 
    'ALTER TABLE `events` ADD COLUMN `accept_registrations` TINYINT(1) NOT NULL DEFAULT 1', 
    'SELECT 1');
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Add participate_points column
SET @col_exists = (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS 
    WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'events' AND COLUMN_NAME = 'participate_points');
SET @sql = IF(@col_exists = 0, 
    'ALTER TABLE `events` ADD COLUMN `participate_points` INT(11) NOT NULL DEFAULT 5', 
    'SELECT 1');
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Add winner_points column
SET @col_exists = (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS 
    WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'events' AND COLUMN_NAME = 'winner_points');
SET @sql = IF(@col_exists = 0, 
    'ALTER TABLE `events` ADD COLUMN `winner_points` INT(11) NOT NULL DEFAULT 10', 
    'SELECT 1');
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Add runner_points column
SET @col_exists = (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS 
    WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'events' AND COLUMN_NAME = 'runner_points');
SET @sql = IF(@col_exists = 0, 
    'ALTER TABLE `events` ADD COLUMN `runner_points` INT(11) NOT NULL DEFAULT 8', 
    'SELECT 1');
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Add organiser_points column
SET @col_exists = (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS 
    WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'events' AND COLUMN_NAME = 'organiser_points');
SET @sql = IF(@col_exists = 0, 
    'ALTER TABLE `events` ADD COLUMN `organiser_points` INT(11) NOT NULL DEFAULT 7', 
    'SELECT 1');
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- ────────────────────────────────────────────────────
-- 2. STUDENTS TABLE — alumni flag + profile picture
-- ────────────────────────────────────────────────────

-- Add is_alumni column
SET @col_exists = (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS 
    WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'students' AND COLUMN_NAME = 'is_alumni');
SET @sql = IF(@col_exists = 0, 
    'ALTER TABLE `students` ADD COLUMN `is_alumni` TINYINT(1) DEFAULT 0', 
    'SELECT 1');
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Add profile_picture column
SET @col_exists = (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS 
    WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'students' AND COLUMN_NAME = 'profile_picture');
SET @sql = IF(@col_exists = 0, 
    'ALTER TABLE `students` ADD COLUMN `profile_picture` VARCHAR(255) DEFAULT NULL', 
    'SELECT 1');
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- ────────────────────────────────────────────────────
-- 3. STUDENT_PERSONAL TABLE — personal phone number
-- ────────────────────────────────────────────────────

-- Add personal_number column
SET @col_exists = (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS 
    WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'student_personal' AND COLUMN_NAME = 'personal_number');
SET @sql = IF(@col_exists = 0, 
    'ALTER TABLE `student_personal` ADD COLUMN `personal_number` VARCHAR(15) NOT NULL DEFAULT ''''', 
    'SELECT 1');
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- ────────────────────────────────────────────────────
-- 4. STUDENT_PROFILE TABLE — extended profile fields
-- ────────────────────────────────────────────────────

-- Add projects column
SET @col_exists = (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS 
    WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'student_profile' AND COLUMN_NAME = 'projects');
SET @sql = IF(@col_exists = 0, 
    'ALTER TABLE `student_profile` ADD COLUMN `projects` TEXT DEFAULT NULL', 
    'SELECT 1');
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Add experience column
SET @col_exists = (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS 
    WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'student_profile' AND COLUMN_NAME = 'experience');
SET @sql = IF(@col_exists = 0, 
    'ALTER TABLE `student_profile` ADD COLUMN `experience` TEXT DEFAULT NULL', 
    'SELECT 1');
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Add education column
SET @col_exists = (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS 
    WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'student_profile' AND COLUMN_NAME = 'education');
SET @sql = IF(@col_exists = 0, 
    'ALTER TABLE `student_profile` ADD COLUMN `education` TEXT DEFAULT NULL', 
    'SELECT 1');
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Add certifications column
SET @col_exists = (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS 
    WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'student_profile' AND COLUMN_NAME = 'certifications');
SET @sql = IF(@col_exists = 0, 
    'ALTER TABLE `student_profile` ADD COLUMN `certifications` TEXT DEFAULT NULL', 
    'SELECT 1');
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Add achievements column
SET @col_exists = (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS 
    WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'student_profile' AND COLUMN_NAME = 'achievements');
SET @sql = IF(@col_exists = 0, 
    'ALTER TABLE `student_profile` ADD COLUMN `achievements` TEXT DEFAULT NULL', 
    'SELECT 1');
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Add profile_picture column to student_profile
SET @col_exists = (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS 
    WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'student_profile' AND COLUMN_NAME = 'profile_picture');
SET @sql = IF(@col_exists = 0, 
    'ALTER TABLE `student_profile` ADD COLUMN `profile_picture` VARCHAR(255) DEFAULT NULL', 
    'SELECT 1');
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- ============================================================
-- DONE! All columns added safely. Existing data is untouched.
-- ============================================================
