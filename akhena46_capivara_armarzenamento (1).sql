-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Tempo de geração: 17/07/2025 às 17:04
-- Versão do servidor: 5.7.23-23
-- Versão do PHP: 8.1.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `akhena46_capivara.armarzenamento`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `files`
--

CREATE TABLE `files` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `parent_id` int(10) UNSIGNED DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `path` varchar(1024) NOT NULL,
  `size` bigint(20) UNSIGNED DEFAULT NULL,
  `mime_type` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `type` enum('file','folder') NOT NULL DEFAULT 'file',
  `server_filename` varchar(255) NOT NULL,
  `server_folder_path` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Despejando dados para a tabela `files`
--

INSERT INTO `files` (`id`, `user_id`, `parent_id`, `name`, `path`, `size`, `mime_type`, `created_at`, `updated_at`, `type`, `server_filename`, `server_folder_path`) VALUES
(1, 1, NULL, 'Dominio-Contrata.pdf', '', 0, 'a40c05e1-82e1-47cb-9244-510c3998a8b3.pdf', '2025-07-14 17:18:15', NULL, 'file', '246523', 'application/pdf'),
(3, 1, NULL, 'Design sem nome (3).png', '', 0, '993e8437-9f3d-4ab9-a30b-2f46ad2a3319.png', '2025-07-14 17:22:15', NULL, 'file', '690238', 'image/png'),
(4, 1, NULL, 'Captura de tela 2025-07-10 104121.png', '', 0, 'bbec136d-26a2-41cb-9684-3b97ba598a13.png', '2025-07-17 14:07:13', NULL, 'file', '29431', 'image/png'),
(7, 1, NULL, 'Captura de tela 2025-07-10 104121.png', '', 0, '21e0c0e3-715a-44ab-bec9-7bb2e002db2d.png', '2025-07-17 10:08:24', NULL, 'file', '29431', 'image/png'),
(12, 1, NULL, 'Captura de tela 2025-07-15 195819.png', '', 0, '05d6acf2-86ca-44af-89de-4fe61c63f334.png', '2025-07-17 14:07:16', NULL, 'file', '602164', 'image/png'),
(26, 1, NULL, 'Captura de tela 2025-07-17 121147.png', '', 0, '8d67cd40-6948-48e3-bbbb-4e31f565af72.png', '2025-07-17 14:06:28', NULL, 'file', '174703', 'image/png'),
(48, 1, NULL, 'Captura de tela 2025-07-17 121221.png', '', 0, '1f23d504-3f5d-46fb-af21-b51de71c159e.png', '2025-07-17 14:07:19', NULL, 'file', '180650', 'image/png'),
(52, 1, NULL, 'Captura de tela 2025-07-12 134020.png', '', 0, '9ad99587-2ceb-493b-8932-7cfd24172770.png', '2025-07-17 14:07:14', NULL, 'file', '72758', 'image/png'),
(78, 1, NULL, 'desidratação e garrafadas.jpg', '', 0, '3112cbc3-7a7e-422b-9756-ae3290aab7c8.jpg', '2025-07-14 18:14:39', NULL, 'file', '517335', 'image/jpeg'),
(496, 1, NULL, 'Captura de tela 2025-07-17 121200.png', '', 0, '9605e794-8ee7-42cb-b8ca-787991bcbb64.png', '2025-07-17 14:07:18', NULL, 'file', '174581', 'image/png'),
(706, 1, NULL, 'Captura de tela 2025-07-10 104121.png', '', 0, 'd737b885-cf28-4995-8154-3b2eb97f50e4.png', '2025-07-17 10:23:07', NULL, 'file', '29431', 'image/png'),
(1979, 1, NULL, 'Captura de tela 2025-07-15 200721.png', '', 0, '0afa2dd3-8be5-4565-aca3-2d65f1bdefce.png', '2025-07-17 14:07:16', NULL, 'file', '68580', 'image/png'),
(2559, 1, NULL, 'Proposta Comercial – Plataforma Web para Conexão entre Compradores e Concessionárias.pdf', '', 0, '2ce469b5-ac8c-460a-b75c-6b8c1208f16a.pdf', '2025-07-17 10:23:31', NULL, 'file', '131122', 'application/pdf'),
(2560, 1, NULL, 'Captura de tela 2025-07-14 234935.png', '', 0, 'f32d4532-563d-45c7-b609-1c7e6f1fa703.png', '2025-07-17 14:07:15', NULL, 'file', '444699', 'image/png'),
(2561, 1, NULL, 'Captura de tela 2025-07-17 121021.png', '', 0, '744c969d-149b-4eb6-a64b-9a7ddb6afebc.png', '2025-07-17 14:07:16', NULL, 'file', '80519', 'image/png'),
(42780, 1, NULL, 'Captura de tela 2025-07-17 121131.png', '', 0, '01dbff26-ae00-4aaf-8b56-d1715d601e84.png', '2025-07-17 14:07:17', NULL, 'file', '172513', 'image/png'),
(42781, 1, NULL, 'Captura de tela 2025-07-17 121147.png', '', 0, '384a38f7-fb21-41d0-984c-01b9e6033b77.png', '2025-07-17 14:07:17', NULL, 'file', '174703', 'image/png'),
(42782, 1, NULL, 'Captura de tela 2025-07-10 104121.png', '', 0, 'd2bf2988-5c82-4cd1-8c10-a128b8118686.png', '2025-07-17 14:23:34', NULL, 'file', '29431', 'image/png'),
(4294967295, 1, NULL, '99', '', NULL, NULL, '2025-07-17 14:23:52', NULL, 'folder', '99', '');

-- --------------------------------------------------------

--
-- Estrutura para tabela `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `username` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password_hash` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('user','admin') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password_hash`, `role`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'admin@example.com', '$2y$10$n9LcszT.q9JrFv5CHY.ZIew2KhNwcKwUbsluDbl0zpne3EevwUU3O', 'admin', '2025-07-09 06:16:49', '2025-07-11 20:24:43'),
(4, '123456', 'a@a.a', '$2y$10$mocH9.w.MC21JZ8q/W5Oc.c.INaiWNQq0Mwqryx4B.GBwa.9.BhVS', 'user', '2025-07-14 18:35:21', '2025-07-14 18:35:21');

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `files`
--
ALTER TABLE `files`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `parent_id` (`parent_id`);

--
-- Índices de tabela `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `files`
--
ALTER TABLE `files`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4294967295;

--
-- AUTO_INCREMENT de tabela `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `files`
--
ALTER TABLE `files`
  ADD CONSTRAINT `files_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `files_ibfk_2` FOREIGN KEY (`parent_id`) REFERENCES `files` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
