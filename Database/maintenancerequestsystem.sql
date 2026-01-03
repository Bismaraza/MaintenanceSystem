-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:4306
-- Generation Time: Jan 03, 2026 at 03:41 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `maintenancerequestsystem`
--

-- --------------------------------------------------------

--
-- Table structure for table `issuetype`
--

CREATE TABLE `issuetype` (
  `IssueTypeID` int(11) NOT NULL,
  `TypeName` varchar(50) NOT NULL,
  `Description` varchar(150) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `issuetype`
--

INSERT INTO `issuetype` (`IssueTypeID`, `TypeName`, `Description`) VALUES
(1, 'Electrical', 'Issues related to electricity'),
(2, 'Plumbing', 'Water pipes and leakage'),
(3, 'Carpentry', 'Furniture repairs'),
(4, 'HVAC', 'Heating and AC'),
(5, 'Cleaning', 'Sanitation requests'),
(6, 'Network', 'Internet connectivity');

-- --------------------------------------------------------

--
-- Table structure for table `location`
--

CREATE TABLE `location` (
  `LocationID` int(11) NOT NULL,
  `BuildingName` varchar(100) NOT NULL,
  `RoomNumber` varchar(10) DEFAULT NULL,
  `FloorNumber` int(11) DEFAULT NULL,
  `Description` varchar(150) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `location`
--

INSERT INTO `location` (`LocationID`, `BuildingName`, `RoomNumber`, `FloorNumber`, `Description`) VALUES
(1, 'Admin Block', '101', 1, 'Main Office'),
(2, 'Science Block', 'B201', 2, 'Biology Lab'),
(3, 'Library', 'L05', 1, 'Reading Room'),
(4, 'Hostel A', 'H-204', 2, 'Student Hostel'),
(5, 'Computer Lab', 'C-301', 3, 'CS Lab'),
(6, 'Cafeteria', 'CF-01', 0, 'Main Canteen'),
(7, 'Block A', '101', 2, 'Network Issue');

-- --------------------------------------------------------

--
-- Table structure for table `maintenancelogs`
--

CREATE TABLE `maintenancelogs` (
  `LogID` int(11) NOT NULL,
  `RequestID` int(11) NOT NULL,
  `StaffID` int(11) NOT NULL,
  `LogDate` datetime NOT NULL,
  `LogDetails` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `maintenancelogs`
--

INSERT INTO `maintenancelogs` (`LogID`, `RequestID`, `StaffID`, `LogDate`, `LogDetails`) VALUES
(4, 4, 4, '2025-12-27 07:48:04', 'Assigned to StaffID=4 (Status: In Progress)'),
(5, 4, 4, '2025-12-27 07:48:16', 'Marked Completed (CompletionDate: 2025-12-27)'),
(8, 5, 4, '2025-12-29 07:15:06', 'Assigned to StaffID=4 (Status: In Progress)'),
(10, 6, 1, '2025-12-29 07:52:37', 'Assigned to StaffID=1 (Status: In Progress)'),
(11, 6, 1, '2025-12-29 07:53:08', 'Marked Completed (CompletionDate: 2025-12-29)');

-- --------------------------------------------------------

--
-- Table structure for table `maintenancerequest`
--

CREATE TABLE `maintenancerequest` (
  `RequestID` int(11) NOT NULL,
  `UserID` int(11) DEFAULT NULL,
  `IssueTypeID` int(11) NOT NULL,
  `LocationID` int(11) NOT NULL,
  `RequestDescription` varchar(255) DEFAULT NULL,
  `RequestDate` date NOT NULL,
  `Status` varchar(50) DEFAULT 'Pending',
  `AssignedStaffID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `maintenancerequest`
--

INSERT INTO `maintenancerequest` (`RequestID`, `UserID`, `IssueTypeID`, `LocationID`, `RequestDescription`, `RequestDate`, `Status`, `AssignedStaffID`) VALUES
(1, 1, 1, 1, 'AC not working in Admin', '2025-11-19', 'Completed', 1),
(2, 2, 2, 2, 'Water leak in Science Lab', '2025-11-18', 'Completed', 2),
(3, 1, 6, 5, 'Internet slow in CS Lab', '2025-11-20', 'Completed', NULL),
(4, NULL, 5, 4, '', '2025-12-27', 'Completed', 4),
(5, 1, 5, 1, 'Network Issue', '2025-12-29', 'In Progress', 4),
(6, 1, 6, 6, '', '2025-12-29', 'Completed', 1);

-- --------------------------------------------------------

--
-- Table structure for table `maintenancestaff`
--

CREATE TABLE `maintenancestaff` (
  `StaffID` int(11) NOT NULL,
  `StaffName` varchar(100) NOT NULL,
  `ContactNumber` varchar(20) DEFAULT NULL,
  `Email` varchar(100) DEFAULT NULL,
  `Role` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `maintenancestaff`
--

INSERT INTO `maintenancestaff` (`StaffID`, `StaffName`, `ContactNumber`, `Email`, `Role`) VALUES
(1, 'Ali Khan', '03001234567', 'ali@uni.edu', 'Electrician'),
(2, 'Sara Ahmed', '03007654321', 'sara@uni.edu', 'Plumber'),
(3, 'Usman Farooq', '03001110001', 'usman@uni.edu', 'Carpenter'),
(4, 'Bisma Raza', '1234567890', 'ma032@gmail.com', 'Electrician'),
(5, 'Fahad raza', '123456789', 'fahad@gmail.com', 'Electrician');

-- --------------------------------------------------------

--
-- Table structure for table `requestassignment`
--

CREATE TABLE `requestassignment` (
  `AssignmentID` int(11) NOT NULL,
  `RequestID` int(11) NOT NULL,
  `StaffID` int(11) NOT NULL,
  `AssignedDate` date NOT NULL,
  `CompletionDate` date DEFAULT NULL,
  `AssignmentStatus` varchar(50) DEFAULT 'Assigned'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `requestassignment`
--

INSERT INTO `requestassignment` (`AssignmentID`, `RequestID`, `StaffID`, `AssignedDate`, `CompletionDate`, `AssignmentStatus`) VALUES
(1, 4, 4, '2025-12-27', '2025-12-27', 'Completed'),
(2, 5, 4, '2025-12-29', NULL, 'Assigned'),
(3, 6, 1, '2025-12-29', '2025-12-29', 'Completed');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `UserID` int(11) NOT NULL,
  `UserName` varchar(100) NOT NULL,
  `Email` varchar(100) NOT NULL,
  `ContactNumber` varchar(20) DEFAULT NULL,
  `Role` varchar(50) DEFAULT 'Requester'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`UserID`, `UserName`, `Email`, `ContactNumber`, `Role`) VALUES
(1, 'Ahmed Ali', 'ahmed@uni.edu', '03001112233', 'Requester'),
(2, 'Fatima Khan', 'fatima@uni.edu', '03004445566', 'Requester'),
(3, 'Admin User', 'admin@uni.edu', '03007778899', 'Admin');

-- --------------------------------------------------------

--
-- Stand-in structure for view `vw_requestdetails`
-- (See below for the actual view)
--
CREATE TABLE `vw_requestdetails` (
`RequestID` int(11)
,`RequestDescription` varchar(255)
,`RequestDate` date
,`Status` varchar(50)
,`Requester` varchar(100)
,`IssueType` varchar(50)
,`Location` varchar(111)
,`AssignedStaff` varchar(100)
);

-- --------------------------------------------------------

--
-- Structure for view `vw_requestdetails`
--
DROP TABLE IF EXISTS `vw_requestdetails`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vw_requestdetails`  AS SELECT `r`.`RequestID` AS `RequestID`, `r`.`RequestDescription` AS `RequestDescription`, `r`.`RequestDate` AS `RequestDate`, `r`.`Status` AS `Status`, `u`.`UserName` AS `Requester`, `i`.`TypeName` AS `IssueType`, concat(`l`.`BuildingName`,' ',ifnull(`l`.`RoomNumber`,'')) AS `Location`, `ms`.`StaffName` AS `AssignedStaff` FROM ((((`maintenancerequest` `r` left join `users` `u` on(`r`.`UserID` = `u`.`UserID`)) left join `issuetype` `i` on(`r`.`IssueTypeID` = `i`.`IssueTypeID`)) left join `location` `l` on(`r`.`LocationID` = `l`.`LocationID`)) left join `maintenancestaff` `ms` on(`r`.`AssignedStaffID` = `ms`.`StaffID`)) ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `issuetype`
--
ALTER TABLE `issuetype`
  ADD PRIMARY KEY (`IssueTypeID`);

--
-- Indexes for table `location`
--
ALTER TABLE `location`
  ADD PRIMARY KEY (`LocationID`);

--
-- Indexes for table `maintenancelogs`
--
ALTER TABLE `maintenancelogs`
  ADD PRIMARY KEY (`LogID`),
  ADD KEY `RequestID` (`RequestID`),
  ADD KEY `StaffID` (`StaffID`);

--
-- Indexes for table `maintenancerequest`
--
ALTER TABLE `maintenancerequest`
  ADD PRIMARY KEY (`RequestID`),
  ADD KEY `UserID` (`UserID`),
  ADD KEY `IssueTypeID` (`IssueTypeID`),
  ADD KEY `LocationID` (`LocationID`),
  ADD KEY `AssignedStaffID` (`AssignedStaffID`);

--
-- Indexes for table `maintenancestaff`
--
ALTER TABLE `maintenancestaff`
  ADD PRIMARY KEY (`StaffID`);

--
-- Indexes for table `requestassignment`
--
ALTER TABLE `requestassignment`
  ADD PRIMARY KEY (`AssignmentID`),
  ADD KEY `RequestID` (`RequestID`),
  ADD KEY `StaffID` (`StaffID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`UserID`),
  ADD UNIQUE KEY `Email` (`Email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `issuetype`
--
ALTER TABLE `issuetype`
  MODIFY `IssueTypeID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `location`
--
ALTER TABLE `location`
  MODIFY `LocationID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `maintenancelogs`
--
ALTER TABLE `maintenancelogs`
  MODIFY `LogID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `maintenancerequest`
--
ALTER TABLE `maintenancerequest`
  MODIFY `RequestID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `maintenancestaff`
--
ALTER TABLE `maintenancestaff`
  MODIFY `StaffID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `requestassignment`
--
ALTER TABLE `requestassignment`
  MODIFY `AssignmentID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `UserID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `maintenancelogs`
--
ALTER TABLE `maintenancelogs`
  ADD CONSTRAINT `maintenancelogs_ibfk_1` FOREIGN KEY (`RequestID`) REFERENCES `maintenancerequest` (`RequestID`) ON DELETE CASCADE,
  ADD CONSTRAINT `maintenancelogs_ibfk_2` FOREIGN KEY (`StaffID`) REFERENCES `maintenancestaff` (`StaffID`);

--
-- Constraints for table `maintenancerequest`
--
ALTER TABLE `maintenancerequest`
  ADD CONSTRAINT `maintenancerequest_ibfk_1` FOREIGN KEY (`UserID`) REFERENCES `users` (`UserID`) ON DELETE SET NULL,
  ADD CONSTRAINT `maintenancerequest_ibfk_2` FOREIGN KEY (`IssueTypeID`) REFERENCES `issuetype` (`IssueTypeID`),
  ADD CONSTRAINT `maintenancerequest_ibfk_3` FOREIGN KEY (`LocationID`) REFERENCES `location` (`LocationID`),
  ADD CONSTRAINT `maintenancerequest_ibfk_4` FOREIGN KEY (`AssignedStaffID`) REFERENCES `maintenancestaff` (`StaffID`);

--
-- Constraints for table `requestassignment`
--
ALTER TABLE `requestassignment`
  ADD CONSTRAINT `requestassignment_ibfk_1` FOREIGN KEY (`RequestID`) REFERENCES `maintenancerequest` (`RequestID`) ON DELETE CASCADE,
  ADD CONSTRAINT `requestassignment_ibfk_2` FOREIGN KEY (`StaffID`) REFERENCES `maintenancestaff` (`StaffID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
