-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 12/12/2024 às 19:24
-- Versão do servidor: 10.4.32-MariaDB
-- Versão do PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `tcc`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `comentarios`
--

CREATE TABLE `comentarios` (
  `id` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_produto` int(11) NOT NULL,
  `comentario` text NOT NULL,
  `data_comentario` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `comentarios`
--

INSERT INTO `comentarios` (`id`, `id_usuario`, `id_produto`, `comentario`, `data_comentario`) VALUES
(33, 3, 31, 'Oii, teria interesse. Vou te chamar no whats ?', '2024-11-27 19:04:05');

-- --------------------------------------------------------

--
-- Estrutura para tabela `imagens_produto`
--

CREATE TABLE `imagens_produto` (
  `id_imagem` int(11) NOT NULL,
  `id_produto` int(11) NOT NULL,
  `url_imagem` varchar(255) NOT NULL,
  `data_upload` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `imagens_produto`
--

INSERT INTO `imagens_produto` (`id_imagem`, `id_produto`, `url_imagem`, `data_upload`) VALUES
(81, 30, 'uploads/imagens_produtos/img_67462035f2dbb.jpg', '2024-11-26 19:23:33'),
(82, 30, 'uploads/imagens_produtos/img_67462035f32a1.jpg', '2024-11-26 19:23:33'),
(83, 30, 'uploads/imagens_produtos/img_67462035f35d3.jpg', '2024-11-26 19:23:33'),
(84, 31, 'uploads/imagens_produtos/img_674768cc49fae.jpg', '2024-11-27 18:45:32'),
(85, 31, 'uploads/imagens_produtos/img_674768cc4a4dd.jpg', '2024-11-27 18:45:32'),
(86, 31, 'uploads/imagens_produtos/img_674768cc4a990.jpg', '2024-11-27 18:45:32');

-- --------------------------------------------------------

--
-- Estrutura para tabela `produtos`
--

CREATE TABLE `produtos` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `categoria` varchar(50) NOT NULL,
  `descricao` text DEFAULT NULL,
  `imagens` varchar(255) DEFAULT NULL,
  `data_cadastro` timestamp NOT NULL DEFAULT current_timestamp(),
  `id_usuario` int(11) NOT NULL,
  `visualizacoes` int(11) DEFAULT 0,
  `status` enum('Funcionando','Não Funcionando') NOT NULL DEFAULT 'Funcionando'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `produtos`
--

INSERT INTO `produtos` (`id`, `nome`, `categoria`, `descricao`, `imagens`, `data_cadastro`, `id_usuario`, `visualizacoes`, `status`) VALUES
(30, 'Placa Mae de PS4', 'placa_mae', 'Ta queimada', NULL, '2024-11-26 19:23:33', 1, 7, 'Não Funcionando'),
(31, 'Teste3', 'Memória RAM', 'Lipsum loremLipsum loremLipsum loremLipsum loremLipsum loremLipsum loremLipsum loremLipsum loremLipsum loremLipsum loremLipsum loremLipsum loremLipsum loremLipsum loremLipsum loremLipsum loremLipsum loremLipsum loremLipsum loremLipsum loremLipsum loremLipsum loremLipsum loremLipsum lorem', NULL, '2024-11-27 18:45:32', 1, 4, 'Funcionando');

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuariosbd`
--

CREATE TABLE `usuariosbd` (
  `ID` int(11) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `email` varchar(155) NOT NULL,
  `TipoUsuario` enum('normal','admin') NOT NULL DEFAULT 'normal',
  `telefone` varchar(15) NOT NULL,
  `Estado` varchar(50) NOT NULL,
  `Cidade` varchar(100) NOT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `status` enum('ativo','inativo') DEFAULT 'ativo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `usuariosbd`
--

INSERT INTO `usuariosbd` (`ID`, `nome`, `senha`, `email`, `TipoUsuario`, `telefone`, `Estado`, `Cidade`, `foto`, `status`) VALUES
(1, 'Nicolas', '123', 'nicomenta03@gmail.com', 'admin', '2147483647', 'Pernambuco', 'Belo Jardim', '../uploads/usuarios/6744f7a59fb05.jpg', 'ativo'),
(3, 'nicole menta', '123', 'nimenta04@gmail.com', 'normal', '11111111', 'Rio de Janeiro', 'Cardoso Moreira', NULL, 'ativo'),
(4, 'Joao', '123', '1234@gmail.com', 'normal', '(42) 9811-1221', '22', 'Avelino Lopes', NULL, 'ativo'),
(5, 'Nicolau', '$2y$10$KMK/6Wen4.7j9NeBvR.KX.HNIIzeunK2eozCV9060PukWz3p23YVW', '2@gmail.com', 'normal', '(21) 3213-2432', 'PR', 'Antônio Olinto', '../uploads/usuarios/6744f7a59fb05.jpg', 'ativo'),
(8, 'nico', '$2y$10$dP9kMO7bGns9q3.LznxnXeJW1LbjlPnEUzCtiynU1ByOlGmSgmxxa', 'ok@gmail.com', 'normal', '(11) 1111-1111', 'CE', 'Acarape', '../uploads/usuarios/674f3a75e6150.jpg', 'ativo'),
(9, 'nico', '123', 'ok1@gmail.com', 'normal', '(11) 1111-1111', 'CE', 'Acarape', '../uploads/usuarios/674f3a8f0c7ca.jpg', 'ativo'),
(10, 'tswsw', '123', '33232@gmail.com', 'normal', '(12) 1231-2312', 'AC', 'Assis Brasil', '../uploads/usuarios/674f42f53b1d7.jpg', 'inativo'),
(11, 'testr', '123', '23@gmail.com', 'normal', '(12) 3123-2121', 'AC', 'Assis Brasil', '../uploads/usuarios/674f43413a370.jpg', 'ativo'),
(12, 'ta', '123', 'ta@gmail.com', 'normal', '(21) 1123-2321', 'AC', 'Brasiléia', '../uploads/usuarios/674f437582ed9.jpg', 'ativo'),
(13, 'ta', '123', 'ta2@gmail.com', 'normal', '(21) 1123-2321', 'AC', 'Assis Brasil', '../uploads/usuarios/674f43d665771.jpg', 'ativo'),
(14, 'tas', '123', 'ta3@gmail.com', 'normal', '(21) 1123-2321', 'AL', 'Anadia', '../uploads/usuarios/674f43f0beeea.jpg', 'ativo');

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `comentarios`
--
ALTER TABLE `comentarios`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_usuario` (`id_usuario`),
  ADD KEY `comentarios_ibfk_2` (`id_produto`);

--
-- Índices de tabela `imagens_produto`
--
ALTER TABLE `imagens_produto`
  ADD PRIMARY KEY (`id_imagem`),
  ADD KEY `id_produto` (`id_produto`);

--
-- Índices de tabela `produtos`
--
ALTER TABLE `produtos`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `usuariosbd`
--
ALTER TABLE `usuariosbd`
  ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `comentarios`
--
ALTER TABLE `comentarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT de tabela `imagens_produto`
--
ALTER TABLE `imagens_produto`
  MODIFY `id_imagem` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=87;

--
-- AUTO_INCREMENT de tabela `produtos`
--
ALTER TABLE `produtos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT de tabela `usuariosbd`
--
ALTER TABLE `usuariosbd`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `comentarios`
--
ALTER TABLE `comentarios`
  ADD CONSTRAINT `comentarios_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuariosbd` (`ID`),
  ADD CONSTRAINT `comentarios_ibfk_2` FOREIGN KEY (`id_produto`) REFERENCES `produtos` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `imagens_produto`
--
ALTER TABLE `imagens_produto`
  ADD CONSTRAINT `imagens_produto_ibfk_1` FOREIGN KEY (`id_produto`) REFERENCES `produtos` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
