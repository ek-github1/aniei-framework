-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 09-07-2014 a las 14:07:32
-- Versión del servidor: 5.5.25
-- Versión de PHP: 5.4.4

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Base de datos: `app_spiderframe`
--
-- CREATE DATABASE `app_spiderframe`;
-- USE `app_spiderframe`;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `catalog_format_date`
--

CREATE TABLE `catalog_format_date` (
  `catalog_format_date_id` int(11) NOT NULL AUTO_INCREMENT,
  `format_date` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `saparator` enum('/','-','') COLLATE utf8_unicode_ci DEFAULT NULL,
  `active` enum('-1','0','1') COLLATE utf8_unicode_ci NOT NULL DEFAULT '1',
  PRIMARY KEY (`catalog_format_date_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=13 ;

--
-- Volcado de datos para la tabla `catalog_format_date`
--

INSERT INTO `catalog_format_date` (`catalog_format_date_id`, `format_date`, `saparator`, `active`) VALUES
(1, 'MM-DD-YY', '', '1'),
(2, 'MM/DD/YY', '', '1'),
(3, 'MM DD YY', '', '0'),
(4, 'MM-DD-YYYY', '', '1'),
(5, 'MM/DD/YYYY', '', '1'),
(6, 'MM DD YYYY', '', '0'),
(7, 'DD-MM-YY', '', '1'),
(8, 'DD/MM/YY', '', '1'),
(9, 'DD MM YY', '', '0'),
(10, 'DD-MM-YYYY', '', '1'),
(11, 'DD/MM/YYYY', '', '1'),
(12, 'DD MM YYYY', '', '0');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `catalog_format_number`
--

CREATE TABLE `catalog_format_number` (
  `catalog_format_number_id` int(11) NOT NULL AUTO_INCREMENT,
  `format_number` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`catalog_format_number_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;

--
-- Volcado de datos para la tabla `catalog_format_number`
--

INSERT INTO `catalog_format_number` (`catalog_format_number_id`, `format_number`) VALUES
(1, '1,000'),
(2, '1.000');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `catalog_identification_type`
--

CREATE TABLE `catalog_identification_type` (
  `catalog_identification_type_id` int(11) NOT NULL AUTO_INCREMENT,
  `identification_type` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`catalog_identification_type_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=5 ;

--
-- Volcado de datos para la tabla `catalog_identification_type`
--

INSERT INTO `catalog_identification_type` (`catalog_identification_type_id`, `identification_type`) VALUES
(1, 'National identification number'),
(2, 'Social Security Number'),
(3, 'Passport'),
(4, 'Driver license');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `catalog_mail_controller`
--

CREATE TABLE `catalog_mail_controller` (
  `catalog_mail_controller_id` int(11) NOT NULL AUTO_INCREMENT,
  `capacity` int(11) DEFAULT NULL,
  `mail` varchar(80) COLLATE utf8_unicode_ci DEFAULT NULL,
  `password` varchar(40) COLLATE utf8_unicode_ci DEFAULT NULL,
  `server` varchar(40) COLLATE utf8_unicode_ci DEFAULT NULL,
  `port` varchar(5) COLLATE utf8_unicode_ci DEFAULT NULL,
  `active` enum('-1','0','1') COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`catalog_mail_controller_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Volcado de datos para la tabla `catalog_mail_controller`
--

INSERT INTO `catalog_mail_controller` (`catalog_mail_controller_id`, `capacity`, `mail`, `password`, `server`, `port`, `active`) VALUES
(1, 200, 'test.estilosfrescos@gmail.com', 'spidermay_test', 'gmail.com', '465', '1');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `catalog_module`
--

CREATE TABLE `catalog_module` (
  `catalog_module_id` int(11) NOT NULL AUTO_INCREMENT,
  `module` varchar(80) COLLATE utf8_unicode_ci NOT NULL,
  `context` varchar(80) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `active` enum('-1','0','1') COLLATE utf8_unicode_ci NOT NULL DEFAULT '1',
  PRIMARY KEY (`catalog_module_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=18 ;

--
-- Volcado de datos para la tabla `catalog_module`
--

INSERT INTO `catalog_module` (`catalog_module_id`, `module`, `context`, `description`, `active`) VALUES
(1, 'admin', 'General admin', 'General admin system', '1'),
(2, 'support_user', 'Support user', 'User control', '1'),
(3, 'address', 'Address', 'Control address for users', '1'),
(4, 'member', 'Member', 'Member control', '1'),
(5, 'permission', 'Permission', '', '1'),
(6, 'city', 'Cities', '', '1'),
(7, 'mail', 'Mailer', '', '1'),
(8, 'images', 'Admin images', '', '1'),
(9, 'state', 'States', '', '1'),
(10, 'dictionary', 'Dictionaries', 'All reference to add and edit dictionaries', '1'),
(11, 'reference', 'Reference', '', '1'),
(12, 'stock', 'Stock', 'Admin Stocks', '1'),
(13, 'provider', 'Provider', '', '1'),
(14, 'settings', 'Settings', 'Settings for user', '1'),
(15, 'products', 'Products', 'Admin every products', '1'),
(16, 'applications', 'Applications', 'Applications for system', '1'),
(17, 'Test', 'Test', '', '1');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `catalog_module_permission`
--

CREATE TABLE `catalog_module_permission` (
  `catalog_module_permission_id` int(11) NOT NULL AUTO_INCREMENT,
  `catalog_module_id` int(11) NOT NULL,
  `permission` varchar(80) COLLATE utf8_unicode_ci NOT NULL,
  `context` varchar(80) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `active` enum('-1','0','1') COLLATE utf8_unicode_ci NOT NULL DEFAULT '1',
  PRIMARY KEY (`catalog_module_permission_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=46 ;

--
-- Volcado de datos para la tabla `catalog_module_permission`
--

INSERT INTO `catalog_module_permission` (`catalog_module_permission_id`, `catalog_module_id`, `permission`, `context`, `description`, `active`) VALUES
(1, 2, 'add_user', 'Add user', 'Agregar nuevos usuarios', '1'),
(2, 2, 'edit_user', 'Edit user', 'Editar usuarios', '1'),
(3, 2, 'inactive_user', 'Inactive user', 'Desactivar usuarios', '1'),
(4, 2, 'delete_user', 'Delete user', 'Delete users', '1'),
(5, 2, 'view_user', 'View user', 'Ver usuarios', '1'),
(6, 2, 'list_user', 'List user', 'Listing users', '1'),
(7, 2, 'view_permission', 'View permission', 'View permissions by user', '1'),
(8, 5, 'inactive_module_permission', '', 'Inactive module permissions', '1'),
(9, 4, 'add_member', 'Add member', 'Add members', '1'),
(10, 4, 'edit_member', 'Edit member', 'Edit members', '1'),
(11, 4, 'list_member', 'List member', 'List member', '1'),
(12, 4, 'inactive_member', 'Inactive member', 'Inactive and active members', '1'),
(13, 8, 'add_image', 'Add image', 'Add imagen for users', '1'),
(14, 2, 'add_permission', 'Add permission', 'Add permissions by user', '1'),
(15, 3, 'dele_address', 'Dele Address', '', '1'),
(16, 3, 'delete_address', 'Delete Address', '', '1'),
(17, 5, 'add_module', 'Add module', 'Add module permissions', '1'),
(18, 10, 'add_dictionary', 'Add dictionary', 'Add new dictionary', '1'),
(19, 10, 'add_dictionary_word', 'Add dictionary word', 'Add new pharagraph', '1'),
(20, 10, 'edit_dictionary', 'Edit dictionary', 'Edit dictionary', '1'),
(21, 10, 'edit_dictionary_word', 'Edit dictionary word', 'Edit paragraph', '1'),
(22, 5, 'add_module_permission', 'Add module permission', 'Add new permissions', '1'),
(23, 5, 'edit_module_permission', 'Edit module permission', 'Edit permission', '1'),
(24, 5, 'edit_module', 'Edit module', 'Edit module', '1'),
(25, 5, 'inactive_module', 'Inactive module', '', '1'),
(26, 10, 'delete_dictionary', 'Delete dictionary', '', '1'),
(27, 12, 'view_stock', 'View stock', '', '1'),
(28, 12, 'edit_stock', 'Edit stock', '', '1'),
(29, 12, 'delete_stock', 'Delete stock', '', '1'),
(30, 12, 'add_item_in_stock', 'Add item in stock', '', '1'),
(31, 12, 'add_stock', 'Add stock', '', '1'),
(32, 12, 'add_stock_item', 'Add stock item', 'Add new items for stock', '1'),
(33, 12, 'inactive_stock', 'Inactive stock', '', '1'),
(34, 13, 'add_provider', 'Add provider', '', '1'),
(35, 2, 'edit_rol', 'Edit rol', 'Edit rol per user', '1'),
(36, 1, 'add_user_rol', 'Add user rol', 'Add roles for user', '1'),
(37, 1, 'add_admin_user', 'Add admin user', 'Add new user rol with admin account', '1'),
(38, 14, 'edit_user_setting', 'Edit user setting', 'Edit user settings', '1'),
(39, 10, 'delete_dictionary_word', 'Delete dictionary word', 'Delete lines in dictionary', '1'),
(40, 15, 'view_products', 'View products', '', '1'),
(41, 15, 'edit_product', 'Edit product', '', '1'),
(42, 1, 'view_admin_section', 'View admin section', 'View admin section and details', '1'),
(43, 16, 'view_applications', 'View applications', '', '1'),
(44, 1, 'Test', 'test', 'test', '1'),
(45, 1, 'Test', '', 'Description', '1');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `catalog_timezone`
--

CREATE TABLE `catalog_timezone` (
  `catalog_timezone_id` int(11) NOT NULL AUTO_INCREMENT,
  `timezone` varchar(80) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`catalog_timezone_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=580 ;

--
-- Volcado de datos para la tabla `catalog_timezone`
--

INSERT INTO `catalog_timezone` (`catalog_timezone_id`, `timezone`) VALUES
(1, 'Africa/Abidjan'),
(2, 'Africa/Accra'),
(3, 'Africa/Addis_Ababa'),
(4, 'Africa/Algiers'),
(5, 'Africa/Asmara'),
(6, 'Africa/Asmera'),
(7, 'Africa/Bamako'),
(8, 'Africa/Bangui'),
(9, 'Africa/Banjul'),
(10, 'Africa/Bissau'),
(11, 'Africa/Blantyre'),
(12, 'Africa/Brazzaville'),
(13, 'Africa/Bujumbura'),
(14, 'Africa/Cairo'),
(15, 'Africa/Casablanca'),
(16, 'Africa/Ceuta'),
(17, 'Africa/Conakry'),
(18, 'Africa/Dakar'),
(19, 'Africa/Dar_es_Salaam'),
(20, 'Africa/Djibouti'),
(21, 'Africa/Douala'),
(22, 'Africa/El_Aaiun'),
(23, 'Africa/Freetown'),
(24, 'Africa/Gaborone'),
(25, 'Africa/Harare'),
(26, 'Africa/Johannesburg'),
(27, 'Africa/Juba'),
(28, 'Africa/Kampala '),
(29, 'Africa/Khartoum'),
(30, 'Africa/Kigali'),
(31, 'Africa/Kinshasa'),
(32, 'Africa/Lagos '),
(33, 'Africa/Libreville'),
(34, 'Africa/Lome'),
(35, 'Africa/Luanda'),
(36, 'Africa/Lubumbashi'),
(37, 'Africa/Lusaka'),
(38, 'Africa/Malabo'),
(39, 'Africa/Maputo'),
(40, 'Africa/Maseru'),
(41, 'Africa/Mbabane '),
(42, 'Africa/Mogadishu '),
(43, 'Africa/Monrovia'),
(44, 'Africa/Nairobi'),
(45, 'Africa/Ndjamena'),
(46, 'Africa/Niamey'),
(47, 'Africa/Nouakchott'),
(48, 'Africa/Ouagadougou'),
(49, 'Africa/Porto-Novo'),
(50, 'Africa/Sao_Tome'),
(51, 'Africa/Timbuktu'),
(52, 'Africa/Tripoli '),
(53, 'Africa/Tunis'),
(54, 'Africa/Windhoek'),
(55, 'America/Adak'),
(56, 'America/Anchorage'),
(57, 'America/Anguilla'),
(58, 'America/Antigua'),
(59, 'America/Araguaina'),
(60, 'America/Argentina/Buenos_Aires'),
(61, 'America/Argentina/Catamarca'),
(62, 'America/Argentina/ComodRivadavia'),
(63, 'America/Argentina/Cordoba'),
(64, 'America/Argentina/Jujuy'),
(65, 'America/Argentina/La_Rioja'),
(66, 'America/Argentina/Mendoza'),
(67, 'America/Argentina/Rio_Gallegos'),
(68, 'America/Argentina/Salta'),
(69, 'America/Argentina/San_Juan'),
(70, 'America/Argentina/San_Luis'),
(71, 'America/Argentina/Tucuman'),
(72, 'America/Argentina/Ushuaia'),
(73, 'America/Aruba'),
(74, 'America/Asuncion'),
(75, 'America/Atikokan'),
(76, 'America/Atka'),
(77, 'America/Bahia'),
(78, 'America/Bahia_Banderas'),
(79, 'America/Barbados'),
(80, 'America/Belem'),
(81, 'America/Belize'),
(82, 'America/Blanc-Sablon'),
(83, 'America/Boa_Vista'),
(84, 'America/Bogota'),
(85, 'America/Boise'),
(86, 'America/Buenos_Aires'),
(87, 'America/Cambridge_Bay'),
(88, 'America/Campo_Grande'),
(89, 'America/Cancun'),
(90, 'America/Caracas'),
(91, 'America/Catamarca'),
(92, 'America/Cayenne'),
(93, 'America/Cayman'),
(94, 'America/Chicago'),
(95, 'America/Chihuahua'),
(96, 'America/Coral_Harbour'),
(97, 'America/Cordoba'),
(98, 'America/Costa_Rica'),
(99, 'America/Creston'),
(100, 'America/Cuiaba'),
(101, 'America/Curacao'),
(102, 'America/Danmarkshavn'),
(103, 'America/Dawson'),
(104, 'America/Dawson_Creek'),
(105, 'America/Denver'),
(106, 'America/Detroit'),
(107, 'America/Dominica'),
(108, 'America/Edmonton'),
(109, 'America/Eirunepe'),
(110, 'America/El_Salvador'),
(111, 'America/Ensenada'),
(112, 'America/Fort_Wayne'),
(113, 'America/Fortaleza'),
(114, 'America/Glace_Bay'),
(115, 'America/Godthab'),
(116, 'America/Goose_Bay'),
(117, 'America/Grand_Turk'),
(118, 'America/Grenada'),
(119, 'America/Guadeloupe'),
(120, 'America/Guatemala'),
(121, 'America/Guayaquil'),
(122, 'America/Guyana'),
(123, 'America/Halifax'),
(124, 'America/Havana'),
(125, 'America/Hermosillo'),
(126, 'America/Indiana/Indianapolis'),
(127, 'America/Indiana/Knox'),
(128, 'America/Indiana/Marengo'),
(129, 'America/Indiana/Petersburg'),
(130, 'America/Indiana/Tell_City'),
(131, 'America/Indiana/Vevay'),
(132, 'America/Indiana/Vincennes'),
(133, 'America/Indiana/Winamac'),
(134, 'America/Indianapolis'),
(135, 'America/Inuvik'),
(136, 'America/Iqaluit'),
(137, 'America/Jamaica'),
(138, 'America/Jujuy'),
(139, 'America/Juneau'),
(140, 'America/Kentucky/Louisville'),
(141, 'America/Kentucky/Monticello'),
(142, 'America/Knox_IN'),
(143, 'America/Kralendijk'),
(144, 'America/La_Paz'),
(145, 'America/Lima'),
(146, 'America/Los_Angeles'),
(147, 'America/Louisville'),
(148, 'America/Lower_Princes'),
(149, 'America/Maceio'),
(150, 'America/Managua'),
(151, 'America/Manaus'),
(152, 'America/Marigot'),
(153, 'America/Martinique'),
(154, 'America/Matamoros'),
(155, 'America/Mazatlan'),
(156, 'America/Mendoza'),
(157, 'America/Menominee'),
(158, 'America/Merida'),
(159, 'America/Metlakatla'),
(160, 'America/Mexico_City'),
(161, 'America/Miquelon'),
(162, 'America/Moncton'),
(163, 'America/Monterrey'),
(164, 'America/Montevideo'),
(165, 'America/Montreal'),
(166, 'America/Montserrat'),
(167, 'America/Nassau'),
(168, 'America/New_York'),
(169, 'America/Nipigon'),
(170, 'America/Nome'),
(171, 'America/Noronha'),
(172, 'America/North_Dakota/Beulah'),
(173, 'America/North_Dakota/Center'),
(174, 'America/North_Dakota/New_Salem'),
(175, 'America/Ojinaga'),
(176, 'America/Panama'),
(177, 'America/Pangnirtung'),
(178, 'America/Paramaribo'),
(179, 'America/Phoenix'),
(180, 'America/Port-au-Prince'),
(181, 'America/Port_of_Spain'),
(182, 'America/Porto_Acre'),
(183, 'America/Porto_Velho'),
(184, 'America/Puerto_Rico'),
(185, 'America/Rainy_River'),
(186, 'America/Rankin_Inlet'),
(187, 'America/Recife'),
(188, 'America/Regina'),
(189, 'America/Resolute'),
(190, 'America/Rio_Branco'),
(191, 'America/Rosario'),
(192, 'America/Santa_Isabel'),
(193, 'America/Santarem'),
(194, 'America/Santiago'),
(195, 'America/Santo_Domingo'),
(196, 'America/Sao_Paulo'),
(197, 'America/Scoresbysund'),
(198, 'America/Shiprock'),
(199, 'America/Sitka'),
(200, 'America/St_Barthelemy'),
(201, 'America/St_Johns'),
(202, 'America/St_Kitts'),
(203, 'America/St_Lucia'),
(204, 'America/St_Thomas'),
(205, 'America/St_Vincent'),
(206, 'America/Swift_Current'),
(207, 'America/Tegucigalpa'),
(208, 'America/Thule'),
(209, 'America/Thunder_Bay'),
(210, 'America/Tijuana'),
(211, 'America/Toronto'),
(212, 'America/Tortola'),
(213, 'America/Vancouver'),
(214, 'America/Virgin'),
(215, 'America/Whitehorse'),
(216, 'America/Winnipeg'),
(217, 'America/Yakutat'),
(218, 'America/Yellowknife'),
(219, 'Antarctica/Casey'),
(220, 'Antarctica/Davis'),
(221, 'Antarctica/DumontDUrville'),
(222, 'Antarctica/Macquarie'),
(223, 'Antarctica/Mawson'),
(224, 'Antarctica/McMurdo'),
(225, 'Antarctica/Palmer'),
(226, 'Antarctica/Rothera'),
(227, 'Antarctica/South_Pole Antarctica/Syowa'),
(228, 'Antarctica/Vostok'),
(229, 'Arctic/Longyearbyen'),
(230, 'Asia/Aden'),
(231, 'Asia/Almaty'),
(232, 'Asia/Amman'),
(233, 'Asia/Anadyr'),
(234, 'Asia/Aqtau'),
(235, 'Asia/Aqtobe'),
(236, 'Asia/Ashgabat'),
(237, 'Asia/Ashkhabad'),
(238, 'Asia/Baghdad'),
(239, 'Asia/Bahrain'),
(240, 'Asia/Baku'),
(241, 'Asia/Bangkok'),
(242, 'Asia/Beirut'),
(243, 'Asia/Bishkek'),
(244, 'Asia/Brunei'),
(245, 'Asia/Calcutta'),
(246, 'Asia/Choibalsan'),
(247, 'Asia/Chongqing'),
(248, 'Asia/Chungking'),
(249, 'Asia/Colombo'),
(250, 'Asia/Dacca'),
(251, 'Asia/Damascus'),
(252, 'Asia/Dhaka'),
(253, 'Asia/Dili'),
(254, 'Asia/Dubai'),
(255, 'Asia/Dushanbe'),
(256, 'Asia/Gaza'),
(257, 'Asia/Harbin'),
(258, 'Asia/Hebron'),
(259, 'Asia/Ho_Chi_Minh'),
(260, 'Asia/Hong_Kong'),
(261, 'Asia/Hovd'),
(262, 'Asia/Irkutsk'),
(263, 'Asia/Istanbul'),
(264, 'Asia/Jakarta'),
(265, 'Asia/Jayapura'),
(266, 'Asia/Jerusalem'),
(267, 'Asia/Kabul'),
(268, 'Asia/Kamchatka'),
(269, 'Asia/Karachi'),
(270, 'Asia/Kashgar'),
(271, 'Asia/Kathmandu'),
(272, 'Asia/Katmandu'),
(273, 'Asia/Khandyga'),
(274, 'Asia/Kolkata'),
(275, 'Asia/Krasnoyarsk'),
(276, 'Asia/Kuala_Lumpur'),
(277, 'Asia/Kuching'),
(278, 'Asia/Kuwait'),
(279, 'Asia/Macao'),
(280, 'Asia/Macau'),
(281, 'Asia/Magadan'),
(282, 'Asia/Makassar'),
(283, 'Asia/Manila'),
(284, 'Asia/Muscat'),
(285, 'Asia/Nicosia'),
(286, 'Asia/Novokuznetsk'),
(287, 'Asia/Novosibirsk'),
(288, 'Asia/Omsk'),
(289, 'Asia/Oral'),
(290, 'Asia/Phnom_Penh'),
(291, 'Asia/Pontianak'),
(292, 'Asia/Pyongyang'),
(293, 'Asia/Qatar'),
(294, 'Asia/Qyzylorda'),
(295, 'Asia/Rangoon'),
(296, 'Asia/Riyadh'),
(297, 'Asia/Saigon'),
(298, 'Asia/Sakhalin'),
(299, 'Asia/Samarkand'),
(300, 'Asia/Seoul'),
(301, 'Asia/Shanghai'),
(302, 'Asia/Singapore'),
(303, 'Asia/Taipei'),
(304, 'Asia/Tashkent'),
(305, 'Asia/Tbilisi'),
(306, 'Asia/Tehran'),
(307, 'Asia/Tel_Aviv'),
(308, 'Asia/Thimbu'),
(309, 'Asia/Thimphu'),
(310, 'Asia/Tokyo'),
(311, 'Asia/Ujung_Pandang'),
(312, 'Asia/Ulaanbaatar'),
(313, 'Asia/Ulan_Bator'),
(314, 'Asia/Urumqi'),
(315, 'Asia/Ust-Nera'),
(316, 'Asia/Vientiane'),
(317, 'Asia/Vladivostok'),
(318, 'Asia/Yakutsk'),
(319, 'Asia/Yekaterinburg'),
(320, 'Asia/Yerevan'),
(321, 'Atlantic/Azores'),
(322, 'Atlantic/Bermuda'),
(323, 'Atlantic/Canary'),
(324, 'Atlantic/Cape_Verde'),
(325, 'Atlantic/Faeroe'),
(326, 'Atlantic/Faroe'),
(327, 'Atlantic/Jan_Mayen'),
(328, 'Atlantic/Madeira'),
(329, 'Atlantic/Reykjavik'),
(330, 'Atlantic/South_Georgia'),
(331, 'Atlantic/St_Helena'),
(332, 'Atlantic/Stanley'),
(333, 'Atlantic/Azores'),
(334, 'Australia/ACT'),
(335, 'Australia/Adelaide'),
(336, 'Australia/Brisbane'),
(337, 'Australia/Broken_Hill'),
(338, 'Australia/Canberra'),
(339, 'Australia/Currie'),
(340, 'Australia/Darwin'),
(341, 'Australia/Eucla'),
(342, 'Australia/Hobart'),
(343, 'Australia/LHI'),
(344, 'Australia/Lindeman'),
(345, 'Australia/Lord_Howe'),
(346, 'Australia/Melbourne'),
(347, 'Australia/North'),
(348, 'Australia/NSW'),
(349, 'Australia/Perth'),
(350, 'Australia/Queensland'),
(351, 'Australia/South'),
(352, 'Australia/Sydney'),
(353, 'Australia/Tasmania'),
(354, 'Australia/Victoria'),
(355, 'Australia/West'),
(356, 'Australia/Yancowinna'),
(357, 'Europe/Amsterdam'),
(358, 'Europe/Andorra'),
(359, 'Europe/Athens'),
(360, 'Europe/Belfast'),
(361, 'Europe/Belgrade'),
(362, 'Europe/Berlin'),
(363, 'Europe/Bratislava'),
(364, 'Europe/Brussels'),
(365, 'Europe/Bucharest'),
(366, 'Europe/Budapest'),
(367, 'Europe/Busingen'),
(368, 'Europe/Chisinau'),
(369, 'Europe/Copenhagen'),
(370, 'Europe/Dublin'),
(371, 'Europe/Gibraltar'),
(372, 'Europe/Guernsey'),
(373, 'Europe/Helsinki'),
(374, 'Europe/Isle_of_Man'),
(375, 'Europe/Istanbul'),
(376, 'Europe/Jersey'),
(377, 'Europe/Kaliningrad'),
(378, 'Europe/Kiev'),
(379, 'Europe/Lisbon'),
(380, 'Europe/Ljubljana'),
(381, 'Europe/London'),
(382, 'Europe/Luxembourg'),
(383, 'Europe/Madrid'),
(384, 'Europe/Malta'),
(385, 'Europe/Mariehamn'),
(386, 'Europe/Minsk'),
(387, 'Europe/Monaco'),
(388, 'Europe/Moscow'),
(389, 'Europe/Nicosia'),
(390, 'Europe/Oslo'),
(391, 'Europe/Paris'),
(392, 'Europe/Podgorica'),
(393, 'Europe/Prague'),
(394, 'Europe/Riga'),
(395, 'Europe/Rome'),
(396, 'Europe/Samara'),
(397, 'Europe/San_Marino'),
(398, 'Europe/Sarajevo'),
(399, 'Europe/Simferopol'),
(400, 'Europe/Skopje'),
(401, 'Europe/Sofia'),
(402, 'Europe/Stockholm'),
(403, 'Europe/Tallinn'),
(404, 'Europe/Tirane'),
(405, 'Europe/Tiraspol'),
(406, 'Europe/Uzhgorod'),
(407, 'Europe/Vaduz'),
(408, 'Europe/Vatican'),
(409, 'Europe/Vienna'),
(410, 'Europe/Vilnius'),
(411, 'Europe/Volgograd'),
(412, 'Europe/Warsaw'),
(413, 'Europe/Zagreb'),
(414, 'Europe/Zaporozhye'),
(415, 'Europe/Zurich'),
(416, 'Indian/Antananarivo'),
(417, 'Indian/Chagos'),
(418, 'Indian/Christmas'),
(419, 'Indian/Cocos'),
(420, 'Indian/Comoro'),
(421, 'Indian/Kerguelen'),
(422, 'Indian/Mahe'),
(423, 'Indian/Maldives'),
(424, 'Indian/Mauritius'),
(425, 'Indian/Mayotte'),
(426, 'Indian/Reunion'),
(427, 'Pacific/Apia'),
(428, 'Pacific/Auckland'),
(429, 'Pacific/Chatham'),
(430, 'Pacific/Chuuk'),
(431, 'Pacific/Easter'),
(432, 'Pacific/Efate'),
(433, 'Pacific/Enderbury'),
(434, 'Pacific/Fakaofo'),
(435, 'Pacific/Fiji'),
(436, 'Pacific/Funafuti'),
(437, 'Pacific/Galapagos'),
(438, 'Pacific/Gambier'),
(439, 'Pacific/Guadalcanal'),
(440, 'Pacific/Guam'),
(441, 'Pacific/Honolulu'),
(442, 'Pacific/Johnston'),
(443, 'Pacific/Kiritimati'),
(444, 'Pacific/Kosrae'),
(445, 'Pacific/Kwajalein'),
(446, 'Pacific/Majuro'),
(447, 'Pacific/Marquesas'),
(448, 'Pacific/Midway'),
(449, 'Pacific/Nauru'),
(450, 'Pacific/Niue'),
(451, 'Pacific/Norfolk'),
(452, 'Pacific/Noumea'),
(453, 'Pacific/Pago_Pago'),
(454, 'Pacific/Palau'),
(455, 'Pacific/Pitcairn'),
(456, 'Pacific/Pohnpei'),
(457, 'Pacific/Ponape'),
(458, 'Pacific/Port_Moresby'),
(459, 'Pacific/Rarotonga'),
(460, 'Pacific/Saipan'),
(461, 'Pacific/Samoa'),
(462, 'Pacific/Tahiti'),
(463, 'Pacific/Tarawa'),
(464, 'Pacific/Tongatapu'),
(465, 'Pacific/Truk'),
(466, 'Pacific/Wake'),
(467, 'Pacific/Wallis'),
(468, 'Pacific/Yap'),
(469, 'Brazil/Acre'),
(470, 'Brazil/DeNoronha'),
(471, 'Brazil/East'),
(472, 'Brazil/West'),
(473, 'Canada/Atlantic'),
(474, 'Canada/Central'),
(475, 'Canada/East-Saskatchewan'),
(476, 'Canada/Eastern'),
(477, 'Canada/Mountain'),
(478, 'Canada/Newfoundland'),
(479, 'Canada/Pacific'),
(480, 'Canada/Saskatchewan'),
(481, 'Canada/Yukon'),
(482, 'CET'),
(483, 'Chile/Continental'),
(484, 'Chile/EasterIsland'),
(485, 'CST6CDT'),
(486, 'Cuba'),
(487, 'EET'),
(488, 'Egypt'),
(489, 'Eire'),
(490, 'EST'),
(491, 'EST5EDT'),
(492, 'Etc/GMT'),
(493, 'Etc/GMT+0'),
(494, 'Etc/GMT+1'),
(495, 'Etc/GMT+10'),
(496, 'Etc/GMT+11'),
(497, 'Etc/GMT+12'),
(498, 'Etc/GMT+2'),
(499, 'Etc/GMT+3'),
(500, 'Etc/GMT+4'),
(501, 'Etc/GMT+5'),
(502, 'Etc/GMT+6'),
(503, 'Etc/GMT+7'),
(504, 'Etc/GMT+8'),
(505, 'Etc/GMT+9'),
(506, 'Etc/GMT-0'),
(507, 'Etc/GMT-1'),
(508, 'Etc/GMT-10'),
(509, 'Etc/GMT-11'),
(510, 'Etc/GMT-12'),
(511, 'Etc/GMT-13'),
(512, 'Etc/GMT-14'),
(513, 'Etc/GMT-2'),
(514, 'Etc/GMT-3'),
(515, 'Etc/GMT-4'),
(516, 'Etc/GMT-5'),
(517, 'Etc/GMT-6'),
(518, 'Etc/GMT-7'),
(519, 'Etc/GMT-8'),
(520, 'Etc/GMT-9'),
(521, 'Etc/GMT0'),
(522, 'Etc/Greenwich'),
(523, 'Etc/UCT'),
(524, 'Etc/Universal'),
(525, 'Etc/UTC'),
(526, 'Etc/Zulu'),
(527, 'Factory'),
(528, 'GB'),
(529, 'GB-Eire'),
(530, 'GMT'),
(531, 'GMT+0'),
(532, 'GMT-0'),
(533, 'GMT0'),
(534, 'Greenwich'),
(535, 'Hongkong'),
(536, 'HST'),
(537, 'Iceland'),
(538, 'Iran'),
(539, 'Israel'),
(540, 'Jamaica'),
(541, 'Japan'),
(542, 'Kwajalein'),
(543, 'Libya'),
(544, 'MET'),
(545, 'Mexico/BajaNorte'),
(546, 'Mexico/BajaSur'),
(547, 'Mexico/General'),
(548, 'MST'),
(549, 'MST7MDT'),
(550, 'Navajo'),
(551, 'NZ'),
(552, 'NZ-CHAT'),
(553, 'Poland'),
(554, 'Portugal'),
(555, 'PRC'),
(556, 'PST8PDT'),
(557, 'ROC'),
(558, 'ROK'),
(559, 'Singapore'),
(560, 'Turkey'),
(561, 'UCT'),
(562, 'Universal'),
(563, 'US/Alaska'),
(564, 'US/Aleutian'),
(565, 'US/Arizona'),
(566, 'US/Central'),
(567, 'US/East-Indiana'),
(568, 'US/Eastern'),
(569, 'US/Hawaii'),
(570, 'US/Indiana-Starke'),
(571, 'US/Michigan'),
(572, 'US/Mountain'),
(573, 'US/Pacific'),
(574, 'US/Pacific-New'),
(575, 'US/Samoa'),
(576, 'UTC'),
(577, 'W-SU'),
(578, 'WET'),
(579, 'Zulu');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `newsletter_campaign`
--

CREATE TABLE `newsletter_campaign` (
  `newsletter_campaign_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_login_id` int(11) NOT NULL,
  `title` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `campaign` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `date` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `user_type` enum('prospect','user') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'prospect',
  `active` enum('1','0','-1') COLLATE utf8_unicode_ci NOT NULL DEFAULT '1',
  PRIMARY KEY (`newsletter_campaign_id`),
  KEY `user_login_fk_idx` (`user_login_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `newsletter_campaign_mail`
--

CREATE TABLE `newsletter_campaign_mail` (
  `newsletter_campaign_mail_id` int(11) NOT NULL AUTO_INCREMENT,
  `newsletter_campaign_id` int(11) NOT NULL,
  `title` varchar(80) COLLATE utf8_unicode_ci NOT NULL,
  `template_path` varchar(160) COLLATE utf8_unicode_ci NOT NULL DEFAULT '/apps/prospecting/subcore/templates/',
  `template` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `order_mail` int(11) NOT NULL,
  `active` enum('1','0','-1') COLLATE utf8_unicode_ci NOT NULL DEFAULT '1',
  PRIMARY KEY (`newsletter_campaign_mail_id`),
  KEY `newsletter_campaign_fk_idx` (`newsletter_campaign_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `newsletter_send_per_user_login`
--

CREATE TABLE `newsletter_send_per_user_login` (
  `newsletter_send_per_user_login_id` int(11) NOT NULL AUTO_INCREMENT,
  `newsletter_campaign_mail_id` int(11) NOT NULL,
  `user_login_id` int(11) NOT NULL,
  `prospect_id` int(11) NOT NULL,
  `token` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `date` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `send_date` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `confirm_date` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`newsletter_send_per_user_login_id`),
  KEY `newsletter_campaign_mail_fk_idx` (`newsletter_campaign_mail_id`),
  KEY `user_login_id_idx` (`user_login_id`),
  KEY `prospect_fk_idx` (`prospect_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `permission_per_support_user`
--

CREATE TABLE `permission_per_support_user` (
  `permission_per_support_user_id` int(11) NOT NULL AUTO_INCREMENT,
  `catalog_module_permission_id` int(11) NOT NULL,
  `support_user_id` int(11) NOT NULL,
  `active` enum('-1','0','1') COLLATE utf8_unicode_ci NOT NULL DEFAULT '1',
  PRIMARY KEY (`permission_per_support_user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=16 ;

--
-- Volcado de datos para la tabla `permission_per_support_user`
--

INSERT INTO `permission_per_support_user` (`permission_per_support_user_id`, `catalog_module_permission_id`, `support_user_id`, `active`) VALUES
(1, 7, 1, '1'),
(2, 14, 1, '1'),
(3, 1, 1, '1'),
(4, 2, 1, '1'),
(5, 3, 1, '1'),
(6, 4, 1, '1'),
(7, 5, 1, '1'),
(8, 6, 1, '1'),
(9, 35, 1, '1'),
(10, 18, 1, '1'),
(11, 19, 1, '1'),
(12, 20, 1, '1'),
(13, 21, 1, '1'),
(14, 26, 1, '1'),
(15, 39, 1, '1');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `support_user`
--

CREATE TABLE `support_user` (
  `support_user_id` int(11) NOT NULL AUTO_INCREMENT,
  `names` varchar(80) COLLATE utf8_unicode_ci NOT NULL,
  `mail` varchar(80) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `secret` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `active` enum('-1','0','1') COLLATE utf8_unicode_ci NOT NULL DEFAULT '1',
  PRIMARY KEY (`support_user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Volcado de datos para la tabla `support_user`
--

INSERT INTO `support_user` (`support_user_id`, `names`, `mail`, `password`, `secret`, `active`) VALUES
(1, 'Admin', 'admin@spiderframe.com', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', '915a4beba0b4c1f33f273a2b0172add941f79daa', '1');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `user_address`
--

CREATE TABLE `user_address` (
  `user_address_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_login_id` int(11) DEFAULT NULL,
  `country_id` int(11) DEFAULT NULL,
  `city_id` int(11) DEFAULT NULL,
  `street` varchar(80) COLLATE utf8_unicode_ci DEFAULT NULL,
  `number` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `colony` varchar(80) COLLATE utf8_unicode_ci DEFAULT NULL,
  `zip_code` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`user_address_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Volcado de datos para la tabla `user_address`
--

INSERT INTO `user_address` (`user_address_id`, `user_login_id`, `country_id`, `city_id`, `street`, `number`, `colony`, `zip_code`) VALUES
(1, 1, 159, 1211, NULL, NULL, NULL, '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `user_bank`
--

CREATE TABLE `user_bank` (
  `user_bank_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_login_id` int(11) NOT NULL,
  `bank` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `bank_account` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `clabe` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `sku` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `cis` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `swift` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`user_bank_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Volcado de datos para la tabla `user_bank`
--

INSERT INTO `user_bank` (`user_bank_id`, `user_login_id`, `bank`, `bank_account`, `clabe`, `sku`, `cis`, `swift`) VALUES
(1, 1, 'A', 'A', '222222222222222222', 'A', 'A', 'A');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `user_contact`
--

CREATE TABLE `user_contact` (
  `user_contact_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_login_id` int(11) NOT NULL,
  `cellular` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `phone` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `mail_alternative` varchar(80) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`user_contact_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Volcado de datos para la tabla `user_contact`
--

INSERT INTO `user_contact` (`user_contact_id`, `user_login_id`, `cellular`, `phone`, `mail_alternative`) VALUES
(1, 1, '1823791273891', '', '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `user_data`
--

CREATE TABLE `user_data` (
  `user_data_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_login_id` int(11) DEFAULT NULL,
  `names` varchar(80) COLLATE utf8_unicode_ci NOT NULL,
  `lastname` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `mother_lastname` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `rfc` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `birthday` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `sex` enum('male','female') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'male',
  PRIMARY KEY (`user_data_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Volcado de datos para la tabla `user_data`
--

INSERT INTO `user_data` (`user_data_id`, `user_login_id`, `names`, `lastname`, `mother_lastname`, `rfc`, `birthday`, `sex`) VALUES
(1, 1, 'José Luis', 'De la Rosa', 'Fernandez', '', '', 'female');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `user_login`
--

CREATE TABLE `user_login` (
  `user_login_id` int(11) NOT NULL AUTO_INCREMENT,
  `mail` varchar(80) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `secret` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `date` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `active` enum('-1','0','1') COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`user_login_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Volcado de datos para la tabla `user_login`
--

INSERT INTO `user_login` (`user_login_id`, `mail`, `password`, `secret`, `date`, `active`) VALUES
(1, 'me@mail.com', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'iqzpfowyvc', '1402674701', '1');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `user_message`
--

CREATE TABLE `user_message` (
  `user_message_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_login_id` int(11) NOT NULL,
  `from_user_login_id` int(11) NOT NULL,
  `mail` varchar(80) COLLATE utf8_unicode_ci NOT NULL,
  `language` varchar(80) COLLATE utf8_unicode_ci NOT NULL,
  `message` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `date` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `read` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `active` enum('-1','0','1') COLLATE utf8_unicode_ci NOT NULL DEFAULT '1',
  PRIMARY KEY (`user_message_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Volcado de datos para la tabla `user_message`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `user_settings`
--

CREATE TABLE `user_settings` (
  `user_settings_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_login_id` int(11) DEFAULT NULL,
  `catalog_format_date_id` int(11) DEFAULT NULL,
  `catalog_format_number_id` int(11) DEFAULT NULL,
  `catalog_timezone_id` int(11) DEFAULT NULL,
  `language` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`user_settings_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Volcado de datos para la tabla `user_settings`
--

INSERT INTO `user_settings` (`user_settings_id`, `user_login_id`, `catalog_format_date_id`, `catalog_format_number_id`, `catalog_timezone_id`, `language`) VALUES
(1, 1, 4, 1, 160, 'spanish');
