-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Авг 22 2022 г., 09:18
-- Версия сервера: 5.7.29
-- Версия PHP: 7.4.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `test_mira`
--

-- --------------------------------------------------------

--
-- Структура таблицы `cards`
--

CREATE TABLE `cards` (
  `id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `body` varchar(255) NOT NULL,
  `user` enum('Директор','Менеджер','Виконавець') NOT NULL,
  `button` enum('Кнопка boss','Кнопка manager','Кнопка performer') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `position` enum('Директор','Менеджер','Виконавець') NOT NULL,
  `password_hash` varchar(100) NOT NULL,
  `auth_token` varchar(100) NOT NULL,
  `is_confirmed` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `email`, `position`, `password_hash`, `auth_token`, `is_confirmed`, `created_at`) VALUES
(1, 'boss@mail.com', 'Директор', '$2y$10$rojB5nZNeSaErFDKVxAaaugCm2V7tA.q7YoEZZue86VCnc.5sy0La', '41669a62513fb314c36f3d523f72dd1ee07bf3e88220990eea82d1c582ad089ac1c7d6c76a5288d2', 1, '2022-08-16 10:56:09'),
(2, 'manager@mail.com', 'Менеджер', '$2y$10$rojB5nZNeSaErFDKVxAaaugCm2V7tA.q7YoEZZue86VCnc.5sy0La', 'e172d2ffb2a662c0498f51c3bb989abecc5025bbdb9051170c9eed1e01562df74b3550563f6f51b4', 1, '2022-08-22 01:10:17'),
(3, 'performer@mail.com', 'Виконавець', '$2y$10$rojB5nZNeSaErFDKVxAaaugCm2V7tA.q7YoEZZue86VCnc.5sy0La', '901f346e09455527f24499747b284756491eb4d9bce070a9a76219db0ca333249d7873f78b510037', 1, '2022-08-22 01:10:40'),
(4, 'manager2@mail.com', 'Менеджер', '$2y$10$rojB5nZNeSaErFDKVxAaaugCm2V7tA.q7YoEZZue86VCnc.5sy0La', 'dd0ee7c67a0d1afaa263b417f7b04627f70acd0c82f19641cae7a042070a0ecb9ef58f9f3adc09d2', 1, '2022-08-22 01:18:36'),
(5, 'performer2@mail.com', 'Виконавець', '$2y$10$rojB5nZNeSaErFDKVxAaaugCm2V7tA.q7YoEZZue86VCnc.5sy0La', 'd883c8e7583ecb465daac8be266b55d0404f795f98ff536d74a0e2beed0358aecdba9c89d51ff2ed', 1, '2022-08-22 01:19:40'),
(6, 'performer3@mail.com', 'Виконавець', '$2y$10$rojB5nZNeSaErFDKVxAaaugCm2V7tA.q7YoEZZue86VCnc.5sy0La', '22a954bc408b3c74ee3a0663681676c6a81036fff5cf3d68b4ddbd3f09ecc46dcbf61f4a9eab24cd', 1, '2022-08-22 01:20:13'),
(7, 'performer4@mail.com', 'Виконавець', '$2y$10$rojB5nZNeSaErFDKVxAaaugCm2V7tA.q7YoEZZue86VCnc.5sy0La', '73db45159b4008d8939a45121b2fa03a0c531e3744b40f5ef841891ef23ce23e549e971d957132b8', 1, '2022-08-22 01:20:13');

-- --------------------------------------------------------

--
-- Структура таблицы `users_activation_code`
--

CREATE TABLE `users_activation_code` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `code` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `cards`
--
ALTER TABLE `cards`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Индексы таблицы `users_activation_code`
--
ALTER TABLE `users_activation_code`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `cards`
--
ALTER TABLE `cards`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT для таблицы `users_activation_code`
--
ALTER TABLE `users_activation_code`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
