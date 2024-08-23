-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Hostiteľ: 127.0.0.1
-- Čas generovania: Sun 15.Máj 2022, 20:19
-- Verzia serveru: 10.4.22-MariaDB
-- Verzia PHP: 8.0.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Databáza: `reklamne_studio_michal_pacesa`
--

DROP DATABASE IF EXISTS reklamne_studio_michal_pacesa;
CREATE DATABASE reklamne_studio_michal_pacesa;
USE reklamne_studio_michal_pacesa;

-- --------------------------------------------------------

--
-- Štruktúra tabuľky pre tabuľku `cenova_ponuka`
--

CREATE TABLE `cenova_ponuka` (
  `ID_cenovej_ponuky` int(10) UNSIGNED NOT NULL,
  `Cp_Datum_vytvorenia` date NOT NULL,
  `Cp_Datum_upravy` date NOT NULL,
  `Celkova_cena_s_DPH` double UNSIGNED NOT NULL,
  `Cp_Poznamka` varchar(200) DEFAULT NULL,
  `ID_objednavky` int(10) UNSIGNED NOT NULL,
  `ID_pouzivatela` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Sťahujem dáta pre tabuľku `cenova_ponuka`
--

INSERT INTO `cenova_ponuka` (`ID_cenovej_ponuky`, `Cp_Datum_vytvorenia`, `Cp_Datum_upravy`, `Celkova_cena_s_DPH`, `Cp_Poznamka`, `ID_objednavky`, `ID_pouzivatela`) VALUES
(20, '2022-05-15', '2022-05-15', 130, '', 52, 1),
(21, '2022-05-15', '2022-05-15', 150, '', 51, 1),
(22, '2022-05-15', '2022-05-15', 10, '', 46, 1),
(23, '2022-05-15', '2022-05-15', 10, '', 48, 1),
(24, '2022-05-15', '2022-05-15', 60, '', 25, 1),
(25, '2022-05-15', '2022-05-15', 40, '', 31, 1);

-- --------------------------------------------------------

--
-- Štruktúra tabuľky pre tabuľku `objednavka`
--

CREATE TABLE `objednavka` (
  `ID_objednavky` int(10) UNSIGNED NOT NULL,
  `Obj_Datum_vytvorenia` date NOT NULL,
  `Obj_Datum_upravy` date NOT NULL,
  `Obj_Nazov_firmy` varchar(50) DEFAULT NULL,
  `Obj_Meno` varchar(50) NOT NULL,
  `Obj_Priezvisko` varchar(50) NOT NULL,
  `Obj_Email` varchar(50) NOT NULL,
  `Obj_Telefon` varchar(20) NOT NULL,
  `Obj_Ulica_cislo_fakturacna` varchar(50) NOT NULL,
  `Obj_Mesto_fakturacna` varchar(50) NOT NULL,
  `Obj_PSC_fakturacna` varchar(5) NOT NULL,
  `Obj_Ulica_cislo_dodacia` varchar(50) DEFAULT NULL,
  `Obj_Mesto_dodacia` varchar(50) DEFAULT NULL,
  `Obj_PSC_dodacia` varchar(5) DEFAULT NULL,
  `Obj_ICO` varchar(8) DEFAULT NULL,
  `Obj_DIC` varchar(10) DEFAULT NULL,
  `Obj_IC_DPH` varchar(12) DEFAULT NULL,
  `Stav_objednavky` int(10) UNSIGNED DEFAULT NULL,
  `Obj_Poznamka` varchar(200) DEFAULT NULL,
  `ID_pouzivatela` int(10) UNSIGNED DEFAULT NULL,
  `ID_zakaznika` int(10) UNSIGNED NOT NULL,
  `ID_zamestnanca` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Sťahujem dáta pre tabuľku `objednavka`
--

INSERT INTO `objednavka` (`ID_objednavky`, `Obj_Datum_vytvorenia`, `Obj_Datum_upravy`, `Obj_Nazov_firmy`, `Obj_Meno`, `Obj_Priezvisko`, `Obj_Email`, `Obj_Telefon`, `Obj_Ulica_cislo_fakturacna`, `Obj_Mesto_fakturacna`, `Obj_PSC_fakturacna`, `Obj_Ulica_cislo_dodacia`, `Obj_Mesto_dodacia`, `Obj_PSC_dodacia`, `Obj_ICO`, `Obj_DIC`, `Obj_IC_DPH`, `Stav_objednavky`, `Obj_Poznamka`, `ID_pouzivatela`, `ID_zakaznika`, `ID_zamestnanca`) VALUES
(25, '2022-05-14', '2022-05-15', 'Kollár s.r.o', 'Roman', 'Kollár', 'KollarRoman@gmail.com', '+421903123456', 'Kvetná 10', 'Trnava', '91700', '', '', '', '12345678', '1234567890', '', 7, '', 1, 25, 1),
(26, '2022-05-14', '2022-05-15', '', 'Pavol', 'Biely', 'Biely@gmail.com', '+421904123444', 'Zelená 25', 'Trnava', '91700', '', '', '', '', '', '', 1, '', 1, 26, 4),
(27, '2022-05-14', '2022-05-15', '', 'Daniel', 'Smrek', 'Smrek@gmail.com', '+421903111225', 'Hlavná 5', 'Bratislava', '82108', 'Hlavná 10', 'Bratislava', '82108', '', '', '', 5, '', 1, 27, 5),
(29, '2022-05-14', '2022-05-15', 'Kollár s.r.o', 'Roman', 'Kollár', 'KollarRoman@gmail.com', '+421903123456', 'Kvetná 10', 'Trnava', '91700', '', '', '', '12345678', '1234567890', '', 2, '', 1, 25, 3),
(30, '2022-05-14', '2022-05-15', '', 'Pavol', 'Biely', 'Biely@gmail.com', '+421904123444', 'Zelená 25', 'Trnava', '91700', '', '', '', '', '', '', 6, '', 1, 26, 3),
(31, '2022-05-15', '2022-05-15', '', 'Roman', 'Cibuľka', 'cibulka@gmail.com', '+421903147258', 'Cibulková 25', 'Trenčín', '11122', '', '', '', '', '', '', 4, '', 1, 28, NULL),
(32, '2022-05-15', '2022-05-15', 'Kollár s.r.o', 'Roman', 'Kollár', 'KollarRoman@gmail.com', '+421903123456', 'Kvetná 10', 'Trnava', '91700', '', '', '', '12345678', '1234567890', '', 6, '', 1, 25, 3),
(33, '2022-05-15', '2022-05-15', '', 'Michal', 'Lacko', 'lacko45@gmail.com', '+421903123789', 'Lacková 11', 'Pezinok', '12345', 'Pošta Pezinok 1', 'Pezinok', '12345', '', '', '', 1, '', 1, 29, 5),
(34, '2022-05-15', '2022-05-15', '', 'Roman', 'Cibuľka', 'cibulka@gmail.com', '+421903147258', 'Cibulková 25', 'Trenčín', '11122', '', '', '', '', '', '', 1, '', 1, 28, NULL),
(35, '2022-05-15', '2022-05-15', '', 'Fillip', 'Kováčik', 'kovacik@gmail.com', '+421903602074', 'Kováčová 31', 'Bratislava', '99911', '', '', '', '78945612', '7894561230', '789456123000', 1, '', 1, 30, 3),
(39, '2022-05-15', '2022-05-15', '', 'Jakub', 'Dolina', 'dolina@gmail.com', '+42190360360', 'Dolinská 74', 'Senec', '11122', '', '', '', '', '', '', 1, '', 1, 32, 3),
(40, '2022-05-15', '2022-05-15', '', 'Šimon', 'Laco', 'Laco@gmail.com', '+421904055789', 'Lacová 32', 'Levice', '55522', '', '', '', '', '', '', 1, '', 1, 33, 1),
(41, '2022-05-15', '2022-05-15', '', 'Iveta', 'Repková', 'repkova@gmail.com', '+421903777111', 'Dlhá 12', 'Trenčín', '12121', '', '', '', '', '', '', 1, '', 1, 34, NULL),
(42, '2022-05-15', '2022-05-15', '', 'Iveta', 'Repková', 'repkova@gmail.com', '+421903777111', 'Dlhá 12', 'Trenčín', '12121', '', '', '', '', '', '', 1, '', 1, 34, NULL),
(43, '2022-05-15', '2022-05-15', '', 'Milan', 'Dlhý', 'dlhy@gmail.com', '+421903538862', 'Dlhá 82', 'Trnava', '91700', '', '', '', '', '', '', 1, '', 1, 35, NULL),
(44, '2022-05-15', '2022-05-15', '', 'Lukáš', 'Havel', 'havel@gmail.com', '+421903693523', 'Havlová 52', 'Nitra', '99912', '', '', '', '', '', '', 1, '', 1, 36, 1),
(45, '2022-05-15', '2022-05-15', '', 'Dalibor', 'Dvorský', 'dvorsky@gmail.com', '+421903526490', 'Dvorská 78', 'Nitra', '99912', '', '', '', '', '', '', 1, '', 1, 37, NULL),
(46, '2022-05-15', '2022-05-15', '', 'Dalibor', 'Dvorský', 'dvorsky@gmail.com', '+421903526490', 'Dvorská 78', 'Nitra', '99912', '', '', '', '', '', '', 1, '', 1, 37, NULL),
(47, '2022-05-15', '2022-05-15', '', 'Lukáš', 'Havel', 'havel@gmail.com', '+421903693523', 'Havlová 52', 'Nitra', '99912', '', '', '', '', '', '', 1, '', 1, 36, NULL),
(48, '2022-05-15', '2022-05-15', '', 'Pavol', 'Biely', 'Biely@gmail.com', '+421904123444', 'Zelená 25', 'Trnava', '91700', '', '', '', '', '', '', 1, '', 1, 32, NULL),
(49, '2022-05-15', '2022-05-15', '', 'Jakub', 'Dolina', 'dolina@gmail.com', '+42190360360', 'Dolinská 74', 'Senec', '11122', '', '', '', '', '', '', 1, '', 1, 32, 1),
(50, '2022-05-15', '2022-05-15', '', 'Juraj', 'Maliar', 'maliar@gmail.com', '+421903526411', 'Smreková 99', 'Žilina', '99911', '', '', '', '', '', '', 1, '', 1, 36, 1),
(51, '2022-05-15', '2022-05-15', '', 'Sofia', 'Molnárová', 'molnarova12@gmail.com', '+421904766020', 'Pavlíková 33', 'Skalica', '99922', '', '', '', '', '', '', 4, '', 1, 38, 4),
(52, '2022-05-15', '2022-05-15', '', 'Juraj', 'Maliar', 'maliar@gmail.com', '+421903526411', 'Smreková 99', 'Žilina', '99911', '', '', '', '', '', '', 2, '', 1, 39, 5);

-- --------------------------------------------------------

--
-- Štruktúra tabuľky pre tabuľku `prava`
--

CREATE TABLE `prava` (
  `ID_prava` int(10) UNSIGNED NOT NULL,
  `Rola` varchar(30) NOT NULL,
  `Zobraz_objednavky` tinyint(1) DEFAULT NULL,
  `Edit_objednavky` tinyint(1) DEFAULT NULL,
  `Zobraz_cenove_ponuky` tinyint(1) DEFAULT NULL,
  `Edit_cenove_ponuky` tinyint(1) DEFAULT NULL,
  `Zobraz_zamestnancov` tinyint(1) DEFAULT NULL,
  `Edit_zamestnancov` tinyint(1) DEFAULT NULL,
  `Zobraz_zakaznikov` tinyint(1) DEFAULT NULL,
  `Edit_zakaznikov` tinyint(1) DEFAULT NULL,
  `Zobraz_sluzby` tinyint(1) DEFAULT NULL,
  `Edit_sluzby` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Sťahujem dáta pre tabuľku `prava`
--

INSERT INTO `prava` (`ID_prava`, `Rola`, `Zobraz_objednavky`, `Edit_objednavky`, `Zobraz_cenove_ponuky`, `Edit_cenove_ponuky`, `Zobraz_zamestnancov`, `Edit_zamestnancov`, `Zobraz_zakaznikov`, `Edit_zakaznikov`, `Zobraz_sluzby`, `Edit_sluzby`) VALUES
(1, 'Systémový administrátor', 1, 1, 1, 1, 1, 1, 1, 1, 1, 1),
(2, 'Zamestnanec', 1, 1, 0, 0, 0, 0, 1, 1, 0, 0),
(3, 'Registrovaný zákazník', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- Štruktúra tabuľky pre tabuľku `prilohy`
--

CREATE TABLE `prilohy` (
  `ID_prilohy` int(10) UNSIGNED NOT NULL,
  `Nazov_suboru` varchar(50) NOT NULL,
  `ID_objednavky` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Sťahujem dáta pre tabuľku `prilohy`
--

INSERT INTO `prilohy` (`ID_prilohy`, `Nazov_suboru`, `ID_objednavky`) VALUES
(38, '5131-logo.png', 32);

-- --------------------------------------------------------

--
-- Štruktúra tabuľky pre tabuľku `registrovany_pouzivatel`
--

CREATE TABLE `registrovany_pouzivatel` (
  `ID_pouzivatela` int(10) UNSIGNED NOT NULL,
  `Pouz_Meno` varchar(50) NOT NULL,
  `Pouz_Priezvisko` varchar(50) NOT NULL,
  `Prihlasovacie_meno` varchar(50) NOT NULL,
  `Prihlasovacie_heslo` varchar(50) NOT NULL,
  `ID_prava` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Sťahujem dáta pre tabuľku `registrovany_pouzivatel`
--

INSERT INTO `registrovany_pouzivatel` (`ID_pouzivatela`, `Pouz_Meno`, `Pouz_Priezvisko`, `Prihlasovacie_meno`, `Prihlasovacie_heslo`, `ID_prava`) VALUES
(1, 'Michal', 'Pačesa', 'pacesa', '5f8c99666f1a7768c8603b47aff054b3883b7bd2', 1),
(3, 'Adam', 'Pekár', 'pekar', 'cbc7a59ceceac23101be4821512cadfd3ffa6cf0', 2),
(11, 'Roman', 'Kollár', 'kollar', '805b7efe750ac261c3e7926f86122fe5b7db3a9a', 3),
(12, 'Daniel', 'Smrek', 'smrek', '269e77fb024e64a607ec7711ad08b18594776f3c', 3),
(13, 'Jana', 'Nováková', 'novakova', '94e085e8fcf95161af1dca19b80fae23bd75adee', 2),
(14, 'Roman', 'Cibuľka', 'cibulka', 'b339cbd3717de6cbb34b9ec2602366284bc826e5', 3),
(15, 'Karol', 'Urban', 'urban', 'ba7a82a9819c5a1c19a8ff6c426d3365e2735678', 2),
(16, 'Michal', 'Lacko', 'lacko', 'eeddcfc2069e4ee004de774c46060e9275696a7c', 3),
(17, 'Fillip', 'Kováčik', 'kovacik', '5ee3a97ec55e6c2765c3e1fd984d7313243efdbe', 3);

-- --------------------------------------------------------

--
-- Štruktúra tabuľky pre tabuľku `sluzba`
--

CREATE TABLE `sluzba` (
  `ID_sluzby` int(10) UNSIGNED NOT NULL,
  `Nazov` varchar(100) NOT NULL,
  `Farby` varchar(200) DEFAULT NULL,
  `Dostupne_formaty` varchar(200) DEFAULT NULL,
  `Zobrazit_rozmery` tinyint(1) DEFAULT NULL,
  `Zobrazit_velkost` tinyint(1) DEFAULT NULL,
  `Zobrazovat_sluzbu` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Sťahujem dáta pre tabuľku `sluzba`
--

INSERT INTO `sluzba` (`ID_sluzby`, `Nazov`, `Farby`, `Dostupne_formaty`, `Zobrazit_rozmery`, `Zobrazit_velkost`, `Zobrazovat_sluzbu`) VALUES
(3, 'Pečiatky COLOP Printer s čiernym atramentom', 'bielo-čierna,\r\nbielo-červená,\r\nbielo-zelená,\r\nbielo-modrá,\r\nčierno-červená,\r\nčierno-zelená,\r\nčierno-modrá', '27x10 mm,38x14 mm,47x18 mm,59x18 mm', 0, 0, 1),
(4, 'Potlač trička vlastným motívom - bavlna', 'červená,modrá,čierna,biela,zelená,žltá,fialová,hnedá', '', 1, 1, 1),
(5, 'Potlač trička vlastným motívom - bio bavlna', 'červená,modrá,čierna,biela,zelená,žltá,fialová,hnedá', '', 1, 1, 1),
(6, 'Potlač trička vlastným motívom - polyester', 'červená,modrá,čierna,biela,zelená,žltá,fialová,hnedá', '', 1, 1, 1),
(9, 'Pečiatky COLOP Printer s modrým atramentom', 'bielo-čierna,\r\nbielo-červená,\r\nbielo-zelená,\r\nbielo-modrá,\r\nčierno-červená,\r\nčierno-zelená,\r\nčierno-modrá', '27x10 mm,38x14 mm,47x18 mm,59x18 mm', 0, 0, 1),
(10, 'Pečiatky COLOP Printer s červeným atramentom', 'bielo-čierna,\r\nbielo-červená,\r\nbielo-zelená,\r\nbielo-modrá,\r\nčierno-červená,\r\nčierno-zelená,\r\nčierno-modrá', '27x10 mm,38x14 mm,47x18 mm,59x18 mm', 0, 0, 1),
(11, 'Pečiatky COLOP Printer Green Line so zeleným atramentom', 'čierno-zelená', '38x14 mm,47x18 mm,59x18 mm', 0, 0, 1),
(12, 'Tlač vizitiek', '', '90x50mm', 0, 0, 1),
(13, 'Tlač samolepiek z matnej fólie', 'biela,zlatá,šedá,modrá,zelená,červená,strieborná,čierna,transparentná', '50x50mm,100x100mm,100x150mm,100x200mm', 1, 0, 1),
(14, 'Tlač samolepiek z lesklej fólie', 'biela,zlatá,šedá,modrá,zelená,červená,strieborná,čierna,transparentná', '50x50mm,100x100mm,100x150mm,100x200mm', 1, 0, 1),
(15, 'Tlač letáku s vlastným motívom', '', '', 1, 0, 1),
(16, 'Tlač plagátu s vlastným motívom', '', '', 1, 0, 1),
(19, 'Tlač banneru', '', '1500x1000mm,1000x1500,2000x1500mm,2000x1000mm', 1, 0, 1),
(20, 'Potlač keramických hrnčekov', 'biela,čierna,modrá,žltá,zelená,červená', '250ml,300ml,350ml', 0, 0, 1),
(21, 'Potlač vlastného textilu', '', '', 1, 0, 1),
(22, 'Potlač odznakov', 'biela,žltá,modrá,čierna,modrá,zlatá,strieborná', '37mm,56mm', 0, 0, 1),
(23, 'Tlač fotoobrazu', '', '300x450mm,500x700mm,300x900mm,800x800mm,450x300mm,700x500mm,900x300mm', 1, 0, 1),
(25, 'Potlač príveskov', 'biela,žltá,modrá,čierna,modrá,zlatá,strieborná', '37mm,56mm', 0, 0, 1),
(26, 'Potlač sviečok s vlastným motívom', 'oranžová,žltá,ružová,fialová,modrá', 'malá Ø7x7 cm,stredná Ø7x10cm,veľká Ø7x12cm', 0, 0, 1);

-- --------------------------------------------------------

--
-- Štruktúra tabuľky pre tabuľku `sluzby_na_cenovej_ponuke`
--

CREATE TABLE `sluzby_na_cenovej_ponuke` (
  `ID_sluzby_na_cenovej_ponuke` int(10) UNSIGNED NOT NULL,
  `Cena_za_jednotku` double UNSIGNED NOT NULL,
  `Cena_za_sluzbu` double UNSIGNED NOT NULL,
  `ID_cenovej_ponuky` int(10) UNSIGNED NOT NULL,
  `ID_sluzby_na_objednavke` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Sťahujem dáta pre tabuľku `sluzby_na_cenovej_ponuke`
--

INSERT INTO `sluzby_na_cenovej_ponuke` (`ID_sluzby_na_cenovej_ponuke`, `Cena_za_jednotku`, `Cena_za_sluzbu`, `ID_cenovej_ponuky`, `ID_sluzby_na_objednavke`) VALUES
(31, 10, 30, 20, 89),
(32, 20, 100, 20, 90),
(33, 10, 50, 21, 87),
(34, 20, 100, 21, 88),
(35, 10, 10, 22, 82),
(36, 10, 10, 23, 84),
(37, 20, 40, 24, 34),
(38, 20, 20, 24, 35),
(39, 20, 20, 25, 44),
(40, 20, 20, 25, 45);

-- --------------------------------------------------------

--
-- Štruktúra tabuľky pre tabuľku `sluzby_na_objednavke`
--

CREATE TABLE `sluzby_na_objednavke` (
  `ID_sluzby_na_objednavke` int(10) UNSIGNED NOT NULL,
  `Pocet_kusov` int(11) NOT NULL,
  `Rozmer_sirka` int(10) UNSIGNED DEFAULT NULL,
  `Rozmer_vyska` int(10) UNSIGNED DEFAULT NULL,
  `Farba` varchar(30) DEFAULT NULL,
  `Format` varchar(30) DEFAULT NULL,
  `Velkost` varchar(10) DEFAULT NULL,
  `ID_objednavky` int(10) UNSIGNED NOT NULL,
  `ID_sluzby` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Sťahujem dáta pre tabuľku `sluzby_na_objednavke`
--

INSERT INTO `sluzby_na_objednavke` (`ID_sluzby_na_objednavke`, `Pocet_kusov`, `Rozmer_sirka`, `Rozmer_vyska`, `Farba`, `Format`, `Velkost`, `ID_objednavky`, `ID_sluzby`) VALUES
(34, 2, 100, 100, 'čierna', '', 'L', 25, 4),
(35, 1, 150, 150, 'modrá', '', 'XL', 25, 5),
(36, 2, 50, 50, 'biela', '', 'XL', 26, 4),
(37, 2, 50, 50, 'biela', '', 'L', 26, 5),
(38, 1, 0, 0, '\r\nčierno-modrá', '47x18 mm', '', 27, 3),
(39, 1, 0, 0, '\r\nčierno-zelená', '59x18 mm', '', 27, 3),
(40, 100, 0, 0, 'biela', '90x50mm', '', 29, 12),
(41, 100, 0, 0, 'biela', 'A4', '', 29, 15),
(42, 3, 0, 0, 'strieborná', '50x50mm', '', 30, 13),
(43, 2, 0, 0, 'biela', '100x100mm', '', 30, 14),
(44, 1, 0, 0, 'modrá', '', 'L', 31, 4),
(45, 1, 100, 100, 'čierna', '', 'L', 31, 5),
(46, 8, 0, 0, '', '56mm', '', 32, 22),
(47, 5, 50, 50, 'modrá', '', 'L', 32, 4),
(48, 3, 50, 50, 'modrá', '', 'XL', 32, 5),
(49, 1, 0, 0, '', 'A3', '', 33, 16),
(50, 1, 0, 0, '', 'A4', '', 33, 15),
(51, 2, 0, 0, 'čierna', '', 'L', 33, 6),
(52, 1, 0, 0, 'bielo-čierna', '38x14 mm', '', 34, 10),
(53, 1, 0, 0, 'čierno-zelená', '59x18 mm', '', 34, 11),
(54, 1, 0, 0, 'modrá', '350ml', '', 34, 20),
(55, 2, 0, 0, '\r\nbielo-modrá', '47x18 mm', '', 35, 9),
(56, 3, 100, 100, 'modrá', '', 'XL', 35, 5),
(60, 2, 0, 0, 'zelená', '300ml', '', 39, 20),
(61, 10, 0, 0, 'strieborná', '100x100mm', '', 39, 13),
(62, 1, 0, 0, 'čierno-zelená', '38x14 mm', '', 39, 11),
(63, 20, 0, 0, '', '90x50mm', '', 40, 12),
(64, 10, 100, 100, 'biela', '', 'L', 40, 5),
(65, 3, 0, 0, 'čierno-zelená', '59x18 mm', '', 41, 11),
(66, 1, 0, 0, '', '300x900mm', '', 41, 23),
(67, 5, 0, 0, '', '56mm', '', 41, 22),
(68, 2, 0, 0, 'žltá', 'stredná Ø7x10cm', '', 42, 26),
(69, 2, 0, 0, 'ružová', 'veľká Ø7x12cm', '', 42, 26),
(70, 5, 0, 0, 'modrá', '56mm', '', 42, 22),
(71, 10, 0, 0, '', '90x50mm', '', 43, 12),
(72, 5, 0, 0, 'žltá', '350ml', '', 43, 20),
(73, 2, 0, 0, 'fialová', 'veľká Ø7x12cm', '', 43, 26),
(74, 5, 0, 0, 'biela', '350ml', '', 44, 20),
(75, 3, 0, 0, '\r\nčierno-červená', '59x18 mm', '', 44, 9),
(76, 2, 0, 0, 'čierno-zelená', '59x18 mm', '', 44, 11),
(77, 5, 100, 100, 'modrá', '', 'L', 44, 6),
(78, 1, 0, 0, 'strieborná', '100x150mm', '', 44, 14),
(79, 5, 0, 0, 'čierna', '56mm', '', 45, 25),
(80, 5, 0, 0, 'modrá', '350ml', '', 45, 20),
(81, 3, 0, 0, '\r\nbielo-modrá', '59x18 mm', '', 45, 9),
(82, 1, 0, 0, '\r\nbielo-modrá', '38x14 mm', '', 46, 9),
(83, 2, 0, 0, '', '1000x1500', '', 47, 19),
(84, 1, 0, 0, 'čierno-zelená', '47x18 mm', '', 48, 11),
(85, 1, 0, 0, '\r\nbielo-červená', '38x14 mm', '', 49, 9),
(86, 1, 0, 0, 'ružová', 'stredná Ø7x10cm', '', 50, 26),
(87, 5, 0, 0, 'čierno-zelená', '38x14 mm', '', 51, 11),
(88, 5, 150, 150, 'žltá', '', 'XL', 51, 4),
(89, 3, 0, 0, 'čierno-zelená', '38x14 mm', '', 52, 11),
(90, 5, 0, 0, 'červená', '', 'XL', 52, 5);

-- --------------------------------------------------------

--
-- Štruktúra tabuľky pre tabuľku `zakaznik`
--

CREATE TABLE `zakaznik` (
  `ID_zakaznika` int(10) UNSIGNED NOT NULL,
  `Zak_Nazov_firmy` varchar(50) DEFAULT NULL,
  `Zak_Meno` varchar(50) NOT NULL,
  `Zak_Priezvisko` varchar(50) NOT NULL,
  `Zak_Email` varchar(50) NOT NULL,
  `Zak_Telefon` varchar(20) NOT NULL,
  `Zak_Ulica_cislo_fakturacna` varchar(50) NOT NULL,
  `Zak_Mesto_fakturacna` varchar(50) NOT NULL,
  `Zak_PSC_fakturacna` varchar(5) NOT NULL,
  `Zak_Ulica_cislo_dodacia` varchar(50) DEFAULT NULL,
  `Zak_Mesto_dodacia` varchar(50) DEFAULT NULL,
  `Zak_PSC_dodacia` varchar(5) DEFAULT NULL,
  `Zak_ICO` varchar(8) DEFAULT NULL,
  `Zak_DIC` varchar(10) DEFAULT NULL,
  `Zak_IC_DPH` varchar(12) DEFAULT NULL,
  `Zak_Nazov_banky` varchar(50) DEFAULT NULL,
  `Zak_Cislo_uctu` varchar(30) DEFAULT NULL,
  `Zak_Poznamka` varchar(200) DEFAULT NULL,
  `ID_pouzivatela` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Sťahujem dáta pre tabuľku `zakaznik`
--

INSERT INTO `zakaznik` (`ID_zakaznika`, `Zak_Nazov_firmy`, `Zak_Meno`, `Zak_Priezvisko`, `Zak_Email`, `Zak_Telefon`, `Zak_Ulica_cislo_fakturacna`, `Zak_Mesto_fakturacna`, `Zak_PSC_fakturacna`, `Zak_Ulica_cislo_dodacia`, `Zak_Mesto_dodacia`, `Zak_PSC_dodacia`, `Zak_ICO`, `Zak_DIC`, `Zak_IC_DPH`, `Zak_Nazov_banky`, `Zak_Cislo_uctu`, `Zak_Poznamka`, `ID_pouzivatela`) VALUES
(25, 'Kollár s.r.o', 'Roman', 'Kollár', 'KollarRoman@gmail.com', '+421903123456', 'Kvetná 10', 'Trnava', '91700', '', '', '', '12345678', '1234567890', '', '', '', '', 11),
(26, '', 'Pavol', 'Biely', 'Biely@gmail.com', '+421904123444', 'Zelená 25', 'Trnava', '91700', '', '', '', '', '', '', NULL, NULL, '', NULL),
(27, '', 'Daniel', 'Smrek', 'Smrek@gmail.com', '+421903111225', 'Hlavná 5', 'Bratislava', '82108', 'Hlavná 10', 'Bratislava', '82108', '', '', '', '', '', '', 12),
(28, '', 'Roman', 'Cibuľka', 'cibulka@gmail.com', '+421903147258', 'Cibulková 25', 'Trenčín', '11122', '', '', '', '', '', '', '', '', '', 14),
(29, '', 'Michal', 'Lacko', 'lacko45@gmail.com', '+421903123789', 'Lacková 11', 'Pezinok', '12345', 'Pošta Pezinok 1', 'Pezinok', '12345', '', '', '', 'SLSP', 'SK90 1100 0000 0000 0000 0002', '', 16),
(30, '', 'Fillip', 'Kováčik', 'kovacik@gmail.com', '+421903602074', 'Kováčová 31', 'Bratislava', '99911', '', '', '', '78945612', '7894561230', '789456123000', '', '', '', 17),
(32, '', 'Jakub', 'Dolina', 'dolina@gmail.com', '+42190360360', 'Dolinská 74', 'Senec', '11122', '', '', '', '', '', '', '', '', '', NULL),
(33, '', 'Šimon', 'Laco', 'Laco@gmail.com', '+421904055789', 'Lacová 32', 'Levice', '55522', '', '', '', '', '', '', NULL, NULL, '', NULL),
(34, '', 'Iveta', 'Repková', 'repkova@gmail.com', '+421903777111', 'Dlhá 12', 'Trenčín', '12121', '', '', '', '', '', '', NULL, NULL, '', NULL),
(35, '', 'Milan', 'Dlhý', 'dlhy@gmail.com', '+421903538862', 'Dlhá 82', 'Trnava', '91700', '', '', '', '', '', '', NULL, NULL, '', NULL),
(36, '', 'Lukáš', 'Havel', 'havel@gmail.com', '+421903693523', 'Havlová 52', 'Nitra', '99912', '', '', '', '', '', '', NULL, NULL, '', NULL),
(37, '', 'Dalibor', 'Dvorský', 'dvorsky@gmail.com', '+421903526490', 'Dvorská 78', 'Nitra', '99912', '', '', '', '', '', '', NULL, NULL, '', NULL),
(38, '', 'Sofia', 'Molnárová', 'molnarova@gmail.com', '+421904766020', 'Pavlíková 31', 'Skalica', '99922', '', '', '', '', '', '', NULL, NULL, '', NULL),
(39, '', 'Juraj', 'Maliar', 'maliar@gmail.com', '+421903526411', 'Smreková 99', 'Žilina', '99911', '', '', '', '', '', '', NULL, NULL, '', NULL);

-- --------------------------------------------------------

--
-- Štruktúra tabuľky pre tabuľku `zamestnanec`
--

CREATE TABLE `zamestnanec` (
  `ID_zamestnanca` int(10) UNSIGNED NOT NULL,
  `Zam_Meno` varchar(20) NOT NULL,
  `Zam_Priezvisko` varchar(50) NOT NULL,
  `Zam_Datum_narodenia` date NOT NULL,
  `Zam_Email` varchar(50) NOT NULL,
  `Zam_Telefon` varchar(20) NOT NULL,
  `Zam_Ulica_cislo` varchar(50) NOT NULL,
  `Zam_Mesto` varchar(50) NOT NULL,
  `Zam_PSC` varchar(5) NOT NULL,
  `Druh_zamestnania` int(10) UNSIGNED NOT NULL,
  `Uvazok` int(10) UNSIGNED NOT NULL,
  `Pracovna_pozicia` varchar(30) DEFAULT NULL,
  `Datum_nastupu` date DEFAULT NULL,
  `Zam_Nazov_firmy` varchar(50) DEFAULT NULL,
  `Zam_ICO` varchar(8) DEFAULT NULL,
  `Zam_DIC` varchar(10) DEFAULT NULL,
  `Zam_IC_DPH` varchar(12) DEFAULT NULL,
  `Zam_Nazov_banky` varchar(50) DEFAULT NULL,
  `Zam_Cislo_uctu` varchar(30) DEFAULT NULL,
  `Zam_Poznamka` varchar(200) DEFAULT NULL,
  `ID_pouzivatela` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Sťahujem dáta pre tabuľku `zamestnanec`
--

INSERT INTO `zamestnanec` (`ID_zamestnanca`, `Zam_Meno`, `Zam_Priezvisko`, `Zam_Datum_narodenia`, `Zam_Email`, `Zam_Telefon`, `Zam_Ulica_cislo`, `Zam_Mesto`, `Zam_PSC`, `Druh_zamestnania`, `Uvazok`, `Pracovna_pozicia`, `Datum_nastupu`, `Zam_Nazov_firmy`, `Zam_ICO`, `Zam_DIC`, `Zam_IC_DPH`, `Zam_Nazov_banky`, `Zam_Cislo_uctu`, `Zam_Poznamka`, `ID_pouzivatela`) VALUES
(1, 'Michal', 'Pačesa', '2000-06-22', 'michal.pacesa4@gmail.com', '+421903111222', 'Hlavná 60', 'Trnava', '91700', 1, 40, 'Systémový administrátor', '2022-01-01', '', '', '', '', '', '', '', 1),
(3, 'Adam', 'Pekár', '1988-05-12', 'Pekar@gmail.com', '+421903123456', 'Hlavná 32', 'Bratislava', '91700', 1, 40, 'Grafik', '2021-05-05', '', '', '', '', '', '', '', 3),
(4, 'Jana', 'Nováková', '1997-05-01', 'novakova@gmail.com', '+421903555222', 'Hlavná 8', 'Galanta', '92401', 1, 20, 'Pokladníčka', '2022-05-01', '', '', '', '', '', '', '', 13),
(5, 'Karol', 'Urban', '1993-05-07', 'urban@gmail.com', '+421903666333', 'Tulipánová 20', 'Senec', '11122', 1, 40, '', '2022-05-02', '', '', '', '', '', '', '', 15);

--
-- Kľúče pre exportované tabuľky
--

--
-- Indexy pre tabuľku `cenova_ponuka`
--
ALTER TABLE `cenova_ponuka`
  ADD PRIMARY KEY (`ID_cenovej_ponuky`),
  ADD KEY `IXFK_Cenova_ponuka_Objednavka` (`ID_objednavky`),
  ADD KEY `IXFK_Cenova_ponuka_Registrovany_Pouzivatel` (`ID_pouzivatela`);

--
-- Indexy pre tabuľku `objednavka`
--
ALTER TABLE `objednavka`
  ADD PRIMARY KEY (`ID_objednavky`),
  ADD KEY `IXFK_Objednavka_Registrovany_Pouzivatel` (`ID_pouzivatela`),
  ADD KEY `IXFK_Objednavka_Zakaznik` (`ID_zakaznika`),
  ADD KEY `IXFK_Objednavka_Zamestnanec` (`ID_zamestnanca`);

--
-- Indexy pre tabuľku `prava`
--
ALTER TABLE `prava`
  ADD PRIMARY KEY (`ID_prava`);

--
-- Indexy pre tabuľku `prilohy`
--
ALTER TABLE `prilohy`
  ADD PRIMARY KEY (`ID_prilohy`),
  ADD KEY `IXFK_Prilohy_Objednavka` (`ID_objednavky`);

--
-- Indexy pre tabuľku `registrovany_pouzivatel`
--
ALTER TABLE `registrovany_pouzivatel`
  ADD PRIMARY KEY (`ID_pouzivatela`),
  ADD KEY `IXFK_Registrovany_Pouzivatel_Prava` (`ID_prava`);

--
-- Indexy pre tabuľku `sluzba`
--
ALTER TABLE `sluzba`
  ADD PRIMARY KEY (`ID_sluzby`);

--
-- Indexy pre tabuľku `sluzby_na_cenovej_ponuke`
--
ALTER TABLE `sluzby_na_cenovej_ponuke`
  ADD PRIMARY KEY (`ID_sluzby_na_cenovej_ponuke`),
  ADD KEY `IXFK_Sluzby_na_cenovej_ponuke_Cenova_ponuka` (`ID_cenovej_ponuky`),
  ADD KEY `IXFK_Sluzby_na_cenovej_ponuke_Sluzby_na_objednavke` (`ID_sluzby_na_objednavke`);

--
-- Indexy pre tabuľku `sluzby_na_objednavke`
--
ALTER TABLE `sluzby_na_objednavke`
  ADD PRIMARY KEY (`ID_sluzby_na_objednavke`),
  ADD KEY `IXFK_Sluzby_na_objednavke_Objednavka` (`ID_objednavky`),
  ADD KEY `IXFK_Sluzby_na_objednavke_Sluzba` (`ID_sluzby`);

--
-- Indexy pre tabuľku `zakaznik`
--
ALTER TABLE `zakaznik`
  ADD PRIMARY KEY (`ID_zakaznika`),
  ADD KEY `IXFK_Zakaznik_Registrovany_Pouzivatel` (`ID_pouzivatela`);

--
-- Indexy pre tabuľku `zamestnanec`
--
ALTER TABLE `zamestnanec`
  ADD PRIMARY KEY (`ID_zamestnanca`),
  ADD KEY `IXFK_Zamestnanec_Registrovany_Pouzivatel` (`ID_pouzivatela`);

--
-- AUTO_INCREMENT pre exportované tabuľky
--

--
-- AUTO_INCREMENT pre tabuľku `cenova_ponuka`
--
ALTER TABLE `cenova_ponuka`
  MODIFY `ID_cenovej_ponuky` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT pre tabuľku `objednavka`
--
ALTER TABLE `objednavka`
  MODIFY `ID_objednavky` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT pre tabuľku `prava`
--
ALTER TABLE `prava`
  MODIFY `ID_prava` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pre tabuľku `prilohy`
--
ALTER TABLE `prilohy`
  MODIFY `ID_prilohy` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT pre tabuľku `registrovany_pouzivatel`
--
ALTER TABLE `registrovany_pouzivatel`
  MODIFY `ID_pouzivatela` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT pre tabuľku `sluzba`
--
ALTER TABLE `sluzba`
  MODIFY `ID_sluzby` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT pre tabuľku `sluzby_na_cenovej_ponuke`
--
ALTER TABLE `sluzby_na_cenovej_ponuke`
  MODIFY `ID_sluzby_na_cenovej_ponuke` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT pre tabuľku `sluzby_na_objednavke`
--
ALTER TABLE `sluzby_na_objednavke`
  MODIFY `ID_sluzby_na_objednavke` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=91;

--
-- AUTO_INCREMENT pre tabuľku `zakaznik`
--
ALTER TABLE `zakaznik`
  MODIFY `ID_zakaznika` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT pre tabuľku `zamestnanec`
--
ALTER TABLE `zamestnanec`
  MODIFY `ID_zamestnanca` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Obmedzenie pre exportované tabuľky
--

--
-- Obmedzenie pre tabuľku `cenova_ponuka`
--
ALTER TABLE `cenova_ponuka`
  ADD CONSTRAINT `FK_Cenova_ponuka_Objednavka` FOREIGN KEY (`ID_objednavky`) REFERENCES `objednavka` (`ID_objednavky`) ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_Cenova_ponuka_Registrovany_Pouzivatel` FOREIGN KEY (`ID_pouzivatela`) REFERENCES `registrovany_pouzivatel` (`ID_pouzivatela`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Obmedzenie pre tabuľku `objednavka`
--
ALTER TABLE `objednavka`
  ADD CONSTRAINT `FK_Objednavka_Registrovany_Pouzivatel` FOREIGN KEY (`ID_pouzivatela`) REFERENCES `registrovany_pouzivatel` (`ID_pouzivatela`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_Objednavka_Zakaznik` FOREIGN KEY (`ID_zakaznika`) REFERENCES `zakaznik` (`ID_zakaznika`) ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_Objednavka_Zamestnanec` FOREIGN KEY (`ID_zamestnanca`) REFERENCES `zamestnanec` (`ID_zamestnanca`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Obmedzenie pre tabuľku `prilohy`
--
ALTER TABLE `prilohy`
  ADD CONSTRAINT `FK_Prilohy_Objednavka` FOREIGN KEY (`ID_objednavky`) REFERENCES `objednavka` (`ID_objednavky`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Obmedzenie pre tabuľku `registrovany_pouzivatel`
--
ALTER TABLE `registrovany_pouzivatel`
  ADD CONSTRAINT `FK_Registrovany_Pouzivatel_Prava` FOREIGN KEY (`ID_prava`) REFERENCES `prava` (`ID_prava`) ON UPDATE CASCADE;

--
-- Obmedzenie pre tabuľku `sluzby_na_cenovej_ponuke`
--
ALTER TABLE `sluzby_na_cenovej_ponuke`
  ADD CONSTRAINT `FK_Sluzby_na_cenovej_ponuke_Cenova_ponuka` FOREIGN KEY (`ID_cenovej_ponuky`) REFERENCES `cenova_ponuka` (`ID_cenovej_ponuky`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_Sluzby_na_cenovej_ponuke_Sluzby_na_objednavke` FOREIGN KEY (`ID_sluzby_na_objednavke`) REFERENCES `sluzby_na_objednavke` (`ID_sluzby_na_objednavke`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Obmedzenie pre tabuľku `sluzby_na_objednavke`
--
ALTER TABLE `sluzby_na_objednavke`
  ADD CONSTRAINT `FK_Sluzby_na_objednavke_Objednavka` FOREIGN KEY (`ID_objednavky`) REFERENCES `objednavka` (`ID_objednavky`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_Sluzby_na_objednavke_Sluzba` FOREIGN KEY (`ID_sluzby`) REFERENCES `sluzba` (`ID_sluzby`) ON UPDATE CASCADE;

--
-- Obmedzenie pre tabuľku `zakaznik`
--
ALTER TABLE `zakaznik`
  ADD CONSTRAINT `FK_Zakaznik_Registrovany_Pouzivatel` FOREIGN KEY (`ID_pouzivatela`) REFERENCES `registrovany_pouzivatel` (`ID_pouzivatela`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Obmedzenie pre tabuľku `zamestnanec`
--
ALTER TABLE `zamestnanec`
  ADD CONSTRAINT `FK_Zamestnanec_Registrovany_Pouzivatel` FOREIGN KEY (`ID_pouzivatela`) REFERENCES `registrovany_pouzivatel` (`ID_pouzivatela`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
