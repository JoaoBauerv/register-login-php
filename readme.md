composer require vlucas/phpdotenv
composer require phpmailer/phpmailer

--
-- Banco de dados: `logintemplate`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `tb_documento`
--

CREATE TABLE `tb_documento` (
  `id` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `cpf` varchar(14) DEFAULT NULL,
  `rg` varchar(9) DEFAULT NULL,
  `cnh` varchar(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- --------------------------------------------------------

--
-- Estrutura para tabela `tb_endereco`
--

CREATE TABLE `tb_endereco` (
  `id` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `cep` varchar(9) DEFAULT NULL,
  `logradouro` varchar(100) DEFAULT NULL,
  `numero` int(11) DEFAULT NULL,
  `complemento` varchar(50) DEFAULT NULL,
  `cidade` varchar(50) DEFAULT NULL,
  `bairro` varchar(50) DEFAULT NULL,
  `referencia` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;



-- --------------------------------------------------------


-- Estrutura para tabela `tb_registro_movimento`


CREATE TABLE `tb_registro_movimento` (
  `id` int(11) NOT NULL,
  `id_usuario_admin` int(11) NOT NULL,
  `id_usuario_modificado` int(11) NOT NULL,
  `descricao` varchar(40) NOT NULL,
  `tipo` varchar(20) NOT NULL,
  `data` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;



-- --------------------------------------------------------

--
-- Estrutura para tabela `tb_usuario`
--

CREATE TABLE `tb_usuario` (
  `id_usuario` int(11) NOT NULL,
  `nome` varchar(40) NOT NULL,
  `email` varchar(50) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `foto` varchar(255) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `usuario` varchar(40) NOT NULL,
  `data_nascimento` date DEFAULT NULL,
  `permissao` varchar(30) NOT NULL,
  `telefone` varchar(20) DEFAULT NULL,
  `celular` varchar(20) DEFAULT NULL,
  `precisa_alterar_senha` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;



--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `tb_documento`
--
ALTER TABLE `tb_documento`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `tb_endereco`
--
ALTER TABLE `tb_endereco`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `tb_registro_movimento`
--
ALTER TABLE `tb_registro_movimento`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `tb_usuario`
--
ALTER TABLE `tb_usuario`
  ADD PRIMARY KEY (`id_usuario`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `tb_documento`
--
ALTER TABLE `tb_documento`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

--
-- AUTO_INCREMENT de tabela `tb_endereco`
--
ALTER TABLE `tb_endereco`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

--
-- AUTO_INCREMENT de tabela `tb_registro_movimento`
--
ALTER TABLE `tb_registro_movimento`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

--
-- AUTO_INCREMENT de tabela `tb_usuario`
--
ALTER TABLE `tb_usuario`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
