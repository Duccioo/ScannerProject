-- phpMyAdmin SQL Dump
-- version 4.9.7
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Creato il: Ago 30, 2021 alle 13:55
-- Versione del server: 5.7.32
-- Versione PHP: 7.4.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `BarCodeScanner`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `Contiene`
--

CREATE TABLE `Contiene` (
  `id_ProdottoConosciuto` int(20) NOT NULL,
  `id_ListaImmagazzinamento` int(20) NOT NULL,
  `quantità` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dump dei dati per la tabella `Contiene`
--

INSERT INTO `Contiene` (`id_ProdottoConosciuto`, `id_ListaImmagazzinamento`, `quantità`) VALUES
(9, 2, NULL),
(10, 2, NULL),
(11, 3, NULL),
(13, 2, NULL),
(13, 3, NULL);

-- --------------------------------------------------------

--
-- Struttura della tabella `DaAcquisire`
--

CREATE TABLE `DaAcquisire` (
  `id_Prodotto` int(20) NOT NULL,
  `id_ListaToDo` int(20) NOT NULL,
  `data_completamento` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dump dei dati per la tabella `DaAcquisire`
--

INSERT INTO `DaAcquisire` (`id_Prodotto`, `id_ListaToDo`, `data_completamento`) VALUES
(1, 1, NULL),
(9, 1, NULL),
(9, 13, NULL),
(10, 1, NULL),
(13, 1, NULL);

-- --------------------------------------------------------

--
-- Struttura della tabella `ListaImmagazzinamento`
--

CREATE TABLE `ListaImmagazzinamento` (
  `id` int(20) NOT NULL,
  `nome` varchar(40) DEFAULT NULL,
  `descrizione` varchar(200) DEFAULT NULL,
  `id_UtenteRegistrato` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dump dei dati per la tabella `ListaImmagazzinamento`
--

INSERT INTO `ListaImmagazzinamento` (`id`, `nome`, `descrizione`, `id_UtenteRegistrato`) VALUES
(2, 'Prova2', 'aaaaaaa', 2),
(3, 'Prova3', 'questa è una prova di descrizione perepereprpere', 2);

-- --------------------------------------------------------

--
-- Struttura della tabella `ListaToDo`
--

CREATE TABLE `ListaToDo` (
  `id` int(20) NOT NULL,
  `nome` varchar(40) DEFAULT NULL,
  `data_creazione` date DEFAULT NULL,
  `descrizione` varchar(200) DEFAULT NULL,
  `id_UtenteRegistrato` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dump dei dati per la tabella `ListaToDo`
--

INSERT INTO `ListaToDo` (`id`, `nome`, `data_creazione`, `descrizione`, `id_UtenteRegistrato`) VALUES
(1, 'Spesa', NULL, 'cose da comprare', 2),
(2, 'Non Vedere', NULL, 'Prova non vista', 1),
(13, 'Da comprare', '2021-08-27', '', 2);

-- --------------------------------------------------------

--
-- Struttura della tabella `ProdottiConosciuti`
--

CREATE TABLE `ProdottiConosciuti` (
  `id_Prodotto` int(20) NOT NULL,
  `codice_a_barre` varchar(40) DEFAULT NULL,
  `data_iscrizione` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dump dei dati per la tabella `ProdottiConosciuti`
--

INSERT INTO `ProdottiConosciuti` (`id_Prodotto`, `codice_a_barre`, `data_iscrizione`) VALUES
(9, '8003510020423', '2021-08-26'),
(10, '4006381333627', '2021-08-26'),
(11, '8058697280627', '2021-08-27'),
(13, '5412841601240', '2021-08-27'),
(15, '8076809573252', '2021-08-28');

-- --------------------------------------------------------

--
-- Struttura della tabella `Prodotto`
--

CREATE TABLE `Prodotto` (
  `id` int(20) NOT NULL,
  `nome` varchar(40) DEFAULT NULL,
  `info` varchar(200) DEFAULT NULL,
  `id_UtenteRegistrato` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dump dei dati per la tabella `Prodotto`
--

INSERT INTO `Prodotto` (`id`, `nome`, `info`, `id_UtenteRegistrato`) VALUES
(1, 'Nutella', 'Nutelloso', 2),
(9, 'Deodorante', '', 2),
(10, 'Evidenziatore STABILO Boss', 'Giallo', 2),
(11, 'Be-Total', 'Integratore Alimentare', 2),
(13, 'Mentine Frisk', '', 2),
(14, 'sENZA CODICE', 'aaa', 2),
(15, 'Baiocchi mulino bianco', '', 2);

-- --------------------------------------------------------

--
-- Struttura della tabella `Registrato`
--

CREATE TABLE `Registrato` (
  `id_Utente` int(20) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `data_iscrizione` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dump dei dati per la tabella `Registrato`
--

INSERT INTO `Registrato` (`id_Utente`, `username`, `password`, `data_iscrizione`) VALUES
(1, 'Dosio', '$2y$10$PMV8WeDSUf/pIi0ZjqEndO8AXUbeqhRX6yq7V28F.fGoi9PxRYIm2', '2021-08-23 01:11:05'),
(2, 'duccio', '$2y$10$4wS8CTMp6rTFBVnZoPCpUewAMQybDAXCD1FPwnYwOJymYAriWbN6e', '2021-08-23 01:29:09'),
(3, 'Martina', '$2y$10$ViVvKlJuRSdZWhwayf/mceaD1uRIIx1AvkDfbZHV/z7QOvUueDUTy', '2021-08-27 20:20:34');

-- --------------------------------------------------------

--
-- Struttura della tabella `Scansione`
--

CREATE TABLE `Scansione` (
  `id_Utente` int(11) NOT NULL,
  `id_Prodotto` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struttura della tabella `Utente`
--

CREATE TABLE `Utente` (
  `id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `Contiene`
--
ALTER TABLE `Contiene`
  ADD PRIMARY KEY (`id_ProdottoConosciuto`,`id_ListaImmagazzinamento`),
  ADD KEY `id_ListaImmagazzinamento` (`id_ListaImmagazzinamento`);

--
-- Indici per le tabelle `DaAcquisire`
--
ALTER TABLE `DaAcquisire`
  ADD PRIMARY KEY (`id_Prodotto`,`id_ListaToDo`),
  ADD KEY `id_ListaToDo` (`id_ListaToDo`);

--
-- Indici per le tabelle `ListaImmagazzinamento`
--
ALTER TABLE `ListaImmagazzinamento`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `id_UtenteRegistrato` (`id_UtenteRegistrato`);

--
-- Indici per le tabelle `ListaToDo`
--
ALTER TABLE `ListaToDo`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `id_UtenteRegistrato` (`id_UtenteRegistrato`);

--
-- Indici per le tabelle `ProdottiConosciuti`
--
ALTER TABLE `ProdottiConosciuti`
  ADD PRIMARY KEY (`id_Prodotto`),
  ADD UNIQUE KEY `codice_a_barre` (`codice_a_barre`);

--
-- Indici per le tabelle `Prodotto`
--
ALTER TABLE `Prodotto`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD UNIQUE KEY `nome` (`nome`),
  ADD KEY `id_UtenteRegistrato` (`id_UtenteRegistrato`);

--
-- Indici per le tabelle `Registrato`
--
ALTER TABLE `Registrato`
  ADD PRIMARY KEY (`id_Utente`),
  ADD UNIQUE KEY `id_Utente` (`id_Utente`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `id_Utente_2` (`id_Utente`);

--
-- Indici per le tabelle `Scansione`
--
ALTER TABLE `Scansione`
  ADD PRIMARY KEY (`id_Utente`,`id_Prodotto`),
  ADD KEY `id_Prodotto` (`id_Prodotto`);

--
-- Indici per le tabelle `Utente`
--
ALTER TABLE `Utente`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `ListaImmagazzinamento`
--
ALTER TABLE `ListaImmagazzinamento`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT per la tabella `ListaToDo`
--
ALTER TABLE `ListaToDo`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT per la tabella `Prodotto`
--
ALTER TABLE `Prodotto`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT per la tabella `Registrato`
--
ALTER TABLE `Registrato`
  MODIFY `id_Utente` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT per la tabella `Utente`
--
ALTER TABLE `Utente`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Limiti per le tabelle scaricate
--

--
-- Limiti per la tabella `Contiene`
--
ALTER TABLE `Contiene`
  ADD CONSTRAINT `contiene_ibfk_1` FOREIGN KEY (`id_ProdottoConosciuto`) REFERENCES `ProdottiConosciuti` (`id_Prodotto`),
  ADD CONSTRAINT `contiene_ibfk_2` FOREIGN KEY (`id_ListaImmagazzinamento`) REFERENCES `ListaImmagazzinamento` (`id`);

--
-- Limiti per la tabella `DaAcquisire`
--
ALTER TABLE `DaAcquisire`
  ADD CONSTRAINT `daacquisire_ibfk_1` FOREIGN KEY (`id_Prodotto`) REFERENCES `Prodotto` (`id`),
  ADD CONSTRAINT `daacquisire_ibfk_2` FOREIGN KEY (`id_ListaToDo`) REFERENCES `ListaToDo` (`id`);

--
-- Limiti per la tabella `ListaImmagazzinamento`
--
ALTER TABLE `ListaImmagazzinamento`
  ADD CONSTRAINT `listaimmagazzinamento_ibfk_1` FOREIGN KEY (`id_UtenteRegistrato`) REFERENCES `Registrato` (`id_Utente`);

--
-- Limiti per la tabella `ListaToDo`
--
ALTER TABLE `ListaToDo`
  ADD CONSTRAINT `listatodo_ibfk_1` FOREIGN KEY (`id_UtenteRegistrato`) REFERENCES `Registrato` (`id_Utente`);

--
-- Limiti per la tabella `ProdottiConosciuti`
--
ALTER TABLE `ProdottiConosciuti`
  ADD CONSTRAINT `prodotticonosciuti_ibfk_1` FOREIGN KEY (`id_Prodotto`) REFERENCES `Prodotto` (`id`);

--
-- Limiti per la tabella `Prodotto`
--
ALTER TABLE `Prodotto`
  ADD CONSTRAINT `prodotto_ibfk_1` FOREIGN KEY (`id_UtenteRegistrato`) REFERENCES `Registrato` (`id_Utente`);

--
-- Limiti per la tabella `Scansione`
--
ALTER TABLE `Scansione`
  ADD CONSTRAINT `scansione_ibfk_1` FOREIGN KEY (`id_Utente`) REFERENCES `Registrato` (`id_Utente`),
  ADD CONSTRAINT `scansione_ibfk_2` FOREIGN KEY (`id_Prodotto`) REFERENCES `Prodotto` (`id`);