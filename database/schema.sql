-- ============================================================
-- United 24 X MedNovas — Payment Intake Portal
-- Database Schema (intake-only: collection + storage)
-- ============================================================

CREATE DATABASE IF NOT EXISTS united24_mednovas
  CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE united24_mednovas;

CREATE TABLE IF NOT EXISTS submissions (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  reg_number VARCHAR(50) NOT NULL,
  full_name VARCHAR(150) NOT NULL,
  email VARCHAR(150) NOT NULL,
  phone VARCHAR(30) NOT NULL,
  department VARCHAR(150) NOT NULL,
  passport_photo VARCHAR(255) NOT NULL,   -- stored random filename, see uploads/passports/
  payment_proof VARCHAR(255) NOT NULL,    -- stored random filename, see uploads/proofs/
  status ENUM('pending','accepted','rejected') NOT NULL DEFAULT 'pending',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_status (status),
  INDEX idx_department (department),
  INDEX idx_reg_number (reg_number)
) ENGINE=InnoDB;

-- `status` defaults to 'pending' and is left for whatever downstream
-- review/ticketing system you connect to this database to update.
-- This schema intentionally has no admin/login tables — this portal
-- only collects and stores submissions.







