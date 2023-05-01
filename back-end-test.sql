-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Май 01 2023 г., 08:21
-- Версия сервера: 8.0.30
-- Версия PHP: 8.0.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `back-end-test`
--

-- --------------------------------------------------------

--
-- Структура таблицы `companies`
--

CREATE TABLE `companies` (
  `id` int NOT NULL,
  `name` varchar(255) NOT NULL,
  `website` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `phone` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `companies`
--

INSERT INTO `companies` (`id`, `name`, `website`, `address`, `phone`) VALUES
(1, 'Company #1', 'www.company#1.com', '7051 Littleton Street Statesville, NC 28625', '+380000000000'),
(2, 'Company #2', 'www.company#2.com', '390 Honey Creek Lane Mahopac, NY 10541', '+380900000000'),
(3, 'Company #3', 'www.company#3.com', '34 Sherman Road Baton Rouge, LA 70806', '+380970000000');

-- --------------------------------------------------------

--
-- Структура таблицы `resumes`
--

CREATE TABLE `resumes` (
  `id` int NOT NULL,
  `position` varchar(255) NOT NULL,
  `resume_text` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `file_path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `resumes`
--

INSERT INTO `resumes` (`id`, `position`, `resume_text`, `file_path`, `created_at`, `updated_at`) VALUES
(1, 'Front-end Developer', NULL, '644f4bcab18a0.pdf', '2023-05-01 08:19:06', '2023-05-01 08:19:06'),
(2, 'Back-end Developer', '*Resume Text*', NULL, '2023-05-01 08:19:53', '2023-05-01 08:19:53');

-- --------------------------------------------------------

--
-- Структура таблицы `sent_resumes`
--

CREATE TABLE `sent_resumes` (
  `id` int NOT NULL,
  `resume_id` int NOT NULL,
  `company_id` int NOT NULL,
  `sent_at` datetime NOT NULL,
  `response` enum('positive','negative','none') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'none'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `sent_resumes`
--

INSERT INTO `sent_resumes` (`id`, `resume_id`, `company_id`, `sent_at`, `response`) VALUES
(1, 1, 2, '2023-05-01 08:20:08', 'none'),
(2, 2, 3, '2023-05-01 08:20:16', 'none');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `companies`
--
ALTER TABLE `companies`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `resumes`
--
ALTER TABLE `resumes`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `sent_resumes`
--
ALTER TABLE `sent_resumes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `company_id` (`company_id`),
  ADD KEY `resume_id` (`resume_id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `companies`
--
ALTER TABLE `companies`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT для таблицы `resumes`
--
ALTER TABLE `resumes`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT для таблицы `sent_resumes`
--
ALTER TABLE `sent_resumes`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `sent_resumes`
--
ALTER TABLE `sent_resumes`
  ADD CONSTRAINT `sent_resumes_ibfk_1` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `sent_resumes_ibfk_2` FOREIGN KEY (`resume_id`) REFERENCES `resumes` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
