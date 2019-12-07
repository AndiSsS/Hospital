-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1
-- Время создания: Ноя 28 2019 г., 01:36
-- Версия сервера: 10.4.6-MariaDB
-- Версия PHP: 7.3.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `hospital`
--

-- --------------------------------------------------------

--
-- Структура таблицы `apparatuses`
--

CREATE TABLE `apparatuses` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `doctor_id` int(11) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `apparatuses`
--

INSERT INTO `apparatuses` (`id`, `name`, `doctor_id`, `is_active`) VALUES
(1, 'Тонометр', 1, 1),
(2, 'Спирометр', 2, 1);

-- --------------------------------------------------------

--
-- Структура таблицы `beds`
--

CREATE TABLE `beds` (
  `id` int(11) NOT NULL,
  `number` int(11) NOT NULL,
  `chamber_id` int(11) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `beds`
--

INSERT INTO `beds` (`id`, `number`, `chamber_id`, `is_active`) VALUES
(1, 1, 1, 1),
(2, 2, 1, 1),
(3, 3, 2, 1),
(4, 4, 2, 1);

-- --------------------------------------------------------

--
-- Структура таблицы `chambers`
--

CREATE TABLE `chambers` (
  `id` int(11) NOT NULL,
  `number` int(11) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `chambers`
--

INSERT INTO `chambers` (`id`, `number`, `is_active`) VALUES
(1, 1, 1),
(2, 2, 1),
(3, 3, 0),
(4, 4, 0),
(5, 5, 0);

-- --------------------------------------------------------

--
-- Структура таблицы `diseases`
--

CREATE TABLE `diseases` (
  `id` int(11) NOT NULL,
  `name` varchar(400) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `diseases`
--

INSERT INTO `diseases` (`id`, `name`, `is_active`) VALUES
(1, 'Рахит', 1),
(2, 'Корь', 1),
(5, 'Атеросклероз', 1),
(6, 'Ожирение', 1);

-- --------------------------------------------------------

--
-- Структура таблицы `doctors`
--

CREATE TABLE `doctors` (
  `id` int(10) NOT NULL,
  `name` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `surname` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `patronymic` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `mobile_number` varchar(15) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `doctors`
--

INSERT INTO `doctors` (`id`, `name`, `surname`, `patronymic`, `mobile_number`, `is_active`) VALUES
(1, 'Сергей', 'Смирнов', 'Иванович', '34654312135', 1),
(2, 'Максим', 'Кузнецов', 'Владимирович', '84651532135', 1),
(6, 'placeholder', 'placeholder', 'placeholder', 'placeholder', 0);

-- --------------------------------------------------------

--
-- Структура таблицы `drugs`
--

CREATE TABLE `drugs` (
  `id` int(11) NOT NULL,
  `name` varchar(400) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `provider_id` int(11) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `drugs`
--

INSERT INTO `drugs` (`id`, `name`, `provider_id`, `is_active`) VALUES
(1, 'Валокордин ', 2, 1),
(2, 'Викс АнтиГрипп', 1, 1),
(3, 'Имедин ', 1, 1),
(4, 'Постеризан', 2, 1);

-- --------------------------------------------------------

--
-- Структура таблицы `expendable_materials`
--

CREATE TABLE `expendable_materials` (
  `id` int(11) NOT NULL,
  `name` varchar(200) NOT NULL,
  `quantity` int(11) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `expendable_materials`
--

INSERT INTO `expendable_materials` (`id`, `name`, `quantity`, `is_active`) VALUES
(1, 'Бинт', 2, 1),
(2, 'Спирт', 5, 1);

-- --------------------------------------------------------

--
-- Структура таблицы `journal`
--

CREATE TABLE `journal` (
  `id` int(11) NOT NULL,
  `date` datetime NOT NULL DEFAULT current_timestamp(),
  `quantity` int(11) NOT NULL DEFAULT 1,
  `drug_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL DEFAULT 7,
  `doctor_id` int(11) NOT NULL DEFAULT 6,
  `type` enum('intake','outgo') NOT NULL DEFAULT 'intake'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `journal`
--

INSERT INTO `journal` (`id`, `date`, `quantity`, `drug_id`, `patient_id`, `doctor_id`, `type`) VALUES
(26, '2019-10-17 22:58:11', 2, 2, 7, 6, 'intake'),
(27, '2019-10-17 22:58:26', 2, 2, 1, 1, 'outgo');

-- --------------------------------------------------------

--
-- Структура таблицы `patients`
--

CREATE TABLE `patients` (
  `id` int(10) NOT NULL,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `surname` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `patronymic` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `disease_id` int(11) NOT NULL,
  `doctor_id` int(11) NOT NULL,
  `chamber_id` int(11) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп данных таблицы `patients`
--

INSERT INTO `patients` (`id`, `name`, `surname`, `patronymic`, `disease_id`, `doctor_id`, `chamber_id`, `is_active`) VALUES
(1, 'Андрей', 'Макаров', 'Петрович', 2, 2, 1, 1),
(2, 'Олег', 'Захаров', 'Сергеевич', 1, 1, 2, 1),
(3, 'Роман', 'Морозов', 'Андреевич', 6, 2, 1, 1),
(4, 'Леонид', 'Михайлов', 'Степанович', 5, 1, 2, 1),
(7, 'placeholder', 'placeholder', 'placeholder', 1, 1, 1, 0);

-- --------------------------------------------------------

--
-- Структура таблицы `providers`
--

CREATE TABLE `providers` (
  `id` int(11) NOT NULL,
  `name` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `providers`
--

INSERT INTO `providers` (`id`, `name`, `is_active`) VALUES
(1, 'Аметрин', 1),
(2, 'БАДМ', 1);

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `apparatuses`
--
ALTER TABLE `apparatuses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `doctor_id` (`doctor_id`);

--
-- Индексы таблицы `beds`
--
ALTER TABLE `beds`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `chambers`
--
ALTER TABLE `chambers`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `diseases`
--
ALTER TABLE `diseases`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `doctors`
--
ALTER TABLE `doctors`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `mobile_number` (`mobile_number`);

--
-- Индексы таблицы `drugs`
--
ALTER TABLE `drugs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `provider_id` (`provider_id`);

--
-- Индексы таблицы `expendable_materials`
--
ALTER TABLE `expendable_materials`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `journal`
--
ALTER TABLE `journal`
  ADD PRIMARY KEY (`id`),
  ADD KEY `patient_id` (`patient_id`),
  ADD KEY `doctor_id` (`doctor_id`),
  ADD KEY `drug_id` (`drug_id`);

--
-- Индексы таблицы `patients`
--
ALTER TABLE `patients`
  ADD PRIMARY KEY (`id`),
  ADD KEY `disease_id` (`disease_id`),
  ADD KEY `doctor_id` (`doctor_id`),
  ADD KEY `patients_chamber_id` (`chamber_id`);

--
-- Индексы таблицы `providers`
--
ALTER TABLE `providers`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `apparatuses`
--
ALTER TABLE `apparatuses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT для таблицы `beds`
--
ALTER TABLE `beds`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT для таблицы `chambers`
--
ALTER TABLE `chambers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT для таблицы `diseases`
--
ALTER TABLE `diseases`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT для таблицы `doctors`
--
ALTER TABLE `doctors`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT для таблицы `drugs`
--
ALTER TABLE `drugs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT для таблицы `expendable_materials`
--
ALTER TABLE `expendable_materials`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT для таблицы `journal`
--
ALTER TABLE `journal`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT для таблицы `patients`
--
ALTER TABLE `patients`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT для таблицы `providers`
--
ALTER TABLE `providers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `apparatuses`
--
ALTER TABLE `apparatuses`
  ADD CONSTRAINT `doctor_id` FOREIGN KEY (`doctor_id`) REFERENCES `doctors` (`id`) ON UPDATE NO ACTION;

--
-- Ограничения внешнего ключа таблицы `drugs`
--
ALTER TABLE `drugs`
  ADD CONSTRAINT `drugs_ibfk_1` FOREIGN KEY (`provider_id`) REFERENCES `providers` (`id`) ON UPDATE NO ACTION;

--
-- Ограничения внешнего ключа таблицы `journal`
--
ALTER TABLE `journal`
  ADD CONSTRAINT `journal_ibfk_1` FOREIGN KEY (`drug_id`) REFERENCES `drugs` (`id`) ON UPDATE NO ACTION,
  ADD CONSTRAINT `journal_ibfk_2` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON UPDATE NO ACTION,
  ADD CONSTRAINT `journal_ibfk_3` FOREIGN KEY (`doctor_id`) REFERENCES `doctors` (`id`) ON UPDATE NO ACTION;

--
-- Ограничения внешнего ключа таблицы `patients`
--
ALTER TABLE `patients`
  ADD CONSTRAINT `patients_chamber_id` FOREIGN KEY (`chamber_id`) REFERENCES `chambers` (`id`) ON UPDATE NO ACTION,
  ADD CONSTRAINT `patients_doctor_id` FOREIGN KEY (`doctor_id`) REFERENCES `doctors` (`id`) ON UPDATE NO ACTION,
  ADD CONSTRAINT `patients_ibfk_1` FOREIGN KEY (`disease_id`) REFERENCES `diseases` (`id`) ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
