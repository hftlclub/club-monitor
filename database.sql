SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;


--
-- Tabellenstruktur für Tabelle `authentication_tokens`
--

CREATE TABLE IF NOT EXISTS `authentication_tokens` (
  `id` varchar(13) NOT NULL,
  `token` varchar(32) NOT NULL,
  `login` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `lastused` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `drinks_addassign`
--

CREATE TABLE IF NOT EXISTS `drinks_addassign` (
  `drink` varchar(13) NOT NULL,
  `additive` varchar(13) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `drinks_addassign`
--

INSERT INTO `drinks_addassign` (`drink`, `additive`) VALUES
('7', '4'),
('6', '3'),
('5', '1'),
('5', '2'),
('7', '1'),
('8', '2'),
('10', '1'),
('10', '3'),
('13', '2'),
('19', '5'),
('22', '1'),
('15', '6'),
('16', '6'),
('23', '1'),
('23', '2'),
('11', '1'),
('11', '2'),
('25', '1'),
('17', '1'),
('30', '4'),
('30', '7'),
('30', '8');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `drinks_additives`
--

CREATE TABLE IF NOT EXISTS `drinks_additives` (
  `id` varchar(13) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `drinks_additives`
--

INSERT INTO `drinks_additives` (`id`, `name`) VALUES
('1', 'Farbstoff'),
('2', 'Koffein'),
('3', 'Süßstoff'),
('4', 'Antioxidationsmittel'),
('5', 'Konservierungsmittel'),
('6', 'Sulfite'),
('7', 'Chinin'),
('8', 'Säuerungsmittel');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `drinks_categories`
--

CREATE TABLE IF NOT EXISTS `drinks_categories` (
  `id` varchar(13) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `drinks_categories`
--

INSERT INTO `drinks_categories` (`id`, `name`) VALUES
('1', 'Bier'),
('2', 'Alkoholfreie Getränke'),
('3', 'Wein und Sekt'),
('4', 'Schnaps'),
('5', 'Cocktails');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `drinks_drinks`
--

CREATE TABLE IF NOT EXISTS `drinks_drinks` (
  `id` varchar(13) NOT NULL,
  `name` varchar(255) NOT NULL,
  `category` varchar(13) NOT NULL,
  `group` varchar(255) DEFAULT NULL,
  `price` float NOT NULL,
  `inactive` tinyint(1) NOT NULL DEFAULT '0',
  `size` varchar(13) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `drinks_drinks`
--

INSERT INTO `drinks_drinks` (`id`, `name`, `category`, `group`, `price`, `inactive`, `size`) VALUES
('1', 'Ur-Krostitzer', '1', NULL, 1, 0, '0,5 l'),
('2', 'Augustiner hell', '1', NULL, 1.5, 0, '0,5 l'),
('11', 'Cola', '2', 'ColaCompany', 1, 0, '0,33 l'),
('9', 'Wostok', '2', NULL, 1.5, 0, '0,33 l'),
('3', 'Köstritzer Schwarzbier', '1', NULL, 1.5, 0, '0,5 l'),
('4', 'Paulaner Hefe-Weißbier Hell', '1', NULL, 1.5, 0, '0,5 l'),
('5', 'Mixery', '1', NULL, 1.5, 0, '0,5 l'),
('6', 'Radler', '1', NULL, 1.5, 0, '0,5 l'),
('12', 'Wasser', '2', NULL, 0.5, 0, '0,33 l'),
('10', 'Himbeerbrause', '2', NULL, 1, 0, '0,33 l'),
('8', 'Club Mate', '2', NULL, 1.5, 0, '0,5 l'),
('30', 'Gin Tonic', '5', NULL, 2.5, 0, '0,4 l'),
('15', 'Sekt', '3', NULL, 1.5, 0, '0,2 l'),
('16', 'Wein (Rot/Weiß/Rosé)', '3', NULL, 1.5, 0, '0,2 l'),
('17', 'Pfeffi', '4', 'BunterSchnapps', 0.5, 0, '4 cl'),
('25', 'Fanta', '2', 'ColaCompany', 1, 0, '0,33 l'),
('18', 'Wodka', '4', NULL, 1.5, 0, '4 cl'),
('19', 'Gisela', '4', NULL, 1.5, 0, '4 cl'),
('20', 'Kräuter', '4', '', 1.5, 0, '4 cl'),
('22', 'Whiskey, Scotch, ...', '4', NULL, 2.5, 0, '4 cl'),
('23', 'Cuba Libre', '5', NULL, 3, 0, '0,4 l'),
('24', 'Caipirinha', '5', NULL, 3, 0, '0,4 l'),
('26', 'Sprite', '2', 'ColaCompany', 1, 0, '0,33 l'),
('27', 'Apfel', '4', 'BunterSchnapps', 0.5, 0, '4 cl'),
('28', 'Kirsch', '4', 'BunterSchnapps', 0.5, 0, '4 cl'),
('29', 'Clausthaler Alkoholfrei', '1', NULL, 1.5, 0, '0.5 l');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `infoscreen_ticker`
--

CREATE TABLE IF NOT EXISTS `infoscreen_ticker` (
  `id` varchar(13) NOT NULL,
  `author` varchar(20) NOT NULL,
  `text` varchar(160) NOT NULL,
  `posted` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `views` int(5) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;



--
-- Tabellenstruktur für Tabelle `infoscreen_timeline`
--

CREATE TABLE IF NOT EXISTS `infoscreen_timeline` (
  `id` varchar(13) NOT NULL,
  `duration` int(13) NOT NULL,
  `type` varchar(255) NOT NULL,
  `moduleid` varchar(13) DEFAULT NULL,
  `order` int(13) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `module_barclosing`
--

CREATE TABLE IF NOT EXISTS `module_barclosing` (
  `id` varchar(13) NOT NULL,
  `time` varchar(255) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;



--
-- Tabellenstruktur für Tabelle `module_highlights`
--

CREATE TABLE IF NOT EXISTS `module_highlights` (
  `id` varchar(13) NOT NULL,
  `headline` varchar(30) DEFAULT NULL,
  `description` text,
  `url` varchar(255) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `module_text`
--

CREATE TABLE IF NOT EXISTS `module_text` (
  `id` varchar(13) NOT NULL,
  `headline` text,
  `body` text
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `options`
--

CREATE TABLE IF NOT EXISTS `options` (
  `key` varchar(255) NOT NULL,
  `value` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `options`
--

INSERT INTO `options` (`key`, `value`) VALUES
('passwords', '[{"password":""}]'),
('karaoke_songInputActive', 'false');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `pizza_orders`
--

CREATE TABLE IF NOT EXISTS `pizza_orders` (
  `id` varchar(13) NOT NULL,
  `timestamp` bigint(20) NOT NULL,
  `name` varchar(255) NOT NULL,
  `pizza` varchar(13) NOT NULL,
  `comment` varchar(255) NOT NULL,
  `paid` tinyint(4) NOT NULL DEFAULT '0',
  `ordered` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `pizza_pizzas`
--

CREATE TABLE IF NOT EXISTS `pizza_pizzas` (
  `id` varchar(13) NOT NULL,
  `number` varchar(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `ingredients` mediumtext NOT NULL,
  `service` varchar(13) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Daten für Tabelle `pizza_pizzas`
--

INSERT INTO `pizza_pizzas` (`id`, `number`, `name`, `ingredients`, `service`) VALUES
('535c15c989a39', '9', 'Hawaii', 'Schinken\r\nAnanas\r\nOregano\r\nGoudakäse', '535c198491490'),
('535c1605c66c1', '1', 'Margarita', 'Tomatensoße\r\nKäse\r\nOregano\r\nGoudakäse', '535c198491490'),
('535c17c2435a2', '2', 'Salami', 'Salami\r\nOregano\r\nGoudakäse', '535c198491490'),
('535c1826dd107', '3', 'Prosciutto', 'Schinken\r\nOregano\r\nGoudakäse', '535c198491490'),
('535c1889599d2', '4', 'Funghi', 'frische Champignons\r\nOregano\r\nGoudakäse', '535c198491490'),
('535c189403b7b', '5', 'Regina', 'Schinken\r\nChampignons\r\nmilde Peperoni\r\nOregano\r\nGoudakäse', '535c198491490'),
('535c18c169755', '6', 'Milano', 'Schinken\r\nChampignons\r\nArtischocken\r\nfrische Tomaten\r\nOregano\r\nGoudakäse', '535c198491490'),
('535c18f694a2a', '7', 'Ancona', 'Salami\r\nChampignons\r\nZwiebeln\r\nOregano\r\nGoudakäse', '535c198491490'),
('535c192a73a42', '8', 'Broccoli', 'Schinken\r\nChampignons\r\nBroccoli\r\nOregano\r\nGoudakäse', '535c198491490'),
('535c1958d9613', '10', 'Spinaci 1', 'Schinken\nSpinat\nEi\nMozzarella\nOregano\nGoudakäse', '535c198491490'),
('535c19958c780', '11', 'Spinaci 2', 'Spinat\r\nHirtenkäse\r\nKnoblauch\r\nOregano\r\nGoudakäse', '535c198491490'),
('535c1a28ca91c', '12', 'Vier Jahreszeiten', 'Schinken\r\nSalami\r\nChampignons\r\nEi\r\nPaprika\r\nOregano\r\nGoudakäse', '535c198491490'),
('535c1a646a1f5', '13', 'Bandito', 'Salami\r\nSchinken\r\nChampignons\r\nZwiebel\r\nPeperoni\r\nOregano\r\nGoudakäse', '535c198491490'),
('535c1ab0483d3', '14', 'Mista', 'Salami\r\nSchinken\r\nHackfleisch\r\nSpeck\r\nKnoblauch\r\nOregano\r\nGoudakäse', '535c198491490'),
('535c1b24ae421', '13L', 'Lovely', 'Salami\r\nFormfleischschinken\r\nHackfleisch\r\nKnoblauch\r\nOliven\r\nscharfe Peperoni\r\nOregano\r\nGoudakäse', '535c198491490'),
('535c1b5791293', '15', 'Frutti di Mare', 'Meeresfrüchte\r\nKnoblauch\r\nOregano\r\nGoudakäse', '535c198491490'),
('535c1b86db909', '16', 'Nizza', 'Thunfisch\r\nZwiebeln\r\nOregano\r\nGoudakäse', '535c198491490'),
('535c1baf157b2', '17', 'Sizilia', 'Lachs\r\nSardellen\r\nThunfisch\r\nKrabben\r\nKnoblauch\r\nOregano\r\nGoudakäse', '535c198491490'),
('535c1c20035e1', '18', 'Tonno', 'Thunfisch\r\nPeperoni\r\nZwiebeln\r\nKnoblauch\r\nOregano\r\nGoudakäse', '535c198491490'),
('535c1c633b4c4', '19', 'Krabben', 'Krabben\r\nSpinat\r\nZwiebeln\r\nKnoblauch\r\nOregano\r\nGoudakäse', '535c198491490'),
('535c1caad1f75', '20', 'Hollandaise', 'Schinken\r\nBroccoli\r\nSpargel\r\nSauce Hollandaise\r\nOregano\r\nGoudakäse', '535c198491490'),
('535c1ced478da', '21', 'Quattro Formaggi', 'Mozzarella\r\nGorgonzola\r\nHirtenkäse\r\nGouda\r\nOregano', '535c198491490'),
('535c1f16ad568', '27', 'Vegetaria', 'Broccoli\r\nPaprika\r\nMais\r\nZwiebeln\r\nPeperoni\r\nfrische Tomaten\r\nOregano\r\nGoudakäse', '535c198491490'),
('535c1d8779f69', '22', 'Caprese', 'frische Tomaten\r\nMozzarella\r\nBasilikum\r\nOregano\r\nGoudakäse', '535c198491490'),
('535c1dc00fbd4', '23', 'Mexicana', 'Mais\r\nSalami\r\nSpeck\r\nPeperoni\r\nZwiebeln\r\nBohnen\r\nKäse\r\nOregano\r\nGoudakäse', '535c198491490'),
('535c1dfb9c98f', '24', 'Bolognese', 'Schinken\r\nSalami\r\nPeperoni\r\nPaprika\r\nwürzige Bolognesesoße\r\nOregano, Goudakäse', '535c198491490'),
('535c1e4275d38', '25', 'Gyros', 'Gyros\r\nZwiebeln\r\nHackfleisch\r\nOregano\r\nGoudakäse', '535c198491490'),
('535c2027e5173', '28', 'Italia', 'Hackfleisch\r\nMais\r\nZwiebeln\r\nBroccoli\r\nEi\r\nOregano\r\nGoudakäse', '535c198491490'),
('535c1eb2c654a', '26', 'Pollo', 'Hähnchenbrustfilet\r\nAnanas\r\nPaprika\r\nOregano\r\nGoudakäs', '535c198491490'),
('535c205111568', '29', 'Atlanta', 'Hackfleisch\r\nHähnchenbrustfilet\r\nBroccoli\r\nMais\r\nOregano\r\nGoudakäse', '535c198491490'),
('535c20808a9e2', '30', 'Griechisch', 'Gyros\r\nHähnchenbrustfilet\r\nMais\r\nZwiebeln\r\nOregano\r\nGoudakäse', '535c198491490'),
('535c20c05045f', '31', 'Standard', 'Hackfleisch\r\nZwiebeln\r\nSalami\r\nSchinken\r\nChampignons\r\nOregano\r\nGoudakäse', '535c198491490'),
('535c21025f2f2', '32', 'Mallorca', 'Schinken\r\nSalami\r\nChampignons\r\nArtischocken\r\nfrische Tomaten\r\nPaprika\r\nOregano\r\nGoudakäse', '535c198491490'),
('535c21380b681', '33', 'Express-Pizza', 'Schinken\r\nSalami\r\nThunfisch\r\nPaprika\r\nZwiebeln (scharf)\r\nOregano\r\nGoudakäse', '535c198491490'),
('535c2190649ea', '34', 'Tornado', 'Salami\r\nThunfisch\r\nPeperoni\r\nZwiebeln\r\nEi\r\nOregano\r\nGoudakäse', '535c198491490'),
('535c21ba2c248', '35', 'Americana', 'Hähnchenbrustfilet\r\nPaprika\r\nMais\r\nZwiebeln\r\nKnoblauch\r\nOregano\r\nGoudakäse', '535c198491490');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `pizza_services`
--

CREATE TABLE IF NOT EXISTS `pizza_services` (
  `id` varchar(13) CHARACTER SET utf8 NOT NULL,
  `name` varchar(255) CHARACTER SET utf8 NOT NULL,
  `address` varchar(255) CHARACTER SET utf8 NOT NULL,
  `phone` varchar(255) CHARACTER SET utf8 NOT NULL,
  `comment` varchar(255) CHARACTER SET utf8 NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `pizza_services`
--

INSERT INTO `pizza_services` (`id`, `name`, `address`, `phone`, `comment`) VALUES
('535c198491490', 'Test Pizzaservice', 'Musterstrasse 75, 12345 Musterstadt', '(0341) 123456789', 'Kundennummer: XXXX');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `queue`
--

CREATE TABLE IF NOT EXISTS `queue` (
  `id` varchar(13) NOT NULL,
  `songid` int(11) NOT NULL,
  `singer` varchar(255) NOT NULL,
  `timestamp` bigint(20) NOT NULL,
  `order` int(11) NOT NULL,
  `played` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `songlist`
--

CREATE TABLE IF NOT EXISTS `songlist` (
  `id` int(11) NOT NULL,
  `interpret` varchar(110) DEFAULT NULL,
  `title` varchar(125) DEFAULT NULL,
  `language` varchar(47) DEFAULT NULL
) ENGINE=MyISAM AUTO_INCREMENT=3687 DEFAULT CHARSET=utf8;

--
-- Daten für Tabelle `songlist`
--

INSERT INTO `songlist` (`id`, `interpret`, `title`, `language`) VALUES
(1, 'Testinterpret', 'Testsong', NULL);


--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `drinks_additives`
--
ALTER TABLE `drinks_additives`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `drinks_categories`
--
ALTER TABLE `drinks_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `drinks_drinks`
--
ALTER TABLE `drinks_drinks`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `infoscreen_ticker`
--
ALTER TABLE `infoscreen_ticker`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `infoscreen_timeline`
--
ALTER TABLE `infoscreen_timeline`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `module_highlights`
--
ALTER TABLE `module_highlights`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `module_text`
--
ALTER TABLE `module_text`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `options`
--
ALTER TABLE `options`
  ADD UNIQUE KEY `key` (`key`);

--
-- Indizes für die Tabelle `queue`
--
ALTER TABLE `queue`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `songlist`
--
ALTER TABLE `songlist`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD UNIQUE KEY `id_2` (`id`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `songlist`
--
ALTER TABLE `songlist`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
