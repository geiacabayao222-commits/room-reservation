-- Room Reservation System
-- Select your database FIRST in phpMyAdmin, then Import this file.
-- (No CREATE DATABASE / USE lines, so it works on InfinityFree.)

DROP TABLE IF EXISTS `reservations`;
DROP TABLE IF EXISTS `rooms`;

CREATE TABLE `rooms` (
  `room_id` int(11) NOT NULL AUTO_INCREMENT,
  `room_name` varchar(100) NOT NULL,
  `room_type` varchar(50) NOT NULL,
  `capacity` int(11) NOT NULL,
  `location` varchar(100) NOT NULL,
  `status` enum('Available','Maintenance') NOT NULL DEFAULT 'Available',
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`room_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `reservations` (
  `reservation_id` int(11) NOT NULL AUTO_INCREMENT,
  `room_id` int(11) NOT NULL,
  `guest_name` varchar(100) NOT NULL,
  `guest_email` varchar(100) NOT NULL,
  `reservation_date` date NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `purpose` varchar(255) DEFAULT NULL,
  `status` enum('Pending','Approved','Cancelled') NOT NULL DEFAULT 'Pending',
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`reservation_id`),
  KEY `room_id` (`room_id`),
  CONSTRAINT `reservations_ibfk_1` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`room_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `rooms` (`room_id`,`room_name`,`room_type`,`capacity`,`location`,`status`) VALUES
(1,'Conference Room A','Conference',20,'2nd Floor','Available'),
(2,'Computer Lab 1','Laboratory',40,'1st Floor','Available'),
(3,'Meeting Room B','Meeting',10,'3rd Floor','Available'),
(4,'Seminar Hall','Hall',100,'Ground Floor','Maintenance');

INSERT INTO `reservations` (`reservation_id`,`room_id`,`guest_name`,`guest_email`,`reservation_date`,`start_time`,`end_time`,`purpose`,`status`) VALUES
(1,1,'Juan Dela Cruz','juan@example.com',CURDATE(),'09:00:00','11:00:00','Department meeting','Approved'),
(2,2,'Maria Santos','maria@example.com',CURDATE(),'13:00:00','15:00:00','Programming class','Pending');
