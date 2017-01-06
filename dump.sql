-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: 2016 年 12 朁E05 日 14:14
-- サーバのバージョン： 10.1.19-MariaDB
-- PHP Version: 5.6.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `flyby`
--

-- --------------------------------------------------------

--
-- テーブルの構造 `game`
--

CREATE TABLE `game` (
  `id` int(10) NOT NULL,
  `first_player_name` varchar(20) DEFAULT NULL,
  `second_player_name` varchar(20) DEFAULT NULL,
  `description` text,
  `epoch` int(10) DEFAULT NULL,
  `winner` tinyint(5) DEFAULT NULL,
  `start_time` datetime DEFAULT NULL,
  `end_time` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='局テーブル';

-- --------------------------------------------------------

--
-- テーブルの構造 `game_record`
--

CREATE TABLE `game_record` (
  `id` int(10) NOT NULL,
  `game_id` int(10) NOT NULL,
  `epoch` varchar(10) NOT NULL,
  `position` varchar(10) NOT NULL,
  `target` int(3) NOT NULL,
  `is_promotion` varchar(5) NOT NULL,
  `revival` tinyint(3) DEFAULT NULL,
  `kihu` text NOT NULL,
  `create_time` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `game`
--
ALTER TABLE `game`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `game_record`
--
ALTER TABLE `game_record`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `game`
--
ALTER TABLE `game`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `game_record`
--
ALTER TABLE `game_record`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
