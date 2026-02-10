
CREATE TABLE IF NOT EXISTS `app_download_logs` (
  `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `store_code` VARCHAR(50) DEFAULT NULL,
  `campaign` VARCHAR(100) DEFAULT NULL,
  `ip` VARCHAR(45) DEFAULT NULL,
  `country` VARCHAR(100) DEFAULT NULL,
  `country_alpha_2` CHAR(3) DEFAULT NULL,
  `province` VARCHAR(100) DEFAULT NULL,
  `regency` VARCHAR(100) DEFAULT NULL,
  `district` VARCHAR(100) DEFAULT NULL,
  `subdistrict` VARCHAR(100) DEFAULT NULL,
  `street` TEXT DEFAULT NULL,
  `postal_code` VARCHAR(20) DEFAULT NULL,
  `latitude` DECIMAL(10, 8) DEFAULT NULL,
  `longitude` DECIMAL(11, 8) DEFAULT NULL,
  `isp` VARCHAR(100) DEFAULT NULL,
  `asn` VARCHAR(100) DEFAULT NULL,
  `os` VARCHAR(50) DEFAULT NULL,
  `os_version` VARCHAR(50) DEFAULT NULL,
  `device_type` VARCHAR(50) DEFAULT NULL,
  `browser` VARCHAR(50) DEFAULT NULL,
  `is_in_app_browser` BOOLEAN DEFAULT FALSE,
  `redirect_to` VARCHAR(50) DEFAULT NULL,
  `result` VARCHAR(50) DEFAULT 'success',
  `timezone` VARCHAR(50) DEFAULT NULL,
  
  -- Indices for faster querying on common filter columns
  INDEX `idx_store_code` (`store_code`),
  INDEX `idx_campaign` (`campaign`),
  INDEX `idx_created_at` (`created_at`),
  INDEX `idx_ip` (`ip`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
