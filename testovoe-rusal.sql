SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


--
-- База данных: `testovoe-rusal`
--

-- --------------------------------------------------------

--
-- Структура таблицы `Documents`
--

CREATE TABLE `Documents` (
  `Id` bigint NOT NULL,
  `Tender_TenderNumber` varchar(255) NOT NULL,
  `Name` varchar(255) NOT NULL,
  `Link` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Структура таблицы `Tenders`
--

CREATE TABLE `Tenders` (
  `Id` bigint NOT NULL,
  `TenderNumber` varchar(255) NOT NULL,
  `OrganizerName` varchar(255) NOT NULL,
  `TenderViewUrl` varchar(255) NOT NULL,
  `RequestReceivingBeginDate` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `Documents`
--
ALTER TABLE `Documents`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `Tender_TenderNumber` (`Tender_TenderNumber`);

--
-- Индексы таблицы `Tenders`
--
ALTER TABLE `Tenders`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `TenderNumber` (`TenderNumber`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `Documents`
--
ALTER TABLE `Documents`
  MODIFY `Id` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT для таблицы `Tenders`
--
ALTER TABLE `Tenders`
  MODIFY `Id` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `Documents`
--
ALTER TABLE `Documents`
  ADD CONSTRAINT `documents_ibfk_1` FOREIGN KEY (`Tender_TenderNumber`) REFERENCES `Tenders` (`TenderNumber`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;