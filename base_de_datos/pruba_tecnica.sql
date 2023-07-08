-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3306
-- Tiempo de generación: 08-07-2023 a las 14:10:02
-- Versión del servidor: 8.0.30
-- Versión de PHP: 8.2.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `pruba_tecnica`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proyectos`
--

CREATE TABLE `proyectos` (
  `id` bigint UNSIGNED NOT NULL,
  `titulo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fecha_inicio` date NOT NULL,
  `fecha_fin` date NOT NULL,
  `idUsuario` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `proyectos`
--

INSERT INTO `proyectos` (`id`, `titulo`, `descripcion`, `fecha_inicio`, `fecha_fin`, `idUsuario`, `created_at`, `updated_at`) VALUES
(1, 'Mi segundo proyecto', 'A continuación rectificaremos si solo se llenan los campos enviados', '2023-07-06', '2023-07-06', 2, '2023-07-08 13:58:12', '2023-07-08 14:11:18');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tareas`
--

CREATE TABLE `tareas` (
  `id` bigint UNSIGNED NOT NULL,
  `titulo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `estado` enum('pendiente','en progreso','completada') COLLATE utf8mb4_unicode_ci NOT NULL,
  `idProyecto` bigint UNSIGNED NOT NULL,
  `responsable` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `tareas`
--

INSERT INTO `tareas` (`id`, `titulo`, `descripcion`, `estado`, `idProyecto`, `responsable`, `created_at`, `updated_at`) VALUES
(1, 'Mi segunda tarea', 'Esta es para verificar la edicion de tareas', 'en progreso', 1, 4, '2023-07-08 14:02:13', '2023-07-08 19:03:58'),
(2, 'Tercer tarea', 'probando asignación', 'pendiente', 1, 3, '2023-07-08 14:52:01', '2023-07-08 19:07:20');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `rol` enum('usuario','administrador') COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `rol`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(2, 'Santiago Aristizabal', 'aristizabalsantiago482@gmail.com', NULL, 'administrador', '$2y$10$Y9eyNwSY4BGKkuhHMDkNNuBguuUfwa1gJEY2vLgSlhI6kL0jocfFK', NULL, '2023-07-08 13:40:01', '2023-07-08 13:40:01'),
(3, 'Eiden Perea', 'eidenperea505@gmail.com', NULL, 'usuario', '$2y$10$rh0qp2QeKK8y8lztGODjVOvTYNeBTGyO9XtLNV8seJvPUTU.yReGO', NULL, '2023-07-08 13:41:33', '2023-07-08 13:41:33'),
(4, 'Carlos Aristizabal', 'carlosmario201@gmail.com', NULL, 'usuario', '$2y$10$EAWBw47Lg9bGrJDvR9RtJemWYbhsFzvZgIAa3w22fQP0KzKKNSGDW', NULL, '2023-07-08 14:56:49', '2023-07-08 14:56:49');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `proyectos`
--
ALTER TABLE `proyectos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `proyectos_idusuario_foreign` (`idUsuario`);

--
-- Indices de la tabla `tareas`
--
ALTER TABLE `tareas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tareas_idproyecto_foreign` (`idProyecto`),
  ADD KEY `tareas_responsable_foreign` (`responsable`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `proyectos`
--
ALTER TABLE `proyectos`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `tareas`
--
ALTER TABLE `tareas`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `proyectos`
--
ALTER TABLE `proyectos`
  ADD CONSTRAINT `proyectos_idusuario_foreign` FOREIGN KEY (`idUsuario`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `tareas`
--
ALTER TABLE `tareas`
  ADD CONSTRAINT `tareas_idproyecto_foreign` FOREIGN KEY (`idProyecto`) REFERENCES `proyectos` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `tareas_responsable_foreign` FOREIGN KEY (`responsable`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
