-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 15, 2025 at 02:48 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `hoteldb`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `add_reservation` (IN `guest_id_param` INT, IN `room_id_param` INT, IN `check_in_date_param` DATE, IN `check_out_date_param` DATE, IN `total_price_param` DECIMAL(10,2))   BEGIN  
    INSERT INTO reservations (guest_id, room_id, check_in_date, check_out_date, total_price)  
    VALUES (guest_id_param, room_id_param, check_in_date_param, check_out_date_param, total_price_param);  
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `get_guest_reservations` (IN `guest_id_param` INT)   BEGIN  
    SELECT r.reservation_id, r.room_id, r.check_in_date, r.check_out_date, r.total_price, r.status  
    FROM reservations r  
    WHERE r.guest_id = guest_id_param;  
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `update_room_status` (IN `reservation_id_param` INT, IN `new_status` ENUM('Confirmed','Checked-in','Completed','Cancelled'))   BEGIN  
    DECLARE room_id_var INT;  
    
    -- Dapatkan room_id dari reservasi
    SELECT room_id INTO room_id_var FROM reservations WHERE reservation_id = reservation_id_param;  

    -- Update status reservasi
    UPDATE reservations SET status = new_status WHERE reservation_id = reservation_id_param;  

    -- Update status kamar berdasarkan status reservasi yang baru
    IF new_status = 'Checked-in' THEN  
        UPDATE rooms SET status = 'Occupied' WHERE room_id = room_id_var;  
    ELSEIF new_status = 'Completed' OR new_status = 'Cancelled' THEN  
        UPDATE rooms SET status = 'Available' WHERE room_id = room_id_var;  
    END IF;  
END$$

--
-- Functions
--
CREATE DEFINER=`root`@`localhost` FUNCTION `get_monthly_revenue` (`year` INT, `month` INT) RETURNS DECIMAL(10,2) DETERMINISTIC BEGIN
    DECLARE total DECIMAL(10,2);
    SELECT COALESCE(SUM(amount), 0) INTO total
    FROM payments
    WHERE YEAR(payment_date) = year 
      AND MONTH(payment_date) = month
      AND status = 'Paid'; -- Hanya menghitung pembayaran yang "Paid"
    RETURN total;
END$$

CREATE DEFINER=`root`@`localhost` FUNCTION `get_reservation_count` (`guest_id_param` INT) RETURNS INT(11) DETERMINISTIC BEGIN  
    DECLARE reservation_count INT;  
    SELECT COUNT(*) INTO reservation_count FROM reservations WHERE guest_id = guest_id_param;  
    RETURN reservation_count;  
END$$

CREATE DEFINER=`root`@`localhost` FUNCTION `get_total_payment` (`reservation_id_param` INT) RETURNS DECIMAL(10,2) DETERMINISTIC BEGIN  
    DECLARE total_paid DECIMAL(10,2);  
    SELECT COALESCE(SUM(amount), 0) INTO total_paid FROM payments WHERE reservation_id = reservation_id_param;  
    RETURN total_paid;  
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE `employees` (
  `employee_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `position` varchar(50) NOT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `hire_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employees`
--

INSERT INTO `employees` (`employee_id`, `name`, `position`, `phone`, `email`, `hire_date`) VALUES
(1, 'Asep Suryaman', 'manager', '082122113344', 'asep@xyz.com', '2025-01-01'),
(2, 'admin', 'admin', '0800', 'admin', '2025-02-01');

-- --------------------------------------------------------

--
-- Table structure for table `guests`
--

CREATE TABLE `guests` (
  `guest_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `guests`
--

INSERT INTO `guests` (`guest_id`, `name`, `email`, `phone`, `address`, `created_at`) VALUES
(1, 'Ahmad Setiawan', 'ahmadsetiawan@gmail.com', '+6281234567890', 'Jl. Merdeka No. 10, Jakarta', '2024-06-20 03:00:05'),
(2, 'Budi Santoso', 'budisantoso@gmail.com', '+6282234567891', 'Jl. Sudirman No. 15, Bandung', '2024-06-20 03:00:10'),
(3, 'Citra Lestari', 'citralestari@gmail.com', '+6283234567892', 'Jl. Diponegoro No. 5, Surabaya', '2024-06-20 03:00:15'),
(4, 'Dewi Anggraini', 'dewianggraini@gmail.com', '+6284234567893', 'Jl. Ahmad Yani No. 20, Yogyakarta', '2024-06-20 03:00:20'),
(5, 'Eka Prasetyo', 'ekaprasetyo@gmail.com', '+6285234567894', 'Jl. Gatot Subroto No. 8, Semarang', '2024-06-20 03:00:25'),
(6, 'Fajar Nugroho', 'fajarnugroho@gmail.com', '+6286234567895', 'Jl. Pahlawan No. 12, Medan', '2024-06-20 03:00:30'),
(7, 'Gita Permata', 'gitapermata@gmail.com', '+6287234567896', 'Jl. Teuku Umar No. 9, Makassar', '2024-06-20 03:00:35'),
(8, 'Hadi Wijaya', 'hadiwijaya@gmail.com', '+6288234567897', 'Jl. Dr. Sutomo No. 7, Malang', '2024-06-20 03:00:40'),
(9, 'Indah Kartika', 'indahkartika@gmail.com', '+6289234567898', 'Jl. Pemuda No. 3, Palembang', '2024-06-20 03:00:45'),
(10, 'Joko Widodo', 'jokowidodo@gmail.com', '+6281334567899', 'Jl. Sultan Agung No. 2, Balikpapan', '2024-06-20 03:00:50'),
(11, 'Kiki Amelia', 'kikiamelia@gmail.com', '+6282334567800', 'Jl. Cendana No. 14, Pekanbaru', '2024-06-20 03:00:55'),
(12, 'Lukman Hakim', 'lukmanhakim@gmail.com', '+6283334567801', 'Jl. Dipatiukur No. 22, Banjarmasin', '2024-06-20 03:01:00'),
(13, 'Maya Sari', 'mayasari@gmail.com', '+6284334567802', 'Jl. Veteran No. 17, Bogor', '2024-06-20 03:01:05'),
(14, 'Nanda Putra', 'nandaputra@gmail.com', '+6285334567803', 'Jl. Imam Bonjol No. 6, Maluku', '2024-06-20 03:01:10'),
(15, 'Oki Pranata', 'okipranata@gmail.com', '+6286334567804', 'Jl. Raden Saleh No. 11, Banda Aceh', '2024-06-20 03:01:15'),
(16, 'Putri Melati', 'putrimelati@gmail.com', '+6287334567805', 'Jl. Kenanga No. 25, Manado', '2024-06-20 03:01:20'),
(17, 'Qori Ananda', 'qoriananda@gmail.com', '+6288334567806', 'Jl. Bunga Mawar No. 30, Jayapura', '2024-06-20 03:01:25'),
(18, 'Rudi Hartono', 'rudihartono@gmail.com', '+6289334567807', 'Jl. Hasanuddin No. 19, Kendari', '2024-06-20 03:01:30'),
(19, 'Siti Rohmah', 'sitirohmah@gmail.com', '+6281434567808', 'Jl. Jendral Sudirman No. 27, Pontianak', '2024-06-20 03:01:35'),
(20, 'Taufik Hidayat', 'taufikhidayat@gmail.com', '+6282434567809', 'Jl. KH Ahmad Dahlan No. 4, Palu', '2024-06-19 17:00:00'),
(21, 'Budi Santoso', 'budi@email.com', '081234567890', 'Jl. Merdeka No. 10', '2025-01-29 08:42:53'),
(22, 'ujang', 'ujang@xyz.com', '082221213131', 'Di sana di sini', '2025-02-01 06:56:05');

--
-- Triggers `guests`
--
DELIMITER $$
CREATE TRIGGER `before_insert_guest` BEFORE INSERT ON `guests` FOR EACH ROW BEGIN  
    IF NEW.created_at IS NULL THEN  
        SET NEW.created_at = NOW();  
    END IF;  
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Stand-in structure for view `guest_reservation_count`
-- (See below for the actual view)
--
CREATE TABLE `guest_reservation_count` (
`guest_id` int(11)
,`name` varchar(100)
,`total_reservations` bigint(21)
);

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `payment_id` int(11) NOT NULL,
  `reservation_id` int(11) NOT NULL,
  `payment_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `amount` decimal(10,2) NOT NULL,
  `payment_method` enum('Credit Card','Cash','Bank Transfer') NOT NULL,
  `status` enum('Pending','Paid','Refunded') DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`payment_id`, `reservation_id`, `payment_date`, `amount`, `payment_method`, `status`) VALUES
(1, 1, '2025-01-20 07:00:00', 500000.00, 'Credit Card', 'Paid'),
(2, 2, '2025-01-21 03:30:00', 1050000.00, 'Cash', 'Paid'),
(3, 3, '2025-01-18 02:00:00', 200000.00, 'Bank Transfer', 'Pending'),
(4, 4, '2025-01-25 09:45:00', 250000.00, 'Credit Card', 'Refunded'),
(5, 5, '2025-01-15 04:00:00', 1600000.00, 'Bank Transfer', 'Paid'),
(6, 6, '2025-01-22 06:20:00', 1440000.00, 'Credit Card', 'Paid'),
(7, 7, '2025-01-24 11:30:00', 900000.00, 'Cash', 'Paid'),
(8, 8, '2025-01-19 01:15:00', 1850000.00, 'Bank Transfer', 'Paid'),
(9, 9, '2025-01-22 05:00:00', 1350000.00, 'Cash', 'Refunded'),
(10, 10, '2025-01-17 08:00:00', 700000.00, 'Credit Card', 'Paid'),
(11, 11, '2025-02-01 08:19:14', 500000.00, 'Cash', 'Paid'),
(14, 3, '2025-01-29 08:49:21', 200000.00, 'Credit Card', 'Paid');

--
-- Triggers `payments`
--
DELIMITER $$
CREATE TRIGGER `after_insert_payment` AFTER INSERT ON `payments` FOR EACH ROW BEGIN  
    INSERT INTO payment_audit_log (payment_id, reservation_id, amount, payment_method, status)  
    VALUES (NEW.payment_id, NEW.reservation_id, NEW.amount, NEW.payment_method, NEW.status);  
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `payment_audit_log`
--

CREATE TABLE `payment_audit_log` (
  `log_id` int(11) NOT NULL,
  `payment_id` int(11) DEFAULT NULL,
  `reservation_id` int(11) DEFAULT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  `payment_method` enum('Credit Card','Cash','Bank Transfer') DEFAULT NULL,
  `status` enum('Pending','Paid','Refunded') DEFAULT NULL,
  `log_timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payment_audit_log`
--

INSERT INTO `payment_audit_log` (`log_id`, `payment_id`, `reservation_id`, `amount`, `payment_method`, `status`, `log_timestamp`) VALUES
(1, 14, 3, 200000.00, 'Credit Card', 'Paid', '2025-01-29 08:49:21'),
(2, 15, 4, 9000000.00, 'Cash', 'Paid', '2025-02-01 09:37:30');

-- --------------------------------------------------------

--
-- Table structure for table `reservations`
--

CREATE TABLE `reservations` (
  `reservation_id` int(11) NOT NULL,
  `guest_id` int(11) NOT NULL,
  `room_id` int(11) NOT NULL,
  `check_in_date` date NOT NULL,
  `check_out_date` date NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `status` enum('Confirmed','Checked-in','Completed','Cancelled') DEFAULT 'Confirmed'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reservations`
--

INSERT INTO `reservations` (`reservation_id`, `guest_id`, `room_id`, `check_in_date`, `check_out_date`, `total_price`, `status`) VALUES
(1, 1, 1, '2025-01-20', '2025-01-23', 750000.00, 'Confirmed'),
(2, 2, 2, '2025-01-21', '2025-01-23', 700000.00, 'Checked-in'),
(3, 3, 3, '2025-01-18', '2025-01-23', 2500000.00, 'Checked-in'),
(4, 4, 4, '2025-01-25', '2025-01-26', 200000.00, 'Cancelled'),
(5, 5, 5, '2025-01-15', '2025-01-19', 1600000.00, 'Checked-in'),
(6, 6, 6, '2025-01-22', '2025-01-25', 1440000.00, 'Cancelled'),
(7, 7, 7, '2025-01-24', '2025-01-26', 600000.00, 'Completed'),
(8, 8, 8, '2025-01-19', '2025-01-25', 2220000.00, 'Completed'),
(9, 9, 9, '2025-01-22', '2025-01-24', 900000.00, 'Cancelled'),
(10, 10, 10, '2025-01-17', '2025-01-22', 1400000.00, 'Completed'),
(11, 1, 2, '2025-02-01', '2025-02-05', 900000.00, 'Completed'),
(15, 4, 4, '2025-02-15', '2025-02-17', 400000.00, 'Checked-in'),
(16, 13, 1, '2025-02-15', '2025-02-19', 1000000.00, 'Completed');

--
-- Triggers `reservations`
--
DELIMITER $$
CREATE TRIGGER `after_update_reservation_status` AFTER UPDATE ON `reservations` FOR EACH ROW BEGIN  
    IF NEW.status = 'Checked-in' THEN  
        UPDATE rooms SET status = 'Occupied' WHERE room_id = NEW.room_id;  
    ELSEIF NEW.status = 'Completed' OR NEW.status = 'Cancelled' THEN  
        UPDATE rooms SET status = 'Available' WHERE room_id = NEW.room_id;  
    END IF;  
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `check_room_occupied` BEFORE INSERT ON `reservations` FOR EACH ROW BEGIN
    -- Cek apakah kamar yang dipesan sudah occupied
    DECLARE room_status ENUM('Available', 'Occupied', 'Maintenance');
    
    -- Ambil status kamar yang ingin dipesan
    SELECT status INTO room_status
    FROM rooms
    WHERE room_id = NEW.room_id;

    -- Jika kamar sudah occupied, batalkan insert
    IF room_status = 'Occupied' THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Kamar ini sudah ditempati. Silakan pilih kamar lain.';
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `update_room_status_after_insert` AFTER INSERT ON `reservations` FOR EACH ROW BEGIN
    -- Jika reservasi dibuat dengan status "Checked-in", ubah status kamar jadi "Occupied"
    IF NEW.status = 'Checked-in' THEN
        UPDATE rooms
        SET status = 'Occupied'
        WHERE room_id = NEW.room_id;
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `update_room_status_after_update` AFTER UPDATE ON `reservations` FOR EACH ROW BEGIN
    -- Jika status berubah dari "Checked-in" menjadi yang lain, cek apakah masih ada reservasi lain
    IF OLD.status = 'Checked-in' AND NEW.status <> 'Checked-in' THEN
        UPDATE rooms
        SET status = 'Available'
        WHERE room_id = OLD.room_id
        AND NOT EXISTS (
            SELECT 1 FROM reservations
            WHERE room_id = OLD.room_id AND status = 'Checked-in'
        );
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Stand-in structure for view `reservation_details`
-- (See below for the actual view)
--
CREATE TABLE `reservation_details` (
`reservation_id` int(11)
,`guest_name` varchar(100)
,`room_number` varchar(10)
,`check_in_date` date
,`check_out_date` date
,`status` enum('Confirmed','Checked-in','Completed','Cancelled')
);

-- --------------------------------------------------------

--
-- Table structure for table `rooms`
--

CREATE TABLE `rooms` (
  `room_id` int(11) NOT NULL,
  `room_number` varchar(10) NOT NULL,
  `type` enum('Single','Double','Suite') NOT NULL,
  `price_per_night` decimal(10,2) NOT NULL,
  `status` enum('Available','Occupied','Maintenance') DEFAULT 'Available'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rooms`
--

INSERT INTO `rooms` (`room_id`, `room_number`, `type`, `price_per_night`, `status`) VALUES
(1, '101', 'Single', 250000.00, 'Available'),
(2, '102', 'Double', 350000.00, 'Available'),
(3, '103', 'Suite', 500000.00, 'Occupied'),
(4, '104', 'Single', 200000.00, 'Occupied'),
(5, '105', 'Double', 400000.00, 'Occupied'),
(6, '106', 'Suite', 480000.00, 'Available'),
(7, '107', 'Single', 300000.00, 'Available'),
(8, '108', 'Double', 370000.00, 'Available'),
(9, '109', 'Suite', 450000.00, 'Available'),
(10, '110', 'Single', 280000.00, 'Available');

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `service_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`service_id`, `name`, `description`, `price`) VALUES
(1, 'Pembersihan Kamar', 'Layanan pembersihan kamar harian', 150000.00),
(2, 'Laundry', 'Layanan cuci dan dry cleaning', 250000.00),
(3, 'Spa', 'Layanan spa dan pijat relaksasi', 500000.00),
(4, 'Akses Gym', 'Akses ke pusat kebugaran hotel', 100000.00),
(5, 'Kolam Renang', 'Akses ke kolam renang hotel', 120000.00),
(6, 'Antar-Jemput Bandara', 'Transportasi ke dan dari bandara', 300000.00),
(7, 'Sarapan Prasmanan', 'Sarapan prasmanan sepuasnya', 200000.00),
(8, 'Layanan Concierge', 'Bantuan pemesanan dan reservasi', 80000.00),
(9, 'Mini Bar', 'Minibar yang tersedia di dalam kamar', 180000.00),
(10, 'Layanan Kamar', 'Layanan makanan ke kamar 24/7', 220000.00);

-- --------------------------------------------------------

--
-- Table structure for table `service_usage`
--

CREATE TABLE `service_usage` (
  `usage_id` int(11) NOT NULL,
  `reservation_id` int(11) NOT NULL,
  `service_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `total_price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `service_usage`
--

INSERT INTO `service_usage` (`usage_id`, `reservation_id`, `service_id`, `quantity`, `total_price`) VALUES
(1, 1, 1, 2, 500000.00),
(2, 2, 2, 3, 1050000.00),
(3, 3, 3, 1, 200000.00),
(4, 4, 1, 1, 250000.00),
(5, 5, 4, 5, 1500000.00);

-- --------------------------------------------------------

--
-- Stand-in structure for view `total_revenue_per_reservation`
-- (See below for the actual view)
--
CREATE TABLE `total_revenue_per_reservation` (
`reservation_id` int(11)
,`guest_name` varchar(100)
,`total_payment` decimal(32,2)
,`total_service_usage` decimal(32,2)
,`total_revenue` decimal(33,2)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `view_occupied_rooms`
-- (See below for the actual view)
--
CREATE TABLE `view_occupied_rooms` (
`room_number` varchar(10)
,`check_in_date` date
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `view_unpaid_reservations`
-- (See below for the actual view)
--
CREATE TABLE `view_unpaid_reservations` (
`reservation_id` int(11)
,`guest_id` int(11)
,`check_in_date` date
);

-- --------------------------------------------------------

--
-- Structure for view `guest_reservation_count`
--
DROP TABLE IF EXISTS `guest_reservation_count`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `guest_reservation_count`  AS SELECT `g`.`guest_id` AS `guest_id`, `g`.`name` AS `name`, count(`r`.`reservation_id`) AS `total_reservations` FROM (`guests` `g` left join `reservations` `r` on(`g`.`guest_id` = `r`.`guest_id`)) GROUP BY `g`.`guest_id`, `g`.`name` ;

-- --------------------------------------------------------

--
-- Structure for view `reservation_details`
--
DROP TABLE IF EXISTS `reservation_details`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `reservation_details`  AS SELECT `r`.`reservation_id` AS `reservation_id`, `g`.`name` AS `guest_name`, `rm`.`room_number` AS `room_number`, `r`.`check_in_date` AS `check_in_date`, `r`.`check_out_date` AS `check_out_date`, `r`.`status` AS `status` FROM ((`reservations` `r` join `guests` `g` on(`r`.`guest_id` = `g`.`guest_id`)) join `rooms` `rm` on(`r`.`room_id` = `rm`.`room_id`)) ;

-- --------------------------------------------------------

--
-- Structure for view `total_revenue_per_reservation`
--
DROP TABLE IF EXISTS `total_revenue_per_reservation`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `total_revenue_per_reservation`  AS SELECT `r`.`reservation_id` AS `reservation_id`, `g`.`name` AS `guest_name`, coalesce(sum(`p`.`amount`),0) AS `total_payment`, coalesce(sum(`su`.`total_price`),0) AS `total_service_usage`, coalesce(sum(`p`.`amount`),0) + coalesce(sum(`su`.`total_price`),0) AS `total_revenue` FROM (((`reservations` `r` join `guests` `g` on(`r`.`guest_id` = `g`.`guest_id`)) left join `payments` `p` on(`r`.`reservation_id` = `p`.`reservation_id`)) left join `service_usage` `su` on(`r`.`reservation_id` = `su`.`reservation_id`)) GROUP BY `r`.`reservation_id`, `g`.`name` ;

-- --------------------------------------------------------

--
-- Structure for view `view_occupied_rooms`
--
DROP TABLE IF EXISTS `view_occupied_rooms`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_occupied_rooms`  AS SELECT `r`.`room_number` AS `room_number`, `res`.`check_in_date` AS `check_in_date` FROM (`reservations` `res` join `rooms` `r` on(`res`.`room_id` = `r`.`room_id`)) WHERE `r`.`status` = 'Occupied' AND to_days(current_timestamp()) - to_days(`res`.`check_in_date`) > 7 ;

-- --------------------------------------------------------

--
-- Structure for view `view_unpaid_reservations`
--
DROP TABLE IF EXISTS `view_unpaid_reservations`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_unpaid_reservations`  AS SELECT `res`.`reservation_id` AS `reservation_id`, `res`.`guest_id` AS `guest_id`, `res`.`check_in_date` AS `check_in_date` FROM `reservations` AS `res` WHERE `res`.`status` = 'Checked-in' AND !(`res`.`reservation_id` in (select `payments`.`reservation_id` from `payments` where `payments`.`status` = 'Paid')) ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`employee_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `guests`
--
ALTER TABLE `guests`
  ADD PRIMARY KEY (`guest_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`payment_id`),
  ADD KEY `reservation_id` (`reservation_id`);

--
-- Indexes for table `payment_audit_log`
--
ALTER TABLE `payment_audit_log`
  ADD PRIMARY KEY (`log_id`);

--
-- Indexes for table `reservations`
--
ALTER TABLE `reservations`
  ADD PRIMARY KEY (`reservation_id`),
  ADD KEY `guest_id` (`guest_id`),
  ADD KEY `room_id` (`room_id`);

--
-- Indexes for table `rooms`
--
ALTER TABLE `rooms`
  ADD PRIMARY KEY (`room_id`),
  ADD UNIQUE KEY `room_number` (`room_number`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`service_id`);

--
-- Indexes for table `service_usage`
--
ALTER TABLE `service_usage`
  ADD PRIMARY KEY (`usage_id`),
  ADD KEY `reservation_id` (`reservation_id`),
  ADD KEY `service_id` (`service_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
  MODIFY `employee_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `guests`
--
ALTER TABLE `guests`
  MODIFY `guest_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `payment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `payment_audit_log`
--
ALTER TABLE `payment_audit_log`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `reservations`
--
ALTER TABLE `reservations`
  MODIFY `reservation_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `rooms`
--
ALTER TABLE `rooms`
  MODIFY `room_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `service_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `service_usage`
--
ALTER TABLE `service_usage`
  MODIFY `usage_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`reservation_id`) REFERENCES `reservations` (`reservation_id`);

--
-- Constraints for table `reservations`
--
ALTER TABLE `reservations`
  ADD CONSTRAINT `reservations_ibfk_1` FOREIGN KEY (`guest_id`) REFERENCES `guests` (`guest_id`),
  ADD CONSTRAINT `reservations_ibfk_2` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`room_id`);

--
-- Constraints for table `service_usage`
--
ALTER TABLE `service_usage`
  ADD CONSTRAINT `service_usage_ibfk_1` FOREIGN KEY (`reservation_id`) REFERENCES `reservations` (`reservation_id`),
  ADD CONSTRAINT `service_usage_ibfk_2` FOREIGN KEY (`service_id`) REFERENCES `services` (`service_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
