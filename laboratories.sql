-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1
-- Время создания: Июл 05 2022 г., 11:48
-- Версия сервера: 10.4.19-MariaDB
-- Версия PHP: 8.0.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `laboratories`
--
CREATE DATABASE IF NOT EXISTS `laboratories` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `laboratories`;

-- --------------------------------------------------------

--
-- Структура таблицы `attribute_list_values`
--

CREATE TABLE `attribute_list_values` (
  `id` int(11) NOT NULL,
  `attribute` int(11) NOT NULL,
  `value` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Триггеры `attribute_list_values`
--
DELIMITER $$
CREATE TRIGGER `DeleteLabAttVal` BEFORE DELETE ON `attribute_list_values` FOR EACH ROW DELETE FROM lab_attributes_values WHERE lab_attributes_values.value=old.id
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Структура таблицы `equipments`
--

CREATE TABLE `equipments` (
  `id` int(11) NOT NULL,
  `name` varchar(25) NOT NULL,
  `enabled` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Триггеры `equipments`
--
DELIMITER $$
CREATE TRIGGER `AttRemove` BEFORE DELETE ON `equipments` FOR EACH ROW DELETE FROM equipments_attributes where equipments_attributes.equipment = old.id
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `Eq_Log_Add` AFTER UPDATE ON `equipments` FOR EACH ROW INSERT INTO logs Set logs._table = 'equ', logs.element = old.id
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Структура таблицы `equipments_attributes`
--

CREATE TABLE `equipments_attributes` (
  `id` int(11) NOT NULL,
  `equipment` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `type` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Триггеры `equipments_attributes`
--
DELIMITER $$
CREATE TRIGGER `AttValDelete` BEFORE DELETE ON `equipments_attributes` FOR EACH ROW DELETE FROM attribute_list_values WHERE attribute_list_values.attribute=old.id
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `Att_Log_Add` AFTER UPDATE ON `equipments_attributes` FOR EACH ROW INSERT INTO logs Set logs._table = 'equ', logs.element = old.equipment
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `ChangedType` AFTER UPDATE ON `equipments_attributes` FOR EACH ROW IF new.type = 1 THEN
DELETE FROM attribute_list_values WHERE attribute_list_values.attribute=old.id;
END IF
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `LabAttValDelete` BEFORE DELETE ON `equipments_attributes` FOR EACH ROW DELETE FROM lab_attributes_values WHERE lab_attributes_values.attribute=old.id
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Структура таблицы `keys`
--

CREATE TABLE `keys` (
  `id` int(11) NOT NULL,
  `action` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Дамп данных таблицы `keys`
--

INSERT INTO `keys` (`id`, `action`) VALUES
(1, 'Вывести наименование'),
(2, 'Вывести тип'),
(3, 'Вывести подтип'),
(4, 'Вывести ответственных'),
(5, 'Вывести описание'),
(6, 'Вывести оборудование '),
(7, 'Вывести программное обеспечение'),
(8, 'Вывести фото'),
(9, 'Вывести характеристики оборудования');

-- --------------------------------------------------------

--
-- Структура таблицы `keys_passports`
--

CREATE TABLE `keys_passports` (
  `id` int(11) NOT NULL,
  `key` int(11) NOT NULL,
  `mask` varchar(20) NOT NULL,
  `passport` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Структура таблицы `laboratories`
--

CREATE TABLE `laboratories` (
  `id` int(11) NOT NULL,
  `name` varchar(20) NOT NULL,
  `type` int(11) NOT NULL,
  `sub_type` int(11) NOT NULL,
  `descr` text DEFAULT NULL,
  `image` text DEFAULT NULL,
  `enabled` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Триггеры `laboratories`
--
DELIMITER $$
CREATE TRIGGER `Lab_Log_Add` AFTER UPDATE ON `laboratories` FOR EACH ROW INSERT INTO logs Set logs._table = 'lab', logs.element = old.id
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Структура таблицы `labs_equipments`
--

CREATE TABLE `labs_equipments` (
  `id` int(11) NOT NULL,
  `laboratory` int(11) NOT NULL,
  `equipment` int(11) NOT NULL,
  `amount` int(11) NOT NULL DEFAULT 1,
  `teacher` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Триггеры `labs_equipments`
--
DELIMITER $$
CREATE TRIGGER `LabAttDelete` BEFORE DELETE ON `labs_equipments` FOR EACH ROW DELETE FROM lab_attributes_values WHERE lab_attributes_values.lab_equipment=old.id
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `LabEq_Log_Add` AFTER UPDATE ON `labs_equipments` FOR EACH ROW INSERT INTO logs Set logs._table = 'l-e', logs.element = old.id
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Структура таблицы `labs_responsibles`
--

CREATE TABLE `labs_responsibles` (
  `id` int(11) NOT NULL,
  `laboratory` int(11) NOT NULL,
  `responsible` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Структура таблицы `labs_software`
--

CREATE TABLE `labs_software` (
  `id` int(11) NOT NULL,
  `laboratory` int(11) NOT NULL,
  `soft_ver` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Триггеры `labs_software`
--
DELIMITER $$
CREATE TRIGGER `LabSoft_Log_Add` AFTER UPDATE ON `labs_software` FOR EACH ROW INSERT INTO logs Set logs._table = 'lab', logs.element = old.laboratory
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Структура таблицы `lab_attributes_values`
--

CREATE TABLE `lab_attributes_values` (
  `id` int(11) NOT NULL,
  `lab_equipment` int(11) NOT NULL,
  `attribute` int(11) NOT NULL,
  `value` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Триггеры `lab_attributes_values`
--
DELIMITER $$
CREATE TRIGGER `AttVal_Log_Add` AFTER UPDATE ON `lab_attributes_values` FOR EACH ROW INSERT INTO logs Set logs._table = 'l-e', logs.element = old.lab_equipment
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Структура таблицы `logs`
--

CREATE TABLE `logs` (
  `id` int(11) NOT NULL,
  `_table` varchar(4) NOT NULL,
  `element` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Дамп данных таблицы `logs`
--

INSERT INTO `logs` (`id`, `_table`, `element`) VALUES
(1, 'usr', 1),
(2, 'usr', 1);

-- --------------------------------------------------------

--
-- Структура таблицы `passports`
--

CREATE TABLE `passports` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `creator` int(11) NOT NULL,
  `file` text NOT NULL,
  `enabled` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Триггеры `passports`
--
DELIMITER $$
CREATE TRIGGER `PspLogAdd` AFTER UPDATE ON `passports` FOR EACH ROW INSERT INTO logs Set logs._table = 'psp', logs.element = old.id
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Структура таблицы `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `softwares`
--

CREATE TABLE `softwares` (
  `id` int(11) NOT NULL,
  `name` varchar(70) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Триггеры `softwares`
--
DELIMITER $$
CREATE TRIGGER `SoftwareDelete` BEFORE DELETE ON `softwares` FOR EACH ROW DELETE FROM softwares_versions WHERE softwares_versions.software=old.id
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Структура таблицы `softwares_versions`
--

CREATE TABLE `softwares_versions` (
  `id` int(11) NOT NULL,
  `software` int(11) NOT NULL,
  `version` varchar(70) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Структура таблицы `software_template`
--

CREATE TABLE `software_template` (
  `id` int(11) NOT NULL,
  `name` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Структура таблицы `soft_vers_template`
--

CREATE TABLE `soft_vers_template` (
  `id` int(11) NOT NULL,
  `soft_vers` int(11) NOT NULL,
  `soft_template` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Структура таблицы `sub-types`
--

CREATE TABLE `sub-types` (
  `id` int(11) NOT NULL,
  `type` int(11) NOT NULL,
  `name` varchar(70) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Структура таблицы `types`
--

CREATE TABLE `types` (
  `id` int(11) NOT NULL,
  `name` varchar(70) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` varchar(80) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `surname` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `patronymic` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `role` tinyint(1) NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `enabled` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `email`, `password`, `surname`, `name`, `patronymic`, `role`, `remember_token`, `enabled`) VALUES
(1, 'admin@gmail.com', '$2y$10$v333f8DJPv2Ul9aScIfcBOcR60dyanPSewDCqMlVwTG0QSZ2fP7YW', 'change-me', 'change-me', 'change-me', 1, '1FcP5wQ0HEPG8nNVRR9H2UcipTU7D4aR1RZ11FPTaN5PYVeNTkbUVh8BoWv3', 1);

--
-- Триггеры `users`
--
DELIMITER $$
CREATE TRIGGER `LogUserAdd` AFTER UPDATE ON `users` FOR EACH ROW INSERT INTO logs Set logs._table = 'usr', logs.element = old.id
$$
DELIMITER ;

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `attribute_list_values`
--
ALTER TABLE `attribute_list_values`
  ADD PRIMARY KEY (`id`),
  ADD KEY `attribute` (`attribute`);

--
-- Индексы таблицы `equipments`
--
ALTER TABLE `equipments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Индексы таблицы `equipments_attributes`
--
ALTER TABLE `equipments_attributes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `equipment` (`equipment`);

--
-- Индексы таблицы `keys`
--
ALTER TABLE `keys`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Индексы таблицы `keys_passports`
--
ALTER TABLE `keys_passports`
  ADD PRIMARY KEY (`id`),
  ADD KEY `passport` (`passport`),
  ADD KEY `keys_passports_ibfk_1` (`key`);

--
-- Индексы таблицы `laboratories`
--
ALTER TABLE `laboratories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`),
  ADD KEY `sub-type` (`sub_type`),
  ADD KEY `laboratories_ibfk_1` (`sub_type`),
  ADD KEY `type` (`type`);

--
-- Индексы таблицы `labs_equipments`
--
ALTER TABLE `labs_equipments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `laboratory` (`laboratory`),
  ADD KEY `equipment` (`equipment`);

--
-- Индексы таблицы `labs_responsibles`
--
ALTER TABLE `labs_responsibles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `laboratory` (`laboratory`),
  ADD KEY `responsible` (`responsible`);

--
-- Индексы таблицы `labs_software`
--
ALTER TABLE `labs_software`
  ADD PRIMARY KEY (`id`),
  ADD KEY `laboratory` (`laboratory`),
  ADD KEY `soft_ver` (`soft_ver`);

--
-- Индексы таблицы `lab_attributes_values`
--
ALTER TABLE `lab_attributes_values`
  ADD PRIMARY KEY (`id`),
  ADD KEY `lab_equipment` (`lab_equipment`),
  ADD KEY `attribute` (`attribute`);

--
-- Индексы таблицы `logs`
--
ALTER TABLE `logs`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `passports`
--
ALTER TABLE `passports`
  ADD PRIMARY KEY (`id`),
  ADD KEY `creator` (`creator`);

--
-- Индексы таблицы `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Индексы таблицы `softwares`
--
ALTER TABLE `softwares`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Индексы таблицы `softwares_versions`
--
ALTER TABLE `softwares_versions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `software` (`software`),
  ADD KEY `version` (`version`);

--
-- Индексы таблицы `software_template`
--
ALTER TABLE `software_template`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `soft_vers_template`
--
ALTER TABLE `soft_vers_template`
  ADD PRIMARY KEY (`id`),
  ADD KEY `soft_vers` (`soft_vers`),
  ADD KEY `soft_template` (`soft_template`);

--
-- Индексы таблицы `sub-types`
--
ALTER TABLE `sub-types`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`),
  ADD KEY `type` (`type`);

--
-- Индексы таблицы `types`
--
ALTER TABLE `types`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `attribute_list_values`
--
ALTER TABLE `attribute_list_values`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `equipments`
--
ALTER TABLE `equipments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `equipments_attributes`
--
ALTER TABLE `equipments_attributes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `keys`
--
ALTER TABLE `keys`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT для таблицы `keys_passports`
--
ALTER TABLE `keys_passports`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `laboratories`
--
ALTER TABLE `laboratories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `labs_equipments`
--
ALTER TABLE `labs_equipments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `labs_responsibles`
--
ALTER TABLE `labs_responsibles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `labs_software`
--
ALTER TABLE `labs_software`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `lab_attributes_values`
--
ALTER TABLE `lab_attributes_values`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `logs`
--
ALTER TABLE `logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT для таблицы `passports`
--
ALTER TABLE `passports`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `softwares`
--
ALTER TABLE `softwares`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `softwares_versions`
--
ALTER TABLE `softwares_versions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `software_template`
--
ALTER TABLE `software_template`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `soft_vers_template`
--
ALTER TABLE `soft_vers_template`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `sub-types`
--
ALTER TABLE `sub-types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `types`
--
ALTER TABLE `types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `attribute_list_values`
--
ALTER TABLE `attribute_list_values`
  ADD CONSTRAINT `attribute_list_values_ibfk_1` FOREIGN KEY (`attribute`) REFERENCES `equipments_attributes` (`id`);

--
-- Ограничения внешнего ключа таблицы `equipments_attributes`
--
ALTER TABLE `equipments_attributes`
  ADD CONSTRAINT `equipments_attributes_ibfk_1` FOREIGN KEY (`equipment`) REFERENCES `equipments` (`id`);

--
-- Ограничения внешнего ключа таблицы `keys_passports`
--
ALTER TABLE `keys_passports`
  ADD CONSTRAINT `keys_passports_ibfk_1` FOREIGN KEY (`key`) REFERENCES `keys` (`id`),
  ADD CONSTRAINT `keys_passports_ibfk_2` FOREIGN KEY (`passport`) REFERENCES `passports` (`id`);

--
-- Ограничения внешнего ключа таблицы `laboratories`
--
ALTER TABLE `laboratories`
  ADD CONSTRAINT `laboratories_ibfk_2` FOREIGN KEY (`sub_type`) REFERENCES `sub-types` (`id`),
  ADD CONSTRAINT `laboratories_ibfk_3` FOREIGN KEY (`type`) REFERENCES `types` (`id`);

--
-- Ограничения внешнего ключа таблицы `labs_equipments`
--
ALTER TABLE `labs_equipments`
  ADD CONSTRAINT `labs_equipments_ibfk_1` FOREIGN KEY (`laboratory`) REFERENCES `laboratories` (`id`),
  ADD CONSTRAINT `labs_equipments_ibfk_2` FOREIGN KEY (`equipment`) REFERENCES `equipments` (`id`);

--
-- Ограничения внешнего ключа таблицы `labs_responsibles`
--
ALTER TABLE `labs_responsibles`
  ADD CONSTRAINT `labs_responsibles_ibfk_1` FOREIGN KEY (`laboratory`) REFERENCES `laboratories` (`id`),
  ADD CONSTRAINT `labs_responsibles_ibfk_2` FOREIGN KEY (`responsible`) REFERENCES `users` (`id`);

--
-- Ограничения внешнего ключа таблицы `labs_software`
--
ALTER TABLE `labs_software`
  ADD CONSTRAINT `labs_software_ibfk_1` FOREIGN KEY (`laboratory`) REFERENCES `laboratories` (`id`),
  ADD CONSTRAINT `labs_software_ibfk_2` FOREIGN KEY (`soft_ver`) REFERENCES `softwares_versions` (`id`);

--
-- Ограничения внешнего ключа таблицы `lab_attributes_values`
--
ALTER TABLE `lab_attributes_values`
  ADD CONSTRAINT `lab_attributes_values_ibfk_1` FOREIGN KEY (`lab_equipment`) REFERENCES `labs_equipments` (`id`),
  ADD CONSTRAINT `lab_attributes_values_ibfk_2` FOREIGN KEY (`attribute`) REFERENCES `equipments_attributes` (`id`);

--
-- Ограничения внешнего ключа таблицы `passports`
--
ALTER TABLE `passports`
  ADD CONSTRAINT `passports_ibfk_1` FOREIGN KEY (`creator`) REFERENCES `users` (`id`);

--
-- Ограничения внешнего ключа таблицы `softwares_versions`
--
ALTER TABLE `softwares_versions`
  ADD CONSTRAINT `softwares_versions_ibfk_1` FOREIGN KEY (`software`) REFERENCES `softwares` (`id`);

--
-- Ограничения внешнего ключа таблицы `soft_vers_template`
--
ALTER TABLE `soft_vers_template`
  ADD CONSTRAINT `soft_vers_template_ibfk_1` FOREIGN KEY (`soft_vers`) REFERENCES `softwares_versions` (`id`),
  ADD CONSTRAINT `soft_vers_template_ibfk_2` FOREIGN KEY (`soft_template`) REFERENCES `software_template` (`id`);

--
-- Ограничения внешнего ключа таблицы `sub-types`
--
ALTER TABLE `sub-types`
  ADD CONSTRAINT `sub-types_ibfk_1` FOREIGN KEY (`type`) REFERENCES `types` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
