-- phpMyAdmin SQL Dump
-- version 3.4.10.1deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Apr 12, 2015 at 03:48 PM
-- Server version: 5.5.37
-- PHP Version: 5.3.10-1ubuntu3.14

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `nirbuydb`
--

-- --------------------------------------------------------

--
-- Table structure for table `content_pages`
--

CREATE TABLE IF NOT EXISTS `content_pages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `add_datetime` datetime NOT NULL,
  `latest_update` datetime NOT NULL,
  `meta_keywords` text NOT NULL,
  `meta_description` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `content_pages`
--

INSERT INTO `content_pages` (`id`, `title`, `slug`, `description`, `add_datetime`, `latest_update`, `meta_keywords`, `meta_description`) VALUES
(1, 'welcome page 2', 'welcome-page-2', '<div class="inner_wrapper">\r\n	<div class="container">\r\n		<div class="row columns	">\r\n			<div class="span3">\r\n				<span class="col_title blue_t">Business details </span>\r\n				<p>\r\n					hello-hello-hello sample text</p>\r\n			</div>\r\n			<div class="span3">\r\n				<span class="col_title green_t">Business details </span>\r\n				<p>\r\n					hello-hello-hello sample text 2</p>\r\n			</div>\r\n			<div class="span3">\r\n				<span class="col_title yellow_t">Business details </span>\r\n				<p>\r\n					hello-hello-hello sample text 3</p>\r\n			</div>\r\n			<div class="span3">\r\n				<span class="col_title red_t">Business details </span>\r\n				<p>\r\n					<span class="col_title red_t">Optional. On payment</span></p>\r\n				<p>\r\n					hello-hello-hello sample text 4</p>\r\n				<p>\r\n					&nbsp;</p>\r\n				<p>\r\n					&nbsp;</p>\r\n			</div>\r\n			<div class="row title_list">\r\n				<div class="span9">\r\n					&nbsp;</div>\r\n				<div class="span3" style="text-align:center;">\r\n					<button>Start</button><span>Click to above button to proceed</span></div>\r\n			</div>\r\n		</div>\r\n		<div class="row list_content">\r\n			<div class="span12 ">\r\n				<div class="row title_list">\r\n					<div class="span9">\r\n						some orange text</div>\r\n					<div class="span3" style="text-align:center;">\r\n						&nbsp;</div>\r\n				</div>\r\n				<div class="row list">\r\n					<ol>\r\n						<li>\r\n							<span>Updating ypur catalogue gets rewarded </span>\r\n							<p>\r\n								Updating ypur catalogue gets rewardedUpdating ypur catalogue gets rewardedUpdating ypur catalogue gets rewarded</p>\r\n						</li>\r\n						<li>\r\n							<span>Updating ypur catalogue gets rewarded </span>\r\n							<p>\r\n								Updating ypur catalogue gets rewardedUpdating ypur catalogue gets rewardedUpdating ypur catalogue gets rewarded</p>\r\n						</li>\r\n						<li>\r\n							<span>Updating ypur catalogue gets rewarded </span>\r\n							<p>\r\n								Updating ypur catalogue gets rewardedUpdating ypur catalogue gets rewardedUpdating ypur catalogue gets rewarded</p>\r\n						</li>\r\n						<li>\r\n							<span>Updating ypur catalogue gets rewarded </span>\r\n							<p>\r\n								Updating ypur catalogue gets rewardedUpdating ypur catalogue gets rewardedUpdating ypur catalogue gets rewarded</p>\r\n						</li>\r\n						<li>\r\n							<span>Updating ypur catalogue gets rewarded </span>\r\n							<p>\r\n								Updating ypur catalogue gets rewardedUpdating ypur catalogue gets rewardedUpdating ypur catalogue gets rewarded</p>\r\n						</li>\r\n						<li>\r\n							<span>Updating ypur catalogue gets rewarded </span>\r\n							<p>\r\n								Updating ypur catalogue gets rewardedUpdating ypur catalogue gets rewardedUpdating ypur catalogue gets rewarded</p>\r\n						</li>\r\n						<li>\r\n							<span>Updating ypur catalogue gets rewarded </span>\r\n							<p>\r\n								Updating ypur catalogue gets rewardedUpdating ypur catalogue gets rewardedUpdating ypur catalogue gets rewarded</p>\r\n						</li>\r\n						<li>\r\n							<span>Updating ypur catalogue gets rewarded </span>\r\n							<p>\r\n								Updating ypur catalogue gets rewardedUpdating ypur catalogue gets rewardedUpdating ypur catalogue gets rewarded</p>\r\n						</li>\r\n					</ol>\r\n				</div>\r\n				<div class="row bottom_btn">\r\n					<div class="span12" style="text-align:center;">\r\n						<button>Start</button><span>Click to above button to proceed</span></div>\r\n				</div>\r\n			</div>\r\n		</div>\r\n	</div>\r\n</div>\r\n', '2014-07-16 05:05:04', '2015-03-24 09:40:40', 'keyword-1, keyword-2', 'Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.'),
(2, 'welcome page', 'welcome-page', '<div class="inner_wrapper">\r\n	<div class="container">\r\n		<div class="row">\r\n			<aside class="span4">\r\n			<div class="aside_block">\r\n				<div class="aside_title">\r\n					You will see this button close to available shops. Click on it to add it as your own favorite</div>\r\n				<img id="add_to_fav" src="/themes/bootstrap/img/buttons/btn1.jpg" /></div>\r\n			<div class="aside_block">\r\n				<div class="aside_title">\r\n					Click on this button ti add this location as favorite</div>\r\n				<img id="make_req" src="/themes/bootstrap/img/buttons/btn2.jpg" /></div>\r\n			<div class="aside_block">\r\n				<div class="aside_title">\r\n					Click on this button ti add this location as favorite</div>\r\n				<img id="wait_raq" src="/themes/bootstrap/img/buttons/btn3.jpg" /></div>\r\n			<div class="aside_block">\r\n				<div class="aside_title">\r\n					Click on this button ti add this location as favorite</div>\r\n				<img id="to_fav" src="/themes/bootstrap/img/buttons/btn4.jpg" /></div>\r\n			</aside>\r\n			<div class="content_right span8">\r\n				<div class="row">\r\n					<h3>\r\n						Welcome to Nirbuy</h3>\r\n					<p>\r\n						Questa &egrave; una prova per vedere se tutto funziona come dico io. La lunghezza del testo e cos&igrave; via.</p>\r\n				</div>\r\n				<div class="row see_our_location">\r\n					<div class="left_block span3">\r\n						<span>See our locations list:</span></div>\r\n					<div class="right_block span5">\r\n						<a data-target="#myModal" data-toggle="modal" href="#" id="cityId">London</a>\r\n						<p>\r\n							Click on above link to select or change city or area.</p>\r\n						<button id="searchButton">Search</button></div>\r\n				</div>\r\n				<div class="row">\r\n					<div class="add_your_b">\r\n						<div class="span2">\r\n							<button>Add your Busines</button><a href="#">Contact us</a></div>\r\n						<div class="span6">\r\n							<span>Click on above link to select or change city or area. </span> <span> Click on above link to select or change city or area. </span></div>\r\n						<div class="clear">\r\n							&nbsp;</div>\r\n					</div>\r\n				</div>\r\n			</div>\r\n		</div>\r\n	</div>\r\n</div>\r\n', '2014-08-28 02:38:24', '2015-03-25 01:41:25', 'welcome page', 'Welcome page');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
