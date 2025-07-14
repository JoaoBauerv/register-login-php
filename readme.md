Composer ultilizado para .ENV
composer require vlucas/phpdotenv

Estrutura para tabela `tb_usuario`

CREATE TABLE `tb_usuario` (
  `id_usuario` int(11) NOT NULL,
  `nome` varchar(40) NOT NULL,
  `email` varchar(50) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `foto` varchar(255) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `usuario` varchar(40) NOT NULL,
  `admin` tinyint(1) DEFAULT NULL
);

ALTER TABLE `tb_usuario`
  ADD PRIMARY KEY (`id_usuario`);

ALTER TABLE `tb_usuario`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
COMMIT;

