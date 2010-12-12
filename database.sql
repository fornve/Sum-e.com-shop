-- MySQL dump 10.11
--
-- Host: localhost    Database: shop
-- ------------------------------------------------------
-- Server version	5.0.90

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `admin`
--

DROP TABLE IF EXISTS `admin`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `admin` (
  `id` bigint(20) unsigned NOT NULL auto_increment,
  `username` varchar(40) default NULL,
  `password` varchar(40) default NULL,
  `last_login_time` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `last_login_ip` varchar(40) default NULL,
  `vendor` int(10) default NULL,
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `category`
--

DROP TABLE IF EXISTS `category`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `category` (
  `id` bigint(20) unsigned NOT NULL auto_increment,
  `name` varchar(255) default NULL,
  `image` varchar(255) default NULL,
  `parent` int(20) default NULL,
  `sort_order` int(5) default NULL,
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=39 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `category_description`
--

DROP TABLE IF EXISTS `category_description`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `category_description` (
  `category` int(20) NOT NULL,
  `description` text,
  PRIMARY KEY  (`category`),
  UNIQUE KEY `category` (`category`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `country`
--

DROP TABLE IF EXISTS `country`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `country` (
  `code` varchar(2) NOT NULL,
  `name` varchar(50) default NULL,
  PRIMARY KEY  (`code`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `geoip`
--

DROP TABLE IF EXISTS `geoip`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `geoip` (
  `ip_from` varchar(15) NOT NULL,
  `ip_to` varchar(15) default NULL,
  `ip_num_from` int(11) default NULL,
  `ip_num_to` int(11) default NULL,
  `country_code` varchar(2) default NULL,
  `country_name` varchar(40) default NULL,
  PRIMARY KEY  (`ip_from`),
  UNIQUE KEY `ip_from` (`ip_from`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `imageserver`
--

DROP TABLE IF EXISTS `imageserver`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `imageserver` (
  `id` bigint(20) unsigned NOT NULL auto_increment,
  `file` varchar(255) default NULL,
  `original_url` varchar(255) default NULL,
  `destination_url` varchar(255) default NULL,
  `last_access` int(10) default NULL,
  UNIQUE KEY `id` (`id`),
  KEY `original_url_idx` (`original_url`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `log`
--

DROP TABLE IF EXISTS `log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `log` (
  `id` bigint(20) unsigned NOT NULL auto_increment,
  `type` varchar(20) default NULL,
  `section` varchar(20) default NULL,
  `user` int(20) default NULL,
  `comment` varchar(255) default NULL,
  `created` int(10) default NULL,
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=66 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `manufacturer`
--

DROP TABLE IF EXISTS `manufacturer`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `manufacturer` (
  `id` bigint(20) unsigned NOT NULL auto_increment,
  `name` varchar(255) default NULL,
  `image` varchar(255) default NULL,
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `order`
--

DROP TABLE IF EXISTS `order`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `order` (
  `id` bigint(20) unsigned NOT NULL auto_increment,
  `customer` int(11) NOT NULL,
  `customer_name` varchar(255) NOT NULL,
  `customer_company` varchar(255) default NULL,
  `customer_address` varchar(255) NOT NULL,
  `customer_suburb` varchar(255) default NULL,
  `customer_city` varchar(255) NOT NULL,
  `customer_postcode` varchar(255) NOT NULL,
  `customer_county` varchar(255) default NULL,
  `customer_country` varchar(255) NOT NULL,
  `customer_phone` varchar(255) NOT NULL,
  `customer_email` varchar(255) NOT NULL,
  `customer_ip_address` varchar(15) default NULL,
  `customer_note` text,
  `delivery_name` varchar(255) NOT NULL,
  `delivery_company` varchar(255) default NULL,
  `delivery_address` varchar(255) NOT NULL,
  `delivery_city` varchar(255) NOT NULL,
  `delivery_postcode` varchar(255) NOT NULL,
  `delivery_county` varchar(255) default NULL,
  `delivery_country` varchar(255) NOT NULL,
  `billing_name` varchar(255) NOT NULL,
  `billing_company` varchar(255) default NULL,
  `billing_address` varchar(255) NOT NULL,
  `billing_city` varchar(255) NOT NULL,
  `billing_postcode` varchar(255) NOT NULL,
  `billing_county` varchar(255) default NULL,
  `billing_country` varchar(255) NOT NULL,
  `payment` varchar(255) NOT NULL,
  `last_modified` datetime default NULL,
  `purchase_date` datetime default NULL,
  `status` int(11) NOT NULL,
  `finished_date` datetime default NULL,
  `currency` char(3) default NULL,
  `value` decimal(14,6) default NULL,
  `shipping` int(10) default '1',
  `vendor` int(20) default '0',
  `despatched` tinyint(1) default '0',
  `shipping_value` float(15,2) default '0.00',
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=112 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `order_product`
--

DROP TABLE IF EXISTS `order_product`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `order_product` (
  `id` bigint(20) unsigned NOT NULL auto_increment,
  `order` bigint(20) default NULL,
  `product` int(20) default NULL,
  `quantity` int(20) default NULL,
  `item_value` float(15,4) default NULL,
  `tax` int(10) default '0',
  `variant` text,
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=113 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `order_status_history`
--

DROP TABLE IF EXISTS `order_status_history`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `order_status_history` (
  `id` bigint(20) unsigned NOT NULL auto_increment,
  `order` int(20) default NULL,
  `created` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `customer_notified` tinyint(1) default NULL,
  `comments` text,
  `payer_id` varchar(255) default NULL,
  `transaction_id` varchar(255) default NULL,
  `status` varchar(255) default NULL,
  `total` float(15,2) default NULL,
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=84 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `page`
--

DROP TABLE IF EXISTS `page`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `page` (
  `id` bigint(20) unsigned NOT NULL auto_increment,
  `title` varchar(255) default NULL,
  `text` text,
  `image` varchar(255) default NULL,
  `type` varchar(20) default NULL,
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `product`
--

DROP TABLE IF EXISTS `product`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `product` (
  `id` bigint(20) unsigned NOT NULL auto_increment,
  `quantity` int(20) default NULL,
  `price` float(15,4) default NULL,
  `name` varchar(255) default NULL,
  `added_date` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `weight` float(10,2) default NULL,
  `status` tinyint(1) default NULL,
  `tax` int(11) default NULL,
  `vendor` int(10) default '0',
  `description` text,
  `keywords` varchar(255) default NULL,
  `upc` varchar(255) default NULL,
  `storage_location` varchar(255) default NULL,
  `condition` varchar(10) default NULL,
  `visited` int(20) default '0',
  `ordered` int(20) default '0',
  `sold` int(20) default '0',
  `deleted` tinyint(1) default '0',
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=26 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `product_category`
--

DROP TABLE IF EXISTS `product_category`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `product_category` (
  `id` bigint(20) unsigned NOT NULL auto_increment,
  `product` int(20) default NULL,
  `category` int(20) default NULL,
  `order` int(20) default '0',
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=23 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `product_description`
--

DROP TABLE IF EXISTS `product_description`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `product_description` (
  `product` int(20) NOT NULL,
  `description` text,
  PRIMARY KEY  (`product`),
  UNIQUE KEY `product` (`product`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `product_image`
--

DROP TABLE IF EXISTS `product_image`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `product_image` (
  `id` int(20) NOT NULL auto_increment,
  `product` int(20) NOT NULL,
  `image` varchar(255) default NULL,
  `title` varchar(255) default NULL,
  `main` tinyint(1) default NULL,
  PRIMARY KEY  (`id`),
  KEY `id` (`id`,`product`)
) ENGINE=MyISAM AUTO_INCREMENT=40 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `product_manufacturer`
--

DROP TABLE IF EXISTS `product_manufacturer`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `product_manufacturer` (
  `product` int(20) default NULL,
  `manufacturer` int(20) default NULL,
  KEY `product` (`product`,`manufacturer`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `product_shipping`
--

DROP TABLE IF EXISTS `product_shipping`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `product_shipping` (
  `product` int(20) NOT NULL,
  `shipping` int(20) default NULL,
  PRIMARY KEY  (`product`),
  KEY `product` (`product`,`shipping`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `search`
--

DROP TABLE IF EXISTS `search`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `search` (
  `id` bigint(20) unsigned NOT NULL auto_increment,
  `sentence` varchar(255) default NULL,
  `count` int(20) default NULL,
  `results` int(20) default NULL,
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=23 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `shipping`
--

DROP TABLE IF EXISTS `shipping`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shipping` (
  `id` bigint(20) unsigned NOT NULL auto_increment,
  `name` varchar(255) default NULL,
  `flat_value` float(15,2) default NULL,
  `vendor` int(20) default NULL,
  `weight_multiply` float(6,2) default NULL,
  `limit_price` float(10,2) default NULL,
  `description` text,
  `enabled` tinyint(1) default '0',
  UNIQUE KEY `id` (`id`),
  KEY `id_2` (`id`,`vendor`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `site_config`
--

DROP TABLE IF EXISTS `site_config`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `site_config` (
  `id` bigint(20) unsigned NOT NULL auto_increment,
  `title` varchar(255) default NULL,
  `value` varchar(255) default NULL,
  `type` varchar(10) default 'text',
  `name` varchar(40) default NULL,
  `description` text,
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tax`
--

DROP TABLE IF EXISTS `tax`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tax` (
  `id` bigint(20) unsigned NOT NULL auto_increment,
  `vendor` int(20) default NULL,
  `name` varchar(255) default NULL,
  `value` float(5,2) default NULL,
  UNIQUE KEY `id` (`id`),
  KEY `id_2` (`id`,`vendor`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `url`
--

DROP TABLE IF EXISTS `url`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `url` (
  `id` bigint(20) unsigned NOT NULL auto_increment,
  `organic` varchar(255) default NULL,
  `artificial` varchar(255) default NULL,
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `user_agent`
--

DROP TABLE IF EXISTS `user_agent`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_agent` (
  `id` bigint(20) unsigned NOT NULL auto_increment,
  `name` varchar(255) default NULL,
  `count` int(20) default NULL,
  `type` varchar(255) default NULL,
  `agent` varchar(255) default NULL,
  `os` varchar(255) default NULL,
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2205 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `variant`
--

DROP TABLE IF EXISTS `variant`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `variant` (
  `id` bigint(20) unsigned NOT NULL auto_increment,
  `product` int(20) default NULL,
  `type` varchar(255) default NULL,
  `name` varchar(255) default NULL,
  `price_change` float(5,2) default NULL,
  `quantity` int(10) default NULL,
  UNIQUE KEY `id` (`id`),
  KEY `id_2` (`id`,`product`)
) ENGINE=MyISAM AUTO_INCREMENT=18 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `vendor`
--

DROP TABLE IF EXISTS `vendor`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `vendor` (
  `id` bigint(20) unsigned NOT NULL auto_increment,
  `name` varchar(255) default NULL,
  `shortname` varchar(255) default NULL,
  `email` varchar(255) default NULL,
  `address` varchar(255) default NULL,
  `city` varchar(255) default NULL,
  `zip` varchar(255) default NULL,
  `county` varchar(255) default NULL,
  `country` varchar(2) default NULL,
  `phone` varchar(255) default NULL,
  `fax` varchar(255) default NULL,
  `company_number` varchar(255) default NULL,
  `vat` varchar(255) default NULL,
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=74 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2010-12-12 21:04:24
