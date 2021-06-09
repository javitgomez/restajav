-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3306
-- Tiempo de generación: 06-06-2021 a las 22:21:07
-- Versión del servidor: 10.3.28-MariaDB-log
-- Versión de PHP: 7.4.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `dbresta`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `address`
--

CREATE TABLE `address` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `cp` int(11) DEFAULT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `number` int(11) DEFAULT NULL,
  `phone` varchar(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `address`
--

INSERT INTO `address` (`id`, `user_id`, `cp`, `address`, `number`, `phone`) VALUES
(6, 37, 3569, 'calle San juan', 21, '65885789');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `aggregate`
--

CREATE TABLE `aggregate` (
  `id` int(11) NOT NULL,
  `dish_id` int(11) DEFAULT NULL,
  `name` varchar(70) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `required` tinyint(1) NOT NULL,
  `prize` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `book`
--

CREATE TABLE `book` (
  `id` int(11) NOT NULL,
  `name` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(70) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date` datetime NOT NULL,
  `time` datetime NOT NULL,
  `people` int(11) NOT NULL,
  `message` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `state` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `book`
--

INSERT INTO `book` (`id`, `name`, `email`, `phone`, `date`, `time`, `people`, `message`, `state`, `created_at`, `updated_at`) VALUES
(21, 'Roberto', 'manchadoroberto@gmail.com', '699193558', '2021-05-03 12:20:42', '2021-05-03 12:20:42', 5, 'test', 'answered', '2021-05-03 12:20:42', '2021-05-31 17:30:20');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cart_dish`
--

CREATE TABLE `cart_dish` (
  `id` int(11) NOT NULL,
  `quanty` int(11) NOT NULL,
  `dto` int(11) NOT NULL,
  `session_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `dish_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `cart_dish`
--

INSERT INTO `cart_dish` (`id`, `quanty`, `dto`, `session_id`, `dish_id`) VALUES
(14, 1, 5, 'rs3fXg4jDexOfqIUREOJ-zMdqR1T1Kk3-cAEVLR2UtA', 3),
(15, 1, 0, 'rs3fXg4jDexOfqIUREOJ-zMdqR1T1Kk3-cAEVLR2UtA', 3),
(16, 1, 0, 'rs3fXg4jDexOfqIUREOJ-zMdqR1T1Kk3-cAEVLR2UtA', 4),
(17, 1, 0, 'rs3fXg4jDexOfqIUREOJ-zMdqR1T1Kk3-cAEVLR2UtA', 3),
(18, 3, 0, 'PzCC2X9cOERy8wegUVgA2f5oB0j7mxAiKYJmeVuQ1Og', 8),
(19, 2, 0, 'PzCC2X9cOERy8wegUVgA2f5oB0j7mxAiKYJmeVuQ1Og', 11),
(20, 1, 0, 'PzCC2X9cOERy8wegUVgA2f5oB0j7mxAiKYJmeVuQ1Og', 10),
(22, 1, 0, 'PzCC2X9cOERy8wegUVgA2f5oB0j7mxAiKYJmeVuQ1Og', 20),
(23, 1, 0, 'PzCC2X9cOERy8wegUVgA2f5oB0j7mxAiKYJmeVuQ1Og', 33),
(25, 3, 0, 'NkCk_jmqxVFBfMwWAPSBqp-00a2XcXw0Mr9G45aOOz8', 4),
(26, 2, 0, 'NkCk_jmqxVFBfMwWAPSBqp-00a2XcXw0Mr9G45aOOz8', 15),
(27, 1, 0, '73sGjKC-pkUpVkJ5lht4nu_ZRMpueAW5bsMH-paiFRA', 3),
(29, 2, 0, 'ES3zEVaAnFyvWMeNoWw3eTF7_u8nJfAoP_jVFsIV4Do', 13),
(31, 1, 0, 'Gxfu2y_dBcxOt9f5vQIKKFnmVZQMUxFPfB5DEvvMg2k', 5),
(32, 1, 0, 'Gxfu2y_dBcxOt9f5vQIKKFnmVZQMUxFPfB5DEvvMg2k', 8),
(33, 1, 0, 'Rm3bx9f1cfxQYrlTdh7PQMGWP5UpzEfPGMD9YPRkXAM', 5),
(34, 1, 0, 'iTXJvfEuY3BnHCrxWCdGRuSVYlgZGpNTt3oYE-kdZm4', 3),
(39, 1, 0, 'mV9wGSyvptt0f9ebGh55VnLioUMddSZ8dRWqOp61SDQ', 11),
(40, 1, 0, 'WsQicWCJ0HlfqcR3ldHHyFSBBNz4IGLJN8HlqcKyfxg', 15),
(41, 2, 0, 'kZkeJoMZSF_skxnMCxTmfph1nBS1wkusxSubMOuvZFA', 3),
(42, 1, 0, 'ORuOKNEB4vMpMweB2duZQSk7mpkixwhbj8Yk76qKTPY', 3),
(43, 1, 50, 'ORuOKNEB4vMpMweB2duZQSk7mpkixwhbj8Yk76qKTPY', 10),
(44, 1, 6, 'XEOoqxKoWShg6RFCX695R6TJvujmmFGK_JXOT_Wmysw', 34),
(45, 1, 0, 'XEOoqxKoWShg6RFCX695R6TJvujmmFGK_JXOT_Wmysw', 7),
(46, 1, 0, 'Ke4pAiwRS9pFY9JXSeT8ZG8LobF_S6gLjVi-5Gty1HM', 16),
(56, 1, 0, 'T3i-o_CAhpekw61sUKzWI4RLTBcIMovld2PwM8NLXe4', 39),
(57, 1, 0, 'T3i-o_CAhpekw61sUKzWI4RLTBcIMovld2PwM8NLXe4', 23),
(58, 1, 0, 'T3i-o_CAhpekw61sUKzWI4RLTBcIMovld2PwM8NLXe4', 16);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `category`
--

CREATE TABLE `category` (
  `id` int(11) NOT NULL,
  `name` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `category`
--

INSERT INTO `category` (`id`, `name`, `description`, `created_at`, `updated_at`) VALUES
(2, 'Arroces', 'Arroces tradicionales', '2021-05-12 19:24:28', '2021-05-12 19:24:28'),
(3, 'Carnes', 'Carnes a la plancha y asados', '2021-05-12 19:53:52', '2021-05-12 19:53:52'),
(4, 'Ensaladas', 'Ensaladas de la casa', '2021-05-12 20:01:36', '2021-05-12 20:01:36');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `chef`
--

CREATE TABLE `chef` (
  `id` int(11) NOT NULL,
  `name` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `job` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `twitter` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `facebook` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `instagram` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `linkedin` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `image` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `chef`
--

INSERT INTO `chef` (`id`, `name`, `job`, `twitter`, `facebook`, `instagram`, `linkedin`, `created_at`, `updated_at`, `image`) VALUES
(6, 'Pepe Rodriguez', 'MasterChef', NULL, NULL, 'https://www.instagram.com/pepe_rodriguezrey/?hl=es', NULL, '2021-06-05 22:10:12', '2021-06-05 22:15:22', 'pepe-rodriguez-masterchef-1200x900-60bbdb5ac1d18.jpg'),
(7, 'Gordon Ramsay', 'MasterChef Canada', NULL, NULL, 'https://www.instagram.com/gordongram/?hl=es', NULL, '2021-06-05 22:10:59', '2021-06-05 22:15:36', 'GordonRamsay-1-60bbdb6867d7a.jpg'),
(8, 'Alberto Chicote', 'Pesadilla en la Cocina', NULL, NULL, 'https://www.instagram.com/albertochicote/?hl=es', NULL, '2021-06-05 22:11:57', '2021-06-05 22:16:10', '1539613767-768399-1539615412-noticia-normal-recorte1-1-60bbdb7684905.jpg');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `config`
--

CREATE TABLE `config` (
  `id` int(11) NOT NULL,
  `number_photo_gallery` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `contact_form`
--

CREATE TABLE `contact_form` (
  `id` int(11) NOT NULL,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  `subject` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `state` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `contact_form`
--

INSERT INTO `contact_form` (`id`, `name`, `email`, `subject`, `message`, `created_at`, `updated_at`, `state`) VALUES
(1, 'Roberto', 'manchadoroberto@gmail.com', 'test', 'test', '2021-05-03 18:59:48', '2021-05-03 20:30:50', 'answered');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `custom_manager`
--

CREATE TABLE `custom_manager` (
  `id` int(11) NOT NULL,
  `phone` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `calendar` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `photo_description` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `location` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `open_hour` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `call_phone` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `google_maps_frame` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `photo_main` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `custom_manager`
--

INSERT INTO `custom_manager` (`id`, `phone`, `calendar`, `description`, `photo_description`, `location`, `open_hour`, `email`, `call_phone`, `google_maps_frame`, `photo_main`) VALUES
(1, '<i class=\"bi bi-phone d-flex align-items-center\"><span>650929247</span></i>', '<i class=\"bi bi-clock d-flex align-items-center ms-4\"><span> Lun-Sáb: 11AM - 23PM</span></i>', '<h2>Voluptatem dignissimos provident quasi corporis voluptates sit assumenda.</h2>\r\n\r\n<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>\r\n\r\n<ul>\r\n	<li>Ullamco laboris nisi ut aliquip ex ea commodo consequat</li>\r\n	<li>Ullamco laboris nisi ut aliquip ex ea commodo consequat</li>\r\n	<li>Ullamco laboris nisi ut aliquip ex ea commodo consequat</li>\r\n</ul>\r\n\r\n<p>&nbsp;Ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt i culpa qui officia deserunt mollit anim id est laborum.</p>', 'headway-F2KRf-QfCqw-unsplash-60ba7805bd64c.jpg', 'Calle Jose María Py, 19 03005 Alicante', 'Monday-Saturday: 11:00 AM - 2300 PM', 'info@horuslegalalliance.es', '650929247', '<iframe src=\"https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d930.268201709875!2d-0.4929645920489541!3d38.348130656526834!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0xd623651f6a48cf1%3A0x401f9cf3219fe558!2sCalle%20Jose%20Maria%20Py%2C%2003005%20Alicante%20(Alacant)%2C%20Alicante!5e0!3m2!1ses!2ses!4v1622832996521!5m2!1ses!2ses\" width=\"100%\" height=\"450\" style=\"border:0;\" allowfullscreen=\"\" loading=\"lazy\"></iframe>', 'comedor-03-restaurante-amaren-60bd250f0e999.jpg');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `dish`
--

CREATE TABLE `dish` (
  `id` int(11) NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `photo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `short_description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `prize` double NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `allergen` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `published` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `dish`
--

INSERT INTO `dish` (`id`, `category_id`, `name`, `photo`, `description`, `short_description`, `prize`, `created_at`, `updated_at`, `allergen`, `published`) VALUES
(1, NULL, 'carbonara ', 'dish-00-609952946aa14.jpg', NULL, 'a list test', 333, '2021-05-10 17:34:44', '2021-05-10 17:34:44', 'ninguno', 0),
(3, 2, 'Arroz Blanco', '00-609c0f6e43e6e.jpg', NULL, 'Arroz Blanco tradicional', 14.39, '2021-05-12 19:25:02', '2021-05-17 12:07:32', 'ninguno', 0),
(4, 2, 'Paella Valenciana', '01-609c0f7ff1961.jpg', NULL, 'Paella Valenciana tradicional', 15.65, '2021-05-12 19:25:19', '2021-05-17 12:05:54', 'ninguno', 0),
(5, 2, 'Arroz con bogavante', 'Arroz-meloso-con-bogavante-1024x852-60bbd4f9a301a.jpg', NULL, 'Arroz con bogavante tradicional', 15.8, '2021-05-12 19:25:47', '2021-06-05 21:48:09', 'ninguno', 0),
(6, 2, 'Arroz Negro', '04-609c0fad8f6a0.jpg', NULL, 'Arroz Negro tradicional', 16.45, '2021-05-12 19:26:05', '2021-05-12 19:26:05', 'ninguno', 0),
(7, 2, 'Arroz Tres Delicias', '05-609c0fc615c93.jpg', NULL, 'Arros Tres Delicias tradicional', 15.78, '2021-05-12 19:26:30', '2021-05-12 19:26:30', 'ninguno', 0),
(8, 2, 'Arroz Valenciano', '07-609c0fda4aede.jpg', NULL, 'Arroz Valenciano tradicional', 13.45, '2021-05-12 19:26:50', '2021-05-12 19:26:50', 'ninguno', 0),
(9, 2, 'Arroz Oriental', '08-609c0fed3b6bc.jpg', NULL, 'Arroz Oriental tradicional', 17.89, '2021-05-12 19:27:09', '2021-05-12 19:27:09', 'ninguno', 0),
(10, 2, 'Arroz a la Cubana', '09-609c1004afcd3.jpg', NULL, 'Arroz a la Cubana tradicional', 14.89, '2021-05-12 19:27:32', '2021-05-12 19:27:32', 'ninguno', 0),
(11, 2, 'Arroz Típico de Sevilla', '10-609c101ad0ef0.jpg', NULL, 'Arroz Tipico de Sevilla', 18.9, '2021-05-12 19:27:54', '2021-05-12 19:27:54', 'ninguno', 0),
(12, 2, 'Arroz fino', '11-609c102a089f3.jpg', NULL, 'Arroz fino tradicional', 16.78, '2021-05-12 19:28:10', '2021-05-12 19:28:10', 'ninguno', 0),
(13, 2, 'Arroz Blanco Estilo Oriental', '12-609c1043a6483.jpg', NULL, 'Arroz Blanco Estilo Oriental', 21.45, '2021-05-12 19:28:35', '2021-05-12 19:28:35', 'ninguno', 0),
(14, 2, 'Arroz amarillo', '13-609c10589246c.jpg', NULL, 'Arroz amarillo tradicional', 67.89, '2021-05-12 19:28:56', '2021-05-12 19:28:56', 'ninguno', 0),
(15, 2, 'Arroz rojo', '80E87376-811C-6377-B9D8-FF0000673B69-500x367-b-principal-1200-600-1-1-60bbd6d599dee.png', NULL, 'Arroz rojo tradicional', 67.89, '2021-05-12 19:29:12', '2021-06-05 21:56:05', 'ninguno', 0),
(16, 2, 'Arroz a la Castellana', '15-609c107db933f.jpg', NULL, 'Arroz a la Castellana tradicional', 34.56, '2021-05-12 19:29:33', '2021-05-12 19:29:33', 'lacteos', 0),
(17, 3, 'Bistec de Ternera', 'depositphotos-215130068-stock-photo-delicious-beef-steak-ketchup-potatoes-1-60bbd6149f23f.jpg', NULL, 'Bistec de Ternera con frutos del bosque', 45, '2021-05-12 19:55:09', '2021-06-05 21:52:52', 'ninguno', 0),
(18, 3, 'Bistec con salsa de bernea', '02-609c168d5e665.jpg', NULL, 'Bistec con salsa de bernea', 46, '2021-05-12 19:55:25', '2021-05-12 19:55:25', 'ninguno', 0),
(19, 3, 'Bistec de ternera asada', '03-609c169c63132.jpg', NULL, 'Bistec de ternera asada', 44, '2021-05-12 19:55:40', '2021-05-12 19:55:40', 'ninguno', 0),
(20, 3, 'Filetes de ternera a la plancha', '04-609c16aa5ed7c.jpg', NULL, 'Filetes de ternera a la plancha', 56, '2021-05-12 19:55:54', '2021-05-12 19:55:54', 'ninguno', 0),
(21, 3, 'Solomillo de Ternera con patatas', '05-609c16bab4d0c.jpg', NULL, 'Solomillo de Ternera con patatas', 23, '2021-05-12 19:56:10', '2021-05-12 19:56:10', 'ninguno', 0),
(22, 3, 'Pollo con arroz blanco', 'white-rice-with-stir-fried-979854-1-60bbd7c1c0ff5.jpg', NULL, 'Pollo con arroz blanco', 33, '2021-05-12 19:56:23', '2021-06-05 22:00:01', 'ninguno', 0),
(23, 3, 'Pollo al limón', '07-609c16ed28c9b.jpg', NULL, 'Pollo al limón', 45, '2021-05-12 19:57:01', '2021-05-12 19:57:01', 'ninguno', 0),
(24, 3, 'Pollo estilo KFC', '08-609c1700907df.jpg', NULL, 'Pollo estilo KFC', 34.89, '2021-05-12 19:57:20', '2021-05-12 19:57:20', 'ninguno', 0),
(25, 3, 'Filetes de ternera con bechamel', '09-609c17133c903.jpg', NULL, 'Filetes de ternera con bechamel', 56, '2021-05-12 19:57:39', '2021-05-12 19:57:39', 'ninguno', 0),
(26, 3, 'Solomillo de cerdo con salsa picante', '10-609c172728abe.jpg', NULL, 'Solomillo de cerdo con salsa picante', 34, '2021-05-12 19:57:59', '2021-05-12 19:57:59', 'ninguno', 0),
(27, 3, 'Costilla asada con salsa agridulce', '11-609c173fe8ba2.jpg', NULL, 'Costilla asada con salsa agridulce', 56, '2021-05-12 19:58:23', '2021-05-12 19:58:23', 'ninguno', 0),
(28, 3, 'Pastel de carne con mermelada', '12-609c175064dd1.jpg', NULL, 'Pastel de carne con mermelada', 21.45, '2021-05-12 19:58:40', '2021-05-12 19:58:40', 'ninguno', 0),
(29, 3, 'Secreto ibético', '13-609c17618e164.jpg', NULL, 'Secreto ibético', 34.56, '2021-05-12 19:58:57', '2021-05-12 19:58:57', 'ninguno', 0),
(30, 3, 'Solomillo de cerdo', '14-609c1788efb66.jpg', NULL, 'Solomillo de cerdo', 46, '2021-05-12 19:59:36', '2021-05-12 19:59:36', 'ninguno', 0),
(31, 3, 'Secreto con cebolla caramelizada', '15-609c17a2d9298.jpg', NULL, 'Secreto con cebolla caramelizada', 66, '2021-05-12 20:00:02', '2021-05-12 20:00:02', 'ninguno', 0),
(32, 4, 'Ensalada de la casa', '01-609c19651f77c.jpg', NULL, 'Ensalada de la casa', 33, '2021-05-12 20:07:33', '2021-05-12 20:07:33', 'ninguno', 0),
(33, 4, 'Enslada con picatostes', '02-609c19883dd5e.jpg', NULL, 'Enslada con picatostes', 33.45, '2021-05-12 20:08:08', '2021-05-12 20:08:08', 'ninguno', 0),
(34, 4, 'Ensalada con filetes de pollo', '03-609c19a2720c9.jpg', NULL, 'Ensalada con filetes de pollo', 22, '2021-05-12 20:08:34', '2021-05-12 20:08:34', 'ninguno', 0),
(35, 4, 'Ensalada de espinacas', '04-609c19be7242b.jpg', NULL, 'Ensalada de espinacas', 33.45, '2021-05-12 20:09:02', '2021-05-12 20:09:02', 'ninguno', 0),
(36, 4, 'Ensalada mini', '05-609c19d54375e.jpg', NULL, 'Ensalada mini', 10, '2021-05-12 20:09:25', '2021-05-12 20:09:25', 'ninguno', 0),
(37, 4, 'Ensalada con carne al limón', '06-609c19f84b9c2.jpg', NULL, 'Ensalada con carne al limón', 33, '2021-05-12 20:10:00', '2021-05-12 20:10:00', 'ninguno', 0),
(38, 4, 'Ensalada Caesar', '07-609c1a188d401.jpg', NULL, 'Ensalada Caesar', 45, '2021-05-12 20:10:32', '2021-05-12 20:10:32', 'ninguno', 0),
(39, 4, 'Ensalada de tomates', '08-609c1a31158d1.jpg', NULL, 'Ensalada de tomates', 67.89, '2021-05-12 20:10:57', '2021-05-12 20:10:57', 'ninguno', 0),
(40, 4, 'Ensalada Maese Piñon', '09-609c1a4a847f6.jpg', NULL, 'Ensalada Maese Piñon', 45, '2021-05-12 20:11:22', '2021-05-12 20:11:22', 'ninguno', 0),
(41, 4, 'Ensalada de Pasta', '10-609c1a60ab4ef.jpg', NULL, 'Ensalada de Pasta', 45, '2021-05-12 20:11:44', '2021-05-12 20:11:44', 'ninguno', 0),
(42, 4, 'Ensalada con patatas', '11-609c1a7f65e14.jpg', NULL, 'Ensalada con patatas', 11, '2021-05-12 20:12:15', '2021-05-12 20:12:15', 'ninguno', 0),
(43, 4, 'Ensalada con orégano', '12-609c1abb63aaf.jpg', NULL, 'Ensalada con orégano', 11, '2021-05-12 20:13:15', '2021-05-12 20:13:15', 'ninguno', 0),
(44, 4, 'Ensalada fina', '13-609c1ad2b1c97.jpg', NULL, 'Ensalada fina', 22.66, '2021-05-12 20:13:38', '2021-05-12 20:13:38', 'ninguno', 0),
(45, 4, 'Ensalada de cereales', '14-609c1ae782feb.jpg', NULL, 'Ensalada de cereales', 11, '2021-05-12 20:13:59', '2021-05-12 20:13:59', 'ninguno', 0),
(46, 4, 'Enslada de tomates y cebolla', '15-609c1b3378e3a.jpg', NULL, 'Enslada de tomates y cebolla', 22.66, '2021-05-12 20:15:15', '2021-05-12 20:15:15', 'ninguno', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `event`
--

CREATE TABLE `event` (
  `id` int(11) NOT NULL,
  `title` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `prize` double NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `visible` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `event`
--

INSERT INTO `event` (`id`, `title`, `description`, `prize`, `created_at`, `updated_at`, `image`, `visible`) VALUES
(8, 'Día de la Madre', '<p>Descuento especial del 5%</p>\r\n\r\n<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry&#39;s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum</p>', 45.65, '2021-04-25 17:59:58', '2021-06-05 22:32:43', 'Mothers-Day-Promotions-Hero-Image-1-1-60bbdf6bbdb41.png', 0),
(9, 'San Valentin', '<p>Fiesta Especial para este d&iacute;a&nbsp;</p>\r\n\r\n<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry&#39;s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum</p>', 15, '2021-04-25 18:00:48', '2021-06-05 22:35:29', 'restaurante-san-valentin-60bbe0119ff77.jpg', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `image`
--

CREATE TABLE `image` (
  `id` int(11) NOT NULL,
  `name` varchar(70) COLLATE utf8mb4_unicode_ci NOT NULL,
  `url` varchar(170) COLLATE utf8mb4_unicode_ci NOT NULL,
  `visible` tinyint(1) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `image`
--

INSERT INTO `image` (`id`, `name`, `url`, `visible`, `created_at`, `updated_at`) VALUES
(1, '4ecf4ab175c4902f54fa9496f39ab532.jpg', '4ecf4ab175c4902f54fa9496f39ab532.jpg', 0, '2021-06-05 22:23:12', '2021-06-05 22:23:12'),
(2, 'ecb75dabbded84cc76d7b3a77034c536.jpg', 'ecb75dabbded84cc76d7b3a77034c536.jpg', 0, '2021-06-05 22:23:12', '2021-06-05 22:23:12'),
(3, 'ce25cd7a8e9730b6a7c94ce6ed05612b.jpg', 'ce25cd7a8e9730b6a7c94ce6ed05612b.jpg', 0, '2021-06-05 22:23:12', '2021-06-05 22:23:12'),
(4, '4c72a97ebb855976cc1f58bc985a0b4f.jpg', '4c72a97ebb855976cc1f58bc985a0b4f.jpg', 0, '2021-06-05 22:23:12', '2021-06-05 22:23:12'),
(5, 'd656b71af5cd990351c80aee534a92e7.jpg', 'd656b71af5cd990351c80aee534a92e7.jpg', 0, '2021-06-05 22:23:30', '2021-06-05 22:23:30'),
(6, 'e0a5082cbe49b2454079c19b369113e4.jpg', 'e0a5082cbe49b2454079c19b369113e4.jpg', 0, '2021-06-05 22:23:41', '2021-06-05 22:23:41'),
(7, '6b3de04c2e855010d1755db93ce2620f.jpg', '6b3de04c2e855010d1755db93ce2620f.jpg', 0, '2021-06-05 22:23:41', '2021-06-05 22:23:41'),
(8, 'e2e4c29b1235504e1bc9ff0cb06e8ab0.jpg', 'e2e4c29b1235504e1bc9ff0cb06e8ab0.jpg', 0, '2021-06-05 22:23:41', '2021-06-05 22:23:41');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `link`
--

CREATE TABLE `link` (
  `id` int(11) NOT NULL,
  `name` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `href` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `marker` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `slug` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `link`
--

INSERT INTO `link` (`id`, `name`, `href`, `marker`, `slug`) VALUES
(3, 'Home', '/', NULL, 'home'),
(5, 'About', '/#about', 'about', 'about'),
(6, 'Menu', '/#menu', 'menu', 'menu'),
(7, 'Especiales', '/#specials', 'specials', 'especiales'),
(8, 'Eventos', '/#events', 'events', 'eventos'),
(9, 'Chefs', '/#chefs', 'chefs', 'chef'),
(10, 'Galeria', '/#gallery', 'gallery', 'galeria'),
(11, 'Cesta', '/cart/', NULL, 'cesta'),
(12, 'Contacto', '/#contact', 'contact', 'contacto'),
(13, 'Login', '/login', NULL, 'login');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `orderCustomer`
--

CREATE TABLE `orderCustomer` (
  `id` int(11) NOT NULL,
  `total` double NOT NULL,
  `total_dto` double NOT NULL,
  `state` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `user_id` int(11) NOT NULL,
  `payment_method` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `orderCustomer`
--

INSERT INTO `orderCustomer` (`id`, `total`, `total_dto`, `state`, `created_at`, `updated_at`, `user_id`, `payment_method`) VALUES
(49, 203.34, 0, 'delivered', '2021-06-06 21:58:54', '2021-06-06 22:03:50', 37, 'paypal');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `orderItem`
--

CREATE TABLE `orderItem` (
  `id` int(11) NOT NULL,
  `dish_id` int(11) NOT NULL,
  `order_customer_id` int(11) NOT NULL,
  `quanty` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `orderItem`
--

INSERT INTO `orderItem` (`id`, `dish_id`, `order_customer_id`, `quanty`) VALUES
(65, 15, 49, 1),
(66, 22, 49, 1),
(67, 39, 49, 1),
(68, 16, 49, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `promotions`
--

CREATE TABLE `promotions` (
  `id` int(11) NOT NULL,
  `dish_id` int(11) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `begin` datetime DEFAULT NULL,
  `ending` datetime DEFAULT NULL,
  `code` varchar(5) COLLATE utf8mb4_unicode_ci NOT NULL,
  `dto` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `promotions`
--

INSERT INTO `promotions` (`id`, `dish_id`, `category_id`, `begin`, `ending`, `code`, `dto`, `status`) VALUES
(36, NULL, NULL, NULL, NULL, 'e3e', 7, 0),
(37, NULL, 3, '2021-06-06 00:00:00', '2021-06-06 00:00:00', 'hhh', 8, 0),
(38, NULL, 3, '2021-06-17 00:00:00', '2021-06-02 00:00:00', 'hhh', 5, 0),
(39, 34, NULL, '2021-06-01 00:00:00', '2021-06-26 00:00:00', 'vvv', 6, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `survey`
--

CREATE TABLE `survey` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `valoration` int(11) NOT NULL,
  `satisfaction` int(11) NOT NULL,
  `comment` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `public` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `survey`
--

INSERT INTO `survey` (`id`, `user_id`, `valoration`, `satisfaction`, `comment`, `public`) VALUES
(32, 1, 5, 5, 'El restaurante es totalmente recomendable, comida muy rica y servicio de 10.', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `testimonial`
--

CREATE TABLE `testimonial` (
  `id` int(11) NOT NULL,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `job` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `valoration` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `rate` int(11) NOT NULL,
  `published` tinyint(1) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `email` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  `comment` longtext COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `testimonial`
--

INSERT INTO `testimonial` (`id`, `name`, `job`, `valoration`, `rate`, `published`, `created_at`, `updated_at`, `email`, `comment`) VALUES
(1, 'inforob', 'Freelancer', '5', 5, 1, '2021-06-06 22:06:23', '2021-06-06 22:06:23', 'admin@restajav.com', 'El restaurante es totalmente recomendable, comida muy rica y servicio de 10.');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `username` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `roles` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `token` varchar(70) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `active` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `user`
--

INSERT INTO `user` (`id`, `username`, `roles`, `password`, `email`, `created_at`, `updated_at`, `token`, `active`) VALUES
(1, 'inforob', '[\"ROLE_ADMIN\"]', '$2y$13$1H9B.6xKAdkvIWb4NzFFFO.pJOyw/3LEb05RwUmqcX51ZEYQ/diVG', 'admin@restajav.com', '2021-04-18 18:41:48', '2021-06-05 21:26:30', NULL, 1),
(2, 'user-fake', '[\"ROLE_USER\"]', '$argon2id$v=19$m=65536,t=4,p=1$2Nc7x7VcSlojgvO2JhIz1Q$/SbhkPZ3HsSXKXRtYMrHqOxHWF8CTJ3alpa1oqQ17lo', 'user-fake@restjav.com', '2021-04-18 18:41:48', '2021-04-18 18:41:48', NULL, 1),
(3, 'inforob', '[\"ROLE_USER\"]', '$2y$13$z9XOLgHDzS6S9eCDVAsv5u06SMblFGyIRoZGXipn.omGFBoFmF.16', 'inforob@ono.com', '2021-05-15 16:16:02', '2021-05-29 17:07:10', NULL, 1),
(36, 'infotest', '[\"ROLE_USER\"]', '$2y$13$6YdrIN.SQu7Y8yxvZQnVV.FxwjBrxTW2YR1UTloP0bsZ5vn6..jji', 'manchadoroberto@gmail.com', '2021-06-06 21:49:23', '2021-06-06 21:49:23', 'oE_Sa0H4jZkKzJLpc-KCoGNHUR5ceKWIR6Oa2V6ALjk', 0),
(37, 'Javier', '[\"ROLE_USER\"]', '$2y$13$UsL/zmSWNFQ6Xu02XJt/Bev82dyAmTpqcf5C8Wbx8Aucbkm81iGMy', 'javitgomez@gmail.com', '2021-06-06 21:52:44', '2021-06-06 21:55:45', NULL, 1);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `address`
--
ALTER TABLE `address`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_D4E6F81A76ED395` (`user_id`);

--
-- Indices de la tabla `aggregate`
--
ALTER TABLE `aggregate`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_B77949FF148EB0CB` (`dish_id`);

--
-- Indices de la tabla `book`
--
ALTER TABLE `book`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `cart_dish`
--
ALTER TABLE `cart_dish`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `chef`
--
ALTER TABLE `chef`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `config`
--
ALTER TABLE `config`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `contact_form`
--
ALTER TABLE `contact_form`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `custom_manager`
--
ALTER TABLE `custom_manager`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `dish`
--
ALTER TABLE `dish`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_957D8CB812469DE2` (`category_id`);

--
-- Indices de la tabla `event`
--
ALTER TABLE `event`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `image`
--
ALTER TABLE `image`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `link`
--
ALTER TABLE `link`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `orderCustomer`
--
ALTER TABLE `orderCustomer`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_FA7DA469A76ED395` (`user_id`);

--
-- Indices de la tabla `orderItem`
--
ALTER TABLE `orderItem`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_B119DCBA8827BC75` (`order_customer_id`),
  ADD KEY `IDX_B119DCBA148EB0CB` (`dish_id`);

--
-- Indices de la tabla `promotions`
--
ALTER TABLE `promotions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_EA1B3034148EB0CB` (`dish_id`),
  ADD KEY `IDX_EA1B303412469DE2` (`category_id`);

--
-- Indices de la tabla `survey`
--
ALTER TABLE `survey`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_AD5F9BFCA76ED395` (`user_id`);

--
-- Indices de la tabla `testimonial`
--
ALTER TABLE `testimonial`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `address`
--
ALTER TABLE `address`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `aggregate`
--
ALTER TABLE `aggregate`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `book`
--
ALTER TABLE `book`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT de la tabla `cart_dish`
--
ALTER TABLE `cart_dish`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;

--
-- AUTO_INCREMENT de la tabla `category`
--
ALTER TABLE `category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `chef`
--
ALTER TABLE `chef`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `config`
--
ALTER TABLE `config`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `contact_form`
--
ALTER TABLE `contact_form`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `custom_manager`
--
ALTER TABLE `custom_manager`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `dish`
--
ALTER TABLE `dish`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT de la tabla `event`
--
ALTER TABLE `event`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `image`
--
ALTER TABLE `image`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `link`
--
ALTER TABLE `link`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de la tabla `orderCustomer`
--
ALTER TABLE `orderCustomer`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT de la tabla `orderItem`
--
ALTER TABLE `orderItem`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=69;

--
-- AUTO_INCREMENT de la tabla `promotions`
--
ALTER TABLE `promotions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT de la tabla `survey`
--
ALTER TABLE `survey`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT de la tabla `testimonial`
--
ALTER TABLE `testimonial`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `address`
--
ALTER TABLE `address`
  ADD CONSTRAINT `FK_D4E6F81A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Filtros para la tabla `aggregate`
--
ALTER TABLE `aggregate`
  ADD CONSTRAINT `FK_B77949FF148EB0CB` FOREIGN KEY (`dish_id`) REFERENCES `dish` (`id`);

--
-- Filtros para la tabla `dish`
--
ALTER TABLE `dish`
  ADD CONSTRAINT `FK_957D8CB812469DE2` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`);

--
-- Filtros para la tabla `orderCustomer`
--
ALTER TABLE `orderCustomer`
  ADD CONSTRAINT `FK_FA7DA469A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Filtros para la tabla `orderItem`
--
ALTER TABLE `orderItem`
  ADD CONSTRAINT `FK_B119DCBA148EB0CB` FOREIGN KEY (`dish_id`) REFERENCES `dish` (`id`),
  ADD CONSTRAINT `FK_B119DCBA8827BC75` FOREIGN KEY (`order_customer_id`) REFERENCES `orderCustomer` (`id`);

--
-- Filtros para la tabla `promotions`
--
ALTER TABLE `promotions`
  ADD CONSTRAINT `FK_EA1B303412469DE2` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`),
  ADD CONSTRAINT `FK_EA1B3034148EB0CB` FOREIGN KEY (`dish_id`) REFERENCES `dish` (`id`);

--
-- Filtros para la tabla `survey`
--
ALTER TABLE `survey`
  ADD CONSTRAINT `FK_AD5F9BFCA76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
