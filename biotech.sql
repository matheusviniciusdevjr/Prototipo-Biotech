-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 10/11/2025 às 10:19
-- Versão do servidor: 10.4.32-MariaDB
-- Versão do PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `biotech`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `registro_login`
--

CREATE TABLE `registro_login` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `data_login` datetime NOT NULL,
  `ip_usuario` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `registro_login`
--

INSERT INTO `registro_login` (`id`, `usuario_id`, `data_login`, `ip_usuario`) VALUES
(1, 1, '2025-11-09 23:43:18', '::1'),
(2, 2, '2025-11-09 23:46:03', '::1'),
(3, 1, '2025-11-09 23:46:29', '::1'),
(4, 2, '2025-11-09 23:56:01', '::1');

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `usuario` varchar(100) NOT NULL COMMENT 'Nome de usuário para login',
  `nome_completo` varchar(255) NOT NULL,
  `cpf` varchar(14) NOT NULL,
  `email` varchar(150) NOT NULL,
  `telefone` varchar(20) NOT NULL,
  `endereco` varchar(255) DEFAULT NULL,
  `senha` varchar(255) NOT NULL COMMENT 'Senha criptografada',
  `palavra_chave` varchar(100) DEFAULT NULL COMMENT 'Para dica de recuperação de senha',
  `tipo` enum('comum','empresa','admin') NOT NULL DEFAULT 'comum' COMMENT 'Tipo de conta',
  `reset_token` varchar(64) DEFAULT NULL,
  `token_expira` datetime DEFAULT NULL,
  `codigo_2fa` varchar(6) DEFAULT NULL,
  `codigo_2fa_expira` datetime DEFAULT NULL,
  `codigo_admin_2fa` varchar(6) DEFAULT NULL COMMENT 'Código estático para admins'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `usuarios`
--

INSERT INTO `usuarios` (`id`, `usuario`, `nome_completo`, `cpf`, `email`, `telefone`, `endereco`, `senha`, `palavra_chave`, `tipo`, `reset_token`, `token_expira`, `codigo_2fa`, `codigo_2fa_expira`, `codigo_admin_2fa`) VALUES
(1, 'agouro', 'Vinicius Souza', '121.212.121-21', 'matheus@gmail.com', '(21) 67676-7676', 'Rio de Janeiro - RJ', '$2y$10$D/q9taBjVlHOSA9SU0FZPOeSOhsouChNjG2TOAFnqAgPNWoeGxXO6', 'Admin', 'admin', NULL, NULL, NULL, NULL, NULL),
(2, 'Dionny', 'Pablo', '432.256.755-77', 'dionny@gmail.com', '(21) 78787-8787', 'Rio de Janeiro - RJ', '$2y$10$kR50l8R25ZK8HtSQfYmeiuOOWurMkWLnQpZ/CGifKSCaKvBcHpXbK', 'praia namorado', 'comum', NULL, NULL, NULL, NULL, NULL);

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `registro_login`
--
ALTER TABLE `registro_login`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Índices de tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `usuario` (`usuario`),
  ADD UNIQUE KEY `cpf` (`cpf`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `registro_login`
--
ALTER TABLE `registro_login`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `registro_login`
--
ALTER TABLE `registro_login`
  ADD CONSTRAINT `registro_login_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
