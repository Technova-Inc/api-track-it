--
-- Database: `database`
--

-- --------------------------------------------------------

--
-- Table structure for table `device`
--

CREATE TABLE `device` (
  `ID` int(11) NOT NULL,
  `Type` varchar(255) NOT NULL,
  `HardwareID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `drives`
--

CREATE TABLE `drives` (
  `ID` int(11) NOT NULL,
  `Letter` varchar(255) NOT NULL,
  `Type` int(255) NOT NULL,
  `Total` int(20) NOT NULL,
  `Free` int(20) NOT NULL,
  `HardwareID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hardware`
--

CREATE TABLE `hardware` (
  `ID` int(11) NOT NULL,
  `Name` varchar(255) NOT NULL,
  `os` varchar(255) NOT NULL,
  `osname` varchar(255) NOT NULL,
  `architecture` varchar(255) NOT NULL,
  `user` varchar(255) NOT NULL,
  `ram` int(11) NOT NULL,
  `cpu` varchar(255) NOT NULL,
  `serial` int(11) NOT NULL,
  `mac` varchar(255) NOT NULL,
  `ip` varchar(255) NOT NULL,
  `domain` varchar(255) NOT NULL,
  `windowskey` varchar(255) NOT NULL,
  `licensestatus` varchar(255) NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `Note` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `networks`
--

CREATE TABLE `networks` (
  `ID` int(16) NOT NULL,
  `Description` varchar(255) NOT NULL,
  `Type` varchar(255) NOT NULL,
  `Speed` varchar(255) NOT NULL,
  `MACADDR` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL,
  `IPADDR` varchar(255) NOT NULL,
  `IPmask` varchar(255) NOT NULL,
  `IPgateway` varchar(255) NOT NULL,
  `Ipsubnet` varchar(255) NOT NULL,
  `IPDHCP` varchar(255) NOT NULL,
  `HardwareID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `room`
--

CREATE TABLE `room` (
  `ID` int(11) NOT NULL,
  `Tag` int(11) NOT NULL,
  `HardwareID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `software`
--

CREATE TABLE `software` (
  `ID` int(11) NOT NULL,
  `Name` varchar(255) NOT NULL,
  `Language` varchar(255) NOT NULL,
  `InstallDate` date NOT NULL,
  `Bit` varchar(255) NOT NULL,
  `HardwareID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `ID` int(11) NOT NULL,
  `Login` varchar(255) NOT NULL,
  `MDP` varchar(255) NOT NULL,
  `Type` varchar(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `device`
--
ALTER TABLE `device`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `fk_device_hardware` (`HardwareID`);

--
-- Indexes for table `drives`
--
ALTER TABLE `drives`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `fk_drive_hardware` (`HardwareID`);

--
-- Indexes for table `hardware`
--
ALTER TABLE `hardware`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `networks`
--
ALTER TABLE `networks`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `fk_networks_hardware` (`HardwareID`);

--
-- Indexes for table `room`
--
ALTER TABLE `room`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `fk_room_hardware` (`HardwareID`);

--
-- Indexes for table `software`
--
ALTER TABLE `software`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `fk_software_hardware` (`HardwareID`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `device`
--
ALTER TABLE `device`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `drives`
--
ALTER TABLE `drives`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hardware`
--
ALTER TABLE `hardware`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `networks`
--
ALTER TABLE `networks`
  MODIFY `ID` int(16) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `room`
--
ALTER TABLE `room`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `software`
--
ALTER TABLE `software`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `device`
--
ALTER TABLE `device`
  ADD CONSTRAINT `fk_device_hardware` FOREIGN KEY (`HardwareID`) REFERENCES `hardware` (`ID`);

--
-- Constraints for table `drives`
--
ALTER TABLE `drives`
  ADD CONSTRAINT `fk_drive_hardware` FOREIGN KEY (`HardwareID`) REFERENCES `hardware` (`ID`);

--
-- Constraints for table `networks`
--
ALTER TABLE `networks`
  ADD CONSTRAINT `fk_networks_hardware` FOREIGN KEY (`HardwareID`) REFERENCES `hardware` (`ID`);

--
-- Constraints for table `room`
--
ALTER TABLE `room`
  ADD CONSTRAINT `fk_room_hardware` FOREIGN KEY (`HardwareID`) REFERENCES `hardware` (`ID`);

--
-- Constraints for table `software`
--
ALTER TABLE `software`
  ADD CONSTRAINT `fk_software_hardware` FOREIGN KEY (`HardwareID`) REFERENCES `hardware` (`ID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
