CREATE DATABASE IF NOT EXISTS AskNicelyDB;
USE AskNicelyDB;

-- Grant all privileges to the 'Developer' user on the AskNicelyDB database
GRANT ALL PRIVILEGES ON AskNicelyDB.* TO 'Developer'@'%';

-- Flush the privileges to apply the changes
FLUSH PRIVILEGES;

CREATE TABLE IF NOT EXISTS `Company` (
  `companyId` int NOT NULL AUTO_INCREMENT,
  `name` varchar(64) DEFAULT NULL,
  PRIMARY KEY (`companyId`),
  UNIQUE KEY `name` (`name`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE IF NOT EXISTS `Employee` (
  `employeeId` int NOT NULL AUTO_INCREMENT,
  `name` varchar(64) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`employeeId`),
  UNIQUE KEY `email` (`email`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE IF NOT EXISTS `EmployeeSalary` (
  `employeeId` int NOT NULL,
  `companyId` int NOT NULL,
  `salary` int NOT NULL,
  KEY `companyId` (`companyId`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;