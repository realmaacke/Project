-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Värd: 127.0.0.1
-- Tid vid skapande: 30 nov 2021 kl 16:07
-- Serverversion: 10.4.21-MariaDB
-- PHP-version: 8.0.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Databas: `project`
--

-- --------------------------------------------------------

--
-- Tabellstruktur `administrator`
--

CREATE TABLE `administrator` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumpning av Data i tabell `administrator`
--

INSERT INTO `administrator` (`id`, `user_id`) VALUES
(5, 3);

-- --------------------------------------------------------

--
-- Tabellstruktur `comments`
--

CREATE TABLE `comments` (
  `id` int(11) NOT NULL,
  `comment` text NOT NULL,
  `user_id` int(11) NOT NULL,
  `posted_at` int(11) NOT NULL,
  `post_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumpning av Data i tabell `comments`
--

INSERT INTO `comments` (`id`, `comment`, `user_id`, `posted_at`, `post_id`) VALUES
(75, 'Test Comment', 3, 2147483647, 22),
(76, 'Test Comment', 3, 2147483647, 22),
(77, 'Test Comment', 3, 2147483647, 22),
(81, 'dsa', 3, 2147483647, 22),
(152, 'sad', 3, 2147483647, 22),
(153, 'sa', 3, 2147483647, 22),
(155, 's', 3, 2147483647, 2),
(156, 'f', 3, 2147483647, 2),
(157, 'g', 3, 2147483647, 3),
(158, 'g', 3, 2147483647, 3),
(159, 'g', 3, 2147483647, 3),
(160, 's', 3, 2147483647, 22),
(161, 'g', 3, 2147483647, 22);

-- --------------------------------------------------------

--
-- Tabellstruktur `followers`
--

CREATE TABLE `followers` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `follower_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumpning av Data i tabell `followers`
--

INSERT INTO `followers` (`id`, `user_id`, `follower_id`) VALUES
(62, 9, 3),
(67, 6, 4),
(88, 5, 3);

-- --------------------------------------------------------

--
-- Tabellstruktur `login_tokens`
--

CREATE TABLE `login_tokens` (
  `id` int(11) NOT NULL,
  `token` varchar(64) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumpning av Data i tabell `login_tokens`
--

INSERT INTO `login_tokens` (`id`, `token`, `user_id`) VALUES
(11, 'f43a0571dae0d4f78ed2d9c1689842e73431b855', 6),
(60, 'bba46729f2105a186fce519b5fc8c8fcf175df12', 9),
(64, '27d11c471d8523ca115dcc02db3271ce485bbb75', 4),
(65, '6b4980f4680ca984585c56396d6c0b6a8b3cd9f3', 3),
(66, 'c42d639703e13b9f0fc8440a86544ced1708cadf', 3),
(67, '98a30ad990a6d06d7086f16e922bca565485d0c7', 3),
(68, '245f7c5ab1b323fab3dde23abb4736c2a5d8c940', 3);

-- --------------------------------------------------------

--
-- Tabellstruktur `messages`
--

CREATE TABLE `messages` (
  `msg_id` int(11) NOT NULL,
  `outgoing_msg_id` int(11) NOT NULL,
  `incoming_msg_id` int(11) NOT NULL,
  `msg` varchar(1000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumpning av Data i tabell `messages`
--

INSERT INTO `messages` (`msg_id`, `outgoing_msg_id`, `incoming_msg_id`, `msg`) VALUES
(1, 3, 11, 'asd'),
(2, 3, 11, 'dgdhgf'),
(3, 3, 11, 'ds'),
(4, 3, 11, 'fds'),
(5, 3, 11, 'hgj324'),
(6, 4, 3, 'hej'),
(7, 3, 4, 'hej'),
(8, 4, 3, '123'),
(9, 3, 4, 'asd'),
(10, 3, 4, 'asd'),
(11, 4, 11, 'asd'),
(12, 3, 6, 'gvdsx');

-- --------------------------------------------------------

--
-- Tabellstruktur `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) UNSIGNED NOT NULL,
  `type` int(11) UNSIGNED NOT NULL,
  `receiver` int(10) UNSIGNED NOT NULL,
  `sender` int(11) UNSIGNED NOT NULL,
  `extra` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumpning av Data i tabell `notifications`
--

INSERT INTO `notifications` (`id`, `type`, `receiver`, `sender`, `extra`) VALUES
(1, 1, 3, 6, ''),
(2, 1, 6, 6, '');

-- --------------------------------------------------------

--
-- Tabellstruktur `password_tokens`
--

CREATE TABLE `password_tokens` (
  `id` int(11) NOT NULL,
  `token` int(11) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumpning av Data i tabell `password_tokens`
--

INSERT INTO `password_tokens` (`id`, `token`, `user_id`) VALUES
(1, 2, 3),
(2, 0, 3),
(3, 9350, 3),
(4, 1, 3);

-- --------------------------------------------------------

--
-- Tabellstruktur `posts`
--

CREATE TABLE `posts` (
  `id` int(11) NOT NULL,
  `body` varchar(160) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `posted_at` datetime NOT NULL,
  `user_id` int(11) NOT NULL,
  `likes` int(255) NOT NULL,
  `postimg` varchar(255) NOT NULL,
  `topics` varchar(400) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumpning av Data i tabell `posts`
--

INSERT INTO `posts` (`id`, `body`, `posted_at`, `user_id`, `likes`, `postimg`, `topics`) VALUES
(2, 'Verified', '2021-09-28 17:02:21', 5, 1, '', ''),
(3, 'Sup', '2021-09-28 17:02:28', 5, 1, '', ''),
(22, 'First Post #PHP #CODING', '2021-09-29 15:47:07', 6, 1, '', 'PHP,CODING,'),
(53, '', '2021-11-26 18:00:52', 0, 0, '', ''),
(54, 'ads', '2021-11-27 01:06:55', 3, 1, '', ''),
(59, 'sad', '2021-11-28 12:56:49', 3, 1, '', ''),
(60, 'asd #PHP', '2021-11-28 15:29:18', 4, 0, '', 'PHP,');

-- --------------------------------------------------------

--
-- Tabellstruktur `post_likes`
--

CREATE TABLE `post_likes` (
  `id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumpning av Data i tabell `post_likes`
--

INSERT INTO `post_likes` (`id`, `post_id`, `user_id`) VALUES
(370, 0, 3),
(387, 54, 3),
(391, 3, 3),
(393, 59, 3),
(395, 2, 3),
(401, 22, 3);

-- --------------------------------------------------------

--
-- Tabellstruktur `topics`
--

CREATE TABLE `topics` (
  `id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `topics` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumpning av Data i tabell `topics`
--

INSERT INTO `topics` (`id`, `post_id`, `topics`) VALUES
(3, 0, 'PHP,');

-- --------------------------------------------------------

--
-- Tabellstruktur `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(32) NOT NULL,
  `password` varchar(60) NOT NULL,
  `email` text NOT NULL,
  `verified` tinyint(1) NOT NULL DEFAULT 0,
  `profileimg` varchar(255) NOT NULL,
  `colorbanner` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumpning av Data i tabell `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `email`, `verified`, `profileimg`, `colorbanner`) VALUES
(3, 'marcus', '$2y$10$50J7X/rb/tAMTP3V6VhEQusaSLrHkdGGWDm3fjYO1OvJeo6ROXsdq', 'mackan1@telia.com', 1, 'https://i.imgur.com/oQwrodB.png', '#8000ff'),
(4, 'maacke', '$2y$10$xZXhCRuPyPxKfjLEmXQY0eKuvHQH2lpqEDSwfpXxW2e5C/Vp4cJBO', 'mackan1@gmail.com', 1, '', '#80f269'),
(5, 'Verified', '$2y$10$uayWyzjduY770wwLY1wbXeGlQnf99dxiOiExmmujsN3DvsxaK9dzK', 'Verified@combined.com', 1, '', ''),
(6, 'pettersson', '$2y$10$19W6Z87DlN/RDomgYnZS0uY5b/.6iI47MF9R7aWnI5OQRgD5vJmTi', 'pettersson1@telia.com', 0, 'https://i.imgur.com/A7eBOen.jpg', ''),
(7, 'asd', '$2y$10$GXPuzIT/8xDhwCKHwNbx.eBYBBeGddO.Zae.zpeDagr9rlBvoZC.u', 'ads@gmail.com', 0, '', ''),
(8, 'pettersson123', '$2y$10$vsisy4OCTxTBWwG9Et1bN.Z3RShWSEYjyo3rAEKFCcm0n8e3bFipG', 'marcus321@telia.com', 0, '', ''),
(9, 'Guest', '$2y$10$H9tSfsenuBzHJ2tx/y9fn.WDQtoTxA3b29zqPaiDgA7ICUWrhm672', 'guest@gmail.com', 0, '', ''),
(10, 'Fearless', '$2y$10$h7ClsWTQ/zGjnFpb/RKFeePqyqNKdetDpnqGT1mkS3QGI2YYrpe6a', 'Fearless@gmail.com', 0, '', ''),
(11, 'dfhgdfs', '$2y$10$KWmwuCrWNwa1coGSRElMwu/pjoOw7fzlAyNnSjfiwaq5QazGb84QG', 'marcus1@telia.com', 0, '', '');

--
-- Index för dumpade tabeller
--

--
-- Index för tabell `administrator`
--
ALTER TABLE `administrator`
  ADD PRIMARY KEY (`id`);

--
-- Index för tabell `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `comments_ibfk_1` (`user_id`),
  ADD KEY `comments_ibfk_2` (`post_id`);

--
-- Index för tabell `followers`
--
ALTER TABLE `followers`
  ADD PRIMARY KEY (`id`);

--
-- Index för tabell `login_tokens`
--
ALTER TABLE `login_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `token` (`token`),
  ADD KEY `user_id` (`user_id`);

--
-- Index för tabell `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`msg_id`);

--
-- Index för tabell `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`);

--
-- Index för tabell `password_tokens`
--
ALTER TABLE `password_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `token` (`token`);

--
-- Index för tabell `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `posts_ibfk_1` (`user_id`);

--
-- Index för tabell `post_likes`
--
ALTER TABLE `post_likes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `post_id` (`post_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Index för tabell `topics`
--
ALTER TABLE `topics`
  ADD PRIMARY KEY (`id`),
  ADD KEY `topics_ibfk_1` (`post_id`);

--
-- Index för tabell `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT för dumpade tabeller
--

--
-- AUTO_INCREMENT för tabell `administrator`
--
ALTER TABLE `administrator`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT för tabell `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=162;

--
-- AUTO_INCREMENT för tabell `followers`
--
ALTER TABLE `followers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=92;

--
-- AUTO_INCREMENT för tabell `login_tokens`
--
ALTER TABLE `login_tokens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=69;

--
-- AUTO_INCREMENT för tabell `messages`
--
ALTER TABLE `messages`
  MODIFY `msg_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT för tabell `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT för tabell `password_tokens`
--
ALTER TABLE `password_tokens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT för tabell `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT för tabell `post_likes`
--
ALTER TABLE `post_likes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=402;

--
-- AUTO_INCREMENT för tabell `topics`
--
ALTER TABLE `topics`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT för tabell `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Restriktioner för dumpade tabeller
--

--
-- Restriktioner för tabell `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Restriktioner för tabell `login_tokens`
--
ALTER TABLE `login_tokens`
  ADD CONSTRAINT `login_tokens_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Restriktioner för tabell `post_likes`
--
ALTER TABLE `post_likes`
  ADD CONSTRAINT `post_likes_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
