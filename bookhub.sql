-- phpMyAdmin SQL Dump
-- version 5.1.2
-- https://www.phpmyadmin.net/
--
-- Хост: localhost:3306
-- Время создания: Май 07 2025 г., 08:34
-- Версия сервера: 5.7.24
-- Версия PHP: 8.3.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `bookhub`
--

-- --------------------------------------------------------

--
-- Структура таблицы `posts`
--

CREATE TABLE `posts` (
  `id` int(11) NOT NULL,
  `title` varchar(50) NOT NULL,
  `text` text NOT NULL,
  `category` enum('Классическая литература','Современная литература','Научная фантастика','Фэнтези','Детективы') NOT NULL,
  `deleted` tinyint(1) NOT NULL,
  `author` int(11) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `changed` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `posts`
--

INSERT INTO `posts` (`id`, `title`, `text`, `category`, `deleted`, `author`, `timestamp`, `changed`) VALUES
(1, 'Война и мир', 'Эпический роман Льва Толстого о войне 1812 года...', 'Классическая литература', 0, 1, '2025-05-07 05:39:38', 0),
(2, '1984', 'Роман-антиутопия Джорджа Оруэлла о тоталитарном обществе...', 'Научная фантастика', 0, 2, '2025-05-07 05:39:38', 0),
(3, 'Гарри Поттер и философский камень', 'Первая книга о юном волшебнике Гарри Поттере...', 'Фэнтези', 0, 3, '2025-05-07 05:39:38', 0),
(4, 'Убийство в Восточном экспрессе', 'Знаменитый детектив Эркюля Пуаро...', 'Детективы', 0, 4, '2025-05-07 05:39:38', 0),
(5, 'Нормальные люди', 'Современный роман о сложных отношениях двух молодых людей...', 'Современная литература', 0, 5, '2025-05-07 05:39:38', 0),
(6, 'Преступление и наказание', 'Классический роман Ф.М. Достоевского о преступлении и его последствиях...', 'Классическая литература', 1, 6, '2025-05-07 05:39:38', 0),
(7, 'Дюна', 'Эпическая научно-фантастическая сага о пустынной планете...', 'Научная фантастика', 1, 7, '2025-05-07 07:36:41', 0),
(8, 'Властелин колец', 'Эпическая трилогия о Средиземье и Кольце Всевластья...', 'Фэнтези', 0, 8, '2025-05-07 05:39:38', 0),
(9, 'Десять негритят', 'Классический детектив об убийствах на острове...', 'Детективы', 0, 4, '2025-05-07 05:39:38', 0),
(10, 'Шантарам', 'Современный роман о жизни в Индии...', 'Современная литература', 1, 9, '2025-05-07 05:39:38', 0),
(11, 'чиичсичсичч', 'чмчсмчсичичсмичсмвпяявия иявк якпк рр вяр рявр ваяп апав ва пварв а', 'Классическая литература', 1, 7, '2025-05-07 08:34:11', 0),
(12, 'т текст текст текст текст текст текст текст', 'текст текст текст текст текст текст текст текст текст текст текст текст текст текст текст текст текст текст', 'Современная литература', 1, 7, '2025-05-07 08:34:09', 0);

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(40) NOT NULL,
  `login` varchar(20) NOT NULL,
  `password` varchar(120) NOT NULL,
  `email` varchar(60) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `username`, `login`, `password`, `email`) VALUES
(4, 'alex', 'alex', '$2y$10$AYqdo1GvgvExxXmDsY4i5.JCZoFCOVzLZzvr6XVnd066MP5aFdc3q', ''),
(7, 'bob', 'bob', '$2y$10$Pn1llxBGqzKcbuSxMbqupu3.y.3X9xk6o1zOve.RNG6u.qJaFmjgS', '');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
