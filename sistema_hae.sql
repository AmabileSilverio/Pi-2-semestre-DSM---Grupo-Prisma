-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 25/06/2025 às 17:32
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
-- Banco de dados: `sistema_hae`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `agenda_hae`
--

CREATE TABLE `agenda_hae` (
  `id` int(11) NOT NULL,
  `id_inscricao` int(11) NOT NULL,
  `id_professor` int(11) NOT NULL,
  `data_inicio` date DEFAULT NULL,
  `hora_inicio` time DEFAULT NULL,
  `data_fim` date DEFAULT NULL,
  `status` varchar(50) DEFAULT 'Pendente'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `agenda_hae`
--

INSERT INTO `agenda_hae` (`id`, `id_inscricao`, `id_professor`, `data_inicio`, `hora_inicio`, `data_fim`, `status`) VALUES
(1, 10, 1, '2025-06-16', '08:00:00', '2025-07-07', 'Em Andamento'),
(2, 11, 1, '2025-06-10', '08:00:00', '2025-07-12', 'Em Andamento'),
(3, 12, 1, '2025-06-11', '08:00:00', '2025-07-12', 'Em Andamento'),
(4, 13, 1, '2025-06-10', '08:00:00', '2025-08-09', 'Em Andamento'),
(5, 14, 2, '2025-06-10', '08:00:00', '2025-07-12', 'Em Andamento'),
(6, 15, 2, '2025-06-11', '08:00:00', '2025-10-09', 'Em Andamento'),
(7, 16, 2, '2025-07-02', '08:00:00', '2025-08-02', 'Em Andamento'),
(8, 18, 1, '2025-06-19', '08:00:00', '2025-07-12', 'Em Andamento'),
(9, 21, 2, '2025-06-12', '08:00:00', '2025-07-05', 'Em Andamento'),
(10, 28, 2, '2025-06-12', '08:00:00', '2025-07-12', 'Em Andamento'),
(13, 46, 2, '2025-06-30', '08:00:00', '2025-12-06', 'Em Andamento'),
(14, 48, 1, '2025-06-23', '08:00:00', '2025-12-01', 'Em Andamento'),
(15, 50, 3, '2025-07-01', '08:00:00', '2025-12-01', 'Em Andamento'),
(16, 51, 3, '2025-06-23', '08:00:00', '2025-12-01', 'Em Andamento'),
(17, 52, 3, '2025-06-24', '08:00:00', '2025-12-24', 'Pendente'),
(18, 53, 3, '2025-07-01', '08:00:00', '2025-12-01', 'Em Andamento'),
(19, 54, 3, '2025-07-01', '08:00:00', '2025-12-01', 'Em Andamento'),
(20, 55, 1, '2025-07-01', '08:00:00', '2025-12-01', 'Em Andamento');

-- --------------------------------------------------------

--
-- Estrutura para tabela `coordenador`
--

CREATE TABLE `coordenador` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `senha` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `coordenador`
--

INSERT INTO `coordenador` (`id`, `nome`, `email`, `senha`) VALUES
(1, 'Márcia Regina', 'marcia@fatec.com', '123456');

-- --------------------------------------------------------

--
-- Estrutura para tabela `editais_hae`
--

CREATE TABLE `editais_hae` (
  `id` int(11) NOT NULL,
  `titulo` varchar(255) NOT NULL,
  `unidade` enum('Americana','Araras','Campinas','Itapira','Mogi Mirim','Santo André') NOT NULL,
  `data_inicio` date NOT NULL,
  `data_termino` date NOT NULL,
  `semestre` enum('1º semestre','2º semestre') NOT NULL,
  `data_criacao` timestamp NOT NULL DEFAULT current_timestamp(),
  `id_coordenador` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `editais_hae`
--

INSERT INTO `editais_hae` (`id`, `titulo`, `unidade`, `data_inicio`, `data_termino`, `semestre`, `data_criacao`, `id_coordenador`) VALUES
(3, 'Iniciação cientifica 2', 'Campinas', '2025-06-30', '2025-12-06', '2º semestre', '2025-06-23 20:18:05', 1),
(4, 'Divulgação do vestibular 1', 'Campinas', '2025-06-23', '2025-12-01', '2º semestre', '2025-06-23 20:25:17', 1),
(9, 'Inscrições HAE - 2025', 'Itapira', '2025-07-01', '2025-12-01', '2º semestre', '2025-06-24 17:06:55', NULL);

-- --------------------------------------------------------

--
-- Estrutura para tabela `inscricoes_hae`
--

CREATE TABLE `inscricoes_hae` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `tipo_contrato` varchar(50) DEFAULT NULL,
  `rg` varchar(20) DEFAULT NULL,
  `contato` varchar(20) DEFAULT NULL,
  `matricula` varchar(20) DEFAULT NULL,
  `aula_outra_fatec` varchar(10) DEFAULT NULL,
  `horas_disponiveis` time DEFAULT NULL,
  `tipo_hae` enum('estagio_supervisionado','trabalho_graduacao','iniciacao_cientifica','divulgacao_cursos','administracao_academica','enade') NOT NULL,
  `horas_solicitadas` time DEFAULT NULL,
  `projeto_unidade` enum('Americana','Araras','Campinas','Itapira','Mogi Mirim','Santo André') NOT NULL,
  `titulo_editado` varchar(255) DEFAULT NULL,
  `titulo_projeto` varchar(255) DEFAULT NULL,
  `metodologia` text DEFAULT NULL,
  `descricao` text DEFAULT NULL,
  `dias` text DEFAULT NULL,
  `horarios` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`horarios`)),
  `proposta_nome` varchar(255) DEFAULT NULL,
  `proposta_path` varchar(255) DEFAULT NULL,
  `aceite_termos` tinyint(1) DEFAULT NULL,
  `data_envio` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` varchar(20) DEFAULT 'Pendente' CHECK (`status` in ('Pendente','Aprovado','Rejeitado','Em Análise')),
  `horas_aprovadas` time DEFAULT NULL,
  `data_inicio` date DEFAULT NULL,
  `data_fim` date DEFAULT NULL,
  `motivo_rejeicao` text DEFAULT NULL,
  `id_professor` int(11) DEFAULT NULL,
  `curso` enum('DESENVOLVIMENTO DE SOFTWARE MULTIPLATAFORMA','GESTAO EMPRESARIAL','GESTÃO DA PRODUÇÃO INDUSTRIAL') NOT NULL,
  `anexo` varchar(255) DEFAULT NULL,
  `id_edital` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `inscricoes_hae`
--

INSERT INTO `inscricoes_hae` (`id`, `nome`, `email`, `tipo_contrato`, `rg`, `contato`, `matricula`, `aula_outra_fatec`, `horas_disponiveis`, `tipo_hae`, `horas_solicitadas`, `projeto_unidade`, `titulo_editado`, `titulo_projeto`, `metodologia`, `descricao`, `dias`, `horarios`, `proposta_nome`, `proposta_path`, `aceite_termos`, `data_envio`, `status`, `horas_aprovadas`, `data_inicio`, `data_fim`, `motivo_rejeicao`, `id_professor`, `curso`, `anexo`, `id_edital`) VALUES
(2, 'Amábile Silvério', 'amabile.silverio34@gmail.com', 'contrato_temporario', '591285460', '971120245', '123456', 'sim', NULL, 'estagio_supervisionado', NULL, 'Itapira', 'Estagio - DSM', 'Estagio alunos - DSM', 'Reuniões semanais', 'Mentoria de estagio turma DSM', 'segunda', '{\"segunda\":[\"09:00\",\"10:00\"],\"terca\":[\"\",\"\"],\"quarta\":[\"\",\"\"],\"quinta\":[\"\",\"\"],\"sexta\":[\"\",\"\"]}', '', 'uploads/1749419467_', 1, '2025-06-08 21:51:07', 'Aprovado', '00:00:02', '2025-06-16', '2025-06-20', NULL, NULL, 'DESENVOLVIMENTO DE SOFTWARE MULTIPLATAFORMA', NULL, NULL),
(3, 'Amábile Silvério', 'amabile.silverio34@gmail.com', 'contrato_temporario', '591285460', '971120245', '123456', 'sim', NULL, 'estagio_supervisionado', NULL, 'Itapira', 'Estagio - DSM', 'Estagio alunos - DSM', 'Reuniões semanais', 'Mentoria de estagio turma DSM', 'segunda', '{\"segunda\":[\"09:00\",\"10:00\"],\"terca\":[\"\",\"\"],\"quarta\":[\"\",\"\"],\"quinta\":[\"\",\"\"],\"sexta\":[\"\",\"\"]}', 'Documentação de Requisitos - Amabile, Gustavo e Julia.pdf', 'uploads/1749419517_Documentação de Requisitos - Amabile, Gustavo e Julia.pdf', 1, '2025-06-08 21:51:57', 'Rejeitado', NULL, NULL, NULL, 'Não atendeu aos criterios', NULL, 'DESENVOLVIMENTO DE SOFTWARE MULTIPLATAFORMA', NULL, NULL),
(4, 'Amábile Silvério', 'amabile.silverio34@gmail.com', 'contrato_efetivo', '591285460', '19971120245', '123456', 'sim', '08:00:00', 'estagio_supervisionado', '08:00:00', '', 'fdfdf', 'dfdf', 'fdfd', 'dfdfdf', 'segunda,terca,quarta,quinta', '{\"segunda\":[\"10:00\",\"12:00\"],\"terca\":[\"12:00\",\"14:00\"],\"quarta\":[\"14:00\",\"16:00\"],\"quinta\":[\"16:00\",\"18:00\"],\"sexta\":[\"\",\"\"]}', 'Documentação de Requisitos - Amabile, Gustavo e Julia.pdf', 'uploads/1749419858_Documentação de Requisitos - Amabile, Gustavo e Julia.pdf', 1, '2025-06-08 21:57:38', 'Aprovado', '00:00:08', '2025-06-16', '2025-07-05', NULL, NULL, 'DESENVOLVIMENTO DE SOFTWARE MULTIPLATAFORMA', NULL, NULL),
(6, 'Amábile Silvério', 'amabile.silverio34@gmail.com', 'contrato_efetivo', '591285460', '19971120245', '123456', 'nao', '05:00:00', 'divulgacao_cursos', '02:00:00', 'Itapira', 'Divulgação do vestibular', 'Divulgação - turma dsm', 'Publicações, cartazes desenvolvidos no canva', 'Visa fazer a publicidade atraves das redes sociais e envios de lista de transmissões por e-mail', 'segunda,quarta', '{\"segunda\":[\"01:00\",\"02:00\"],\"terca\":[\"\",\"\"],\"quarta\":[\"07:00\",\"08:00\"],\"quinta\":[\"\",\"\"],\"sexta\":[\"\",\"\"]}', 'Descrição dos Casos de Us1.pdf', 'uploads/1749469937_Descrição dos Casos de Us1.pdf', 1, '2025-06-09 11:52:17', 'Aprovado', '00:00:02', '2025-06-16', '2025-06-28', NULL, NULL, 'DESENVOLVIMENTO DE SOFTWARE MULTIPLATAFORMA', NULL, NULL),
(7, 'Amábile Silvério', 'amabile.silverio34@gmail.com', 'contrato_efetivo', '41.111.709.9', '(19) 971120245', '123456', 'sim', '05:00:00', 'administracao_academica', '03:00:00', 'Itapira', 'Administração academica do vestibular', 'Administração academica do vestibular - Curso GE', 'Sprints semanais, divisão de tarefas com a equipe', 'Organizar e enviar documentações', 'segunda,quarta,quinta', '{\"segunda\":[\"08:00\",\"09:00\"],\"terca\":[\"\",\"\"],\"quarta\":[\"10:00\",\"11:00\"],\"quinta\":[\"12:00\",\"13:00\"],\"sexta\":[\"\",\"\"]}', 'Descrição de caso de uso IA.pdf', 'uploads/1749470313_Descrição de caso de uso IA.pdf', 1, '2025-06-09 11:58:33', 'Aprovado', '00:00:03', '2025-06-16', '2025-06-30', NULL, NULL, 'DESENVOLVIMENTO DE SOFTWARE MULTIPLATAFORMA', NULL, NULL),
(8, 'Amábile Silvério', 'amabile.silverio34@gmail.com', 'contrato_temporario', '41.111.709.9', '(19) 971120245', '123456', 'sim', '08:00:00', 'trabalho_graduacao', '08:00:00', 'Itapira', 'Graduação', 'Graduação turma GTI', 'Reuniões semanais', 'Trabalho de graduação da turma de GTI', 'segunda,quarta,quinta,sexta', '{\"segunda\":[\"13:00\",\"15:00\"],\"terca\":[\"\",\"\"],\"quarta\":[\"13:00\",\"15:00\"],\"quinta\":[\"15:00\",\"17:00\"],\"sexta\":[\"12:00\",\"14:00\"]}', 'Descrição dos Casos de Us1.pdf', 'uploads/1749470434_Descrição dos Casos de Us1.pdf', 1, '2025-06-09 12:00:34', 'Rejeitado', NULL, NULL, NULL, 'Não está conforme ', NULL, 'DESENVOLVIMENTO DE SOFTWARE MULTIPLATAFORMA', NULL, NULL),
(9, 'Ana Célia', 'anacelia@fatec.com', 'contrato_temporario', '591285460', '19971120245', '123456789', 'sim', '08:00:00', 'trabalho_graduacao', '08:00:00', 'Itapira', 'Trabalho de Graduação', 'Graduação turma gti', 'analise das metodologias', 'Ajudar na graduação da turma de GTI', 'segunda,terca,quarta,quinta', '{\"segunda\":[\"13:00\",\"15:00\"],\"terca\":[\"15:00\",\"17:00\"],\"quarta\":[\"17:00\",\"19:00\"],\"quinta\":[\"19:00\",\"21:00\"],\"sexta\":[\"\",\"\"]}', 'Descrição dos Casos de Us1.pdf', 'uploads/1749476928_Descrição dos Casos de Us1.pdf', 1, '2025-06-09 13:48:48', 'Aprovado', '00:00:08', '2025-06-23', '2025-08-02', NULL, 1, 'DESENVOLVIMENTO DE SOFTWARE MULTIPLATAFORMA', NULL, NULL),
(10, 'Ana Célia', 'anacelia@fatec.com', 'contrato_temporario', '591285460', '19971120245', '123456789', 'sim', '08:00:00', 'estagio_supervisionado', '08:00:00', 'Itapira', 'Estagio Supervisionado', 'Estagio supervisionado turma DSM', 'Reuniões semanais', 'Supervisionar os alunos de DSM no estagio', 'segunda,terca,quarta,quinta', '{\"segunda\":[\"13:00\",\"15:00\"],\"terca\":[\"15:00\",\"17:00\"],\"quarta\":[\"17:00\",\"19:00\"],\"quinta\":[\"19:00\",\"21:00\"],\"sexta\":[\"\",\"\"]}', 'Descrição de caso de uso IA.pdf', 'uploads/1749477165_Descrição de caso de uso IA.pdf', 1, '2025-06-09 13:52:45', 'Aprovado', '00:00:08', '2025-06-16', '2025-07-07', NULL, 1, 'DESENVOLVIMENTO DE SOFTWARE MULTIPLATAFORMA', NULL, NULL),
(11, 'Ana Célia', 'anacelia@fatec.com', 'contrato_temporario', '591285460', '19971120245', '123456', 'sim', '08:00:00', 'estagio_supervisionado', '01:00:00', 'Itapira', 'Estagio Supervisionado', 'Estagio Supervisionado', 'Estagio Supervisionado', 'Estagio Supervisionado', 'segunda', '{\"segunda\":[\"12:00\",\"13:00\"],\"terca\":[\"\",\"\"],\"quarta\":[\"\",\"\"],\"quinta\":[\"\",\"\"],\"sexta\":[\"\",\"\"]}', 'Descrição de caso de uso IA.pdf', 'uploads/1749491295_Descrição de caso de uso IA.pdf', 1, '2025-06-09 17:48:15', 'Aprovado', '00:00:01', '2025-06-10', '2025-07-12', NULL, 1, 'DESENVOLVIMENTO DE SOFTWARE MULTIPLATAFORMA', NULL, NULL),
(12, 'Ana Célia', 'anacelia@fatec.com', 'contrato_efetivo', '41.111.709.9', '(19) 971120245', '123456', 'sim', '08:00:00', 'estagio_supervisionado', '01:00:00', 'Itapira', 'Estagio Supervisionado', 'Estagio Supervisionado - ANA', 'Estagio Supervisionado - ANA', 'Estagio Supervisionado - ANA', 'segunda', '{\"segunda\":[\"13:00\",\"14:00\"],\"terca\":[\"\",\"\"],\"quarta\":[\"\",\"\"],\"quinta\":[\"\",\"\"],\"sexta\":[\"\",\"\"]}', 'Descrição de caso de uso IA.pdf', 'uploads/1749497771_Descrição de caso de uso IA.pdf', 1, '2025-06-09 19:36:11', 'Aprovado', '00:00:01', '2025-06-11', '2025-07-12', NULL, 1, 'DESENVOLVIMENTO DE SOFTWARE MULTIPLATAFORMA', NULL, NULL),
(13, 'Ana Célia', 'anacelia@fatec.com', 'contrato_efetivo', '591285460', '19971120245', '123456789', 'sim', '08:00:00', 'estagio_supervisionado', '02:00:00', 'Itapira', 'Estagio Supervisionado', 'Estagio Supervisionado - DSM', 'Estagio Supervisionado - DSM', 'Estagio Supervisionado - DSM', 'segunda', '{\"segunda\":[\"13:00\",\"15:00\"],\"terca\":[\"\",\"\"],\"quarta\":[\"\",\"\"],\"quinta\":[\"\",\"\"],\"sexta\":[\"\",\"\"]}', 'pc_306855_20250606074257.pdf', 'uploads/1749564119_pc_306855_20250606074257.pdf', 1, '2025-06-10 14:01:59', 'Aprovado', '00:00:02', '2025-06-10', '2025-08-09', NULL, 1, 'DESENVOLVIMENTO DE SOFTWARE MULTIPLATAFORMA', NULL, NULL),
(14, 'Júnior Gonçalves', 'junior@fatec.com', 'contrato_efetivo', '471254780', '19987152445', '789456123', 'sim', '08:00:00', 'iniciacao_cientifica', '08:00:00', 'Itapira', 'Iniciação cientifica - GE', 'Iniciação cientifica do Junior', 'Iniciação cientifica do Junior', 'Iniciação cientifica do Junior', 'segunda,terca,quarta,quinta,sexta', '{\"segunda\":[\"13:00\",\"15:00\"],\"terca\":[\"15:00\",\"17:00\"],\"quarta\":[\"12:00\",\"14:00\"],\"quinta\":[\"14:00\",\"16:00\"],\"sexta\":[\"16:00\",\"18:00\"]}', 'pc_306855_20250606074257.pdf', 'uploads/1749564731_pc_306855_20250606074257.pdf', 1, '2025-06-10 14:12:11', 'Aprovado', '00:00:08', '2025-06-10', '2025-07-12', NULL, 2, 'GESTAO EMPRESARIAL', NULL, NULL),
(15, 'Júnior Gonçalves', 'junior@fatec.com', 'contrato_efetivo', '471254780', '19987152445', '789456123', 'nao', '08:00:00', 'trabalho_graduacao', '13:00:00', 'Itapira', 'Iniciação cientifica - GE', 'Iniciação cientifica do Junior', 'Iniciação cientifica do Junior', 'Iniciação cientifica do Junior', 'segunda,terca,quarta,quinta,sexta', '{\"segunda\":[\"13:00\",\"15:00\"],\"terca\":[\"15:00\",\"17:00\"],\"quarta\":[\"12:00\",\"14:00\"],\"quinta\":[\"14:00\",\"16:00\"],\"sexta\":[\"16:00\",\"18:00\"]}', 'pc_307021_20250609120603.pdf', 'uploads/1749565650_pc_307021_20250609120603.pdf', 1, '2025-06-10 14:27:30', 'Aprovado', '00:00:09', '2025-06-11', '2025-10-09', NULL, 2, 'GESTAO EMPRESARIAL', NULL, NULL),
(16, 'Júnior Gonçalves', 'junior@fatec.com', 'contrato_efetivo', '41.111.709.9', '19971120245', '123456', 'sim', '08:00:00', 'trabalho_graduacao', '08:00:00', 'Itapira', 'sfsdfsd', 'sdsds', 'dsdsd', 'dsdsd', 'segunda', '{\"segunda\":[\"12:00\",\"20:00\"],\"terca\":[\"\",\"\"],\"quarta\":[\"\",\"\"],\"quinta\":[\"\",\"\"],\"sexta\":[\"\",\"\"]}', 'pc_306855_20250606074257.pdf', 'uploads/1749566708_pc_306855_20250606074257.pdf', 1, '2025-06-10 14:45:08', 'Aprovado', '00:00:08', '2025-07-02', '2025-08-02', NULL, 2, 'GESTÃO DA PRODUÇÃO INDUSTRIAL', NULL, NULL),
(17, 'Júnior Gonçalves', 'junior@fatec.com', 'contrato_efetivo', '41.111.709.9', '19987152445', '123456', 'sim', '08:00:00', 'estagio_supervisionado', '08:00:00', 'Campinas', 'cxcxc', 'xcxcxc', 'cxcxc', 'cxcxc', 'segunda', '{\"segunda\":[\"10:00\",\"18:00\"],\"terca\":[\"\",\"\"],\"quarta\":[\"\",\"\"],\"quinta\":[\"\",\"\"],\"sexta\":[\"\",\"\"]}', 'pc_306855_20250606074257.pdf', 'uploads/1749568772_pc_306855_20250606074257.pdf', 1, '2025-06-10 15:19:32', 'Rejeitado', NULL, NULL, NULL, 'Não está conforme esperado', 2, 'GESTAO EMPRESARIAL', NULL, NULL),
(18, 'Ana Célia', 'anacelia@fatec.com', 'contrato_efetivo', '591285460', '19971120245', '123456789', 'sim', '08:00:00', 'estagio_supervisionado', '08:00:00', 'Americana', 'dfdf', 'fdfd', 'fdfd', 'fdfd', 'segunda', '{\"segunda\":[\"12:00\",\"20:00\"],\"terca\":[\"\",\"\"],\"quarta\":[\"\",\"\"],\"quinta\":[\"\",\"\"],\"sexta\":[\"\",\"\"]}', 'Certificado w - 200 x 22.pdf', 'uploads/1749586399_Certificado w - 200 x 22.pdf', 1, '2025-06-10 20:13:19', 'Aprovado', '00:00:08', '2025-06-19', '2025-07-12', NULL, 1, 'DESENVOLVIMENTO DE SOFTWARE MULTIPLATAFORMA', NULL, NULL),
(19, 'Ana Célia', 'anacelia@fatec.com', 'contrato_efetivo', '591285460', '19971120245', '123456789', 'sim', '08:00:00', 'estagio_supervisionado', '08:00:00', 'Itapira', 'Estagio Supervisionado', 'Estagio Supervisionado', 'Estagio Supervisionado', 'Estagio Supervisionado', 'segunda', '{\"segunda\":[\"12:00\",\"20:00\"],\"terca\":[\"\",\"\"],\"quarta\":[\"\",\"\"],\"quinta\":[\"\",\"\"],\"sexta\":[\"\",\"\"]}', 'Certificado w - 200 x 22.pdf', 'uploads/1749587081_Certificado w - 200 x 22.pdf', 1, '2025-06-10 20:24:41', 'Rejeitado', NULL, NULL, NULL, 'Não esta conforme\n', 1, 'DESENVOLVIMENTO DE SOFTWARE MULTIPLATAFORMA', NULL, NULL),
(20, 'Júnior Gonçalves ', 'junior@fatec.com', 'contrato_efetivo', '471254780', '19987152445', '789456123', 'sim', '08:00:00', 'estagio_supervisionado', '01:00:00', 'Itapira', 'Estagio Supervisionado', 'Estagio Supervisionado', 'Estagio Supervisionado', 'Estagio Supervisionado', 'segunda', '{\"segunda\":[\"12:00\",\"13:00\"],\"terca\":[\"\",\"\"],\"quarta\":[\"\",\"\"],\"quinta\":[\"\",\"\"],\"sexta\":[\"\",\"\"]}', '03667.pdf', 'uploads/1749736383_03667.pdf', 1, '2025-06-12 13:53:03', 'Rejeitado', NULL, NULL, NULL, 'Não esta conforme\n', 2, 'GESTÃO DA PRODUÇÃO INDUSTRIAL', NULL, NULL),
(21, 'Júnior Gonçalves ', 'junior@fatec.com', 'contrato_efetivo', '471254780', '19987152445', '789456123', 'sim', '08:00:00', 'estagio_supervisionado', '01:00:00', 'Itapira', 'Estagio Supervisionado', 'Estagio Supervisionado', 'Estagio Supervisionado', 'Estagio Supervisionado', 'segunda', '{\"segunda\":[\"12:00\",\"13:00\"],\"terca\":[\"\",\"\"],\"quarta\":[\"\",\"\"],\"quinta\":[\"\",\"\"],\"sexta\":[\"\",\"\"]}', '03667.pdf', 'uploads/1749736383_03667.pdf', 1, '2025-06-12 13:53:03', 'Aprovado', '00:00:01', '2025-06-12', '2025-07-05', NULL, 2, 'GESTÃO DA PRODUÇÃO INDUSTRIAL', NULL, NULL),
(22, 'Júnior Gonçalves ', 'junior@fatec.com', 'contrato_efetivo', '471254780', '19987152445', '789456123', 'sim', '01:00:00', 'trabalho_graduacao', '01:00:00', 'Itapira', 'Estagio Supervisionado', 'Estagio Supervisionado', 'Estagio Supervisionado', 'Estagio Supervisionado', 'terca', '{\"segunda\":[\"\",\"\"],\"terca\":[\"12:00\",\"13:00\"],\"quarta\":[\"\",\"\"],\"quinta\":[\"\",\"\"],\"sexta\":[\"\",\"\"]}', '03667.pdf', 'uploads/1749736455_03667.pdf', 1, '2025-06-12 13:54:15', 'Rejeitado', NULL, NULL, NULL, 'não esta confomre', 2, 'GESTAO EMPRESARIAL', NULL, NULL),
(23, 'Júnior Gonçalves ', 'junior@fatec.com', 'contrato_efetivo', '471254780', '19987152445', '789456123', 'nao', '08:00:00', 'trabalho_graduacao', '01:00:00', 'Itapira', 'dsdsd', 'dsds', 'dsds', 'dsds', 'segunda', '{\"segunda\":[\"12:00\",\"13:00\"],\"terca\":[\"\",\"\"],\"quarta\":[\"\",\"\"],\"quinta\":[\"\",\"\"],\"sexta\":[\"\",\"\"]}', '03667.pdf', 'uploads/1749737186_03667.pdf', 1, '2025-06-12 14:06:26', 'Rejeitado', NULL, NULL, NULL, 'não esta confomre\n', 2, 'DESENVOLVIMENTO DE SOFTWARE MULTIPLATAFORMA', NULL, NULL),
(24, 'Júnior Gonçalves ', 'junior@fatec.com', 'contrato_efetivo', '471254780', '19987152445', '789456123', 'nao', '08:00:00', 'trabalho_graduacao', '01:00:00', 'Itapira', 'dsdsd', 'dsds', 'dsds', 'dsds', 'segunda', '{\"segunda\":[\"12:00\",\"13:00\"],\"terca\":[\"\",\"\"],\"quarta\":[\"\",\"\"],\"quinta\":[\"\",\"\"],\"sexta\":[\"\",\"\"]}', '03667.pdf', 'uploads/1749737247_03667.pdf', 1, '2025-06-12 14:07:27', 'Rejeitado', NULL, NULL, NULL, 'fgf', 2, 'DESENVOLVIMENTO DE SOFTWARE MULTIPLATAFORMA', NULL, NULL),
(25, 'Ana Célia', 'anacelia@fatec.com', 'contrato_efetivo', '591285460', '19971120245', '123456789', 'sim', '08:00:00', 'estagio_supervisionado', '01:00:00', 'Itapira', 'Estagio Supervisionado', 'Estagio Supervisionado', 'Estagio Supervisionado', 'Estagio Supervisionado', 'segunda', '{\"segunda\":[\"12:00\",\"13:00\"],\"terca\":[\"\",\"\"],\"quarta\":[\"\",\"\"],\"quinta\":[\"\",\"\"],\"sexta\":[\"\",\"\"]}', '03667.pdf', 'uploads/1749737401_03667.pdf', 1, '2025-06-12 14:10:01', 'Rejeitado', NULL, NULL, NULL, 'não conforme', 1, 'DESENVOLVIMENTO DE SOFTWARE MULTIPLATAFORMA', NULL, NULL),
(26, 'Ana Célia', 'anacelia@fatec.com', 'contrato_efetivo', '591285460', '19971120245', '123456789', 'nao', '01:00:00', 'estagio_supervisionado', '01:00:00', 'Itapira', 'Estagio Supervisionado', 'Estagio Supervisionado', 'Estagio Supervisionado', 'Estagio Supervisionado', 'segunda', '{\"segunda\":[\"13:00\",\"14:00\"],\"terca\":[\"\",\"\"],\"quarta\":[\"\",\"\"],\"quinta\":[\"\",\"\"],\"sexta\":[\"\",\"\"]}', '03667.pdf', 'uploads/1749737881_03667.pdf', 1, '2025-06-12 14:18:01', 'Rejeitado', NULL, NULL, NULL, 'nao conforme', 1, 'DESENVOLVIMENTO DE SOFTWARE MULTIPLATAFORMA', NULL, NULL),
(27, 'Ana Célia', 'anacelia@fatec.com', 'contrato_efetivo', '591285460', '19971120245', '123456789', 'nao', '01:00:00', 'estagio_supervisionado', '01:00:00', 'Itapira', 'Estagio Supervisionado', 'Estagio Supervisionado', 'Estagio Supervisionado', 'Estagio Supervisionado', 'segunda', '{\"segunda\":[\"12:00\",\"13:00\"],\"terca\":[\"\",\"\"],\"quarta\":[\"\",\"\"],\"quinta\":[\"\",\"\"],\"sexta\":[\"\",\"\"]}', '03667.pdf', 'uploads/1749738104_03667.pdf', 1, '2025-06-12 14:21:44', 'Rejeitado', NULL, NULL, NULL, 'não conforme', 1, 'GESTAO EMPRESARIAL', NULL, NULL),
(28, 'Júnior Gonçalves ', 'junior@fatec.com', 'contrato_efetivo', '471254780', '19987152445', '789456123', 'sim', '08:00:00', 'estagio_supervisionado', '01:00:00', 'Itapira', 'Estagio Supervisionado', 'Estagio Supervisionado', 'Estagio Supervisionado', 'Estagio Supervisionado', 'segunda', '{\"segunda\":[\"12:00\",\"13:00\"],\"terca\":[\"\",\"\"],\"quarta\":[\"\",\"\"],\"quinta\":[\"\",\"\"],\"sexta\":[\"\",\"\"]}', '03569.pdf', 'uploads/1749738629_03569.pdf', 1, '2025-06-12 14:30:29', 'Aprovado', '00:00:01', '2025-06-12', '2025-07-12', NULL, 2, 'GESTAO EMPRESARIAL', NULL, NULL),
(29, 'Júnior Gonçalves ', 'junior@fatec.com', 'contrato_efetivo', '471254780', '19987152445', '789456123', 'nao', '01:00:00', 'estagio_supervisionado', '01:00:00', 'Americana', 'Estagio Supervisionado', 'Estagio Supervisionado', 'Estagio Supervisionado', 'Estagio Supervisionado', 'segunda', '{\"segunda\":[\"13:00\",\"14:00\"],\"terca\":[\"\",\"\"],\"quarta\":[\"\",\"\"],\"quinta\":[\"\",\"\"],\"sexta\":[\"\",\"\"]}', '03667.pdf', 'uploads/1749738848_03667.pdf', 1, '2025-06-12 14:34:08', 'Pendente', NULL, NULL, NULL, NULL, 2, 'DESENVOLVIMENTO DE SOFTWARE MULTIPLATAFORMA', NULL, NULL),
(30, 'Ana Célia', 'anacelia@fatec.com', 'contrato_efetivo', '591285460', '19971120245', '123456789', 'sim', '01:00:00', 'estagio_supervisionado', '01:00:00', 'Americana', 'Estagio Supervisionado', 'Estagio Supervisionado', 'Estagio Supervisionado', 'Estagio Supervisionado', 'segunda', '{\"segunda\":[\"13:00\",\"14:00\"],\"terca\":[\"\",\"\"],\"quarta\":[\"\",\"\"],\"quinta\":[\"\",\"\"],\"sexta\":[\"\",\"\"]}', '03667.pdf', 'uploads/1749738992_03667.pdf', 1, '2025-06-12 14:36:32', 'Pendente', NULL, NULL, NULL, NULL, 1, 'DESENVOLVIMENTO DE SOFTWARE MULTIPLATAFORMA', NULL, NULL),
(31, 'Júnior Gonçalves ', 'junior@fatec.com', 'contrato_efetivo', '471254780', '19987152445', '789456123', 'nao', '01:00:00', 'estagio_supervisionado', '01:00:00', 'Americana', 'Estagio Supervisionado', 'Estagio Supervisionado', 'Estagio Supervisionado', 'Estagio Supervisionado', 'segunda', '{\"segunda\":[\"13:00\",\"14:00\"],\"terca\":[\"\",\"\"],\"quarta\":[\"\",\"\"],\"quinta\":[\"\",\"\"],\"sexta\":[\"\",\"\"]}', '03667.pdf', 'uploads/1749739196_03667.pdf', 1, '2025-06-12 14:39:56', 'Pendente', NULL, NULL, NULL, NULL, 2, 'DESENVOLVIMENTO DE SOFTWARE MULTIPLATAFORMA', NULL, NULL),
(32, 'Ana Célia', 'anacelia@fatec.com', 'contrato_efetivo', '591285460', '19971120245', '123456789', 'nao', '08:00:00', 'estagio_supervisionado', '02:00:00', 'Itapira', 'Estagio Supervisionado', 'Estagio Supervisionado', 'Estagio Supervisionado', 'Estagio Supervisionado', 'segunda', '{\"segunda\":[\"12:00\",\"14:00\"],\"terca\":[\"\",\"\"],\"quarta\":[\"\",\"\"],\"quinta\":[\"\",\"\"],\"sexta\":[\"\",\"\"]}', '03667.pdf', 'uploads/1749763379_03667.pdf', 1, '2025-06-12 21:22:59', 'Pendente', NULL, NULL, NULL, NULL, 1, 'DESENVOLVIMENTO DE SOFTWARE MULTIPLATAFORMA', NULL, NULL),
(33, 'Amábile Silvério', 'amabile.silverio34@gmail.com', 'contrato_efetivo', '591285460', '19971120245', '123456456', 'sim', '08:00:00', 'estagio_supervisionado', '06:00:00', 'Itapira', 'Estagio Supervisionado', 'Estagio Supervisionado', 'Estagio Supervisionado', 'Estagio Supervisionado', 'segunda,terca', '{\"segunda\":[\"12:00\",\"15:00\"],\"terca\":[\"12:00\",\"15:00\"],\"quarta\":[\"\",\"\"],\"quinta\":[\"\",\"\"],\"sexta\":[\"\",\"\"]}', '03667.pdf', 'uploads/1750086008_03667.pdf', 1, '2025-06-16 15:00:08', 'Pendente', NULL, NULL, NULL, NULL, 3, 'DESENVOLVIMENTO DE SOFTWARE MULTIPLATAFORMA', NULL, NULL),
(34, 'Amábile Silvério', 'amabile.silverio34@gmail.com', 'contrato_efetivo', '591285460', '19971120245', '123456456', 'sim', '08:00:00', 'estagio_supervisionado', '06:00:00', 'Itapira', 'Estagio Supervisionado', 'Estagio Supervisionado', 'Estagio Supervisionado', 'Estagio Supervisionado', 'segunda,terca', '{\"segunda\":[\"12:00\",\"15:00\"],\"terca\":[\"12:00\",\"15:00\"],\"quarta\":[\"\",\"\"],\"quinta\":[\"\",\"\"],\"sexta\":[\"\",\"\"]}', '03667.pdf', 'uploads/1750086009_03667.pdf', 1, '2025-06-16 15:00:09', 'Pendente', NULL, NULL, NULL, NULL, 3, 'DESENVOLVIMENTO DE SOFTWARE MULTIPLATAFORMA', NULL, NULL),
(35, 'Amábile Silvério', 'amabile.silverio34@gmail.com', 'contrato_efetivo', '591285460', '19971120245', '123456456', 'sim', '08:00:00', 'estagio_supervisionado', '06:00:00', 'Itapira', 'Estagio Supervisionado', 'Estagio Supervisionado', 'Estagio Supervisionado', 'Estagio Supervisionado', 'segunda,terca', '{\"segunda\":[\"12:00\",\"15:00\"],\"terca\":[\"12:00\",\"15:00\"],\"quarta\":[\"\",\"\"],\"quinta\":[\"\",\"\"],\"sexta\":[\"\",\"\"]}', '03667.pdf', 'uploads/1750086009_03667.pdf', 1, '2025-06-16 15:00:09', 'Pendente', NULL, NULL, NULL, NULL, 3, 'DESENVOLVIMENTO DE SOFTWARE MULTIPLATAFORMA', NULL, NULL),
(36, 'Amábile Silvério', 'amabile.silverio34@gmail.com', 'contrato_efetivo', '591285460', '19971120245', '123456456', 'sim', '08:00:00', 'estagio_supervisionado', '06:00:00', 'Itapira', 'Estagio Supervisionado', 'Estagio Supervisionado', 'Estagio Supervisionado', 'Estagio Supervisionado', 'segunda,terca', '{\"segunda\":[\"12:00\",\"15:00\"],\"terca\":[\"12:00\",\"15:00\"],\"quarta\":[\"\",\"\"],\"quinta\":[\"\",\"\"],\"sexta\":[\"\",\"\"]}', '03667.pdf', 'uploads/1750086010_03667.pdf', 1, '2025-06-16 15:00:10', 'Pendente', NULL, NULL, NULL, NULL, 3, 'DESENVOLVIMENTO DE SOFTWARE MULTIPLATAFORMA', NULL, NULL),
(37, 'Amábile Silvério', 'amabile.silverio34@gmail.com', 'contrato_efetivo', '591285460', '19971120245', '123456456', 'sim', '08:00:00', 'estagio_supervisionado', '06:00:00', 'Itapira', 'Estagio Supervisionado', 'Estagio Supervisionado', 'Estagio Supervisionado', 'Estagio Supervisionado', 'segunda,terca', '{\"segunda\":[\"12:00\",\"15:00\"],\"terca\":[\"12:00\",\"15:00\"],\"quarta\":[\"\",\"\"],\"quinta\":[\"\",\"\"],\"sexta\":[\"\",\"\"]}', '03667.pdf', 'uploads/1750086010_03667.pdf', 1, '2025-06-16 15:00:10', 'Pendente', NULL, NULL, NULL, NULL, 3, 'DESENVOLVIMENTO DE SOFTWARE MULTIPLATAFORMA', NULL, NULL),
(38, 'Amábile Silvério', 'amabile.silverio34@gmail.com', 'contrato_efetivo', '591285460', '19971120245', '123456456', 'sim', '08:00:00', 'estagio_supervisionado', '02:00:00', 'Itapira', 'Estagio Supervisionado - DSM', 'Estagio Supervisionado - DSM', 'Estagio Supervisionado - DSM', 'Estagio Supervisionado - DSM', 'segunda', '{\"segunda\":[\"12:00\",\"14:00\"],\"terca\":[\"\",\"\"],\"quarta\":[\"\",\"\"],\"quinta\":[\"\",\"\"],\"sexta\":[\"\",\"\"]}', '03667.pdf', 'uploads/1750087080_03667.pdf', 1, '2025-06-16 15:18:00', 'Pendente', NULL, NULL, NULL, NULL, 3, 'DESENVOLVIMENTO DE SOFTWARE MULTIPLATAFORMA', NULL, NULL),
(39, 'Amábile Silvério', 'amabile.silverio34@gmail.com', 'contrato_efetivo', '591285460', '19971120245', '123456456', 'sim', '08:00:00', 'estagio_supervisionado', '02:00:00', 'Itapira', 'Estagio Supervisionado', 'Estagio Supervisionado', 'Estagio Supervisionado', 'Estagio Supervisionado', 'segunda', '{\"segunda\":[\"12:00\",\"14:00\"],\"terca\":[\"\",\"\"],\"quarta\":[\"\",\"\"],\"quinta\":[\"\",\"\"],\"sexta\":[\"\",\"\"]}', '03667.pdf', 'uploads/1750087531_03667.pdf', 1, '2025-06-16 15:25:31', 'Pendente', NULL, NULL, NULL, NULL, 3, 'DESENVOLVIMENTO DE SOFTWARE MULTIPLATAFORMA', NULL, NULL),
(40, 'Ana Célia', 'anacelia@fatec.com', 'contrato_efetivo', '591285460', '19971120245', '123456789', 'sim', '10:00:00', 'estagio_supervisionado', '02:00:00', 'Itapira', 'Estagio Supervisionado', 'Estagio Supervisionado', 'Estagio Supervisionado', 'Estagio Supervisionado', 'segunda', '{\"segunda\":[\"12:00\",\"14:00\"],\"terca\":[\"\",\"\"],\"quarta\":[\"\",\"\"],\"quinta\":[\"\",\"\"],\"sexta\":[\"\",\"\"]}', '03569 (1).pdf', 'uploads/1750100345_03569 (1).pdf', 1, '2025-06-16 18:59:05', 'Pendente', NULL, NULL, NULL, NULL, 1, 'DESENVOLVIMENTO DE SOFTWARE MULTIPLATAFORMA', NULL, NULL),
(41, 'Ana Célia', 'anacelia@fatec.com', 'contrato_efetivo', '591285460', '19971120245', '123456789', 'sim', '10:00:00', 'estagio_supervisionado', '02:00:00', 'Itapira', 'Estagio Supervisionado', 'Estagio Supervisionado', 'Estagio Supervisionado', 'Estagio Supervisionado', 'segunda', '{\"segunda\":[\"12:00\",\"14:00\"],\"terca\":[\"\",\"\"],\"quarta\":[\"\",\"\"],\"quinta\":[\"\",\"\"],\"sexta\":[\"\",\"\"]}', '03569 (1).pdf', 'uploads/1750100346_03569 (1).pdf', 1, '2025-06-16 18:59:06', 'Pendente', NULL, NULL, NULL, NULL, 1, 'DESENVOLVIMENTO DE SOFTWARE MULTIPLATAFORMA', NULL, NULL),
(42, 'Júnior Gonçalves ', 'junior@fatec.com', 'contrato_efetivo', '471254780', '19987152445', '789456123', 'sim', '08:00:00', 'iniciacao_cientifica', '02:00:00', 'Itapira', 'Iniciação cientifica', 'Iniciação cientifica', 'Iniciação cientifica', 'Iniciação cientifica', 'segunda', '{\"segunda\":[\"12:00\",\"14:00\"],\"terca\":[\"\",\"\"],\"quarta\":[\"\",\"\"],\"quinta\":[\"\",\"\"],\"sexta\":[\"\",\"\"]}', '03569 (1).pdf', 'uploads/1750103550_03569 (1).pdf', 1, '2025-06-16 19:52:30', 'Pendente', NULL, NULL, NULL, NULL, 2, 'GESTAO EMPRESARIAL', '', NULL),
(43, 'Ana Célia', 'anacelia@fatec.com', 'contrato_efetivo', '591285460', '19971120245', '123456789', 'sim', '08:00:00', 'estagio_supervisionado', '01:00:00', 'Campinas', 'Divulgação do vestibular 1', 'Estagio Supervisionado - DSM', 'Estagio Supervisionado', 'Estagio Supervisionado', 'segunda', '{\"segunda\":[\"13:00\",\"14:00\"],\"terca\":[\"\",\"\"],\"quarta\":[\"\",\"\"],\"quinta\":[\"\",\"\"],\"sexta\":[\"\",\"\"]}', 'Currículo Amábile.pdf', 'uploads/1750775579_Currículo Amábile.pdf', 1, '2025-06-24 14:32:59', 'Pendente', NULL, NULL, NULL, NULL, 1, 'DESENVOLVIMENTO DE SOFTWARE MULTIPLATAFORMA', '', NULL),
(44, 'Ana Célia', 'anacelia@fatec.com', 'contrato_efetivo', '591285460', '19971120245', '123456789', 'sim', '08:00:00', 'estagio_supervisionado', '01:00:00', 'Campinas', 'Divulgação do vestibular 1', 'Estagio Supervisionado - DSM', 'Estagio Supervisionado', 'Estagio Supervisionado', 'segunda', '{\"segunda\":[\"13:00\",\"14:00\"],\"terca\":[\"\",\"\"],\"quarta\":[\"\",\"\"],\"quinta\":[\"\",\"\"],\"sexta\":[\"\",\"\"]}', 'Currículo Amábile.pdf', 'uploads/1750775580_Currículo Amábile.pdf', 1, '2025-06-24 14:33:00', 'Pendente', NULL, NULL, NULL, NULL, 1, 'DESENVOLVIMENTO DE SOFTWARE MULTIPLATAFORMA', '', NULL),
(45, 'Ana Célia', 'anacelia@fatec.com', 'contrato_efetivo', '591285460', '19971120245', '123456789', 'sim', '08:00:00', 'trabalho_graduacao', '02:00:00', 'Campinas', 'Divulgação do vestibular 1', 'Divulgação', 'Reuniões semanais', 'Divulgação', 'segunda', '{\"segunda\":[\"12:00\",\"14:00\"],\"terca\":[\"\",\"\"],\"quarta\":[\"\",\"\"],\"quinta\":[\"\",\"\"],\"sexta\":[\"\",\"\"]}', 'Currículo Amábile.pdf', 'uploads/1750776920_Currículo Amábile.pdf', 1, '2025-06-24 14:55:20', 'Pendente', NULL, NULL, NULL, NULL, 1, 'DESENVOLVIMENTO DE SOFTWARE MULTIPLATAFORMA', '', NULL),
(46, 'Júnior Gonçalves ', 'junior@fatec.com', 'contrato_efetivo', '471254780', '19987152445', '789456123', 'sim', '08:00:00', 'iniciacao_cientifica', '02:00:00', 'Campinas', 'Iniciação cientifica 2', 'Iniciação cientifica do Junior', 'Iniciação cientifica do Junior', 'Iniciação cientifica do Junior', 'segunda', '{\"segunda\":[\"12:00\",\"14:00\"],\"terca\":[\"\",\"\"],\"quarta\":[\"\",\"\"],\"quinta\":[\"\",\"\"],\"sexta\":[\"\",\"\"]}', 'Currículo Amábile.pdf', 'uploads/1750777820_Currículo Amábile.pdf', 1, '2025-06-24 15:10:20', 'Aprovado', '02:00:00', NULL, NULL, NULL, 2, 'DESENVOLVIMENTO DE SOFTWARE MULTIPLATAFORMA', '', 3),
(47, 'Júnior Gonçalves ', 'junior@fatec.com', 'contrato_efetivo', '471254780', '19987152445', '789456123', 'sim', '08:00:00', 'iniciacao_cientifica', '01:00:00', 'Campinas', 'Divulgação do vestibular 1', 'Iniciação cientifica do Junior', 'reunioes', 'divulgação', 'segunda', '{\"segunda\":[\"12:00\",\"13:00\"],\"terca\":[\"\",\"\"],\"quarta\":[\"\",\"\"],\"quinta\":[\"\",\"\"],\"sexta\":[\"\",\"\"]}', '', '', 1, '2025-06-24 15:53:02', 'Pendente', NULL, NULL, NULL, NULL, 2, 'DESENVOLVIMENTO DE SOFTWARE MULTIPLATAFORMA', '1750780382_detalhes_inscricao (11).pdf', 4),
(48, 'Ana Célia', 'anacelia@fatec.com', 'contrato_efetivo', '591285460', '19971120245', '123456789', 'sim', '08:00:00', 'trabalho_graduacao', '01:00:00', 'Campinas', 'Divulgação do vestibular 1', 'Divulgação - turma dsm', 'Divulgação - turma dsm', 'Divulgação - turma dsm', 'segunda', '{\"segunda\":[\"12:00\",\"13:00\"],\"terca\":[\"\",\"\"],\"quarta\":[\"\",\"\"],\"quinta\":[\"\",\"\"],\"sexta\":[\"\",\"\"]}', '', '', 1, '2025-06-24 16:20:03', 'Aprovado', '01:00:00', NULL, NULL, NULL, 1, 'DESENVOLVIMENTO DE SOFTWARE MULTIPLATAFORMA', '1750782003_detalhes_inscricao (11).pdf', 4),
(49, 'Amábile Silvério', 'amabile.silverio34@gmail.com', 'contrato_efetivo', '591285460', '19971120245', '123456456', 'sim', '08:00:00', 'estagio_supervisionado', '05:00:00', 'Itapira', 'Inscrições HAE - 2025', 'Estagio Supervisionado - Amábile', 'Reuniões semanais', 'Supervisão dos alunos de estágio', 'segunda,terca', '{\"segunda\":[\"12:00\",\"14:00\"],\"terca\":[\"12:00\",\"15:00\"],\"quarta\":[\"\",\"\"],\"quinta\":[\"\",\"\"],\"sexta\":[\"\",\"\"]}', '', '', 1, '2025-06-24 17:08:27', 'Rejeitado', NULL, NULL, NULL, 'Não está conforme', 3, 'DESENVOLVIMENTO DE SOFTWARE MULTIPLATAFORMA', '1750784907_detalhes_inscricao (11).pdf', 9),
(50, 'Amábile Silvério', 'amabile.silverio34@gmail.com', 'contrato_efetivo', '591285460', '19971120245', '123456456', 'sim', '08:00:00', 'estagio_supervisionado', '05:00:00', 'Itapira', 'Inscrições HAE - 2025', 'Estagio Supervisionado - Amábile', 'Reunições semanais', 'Estagio dos alunos de dsm', 'segunda', '{\"segunda\":[\"13:00\",\"17:00\"],\"terca\":[\"\",\"\"],\"quarta\":[\"\",\"\"],\"quinta\":[\"\",\"\"],\"sexta\":[\"\",\"\"]}', '', '', 1, '2025-06-24 17:28:11', 'Aprovado', '06:00:00', NULL, NULL, NULL, 3, 'DESENVOLVIMENTO DE SOFTWARE MULTIPLATAFORMA', '1750786091_detalhes_inscricao (18).pdf', 9),
(51, 'Amábile Silvério', 'amabile.silverio34@gmail.com', 'contrato_efetivo', '591285460', '19971120245', '123456456', 'sim', '08:00:00', 'estagio_supervisionado', '05:00:00', 'Campinas', 'Divulgação do vestibular 1', 'Estagio Supervisionado', 'Estagio Supervisionado', 'Estagio Supervisionado', 'terca', '{\"segunda\":[\"\",\"\"],\"terca\":[\"12:00\",\"17:00\"],\"quarta\":[\"\",\"\"],\"quinta\":[\"\",\"\"],\"sexta\":[\"\",\"\"]}', '', '', 1, '2025-06-24 17:58:05', 'Aprovado', '05:00:00', NULL, NULL, NULL, 3, 'DESENVOLVIMENTO DE SOFTWARE MULTIPLATAFORMA', '1750787885_detalhes_inscricao (18).pdf', 4),
(52, 'Amábile Silvério', 'amabile.silverio34@gmail.com', 'contrato_efetivo', '591285460', '19971120245', '123456456', 'sim', '08:00:00', 'estagio_supervisionado', '05:00:00', 'Itapira', 'Inscrições HAE - 2025', 'Estagio Supervisionado', 'Estagio Supervisionado', 'Estagio Supervisionado', 'terca', '{\"segunda\":[\"\",\"\"],\"terca\":[\"12:00\",\"17:00\"],\"quarta\":[\"\",\"\"],\"quinta\":[\"\",\"\"],\"sexta\":[\"\",\"\"]}', '', '', 1, '2025-06-24 18:13:11', 'Pendente', NULL, NULL, NULL, NULL, 3, 'DESENVOLVIMENTO DE SOFTWARE MULTIPLATAFORMA', '1750788791_detalhes_inscricao (19).pdf', 9),
(53, 'Amábile Silvério', 'amabile.silverio34@gmail.com', 'contrato_efetivo', '591285460', '19971120245', '123456456', 'sim', '08:00:00', 'trabalho_graduacao', '05:00:00', 'Itapira', 'Inscrições HAE - 2025', 'Trabalho de Graduação', 'Trabalho de Graduação', 'Trabalho de Graduação', 'terca', '{\"segunda\":[\"\",\"\"],\"terca\":[\"12:00\",\"17:00\"],\"quarta\":[\"\",\"\"],\"quinta\":[\"\",\"\"],\"sexta\":[\"\",\"\"]}', '', '', 1, '2025-06-24 18:33:03', 'Aprovado', '05:00:00', NULL, NULL, NULL, 3, 'DESENVOLVIMENTO DE SOFTWARE MULTIPLATAFORMA', '1750789983_detalhes_inscricao (19).pdf', 9),
(54, 'Amábile Silvério', 'amabile.silverio34@gmail.com', 'contrato_efetivo', '591285460', '19971120245', '123456456', 'sim', '08:00:00', 'iniciacao_cientifica', '05:00:00', 'Itapira', 'Inscrições HAE - 2025', 'Iniciação turma DSM', 'Iniciação turma DSM', 'Iniciação turma DSM', 'terca', '{\"segunda\":[\"\",\"\"],\"terca\":[\"13:00\",\"18:00\"],\"quarta\":[\"\",\"\"],\"quinta\":[\"\",\"\"],\"sexta\":[\"\",\"\"]}', '', '', 1, '2025-06-24 18:42:46', 'Aprovado', '05:00:00', NULL, NULL, NULL, 3, 'DESENVOLVIMENTO DE SOFTWARE MULTIPLATAFORMA', '1750790566_CamScanner �24-06-2025 14.43�.pdf', 9),
(55, 'Ana Célia', 'anacelia@fatec.com', 'contrato_efetivo', '591285460', '19971120245', '123456789', 'sim', '08:00:00', 'estagio_supervisionado', '05:00:00', 'Itapira', 'Inscrições HAE - 2025', 'Estagio Supervisionado', 'Estagio Supervisionado', 'Estagio Supervisionado', 'terca', '{\"segunda\":[\"\",\"\"],\"terca\":[\"12:00\",\"17:00\"],\"quarta\":[\"\",\"\"],\"quinta\":[\"\",\"\"],\"sexta\":[\"\",\"\"]}', '', '', 1, '2025-06-24 20:11:25', 'Aprovado', '05:00:00', NULL, NULL, NULL, 1, 'DESENVOLVIMENTO DE SOFTWARE MULTIPLATAFORMA', '1750795884_DOC ESTAGIO.pdf', 9);

-- --------------------------------------------------------

--
-- Estrutura para tabela `professor`
--

CREATE TABLE `professor` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `rg` varchar(20) DEFAULT NULL,
  `contato` varchar(20) DEFAULT NULL,
  `matricula` varchar(20) DEFAULT NULL,
  `tipo_contrato` enum('contrato_temporario','contrato_efetivo') NOT NULL,
  `senha` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `professor`
--

INSERT INTO `professor` (`id`, `nome`, `email`, `rg`, `contato`, `matricula`, `tipo_contrato`, `senha`) VALUES
(1, 'Ana Célia', 'anacelia@fatec.com', '591285460', '19971120245', '123456789', 'contrato_efetivo', '1234567'),
(2, 'Júnior Gonçalves ', 'junior@fatec.com', '471254780', '19987152445', '789456123', 'contrato_efetivo', '1234'),
(3, 'Amábile Silvério', 'amabile.silverio34@gmail.com', '591285460', '19971120245', '123456456', 'contrato_efetivo', '123');

-- --------------------------------------------------------

--
-- Estrutura para tabela `projetos_estatisticos`
--

CREATE TABLE `projetos_estatisticos` (
  `id` int(11) NOT NULL,
  `id_inscricao` int(11) NOT NULL,
  `id_professor` int(11) NOT NULL,
  `curso` varchar(100) NOT NULL,
  `semestre` varchar(20) NOT NULL,
  `status` enum('inscrito','aprovado','rejeitado') NOT NULL,
  `data_envio` date DEFAULT NULL,
  `titulo_projeto` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `relatorios_hae`
--

CREATE TABLE `relatorios_hae` (
  `id` int(11) NOT NULL,
  `id_inscricao` int(11) NOT NULL,
  `descricao` text DEFAULT NULL,
  `anexo` varchar(255) DEFAULT NULL,
  `status` enum('Pendente','Aprovado','Correção Solicitada') DEFAULT 'Pendente',
  `parecer_coordenador` text DEFAULT NULL,
  `data_envio` datetime DEFAULT current_timestamp(),
  `data_analise` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `relatorios_hae`
--

INSERT INTO `relatorios_hae` (`id`, `id_inscricao`, `descricao`, `anexo`, `status`, `parecer_coordenador`, `data_envio`, `data_analise`) VALUES
(1, 18, 'Tudo ocorreu conforme', '', 'Aprovado', 'Ok aprovado', '2025-06-16 14:55:27', '2025-06-16 15:56:55'),
(2, 18, 'ok segue mais detalhado', '1750100378_03569 (1).pdf', 'Aprovado', 'Ok', '2025-06-16 15:59:38', '2025-06-16 16:04:48'),
(3, 10, 'Ocorreu tudo conforme', '', 'Pendente', NULL, '2025-06-16 16:21:57', NULL),
(4, 10, 'Tudo ocorreu conforme', '', 'Pendente', NULL, '2025-06-16 16:24:17', NULL),
(5, 28, 'Tudo ocorreu conforme', '', 'Pendente', NULL, '2025-06-16 17:00:26', NULL),
(6, 28, 'Tudo ocorreu confomre', '1750104039_03569 (1).pdf', 'Pendente', NULL, '2025-06-16 17:00:39', NULL),
(7, 28, 'fsdds', '1750104124_03569 (1).pdf', 'Pendente', NULL, '2025-06-16 17:02:04', NULL),
(8, 21, 'dsds', '', 'Pendente', NULL, '2025-06-16 17:03:04', NULL),
(9, 21, 'fdfd', '', 'Pendente', NULL, '2025-06-16 17:03:47', NULL),
(10, 28, 'tudo ok', '1750104307_03569 (1).pdf', 'Pendente', NULL, '2025-06-16 17:05:07', NULL),
(11, 21, 'tudo ok', '', 'Pendente', NULL, '2025-06-16 17:07:58', NULL),
(12, 28, 'tudo conforme', '', 'Pendente', NULL, '2025-06-16 17:10:13', NULL),
(13, 28, 'tudo ok', '', 'Pendente', NULL, '2025-06-16 17:12:18', NULL),
(14, 21, 'tudo ok', '1750104747_03569 (1).pdf', 'Pendente', NULL, '2025-06-16 17:12:27', NULL),
(15, 21, 'tudo conforme', '', 'Pendente', NULL, '2025-06-16 17:14:19', NULL),
(16, 46, 'Ok ocrreu conforme', '', 'Aprovado', 'ok', '2025-06-24 12:24:13', '2025-06-24 12:24:34'),
(17, 48, 'Ok, segue correção', '1750783048_Currículo Amábile.pdf', 'Pendente', NULL, '2025-06-24 13:25:14', '2025-06-24 13:37:03'),
(18, 51, 'Ok ocorreu tudo confomre', '1750787971_detalhes_inscricao (18).pdf', 'Pendente', NULL, '2025-06-24 14:59:31', NULL),
(19, 54, 'Ok', '1750790668_CamScanner �24-06-2025 14.43�.pdf', 'Aprovado', 'Aprovado', '2025-06-24 15:43:32', '2025-06-24 15:44:45'),
(20, 51, 'tudo ok', '', 'Aprovado', 'ok aprovado', '2025-06-24 15:49:42', '2025-06-24 15:50:08'),
(21, 50, 'tudo conforme', '', 'Aprovado', 'Ok aprovado', '2025-06-24 15:51:51', '2025-06-24 15:52:08'),
(22, 55, 'Ocorreu tudo conforme', '1750795996_DOC RELATORIO.pdf', 'Aprovado', 'aprovado', '2025-06-24 17:13:16', '2025-06-25 11:35:43'),
(23, 55, 'Tudo ocorreu conforme', '', 'Aprovado', 'aprovado', '2025-06-25 11:05:33', '2025-06-25 11:35:55'),
(24, 55, 'Tudo conforme', '', 'Correção Solicitada', 'ajustar', '2025-06-25 11:16:41', '2025-06-25 11:32:34'),
(25, 55, 'ok', '', 'Aprovado', 'ok', '2025-06-25 11:18:39', '2025-06-25 11:37:58'),
(26, 48, 'ok', '', 'Correção Solicitada', 'Corrigir', '2025-06-25 11:19:59', '2025-06-25 11:31:20'),
(27, 55, 'ok', '', 'Pendente', NULL, '2025-06-25 11:49:19', NULL);

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `agenda_hae`
--
ALTER TABLE `agenda_hae`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_inscricao` (`id_inscricao`),
  ADD KEY `id_professor` (`id_professor`);

--
-- Índices de tabela `coordenador`
--
ALTER TABLE `coordenador`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Índices de tabela `editais_hae`
--
ALTER TABLE `editais_hae`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_coordenador` (`id_coordenador`);

--
-- Índices de tabela `inscricoes_hae`
--
ALTER TABLE `inscricoes_hae`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_edital` (`id_edital`);

--
-- Índices de tabela `professor`
--
ALTER TABLE `professor`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `matricula` (`matricula`);

--
-- Índices de tabela `relatorios_hae`
--
ALTER TABLE `relatorios_hae`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_inscricao` (`id_inscricao`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `agenda_hae`
--
ALTER TABLE `agenda_hae`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de tabela `coordenador`
--
ALTER TABLE `coordenador`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `editais_hae`
--
ALTER TABLE `editais_hae`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de tabela `inscricoes_hae`
--
ALTER TABLE `inscricoes_hae`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- AUTO_INCREMENT de tabela `professor`
--
ALTER TABLE `professor`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `relatorios_hae`
--
ALTER TABLE `relatorios_hae`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `agenda_hae`
--
ALTER TABLE `agenda_hae`
  ADD CONSTRAINT `agenda_hae_ibfk_1` FOREIGN KEY (`id_inscricao`) REFERENCES `inscricoes_hae` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `agenda_hae_ibfk_2` FOREIGN KEY (`id_professor`) REFERENCES `professor` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Restrições para tabelas `editais_hae`
--
ALTER TABLE `editais_hae`
  ADD CONSTRAINT `editais_hae_ibfk_1` FOREIGN KEY (`id_coordenador`) REFERENCES `coordenador` (`id`);

--
-- Restrições para tabelas `inscricoes_hae`
--
ALTER TABLE `inscricoes_hae`
  ADD CONSTRAINT `inscricoes_hae_ibfk_1` FOREIGN KEY (`id_edital`) REFERENCES `editais_hae` (`id`) ON DELETE SET NULL;

--
-- Restrições para tabelas `relatorios_hae`
--
ALTER TABLE `relatorios_hae`
  ADD CONSTRAINT `relatorios_hae_ibfk_1` FOREIGN KEY (`id_inscricao`) REFERENCES `inscricoes_hae` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
