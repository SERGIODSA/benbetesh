-- phpMyAdmin SQL Dump
-- version 4.0.4
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 21-01-2014 a las 19:42:36
-- Versión del servidor: 5.6.12-log
-- Versión de PHP: 5.4.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de datos: `benbetesh`
--
CREATE DATABASE IF NOT EXISTS `benbetesh` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `benbetesh`;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `benbetesh`
--

CREATE TABLE IF NOT EXISTS `benbetesh` (
  `id_tienda` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `id_idioma` smallint(5) unsigned NOT NULL,
  `imagen_url` varchar(256) NOT NULL,
  `titulo` varchar(100) NOT NULL,
  `horario` varchar(150) NOT NULL,
  `telefono` varchar(30) NOT NULL,
  `dias` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`id_tienda`),
  KEY `fk_tiendas_idioma1_idx` (`id_idioma`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=20 ;

--
-- Volcado de datos para la tabla `benbetesh`
--

INSERT INTO `benbetesh` (`id_tienda`, `id_idioma`, `imagen_url`, `titulo`, `horario`, `telefono`, `dias`) VALUES
(10, 1, 'tiendabenbetesh.jpg', 'BEN BETESH V&Iacute;A ESPAÑA', '9:30AM - 7:30PM', '(+507) 210-3797', 'Lunes a S&aacute;bado'),
(11, 1, 'tiendabenbetesh.jpg', 'BEN BETESH ZONA LIBRE COL&Oacute;N', '8:30AM - 5:00PM', '(+507) 441-0857 / 441-9450', 'Lunes a Domingo'),
(12, 1, 'tiendabenbetesh.jpg', 'BEN BETESH AEROPUERTO INTERNACIONAL DE TOCUMEN', '6:00AM - 10:00PM', '(+507) 238-4343', 'Lunes a Domingo'),
(13, 1, 'tiendabenbetesh.jpg', 'BEN BETESH ALBROOK MALL', 'Lunes a Jueves<br>10:00AM - 8:00PM<br>Viernes a S&aacute;bado<br>10:00AM - 9:00PM<br>Domingo<br>10:30AM - 7:00PM', '(+507) 314-6605 / 314-6606', NULL),
(14, 1, 'tiendabenbetesh.jpg', 'BEN BETESH SHOES & ACCESORIES MULTICENTRO', 'Lunes a Jueves<br>10:00AM - 8:00PM<br>Viernes a S&aacute;bado<br>10:00AM - 9:00PM<br>Domingo<br>11:00AM - 7:00PM', '(+507) 235-4753', NULL),
(15, 2, 'tiendabenbetesh.jpg', 'BEN BETESH VIA ESPAÑA', '9:30AM - 7:30PM', '(+507) 210-3797', 'Monday to Saturday'),
(16, 2, 'tiendabenbetesh.jpg', 'BEN BETESH ZONA LIBRE COLON', '8:30AM - 5:00PM', '(+507) 441-0857 / 441-9450', 'Monday to Sunday'),
(17, 2, 'tiendabenbetesh.jpg', 'BEN BETESH INTERNATIONAL AIRPORT OF TOCUMEN', '6:00AM - 10:00PM', '(+507) 238-4343', 'Monday to Sunday'),
(18, 2, 'tiendabenbetesh.jpg', 'BEN BETESH ALBROOK MALL', 'Monday to Thursday<br>10:00AM - 8:00PM<br>Friday to Saturday<br>10:00AM - 9:00PM<br>Sunday<br>10:30AM - 7:00PM', '(+507) 314-6605 / 314-6606', NULL),
(19, 2, 'tiendabenbetesh.jpg', 'BEN BETESH SHOES & ACCESORIES MULTICENTRO', 'Monday to Thursday<br>10:00AM - 8:00PM<br>Friday to Saturday<br>10:00AM - 9:00PM<br>Sunday<br>11:00AM - 7:00PM', '(+507) 235-4753', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `form_contacto`
--

CREATE TABLE IF NOT EXISTS `form_contacto` (
  `id_contacto` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_idioma` smallint(5) unsigned NOT NULL,
  `nombre` varchar(45) NOT NULL,
  `empresa` varchar(45) NOT NULL,
  `email` varchar(30) NOT NULL,
  `telefono` varchar(20) NOT NULL,
  `pais` varchar(45) NOT NULL,
  `mensaje` text NOT NULL,
  `fecha_creacion` datetime NOT NULL,
  PRIMARY KEY (`id_contacto`),
  KEY `fk_form_contacto_idioma1_idx` (`id_idioma`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `form_empleo`
--

CREATE TABLE IF NOT EXISTS `form_empleo` (
  `id_empleo` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `id_idioma` smallint(5) unsigned NOT NULL,
  `nombre` varchar(40) NOT NULL,
  `telefono` varchar(40) NOT NULL,
  `email` varchar(45) NOT NULL,
  `archivo` varchar(256) NOT NULL,
  `mensaje` text NOT NULL,
  `fecha` datetime NOT NULL,
  PRIMARY KEY (`id_empleo`),
  KEY `fk_form_empleo_idioma1_idx` (`id_idioma`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `idioma`
--

CREATE TABLE IF NOT EXISTS `idioma` (
  `id_idioma` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `descripcion` varchar(20) NOT NULL,
  PRIMARY KEY (`id_idioma`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Volcado de datos para la tabla `idioma`
--

INSERT INTO `idioma` (`id_idioma`, `descripcion`) VALUES
(1, 'Español'),
(2, 'Ingles');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `marcas`
--

CREATE TABLE IF NOT EXISTS `marcas` (
  `id_marca` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `id_idioma` smallint(5) unsigned NOT NULL,
  `nombre` varchar(256) NOT NULL,
  `amigable` varchar(256) NOT NULL,
  `imagen_url` varchar(256) DEFAULT NULL,
  `logo_url` varchar(256) NOT NULL,
  `slideshow1_url` varchar(256) DEFAULT NULL,
  `slideshow2_url` varchar(256) DEFAULT NULL,
  `slideshow3_url` varchar(256) DEFAULT NULL,
  `slideshow4_url` varchar(256) DEFAULT NULL,
  `slideshow5_url` varchar(256) DEFAULT NULL,
  `tienda1_url` varchar(256) NOT NULL,
  `tienda2_url` varchar(256) NOT NULL,
  `tienda3_url` varchar(256) NOT NULL,
  `titulo1` varchar(15) NOT NULL,
  `descripcion1` text NOT NULL,
  `titulo2` varchar(15) NOT NULL,
  `descripcion2` text NOT NULL,
  `titulo3` varchar(15) NOT NULL,
  `descripcion3` text NOT NULL,
  `tiendas_pie` tinyint(1) NOT NULL,
  `marcas_pie` tinyint(1) NOT NULL,
  `descripcion_form` varchar(60) NOT NULL,
  `telefono` varchar(30) NOT NULL,
  `url_facebook` varchar(256) DEFAULT NULL,
  `url_twitter` varchar(256) DEFAULT NULL,
  `url_youtube` varchar(256) DEFAULT NULL,
  PRIMARY KEY (`id_marca`),
  KEY `fk_marcas_idioma1_idx` (`id_idioma`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=19 ;

--
-- Volcado de datos para la tabla `marcas`
--

INSERT INTO `marcas` (`id_marca`, `id_idioma`, `nombre`, `amigable`, `imagen_url`, `logo_url`, `slideshow1_url`, `slideshow2_url`, `slideshow3_url`, `slideshow4_url`, `slideshow5_url`, `tienda1_url`, `tienda2_url`, `tienda3_url`, `titulo1`, `descripcion1`, `titulo2`, `descripcion2`, `titulo3`, `descripcion3`, `tiendas_pie`, `marcas_pie`, `descripcion_form`, `telefono`, `url_facebook`, `url_twitter`, `url_youtube`) VALUES
(1, 1, 'Crocs', 'crocs', 'CrocsTienda.jpg', 'CrocsLogo.jpg', 'CrocsA.jpg', 'CrocsB.jpg', NULL, NULL, NULL, 'Crocs1.jpg', 'Crocs2.jpg', 'Crocs3.jpg', 'PERFIL', '<p>La reconocida marca Crocs<strong><em>&trade;</em></strong>&nbsp;, l&iacute;der mundial en calzado casual e innovador, se vende en 125 pa&iacute;ses alrededor del mundo. Existen m&aacute;s de 120 modelos para adaptarse a cada estilo de vida: para aquellos que est&aacute;n todo el d&iacute;a de pie y necesitan zapatos c&oacute;modos,&nbsp; profesionales de la salud o de la alimentaci&oacute;n, marineros, peluqueros, vendedores, viajeros y amantes de zapatos confortables</p>\r\n\r\n<p>Gracias a su tecnolog&iacute;a Croslite<strong><em>&trade;</em></strong>, una resina patentada de c&eacute;lulas cerrada que se calienta y se suaviza con el calor corporal adapt&aacute;ndose a la piel del consumidor, hace que los Crocs<strong><em>&trade;</em></strong> sean ligeros, con mayor adherencia y resistentes a deslizamientos, las bacterias y a los malos olores: los Crocs<strong><em>&trade;</em></strong> son la mejor opci&oacute;n pensada para la mujer, hombre y ni&ntilde;o de hoy.</p>', 'HISTORIA', '<p>La reconocida marca Crocs<strong><em>&trade;</em></strong>&nbsp;l&iacute;der mundial en calzado casual, tiene sus inicios en el a&ntilde;o 2002 cuando tres inventores aficionados: George Boedecker, Lyndon &quot;Duke&quot; Hanson y Scott Seamans, durante unas vacaciones quedaron encantados con la creaci&oacute;n de unos zapatos con agujeros y decidieron ponerlos en el mercado. Financiados por conocidos, comenzaron a vender estos zapatos diferentes y extra&ntilde;os, pero muy c&oacute;modos, resistentes a los malos olores y cualquier bacteria u hongo. Pensados inicialmente en deportistas n&aacute;uticos sus fundadores so&ntilde;aban con unos zapatos que fueran perfectos para navegar, funcionales, divertidos, bonitos y muy c&oacute;modos.</p>\r\n\r\n<p>Logrando en 2 a&ntilde;os lo que una marca muy popular realiza generalmente en 10 a&ntilde;os; Crocs<strong><em>&trade;</em></strong>&nbsp;lleg&oacute; al mercado mundial para quedarse y marcar un hito en la historia de los zapatos y la moda.</p>', 'TIENDAS', '<table border="0" cellpadding="1" cellspacing="1" style="width:100%">\r\n	<tbody>\r\n		<tr>\r\n			<td width="50%">\r\n			<p><strong>Colombia</strong></p>\r\n\r\n			<p>Cali</p>\r\n\r\n			<p>San Gil</p>\r\n\r\n			<p>Bogot&aacute;</p>\r\n\r\n			<p>Manizales</p>\r\n\r\n			<p>Cucuta</p>\r\n\r\n			<p>Burunga</p>\r\n\r\n			<p>Barrancabermeja</p>\r\n\r\n			<p>Pereira</p>\r\n\r\n			<p>Villavicencio</p>\r\n\r\n			<p>Yopal</p>\r\n\r\n			<p>Valle Dupar</p>\r\n\r\n			<p>Neiva</p>\r\n\r\n			<p>Cartago</p>\r\n\r\n			<p>Llano Grande</p>\r\n\r\n			<p>Armenia</p>\r\n\r\n			<p>Monter&iacute;a</p>\r\n\r\n			<p>Sincelejo</p>\r\n\r\n			<p>Medell&iacute;n</p>\r\n\r\n			<p>Barranquilla</p>\r\n\r\n			<p>Cartagena</p>\r\n\r\n			<p>Santa Marta</p>\r\n\r\n			<p>&nbsp;</p>\r\n\r\n			<p><strong>Costa Rica</strong></p>\r\n\r\n			<p>San Jos&eacute;</p>\r\n\r\n			<p>&nbsp;</p>\r\n\r\n			<p><strong>Ecuador</strong></p>\r\n\r\n			<p>Guayaquil</p>\r\n\r\n			<p>Quito</p>\r\n\r\n			<p>&nbsp;</p>\r\n\r\n			<p><strong>El Salvador</strong></p>\r\n\r\n			<p>San Salvador</p>\r\n			</td>\r\n			<td>\r\n			<p><strong>Guatemala</strong></p>\r\n\r\n			<p>Ciudad Guatemala</p>\r\n\r\n			<p>Suchitpequez- Mazatenango</p>\r\n\r\n			<p>Sacatepequez</p>\r\n\r\n			<p>&nbsp;</p>\r\n\r\n			<p><strong>Honduras</strong></p>\r\n\r\n			<p>Tegucigalpa</p>\r\n\r\n			<p>San Pedro Sula</p>\r\n\r\n			<p>La Ceiba</p>\r\n\r\n			<p>Palenque</p>\r\n\r\n			<p>&nbsp;</p>\r\n\r\n			<p><strong>Panam&aacute;</strong></p>\r\n\r\n			<p>Ciudad de Panam&aacute;</p>\r\n\r\n			<p>Coronado</p>\r\n\r\n			<p>Changuinola</p>\r\n\r\n			<p>Chiriqu&iacute;</p>\r\n\r\n			<p>Chitr&eacute;</p>\r\n\r\n			<p>Paso Canoas</p>\r\n\r\n			<p>&nbsp;</p>\r\n\r\n			<p><strong>Nicaragua</strong></p>\r\n\r\n			<p>Managua</p>\r\n\r\n			<p>&nbsp;</p>\r\n\r\n			<p><strong>Venezuela</strong></p>\r\n\r\n			<p>Caracas</p>\r\n\r\n			<p>Maracay</p>\r\n\r\n			<p>Paraguan&aacute;</p>\r\n\r\n			<p>Barquisimento</p>\r\n\r\n			<p>Margarita / Porlamar</p>\r\n\r\n			<p>Anzo&aacute;tegui / Puerto La Cruz</p>\r\n			</td>\r\n		</tr>\r\n	</tbody>\r\n</table>\r\n\r\n<p>&nbsp;</p>', 1, 1, 'info@benbetesh.com', '(+507) 223-8895 / 223-9459', NULL, NULL, NULL),
(2, 2, 'Crocs', 'crocs', 'CrocsTienda.jpg', 'CrocsLogo.jpg', 'CrocsA.jpg', 'CrocsB.jpg', NULL, NULL, NULL, 'Crocs1.jpg', 'Crocs2.jpg', 'Crocs3.jpg', 'PROFILE', '<p>Missing Content!</p>', 'HISTORY', '<p>Missing Content!</p>', 'STORES', '<p>Missing Content!</p>', 1, 1, 'info@benbetesh.com', '(+507) 223-8895 / 223-9459', NULL, NULL, NULL),
(3, 1, 'Lacoste', 'lacoste', 'LacosteTienda.jpg', 'logolacoste.jpg', 'LacosteA.jpg', 'LacosteB.jpg', NULL, NULL, NULL, 'lacoste1.jpg', 'lacoste2.jpg', 'lacoste3.jpg', 'PERFIL', '<p>En la actualidad, la marca Lacoste est&aacute; presente en 114 pa&iacute;ses.&nbsp; M&aacute;s de&nbsp;80&nbsp;a&ntilde;os despu&eacute;s de su creaci&oacute;n, Lacoste se ha convertido en una marca &ldquo;estilo de vida&rdquo; que combina elegancia e informalidad&nbsp;y celebra el &ldquo;joie de vivre&rdquo;.</p>\r\n\r\n<p>El arte de vivir Lacoste se expresa actualmente a trav&eacute;s de una amplia colecci&oacute;n de prendas para hombre, mujer, y ni&ntilde;o; zapatos, perfumes, gafas, relojes, textil para el hogar y joyas de fantas&iacute;a.</p>', 'HISTORIA', '<p>La aut&eacute;ntica historia del &ldquo;Cocodrilo&rdquo; se remonta a 1923 y comienza con la apuesta que Ren&eacute; Lacoste hab&iacute;a hecho con el Capit&aacute;n del Equipo de Francia de La Copa Davis, Allan H. Muhr, que le prometi&oacute; una maleta de piel de cocodrilo si ganaba un importante partido para el equipo. Este episodio fue objeto de un art&iacute;culo del Boston Evening Transcript, en el que el periodista llamaba por primera vez a Ren&eacute; Lacoste &ldquo;El Cocodrilo&rdquo;&nbsp;y aunque hab&iacute;a perdido la apuesta hab&iacute;a jugado con tenacidad, ah&iacute; nace la leyenda.</p>\r\n\r\n<p>A finales de los a&ntilde;os 20, Ren&eacute; Lacoste dise&ntilde;&oacute; y mand&oacute; a confeccionar para su uso personal un lote de camisas de algod&oacute;n en una malla ventilada con el logo del famoso cocodrilo&nbsp;dise&ntilde;ado por su amigo Robert George. Era una camisa c&oacute;moda, que absorb&iacute;a perfectamente el sudor, permit&iacute;a soportar mejor el calor en las pistas americanas. En 1993, Ren&eacute; Lacoste se asocia con el gran fabricante de g&eacute;neros de puntos Andr&eacute; Gillier para lanzar la producci&oacute;n industrial del primer polo con la marca del cocodrillo.</p>', 'TIENDAS', '<table border="0" cellpadding="1" cellspacing="1" style="width:100%">\r\n	<tbody>\r\n		<tr>\r\n			<td width="50%">\r\n			<p><strong>Aruba</strong></p>\r\n\r\n			<p>&nbsp;</p>\r\n\r\n			<p><strong>Colombia</strong></p>\r\n\r\n			<p>Armenia</p>\r\n\r\n			<p>Barranquilla</p>\r\n\r\n			<p>Bogot&aacute;</p>\r\n\r\n			<p>Bucaramanga</p>\r\n\r\n			<p>Cali</p>\r\n\r\n			<p>Cartagena</p>\r\n\r\n			<p>Medell&iacute;n</p>\r\n\r\n			<p>Pereira</p>\r\n\r\n			<p>Santa Marta</p>\r\n\r\n			<p>Villavicencio</p>\r\n\r\n			<p>Cucuta</p>\r\n\r\n			<p>San Andr&eacute;s</p>\r\n\r\n			<p>&nbsp;</p>\r\n\r\n			<p><strong>Costa Rica</strong></p>\r\n\r\n			<p>San Jos&eacute;</p>\r\n\r\n			<p>&nbsp;</p>\r\n\r\n			<p><strong>Curacao</strong></p>\r\n\r\n			<p>&nbsp;</p>\r\n\r\n			<p><strong>Ecuador</strong></p>\r\n\r\n			<p>Quito</p>\r\n\r\n			<p>Guayaquil</p>\r\n			</td>\r\n			<td>&nbsp;\r\n			<p><strong>El Salvador</strong></p>\r\n\r\n			<p>San Salvador</p>\r\n\r\n			<p>&nbsp;</p>\r\n\r\n			<p><strong>Guatemala</strong></p>\r\n\r\n			<p>Ciudad de Guatemala</p>\r\n\r\n			<p>&nbsp;</p>\r\n\r\n			<p><strong>Honduras</strong></p>\r\n\r\n			<p>Tegucigalpa</p>\r\n\r\n			<p>San Pedro Sula</p>\r\n\r\n			<p>&nbsp;</p>\r\n\r\n			<p><strong>Nicaragua</strong></p>\r\n\r\n			<p>Managua</p>\r\n\r\n			<p>&nbsp;</p>\r\n\r\n			<p><strong>Panam&aacute;</strong></p>\r\n\r\n			<p>Ciudad de Panam&aacute;</p>\r\n\r\n			<p>&nbsp;</p>\r\n\r\n			<p><strong>Per&uacute;</strong></p>\r\n\r\n			<p>Lima</p>\r\n\r\n			<p>&nbsp;</p>\r\n\r\n			<p><strong>Suriname</strong></p>\r\n\r\n			<p>Paramaribo</p>\r\n\r\n			<p>&nbsp;</p>\r\n\r\n			<p><strong>Venezuela</strong></p>\r\n			</td>\r\n		</tr>\r\n	</tbody>\r\n</table>', 1, 1, 'info@benbetesh.com', '(+507) 223-8895 / 223-9459', 'https://www.facebook.com/Lacoste', 'https://twitter.com/LACOSTE', 'http://www.youtube.com/user/lacosteofficial'),
(4, 2, 'Lacoste', 'lacoste', 'LacosteTienda.jpg', 'logolacoste.jpg', 'LacosteA.jpg', 'LacosteB.jpg', NULL, NULL, NULL, 'lacoste1.jpg', 'lacoste2.jpg', 'lacoste3.jpg', 'PROFILE', '<p>Missing Content!</p>', 'HISTORY', '<p>Missing Content!</p>', 'STORES', '<p>Missing Content!</p>', 1, 1, 'info@benbetesh.com', '(+507) 223-8895 / 223-9459', 'https://www.facebook.com/Lacoste', 'https://twitter.com/LACOSTE', 'http://www.youtube.com/user/lacosteofficial'),
(5, 1, 'Speedo', 'speedo', 'SpeedoTienda.jpg', 'speedologo.jpg', 'SpeedoA.jpg', 'SpeedoB.jpg', 'SpeedoC.jpg', 'SpeedoD.jpg', NULL, 'Speedo1.jpg', 'small2.jpg', 'small3.jpg', 'PERFIL', '<p>Speedo fue la primera compa&ntilde;&iacute;a en producir trajes de ba&ntilde;o con nylon y elastano, el m&aacute;s popular de los tejidos para ba&ntilde;adores hoy en d&iacute;a, y sigue actualmente siendo innovadora y pionera en el mercado textil de Estados Unidos, Europa, Jap&oacute;n, Nueva Zelanda y Sud&aacute;frica.</p>\r\n\r\n<p>Speedo es perfecto para aquellos amantes del rendimiento, estilo, calidad y dise&ntilde;o de ba&ntilde;adores. La marca Speedo est&aacute; actualmente protegida en 112 pa&iacute;ses y es considerada un referente mundial, ofreciendo productos con gran valor respecto a su precio, calidad, seguridad e impacto ambiental.</p>', 'HISTORIA', '<p>Tras la aceptaci&oacute;n de la nataci&oacute;n como deporte, en 1914 el joven escoc&eacute;s Alexander MacRae inaugurar&iacute;a una f&aacute;brica textil en Australia donde lanzar&iacute;a el cl&aacute;sico &ldquo;Razerback&rdquo;. Este ba&ntilde;ador ce&ntilde;ido al cuerpo conced&iacute;a mayor libertad de movimientos, permitiendo a quien lo llevaba nadar m&aacute;s deprisa.</p>\r\n\r\n<p>Bajo el lema &ldquo;Speed on in your Speedos&rdquo; numerosos nadadores ganaron medallas y&nbsp; batieron r&eacute;cords mundiales llevando este ba&ntilde;ador. Pero su pasi&oacute;n por el rendimiento siempre se ha extendido m&aacute;s all&aacute; de la piscina, ya que ha sido pionero en crear trajes de ba&ntilde;o de dos piezas cuando los bikinis no estaban aun considerados aun una pieza &ldquo;decente&rdquo;.</p>', 'TIENDAS', '<table border="0" cellpadding="1" cellspacing="1" style="width:100%">\r\n	<tbody>\r\n		<tr>\r\n			<td width="50%">\r\n			<p><strong>Panam&aacute;</strong></p>\r\n\r\n			<p>Ciudad de Panam&aacute;</p>\r\n\r\n			<p>&nbsp;</p>\r\n\r\n			<p><strong>Costa Rica</strong></p>\r\n\r\n			<p>San Jos&eacute;</p>\r\n\r\n			<p>&nbsp;</p>\r\n\r\n			<p><strong>Honduras</strong></p>\r\n\r\n			<p>San Pedro</p>\r\n\r\n			<p>Tegucigalpa</p>\r\n\r\n			<p>La Ceiba</p>\r\n			</td>\r\n			<td>\r\n			<p><strong>Guatemala</strong></p>\r\n\r\n			<p>Ciudad de Guatemala</p>\r\n\r\n			<p>&nbsp;</p>\r\n\r\n			<p><strong>El Salvador</strong></p>\r\n\r\n			<p>San Salvador</p>\r\n\r\n			<p>&nbsp;</p>\r\n\r\n			<p><strong>Nicaragua</strong></p>\r\n\r\n			<p>Managua</p>\r\n			</td>\r\n		</tr>\r\n	</tbody>\r\n</table>\r\n\r\n<p>&nbsp;</p>', 1, 1, 'info@benbetesh.com', '(+507) 223-8895 / 223-9459', NULL, NULL, NULL),
(6, 2, 'Speedo', 'speedo', 'SpeedoTienda.jpg', 'speedologo.jpg', 'SpeedoA.jpg', 'SpeedoB.jpg', 'SpeedoC.jpg', 'SpeedoD.jpg', NULL, 'Speedo1.jpg', 'small2.jpg', 'small3.jpg', 'PROFILE', '<p>Missing Content!</p>', 'HISTORY', '<p>Missing Content!</p>', 'STORES', '<p>Missing Content!</p>', 1, 1, 'info@benbetesh.com', '(+507) 223-8895 / 223-9459', NULL, NULL, NULL),
(7, 1, 'Harmont & Blaine', 'harmont-blaine', 'H&BTienda.jpg', 'H&BLogo.jpg', 'H&BA.jpg', 'H&BB.jpg', 'H&BC.jpg', NULL, NULL, 'H&B1.jpg', 'H&B2.jpg', 'H&B3.jpg', 'PERFIL', '<p>Distinguida por su reconocido logo del perro dachshund, &ldquo;Harmont &amp; Blaine&rdquo; es una sofisticada l&iacute;nea de ropa informal reconocida tanto en Italia como mundialmente por su alta calidad y constante b&uacute;squeda de colores y textiles innovadores y rompedores.</p>\r\n\r\n<p>&ldquo;Harmont &amp; Blaine&rdquo; es perfecto para acompa&ntilde;ar al hombre y a la mujer en la recaptura de su tiempo libre, vestir sus emociones, aspiraciones y alegr&iacute;as, contadas desde el estilo de vida del mediterr&aacute;neo. &ldquo;Harmont &amp; Blaine&rdquo; &nbsp;deshace la interpretaci&oacute;n tradicional y convencional de la moda, para darle una visi&oacute;n mucho m&aacute;s emocionante e imaginativa.</p>', 'HISTORIA', '<p>Desde 1995 &ldquo;Harmont &amp; Blaine&rdquo; ha dirigido sus productos hacia personas que vivan las exigencias de la vida moderna combinando dinamismo, confort y calidad para promocionarles un producto que sea coherente con el estilo de vida al que aspiran. A lo largo de los a&ntilde;os numerosas colecciones han sido creadas en torno a este pensamiento que, con &eacute;xito, se ha conseguido que &ldquo;Harmont &amp; Blaine&rdquo; haya conseguido niveles de crecimiento insuperables convirti&eacute;ndose en una referencia internacional en el sector de ropa informal de gama alta.</p>\r\n\r\n<p>Pocos a&ntilde;os han bastado para que &ldquo;Harmont &amp; Blaine&rdquo; se consolide como la marca italiana l&iacute;der en el sector de la ropa informal para hombre, con presencia en m&aacute;s de 50 pa&iacute;ses: a&ntilde;os de &eacute;xitos conseguidos a trav&eacute;s de una mezcla perfecta de emprendimiento, innovaci&oacute;n y audacia en la creatividad. &nbsp;</p>', 'TIENDAS', '<table border="0" cellpadding="1" cellspacing="1" style="width:100%">\r\n	<tbody>\r\n		<tr>\r\n			<td width="50%">\r\n			<p><strong>Colombia</strong></p>\r\n\r\n			<p>Bogot&aacute;</p>\r\n\r\n			<p>Bucaramanga</p>\r\n\r\n			<p>Medell&iacute;n</p>\r\n\r\n			<p>Cartagena</p>\r\n\r\n			<p>Barranquilla</p>\r\n\r\n			<p>&nbsp;</p>\r\n\r\n			<p><strong>Panam&aacute;</strong></p>\r\n\r\n			<p>Ciudad de Panam&aacute;</p>\r\n			</td>\r\n			<td>\r\n			<p><strong>Per&uacute; </strong></p>\r\n\r\n			<p>Lima</p>\r\n\r\n			<p>&nbsp;</p>\r\n\r\n			<p><strong>Venezuela</strong></p>\r\n\r\n			<p>Caracas</p>\r\n\r\n			<p>&nbsp;</p>\r\n\r\n			<p><strong>M&eacute;xico</strong></p>\r\n\r\n			<p>M&eacute;xico D.F.</p>\r\n			</td>\r\n		</tr>\r\n	</tbody>\r\n</table>\r\n\r\n<p>&nbsp;</p>', 1, 1, 'info@benbetesh.com', '(+507) 223-8895 / 223-9459', NULL, NULL, NULL),
(8, 2, 'Harmont & Blaine', 'harmont-blaine', 'H&BTienda.jpg', 'H&BLogo.jpg', 'H&BA.jpg', 'H&BB.jpg', 'H&BC.jpg', NULL, NULL, 'H&B1.jpg', 'H&B2.jpg', 'H&B3.jpg', 'PROFILE', '<p>Missing Content!</p>', 'HISTORY', '<p>Missing Content!</p>', 'STORES', '<p>Missing Content!</p>', 1, 1, 'info@benbetesh.com', '(+507) 223-8895 / 223-9459', NULL, NULL, NULL),
(9, 1, 'Celio', 'celio', 'CelioTienda.jpg', 'celiologo.jpg', 'CelioA.jpg', 'CelioB.jpg', NULL, NULL, NULL, 'Celio1.jpg', 'small2.jpg', 'small3.jpg', 'PERFIL', '<p>Celio* es una marca potente e internacional basada en fuertes valores, que propone exclusivamente al hombre una moda c&oacute;moda, asequible y variada. La Carta de Calidad de Producto Celio* asegura prendas de calidad m&aacute;xima, tras superar rigurosas pruebas en las fases de previas y posteriores a la producci&oacute;n.</p>\r\n\r\n<p>Celio* es la marca perfecta para los amantes de las &uacute;ltimas tendencias ya que su colecci&oacute;n propia &ndash; compuesta por 800 modelos en cada colecci&oacute;n &ndash; est&aacute; ideada por buscadores de tendencias en las principales capitales de la moda.</p>', 'HISTORIA', '<p>Fundada en 1985 por Marc y Laurent Grosman e inspirada por el sportswear, Celio* se ha convertido en 25 a&ntilde;os en la marca internacional ineludible del pr&ecirc;t-&agrave;-porter masculino.</p>\r\n\r\n<p>Tras su conquista de Beirut, Italia, Pa&iacute;ses del Este&hellip; Celio* crea la marca Celio* Club, un nuevo concepto de tienda y colecci&oacute;n de sastrer&iacute;a. Gracias a su presencia en internet con su sitio de venta en l&iacute;nea franc&eacute;s desde 2009 y la presencia de 1.000 tiendas alrededor del mundo, Celio* vende m&aacute;s de 37 millones de art&iacute;culos al a&ntilde;o, ofreciendo a sus clientes productos llenos de moda, &uacute;ltimas tendencias y singularidad.</p>', 'TIENDAS', '<table border="0" cellpadding="1" cellspacing="1" style="width:100%">\r\n	<tbody>\r\n		<tr>\r\n			<td>\r\n			<p><strong>Panam&aacute;</strong></p>\r\n\r\n			<p>Ciudad de Panam&aacute;</p>\r\n			</td>\r\n		</tr>\r\n	</tbody>\r\n</table>', 0, 1, 'info@benbetesh.com', '(+507) 223-8895 / 223-9459', NULL, NULL, NULL),
(10, 2, 'Celio', 'celio', 'CelioTienda.jpg', 'celiologo.jpg', 'CelioA.jpg', 'CelioB.jpg', NULL, NULL, NULL, 'Celio1.jpg', 'small2.jpg', 'small3.jpg', 'PROFILE', '<p>Missing Content!</p>', 'HISTORY', '<p>Missing Content!</p>', 'STORES', '<p>Missing Content!</p>', 0, 1, 'info@benbetesh.com', '(+507) 223-8895 / 223-9459', NULL, NULL, NULL),
(11, 1, 'Red Valentino', 'red-valentino', 'RedValentinoTienda.jpg', 'redvalentinologo.jpg', 'RedValentinoA.jpg', 'RedValentinoB.jpg', 'RedValentinoC.jpg', NULL, NULL, 'RedValentino1.jpg', 'RedValentino2.jpg', 'RedValentino3.jpg', 'PERFIL', '<p>Falta contenido!</p>', 'HISTORIA', '<p>Falta contenido!</p>', 'TIENDAS', '<table border="0" cellpadding="1" cellspacing="1" style="width:100%">\r\n	<tbody>\r\n		<tr>\r\n			<td>\r\n			<p><strong>Panam&aacute;</strong></p>\r\n\r\n			<p>Ciudad de Panam&aacute;</p>\r\n\r\n			<p>&nbsp;</p>\r\n\r\n			<p><strong>Per&uacute;</strong></p>\r\n\r\n			<p>Lima</p>\r\n			</td>\r\n		</tr>\r\n	</tbody>\r\n</table>', 1, 1, 'Corina Arango / Gerente de Marca / ventas@benbetesh.com', '230000', NULL, NULL, NULL),
(12, 2, 'Red Valentino', 'red-valentino', 'RedValentinoTienda.jpg', 'redvalentinologo.jpg', 'RedValentinoA.jpg', 'RedValentinoB.jpg', 'RedValentinoC.jpg', NULL, NULL, 'RedValentino1.jpg', 'RedValentino2.jpg', 'RedValentino3.jpg', 'PROFILE', '<p>Missing Content!</p>', 'HISTORY', '<p>Missing Content!</p>', 'STORES', '<p>Missing Content!</p>', 1, 1, 'Corina Arango / Gerente de Marca / ventas@benbetesh.com', '230000', NULL, NULL, NULL),
(13, 1, 'Ben Betesh', 'ben-betesh', NULL, 'BB_Logo.jpg', 'BenbeteshA.jpg', 'BenBeteshB.jpg', 'BenBeteshC.jpg', 'BenBeteshD.jpg', NULL, 'BenBetesh1.jpg', 'BenBetesh2.jpg', 'BenBetesh3.jpg', 'PERFIL', '<p>Desde su primera tienda &ldquo;Btesh 52&rdquo; en la Avenida Central en el a&ntilde;o 1959, los hermanos Btesh han sido pioneros de la moda en Panam&aacute;. A&ntilde;os m&aacute;s tarde, deciden llamar la tienda &ldquo;Ben Betesh&rdquo;, del hebreo &ldquo;Ben&rdquo; que significa &ldquo;hijo de&rdquo; y Btesh &ldquo;Casa de Fuego&rdquo;.</p>\r\n\r\n<p>En 1960, ampl&iacute;an sus horizontes en el Aeropuerto Internacional de Tocumen y la Zona Libre de Col&oacute;n y ocho a&ntilde;os m&aacute;s tarde llegan a la prestigiosa y popular Avenida &ldquo;V&iacute;a Espa&ntilde;a&rdquo;.</p>\r\n\r\n<p>En el 2004, &ldquo;Ben Betesh&rdquo; abre sus puertas en Albrook Mall, el centro comercial m&aacute;s reconocido de Panam&aacute;. Finalizando el 2013, se lanza un concepto m&aacute;s fresco y moderno con su nueva tienda &ldquo;Ben Betesh &ndash; Shoes and Accessories&rdquo;, en el mall Multicentro.</p>\r\n\r\n<p>En la actualidad, las tiendas &ldquo;Ben Betesh&rdquo; cuentan con reconocidas marcas europeas para clientes con diversos gustos como Harmont &amp; Blaine, Canali, Fratelli &amp; Rossetti; y las marcas casuales y deportivas como: Lacoste, Crocs, Speedo, Celio, entre otras.</p>', 'MARCAS', '<table border="0" cellpadding="1" cellspacing="1" style="width:100%">\r\n	<tbody>\r\n		<tr>\r\n			<td>\r\n			<p><strong>V&iacute;a Espa&ntilde;a</strong></p>\r\n\r\n			<p>Lacoste Canali</p>\r\n\r\n			<p>Eden Park</p>\r\n\r\n			<p>Fratelli</p>\r\n\r\n			<p>Rossetti</p>\r\n\r\n			<p>Harmon &amp; Blaine</p>\r\n\r\n			<p>San Remo</p>\r\n\r\n			<p>Hedgren</p>\r\n\r\n			<p>Ingram</p>\r\n\r\n			<p>Eminent</p>\r\n\r\n			<p>&nbsp;</p>\r\n\r\n			<p><strong>Zona Libre Col&oacute;n</strong></p>\r\n\r\n			<p>Lacoste</p>\r\n\r\n			<p>Celio</p>\r\n\r\n			<p>Bally</p>\r\n\r\n			<p>Le Coq Spotif</p>\r\n\r\n			<p>Adidas</p>\r\n\r\n			<p>Harmont &amp; Blaine</p>\r\n\r\n			<p>Speedo</p>\r\n\r\n			<p>Stelli</p>\r\n\r\n			<p>Eminent</p>\r\n\r\n			<p>Hedgren</p>\r\n\r\n			<p>Butterfly Twist</p>\r\n\r\n			<p>&nbsp;</p>\r\n\r\n			<p>&nbsp;</p>\r\n\r\n			<p><strong>Aeropuerto Internacional de Tocumen</strong></p>\r\n\r\n			<p>Celio</p>\r\n\r\n			<p>Weatherproof</p>\r\n\r\n			<p>Eden Park</p>\r\n\r\n			<p>Crocs</p>\r\n\r\n			<p>Fratelli Rossetti</p>\r\n\r\n			<p>Wolsey</p>\r\n\r\n			<p>Joe Abboud</p>\r\n\r\n			<p>Eminent</p>\r\n\r\n			<p>Hedgren</p>\r\n			</td>\r\n			<td>\r\n			<p><strong>Albrook Mall</strong></p>\r\n\r\n			<p>Hedgren</p>\r\n\r\n			<p>Le Coq Sportif</p>\r\n\r\n			<p>Eminent</p>\r\n\r\n			<p>Stelli</p>\r\n\r\n			<p>Lacoste</p>\r\n\r\n			<p>Celio</p>\r\n\r\n			<p>Crocs</p>\r\n\r\n			<p>Waterproof</p>\r\n\r\n			<p>&nbsp;</p>\r\n\r\n			<p>&nbsp;</p>\r\n\r\n			<p><strong>Shoes &amp; Accesories &ndash; Multicentro</strong></p>\r\n\r\n			<p>Lacoste</p>\r\n\r\n			<p>Crocs</p>\r\n\r\n			<p>Adidas</p>\r\n\r\n			<p>Polo Ralph Lauren</p>\r\n\r\n			<p>Le Coq Sportif</p>\r\n\r\n			<p>Hedgren</p>\r\n			</td>\r\n		</tr>\r\n	</tbody>\r\n</table>', 'TIENDAS', '<table border="0" cellpadding="1" cellspacing="1" style="width:100%">\r\n	<tbody>\r\n		<tr>\r\n			<td>\r\n			<p><strong>Ben Betesh V&iacute;a Espa&ntilde;a</strong></p>\r\n\r\n			<p>Lunes a S&aacute;bado</p>\r\n\r\n			<p>9:30A.M. - 7:30P.M.</p>\r\n\r\n			<p>Tel&eacute;fonos: (+507) 210-3797</p>\r\n\r\n			<p>&nbsp;</p>\r\n\r\n			<p><strong>Ben Betesh Zona Libre Col&oacute;n</strong></p>\r\n\r\n			<p>Lunes a Domingo</p>\r\n\r\n			<p>8:30A.M. - 5:00P.M.</p>\r\n\r\n			<p>Tel&eacute;fonos: (+507) 441-0857 / 441-9450</p>\r\n\r\n			<p>&nbsp;</p>\r\n\r\n			<p><strong>Ben Betesh Aeropuerto Internacional de Tocumen</strong></p>\r\n\r\n			<p>Lunes a Domingo</p>\r\n\r\n			<p>6:00A.M. - 10:00P.M.</p>\r\n\r\n			<p>Tel&eacute;fonos: (+507) 238-4343</p>\r\n\r\n			<p>&nbsp;</p>\r\n\r\n			<p><strong>Ben Betesh Albrook Mall</strong></p>\r\n\r\n			<p>Lunes a Jueves 10:00A.M. - 8:00P.M.</p>\r\n\r\n			<p>Viernes a S&aacute;bado 10:00A.M. - 9:00P.M.</p>\r\n\r\n			<p>Domingo 10:30A.M. - 7:00P.M.</p>\r\n\r\n			<p>Tel&eacute;fonos: (+507) 314-6605 / 314-6606</p>\r\n\r\n			<p>&nbsp;</p>\r\n\r\n			<p><strong>Ben Betesh Shoes &amp; Accesories Multicentro</strong></p>\r\n\r\n			<p>Lunes a Jueves 10:00A.M.- 8:00P.M.</p>\r\n\r\n			<p>Viernes a S&aacute;bado 10:00A.M. - 9:00P.M.</p>\r\n\r\n			<p>Domingo 11:00A.M. - 7:00P.M.</p>\r\n\r\n			<p>Tel&eacute;fonos: (+507) 235-4753</p>\r\n			</td>\r\n		</tr>\r\n	</tbody>\r\n</table>\r\n\r\n<p>&nbsp;</p>', 0, 0, 'info@benbetesh.com', '(+507) 223-8895 / 223-9459', 'https://www.facebook.com/ben.betesh.3?fref=ts', NULL, NULL),
(14, 2, 'Ben Betesh', 'ben-betesh', NULL, 'BB_Logo.jpg', 'BenbeteshA.jpg', 'BenBeteshB.jpg', 'BenBeteshC.jpg', 'BenBeteshD.jpg', '', 'BenBetesh1.jpg', 'BenBetesh2.jpg', 'BenBetesh3.jpg', 'PROFILE', '<p>Missing Content!</p>', 'HISTORY', '<p>Missing Content!</p>', 'STORES', '<p>Missing Content!</p>', 0, 0, 'info@benbetesh.com', '(+507) 223-8895 / 223-9459', 'https://www.facebook.com/ben.betesh.3?fref=ts', NULL, NULL),
(15, 1, 'Bally', 'bally', 'tiendabally.jpg', 'Bally.jpg', 'ballyslide.jpg', 'ballyslide.jpg', 'ballyslide.jpg', 'ballyslide.jpg', 'ballyslide.jpg', 'small1.jpg', 'small2.jpg', 'small3.jpg', 'PERFIL', '<p>Conocidos por ser a la vez est&eacute;ticamente bellos y funcionales, &nbsp;los art&iacute;culos de cuero de BALLY hablan de una herencia suiza que est&aacute; basada en materiales nobles, la atenci&oacute;n al detalle y la elegancia tranquila.</p>\r\n\r\n<p>Tanto los zapatos como la ropa muestran un dise&ntilde;o muy alegre y seducen con sus excepcionales y simp&aacute;ticos detalles. El dise&ntilde;o de BALLY combina tradici&oacute;n y modernidad que se aprecian en los elementos clave de la artesan&iacute;a en cuero como el trenzado y tejido, los recortes, bordados, mallas y perforaciones.</p>', 'HISTORIA', '<p>En 1851, Carl Franz Bally fund&oacute; la empresa BALLY en Suiza. Despu&eacute;s de uno de sus viajes a Par&iacute;s del que trae unas bonitas zapatillas con ornamentos para su mujer, Bally aprovecha para desarrollar nuevos procedimientos en la fabricaci&oacute;n de estos elegantes zapatos hechos a mano.&nbsp; En el a&ntilde;o 1916, alcanza la incre&iacute;ble cifra de 3,9 millones de pares de zapatos vendidos.</p>\r\n\r\n<p>Desde 1950, la marca BALLY tambi&eacute;n ofrece bolsos, complementos de cuero y ropa de confecci&oacute;n. BALLY es una de las marcas de lujo m&aacute;s antiguas del mundo y sigue creciendo a&ntilde;o tras a&ntilde;o, con operaciones a nivel mundial y negocios de comercio electr&oacute;nico.</p>', 'TIENDAS', '<table border="0" cellpadding="1" cellspacing="1" style="width:100%">\r\n	<tbody>\r\n		<tr>\r\n			<td>\r\n			<p><strong>Panam&aacute;</strong></p>\r\n\r\n			<p>Ciudad de Panam&aacute;</p>\r\n\r\n			<p>&nbsp;</p>\r\n\r\n			<p><strong>Per&uacute; </strong></p>\r\n\r\n			<p>Lima</p>\r\n			</td>\r\n		</tr>\r\n	</tbody>\r\n</table>', 1, 0, 'info@benbetesh.com', '(+507) 223-8895 / 223-9459', NULL, NULL, NULL),
(16, 2, 'Bally', 'bally', 'tiendabally.jpg', 'Bally.jpg', 'ballyslide.jpg', 'ballyslide.jpg', 'ballyslide.jpg', 'ballyslide.jpg', 'ballyslide.jpg', 'small1.jpg', 'small2.jpg', 'small3.jpg', 'PROFILE', '<p>Missing Content!</p>', 'HISTORY', '<p>Missing Content!</p>', 'STORES', '<p>Missing Content!</p>', 1, 0, 'info@benbetesh.com', '(+507) 223-8895 / 223-9459', NULL, NULL, NULL),
(17, 1, 'Scotch & Soda', 'scotch-soda', 'sstienda.jpg', 'sslogo.jpg', 'ssslide.jpg', 'ssslide.jpg', 'ssslide.jpg', 'ssslide.jpg', 'ssslide.jpg', 'small1.jpg', 'small2.jpg', 'small3.jpg', 'PERFIL', '<p>Falta contenido</p>', 'HISTORIA', '<p>Falta contenido</p>', 'TIENDAS', '<p>Falta contenido</p>', 1, 1, 'info@benbetesh.com', '(+507) 223-8895 / 223-9459', NULL, NULL, NULL),
(18, 2, 'Scotch & Soda', 'scotch-soda', 'sstienda.jpg', 'sslogo.jpg', 'ssslide.jpg', 'ssslide.jpg', 'ssslide.jpg', 'ssslide.jpg', 'ssslide.jpg', 'small1.jpg', 'small2.jpg', 'small3.jpg', 'PERFIL', '<p>Missing Content!</p>', 'HISTORIA', '<p>Missing Content!</p>', 'TIENDAS', '<p>Missing Content!</p>', 1, 1, 'info@benbetesh.com', '(+507) 223-8895 / 223-9459', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `menu`
--

CREATE TABLE IF NOT EXISTS `menu` (
  `id_menu` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `id_idioma` smallint(5) unsigned NOT NULL,
  `descripcion` varchar(20) NOT NULL,
  `amigable` varchar(20) NOT NULL,
  PRIMARY KEY (`id_menu`),
  KEY `FK_menu_idioma_idx` (`id_idioma`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=15 ;

--
-- Volcado de datos para la tabla `menu`
--

INSERT INTO `menu` (`id_menu`, `id_idioma`, `descripcion`, `amigable`) VALUES
(1, 1, 'INICIO', 'inicio'),
(2, 1, 'MARCAS', 'marcas'),
(3, 1, 'BEN BETESH', 'ben-betesh'),
(4, 1, 'NOSOTROS', 'nosotros'),
(5, 1, 'TRABAJE CON NOSOTROS', 'trabaje-con-nosotros'),
(6, 1, 'NOTICIAS', 'noticias'),
(7, 1, 'CONT&Aacute;CTENOS', 'contactenos'),
(8, 2, 'INDEX', 'index'),
(9, 2, 'BRANDS', 'brands'),
(10, 2, 'BEN BETESH', 'ben-betesh'),
(11, 2, 'ABOUT US', 'about-us'),
(12, 2, 'WORK WITH US', 'work with us'),
(13, 2, 'NEWS', 'news'),
(14, 2, 'CONTACT US', 'contact-us');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `nosotros`
--

CREATE TABLE IF NOT EXISTS `nosotros` (
  `id_nosotros` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `id_idioma` smallint(5) unsigned NOT NULL,
  `imagen1_url` varchar(256) NOT NULL,
  `imagen2_url` varchar(256) NOT NULL,
  `titulo1` varchar(40) NOT NULL,
  `descripcion1` text NOT NULL,
  `titulo2` varchar(15) NOT NULL,
  `descripcion2` text NOT NULL,
  `titulo3` varchar(15) NOT NULL,
  `descripcion3` text NOT NULL,
  PRIMARY KEY (`id_nosotros`),
  KEY `fk_nosotros_idioma1_idx` (`id_idioma`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Volcado de datos para la tabla `nosotros`
--

INSERT INTO `nosotros` (`id_nosotros`, `id_idioma`, `imagen1_url`, `imagen2_url`, `titulo1`, `descripcion1`, `titulo2`, `descripcion2`, `titulo3`, `descripcion3`) VALUES
(1, 1, 'nosotros1prueba.jpg', 'nosotros2prueba.jpg', '¿Quiénes somos?', '<p>Con sede en la Ciudad de Panam&aacute; , Ben Betesh Internacional cuenta con seis oficinas regionales en Panam&aacute;, M&eacute;xico, Colombia, Per&uacute; y Aruba, desde donde se manejan las ventas y la distribuci&oacute;n incluyendo salas de exhibici&oacute;n y almacenes.</p>\r\n\r\n<p>Co-fundada en 1959 por el actual Presidente, el Sr. Jack Btesh, BBI fue el primero en traer marcas europeas a Am&eacute;rica Latina, convirti&eacute;ndose en el mayor importador de productos brit&aacute;nicos en Am&eacute;rica Central con el entonces Presidente, y recibiendo el t&iacute;tulo de caballero de la Orden del Imperio Brit&aacute;nico.</p>\r\n\r\n<p>Los primeros 8 a&ntilde;os de operaci&oacute;n se concentraron exclusivamente en las operaciones de venta de su tienda fundada en el centro de la Ciudad de Panam&aacute;, a donde&nbsp; jefes de estado,&nbsp;&nbsp; los m&aacute;s altos hombres de negocios y artistas viajaban de pa&iacute;ses en toda Am&eacute;rica Latina para encargar trajes hechos a la medida.</p>\r\n\r\n<p>En 1967, la compa&ntilde;&iacute;a tom&oacute; la decisi&oacute;n de traer a la marca parisina Lacoste como la primera marca internacional para distribuci&oacute;n. A partir de ah&iacute;, las operaciones de BBI florecieron en la regi&oacute;n.</p>\r\n\r\n<p>Conocida por ser la primera tienda en traer marcas internacionales en el territorio, el enfoque de la empresa durante las primeras dos d&eacute;cadas fue el de fortalecer y nutrir la mejor operaci&oacute;n minorista multi-marcas en la regi&oacute;n.&nbsp; &nbsp;A finales de los a&ntilde;os 80 la estrategia de la empresa sigui&oacute; evolucionando desde el comercio minorista para incluir una r&aacute;pida y exitosa operaci&oacute;n de distribuci&oacute;n.</p>\r\n\r\n<p>BBI cuenta actualmente con una base de clientes en 27 pa&iacute;ses, manejando m&aacute;s de 190 tiendas al por menor&nbsp; y 730 puntos de venta adicionales.&nbsp;Ben Betesh Intl. ha estado en el negocio&nbsp;<em>duty free</em>&nbsp;desde 1960, cuando la primera tienda abri&oacute; en el Aeropuerto Internacional de Panam&aacute;. Con experiencia en el negocio de las tiendas libres de impuestos en Am&eacute;rica Central, Sur Am&eacute;rica y el Caribe, Betesh cuenta actualmente con 8 tiendas de viaje.</p>', 'MISI&Oacute;N', '<p>Ben Betesh Internacional, S.A. se esfuerza por ser la compa&ntilde;&iacute;a principal de posicionamiento de marca para boutiques de marcas mundiales en Am&eacute;rica Latina en las &aacute;reas de distribuci&oacute;n, venta al por menor y tiendas libres de impuestos.</p>', 'VISI&Oacute;N', '<p>BBI visualiza liderar &nbsp;como el socio preferido en la regi&oacute;n para las empresas internacionales que buscan lograr proyectos exitosos de distribuci&oacute;n, venta al por menor y libres de impuestos en Am&eacute;rica Latina.</p>'),
(2, 2, 'nosotros1prueba.jpg', 'nosotros2prueba.jpg', 'About Us', '<p>Headquartered in Panama City, Ben Betesh International currently has six regional offices handling both the retail and the distribution operations in Panama, Colombia, Mexico, Peru, and Aruba including showrooms and warehouse facilities. Co-founded in 1959 by current Chairman, Mr. Jack Btesh, BBI was the first to bring European brands to Latin America becoming the biggest importer of UK goods in Central America with the then current Chairman receiving an OBE knighthood.</p>\r\n\r\n<p>The first 8 years of operation focused solely in the retail operations of its founding store in downtown Panama City where Presidents, top businessmen and entertainers would travel from countries throughout Latin America to commission hand-tailored suits. In 1967, the company made the decision to bring in the Parisian brand Lacoste as the first international brand for distribution.</p>\r\n\r\n<p>From there on, the Betesh operations in the region blossomed. Known for being the first store to carry international brands in the territory, the focus of the company for the first two decades involved nurturing the best multi-brand retail operation in the region.</p>\r\n\r\n<p>In the late 80&rsquo;s the strategy of the company continued to evolve from retail to include a quickly successful distribution operation. BBI now has a client base in 27 countries, with over 190 retail stores in management and 730 additional points of sale. Ben Betesh Intl. has been in the duty free business since 1960 when the first store was opened in the Panama International Airport. With duty free experience in Central America, South America and the Caribbean, Betesh now owns 8 travel retail stores.</p>', 'MISSION', '<p>Ben Betesh Internacional, S.A. strives to be the premier brand-positioning company for boutique global brands in Latin America in the areas of distribution, retail and duty-free.</p>', 'VISION', '<p>BBI envisions leading as the preferred partner in the region for international companies who seek to achieve successful distribution, retail and duty-free ventures in Latin America.</p>');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `noticias`
--

CREATE TABLE IF NOT EXISTS `noticias` (
  `id_noticia` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `id_idioma` smallint(5) unsigned NOT NULL,
  `fecha` date NOT NULL,
  `titulo` varchar(50) NOT NULL,
  `amigable` varchar(50) NOT NULL,
  `descripcion` text NOT NULL,
  `imagen_url` varchar(256) NOT NULL,
  PRIMARY KEY (`id_noticia`),
  KEY `fk_noticias_idioma1_idx` (`id_idioma`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

--
-- Volcado de datos para la tabla `noticias`
--

INSERT INTO `noticias` (`id_noticia`, `id_idioma`, `fecha`, `titulo`, `amigable`, `descripcion`, `imagen_url`) VALUES
(1, 1, '2014-01-08', 'Ben Betesh apoya a Fundacáncer', 'ben-betesh-apoya-a-fundacancer', '<p>Durante el mes de octubre, Ben Betesh participó en la campaña de la cinta rosada, y apoyó la Campaña de Detección Temprana de Cáncer de Mama donando a Fundacáncer un 10% de las ventas de los productos rosados.</p>\r\n\r\n<p>De izquierda a derecha: Alexandra Castro Novey, Directora Ejecutiva de Fundacáncer y Jack Btesh Hazán, Presidente de Ben Betesh Internacional.</p>', 'noticia1.jpg'),
(2, 2, '2014-01-08', 'Ben Betesh supports Fundacancer', 'ben-betesh-supports-fundacancer', '<p>During the month of October, Ben Betesh participated in the Pink Ribbon campaign and supported the Campaign for Early Breast Cancer Detection Fundacáncer donating 10% of sales of pink products.</p>\r\n\r\n<p>From left to right: Alexandra Castro Novey, Executive Director of Fundacáncer and Jack Btesh Hazán, President of Ben Betesh International.</p>', 'noticia1.jpg'),
(5, 1, '2014-01-08', 'Lacoste celebra su 80 Aniversario', 'lacoste-celebra-su-80-aniversario', '<p>Lacoste celebra su 80 Aniversario con la presentaci&oacute;n de su colecci&oacute;n limitada en colaboraci&oacute;n con el dise&ntilde;ador brit&aacute;nico Peter Saville, realizamos un exclusivo cocktail en Mall Multiplaza Pacific donde la prensa y nuestros distinguidos clientes pudieron conocer un poco m&aacute;s de la historia de Lacoste, su pasado, presente y futuro.</p>', 'noticia2.jpg'),
(6, 1, '2014-01-08', 'Crocs Donación Fundayuda y Operation Walk', 'crocs-donacion-fundayuda-y-operation-walk', '<p>Donaci&oacute;n en la que participamos en el Hospital del San Fernando junto con Fundayuda y Operation Walk. Se donaron 96 pares de calzados, se realiz&oacute; el 7, 8 y 9 de Noviembre 2013.</p>', 'noticia3.jpg'),
(7, 1, '2014-01-08', 'Apertura Red Valentino Multiplaza', 'apertura-red-valentino-multiplaza', '<p>D&iacute;as atr&aacute;s compartimos con invitados y medios la apertura de nuestra tienda Red Valentino en Plaza del Sol 2do piso.</p>', 'noticia4.jpg'),
(8, 1, '2014-01-08', 'Abre sus puertas Ben Betesh Shoes & Accesories', 'abre-sus-puertas-ben-betesh-shoes-accesories', '<p>La reconocida Ben Betesh abre su nueva tienda Shoes &amp; Accesories, en el centro comercial Multicentro con lo mejor de diversas marcas tales como: Lacoste, Le Coq Sportif, Crocs, Adidas, Polo by Ralph Lauren, entre otras.</p>', 'noticia5.jpg');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `oficinas`
--

CREATE TABLE IF NOT EXISTS `oficinas` (
  `id_oficina` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `id_idioma` smallint(5) unsigned NOT NULL,
  `direccion` varchar(100) NOT NULL,
  `lugar_titulo` varchar(50) NOT NULL,
  `telefono` varchar(30) NOT NULL,
  `tienda` varchar(50) DEFAULT NULL,
  `correo` varchar(200) DEFAULT NULL,
  `fax` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`id_oficina`),
  KEY `fk_oficinas_idioma1_idx` (`id_idioma`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=22 ;

--
-- Volcado de datos para la tabla `oficinas`
--

INSERT INTO `oficinas` (`id_oficina`, `id_idioma`, `direccion`, `lugar_titulo`, `telefono`, `tienda`, `correo`, `fax`) VALUES
(1, 1, '<p>Punta Pac&iacute;fica, PH Oceania Business Plaza, torre 1000 y 2000, piso 32</p>', 'PANAM&Aacute;', '(+507) 223-8895 / 223-9459', 'BEN BETESH INTERNACIONAL, S.A.', 'info@benbetesh.com', NULL),
(11, 2, '<p>Punta Pac&iacute;fica, PH Oceania Business Plaza, tower 1000 and 2000, floor 32</p>', 'PANAMA', '(+507) 223-8895 / 223-9459', 'BEN BETESH INTERNATIONAL, LLC.', 'info@benbetesh.com', NULL),
(12, 1, '<p>Zona Libre de Col&oacute;n</p>', 'PANAM&Aacute;', '(+507) 430-3784 / 430-3785', NULL, 'trafico@benbetesh.com', '(+507) 430-3784'),
(13, 1, '<p>Paseo de los Tamarindos #384, piso 6, Colonia Lomas de Palo Alto M&eacute;xico D.F. CP 05119</p>', 'M&Eacute;XICO', '(+52) 55-4774-8405', 'SPORTMEX S.A. DE C.V.', NULL, NULL),
(14, 1, '<p>Calle 41 #6-16, bodega 6, Parque Industrial La Esmeralda</p>', 'COLOMBIA', '(+57) 419-2421', 'COLOMBIANA DE TENIS, S.A.', 'gerencia@coltenis.com', NULL),
(15, 1, '<p>Emmastraat #1, Local 3</p>', 'ARUBA', '(+297) 582-4018', 'COSBA, S.A.', 'laurac@cosbanv.aw', NULL),
(16, 1, '<p>Av. Angamos oeste 1371 &ndash; Miraflores, Lima</p>', 'PER&Uacute;', '(+51) 1-7062400', 'COSPER SAC', 'gerenciacomercial@cosperperu.com', NULL),
(17, 2, '<p>Free zone of Col&oacute;n</p>', 'PANAM&Aacute;', '(+507) 430-3784 / 430-3785', NULL, 'trafico@benbetesh.com', '(+507) 430-3784'),
(18, 2, '<p>Paseo de los Tamarindos #384, piso 6, Colonia Lomas de Palo Alto M&eacute;xico D.F. CP 05119</p>', 'M&Eacute;XICO', '(+52) 55-4774-8405', 'SPORTMEX S.A. DE C.V.', NULL, NULL),
(19, 2, '<p>Calle 41 #6-16, bodega 6, Parque Industrial La Esmeralda</p>', 'COLOMBIA', '(+57) 419-2421', 'COLOMBIANA DE TENIS, S.A.', 'gerencia@coltenis.com', NULL),
(20, 2, '<p>Emmastraat #1, Local 3</p>', 'ARUBA', '(+297) 582-4018', 'COSBA, S.A.', 'laurac@cosbanv.aw', NULL),
(21, 2, '<p>Av. Angamos oeste 1371 &ndash; Miraflores, Lima</p>', 'PER&Uacute;', '(+51) 1-7062400', 'COSPER SAC', 'gerenciacomercial@cosperperu.com', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ss_index`
--

CREATE TABLE IF NOT EXISTS `ss_index` (
  `id_ssindex` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `id_idioma` smallint(5) unsigned NOT NULL,
  `slideshow1_url` varchar(256) DEFAULT NULL,
  `slideshow2_url` varchar(256) DEFAULT NULL,
  `slideshow3_url` varchar(256) DEFAULT NULL,
  `slideshow4_url` varchar(256) DEFAULT NULL,
  `slideshow5_url` varchar(256) DEFAULT NULL,
  PRIMARY KEY (`id_ssindex`),
  KEY `fk_ss_index_idioma1_idx` (`id_idioma`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Volcado de datos para la tabla `ss_index`
--

INSERT INTO `ss_index` (`id_ssindex`, `id_idioma`, `slideshow1_url`, `slideshow2_url`, `slideshow3_url`, `slideshow4_url`, `slideshow5_url`) VALUES
(1, 1, 'BenBeteshC.jpg', 'LacosteA.jpg', 'H&BB.jpg', 'BenBeteshD.jpg', 'CrocsB.jpg'),
(2, 2, 'BenBeteshC.jpg', 'LacosteA.jpg', 'H&BB.jpg', 'BenBeteshD.jpg', 'CrocsB.jpg');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ss_nosotros`
--

CREATE TABLE IF NOT EXISTS `ss_nosotros` (
  `id_ssnosotros` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `id_idioma` smallint(5) unsigned NOT NULL,
  `imagen_url` varchar(256) NOT NULL,
  `titulo` varchar(40) NOT NULL,
  `descripcion` text NOT NULL,
  PRIMARY KEY (`id_ssnosotros`),
  KEY `fk_ss_nosotros_idioma1_idx` (`id_idioma`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Volcado de datos para la tabla `ss_nosotros`
--

INSERT INTO `ss_nosotros` (`id_ssnosotros`, `id_idioma`, `imagen_url`, `titulo`, `descripcion`) VALUES
(1, 1, 'nosotros3prueba.jpg', 'VALORES', '<p>Ben Betesh Intl. se enorgullece en mantener con exito la cultura de la empresa que pone la familia primero. Pertenece y es operada por el Sr. Jack Btesh y sus 4 hijos, junto con mas de 400 colaboradores, todos en Ben Betesh saben que los valores de una familia fuerte son los mismos grandes valores que hacen exitosa a una empresa.</p><p>\r\n<ul class="fuera"><li class="dentro">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Dedicación<li class="dentro">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Trabajo en equipo\r\n<li class="dentro">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Compromiso\r\n<li class="dentro">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Lealtad<li class="dentro">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Integridad<li class="dentro">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Liderazgo</ul></p>'),
(2, 2, 'nosotros3prueba.jpg', 'VALUES', '<p>Ben Betesh Intl is proud to successfully maintain the culture of the company that puts family first. Owned and operated by Mr. Jack Btesh and their 4 children, along with more than 400 employees, all on Ben Betesh know that strong family values are the same great values that make a business successful.</p><p>\r\n<ul class="fuera"><li class="dentro">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Dedication<li class="dentro">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Teamwork<li class="dentro">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Commitment\r\n<li class="dentro">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Loyalty<li class="dentro">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Integrity<li class="dentro">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Leadership</ul></p>'),
(3, 1, 'nosotros4prueba.jpg', 'HISTORIA', '<p>Ben Betesh Internacional es la distribuidora líder en América Latina, especializada en el desarrollo y crecimiento de marcas de lujo en la región. Con sede en la Ciudad de Panamá, Ben Betesh cuenta con oficinas regionales en Ciudad México, Lima, Perú y Aruba, sirviendo a una base de ventas y clientes en 27 países.</p><p>Ben Betesh fue fundada en 1959 y se basa en los principios de excelencia, integridad, trabajo en equipo y liderazgo. La compañía cuenta con más de 500 empleados que se encuentran orgullosos de trabajar con las mejores marcas internacionales y ofrecer siempre productos de la más alta calidad.</p>'),
(4, 2, 'nosotros4prueba.jpg', 'HISTORY', '<p>Ben Betesh International is the leading distributor in Latin America, specializing in the development and growth of luxury brands in the region. Based in Panama City, Ben Betesh has regional offices in Mexico City, Lima, Peru and Aruba, serving a customer base and sales in 27 countries.</p><p>Ben Betesh was founded in 1959 and is based on the principles of excellence, integrity, teamwork and leadership. The company has over 500 employees who are proud to work with the best international brands and products always offer the highest calidad.De left to right: Alexandra Castro Novey, Executive Director and Jack Btesh Fundacáncer Hazán, President of Ben International Betesh.</p>');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `trabajo`
--

CREATE TABLE IF NOT EXISTS `trabajo` (
  `id_trabajo` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `id_idioma` smallint(5) unsigned NOT NULL,
  `titulo1` varchar(30) NOT NULL,
  `descripcion1` text NOT NULL,
  `titulo2` varchar(30) NOT NULL,
  `descripcion2` text NOT NULL,
  PRIMARY KEY (`id_trabajo`),
  KEY `fk_trabajo_idioma1_idx` (`id_idioma`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Volcado de datos para la tabla `trabajo`
--

INSERT INTO `trabajo` (`id_trabajo`, `id_idioma`, `titulo1`, `descripcion1`, `titulo2`, `descripcion2`) VALUES
(1, 1, 'TRABAJE CON NOSOTROS', '<p>El crecimiento que hemos alcanzado como compañía se debe a la calidad humana que siempre nos ha caracterizado y al reconocimiento de nuestra gente como el factor más importante.</p><p>Ahora esperamos que seas parte de este equipo de gente exitosa, entusiasta, comprometida, íntegra, orientada al resultado, con proyección y calidad de propósito.</p>\r\n<p>Nuestra expectativa es que Ben Betesh International se convierta en tu segundo hogar, que te sientas feliz y orgulloso de pertenecer a nuestra organización y que logremos ser un motor para tu.</p>', 'BENEFICIOS', 'Es un hecho establecido hace demasiado tiempo que un lector se distraerá con el contenido del texto de un sitio mientras que mira su diseño. El punto de usar Lorem Ipsum es que tiene una distribución más o menos normal de las letras, al contrario de usar textos como por ejemplo "Contenido aquí, contenido aquí". Estos textos hacen parecerlo un español que se puede leer. Muchos paquetes de autoedición y editores de páginas web usan el Lorem Ipsum como su texto por defecto, y al hacer una búsqueda de "Lorem Ipsum" va a dar por resultado muchos sitios web que usan este texto si se encuentran en estado de desarrollo. Muchas versiones han evolucionado a través de los años, algunas veces por accidente, otras veces a propósito (por ejemplo insertándole humor y cosas por el estilo).\r\nEs un hecho establecido hace demasiado tiempo que un lector se distraerá con el contenido del texto de un sitio mientras que mira su diseño. El punto de usar Lorem Ipsum es que tiene una distribución más o menos normal de las letras, al contrario de usar textos como por ejemplo "Contenido aquí, contenido aquí". Estos textos hacen parecerlo un español que se puede leer. Muchos paquetes de autoedición y editores de páginas web usan el Lorem Ipsum como su texto por defecto, y al hacer una búsqueda de "Lorem Ipsum" va a dar por resultado muchos sitios web que usan este texto si se encuentran en estado de desarrollo. Muchas versiones han evolucionado a través de los años, algunas veces por accidente, otras veces a propósito (por ejemplo insertándole humor y cosas por el estilo).'),
(2, 2, 'WORK WITH US', 'Lorem Ipsum es simplemente el texto de relleno de las imprentas y archivos de texto. Lorem Ipsum ha sido el texto de relleno estándar de las industrias desde el año 1500, cuando un impresor (N. del T. persona que se dedica a la imprenta) desconocido usó una galería de textos y los mezcló de tal manera que logró hacer un libro de textos especimen. No sólo sobrevivió 500 años, sino que tambien ingresó como texto de relleno en documentos electrónicos, quedando esencialmente igual al original. Fue popularizado en los 60s con la creación de las hojas "Letraset", las cuales contenian pasajes de Lorem Ipsum, y más recientemente con software de autoedición, como por ejemplo Aldus PageMaker, el cual incluye versiones de Lorem Ipsum.', 'BENEFITS', 'Es un hecho establecido hace demasiado tiempo que un lector se distraerá con el contenido del texto de un sitio mientras que mira su diseño. El punto de usar Lorem Ipsum es que tiene una distribución más o menos normal de las letras, al contrario de usar textos como por ejemplo "Contenido aquí, contenido aquí". Estos textos hacen parecerlo un español que se puede leer. Muchos paquetes de autoedición y editores de páginas web usan el Lorem Ipsum como su texto por defecto, y al hacer una búsqueda de "Lorem Ipsum" va a dar por resultado muchos sitios web que usan este texto si se encuentran en estado de desarrollo. Muchas versiones han evolucionado a través de los años, algunas veces por accidente, otras veces a propósito (por ejemplo insertándole humor y cosas por el estilo).');

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `benbetesh`
--
ALTER TABLE `benbetesh`
  ADD CONSTRAINT `fk_tiendas_idioma1` FOREIGN KEY (`id_idioma`) REFERENCES `idioma` (`id_idioma`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `form_contacto`
--
ALTER TABLE `form_contacto`
  ADD CONSTRAINT `fk_form_contacto_idioma1` FOREIGN KEY (`id_idioma`) REFERENCES `idioma` (`id_idioma`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `form_empleo`
--
ALTER TABLE `form_empleo`
  ADD CONSTRAINT `fk_form_empleo_idioma1` FOREIGN KEY (`id_idioma`) REFERENCES `idioma` (`id_idioma`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `marcas`
--
ALTER TABLE `marcas`
  ADD CONSTRAINT `fk_marcas_idioma1` FOREIGN KEY (`id_idioma`) REFERENCES `idioma` (`id_idioma`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `menu`
--
ALTER TABLE `menu`
  ADD CONSTRAINT `FK_menu_idioma` FOREIGN KEY (`id_idioma`) REFERENCES `idioma` (`id_idioma`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `nosotros`
--
ALTER TABLE `nosotros`
  ADD CONSTRAINT `fk_nosotros_idioma1` FOREIGN KEY (`id_idioma`) REFERENCES `idioma` (`id_idioma`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `noticias`
--
ALTER TABLE `noticias`
  ADD CONSTRAINT `fk_noticias_idioma1` FOREIGN KEY (`id_idioma`) REFERENCES `idioma` (`id_idioma`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `oficinas`
--
ALTER TABLE `oficinas`
  ADD CONSTRAINT `fk_oficinas_idioma1` FOREIGN KEY (`id_idioma`) REFERENCES `idioma` (`id_idioma`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `ss_index`
--
ALTER TABLE `ss_index`
  ADD CONSTRAINT `fk_ss_index_idioma1` FOREIGN KEY (`id_idioma`) REFERENCES `idioma` (`id_idioma`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `ss_nosotros`
--
ALTER TABLE `ss_nosotros`
  ADD CONSTRAINT `fk_ss_nosotros_idioma1` FOREIGN KEY (`id_idioma`) REFERENCES `idioma` (`id_idioma`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `trabajo`
--
ALTER TABLE `trabajo`
  ADD CONSTRAINT `fk_trabajo_idioma1` FOREIGN KEY (`id_idioma`) REFERENCES `idioma` (`id_idioma`) ON DELETE NO ACTION ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
