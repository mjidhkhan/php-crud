-- Adminer 4.6.2 MySQL dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP DATABASE IF EXISTS `product`;
CREATE DATABASE `product` /*!40100 DEFAULT CHARACTER SET latin1 */;
USE `product`;

--
-- Database: `food`
--

-- --------------------------------------------------------

--
-- Table structure for table `sandwitch`
--

DROP TABLE IF EXISTS `product`;
CREATE TABLE `product` (
  `Id` int(11) NOT NULL,
  `Name` varchar(50) NOT NULL,
  `Category` text NOT NULL,
  `Description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `sandwitch`
--

INSERT INTO `product` (`Id`, `Name`, `Category`, `Description`) VALUES
(1, 'Turkey and cheese', 'Baguette', '4 turkey slices and 3 cheese slices'),
(2, 'chargrill chicken ', 'Oval bite', 'salad brown bread char-grill chicken and bacon '),
(3, 'jalfrexzi', 'Sub roll', 'soft bread Jalfrezi mixand salad ');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `sandwitch`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`Id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `sandwitch`
--
ALTER TABLE `product`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
