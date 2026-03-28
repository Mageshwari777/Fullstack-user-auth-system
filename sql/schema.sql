-- MySQL schema for User Authentication System
-- Database: user_auth

CREATE DATABASE IF NOT EXISTS user_auth
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE user_auth;

CREATE TABLE IF NOT EXISTS users (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  username VARCHAR(50) NOT NULL,
  email VARCHAR(190) NOT NULL,
  password_hash VARCHAR(255) NOT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY uq_users_username (username),
  UNIQUE KEY uq_users_email (email)
);

