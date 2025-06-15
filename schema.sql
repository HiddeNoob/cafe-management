-- Mevcut tabloları sil (varsa)
DROP TABLE IF EXISTS `RECEIPT_PRODUCTS`;
DROP TABLE IF EXISTS `RECEIPT`;
DROP TABLE IF EXISTS `PRODUCT_INGREDIENT`;
DROP TABLE IF EXISTS `PRODUCT`;
DROP TABLE IF EXISTS `INGREDIENT`;
DROP TABLE IF EXISTS `EMPLOYEE_HISTORY`;
DROP TABLE IF EXISTS `ADDRESS`;
DROP TABLE IF EXISTS `EMPLOYEE`;
DROP TABLE IF EXISTS `CUSTOMER`;
DROP TABLE IF EXISTS `CATEGORY_TREE`;
DROP TABLE IF EXISTS `CATEGORY`;

begin;

CREATE TABLE `ADDRESS` (
  `employee_id` bigint(20) NOT NULL PRIMARY KEY,
  `address` varchar(150) DEFAULT NULL,
  `locality` varchar(32) DEFAULT NULL,
  `province` varchar(32) DEFAULT NULL,
  `postal_code` varchar(32) DEFAULT NULL
);

CREATE TABLE `CATEGORY` (
  `category_id` bigint(20) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `category_name` varchar(32) DEFAULT NULL
);

CREATE TABLE `CATEGORY_TREE` (
  `parent_category_id` bigint(20) NOT NULL,
  `sub_category_id` bigint(20) NOT NULL,
  PRIMARY KEY (`parent_category_id`,`sub_category_id`),
  FOREIGN KEY (`parent_category_id`) REFERENCES `CATEGORY` (`category_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (`sub_category_id`) REFERENCES `CATEGORY` (`category_id`) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE `CUSTOMER` (
  `customer_id` bigint(20) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `customer_name` varchar(32) NOT NULL,
  `customer_surname` varchar(32) NOT NULL,
  `customer_phone` varchar(15) DEFAULT NULL,
  `customer_email` varchar(64) DEFAULT NULL,
  `customer_nickname` varchar(16) NOT NULL UNIQUE,
  `customer_password` text NOT NULL
);

CREATE TABLE `EMPLOYEE` (
  `employee_id` bigint(20) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `employee_name` varchar(32) DEFAULT NULL,
  `employee_surname` varchar(32) DEFAULT NULL,
  `employee_phone` varchar(32) DEFAULT NULL,
  `employee_email` varchar(32) DEFAULT NULL UNIQUE, -- UNIQUE kısıtlaması eklendi
  `employee_password` TEXT NOT NULL,
  `employee_hire_date` date DEFAULT NULL
);

CREATE TABLE `EMPLOYEE_HISTORY` (
  `history_id` bigint(20) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `employee_id` bigint(20) DEFAULT NULL,
  `employee_start_date` date DEFAULT NULL,
  `employee_end_date` date DEFAULT NULL,
  `employee_salary` decimal(10,2) DEFAULT NULL,
  FOREIGN KEY (`employee_id`) REFERENCES `EMPLOYEE` (`employee_id`) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE `INGREDIENT` (
  `ingredient_id` bigint(20) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `ingredient_name` varchar(32) DEFAULT NULL
);

CREATE TABLE `PRODUCT` (
  `product_id` bigint(20) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `category_id` bigint(20) DEFAULT NULL,
  `product_description` text DEFAULT NULL,
  `product_img_url` text DEFAULT NULL,
  `product_name` varchar(32) DEFAULT NULL,
  `product_price` decimal(10,2) DEFAULT NULL,
  FOREIGN KEY (`category_id`) REFERENCES `CATEGORY` (`category_id`) ON DELETE SET NULL ON UPDATE CASCADE -- Category silindiğinde product'ın category_id'si NULL olsun
);

CREATE TABLE `PRODUCT_INGREDIENT` (
  `product_id` bigint(20) NOT NULL,
  `ingredient_id` bigint(20) NOT NULL,
  `ingredient_amount` bigint(20) DEFAULT NULL,
  `ingredient_amount_type` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`product_id`,`ingredient_id`),
  FOREIGN KEY (`product_id`) REFERENCES `PRODUCT` (`product_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (`ingredient_id`) REFERENCES `INGREDIENT` (`ingredient_id`) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE `RECEIPT` (
  `receipt_id` bigint(20) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `customer_id` bigint(20) DEFAULT NULL,
  `employee_id` bigint(20) DEFAULT NULL,
  `receipt_timestamp` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `receipt_total_amount` decimal(10,2) DEFAULT NULL,
  FOREIGN KEY (`customer_id`) REFERENCES `CUSTOMER` (`customer_id`) ON DELETE SET NULL ON UPDATE CASCADE, -- Müşteri silindiğinde veya güncellendiğinde fatura kalır ama customer_id NULL olur
  FOREIGN KEY (`employee_id`) REFERENCES `EMPLOYEE` (`employee_id`) ON DELETE SET NULL ON UPDATE CASCADE -- Çalışan silindiğinde veya güncellendiğinde fatura kalır ama employee_id NULL olur
);

CREATE TABLE `RECEIPT_PRODUCTS` (
  `receipt_id` bigint(20) NOT NULL,
  `product_id` bigint(20) NOT NULL,
  `product_quantity` bigint(20) DEFAULT NULL,
  `product_total_amount` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`receipt_id`,`product_id`),
  FOREIGN KEY (`receipt_id`) REFERENCES `RECEIPT` (`receipt_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (`product_id`) REFERENCES `PRODUCT` (`product_id`) ON DELETE CASCADE ON UPDATE CASCADE
);

commit;