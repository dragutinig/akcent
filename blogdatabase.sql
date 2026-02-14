-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 30, 2024 at 05:42 PM
-- Server version: 10.3.16-MariaDB
-- PHP Version: 7.1.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `blogdatabase`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(100) COLLATE utf8_slovenian_ci NOT NULL,
  `slug` varchar(100) COLLATE utf8_slovenian_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_slovenian_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `slug`, `created_at`, `updated_at`) VALUES
(3, 'plakari', 'plakari', '2024-12-24 17:01:41', '2024-12-24 17:01:41'),
(4, 'ogledala', 'ogledala', '2024-12-24 17:01:53', '2024-12-24 17:01:53'),
(5, 'kupatilski elementi', 'kupatilski-elementi', '2024-12-24 17:02:18', '2024-12-24 17:02:18'),
(6, 'edwedwedwe', 'etgegrg', '2024-12-25 21:52:20', '2024-12-25 21:52:20'),
(7, 'wdawdawd', 'awdadawd', '2024-12-29 10:01:23', '2024-12-29 10:01:23');

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `content` text COLLATE utf8_slovenian_ci NOT NULL,
  `status` enum('pending','approved','rejected') COLLATE utf8_slovenian_ci DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_slovenian_ci;

-- --------------------------------------------------------

--
-- Table structure for table `likes`
--

CREATE TABLE `likes` (
  `id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_slovenian_ci;

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `id` int(11) NOT NULL,
  `title` varchar(255) COLLATE utf8_slovenian_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8_slovenian_ci NOT NULL,
  `content` text COLLATE utf8_slovenian_ci NOT NULL,
  `featured_image` varchar(255) COLLATE utf8_slovenian_ci DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `status` enum('draft','published') COLLATE utf8_slovenian_ci DEFAULT 'draft',
  `published_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_slovenian_ci;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`id`, `title`, `slug`, `content`, `featured_image`, `user_id`, `category_id`, `status`, `published_at`, `created_at`, `updated_at`) VALUES
(51, 'Kako da Postavite Ciljeve', 'kako-da-postavite-ciljeve', '<div class=\"container\">\r\n<h1>Kako da Postavite Ciljeve</h1>\r\n<ol>\r\n<li>Defini&scaron;ite svoje ciljeve jasno. Da biste postigli bilo &scaron;ta u Å¾ivotu, morate znati &scaron;ta Å¾elite da postignete. Jasno definisani ciljevi su osnova za svaki uspeh.</li>\r\n<li>Postavite realistiÄne rokove. Nemojte sebi postavljati nerealne rokove. Razmislite koliko vremena vam je potrebno da postignete svoj cilj, uzimajuÄ‡i u obzir sve prepreke koje bi se mogle pojaviti.</li>\r\n<li>Razbijte ciljeve na manje, dostiÅ¾ne korake. Veliki ciljevi mogu biti zastra&scaron;ujuÄ‡i. Podelite ih na manje korake koje Ä‡ete moÄ‡i lak&scaron;e da ostvarite.</li>\r\n<li>Pratite napredak redovno. Redovno proveravajte svoj napredak i analizirajte da li idete u pravom smeru. Ako nije sve u redu, prilagodite svoj plan.</li>\r\n<li>UÄite iz gre&scaron;aka i prilagodite planove. Nikada ne prestajte da uÄite. Gre&scaron;ke su samo prilike za rast. Ako ne&scaron;to ne ide kako treba, nemojte odustati, veÄ‡ pronaÄ‘ite re&scaron;enje.</li>\r\n</ol>\r\n</div>\r\n<footer>&copy; 2024 Va&scaron; Blog</footer>', '../uploads/0-02-0a-4b1bda54348ee57187756c37729adcb2991020fa7b04adce43a4d97db8968605_539b7a269eae787b.jpg', 8, 3, 'published', '2024-12-28 19:03:05', '2024-12-28 19:03:05', '2024-12-28 19:03:05'),
(52, 'Uvod u Poslovni Uspeh', 'uvod-u-poslovni-uspeh', '<div class=\"container\">\r\n<h1>Ultimate Guide za Poslovni Uspeh</h1>\r\n<h2>Uvod u Poslovni Uspeh</h2>\r\n<p>Poslovni uspeh zahteva konstantno uÄenje i prilagoÄ‘avanje. Prvo, morate definisati &scaron;ta znaÄi uspeh za vas. Da biste postigli uspeh, vaÅ¾no je da imate jasan plan i strategiju.</p>\r\n<h2>1. Postavite Pravilne Ciljeve</h2>\r\n<p>Defini&scaron;ite jasne ciljeve i radite na njihovom ostvarivanju svakodnevno. Ciljevi vam daju smernicu i motivaciju da nastavite da se trudite. Bez ciljeva, lak&scaron;e je izgubiti fokus.</p>\r\n<h2>2. Razvijajte Ve&scaron;tine</h2>\r\n<p>Kontinuirano obrazovanje i razvoj ve&scaron;tina su kljuÄ uspeha. NauÄite nove tehnologije, metode i ve&scaron;tine koje Ä‡e vam omoguÄ‡iti da budete konkurentniji na trÅ¾i&scaron;tu rada.</p>\r\n<h2>3. Usmerite Energiju na Prave Prioritete</h2>\r\n<p>Fokusirajte se na aktivnosti koje direktno utiÄu na rast va&scaron;eg biznisa. NauÄite da prepoznate &scaron;ta je zaista vaÅ¾no i na &scaron;ta treba da tro&scaron;ite svoju energiju.</p>\r\n</div>\r\n<footer>&copy; 2024 Va&scaron; Blog</footer>', '../uploads/0-02-0a-60bccd7efe17f6990df8f21fab08c93f813a0760c2d58f890713fb3119c183ee_bd5a135592e55bc4.jpg', 8, 4, 'published', '2024-12-28 19:03:30', '2024-12-28 19:03:30', '2024-12-28 19:03:30'),
(54, '10 Najboljih Saveta za Uspeh', '10-najboljih-saveta-za-uspeh', '<div class=\"container\">\r\n<h1>10 Najboljih Saveta za Uspeh</h1>\r\n<ul>\r\n<li>Postavite ciljeve i radite na njima svakodnevno. Ciljevi su va&scaron;a mapa puta prema uspehu. Svakodnevni rad je kljuÄ za postizanje tih ciljeva.</li>\r\n<li>Obrazujte se kontinuirano. Ne prestajte da uÄite, jer se svet brzo menja. IstraÅ¾ujte nove tehnike i ve&scaron;tine koje Ä‡e vam pomoÄ‡i da napredujete.</li>\r\n<li>Budite organizovani i produktivni. Dobar plan i organizacija vremena su kljuÄ uspeha. NauÄite da postavite prioritete i efikasno koristite svoje resurse.</li>\r\n<li>Razvijajte socijalne ve&scaron;tine. Uspostavljanje jakih odnosa sa ljudima moÅ¾e vam pomoÄ‡i da napredujete u Å¾ivotu i poslu. NauÄite kako da se poveÅ¾ete sa drugima na dubljem nivou.</li>\r\n<li>Fokusirajte se na zdravlje i fiziÄku aktivnost. Zdravlje je osnovna komponenta uspeha. Uzmite vreme za fiziÄke aktivnosti i odmor kako biste se oseÄ‡ali energiÄno i motivisano.</li>\r\n<li>Radite sa strastima koje vas pokreÄ‡u. Strast prema onome &scaron;to radite moÅ¾e vas motivisati da se trudite jo&scaron; vi&scaron;e. Ako volite ono &scaron;to radite, uspeh dolazi prirodno.</li>\r\n<li>OkruÅ¾ite se pozitivnim ljudima. Ljudi sa kojima provodite vreme mogu uticati na va&scaron; mentalni sklop. OkruÅ¾ite se onima koji vas podstiÄu na rast i napredak.</li>\r\n<li>UÄite iz gre&scaron;aka. Svaka gre&scaron;ka je prilika za uÄenje. Ne bojte se da napravite gre&scaron;ku, veÄ‡ iskoristite to iskustvo da postanete bolji.</li>\r\n<li>Napravite ravnoteÅ¾u izmeÄ‘u posla i privatnog Å¾ivota. Balansiranje izmeÄ‘u poslovnog Å¾ivota i liÄnih interesa pomoÄ‡i Ä‡e vam da ostanete motivisani i smireni.</li>\r\n<li>Ostanite motivisani i disciplinovani. Uspostavite svakodnevne navike koje Ä‡e vas voditi ka uspehu. Disciplina je kljuÄ kada je motivacija slaba.</li>\r\n</ul>\r\n</div>\r\n<footer>&copy; 2024 Va&scaron; Blog</footer>', '../uploads/88715ebc1aca3a4714656fdf21e6ff88.jpg', 8, 5, 'published', '2024-12-28 19:04:36', '2024-12-28 19:04:36', '2024-12-28 19:04:36'),
(55, 'Studija SluÄaja: PoboljÅ¡anje Produktivnosti', 'studija-sluaja-poboljanje-produktivnosti', '<div class=\"container\">\r\n<h1>Studija SluÄaja: Pobolj&scaron;anje Produktivnosti</h1>\r\n<div class=\"section\">\r\n<h2>Problem</h2>\r\n<p>U ovoj studiji, istraÅ¾ili smo problem niske produktivnosti u firmama. Iako su zaposleni imali potrebne resurse, njihova produktivnost je bila na nivou ispod oÄekivanja. Provedena je analiza kako bi se prona&scaron;li uzroci ovog problema.</p>\r\n</div>\r\n<div class=\"section\">\r\n<h2>Re&scaron;enje</h2>\r\n<p>Na osnovu analize, odluÄeno je da se pobolj&scaron;a radno okruÅ¾enje i uvedu novi alati za efikasno upravljanje projektima. Kori&scaron;Ä‡eni su softveri za praÄ‡enje vremena i zadataka, &scaron;to je pomoglo zaposlenima da se fokusiraju na najvaÅ¾nije zadatke.</p>\r\n</div>\r\n<div class=\"section\">\r\n<h2>Rezultati</h2>\r\n<p>Nakon implementacije ovih strategija, produktivnost se poveÄ‡ala za 30%. Zaposleni su postali efikasniji, a timovi su se bolje povezivali, &scaron;to je rezultiralo brÅ¾im zavr&scaron;avanjem projekata i boljim timskim radom.</p>\r\n</div>\r\n</div>\r\n<footer>&copy; 2024 Va&scaron; Blog</footer>', '../uploads/krov.jpg', 8, 6, 'published', '2024-12-28 19:05:10', '2024-12-28 19:05:10', '2024-12-28 19:05:10'),
(56, 'dfsfdf', 'dfsfdf', '<div class=\"container\">\r\n<h1>10 Najboljih Saveta za Uspeh</h1>\r\n<ul>\r\n<li>Postavite ciljeve i radite na njima svakodnevno. Ciljevi su va&scaron;a mapa puta prema uspehu. Svakodnevni rad je kljuÄ za postizanje tih ciljeva.</li>\r\n<li>Obrazujte se kontinuirano. Ne prestajte da uÄite, jer se svet brzo menja. IstraÅ¾ujte nove tehnike i ve&scaron;tine koje Ä‡e vam pomoÄ‡i da napredujete.</li>\r\n<li>Budite organizovani i produktivni. Dobar plan i organizacija vremena su kljuÄ uspeha. NauÄite da postavite prioritete i efikasno koristite svoje resurse.</li>\r\n<li>Razvijajte socijalne ve&scaron;tine. Uspostavljanje jakih odnosa sa ljudima moÅ¾e vam pomoÄ‡i da napredujete u Å¾ivotu i poslu. NauÄite kako da se poveÅ¾ete sa drugima na dubljem nivou.</li>\r\n<li>Fokusirajte se na zdravlje i fiziÄku aktivnost. Zdravlje je osnovna komponenta uspeha. Uzmite vreme za fiziÄke aktivnosti i odmor kako biste se oseÄ‡ali energiÄno i motivisano.</li>\r\n<li>Radite sa strastima koje vas pokreÄ‡u. Strast prema onome &scaron;to radite moÅ¾e vas motivisati da se trudite jo&scaron; vi&scaron;e. Ako volite ono &scaron;to radite, uspeh dolazi prirodno.</li>\r\n<li>OkruÅ¾ite se pozitivnim ljudima. Ljudi sa kojima provodite vreme mogu uticati na va&scaron; mentalni sklop. OkruÅ¾ite se onima koji vas podstiÄu na rast i napredak.</li>\r\n<li>UÄite iz gre&scaron;aka. Svaka gre&scaron;ka je prilika za uÄenje. Ne bojte se da napravite gre&scaron;ku, veÄ‡ iskoristite to iskustvo da postanete bolji.</li>\r\n<li>Napravite ravnoteÅ¾u izmeÄ‘u posla i privatnog Å¾ivota. Balansiranje izmeÄ‘u poslovnog Å¾ivota i liÄnih interesa pomoÄ‡i Ä‡e vam da ostanete motivisani i smireni.</li>\r\n<li>Ostanite motivisani i disciplinovani. Uspostavite svakodnevne navike koje Ä‡e vas voditi ka uspehu. Disciplina je kljuÄ kada je motivacija slaba.</li>\r\n</ul>\r\n</div>\r\n<footer>&copy; 2024 Va&scaron; Blog</footer>', '../uploads/0-02-0a-60bccd7efe17f6990df8f21fab08c93f813a0760c2d58f890713fb3119c183ee_bd5a135592e55bc4.jpg', 8, 5, 'published', '2024-12-29 10:03:02', '2024-12-29 10:03:02', '2024-12-29 10:03:02'),
(57, 'dsfsdf', 'dsfsdf', '<div class=\"container\">\r\n<h1>10 Najboljih Saveta za Uspeh</h1>\r\n<h1 style=\"text-align: center;\">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;</h1>\r\n<p>Postavite ciljeve i radite na njima svakodnevno. Ciljevi su va&scaron;a mapa puta prema uspehu. Svakodnevni r</p>\r\n<p>ad je kljuÄ za postizanje tih ciljeva.</p>\r\n<ul>\r\n<li>Obrazujte se kontinuirano. Ne prestajte da uÄite, jer se svet brzo menja. IstraÅ¾ujte nove tehnike i ve&scaron;tine koje Ä‡e vam pomoÄ‡i da napredujete.</li>\r\n<li>Budite organizovani i produktivni. Dobar plan i organizacija vremena su kljuÄ uspeha. NauÄite da postavite prioritete i efikasno koristite svoje resurse.</li>\r\n<li>Razvijajte socijalne ve&scaron;tine. Uspostavljanje jakih odnosa sa ljudima moÅ¾e vam pomoÄ‡i da napredujete u Å¾ivotu i poslu. NauÄite kako da se poveÅ¾ete sa drugima na dubljem nivou.</li>\r\n<li>&nbsp;Fokusirajte se na zdravlje i fiziÄku aktivnost. Zdravlje je osnovna komponenta uspeha. Uzmite vreme za fiziÄke aktivnosti i odmor kako biste se oseÄ‡ali energiÄno i motivisano.</li>\r\n<li>Radite sa strastima koje vas pokreÄ‡u\r\n<h1 style=\"text-align: center;\"><img style=\"float: left;\" src=\"../uploads/0-02-0a-092fe4962661d3e3b4bd6b73883a4610bcb9c4942c57cebb5f80fea91cf4b21e_d2c6b23f30e924f6.jpg\" alt=\"cascascascsc\" width=\"325\" height=\"234\"></h1>\r\n. Strast prema onome &scaron;to radite moÅ¾e vas motivisati da se trudite jo&scaron; vi&scaron;e. Ako volite ono &scaron;to radite, uspeh dolazi prirodno.</li>\r\n<li>OkruÅ¾ite se pozitivnim ljudima. Ljudi sa kojima provodite vreme mogu uticati na va&scaron; mentalni sklop. OkruÅ¾ite se onima koji vas podstiÄu na rast i napredak.</li>\r\n<li>UÄite iz gre&scaron;aka. Svaka gre&scaron;ka je prilika za uÄenje. Ne bojte se da napravite gre&scaron;ku, veÄ‡ iskoristite to iskustvo da postanete bolji.</li>\r\n<li>Napravite ravnoteÅ¾u izmeÄ‘u posla i privatnog Å¾ivota. Balansiranje izmeÄ‘u poslovnog Å¾ivota i liÄnih interesa pomoÄ‡i Ä‡e vam da ostanete motivisani i smireni.</li>\r\n<li>Ostanite motivisani i disciplinovani. Uspostavite svakodnevne navike koje Ä‡e vas voditi ka uspehu. Disciplina je kljuÄ kada je motivacija slaba.</li>\r\n</ul>\r\n</div>\r\n<footer>&copy; 2024 Va&scaron; Blog</footer>', '../uploads/0-02-05-14d51a812d3f97301b6c13c9f9e89c751d22d0b87430e30e34b2114232394f32_9736d5860821dd09.jpg', 8, 3, 'published', '2024-12-29 10:07:50', '2024-12-29 10:07:50', '2024-12-29 10:07:50'),
(58, 'sdfsdfsd', 'sdfsdfsd', '<header class=\"blog-header\">\r\n<div class=\"container\">\r\n<h1>How to Choose the Perfect Mattress</h1>\r\n<p class=\"subtitle\">A step-by-step guide to ensure a great night\'s sleep</p>\r\n</div>\r\n</header><main class=\"blog-content container\">\r\n<article>\r\n<section><img class=\"featured-image\" src=\"../uploads/4-1 MASTER SOBA.jpg\" alt=\"Comfortable mattress in a bedroom\">\r\n<h2>Introduction</h2>\r\n<p>Choosing the right mattress can make a world of difference in your quality of sleep. In this guide, we will walk you through the factors to consider when selecting the perfect mattress.</p>\r\n</section>\r\n<section>\r\n<h2>1. Determine Your Budget</h2>\r\n<img class=\"section-image\" src=\"../uploads/4-1 MASTER SOBA.jpg\" alt=\"Calculating budget for a mattress\">\r\n<p>Before diving into the types of mattresses, it is essential to determine your budget. Mattresses range in price from affordable to luxury. Set a realistic budget to narrow your options.</p>\r\n</section>\r\n<section>\r\n<h2>2. Understand Mattress Types</h2>\r\n<img class=\"section-image\" src=\"../uploads/4-1 MASTER SOBA.jpg\" alt=\"Different types of mattresses\">\r\n<p>There are various types of mattresses, including memory foam, innerspring, hybrid, and latex. Each type offers unique benefits and drawbacks, so it is important to understand which suits your needs best.</p>\r\n</section>\r\n<section>\r\n<h2>3. Consider Your Sleeping Position</h2>\r\n<img class=\"section-image\" src=\"../uploads/4-1 MASTER SOBA.jpg\" alt=\"Sleeping positions and mattress support\">\r\n<p>Your preferred sleeping position plays a crucial role in mattress selection. Side sleepers, back sleepers, and stomach sleepers require different levels of firmness and support.</p>\r\n</section>\r\n<section>\r\n<h2>4. Test the Mattress</h2>\r\n<img class=\"section-image\" src=\"../uploads/4-1 MASTER SOBA.jpg\" alt=\"Testing mattress in a store\">\r\n<p>If possible, test the mattress in-store. Lie down in your typical sleeping position for at least 10 minutes to gauge comfort and support.</p>\r\n</section>\r\n<section>\r\n<h2>Conclusion</h2>\r\n<p>By considering your budget, mattress type, sleeping position, and testing options, you can confidently choose a mattress that suits your needs and ensures restful sleep.</p>\r\n<img class=\"conclusion-image\" src=\"../uploads/4-1 MASTER SOBA.jpg\" alt=\"Person sleeping peacefully on a mattress\"></section>\r\n</article>\r\n</main><footer class=\"blog-footer\">\r\n<div class=\"container\">\r\n<p>&copy; 2024 Your Blog Name. All Rights Reserved.</p>\r\n</div>\r\n</footer>', NULL, 8, 3, 'published', '2024-12-30 15:01:26', '2024-12-30 15:01:26', '2024-12-30 15:01:26'),
(59, 'scsdcvferfg', 'scsdcvferfg', '<header class=\"blog-header\">\r\n<div class=\"container\">\r\n<h1>How to Choose the Perfect Mattress</h1>\r\n<p class=\"subtitle\">A step-by-step guide to ensure a great night\'s sleep</p>\r\n</div>\r\n</header><main class=\"blog-content container\">\r\n<article>\r\n<section><img class=\"featured-image\" src=\"../uploads/4-1 MASTER SOBA.jpg\" alt=\"Comfortable mattress in a bedroom\">\r\n<h2>Introduction</h2>\r\n<p>Choosing the right mattress can make a world of difference in your quality of sleep. In this guide, we will walk you through the factors to consider when selecting the perfect mattress.</p>\r\n</section>\r\n<section>\r\n<h2>1. Determine Your Budget</h2>\r\n<img class=\"section-image\" src=\"../uploads/4-1 MASTER SOBA.jpg\" alt=\"Calculating budget for a mattress\">\r\n<p>Before diving into the types of mattresses, it is essential to determine your budget. Mattresses range in price from affordable to luxury. Set a realistic budget to narrow your options.</p>\r\n</section>\r\n<section>\r\n<h2>2. Understand Mattress Types</h2>\r\n<img class=\"section-image\" src=\"../uploads/4-1 MASTER SOBA.jpg\" alt=\"Different types of mattresses\">\r\n<p>There are various types of mattresses, including memory foam, innerspring, hybrid, and latex. Each type offers unique benefits and drawbacks, so it is important to understand which suits your needs best.</p>\r\n</section>\r\n<section>\r\n<h2>3. Consider Your Sleeping Position</h2>\r\n<img class=\"section-image\" src=\"../uploads/4-1 MASTER SOBA.jpg\" alt=\"Sleeping positions and mattress support\">\r\n<p>Your preferred sleeping position plays a crucial role in mattress selection. Side sleepers, back sleepers, and stomach sleepers require different levels of firmness and support.</p>\r\n</section>\r\n<section>\r\n<h2>4. Test the Mattress</h2>\r\n<img class=\"section-image\" src=\"../uploads/4-1 MASTER SOBA.jpg\" alt=\"Testing mattress in a store\">\r\n<p>If possible, test the mattress in-store. Lie down in your typical sleeping position for at least 10 minutes to gauge comfort and support.</p>\r\n</section>\r\n<section>\r\n<h2>Conclusion</h2>\r\n<p>By considering your budget, mattress type, sleeping position, and testing options, you can confidently choose a mattress that suits your needs and ensures restful sleep.</p>\r\n<img class=\"conclusion-image\" src=\"../uploads/4-1 MASTER SOBA.jpg\" alt=\"Person sleeping peacefully on a mattress\"></section>\r\n</article>\r\n</main><footer class=\"blog-footer\">\r\n<div class=\"container\">\r\n<p>&copy; 2024 Your Blog Name. All Rights Reserved.</p>\r\n</div>\r\n</footer>', NULL, 8, 6, 'published', '2024-12-30 15:02:42', '2024-12-30 15:02:42', '2024-12-30 15:02:42'),
(60, 'SDFRGEGERG', 'sdfrgegerg', '<header class=\"header\">\r\n<div class=\"header__utility-bar\"><img src=\"../../../images/bdi-40.svg\" alt=\"40 years of innovative design\"><form action=\"/search\" novalidate=\"\"><label class=\"screen-reader\" for=\"keywords\">Search</label> <input id=\"keywords\" name=\"q\" required=\"\" type=\"text\" placeholder=\"Search\"> <button type=\"submit\">Search</button></form></div>\r\n<nav class=\"header__nav\">\r\n<ul>\r\n<li><a href=\"../../../\">Home</a></li>\r\n<li><a href=\"../../../blog\">Blog</a></li>\r\n<li><a href=\"../../../about\">About</a></li>\r\n<li><a href=\"../../../contact\">Contact</a></li>\r\n</ul>\r\n</nav></header><main class=\"main-content\">\r\n<article class=\"blog-post\"><header>\r\n<h1>How to Choose the Perfect Mattress</h1>\r\n<p>A step-by-step guide to ensure a great night\'s sleep</p>\r\n</header><img class=\"featured-image\" src=\"../../../images/mattress-guide-header.jpg\" alt=\"Comfortable mattress in a bedroom\">\r\n<section>\r\n<h2>Introduction</h2>\r\n<p>Choosing the right mattress can make a world of difference in your quality of sleep. In this guide, we will walk you through the factors to consider when selecting the perfect mattress.</p>\r\n</section>\r\n<section>\r\n<h2>1. Determine Your Budget</h2>\r\n<img src=\"../uploads/1-2 DNEVNA SOBA.jpg\" alt=\"Calculating budget for a mattress\">\r\n<p>Before diving into the types of mattresses, it is essential to determine your budget. Mattresses range in price from affordable to luxury. Set a realistic budget to narrow your options.</p>\r\n</section>\r\n<section>\r\n<h2>2. Understand Mattress Types</h2>\r\n<img src=\"../uploads/1-2 DNEVNA SOBA.jpg\" alt=\"Different types of mattresses\">\r\n<p>There are various types of mattresses, including memory foam, innerspring, hybrid, and latex. Each type offers unique benefits and drawbacks, so it is important to understand which suits your needs best.</p>\r\n</section>\r\n<section>\r\n<h2>3. Consider Your Sleeping Position</h2>\r\n<img src=\"../uploads/1-2 DNEVNA SOBA.jpg\" alt=\"Sleeping positions and mattress support\">\r\n<p>Your preferred sleeping position plays a crucial role in mattress selection. Side sleepers, back sleepers, and stomach sleepers require different levels of firmness and support.</p>\r\n</section>\r\n<section>\r\n<h2>4. Test the Mattress</h2>\r\n<img src=\"../uploads/1-2 DNEVNA SOBA.jpg\" alt=\"Testing mattress in a store\">\r\n<p>If possible, test the mattress in-store. Lie down in your typical sleeping position for at least 10 minutes to gauge comfort and support.</p>\r\n</section>\r\n<footer>\r\n<h2>Conclusion</h2>\r\n<p>By considering your budget, mattress type, sleeping position, and testing options, you can confidently choose a mattress that suits your needs and ensures restful sleep.</p>\r\n<img src=\"../uploads/1-2 DNEVNA SOBA.jpg\" alt=\"Person sleeping peacefully on a mattress\"></footer></article>\r\n</main><footer class=\"footer\">\r\n<div class=\"footer__columns\">\r\n<div class=\"footer__column\">\r\n<h3>About Us</h3>\r\n<ul>\r\n<li><a href=\"../../../about\">Our Story</a></li>\r\n<li><a href=\"../../../careers\">Careers</a></li>\r\n</ul>\r\n</div>\r\n<div class=\"footer__column\">\r\n<h3>Help</h3>\r\n<ul>\r\n<li><a href=\"../../../faq\">FAQ</a></li>\r\n<li><a href=\"../../../contact\">Contact Us</a></li>\r\n</ul>\r\n</div>\r\n</div>\r\n<p>&copy; 2024 BDI Furniture. All Rights Reserved.</p>\r\n</footer>', '../uploads/4-1 MASTER SOBA.jpg', 8, 4, 'published', '2024-12-30 15:14:37', '2024-12-30 15:14:37', '2024-12-30 15:14:37'),
(61, 'xfbsfsf', 'xfbsfsf', '<main class=\"main-content\">\r\n<article class=\"blog-post\"><header>\r\n<h1>How to Choose the Perfect Mattress</h1>\r\n<p>Sleep struggles are more common than you might think. According to the CDC, 14.5% of adults have trouble falling asleep, while 17.8% struggle to stay asleep. On top of that, an estimated 50 to 70 million Americans suffer from chronic sleep disorders.</p>\r\n<p>While many factors contribute to restless nights, prioritizing comfort is one of the best ways to improve sleep. That\'s why so many consumers research and test different mattresses to find the perfect fit and feel. But with so many options out there, knowing where to start can feel overwhelming.</p>\r\n<p>In this guide, we will help you choose a comfortable mattress that complements your bed frame.</p>\r\n</header><img class=\"full-width-image\" src=\"../uploads/1-2 DNEVNA SOBA.jpg\" alt=\"Mattress image\">\r\n<section>\r\n<h2 class=\"section-title\">Understanding Mattress Types</h2>\r\n<p class=\"section-content\">Various types of mattresses are available on the market today, offering different benefits. Every year, new technologies and advancements also improve the comfort and versatility of mattresses. For that reason, it&rsquo;s worth taking the time to check out the newest models to ensure you are making an informed decision.</p>\r\n<div class=\"text-image-section\">\r\n<div class=\"text\">\r\n<ul>\r\n<li><strong>INNERSPRING:</strong> Traditional and widely available, they offer strong support and a cooler sleep experience.</li>\r\n<li><strong>MEMORY FOAM:</strong> Known for contouring and pressure relief, with newer models featuring cooling technologies to reduce heat retention.</li>\r\n<li><strong>LATEX:</strong> Durable and bouncy, made from natural or synthetic latex, with cooling and hypoallergenic properties.</li>\r\n<li><strong>HYBRID:</strong> Combines coils with foam or latex for a balance of support and comfort.</li>\r\n<li><strong>PILLOW TOP:</strong> Ultra-plush with a soft top layer over innerspring construction, typically at a higher price point.</li>\r\n<li><strong>ADJUSTABLE AIR:</strong> Customizable firmness using air chambers, offering flexibility but at a higher cost.</li>\r\n</ul>\r\n</div>\r\n<img src=\"../uploads/1-2 DNEVNA SOBA.jpg\" alt=\"Different types of mattresses\"></div>\r\n</section>\r\n<section>\r\n<h2 class=\"section-title\">Determine the Right Size</h2>\r\n<p class=\"section-content\">Before selecting a mattress, it&rsquo;s important to ensure it fits your bed frame properly. Mattress sizes vary, so you should choose one that best fits your bed frame and meets your needs. Here&rsquo;s a quick rundown of standard mattress sizes:</p>\r\n<div class=\"text-image-section\">\r\n<div class=\"text\">\r\n<ul>\r\n<li><strong>TWIN (38&rdquo; x 75&rdquo;):</strong> Ideal for smaller spaces or children&rsquo;s rooms.</li>\r\n<li><strong>TWIN XL (38&rdquo; x 80&rdquo;):</strong> Offers a slightly wider sleep surface for single beds.</li>\r\n<li><strong>FULL/DOUBLE (54&rdquo; x 75&rdquo;):</strong> Suitable for single sleepers who want extra space or for smaller guest rooms.</li>\r\n<li><strong>QUEEN (60&rdquo; x 80&rdquo;):</strong> A popular choice for couples or single sleepers who prefer more room.</li>\r\n<li><strong>KING (76&rdquo; x 80&rdquo;):</strong> Offers ample space for couples or those who share the bed with children or pets.</li>\r\n<li><strong>CALIFORNIA KING (72&rdquo; x 84&rdquo;):</strong> Longer but slightly narrower than a standard King, making it suitable for taller individuals.</li>\r\n</ul>\r\n</div>\r\n<img src=\"../uploads/1-2 DNEVNA SOBA.jpg\" alt=\"Mattress sizes\"></div>\r\n</section>\r\n<img class=\"full-width-image\" src=\"../uploads/1-2 DNEVNA SOBA.jpg\" alt=\"Large bed image\">\r\n<section>\r\n<div class=\"text-image-section\"><img src=\"../uploads/1-2 DNEVNA SOBA.jpg\" alt=\"Factors to consider\">\r\n<div class=\"text\">\r\n<h2 class=\"section-title\">Factors to Consider</h2>\r\n<p><strong>Sleep Position:</strong> Your sleep position greatly influences your mattress choice. Side sleepers often benefit from a softer mattress that conforms to their body, while back and stomach sleepers might need a firmer surface for proper spinal alignment.</p>\r\n<p><strong>Body Weight:</strong> Heavier individuals may require a firmer, more supportive mattress to avoid sinking too deeply, while lighter individuals might prefer a softer feel.</p>\r\n<p><strong>Temperature Sensitivity:</strong> If you tend to sleep hot, look for mattresses with cooling features or materials like gel-infused memory foam or breathable latex.</p>\r\n</div>\r\n</div>\r\n</section>\r\n<section>\r\n<h2 class=\"section-title\">Weigh in on Mattress Firmness</h2>\r\n<div class=\"text-image-section\">\r\n<div class=\"text\">\r\n<ul>\r\n<li><strong>SOFT:</strong> Ideal for side sleepers needing extra cushioning for hips and shoulders. Best for lighter individuals.</li>\r\n<li><strong>MEDIUM:</strong> Balanced feel, recommended for combination sleepers or those with back, hip, or shoulder pain.</li>\r\n<li><strong>FIRM:</strong> Best for back and stomach sleepers requiring extra support. Heavier individuals may also prefer firmer mattresses to prevent sagging.</li>\r\n</ul>\r\n</div>\r\n<img src=\"../uploads/1-2 DNEVNA SOBA.jpg\" alt=\"Mattress firmness levels\"></div>\r\n</section>\r\n<section>\r\n<h2 class=\"section-title\">Mattress Maintenance Tips</h2>\r\n<p class=\"section-content\">Once you&rsquo;ve found your perfect mattress, keeping it in excellent condition is essential for years of restful sleep. Here are some simple maintenance tips:</p>\r\n<ul>\r\n<li><strong>ROTATE REGULARLY:</strong> Rotate your mattress 180 degrees every 3-6 months to prevent uneven wear and sagging.</li>\r\n<li><strong>USE A PROTECTIVE COVER:</strong> A waterproof and breathable mattress protector keeps your mattress fresh by shielding it against spills, dust, and allergens.</li>\r\n<li><strong>CLEAN SPILLS IMMEDIATELY:</strong> Spot-clean with a mild detergent and allow it to air dry completely.</li>\r\n<li><strong>VACUUM OCCASIONALLY:</strong> Use a vacuum with an upholstery attachment to remove dust and debris.</li>\r\n<li><strong>SUPPORT MATTERS:</strong> Ensure your mattress is supported by a sturdy frame or foundation.</li>\r\n</ul>\r\n</section>\r\n</article>\r\n</main>', NULL, 8, 6, 'published', '2024-12-30 15:34:34', '2024-12-30 15:34:34', '2024-12-30 15:34:34'),
(62, 'sdfsdfsdf', 'sdfsdfsdf', '<main class=\"main-content\">\r\n<article class=\"blog-post\"><!-- Prikaz naslova i osnovnih informacija --><header>\r\n<h1><!--?php echo htmlspecialchars($post[\'title\']); ?--></h1>\r\n<p><!--?php echo htmlspecialchars($post[\'description\']); ?--></p>\r\n<p>Category: <!--?php echo htmlspecialchars($post[\'category_name\']); ?--> | Published on <!--?php echo htmlspecialchars($post[\'published_at\']); ?--></p>\r\n</header><!-- Prikaz glavne slike --> <img class=\"full-width-image\" src=\"../uploads/1-2 DNEVNA SOBA.jpg\" alt=\"Featured Image\"> <!-- Sekcija tipova duÅ¡eka -->\r\n<section>\r\n<h2 class=\"section-title\">Understanding Mattress Types</h2>\r\n<p class=\"section-content\"><!--?php echo htmlspecialchars($post[\'intro\']); ?--></p>\r\n<div class=\"text-image-section\">\r\n<div class=\"text\"><!--?php echo htmlspecialchars($post[\'content_section_1\']); ?--></div>\r\n<img src=\"../uploads/1-2 DNEVNA SOBA.jpg\" alt=\"Mattress Types\"></div>\r\n</section>\r\n<!-- Sekcija veliÄina duÅ¡eka -->\r\n<section>\r\n<h2 class=\"section-title\">Determine the Right Size</h2>\r\n<p class=\"section-content\"><!--?php echo htmlspecialchars($post[\'size_intro\']); ?--></p>\r\n<div class=\"text-image-section\">\r\n<div class=\"text\"><!--?php echo htmlspecialchars($post[\'content_section_2\']); ?--></div>\r\n<img src=\"../uploads/1-2 DNEVNA SOBA.jpg\" alt=\"Mattress Sizes\"></div>\r\n</section>\r\n<!-- Sekcija faktora za razmatranje -->\r\n<section>\r\n<div class=\"text-image-section\"><img src=\"../uploads/1-2 DNEVNA SOBA.jpg\" alt=\"Factors to consider\">\r\n<div class=\"text\">\r\n<h2 class=\"section-title\">Factors to Consider</h2>\r\n<!--?php echo htmlspecialchars($post[\'content_section_3\']); ?--></div>\r\n</div>\r\n</section>\r\n<!-- Sekcija odrÅ¾avanja -->\r\n<section>\r\n<h2 class=\"section-title\">Mattress Maintenance Tips</h2>\r\n<p class=\"section-content\"><!--?php echo htmlspecialchars($post[\'content_section_4\']); ?--></p>\r\n<ul>\r\n<li><strong>ROTATE REGULARLY:</strong> Rotate your mattress 180 degrees every 3-6 months to prevent uneven wear and sagging.</li>\r\n<li><strong>USE A PROTECTIVE COVER:</strong> A waterproof and breathable mattress protector keeps your mattress fresh by shielding it against spills, dust, and allergens.</li>\r\n<li><strong>CLEAN SPILLS IMMEDIATELY:</strong> Spot-clean with a mild detergent and allow it to air dry completely.</li>\r\n<li><strong>VACUUM OCCASIONALLY:</strong> Use a vacuum with an upholstery attachment to remove dust and debris.</li>\r\n<li><strong>SUPPORT MATTERS:</strong> Ensure your mattress is supported by a sturdy frame or foundation.</li>\r\n</ul>\r\n</section>\r\n</article>\r\n</main>', '../uploads/4-1 MASTER SOBA.jpg', 8, 3, 'published', '2024-12-30 15:58:44', '2024-12-30 15:58:44', '2024-12-30 15:58:44');

-- --------------------------------------------------------

--
-- Table structure for table `posttags`
--

CREATE TABLE `posttags` (
  `post_id` int(11) NOT NULL,
  `tag_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_slovenian_ci;

--
-- Dumping data for table `posttags`
--

INSERT INTO `posttags` (`post_id`, `tag_id`) VALUES
(51, 16),
(52, 17),
(54, 6),
(55, 18),
(56, 19),
(56, 20),
(56, 21),
(57, 22),
(58, 15),
(59, 23),
(60, 24),
(61, 15),
(62, 25);

-- --------------------------------------------------------

--
-- Table structure for table `sociallinks`
--

CREATE TABLE `sociallinks` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `platform` enum('facebook','twitter','instagram','linkedin') COLLATE utf8_slovenian_ci NOT NULL,
  `url` varchar(255) COLLATE utf8_slovenian_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_slovenian_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tags`
--

CREATE TABLE `tags` (
  `id` int(11) NOT NULL,
  `name` varchar(50) COLLATE utf8_slovenian_ci NOT NULL,
  `slug` varchar(50) COLLATE utf8_slovenian_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_slovenian_ci;

--
-- Dumping data for table `tags`
--

INSERT INTO `tags` (`id`, `name`, `slug`, `created_at`, `updated_at`) VALUES
(1, 'edwedwe', 'edwedwe', '2024-12-26 16:22:17', '2024-12-26 16:22:17'),
(2, 'tgrtgrt', 'tgrtgrt', '2024-12-26 16:22:17', '2024-12-26 16:22:17'),
(3, 'thrtgerg', 'thrtgerg', '2024-12-26 16:22:17', '2024-12-26 16:22:17'),
(4, 'gagi', 'gagi', '2024-12-26 16:31:53', '2024-12-26 16:31:53'),
(5, 'referf', 'referf', '2024-12-27 19:00:48', '2024-12-27 19:00:48'),
(6, 'rwfrewfr', 'rwfrewfr', '2024-12-27 21:36:50', '2024-12-27 21:36:50'),
(7, 'sdfsdf', 'sdfsdf', '2024-12-27 21:37:13', '2024-12-27 21:37:13'),
(8, 'ergrte', 'ergrte', '2024-12-27 21:37:37', '2024-12-27 21:37:37'),
(9, 'tyyrtyry', 'tyyrtyry', '2024-12-27 21:37:59', '2024-12-27 21:37:59'),
(10, 'fjgjfgj', 'fjgjfgj', '2024-12-27 21:38:16', '2024-12-27 21:38:16'),
(11, 'df', 'df', '2024-12-28 14:37:29', '2024-12-28 14:37:29'),
(12, 'sfsdf', 'sfsdf', '2024-12-28 14:37:29', '2024-12-28 14:37:29'),
(13, 'sdfsdfsd', 'sdfsdfsd', '2024-12-28 14:37:29', '2024-12-28 14:37:29'),
(14, 'sdfd', 'sdfd', '2024-12-28 14:37:29', '2024-12-28 14:37:29'),
(15, 'sdfsd', 'sdfsd', '2024-12-28 14:40:23', '2024-12-28 14:40:23'),
(16, 'ASDAD', 'asdad', '2024-12-28 19:03:05', '2024-12-28 19:03:05'),
(17, 'ewdwd', 'ewdwd', '2024-12-28 19:03:30', '2024-12-28 19:03:30'),
(18, 'dwedw', 'dwedw', '2024-12-28 19:05:10', '2024-12-28 19:05:10'),
(19, 'zczczsczs', 'zczczsczs', '2024-12-29 10:03:02', '2024-12-29 10:03:02'),
(20, 'dawda', 'dawda', '2024-12-29 10:03:02', '2024-12-29 10:03:02'),
(21, 'fafaw', 'fafaw', '2024-12-29 10:03:02', '2024-12-29 10:03:02'),
(22, 'dfsd', 'dfsd', '2024-12-29 10:07:50', '2024-12-29 10:07:50'),
(23, 'erferfrf', 'erferfrf', '2024-12-30 15:02:42', '2024-12-30 15:02:42'),
(24, 'erferf', 'erferf', '2024-12-30 15:14:37', '2024-12-30 15:14:37'),
(25, 'dsdsc', 'dsdsc', '2024-12-30 15:58:44', '2024-12-30 15:58:44');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) COLLATE utf8_slovenian_ci NOT NULL,
  `email` varchar(100) COLLATE utf8_slovenian_ci NOT NULL,
  `password_hash` varchar(255) COLLATE utf8_slovenian_ci NOT NULL,
  `role` enum('admin','author','user') COLLATE utf8_slovenian_ci DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `reset_token` varchar(64) COLLATE utf8_slovenian_ci DEFAULT NULL,
  `reset_expire_at` varchar(64) COLLATE utf8_slovenian_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_slovenian_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password_hash`, `role`, `created_at`, `updated_at`, `reset_token`, `reset_expire_at`) VALUES
(8, 'drgutin', 'ignjatovic.dragutin1992@gmail.com', '$2y$10$lla3G7KW9hr6T3XyiUZtwe61CnDBHhUdJVwilSpat89fDhK2eWnlm', 'admin', '2024-12-24 16:57:31', '2024-12-24 16:58:03', NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `post_id` (`post_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `likes`
--
ALTER TABLE `likes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `post_id` (`post_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `posttags`
--
ALTER TABLE `posttags`
  ADD PRIMARY KEY (`post_id`,`tag_id`),
  ADD KEY `tag_id` (`tag_id`);

--
-- Indexes for table `sociallinks`
--
ALTER TABLE `sociallinks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `tags`
--
ALTER TABLE `tags`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `likes`
--
ALTER TABLE `likes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;

--
-- AUTO_INCREMENT for table `sociallinks`
--
ALTER TABLE `sociallinks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tags`
--
ALTER TABLE `tags`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `likes`
--
ALTER TABLE `likes`
  ADD CONSTRAINT `likes_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `likes_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `posts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `posts_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `posttags`
--
ALTER TABLE `posttags`
  ADD CONSTRAINT `posttags_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `posttags_ibfk_2` FOREIGN KEY (`tag_id`) REFERENCES `tags` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `sociallinks`
--
ALTER TABLE `sociallinks`
  ADD CONSTRAINT `sociallinks_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
