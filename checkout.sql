-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 15, 2023 at 07:57 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `project`
--

-- --------------------------------------------------------

--
-- Table structure for table `checkout`
--

CREATE TABLE `checkout` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `item_name` varchar(255) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `image` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `order_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `checkout`
--

INSERT INTO `checkout` (`id`, `email`, `item_name`, `quantity`, `price`, `image`, `description`, `order_date`) VALUES
(49, 'sss@sss.com', 'Popcorn', 1, 7.50, '?PNG\r\n\Z\n\0\0\0\rIHDR\0\0?\0\0?\0\0\0??ߊ\0\0\0	pHYs\0\0?\0\0??+\0\0\0<tEXtComment\0xr:d:DAFwl91p2fE:31,j:8817327523590116339,t:23100810R??E\0\0?iTXtXML:com.adobe.xmp\0\0\0\0\0<x:xmpmeta xmlns:x=\'adobe:ns:meta/\'>\n        <rdf:RDF xmlns:rdf=\'http://www.w3.org/1999/02/22-rdf-sy', 'Complete your movie experience with our freshly popped butter Popcorn!', '2023-11-14 17:33:42'),
(50, 'ss@ss.ss', 'Popcorn', 1, 7.50, '?PNG\r\n\Z\n\0\0\0\rIHDR\0\0?\0\0?\0\0\0??ߊ\0\0\0	pHYs\0\0?\0\0??+\0\0\0<tEXtComment\0xr:d:DAFwl91p2fE:31,j:8817327523590116339,t:23100810R??E\0\0?iTXtXML:com.adobe.xmp\0\0\0\0\0<x:xmpmeta xmlns:x=\'adobe:ns:meta/\'>\n        <rdf:RDF xmlns:rdf=\'http://www.w3.org/1999/02/22-rdf-sy', 'Complete your movie experience with our freshly popped butter Popcorn!', '2023-11-15 06:29:53');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `checkout`
--
ALTER TABLE `checkout`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `checkout`
--
ALTER TABLE `checkout`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
