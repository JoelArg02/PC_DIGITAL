CREATE DATABASE citaviso CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci;

use citaviso;

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `u900659423_citaviso`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cliente`
--

CREATE TABLE `cliente` (
  `id_cliente` int(11) NOT NULL,
  `cedula` varchar(10) DEFAULT NULL,
  `nombre` varchar(80) DEFAULT NULL,
  `telefono` varchar(10) DEFAULT NULL,
  `direccion` mediumtext DEFAULT NULL,
  `dateadd` datetime NOT NULL DEFAULT current_timestamp(),
  `usuario_id` int(11) NOT NULL,
  `estatus` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Volcado de datos para la tabla `cliente`
--

INSERT INTO `cliente` (`id_cliente`, `cedula`, `nombre`, `telefono`, `direccion`, `dateadd`, `usuario_id`, `estatus`) VALUES
(1, '2250200512', 'Carlos Javier Granda Velasquez', '0997884812', 'AVENIDA GENERAL RUMIÑAGUI', '2023-08-30 05:26:29', 1, 1),
(2, '1102668934', 'Alex Gabriel Garcia Carrasco', '0997443822', 'Espe', '2023-08-30 05:27:59', 1, 1),
(3, '1754020624', 'Joel Daniel Arguello Espinosa', '0988778482', 'AVENIDA GENERAL RUMIÑAGUI', '2023-08-30 05:30:18', 1, 1),
(4, '0801458225', 'Ana Karla Gonzalez Gomez', '0997881212', 'Quito', '2023-08-30 05:31:50', 1, 1),
(5, '2250020092', 'Nelson Isaac Granda Velasquez', '0997684811', 'Loja', '2023-08-30 05:34:32', 1, 1),
(6, '1751276526', 'Alisson Mireya Cueva Arguello', '0977181212', 'Manabi', '2023-08-30 05:37:15', 1, 1),
(7, '1105480386', 'Rony Gabriel Granda Rosario', '0987181619', 'Guayaquil', '2023-08-30 05:40:16', 1, 1),
(8, '1710202449', 'Jorge Daniel Arguello Espinosa', '0998500498', 'Calderon', '2023-08-30 05:40:51', 1, 1),
(9, '1713124426', 'Beronica Mercedes Espinosa Rodriguez', '0998500498', 'Carapungo', '2023-08-30 05:41:22', 1, 1),
(10, '1105480691', 'Ana Maria Pozo Ganchozo', '0988185815', 'Los Rios', '2023-08-30 05:45:10', 1, 1);

--
-- Disparadores `cliente`
--
DELIMITER $$
CREATE TRIGGER `insert_client_i` AFTER INSERT ON `cliente` FOR EACH ROW BEGIN
    INSERT INTO client_i (id_cliente, cedula, nombre, telefono, direccion, dateadd, usuario_id)
    VALUES (NEW.id_cliente, NEW.cedula, NEW.nombre, NEW.telefono, NEW.direccion, NEW.dateadd, NEW.usuario_id);
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `client_i`
--

CREATE TABLE `client_i` (
  `id_cliente` int(11) NOT NULL,
  `cedula` varchar(20) DEFAULT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `telefono` varchar(15) DEFAULT NULL,
  `direccion` varchar(200) DEFAULT NULL,
  `dateadd` datetime DEFAULT NULL,
  `usuario_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Volcado de datos para la tabla `client_i`
--

INSERT INTO `client_i` (`id_cliente`, `cedula`, `nombre`, `telefono`, `direccion`, `dateadd`, `usuario_id`) VALUES
(1, '2250200512', 'Carlos Javier Granda Velasquez', '0997884812', 'AVENIDA GENERAL RUMIÑAGUI', '2023-08-30 05:26:29', 1),
(2, '1102668934', 'Alex Gabriel Garcia Carrasco', '0997884812', 'AVENIDA GENERAL RUMIÑAGUI', '2023-08-30 05:27:59', 1),
(3, '1754020624', 'Joel Daniel Arguello Espinosa', '0997884812', 'AVENIDA GENERAL RUMIÑAGUI', '2023-08-30 05:30:18', 1),
(4, '0801458225', 'Ana Karla González Gómez', '0997881212', 'Quito', '2023-08-30 05:31:50', 1),
(5, '2250020092', 'Granda Velasquez Nelson Isaac', '0997684811', 'Loja', '2023-08-30 05:34:32', 1),
(6, '1751276526', 'Alisson Mireya Cueva Arguello', '0977181212', 'Manabi', '2023-08-30 05:37:15', 1),
(7, '1105480386', 'Rony Gabriel Granda Rosario', '0987181619', 'Guayaquil', '2023-08-30 05:40:16', 1),
(8, '1710202449', 'Jorge Daniel Arguello Espinosa', '0998500498', 'Calderon', '2023-08-30 05:40:51', 1),
(9, '1713124426', 'Beronica Mercedes Espinosa Rodriguez', '0998500498', 'Carapungo', '2023-08-30 05:41:22', 1),
(10, '1105480691', 'Ana Maria Pozo Ganchozo', '0988185815', 'Los Rios', '2023-08-30 05:45:10', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `client_log_update`
--

CREATE TABLE `client_log_update` (
  `id_change` int(11) NOT NULL,
  `id_client` int(11) NOT NULL,
  `old_cedula` varchar(20) DEFAULT NULL,
  `new_cedula` varchar(20) DEFAULT NULL,
  `old_name` varchar(100) DEFAULT NULL,
  `new_name` varchar(100) DEFAULT NULL,
  `old_tel` varchar(15) DEFAULT NULL,
  `new_tel` varchar(15) DEFAULT NULL,
  `old_dir` varchar(200) DEFAULT NULL,
  `new_dir` varchar(200) DEFAULT NULL,
  `dateadd` datetime DEFAULT current_timestamp(),
  `usuario_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Volcado de datos para la tabla `client_log_update`
--

INSERT INTO `client_log_update` (`id_change`, `id_client`, `old_cedula`, `new_cedula`, `old_name`, `new_name`, `old_tel`, `new_tel`, `old_dir`, `new_dir`, `dateadd`, `usuario_id`) VALUES
(1, 2, '1102668934', '2250200512', 'Alex Gabriel Garcia Carrasco', 'Alex Gabriel Garcia Carrasco', '0997884812', '0997884812', 'AVENIDA GENERAL RUMIÑAGUI', 'AVENIDA GENERAL RUMIÑAGUI', '2023-08-30 05:28:15', 1),
(2, 2, '2250200512', '1102668934', 'Alex Gabriel Garcia Carrasco', 'Alex Gabriel Garcia Carrasco', '0997884812', '0997884812', 'AVENIDA GENERAL RUMIÑAGUI', 'AVENIDA GENERAL RUMIÑAGUI', '2023-08-30 05:28:53', 1),
(3, 2, '1102668934', '1102668934', 'Alex Gabriel Garcia Carrasco', 'Alex Gabriel Garcia Carrasco', '0997884812', '0997884812', 'AVENIDA GENERAL RUMIÑAGUI', 'Espe', '2023-08-30 05:50:22', 1),
(4, 2, '1102668934', '1102668934', 'Alex Gabriel Garcia Carrasco', 'Alex Gabriel Garcia Carrasco', '0997884812', '0997884812', 'Espe', 'Espe', '2023-08-30 05:54:31', 1),
(5, 5, '2250020092', '2250020092', 'Granda Velasquez Nelson Isaac', 'Nelson Isaac Granda Velasquez', '0997684811', '0997684811', 'Loja', 'Loja', '2023-08-30 06:11:44', 1),
(6, 2, '1102668934', '1102668934', 'Alex Gabriel Garcia Carrasco', 'Alex Gabriel Garcia Carrasco', '0997884812', '0997443822', 'Espe', 'Espe', '2023-08-30 06:12:46', 1),
(7, 3, '1754020624', '1754020624', 'Joel Daniel Arguello Espinosa', 'Joel Daniel Arguello Espinosa', '0997884812', '0988778482', 'AVENIDA GENERAL RUMIÑAGUI', 'AVENIDA GENERAL RUMIÑAGUI', '2023-08-30 06:13:08', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `login_log`
--

CREATE TABLE `login_log` (
  `id_log` int(11) NOT NULL,
  `usuario_id` varchar(45) NOT NULL,
  `accion` varchar(45) NOT NULL,
  `user_ip` varchar(45) NOT NULL,
  `date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Volcado de datos para la tabla `login_log`
--

INSERT INTO `login_log` (`id_log`, `usuario_id`, `accion`, `user_ip`, `date`) VALUES
(1, '1', 'Inicio sesión', 'local', '2023-08-30 05:10:30'),
(2, '1', 'Inicio sesión', 'local', '2023-08-30 05:17:07'),
(3, '1', 'Inicio sesión', 'local', '2023-08-30 05:25:53'),
(4, '1', 'Inicio sesión', 'local', '2023-08-30 05:31:30'),
(5, '1', 'Inicio sesión', 'local', '2023-08-30 05:39:07'),
(6, '1', 'Inicio sesión', 'local', '2023-08-30 05:43:13'),
(7, '1', 'Inicio sesión', 'local', '2023-08-30 05:46:30'),
(8, '1', 'Inicio sesión', 'local', '2023-08-30 05:49:06'),
(9, '1', 'Inicio sesión', 'local', '2023-08-30 06:03:46'),
(10, '1', 'Inicio sesión', 'local', '2023-08-30 06:06:02'),
(11, '1', 'Inicio sesión', 'local', '2023-08-30 06:08:31'),
(12, '1', 'Inicio sesión', 'local', '2023-08-30 06:12:09'),
(13, '1', 'Inicio sesión', 'local', '2023-08-30 06:15:35'),
(14, '1', 'Inicio sesión', 'local', '2023-08-30 06:15:57'),
(15, '1', 'Inicio sesión', 'local', '2023-08-30 06:16:52'),
(16, '1', 'Inicio sesión', 'local', '2023-08-30 06:18:27'),
(17, '1', 'Inicio sesión', 'local', '2023-08-30 06:41:21'),
(18, '1', 'Inicio sesión', 'local', '2023-08-30 06:45:46'),
(19, '1', 'Inicio sesión', 'local', '2023-08-30 07:10:34'),
(20, '1', 'Inicio sesión', 'local', '2023-08-30 07:16:49'),
(21, '1', 'Inicio sesión', 'local', '2023-08-30 07:21:54'),
(22, '1', 'Inicio sesión', 'local', '2023-08-30 07:30:51'),
(23, '1', 'Inicio sesión', 'local', '2023-08-30 07:32:54'),
(24, '1', 'Inicio sesión', 'local', '2023-08-30 07:34:24'),
(25, '1', 'Inicio sesión', 'local', '2023-08-30 07:36:37'),
(26, '1', 'Inicio sesión', 'local', '2023-08-30 07:37:21');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ordenes`
--

CREATE TABLE `ordenes` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `customer_name` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `estatus` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Volcado de datos para la tabla `ordenes`
--

INSERT INTO `ordenes` (`id`, `user_id`, `customer_name`, `created_at`, `updated_at`, `estatus`) VALUES
(1, 1, 'Carlos Javier Granda Velasquez', '2023-08-30 07:58:43', '2023-08-30 07:58:43', 0),
(2, 1, 'Granda Velasquez Nelson Isaac', '2023-08-30 08:11:33', '2023-08-30 08:11:33', 1),
(3, 1, 'Ana Karla', '2023-08-30 07:31:59', '2023-08-30 07:31:59', 1),
(4, 1, 'Joel Daniel Arguello Espinosa', '2023-08-30 07:33:26', '2023-08-30 07:33:26', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ordenes_recetas`
--

CREATE TABLE `ordenes_recetas` (
  `orden_id` int(11) NOT NULL,
  `receta_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 0,
  `estatus` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Volcado de datos para la tabla `ordenes_recetas`
--

INSERT INTO `ordenes_recetas` (`orden_id`, `receta_id`, `quantity`, `estatus`) VALUES
(1, 1, 2, 1),
(2, 1, 4, 1),
(2, 3, 3, 1),
(3, 1, 1, 1),
(4, 4, 1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `producto`
--

CREATE TABLE `producto` (
  `codproducto` int(11) NOT NULL,
  `descripcion` varchar(100) DEFAULT NULL,
  `medida` varchar(45) NOT NULL,
  `proveedor` int(11) DEFAULT NULL,
  `precio` decimal(10,2) DEFAULT NULL,
  `existencia` float DEFAULT NULL,
  `date_add` datetime NOT NULL DEFAULT current_timestamp(),
  `usuario_id` int(11) NOT NULL,
  `estatus` int(11) NOT NULL DEFAULT 1,
  `id_measurement` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `producto`
--

INSERT INTO `producto` (`codproducto`, `descripcion`, `medida`, `proveedor`, `precio`, `existencia`, `date_add`, `usuario_id`, `estatus`, `id_measurement`) VALUES
(1, 'Madera', '100 x 100 cm', 1, 40.00, 711, '2023-08-30 05:32:58', 1, 1, NULL),
(2, 'Tornillo de madera', 'Cabeza Plana', 5, 0.23, 3546, '2023-08-30 05:33:48', 1, 1, NULL),
(3, 'Tornillo de madera', 'Cabeza Cilindrica', 5, 1.00, 1801, '2023-08-30 05:34:41', 1, 1, NULL),
(4, 'Perno', 'Ojo con hombro', 7, 1.23, 4555, '2023-08-30 05:36:26', 1, 1, NULL),
(5, 'Triplex', '80x120 cm', 1, 25.50, 60, '2023-08-30 05:38:28', 1, 1, NULL),
(6, 'Laca', '400 L', 9, 56.90, 540, '2023-08-30 05:49:31', 1, 1, NULL),
(7, 'Clavos ', '1 1/2\'', 6, 0.50, 288, '2023-08-30 05:51:55', 1, 1, NULL),
(8, 'Clavos', '3\'', 4, 0.60, 4000, '2023-08-30 05:52:57', 1, 1, NULL),
(9, 'Pata goma', '5 x 5 cm', 8, 0.30, 8000, '2023-08-30 05:54:37', 1, 1, NULL),
(10, 'Tornillos', '1/2', 2, 0.20, 4968, '2023-08-30 05:55:29', 1, 1, NULL),
(11, 'Pernos', '3/1', 8, 1.31, 180, '2023-08-30 05:56:19', 1, 1, NULL);

--
-- Disparadores `producto`
--
DELIMITER $$
CREATE TRIGGER `insert_product_i` AFTER INSERT ON `producto` FOR EACH ROW BEGIN
    INSERT INTO `product_i` (id_producto, name, supplier, price, stock, id_user)
    VALUES (NEW.codproducto, NEW.descripcion, NEW.proveedor, NEW.precio, NEW.existencia, NEW.usuario_id);
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `product_i`
--

CREATE TABLE `product_i` (
  `id_producto` int(10) NOT NULL,
  `name` varchar(45) NOT NULL,
  `supplier` int(10) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `stock` int(11) NOT NULL,
  `date_add` datetime NOT NULL DEFAULT current_timestamp(),
  `id_user` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Volcado de datos para la tabla `product_i`
--

INSERT INTO `product_i` (`id_producto`, `name`, `supplier`, `price`, `stock`, `date_add`, `id_user`) VALUES
(1, 'Madera', 5, 1.40, 1, '2023-08-30 05:32:58', 1),
(2, 'Tornillo de madera', 5, 0.23, 1, '2023-08-30 05:33:48', 1),
(3, 'Tornillo de madera', 5, 1.00, 1, '2023-08-30 05:34:41', 1),
(4, 'Perno', 7, 1.23, 1, '2023-08-30 05:36:26', 1),
(5, 'Triplex', 1, 25.50, 180, '2023-08-30 05:38:28', 1),
(6, 'Laca', 9, 56.90, 600, '2023-08-30 05:49:31', 1),
(7, 'Clavos ', 6, 0.50, 300, '2023-08-30 05:51:55', 1),
(8, 'Clavos', 4, 0.60, 4000, '2023-08-30 05:52:57', 1),
(9, 'Pata goma', 8, 0.30, 8000, '2023-08-30 05:54:37', 1),
(10, 'Tornillos', 2, 0.20, 4968, '2023-08-30 05:55:29', 1),
(11, 'Pernos', 8, 1.31, 160, '2023-08-30 05:56:19', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `product_log_update`
--

CREATE TABLE `product_log_update` (
  `id` int(11) NOT NULL,
  `producto_id` int(11) DEFAULT NULL,
  `usuario_id` int(11) DEFAULT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp(),
  `cambios` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`cambios`)),
  `old_medida` varchar(45) NOT NULL,
  `new_medida` varchar(45) NOT NULL,
  `old_price` decimal(10,2) DEFAULT NULL,
  `new_price` decimal(10,2) DEFAULT NULL,
  `old_supplier` int(11) DEFAULT NULL,
  `new_supplier` int(11) DEFAULT NULL,
  `old_stock` int(11) DEFAULT NULL,
  `new_stock` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Volcado de datos para la tabla `product_log_update`
--

INSERT INTO `product_log_update` (`id`, `producto_id`, `usuario_id`, `fecha_registro`, `cambios`, `old_medida`, `new_medida`, `old_price`, `new_price`, `old_supplier`, `new_supplier`, `old_stock`, `new_stock`) VALUES
(1, 1, 1, '2023-08-30 05:39:23', '{\"precio\":{\"anterior\":\"1.40\",\"nuevo\":\"1.40\"},\"proveedor\":{\"anterior\":\"5\",\"nuevo\":\"5\"},\"medida\":{\"anterior\":\"20cm * 30cm*\",\"nuevo\":\"20cm * 30cm\"},\"stock\":{\"anterior\":\"1\",\"nuevo\":\"1\"}}', '20cm * 30cm*', '20cm * 30cm', 1.40, 1.40, 5, 5, 1, 1),
(2, 1, 1, '2023-08-30 05:39:32', '{\"precio\":{\"anterior\":\"1.40\",\"nuevo\":\"1.40\"},\"proveedor\":{\"anterior\":\"5\",\"nuevo\":\"5\"},\"stock\":{\"anterior\":\"2\",\"nuevo\":\"2\"}}', '', '', 1.40, 1.40, 5, 5, 2, 2),
(3, 1, 1, '2023-08-30 05:40:49', '{\"precio\":{\"anterior\":\"1.40\",\"nuevo\":\"40.00\"},\"proveedor\":{\"anterior\":\"5\",\"nuevo\":\"1\"},\"stock\":{\"anterior\":\"2\",\"nuevo\":\"2\"}}', '', '', 1.40, 40.00, 5, 1, 2, 2),
(4, 1, 1, '2023-08-30 05:43:58', '{\"precio\":{\"anterior\":\"40.00\",\"nuevo\":\"40.00\"},\"proveedor\":{\"anterior\":\"1\",\"nuevo\":\"1\"},\"medida\":{\"anterior\":\"5\",\"nuevo\":\"20 x 20 cm\"},\"stock\":{\"anterior\":\"2\",\"nuevo\":\"2\"}}', '5', '20 x 20 cm', 40.00, 40.00, 1, 1, 2, 2),
(5, 1, 1, '2023-08-30 05:47:34', '{\"precio\":{\"anterior\":\"40.00\",\"nuevo\":\"40.00\"},\"proveedor\":{\"anterior\":\"1\",\"nuevo\":\"1\"},\"medida\":{\"anterior\":\"1\",\"nuevo\":\"100 x 100 cm\"},\"stock\":{\"anterior\":\"802\",\"nuevo\":\"802\"}}', '1', '100 x 100 cm', 40.00, 40.00, 1, 1, 802, 802),
(6, 2, 1, '2023-08-30 05:56:00', '{\"precio\":{\"anterior\":\"0.23\",\"nuevo\":\"0.23\"},\"proveedor\":{\"anterior\":\"5\",\"nuevo\":\"5\"},\"medida\":{\"anterior\":\"Cabeza Plana\",\"nuevo\":\"Cabeza Plana\"},\"stock\":{\"anterior\":\"1\",\"nuevo\":\"1\"}}', 'Cabeza Plana', 'Cabeza Plana', 0.23, 0.23, 5, 5, 1, 1),
(7, 3, 1, '2023-08-30 05:56:28', '{\"precio\":{\"anterior\":\"1.00\",\"nuevo\":\"1.00\"},\"proveedor\":{\"anterior\":\"5\",\"nuevo\":\"5\"},\"medida\":{\"anterior\":\"Cabeza Cilindrica\",\"nuevo\":\"Cabeza Cilindrica\"},\"stock\":{\"anterior\":\"1\",\"nuevo\":\"1\"}}', 'Cabeza Cilindrica', 'Cabeza Cilindrica', 1.00, 1.00, 5, 5, 1, 1),
(8, 4, 1, '2023-08-30 05:56:47', '{\"precio\":{\"anterior\":\"1.23\",\"nuevo\":\"1.23\"},\"proveedor\":{\"anterior\":\"7\",\"nuevo\":\"7\"},\"medida\":{\"anterior\":\"Ojo con hombro\",\"nuevo\":\"Ojo con hombro\"},\"stock\":{\"anterior\":\"1\",\"nuevo\":\"1\"}}', 'Ojo con hombro', 'Ojo con hombro', 1.23, 1.23, 7, 7, 1, 1),
(9, 11, 1, '2023-08-30 05:56:59', '{\"precio\":{\"anterior\":\"1.31\",\"nuevo\":\"1.31\"},\"proveedor\":{\"anterior\":\"8\",\"nuevo\":\"8\"},\"medida\":{\"anterior\":\"3/1\",\"nuevo\":\"3/1\"},\"stock\":{\"anterior\":\"160\",\"nuevo\":\"160\"}}', '3/1', '3/1', 1.31, 1.31, 8, 8, 160, 160);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `product_measurement`
--

CREATE TABLE `product_measurement` (
  `id_measurement` int(11) NOT NULL,
  `measurement` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proveedor`
--

CREATE TABLE `proveedor` (
  `id_supplier` int(11) NOT NULL,
  `cedula` varchar(10) DEFAULT NULL,
  `proveedor` varchar(100) DEFAULT NULL,
  `contacto` varchar(100) DEFAULT NULL,
  `correo` varchar(320) DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `direccion` text DEFAULT NULL,
  `date_add` datetime NOT NULL DEFAULT current_timestamp(),
  `usuario_id` int(11) NOT NULL,
  `estatus` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `proveedor`
--

INSERT INTO `proveedor` (`id_supplier`, `cedula`, `proveedor`, `contacto`, `correo`, `telefono`, `direccion`, `date_add`, `usuario_id`, `estatus`) VALUES
(1, '1753368099', 'Maderas S.A', 'Steven Pozo', 'stevenpozo0999@gmail.com', '0981515127', 'Tumbaco', '2023-08-30 05:20:45', 1, 1),
(2, '1303753618', 'Importadora Marvin', 'Ana Fernanda', 'anaFernanda@gamil.com', '0998500498', 'Cumbaya', '2023-08-30 05:22:50', 1, 1),
(3, '1103756134', 'Provitech', 'Carlos Granda', 'carlos0999@gmail.com', '0917385288', 'Puembo', '2023-08-30 05:24:12', 1, 1),
(4, '0301506044', 'Edimca', 'Klever Aguilar', 'klever1234@gmail.com', '0991372423', 'Conocoto', '2023-08-30 05:25:40', 1, 1),
(5, '1709784613', 'Comadera', 'Joel Arguello', 'joelAr12@gmail.com', '0999876543', 'Calderón', '2023-08-30 05:27:02', 1, 1),
(6, '0400911897', 'Quiminet', 'Roberto Burbano', 'roberto1234@gmail.com', '0981514567', 'Carapungo', '2023-08-30 05:29:09', 1, 1),
(7, '0700685563', 'Kukitas S.A', 'Erick Ramirez', 'erick2002@gmail.com', '0913896965', 'Miami', '2023-08-30 05:30:44', 1, 1),
(8, '0301509238', 'Tornilloski Forte', 'Lizeth Rodriguez', 'lizUwU@gmail.com', '0998500111', 'San Francisco y Av Universitaria', '2023-08-30 05:32:50', 1, 1),
(9, '0702931510', 'LiquidHouse', 'Maria DB', 'maria@gamil.com', '0911884812', 'Belén', '2023-08-30 05:34:02', 1, 1),
(10, '2250200512', 'Plastic E.C', 'Anabelle Chucky', 'anabelle@gmail.com', '0981215223', 'Cavernas del Ilalo', '2023-08-30 05:36:45', 1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `recipe`
--

CREATE TABLE `recipe` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `price` decimal(15,2) NOT NULL,
  `estatus` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Volcado de datos para la tabla `recipe`
--

INSERT INTO `recipe` (`id`, `user_id`, `name`, `price`, `estatus`, `created_at`, `updated_at`) VALUES
(1, 1, 'Mesa', 162.00, 1, '2023-08-30 07:58:11', '2023-08-30 07:58:11'),
(2, 1, 'Mesa LS', 1.00, 1, '2023-08-30 06:07:35', '2023-08-30 06:07:35'),
(3, 1, 'Armario', 2558.00, 1, '2023-08-30 08:09:41', '2023-08-30 08:09:41'),
(4, 1, 'Armario xl', 0.23, 1, '2023-08-30 07:28:42', '2023-08-30 07:28:42');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `recipe_log`
--

CREATE TABLE `recipe_log` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `price` decimal(15,2) NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rol`
--

CREATE TABLE `rol` (
  `idrol` int(11) NOT NULL,
  `permiso_crear_usuarios` tinyint(1) DEFAULT NULL,
  `permiso_ver_usuarios` tinyint(1) DEFAULT NULL,
  `permiso_ver_proveedores` tinyint(1) DEFAULT NULL,
  `permiso_crear_proveedor` tinyint(1) DEFAULT NULL,
  `permiso_ver_productos` tinyint(1) DEFAULT NULL,
  `permiso_crear_productos` tinyint(1) DEFAULT NULL,
  `permiso_agregar_productos` tinyint(1) DEFAULT NULL,
  `permiso_crear_hoja_tecnica` tinyint(1) DEFAULT NULL,
  `permiso_ver_hojas_tecnicas` tinyint(1) DEFAULT NULL,
  `permiso_ver_ordenes` tinyint(1) DEFAULT NULL,
  `permiso_crear_ordenes` tinyint(1) DEFAULT NULL,
  `permiso_ver_clientes` tinyint(1) DEFAULT NULL,
  `permiso_crear_clientes` tinyint(1) DEFAULT NULL,
  `permiso_ver_reportes` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Volcado de datos para la tabla `rol`
--

INSERT INTO `rol` (`idrol`, `permiso_crear_usuarios`, `permiso_ver_usuarios`, `permiso_ver_proveedores`, `permiso_crear_proveedor`, `permiso_ver_productos`, `permiso_crear_productos`, `permiso_agregar_productos`, `permiso_crear_hoja_tecnica`, `permiso_ver_hojas_tecnicas`, `permiso_ver_ordenes`, `permiso_crear_ordenes`, `permiso_ver_clientes`, `permiso_crear_clientes`, `permiso_ver_reportes`) VALUES
(1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1),
(2, 0, 1, 1, 0, 1, 0, 0, 0, 0, 1, 0, 1, 0, 1),
(3, 0, 0, 0, 0, 1, 1, 1, 0, 0, 0, 0, 0, 0, 0),
(4, 0, 0, 0, 0, 1, 1, 1, 0, 0, 0, 0, 0, 0, 0),
(5, 0, 1, 1, 0, 1, 0, 0, 0, 0, 1, 0, 1, 0, 1),
(6, 0, 0, 0, 0, 1, 0, 0, 1, 1, 1, 1, 1, 1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rule_recipe`
--

CREATE TABLE `rule_recipe` (
  `id_recipe` int(11) NOT NULL,
  `id_product_rule` int(11) NOT NULL,
  `cantidad` double NOT NULL DEFAULT 1,
  `estatus` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Volcado de datos para la tabla `rule_recipe`
--

INSERT INTO `rule_recipe` (`id_recipe`, `id_product_rule`, `cantidad`, `estatus`) VALUES
(1, 1, 9, 1),
(1, 2, 3, 1),
(1, 7, 4, 1),
(2, 3, 1, 1),
(3, 1, 30, 1),
(3, 5, 40, 1),
(3, 6, 20, 1),
(4, 2, 1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rule_recipe_log`
--

CREATE TABLE `rule_recipe_log` (
  `id_recipe` int(11) NOT NULL,
  `id_product_rule` int(11) NOT NULL,
  `cantidad` double NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `idusuario` int(11) NOT NULL,
  `nombre` varchar(50) DEFAULT NULL,
  `correo` varchar(100) DEFAULT NULL,
  `usuario` varchar(15) DEFAULT NULL,
  `clave` varchar(100) DEFAULT NULL,
  `cargo` varchar(45) DEFAULT NULL,
  `rol` int(11) DEFAULT NULL,
  `estatus` int(10) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`idusuario`, `nombre`, `correo`, `usuario`, `clave`, `cargo`, `rol`, `estatus`) VALUES
(1, 'admin', 'admin', 'admin', '21232f297a57a5a743894a0e4a801fc3', 'Superadmin', 1, 1),
(2, 'Joel Arguello', 'pjoel2019@gmail.com', 'joel', 'fa86e3119f4a4fdf8f8f5d628ab1ae42', 'Gerente', 2, 1),
(3, 'Steven Pozo', 'stevenpozo0999@gmail.com', 'steven', 'e55b988dad433dc46acd6b777cf5fbef', 'Almacenero', 3, 1),
(4, 'Carlos Granda', 'carlos0999@gmail.com', 'joel', '64d3acada101acc8bfed140e83cc1aa0', 'Almacenero', 4, 0),
(5, 'steven pozo', 'stevenpozo0999@gmail.com', 'steven', 'e55b988dad433dc46acd6b777cf5fbef', 'Gerente', 5, 0),
(6, 'Carlos Espinoza', 'carlos0999@gmail.com', 'carlos', '64d3acada101acc8bfed140e83cc1aa0', 'Vendedor', 6, 1);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `cliente`
--
ALTER TABLE `cliente`
  ADD PRIMARY KEY (`id_cliente`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Indices de la tabla `client_i`
--
ALTER TABLE `client_i`
  ADD PRIMARY KEY (`id_cliente`);

--
-- Indices de la tabla `client_log_update`
--
ALTER TABLE `client_log_update`
  ADD PRIMARY KEY (`id_change`);

--
-- Indices de la tabla `login_log`
--
ALTER TABLE `login_log`
  ADD PRIMARY KEY (`id_log`);

--
-- Indices de la tabla `ordenes`
--
ALTER TABLE `ordenes`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `ordenes_recetas`
--
ALTER TABLE `ordenes_recetas`
  ADD PRIMARY KEY (`orden_id`,`receta_id`),
  ADD KEY `receta_id_fk_idx` (`receta_id`);

--
-- Indices de la tabla `producto`
--
ALTER TABLE `producto`
  ADD PRIMARY KEY (`codproducto`),
  ADD KEY `proveedor` (`proveedor`),
  ADD KEY `usuario_id` (`usuario_id`),
  ADD KEY `id_measurament` (`id_measurement`) USING BTREE;

--
-- Indices de la tabla `product_i`
--
ALTER TABLE `product_i`
  ADD PRIMARY KEY (`id_producto`);

--
-- Indices de la tabla `product_log_update`
--
ALTER TABLE `product_log_update`
  ADD PRIMARY KEY (`id`),
  ADD KEY `producto_id` (`producto_id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Indices de la tabla `product_measurement`
--
ALTER TABLE `product_measurement`
  ADD PRIMARY KEY (`id_measurement`);

--
-- Indices de la tabla `proveedor`
--
ALTER TABLE `proveedor`
  ADD PRIMARY KEY (`id_supplier`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Indices de la tabla `recipe`
--
ALTER TABLE `recipe`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id_fk_idx` (`user_id`);

--
-- Indices de la tabla `recipe_log`
--
ALTER TABLE `recipe_log`
  ADD KEY `recipe_log_ibfk_1` (`user_id`),
  ADD KEY `recipe_log_ibfk_2` (`id`);

--
-- Indices de la tabla `rol`
--
ALTER TABLE `rol`
  ADD PRIMARY KEY (`idrol`);

--
-- Indices de la tabla `rule_recipe`
--
ALTER TABLE `rule_recipe`
  ADD PRIMARY KEY (`id_recipe`,`id_product_rule`),
  ADD KEY `producto_id_fk_idx` (`id_product_rule`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`idusuario`),
  ADD KEY `rol` (`rol`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `cliente`
--
ALTER TABLE `cliente`
  MODIFY `id_cliente` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `client_log_update`
--
ALTER TABLE `client_log_update`
  MODIFY `id_change` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `login_log`
--
ALTER TABLE `login_log`
  MODIFY `id_log` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `ordenes`
--
ALTER TABLE `ordenes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `producto`
--
ALTER TABLE `producto`
  MODIFY `codproducto` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `product_i`
--
ALTER TABLE `product_i`
  MODIFY `id_producto` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `product_log_update`
--
ALTER TABLE `product_log_update`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `product_measurement`
--
ALTER TABLE `product_measurement`
  MODIFY `id_measurement` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `proveedor`
--
ALTER TABLE `proveedor`
  MODIFY `id_supplier` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `recipe`
--
ALTER TABLE `recipe`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `rol`
--
ALTER TABLE `rol`
  MODIFY `idrol` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `idusuario` int(11) NOT NULL AUTO_INCREMENT;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `cliente`
--
ALTER TABLE `cliente`
  ADD CONSTRAINT `cliente_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuario` (`idusuario`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `client_i`
--
ALTER TABLE `client_i`
  ADD CONSTRAINT `client_i_ibfk_1` FOREIGN KEY (`id_cliente`) REFERENCES `cliente` (`id_cliente`);

--
-- Filtros para la tabla `ordenes_recetas`
--
ALTER TABLE `ordenes_recetas`
  ADD CONSTRAINT `ordenes_recetas_orden_id_fk` FOREIGN KEY (`orden_id`) REFERENCES `ordenes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `ordenes_recetas_receta_id_fk` FOREIGN KEY (`receta_id`) REFERENCES `recipe` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `producto`
--
ALTER TABLE `producto`
  ADD CONSTRAINT `fk_measurement` FOREIGN KEY (`id_measurement`) REFERENCES `product_measurement` (`id_measurement`);

--
-- Filtros para la tabla `product_log_update`
--
ALTER TABLE `product_log_update`
  ADD CONSTRAINT `product_log_update_ibfk_1` FOREIGN KEY (`producto_id`) REFERENCES `producto` (`codproducto`),
  ADD CONSTRAINT `product_log_update_ibfk_2` FOREIGN KEY (`usuario_id`) REFERENCES `usuario` (`idusuario`);

--
-- Filtros para la tabla `recipe`
--
ALTER TABLE `recipe`
  ADD CONSTRAINT `user_id_fk` FOREIGN KEY (`user_id`) REFERENCES `usuario` (`idusuario`) ON DELETE CASCADE;

--
-- Filtros para la tabla `recipe_log`
--
ALTER TABLE `recipe_log`
  ADD CONSTRAINT `recipe_log_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `usuario` (`idusuario`),
  ADD CONSTRAINT `recipe_log_ibfk_2` FOREIGN KEY (`id`) REFERENCES `recipe` (`id`);

--
-- Filtros para la tabla `rule_recipe`
--
ALTER TABLE `rule_recipe`
  ADD CONSTRAINT `producto_id_fk` FOREIGN KEY (`id_product_rule`) REFERENCES `producto` (`codproducto`),
  ADD CONSTRAINT `receta_id_fk` FOREIGN KEY (`id_recipe`) REFERENCES `recipe` (`id`);

--
-- Filtros para la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD CONSTRAINT `usuario_ibfk_1` FOREIGN KEY (`rol`) REFERENCES `rol` (`idrol`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
