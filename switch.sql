-- phpMyAdmin SQL Dump
-- version 4.9.7
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Feb 07, 2023 at 05:59 PM
-- Server version: 10.3.32-MariaDB
-- PHP Version: 7.4.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `network`
--

-- --------------------------------------------------------

--
-- Table structure for table `first_floor_bedroom_switch_switch`
--

CREATE TABLE `first_floor_bedroom_switch_switch` (
  `port1_status` tinyint(1) DEFAULT NULL,
  `port2_status` tinyint(1) DEFAULT NULL,
  `port3_status` tinyint(1) NOT NULL,
  `port4_status` tinyint(1) NOT NULL,
  `port5_status` tinyint(1) NOT NULL,
  `port6_status` tinyint(1) NOT NULL,
  `port7_status` tinyint(1) NOT NULL,
  `port8_status` tinyint(1) NOT NULL,
  `port9_status` tinyint(1) NOT NULL,
  `port10_status` tinyint(1) NOT NULL,
  `port11_status` tinyint(1) NOT NULL,
  `port12_status` tinyint(1) NOT NULL,
  `port13_status` tinyint(1) NOT NULL,
  `port14_status` tinyint(1) NOT NULL,
  `port15_status` tinyint(1) NOT NULL,
  `port16_status` tinyint(1) NOT NULL,
  `port1_name` tinytext NOT NULL,
  `port2_name` tinytext NOT NULL,
  `port3_name` tinytext NOT NULL,
  `port4_name` tinytext NOT NULL,
  `port5_name` tinytext NOT NULL,
  `port6_name` tinytext NOT NULL,
  `port7_name` tinytext NOT NULL,
  `port8_name` tinytext NOT NULL,
  `port9_name` tinytext NOT NULL,
  `port10_name` tinytext NOT NULL,
  `port11_name` tinytext NOT NULL,
  `port12_name` tinytext NOT NULL,
  `port13_name` tinytext NOT NULL,
  `port14_name` tinytext NOT NULL,
  `port15_name` tinytext NOT NULL,
  `port16_name` tinytext NOT NULL,
  `port1_vlan` int(11) NOT NULL,
  `port2_vlan` int(11) NOT NULL,
  `port3_vlan` int(11) NOT NULL,
  `port4_vlan` int(11) NOT NULL,
  `port5_vlan` int(11) NOT NULL,
  `port6_vlan` int(11) NOT NULL,
  `port7_vlan` int(11) NOT NULL,
  `port8_vlan` int(11) NOT NULL,
  `port9_vlan` int(11) NOT NULL,
  `port10_vlan` int(11) NOT NULL,
  `port11_vlan` int(11) NOT NULL,
  `port12_vlan` int(11) NOT NULL,
  `port13_vlan` int(11) NOT NULL,
  `port14_vlan` int(11) NOT NULL,
  `port15_vlan` int(11) NOT NULL,
  `port16_vlan` int(11) NOT NULL,
  `fan1_speed` int(11) NOT NULL,
  `fan2_speed` int(11) NOT NULL,
  `mac_temp` int(11) NOT NULL,
  `phy_temp` int(11) NOT NULL,
  `uptime` tinytext NOT NULL,
  `name` tinytext NOT NULL,
  `serial` tinytext NOT NULL,
  `version` tinytext NOT NULL,
  `date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE current_timestamp(),
  `id` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `first_floor_bedroom_switch_switch`
--

INSERT INTO `first_floor_bedroom_switch_switch` (`port1_status`, `port2_status`, `port3_status`, `port4_status`, `port5_status`, `port6_status`, `port7_status`, `port8_status`, `port9_status`, `port10_status`, `port11_status`, `port12_status`, `port13_status`, `port14_status`, `port15_status`, `port16_status`, `port1_name`, `port2_name`, `port3_name`, `port4_name`, `port5_name`, `port6_name`, `port7_name`, `port8_name`, `port9_name`, `port10_name`, `port11_name`, `port12_name`, `port13_name`, `port14_name`, `port15_name`, `port16_name`, `port1_vlan`, `port2_vlan`, `port3_vlan`, `port4_vlan`, `port5_vlan`, `port6_vlan`, `port7_vlan`, `port8_vlan`, `port9_vlan`, `port10_vlan`, `port11_vlan`, `port12_vlan`, `port13_vlan`, `port14_vlan`, `port15_vlan`, `port16_vlan`, `fan1_speed`, `fan2_speed`, `mac_temp`, `phy_temp`, `uptime`, `name`, `serial`, `version`, `date`, `id`) VALUES
(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 'xg1', 'xg2', 'xg3', 'xg4', 'xg5', 'xg6', 'xg7', 'xg8', '', '', '', '', '', '', '', '', 1, 20, 20, 1, 11, 22, 11, 11, 1, 1, 1, 1, 1, 1, 1, 1, 3600, 0, 35, 38, '40:4:36:11.00', 'Switch-Bedroom_Closet', '4KN3837W801D1', '7.0.0.24', '2023-02-07 23:59:04', 0);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
