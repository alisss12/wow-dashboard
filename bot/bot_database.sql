-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 02, 2026 at 03:33 PM
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
-- Database: `bot_database`
--

-- --------------------------------------------------------

--
-- Table structure for table `discord_heroes`
--

CREATE TABLE `discord_heroes` (
  `id` int(11) NOT NULL,
  `discord_id` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `class` varchar(50) NOT NULL,
  `rank_level` varchar(3) NOT NULL,
  `realm` varchar(50) NOT NULL,
  `region` varchar(50) NOT NULL,
  `role` varchar(50) DEFAULT NULL,
  `active_spec_role` varchar(50) NOT NULL,
  `score_all` float DEFAULT NULL,
  `score_dps` float DEFAULT NULL,
  `score_healer` float DEFAULT NULL,
  `score_tank` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `discord_heroes`
--

INSERT INTO `discord_heroes` (`id`, `discord_id`, `name`, `class`, `rank_level`, `realm`, `region`, `role`, `active_spec_role`, `score_all`, `score_dps`, `score_healer`, `score_tank`) VALUES
(1, '1081147280180125757', 'Triggus', 'Hunter', '0', 'Drak\'thul', 'eu', 'Mail', 'DPS', 3062.4, 3062.4, 0, 0),
(3, '1081147280180125757', 'Stramhud', 'Paladin', '0', 'Tarren Mill', 'eu', 'Plate', 'DPS', 0, 0, 0, 0),
(4, '1081147280180125757', 'Rest', 'Mage', '0', 'Gorgonnash', 'us', 'Cloth', 'DPS', 0, 0, 0, 0),
(5, '1081147280180125757', 'Restõ', 'Shaman', '0', 'Ravencrest', 'eu', 'Mail', 'HEALING', 3213.7, 0, 3213.7, 0),
(14, '416360805748047872', 'Päin-236292039', 'Demon Hunter', '', 'Ravencrest', 'eu', 'Leather', 'DPS', 3816.3, 3816.3, 0, 2351.5),
(15, '882716317109616690', 'Dvol', 'Shaman', '', 'Ravencrest', 'eu', 'Mail', 'HEALING', 3581.4, 1932.5, 3581.4, 0),
(16, '1310961363937398854', 'Exuhunt', 'Hunter', '', 'Blackrock', 'eu', 'Mail', 'DPS', 3161.5, 3161.5, 0, 0),
(17, '1204001966062047232', 'Solodin', 'Paladin', '', 'Agamaggan', 'eu', 'Plate', 'DPS', 3947.7, 3947.7, 0, 0),
(18, '468519892459716609', 'Ironwi', 'Shaman', '', 'Kazzak', 'eu', 'Mail', 'HEALING', 3795.3, 3617.8, 3795.3, 0),
(19, '293025307827503105', 'Rythure', 'Warlock', '', 'Tarren Mill', 'eu', 'Cloth', 'DPS', 3492.5, 3492.5, 0, 0),
(20, '830746612551647272', 'Fightstance', 'Warrior', '', 'Silvermoon', 'eu', 'Plate', 'Tank', 3995.1, 2097.6, 0, 3995.1),
(21, '1156909641943502899', 'Canibal', 'Warrior', '', 'Kazzak', 'eu', 'Plate', 'Tank', 3845.3, 0, 0, 3845.3),
(22, '830746612551647272', 'Shockstance', 'Shaman', '', 'Kazzak', 'eu', 'Mail', 'HEALING', 3837.4, 661, 3837.4, 0),
(23, '830746612551647272', 'Darkstance', 'Demon Hunter', '', 'Kazzak', 'eu', 'Leather', 'DPS', 3823.1, 3823.1, 0, 3140.6),
(24, '522460101995659285', 'Wishtodie', 'Demon Hunter', '', 'Tarren Mill', 'eu', 'Leather', 'DPS', 3562.9, 3562.9, 0, 0),
(25, '497747175506444288', 'Holdk', 'Death Knight', '', 'Silvermoon', 'eu', 'Plate', 'DPS', 3847.2, 3847.2, 0, 0),
(26, '618344620652232735', 'Blackwár', 'Warrior', '', 'Kazzak', 'eu', 'Plate', 'Tank', 3967.2, 0, 0, 3967.2),
(27, '895632505170984970', 'Viizii', 'Shaman', '', 'Kazzak', 'eu', 'Mail', 'HEALING', 3729, 889.9, 3729, 0),
(28, '933445990835425390', 'Lrw', 'Shaman', '', 'Silvermoon', 'eu', 'Mail', 'HEALING', 3556.1, 2622.6, 3492.7, 0),
(30, '516941042872745994', 'Rwm', 'Evoker', '', 'Silvermoon', 'eu', 'Mail', 'DPS', 0, 0, 0, 0),
(31, '516941042872745994', 'Lrain', 'Shaman', '', 'Silvermoon', 'eu', 'Mail', 'HEALING', 3634.4, 0, 3634.4, 0),
(32, '479671442754109442', 'Gghunt', 'Hunter', '', 'Ravencrest', 'eu', 'Mail', 'DPS', 3694.1, 3694.1, 0, 0),
(33, '416946417278517260', 'Neysa', 'Demon Hunter', '', 'Tarren Mill', 'eu', 'Leather', 'DPS', 3760.9, 3760.9, 0, 1042),
(34, '1011123971787325470', 'Keshmeshi', 'Hunter', '', 'Kazzak', 'eu', 'Mail', 'DPS', 3623.3, 3623.3, 0, 0),
(35, '414794408269971477', 'Sushihunt', 'Hunter', '', 'Tarren Mill', 'eu', 'Mail', 'DPS', 4017, 4017, 0, 0),
(39, '419829274002849802', 'Palacute', 'Paladin', '', 'Kazzak', 'eu', 'Plate', 'DPS', 3789.6, 3789.6, 0, 3593),
(40, '306801549856997377', 'Grâve', 'Death Knight', '', 'Ravencrest', 'eu', 'Plate', 'DPS', 3891.7, 3891.7, 0, 0),
(41, '812435031715872828', 'Frosttwo', 'Death Knight', '', 'Kazzak', 'eu', 'Plate', 'DPS', 3894.4, 3894.4, 0, 0),
(42, '450671455634456576', 'Olaw', 'Shaman', '', 'Twisting Nether', 'eu', 'Mail', 'HEALING', 3845.8, 0, 3845.8, 0),
(43, '993424894538817646', 'Romeria', 'Paladin', '', 'Tarren Mill', 'eu', 'Plate', 'DPS', 3894.1, 3894.1, 0, 3199.6),
(44, '687954818873950216', 'Tormmund', 'Death Knight', '', 'Silvermoon', 'eu', 'Plate', 'DPS', 3974.8, 3974.8, 0, 0),
(45, '519048679827701775', 'Truegkiller', 'Hunter', '', 'Silvermoon', 'eu', 'Mail', 'DPS', 3994.2, 3994.2, 0, 0),
(46, '706999274709319761', 'Demonoxini', 'Demon Hunter', '', 'Silvermoon', 'eu', 'Leather', 'DPS', 3845.9, 3845.9, 0, 3028.1),
(48, '818904649594699798', 'Rasmusgodd', 'Shaman', '', 'Tarren Mill', 'eu', 'Mail', 'HEALING', 3849.7, 0, 3849.7, 0),
(49, '541955616114081792', 'Merliine', 'Mage', '', 'Draenor', 'eu', 'Cloth', 'DPS', 3503.3, 3503.3, 0, 0),
(50, '1074592382441504768', 'Bestshami', 'Shaman', '', 'Kazzak', 'eu', 'Mail', 'HEALING', 3702.8, 1459.3, 3702.8, 0),
(51, '433862183709966336', 'Brokensmilee', 'Death Knight', '', 'Kazzak', 'eu', 'Plate', 'DPS', 3849.7, 3849.7, 0, 0),
(53, '650364543310233611', 'Redwãter', 'Shaman', '', 'Silvermoon', 'eu', 'Mail', 'HEALING', 3781.6, 432.8, 3781.6, 0),
(54, '440596496329474057', 'Exà', 'Warrior', '', 'Silvermoon', 'eu', 'Plate', 'Tank', 3799.6, 0, 0, 3799.6),
(55, '638346191045656577', 'Nbmage', 'Mage', '', 'Doomhammer', 'eu', 'Cloth', 'DPS', 3545.9, 3545.9, 0, 0),
(56, '212557379382804480', 'Shalidh', 'Demon Hunter', '', 'Silvermoon', 'eu', 'Leather', 'DPS', 4034.2, 4034.2, 0, 1432.4),
(57, '212557379382804480', 'Shalihunt', 'Hunter', '', 'Silvermoon', 'eu', 'Mail', 'DPS', 3988.6, 3988.6, 0, 0),
(58, '715290838510469221', 'Angelheal', 'Shaman', '', 'Ravencrest', 'eu', 'Mail', 'HEALING', 3751.1, 0, 3751.1, 0),
(59, '378594307772383232', 'Diginity', 'Paladin', '', 'Tarren Mill', 'eu', 'Plate', 'Tank', 3859.6, 0, 0, 3859.6),
(60, '1310705338336546857', 'Choskon', 'Hunter', '', 'Kazzak', 'eu', 'Mail', 'DPS', 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `discord_users`
--

CREATE TABLE `discord_users` (
  `id` int(11) NOT NULL,
  `discord_id` varchar(255) NOT NULL,
  `faction` varchar(50) DEFAULT NULL,
  `region` varchar(50) DEFAULT NULL,
  `class` varchar(50) DEFAULT NULL,
  `active_spec_role` varchar(50) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `race` varchar(50) DEFAULT NULL,
  `realm` varchar(50) DEFAULT NULL,
  `score_all` float DEFAULT NULL,
  `score_dps` float DEFAULT NULL,
  `score_healer` float DEFAULT NULL,
  `score_tank` float DEFAULT NULL,
  `role` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `id` int(11) NOT NULL,
  `event_name` varchar(255) NOT NULL,
  `role` varchar(4) NOT NULL,
  `event_date` datetime NOT NULL,
  `captain_id` varchar(255) NOT NULL,
  `user_id` varchar(100) NOT NULL,
  `status` enum('waiting','accepted','declined') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`id`, `event_name`, `role`, `event_date`, `captain_id`, `user_id`, `status`) VALUES
(1, '  lalame', 'DPS', '0000-00-00 00:00:00', '1081147280180125757', '1081147280180125757', 'waiting');

-- --------------------------------------------------------

--
-- Table structure for table `event_participants`
--

CREATE TABLE `event_participants` (
  `id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `event_registrations`
--

CREATE TABLE `event_registrations` (
  `id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `user_id` varchar(255) NOT NULL,
  `status` enum('accepted','declined') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `mplus_channel_logs`
--

CREATE TABLE `mplus_channel_logs` (
  `id` bigint(20) NOT NULL,
  `order_id` varchar(20) DEFAULT NULL,
  `channel_id` varchar(20) DEFAULT NULL,
  `message_id` varchar(20) DEFAULT NULL,
  `author_id` varchar(20) DEFAULT NULL,
  `author_username` varchar(100) DEFAULT NULL,
  `author_avatar` varchar(255) DEFAULT NULL,
  `content` text DEFAULT NULL,
  `timestamp` datetime DEFAULT NULL,
  `attachments` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`attachments`)),
  `embeds` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`embeds`)),
  `reactions` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`reactions`))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `mplus_channel_logs`
--

INSERT INTO `mplus_channel_logs` (`id`, `order_id`, `channel_id`, `message_id`, `author_id`, `author_username`, `author_avatar`, `content`, `timestamp`, `attachments`, `embeds`, `reactions`) VALUES
(1, NULL, '1453680012799770665', '1453680399313145918', '1081147280180125757', 'aliss12_', 'https://cdn.discordapp.com/avatars/1081147280180125757/94a0cd79ccdc45817754153e6904bf20.webp?size=128', '!done', '2025-12-25 12:56:55', '[]', '[]', '[]'),
(2, NULL, '1453680012799770665', '1453680075957342232', '1281884910260453416', 'testbot', 'https://cdn.discordapp.com/embed/avatars/0.png', 'An error occurred while processing `!done`.', '2025-12-25 12:55:38', '[]', '[]', '[]'),
(3, NULL, '1453680012799770665', '1453680073730293852', '1081147280180125757', 'aliss12_', 'https://cdn.discordapp.com/avatars/1081147280180125757/94a0cd79ccdc45817754153e6904bf20.webp?size=128', '!done', '2025-12-25 12:55:37', '[]', '[]', '[]'),
(4, NULL, '1453680012799770665', '1453680061486989403', '1081147280180125757', 'aliss12_', 'https://cdn.discordapp.com/avatars/1081147280180125757/94a0cd79ccdc45817754153e6904bf20.webp?size=128', 'mmmma', '2025-12-25 12:55:34', '[]', '[]', '[]'),
(5, NULL, '1453680012799770665', '1453680050695311381', '1081147280180125757', 'aliss12_', 'https://cdn.discordapp.com/avatars/1081147280180125757/94a0cd79ccdc45817754153e6904bf20.webp?size=128', 'baba', '2025-12-25 12:55:32', '[]', '[]', '[]'),
(6, NULL, '1453680012799770665', '1453680042507899012', '1081147280180125757', 'aliss12_', 'https://cdn.discordapp.com/avatars/1081147280180125757/94a0cd79ccdc45817754153e6904bf20.webp?size=128', 'lala', '2025-12-25 12:55:30', '[]', '[]', '[]'),
(7, NULL, '1453680012799770665', '1453680014821429380', '1281884910260453416', 'testbot', 'https://cdn.discordapp.com/embed/avatars/0.png', 'Congratulations <@1081147280180125757>! You have been selected!', '2025-12-25 12:55:23', '[]', '[{\"type\":\"rich\",\"title\":\"M+ Boost Assigned!\",\"color\":65280,\"timestamp\":\"2025-12-25T09:25:23.676000+00:00\",\"fields\":[{\"name\":\"Region\",\"value\":\"EU\",\"inline\":true},{\"name\":\"M+\",\"value\":\"34\",\"inline\":true},{\"name\":\"Armor Stack\",\"value\":\"3423\",\"inline\":true},{\"name\":\"Key\",\"value\":\"25\",\"inline\":true},{\"name\":\"Cut\",\"value\":\"324\",\"inline\":true},{\"name\":\"Additional Notes\",\"value\":\"234\",\"inline\":false},{\"name\":\"Customer\",\"value\":\"<@1081147280180125757>\",\"inline\":false},{\"name\":\"Booster\",\"value\":\"<@1081147280180125757>\",\"inline\":false}],\"content_scan_version\":3}]', '[]'),
(8, NULL, '1453755272563527701', '1453756093531558122', '1330425874142724167', 'bablogod', 'https://cdn.discordapp.com/avatars/1330425874142724167/811e816928b0dd9b794320ba609a670b.webp?size=128', '!done', '2025-12-25 17:57:42', '[]', '[]', '[]'),
(9, NULL, '1453755272563527701', '1453755756536004731', '1330425874142724167', 'bablogod', 'https://cdn.discordapp.com/avatars/1330425874142724167/811e816928b0dd9b794320ba609a670b.webp?size=128', 'ok mrc', '2025-12-25 17:56:21', '[]', '[]', '[]'),
(10, NULL, '1453755272563527701', '1453755727503167558', '868412471214493716', 'nami44671', 'https://cdn.discordapp.com/avatars/868412471214493716/0fcd11d69a16509111c620627a780c81.webp?size=128', 'چند بار', '2025-12-25 17:56:14', '[]', '[]', '[]'),
(11, NULL, '1453755272563527701', '1453755710604316746', '868412471214493716', 'nami44671', 'https://cdn.discordapp.com/avatars/868412471214493716/0fcd11d69a16509111c620627a780c81.webp?size=128', 'رفتیم', '2025-12-25 17:56:10', '[]', '[]', '[]'),
(12, NULL, '1453755272563527701', '1453755707198537768', '868412471214493716', 'nami44671', 'https://cdn.discordapp.com/avatars/868412471214493716/0fcd11d69a16509111c620627a780c81.webp?size=128', 'ی ران دگ هست عزیزم برای این یارو', '2025-12-25 17:56:10', '[]', '[]', '[]'),
(13, NULL, '1453755272563527701', '1453755679662805176', '1330425874142724167', 'bablogod', 'https://cdn.discordapp.com/avatars/1330425874142724167/811e816928b0dd9b794320ba609a670b.webp?size=128', 'age mishe', '2025-12-25 17:56:03', '[]', '[]', '[]'),
(14, NULL, '1453755272563527701', '1453755648595591188', '1330425874142724167', 'bablogod', 'https://cdn.discordapp.com/avatars/1330425874142724167/811e816928b0dd9b794320ba609a670b.webp?size=128', 'try bedin kho', '2025-12-25 17:55:56', '[]', '[]', '[]'),
(15, NULL, '1453755272563527701', '1453755605868351489', '868412471214493716', 'nami44671', 'https://cdn.discordapp.com/avatars/868412471214493716/0fcd11d69a16509111c620627a780c81.webp?size=128', 'is bd for hoa', '2025-12-25 17:55:45', '[]', '[]', '[]'),
(16, NULL, '1453755272563527701', '1453755594183016613', '868412471214493716', 'nami44671', 'https://cdn.discordapp.com/avatars/868412471214493716/0fcd11d69a16509111c620627a780c81.webp?size=128', 'mage', '2025-12-25 17:55:43', '[]', '[]', '[]'),
(17, NULL, '1453755272563527701', '1453755591854919802', '868412471214493716', 'nami44671', 'https://cdn.discordapp.com/avatars/868412471214493716/0fcd11d69a16509111c620627a780c81.webp?size=128', 'no bro', '2025-12-25 17:55:42', '[]', '[]', '[]'),
(18, NULL, '1453755272563527701', '1453755555029192756', '1330425874142724167', 'bablogod', 'https://cdn.discordapp.com/avatars/1330425874142724167/811e816928b0dd9b794320ba609a670b.webp?size=128', 'okeye?', '2025-12-25 17:55:33', '[]', '[]', '[]'),
(19, NULL, '1453755272563527701', '1453755507818107093', '1330425874142724167', 'bablogod', 'https://cdn.discordapp.com/avatars/1330425874142724167/811e816928b0dd9b794320ba609a670b.webp?size=128', 'mage', '2025-12-25 17:55:22', '[]', '[]', '[]'),
(20, NULL, '1453755272563527701', '1453755500897505350', '1330425874142724167', 'bablogod', 'https://cdn.discordapp.com/avatars/1330425874142724167/811e816928b0dd9b794320ba609a670b.webp?size=128', 'hi', '2025-12-25 17:55:20', '[]', '[]', '[]'),
(21, NULL, '1453755272563527701', '1453755361818575015', '868412471214493716', 'nami44671', 'https://cdn.discordapp.com/avatars/868412471214493716/0fcd11d69a16509111c620627a780c81.webp?size=128', 'name', '2025-12-25 17:54:47', '[]', '[]', '[]'),
(22, NULL, '1453755272563527701', '1453755359201202259', '868412471214493716', 'nami44671', 'https://cdn.discordapp.com/avatars/868412471214493716/0fcd11d69a16509111c620627a780c81.webp?size=128', 'yo', '2025-12-25 17:54:47', '[]', '[]', '[]'),
(23, NULL, '1453755272563527701', '1453755274065219676', '1450064117804040275', 'G3Boost', 'https://cdn.discordapp.com/embed/avatars/1.png', 'Congratulations <@868412471214493716>! You have been selected!', '2025-12-25 17:54:26', '[]', '[{\"type\":\"rich\",\"title\":\"M+ Boost Assigned!\",\"color\":65280,\"timestamp\":\"2025-12-25T14:24:27.048000+00:00\",\"fields\":[{\"name\":\"Region\",\"value\":\"EU\",\"inline\":true},{\"name\":\"M+\",\"value\":\"1x21\",\"inline\":true},{\"name\":\"Armor Stack\",\"value\":\"any\",\"inline\":true},{\"name\":\"Key\",\"value\":\"halls\",\"inline\":true},{\"name\":\"Cut\",\"value\":\"7t\",\"inline\":true},{\"name\":\"Additional Notes\",\"value\":\"-\",\"inline\":false},{\"name\":\"Customer\",\"value\":\"<@1330425874142724167>\",\"inline\":false},{\"name\":\"Booster\",\"value\":\"<@868412471214493716>\",\"inline\":false}],\"content_scan_version\":3}]', '[]'),
(24, NULL, '1454062910790963343', '1454398860418879559', '1330425874142724167', 'bablogod', 'https://cdn.discordapp.com/avatars/1330425874142724167/811e816928b0dd9b794320ba609a670b.webp?size=128', '!done', '2025-12-27 12:31:49', '[]', '[]', '[]'),
(25, NULL, '1454062910790963343', '1454072689588310209', '468519892459716609', 'bahamut.s', 'https://cdn.discordapp.com/avatars/468519892459716609/bbe31d83dcdee60ba3a7dd52bbdb32b8.webp?size=128', '<@1330425874142724167>  done shod', '2025-12-26 14:55:44', '[]', '[]', '[]'),
(26, NULL, '1454062910790963343', '1454065867662037074', '1330425874142724167', 'bablogod', 'https://cdn.discordapp.com/avatars/1330425874142724167/811e816928b0dd9b794320ba609a670b.webp?size=128', 'inja dadam', '2025-12-26 14:28:38', '[]', '[]', '[]'),
(27, NULL, '1454062910790963343', '1454065855074664573', '1330425874142724167', 'bablogod', 'https://cdn.discordapp.com/avatars/1330425874142724167/811e816928b0dd9b794320ba609a670b.webp?size=128', 'https://discord.com/channels/@me/1454063020886982656/1454065636832444556', '2025-12-26 14:28:35', '[]', '[]', '[]'),
(28, NULL, '1454062910790963343', '1454065783188754483', '468519892459716609', 'bahamut.s', 'https://cdn.discordapp.com/avatars/468519892459716609/bbe31d83dcdee60ba3a7dd52bbdb32b8.webp?size=128', 'info bede plz berim', '2025-12-26 14:28:17', '[]', '[]', '[]'),
(29, NULL, '1454062910790963343', '1454065588254015509', '1330425874142724167', 'bablogod', 'https://cdn.discordapp.com/avatars/1330425874142724167/811e816928b0dd9b794320ba609a670b.webp?size=128', 'are', '2025-12-26 14:27:31', '[]', '[]', '[]'),
(30, NULL, '1454062910790963343', '1454064755592396941', '468519892459716609', 'bahamut.s', 'https://cdn.discordapp.com/avatars/468519892459716609/bbe31d83dcdee60ba3a7dd52bbdb32b8.webp?size=128', 'kharid?', '2025-12-26 14:24:12', '[]', '[]', '[]'),
(31, NULL, '1454062910790963343', '1454063846254841928', '1330425874142724167', 'bablogod', 'https://cdn.discordapp.com/avatars/1330425874142724167/811e816928b0dd9b794320ba609a670b.webp?size=128', 'info midam', '2025-12-26 14:20:36', '[]', '[]', '[]'),
(32, NULL, '1454062910790963343', '1454063834368311336', '1330425874142724167', 'bablogod', 'https://cdn.discordapp.com/avatars/1330425874142724167/811e816928b0dd9b794320ba609a670b.webp?size=128', 'alan mikhare', '2025-12-26 14:20:33', '[]', '[]', '[]'),
(33, NULL, '1454062910790963343', '1454063745058869288', '468519892459716609', 'bahamut.s', 'https://cdn.discordapp.com/avatars/468519892459716609/bbe31d83dcdee60ba3a7dd52bbdb32b8.webp?size=128', 'y', '2025-12-26 14:20:12', '[]', '[]', '[]'),
(34, NULL, '1454062910790963343', '1454063704545951896', '1330425874142724167', 'bablogod', 'https://cdn.discordapp.com/avatars/1330425874142724167/811e816928b0dd9b794320ba609a670b.webp?size=128', 'ready?', '2025-12-26 14:20:02', '[]', '[]', '[]'),
(35, NULL, '1454062910790963343', '1454063380599017478', '468519892459716609', 'bahamut.s', 'https://cdn.discordapp.com/avatars/468519892459716609/bbe31d83dcdee60ba3a7dd52bbdb32b8.webp?size=128', 'salam', '2025-12-26 14:18:45', '[]', '[]', '[]'),
(36, NULL, '1454062910790963343', '1454063358775922730', '1330425874142724167', 'bablogod', 'https://cdn.discordapp.com/avatars/1330425874142724167/811e816928b0dd9b794320ba609a670b.webp?size=128', 'slm\\', '2025-12-26 14:18:39', '[]', '[]', '[]'),
(37, NULL, '1454062910790963343', '1454062912468684802', '1450064117804040275', 'G3Boost', 'https://cdn.discordapp.com/embed/avatars/1.png', 'Congratulations <@468519892459716609>! You have been selected!', '2025-12-26 14:16:53', '[]', '[{\"type\":\"rich\",\"title\":\"M+ Boost Assigned!\",\"color\":65280,\"timestamp\":\"2025-12-26T10:46:53.685000+00:00\",\"fields\":[{\"name\":\"Region\",\"value\":\"EU\",\"inline\":true},{\"name\":\"M+\",\"value\":\"1x15\",\"inline\":true},{\"name\":\"Armor Stack\",\"value\":\"any\",\"inline\":true},{\"name\":\"Key\",\"value\":\"ara dawn\",\"inline\":true},{\"name\":\"Cut\",\"value\":\"330\",\"inline\":true},{\"name\":\"Additional Notes\",\"value\":\"-\",\"inline\":false},{\"name\":\"Customer\",\"value\":\"<@1330425874142724167>\",\"inline\":false},{\"name\":\"Booster\",\"value\":\"<@468519892459716609>\",\"inline\":false}],\"content_scan_version\":3}]', '[]'),
(38, NULL, '1453697284167700545', '1454398933794029723', '1330425874142724167', 'bablogod', 'https://cdn.discordapp.com/avatars/1330425874142724167/811e816928b0dd9b794320ba609a670b.webp?size=128', '!done', '2025-12-27 12:32:07', '[]', '[]', '[]'),
(39, NULL, '1453697284167700545', '1453755811040854189', '1330425874142724167', 'bablogod', 'https://cdn.discordapp.com/avatars/1330425874142724167/811e816928b0dd9b794320ba609a670b.webp?size=128', 'add shud', '2025-12-25 17:56:34', '[\"https://cdn.discordapp.com/attachments/1453697284167700545/1453755811049373849/image.png?ex=6950955a&is=694f43da&hm=08688e336e8d2637b8006fc62016681fd1f19474794c6434f8f2d770fd648b83&\"]', '[]', '[]'),
(40, NULL, '1453697284167700545', '1453708181900628090', '650364543310233611', 'esmail5100', 'https://cdn.discordapp.com/avatars/650364543310233611/a3be28988e6057bc27dedb6faebd95fc.webp?size=128', NULL, '2025-12-25 14:47:19', '[\"https://cdn.discordapp.com/attachments/1453697284167700545/1453708181413957642/WoWScrnShot_122525_144350.jpg?ex=695068ff&is=694f177f&hm=ffea16bb60b16b52b6afbba0dd2cec62034a14163e854d8545f6e618ff5be546&\"]', '[]', '[]'),
(41, NULL, '1453697284167700545', '1453697410470510602', '650364543310233611', 'esmail5100', 'https://cdn.discordapp.com/avatars/650364543310233611/a3be28988e6057bc27dedb6faebd95fc.webp?size=128', 'ty', '2025-12-25 14:04:31', '[]', '[]', '[{\"emoji\":\"❤️\",\"count\":1}]'),
(42, NULL, '1453697284167700545', '1453697399351541832', '1330425874142724167', 'bablogod', 'https://cdn.discordapp.com/avatars/1330425874142724167/811e816928b0dd9b794320ba609a670b.webp?size=128', 'char: Solorwp-Blackhand   btag: Myra#21229\n\n1x18 street\n47995072\n800t\ndone', '2025-12-25 14:04:28', '[]', '[]', '[]'),
(43, NULL, '1453697284167700545', '1453697329696608420', '650364543310233611', 'esmail5100', 'https://cdn.discordapp.com/avatars/650364543310233611/a3be28988e6057bc27dedb6faebd95fc.webp?size=128', 'dorood', '2025-12-25 14:04:11', '[]', '[]', '[]'),
(44, NULL, '1453697284167700545', '1453697306598838342', '1330425874142724167', 'bablogod', 'https://cdn.discordapp.com/avatars/1330425874142724167/811e816928b0dd9b794320ba609a670b.webp?size=128', 'hi', '2025-12-25 14:04:06', '[]', '[]', '[]'),
(45, NULL, '1453697284167700545', '1453697286034161737', '1450064117804040275', 'G3Boost', 'https://cdn.discordapp.com/embed/avatars/1.png', 'Congratulations <@650364543310233611>! You have been selected!', '2025-12-25 14:04:01', '[]', '[{\"type\":\"rich\",\"title\":\"M+ Boost Assigned!\",\"color\":65280,\"timestamp\":\"2025-12-25T10:34:01.603000+00:00\",\"fields\":[{\"name\":\"Region\",\"value\":\"EU\",\"inline\":true},{\"name\":\"M+\",\"value\":\"1x18\",\"inline\":true},{\"name\":\"Armor Stack\",\"value\":\"any\",\"inline\":true},{\"name\":\"Key\",\"value\":\"street\",\"inline\":true},{\"name\":\"Cut\",\"value\":\"800\",\"inline\":true},{\"name\":\"Additional Notes\",\"value\":\"-\",\"inline\":false},{\"name\":\"Customer\",\"value\":\"<@1330425874142724167>\",\"inline\":false},{\"name\":\"Booster\",\"value\":\"<@650364543310233611>\",\"inline\":false}],\"content_scan_version\":3}]', '[]'),
(46, NULL, '1454425289026437336', '1454485936569061537', '1330425874142724167', 'bablogod', 'https://cdn.discordapp.com/avatars/1330425874142724167/811e816928b0dd9b794320ba609a670b.webp?size=128', '!done', '2025-12-27 18:17:50', '[]', '[]', '[]'),
(47, NULL, '1454425289026437336', '1454426091694329886', '431892711650623488', 'mamadshadow', 'https://cdn.discordapp.com/avatars/431892711650623488/80bd0e89b781d280ec3ded474954a47d.webp?size=128', '<@1330425874142724167>Invesh bedam?', '2025-12-27 14:20:02', '[]', '[]', '[]'),
(48, NULL, '1454425289026437336', '1454425505481752736', '431892711650623488', 'mamadshadow', 'https://cdn.discordapp.com/avatars/431892711650623488/80bd0e89b781d280ec3ded474954a47d.webp?size=128', 'YO', '2025-12-27 14:17:42', '[]', '[]', '[]'),
(49, NULL, '1454425289026437336', '1454425291270394007', '1450064117804040275', 'G3Boost', 'https://cdn.discordapp.com/embed/avatars/1.png', 'Congratulations <@431892711650623488>! You have been selected!', '2025-12-27 14:16:51', '[]', '[{\"type\":\"rich\",\"title\":\"M+ Boost Assigned!\",\"color\":65280,\"timestamp\":\"2025-12-27T10:46:51.382000+00:00\",\"fields\":[{\"name\":\"Region\",\"value\":\"EU\",\"inline\":true},{\"name\":\"M+\",\"value\":\"1x20\",\"inline\":true},{\"name\":\"Armor Stack\",\"value\":\"any\",\"inline\":true},{\"name\":\"Key\",\"value\":\"psf\",\"inline\":true},{\"name\":\"Cut\",\"value\":\"4400\",\"inline\":true},{\"name\":\"Additional Notes\",\"value\":\"-\",\"inline\":false},{\"name\":\"Customer\",\"value\":\"<@1330425874142724167>\",\"inline\":false},{\"name\":\"Booster\",\"value\":\"<@431892711650623488>\",\"inline\":false}],\"content_scan_version\":3}]', '[]'),
(50, NULL, '1454742321806704796', '1454765711699214508', '1310961363937398854', 'norozigod', 'https://cdn.discordapp.com/avatars/1310961363937398854/26f760a33854acedf241e9455bd5def1.webp?size=128', '!done', '2025-12-28 12:49:33', '[]', '[]', '[]'),
(51, NULL, '1454742321806704796', '1454764840760377406', '1310961363937398854', 'norozigod', 'https://cdn.discordapp.com/avatars/1310961363937398854/26f760a33854acedf241e9455bd5def1.webp?size=128', 'ok', '2025-12-28 12:46:06', '[]', '[]', '[]'),
(52, NULL, '1454742321806704796', '1454764611734863975', '440596496329474057', 'elish5575', 'https://cdn.discordapp.com/avatars/440596496329474057/4bedd44b192c7277bad5474a3d5277f5.webp?size=128', 'yo 2x17 done shod', '2025-12-28 12:45:11', '[]', '[]', '[]'),
(53, NULL, '1454742321806704796', '1454742838523986011', '440596496329474057', 'elish5575', 'https://cdn.discordapp.com/avatars/440596496329474057/4bedd44b192c7277bad5474a3d5277f5.webp?size=128', 'slm ok', '2025-12-28 11:18:40', '[]', '[]', '[]'),
(54, NULL, '1454742321806704796', '1454742767430406164', '1310961363937398854', 'norozigod', 'https://cdn.discordapp.com/avatars/1310961363937398854/26f760a33854acedf241e9455bd5def1.webp?size=128', 'https://raider.io/characters/eu/blackrock/Exuhunt\nkenny#25880\n\n23582342\n2x +17 \ncut : 700 t', '2025-12-28 11:18:23', '[]', '[{\"type\":\"link\",\"url\":\"https://raider.io/characters/eu/blackrock/Exuhunt\",\"title\":\"World of Warcraft Rankings for Mythic+ and Raid Progress\",\"color\":16760074,\"provider\":{\"name\":\"Raider.IO\"},\"thumbnail\":{\"url\":\"http://cdn.raiderio.net/images/fb_app_image.jpg\",\"proxy_url\":\"https://images-ext-1.discordapp.net/external/8SIKYt2IQIH4cr1HEaMGdGYluRdrPMEtSAwA0xIgbvQ/http/cdn.raiderio.net/images/fb_app_image.jpg\",\"width\":1024,\"height\":1024,\"content_type\":\"image/jpeg\",\"placeholder\":\"iBgKHwQIiIeHh3hzeleXV5eKdcAoCIsB\",\"placeholder_version\":1,\"flags\":0},\"content_scan_version\":3}]', '[{\"emoji\":\"⌛\",\"count\":1},{\"emoji\":\"✅\",\"count\":1}]'),
(55, NULL, '1454742321806704796', '1454742324591464560', '1450064117804040275', 'G3Boost', 'https://cdn.discordapp.com/embed/avatars/1.png', 'Congratulations <@440596496329474057>! You have been selected!', '2025-12-28 11:16:37', '[]', '[{\"type\":\"rich\",\"title\":\"M+ Boost Assigned!\",\"color\":65280,\"timestamp\":\"2025-12-28T07:46:38.172000+00:00\",\"fields\":[{\"name\":\"Region\",\"value\":\"EU\",\"inline\":true},{\"name\":\"M+\",\"value\":\"1x +17\",\"inline\":true},{\"name\":\"Armor Stack\",\"value\":\"-\",\"inline\":true},{\"name\":\"Key\",\"value\":\"any not Gambit DB Streets\",\"inline\":true},{\"name\":\"Cut\",\"value\":\"700\",\"inline\":true},{\"name\":\"Additional Notes\",\"value\":\"-\",\"inline\":false},{\"name\":\"Customer\",\"value\":\"<@1310961363937398854>\",\"inline\":false},{\"name\":\"Booster\",\"value\":\"<@440596496329474057>\",\"inline\":false}],\"content_scan_version\":3}]', '[]'),
(56, NULL, '1454777556472434772', '1454800818443124803', '1310961363937398854', 'norozigod', 'https://cdn.discordapp.com/avatars/1310961363937398854/26f760a33854acedf241e9455bd5def1.webp?size=128', '!done', '2025-12-28 15:09:04', '[]', '[]', '[]'),
(57, NULL, '1454777556472434772', '1454799075084861450', '497747175506444288', 'erch2294', 'https://cdn.discordapp.com/avatars/497747175506444288/5f47c46c95cd7cac3e90168bfd7e4f9b.webp?size=128', 'done shod 2x +16 <@1310961363937398854>', '2025-12-28 15:02:08', '[]', '[]', '[]'),
(58, NULL, '1454777556472434772', '1454777558473244775', '1450064117804040275', 'G3Boost', 'https://cdn.discordapp.com/embed/avatars/1.png', 'Congratulations <@497747175506444288>! You have been selected!', '2025-12-28 13:36:38', '[]', '[{\"type\":\"rich\",\"title\":\"M+ Boost Assigned!\",\"color\":65280,\"timestamp\":\"2025-12-28T10:06:38.520000+00:00\",\"fields\":[{\"name\":\"Region\",\"value\":\"EU\",\"inline\":true},{\"name\":\"M+\",\"value\":\"2x +16\",\"inline\":true},{\"name\":\"Armor Stack\",\"value\":\"-\",\"inline\":true},{\"name\":\"Key\",\"value\":\"db flood psf\",\"inline\":true},{\"name\":\"Cut\",\"value\":\"400\",\"inline\":true},{\"name\":\"Additional Notes\",\"value\":\"-\",\"inline\":false},{\"name\":\"Customer\",\"value\":\"<@1310961363937398854>\",\"inline\":false},{\"name\":\"Booster\",\"value\":\"<@497747175506444288>\",\"inline\":false}],\"content_scan_version\":3}]', '[]'),
(59, NULL, '1454500998440685653', '1454853066003189841', '1310888087491973151', 'hoseing3', 'https://cdn.discordapp.com/avatars/1310888087491973151/6ab466af09a80d4b4f5636d465e685df.webp?size=128', '!done', '2025-12-28 18:36:40', '[]', '[]', '[]'),
(60, NULL, '1454500998440685653', '1454501674579136614', '1310888087491973151', 'hoseing3', 'https://cdn.discordapp.com/avatars/1310888087491973151/6ab466af09a80d4b4f5636d465e685df.webp?size=128', 'yw', '2025-12-27 19:20:22', '[]', '[]', '[]'),
(61, NULL, '1454500998440685653', '1454501662822502609', '450671455634456576', 'w4rri0r', 'https://cdn.discordapp.com/avatars/450671455634456576/06a95867e05796e088f09dc949e26fe6.webp?size=128', 'ty', '2025-12-27 19:20:19', '[]', '[]', '[]'),
(62, NULL, '1454500998440685653', '1454501480848687205', '1310888087491973151', 'hoseing3', 'https://cdn.discordapp.com/avatars/1310888087491973151/6ab466af09a80d4b4f5636d465e685df.webp?size=128', 'virus#25157\n\nvirusvikica\n\n23609010', '2025-12-27 19:19:36', '[]', '[]', '[]'),
(63, NULL, '1454500998440685653', '1454501202577588304', '450671455634456576', 'w4rri0r', 'https://cdn.discordapp.com/avatars/450671455634456576/06a95867e05796e088f09dc949e26fe6.webp?size=128', 'okeye dada', '2025-12-27 19:18:30', '[]', '[]', '[]'),
(64, NULL, '1454500998440685653', '1454501126203510907', '1310888087491973151', 'hoseing3', 'https://cdn.discordapp.com/avatars/1310888087491973151/6ab466af09a80d4b4f5636d465e685df.webp?size=128', 'okaye chan min sabr age mihse', '2025-12-27 19:18:11', '[]', '[]', '[]'),
(65, NULL, '1454500998440685653', '1454501096138735616', '450671455634456576', 'w4rri0r', 'https://cdn.discordapp.com/avatars/450671455634456576/06a95867e05796e088f09dc949e26fe6.webp?size=128', '+key 🙂', '2025-12-27 19:18:04', '[]', '[]', '[]'),
(66, NULL, '1454500998440685653', '1454501076748206101', '450671455634456576', 'w4rri0r', 'https://cdn.discordapp.com/avatars/450671455634456576/06a95867e05796e088f09dc949e26fe6.webp?size=128', 'team ready', '2025-12-27 19:18:00', '[]', '[]', '[]'),
(67, NULL, '1454500998440685653', '1454501059874652214', '450671455634456576', 'w4rri0r', 'https://cdn.discordapp.com/avatars/450671455634456576/06a95867e05796e088f09dc949e26fe6.webp?size=128', 'hi', '2025-12-27 19:17:55', '[]', '[]', '[]'),
(68, NULL, '1454500998440685653', '1454501014949597451', '1310888087491973151', 'hoseing3', 'https://cdn.discordapp.com/avatars/1310888087491973151/6ab466af09a80d4b4f5636d465e685df.webp?size=128', 'hi', '2025-12-27 19:17:45', '[]', '[]', '[]'),
(69, NULL, '1454500998440685653', '1454501000223264859', '1450064117804040275', 'G3Boost', 'https://cdn.discordapp.com/embed/avatars/1.png', 'Congratulations <@450671455634456576>! You have been selected!', '2025-12-27 19:17:41', '[]', '[{\"type\":\"rich\",\"title\":\"M+ Boost Assigned!\",\"color\":65280,\"timestamp\":\"2025-12-27T15:47:41.967000+00:00\",\"fields\":[{\"name\":\"Region\",\"value\":\"EU\",\"inline\":true},{\"name\":\"M+\",\"value\":\"18\",\"inline\":true},{\"name\":\"Armor Stack\",\"value\":\"0\",\"inline\":true},{\"name\":\"Key\",\"value\":\"Flood\",\"inline\":true},{\"name\":\"Cut\",\"value\":\"800\",\"inline\":true},{\"name\":\"Additional Notes\",\"value\":\"Dps\",\"inline\":false},{\"name\":\"Customer\",\"value\":\"<@1310888087491973151>\",\"inline\":false},{\"name\":\"Booster\",\"value\":\"<@450671455634456576>\",\"inline\":false}],\"content_scan_version\":3}]', '[]'),
(70, NULL, '1454846527238570157', '1454886327928619123', '1310705338336546857', 'poyag3', 'https://cdn.discordapp.com/avatars/1310705338336546857/c73964b6e6dfc15ecbe5bd7d30d27fe8.webp?size=128', '!done', '2025-12-28 20:48:51', '[]', '[]', '[]'),
(71, NULL, '1454846527238570157', '1454846528828080129', '1450064117804040275', 'G3Boost', 'https://cdn.discordapp.com/embed/avatars/1.png', 'Congratulations <@586594616871223337>! You have been selected!', '2025-12-28 18:10:42', '[]', '[{\"type\":\"rich\",\"title\":\"M+ Boost Assigned!\",\"color\":65280,\"timestamp\":\"2025-12-28T14:40:42.379000+00:00\",\"fields\":[{\"name\":\"Region\",\"value\":\"EU\",\"inline\":true},{\"name\":\"M+\",\"value\":\"1x20\",\"inline\":true},{\"name\":\"Armor Stack\",\"value\":\"any\",\"inline\":true},{\"name\":\"Key\",\"value\":\"FG,Prio,Ara,dawn,eco\",\"inline\":true},{\"name\":\"Cut\",\"value\":\"3000\",\"inline\":true},{\"name\":\"Additional Notes\",\"value\":\"FG geron tare - ret 3650\",\"inline\":false},{\"name\":\"Customer\",\"value\":\"<@1310705338336546857>\",\"inline\":false},{\"name\":\"Booster\",\"value\":\"<@586594616871223337>\",\"inline\":false}],\"content_scan_version\":0}]', '[]'),
(72, NULL, '1454516060010315837', '1454886340754935858', '1310705338336546857', 'poyag3', 'https://cdn.discordapp.com/avatars/1310705338336546857/c73964b6e6dfc15ecbe5bd7d30d27fe8.webp?size=128', '!done', '2025-12-28 20:48:54', '[]', '[]', '[]'),
(73, NULL, '1454516060010315837', '1454516063009112116', '1450064117804040275', 'G3Boost', 'https://cdn.discordapp.com/embed/avatars/1.png', 'Congratulations <@801958637374930954>! You have been selected!', '2025-12-27 20:17:33', '[]', '[{\"type\":\"rich\",\"title\":\"M+ Boost Assigned!\",\"color\":65280,\"timestamp\":\"2025-12-27T16:47:32.803000+00:00\",\"fields\":[{\"name\":\"Region\",\"value\":\"EU\",\"inline\":true},{\"name\":\"M+\",\"value\":\"1x19\",\"inline\":true},{\"name\":\"Armor Stack\",\"value\":\"any\",\"inline\":true},{\"name\":\"Key\",\"value\":\"fg\",\"inline\":true},{\"name\":\"Cut\",\"value\":\"1600\",\"inline\":true},{\"name\":\"Additional Notes\",\"value\":\"prot warrior 3450\",\"inline\":false},{\"name\":\"Customer\",\"value\":\"<@1310705338336546857>\",\"inline\":false},{\"name\":\"Booster\",\"value\":\"<@801958637374930954>\",\"inline\":false}],\"content_scan_version\":3}]', '[]'),
(74, NULL, '1454852948323467427', '1454957789100249108', '1310888087491973151', 'hoseing3', 'https://cdn.discordapp.com/avatars/1310888087491973151/6ab466af09a80d4b4f5636d465e685df.webp?size=128', '!done', '2025-12-29 01:32:48', '[]', '[]', '[]'),
(75, NULL, '1454852948323467427', '1454872674361671797', '497747175506444288', 'erch2294', 'https://cdn.discordapp.com/avatars/497747175506444288/5f47c46c95cd7cac3e90168bfd7e4f9b.webp?size=128', 'done shod 2x +18', '2025-12-28 19:54:35', '[]', '[]', '[]'),
(76, NULL, '1454852948323467427', '1454853243766050944', '1310888087491973151', 'hoseing3', 'https://cdn.discordapp.com/avatars/1310888087491973151/6ab466af09a80d4b4f5636d465e685df.webp?size=128', 'Dikkenék - Sylvanas\n\nDikkenek#21970\n\nJohnny1985\n23591906', '2025-12-28 18:37:23', '[]', '[]', '[]'),
(77, NULL, '1454852948323467427', '1454853091357626562', '1310888087491973151', 'hoseing3', 'https://cdn.discordapp.com/avatars/1310888087491973151/6ab466af09a80d4b4f5636d465e685df.webp?size=128', 'saalm', '2025-12-28 18:36:46', '[]', '[]', '[]'),
(78, NULL, '1454852948323467427', '1454853038090227742', '497747175506444288', 'erch2294', 'https://cdn.discordapp.com/avatars/497747175506444288/5f47c46c95cd7cac3e90168bfd7e4f9b.webp?size=128', 'salam', '2025-12-28 18:36:34', '[]', '[]', '[]'),
(79, NULL, '1454852948323467427', '1454852990669291663', '1310888087491973151', 'hoseing3', 'https://cdn.discordapp.com/avatars/1310888087491973151/6ab466af09a80d4b4f5636d465e685df.webp?size=128', '2 ta Rune', '2025-12-28 18:36:22', '[]', '[]', '[]'),
(80, NULL, '1454852948323467427', '1454852968292814899', '1310888087491973151', 'hoseing3', 'https://cdn.discordapp.com/avatars/1310888087491973151/6ab466af09a80d4b4f5636d465e685df.webp?size=128', 'hi', '2025-12-28 18:36:17', '[]', '[]', '[]'),
(81, NULL, '1454852948323467427', '1454852949833547787', '1450064117804040275', 'G3Boost', 'https://cdn.discordapp.com/embed/avatars/1.png', 'Congratulations <@497747175506444288>! You have been selected!', '2025-12-28 18:36:13', '[]', '[{\"type\":\"rich\",\"title\":\"M+ Boost Assigned!\",\"color\":65280,\"timestamp\":\"2025-12-28T15:06:13.311000+00:00\",\"fields\":[{\"name\":\"Region\",\"value\":\"EU\",\"inline\":true},{\"name\":\"M+\",\"value\":\"18\",\"inline\":true},{\"name\":\"Armor Stack\",\"value\":\"0\",\"inline\":true},{\"name\":\"Key\",\"value\":\"Flodd / Gambit / Psf\",\"inline\":true},{\"name\":\"Cut\",\"value\":\"1600\",\"inline\":true},{\"name\":\"Additional Notes\",\"value\":\".\",\"inline\":false},{\"name\":\"Customer\",\"value\":\"<@1310888087491973151>\",\"inline\":false},{\"name\":\"Booster\",\"value\":\"<@497747175506444288>\",\"inline\":false}],\"content_scan_version\":3}]', '[]'),
(82, NULL, '1454193904206217460', '1455231728825405616', '1310700453318885447', 'amirogod', 'https://cdn.discordapp.com/avatars/1310700453318885447/218fae3355907a871b057d639080a487.webp?size=128', '!done', '2025-12-29 19:41:21', '[]', '[]', '[]'),
(83, NULL, '1454193904206217460', '1454206699454861405', '801958637374930954', 'raden6168', 'https://cdn.discordapp.com/avatars/801958637374930954/5196acbcb31e90890cbfd41a85aeecc1.webp?size=128', 'https://s6.uupload.ir/files/wowscrnshot_122625_234515_b623.jpg', '2025-12-26 23:48:15', '[]', '[{\"type\":\"image\",\"url\":\"https://s6.uupload.ir/files/wowscrnshot_122625_234515_b623.jpg\",\"thumbnail\":{\"url\":\"https://s6.uupload.ir/files/wowscrnshot_122625_234515_b623.jpg\",\"proxy_url\":\"https://images-ext-1.discordapp.net/external/yAmwFmq4vb3-tQxaqknC7EC-WqtYeoTZkW408UUjC6c/https/s6.uupload.ir/files/wowscrnshot_122625_234515_b623.jpg\",\"width\":1920,\"height\":1027,\"content_type\":\"image/jpeg\",\"placeholder\":\"WAcGDIBlDJpWZXhmZnrn96Zfnw==\",\"placeholder_version\":1,\"flags\":0},\"content_scan_version\":3}]', '[]'),
(84, NULL, '1454193904206217460', '1454193964654657788', '801958637374930954', 'raden6168', 'https://cdn.discordapp.com/avatars/801958637374930954/5196acbcb31e90890cbfd41a85aeecc1.webp?size=128', 'yo', '2025-12-26 22:57:38', '[]', '[]', '[]'),
(85, NULL, '1454193904206217460', '1454193906131665069', '1450064117804040275', 'G3Boost', 'https://cdn.discordapp.com/embed/avatars/1.png', 'Congratulations <@801958637374930954>! You have been selected!', '2025-12-26 22:57:24', '[]', '[{\"type\":\"rich\",\"title\":\"M+ Boost Assigned!\",\"color\":65280,\"timestamp\":\"2025-12-26T19:27:24.959000+00:00\",\"fields\":[{\"name\":\"Region\",\"value\":\"EU\",\"inline\":true},{\"name\":\"M+\",\"value\":\"20\",\"inline\":true},{\"name\":\"Armor Stack\",\"value\":\"None\",\"inline\":true},{\"name\":\"Key\",\"value\":\"Flood/Street/PSF\",\"inline\":true},{\"name\":\"Cut\",\"value\":\"-\",\"inline\":true},{\"name\":\"Additional Notes\",\"value\":\"Buyer chos\",\"inline\":false},{\"name\":\"Customer\",\"value\":\"<@1310700453318885447>\",\"inline\":false},{\"name\":\"Booster\",\"value\":\"<@801958637374930954>\",\"inline\":false}],\"content_scan_version\":3}]', '[]'),
(86, NULL, '1454213159404568628', '1455231750119887127', '1310700453318885447', 'amirogod', 'https://cdn.discordapp.com/avatars/1310700453318885447/218fae3355907a871b057d639080a487.webp?size=128', '!done', '2025-12-29 19:41:26', '[]', '[]', '[]'),
(87, NULL, '1454213159404568628', '1454213161493463201', '1450064117804040275', 'G3Boost', 'https://cdn.discordapp.com/embed/avatars/1.png', 'Congratulations <@650364543310233611>! You have been selected!', '2025-12-27 00:13:55', '[]', '[{\"type\":\"rich\",\"title\":\"M+ Boost Assigned!\",\"color\":65280,\"timestamp\":\"2025-12-26T20:43:55.876000+00:00\",\"fields\":[{\"name\":\"Region\",\"value\":\"EU\",\"inline\":true},{\"name\":\"M+\",\"value\":\"18\",\"inline\":true},{\"name\":\"Armor Stack\",\"value\":\"noen\",\"inline\":true},{\"name\":\"Key\",\"value\":\"Flood\",\"inline\":true},{\"name\":\"Cut\",\"value\":\"-\",\"inline\":true},{\"name\":\"Additional Notes\",\"value\":\"-\",\"inline\":false},{\"name\":\"Customer\",\"value\":\"<@1310700453318885447>\",\"inline\":false},{\"name\":\"Booster\",\"value\":\"<@650364543310233611>\",\"inline\":false}],\"content_scan_version\":3}]', '[]'),
(88, NULL, '1454214717773058181', '1455231756642025504', '1310700453318885447', 'amirogod', 'https://cdn.discordapp.com/avatars/1310700453318885447/218fae3355907a871b057d639080a487.webp?size=128', '!done', '2025-12-29 19:41:27', '[]', '[]', '[]'),
(89, NULL, '1454214717773058181', '1454214720788889714', '1450064117804040275', 'G3Boost', 'https://cdn.discordapp.com/embed/avatars/1.png', 'Congratulations <@1093955654076596335>! You have been selected!', '2025-12-27 00:20:07', '[]', '[{\"type\":\"rich\",\"title\":\"M+ Boost Assigned!\",\"color\":65280,\"timestamp\":\"2025-12-26T20:50:07.579000+00:00\",\"fields\":[{\"name\":\"Region\",\"value\":\"EU\",\"inline\":true},{\"name\":\"M+\",\"value\":\"18\",\"inline\":true},{\"name\":\"Armor Stack\",\"value\":\"noen\",\"inline\":true},{\"name\":\"Key\",\"value\":\"Flood/Eco\",\"inline\":true},{\"name\":\"Cut\",\"value\":\"-\",\"inline\":true},{\"name\":\"Additional Notes\",\"value\":\"-\",\"inline\":false},{\"name\":\"Customer\",\"value\":\"<@1310700453318885447>\",\"inline\":false},{\"name\":\"Booster\",\"value\":\"<@1093955654076596335>\",\"inline\":false}],\"content_scan_version\":3}]', '[]'),
(90, NULL, '1454926270579347611', '1455231764569133076', '1310700453318885447', 'amirogod', 'https://cdn.discordapp.com/avatars/1310700453318885447/218fae3355907a871b057d639080a487.webp?size=128', '!done', '2025-12-29 19:41:29', '[]', '[]', '[]'),
(91, NULL, '1454926270579347611', '1454926326766108712', '541955616114081792', 'r4vemn', 'https://cdn.discordapp.com/avatars/541955616114081792/64f0d01f12d8bd614279e364708a9047.webp?size=128', 'slm kako', '2025-12-28 23:27:47', '[]', '[]', '[]'),
(92, NULL, '1454926270579347611', '1454926271913136266', '1450064117804040275', 'G3Boost', 'https://cdn.discordapp.com/embed/avatars/1.png', 'Congratulations <@541955616114081792>! You have been selected!', '2025-12-28 23:27:34', '[]', '[{\"type\":\"rich\",\"title\":\"M+ Boost Assigned!\",\"color\":65280,\"timestamp\":\"2025-12-28T19:57:34.622000+00:00\",\"fields\":[{\"name\":\"Region\",\"value\":\"EU\",\"inline\":true},{\"name\":\"M+\",\"value\":\"4x17 Different\",\"inline\":true},{\"name\":\"Armor Stack\",\"value\":\"None\",\"inline\":true},{\"name\":\"Key\",\"value\":\"-\",\"inline\":true},{\"name\":\"Cut\",\"value\":\"-\",\"inline\":true},{\"name\":\"Additional Notes\",\"value\":\"-\",\"inline\":false},{\"name\":\"Customer\",\"value\":\"<@1310700453318885447>\",\"inline\":false},{\"name\":\"Booster\",\"value\":\"<@541955616114081792>\",\"inline\":false}],\"content_scan_version\":0}]', '[]'),
(93, NULL, '1454946966814462197', '1455231772722729135', '1310700453318885447', 'amirogod', 'https://cdn.discordapp.com/avatars/1310700453318885447/218fae3355907a871b057d639080a487.webp?size=128', '!done', '2025-12-29 19:41:31', '[]', '[]', '[]'),
(94, NULL, '1454946966814462197', '1454974062039400499', '810170240347799583', 'euruse', 'https://cdn.discordapp.com/avatars/810170240347799583/a105a5721b381be50334ab276c97fd73.webp?size=128', 'done', '2025-12-29 02:37:28', '[]', '[]', '[]'),
(95, NULL, '1454946966814462197', '1454947051535077541', '810170240347799583', 'euruse', 'https://cdn.discordapp.com/avatars/810170240347799583/a105a5721b381be50334ab276c97fd73.webp?size=128', 'yo client name', '2025-12-29 00:50:08', '[]', '[]', '[]'),
(96, NULL, '1454946966814462197', '1454946969310073155', '1450064117804040275', 'G3Boost', 'https://cdn.discordapp.com/embed/avatars/1.png', 'Congratulations <@810170240347799583>! You have been selected!', '2025-12-29 00:49:49', '[]', '[{\"type\":\"rich\",\"title\":\"M+ Boost Assigned!\",\"color\":65280,\"timestamp\":\"2025-12-28T21:19:49.249000+00:00\",\"fields\":[{\"name\":\"Region\",\"value\":\"EU\",\"inline\":true},{\"name\":\"M+\",\"value\":\"3x17\",\"inline\":true},{\"name\":\"Armor Stack\",\"value\":\"-\",\"inline\":true},{\"name\":\"Key\",\"value\":\"diff joz eco\",\"inline\":true},{\"name\":\"Cut\",\"value\":\"600t per\",\"inline\":true},{\"name\":\"Additional Notes\",\"value\":\"-\",\"inline\":false},{\"name\":\"Customer\",\"value\":\"<@1310700453318885447>\",\"inline\":false},{\"name\":\"Booster\",\"value\":\"<@810170240347799583>\",\"inline\":false}],\"content_scan_version\":0}]', '[]'),
(97, NULL, '1454956263657046218', '1455231785679196294', '1310700453318885447', 'amirogod', 'https://cdn.discordapp.com/avatars/1310700453318885447/218fae3355907a871b057d639080a487.webp?size=128', '!done', '2025-12-29 19:41:34', '[]', '[]', '[]'),
(98, NULL, '1454956263657046218', '1454966912470745159', '650364543310233611', 'esmail5100', 'https://cdn.discordapp.com/avatars/650364543310233611/a3be28988e6057bc27dedb6faebd95fc.webp?size=128', 'done kheili mmnon', '2025-12-29 02:09:03', '[]', '[]', '[]'),
(99, NULL, '1454956263657046218', '1454966907563413702', '650364543310233611', 'esmail5100', 'https://cdn.discordapp.com/avatars/650364543310233611/a3be28988e6057bc27dedb6faebd95fc.webp?size=128', NULL, '2025-12-29 02:09:02', '[\"https://cdn.discordapp.com/attachments/1454956263657046218/1454966907311489140/WoWScrnShot_122925_020644.jpg?ex=6953abc6&is=69525a46&hm=5b9a6f133979f93162c2648406ff64b669e240e29bb6a53f17337fbc0f111f9a&\"]', '[]', '[]'),
(100, NULL, '1454956263657046218', '1454956265494282508', '1450064117804040275', 'G3Boost', 'https://cdn.discordapp.com/embed/avatars/1.png', 'Congratulations <@650364543310233611>! You have been selected!', '2025-12-29 01:26:45', '[]', '[{\"type\":\"rich\",\"title\":\"M+ Boost Assigned!\",\"color\":65280,\"timestamp\":\"2025-12-28T21:56:45.672000+00:00\",\"fields\":[{\"name\":\"Region\",\"value\":\"EU\",\"inline\":true},{\"name\":\"M+\",\"value\":\"1x18 Floood\",\"inline\":true},{\"name\":\"Armor Stack\",\"value\":\"-\",\"inline\":true},{\"name\":\"Key\",\"value\":\"Flood\",\"inline\":true},{\"name\":\"Cut\",\"value\":\"-\",\"inline\":true},{\"name\":\"Additional Notes\",\"value\":\"-\",\"inline\":false},{\"name\":\"Customer\",\"value\":\"<@1310700453318885447>\",\"inline\":false},{\"name\":\"Booster\",\"value\":\"<@650364543310233611>\",\"inline\":false}],\"content_scan_version\":0}]', '[]'),
(101, NULL, '1455296972843126835', '1455318518911336540', '1310700453318885447', 'amirogod', 'https://cdn.discordapp.com/avatars/1310700453318885447/218fae3355907a871b057d639080a487.webp?size=128', '!done', '2025-12-30 01:26:13', '[]', '[]', '[]'),
(102, NULL, '1455296972843126835', '1455296982708130016', '1450064117804040275', 'G3Boost', 'https://cdn.discordapp.com/embed/avatars/1.png', 'Congratulations <@278935264007684096>! You have been selected!', '2025-12-30 00:00:38', '[]', '[{\"type\":\"rich\",\"title\":\"M+ Boost Assigned!\",\"color\":65280,\"timestamp\":\"2025-12-29T20:30:38.842000+00:00\",\"fields\":[{\"name\":\"Region\",\"value\":\"EU\",\"inline\":true},{\"name\":\"M+\",\"value\":\"1x15 PSF\",\"inline\":true},{\"name\":\"Armor Stack\",\"value\":\"-\",\"inline\":true},{\"name\":\"Key\",\"value\":\"PSF\",\"inline\":true},{\"name\":\"Cut\",\"value\":\"-\",\"inline\":true},{\"name\":\"Additional Notes\",\"value\":\"-\",\"inline\":false},{\"name\":\"Customer\",\"value\":\"<@1310700453318885447>\",\"inline\":false},{\"name\":\"Booster\",\"value\":\"<@278935264007684096>\",\"inline\":false}],\"content_scan_version\":0}]', '[]'),
(103, NULL, '1455534990795214940', '1455536260243390525', '1310961363937398854', 'norozigod', 'https://cdn.discordapp.com/avatars/1310961363937398854/26f760a33854acedf241e9455bd5def1.webp?size=128', '!done', '2025-12-30 15:51:26', '[]', '[]', '[]'),
(104, NULL, '1455534990795214940', '1455535060777435278', '752850271582945391', 'matiinow', 'https://cdn.discordapp.com/avatars/752850271582945391/2fad6541a44faa41a075c61939644de6.webp?size=128', 'salam', '2025-12-30 15:46:41', '[]', '[]', '[]'),
(105, NULL, '1455534990795214940', '1455534993496473855', '1450064117804040275', 'G3Boost', 'https://cdn.discordapp.com/embed/avatars/1.png', 'Congratulations <@752850271582945391>! You have been selected!', '2025-12-30 15:46:24', '[]', '[{\"type\":\"rich\",\"title\":\"M+ Boost Assigned!\",\"color\":65280,\"timestamp\":\"2025-12-30T12:16:25.110000+00:00\",\"fields\":[{\"name\":\"Region\",\"value\":\"EU\",\"inline\":true},{\"name\":\"M+\",\"value\":\"1x +18\",\"inline\":true},{\"name\":\"Armor Stack\",\"value\":\"-\",\"inline\":true},{\"name\":\"Key\",\"value\":\"psf / flood\",\"inline\":true},{\"name\":\"Cut\",\"value\":\"900\",\"inline\":true},{\"name\":\"Additional Notes\",\"value\":\"buyer heal\",\"inline\":false},{\"name\":\"Customer\",\"value\":\"<@1310961363937398854>\",\"inline\":false},{\"name\":\"Booster\",\"value\":\"<@752850271582945391>\",\"inline\":false}],\"content_scan_version\":3}]', '[]'),
(106, NULL, '1455592124929282059', '1455592298015359114', '1310705338336546857', 'poyag3', 'https://cdn.discordapp.com/avatars/1310705338336546857/c73964b6e6dfc15ecbe5bd7d30d27fe8.webp?size=128', '!done', '2025-12-30 19:34:07', '[]', '[]', '[]'),
(107, NULL, '1455592124929282059', '1455592126544089339', '1450064117804040275', 'G3Boost', 'https://cdn.discordapp.com/embed/avatars/1.png', 'Congratulations <@793980296801419274>! You have been selected!', '2025-12-30 19:33:26', '[]', '[{\"type\":\"rich\",\"title\":\"M+ Boost Assigned!\",\"color\":65280,\"timestamp\":\"2025-12-30T16:03:26.693000+00:00\",\"fields\":[{\"name\":\"Region\",\"value\":\"EU\",\"inline\":true},{\"name\":\"M+\",\"value\":\"1x20\",\"inline\":true},{\"name\":\"Armor Stack\",\"value\":\".\",\"inline\":true},{\"name\":\"Key\",\"value\":\"DB\",\"inline\":true},{\"name\":\"Cut\",\"value\":\"3000\",\"inline\":true},{\"name\":\"Additional Notes\",\"value\":\"TANK WARRIOR 3850\",\"inline\":false},{\"name\":\"Customer\",\"value\":\"<@1310705338336546857>\",\"inline\":false},{\"name\":\"Booster\",\"value\":\"<@793980296801419274>\",\"inline\":false}],\"content_scan_version\":0}]', '[]'),
(108, NULL, '1454895954770792649', '1455592318815047733', '1310705338336546857', 'poyag3', 'https://cdn.discordapp.com/avatars/1310705338336546857/c73964b6e6dfc15ecbe5bd7d30d27fe8.webp?size=128', '!done', '2025-12-30 19:34:12', '[]', '[]', '[]'),
(109, NULL, '1454895954770792649', '1454895956322418810', '1450064117804040275', 'G3Boost', 'https://cdn.discordapp.com/embed/avatars/1.png', 'Congratulations <@822938419918798848>! You have been selected!', '2025-12-28 21:27:06', '[]', '[{\"type\":\"rich\",\"title\":\"M+ Boost Assigned!\",\"color\":65280,\"timestamp\":\"2025-12-28T17:57:06.813000+00:00\",\"fields\":[{\"name\":\"Region\",\"value\":\"EU\",\"inline\":true},{\"name\":\"M+\",\"value\":\"1x20\",\"inline\":true},{\"name\":\"Armor Stack\",\"value\":\".\",\"inline\":true},{\"name\":\"Key\",\"value\":\"FG-PSF\",\"inline\":true},{\"name\":\"Cut\",\"value\":\"5000\",\"inline\":true},{\"name\":\"Additional Notes\",\"value\":\"ret 3685\",\"inline\":false},{\"name\":\"Customer\",\"value\":\"<@1310705338336546857>\",\"inline\":false},{\"name\":\"Booster\",\"value\":\"<@822938419918798848>\",\"inline\":false}],\"content_scan_version\":3}]', '[]'),
(110, NULL, '1454891578798637078', '1455592324573827102', '1310705338336546857', 'poyag3', 'https://cdn.discordapp.com/avatars/1310705338336546857/c73964b6e6dfc15ecbe5bd7d30d27fe8.webp?size=128', '!done', '2025-12-30 19:34:13', '[]', '[]', '[]'),
(111, NULL, '1454891578798637078', '1454891580312780975', '1450064117804040275', 'G3Boost', 'https://cdn.discordapp.com/embed/avatars/1.png', 'Congratulations <@793980296801419274>! You have been selected!', '2025-12-28 21:09:43', '[]', '[{\"type\":\"rich\",\"title\":\"M+ Boost Assigned!\",\"color\":65280,\"timestamp\":\"2025-12-28T17:39:43.478000+00:00\",\"fields\":[{\"name\":\"Region\",\"value\":\"EU\",\"inline\":true},{\"name\":\"M+\",\"value\":\"1x20\",\"inline\":true},{\"name\":\"Armor Stack\",\"value\":\".\",\"inline\":true},{\"name\":\"Key\",\"value\":\"FG-PSF\",\"inline\":true},{\"name\":\"Cut\",\"value\":\"5000\",\"inline\":true},{\"name\":\"Additional Notes\",\"value\":\"ret 3685\",\"inline\":false},{\"name\":\"Customer\",\"value\":\"<@1310705338336546857>\",\"inline\":false},{\"name\":\"Booster\",\"value\":\"<@793980296801419274>\",\"inline\":false}],\"content_scan_version\":3}]', '[]'),
(112, NULL, '1454878065803919567', '1455592339346161904', '1310705338336546857', 'poyag3', 'https://cdn.discordapp.com/avatars/1310705338336546857/c73964b6e6dfc15ecbe5bd7d30d27fe8.webp?size=128', '!done', '2025-12-30 19:34:17', '[]', '[]', '[]'),
(113, NULL, '1454878065803919567', '1454891318579953704', '799201230634352671', 'vp021', 'https://cdn.discordapp.com/avatars/799201230634352671/7274423da8ac50dc8168366302468b38.webp?size=128', 'Done', '2025-12-28 21:08:40', '[]', '[]', '[]'),
(114, NULL, '1454878065803919567', '1454878067863584788', '1450064117804040275', 'G3Boost', 'https://cdn.discordapp.com/embed/avatars/1.png', 'Congratulations <@799201230634352671>! You have been selected!', '2025-12-28 20:16:01', '[]', '[{\"type\":\"rich\",\"title\":\"M+ Boost Assigned!\",\"color\":65280,\"timestamp\":\"2025-12-28T16:46:01.842000+00:00\",\"fields\":[{\"name\":\"Region\",\"value\":\"EU\",\"inline\":true},{\"name\":\"M+\",\"value\":\"1x20\",\"inline\":true},{\"name\":\"Armor Stack\",\"value\":\".\",\"inline\":true},{\"name\":\"Key\",\"value\":\"FG,Prio,Ara,dawn,eco\",\"inline\":true},{\"name\":\"Cut\",\"value\":\"3000\",\"inline\":true},{\"name\":\"Additional Notes\",\"value\":\"fg geron tare - 3650 RET\",\"inline\":false},{\"name\":\"Customer\",\"value\":\"<@1310705338336546857>\",\"inline\":false},{\"name\":\"Booster\",\"value\":\"<@799201230634352671>\",\"inline\":false}],\"content_scan_version\":3}]', '[]'),
(115, NULL, '1455609639571030139', '1455610083445571833', '1310888087491973151', 'hoseing3', 'https://cdn.discordapp.com/avatars/1310888087491973151/6ab466af09a80d4b4f5636d465e685df.webp?size=128', '!done', '2025-12-30 20:44:47', '[]', '[]', '[]'),
(116, NULL, '1455609639571030139', '1455610077422813296', '1310888087491973151', 'hoseing3', 'https://cdn.discordapp.com/avatars/1310888087491973151/6ab466af09a80d4b4f5636d465e685df.webp?size=128', '!dne', '2025-12-30 20:44:46', '[]', '[]', '[]'),
(117, NULL, '1455609639571030139', '1455609886875455580', '1310888087491973151', 'hoseing3', 'https://cdn.discordapp.com/avatars/1310888087491973151/6ab466af09a80d4b4f5636d465e685df.webp?size=128', 'alan send mikonam inforo', '2025-12-30 20:44:00', '[]', '[]', '[]'),
(118, NULL, '1455609639571030139', '1455609866964963349', '1310888087491973151', 'hoseing3', 'https://cdn.discordapp.com/avatars/1310888087491973151/6ab466af09a80d4b4f5636d465e685df.webp?size=128', '2 min sabr kon', '2025-12-30 20:43:56', '[]', '[]', '[]'),
(119, NULL, '1455609639571030139', '1455609848338059388', '1310888087491973151', 'hoseing3', 'https://cdn.discordapp.com/avatars/1310888087491973151/6ab466af09a80d4b4f5636d465e685df.webp?size=128', 'Hey', '2025-12-30 20:43:51', '[]', '[]', '[]'),
(120, NULL, '1455609639571030139', '1455609806298681517', '801958637374930954', 'raden6168', 'https://cdn.discordapp.com/avatars/801958637374930954/5196acbcb31e90890cbfd41a85aeecc1.webp?size=128', '@', '2025-12-30 20:43:41', '[]', '[]', '[]'),
(121, NULL, '1455609639571030139', '1455609650144608497', '1450064117804040275', 'G3Boost', 'https://cdn.discordapp.com/embed/avatars/1.png', 'Congratulations <@801958637374930954>! You have been selected!', '2025-12-30 20:43:04', '[]', '[{\"type\":\"rich\",\"title\":\"M+ Boost Assigned!\",\"color\":65280,\"timestamp\":\"2025-12-30T17:13:04.674000+00:00\",\"fields\":[{\"name\":\"Region\",\"value\":\"EU\",\"inline\":true},{\"name\":\"M+\",\"value\":\"18\",\"inline\":true},{\"name\":\"Armor Stack\",\"value\":\"0\",\"inline\":true},{\"name\":\"Key\",\"value\":\"Flood\",\"inline\":true},{\"name\":\"Cut\",\"value\":\"800\",\"inline\":true},{\"name\":\"Additional Notes\",\"value\":\"Dps\",\"inline\":false},{\"name\":\"Customer\",\"value\":\"<@1310888087491973151>\",\"inline\":false},{\"name\":\"Booster\",\"value\":\"<@801958637374930954>\",\"inline\":false}],\"content_scan_version\":0}]', '[]'),
(122, NULL, '1455620816950526044', '1457051196941537302', '1310700453318885447', 'amirogod', 'https://cdn.discordapp.com/avatars/1310700453318885447/218fae3355907a871b057d639080a487.webp?size=128', '!done', '2026-01-03 20:11:16', '[]', '[]', '[]'),
(123, NULL, '1455620816950526044', '1456410329884917863', '1310700453318885447', 'amirogod', 'https://cdn.discordapp.com/avatars/1310700453318885447/218fae3355907a871b057d639080a487.webp?size=128', '!done', '2026-01-02 01:44:41', '[]', '[]', '[]'),
(124, NULL, '1455620816950526044', '1455620818678841345', '1450064117804040275', 'G3Boost', 'https://cdn.discordapp.com/embed/avatars/1.png', 'Congratulations <@1093955654076596335>! You have been selected!', '2025-12-30 21:27:27', '[]', '[{\"type\":\"rich\",\"title\":\"M+ Boost Assigned!\",\"color\":65280,\"timestamp\":\"2025-12-30T17:57:27.387000+00:00\",\"fields\":[{\"name\":\"Region\",\"value\":\"EU\",\"inline\":true},{\"name\":\"M+\",\"value\":\"8x16\",\"inline\":true},{\"name\":\"Armor Stack\",\"value\":\"-\",\"inline\":true},{\"name\":\"Key\",\"value\":\"WT\",\"inline\":true},{\"name\":\"Cut\",\"value\":\"360t per key\",\"inline\":true},{\"name\":\"Additional Notes\",\"value\":\"-\",\"inline\":false},{\"name\":\"Customer\",\"value\":\"<@1310700453318885447>\",\"inline\":false},{\"name\":\"Booster\",\"value\":\"<@1093955654076596335>\",\"inline\":false}],\"content_scan_version\":3}]', '[]'),
(125, NULL, '1456690972963373077', '1457051206076596507', '1310700453318885447', 'amirogod', 'https://cdn.discordapp.com/avatars/1310700453318885447/218fae3355907a871b057d639080a487.webp?size=128', '!done', '2026-01-03 20:11:18', '[]', '[]', '[]');
INSERT INTO `mplus_channel_logs` (`id`, `order_id`, `channel_id`, `message_id`, `author_id`, `author_username`, `author_avatar`, `content`, `timestamp`, `attachments`, `embeds`, `reactions`) VALUES
(126, NULL, '1456690972963373077', '1456690975760973897', '1450064117804040275', 'G3Boost', 'https://cdn.discordapp.com/embed/avatars/1.png', 'Congratulations <@278935264007684096>! You have been selected!', '2026-01-02 20:19:52', '[]', '[{\"type\":\"rich\",\"title\":\"M+ Boost Assigned!\",\"color\":65280,\"timestamp\":\"2026-01-02T16:49:52.573000+00:00\",\"fields\":[{\"name\":\"Region\",\"value\":\"EU\",\"inline\":true},{\"name\":\"M+\",\"value\":\"4x16\",\"inline\":true},{\"name\":\"Armor Stack\",\"value\":\"-\",\"inline\":true},{\"name\":\"Key\",\"value\":\"Diff keys\",\"inline\":true},{\"name\":\"Cut\",\"value\":\"360t per key\",\"inline\":true},{\"name\":\"Additional Notes\",\"value\":\"-\",\"inline\":false},{\"name\":\"Customer\",\"value\":\"<@1310700453318885447>\",\"inline\":false},{\"name\":\"Booster\",\"value\":\"<@278935264007684096>\",\"inline\":false}],\"content_scan_version\":0}]', '[]'),
(127, NULL, '1458198932382416946', '1474453078844379330', '1310700453318885447', 'amirogod', 'https://cdn.discordapp.com/avatars/1310700453318885447/218fae3355907a871b057d639080a487.webp?size=128', '!done', '2026-02-20 20:40:08', '[]', '[]', '[]'),
(128, NULL, '1458198932382416946', '1458198935821746186', '1450064117804040275', 'G3Boost', 'https://cdn.discordapp.com/embed/avatars/1.png', 'Congratulations <@519048679827701775>! You have been selected!', '2026-01-07 00:11:58', '[]', '[{\"type\":\"rich\",\"title\":\"M+ Boost Assigned!\",\"color\":65280,\"timestamp\":\"2026-01-06T20:41:58.310000+00:00\",\"fields\":[{\"name\":\"Region\",\"value\":\"EU\",\"inline\":true},{\"name\":\"M+\",\"value\":\"18\",\"inline\":true},{\"name\":\"Armor Stack\",\"value\":\"-\",\"inline\":true},{\"name\":\"Key\",\"value\":\"Random\",\"inline\":true},{\"name\":\"Cut\",\"value\":\"-\",\"inline\":true},{\"name\":\"Additional Notes\",\"value\":\"-\",\"inline\":false},{\"name\":\"Customer\",\"value\":\"<@1310700453318885447>\",\"inline\":false},{\"name\":\"Booster\",\"value\":\"<@519048679827701775>\",\"inline\":false}],\"content_scan_version\":0}]', '[]');

-- --------------------------------------------------------

--
-- Table structure for table `mplus_orders`
--

CREATE TABLE `mplus_orders` (
  `id` int(11) NOT NULL,
  `order_id` varchar(20) NOT NULL,
  `customer_id` varchar(20) NOT NULL,
  `booster_id` varchar(20) NOT NULL,
  `region` varchar(10) NOT NULL,
  `mplus_level` varchar(50) DEFAULT NULL,
  `armor_stack` varchar(50) DEFAULT NULL,
  `mplus_key` varchar(50) DEFAULT NULL,
  `cut` varchar(100) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `channel_id` varchar(20) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `mplus_orders`
--

INSERT INTO `mplus_orders` (`id`, `order_id`, `customer_id`, `booster_id`, `region`, `mplus_level`, `armor_stack`, `mplus_key`, `cut`, `notes`, `channel_id`, `created_at`) VALUES
(1, 'mjb13la7gahp2', '1081147280180125757', '1081147280180125757', 'EU', '45', '68', '456', '456', '45', '1451090977971507310', '2025-12-18 05:57:29'),
(2, 'mjb1yk5eq97nl', '1081147280180125757', '1081147280180125757', 'EU', '87', '78', '78', '78', '78', '1451097037608190044', '2025-12-18 06:21:34'),
(3, 'mjb98dxnn88e9', '1081147280180125757', '1081147280180125757', 'EU', '456', '456', '6', '546', '56', '1451148274181476433', '2025-12-18 09:45:10'),
(4, 'mjb9e1fe0gyzi', '1081147280180125757', '1081147280180125757', 'EU', '43', '54', '453', '453', '453', '1451149380869427232', '2025-12-18 09:49:34'),
(5, 'mjh04luea040o', '1081147280180125757', '1081147280180125757', 'EU', '12', '3', '12', '200', '4512', '1452605812974288918', '2025-12-22 10:16:54'),
(6, 'mjhc455kx4qnr', '1081147280180125757', '1081147280180125757', 'EU', '12', '35', '456', '456', 'rrr', '1452690259866554389', '2025-12-22 15:52:28'),
(7, 'mjl8597sk5iy3', '1081147280180125757', '1081147280180125757', 'EU', '10', '123', '1243', '123', '213', '1453676752521134121', '2025-12-25 09:12:26'),
(8, 'mjl8fjx96ouse', '1081147280180125757', '1081147280180125757', 'EU', '1', '4', '1243', '214', '12', '1453678760028147834', '2025-12-25 09:20:26'),
(9, 'mjl8lwzgpdp5p', '1081147280180125757', '1081147280180125757', 'EU', '34', '3423', '25', '324', '234', '1453680012799770665', '2025-12-25 09:25:23'),
(10, 'mjlb26dxigc12', '1330425874142724167', '650364543310233611', 'EU', '1x18', 'any', 'street', '800', '-', '1453697284167700545', '2025-12-25 10:34:01'),
(11, 'mjlcys3qyi0zm', '1310958500888707072', '594475064875810827', 'EU', '1', '.', '19', '1500', '1x19 dawn', '1453710709014659236', '2025-12-25 11:27:22'),
(12, 'mjlhxjggzzsn9', '1310958500888707072', '414794408269971477', 'EU', '1', '.', '21', '7t', 'gambit', '1453745690592415962', '2025-12-25 13:46:22'),
(13, 'mjljai6wxn37w', '1330425874142724167', '868412471214493716', 'EU', '1x21', 'any', 'halls', '7t', '-', '1453755272563527701', '2025-12-25 14:24:27'),
(14, 'mjlmlmnmwgszc', '1310705338336546857', '810170240347799583', 'EU', '2x18', 'any', 'eco/flood/dawn/prio/halls/streets', '1500', 'ret 3400 alt', '1453778584270278819', '2025-12-25 15:57:04'),
(15, 'mjlroeabtio2d', '1310705338336546857', '414794408269971477', 'EU', '2x20', 'any', 'STRT-HOA', '6000', 'ret 3500 alt', '1453814349729628282', '2025-12-25 18:19:12'),
(16, 'mjlvcbajtz8pq', '1310705338336546857', '1093955654076596335', 'EU', '1x20', 'any', 'STRT', '3000', 'ret 3550 ALT', '1453840164119118017', '2025-12-25 20:01:46'),
(17, 'mjm0sd7ivcrou', '1310700453318885447', '946012168439091202', 'EU', '20', 'None', 'HoA/Ara', '3100t', 'Buyer Havoc DH', '1453878530093093026', '2025-12-25 22:34:13'),
(18, 'mjmqyktizk9l5', '1330425874142724167', '468519892459716609', 'EU', '1x15', 'any', 'ara dawn', '330', '-', '1454062910790963343', '2025-12-26 10:46:53'),
(19, 'mjmsc0sbsqzsd', '1310958500888707072', '468519892459716609', 'EU', '1', '.', '15', '340', 'ara', '1454072586194522256', '2025-12-26 11:25:20'),
(20, 'mjmt7ofxmfbru', '1310888087491973151', '706999274709319761', 'EU', '19', '0', 'Street / Gambit', '2600', '3700 io Dps', '1454078781437247622', '2025-12-26 11:49:57'),
(21, 'mjn5dji8jcx45', '1310700453318885447', '946012168439091202', 'EU', '19', 'None', 'Flood/PSF', '-', 'Buyer healer Resto Shaman', '1454164466647306251', '2025-12-26 17:30:26'),
(22, 'mjn9jz1b88qeo', '1310700453318885447', '801958637374930954', 'EU', '20', 'None', 'Flood/Street/PSF', '-', 'Buyer chos', '1454193904206217460', '2025-12-26 19:27:24'),
(23, 'mjncades4v1nf', '1310700453318885447', '650364543310233611', 'EU', '18', 'noen', 'Flood', '-', '-', '1454213159404568628', '2025-12-26 20:43:55'),
(24, 'mjncic7g5f3r3', '1310700453318885447', '1093955654076596335', 'EU', '18', 'noen', 'Flood/Eco', '-', '-', '1454214717773058181', '2025-12-26 20:50:07'),
(25, 'mjo6edpyo41i9', '1330425874142724167', '431892711650623488', 'EU', '1x20', 'any', 'psf', '4400', '-', '1454425289026437336', '2025-12-27 10:46:51'),
(26, 'mjob4vs72urqo', '1310958500888707072', '468519892459716609', 'EU', '1', '.', '16', '350', 'psf', '1454458654546989297', '2025-12-27 12:59:26'),
(27, 'mjoe7qydczgps', '1310958500888707072', '278935264007684096', 'EU', '1', '.', '14', '300', 'flood', '1454480349399617741', '2025-12-27 14:25:38'),
(28, 'mjoh59n388lzw', '1310888087491973151', '450671455634456576', 'EU', '18', '0', 'Flood', '800', 'Dps', '1454500998440685653', '2025-12-27 15:47:41'),
(29, 'mjoja8cj8n738', '1310705338336546857', '801958637374930954', 'EU', '1x19', 'any', 'fg', '1600', 'prot warrior 3450', '1454516060010315837', '2025-12-27 16:47:32'),
(30, 'mjpfegnnjlq7s', '1310961363937398854', '440596496329474057', 'EU', '1x +17', '-', 'any not Gambit DB Streets', '700', '-', '1454742321806704796', '2025-12-28 07:46:38'),
(31, 'mjpkeim07jgvz', '1310961363937398854', '497747175506444288', 'EU', '2x +16', '-', 'db flood psf', '400', '-', '1454777556472434772', '2025-12-28 10:06:38'),
(32, 'mjpu6yrg6pdm7', '1310705338336546857', '586594616871223337', 'EU', '1x20', 'any', 'FG,Prio,Ara,dawn,eco', '3000', 'FG geron tare - ret 3650', '1454846527238570157', '2025-12-28 14:40:42'),
(33, 'mjpv3s1rm4tl3', '1310888087491973151', '497747175506444288', 'EU', '18', '0', 'Flodd / Gambit / Psf', '1600', '.', '1454852948323467427', '2025-12-28 15:06:13'),
(34, 'mjpyo4tug7uv1', '1310705338336546857', '799201230634352671', 'EU', '1x20', '.', 'FG,Prio,Ara,dawn,eco', '3000', 'fg geron tare - 3650 RET', '1454878065803919567', '2025-12-28 16:46:01'),
(35, 'mjq0l6nq7b9py', '1310705338336546857', '793980296801419274', 'EU', '1x20', '.', 'FG-PSF', '5000', 'ret 3685', '1454891578798637078', '2025-12-28 17:39:43'),
(36, 'mjq17jottec32', '1310705338336546857', '822938419918798848', 'EU', '1x20', '.', 'FG-PSF', '5000', 'ret 3685', '1454895954770792649', '2025-12-28 17:57:06'),
(37, 'mjq5igpb5123j', '1310700453318885447', '541955616114081792', 'EU', '4x17 Different', 'None', '-', '-', '-', '1454926270579347611', '2025-12-28 19:57:34'),
(38, 'mjq8g8aaa08ez', '1310700453318885447', '810170240347799583', 'EU', '3x17', '-', 'diff joz eco', '600t per', '-', '1454946966814462197', '2025-12-28 21:19:49'),
(39, 'mjq9rqi0u6e8k', '1310700453318885447', '650364543310233611', 'EU', '1x18 Floood', '-', 'Flood', '-', '-', '1454956263657046218', '2025-12-28 21:56:45'),
(40, 'mjqz8miq3pxhd', '1330425874142724167', '650364543310233611', 'EU', '1x18', 'any', 'psf-flood', '840t', 'buyer healer', '1455135688780681291', '2025-12-29 09:49:44'),
(41, 'mjqzneczf9y74', '1330425874142724167', '1103754252297445489', 'EU', '1x18', 'Any', 'psf flood', '840', 'buyer healer', '1455138580522598575', '2025-12-29 10:01:13'),
(42, 'mjr4xg5xqyxb6', '1310958500888707072', '212557379382804480', 'EU', '1', '.', '21', '7t', 'random no ara no flood no psf', '1455175771298594950', '2025-12-29 12:29:00'),
(43, 'mjr73myurr3b8', '1310958500888707072', '933445990835425390', 'EU', '5', '.', '18', '800per', '5x18 psf eco halls db ara', '1455191072274645074', '2025-12-29 13:29:48'),
(44, 'mjr773xk73a3u', '1310958500888707072', '1093955654076596335', 'EU', '5', '.', '18', '750per', '5x18 psf eco halls db ara/ psf key darid take konid', '1455191751508754525', '2025-12-29 13:32:30'),
(45, 'mjr83qxwrlmj8', '1330425874142724167', '823869903756328972', 'EU', '10x30 legion', '-', '-', '35t per', '-', '1455198138238505053', '2025-12-29 13:57:53'),
(46, 'mjrcdt6uvbblm', '1310888087491973151', '839546226009178112', 'EU', '17', '0', 'Ara / Dawn / Psf', '600', '3400 io', '1455228287251906724', '2025-12-29 15:57:41'),
(47, 'mjrm4ueyexmzi', '1310700453318885447', '278935264007684096', 'EU', '1x15 PSF', '-', 'PSF', '-', '-', '1455296972843126835', '2025-12-29 20:30:38'),
(48, 'mjsjx3wsbz0v1', '1310961363937398854', '752850271582945391', 'EU', '1x +18', '-', 'psf / flood', '900', 'buyer heal', '1455534990795214940', '2025-12-30 12:16:25'),
(49, 'mjss12l1qjmc3', '1310705338336546857', '793980296801419274', 'EU', '1x20', '.', 'DB', '3000', 'TANK WARRIOR 3850', '1455592124929282059', '2025-12-30 16:03:26'),
(50, 'mjsuimciulqma', '1310888087491973151', '801958637374930954', 'EU', '18', '0', 'Flood', '800', 'Dps', '1455609639571030139', '2025-12-30 17:13:04'),
(51, 'mjsulwg9w2qq9', '1310888087491973151', '433862183709966336', 'EU', '18', '0', 'Flood', '800', 'Dps', '1455610290686394449', '2025-12-30 17:15:37'),
(52, 'mjsw3owrboxj4', '1310700453318885447', '1093955654076596335', 'EU', '8x16', '-', 'WT', '360t per key', '-', '1455620816950526044', '2025-12-30 17:57:27'),
(53, 'mjwwqgegw0iio', '1310883208308461591', '818904649594699798', 'EU', '18', 'none', 'flood', '850', 's', '1456639725703266507', '2026-01-02 13:26:14'),
(54, 'mjx40c6lsba45', '1310700453318885447', '278935264007684096', 'EU', '4x16', '-', 'Diff keys', '360t per key', '-', '1456690972963373077', '2026-01-02 16:49:52'),
(55, 'mjyita7632jgj', '1310705338336546857', '409020644974657548', 'EU', '2x18', '..', 'FG-PSF', '750t per', 'healer druid 3621', '1457048878418235452', '2026-01-03 16:32:04'),
(56, 'mk0w1a3yha226', '1310883208308461591', '409020644974657548', 'EU', '18', 'none', 'ara kara', '740t', 's', '1457649253030694912', '2026-01-05 08:17:44'),
(57, 'mk3227toxqzba', '1310700453318885447', '519048679827701775', 'EU', '18', '-', 'Random', '-', '-', '1458198932382416946', '2026-01-06 20:41:58'),
(58, 'mk34wn8e2ttfh', '1310705338336546857', '1103754252297445489', 'EU', '1x18', '-', 'PSF-ARA', '-', 'fury warrior 3780', '1458218976646791302', '2026-01-06 22:01:36');

-- --------------------------------------------------------

--
-- Table structure for table `payment_report`
--

CREATE TABLE `payment_report` (
  `id` int(11) NOT NULL,
  `channel_id` varchar(255) DEFAULT NULL,
  `command_user_id` varchar(255) DEFAULT NULL,
  `mentioned_user_ids` text DEFAULT NULL,
  `transactionId` varchar(255) NOT NULL,
  `amount` float DEFAULT NULL,
  `payment_count` int(11) NOT NULL,
  `add_balance` tinyint(1) NOT NULL,
  `payment_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payment_report`
--

INSERT INTO `payment_report` (`id`, `channel_id`, `command_user_id`, `mentioned_user_ids`, `transactionId`, `amount`, `payment_count`, `add_balance`, `payment_date`) VALUES
(4, '1188126813319340132', '1081147280180125757', '675309510491111443', '0', 10, 0, 0, '2023-12-30 06:41:28'),
(5, '1188126813319340132', '1081147280180125757', '675309510491111443', '0', 20, 0, 0, '2023-12-30 06:59:46'),
(6, '1188126813319340132', '1081147280180125757', '675309510491111443', '0', 10, 0, 0, '2023-12-30 07:03:34'),
(7, '1188126813319340132', '1081147280180125757', '675309510491111443', '0', 5, 0, 0, '2023-12-30 07:08:57'),
(8, '1188126813319340132', '1081147280180125757', '675309510491111443', '0', 2, 0, 0, '2023-12-30 07:09:45'),
(9, '1188126813319340132', '1081147280180125757', '675309510491111443', '0', 4, 0, 0, '2023-12-30 07:13:10'),
(10, '1188126813319340132', '1081147280180125757', '675309510491111443', '0', 1, 0, 0, '2023-12-30 07:14:31'),
(11, '1188126813319340132', '1081147280180125757', '675309510491111443', '658', 10, 0, 0, '2023-12-30 07:23:23'),
(12, '1188126813319340132', '1081147280180125757', '675309510491111443', '658', 12, 0, 0, '2023-12-30 08:29:10'),
(13, '1188126813319340132', '1081147280180125757', '675309510491111443', '658', 19, 0, 0, '2023-12-30 08:33:07'),
(14, '1188126813319340132', '1081147280180125757', '675309510491111443', ' 658ff4c511', 25, 0, 0, '2023-12-30 10:45:25'),
(15, '1188126813319340132', '1081147280180125757', '675309510491111443', ' 658ff9d58fea6 ', 12, 0, 0, '2023-12-30 11:07:01'),
(16, '1188126813319340132', '1081147280180125757', '675309510491111443', ' 658ffc5daf7bd ', 9, 0, 0, '2023-12-30 11:17:49'),
(17, '1188126813319340132', '1081147280180125757', '1081147280180125757,675309510491111443', ' 658ffd49efbef ', 8, 0, 0, '2023-12-30 11:21:45'),
(18, '1188126813319340132', '1081147280180125757', '1081147280180125757,675309510491111443', ' 65900318b0097 ', 8, 0, 0, '2023-12-30 11:46:32'),
(19, '1188126813319340132', '1081147280180125757', '1081147280180125757,675309510491111443', ' 65900613f0eb2 ', 10, 0, 0, '2023-12-30 11:59:15'),
(20, '1188126813319340132', '1081147280180125757', '1081147280180125757', ' 6590069ca1fba ', 10, 0, 0, '2023-12-30 12:01:32'),
(21, '1188126813319340132', '1081147280180125757', '1081147280180125757', ' 6590072377c59 ', 10, 0, 0, '2023-12-30 12:03:47'),
(22, '1188126813319340132', '1081147280180125757', '1081147280180125757', ' 65900a7be666d ', 10, 0, 0, '2023-12-30 12:18:03'),
(23, '1190748640722047067', '675309510491111443', '675309510491111443', ' 659079a7d9c0b ', 2, 0, 0, '2023-12-30 20:12:23'),
(24, '1190916644105633892', '1081147280180125757', '1081147280180125757', ' 65911e574ae8b ', 20, 0, 0, '2023-12-31 07:55:03'),
(25, '1190916644105633892', '1081147280180125757', '1081147280180125757', ' 65911ed70777e ', 20, 0, 0, '2023-12-31 07:57:11'),
(26, NULL, '1081147280180125757', NULL, ' 659157da29e8d ', 100, 0, 1, '2023-12-31 12:00:26'),
(27, NULL, '1081147280180125757', NULL, ' 659158c46ff1b ', 100, 0, 1, '2023-12-31 12:04:20'),
(28, NULL, '1081147280180125757', NULL, ' 65915950c2efe ', 100, 0, 1, '2023-12-31 12:06:40'),
(29, NULL, '1081147280180125757', NULL, ' 65915e6d2eea6 ', 100, 0, 1, '2023-12-31 12:28:29'),
(30, NULL, '1081147280180125757', NULL, ' 65915ed9cc139 ', 100, 0, 1, '2023-12-31 12:30:17'),
(31, NULL, '1081147280180125757', NULL, ' 6591b600176be ', 1, 0, 1, '2023-12-31 18:42:08'),
(32, NULL, '1081147280180125757', NULL, ' 6591b64e6d137 ', 1, 1, 1, '2023-12-31 18:43:26'),
(33, NULL, '1081147280180125757', NULL, ' 6591b68b235f8 ', 14, 2, 1, '2023-12-31 18:44:27'),
(34, NULL, '675309510491111443', NULL, ' 659326630e923 ', 20, 0, 1, '2024-01-01 20:53:55'),
(35, '1190991351643377756', '1081147280180125757', '675309510491111443,1081147280180125757', ' 6594891cb8808 ', 32, 0, 0, '2024-01-02 22:07:24'),
(36, '1452610465476772034', '1081147280180125757', '675309510491111443,1081147280180125757', ' 69491f60ba1ba ', 1000, 0, 0, '2025-12-22 10:37:20'),
(37, '1452610465476772034', '1081147280180125757', '1081147280180125757', ' 69491f8ef33ef ', 200, 0, 0, '2025-12-22 10:38:07');

-- --------------------------------------------------------

--
-- Table structure for table `rank_thresholds`
--

CREATE TABLE `rank_thresholds` (
  `role` varchar(10) NOT NULL,
  `level` int(11) NOT NULL,
  `threshold` float NOT NULL,
  `color` varchar(7) DEFAULT '#00ff00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rank_thresholds`
--

INSERT INTO `rank_thresholds` (`role`, `level`, `threshold`, `color`) VALUES
('DPS', 1, 1000, '#FFCCCC'),
('DPS', 2, 2000, '#FF6666'),
('DPS', 3, 3000, '#FF0000'),
('DPS', 4, 4000, '#00ff00'),
('DPS', 5, 5000, '#00ff00'),
('HEALING', 1, 1000, '#CCFFCC'),
('HEALING', 2, 2000, '#66FF66'),
('HEALING', 3, 3000, '#00FF00'),
('HEALING', 4, 4000, '#00ff00'),
('HEALING', 5, 5000, '#00ff00'),
('Tank', 1, 1000, '#CCCCFF'),
('Tank', 2, 2000, '#6666FF'),
('Tank', 3, 3000, '#0000FF'),
('Tank', 4, 4000, '#00ff00'),
('Tank', 5, 5000, '#00ff00');

-- --------------------------------------------------------

--
-- Table structure for table `sales`
--

CREATE TABLE `sales` (
  `id` int(11) NOT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  `commission` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sales`
--

INSERT INTO `sales` (`id`, `amount`, `commission`) VALUES
(1, NULL, 4.00);

-- --------------------------------------------------------

--
-- Table structure for table `server_info`
--

CREATE TABLE `server_info` (
  `id` int(11) NOT NULL,
  `player_name` varchar(50) DEFAULT NULL,
  `faction` varchar(20) DEFAULT NULL,
  `realm` varchar(50) DEFAULT NULL,
  `gold` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `server_info`
--

INSERT INTO `server_info` (`id`, `player_name`, `faction`, `realm`, `gold`) VALUES
(1, 'goli', 'hord', 'Draenor', 0);

-- --------------------------------------------------------

--
-- Table structure for table `team_input_labels`
--

CREATE TABLE `team_input_labels` (
  `id` int(11) NOT NULL,
  `input1` varchar(255) DEFAULT 'Current Level',
  `input2` varchar(255) DEFAULT 'Desired Level',
  `input3` varchar(255) DEFAULT 'Character Name',
  `input4` varchar(255) DEFAULT 'Realm',
  `input5` varchar(255) DEFAULT 'Additional Notes'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` int(11) NOT NULL,
  `transaction_type` varchar(255) DEFAULT NULL,
  `channelID` varchar(255) DEFAULT NULL,
  `gold_amount` int(11) DEFAULT NULL,
  `user_id` varchar(255) DEFAULT NULL,
  `mail_role_mention` varchar(255) DEFAULT NULL,
  `tank_role_mention` varchar(255) DEFAULT NULL,
  `plate_role_mention` varchar(255) DEFAULT NULL,
  `heal_mention` varchar(255) DEFAULT NULL,
  `dps_role_mention` varchar(255) DEFAULT NULL,
  `leather_role_mention` varchar(255) DEFAULT NULL,
  `cloth_role_mention` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`id`, `transaction_type`, `channelID`, `gold_amount`, `user_id`, `mail_role_mention`, `tank_role_mention`, `plate_role_mention`, `heal_mention`, `dps_role_mention`, `leather_role_mention`, `cloth_role_mention`, `created_at`) VALUES
(1, '8x12', '1195016880554004500', 350, '1081147280180125757', '', '', '1178260740336005260', '', '', '', '', '2024-01-11 14:50:28'),
(2, '3x17', '1195017112377376858', 200, '1081147280180125757', '', '1178271058244141107', '', '1178275394395316286', '1135536909414830120', '', '', '2024-01-11 14:51:23'),
(3, '3x17', '1195017206162010134', 200, '1081147280180125757', '', '1178271058244141107', '', '', '', '', '1178271129685729280', '2024-01-11 14:51:46'),
(4, '3x17', '1195017149480177664', 200, '1081147280180125757', '', '', '', '', '', '1178270867432685618', '', '2024-01-11 14:51:47'),
(5, '1x15', '1195662035246137415', 130, '1081147280180125757', '1178270966682497054', '1178271058244141107', '', '', '', '', '', '2024-01-13 09:34:05'),
(6, '1x15', '1195664868846600274', 100, '675309510491111443', '', '1016081485549281289', '', '1016081485616386108', '1016081485549281288', '', '', '2024-01-13 09:45:21'),
(7, '1x15', '1195684992366432266', 100, '675309510491111443', '', '1016081485549281289', '', '1016081485616386108', '1016081485549281288', '', '', '2024-01-13 11:05:19'),
(8, '1x15', '1195685360999608370', 100, '675309510491111443', '', '1016081485549281289', '', '1016081485616386108', '1016081485549281288', '', '', '2024-01-13 11:06:47'),
(9, '1x10', '1452610465476772034', 300, '1081147280180125757', '1178270966682497054', '1178271058244141107', '', '', '', '', '', '2025-12-22 10:35:24');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `discord_id` varchar(20) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `username` varchar(255) DEFAULT NULL,
  `roles` text DEFAULT NULL,
  `hero_name` varchar(100) NOT NULL,
  `hero_realm` varchar(100) NOT NULL,
  `hero_faction` varchar(100) NOT NULL,
  `balance` decimal(11,4) NOT NULL,
  `credit` int(11) NOT NULL,
  `credit_limit` decimal(11,4) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `discord_id`, `email`, `username`, `roles`, `hero_name`, `hero_realm`, `hero_faction`, `balance`, `credit`, `credit_limit`, `created_at`) VALUES
(1, '675309510491111443', NULL, NULL, 'advertiser,mail,tank,dps,heal,tank,plate,leather,cloth,mail', 'ghazgoli', 'dreanor', 'horde', 3202.2800, 0, 0.0000, '2023-12-19 15:04:09'),
(3, '1081147280180125757', NULL, NULL, 'mail,dps', 'asdfsaf', 'deranor', 'horde', 10979.6400, 200, 2000.0000, '2023-12-20 16:03:49'),
(4, '850855005459841044', NULL, NULL, 'tank,dps,heal,tank,dps,heal,tank,dps,heal,leather,advertiser', '', '', '', 0.0000, 0, 0.0000, '2024-01-13 11:10:33');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `discord_heroes`
--
ALTER TABLE `discord_heroes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_character` (`name`,`realm`);

--
-- Indexes for table `discord_users`
--
ALTER TABLE `discord_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `discord_id` (`discord_id`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `event_participants`
--
ALTER TABLE `event_participants`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_3` (`id`),
  ADD KEY `id` (`id`),
  ADD KEY `id_2` (`id`);

--
-- Indexes for table `event_registrations`
--
ALTER TABLE `event_registrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mplus_channel_logs`
--
ALTER TABLE `mplus_channel_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mplus_orders`
--
ALTER TABLE `mplus_orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `order_id` (`order_id`),
  ADD UNIQUE KEY `channel_id` (`channel_id`),
  ADD UNIQUE KEY `channel_id_2` (`channel_id`);

--
-- Indexes for table `payment_report`
--
ALTER TABLE `payment_report`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `rank_thresholds`
--
ALTER TABLE `rank_thresholds`
  ADD PRIMARY KEY (`role`,`level`);

--
-- Indexes for table `sales`
--
ALTER TABLE `sales`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `server_info`
--
ALTER TABLE `server_info`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `team_input_labels`
--
ALTER TABLE `team_input_labels`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `discord_id` (`discord_id`),
  ADD KEY `idx_discord_id` (`discord_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `discord_heroes`
--
ALTER TABLE `discord_heroes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

--
-- AUTO_INCREMENT for table `discord_users`
--
ALTER TABLE `discord_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `event_registrations`
--
ALTER TABLE `event_registrations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `mplus_channel_logs`
--
ALTER TABLE `mplus_channel_logs`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=129;

--
-- AUTO_INCREMENT for table `mplus_orders`
--
ALTER TABLE `mplus_orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- AUTO_INCREMENT for table `payment_report`
--
ALTER TABLE `payment_report`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `sales`
--
ALTER TABLE `sales`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `server_info`
--
ALTER TABLE `server_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `team_input_labels`
--
ALTER TABLE `team_input_labels`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
