-- phpMyAdmin SQL Dump
-- version 4.4.15.10
-- https://www.phpmyadmin.net
--
-- Host: 10.200.1.104
-- Generation Time: Jul 04, 2020 at 10:23 AM
-- Server version: 10.1.44-MariaDB-0+deb9u1
-- PHP Version: 7.3.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `mysuite`
--

-- --------------------------------------------------------

--
-- Table structure for table `accounts`
--

CREATE TABLE IF NOT EXISTS `accounts` (
  `id` char(36) NOT NULL,
  `name` varchar(150) DEFAULT NULL,
  `date_entered` datetime DEFAULT NULL,
  `date_modified` datetime DEFAULT NULL,
  `modified_user_id` char(36) DEFAULT NULL,
  `created_by` char(36) DEFAULT NULL,
  `description` text,
  `deleted` tinyint(1) DEFAULT '0',
  `assigned_user_id` char(36) DEFAULT NULL,
  `account_type` varchar(50) DEFAULT NULL,
  `industry` varchar(50) DEFAULT NULL,
  `annual_revenue` varchar(100) DEFAULT NULL,
  `phone_fax` varchar(100) DEFAULT NULL,
  `billing_address_street` varchar(150) DEFAULT NULL,
  `billing_address_city` varchar(100) DEFAULT NULL,
  `billing_address_state` varchar(100) DEFAULT NULL,
  `billing_address_postalcode` varchar(20) DEFAULT NULL,
  `billing_address_country` varchar(255) DEFAULT NULL,
  `rating` varchar(100) DEFAULT NULL,
  `phone_office` varchar(100) DEFAULT NULL,
  `phone_alternate` varchar(100) DEFAULT NULL,
  `website` varchar(255) DEFAULT NULL,
  `ownership` varchar(100) DEFAULT NULL,
  `employees` varchar(10) DEFAULT NULL,
  `ticker_symbol` varchar(10) DEFAULT NULL,
  `shipping_address_street` varchar(150) DEFAULT NULL,
  `shipping_address_city` varchar(100) DEFAULT NULL,
  `shipping_address_state` varchar(100) DEFAULT NULL,
  `shipping_address_postalcode` varchar(20) DEFAULT NULL,
  `shipping_address_country` varchar(255) DEFAULT NULL,
  `parent_id` char(36) DEFAULT NULL,
  `sic_code` varchar(10) DEFAULT NULL,
  `campaign_id` char(36) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `accounts_audit`
--

CREATE TABLE IF NOT EXISTS `accounts_audit` (
  `id` char(36) NOT NULL,
  `parent_id` char(36) NOT NULL,
  `date_created` datetime DEFAULT NULL,
  `created_by` varchar(36) DEFAULT NULL,
  `field_name` varchar(100) DEFAULT NULL,
  `data_type` varchar(100) DEFAULT NULL,
  `before_value_string` varchar(255) DEFAULT NULL,
  `after_value_string` varchar(255) DEFAULT NULL,
  `before_value_text` text,
  `after_value_text` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `accounts_bugs`
--

CREATE TABLE IF NOT EXISTS `accounts_bugs` (
  `id` varchar(36) NOT NULL,
  `account_id` varchar(36) DEFAULT NULL,
  `bug_id` varchar(36) DEFAULT NULL,
  `date_modified` datetime DEFAULT NULL,
  `deleted` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `accounts_cases`
--

CREATE TABLE IF NOT EXISTS `accounts_cases` (
  `id` varchar(36) NOT NULL,
  `account_id` varchar(36) DEFAULT NULL,
  `case_id` varchar(36) DEFAULT NULL,
  `date_modified` datetime DEFAULT NULL,
  `deleted` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `accounts_contacts`
--

CREATE TABLE IF NOT EXISTS `accounts_contacts` (
  `id` varchar(36) NOT NULL,
  `contact_id` varchar(36) DEFAULT NULL,
  `account_id` varchar(36) DEFAULT NULL,
  `date_modified` datetime DEFAULT NULL,
  `deleted` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `accounts_cstm`
--

CREATE TABLE IF NOT EXISTS `accounts_cstm` (
  `id_c` char(36) NOT NULL,
  `jjwg_maps_lng_c` float(11,8) DEFAULT '0.00000000',
  `jjwg_maps_lat_c` float(10,8) DEFAULT '0.00000000',
  `jjwg_maps_geocode_status_c` varchar(255) DEFAULT NULL,
  `jjwg_maps_address_c` varchar(255) DEFAULT NULL,
  `stic_acronym_c` varchar(255) DEFAULT NULL,
  `stic_billing_address_county_c` varchar(100) DEFAULT NULL,
  `stic_billing_address_region_c` varchar(100) DEFAULT NULL,
  `stic_billing_address_type_c` varchar(100) DEFAULT NULL,
  `stic_category_c` varchar(100) DEFAULT NULL,
  `stic_identification_number_c` varchar(255) DEFAULT NULL,
  `stic_language_c` varchar(100) DEFAULT NULL,
  `stic_relationship_type_c` text,
  `stic_postal_mail_return_reason_c` varchar(100) DEFAULT NULL,
  `stic_shipping_address_county_c` varchar(100) DEFAULT NULL,
  `stic_shipping_address_region_c` varchar(100) DEFAULT NULL,
  `stic_shipping_address_type_c` varchar(100) DEFAULT NULL,
  `stic_subcategory_c` varchar(100) DEFAULT NULL,
  `stic_182_error_c` tinyint(1) DEFAULT '0',
  `stic_182_excluded_c` tinyint(1) DEFAULT '0',
  `stic_tax_name_c` varchar(255) DEFAULT NULL,
  `stic_total_annual_donation_c` decimal(26,2) DEFAULT '0.00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `accounts_opportunities`
--

CREATE TABLE IF NOT EXISTS `accounts_opportunities` (
  `id` varchar(36) NOT NULL,
  `opportunity_id` varchar(36) DEFAULT NULL,
  `account_id` varchar(36) DEFAULT NULL,
  `date_modified` datetime DEFAULT NULL,
  `deleted` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `accounts_opportunities_1_c`
--

CREATE TABLE IF NOT EXISTS `accounts_opportunities_1_c` (
  `id` varchar(36) NOT NULL,
  `date_modified` datetime DEFAULT NULL,
  `deleted` tinyint(1) DEFAULT '0',
  `accounts_opportunities_1accounts_ida` varchar(36) DEFAULT NULL,
  `accounts_opportunities_1opportunities_idb` varchar(36) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `acl_actions`
--

CREATE TABLE IF NOT EXISTS `acl_actions` (
  `id` char(36) NOT NULL,
  `date_entered` datetime DEFAULT NULL,
  `date_modified` datetime DEFAULT NULL,
  `modified_user_id` char(36) DEFAULT NULL,
  `created_by` char(36) DEFAULT NULL,
  `name` varchar(150) DEFAULT NULL,
  `category` varchar(100) DEFAULT NULL,
  `acltype` varchar(100) DEFAULT NULL,
  `aclaccess` int(3) DEFAULT NULL,
  `deleted` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `acl_actions`
--

INSERT INTO `acl_actions` (`id`, `date_entered`, `date_modified`, `modified_user_id`, `created_by`, `name`, `category`, `acltype`, `aclaccess`, `deleted`) VALUES
('10aad4d4-7467-4c1a-e4af-5e830d8ea1aa', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'export', 'SecurityGroups', 'module', 90, 0),
('111323b8-8703-a6d0-bbc2-5e830d9f3510', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'delete', 'AOS_Product_Categories', 'module', 90, 0),
('11560d81-1782-863e-29bf-5e830d99d986', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'access', 'Notes', 'module', 89, 0),
('117a98d3-2a00-afd9-9b4d-5f003a3cdb0b', '2020-07-04 08:16:22', '2020-07-04 08:16:22', '1', '1', 'access', 'stic_Validation_Actions', 'module', 89, 0),
('11ba14e0-dfbf-be57-1b2f-5e830da26248', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'massupdate', 'jjwg_Markers', 'module', 90, 0),
('120e6231-742d-23b0-6b65-5e830df2b1a4', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'list', 'FP_events', 'module', 90, 0),
('128bd0aa-1318-34b9-c372-5e830d3c67ec', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'edit', 'AOW_WorkFlow', 'module', 90, 0),
('130079f1-dee4-dfc2-24ee-5e830de2c135', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'list', 'Project', 'module', 90, 0),
('1396ab33-70cd-c81d-ac04-5e830d5219b5', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'edit', 'Surveys', 'module', 90, 0),
('13aa220c-3855-0c5e-f215-5e830dc7fb58', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'massupdate', 'AOP_Case_Events', 'module', 90, 0),
('13b0369c-67ce-08ed-d2a6-5e830d2cc709', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'delete', 'Notes', 'module', 90, 0),
('13b80486-2b1c-973d-90cf-5f003aee6c1f', '2020-07-04 08:16:22', '2020-07-04 08:16:22', '1', '1', 'massupdate', 'stic_Events', 'module', 90, 0),
('142bde96-d789-9ead-d190-5e830d1ae399', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'massupdate', 'AOR_Scheduled_Reports', 'module', 90, 0),
('151cc9c9-a992-d2ca-f0fa-5e830d657c51', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'import', 'AOS_Product_Categories', 'module', 90, 0),
('154e2b92-b3cb-7b01-b893-5e830d967600', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'massupdate', 'SecurityGroups', 'module', 90, 0),
('1569f875-e9f6-a661-0985-5f003ad0eb4f', '2020-07-04 08:16:22', '2020-07-04 08:16:22', '1', '1', 'view', 'stic_Validation_Actions', 'module', 90, 0),
('15e3de8e-20e9-6d94-79b6-5e830dc66ce0', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'edit', 'FP_events', 'module', 90, 0),
('16700c81-27b2-3c4d-d7a9-5f003a70f359', '2020-07-04 08:16:22', '2020-07-04 08:16:22', '1', '1', 'edit', 'stic_Events', 'module', 90, 0),
('16ee9240-6b74-f5d1-eb9e-5e830d09aa9c', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'delete', 'AOW_WorkFlow', 'module', 90, 0),
('170def82-de91-84db-46ad-5e830d77d3f7', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'edit', 'Project', 'module', 90, 0),
('17a19423-f2f3-a3bf-fa37-5e830dd6d709', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'delete', 'Surveys', 'module', 90, 0),
('18ad97cf-bf80-3a76-be24-5e830d36f74d', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'import', 'Notes', 'module', 90, 0),
('1929a9a4-79c4-d1df-01bc-5e830dbb9fd3', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'export', 'AOS_Product_Categories', 'module', 90, 0),
('194fe94d-eb3c-cee6-786e-5f003a93bfac', '2020-07-04 08:16:22', '2020-07-04 08:16:22', '1', '1', 'list', 'stic_Validation_Actions', 'module', 90, 0),
('198f55be-5b40-192f-c0a3-5f003afc2d17', '2020-07-04 08:16:22', '2020-07-04 08:16:22', '1', '1', 'access', 'DHA_PlantillasDocumentos', 'module', 89, 0),
('1a2e52c5-9c16-bcaf-7e12-5e830df213a7', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'delete', 'FP_events', 'module', 90, 0),
('1a77fb2b-4db1-e5ab-f578-5f003a1be0eb', '2020-07-04 08:16:22', '2020-07-04 08:16:22', '1', '1', 'access', 'stic_Payment_Commitments', 'module', 89, 0),
('1b3180a5-5a72-eab5-7f83-5e830d49d37f', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'delete', 'Project', 'module', 90, 0),
('1bbaed51-a5ca-3c4b-9193-5e830ddca26b', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'import', 'Surveys', 'module', 90, 0),
('1bc408f3-6d1c-7517-803d-5e830d2f6f76', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'import', 'AOW_WorkFlow', 'module', 90, 0),
('1c615de4-ae6c-84ea-d63b-5e830d6a929a', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'export', 'Notes', 'module', 90, 0),
('1cf0b211-c3b3-0492-1a41-5e830de5f2cd', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'access', 'Prospects', 'module', 89, 0),
('1d26cc7a-4f3a-6dab-4064-5e830d53fd16', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'massupdate', 'AOS_Product_Categories', 'module', 90, 0),
('1d501548-c9d6-9613-a0b9-5f003a8d0d6c', '2020-07-04 08:16:22', '2020-07-04 08:16:22', '1', '1', 'edit', 'stic_Validation_Actions', 'module', 90, 0),
('1e50d4dd-f6a4-1119-31f1-5f003ace3c2e', '2020-07-04 08:16:22', '2020-07-04 08:16:22', '1', '1', 'view', 'DHA_PlantillasDocumentos', 'module', 90, 0),
('1e7bec34-a6eb-b9ab-e971-5f003a65eb8c', '2020-07-04 08:16:22', '2020-07-04 08:16:22', '1', '1', 'view', 'stic_Payment_Commitments', 'module', 90, 0),
('1f5ee0a2-8a5a-9251-1dfa-5e830dbe50c6', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'import', 'FP_events', 'module', 90, 0),
('1fc47bd0-2001-2eed-c15d-5e830d80a899', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'import', 'Project', 'module', 90, 0),
('2030e3a2-7b52-8b21-458d-5e830d12454d', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'export', 'AOW_WorkFlow', 'module', 90, 0),
('20342375-266e-f402-240c-5e830d5e5d7b', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'massupdate', 'Notes', 'module', 90, 0),
('20995985-371a-5071-ca51-5e830d90910b', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'export', 'Surveys', 'module', 90, 0),
('212b3872-d52f-34c7-c425-5e830db9f241', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'view', 'Prospects', 'module', 90, 0),
('215566bf-27c7-c4e5-9f79-5f003a57e639', '2020-07-04 08:16:22', '2020-07-04 08:16:22', '1', '1', 'delete', 'stic_Validation_Actions', 'module', 90, 0),
('22836c62-a23c-42e2-65c6-5f003ac8a04b', '2020-07-04 08:16:22', '2020-07-04 08:16:22', '1', '1', 'list', 'DHA_PlantillasDocumentos', 'module', 90, 0),
('22a1d0dc-c11b-8c87-0e58-5f003a5bba8c', '2020-07-04 08:16:22', '2020-07-04 08:16:22', '1', '1', 'list', 'stic_Payment_Commitments', 'module', 90, 0),
('23a40501-15be-4895-0ec8-5e830d7741d7', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'edit', 'SecurityGroups', 'module', 90, 0),
('23a407cc-f5f8-ea3f-37c2-5e830dc3012f', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'massupdate', 'Contacts', 'module', 90, 0),
('242ad254-bef5-22f3-714b-5e830d8766c1', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'access', 'AM_TaskTemplates', 'module', 89, 0),
('2458e392-9839-5357-1c38-5e830de3b9ea', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'export', 'Project', 'module', 90, 0),
('248da023-1a0b-75aa-6662-5e830dd426d4', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'massupdate', 'AOW_WorkFlow', 'module', 90, 0),
('24f1ab97-983d-7ee0-2f47-5e830db14800', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'export', 'FP_events', 'module', 90, 0),
('2566d394-8f1d-7bdf-a5a5-5e830d2d9fd6', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'massupdate', 'Surveys', 'module', 90, 0),
('25711f67-86b8-90b3-e424-5f003a27abc8', '2020-07-04 08:16:22', '2020-07-04 08:16:22', '1', '1', 'import', 'stic_Validation_Actions', 'module', 90, 0),
('2660b8e0-dbce-2fb4-54f4-5f003a1a6d81', '2020-07-04 08:16:22', '2020-07-04 08:16:22', '1', '1', 'edit', 'stic_Payment_Commitments', 'module', 90, 0),
('26b49e8e-ba1b-7cde-b1c0-5e830de5449b', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'access', 'AOS_Contracts', 'module', 89, 0),
('27c99859-f733-c10b-e6a0-5e830df85d72', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'view', 'AM_TaskTemplates', 'module', 90, 0),
('28091d62-686c-cdef-a2be-5f003a5dff3b', '2020-07-04 08:16:22', '2020-07-04 08:16:22', '1', '1', 'edit', 'DHA_PlantillasDocumentos', 'module', 90, 0),
('28622936-52a3-fe92-e172-5e830dedb381', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'massupdate', 'Project', 'module', 90, 0),
('28880566-e438-a7d6-fed5-5e830df0f71d', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'edit', 'AOP_Case_Events', 'module', 90, 0),
('2ac3fde1-3e7a-9b3a-110a-5e830db47664', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'view', 'AOS_Contracts', 'module', 90, 0),
('2b38c63a-a33d-fa37-61f3-5e830d66b5d7', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'list', 'AM_TaskTemplates', 'module', 90, 0),
('2b806933-e352-867c-5c7f-5e830d52af3c', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'list', 'Prospects', 'module', 90, 0),
('2ba27cdc-0751-646c-aa71-5e830d7a42c4', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'massupdate', 'FP_events', 'module', 90, 0),
('2bf79841-a4f5-d832-dce6-5f003a948319', '2020-07-04 08:16:22', '2020-07-04 08:16:22', '1', '1', 'delete', 'DHA_PlantillasDocumentos', 'module', 90, 0),
('2c0c0009-b7ac-59b0-c90d-5e830d9ea8bb', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'delete', 'AM_ProjectTemplates', 'module', 90, 0),
('2c978d75-b503-1a4d-ed8c-5e830db1a6f6', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'access', 'jjwg_Areas', 'module', 89, 0),
('2db44692-8cb9-ea9a-84eb-5f003a10db09', '2020-07-04 08:16:22', '2020-07-04 08:16:22', '1', '1', 'export', 'stic_Validation_Actions', 'module', 90, 0),
('2eca06a0-0239-c248-1ec4-5e830ddff23a', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'list', 'AOS_Contracts', 'module', 90, 0),
('2f2eb6a1-685f-6e3f-d9cf-5e830d4be476', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'edit', 'AM_TaskTemplates', 'module', 90, 0),
('30041dc7-670f-ca59-9f94-5e830db34d35', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'edit', 'Prospects', 'module', 90, 0),
('300906da-f6e6-43c1-c293-5e830dd3111f', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'export', 'ProspectLists', 'module', 90, 0),
('30829611-d3e6-1bf0-584d-5f003a737372', '2020-07-04 08:16:22', '2020-07-04 08:16:22', '1', '1', 'import', 'DHA_PlantillasDocumentos', 'module', 90, 0),
('31ad3eef-e0f5-8ad5-592e-5f003aeedc19', '2020-07-04 08:16:22', '2020-07-04 08:16:22', '1', '1', 'massupdate', 'stic_Validation_Actions', 'module', 90, 0),
('320f8e7e-e5b5-7086-7821-5e830d1c9180', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'access', 'AOP_Case_Updates', 'module', 89, 0),
('32a48d8d-4848-263f-0b0d-5e830db16ad9', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'edit', 'AOS_Contracts', 'module', 90, 0),
('32e8c162-9579-7885-43c9-5e830deef26a', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'delete', 'AM_TaskTemplates', 'module', 90, 0),
('331900e4-3152-3e65-59e7-5e830dab7023', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'view', 'AOS_Product_Categories', 'module', 90, 0),
('3426b202-2cbc-6e2f-03eb-5e830d7d9dd8', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'delete', 'Prospects', 'module', 90, 0),
('348a9b07-94dc-58ff-923d-5f003a708f9b', '2020-07-04 08:16:22', '2020-07-04 08:16:22', '1', '1', 'export', 'DHA_PlantillasDocumentos', 'module', 90, 0),
('34d205d5-18e4-a6ba-3548-5e830d8817c0', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'import', 'Tasks', 'module', 90, 0),
('3602dd7c-d335-e245-28c5-5e830d2d56e8', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'access', 'OutboundEmailAccounts', 'module', 89, 0),
('36a75ba0-4e55-b672-e1b8-5e830dce41cc', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'access', 'Calls', 'module', 89, 0),
('36cd9e39-e602-a845-8596-5e830dc658fa', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'import', 'AM_TaskTemplates', 'module', 90, 0),
('36d55cbf-c4b2-db47-9ab3-5e830d92fda9', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'view', 'jjwg_Areas', 'module', 90, 0),
('370f572e-6fc6-df49-f783-5e830df192d8', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'delete', 'AOS_Contracts', 'module', 90, 0),
('37137fe6-3bfa-5ac9-b4aa-5e830d128ae5', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'access', 'AOS_Products', 'module', 89, 0),
('371d0045-e162-d7fd-2a16-5e830dbe0d2b', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'view', 'AOP_Case_Updates', 'module', 90, 0),
('3815fc10-7d7d-6e2d-399d-5e830d935a95', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'import', 'Prospects', 'module', 90, 0),
('384a303e-1e84-a66d-35c0-5e830d9fb2d9', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'access', 'Accounts', 'module', 89, 0),
('3881d04f-360f-0932-b93e-5f003ae476e7', '2020-07-04 08:16:22', '2020-07-04 08:16:22', '1', '1', 'massupdate', 'DHA_PlantillasDocumentos', 'module', 90, 0),
('39b3957d-3afb-5f94-0b74-5f003aaf601a', '2020-07-04 08:16:22', '2020-07-04 08:16:22', '1', '1', 'delete', 'stic_Payment_Commitments', 'module', 90, 0),
('3a2c4232-1684-6882-8bca-5e830d0ab576', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'view', 'OutboundEmailAccounts', 'module', 90, 0),
('3a602246-575a-d493-a01e-5e830d4f450b', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'access', 'Cases', 'module', 89, 0),
('3a99efe3-de7a-e323-8ef2-5e830d284f46', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'view', 'Calls', 'module', 90, 0),
('3ab89c0e-d902-33f9-d0ad-5e830de244d0', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'export', 'AM_TaskTemplates', 'module', 90, 0),
('3af45635-7f66-7ffa-607c-5e830d3fe7f7', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'view', 'AOS_Products', 'module', 90, 0),
('3b054a1c-7dd1-7883-798c-5e830d02ff98', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'list', 'jjwg_Areas', 'module', 90, 0),
('3b512974-6cf2-5f87-3a7c-5e830de05fbd', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'import', 'AOS_Contracts', 'module', 90, 0),
('3b65ec99-a98e-c8de-1e19-5e830dcc1c19', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'list', 'AOP_Case_Updates', 'module', 90, 0),
('3b75084e-5b73-eadf-fd81-5e830d8a3b23', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'import', 'Leads', 'module', 90, 0),
('3c3b2291-9b81-4199-a7a8-5e830d41cc39', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'export', 'Prospects', 'module', 90, 0),
('3cb70c64-2eab-79f4-d80b-5e830ddd0bfb', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'delete', 'jjwg_Markers', 'module', 90, 0),
('3d6b3f2a-fa6d-a336-7d92-5e830dd40f12', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'view', 'Accounts', 'module', 90, 0),
('3e034e87-2ff3-2c20-b358-5e830d3b3c4a', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'list', 'OutboundEmailAccounts', 'module', 90, 0),
('3e6f8463-b0ec-a9fb-bb98-5e830d22c7f2', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'list', 'Calls', 'module', 90, 0),
('3e730709-bf6e-755b-970f-5e830d5bd4af', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'view', 'Cases', 'module', 90, 0),
('3ed66ddd-e4a5-aa45-775f-5e830df065cb', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'massupdate', 'AM_TaskTemplates', 'module', 90, 0),
('3f0063da-4880-8525-b7ac-5e830d9765f3', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'list', 'AOS_Products', 'module', 90, 0),
('3f68eb39-9ba3-67fa-5e42-5e830d015cf7', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'edit', 'jjwg_Areas', 'module', 90, 0),
('3ff30d3b-2391-abe0-7616-5e830d35cd5c', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'import', 'AOR_Scheduled_Reports', 'module', 90, 0),
('40366739-8473-446d-8923-5e830de1773c', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'export', 'AOS_Contracts', 'module', 90, 0),
('40add1f1-edad-a5c0-5caa-5e830d9f5666', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'massupdate', 'Prospects', 'module', 90, 0),
('40afa84f-bbc2-db20-312d-5e830da7fc1f', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'edit', 'AOP_Case_Updates', 'module', 90, 0),
('41de31ad-391a-54d9-1d5c-5e830d2806ae', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'edit', 'OutboundEmailAccounts', 'module', 90, 0),
('42188a85-4bf5-8304-71bb-5e830d11eaaf', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'list', 'Accounts', 'module', 90, 0),
('4294e42f-5474-80d7-94de-5e830dc38d1e', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'list', 'Cases', 'module', 90, 0),
('430c92b5-8e51-443d-1ff7-5e830d792890', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'edit', 'Calls', 'module', 90, 0),
('43992c7e-3f13-1660-ff2d-5f003a937258', '2020-07-04 08:16:22', '2020-07-04 08:16:22', '1', '1', 'access', 'KReports', 'module', 89, 0),
('4421e225-d3f3-fe46-f147-5e830d66fe46', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'access', 'ProjectTask', 'module', 89, 0),
('446d2fbc-a703-0328-6ff6-5e830d0aa2fc', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'delete', 'jjwg_Areas', 'module', 90, 0),
('44cb390d-9c1e-aaf3-584b-5e830dc63a97', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'massupdate', 'AOS_Contracts', 'module', 90, 0),
('45528398-8169-ed3f-476a-5e830d758089', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'delete', 'AOP_Case_Updates', 'module', 90, 0),
('45c591b2-75f7-8988-8d2a-5e830d77bd09', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'edit', 'AOS_Products', 'module', 90, 0),
('461d45b1-d719-de46-a7af-5e830d003055', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'edit', 'Accounts', 'module', 90, 0),
('4673b53a-072f-44db-56b6-5e830defc936', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'edit', 'Cases', 'module', 90, 0),
('470a07fc-404e-fe74-283f-5e830d1d0249', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'delete', 'OutboundEmailAccounts', 'module', 90, 0),
('47dbff72-1792-03ae-5e97-5f003afd8dd8', '2020-07-04 08:16:22', '2020-07-04 08:16:22', '1', '1', 'view', 'KReports', 'module', 90, 0),
('48798a3d-9848-0ae7-205b-5e830d463a74', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'delete', 'Calls', 'module', 90, 0),
('48842685-5280-087e-9fed-5f003a4ddb86', '2020-07-04 08:16:22', '2020-07-04 08:16:22', '1', '1', 'import', 'stic_Payment_Commitments', 'module', 90, 0),
('4931f4fd-b283-883c-f5dd-5e830dfd6c89', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'view', 'ProjectTask', 'module', 90, 0),
('4965ba2d-f6ae-d650-aabe-5e830dae5032', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'access', 'FP_Event_Locations', 'module', 89, 0),
('49eafcac-e409-96a1-6ec6-5e830dbe7911', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'import', 'AOP_Case_Updates', 'module', 90, 0),
('4a00b249-4dab-52ae-20ca-5e830d21d81d', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'import', 'jjwg_Areas', 'module', 90, 0),
('4a2a02e0-eb31-64a7-6fe7-5e830d9531d7', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'delete', 'Cases', 'module', 90, 0),
('4aaca21d-c729-5425-93e5-5e830d21bdf6', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'delete', 'AOS_Products', 'module', 90, 0),
('4b042126-920b-1a65-1a05-5e830de44035', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'import', 'OutboundEmailAccounts', 'module', 90, 0),
('4b890e6e-cdc3-aea8-ca19-5f003a4571dd', '2020-07-04 08:16:22', '2020-07-04 08:16:22', '1', '1', 'delete', 'stic_Events', 'module', 90, 0),
('4c52e28a-7ef3-2de0-7708-5f003accf3ea', '2020-07-04 08:16:22', '2020-07-04 08:16:22', '1', '1', 'export', 'stic_Payment_Commitments', 'module', 90, 0),
('4d4a9e51-f686-a62a-70a5-5f003acfc560', '2020-07-04 08:16:22', '2020-07-04 08:16:22', '1', '1', 'list', 'KReports', 'module', 90, 0),
('4d6ae778-5b47-d2fd-ae07-5e830d5020a1', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'list', 'ProjectTask', 'module', 90, 0),
('4dad1d39-02e1-72c0-4e11-5e830d27009d', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'view', 'FP_Event_Locations', 'module', 90, 0),
('4e556794-8c8b-a396-40b6-5e830d3527ae', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'export', 'AOP_Case_Updates', 'module', 90, 0),
('4ea4e4e7-e2e2-4f70-f9b4-5e830d9e03f2', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'import', 'Cases', 'module', 90, 0),
('4f0a1553-1148-50f7-0e7b-5e830ddf74e6', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'import', 'Calls', 'module', 90, 0),
('5002068a-2bb7-0404-319f-5e830d5fd4a8', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'export', 'jjwg_Areas', 'module', 90, 0),
('501398f8-30c8-ecab-fede-5e830d158947', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'export', 'OutboundEmailAccounts', 'module', 90, 0),
('5027fc49-fcb4-c51c-3ba4-5f003a109e25', '2020-07-04 08:16:22', '2020-07-04 08:16:22', '1', '1', 'massupdate', 'stic_Payment_Commitments', 'module', 90, 0),
('502b0e08-505c-fd50-ee3b-5e830d036a7c', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'access', 'Surveys', 'module', 89, 0),
('5161d8e8-1a7e-860c-fd96-5f003a2e504e', '2020-07-04 08:16:22', '2020-07-04 08:16:22', '1', '1', 'edit', 'KReports', 'module', 90, 0),
('5172639d-4b66-d03c-300e-5e830d33986b', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'list', 'FP_Event_Locations', 'module', 90, 0),
('51d5816a-6e62-3871-939e-5e830d26b803', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'edit', 'ProjectTask', 'module', 90, 0),
('528131a9-83a0-6df5-49a4-5e830d3aa12b', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'export', 'Cases', 'module', 90, 0),
('52ed6e13-4b86-023a-4ff3-5e830d04acd9', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'massupdate', 'AOP_Case_Updates', 'module', 90, 0),
('53a0f8a0-c3ca-e166-95b9-5e830d2334e3', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'export', 'Calls', 'module', 90, 0),
('53cbe63c-bf44-35f9-e4dd-5e830d690473', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'massupdate', 'jjwg_Areas', 'module', 90, 0),
('53ea4ebd-f8fa-8f85-e84b-5f003aa20e10', '2020-07-04 08:16:22', '2020-07-04 08:16:22', '1', '1', 'access', 'stic_Payments', 'module', 89, 0),
('543e068f-b663-d9aa-e34e-5e830db3547f', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'access', 'AOW_Processed', 'module', 89, 0),
('549f0e25-04ec-2a33-f7ad-5e830d21578a', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'view', 'Notes', 'module', 90, 0),
('54a64490-081b-93b2-69ff-5e830da2acfb', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'delete', 'Accounts', 'module', 90, 0),
('552e4b4a-c1fd-0fe4-7c2a-5e830ddca5de', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'edit', 'FP_Event_Locations', 'module', 90, 0),
('554bc5f1-d0b6-6f01-3bb3-5e830d2ce3d0', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'massupdate', 'OutboundEmailAccounts', 'module', 90, 0),
('55c6fd07-f1a4-3aa0-48db-5f003afdaa83', '2020-07-04 08:16:22', '2020-07-04 08:16:22', '1', '1', 'delete', 'KReports', 'module', 90, 0),
('55d80778-9b07-8bc0-1a99-5e830d8f0de0', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'access', 'Spots', 'module', 89, 0),
('56613fb3-f5e6-6671-5245-5e830d5d5812', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'massupdate', 'Cases', 'module', 90, 0),
('569e0551-91d0-f3f0-4e49-5e830da85f88', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'delete', 'ProjectTask', 'module', 90, 0),
('56af0fe0-4dc7-89be-ae29-5e830dbe0c5e', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'access', 'AOW_WorkFlow', 'module', 89, 0),
('5771277d-5256-78c4-07e6-5e830d0730c2', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'import', 'AOS_Products', 'module', 90, 0),
('57a8a54c-2fb8-e932-36fe-5f003a203a9e', '2020-07-04 08:16:22', '2020-07-04 08:16:22', '1', '1', 'view', 'stic_Payments', 'module', 90, 0),
('5801614f-08d5-b5a9-2a0e-5e830d386d8b', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'massupdate', 'Calls', 'module', 90, 0),
('588c04d8-8f3a-5ce8-7c7d-5e830dae960c', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'import', 'Accounts', 'module', 90, 0),
('592fea49-5cfe-7dbb-2437-5e830da7aae7', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'delete', 'FP_Event_Locations', 'module', 90, 0),
('59ac0a2e-c44a-d39e-fad5-5e830d562de1', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'view', 'AOW_Processed', 'module', 90, 0),
('5ab5bc5a-5a97-6553-5c66-5e830dc0161b', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'view', 'Spots', 'module', 90, 0),
('5b048131-cbaf-893f-cf2b-5e830ddcf305', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'access', 'SurveyQuestionResponses', 'module', 89, 0),
('5b1e09cf-831d-3856-af2a-5e830db39f61', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'import', 'ProjectTask', 'module', 90, 0),
('5b29c33f-6c89-e2ef-088a-5f003a7c50cc', '2020-07-04 08:16:22', '2020-07-04 08:16:22', '1', '1', 'list', 'stic_Payments', 'module', 90, 0),
('5b59bdf3-7095-a2eb-d01f-5e830dd2932a', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'export', 'AOS_Products', 'module', 90, 0),
('5b6a3a61-c229-8e50-aad0-5f003ac0bc1d', '2020-07-04 08:16:22', '2020-07-04 08:16:22', '1', '1', 'import', 'KReports', 'module', 90, 0),
('5c71227e-8542-7457-0c9e-5e830d0d3d51', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'export', 'Accounts', 'module', 90, 0),
('5d45de6f-ea83-43c1-a3e0-5e830d6e892c', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'import', 'FP_Event_Locations', 'module', 90, 0),
('5eb7cf6c-6483-544c-4a88-5e830d2f7900', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'list', 'Spots', 'module', 90, 0),
('5ed57d31-6b0a-02fc-e698-5f003a22fb0a', '2020-07-04 08:16:22', '2020-07-04 08:16:22', '1', '1', 'edit', 'stic_Payments', 'module', 90, 0),
('5f1232da-ab6e-bef3-acec-5e830dee2789', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'list', 'AOW_Processed', 'module', 90, 0),
('5f5d9049-b3bd-2b86-af40-5e830d744f0c', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'massupdate', 'AOS_Products', 'module', 90, 0),
('5f749010-8786-a4ef-6d51-5e830dc0e1d2', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'export', 'ProjectTask', 'module', 90, 0),
('5fb636df-b173-527c-a404-5e830d8b2eb4', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'view', 'SurveyQuestionResponses', 'module', 90, 0),
('60065558-4b73-5812-0ab7-5f003a829171', '2020-07-04 08:16:22', '2020-07-04 08:16:22', '1', '1', 'export', 'KReports', 'module', 90, 0),
('6081c8d1-3bc6-02ca-909e-5e830d8b23ee', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'massupdate', 'Accounts', 'module', 90, 0),
('60861783-3692-1deb-3ae4-5e830d88fdf1', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'access', 'EmailMarketing', 'module', 89, 0),
('6126bece-174d-bdc1-78b3-5e830d6fc363', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'export', 'FP_Event_Locations', 'module', 90, 0),
('62cbf05a-1a14-84a3-cb05-5e830d49c389', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'edit', 'Spots', 'module', 90, 0),
('63994019-e0cd-ad45-9722-5f003a2e4f1d', '2020-07-04 08:16:22', '2020-07-04 08:16:22', '1', '1', 'delete', 'stic_Payments', 'module', 90, 0),
('63e1336d-b1e4-528f-50bd-5e830d174df9', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'edit', 'AOW_Processed', 'module', 90, 0),
('64856f2d-32e5-2747-03d3-5e830d606c5a', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'list', 'SurveyQuestionResponses', 'module', 90, 0),
('64ad8d1c-afba-186d-bc1f-5e830d1a6b60', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'massupdate', 'ProjectTask', 'module', 90, 0),
('64fca3b6-c87e-5626-04c0-5e830dd46625', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'massupdate', 'FP_Event_Locations', 'module', 90, 0),
('652600bc-8302-42a4-7328-5e830d18b0db', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'view', 'EmailMarketing', 'module', 90, 0),
('66cbe833-aad0-003e-5bb5-5e830dbefb72', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'delete', 'Spots', 'module', 90, 0),
('67bc804b-2ca7-7d17-2de1-5f003a8ee3a6', '2020-07-04 08:16:22', '2020-07-04 08:16:22', '1', '1', 'import', 'stic_Payments', 'module', 90, 0),
('68154b8b-3157-5a86-eed7-5e830d4c88ec', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'delete', 'AOW_Processed', 'module', 90, 0),
('69a0e8e9-57ed-daff-c4e1-5e830dc65276', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'list', 'EmailMarketing', 'module', 90, 0),
('69e63447-1087-655e-fb1b-5e830db610bf', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'edit', 'SurveyQuestionResponses', 'module', 90, 0),
('6b26d9de-e707-6430-f46f-5e830de248d3', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'import', 'Spots', 'module', 90, 0),
('6b76071b-983b-6831-0b81-5e830d00effe', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'import', 'AM_ProjectTemplates', 'module', 90, 0),
('6b8672c3-a763-dbad-43aa-5f003a2c9a69', '2020-07-04 08:16:22', '2020-07-04 08:16:22', '1', '1', 'export', 'stic_Payments', 'module', 90, 0),
('6c9e7312-0580-b56f-8c2b-5e830dcb262f', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'import', 'AOW_Processed', 'module', 90, 0),
('6e33edb8-4cf2-4386-8e6d-5e830dd83111', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'edit', 'EmailMarketing', 'module', 90, 0),
('6e43055a-bb25-0530-bbf9-5e830d95147f', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'delete', 'AOP_Case_Events', 'module', 90, 0),
('6e641f33-e6ad-ab91-08ea-5e830df228be', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'delete', 'SurveyQuestionResponses', 'module', 90, 0),
('6f04f64f-4564-e9b0-d917-5f003aa47bc4', '2020-07-04 08:16:22', '2020-07-04 08:16:22', '1', '1', 'massupdate', 'KReports', 'module', 90, 0),
('6f0706a8-bac5-5fc3-01db-5e830da30db6', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'massupdate', 'ProspectLists', 'module', 90, 0),
('6f11848f-11ac-f11f-0259-5e830d7fded9', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'export', 'Spots', 'module', 90, 0),
('6f4537dc-2268-2959-425c-5f003a80f749', '2020-07-04 08:16:22', '2020-07-04 08:16:22', '1', '1', 'massupdate', 'stic_Payments', 'module', 90, 0),
('6fe2f647-2df2-b568-4285-5e830d155c8c', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'access', 'jjwg_Address_Cache', 'module', 89, 0),
('70478ce3-60c2-fe9b-739e-5e830df658a5', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'access', 'AOS_Invoices', 'module', 89, 0),
('70a38cc3-6d1f-0c03-c4a5-5e830dfc0a0a', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'access', 'Emails', 'module', 89, 0),
('70e38336-b166-5e40-3688-5e830d4cdd39', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'access', 'AOK_Knowledge_Base_Categories', 'module', 89, 0),
('70e85932-11a3-05af-e2b9-5e830d8a98ff', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'access', 'TemplateSectionLine', 'module', 89, 0),
('721b06c9-f699-01a7-54c4-5e830d8d5ff5', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'delete', 'SecurityGroups', 'module', 90, 0),
('72219b3a-30ac-7ac3-7e63-5e830db3bddc', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'export', 'AOW_Processed', 'module', 90, 0),
('7282fdf2-056b-e225-a2c3-5e830d36c84d', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'delete', 'EmailMarketing', 'module', 90, 0),
('7299c4bb-d57a-54c4-4244-5e830d8fc575', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'import', 'SurveyQuestionResponses', 'module', 90, 0),
('7331a2c2-61c7-01c7-81c4-5e830dd07bc3', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'massupdate', 'Spots', 'module', 90, 0),
('7340e2ab-5688-c2dc-843b-5f003a34cb12', '2020-07-04 08:16:22', '2020-07-04 08:16:22', '1', '1', 'access', 'stic_Registrations', 'module', 89, 0),
('7390907a-ebb9-6ccc-44eb-5f003a1858c2', '2020-07-04 08:16:22', '2020-07-04 08:16:22', '1', '1', 'access', 'stic_Accounts_Relationships', 'module', 89, 0),
('73a2d75a-ff6a-f47e-d02a-5e830d03d049', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'access', 'AOR_Reports', 'module', 89, 0),
('73bfc25e-3073-d0a1-4227-5e830d26b50f', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'view', 'jjwg_Address_Cache', 'module', 90, 0),
('744174ca-5ddb-7b74-ab60-5e830d7af34b', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'view', 'Emails', 'module', 90, 0),
('7476dc02-c8d5-4d49-ed3a-5e830d8a4e12', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'access', 'Alerts', 'module', 89, 0),
('74d55c7b-10db-14a4-20bd-5e830db46de9', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'view', 'AOS_Invoices', 'module', 90, 0),
('750761a3-dfe1-7d7b-b130-5e830df43784', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'view', 'AOK_Knowledge_Base_Categories', 'module', 90, 0),
('75dc3330-eeac-79b1-6602-5e830d10b816', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'view', 'TemplateSectionLine', 'module', 90, 0),
('76c60a67-ff60-1a37-0255-5e830d6c0cf4', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'export', 'SurveyQuestionResponses', 'module', 90, 0),
('76e762c3-04a9-3663-b1db-5e830d1eb6cf', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'massupdate', 'AOW_Processed', 'module', 90, 0),
('773bf23b-b09a-fc4b-2f9d-5f003ab119e0', '2020-07-04 08:16:22', '2020-07-04 08:16:22', '1', '1', 'view', 'stic_Registrations', 'module', 90, 0),
('77fa3d91-6b74-c035-8434-5f003a59e021', '2020-07-04 08:16:22', '2020-07-04 08:16:22', '1', '1', 'view', 'stic_Accounts_Relationships', 'module', 90, 0),
('780bab4c-3885-93bf-4eb3-5e830da2464e', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'list', 'Emails', 'module', 90, 0),
('78145555-4d50-1ab5-f506-5e830dec06ff', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'list', 'jjwg_Address_Cache', 'module', 90, 0),
('785e2326-3ab3-8155-61fe-5e830d863753', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'view', 'Alerts', 'module', 90, 0),
('78c80e13-ebf8-17f2-8b2f-5e830d9f8280', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'list', 'AOS_Product_Categories', 'module', 90, 0),
('790a5180-9fcb-a806-a733-5e830dda9498', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'list', 'AOK_Knowledge_Base_Categories', 'module', 90, 0),
('7979df21-0341-53b3-3bdf-5e830db8c004', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'list', 'AOS_Invoices', 'module', 90, 0),
('7ad1e601-6cd3-66cd-3f43-5e830d818b2d', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'list', 'TemplateSectionLine', 'module', 90, 0),
('7b10de62-e718-efce-529d-5f003a5cbbc7', '2020-07-04 08:16:22', '2020-07-04 08:16:22', '1', '1', 'list', 'stic_Registrations', 'module', 90, 0),
('7b9ecfd8-a889-9bae-7d68-5e830dd63db8', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'massupdate', 'SurveyQuestionResponses', 'module', 90, 0),
('7bc646cd-6d5e-1d81-9354-5e830d76eb58', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'import', 'EmailMarketing', 'module', 90, 0),
('7be386c3-3780-1486-98f7-5e830d53eea6', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'edit', 'Emails', 'module', 90, 0),
('7c1e6602-771b-ba16-5797-5e830d689521', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'view', 'AOR_Reports', 'module', 90, 0),
('7c4b159d-9d33-eed6-db57-5e830d3d0b7a', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'edit', 'jjwg_Address_Cache', 'module', 90, 0),
('7c53af59-c973-82d0-9004-5e830d408ad5', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'list', 'Alerts', 'module', 90, 0),
('7c69bbf9-3288-1376-89c3-5e830dda4acc', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'access', 'Bugs', 'module', 89, 0),
('7c90d989-0f11-ddf1-e93e-5f003a28b2a6', '2020-07-04 08:16:22', '2020-07-04 08:16:22', '1', '1', 'list', 'stic_Accounts_Relationships', 'module', 90, 0),
('7d02af15-09ce-c753-011b-5e830dd85938', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'edit', 'AOK_Knowledge_Base_Categories', 'module', 90, 0),
('7f15d045-0420-629e-72a0-5f003ac3d296', '2020-07-04 08:16:22', '2020-07-04 08:16:22', '1', '1', 'edit', 'stic_Registrations', 'module', 90, 0),
('7f758976-455d-8f78-8de2-5e830db7eb99', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'export', 'EmailMarketing', 'module', 90, 0),
('7f9a199b-7666-0e8f-0127-5e830da3489f', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'delete', 'Emails', 'module', 90, 0),
('7fe519b7-2a11-573d-6796-5e830d7e905e', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'list', 'AOR_Reports', 'module', 90, 0),
('7ffa4afc-358f-0620-4353-5e830dde6734', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'edit', 'Alerts', 'module', 90, 0),
('8067a965-3e66-af37-70f1-5e830d381bd3', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'edit', 'TemplateSectionLine', 'module', 90, 0),
('807fa622-6a93-7ca5-bb25-5e830d9e9318', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'delete', 'jjwg_Address_Cache', 'module', 90, 0),
('80bbea78-13ea-bcff-3b5e-5e830de99f56', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'view', 'Bugs', 'module', 90, 0),
('81096723-21a7-6312-55a5-5e830db25fb0', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'delete', 'AOK_Knowledge_Base_Categories', 'module', 90, 0),
('82394f08-1904-31d8-b122-5f003a5e4422', '2020-07-04 08:16:22', '2020-07-04 08:16:22', '1', '1', 'edit', 'stic_Accounts_Relationships', 'module', 90, 0),
('83061c6a-1348-92e2-fabf-5f003a73d44a', '2020-07-04 08:16:22', '2020-07-04 08:16:22', '1', '1', 'delete', 'stic_Registrations', 'module', 90, 0),
('832ddb5c-0ebf-c274-4e0e-5e830db70cdf', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'massupdate', 'EmailMarketing', 'module', 90, 0),
('83763af8-805d-6ec0-af28-5e830da4bfd9', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'access', 'AOD_IndexEvent', 'module', 89, 0),
('83829bc1-70c2-4066-a98f-5e830d179a09', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'edit', 'AOS_Invoices', 'module', 90, 0),
('83868b2c-2b4d-86ca-f04b-5e830d267c75', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'import', 'Emails', 'module', 90, 0),
('83f1ffe6-36a2-de5d-739f-5e830dcf0840', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'delete', 'Alerts', 'module', 90, 0),
('8458d2ba-213d-0f6c-8357-5e830de1f4f7', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'delete', 'TemplateSectionLine', 'module', 90, 0),
('848d6c8f-acc7-c98b-793d-5e830d55eeba', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'list', 'Bugs', 'module', 90, 0),
('85129c0c-be61-dc94-c9c4-5e830d419aff', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'import', 'AOK_Knowledge_Base_Categories', 'module', 90, 0),
('8521de89-784f-911a-5ac5-5e830dca52e1', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'edit', 'AOR_Reports', 'module', 90, 0),
('86a31c92-0fc1-eb55-bef5-5f003ae19ce7', '2020-07-04 08:16:22', '2020-07-04 08:16:22', '1', '1', 'delete', 'stic_Accounts_Relationships', 'module', 90, 0),
('87223ffb-21ed-7625-515c-5f003a97e5b3', '2020-07-04 08:16:22', '2020-07-04 08:16:22', '1', '1', 'import', 'stic_Registrations', 'module', 90, 0),
('873792f9-1439-fd08-4cae-5e830dab228d', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'export', 'Emails', 'module', 90, 0),
('87c1fac3-5f5d-f9c0-f428-5e830df80a94', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'import', 'Alerts', 'module', 90, 0),
('87db22d8-b36a-1351-eaa6-5e830dcd2dba', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'delete', 'AOS_Invoices', 'module', 90, 0),
('87e37010-9014-9400-a3cf-5e830d3f9811', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'view', 'AOD_IndexEvent', 'module', 90, 0),
('884f34c5-67fa-791e-0977-5e830ddea291', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'edit', 'Bugs', 'module', 90, 0),
('887eda48-7178-edf8-9096-5e830d95302f', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'import', 'TemplateSectionLine', 'module', 90, 0),
('8928c5b7-e244-eef2-ace8-5e830d279750', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'delete', 'AOR_Reports', 'module', 90, 0),
('897400a7-b311-3f4b-a321-5e830dc7faae', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'import', 'jjwg_Markers', 'module', 90, 0),
('89ff72f6-5e8e-3893-6e84-5e830d79410e', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'import', 'jjwg_Address_Cache', 'module', 90, 0),
('8a225b1d-7f28-5d08-bdba-5e830d2bc1e1', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'export', 'AOK_Knowledge_Base_Categories', 'module', 90, 0),
('8aba77e2-7021-40da-827f-5f003aba5171', '2020-07-04 08:16:22', '2020-07-04 08:16:22', '1', '1', 'import', 'stic_Accounts_Relationships', 'module', 90, 0),
('8afade02-10aa-03ea-33cc-5e830db377b6', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'access', 'Campaigns', 'module', 89, 0),
('8b079ea3-3d59-aa03-8ca8-5f003a091c9c', '2020-07-04 08:16:22', '2020-07-04 08:16:22', '1', '1', 'export', 'stic_Registrations', 'module', 90, 0),
('8b0e6dd1-f899-7df6-40fa-5e830d9471bf', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'massupdate', 'Emails', 'module', 90, 0),
('8b71fcbd-0d30-10e2-15c2-5e830dc74bde', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'export', 'Alerts', 'module', 90, 0),
('8b8d7eb2-f0d3-3aa6-5c19-5e830d920c34', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'access', 'Opportunities', 'module', 89, 0),
('8c30816c-5748-026f-f7e9-5e830ded89de', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'list', 'AOD_IndexEvent', 'module', 90, 0),
('8c32ded8-905e-637e-7f48-5e830de96041', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'delete', 'Bugs', 'module', 90, 0),
('8c5cc04e-85a4-c7fd-86c3-5e830d6a74ed', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'export', 'TemplateSectionLine', 'module', 90, 0),
('8c620993-b94e-7497-ec50-5f003ab34b41', '2020-07-04 08:16:22', '2020-07-04 08:16:22', '1', '1', 'import', 'stic_Events', 'module', 90, 0),
('8c75e8bd-c551-5058-faf2-5e830d0dbfa8', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'import', 'AOS_Invoices', 'module', 90, 0),
('8dd44352-077e-11f0-fffe-5e830db4c65d', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'export', 'jjwg_Address_Cache', 'module', 90, 0),
('8df8f42c-f26f-53d4-5b71-5e830d63664d', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'import', 'AOR_Reports', 'module', 90, 0),
('8e99b5cf-05d7-5746-df85-5e830da902c6', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'massupdate', 'AOK_Knowledge_Base_Categories', 'module', 90, 0),
('8f01f6a9-4efe-a5b6-80da-5f003a496710', '2020-07-04 08:16:22', '2020-07-04 08:16:22', '1', '1', 'massupdate', 'stic_Registrations', 'module', 90, 0),
('8f519121-942a-ae9d-49d6-5e830db8314d', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'massupdate', 'Alerts', 'module', 90, 0),
('8f721603-8b83-072d-b443-5f003a5d0701', '2020-07-04 08:16:22', '2020-07-04 08:16:22', '1', '1', 'export', 'stic_Accounts_Relationships', 'module', 90, 0),
('8fc40c7c-6bfd-5b75-897a-5e830d60cea4', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'view', 'Campaigns', 'module', 90, 0),
('8fe302e2-11f8-fb83-9ac1-5e830d823ca1', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'view', 'Opportunities', 'module', 90, 0),
('8ff2898a-008c-9559-3e59-5e830d86db4e', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'import', 'Bugs', 'module', 90, 0),
('8ffad0ff-c44e-f024-48b1-5e830dd086ce', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'edit', 'AOD_IndexEvent', 'module', 90, 0),
('90814612-5aab-616f-0dc1-5e830d2bd5d9', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'massupdate', 'TemplateSectionLine', 'module', 90, 0),
('90ea814d-8c1c-8f80-0d84-5e830d567a59', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'export', 'AOS_Invoices', 'module', 90, 0),
('913803cc-50f4-1f90-bb09-5e830d90e0d5', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'export', 'Leads', 'module', 90, 0),
('91e65b8f-8f97-b72a-0990-5e830d9bcd69', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'massupdate', 'jjwg_Address_Cache', 'module', 90, 0),
('93802421-1f29-a848-9602-5e830dfa9d8f', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'delete', 'AOD_IndexEvent', 'module', 90, 0),
('93990c59-debc-baa8-7250-5e830d4028f7', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'export', 'AOR_Reports', 'module', 90, 0),
('93b53ada-6959-f77b-052e-5e830d961ce0', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'list', 'Campaigns', 'module', 90, 0),
('93cd9ab5-b894-8580-7896-5e830db2a994', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'export', 'Bugs', 'module', 90, 0),
('944fcab6-487c-8c9a-e97d-5f003aa89d4c', '2020-07-04 08:16:22', '2020-07-04 08:16:22', '1', '1', 'massupdate', 'stic_Accounts_Relationships', 'module', 90, 0),
('94adc791-9354-f5fd-5410-5e830d6d18b4', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'list', 'Opportunities', 'module', 90, 0),
('95331b4f-0805-71b8-2145-5f003a50bf2f', '2020-07-04 08:16:22', '2020-07-04 08:16:22', '1', '1', 'access', 'stic_Remittances', 'module', 89, 0),
('954476b0-4b9b-0c43-5369-5e830d9ea2b5', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'massupdate', 'AOS_Invoices', 'module', 90, 0),
('960b908a-03fa-4d7e-d677-5e830da977f3', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'access', 'jjwg_Maps', 'module', 89, 0),
('975b37ee-c9c0-ae65-c4c4-5e830d3e457f', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'import', 'AOD_IndexEvent', 'module', 90, 0),
('976d62dd-d292-57ac-1a9e-5e830d8d20df', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'massupdate', 'Bugs', 'module', 90, 0),
('97d9e419-ad4d-be79-5a16-5e830dd9db1e', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'massupdate', 'AOR_Reports', 'module', 90, 0),
('97f65c2b-573e-a513-20e5-5e830d2f3711', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'edit', 'Campaigns', 'module', 90, 0),
('988b40f0-f5aa-cf84-ba25-5f003a499f9c', '2020-07-04 08:16:22', '2020-07-04 08:16:22', '1', '1', 'access', 'stic_Attendances', 'module', 89, 0),
('99414061-7842-399d-b6ac-5f003ab50e6b', '2020-07-04 08:16:22', '2020-07-04 08:16:22', '1', '1', 'view', 'stic_Remittances', 'module', 90, 0),
('994af3aa-eb6d-4722-89b0-5e830d87389b', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'edit', 'Opportunities', 'module', 90, 0),
('998ca628-c7ea-b95f-58f2-5e830db6292c', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'access', 'SurveyQuestions', 'module', 89, 0),
('9abde0f5-e1b0-770c-50f2-5e830d0024a0', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'export', 'AOD_IndexEvent', 'module', 90, 0),
('9ba903cd-32e2-616e-ea3a-5e830d77c782', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'view', 'AOW_WorkFlow', 'module', 90, 0),
('9bce1737-1acc-22a1-7cc0-5e830d52b3cc', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'view', 'jjwg_Maps', 'module', 90, 0),
('9c0816ad-5906-0057-a1d0-5e830d4bc9e3', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'delete', 'Campaigns', 'module', 90, 0),
('9c610772-d2b1-765a-0015-5e830da85af6', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'export', 'Tasks', 'module', 90, 0),
('9d70865d-f8a7-d23e-342c-5f003ac265fe', '2020-07-04 08:16:22', '2020-07-04 08:16:22', '1', '1', 'list', 'stic_Remittances', 'module', 90, 0),
('9d73c3c2-8863-4786-abb6-5e830d5f676d', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'delete', 'Opportunities', 'module', 90, 0),
('9df86585-ea1e-ae80-03f3-5e830daa9c02', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'view', 'SurveyQuestions', 'module', 90, 0),
('9e404754-44ec-6b67-f6c5-5e830de455cf', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'massupdate', 'AOD_IndexEvent', 'module', 90, 0),
('9f88f32e-6cdf-1cc4-adb3-5f003a68e5ff', '2020-07-04 08:16:22', '2020-07-04 08:16:22', '1', '1', 'view', 'stic_Attendances', 'module', 90, 0),
('9fe125dd-2d53-12ed-5f02-5e830ddc7e82', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'access', 'EAPM', 'module', 89, 0),
('a0415a8d-9702-a8f6-ff6d-5e830defcb9d', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'import', 'Campaigns', 'module', 90, 0),
('a093f611-472d-f6db-e076-5e830d7d3719', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'list', 'jjwg_Maps', 'module', 90, 0),
('a13ff16d-1df3-94dc-6793-5e830d6d8438', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'import', 'Opportunities', 'module', 90, 0),
('a15322f5-b0d3-6e3e-db57-5f003a7c4cc3', '2020-07-04 08:16:22', '2020-07-04 08:16:22', '1', '1', 'edit', 'stic_Remittances', 'module', 90, 0),
('a23c8011-242f-31b2-b795-5e830ddc9152', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'list', 'SurveyQuestions', 'module', 90, 0),
('a2870827-97bf-6236-e6ee-5e830d2e0b9e', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'list', 'Notes', 'module', 90, 0),
('a3e10b2d-da2a-5c74-16a3-5e830d5c8981', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'access', 'FP_events', 'module', 89, 0),
('a43f042c-3c6e-5152-fffc-5e830d8c64f3', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'view', 'EAPM', 'module', 90, 0),
('a45ad3f8-c15a-e94e-9549-5e830d34d873', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'export', 'Campaigns', 'module', 90, 0),
('a50239fe-38e1-0ee0-3010-5e830d0a13f8', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'export', 'Opportunities', 'module', 90, 0),
('a50706c5-c45e-0c84-65e9-5e830df0e5de', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'view', 'Surveys', 'module', 90, 0),
('a51b37a6-2a39-6b00-ebb3-5f003aa05ac8', '2020-07-04 08:16:22', '2020-07-04 08:16:22', '1', '1', 'list', 'stic_Attendances', 'module', 90, 0),
('a51c558b-668b-cc92-0612-5f003a4f8cfd', '2020-07-04 08:16:22', '2020-07-04 08:16:22', '1', '1', 'delete', 'stic_Remittances', 'module', 90, 0),
('a61d46ff-d49c-6f33-c935-5e830d94c238', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'edit', 'SurveyQuestions', 'module', 90, 0),
('a6b59a47-b834-f52e-a6a3-5e830d33d03a', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'edit', 'jjwg_Maps', 'module', 90, 0),
('a79f8b03-e670-dae8-4a72-5e830d624b65', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'access', 'Meetings', 'module', 89, 0),
('a7af056c-ddab-4545-14e3-5e830d9787f9', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'access', 'Project', 'module', 89, 0),
('a873ee74-37ef-17f1-516f-5e830d5422c8', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'massupdate', 'Opportunities', 'module', 90, 0),
('a8979a0c-4f51-ac9e-8551-5e830da56bf3', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'list', 'EAPM', 'module', 90, 0);
INSERT INTO `acl_actions` (`id`, `date_entered`, `date_modified`, `modified_user_id`, `created_by`, `name`, `category`, `acltype`, `aclaccess`, `deleted`) VALUES
('a89eb551-52f6-d114-2e48-5e830d3cefef', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'access', 'AOK_KnowledgeBase', 'module', 89, 0),
('a8a5bbe6-e454-49c9-2ea7-5e830de1d863', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'massupdate', 'Campaigns', 'module', 90, 0),
('a90b1393-2fc8-9138-2dac-5f003a3208e8', '2020-07-04 08:16:22', '2020-07-04 08:16:22', '1', '1', 'import', 'stic_Remittances', 'module', 90, 0),
('a93b55e7-ebc6-8d55-4369-5f003aa57871', '2020-07-04 08:16:22', '2020-07-04 08:16:22', '1', '1', 'access', 'Employees', 'module', 89, 0),
('a9ffeb63-db7a-7f19-049e-5e830d22464a', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'delete', 'SurveyQuestions', 'module', 90, 0),
('aa0c5e79-c822-8cd9-8760-5f003a6eae21', '2020-07-04 08:16:22', '2020-07-04 08:16:22', '1', '1', 'edit', 'stic_Attendances', 'module', 90, 0),
('ab079bfb-d050-3a75-5fe2-5e830d8f17f0', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'delete', 'jjwg_Maps', 'module', 90, 0),
('ab356d9e-d402-9522-237f-5e830d1e14ba', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'view', 'Meetings', 'module', 90, 0),
('acab8c16-a6e7-7d06-ddbd-5e830def4b94', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'access', 'Documents', 'module', 89, 0),
('acb6edd6-4756-7b2d-829f-5e830d16b671', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'edit', 'EAPM', 'module', 90, 0),
('acce2199-2611-400a-3efa-5e830dc10d1f', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'view', 'AOK_KnowledgeBase', 'module', 90, 0),
('ad34b226-5672-4828-ed35-5f003a77abe8', '2020-07-04 08:16:22', '2020-07-04 08:16:22', '1', '1', 'export', 'stic_Remittances', 'module', 90, 0),
('ae0241f4-4119-d45f-aa68-5e830d0f8ace', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'import', 'SurveyQuestions', 'module', 90, 0),
('ae920598-1f6e-5907-2a67-5f003a7e27d9', '2020-07-04 08:16:22', '2020-07-04 08:16:22', '1', '1', 'delete', 'stic_Attendances', 'module', 90, 0),
('af3ddf90-3953-a76d-5149-5e830dcdfe22', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'import', 'jjwg_Maps', 'module', 90, 0),
('af606b38-133e-c16b-aab2-5e830d2cf14c', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'list', 'Meetings', 'module', 90, 0),
('b03bbc81-571c-912d-d8d9-5e830daf7b66', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'access', 'Calls_Reschedule', 'module', 89, 0),
('b0c01a9d-b509-7244-b12b-5e830d1c5082', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'list', 'AOK_KnowledgeBase', 'module', 90, 0),
('b137189c-6dab-a71a-647e-5e830dd5a618', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'delete', 'EAPM', 'module', 90, 0),
('b175618e-a8a9-3c1f-899e-5f003ab3fcd1', '2020-07-04 08:16:22', '2020-07-04 08:16:22', '1', '1', 'massupdate', 'stic_Remittances', 'module', 90, 0),
('b1d31cb9-4b70-a495-2218-5e830d4ba9d3', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'view', 'Documents', 'module', 90, 0),
('b2091612-a6f1-71c9-94a1-5e830d783de3', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'export', 'SurveyQuestions', 'module', 90, 0),
('b2ef6753-859b-4992-4417-5f003a902290', '2020-07-04 08:16:22', '2020-07-04 08:16:22', '1', '1', 'import', 'stic_Attendances', 'module', 90, 0),
('b3ac90b1-e7dc-1709-5961-5e830d42b8a6', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'export', 'jjwg_Maps', 'module', 90, 0),
('b3ca5a32-89be-248a-71fd-5e830d499a7a', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'access', 'AOS_PDF_Templates', 'module', 89, 0),
('b4680b4e-dc06-f96a-d41e-5e830de3b24a', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'export', 'AM_ProjectTemplates', 'module', 90, 0),
('b484b77f-7d87-d554-b6a9-5e830dea5b33', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'access', 'AOS_Quotes', 'module', 89, 0),
('b48a0156-4b93-8753-8cf8-5e830d2d3646', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'import', 'AOP_Case_Events', 'module', 90, 0),
('b4994e20-9fff-8620-a24c-5e830d7181f5', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'view', 'Calls_Reschedule', 'module', 90, 0),
('b49eba22-fe82-6943-9a5c-5e830d04e725', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'edit', 'Meetings', 'module', 90, 0),
('b5247265-800f-57b2-abc5-5e830d3fb9a4', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'edit', 'AOK_KnowledgeBase', 'module', 90, 0),
('b5624f08-aef8-470c-8c00-5e830d3105b0', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'import', 'EAPM', 'module', 90, 0),
('b5cb57d8-f261-2999-e724-5f003a65b403', '2020-07-04 08:16:22', '2020-07-04 08:16:22', '1', '1', 'access', 'stic_Sessions', 'module', 89, 0),
('b61b3abb-7457-e343-2603-5e830df6dd2a', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'massupdate', 'SurveyQuestions', 'module', 90, 0),
('b7026e78-6b31-5b4b-6527-5e830d16d60d', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'list', 'Documents', 'module', 90, 0),
('b7405fd6-251e-8dcc-3c19-5f003a490dcd', '2020-07-04 08:16:22', '2020-07-04 08:16:22', '1', '1', 'export', 'stic_Attendances', 'module', 90, 0),
('b7f7fa18-9406-853a-b560-5e830dae6e2e', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'view', 'AOS_PDF_Templates', 'module', 90, 0),
('b8836595-d2ae-ab2a-8759-5e830d46dfab', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'view', 'AOS_Quotes', 'module', 90, 0),
('b8900b66-d9dc-5b5c-c989-5f003a078aa3', '2020-07-04 08:16:22', '2020-07-04 08:16:22', '1', '1', 'massupdate', 'stic_Settings', 'module', 90, 0),
('b8b1667c-c061-c58f-a8f6-5e830d0a55dd', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'delete', 'Meetings', 'module', 90, 0),
('b958ca38-cc41-1f63-dcb0-5e830d6ca022', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'export', 'EAPM', 'module', 90, 0),
('b99e18bb-6a5a-de68-484a-5e830d5ea404', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'list', 'Calls_Reschedule', 'module', 90, 0),
('b99e48f4-3fe7-1c16-5320-5e830d7f647f', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'access', 'AOD_Index', 'module', 89, 0),
('b9ee2f08-35fc-2bb4-8cbf-5f003a1fdc17', '2020-07-04 08:16:22', '2020-07-04 08:16:22', '1', '1', 'view', 'stic_Sessions', 'module', 90, 0),
('ba78abf0-1409-6ad9-dcfc-5e830d589f29', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'delete', 'AOK_KnowledgeBase', 'module', 90, 0),
('bb21e28e-f9ba-804c-c752-5f003ae8e1a8', '2020-07-04 08:16:22', '2020-07-04 08:16:22', '1', '1', 'massupdate', 'stic_Attendances', 'module', 90, 0),
('bb34770c-4de6-129c-2edc-5e830d46f387', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'edit', 'Documents', 'module', 90, 0),
('bc4fd8f2-6ce8-1990-af7f-5e830d3d1223', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'list', 'AOS_PDF_Templates', 'module', 90, 0),
('bc58b0d2-2cd9-e887-e4da-5e830dfd15f4', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'list', 'AOS_Quotes', 'module', 90, 0),
('bce55eb1-d70b-c5f3-d61b-5e830d71e663', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'access', 'SurveyResponses', 'module', 89, 0),
('bd0f107a-c392-6947-b260-5e830dc0aa61', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'import', 'Meetings', 'module', 90, 0),
('bd3671e0-d1a1-d0ce-4d40-5e830d2dfd72', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'view', 'AOD_Index', 'module', 90, 0),
('bdaa9462-5a8d-5a40-0aea-5e830d819c0f', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'access', 'Users', 'module', 89, 0),
('bdbeda91-92e6-dfd9-2238-5e830d03d05e', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'massupdate', 'EAPM', 'module', 90, 0),
('bde7ddfa-2509-ce08-6655-5e830da09e36', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'edit', 'Calls_Reschedule', 'module', 90, 0),
('bdf25b6d-d714-1a1c-846c-5f003a96b8b3', '2020-07-04 08:16:22', '2020-07-04 08:16:22', '1', '1', 'list', 'stic_Sessions', 'module', 90, 0),
('be43b1c0-1502-00a7-21ee-5e830d240818', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'massupdate', 'jjwg_Maps', 'module', 90, 0),
('be8815d2-8614-83b9-c33c-5e830d94f79f', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'import', 'AOK_KnowledgeBase', 'module', 90, 0),
('bf0da854-b806-a8ca-3a63-5e830de282e5', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'delete', 'Documents', 'module', 90, 0),
('bfca0c84-09fa-e767-286a-5e830ddb953f', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'import', 'SecurityGroups', 'module', 90, 0),
('c00e8d10-5ba6-6e5c-9efe-5e830d709d12', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'edit', 'AOS_Quotes', 'module', 90, 0),
('c07445d5-fa72-76a1-d55d-5e830ddd671d', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'edit', 'AOS_PDF_Templates', 'module', 90, 0),
('c0a55d92-49e8-9cd3-6ab5-5e830d87b5df', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'list', 'AOD_Index', 'module', 90, 0),
('c0c6b15c-d05f-8cbd-f68f-5f003a030217', '2020-07-04 08:16:22', '2020-07-04 08:16:22', '1', '1', 'access', 'stic_Contacts_Relationships', 'module', 89, 0),
('c0d73d28-332a-e673-ed00-5e830d0cdcfc', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'export', 'Meetings', 'module', 90, 0),
('c14c673b-4897-2182-323d-5e830deec5e2', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'view', 'SurveyResponses', 'module', 90, 0),
('c1828769-761d-fb7b-4cbb-5e830d81d5b8', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'view', 'Users', 'module', 90, 0),
('c1eb9e93-1d83-346d-ec2e-5e830dcff4d7', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'delete', 'Calls_Reschedule', 'module', 90, 0),
('c297a85a-1690-fdc6-46ec-5e830d20417a', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'export', 'AOK_KnowledgeBase', 'module', 90, 0),
('c2c3814e-36a1-6885-be5a-5e830d088487', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'import', 'Documents', 'module', 90, 0),
('c3c86a10-35e2-d452-5cb0-5e830dbc2cdd', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'delete', 'AOS_Quotes', 'module', 90, 0),
('c42ee7ae-93cc-f80c-a56f-5f003ac47b60', '2020-07-04 08:16:22', '2020-07-04 08:16:22', '1', '1', 'edit', 'stic_Sessions', 'module', 90, 0),
('c4602dbf-c56d-2554-40e0-5e830df350e7', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'edit', 'AOD_Index', 'module', 90, 0),
('c4aa852e-be64-f247-7b7e-5e830d0a0ec3', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'massupdate', 'Meetings', 'module', 90, 0),
('c52c845a-baaa-78a1-8fdd-5e830d4c8c4e', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'list', 'SurveyResponses', 'module', 90, 0),
('c52c9ce2-e88a-b0b6-54de-5e830d2a229c', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'list', 'Users', 'module', 90, 0),
('c5e5b381-26b1-6b22-0623-5f003aada17f', '2020-07-04 08:16:22', '2020-07-04 08:16:22', '1', '1', 'view', 'stic_Contacts_Relationships', 'module', 90, 0),
('c5ea6308-bc65-bf2c-b59a-5e830d9968b3', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'import', 'Calls_Reschedule', 'module', 90, 0),
('c6629c63-1300-f2bc-7c87-5e830d5b8b14', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'massupdate', 'AOK_KnowledgeBase', 'module', 90, 0),
('c7e568bb-bf8c-ca24-376d-5e830de98e14', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'export', 'Documents', 'module', 90, 0),
('c7e92392-9d29-5781-654b-5e830d486bd4', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'delete', 'AOD_Index', 'module', 90, 0),
('c8033c83-6c92-b10f-cae1-5e830da1151e', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'import', 'AOS_Quotes', 'module', 90, 0),
('c83d86a6-2935-0f56-e003-5f003afee876', '2020-07-04 08:16:22', '2020-07-04 08:16:22', '1', '1', 'delete', 'stic_Sessions', 'module', 90, 0),
('c85e384a-02f7-e9e5-3496-5e830d5bba9e', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'delete', 'AOS_PDF_Templates', 'module', 90, 0),
('c8ea3868-20c7-75c9-77c4-5e830d73fe83', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'access', 'EmailTemplates', 'module', 89, 0),
('c9007ff5-06ef-b07b-5739-5e830dec5391', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'edit', 'Users', 'module', 90, 0),
('c9446057-42e0-71b9-1b8f-5e830dd5fd08', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'edit', 'SurveyResponses', 'module', 90, 0),
('c9d09810-846b-47cf-bb98-5e830d6088e2', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'export', 'Calls_Reschedule', 'module', 90, 0),
('ca0ea5f8-d597-108f-46ef-5f003a5c79b8', '2020-07-04 08:16:22', '2020-07-04 08:16:22', '1', '1', 'view', 'Employees', 'module', 90, 0),
('ca48dd9d-2fcb-a6f0-1868-5f003a9faeb7', '2020-07-04 08:16:22', '2020-07-04 08:16:22', '1', '1', 'list', 'stic_Contacts_Relationships', 'module', 90, 0),
('cb754fda-386e-5bd4-609b-5e830d68ede7', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'import', 'AOD_Index', 'module', 90, 0),
('cbc33da8-1813-5812-905b-5e830de9cf48', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'massupdate', 'Documents', 'module', 90, 0),
('cbe10af3-6605-2b1b-bbd7-5f003a29d373', '2020-07-04 08:16:22', '2020-07-04 08:16:22', '1', '1', 'export', 'stic_Events', 'module', 90, 0),
('cc19abda-161f-b114-3888-5f003a9a9f25', '2020-07-04 08:16:22', '2020-07-04 08:16:22', '1', '1', 'import', 'stic_Sessions', 'module', 90, 0),
('cc20372b-ec3e-2940-a1b2-5e830dc5d701', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'export', 'AOS_Quotes', 'module', 90, 0),
('cc988cea-ff97-c134-c372-5e830dfd7e76', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'view', 'EmailTemplates', 'module', 90, 0),
('cd48acea-21be-2b41-67dc-5e830d3c8284', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'delete', 'SurveyResponses', 'module', 90, 0),
('cd6e6d1d-1100-47c4-a596-5e830d11a1a6', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'delete', 'Users', 'module', 90, 0),
('cdb9276a-75c9-b12a-e763-5e830dc04c4b', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'massupdate', 'Calls_Reschedule', 'module', 90, 0),
('ce1df178-b0ed-c922-0036-5f003a1bbe13', '2020-07-04 08:16:22', '2020-07-04 08:16:22', '1', '1', 'list', 'Employees', 'module', 90, 0),
('ce732220-26d0-bedb-110b-5f003aba32af', '2020-07-04 08:16:22', '2020-07-04 08:16:22', '1', '1', 'edit', 'stic_Contacts_Relationships', 'module', 90, 0),
('ce8004b7-1fc8-ac58-35d6-5e830d192b1c', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'import', 'AOS_PDF_Templates', 'module', 90, 0),
('cef3100d-5ce6-b61a-e80f-5e830d96319c', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'export', 'AOD_Index', 'module', 90, 0),
('cf0f0b61-cf52-b382-e0b7-5e830d1b3a5a', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'edit', 'AOS_Product_Categories', 'module', 90, 0),
('cfd9531d-48e5-ecf9-a25f-5f003aa890ca', '2020-07-04 08:16:22', '2020-07-04 08:16:22', '1', '1', 'export', 'stic_Sessions', 'module', 90, 0),
('d0275cbb-4c8f-94d0-e764-5e830d23aa98', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'list', 'EmailTemplates', 'module', 90, 0),
('d06de5f7-5edb-c9c6-1928-5e830d86006f', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'massupdate', 'AOS_Quotes', 'module', 90, 0),
('d163011b-80a1-8e17-dd83-5e830d0c72c6', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'import', 'SurveyResponses', 'module', 90, 0),
('d17a5ec4-69c1-c381-9100-5e830d2b1b1d', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'import', 'Users', 'module', 90, 0),
('d25c73a3-a394-9db6-5a74-5f003a607951', '2020-07-04 08:16:22', '2020-07-04 08:16:22', '1', '1', 'edit', 'Employees', 'module', 90, 0),
('d29d74ee-1eef-595f-07de-5e830dd9c224', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'access', 'SurveyQuestionOptions', 'module', 89, 0),
('d2b074a6-de8e-e579-c817-5e830dad707a', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'massupdate', 'AOD_Index', 'module', 90, 0),
('d2d2b2b3-3ce4-6e1c-b69b-5f003ab7d8ab', '2020-07-04 08:16:22', '2020-07-04 08:16:22', '1', '1', 'delete', 'stic_Contacts_Relationships', 'module', 90, 0),
('d2fed13b-9cc9-79f0-696c-5e830dddbc71', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'export', 'AOS_PDF_Templates', 'module', 90, 0),
('d4001b6b-c8e3-30e6-5977-5f003a8ac5f4', '2020-07-04 08:16:22', '2020-07-04 08:16:22', '1', '1', 'massupdate', 'stic_Sessions', 'module', 90, 0),
('d45b75a0-aa4c-8989-cd29-5e830d5a80e4', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'edit', 'EmailTemplates', 'module', 90, 0),
('d599e59c-6392-ae6d-2ba2-5e830d51fade', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'export', 'Users', 'module', 90, 0),
('d5dfafc9-b639-e05d-678f-5e830d4ddf4d', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'export', 'SurveyResponses', 'module', 90, 0),
('d66500f4-16a9-1f01-668f-5e830db1d6ea', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'massupdate', 'Leads', 'module', 90, 0),
('d693f5ee-8b4c-9dee-84ee-5f003aa611ae', '2020-07-04 08:16:22', '2020-07-04 08:16:22', '1', '1', 'delete', 'Employees', 'module', 90, 0),
('d69d083c-6072-6ff1-5696-5e830d5f77d5', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'export', 'jjwg_Markers', 'module', 90, 0),
('d6e6e799-e401-8d71-7cca-5f003a8ed5d4', '2020-07-04 08:16:22', '2020-07-04 08:16:22', '1', '1', 'import', 'stic_Contacts_Relationships', 'module', 90, 0),
('d778ab4f-2baa-f67a-37c3-5e830d4f233d', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'massupdate', 'AOS_PDF_Templates', 'module', 90, 0),
('d791bdc0-1f9d-34bc-bd94-5e830d7515f4', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'view', 'SurveyQuestionOptions', 'module', 90, 0),
('d81456b6-f82f-e955-37b5-5f003a693771', '2020-07-04 08:16:22', '2020-07-04 08:16:22', '1', '1', 'access', 'stic_Settings', 'module', 89, 0),
('d82a10f7-88b9-dabe-4776-5e830de5e4a8', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'delete', 'EmailTemplates', 'module', 90, 0),
('d8dcf96c-6bea-803d-4bdf-5e830df90f78', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'access', 'Contacts', 'module', 89, 0),
('da8adcd4-477e-0e7b-d622-5e830df23648', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'massupdate', 'Users', 'module', 90, 0),
('da8dee35-8138-4a23-e1f8-5e830d2270e9', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'access', 'Leads', 'module', 89, 0),
('dabf2131-8b69-1c11-6b30-5e830d044eb2', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'access', 'ProspectLists', 'module', 89, 0),
('dadf9c6d-6312-f195-c4bf-5f003a80b469', '2020-07-04 08:16:22', '2020-07-04 08:16:22', '1', '1', 'export', 'stic_Contacts_Relationships', 'module', 90, 0),
('dae486ec-0a58-2451-e0e7-5e830dfd5173', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'massupdate', 'SurveyResponses', 'module', 90, 0),
('dbd205cf-2389-1a6b-b07b-5e830d0db157', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'import', 'EmailTemplates', 'module', 90, 0),
('dbd2ac74-bedd-0ff6-ff3b-5f003a7ea8da', '2020-07-04 08:16:22', '2020-07-04 08:16:22', '1', '1', 'view', 'stic_Settings', 'module', 90, 0),
('dbd3ef12-ee8e-fb35-eddc-5f003af17c4b', '2020-07-04 08:16:22', '2020-07-04 08:16:22', '1', '1', 'import', 'Employees', 'module', 90, 0),
('dcb8af65-2010-1d9a-a716-5e830d891ed1', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'view', 'Contacts', 'module', 90, 0),
('dd190ebb-6cca-df4b-a1ec-5e830d0e6d84', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'massupdate', 'Tasks', 'module', 90, 0),
('dd66029f-99e2-7151-6c6a-5e830ded0240', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'list', 'AOW_WorkFlow', 'module', 90, 0),
('de5847ad-8403-63b5-b193-5e830db878ee', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'list', 'SurveyQuestionOptions', 'module', 90, 0),
('decbdd73-6e54-1879-d1c7-5f003aa137e7', '2020-07-04 08:16:22', '2020-07-04 08:16:22', '1', '1', 'massupdate', 'stic_Contacts_Relationships', 'module', 90, 0),
('ded51e8d-0d5f-8159-7222-5e830d54826a', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'view', 'Leads', 'module', 90, 0),
('dee9fd09-bb81-8f04-a7a6-5e830de9be33', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'access', 'Tasks', 'module', 89, 0),
('df56a9e9-2b5a-3129-fb5b-5e830d4efcd3', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'export', 'EmailTemplates', 'module', 90, 0),
('dfa9430f-1e3a-e77f-75ad-5f003a406571', '2020-07-04 08:16:22', '2020-07-04 08:16:22', '1', '1', 'export', 'Employees', 'module', 90, 0),
('dfb26cd3-d703-a584-aefc-5f003a3a99dc', '2020-07-04 08:16:22', '2020-07-04 08:16:22', '1', '1', 'list', 'stic_Settings', 'module', 90, 0),
('e046280a-4a83-c36c-649c-5e830d0ee849', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'view', 'ProspectLists', 'module', 90, 0),
('e087ee0d-e49f-2625-caa0-5e830db407f8', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'list', 'Contacts', 'module', 90, 0),
('e1005ef3-a256-04d6-e6b3-5e830d080154', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'access', 'AOR_Scheduled_Reports', 'module', 89, 0),
('e24be20e-4f20-2354-1997-5e830d5b8c31', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'edit', 'SurveyQuestionOptions', 'module', 90, 0),
('e2e766a9-5097-07fa-1f87-5e830d7dff3f', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'massupdate', 'EmailTemplates', 'module', 90, 0),
('e3c5ace2-0f20-e9f2-089c-5e830d71f0eb', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'access', 'jjwg_Markers', 'module', 89, 0),
('e3e6cfec-5878-4912-e2d0-5f003a976fd7', '2020-07-04 08:16:22', '2020-07-04 08:16:22', '1', '1', 'edit', 'stic_Settings', 'module', 90, 0),
('e469c132-6b87-ae28-9477-5f003a0a162f', '2020-07-04 08:16:22', '2020-07-04 08:16:22', '1', '1', 'massupdate', 'Employees', 'module', 90, 0),
('e4f61baf-a981-5dac-1042-5e830d507b2c', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'list', 'ProspectLists', 'module', 90, 0),
('e52c2fc5-36f9-bcf5-3f08-5e830ded7511', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'list', 'Leads', 'module', 90, 0),
('e52cdc0d-5e2f-525d-819a-5e830de519d2', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'view', 'AOR_Scheduled_Reports', 'module', 90, 0),
('e5d68155-5865-aa64-2154-5e830d2ae07e', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'access', 'AM_ProjectTemplates', 'module', 89, 0),
('e5fc0989-dd33-0af7-0b68-5e830d4850bc', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'view', 'FP_events', 'module', 90, 0),
('e619b12b-464e-c277-f262-5e830d1cf10c', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'edit', 'Contacts', 'module', 90, 0),
('e6239306-9790-bc7f-b90e-5f003a53a518', '2020-07-04 08:16:22', '2020-07-04 08:16:22', '1', '1', 'access', 'stic_Events', 'module', 89, 0),
('e64dec23-b477-ac4b-7c40-5e830da24259', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'delete', 'SurveyQuestionOptions', 'module', 90, 0),
('e67b0375-6fcf-b9df-d409-5e830dddc2ed', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'view', 'Tasks', 'module', 90, 0),
('e7dbf963-dba3-9bb4-c355-5e830d232a13', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'access', 'SecurityGroups', 'module', 89, 0),
('e7eda019-5b21-98f2-fee8-5f003a7f6251', '2020-07-04 08:16:22', '2020-07-04 08:16:22', '1', '1', 'delete', 'stic_Settings', 'module', 90, 0),
('ea287ad0-c840-1772-140f-5e830d1809fa', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'view', 'AM_ProjectTemplates', 'module', 90, 0),
('ea320979-2cad-85ad-9a7a-5e830d68320b', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'import', 'SurveyQuestionOptions', 'module', 90, 0),
('ea54824b-ff63-06a6-de64-5e830df4c6b4', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'list', 'AOR_Scheduled_Reports', 'module', 90, 0),
('ea68642e-a403-5aac-dad3-5e830d62ffaf', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'delete', 'Contacts', 'module', 90, 0),
('ea750b0c-6b05-7b0b-9420-5f003a426bd8', '2020-07-04 08:16:22', '2020-07-04 08:16:22', '1', '1', 'view', 'stic_Events', 'module', 90, 0),
('ea9f6095-5f3c-6a59-9975-5e830d7feca2', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'access', 'AOP_Case_Events', 'module', 89, 0),
('eabc11a3-dd24-caf7-6c94-5e830d266d5e', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'list', 'Tasks', 'module', 90, 0),
('eb6161cb-b6c2-282b-5481-5e830dfcf89b', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'view', 'jjwg_Markers', 'module', 90, 0),
('eb81b266-09cb-5208-0587-5e830d055361', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'edit', 'Leads', 'module', 90, 0),
('ebfb67be-ea55-f629-abbc-5f003a6d6d39', '2020-07-04 08:16:22', '2020-07-04 08:16:22', '1', '1', 'import', 'stic_Settings', 'module', 90, 0),
('ec29b71b-0eb9-4d79-3595-5e830dc1596a', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'edit', 'ProspectLists', 'module', 90, 0),
('ec5c6624-d924-c4d4-10de-5e830dbb45b6', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'view', 'SecurityGroups', 'module', 90, 0),
('ed7b0c87-c6b5-08b5-ba73-5e830d211438', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'view', 'Project', 'module', 90, 0),
('edd408f6-ec78-6780-f605-5e830d4d1e25', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'edit', 'Notes', 'module', 90, 0),
('edf9a79a-4814-bb87-b7b4-5e830d6033df', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'list', 'AM_ProjectTemplates', 'module', 90, 0),
('edfcfba2-d63b-f9a4-04fa-5e830d248c6a', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'export', 'SurveyQuestionOptions', 'module', 90, 0),
('eeab17b1-fbe9-47ff-2986-5e830d8f04c0', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'import', 'Contacts', 'module', 90, 0),
('eeaf42bf-3474-9071-00f3-5e830d40cd77', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'edit', 'AOR_Scheduled_Reports', 'module', 90, 0),
('eecc0095-666e-13b8-b1e1-5f003a76e479', '2020-07-04 08:16:22', '2020-07-04 08:16:22', '1', '1', 'list', 'stic_Events', 'module', 90, 0),
('eeda70e0-d968-54d2-641d-5e830d93ac55', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'view', 'AOP_Case_Events', 'module', 90, 0),
('ef0115f9-5515-2710-9502-5e830dd062b7', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'edit', 'Tasks', 'module', 90, 0),
('ef9ba590-5347-4839-e702-5e830dcbfee4', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'list', 'jjwg_Markers', 'module', 90, 0),
('efb62cb8-e606-44f9-df44-5e830d0753a3', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'delete', 'ProspectLists', 'module', 90, 0),
('f097d923-82d4-4079-a549-5f003aa87c7f', '2020-07-04 08:16:22', '2020-07-04 08:16:22', '1', '1', 'export', 'stic_Settings', 'module', 90, 0),
('f1a35328-85d6-7377-28af-5e830de812fd', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'massupdate', 'SurveyQuestionOptions', 'module', 90, 0),
('f1aeab63-c1e3-626f-f1bb-5e830d10afd6', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'list', 'SecurityGroups', 'module', 90, 0),
('f1cd0bad-a49c-0119-c3b0-5e830db06bfd', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'massupdate', 'AM_ProjectTemplates', 'module', 90, 0),
('f1f2c5b0-586e-8756-1dc6-5e830dc3c02f', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'delete', 'Leads', 'module', 90, 0),
('f2492416-6e80-5b72-a70a-5e830d3548c8', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'export', 'Contacts', 'module', 90, 0),
('f2dc87de-81ef-4d44-8660-5e830d2ddec5', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'edit', 'AM_ProjectTemplates', 'module', 90, 0),
('f2dccd54-817c-661f-c520-5e830d8fc2d2', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'list', 'AOP_Case_Events', 'module', 90, 0),
('f3010def-1bba-3240-2a79-5e830d0cf3af', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'delete', 'AOR_Scheduled_Reports', 'module', 90, 0),
('f3304d2c-777d-9017-7c43-5e830d610856', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'delete', 'Tasks', 'module', 90, 0),
('f3520918-97ba-c2b1-472b-5e830d5a43a8', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'access', 'AOS_Product_Categories', 'module', 89, 0),
('f363d98c-e8a3-c18b-7c90-5e830d925395', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'import', 'ProspectLists', 'module', 90, 0),
('f3b0c742-8cd6-a208-994e-5e830ddb1f3b', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'edit', 'jjwg_Markers', 'module', 90, 0),
('f4030c9e-5594-6729-e766-5e830d88e2a4', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'export', 'AOR_Scheduled_Reports', 'module', 90, 0),
('f52a02c0-b3b1-4bec-63d1-5e830dd74ea5', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'list', 'Surveys', 'module', 90, 0),
('f7bd0d01-95d7-eb3b-7356-5e830daa47a9', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '', 'export', 'AOP_Case_Events', 'module', 90, 0);

-- --------------------------------------------------------

--
-- Table structure for table `acl_roles`
--

CREATE TABLE IF NOT EXISTS `acl_roles` (
  `id` char(36) NOT NULL,
  `date_entered` datetime DEFAULT NULL,
  `date_modified` datetime DEFAULT NULL,
  `modified_user_id` char(36) DEFAULT NULL,
  `created_by` char(36) DEFAULT NULL,
  `name` varchar(150) DEFAULT NULL,
  `description` text,
  `deleted` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `acl_roles_actions`
--

CREATE TABLE IF NOT EXISTS `acl_roles_actions` (
  `id` varchar(36) NOT NULL,
  `role_id` varchar(36) DEFAULT NULL,
  `action_id` varchar(36) DEFAULT NULL,
  `access_override` int(3) DEFAULT NULL,
  `date_modified` datetime DEFAULT NULL,
  `deleted` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `acl_roles_users`
--

CREATE TABLE IF NOT EXISTS `acl_roles_users` (
  `id` varchar(36) NOT NULL,
  `role_id` varchar(36) DEFAULT NULL,
  `user_id` varchar(36) DEFAULT NULL,
  `date_modified` datetime DEFAULT NULL,
  `deleted` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `address_book`
--

CREATE TABLE IF NOT EXISTS `address_book` (
  `assigned_user_id` char(36) NOT NULL,
  `bean` varchar(50) DEFAULT NULL,
  `bean_id` char(36) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `alerts`
--

CREATE TABLE IF NOT EXISTS `alerts` (
  `id` char(36) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `date_entered` datetime DEFAULT NULL,
  `date_modified` datetime DEFAULT NULL,
  `modified_user_id` char(36) DEFAULT NULL,
  `created_by` char(36) DEFAULT NULL,
  `description` text,
  `deleted` tinyint(1) DEFAULT '0',
  `assigned_user_id` char(36) DEFAULT NULL,
  `is_read` tinyint(1) DEFAULT NULL,
  `target_module` varchar(255) DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL,
  `url_redirect` varchar(255) DEFAULT NULL,
  `reminder_id` char(36) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `am_projecttemplates`
--

CREATE TABLE IF NOT EXISTS `am_projecttemplates` (
  `id` char(36) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `date_entered` datetime DEFAULT NULL,
  `date_modified` datetime DEFAULT NULL,
  `modified_user_id` char(36) DEFAULT NULL,
  `created_by` char(36) DEFAULT NULL,
  `description` text,
  `deleted` tinyint(1) DEFAULT '0',
  `assigned_user_id` char(36) DEFAULT NULL,
  `status` varchar(100) DEFAULT 'Draft',
  `priority` varchar(100) DEFAULT 'High',
  `override_business_hours` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `am_projecttemplates_audit`
--

CREATE TABLE IF NOT EXISTS `am_projecttemplates_audit` (
  `id` char(36) NOT NULL,
  `parent_id` char(36) NOT NULL,
  `date_created` datetime DEFAULT NULL,
  `created_by` varchar(36) DEFAULT NULL,
  `field_name` varchar(100) DEFAULT NULL,
  `data_type` varchar(100) DEFAULT NULL,
  `before_value_string` varchar(255) DEFAULT NULL,
  `after_value_string` varchar(255) DEFAULT NULL,
  `before_value_text` text,
  `after_value_text` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `am_projecttemplates_contacts_1_c`
--

CREATE TABLE IF NOT EXISTS `am_projecttemplates_contacts_1_c` (
  `id` varchar(36) NOT NULL,
  `date_modified` datetime DEFAULT NULL,
  `deleted` tinyint(1) DEFAULT '0',
  `am_projecttemplates_ida` varchar(36) DEFAULT NULL,
  `contacts_idb` varchar(36) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `am_projecttemplates_project_1_c`
--

CREATE TABLE IF NOT EXISTS `am_projecttemplates_project_1_c` (
  `id` varchar(36) NOT NULL,
  `date_modified` datetime DEFAULT NULL,
  `deleted` tinyint(1) DEFAULT '0',
  `am_projecttemplates_project_1am_projecttemplates_ida` varchar(36) DEFAULT NULL,
  `am_projecttemplates_project_1project_idb` varchar(36) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `am_projecttemplates_users_1_c`
--

CREATE TABLE IF NOT EXISTS `am_projecttemplates_users_1_c` (
  `id` varchar(36) NOT NULL,
  `date_modified` datetime DEFAULT NULL,
  `deleted` tinyint(1) DEFAULT '0',
  `am_projecttemplates_ida` varchar(36) DEFAULT NULL,
  `users_idb` varchar(36) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `am_tasktemplates`
--

CREATE TABLE IF NOT EXISTS `am_tasktemplates` (
  `id` char(36) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `date_entered` datetime DEFAULT NULL,
  `date_modified` datetime DEFAULT NULL,
  `modified_user_id` char(36) DEFAULT NULL,
  `created_by` char(36) DEFAULT NULL,
  `description` text,
  `deleted` tinyint(1) DEFAULT '0',
  `assigned_user_id` char(36) DEFAULT NULL,
  `status` varchar(100) DEFAULT 'Not Started',
  `priority` varchar(100) DEFAULT 'High',
  `percent_complete` int(255) DEFAULT '0',
  `predecessors` int(255) DEFAULT NULL,
  `milestone_flag` tinyint(1) DEFAULT '0',
  `relationship_type` varchar(100) DEFAULT 'FS',
  `task_number` int(255) DEFAULT NULL,
  `order_number` int(255) DEFAULT NULL,
  `estimated_effort` int(255) DEFAULT NULL,
  `utilization` varchar(100) DEFAULT '0',
  `duration` int(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `am_tasktemplates_am_projecttemplates_c`
--

CREATE TABLE IF NOT EXISTS `am_tasktemplates_am_projecttemplates_c` (
  `id` varchar(36) NOT NULL,
  `date_modified` datetime DEFAULT NULL,
  `deleted` tinyint(1) DEFAULT '0',
  `am_tasktemplates_am_projecttemplatesam_projecttemplates_ida` varchar(36) DEFAULT NULL,
  `am_tasktemplates_am_projecttemplatesam_tasktemplates_idb` varchar(36) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `am_tasktemplates_audit`
--

CREATE TABLE IF NOT EXISTS `am_tasktemplates_audit` (
  `id` char(36) NOT NULL,
  `parent_id` char(36) NOT NULL,
  `date_created` datetime DEFAULT NULL,
  `created_by` varchar(36) DEFAULT NULL,
  `field_name` varchar(100) DEFAULT NULL,
  `data_type` varchar(100) DEFAULT NULL,
  `before_value_string` varchar(255) DEFAULT NULL,
  `after_value_string` varchar(255) DEFAULT NULL,
  `before_value_text` text,
  `after_value_text` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `aobh_businesshours`
--

CREATE TABLE IF NOT EXISTS `aobh_businesshours` (
  `id` char(36) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `date_entered` datetime DEFAULT NULL,
  `date_modified` datetime DEFAULT NULL,
  `modified_user_id` char(36) DEFAULT NULL,
  `created_by` char(36) DEFAULT NULL,
  `description` text,
  `deleted` tinyint(1) DEFAULT '0',
  `opening_hours` varchar(100) DEFAULT '1',
  `closing_hours` varchar(100) DEFAULT '1',
  `open_status` tinyint(1) DEFAULT NULL,
  `day` varchar(100) DEFAULT 'monday'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `aod_index`
--

CREATE TABLE IF NOT EXISTS `aod_index` (
  `id` char(36) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `date_entered` datetime DEFAULT NULL,
  `date_modified` datetime DEFAULT NULL,
  `modified_user_id` char(36) DEFAULT NULL,
  `created_by` char(36) DEFAULT NULL,
  `description` text,
  `deleted` tinyint(1) DEFAULT '0',
  `assigned_user_id` char(36) DEFAULT NULL,
  `last_optimised` datetime DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `aod_index`
--

INSERT INTO `aod_index` (`id`, `name`, `date_entered`, `date_modified`, `modified_user_id`, `created_by`, `description`, `deleted`, `assigned_user_id`, `last_optimised`, `location`) VALUES
('1', 'Index', '2020-07-04 08:15:38', '2020-07-04 08:15:38', '1', '1', NULL, 0, NULL, NULL, 'modules/AOD_Index/Index/Index');

-- --------------------------------------------------------

--
-- Table structure for table `aod_indexevent`
--

CREATE TABLE IF NOT EXISTS `aod_indexevent` (
  `id` char(36) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `date_entered` datetime DEFAULT NULL,
  `date_modified` datetime DEFAULT NULL,
  `modified_user_id` char(36) DEFAULT NULL,
  `created_by` char(36) DEFAULT NULL,
  `description` text,
  `deleted` tinyint(1) DEFAULT '0',
  `assigned_user_id` char(36) DEFAULT NULL,
  `error` varchar(255) DEFAULT NULL,
  `success` tinyint(1) DEFAULT '0',
  `record_id` char(36) DEFAULT NULL,
  `record_module` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `aod_indexevent_audit`
--

CREATE TABLE IF NOT EXISTS `aod_indexevent_audit` (
  `id` char(36) NOT NULL,
  `parent_id` char(36) NOT NULL,
  `date_created` datetime DEFAULT NULL,
  `created_by` varchar(36) DEFAULT NULL,
  `field_name` varchar(100) DEFAULT NULL,
  `data_type` varchar(100) DEFAULT NULL,
  `before_value_string` varchar(255) DEFAULT NULL,
  `after_value_string` varchar(255) DEFAULT NULL,
  `before_value_text` text,
  `after_value_text` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `aod_index_audit`
--

CREATE TABLE IF NOT EXISTS `aod_index_audit` (
  `id` char(36) NOT NULL,
  `parent_id` char(36) NOT NULL,
  `date_created` datetime DEFAULT NULL,
  `created_by` varchar(36) DEFAULT NULL,
  `field_name` varchar(100) DEFAULT NULL,
  `data_type` varchar(100) DEFAULT NULL,
  `before_value_string` varchar(255) DEFAULT NULL,
  `after_value_string` varchar(255) DEFAULT NULL,
  `before_value_text` text,
  `after_value_text` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `aok_knowledgebase`
--

CREATE TABLE IF NOT EXISTS `aok_knowledgebase` (
  `id` char(36) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `date_entered` datetime DEFAULT NULL,
  `date_modified` datetime DEFAULT NULL,
  `modified_user_id` char(36) DEFAULT NULL,
  `created_by` char(36) DEFAULT NULL,
  `description` text,
  `deleted` tinyint(1) DEFAULT '0',
  `assigned_user_id` char(36) DEFAULT NULL,
  `status` varchar(100) DEFAULT 'Draft',
  `revision` varchar(255) DEFAULT NULL,
  `additional_info` text,
  `user_id_c` char(36) DEFAULT NULL,
  `user_id1_c` char(36) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `aok_knowledgebase_audit`
--

CREATE TABLE IF NOT EXISTS `aok_knowledgebase_audit` (
  `id` char(36) NOT NULL,
  `parent_id` char(36) NOT NULL,
  `date_created` datetime DEFAULT NULL,
  `created_by` varchar(36) DEFAULT NULL,
  `field_name` varchar(100) DEFAULT NULL,
  `data_type` varchar(100) DEFAULT NULL,
  `before_value_string` varchar(255) DEFAULT NULL,
  `after_value_string` varchar(255) DEFAULT NULL,
  `before_value_text` text,
  `after_value_text` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `aok_knowledgebase_categories`
--

CREATE TABLE IF NOT EXISTS `aok_knowledgebase_categories` (
  `id` varchar(36) NOT NULL,
  `date_modified` datetime DEFAULT NULL,
  `deleted` tinyint(1) DEFAULT '0',
  `aok_knowledgebase_id` varchar(36) DEFAULT NULL,
  `aok_knowledge_base_categories_id` varchar(36) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `aok_knowledge_base_categories`
--

CREATE TABLE IF NOT EXISTS `aok_knowledge_base_categories` (
  `id` char(36) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `date_entered` datetime DEFAULT NULL,
  `date_modified` datetime DEFAULT NULL,
  `modified_user_id` char(36) DEFAULT NULL,
  `created_by` char(36) DEFAULT NULL,
  `description` text,
  `deleted` tinyint(1) DEFAULT '0',
  `assigned_user_id` char(36) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `aok_knowledge_base_categories_audit`
--

CREATE TABLE IF NOT EXISTS `aok_knowledge_base_categories_audit` (
  `id` char(36) NOT NULL,
  `parent_id` char(36) NOT NULL,
  `date_created` datetime DEFAULT NULL,
  `created_by` varchar(36) DEFAULT NULL,
  `field_name` varchar(100) DEFAULT NULL,
  `data_type` varchar(100) DEFAULT NULL,
  `before_value_string` varchar(255) DEFAULT NULL,
  `after_value_string` varchar(255) DEFAULT NULL,
  `before_value_text` text,
  `after_value_text` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `aop_case_events`
--

CREATE TABLE IF NOT EXISTS `aop_case_events` (
  `id` char(36) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `date_entered` datetime DEFAULT NULL,
  `date_modified` datetime DEFAULT NULL,
  `modified_user_id` char(36) DEFAULT NULL,
  `created_by` char(36) DEFAULT NULL,
  `description` text,
  `deleted` tinyint(1) DEFAULT '0',
  `assigned_user_id` char(36) DEFAULT NULL,
  `case_id` char(36) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `aop_case_events_audit`
--

CREATE TABLE IF NOT EXISTS `aop_case_events_audit` (
  `id` char(36) NOT NULL,
  `parent_id` char(36) NOT NULL,
  `date_created` datetime DEFAULT NULL,
  `created_by` varchar(36) DEFAULT NULL,
  `field_name` varchar(100) DEFAULT NULL,
  `data_type` varchar(100) DEFAULT NULL,
  `before_value_string` varchar(255) DEFAULT NULL,
  `after_value_string` varchar(255) DEFAULT NULL,
  `before_value_text` text,
  `after_value_text` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `aop_case_updates`
--

CREATE TABLE IF NOT EXISTS `aop_case_updates` (
  `id` char(36) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `date_entered` datetime DEFAULT NULL,
  `date_modified` datetime DEFAULT NULL,
  `modified_user_id` char(36) DEFAULT NULL,
  `created_by` char(36) DEFAULT NULL,
  `description` text,
  `deleted` tinyint(1) DEFAULT '0',
  `assigned_user_id` char(36) DEFAULT NULL,
  `case_id` char(36) DEFAULT NULL,
  `contact_id` char(36) DEFAULT NULL,
  `internal` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `aop_case_updates_audit`
--

CREATE TABLE IF NOT EXISTS `aop_case_updates_audit` (
  `id` char(36) NOT NULL,
  `parent_id` char(36) NOT NULL,
  `date_created` datetime DEFAULT NULL,
  `created_by` varchar(36) DEFAULT NULL,
  `field_name` varchar(100) DEFAULT NULL,
  `data_type` varchar(100) DEFAULT NULL,
  `before_value_string` varchar(255) DEFAULT NULL,
  `after_value_string` varchar(255) DEFAULT NULL,
  `before_value_text` text,
  `after_value_text` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `aor_charts`
--

CREATE TABLE IF NOT EXISTS `aor_charts` (
  `id` char(36) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `date_entered` datetime DEFAULT NULL,
  `date_modified` datetime DEFAULT NULL,
  `modified_user_id` char(36) DEFAULT NULL,
  `created_by` char(36) DEFAULT NULL,
  `description` text,
  `deleted` tinyint(1) DEFAULT '0',
  `aor_report_id` char(36) DEFAULT NULL,
  `type` varchar(100) DEFAULT NULL,
  `x_field` int(11) DEFAULT NULL,
  `y_field` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `aor_conditions`
--

CREATE TABLE IF NOT EXISTS `aor_conditions` (
  `id` char(36) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `date_entered` datetime DEFAULT NULL,
  `date_modified` datetime DEFAULT NULL,
  `modified_user_id` char(36) DEFAULT NULL,
  `created_by` char(36) DEFAULT NULL,
  `description` text,
  `deleted` tinyint(1) DEFAULT '0',
  `aor_report_id` char(36) DEFAULT NULL,
  `condition_order` int(255) DEFAULT NULL,
  `logic_op` varchar(255) DEFAULT NULL,
  `parenthesis` varchar(255) DEFAULT NULL,
  `module_path` longtext,
  `field` varchar(100) DEFAULT NULL,
  `operator` varchar(100) DEFAULT NULL,
  `value_type` varchar(100) DEFAULT NULL,
  `value` varchar(255) DEFAULT NULL,
  `parameter` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `aor_fields`
--

CREATE TABLE IF NOT EXISTS `aor_fields` (
  `id` char(36) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `date_entered` datetime DEFAULT NULL,
  `date_modified` datetime DEFAULT NULL,
  `modified_user_id` char(36) DEFAULT NULL,
  `created_by` char(36) DEFAULT NULL,
  `description` text,
  `deleted` tinyint(1) DEFAULT '0',
  `aor_report_id` char(36) DEFAULT NULL,
  `field_order` int(255) DEFAULT NULL,
  `module_path` longtext,
  `field` varchar(100) DEFAULT NULL,
  `display` tinyint(1) DEFAULT NULL,
  `link` tinyint(1) DEFAULT NULL,
  `label` varchar(255) DEFAULT NULL,
  `field_function` varchar(100) DEFAULT NULL,
  `sort_by` varchar(100) DEFAULT NULL,
  `format` varchar(100) DEFAULT NULL,
  `total` varchar(100) DEFAULT NULL,
  `sort_order` varchar(100) DEFAULT NULL,
  `group_by` tinyint(1) DEFAULT NULL,
  `group_order` varchar(100) DEFAULT NULL,
  `group_display` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `aor_reports`
--

CREATE TABLE IF NOT EXISTS `aor_reports` (
  `id` char(36) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `date_entered` datetime DEFAULT NULL,
  `date_modified` datetime DEFAULT NULL,
  `modified_user_id` char(36) DEFAULT NULL,
  `created_by` char(36) DEFAULT NULL,
  `description` text,
  `deleted` tinyint(1) DEFAULT '0',
  `assigned_user_id` char(36) DEFAULT NULL,
  `report_module` varchar(100) DEFAULT NULL,
  `graphs_per_row` int(11) DEFAULT '2'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `aor_reports_audit`
--

CREATE TABLE IF NOT EXISTS `aor_reports_audit` (
  `id` char(36) NOT NULL,
  `parent_id` char(36) NOT NULL,
  `date_created` datetime DEFAULT NULL,
  `created_by` varchar(36) DEFAULT NULL,
  `field_name` varchar(100) DEFAULT NULL,
  `data_type` varchar(100) DEFAULT NULL,
  `before_value_string` varchar(255) DEFAULT NULL,
  `after_value_string` varchar(255) DEFAULT NULL,
  `before_value_text` text,
  `after_value_text` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `aor_scheduled_reports`
--

CREATE TABLE IF NOT EXISTS `aor_scheduled_reports` (
  `id` char(36) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `date_entered` datetime DEFAULT NULL,
  `date_modified` datetime DEFAULT NULL,
  `modified_user_id` char(36) DEFAULT NULL,
  `created_by` char(36) DEFAULT NULL,
  `description` text,
  `deleted` tinyint(1) DEFAULT '0',
  `schedule` varchar(100) DEFAULT NULL,
  `last_run` datetime DEFAULT NULL,
  `status` varchar(100) DEFAULT NULL,
  `email_recipients` longtext,
  `aor_report_id` char(36) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `aos_contracts`
--

CREATE TABLE IF NOT EXISTS `aos_contracts` (
  `id` char(36) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `date_entered` datetime DEFAULT NULL,
  `date_modified` datetime DEFAULT NULL,
  `modified_user_id` char(36) DEFAULT NULL,
  `created_by` char(36) DEFAULT NULL,
  `description` text,
  `deleted` tinyint(1) DEFAULT '0',
  `assigned_user_id` char(36) DEFAULT NULL,
  `reference_code` varchar(255) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `total_contract_value` decimal(26,6) DEFAULT NULL,
  `total_contract_value_usdollar` decimal(26,6) DEFAULT NULL,
  `currency_id` char(36) DEFAULT NULL,
  `status` varchar(100) DEFAULT 'Not Started',
  `customer_signed_date` date DEFAULT NULL,
  `company_signed_date` date DEFAULT NULL,
  `renewal_reminder_date` datetime DEFAULT NULL,
  `contract_type` varchar(100) DEFAULT 'Type',
  `contract_account_id` char(36) DEFAULT NULL,
  `opportunity_id` char(36) DEFAULT NULL,
  `contact_id` char(36) DEFAULT NULL,
  `call_id` char(36) DEFAULT NULL,
  `total_amt` decimal(26,6) DEFAULT NULL,
  `total_amt_usdollar` decimal(26,6) DEFAULT NULL,
  `subtotal_amount` decimal(26,6) DEFAULT NULL,
  `subtotal_amount_usdollar` decimal(26,6) DEFAULT NULL,
  `discount_amount` decimal(26,6) DEFAULT NULL,
  `discount_amount_usdollar` decimal(26,6) DEFAULT NULL,
  `tax_amount` decimal(26,6) DEFAULT NULL,
  `tax_amount_usdollar` decimal(26,6) DEFAULT NULL,
  `shipping_amount` decimal(26,6) DEFAULT NULL,
  `shipping_amount_usdollar` decimal(26,6) DEFAULT NULL,
  `shipping_tax` varchar(100) DEFAULT NULL,
  `shipping_tax_amt` decimal(26,6) DEFAULT NULL,
  `shipping_tax_amt_usdollar` decimal(26,6) DEFAULT NULL,
  `total_amount` decimal(26,6) DEFAULT NULL,
  `total_amount_usdollar` decimal(26,6) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `aos_contracts_audit`
--

CREATE TABLE IF NOT EXISTS `aos_contracts_audit` (
  `id` char(36) NOT NULL,
  `parent_id` char(36) NOT NULL,
  `date_created` datetime DEFAULT NULL,
  `created_by` varchar(36) DEFAULT NULL,
  `field_name` varchar(100) DEFAULT NULL,
  `data_type` varchar(100) DEFAULT NULL,
  `before_value_string` varchar(255) DEFAULT NULL,
  `after_value_string` varchar(255) DEFAULT NULL,
  `before_value_text` text,
  `after_value_text` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `aos_contracts_documents`
--

CREATE TABLE IF NOT EXISTS `aos_contracts_documents` (
  `id` varchar(36) NOT NULL,
  `date_modified` datetime DEFAULT NULL,
  `deleted` tinyint(1) DEFAULT '0',
  `aos_contracts_id` varchar(36) DEFAULT NULL,
  `documents_id` varchar(36) DEFAULT NULL,
  `document_revision_id` varchar(36) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `aos_invoices`
--

CREATE TABLE IF NOT EXISTS `aos_invoices` (
  `id` char(36) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `date_entered` datetime DEFAULT NULL,
  `date_modified` datetime DEFAULT NULL,
  `modified_user_id` char(36) DEFAULT NULL,
  `created_by` char(36) DEFAULT NULL,
  `description` text,
  `deleted` tinyint(1) DEFAULT '0',
  `assigned_user_id` char(36) DEFAULT NULL,
  `billing_account_id` char(36) DEFAULT NULL,
  `billing_contact_id` char(36) DEFAULT NULL,
  `billing_address_street` varchar(150) DEFAULT NULL,
  `billing_address_city` varchar(100) DEFAULT NULL,
  `billing_address_state` varchar(100) DEFAULT NULL,
  `billing_address_postalcode` varchar(20) DEFAULT NULL,
  `billing_address_country` varchar(255) DEFAULT NULL,
  `shipping_address_street` varchar(150) DEFAULT NULL,
  `shipping_address_city` varchar(100) DEFAULT NULL,
  `shipping_address_state` varchar(100) DEFAULT NULL,
  `shipping_address_postalcode` varchar(20) DEFAULT NULL,
  `shipping_address_country` varchar(255) DEFAULT NULL,
  `number` int(11) NOT NULL,
  `total_amt` decimal(26,6) DEFAULT NULL,
  `total_amt_usdollar` decimal(26,6) DEFAULT NULL,
  `subtotal_amount` decimal(26,6) DEFAULT NULL,
  `subtotal_amount_usdollar` decimal(26,6) DEFAULT NULL,
  `discount_amount` decimal(26,6) DEFAULT NULL,
  `discount_amount_usdollar` decimal(26,6) DEFAULT NULL,
  `tax_amount` decimal(26,6) DEFAULT NULL,
  `tax_amount_usdollar` decimal(26,6) DEFAULT NULL,
  `shipping_amount` decimal(26,6) DEFAULT NULL,
  `shipping_amount_usdollar` decimal(26,6) DEFAULT NULL,
  `shipping_tax` varchar(100) DEFAULT NULL,
  `shipping_tax_amt` decimal(26,6) DEFAULT NULL,
  `shipping_tax_amt_usdollar` decimal(26,6) DEFAULT NULL,
  `total_amount` decimal(26,6) DEFAULT NULL,
  `total_amount_usdollar` decimal(26,6) DEFAULT NULL,
  `currency_id` char(36) DEFAULT NULL,
  `quote_number` int(11) DEFAULT NULL,
  `quote_date` date DEFAULT NULL,
  `invoice_date` date DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `status` varchar(100) DEFAULT NULL,
  `template_ddown_c` text,
  `subtotal_tax_amount` decimal(26,6) DEFAULT NULL,
  `subtotal_tax_amount_usdollar` decimal(26,6) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `aos_invoices_audit`
--

CREATE TABLE IF NOT EXISTS `aos_invoices_audit` (
  `id` char(36) NOT NULL,
  `parent_id` char(36) NOT NULL,
  `date_created` datetime DEFAULT NULL,
  `created_by` varchar(36) DEFAULT NULL,
  `field_name` varchar(100) DEFAULT NULL,
  `data_type` varchar(100) DEFAULT NULL,
  `before_value_string` varchar(255) DEFAULT NULL,
  `after_value_string` varchar(255) DEFAULT NULL,
  `before_value_text` text,
  `after_value_text` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `aos_line_item_groups`
--

CREATE TABLE IF NOT EXISTS `aos_line_item_groups` (
  `id` char(36) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `date_entered` datetime DEFAULT NULL,
  `date_modified` datetime DEFAULT NULL,
  `modified_user_id` char(36) DEFAULT NULL,
  `created_by` char(36) DEFAULT NULL,
  `description` text,
  `deleted` tinyint(1) DEFAULT '0',
  `assigned_user_id` char(36) DEFAULT NULL,
  `total_amt` decimal(26,6) DEFAULT NULL,
  `total_amt_usdollar` decimal(26,6) DEFAULT NULL,
  `discount_amount` decimal(26,6) DEFAULT NULL,
  `discount_amount_usdollar` decimal(26,6) DEFAULT NULL,
  `subtotal_amount` decimal(26,6) DEFAULT NULL,
  `subtotal_amount_usdollar` decimal(26,6) DEFAULT NULL,
  `tax_amount` decimal(26,6) DEFAULT NULL,
  `tax_amount_usdollar` decimal(26,6) DEFAULT NULL,
  `subtotal_tax_amount` decimal(26,6) DEFAULT NULL,
  `subtotal_tax_amount_usdollar` decimal(26,6) DEFAULT NULL,
  `total_amount` decimal(26,6) DEFAULT NULL,
  `total_amount_usdollar` decimal(26,6) DEFAULT NULL,
  `parent_type` varchar(100) DEFAULT NULL,
  `parent_id` char(36) DEFAULT NULL,
  `number` int(11) DEFAULT NULL,
  `currency_id` char(36) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `aos_line_item_groups_audit`
--

CREATE TABLE IF NOT EXISTS `aos_line_item_groups_audit` (
  `id` char(36) NOT NULL,
  `parent_id` char(36) NOT NULL,
  `date_created` datetime DEFAULT NULL,
  `created_by` varchar(36) DEFAULT NULL,
  `field_name` varchar(100) DEFAULT NULL,
  `data_type` varchar(100) DEFAULT NULL,
  `before_value_string` varchar(255) DEFAULT NULL,
  `after_value_string` varchar(255) DEFAULT NULL,
  `before_value_text` text,
  `after_value_text` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `aos_pdf_templates`
--

CREATE TABLE IF NOT EXISTS `aos_pdf_templates` (
  `id` char(36) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `date_entered` datetime DEFAULT NULL,
  `date_modified` datetime DEFAULT NULL,
  `modified_user_id` char(36) DEFAULT NULL,
  `created_by` char(36) DEFAULT NULL,
  `description` longtext,
  `deleted` tinyint(1) DEFAULT '0',
  `assigned_user_id` char(36) DEFAULT NULL,
  `active` tinyint(1) DEFAULT '1',
  `type` varchar(100) DEFAULT NULL,
  `pdfheader` text,
  `pdffooter` text,
  `margin_left` int(255) DEFAULT '15',
  `margin_right` int(255) DEFAULT '15',
  `margin_top` int(255) DEFAULT '16',
  `margin_bottom` int(255) DEFAULT '16',
  `margin_header` int(255) DEFAULT '9',
  `margin_footer` int(255) DEFAULT '9',
  `page_size` varchar(100) DEFAULT NULL,
  `orientation` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `aos_pdf_templates_audit`
--

CREATE TABLE IF NOT EXISTS `aos_pdf_templates_audit` (
  `id` char(36) NOT NULL,
  `parent_id` char(36) NOT NULL,
  `date_created` datetime DEFAULT NULL,
  `created_by` varchar(36) DEFAULT NULL,
  `field_name` varchar(100) DEFAULT NULL,
  `data_type` varchar(100) DEFAULT NULL,
  `before_value_string` varchar(255) DEFAULT NULL,
  `after_value_string` varchar(255) DEFAULT NULL,
  `before_value_text` text,
  `after_value_text` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `aos_products`
--

CREATE TABLE IF NOT EXISTS `aos_products` (
  `id` char(36) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `date_entered` datetime DEFAULT NULL,
  `date_modified` datetime DEFAULT NULL,
  `modified_user_id` char(36) DEFAULT NULL,
  `created_by` char(36) DEFAULT NULL,
  `description` text,
  `deleted` tinyint(1) DEFAULT '0',
  `assigned_user_id` char(36) DEFAULT NULL,
  `maincode` varchar(100) DEFAULT 'XXXX',
  `part_number` varchar(25) DEFAULT NULL,
  `category` varchar(100) DEFAULT NULL,
  `type` varchar(100) DEFAULT 'Good',
  `cost` decimal(26,6) DEFAULT NULL,
  `cost_usdollar` decimal(26,6) DEFAULT NULL,
  `currency_id` char(36) DEFAULT NULL,
  `price` decimal(26,6) DEFAULT NULL,
  `price_usdollar` decimal(26,6) DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL,
  `contact_id` char(36) DEFAULT NULL,
  `product_image` varchar(255) DEFAULT NULL,
  `aos_product_category_id` char(36) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `aos_products_audit`
--

CREATE TABLE IF NOT EXISTS `aos_products_audit` (
  `id` char(36) NOT NULL,
  `parent_id` char(36) NOT NULL,
  `date_created` datetime DEFAULT NULL,
  `created_by` varchar(36) DEFAULT NULL,
  `field_name` varchar(100) DEFAULT NULL,
  `data_type` varchar(100) DEFAULT NULL,
  `before_value_string` varchar(255) DEFAULT NULL,
  `after_value_string` varchar(255) DEFAULT NULL,
  `before_value_text` text,
  `after_value_text` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `aos_products_quotes`
--

CREATE TABLE IF NOT EXISTS `aos_products_quotes` (
  `id` char(36) NOT NULL,
  `name` text,
  `date_entered` datetime DEFAULT NULL,
  `date_modified` datetime DEFAULT NULL,
  `modified_user_id` char(36) DEFAULT NULL,
  `created_by` char(36) DEFAULT NULL,
  `description` text,
  `deleted` tinyint(1) DEFAULT '0',
  `assigned_user_id` char(36) DEFAULT NULL,
  `currency_id` char(36) DEFAULT NULL,
  `part_number` varchar(255) DEFAULT NULL,
  `item_description` text,
  `number` int(11) DEFAULT NULL,
  `product_qty` decimal(18,4) DEFAULT NULL,
  `product_cost_price` decimal(26,6) DEFAULT NULL,
  `product_cost_price_usdollar` decimal(26,6) DEFAULT NULL,
  `product_list_price` decimal(26,6) DEFAULT NULL,
  `product_list_price_usdollar` decimal(26,6) DEFAULT NULL,
  `product_discount` decimal(26,6) DEFAULT NULL,
  `product_discount_usdollar` decimal(26,6) DEFAULT NULL,
  `product_discount_amount` decimal(26,6) DEFAULT NULL,
  `product_discount_amount_usdollar` decimal(26,6) DEFAULT NULL,
  `discount` varchar(255) DEFAULT 'Percentage',
  `product_unit_price` decimal(26,6) DEFAULT NULL,
  `product_unit_price_usdollar` decimal(26,6) DEFAULT NULL,
  `vat_amt` decimal(26,6) DEFAULT NULL,
  `vat_amt_usdollar` decimal(26,6) DEFAULT NULL,
  `product_total_price` decimal(26,6) DEFAULT NULL,
  `product_total_price_usdollar` decimal(26,6) DEFAULT NULL,
  `vat` varchar(100) DEFAULT '5.0',
  `parent_type` varchar(100) DEFAULT NULL,
  `parent_id` char(36) DEFAULT NULL,
  `product_id` char(36) DEFAULT NULL,
  `group_id` char(36) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `aos_products_quotes_audit`
--

CREATE TABLE IF NOT EXISTS `aos_products_quotes_audit` (
  `id` char(36) NOT NULL,
  `parent_id` char(36) NOT NULL,
  `date_created` datetime DEFAULT NULL,
  `created_by` varchar(36) DEFAULT NULL,
  `field_name` varchar(100) DEFAULT NULL,
  `data_type` varchar(100) DEFAULT NULL,
  `before_value_string` varchar(255) DEFAULT NULL,
  `after_value_string` varchar(255) DEFAULT NULL,
  `before_value_text` text,
  `after_value_text` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `aos_product_categories`
--

CREATE TABLE IF NOT EXISTS `aos_product_categories` (
  `id` char(36) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `date_entered` datetime DEFAULT NULL,
  `date_modified` datetime DEFAULT NULL,
  `modified_user_id` char(36) DEFAULT NULL,
  `created_by` char(36) DEFAULT NULL,
  `description` text,
  `deleted` tinyint(1) DEFAULT '0',
  `assigned_user_id` char(36) DEFAULT NULL,
  `is_parent` tinyint(1) DEFAULT '0',
  `parent_category_id` char(36) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `aos_product_categories_audit`
--

CREATE TABLE IF NOT EXISTS `aos_product_categories_audit` (
  `id` char(36) NOT NULL,
  `parent_id` char(36) NOT NULL,
  `date_created` datetime DEFAULT NULL,
  `created_by` varchar(36) DEFAULT NULL,
  `field_name` varchar(100) DEFAULT NULL,
  `data_type` varchar(100) DEFAULT NULL,
  `before_value_string` varchar(255) DEFAULT NULL,
  `after_value_string` varchar(255) DEFAULT NULL,
  `before_value_text` text,
  `after_value_text` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `aos_quotes`
--

CREATE TABLE IF NOT EXISTS `aos_quotes` (
  `id` char(36) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `date_entered` datetime DEFAULT NULL,
  `date_modified` datetime DEFAULT NULL,
  `modified_user_id` char(36) DEFAULT NULL,
  `created_by` char(36) DEFAULT NULL,
  `description` text,
  `deleted` tinyint(1) DEFAULT '0',
  `assigned_user_id` char(36) DEFAULT NULL,
  `approval_issue` text,
  `billing_account_id` char(36) DEFAULT NULL,
  `billing_contact_id` char(36) DEFAULT NULL,
  `billing_address_street` varchar(150) DEFAULT NULL,
  `billing_address_city` varchar(100) DEFAULT NULL,
  `billing_address_state` varchar(100) DEFAULT NULL,
  `billing_address_postalcode` varchar(20) DEFAULT NULL,
  `billing_address_country` varchar(255) DEFAULT NULL,
  `shipping_address_street` varchar(150) DEFAULT NULL,
  `shipping_address_city` varchar(100) DEFAULT NULL,
  `shipping_address_state` varchar(100) DEFAULT NULL,
  `shipping_address_postalcode` varchar(20) DEFAULT NULL,
  `shipping_address_country` varchar(255) DEFAULT NULL,
  `expiration` date DEFAULT NULL,
  `number` int(11) DEFAULT NULL,
  `opportunity_id` char(36) DEFAULT NULL,
  `template_ddown_c` text,
  `total_amt` decimal(26,6) DEFAULT NULL,
  `total_amt_usdollar` decimal(26,6) DEFAULT NULL,
  `subtotal_amount` decimal(26,6) DEFAULT NULL,
  `subtotal_amount_usdollar` decimal(26,6) DEFAULT NULL,
  `discount_amount` decimal(26,6) DEFAULT NULL,
  `discount_amount_usdollar` decimal(26,6) DEFAULT NULL,
  `tax_amount` decimal(26,6) DEFAULT NULL,
  `tax_amount_usdollar` decimal(26,6) DEFAULT NULL,
  `shipping_amount` decimal(26,6) DEFAULT NULL,
  `shipping_amount_usdollar` decimal(26,6) DEFAULT NULL,
  `shipping_tax` varchar(100) DEFAULT NULL,
  `shipping_tax_amt` decimal(26,6) DEFAULT NULL,
  `shipping_tax_amt_usdollar` decimal(26,6) DEFAULT NULL,
  `total_amount` decimal(26,6) DEFAULT NULL,
  `total_amount_usdollar` decimal(26,6) DEFAULT NULL,
  `currency_id` char(36) DEFAULT NULL,
  `stage` varchar(100) DEFAULT 'Draft',
  `term` varchar(100) DEFAULT NULL,
  `terms_c` text,
  `approval_status` varchar(100) DEFAULT NULL,
  `invoice_status` varchar(100) DEFAULT 'Not Invoiced',
  `subtotal_tax_amount` decimal(26,6) DEFAULT NULL,
  `subtotal_tax_amount_usdollar` decimal(26,6) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `aos_quotes_aos_invoices_c`
--

CREATE TABLE IF NOT EXISTS `aos_quotes_aos_invoices_c` (
  `id` varchar(36) NOT NULL,
  `date_modified` datetime DEFAULT NULL,
  `deleted` tinyint(1) DEFAULT '0',
  `aos_quotes77d9_quotes_ida` varchar(36) DEFAULT NULL,
  `aos_quotes6b83nvoices_idb` varchar(36) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `aos_quotes_audit`
--

CREATE TABLE IF NOT EXISTS `aos_quotes_audit` (
  `id` char(36) NOT NULL,
  `parent_id` char(36) NOT NULL,
  `date_created` datetime DEFAULT NULL,
  `created_by` varchar(36) DEFAULT NULL,
  `field_name` varchar(100) DEFAULT NULL,
  `data_type` varchar(100) DEFAULT NULL,
  `before_value_string` varchar(255) DEFAULT NULL,
  `after_value_string` varchar(255) DEFAULT NULL,
  `before_value_text` text,
  `after_value_text` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `aos_quotes_os_contracts_c`
--

CREATE TABLE IF NOT EXISTS `aos_quotes_os_contracts_c` (
  `id` varchar(36) NOT NULL,
  `date_modified` datetime DEFAULT NULL,
  `deleted` tinyint(1) DEFAULT '0',
  `aos_quotese81e_quotes_ida` varchar(36) DEFAULT NULL,
  `aos_quotes4dc0ntracts_idb` varchar(36) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `aos_quotes_project_c`
--

CREATE TABLE IF NOT EXISTS `aos_quotes_project_c` (
  `id` varchar(36) NOT NULL,
  `date_modified` datetime DEFAULT NULL,
  `deleted` tinyint(1) DEFAULT '0',
  `aos_quotes1112_quotes_ida` varchar(36) DEFAULT NULL,
  `aos_quotes7207project_idb` varchar(36) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `aow_actions`
--

CREATE TABLE IF NOT EXISTS `aow_actions` (
  `id` char(36) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `date_entered` datetime DEFAULT NULL,
  `date_modified` datetime DEFAULT NULL,
  `modified_user_id` char(36) DEFAULT NULL,
  `created_by` char(36) DEFAULT NULL,
  `description` text,
  `deleted` tinyint(1) DEFAULT '0',
  `aow_workflow_id` char(36) DEFAULT NULL,
  `action_order` int(255) DEFAULT NULL,
  `action` varchar(100) DEFAULT NULL,
  `parameters` longtext
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `aow_conditions`
--

CREATE TABLE IF NOT EXISTS `aow_conditions` (
  `id` char(36) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `date_entered` datetime DEFAULT NULL,
  `date_modified` datetime DEFAULT NULL,
  `modified_user_id` char(36) DEFAULT NULL,
  `created_by` char(36) DEFAULT NULL,
  `description` text,
  `deleted` tinyint(1) DEFAULT '0',
  `aow_workflow_id` char(36) DEFAULT NULL,
  `condition_order` int(255) DEFAULT NULL,
  `module_path` longtext,
  `field` varchar(100) DEFAULT NULL,
  `operator` varchar(100) DEFAULT NULL,
  `value_type` varchar(255) DEFAULT NULL,
  `value` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `aow_processed`
--

CREATE TABLE IF NOT EXISTS `aow_processed` (
  `id` char(36) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `date_entered` datetime DEFAULT NULL,
  `date_modified` datetime DEFAULT NULL,
  `modified_user_id` char(36) DEFAULT NULL,
  `created_by` char(36) DEFAULT NULL,
  `description` text,
  `deleted` tinyint(1) DEFAULT '0',
  `aow_workflow_id` char(36) DEFAULT NULL,
  `parent_id` char(36) DEFAULT NULL,
  `parent_type` varchar(100) DEFAULT NULL,
  `status` varchar(100) DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `aow_processed_aow_actions`
--

CREATE TABLE IF NOT EXISTS `aow_processed_aow_actions` (
  `id` varchar(36) NOT NULL,
  `aow_processed_id` varchar(36) DEFAULT NULL,
  `aow_action_id` varchar(36) DEFAULT NULL,
  `status` varchar(36) DEFAULT 'Pending',
  `date_modified` datetime DEFAULT NULL,
  `deleted` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `aow_workflow`
--

CREATE TABLE IF NOT EXISTS `aow_workflow` (
  `id` char(36) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `date_entered` datetime DEFAULT NULL,
  `date_modified` datetime DEFAULT NULL,
  `modified_user_id` char(36) DEFAULT NULL,
  `created_by` char(36) DEFAULT NULL,
  `description` text,
  `deleted` tinyint(1) DEFAULT '0',
  `assigned_user_id` char(36) DEFAULT NULL,
  `flow_module` varchar(100) DEFAULT NULL,
  `flow_run_on` varchar(100) DEFAULT '0',
  `status` varchar(100) DEFAULT 'Active',
  `run_when` varchar(100) DEFAULT 'Always',
  `multiple_runs` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `aow_workflow_audit`
--

CREATE TABLE IF NOT EXISTS `aow_workflow_audit` (
  `id` char(36) NOT NULL,
  `parent_id` char(36) NOT NULL,
  `date_created` datetime DEFAULT NULL,
  `created_by` varchar(36) DEFAULT NULL,
  `field_name` varchar(100) DEFAULT NULL,
  `data_type` varchar(100) DEFAULT NULL,
  `before_value_string` varchar(255) DEFAULT NULL,
  `after_value_string` varchar(255) DEFAULT NULL,
  `before_value_text` text,
  `after_value_text` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `bugs`
--

CREATE TABLE IF NOT EXISTS `bugs` (
  `id` char(36) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `date_entered` datetime DEFAULT NULL,
  `date_modified` datetime DEFAULT NULL,
  `modified_user_id` char(36) DEFAULT NULL,
  `created_by` char(36) DEFAULT NULL,
  `description` text,
  `deleted` tinyint(1) DEFAULT '0',
  `assigned_user_id` char(36) DEFAULT NULL,
  `bug_number` int(11) NOT NULL,
  `type` varchar(255) DEFAULT NULL,
  `status` varchar(100) DEFAULT NULL,
  `priority` varchar(100) DEFAULT NULL,
  `resolution` varchar(255) DEFAULT NULL,
  `work_log` text,
  `found_in_release` varchar(255) DEFAULT NULL,
  `fixed_in_release` varchar(255) DEFAULT NULL,
  `source` varchar(255) DEFAULT NULL,
  `product_category` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `bugs_audit`
--

CREATE TABLE IF NOT EXISTS `bugs_audit` (
  `id` char(36) NOT NULL,
  `parent_id` char(36) NOT NULL,
  `date_created` datetime DEFAULT NULL,
  `created_by` varchar(36) DEFAULT NULL,
  `field_name` varchar(100) DEFAULT NULL,
  `data_type` varchar(100) DEFAULT NULL,
  `before_value_string` varchar(255) DEFAULT NULL,
  `after_value_string` varchar(255) DEFAULT NULL,
  `before_value_text` text,
  `after_value_text` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `calls`
--

CREATE TABLE IF NOT EXISTS `calls` (
  `id` char(36) NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `date_entered` datetime DEFAULT NULL,
  `date_modified` datetime DEFAULT NULL,
  `modified_user_id` char(36) DEFAULT NULL,
  `created_by` char(36) DEFAULT NULL,
  `description` text,
  `deleted` tinyint(1) DEFAULT '0',
  `assigned_user_id` char(36) DEFAULT NULL,
  `duration_hours` int(2) DEFAULT NULL,
  `duration_minutes` int(2) DEFAULT NULL,
  `date_start` datetime DEFAULT NULL,
  `date_end` datetime DEFAULT NULL,
  `parent_type` varchar(255) DEFAULT NULL,
  `status` varchar(100) DEFAULT 'Planned',
  `direction` varchar(100) DEFAULT NULL,
  `parent_id` char(36) DEFAULT NULL,
  `reminder_time` int(11) DEFAULT '-1',
  `email_reminder_time` int(11) DEFAULT '-1',
  `email_reminder_sent` tinyint(1) DEFAULT '0',
  `outlook_id` varchar(255) DEFAULT NULL,
  `repeat_type` varchar(36) DEFAULT NULL,
  `repeat_interval` int(3) DEFAULT '1',
  `repeat_dow` varchar(7) DEFAULT NULL,
  `repeat_until` date DEFAULT NULL,
  `repeat_count` int(7) DEFAULT NULL,
  `repeat_parent_id` char(36) DEFAULT NULL,
  `recurring_source` varchar(36) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `calls_contacts`
--

CREATE TABLE IF NOT EXISTS `calls_contacts` (
  `id` varchar(36) NOT NULL,
  `call_id` varchar(36) DEFAULT NULL,
  `contact_id` varchar(36) DEFAULT NULL,
  `required` varchar(1) DEFAULT '1',
  `accept_status` varchar(25) DEFAULT 'none',
  `date_modified` datetime DEFAULT NULL,
  `deleted` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `calls_leads`
--

CREATE TABLE IF NOT EXISTS `calls_leads` (
  `id` varchar(36) NOT NULL,
  `call_id` varchar(36) DEFAULT NULL,
  `lead_id` varchar(36) DEFAULT NULL,
  `required` varchar(1) DEFAULT '1',
  `accept_status` varchar(25) DEFAULT 'none',
  `date_modified` datetime DEFAULT NULL,
  `deleted` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `calls_reschedule`
--

CREATE TABLE IF NOT EXISTS `calls_reschedule` (
  `id` char(36) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `date_entered` datetime DEFAULT NULL,
  `date_modified` datetime DEFAULT NULL,
  `modified_user_id` char(36) DEFAULT NULL,
  `created_by` char(36) DEFAULT NULL,
  `description` text,
  `deleted` tinyint(1) DEFAULT '0',
  `assigned_user_id` char(36) DEFAULT NULL,
  `reason` varchar(100) DEFAULT NULL,
  `call_id` char(36) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `calls_reschedule_audit`
--

CREATE TABLE IF NOT EXISTS `calls_reschedule_audit` (
  `id` char(36) NOT NULL,
  `parent_id` char(36) NOT NULL,
  `date_created` datetime DEFAULT NULL,
  `created_by` varchar(36) DEFAULT NULL,
  `field_name` varchar(100) DEFAULT NULL,
  `data_type` varchar(100) DEFAULT NULL,
  `before_value_string` varchar(255) DEFAULT NULL,
  `after_value_string` varchar(255) DEFAULT NULL,
  `before_value_text` text,
  `after_value_text` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `calls_users`
--

CREATE TABLE IF NOT EXISTS `calls_users` (
  `id` varchar(36) NOT NULL,
  `call_id` varchar(36) DEFAULT NULL,
  `user_id` varchar(36) DEFAULT NULL,
  `required` varchar(1) DEFAULT '1',
  `accept_status` varchar(25) DEFAULT 'none',
  `date_modified` datetime DEFAULT NULL,
  `deleted` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `campaigns`
--

CREATE TABLE IF NOT EXISTS `campaigns` (
  `id` char(36) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `date_entered` datetime DEFAULT NULL,
  `date_modified` datetime DEFAULT NULL,
  `modified_user_id` char(36) DEFAULT NULL,
  `created_by` char(36) DEFAULT NULL,
  `deleted` tinyint(1) DEFAULT '0',
  `assigned_user_id` char(36) DEFAULT NULL,
  `tracker_key` int(11) NOT NULL,
  `tracker_count` int(11) DEFAULT '0',
  `refer_url` varchar(255) DEFAULT 'http://',
  `tracker_text` varchar(255) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `status` varchar(100) DEFAULT NULL,
  `impressions` int(11) DEFAULT '0',
  `currency_id` char(36) DEFAULT NULL,
  `budget` double DEFAULT NULL,
  `expected_cost` double DEFAULT NULL,
  `actual_cost` double DEFAULT NULL,
  `expected_revenue` double DEFAULT NULL,
  `campaign_type` varchar(100) DEFAULT NULL,
  `objective` text,
  `content` text,
  `frequency` varchar(100) DEFAULT NULL,
  `survey_id` char(36) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `campaigns_audit`
--

CREATE TABLE IF NOT EXISTS `campaigns_audit` (
  `id` char(36) NOT NULL,
  `parent_id` char(36) NOT NULL,
  `date_created` datetime DEFAULT NULL,
  `created_by` varchar(36) DEFAULT NULL,
  `field_name` varchar(100) DEFAULT NULL,
  `data_type` varchar(100) DEFAULT NULL,
  `before_value_string` varchar(255) DEFAULT NULL,
  `after_value_string` varchar(255) DEFAULT NULL,
  `before_value_text` text,
  `after_value_text` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `campaign_log`
--

CREATE TABLE IF NOT EXISTS `campaign_log` (
  `id` char(36) NOT NULL,
  `campaign_id` char(36) DEFAULT NULL,
  `target_tracker_key` varchar(36) DEFAULT NULL,
  `target_id` varchar(36) DEFAULT NULL,
  `target_type` varchar(100) DEFAULT NULL,
  `activity_type` varchar(100) DEFAULT NULL,
  `activity_date` datetime DEFAULT NULL,
  `related_id` varchar(36) DEFAULT NULL,
  `related_type` varchar(100) DEFAULT NULL,
  `archived` tinyint(1) DEFAULT '0',
  `hits` int(11) DEFAULT '0',
  `list_id` char(36) DEFAULT NULL,
  `deleted` tinyint(1) DEFAULT NULL,
  `date_modified` datetime DEFAULT NULL,
  `more_information` varchar(100) DEFAULT NULL,
  `marketing_id` char(36) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `campaign_trkrs`
--

CREATE TABLE IF NOT EXISTS `campaign_trkrs` (
  `id` char(36) NOT NULL,
  `tracker_name` varchar(255) DEFAULT NULL,
  `tracker_url` varchar(255) DEFAULT 'http://',
  `tracker_key` int(11) NOT NULL,
  `campaign_id` char(36) DEFAULT NULL,
  `date_entered` datetime DEFAULT NULL,
  `date_modified` datetime DEFAULT NULL,
  `modified_user_id` char(36) DEFAULT NULL,
  `created_by` char(36) DEFAULT NULL,
  `is_optout` tinyint(1) DEFAULT '0',
  `deleted` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `cases`
--

CREATE TABLE IF NOT EXISTS `cases` (
  `id` char(36) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `date_entered` datetime DEFAULT NULL,
  `date_modified` datetime DEFAULT NULL,
  `modified_user_id` char(36) DEFAULT NULL,
  `created_by` char(36) DEFAULT NULL,
  `description` text,
  `deleted` tinyint(1) DEFAULT '0',
  `assigned_user_id` char(36) DEFAULT NULL,
  `case_number` int(11) NOT NULL,
  `type` varchar(255) DEFAULT NULL,
  `status` varchar(100) DEFAULT NULL,
  `priority` varchar(100) DEFAULT NULL,
  `resolution` text,
  `work_log` text,
  `account_id` char(36) DEFAULT NULL,
  `state` varchar(100) DEFAULT 'Open',
  `contact_created_by_id` char(36) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `cases_audit`
--

CREATE TABLE IF NOT EXISTS `cases_audit` (
  `id` char(36) NOT NULL,
  `parent_id` char(36) NOT NULL,
  `date_created` datetime DEFAULT NULL,
  `created_by` varchar(36) DEFAULT NULL,
  `field_name` varchar(100) DEFAULT NULL,
  `data_type` varchar(100) DEFAULT NULL,
  `before_value_string` varchar(255) DEFAULT NULL,
  `after_value_string` varchar(255) DEFAULT NULL,
  `before_value_text` text,
  `after_value_text` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `cases_bugs`
--

CREATE TABLE IF NOT EXISTS `cases_bugs` (
  `id` varchar(36) NOT NULL,
  `case_id` varchar(36) DEFAULT NULL,
  `bug_id` varchar(36) DEFAULT NULL,
  `date_modified` datetime DEFAULT NULL,
  `deleted` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `cases_cstm`
--

CREATE TABLE IF NOT EXISTS `cases_cstm` (
  `id_c` char(36) NOT NULL,
  `jjwg_maps_lng_c` float(11,8) DEFAULT '0.00000000',
  `jjwg_maps_lat_c` float(10,8) DEFAULT '0.00000000',
  `jjwg_maps_geocode_status_c` varchar(255) DEFAULT NULL,
  `jjwg_maps_address_c` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `config`
--

CREATE TABLE IF NOT EXISTS `config` (
  `category` varchar(32) DEFAULT NULL,
  `name` varchar(32) DEFAULT NULL,
  `value` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `config`
--

INSERT INTO `config` (`category`, `name`, `value`) VALUES
('notify', 'fromaddress', 'do_not_reply@example.com'),
('notify', 'fromname', 'SuiteCRM'),
('notify', 'send_by_default', '1'),
('notify', 'on', '1'),
('notify', 'send_from_assigning_user', '0'),
('info', 'sugar_version', '6.5.25'),
('MySettings', 'tab', 'YTozMjp7czo0OiJIb21lIjtzOjQ6IkhvbWUiO3M6ODoiQWNjb3VudHMiO3M6ODoiQWNjb3VudHMiO3M6ODoiQ29udGFjdHMiO3M6ODoiQ29udGFjdHMiO3M6ODoiQ2FsZW5kYXIiO3M6ODoiQ2FsZW5kYXIiO3M6OToiRG9jdW1lbnRzIjtzOjk6IkRvY3VtZW50cyI7czo2OiJFbWFpbHMiO3M6NjoiRW1haWxzIjtzOjU6IkNhbGxzIjtzOjU6IkNhbGxzIjtzOjg6Ik1lZXRpbmdzIjtzOjg6Ik1lZXRpbmdzIjtzOjU6IlRhc2tzIjtzOjU6IlRhc2tzIjtzOjU6Ik5vdGVzIjtzOjU6Ik5vdGVzIjtzOjExOiJBT1JfUmVwb3J0cyI7czoxMToiQU9SX1JlcG9ydHMiO3M6MTI6IkFPV19Xb3JrRmxvdyI7czoxMjoiQU9XX1dvcmtGbG93IjtzOjE0OiJFbWFpbFRlbXBsYXRlcyI7czoxNDoiRW1haWxUZW1wbGF0ZXMiO3M6NzoiU3VydmV5cyI7czo3OiJTdXJ2ZXlzIjtzOjc6IlByb2plY3QiO3M6NzoiUHJvamVjdCI7czo5OiJDYW1wYWlnbnMiO3M6OToiQ2FtcGFpZ25zIjtzOjEzOiJPcHBvcnR1bml0aWVzIjtzOjEzOiJPcHBvcnR1bml0aWVzIjtzOjU6IkxlYWRzIjtzOjU6IkxlYWRzIjtzOjEzOiJQcm9zcGVjdExpc3RzIjtzOjEzOiJQcm9zcGVjdExpc3RzIjtzOjg6IktSZXBvcnRzIjtzOjg6IktSZXBvcnRzIjtzOjI0OiJESEFfUGxhbnRpbGxhc0RvY3VtZW50b3MiO3M6MjQ6IkRIQV9QbGFudGlsbGFzRG9jdW1lbnRvcyI7czoxODoiRlBfRXZlbnRfTG9jYXRpb25zIjtzOjE4OiJGUF9FdmVudF9Mb2NhdGlvbnMiO3M6MTE6IlByb2plY3RUYXNrIjtzOjExOiJQcm9qZWN0VGFzayI7czoyNzoic3RpY19BY2NvdW50c19SZWxhdGlvbnNoaXBzIjtzOjI3OiJzdGljX0FjY291bnRzX1JlbGF0aW9uc2hpcHMiO3M6MTE6InN0aWNfRXZlbnRzIjtzOjExOiJzdGljX0V2ZW50cyI7czoxODoic3RpY19SZWdpc3RyYXRpb25zIjtzOjE4OiJzdGljX1JlZ2lzdHJhdGlvbnMiO3M6MTM6InN0aWNfU2Vzc2lvbnMiO3M6MTM6InN0aWNfU2Vzc2lvbnMiO3M6MTY6InN0aWNfQXR0ZW5kYW5jZXMiO3M6MTY6InN0aWNfQXR0ZW5kYW5jZXMiO3M6MTY6InN0aWNfUmVtaXR0YW5jZXMiO3M6MTY6InN0aWNfUmVtaXR0YW5jZXMiO3M6MTM6InN0aWNfUGF5bWVudHMiO3M6MTM6InN0aWNfUGF5bWVudHMiO3M6MjQ6InN0aWNfUGF5bWVudF9Db21taXRtZW50cyI7czoyNDoic3RpY19QYXltZW50X0NvbW1pdG1lbnRzIjtzOjI3OiJzdGljX0NvbnRhY3RzX1JlbGF0aW9uc2hpcHMiO3M6Mjc6InN0aWNfQ29udGFjdHNfUmVsYXRpb25zaGlwcyI7fQ=='),
('portal', 'on', '0'),
('tracker', 'Tracker', '1'),
('system', 'skypeout_on', '1'),
('sugarfeed', 'enabled', '1'),
('sugarfeed', 'module_UserFeed', '1'),
('sugarfeed', 'module_Cases', '1'),
('sugarfeed', 'module_Leads', '1'),
('sugarfeed', 'module_Contacts', '1'),
('sugarfeed', 'module_Opportunities', '1'),
('Update', 'CheckUpdates', 'manual'),
('system', 'name', 'SinergiaCRM'),
('system', 'adminwizard', '1'),
('notify', 'allow_default_outbound', '0');

-- --------------------------------------------------------

--
-- Table structure for table `contacts`
--

CREATE TABLE IF NOT EXISTS `contacts` (
  `id` char(36) NOT NULL,
  `date_entered` datetime DEFAULT NULL,
  `date_modified` datetime DEFAULT NULL,
  `modified_user_id` char(36) DEFAULT NULL,
  `created_by` char(36) DEFAULT NULL,
  `description` text,
  `deleted` tinyint(1) DEFAULT '0',
  `assigned_user_id` char(36) DEFAULT NULL,
  `salutation` varchar(255) DEFAULT NULL,
  `first_name` varchar(100) DEFAULT NULL,
  `last_name` varchar(100) DEFAULT NULL,
  `title` varchar(100) DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `department` varchar(255) DEFAULT NULL,
  `do_not_call` tinyint(1) DEFAULT '0',
  `phone_home` varchar(100) DEFAULT NULL,
  `phone_mobile` varchar(100) DEFAULT NULL,
  `phone_work` varchar(100) DEFAULT NULL,
  `phone_other` varchar(100) DEFAULT NULL,
  `phone_fax` varchar(100) DEFAULT NULL,
  `lawful_basis` text,
  `date_reviewed` date DEFAULT NULL,
  `lawful_basis_source` varchar(100) DEFAULT NULL,
  `primary_address_street` varchar(150) DEFAULT NULL,
  `primary_address_city` varchar(100) DEFAULT NULL,
  `primary_address_state` varchar(100) DEFAULT NULL,
  `primary_address_postalcode` varchar(20) DEFAULT NULL,
  `primary_address_country` varchar(255) DEFAULT NULL,
  `alt_address_street` varchar(150) DEFAULT NULL,
  `alt_address_city` varchar(100) DEFAULT NULL,
  `alt_address_state` varchar(100) DEFAULT NULL,
  `alt_address_postalcode` varchar(20) DEFAULT NULL,
  `alt_address_country` varchar(255) DEFAULT NULL,
  `assistant` varchar(75) DEFAULT NULL,
  `assistant_phone` varchar(100) DEFAULT NULL,
  `lead_source` varchar(255) DEFAULT NULL,
  `reports_to_id` char(36) DEFAULT NULL,
  `birthdate` date DEFAULT NULL,
  `campaign_id` char(36) DEFAULT NULL,
  `joomla_account_id` varchar(255) DEFAULT NULL,
  `portal_account_disabled` tinyint(1) DEFAULT NULL,
  `portal_user_type` varchar(100) DEFAULT 'Single'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `contacts_audit`
--

CREATE TABLE IF NOT EXISTS `contacts_audit` (
  `id` char(36) NOT NULL,
  `parent_id` char(36) NOT NULL,
  `date_created` datetime DEFAULT NULL,
  `created_by` varchar(36) DEFAULT NULL,
  `field_name` varchar(100) DEFAULT NULL,
  `data_type` varchar(100) DEFAULT NULL,
  `before_value_string` varchar(255) DEFAULT NULL,
  `after_value_string` varchar(255) DEFAULT NULL,
  `before_value_text` text,
  `after_value_text` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `contacts_bugs`
--

CREATE TABLE IF NOT EXISTS `contacts_bugs` (
  `id` varchar(36) NOT NULL,
  `contact_id` varchar(36) DEFAULT NULL,
  `bug_id` varchar(36) DEFAULT NULL,
  `contact_role` varchar(50) DEFAULT NULL,
  `date_modified` datetime DEFAULT NULL,
  `deleted` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `contacts_cases`
--

CREATE TABLE IF NOT EXISTS `contacts_cases` (
  `id` varchar(36) NOT NULL,
  `contact_id` varchar(36) DEFAULT NULL,
  `case_id` varchar(36) DEFAULT NULL,
  `contact_role` varchar(50) DEFAULT NULL,
  `date_modified` datetime DEFAULT NULL,
  `deleted` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `contacts_cstm`
--

CREATE TABLE IF NOT EXISTS `contacts_cstm` (
  `id_c` char(36) NOT NULL,
  `jjwg_maps_lng_c` float(11,8) DEFAULT '0.00000000',
  `jjwg_maps_lat_c` float(10,8) DEFAULT '0.00000000',
  `jjwg_maps_geocode_status_c` varchar(255) DEFAULT NULL,
  `jjwg_maps_address_c` varchar(255) DEFAULT NULL,
  `stic_alt_address_county_c` varchar(100) DEFAULT NULL,
  `stic_alt_address_region_c` varchar(100) DEFAULT NULL,
  `stic_alt_address_type_c` varchar(100) DEFAULT NULL,
  `stic_age_c` int(3) DEFAULT NULL,
  `stic_acquisition_channel_c` varchar(100) DEFAULT NULL,
  `stic_do_not_send_postal_mail_c` tinyint(1) DEFAULT '0',
  `stic_employment_status_c` varchar(100) DEFAULT NULL,
  `stic_gender_c` varchar(100) DEFAULT NULL,
  `stic_identification_number_c` varchar(255) DEFAULT NULL,
  `stic_identification_type_c` varchar(100) DEFAULT NULL,
  `stic_language_c` varchar(100) DEFAULT NULL,
  `stic_postal_mail_return_reason_c` varchar(100) DEFAULT NULL,
  `stic_preferred_contact_channel_c` varchar(100) DEFAULT NULL,
  `stic_primary_address_county_c` varchar(100) DEFAULT NULL,
  `stic_primary_address_region_c` varchar(100) DEFAULT NULL,
  `stic_primary_address_type_c` varchar(100) DEFAULT NULL,
  `stic_professional_sector_c` varchar(100) DEFAULT NULL,
  `stic_professional_sector_other_c` varchar(255) DEFAULT NULL,
  `stic_referral_agent_c` varchar(100) DEFAULT NULL,
  `stic_relationship_type_c` text,
  `stic_tax_name_c` varchar(255) DEFAULT NULL,
  `stic_total_annual_donations_c` decimal(26,2) DEFAULT NULL,
  `stic_182_error_c` tinyint(1) DEFAULT '0',
  `stic_182_excluded_c` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `contacts_users`
--

CREATE TABLE IF NOT EXISTS `contacts_users` (
  `id` varchar(36) NOT NULL,
  `contact_id` varchar(36) DEFAULT NULL,
  `user_id` varchar(36) DEFAULT NULL,
  `date_modified` datetime DEFAULT NULL,
  `deleted` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `cron_remove_documents`
--

CREATE TABLE IF NOT EXISTS `cron_remove_documents` (
  `id` varchar(36) NOT NULL,
  `bean_id` varchar(36) DEFAULT NULL,
  `module` varchar(25) DEFAULT NULL,
  `date_modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `currencies`
--

CREATE TABLE IF NOT EXISTS `currencies` (
  `id` char(36) NOT NULL,
  `name` varchar(36) DEFAULT NULL,
  `symbol` varchar(36) DEFAULT NULL,
  `iso4217` varchar(3) DEFAULT NULL,
  `conversion_rate` double DEFAULT '0',
  `status` varchar(100) DEFAULT NULL,
  `deleted` tinyint(1) DEFAULT NULL,
  `date_entered` datetime DEFAULT NULL,
  `date_modified` datetime DEFAULT NULL,
  `created_by` char(36) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `custom_fields`
--

CREATE TABLE IF NOT EXISTS `custom_fields` (
  `bean_id` varchar(36) DEFAULT NULL,
  `set_num` int(11) DEFAULT '0',
  `field0` varchar(255) DEFAULT NULL,
  `field1` varchar(255) DEFAULT NULL,
  `field2` varchar(255) DEFAULT NULL,
  `field3` varchar(255) DEFAULT NULL,
  `field4` varchar(255) DEFAULT NULL,
  `field5` varchar(255) DEFAULT NULL,
  `field6` varchar(255) DEFAULT NULL,
  `field7` varchar(255) DEFAULT NULL,
  `field8` varchar(255) DEFAULT NULL,
  `field9` varchar(255) DEFAULT NULL,
  `deleted` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `dha_plantillasdocumentos`
--

CREATE TABLE IF NOT EXISTS `dha_plantillasdocumentos` (
  `id` char(36) NOT NULL,
  `date_entered` datetime DEFAULT NULL,
  `date_modified` datetime DEFAULT NULL,
  `modified_user_id` char(36) DEFAULT NULL,
  `created_by` char(36) DEFAULT NULL,
  `description` text,
  `deleted` tinyint(1) DEFAULT '0',
  `assigned_user_id` char(36) DEFAULT NULL,
  `document_name` varchar(255) DEFAULT NULL,
  `filename` varchar(255) DEFAULT NULL,
  `file_ext` varchar(100) DEFAULT NULL,
  `file_mime_type` varchar(100) DEFAULT NULL,
  `uploadfile` varchar(255) DEFAULT NULL,
  `active_date` date DEFAULT NULL,
  `exp_date` date DEFAULT NULL,
  `category_id` varchar(100) DEFAULT NULL,
  `subcategory_id` varchar(100) DEFAULT NULL,
  `status_id` varchar(100) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `modulo` varchar(100) DEFAULT NULL,
  `idioma` varchar(50) DEFAULT 'en',
  `aclroles` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `dha_plantillasdocumentos`
--

INSERT INTO `dha_plantillasdocumentos` (`id`, `date_entered`, `date_modified`, `modified_user_id`, `created_by`, `description`, `deleted`, `assigned_user_id`, `document_name`, `filename`, `file_ext`, `file_mime_type`, `uploadfile`, `active_date`, `exp_date`, `category_id`, `subcategory_id`, `status_id`, `status`, `modulo`, `idioma`, `aclroles`) VALUES
('a053ba63-4d0b-61bd-76f6-5eb2ce0f3603', '2020-07-04 10:16:13', '2020-07-04 10:16:13', '2', '2', '', 0, '2', 'EXAMPLE - Opportunities', 'Opportunities.docx', 'docx', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'Opportunities.docx', '2020-05-06', NULL, '', NULL, 'Draft', NULL, 'Opportunities', 'en_US', NULL),
('a398ad9a-b2f3-8fda-ff85-5eb2ce072bd9', '2020-07-04 10:16:13', '2020-07-04 10:16:13', '2', '2', '', 0, '2', 'EXAMPLE - Opportunities', 'Opportunities.odt', 'odt', 'application/vnd.oasis.opendocument.text', 'Opportunities.odt', '2020-05-06', NULL, '', NULL, 'Draft', NULL, 'Opportunities', 'en_US', NULL),
('a5bc752e-282a-a775-c0f7-5eb2ce9b8ca3', '2020-07-04 10:16:13', '2020-07-04 10:16:13', '2', '2', '', 0, '2', 'EXAMPLE - Opportunities (block synonyms)', 'Opportunities_block_synonyms.odt', 'odt', 'application/vnd.oasis.opendocument.text', 'Opportunities_block_synonyms.odt', '2020-05-06', NULL, '', NULL, 'Draft', NULL, 'Opportunities', 'en_US', NULL),
('a780758b-be64-b330-66f2-5eb2ce3bbc38', '2020-07-04 10:16:13', '2020-07-04 10:16:13', '2', '2', '', 0, '2', 'EXAMPLE - Opportunities Notes and Documents Images', 'Opportunities_Notes_and_Documents_Images.docx', 'docx', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'Opportunities_Notes_and_Documents_Images.docx', '2020-05-06', NULL, '', NULL, 'Draft', NULL, 'Opportunities', 'en_US', NULL),
('a99532db-7bde-1c19-f819-5eb2ce3fcac5', '2020-07-04 10:16:13', '2020-07-04 10:16:13', '2', '2', '', 0, '2', 'EXAMPLE - Opportunities TICKETS - 1 column continous', 'Opportunities_TICKETS (example 1 - 1 column continous).docx', 'docx', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'Opportunities_TICKETS (example 1 - 1 column continous).docx', '2020-05-06', NULL, '', NULL, 'Draft', NULL, 'Opportunities', 'en_US', NULL),
('ac63929e-f5a8-eb08-e405-5eb2ce30d724', '2020-07-04 10:16:13', '2020-07-04 10:16:13', '2', '2', '', 0, '2', 'EXAMPLE - Opportunities TICKETS - 1 column with separator', 'Opportunities_TICKETS (example 2 - 1 column with separator).docx', 'docx', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'Opportunities_TICKETS (example 2 - 1 column with separator).docx', '2020-05-06', NULL, '', NULL, 'Draft', NULL, 'Opportunities', 'en_US', NULL),
('ae568b04-c671-ec6d-456a-5eb2ce97bc4d', '2020-07-04 10:16:13', '2020-07-04 10:16:13', '2', '2', '', 0, '2', 'EXAMPLE - Opportunities TICKETS - 3 columns', 'Opportunities_TICKETS (example 3 - 3 columns).docx', 'docx', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'Opportunities_TICKETS (example 3 - 3 columns).docx', '2020-05-06', NULL, '', NULL, 'Draft', NULL, 'Opportunities', 'en_US', NULL),
('b057f60e-da23-e2de-c9c7-5eb2ce5d6931', '2020-07-04 10:16:13', '2020-07-04 10:16:13', '2', '2', '', 0, '2', 'EXAMPLE - Opportunities', 'Opportunities.xlsx', 'xlsx', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'Opportunities.xlsx', '2020-05-06', NULL, '', NULL, 'Draft', NULL, 'Opportunities', 'en_US', NULL),
('b2bce8e7-c01b-1fb7-0821-5eb2ce8050fe', '2020-07-04 10:16:13', '2020-07-04 10:16:13', '2', '2', '', 0, '2', 'EXAMPLE - Opportunities', 'Opportunities.ods', 'ods', 'application/vnd.oasis.opendocument.spreadsheet', 'Opportunities.ods', '2020-05-06', NULL, '', NULL, 'Draft', NULL, 'Opportunities', 'en_US', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `dha_plantillasdocumentos_audit`
--

CREATE TABLE IF NOT EXISTS `dha_plantillasdocumentos_audit` (
  `id` char(36) NOT NULL,
  `parent_id` char(36) NOT NULL,
  `date_created` datetime DEFAULT NULL,
  `created_by` varchar(36) DEFAULT NULL,
  `field_name` varchar(100) DEFAULT NULL,
  `data_type` varchar(100) DEFAULT NULL,
  `before_value_string` varchar(255) DEFAULT NULL,
  `after_value_string` varchar(255) DEFAULT NULL,
  `before_value_text` text,
  `after_value_text` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `documents`
--

CREATE TABLE IF NOT EXISTS `documents` (
  `id` char(36) NOT NULL,
  `date_entered` datetime DEFAULT NULL,
  `date_modified` datetime DEFAULT NULL,
  `modified_user_id` char(36) DEFAULT NULL,
  `created_by` char(36) DEFAULT NULL,
  `description` text,
  `deleted` tinyint(1) DEFAULT '0',
  `assigned_user_id` char(36) DEFAULT NULL,
  `document_name` varchar(255) DEFAULT NULL,
  `doc_id` varchar(100) DEFAULT NULL,
  `doc_type` varchar(100) DEFAULT 'Sugar',
  `doc_url` varchar(255) DEFAULT NULL,
  `active_date` date DEFAULT NULL,
  `exp_date` date DEFAULT NULL,
  `category_id` varchar(100) DEFAULT NULL,
  `subcategory_id` varchar(100) DEFAULT NULL,
  `status_id` varchar(100) DEFAULT NULL,
  `document_revision_id` varchar(36) DEFAULT NULL,
  `related_doc_id` char(36) DEFAULT NULL,
  `related_doc_rev_id` char(36) DEFAULT NULL,
  `is_template` tinyint(1) DEFAULT '0',
  `template_type` varchar(100) DEFAULT NULL,
  `stic_shared_document_link_c` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `documents_accounts`
--

CREATE TABLE IF NOT EXISTS `documents_accounts` (
  `id` varchar(36) NOT NULL,
  `date_modified` datetime DEFAULT NULL,
  `deleted` tinyint(1) DEFAULT '0',
  `document_id` varchar(36) DEFAULT NULL,
  `account_id` varchar(36) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `documents_bugs`
--

CREATE TABLE IF NOT EXISTS `documents_bugs` (
  `id` varchar(36) NOT NULL,
  `date_modified` datetime DEFAULT NULL,
  `deleted` tinyint(1) DEFAULT '0',
  `document_id` varchar(36) DEFAULT NULL,
  `bug_id` varchar(36) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `documents_cases`
--

CREATE TABLE IF NOT EXISTS `documents_cases` (
  `id` varchar(36) NOT NULL,
  `date_modified` datetime DEFAULT NULL,
  `deleted` tinyint(1) DEFAULT '0',
  `document_id` varchar(36) DEFAULT NULL,
  `case_id` varchar(36) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `documents_contacts`
--

CREATE TABLE IF NOT EXISTS `documents_contacts` (
  `id` varchar(36) NOT NULL,
  `date_modified` datetime DEFAULT NULL,
  `deleted` tinyint(1) DEFAULT '0',
  `document_id` varchar(36) DEFAULT NULL,
  `contact_id` varchar(36) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `documents_cstm`
--

CREATE TABLE IF NOT EXISTS `documents_cstm` (
  `id_c` char(36) NOT NULL,
  `stic_shared_document_link_c` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `documents_opportunities`
--

CREATE TABLE IF NOT EXISTS `documents_opportunities` (
  `id` varchar(36) NOT NULL,
  `date_modified` datetime DEFAULT NULL,
  `deleted` tinyint(1) DEFAULT '0',
  `document_id` varchar(36) DEFAULT NULL,
  `opportunity_id` varchar(36) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `document_revisions`
--

CREATE TABLE IF NOT EXISTS `document_revisions` (
  `id` varchar(36) NOT NULL,
  `change_log` varchar(255) DEFAULT NULL,
  `document_id` varchar(36) DEFAULT NULL,
  `doc_id` varchar(100) DEFAULT NULL,
  `doc_type` varchar(100) DEFAULT NULL,
  `doc_url` varchar(255) DEFAULT NULL,
  `date_entered` datetime DEFAULT NULL,
  `created_by` char(36) DEFAULT NULL,
  `filename` varchar(255) DEFAULT NULL,
  `file_ext` varchar(100) DEFAULT NULL,
  `file_mime_type` varchar(100) DEFAULT NULL,
  `revision` varchar(100) DEFAULT NULL,
  `deleted` tinyint(1) DEFAULT '0',
  `date_modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `eapm`
--

CREATE TABLE IF NOT EXISTS `eapm` (
  `id` char(36) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `date_entered` datetime DEFAULT NULL,
  `date_modified` datetime DEFAULT NULL,
  `modified_user_id` char(36) DEFAULT NULL,
  `created_by` char(36) DEFAULT NULL,
  `description` text,
  `deleted` tinyint(1) DEFAULT '0',
  `assigned_user_id` char(36) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL,
  `application` varchar(100) DEFAULT 'webex',
  `api_data` text,
  `consumer_key` varchar(255) DEFAULT NULL,
  `consumer_secret` varchar(255) DEFAULT NULL,
  `oauth_token` varchar(255) DEFAULT NULL,
  `oauth_secret` varchar(255) DEFAULT NULL,
  `validated` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `emailman`
--

CREATE TABLE IF NOT EXISTS `emailman` (
  `date_entered` datetime DEFAULT NULL,
  `date_modified` datetime DEFAULT NULL,
  `user_id` char(36) DEFAULT NULL,
  `id` int(11) NOT NULL,
  `campaign_id` char(36) DEFAULT NULL,
  `marketing_id` char(36) DEFAULT NULL,
  `list_id` char(36) DEFAULT NULL,
  `send_date_time` datetime DEFAULT NULL,
  `modified_user_id` char(36) DEFAULT NULL,
  `in_queue` tinyint(1) DEFAULT '0',
  `in_queue_date` datetime DEFAULT NULL,
  `send_attempts` int(11) DEFAULT '0',
  `deleted` tinyint(1) DEFAULT '0',
  `related_id` char(36) DEFAULT NULL,
  `related_type` varchar(100) DEFAULT NULL,
  `related_confirm_opt_in` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `emails`
--

CREATE TABLE IF NOT EXISTS `emails` (
  `id` char(36) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `date_entered` datetime DEFAULT NULL,
  `date_modified` datetime DEFAULT NULL,
  `modified_user_id` char(36) DEFAULT NULL,
  `created_by` char(36) DEFAULT NULL,
  `deleted` tinyint(1) DEFAULT '0',
  `assigned_user_id` char(36) DEFAULT NULL,
  `orphaned` tinyint(1) DEFAULT NULL,
  `last_synced` datetime DEFAULT NULL,
  `date_sent_received` datetime DEFAULT NULL,
  `message_id` varchar(255) DEFAULT NULL,
  `type` varchar(100) DEFAULT NULL,
  `status` varchar(100) DEFAULT NULL,
  `flagged` tinyint(1) DEFAULT NULL,
  `reply_to_status` tinyint(1) DEFAULT NULL,
  `intent` varchar(100) DEFAULT 'pick',
  `mailbox_id` char(36) DEFAULT NULL,
  `parent_type` varchar(100) DEFAULT NULL,
  `parent_id` char(36) DEFAULT NULL,
  `uid` varchar(255) DEFAULT NULL,
  `category_id` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `emails_beans`
--

CREATE TABLE IF NOT EXISTS `emails_beans` (
  `id` char(36) NOT NULL,
  `email_id` char(36) DEFAULT NULL,
  `bean_id` char(36) DEFAULT NULL,
  `bean_module` varchar(100) DEFAULT NULL,
  `campaign_data` text,
  `date_modified` datetime DEFAULT NULL,
  `deleted` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `emails_email_addr_rel`
--

CREATE TABLE IF NOT EXISTS `emails_email_addr_rel` (
  `id` char(36) NOT NULL,
  `email_id` char(36) NOT NULL,
  `address_type` varchar(4) DEFAULT NULL,
  `email_address_id` char(36) NOT NULL,
  `deleted` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `emails_text`
--

CREATE TABLE IF NOT EXISTS `emails_text` (
  `email_id` char(36) NOT NULL,
  `from_addr` varchar(255) DEFAULT NULL,
  `reply_to_addr` varchar(255) DEFAULT NULL,
  `to_addrs` text,
  `cc_addrs` text,
  `bcc_addrs` text,
  `description` longtext,
  `description_html` longtext,
  `raw_source` longtext,
  `deleted` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `email_addresses`
--

CREATE TABLE IF NOT EXISTS `email_addresses` (
  `id` char(36) NOT NULL,
  `email_address` varchar(255) DEFAULT NULL,
  `email_address_caps` varchar(255) DEFAULT NULL,
  `invalid_email` tinyint(1) DEFAULT '0',
  `opt_out` tinyint(1) DEFAULT '0',
  `confirm_opt_in` varchar(255) DEFAULT 'not-opt-in',
  `confirm_opt_in_date` datetime DEFAULT NULL,
  `confirm_opt_in_sent_date` datetime DEFAULT NULL,
  `confirm_opt_in_fail_date` datetime DEFAULT NULL,
  `confirm_opt_in_token` varchar(255) DEFAULT NULL,
  `date_created` datetime DEFAULT NULL,
  `date_modified` datetime DEFAULT NULL,
  `deleted` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `email_addresses`
--

INSERT INTO `email_addresses` (`id`, `email_address`, `email_address_caps`, `invalid_email`, `opt_out`, `confirm_opt_in`, `confirm_opt_in_date`, `confirm_opt_in_sent_date`, `confirm_opt_in_fail_date`, `confirm_opt_in_token`, `date_created`, `date_modified`, `deleted`) VALUES
('2f2df9cc-5718-8d4d-9082-5b4001239c92', 'info@sinergiacrm.org', 'INFO@SINERGIACRM.ORG', 0, 0, 'not-opt-in', NULL, NULL, NULL, NULL, '2020-07-04 10:16:13', '2020-07-04 10:16:13', 0),
('65e40330-84da-512c-2b83-5e830efa3986', 'test@test.com', 'TEST@TEST.COM', 0, 0, 'not-opt-in', NULL, NULL, NULL, NULL, '2020-07-04 08:15:42', '2020-07-04 08:15:42', 0),
('9357c4b2-d67e-1abd-b03e-5b40013ced29', 'test@test.com', 'TEST@TEST.COM', 0, 0, 'not-opt-in', NULL, NULL, NULL, NULL, '2020-07-04 10:16:13', '2020-07-04 10:16:13', 0);

-- --------------------------------------------------------

--
-- Table structure for table `email_addresses_audit`
--

CREATE TABLE IF NOT EXISTS `email_addresses_audit` (
  `id` char(36) NOT NULL,
  `parent_id` char(36) NOT NULL,
  `date_created` datetime DEFAULT NULL,
  `created_by` varchar(36) DEFAULT NULL,
  `field_name` varchar(100) DEFAULT NULL,
  `data_type` varchar(100) DEFAULT NULL,
  `before_value_string` varchar(255) DEFAULT NULL,
  `after_value_string` varchar(255) DEFAULT NULL,
  `before_value_text` text,
  `after_value_text` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `email_addr_bean_rel`
--

CREATE TABLE IF NOT EXISTS `email_addr_bean_rel` (
  `id` char(36) NOT NULL,
  `email_address_id` char(36) NOT NULL,
  `bean_id` char(36) NOT NULL,
  `bean_module` varchar(100) DEFAULT NULL,
  `primary_address` tinyint(1) DEFAULT '0',
  `reply_to_address` tinyint(1) DEFAULT '0',
  `date_created` datetime DEFAULT NULL,
  `date_modified` datetime DEFAULT NULL,
  `deleted` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `email_addr_bean_rel`
--

INSERT INTO `email_addr_bean_rel` (`id`, `email_address_id`, `bean_id`, `bean_module`, `primary_address`, `reply_to_address`, `date_created`, `date_modified`, `deleted`) VALUES
('2f13a5a1-f837-dbee-771d-5b4001e86a0d', '2f2df9cc-5718-8d4d-9082-5b4001239c92', '2', 'Users', 1, 0, '2020-07-04 10:16:13', '2020-07-04 10:16:13', 0),
('61410d75-69b9-ccf2-917f-5e830ef631dc', '65e40330-84da-512c-2b83-5e830efa3986', '1', 'Users', 1, 0, '2020-07-04 08:15:42', '2020-07-04 08:15:42', 0),
('933c61d2-15e4-fa94-154e-5b40010d7b19', '9357c4b2-d67e-1abd-b03e-5b40013ced29', '1', 'Users', 1, 0, '2020-07-04 10:16:13', '2020-07-04 10:16:13', 0);

-- --------------------------------------------------------

--
-- Table structure for table `email_cache`
--

CREATE TABLE IF NOT EXISTS `email_cache` (
  `ie_id` char(36) DEFAULT NULL,
  `mbox` varchar(60) DEFAULT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `fromaddr` varchar(100) DEFAULT NULL,
  `toaddr` varchar(255) DEFAULT NULL,
  `senddate` datetime DEFAULT NULL,
  `message_id` varchar(255) DEFAULT NULL,
  `mailsize` int(10) unsigned DEFAULT NULL,
  `imap_uid` int(10) unsigned DEFAULT NULL,
  `msgno` int(10) unsigned DEFAULT NULL,
  `recent` tinyint(4) DEFAULT NULL,
  `flagged` tinyint(4) DEFAULT NULL,
  `answered` tinyint(4) DEFAULT NULL,
  `deleted` tinyint(4) DEFAULT NULL,
  `seen` tinyint(4) DEFAULT NULL,
  `draft` tinyint(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `email_marketing`
--

CREATE TABLE IF NOT EXISTS `email_marketing` (
  `id` char(36) NOT NULL,
  `deleted` tinyint(1) DEFAULT NULL,
  `date_entered` datetime DEFAULT NULL,
  `date_modified` datetime DEFAULT NULL,
  `modified_user_id` char(36) DEFAULT NULL,
  `created_by` char(36) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `from_name` varchar(100) DEFAULT NULL,
  `from_addr` varchar(100) DEFAULT NULL,
  `reply_to_name` varchar(100) DEFAULT NULL,
  `reply_to_addr` varchar(100) DEFAULT NULL,
  `inbound_email_id` varchar(36) DEFAULT NULL,
  `date_start` datetime DEFAULT NULL,
  `template_id` char(36) NOT NULL,
  `status` varchar(100) DEFAULT NULL,
  `campaign_id` char(36) DEFAULT NULL,
  `outbound_email_id` char(36) DEFAULT NULL,
  `all_prospect_lists` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `email_marketing_prospect_lists`
--

CREATE TABLE IF NOT EXISTS `email_marketing_prospect_lists` (
  `id` varchar(36) NOT NULL,
  `prospect_list_id` varchar(36) DEFAULT NULL,
  `email_marketing_id` varchar(36) DEFAULT NULL,
  `date_modified` datetime DEFAULT NULL,
  `deleted` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `email_templates`
--

CREATE TABLE IF NOT EXISTS `email_templates` (
  `id` char(36) NOT NULL,
  `date_entered` datetime DEFAULT NULL,
  `date_modified` datetime DEFAULT NULL,
  `modified_user_id` char(36) DEFAULT NULL,
  `created_by` varchar(36) DEFAULT NULL,
  `published` varchar(3) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `description` text,
  `subject` varchar(255) DEFAULT NULL,
  `body` longtext,
  `body_html` longtext,
  `deleted` tinyint(1) DEFAULT NULL,
  `assigned_user_id` char(36) DEFAULT NULL,
  `text_only` tinyint(1) DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `email_templates`
--

INSERT INTO `email_templates` (`id`, `date_entered`, `date_modified`, `modified_user_id`, `created_by`, `published`, `name`, `description`, `subject`, `body`, `body_html`, `deleted`, `assigned_user_id`, `text_only`, `type`) VALUES
('14f18ded-378d-ab84-3237-5e830d4e874d', '2020-07-04 08:15:43', '2020-07-04 08:15:43', '1', '1', 'off', 'User Case Update', 'Email template to send to a Sugar user when their case is updated.', '$acase_name (# $acase_case_number) update', 'Hi $user_first_name $user_last_name,\n\n					   You''ve had an update to your case $acase_name (# $acase_case_number) on $aop_case_updates_date_entered:\n					       $contact_first_name $contact_last_name, said:\n					               $aop_case_updates_description\n                        You may review this Case at:\n                            $sugarurl/index.php?module=Cases&action=DetailView&record=$acase_id;', '<p>Hi $user_first_name $user_last_name,</p>\n					     <p> </p>\n					     <p>You''ve had an update to your case $acase_name (# $acase_case_number) on $aop_case_updates_date_entered:</p>\n					     <p><strong>$contact_first_name $contact_last_name, said:</strong></p>\n					     <p style="padding-left:30px;">$aop_case_updates_description</p>\n					     <p>You may review this Case at: $sugarurl/index.php?module=Cases&action=DetailView&record=$acase_id;</p>', 0, NULL, NULL, 'system'),
('267275d8-959c-6aed-8754-5e830d07fedf', '2020-07-04 08:15:43', '2020-07-04 08:15:43', '1', '1', 'off', 'Event Invite Template', 'Default event invite template.', 'You have been invited to $fp_events_name', 'Dear $contact_name,\nYou have been invited to $fp_events_name on $fp_events_date_start to $fp_events_date_end\n$fp_events_description\nYours Sincerely,\n', '\n<p>Dear $contact_name,</p>\n<p>You have been invited to $fp_events_name on $fp_events_date_start to $fp_events_date_end</p>\n<p>$fp_events_description</p>\n<p>If you would like to accept this invititation please click accept.</p>\n<p> $fp_events_link or $fp_events_link_declined</p>\n<p>Yours Sincerely,</p>\n', 0, NULL, NULL, 'system'),
('483e1c3b-51dc-b5ab-e220-5e830d52596a', '2020-07-04 08:15:43', '2020-07-04 08:15:43', '1', '1', 'off', 'Confirmed Opt In', 'Email template to send to a contact to confirm they have opted in.', 'Confirm Opt In', 'Hi $contact_first_name $contact_last_name, \\n Please confirm that you have opted in by selecting the following link: $sugarurl/index.php?entryPoint=ConfirmOptIn&from=$emailaddress_email_address', '<p>Hi $contact_first_name $contact_last_name,</p>\n             <p>\n                Please confirm that you have opted in by selecting the following link:\n                <a href="$sugarurl/index.php?entryPoint=ConfirmOptIn&from=$emailaddress_confirm_opt_in_token">Opt In</a>\n             </p>', 0, NULL, NULL, 'system'),
('b4477938-f3c4-bef6-292c-5e830dee6ceb', '2020-07-04 08:15:43', '2020-07-04 08:15:43', '1', '1', 'off', 'System-generated password email', 'This template is used when the System Administrator sends a new password to a user.', 'New account information', '\nHere is your account username and temporary password:\nUsername : $contact_user_user_name\nPassword : $contact_user_user_hash\n\n$config_site_url\n\nAfter you log in using the above password, you may be required to reset the password to one of your own choice.', '<div><table width="550"><tbody><tr><td><p>Here is your account username and temporary password:</p><p>Username : $contact_user_user_name </p><p>Password : $contact_user_user_hash </p><br /><p>$config_site_url</p><br /><p>After you log in using the above password, you may be required to reset the password to one of your own choice.</p>   </td>         </tr><tr><td></td>         </tr></tbody></table></div>', 0, NULL, 0, 'system'),
('bc4c87c3-febd-73d5-cf73-5e830dd3bac0', '2020-07-04 08:15:43', '2020-07-04 08:15:43', '1', '1', 'off', 'Forgot Password email', 'This template is used to send a user a link to click to reset the user''s account password.', 'Reset your account password', '\nYou recently requested on $contact_user_pwd_last_changed to be able to reset your account password.\n\nClick on the link below to reset your password:\n\n$contact_user_link_guid', '<div><table width="550"><tbody><tr><td><p>You recently requested on $contact_user_pwd_last_changed to be able to reset your account password. </p><p>Click on the link below to reset your password:</p><p> $contact_user_link_guid </p>  </td>         </tr><tr><td></td>         </tr></tbody></table></div>', 0, NULL, 0, 'system'),
('c4440a38-10b9-ef63-0e40-5e830d1c293d', '2020-07-04 08:15:43', '2020-07-04 08:15:43', '1', '1', 'off', 'Two Factor Authentication email', 'This template is used to send a user a code for Two Factor Authentication.', 'Two Factor Authentication Code', 'Two Factor Authentication code is $code.', '<div><table width="550"><tbody><tr><td><p>Two Factor Authentication code is <b>$code</b>.</p>  </td>         </tr><tr><td></td>         </tr></tbody></table></div>', 0, NULL, 0, 'system'),
('c6c4093f-0a1a-30d8-5e33-5e830d6ddd51', '2020-07-04 08:15:43', '2020-07-04 08:15:43', '1', '1', 'off', 'Contact Case Update', 'Template to send to a contact when their case is updated.', '$acase_name update [CASE:$acase_case_number]', 'Hi $user_first_name $user_last_name,\n\n					   You''ve had an update to your case $acase_name (# $acase_case_number) on $aop_case_updates_date_entered:\n					       $contact_first_name $contact_last_name, said:\n					               $aop_case_updates_description', '<p>Hi $contact_first_name $contact_last_name,</p>\n					    <p> </p>\n					    <p>You''ve had an update to your case $acase_name (# $acase_case_number) on $aop_case_updates_date_entered:</p>\n					    <p><strong>$user_first_name $user_last_name said:</strong></p>\n					    <p style="padding-left:30px;">$aop_case_updates_description</p>', 0, NULL, NULL, 'system'),
('d3b49c38-eeba-b910-2590-5b4069caaac1', '2020-07-04 10:16:15', '2020-07-04 10:16:15', '1', '1', NULL, 'Example - Donation', 'Example email template for donation confirmation and acknowledgement', 'Thank you for your donation!', 'Dear $contact_first_name $contact_last_name,\r\n\r\nWe have successfully received your donation of $stic_payments_amount €.\r\n\r\nThank you very much for your support!\r\n\r\n', '<p>Dear $contact_first_name $contact_last_name,</p>\n<p>We have successfully received your donation of $stic_payments_amount €.</p>\n<p>Thank you for your support!</p>', 0, '1', 0, 'email'),
('dba65685-d26f-6ecd-802c-5b406983c9b5', '2020-07-04 10:16:15', '2020-07-04 10:16:15', '1', '1', NULL, 'Example - Registration', 'Example email template for registration confirmation', 'Registration confirmation to $stic_events_name', 'Dear $contact_first_name $contact_last_name,\r\n\r\nWe have successfully received your registration to $stic_events_name.\r\n\r\nOn $stic_registrations_registration_date your registration is $stic_registrations_status.\r\n\r\nSee you on $stic_events_start_date!', '<p>Dear $contact_first_name $contact_last_name,</p>\n<p>We have successfully received your registration to $stic_events_name.</p>\n<p>On $stic_registrations_registration_date your registration is $stic_registrations_status.</p>\n<p>See you on $stic_events_start_date!</p>', 0, '1', 0, 'email'),
('e61c85c2-e6cb-6dda-3760-5e830d31fbdc', '2020-07-04 08:15:43', '2020-07-04 08:15:43', '1', '1', 'off', 'Case Closure', 'Template for informing a contact that their case has been closed.', '$acase_name [CASE:$acase_case_number] closed', 'Hi $contact_first_name $contact_last_name,\n\n					   Your case $acase_name (# $acase_case_number) has been closed on $acase_date_entered\n					   Status:				$acase_status\n					   Reference:			$acase_case_number\n					   Resolution:			$acase_resolution', '<p> Hi $contact_first_name $contact_last_name,</p>\n					    <p>Your case $acase_name (# $acase_case_number) has been closed on $acase_date_entered</p>\n					    <table border="0"><tbody><tr><td>Status</td><td>$acase_status</td></tr><tr><td>Reference</td><td>$acase_case_number</td></tr><tr><td>Resolution</td><td>$acase_resolution</td></tr></tbody></table>', 0, NULL, NULL, 'system'),
('e9e00ea2-ac1b-a635-9671-5e830d66a582', '2020-07-04 08:15:43', '2020-07-04 08:15:43', '1', '1', 'off', 'Case Creation', 'Template to send to a contact when a case is received from them.', '$acase_name [CASE:$acase_case_number]', 'Hi $contact_first_name $contact_last_name,\n\n					   We''ve received your case $acase_name (# $acase_case_number) on $acase_date_entered\n					   Status:		$acase_status\n					   Reference:	$acase_case_number\n					   Description:	$acase_description', '<p> Hi $contact_first_name $contact_last_name,</p>\n					    <p>We''ve received your case $acase_name (# $acase_case_number) on $acase_date_entered</p>\n					    <table border="0"><tbody><tr><td>Status</td><td>$acase_status</td></tr><tr><td>Reference</td><td>$acase_case_number</td></tr><tr><td>Description</td><td>$acase_description</td></tr></tbody></table>', 0, NULL, NULL, 'system'),
('ed9867a2-1f56-b684-4bc6-5e830d215b5d', '2020-07-04 08:15:43', '2020-07-04 08:15:43', '1', '1', 'off', 'Joomla Account Creation', 'Template used when informing a contact that they''ve been given an account on the joomla portal.', 'Support Portal Account Created', 'Hi $contact_name,\n					   An account has been created for you at $portal_address.\n					   You may login using this email address and the password $joomla_pass', '<p>Hi $contact_name,</p>\n					    <p>An account has been created for you at <a href="$portal_address">$portal_address</a>.</p>\n					    <p>You may login using this email address and the password $joomla_pass</p>', 0, NULL, NULL, 'system');

-- --------------------------------------------------------

--
-- Table structure for table `favorites`
--

CREATE TABLE IF NOT EXISTS `favorites` (
  `id` char(36) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `date_entered` datetime DEFAULT NULL,
  `date_modified` datetime DEFAULT NULL,
  `modified_user_id` char(36) DEFAULT NULL,
  `created_by` char(36) DEFAULT NULL,
  `description` text,
  `deleted` tinyint(1) DEFAULT '0',
  `assigned_user_id` char(36) DEFAULT NULL,
  `parent_id` char(36) DEFAULT NULL,
  `parent_type` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `fields_meta_data`
--

CREATE TABLE IF NOT EXISTS `fields_meta_data` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `vname` varchar(255) DEFAULT NULL,
  `comments` varchar(255) DEFAULT NULL,
  `help` varchar(255) DEFAULT NULL,
  `custom_module` varchar(255) DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL,
  `len` int(11) DEFAULT NULL,
  `required` tinyint(1) DEFAULT '0',
  `default_value` varchar(255) DEFAULT NULL,
  `date_modified` datetime DEFAULT NULL,
  `deleted` tinyint(1) DEFAULT '0',
  `audited` tinyint(1) DEFAULT '0',
  `massupdate` tinyint(1) DEFAULT '0',
  `duplicate_merge` smallint(6) DEFAULT '0',
  `reportable` tinyint(1) DEFAULT '1',
  `importable` varchar(255) DEFAULT NULL,
  `ext1` varchar(255) DEFAULT NULL,
  `ext2` varchar(255) DEFAULT NULL,
  `ext3` varchar(255) DEFAULT NULL,
  `ext4` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `fields_meta_data`
--

INSERT INTO `fields_meta_data` (`id`, `name`, `vname`, `comments`, `help`, `custom_module`, `type`, `len`, `required`, `default_value`, `date_modified`, `deleted`, `audited`, `massupdate`, `duplicate_merge`, `reportable`, `importable`, `ext1`, `ext2`, `ext3`, `ext4`) VALUES
('Accountsjjwg_maps_address_c', 'jjwg_maps_address_c', 'LBL_JJWG_MAPS_ADDRESS', 'Address', 'Address', 'Accounts', 'varchar', 255, 0, NULL, '2020-07-04 08:15:43', 0, 0, 0, 0, 1, 'true', NULL, '', '', ''),
('Accountsjjwg_maps_geocode_status_c', 'jjwg_maps_geocode_status_c', 'LBL_JJWG_MAPS_GEOCODE_STATUS', 'Geocode Status', 'Geocode Status', 'Accounts', 'varchar', 255, 0, NULL, '2020-07-04 08:15:43', 0, 0, 0, 0, 1, 'true', NULL, '', '', ''),
('Accountsjjwg_maps_lat_c', 'jjwg_maps_lat_c', 'LBL_JJWG_MAPS_LAT', '', 'Latitude', 'Accounts', 'float', 10, 0, '0.00000000', '2020-07-04 08:15:43', 0, 0, 0, 0, 1, 'true', '8', '', '', ''),
('Accountsjjwg_maps_lng_c', 'jjwg_maps_lng_c', 'LBL_JJWG_MAPS_LNG', '', 'Longitude', 'Accounts', 'float', 11, 0, '0.00000000', '2020-07-04 08:15:43', 0, 0, 0, 0, 1, 'true', '8', '', '', ''),
('Accountsstic_182_error_c', 'stic_182_error_c', NULL, NULL, NULL, 'Accounts', NULL, NULL, 0, NULL, NULL, 0, 0, 0, 0, 1, NULL, NULL, NULL, NULL, NULL),
('Accountsstic_182_excluded_c', 'stic_182_excluded_c', NULL, NULL, NULL, 'Accounts', NULL, NULL, 0, NULL, NULL, 0, 0, 0, 0, 1, NULL, NULL, NULL, NULL, NULL),
('Accountsstic_acronym_c', 'stic_acronym_c', NULL, NULL, NULL, 'Accounts', NULL, NULL, 0, NULL, NULL, 0, 0, 0, 0, 1, NULL, NULL, NULL, NULL, NULL),
('Accountsstic_billing_address_county_c', 'stic_billing_address_county_c', NULL, NULL, NULL, 'Accounts', NULL, NULL, 0, NULL, NULL, 0, 0, 0, 0, 1, NULL, NULL, NULL, NULL, NULL),
('Accountsstic_billing_address_region_c', 'stic_billing_address_region_c', NULL, NULL, NULL, 'Accounts', NULL, NULL, 0, NULL, NULL, 0, 0, 0, 0, 1, NULL, NULL, NULL, NULL, NULL),
('Accountsstic_billing_address_type_c', 'stic_billing_address_type_c', NULL, NULL, NULL, 'Accounts', NULL, NULL, 0, NULL, NULL, 0, 0, 0, 0, 1, NULL, NULL, NULL, NULL, NULL),
('Accountsstic_category_c', 'stic_category_c', NULL, NULL, NULL, 'Accounts', NULL, NULL, 0, NULL, NULL, 0, 0, 0, 0, 1, NULL, NULL, NULL, NULL, NULL),
('Accountsstic_identification_number_c', 'stic_identification_number_c', NULL, NULL, NULL, 'Accounts', NULL, NULL, 0, NULL, NULL, 0, 0, 0, 0, 1, NULL, NULL, NULL, NULL, NULL),
('Accountsstic_language_c', 'stic_language_c', NULL, NULL, NULL, 'Accounts', NULL, NULL, 0, NULL, NULL, 0, 0, 0, 0, 1, NULL, NULL, NULL, NULL, NULL),
('Accountsstic_postal_mail_return_reason_c', 'stic_postal_mail_return_reason_c', NULL, NULL, NULL, 'Accounts', NULL, NULL, 0, NULL, NULL, 0, 0, 0, 0, 1, NULL, NULL, NULL, NULL, NULL),
('Accountsstic_relationship_type_c', 'stic_relationship_type_c', NULL, NULL, NULL, 'Accounts', NULL, NULL, 0, NULL, NULL, 0, 0, 0, 0, 1, NULL, NULL, NULL, NULL, NULL),
('Accountsstic_shipping_address_county_c', 'stic_shipping_address_county_c', NULL, NULL, NULL, 'Accounts', NULL, NULL, 0, NULL, NULL, 0, 0, 0, 0, 1, NULL, NULL, NULL, NULL, NULL),
('Accountsstic_shipping_address_region_c', 'stic_shipping_address_region_c', NULL, NULL, NULL, 'Accounts', NULL, NULL, 0, NULL, NULL, 0, 0, 0, 0, 1, NULL, NULL, NULL, NULL, NULL),
('Accountsstic_shipping_address_type_c', 'stic_shipping_address_type_c', NULL, NULL, NULL, 'Accounts', NULL, NULL, 0, NULL, NULL, 0, 0, 0, 0, 1, NULL, NULL, NULL, NULL, NULL),
('Accountsstic_subcategory_c', 'stic_subcategory_c', NULL, NULL, NULL, 'Accounts', NULL, NULL, 0, NULL, NULL, 0, 0, 0, 0, 1, NULL, NULL, NULL, NULL, NULL),
('Accountsstic_tax_name_c', 'stic_tax_name_c', NULL, NULL, NULL, 'Accounts', NULL, NULL, 0, NULL, NULL, 0, 0, 0, 0, 1, NULL, NULL, NULL, NULL, NULL),
('Accountsstic_total_annual_donation_c', 'stic_total_annual_donation_c', NULL, NULL, NULL, 'Accounts', NULL, NULL, 0, NULL, NULL, 0, 0, 0, 0, 1, NULL, NULL, NULL, NULL, NULL),
('Casesjjwg_maps_address_c', 'jjwg_maps_address_c', 'LBL_JJWG_MAPS_ADDRESS', 'Address', 'Address', 'Cases', 'varchar', 255, 0, NULL, '2020-07-04 08:15:43', 0, 0, 0, 0, 1, 'true', NULL, '', '', ''),
('Casesjjwg_maps_geocode_status_c', 'jjwg_maps_geocode_status_c', 'LBL_JJWG_MAPS_GEOCODE_STATUS', 'Geocode Status', 'Geocode Status', 'Cases', 'varchar', 255, 0, NULL, '2020-07-04 08:15:43', 0, 0, 0, 0, 1, 'true', NULL, '', '', ''),
('Casesjjwg_maps_lat_c', 'jjwg_maps_lat_c', 'LBL_JJWG_MAPS_LAT', '', 'Latitude', 'Cases', 'float', 10, 0, '0.00000000', '2020-07-04 08:15:43', 0, 0, 0, 0, 1, 'true', '8', '', '', ''),
('Casesjjwg_maps_lng_c', 'jjwg_maps_lng_c', 'LBL_JJWG_MAPS_LNG', '', 'Longitude', 'Cases', 'float', 11, 0, '0.00000000', '2020-07-04 08:15:43', 0, 0, 0, 0, 1, 'true', '8', '', '', ''),
('Contactsjjwg_maps_address_c', 'jjwg_maps_address_c', 'LBL_JJWG_MAPS_ADDRESS', 'Address', 'Address', 'Contacts', 'varchar', 255, 0, NULL, '2020-07-04 08:15:43', 0, 0, 0, 0, 1, 'true', NULL, '', '', ''),
('Contactsjjwg_maps_geocode_status_c', 'jjwg_maps_geocode_status_c', 'LBL_JJWG_MAPS_GEOCODE_STATUS', 'Geocode Status', 'Geocode Status', 'Contacts', 'varchar', 255, 0, NULL, '2020-07-04 08:15:43', 0, 0, 0, 0, 1, 'true', NULL, '', '', ''),
('Contactsjjwg_maps_lat_c', 'jjwg_maps_lat_c', 'LBL_JJWG_MAPS_LAT', '', 'Latitude', 'Contacts', 'float', 10, 0, '0.00000000', '2020-07-04 08:15:43', 0, 0, 0, 0, 1, 'true', '8', '', '', ''),
('Contactsjjwg_maps_lng_c', 'jjwg_maps_lng_c', 'LBL_JJWG_MAPS_LNG', '', 'Longitude', 'Contacts', 'float', 11, 0, '0.00000000', '2020-07-04 08:15:43', 0, 0, 0, 0, 1, 'true', '8', '', '', ''),
('Contactsstic_182_error_c', 'stic_182_error_c', NULL, NULL, NULL, 'Contacts', NULL, NULL, 0, NULL, NULL, 0, 0, 0, 0, 1, NULL, NULL, NULL, NULL, NULL),
('Contactsstic_182_excluded_c', 'stic_182_excluded_c', NULL, NULL, NULL, 'Contacts', NULL, NULL, 0, NULL, NULL, 0, 0, 0, 0, 1, NULL, NULL, NULL, NULL, NULL),
('Contactsstic_acquisition_channel_c', 'stic_acquisition_channel_c', NULL, NULL, NULL, 'Contacts', NULL, NULL, 0, NULL, NULL, 0, 0, 0, 0, 1, NULL, NULL, NULL, NULL, NULL),
('Contactsstic_age_c', 'stic_age_c', NULL, NULL, NULL, 'Contacts', NULL, NULL, 0, NULL, NULL, 0, 0, 0, 0, 1, NULL, NULL, NULL, NULL, NULL),
('Contactsstic_alt_address_county_c', 'stic_alt_address_county_c', NULL, NULL, NULL, 'Contacts', NULL, NULL, 0, NULL, NULL, 0, 0, 0, 0, 1, NULL, NULL, NULL, NULL, NULL),
('Contactsstic_alt_address_region_c', 'stic_alt_address_region_c', NULL, NULL, NULL, 'Contacts', NULL, NULL, 0, NULL, NULL, 0, 0, 0, 0, 1, NULL, NULL, NULL, NULL, NULL),
('Contactsstic_alt_address_type_c', 'stic_alt_address_type_c', NULL, NULL, NULL, 'Contacts', NULL, NULL, 0, NULL, NULL, 0, 0, 0, 0, 1, NULL, NULL, NULL, NULL, NULL),
('Contactsstic_do_not_send_postal_mail_c', 'stic_do_not_send_postal_mail_c', NULL, NULL, NULL, 'Contacts', NULL, NULL, 0, NULL, NULL, 0, 0, 0, 0, 1, NULL, NULL, NULL, NULL, NULL),
('Contactsstic_employment_status_c', 'stic_employment_status_c', NULL, NULL, NULL, 'Contacts', NULL, NULL, 0, NULL, NULL, 0, 0, 0, 0, 1, NULL, NULL, NULL, NULL, NULL),
('Contactsstic_gender_c', 'stic_gender_c', NULL, NULL, NULL, 'Contacts', NULL, NULL, 0, NULL, NULL, 0, 0, 0, 0, 1, NULL, NULL, NULL, NULL, NULL),
('Contactsstic_identification_number_c', 'stic_identification_number_c', NULL, NULL, NULL, 'Contacts', NULL, NULL, 0, NULL, NULL, 0, 0, 0, 0, 1, NULL, NULL, NULL, NULL, NULL),
('Contactsstic_identification_type_c', 'stic_identification_type_c', NULL, NULL, NULL, 'Contacts', NULL, NULL, 0, NULL, NULL, 0, 0, 0, 0, 1, NULL, NULL, NULL, NULL, NULL),
('Contactsstic_language_c', 'stic_language_c', NULL, NULL, NULL, 'Contacts', NULL, NULL, 0, NULL, NULL, 0, 0, 0, 0, 1, NULL, NULL, NULL, NULL, NULL),
('Contactsstic_postal_mail_return_reason_c', 'stic_postal_mail_return_reason_c', NULL, NULL, NULL, 'Contacts', NULL, NULL, 0, NULL, NULL, 0, 0, 0, 0, 1, NULL, NULL, NULL, NULL, NULL),
('Contactsstic_preferred_contact_channel_c', 'stic_preferred_contact_channel_c', NULL, NULL, NULL, 'Contacts', NULL, NULL, 0, NULL, NULL, 0, 0, 0, 0, 1, NULL, NULL, NULL, NULL, NULL),
('Contactsstic_primary_address_county_c', 'stic_primary_address_county_c', NULL, NULL, NULL, 'Contacts', NULL, NULL, 0, NULL, NULL, 0, 0, 0, 0, 1, NULL, NULL, NULL, NULL, NULL),
('Contactsstic_primary_address_region_c', 'stic_primary_address_region_c', NULL, NULL, NULL, 'Contacts', NULL, NULL, 0, NULL, NULL, 0, 0, 0, 0, 1, NULL, NULL, NULL, NULL, NULL),
('Contactsstic_primary_address_type_c', 'stic_primary_address_type_c', NULL, NULL, NULL, 'Contacts', NULL, NULL, 0, NULL, NULL, 0, 0, 0, 0, 1, NULL, NULL, NULL, NULL, NULL),
('Contactsstic_professional_sector_c', 'stic_professional_sector_c', NULL, NULL, NULL, 'Contacts', NULL, NULL, 0, NULL, NULL, 0, 0, 0, 0, 1, NULL, NULL, NULL, NULL, NULL),
('Contactsstic_professional_sector_other_c', 'stic_professional_sector_other_c', NULL, NULL, NULL, 'Contacts', NULL, NULL, 0, NULL, NULL, 0, 0, 0, 0, 1, NULL, NULL, NULL, NULL, NULL),
('Contactsstic_referral_agent_c', 'stic_referral_agent_c', NULL, NULL, NULL, 'Contacts', NULL, NULL, 0, NULL, NULL, 0, 0, 0, 0, 1, NULL, NULL, NULL, NULL, NULL),
('Contactsstic_relationship_type_c', 'stic_relationship_type_c', NULL, NULL, NULL, 'Contacts', NULL, NULL, 0, NULL, NULL, 0, 0, 0, 0, 1, NULL, NULL, NULL, NULL, NULL),
('Contactsstic_stic_tax_name_c', 'stic_tax_name_c', NULL, NULL, NULL, 'Contacts', NULL, NULL, 0, NULL, NULL, 0, 0, 0, 0, 1, NULL, NULL, NULL, NULL, NULL),
('Contactsstic_total_annual_donations_c', 'stic_total_annual_donations_c', NULL, NULL, NULL, 'Contacts', NULL, NULL, 0, NULL, NULL, 0, 0, 0, 0, 1, NULL, NULL, NULL, NULL, NULL),
('Documentsstic_shared_document_link_c', 'stic_shared_document_link_c', NULL, NULL, NULL, 'Documents', NULL, NULL, 0, NULL, NULL, 0, 0, 0, 0, 1, NULL, NULL, NULL, NULL, NULL),
('FP_Event_Locationsstic_address_county_c', 'stic_address_county_c', NULL, NULL, NULL, 'FP_Event_Locations', NULL, NULL, 0, NULL, NULL, 0, 0, 0, 0, 1, NULL, NULL, NULL, NULL, NULL),
('FP_Event_Locationsstic_address_region_c', 'stic_address_region_c', NULL, NULL, NULL, 'FP_Event_Locations', NULL, NULL, 0, NULL, NULL, 0, 0, 0, 0, 1, NULL, NULL, NULL, NULL, NULL),
('Leadsjjwg_maps_address_c', 'jjwg_maps_address_c', 'LBL_JJWG_MAPS_ADDRESS', 'Address', 'Address', 'Leads', 'varchar', 255, 0, NULL, '2020-07-04 08:15:43', 0, 0, 0, 0, 1, 'true', NULL, '', '', ''),
('Leadsjjwg_maps_geocode_status_c', 'jjwg_maps_geocode_status_c', 'LBL_JJWG_MAPS_GEOCODE_STATUS', 'Geocode Status', 'Geocode Status', 'Leads', 'varchar', 255, 0, NULL, '2020-07-04 08:15:43', 0, 0, 0, 0, 1, 'true', NULL, '', '', ''),
('Leadsjjwg_maps_lat_c', 'jjwg_maps_lat_c', 'LBL_JJWG_MAPS_LAT', '', 'Latitude', 'Leads', 'float', 10, 0, '0.00000000', '2020-07-04 08:15:43', 0, 0, 0, 0, 1, 'true', '8', '', '', ''),
('Leadsjjwg_maps_lng_c', 'jjwg_maps_lng_c', 'LBL_JJWG_MAPS_LNG', '', 'Longitude', 'Leads', 'float', 11, 0, '0.00000000', '2020-07-04 08:15:43', 0, 0, 0, 0, 1, 'true', '8', '', '', ''),
('Leadsstic_acquisition_channel_c', 'stic_acquisition_channel_c', NULL, NULL, NULL, 'Leads', NULL, NULL, 0, NULL, NULL, 0, 0, 0, 0, 1, NULL, NULL, NULL, NULL, NULL),
('Leadsstic_alt_address_county_c', 'stic_alt_address_county_c', NULL, NULL, NULL, 'Leads', NULL, NULL, 0, NULL, NULL, 0, 0, 0, 0, 1, NULL, NULL, NULL, NULL, NULL),
('Leadsstic_alt_address_region_c', 'stic_alt_address_region_c', NULL, NULL, NULL, 'Leads', NULL, NULL, 0, NULL, NULL, 0, 0, 0, 0, 1, NULL, NULL, NULL, NULL, NULL),
('Leadsstic_alt_address_type_c', 'stic_alt_address_type_c', NULL, NULL, NULL, 'Leads', NULL, NULL, 0, NULL, NULL, 0, 0, 0, 0, 1, NULL, NULL, NULL, NULL, NULL),
('Leadsstic_do_not_send_postal_mail_c', 'stic_do_not_send_postal_mail_c', NULL, NULL, NULL, 'Leads', NULL, NULL, 0, NULL, NULL, 0, 0, 0, 0, 1, NULL, NULL, NULL, NULL, NULL),
('Leadsstic_employment_status_c', 'stic_employment_status_c', NULL, NULL, NULL, 'Leads', NULL, NULL, 0, NULL, NULL, 0, 0, 0, 0, 1, NULL, NULL, NULL, NULL, NULL),
('Leadsstic_gender_c', 'stic_gender_c', NULL, NULL, NULL, 'Leads', NULL, NULL, 0, NULL, NULL, 0, 0, 0, 0, 1, NULL, NULL, NULL, NULL, NULL),
('Leadsstic_identification_number_c', 'stic_identification_number_c', NULL, NULL, NULL, 'Leads', NULL, NULL, 0, NULL, NULL, 0, 0, 0, 0, 1, NULL, NULL, NULL, NULL, NULL),
('Leadsstic_identification_type_c', 'stic_identification_type_c', NULL, NULL, NULL, 'Leads', NULL, NULL, 0, NULL, NULL, 0, 0, 0, 0, 1, NULL, NULL, NULL, NULL, NULL),
('Leadsstic_language_c', 'stic_language_c', NULL, NULL, NULL, 'Leads', NULL, NULL, 0, NULL, NULL, 0, 0, 0, 0, 1, NULL, NULL, NULL, NULL, NULL),
('Leadsstic_postal_mail_return_reason_c', 'stic_postal_mail_return_reason_c', NULL, NULL, NULL, 'Leads', NULL, NULL, 0, NULL, NULL, 0, 0, 0, 0, 1, NULL, NULL, NULL, NULL, NULL),
('Leadsstic_primary_address_county_c', 'stic_primary_address_county_c', NULL, NULL, NULL, 'Leads', NULL, NULL, 0, NULL, NULL, 0, 0, 0, 0, 1, NULL, NULL, NULL, NULL, NULL),
('Leadsstic_primary_address_region_c', 'stic_primary_address_region_c', NULL, NULL, NULL, 'Leads', NULL, NULL, 0, NULL, NULL, 0, 0, 0, 0, 1, NULL, NULL, NULL, NULL, NULL),
('Leadsstic_primary_address_type_c', 'stic_primary_address_type_c', NULL, NULL, NULL, 'Leads', NULL, NULL, 0, NULL, NULL, 0, 0, 0, 0, 1, NULL, NULL, NULL, NULL, NULL),
('Leadsstic_professional_sector_c', 'stic_professional_sector_c', NULL, NULL, NULL, 'Leads', NULL, NULL, 0, NULL, NULL, 0, 0, 0, 0, 1, NULL, NULL, NULL, NULL, NULL),
('Leadsstic_professional_sector_other_c', 'stic_professional_sector_other_c', NULL, NULL, NULL, 'Leads', NULL, NULL, 0, NULL, NULL, 0, 0, 0, 0, 1, NULL, NULL, NULL, NULL, NULL),
('Leadsstic_referral_agent_c', 'stic_referral_agent_c', NULL, NULL, NULL, 'Leads', NULL, NULL, 0, NULL, NULL, 0, 0, 0, 0, 1, NULL, NULL, NULL, NULL, NULL),
('Meetingsjjwg_maps_address_c', 'jjwg_maps_address_c', 'LBL_JJWG_MAPS_ADDRESS', 'Address', 'Address', 'Meetings', 'varchar', 255, 0, NULL, '2020-07-04 08:15:43', 0, 0, 0, 0, 1, 'true', NULL, '', '', ''),
('Meetingsjjwg_maps_geocode_status_c', 'jjwg_maps_geocode_status_c', 'LBL_JJWG_MAPS_GEOCODE_STATUS', 'Geocode Status', 'Geocode Status', 'Meetings', 'varchar', 255, 0, NULL, '2020-07-04 08:15:43', 0, 0, 0, 0, 1, 'true', NULL, '', '', ''),
('Meetingsjjwg_maps_lat_c', 'jjwg_maps_lat_c', 'LBL_JJWG_MAPS_LAT', '', 'Latitude', 'Meetings', 'float', 10, 0, '0.00000000', '2020-07-04 08:15:43', 0, 0, 0, 0, 1, 'true', '8', '', '', ''),
('Meetingsjjwg_maps_lng_c', 'jjwg_maps_lng_c', 'LBL_JJWG_MAPS_LNG', '', 'Longitude', 'Meetings', 'float', 11, 0, '0.00000000', '2020-07-04 08:15:43', 0, 0, 0, 0, 1, 'true', '8', '', '', ''),
('Opportunitiesjjwg_maps_address_c', 'jjwg_maps_address_c', 'LBL_JJWG_MAPS_ADDRESS', 'Address', 'Address', 'Opportunities', 'varchar', 255, 0, NULL, '2020-07-04 08:15:43', 0, 0, 0, 0, 1, 'true', NULL, '', '', ''),
('Opportunitiesjjwg_maps_geocode_status_c', 'jjwg_maps_geocode_status_c', 'LBL_JJWG_MAPS_GEOCODE_STATUS', 'Geocode Status', 'Geocode Status', 'Opportunities', 'varchar', 255, 0, NULL, '2020-07-04 08:15:43', 0, 0, 0, 0, 1, 'true', NULL, '', '', ''),
('Opportunitiesjjwg_maps_lat_c', 'jjwg_maps_lat_c', 'LBL_JJWG_MAPS_LAT', '', 'Latitude', 'Opportunities', 'float', 10, 0, '0.00000000', '2020-07-04 08:15:43', 0, 0, 0, 0, 1, 'true', '8', '', '', ''),
('Opportunitiesjjwg_maps_lng_c', 'jjwg_maps_lng_c', 'LBL_JJWG_MAPS_LNG', '', 'Longitude', 'Opportunities', 'float', 11, 0, '0.00000000', '2020-07-04 08:15:43', 0, 0, 0, 0, 1, 'true', '8', '', '', ''),
('Opportunitiesstic_advance_date_c', 'stic_advance_date_c', NULL, NULL, NULL, 'Opportunities', NULL, NULL, 0, NULL, NULL, 0, 0, 0, 0, 1, NULL, NULL, NULL, NULL, NULL),
('Opportunitiesstic_amount_awarded_c', 'stic_amount_awarded_c', NULL, NULL, NULL, 'Opportunities', NULL, NULL, 0, NULL, NULL, 0, 0, 0, 0, 1, NULL, NULL, NULL, NULL, NULL),
('Opportunitiesstic_amount_received_c', 'stic_amount_received_c', NULL, NULL, NULL, 'Opportunities', NULL, NULL, 0, NULL, NULL, 0, 0, 0, 0, 1, NULL, NULL, NULL, NULL, NULL),
('Opportunitiesstic_documentation_to_deliver_c', 'stic_documentation_to_deliver_c', NULL, NULL, NULL, 'Opportunities', NULL, NULL, 0, NULL, NULL, 0, 0, 0, 0, 1, NULL, NULL, NULL, NULL, NULL),
('Opportunitiesstic_justification_date_c', 'stic_justification_date_c', NULL, NULL, NULL, 'Opportunities', NULL, NULL, 0, NULL, NULL, 0, 0, 0, 0, 1, NULL, NULL, NULL, NULL, NULL),
('Opportunitiesstic_payment_date_c', 'stic_payment_date_c', NULL, NULL, NULL, 'Opportunities', NULL, NULL, 0, NULL, NULL, 0, 0, 0, 0, 1, NULL, NULL, NULL, NULL, NULL),
('Opportunitiesstic_presentation_date_c', 'stic_presentation_date_c', NULL, NULL, NULL, 'Opportunities', NULL, NULL, 0, NULL, NULL, 0, 0, 0, 0, 1, NULL, NULL, NULL, NULL, NULL),
('Opportunitiesstic_resolution_date_c', 'stic_resolution_date_c', NULL, NULL, NULL, 'Opportunities', NULL, NULL, 0, NULL, NULL, 0, 0, 0, 0, 1, NULL, NULL, NULL, NULL, NULL),
('Opportunitiesstic_status_c', 'stic_status_c', NULL, NULL, NULL, 'Opportunities', NULL, NULL, 0, NULL, NULL, 0, 0, 0, 0, 1, NULL, NULL, NULL, NULL, NULL),
('Opportunitiesstic_target_c', 'stic_target_c', NULL, NULL, NULL, 'Opportunities', NULL, NULL, 0, NULL, NULL, 0, 0, 0, 0, 1, NULL, NULL, NULL, NULL, NULL),
('Opportunitiesstic_type_c', 'stic_type_c', NULL, NULL, NULL, 'Opportunities', NULL, NULL, 0, NULL, NULL, 0, 0, 0, 0, 1, NULL, NULL, NULL, NULL, NULL),
('Projectjjwg_maps_address_c', 'jjwg_maps_address_c', 'LBL_JJWG_MAPS_ADDRESS', 'Address', 'Address', 'Project', 'varchar', 255, 0, NULL, '2020-07-04 08:15:43', 0, 0, 0, 0, 1, 'true', NULL, '', '', ''),
('Projectjjwg_maps_geocode_status_c', 'jjwg_maps_geocode_status_c', 'LBL_JJWG_MAPS_GEOCODE_STATUS', 'Geocode Status', 'Geocode Status', 'Project', 'varchar', 255, 0, NULL, '2020-07-04 08:15:43', 0, 0, 0, 0, 1, 'true', NULL, '', '', ''),
('Projectjjwg_maps_lat_c', 'jjwg_maps_lat_c', 'LBL_JJWG_MAPS_LAT', '', 'Latitude', 'Project', 'float', 10, 0, '0.00000000', '2020-07-04 08:15:43', 0, 0, 0, 0, 1, 'true', '8', '', '', ''),
('Projectjjwg_maps_lng_c', 'jjwg_maps_lng_c', 'LBL_JJWG_MAPS_LNG', '', 'Longitude', 'Project', 'float', 11, 0, '0.00000000', '2020-07-04 08:15:43', 0, 0, 0, 0, 1, 'true', '8', '', '', ''),
('Projectstic_location_c', 'stic_location_c', NULL, NULL, NULL, 'Project', NULL, NULL, 0, NULL, NULL, 0, 0, 0, 0, 1, NULL, NULL, NULL, NULL, NULL),
('Prospectsjjwg_maps_address_c', 'jjwg_maps_address_c', 'LBL_JJWG_MAPS_ADDRESS', 'Address', 'Address', 'Prospects', 'varchar', 255, 0, NULL, '2020-07-04 08:15:43', 0, 0, 0, 0, 1, 'true', NULL, '', '', ''),
('Prospectsjjwg_maps_geocode_status_c', 'jjwg_maps_geocode_status_c', 'LBL_JJWG_MAPS_GEOCODE_STATUS', 'Geocode Status', 'Geocode Status', 'Prospects', 'varchar', 255, 0, NULL, '2020-07-04 08:15:43', 0, 0, 0, 0, 1, 'true', NULL, '', '', ''),
('Prospectsjjwg_maps_lat_c', 'jjwg_maps_lat_c', 'LBL_JJWG_MAPS_LAT', '', 'Latitude', 'Prospects', 'float', 10, 0, '0.00000000', '2020-07-04 08:15:43', 0, 0, 0, 0, 1, 'true', '8', '', '', ''),
('Prospectsjjwg_maps_lng_c', 'jjwg_maps_lng_c', 'LBL_JJWG_MAPS_LNG', '', 'Longitude', 'Prospects', 'float', 11, 0, '0.00000000', '2020-07-04 08:15:43', 0, 0, 0, 0, 1, 'true', '8', '', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `folders`
--

CREATE TABLE IF NOT EXISTS `folders` (
  `id` char(36) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `folder_type` varchar(25) DEFAULT NULL,
  `parent_folder` char(36) DEFAULT NULL,
  `has_child` tinyint(1) DEFAULT '0',
  `is_group` tinyint(1) DEFAULT '0',
  `is_dynamic` tinyint(1) DEFAULT '0',
  `dynamic_query` text,
  `assign_to_id` char(36) DEFAULT NULL,
  `created_by` char(36) NOT NULL,
  `modified_by` char(36) NOT NULL,
  `deleted` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `folders_rel`
--

CREATE TABLE IF NOT EXISTS `folders_rel` (
  `id` char(36) NOT NULL,
  `folder_id` char(36) NOT NULL,
  `polymorphic_module` varchar(25) DEFAULT NULL,
  `polymorphic_id` char(36) NOT NULL,
  `deleted` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `folders_subscriptions`
--

CREATE TABLE IF NOT EXISTS `folders_subscriptions` (
  `id` char(36) NOT NULL,
  `folder_id` char(36) NOT NULL,
  `assigned_user_id` char(36) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `fp_events`
--

CREATE TABLE IF NOT EXISTS `fp_events` (
  `id` char(36) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `date_entered` datetime DEFAULT NULL,
  `date_modified` datetime DEFAULT NULL,
  `modified_user_id` char(36) DEFAULT NULL,
  `created_by` char(36) DEFAULT NULL,
  `description` text,
  `deleted` tinyint(1) DEFAULT '0',
  `assigned_user_id` char(36) DEFAULT NULL,
  `duration_hours` int(3) DEFAULT NULL,
  `duration_minutes` int(2) DEFAULT NULL,
  `date_start` datetime DEFAULT NULL,
  `date_end` datetime DEFAULT NULL,
  `budget` decimal(26,6) DEFAULT NULL,
  `currency_id` char(36) DEFAULT NULL,
  `invite_templates` varchar(100) DEFAULT NULL,
  `accept_redirect` varchar(255) DEFAULT NULL,
  `decline_redirect` varchar(255) DEFAULT NULL,
  `activity_status_type` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `fp_events_audit`
--

CREATE TABLE IF NOT EXISTS `fp_events_audit` (
  `id` char(36) NOT NULL,
  `parent_id` char(36) NOT NULL,
  `date_created` datetime DEFAULT NULL,
  `created_by` varchar(36) DEFAULT NULL,
  `field_name` varchar(100) DEFAULT NULL,
  `data_type` varchar(100) DEFAULT NULL,
  `before_value_string` varchar(255) DEFAULT NULL,
  `after_value_string` varchar(255) DEFAULT NULL,
  `before_value_text` text,
  `after_value_text` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `fp_events_contacts_c`
--

CREATE TABLE IF NOT EXISTS `fp_events_contacts_c` (
  `id` varchar(36) NOT NULL,
  `date_modified` datetime DEFAULT NULL,
  `deleted` tinyint(1) DEFAULT '0',
  `fp_events_contactsfp_events_ida` varchar(36) DEFAULT NULL,
  `fp_events_contactscontacts_idb` varchar(36) DEFAULT NULL,
  `invite_status` varchar(25) DEFAULT 'Not Invited',
  `accept_status` varchar(25) DEFAULT 'No Response',
  `email_responded` int(2) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `fp_events_fp_event_delegates_1_c`
--

CREATE TABLE IF NOT EXISTS `fp_events_fp_event_delegates_1_c` (
  `id` varchar(36) NOT NULL,
  `date_modified` datetime DEFAULT NULL,
  `deleted` tinyint(1) DEFAULT '0',
  `fp_events_fp_event_delegates_1fp_events_ida` varchar(36) DEFAULT NULL,
  `fp_events_fp_event_delegates_1fp_event_delegates_idb` varchar(36) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `fp_events_fp_event_locations_1_c`
--

CREATE TABLE IF NOT EXISTS `fp_events_fp_event_locations_1_c` (
  `id` varchar(36) NOT NULL,
  `date_modified` datetime DEFAULT NULL,
  `deleted` tinyint(1) DEFAULT '0',
  `fp_events_fp_event_locations_1fp_events_ida` varchar(36) DEFAULT NULL,
  `fp_events_fp_event_locations_1fp_event_locations_idb` varchar(36) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `fp_events_leads_1_c`
--

CREATE TABLE IF NOT EXISTS `fp_events_leads_1_c` (
  `id` varchar(36) NOT NULL,
  `date_modified` datetime DEFAULT NULL,
  `deleted` tinyint(1) DEFAULT '0',
  `fp_events_leads_1fp_events_ida` varchar(36) DEFAULT NULL,
  `fp_events_leads_1leads_idb` varchar(36) DEFAULT NULL,
  `invite_status` varchar(25) DEFAULT 'Not Invited',
  `accept_status` varchar(25) DEFAULT 'No Response',
  `email_responded` int(2) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `fp_events_prospects_1_c`
--

CREATE TABLE IF NOT EXISTS `fp_events_prospects_1_c` (
  `id` varchar(36) NOT NULL,
  `date_modified` datetime DEFAULT NULL,
  `deleted` tinyint(1) DEFAULT '0',
  `fp_events_prospects_1fp_events_ida` varchar(36) DEFAULT NULL,
  `fp_events_prospects_1prospects_idb` varchar(36) DEFAULT NULL,
  `invite_status` varchar(25) DEFAULT 'Not Invited',
  `accept_status` varchar(25) DEFAULT 'No Response',
  `email_responded` int(2) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `fp_event_locations`
--

CREATE TABLE IF NOT EXISTS `fp_event_locations` (
  `id` char(36) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `date_entered` datetime DEFAULT NULL,
  `date_modified` datetime DEFAULT NULL,
  `modified_user_id` char(36) DEFAULT NULL,
  `created_by` char(36) DEFAULT NULL,
  `description` text,
  `deleted` tinyint(1) DEFAULT '0',
  `assigned_user_id` char(36) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `address_city` varchar(100) DEFAULT NULL,
  `address_country` varchar(100) DEFAULT NULL,
  `address_postalcode` varchar(20) DEFAULT NULL,
  `address_state` varchar(100) DEFAULT NULL,
  `capacity` int(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `fp_event_locations_audit`
--

CREATE TABLE IF NOT EXISTS `fp_event_locations_audit` (
  `id` char(36) NOT NULL,
  `parent_id` char(36) NOT NULL,
  `date_created` datetime DEFAULT NULL,
  `created_by` varchar(36) DEFAULT NULL,
  `field_name` varchar(100) DEFAULT NULL,
  `data_type` varchar(100) DEFAULT NULL,
  `before_value_string` varchar(255) DEFAULT NULL,
  `after_value_string` varchar(255) DEFAULT NULL,
  `before_value_text` text,
  `after_value_text` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `fp_event_locations_cstm`
--

CREATE TABLE IF NOT EXISTS `fp_event_locations_cstm` (
  `id_c` char(36) NOT NULL,
  `stic_address_county_c` varchar(100) DEFAULT NULL,
  `stic_address_region_c` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `fp_event_locations_fp_events_1_c`
--

CREATE TABLE IF NOT EXISTS `fp_event_locations_fp_events_1_c` (
  `id` varchar(36) NOT NULL,
  `date_modified` datetime DEFAULT NULL,
  `deleted` tinyint(1) DEFAULT '0',
  `fp_event_locations_fp_events_1fp_event_locations_ida` varchar(36) DEFAULT NULL,
  `fp_event_locations_fp_events_1fp_events_idb` varchar(36) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `import_maps`
--

CREATE TABLE IF NOT EXISTS `import_maps` (
  `id` char(36) NOT NULL,
  `name` varchar(254) DEFAULT NULL,
  `source` varchar(36) DEFAULT NULL,
  `enclosure` varchar(1) DEFAULT ' ',
  `delimiter` varchar(1) DEFAULT ',',
  `module` varchar(36) DEFAULT NULL,
  `content` text,
  `default_values` text,
  `has_header` tinyint(1) DEFAULT '1',
  `deleted` tinyint(1) DEFAULT NULL,
  `date_entered` datetime DEFAULT NULL,
  `date_modified` datetime DEFAULT NULL,
  `assigned_user_id` char(36) DEFAULT NULL,
  `is_published` varchar(3) DEFAULT 'no'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `inbound_email`
--

CREATE TABLE IF NOT EXISTS `inbound_email` (
  `id` varchar(36) NOT NULL,
  `deleted` tinyint(1) DEFAULT '0',
  `date_entered` datetime DEFAULT NULL,
  `date_modified` datetime DEFAULT NULL,
  `modified_user_id` char(36) DEFAULT NULL,
  `created_by` char(36) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `status` varchar(100) DEFAULT 'Active',
  `server_url` varchar(100) DEFAULT NULL,
  `email_user` varchar(100) DEFAULT NULL,
  `email_password` varchar(100) DEFAULT NULL,
  `port` int(5) DEFAULT NULL,
  `service` varchar(50) DEFAULT NULL,
  `mailbox` text,
  `delete_seen` tinyint(1) DEFAULT '0',
  `mailbox_type` varchar(10) DEFAULT NULL,
  `template_id` char(36) DEFAULT NULL,
  `stored_options` text,
  `group_id` char(36) DEFAULT NULL,
  `is_personal` tinyint(1) DEFAULT '0',
  `groupfolder_id` char(36) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `inbound_email_autoreply`
--

CREATE TABLE IF NOT EXISTS `inbound_email_autoreply` (
  `id` char(36) NOT NULL,
  `deleted` tinyint(1) DEFAULT '0',
  `date_entered` datetime DEFAULT NULL,
  `date_modified` datetime DEFAULT NULL,
  `autoreplied_to` varchar(100) DEFAULT NULL,
  `ie_id` char(36) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `inbound_email_cache_ts`
--

CREATE TABLE IF NOT EXISTS `inbound_email_cache_ts` (
  `id` varchar(255) NOT NULL,
  `ie_timestamp` int(10) unsigned DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `jjwg_address_cache`
--

CREATE TABLE IF NOT EXISTS `jjwg_address_cache` (
  `id` char(36) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `date_entered` datetime DEFAULT NULL,
  `date_modified` datetime DEFAULT NULL,
  `modified_user_id` char(36) DEFAULT NULL,
  `created_by` char(36) DEFAULT NULL,
  `description` text,
  `deleted` tinyint(1) DEFAULT '0',
  `assigned_user_id` char(36) DEFAULT NULL,
  `lat` float(10,8) DEFAULT NULL,
  `lng` float(11,8) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `jjwg_address_cache_audit`
--

CREATE TABLE IF NOT EXISTS `jjwg_address_cache_audit` (
  `id` char(36) NOT NULL,
  `parent_id` char(36) NOT NULL,
  `date_created` datetime DEFAULT NULL,
  `created_by` varchar(36) DEFAULT NULL,
  `field_name` varchar(100) DEFAULT NULL,
  `data_type` varchar(100) DEFAULT NULL,
  `before_value_string` varchar(255) DEFAULT NULL,
  `after_value_string` varchar(255) DEFAULT NULL,
  `before_value_text` text,
  `after_value_text` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `jjwg_areas`
--

CREATE TABLE IF NOT EXISTS `jjwg_areas` (
  `id` char(36) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `date_entered` datetime DEFAULT NULL,
  `date_modified` datetime DEFAULT NULL,
  `modified_user_id` char(36) DEFAULT NULL,
  `created_by` char(36) DEFAULT NULL,
  `description` text,
  `deleted` tinyint(1) DEFAULT '0',
  `assigned_user_id` char(36) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `state` varchar(255) DEFAULT NULL,
  `country` varchar(255) DEFAULT NULL,
  `coordinates` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `jjwg_areas_audit`
--

CREATE TABLE IF NOT EXISTS `jjwg_areas_audit` (
  `id` char(36) NOT NULL,
  `parent_id` char(36) NOT NULL,
  `date_created` datetime DEFAULT NULL,
  `created_by` varchar(36) DEFAULT NULL,
  `field_name` varchar(100) DEFAULT NULL,
  `data_type` varchar(100) DEFAULT NULL,
  `before_value_string` varchar(255) DEFAULT NULL,
  `after_value_string` varchar(255) DEFAULT NULL,
  `before_value_text` text,
  `after_value_text` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `jjwg_maps`
--

CREATE TABLE IF NOT EXISTS `jjwg_maps` (
  `id` char(36) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `date_entered` datetime DEFAULT NULL,
  `date_modified` datetime DEFAULT NULL,
  `modified_user_id` char(36) DEFAULT NULL,
  `created_by` char(36) DEFAULT NULL,
  `description` text,
  `deleted` tinyint(1) DEFAULT '0',
  `assigned_user_id` char(36) DEFAULT NULL,
  `distance` float(9,4) DEFAULT NULL,
  `unit_type` varchar(100) DEFAULT 'mi',
  `module_type` varchar(100) DEFAULT 'Accounts',
  `parent_type` varchar(255) DEFAULT NULL,
  `parent_id` char(36) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `jjwg_maps_audit`
--

CREATE TABLE IF NOT EXISTS `jjwg_maps_audit` (
  `id` char(36) NOT NULL,
  `parent_id` char(36) NOT NULL,
  `date_created` datetime DEFAULT NULL,
  `created_by` varchar(36) DEFAULT NULL,
  `field_name` varchar(100) DEFAULT NULL,
  `data_type` varchar(100) DEFAULT NULL,
  `before_value_string` varchar(255) DEFAULT NULL,
  `after_value_string` varchar(255) DEFAULT NULL,
  `before_value_text` text,
  `after_value_text` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `jjwg_maps_jjwg_areas_c`
--

CREATE TABLE IF NOT EXISTS `jjwg_maps_jjwg_areas_c` (
  `id` varchar(36) NOT NULL,
  `date_modified` datetime DEFAULT NULL,
  `deleted` tinyint(1) DEFAULT '0',
  `jjwg_maps_5304wg_maps_ida` varchar(36) DEFAULT NULL,
  `jjwg_maps_41f2g_areas_idb` varchar(36) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `jjwg_maps_jjwg_markers_c`
--

CREATE TABLE IF NOT EXISTS `jjwg_maps_jjwg_markers_c` (
  `id` varchar(36) NOT NULL,
  `date_modified` datetime DEFAULT NULL,
  `deleted` tinyint(1) DEFAULT '0',
  `jjwg_maps_b229wg_maps_ida` varchar(36) DEFAULT NULL,
  `jjwg_maps_2e31markers_idb` varchar(36) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `jjwg_markers`
--

CREATE TABLE IF NOT EXISTS `jjwg_markers` (
  `id` char(36) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `date_entered` datetime DEFAULT NULL,
  `date_modified` datetime DEFAULT NULL,
  `modified_user_id` char(36) DEFAULT NULL,
  `created_by` char(36) DEFAULT NULL,
  `description` text,
  `deleted` tinyint(1) DEFAULT '0',
  `assigned_user_id` char(36) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `state` varchar(255) DEFAULT NULL,
  `country` varchar(255) DEFAULT NULL,
  `jjwg_maps_lat` float(10,8) DEFAULT '0.00000000',
  `jjwg_maps_lng` float(11,8) DEFAULT '0.00000000',
  `marker_image` varchar(100) DEFAULT 'company'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `jjwg_markers_audit`
--

CREATE TABLE IF NOT EXISTS `jjwg_markers_audit` (
  `id` char(36) NOT NULL,
  `parent_id` char(36) NOT NULL,
  `date_created` datetime DEFAULT NULL,
  `created_by` varchar(36) DEFAULT NULL,
  `field_name` varchar(100) DEFAULT NULL,
  `data_type` varchar(100) DEFAULT NULL,
  `before_value_string` varchar(255) DEFAULT NULL,
  `after_value_string` varchar(255) DEFAULT NULL,
  `before_value_text` text,
  `after_value_text` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `job_queue`
--

CREATE TABLE IF NOT EXISTS `job_queue` (
  `assigned_user_id` char(36) DEFAULT NULL,
  `id` char(36) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `deleted` tinyint(1) DEFAULT '0',
  `date_entered` datetime DEFAULT NULL,
  `date_modified` datetime DEFAULT NULL,
  `scheduler_id` char(36) DEFAULT NULL,
  `execute_time` datetime DEFAULT NULL,
  `status` varchar(20) DEFAULT NULL,
  `resolution` varchar(20) DEFAULT NULL,
  `message` text,
  `target` varchar(255) DEFAULT NULL,
  `data` text,
  `requeue` tinyint(1) DEFAULT '0',
  `retry_count` tinyint(4) DEFAULT NULL,
  `failure_count` tinyint(4) DEFAULT NULL,
  `job_delay` int(11) DEFAULT NULL,
  `client` varchar(255) DEFAULT NULL,
  `percent_complete` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `kreports`
--

CREATE TABLE IF NOT EXISTS `kreports` (
  `id` char(36) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `date_entered` datetime DEFAULT NULL,
  `date_modified` datetime DEFAULT NULL,
  `modified_user_id` char(36) DEFAULT NULL,
  `created_by` char(36) DEFAULT NULL,
  `description` text,
  `deleted` tinyint(1) DEFAULT '0',
  `assigned_user_id` char(36) DEFAULT NULL,
  `report_module` varchar(45) DEFAULT NULL,
  `report_status` varchar(1) DEFAULT NULL,
  `union_modules` text,
  `reportoptions` text,
  `listtype` varchar(10) DEFAULT NULL,
  `listtypeproperties` text,
  `selectionlimit` varchar(25) DEFAULT NULL,
  `presentation_params` text,
  `visualization_params` text,
  `integration_params` text,
  `wheregroups` text,
  `whereconditions` text,
  `listfields` text,
  `unionlistfields` text,
  `advancedoptions` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `kreports`
--

INSERT INTO `kreports` (`id`, `name`, `date_entered`, `date_modified`, `modified_user_id`, `created_by`, `description`, `deleted`, `assigned_user_id`, `report_module`, `report_status`, `union_modules`, `reportoptions`, `listtype`, `listtypeproperties`, `selectionlimit`, `presentation_params`, `visualization_params`, `integration_params`, `wheregroups`, `whereconditions`, `listfields`, `unionlistfields`, `advancedoptions`) VALUES
('162a5ee7-e61f-838a-c3a3-5d8087aeedc7', 'Exemple - Contacts - 01 - Contacts basic data', '2020-07-04 10:16:15', '2020-07-04 10:16:15', '1', '1', NULL, 0, '1', 'Contacts', '3', NULL, '{"resultsFolded":false,"optionsFolded":true,"authCheck":"full","showDeleted":false,"showExport":true,"showSnapshots":false,"showTools":true}', NULL, NULL, NULL, '{"plugin":"standard","pluginData":{}}', '{"1":{"googlecharts":{"uid":"k42ebd6325aea43f877a0c7d442b7","dims":"111","type":"Bar","dimensions":{"dimension1":"k6e261a5c9a3e199fc18bab87d227"},"dataseries":[{"fieldid":"k6e261a5c9a3e199fc18bab87d227","name":"Rango edad","chartfunction":"SUM","meaning":"value","axis":"P","renderer":"bars"}],"options":{"legend":true,"is3D":"on"},"title":"Distribuci\\u00f3n por edad"},"plugin":"googlecharts"},"2":{"plugin":"googlecharts","googlecharts":{"uid":"k59aa7bc41bc97cfa305cf24da426","dims":"111","type":"Pie","dimensions":{"dimension1":"keb37c5a0ef33ab0f833321be3ced"},"dataseries":[{"fieldid":"keb37c5a0ef33ab0f833321be3ced","name":"Tipo de relaci\\u00f3n (actual)","chartfunction":"SUM","meaning":"value","axis":"P","renderer":"bars"}],"options":{"legend":true,"is3D":"on"},"title":"Relaci\\u00f3n con la organizaci\\u00f3n"}},"3":{"plugin":"googlecharts","googlecharts":{"uid":"k7e90bd3de2421f1856801e43ce5f","dims":"111","type":"Pie","dimensions":{"dimension1":"kbbeb1d66fff3631785b918b8e4a3"},"dataseries":[{"fieldid":"kbbeb1d66fff3631785b918b8e4a3","name":"Idioma","chartfunction":"SUM","meaning":"value","axis":"P","renderer":"bars"}],"options":{"legend":true,"is3D":"on"},"title":"Idioma preferente"}},"4":{"plugin":"googlecharts","googlecharts":{"uid":"k157995a73f9ae269065229975aa1","dims":"111","type":"Bar","dimensions":{"dimension1":"k021117b74b79740661be5724ae94"},"dataseries":[{"fieldid":"k021117b74b79740661be5724ae94","name":"G\\u00e9nero","chartfunction":"SUM","meaning":"value","axis":"P","renderer":"bars"}],"options":{"legend":true,"is3D":"on"},"title":"G\\u00e9nero"}},"plugin":"googlecharts","layout":"1x4","chartheight":300,"height":"350"}', '{"activePlugins":{"kcsvexport":"1","ktargetlistexport":"0","ksnapshots":"0"}}', '[{"unionid":"root","id":"root","group":"root","type":"AND","parent":"-","notexists":""}]', '[{"unionid":"root","sequence":"","fieldid":"k309f3522772cefed21b178393fa6","name":"Edad","fixedvalue":"","groupid":"root","path":"root:Contacts::field:stic_age_c","displaypath":"Contacts","referencefieldid":"","operator":"ignore","type":"int","value":"","valuekey":"","valueto":"","valuetokey":"","jointype":"required","context":"","reference":"","include":"","usereditable":"yes","dashleteditable":"no","exportpdf":"no","customsqlfunction":""},{"unionid":"root","sequence":"","fieldid":"k902613b1cce32b0f20cbccab20f7","name":"Tipo de relaci\\u00f3n (actual)","fixedvalue":"","groupid":"root","path":"root:Contacts::field:stic_relationship_type_c","displaypath":"Contacts","referencefieldid":"","operator":"ignore","type":"multienum","value":"","valuekey":"","valueto":"","valuetokey":"","jointype":"required","context":"","reference":"","include":"","usereditable":"yes","dashleteditable":"no","exportpdf":"no","customsqlfunction":""},{"unionid":"root","sequence":"","fieldid":"kd6a9b73d59aa112ca7d8de0c50a0","name":"G\\u00e9nero","fixedvalue":"","groupid":"root","path":"root:Contacts::field:stic_gender_c","displaypath":"Contacts","referencefieldid":"","operator":"ignore","type":"enum","value":"","valuekey":"","valueto":"","valuetokey":"","jointype":"required","context":"","reference":"","include":"","usereditable":"yes","dashleteditable":"no","exportpdf":"no","customsqlfunction":""}]', '[{"fieldid":"kfc7bd511f723cab4ed14a193fab2","sequence":"01","fieldname":"Nombre:","name":"Nombre","display":"yes","path":"root:Contacts::field:first_name","displaypath":"Contacts","sort":"sortable","sortpriority":"","width":"100","jointype":"required","sqlfunction":"-","summaryfunction":"","customsqlfunction":"","valuetype":"","groupby":"no","link":"no","editable":"","fixedvalue":"","assigntovalue":"","formulavalue":"","formulasequence":"","widget":"","overridetype":"","overridealignment":""},{"fieldid":"keda9ca31fb338579be38862527c1","sequence":"02","fieldname":"Apellidos:","name":"Apellidos","display":"yes","path":"root:Contacts::field:last_name","displaypath":"Contacts","sort":"asc","sortpriority":"","width":150,"jointype":"required","sqlfunction":"-","summaryfunction":"","customsqlfunction":"","valuetype":"","groupby":"no","link":"no","editable":"","fixedvalue":"","assigntovalue":"","formulavalue":"","formulasequence":"","widget":"","overridetype":"","overridealignment":""},{"fieldid":"k71bafb44d9ee79b795dd1335574b","sequence":"03","fieldname":"Edad","name":"Edad","display":"yes","path":"root:Contacts::field:stic_age_c","displaypath":"Contacts","sort":"sortable","sortpriority":"","width":"100","jointype":"required","sqlfunction":"-","summaryfunction":"","customsqlfunction":"","valuetype":"","groupby":"no","link":"no","editable":"","fixedvalue":"","assigntovalue":"","formulavalue":"","formulasequence":"","widget":"","overridetype":"","overridealignment":""},{"fieldid":"k021117b74b79740661be5724ae94","sequence":"04","fieldname":"G\\u00e9nero","name":"G\\u00e9nero","display":"yes","path":"root:Contacts::field:stic_gender_c","displaypath":"Contacts","sort":"sortable","sortpriority":"","width":"100","jointype":"required","sqlfunction":"-","summaryfunction":"","customsqlfunction":"","valuetype":"","groupby":"no","link":"no","editable":"","fixedvalue":"","assigntovalue":"","formulavalue":"","formulasequence":"","widget":"","overridetype":"","overridealignment":""},{"fieldid":"kbbeb1d66fff3631785b918b8e4a3","sequence":"05","fieldname":"Idioma","name":"Idioma","display":"yes","path":"root:Contacts::field:stic_language_c","displaypath":"Contacts","sort":"sortable","sortpriority":"","width":"100","jointype":"required","sqlfunction":"-","summaryfunction":"","customsqlfunction":"","valuetype":"","groupby":"no","link":"no","editable":"","fixedvalue":"","assigntovalue":"","formulavalue":"","formulasequence":"","widget":"","overridetype":"","overridealignment":""},{"fieldid":"k5a6f7473a78938db4710b630fd66","sequence":"06","fieldname":"Tipo de identificaci\\u00f3n","name":"Tipo de identificaci\\u00f3n","display":"yes","path":"root:Contacts::field:stic_identification_type_c","displaypath":"Contacts","sort":"sortable","sortpriority":"","width":"100","jointype":"required","sqlfunction":"-","summaryfunction":"","customsqlfunction":"","valuetype":"","groupby":"no","link":"no","editable":"","fixedvalue":"","assigntovalue":"","formulavalue":"","formulasequence":"","widget":"","overridetype":"","overridealignment":""},{"fieldid":"k25d06e010a4ad2bfb9b27c140685","sequence":"07","fieldname":"N\\u00famero de identificaci\\u00f3n","name":"N\\u00famero de identificaci\\u00f3n","display":"yes","path":"root:Contacts::field:stic_identification_number_c","displaypath":"Contacts","sort":"sortable","sortpriority":"","width":"100","jointype":"required","sqlfunction":"-","summaryfunction":"","customsqlfunction":"","valuetype":"","groupby":"no","link":"no","editable":"","fixedvalue":"","assigntovalue":"","formulavalue":"","formulasequence":"","widget":"","overridetype":"","overridealignment":""},{"fieldid":"kf0106961c03f5beeafc4d0b9e8d8","sequence":"08","fieldname":"Primer canal de captaci\\u00f3n","name":"Primer canal de captaci\\u00f3n","display":"yes","path":"root:Contacts::field:stic_acquisition_channel_c","displaypath":"Contacts","sort":"sortable","sortpriority":"","width":"100","jointype":"required","sqlfunction":"-","summaryfunction":"","customsqlfunction":"","valuetype":"","groupby":"no","link":"no","editable":"","fixedvalue":"","assigntovalue":"","formulavalue":"","formulasequence":"","widget":"","overridetype":"","overridealignment":""},{"fieldid":"kdf9374147a7fbbcc37e26adb7b2e","sequence":"09","fieldname":"Comunidad Aut\\u00f3noma de la direcci\\u00f3n principal","name":"Comunidad Aut\\u00f3noma","display":"yes","path":"root:Contacts::field:stic_primary_address_region_c","displaypath":"Contacts","sort":"sortable","sortpriority":"","width":"100","jointype":"required","sqlfunction":"-","summaryfunction":"","customsqlfunction":"","valuetype":"","groupby":"no","link":"no","editable":"","fixedvalue":"","assigntovalue":"","formulavalue":"","formulasequence":"","widget":"","overridetype":"","overridealignment":""},{"fieldid":"k958a7cf43a506c21154c6f9cbc51","sequence":10,"fieldname":"Estado/Provincia de direcci\\u00f3n principal:","name":"Estado/Provincia de direcci\\u00f3n principal:","display":"yes","path":"root:Contacts::field:primary_address_state","displaypath":"Contacts","sort":"sortable","sortpriority":"","width":"100","jointype":"required","sqlfunction":"-","summaryfunction":"","customsqlfunction":"","valuetype":"","groupby":"no","link":"no","editable":"","fixedvalue":"","assigntovalue":"","formulavalue":"","formulasequence":"","widget":"","overridetype":"","overridealignment":""},{"fieldid":"k6e261a5c9a3e199fc18bab87d227","sequence":11,"fieldname":"Edad","name":"Rango edad","display":"yes","path":"root:Contacts::field:stic_age_c","displaypath":"Contacts","sort":"sortable","sortpriority":"","width":"100","jointype":"required","sqlfunction":"-","summaryfunction":"","customsqlfunction":"","valuetype":"","groupby":"no","link":"no","editable":"","fixedvalue":"","assigntovalue":"edad","formulavalue":"JTI4JTdCZWRhZCU3RCUyMCUzRSUyMDAlMjAlMjYlMjYlMjAlN0JlZGFkJTdEJTIwJTNDJTIwMTglMjAlM0YlMjAlMjcxX0VudHJlJTIwMCUyMHklMjAxOCUyNyUzQSUwQSUyOCU3QmVkYWQlN0QlMjAlM0UlM0QlMjAxOCUyMCUyNiUyNiUyMCU3QmVkYWQlN0QlMjAlM0MlM0QlMjAyNSUyMCUzRiUyMCUyNzJfRW50cmUlMjAxOCUyMHklMjAyNSUyNyUzQSUwQSUyOCU3QmVkYWQlN0QlMjAlM0UlM0QlMjAyNiUyMCUyNiUyNiUyMCU3QmVkYWQlN0QlMjAlM0MlM0QlMjAzMCUyMCUzRiUyMCUyNzNfRW50cmUlMjAyNiUyMHklMjAzMCUyNyUzQSUwQSUyOCU3QmVkYWQlN0QlMjAlM0UlM0QlMjAzMSUyMCUyNiUyNiUyMCU3QmVkYWQlN0QlMjAlM0MlM0QlMjA0MCUyMCUzRiUyMCUyNzRfRW50cmUlMjAzMSUyMHklMjA0MCUyNyUzQSUwQSUyOCU3QmVkYWQlN0QlMjAlM0UlM0QlMjA0MSUyMCUyNiUyNiUyMCU3QmVkYWQlN0QlMjAlM0MlM0QlMjA1MCUyMCUzRiUyMCUyNzVfRW50cmUlMjA0MSUyMHklMjA1MCUyNyUzQSUwQSUyOCU3QmVkYWQlN0QlMjAlM0UlM0QlMjA1MSUyMCUyNiUyNiUyMCU3QmVkYWQlN0QlMjAlM0MlM0QlMjA2MCUyMCUzRiUyMCUyNzZfRW50cmUlMjA1MSUyMHklMjA2MCUyNyUzQSUwQSUyOCU3QmVkYWQlN0QlMjAlM0UlM0QlMjA2MSUyMCUyNiUyNiUyMCU3QmVkYWQlN0QlMjAlM0MlM0QlMjA3MCUyMCUzRiUyMCUyNzdfRW50cmUlMjA2MSUyMHklMjA3MCUyNyUzQSUwQSUyOCU3QmVkYWQlN0QlMjAlM0UlMjA3MSUyMCUyMCUzRiUyMCUyNzhfTWFzJTIwZGUlMjA3MCUyNyUzQSUwQSUyN05EJTI3JTBBJTI5JTI5JTI5JTI5JTI5JTI5JTI5JTI5","formulasequence":"","widget":"","overridetype":"","overridealignment":""},{"fieldid":"k5302b8db3f95b287668294922366","sequence":12,"fieldname":"Tel\\u00e9fono fijo","name":"Tel\\u00e9fono fijo","display":"yes","path":"root:Contacts::field:phone_home","displaypath":"Contacts","sort":"sortable","sortpriority":"","width":"100","jointype":"required","sqlfunction":"-","summaryfunction":"","customsqlfunction":"","valuetype":"","groupby":"no","link":"no","editable":"","fixedvalue":"","assigntovalue":"","formulavalue":"","formulasequence":"","widget":"","overridetype":"","overridealignment":""},{"fieldid":"k55a6820ee8d8088c7a223d0bdffb","sequence":13,"fieldname":"M\\u00f3vil:","name":"M\\u00f3vil:","display":"yes","path":"root:Contacts::field:phone_mobile","displaypath":"Contacts","sort":"sortable","sortpriority":"","width":"100","jointype":"required","sqlfunction":"-","summaryfunction":"","customsqlfunction":"","valuetype":"","groupby":"no","link":"no","editable":"","fixedvalue":"","assigntovalue":"","formulavalue":"","formulasequence":"","widget":"","overridetype":"","overridealignment":""},{"fieldid":"kc95d79f8bb65cc0b4d81c71010d2","sequence":14,"fieldname":"Direcci\\u00f3n de Email","name":"Direcci\\u00f3n de Email","display":"yes","path":"root:Contacts::link:Contacts:email_addresses_primary::field:email_address","displaypath":"Contacts::Direcci\\u00f3n de Email","sort":"sortable","sortpriority":"","width":150,"jointype":"optional","sqlfunction":"-","summaryfunction":"","customsqlfunction":"","valuetype":"","groupby":"no","link":"no","editable":"","fixedvalue":"","assigntovalue":"","formulavalue":"","formulasequence":"","widget":"","overridetype":"","overridealignment":""},{"fieldid":"keb37c5a0ef33ab0f833321be3ced","sequence":15,"fieldname":"Tipo de relaci\\u00f3n","name":"Tipo de relaci\\u00f3n (actual)","display":"yes","path":"root:Contacts::field:stic_relationship_type_c","displaypath":"Contacts","sort":"sortable","sortpriority":"","width":200,"jointype":"required","sqlfunction":"-","summaryfunction":"","customsqlfunction":"","valuetype":"","groupby":"no","link":"no","editable":"","fixedvalue":"","assigntovalue":"","formulavalue":"","formulasequence":"","widget":"","overridetype":"","overridealignment":""}]', NULL, NULL),
('227a0be7-9637-8598-2128-5d8c7045b206', 'Exemple - Events - 04 - Event attendances tracking', '2020-07-04 10:16:15', '2020-07-04 10:16:15', '2', '1', NULL, 0, '1', 'stic_Attendances', NULL, NULL, '{"resultsFolded":false,"optionsFolded":true,"authCheck":"full","showDeleted":false,"showExport":true,"showSnapshots":false,"showTools":true}', NULL, NULL, NULL, '{"plugin":"standard","pluginData":{}}', '{"1":{"googlecharts":{"uid":"k2287c98ac3f6f752fb935d8c706a","dims":"111","type":"Pie","dimensions":{"dimension1":"k5ac4160fff51e3145c8299f27046"},"dataseries":[{"fieldid":"k5ac4160fff51e3145c8299f27046","name":"Estado","chartfunction":"SUM","meaning":"value","axis":"P","renderer":"bars"}],"options":{"legend":true,"is3D":"on"},"title":"Inscripciones por status"},"plugin":"googlecharts"},"2":{"plugin":"googlecharts","googlecharts":{"uid":"k228a9aa2063fcb2e89505d8c70af","dims":"111","type":"Pie","dimensions":{"dimension1":"k3cf5d003072c2a0e0ce5b1aff7e5"},"dataseries":[{"fieldid":"k3cf5d003072c2a0e0ce5b1aff7e5","name":"Nombre evento","chartfunction":"SUM","meaning":"value","axis":"P","renderer":"bars"}],"options":{"legend":true,"is3D":"on"},"title":"Inscripciones por evento"}},"3":{"plugin":"googlecharts","googlecharts":{"uid":"k17ab444c5d52f72af10b3d591d8c","dims":"111","type":"Bar","dimensions":{"dimension1":null},"dataseries":[{"fieldid":"k3cf5d003072c2a0e0ce5b1aff7e5","name":"Nombre evento","chartfunction":"SUM","meaning":"value","axis":"P","renderer":"bars"}],"options":{"is3D":"on"},"title":"TOTAL INSCRIPCIONES"}},"4":{"plugin":"googlecharts","googlecharts":{"uid":"ka145e81665f047f499f99a49e4c1","dims":"111","type":"Bar","dimensions":{"dimension1":null},"dataseries":[{"fieldid":"kf959e98caa5a7bbbe4702d135ec6","name":"N\\u00ba asist. totales","chartfunction":"SUM","meaning":"value","axis":"P","renderer":"bars"}],"options":{"is3D":"on"},"title":"TOTAL ASISTENCIAS"}},"plugin":"googlecharts","layout":"1x4","chartheight":300,"height":"300"}', '{"activePlugins":{"kcsvexport":"1","ktargetlistexport":"0","ksnapshots":"0"}}', '[{"unionid":"root","id":"root","group":"root","type":"AND","parent":"-","notexists":""}]', '[{"unionid":"root","sequence":"","fieldid":"ke02ab78bdc47ffb5f5c838ca91e0","name":"Fecha Asistencia","fixedvalue":"","groupid":"root","path":"root:stic_Attendances::field:start_date","displaypath":"stic_Attendances","referencefieldid":"","operator":"ignore","type":"datetimecombo","value":"","valuekey":"","valueto":"","valuetokey":"","jointype":"required","context":"","reference":"","include":"","usereditable":"yes","dashleteditable":"no","exportpdf":"no","customsqlfunction":""},{"unionid":"root","sequence":"","fieldid":"k76fd2895bcd9037b782f70ac3b07","name":"Nombre evento","fixedvalue":"","groupid":"root","path":"root:stic_Attendances::link:stic_Attendances:stic_attendances_stic_sessions::link:stic_Sessions:stic_sessions_stic_events::field:name","displaypath":"stic_Attendances::Sesi\\u00f3n::Evento","referencefieldid":"","operator":"ignore","type":"name","value":"","valuekey":"","valueto":"","valuetokey":"","jointype":"required","context":"","reference":"","include":"","usereditable":"yes","dashleteditable":"no","exportpdf":"no","customsqlfunction":""},{"unionid":"root","sequence":"","fieldid":"kb5aec92b589ef393770803bfaa17","name":"Estado inscripci\\u00f3n","fixedvalue":"","groupid":"root","path":"root:stic_Attendances::link:stic_Attendances:stic_attendances_stic_registrations::field:status","displaypath":"stic_Attendances::Inscripci\\u00f3n","referencefieldid":"","operator":"ignore","type":"enum","value":"","valuekey":"","valueto":"","valuetokey":"","jointype":"required","context":"","reference":"","include":"","usereditable":"yes","dashleteditable":"no","exportpdf":"no","customsqlfunction":""}]', '[{"fieldid":"k20563239eabbd260a9caf972fe02","sequence":"01","fieldname":"Apellidos:","name":"Apellidos:","display":"yes","path":"root:stic_Attendances::link:stic_Attendances:stic_attendances_stic_registrations::link:stic_Registrations:stic_registrations_contacts::field:last_name","displaypath":"stic_Attendances::Inscripci\\u00f3n::Persona","sort":"sortable","sortpriority":"","width":200,"jointype":"required","sqlfunction":"-","summaryfunction":"","customsqlfunction":"","valuetype":"","groupby":"yes","link":"yes","editable":"","fixedvalue":"","assigntovalue":"","formulavalue":"","formulasequence":"","widget":"","overridetype":"","overridealignment":""},{"fieldid":"kf463e46d8b6e0ed9a42e381dd183","sequence":"02","fieldname":"Nombre:","name":"Nombre:","display":"yes","path":"root:stic_Attendances::link:stic_Attendances:stic_attendances_stic_registrations::link:stic_Registrations:stic_registrations_contacts::field:first_name","displaypath":"stic_Attendances::Inscripci\\u00f3n::Persona","sort":"sortable","sortpriority":"","width":150,"jointype":"optional","sqlfunction":"-","summaryfunction":"","customsqlfunction":"","valuetype":"","groupby":"yes","link":"no","editable":"","fixedvalue":"","assigntovalue":"","formulavalue":"","formulasequence":"","widget":"","overridetype":"","overridealignment":""},{"fieldid":"k3cf5d003072c2a0e0ce5b1aff7e5","sequence":"03","fieldname":"Nombre","name":"Nombre evento","display":"yes","path":"root:stic_Attendances::link:stic_Attendances:stic_attendances_stic_sessions::link:stic_Sessions:stic_sessions_stic_events::field:name","displaypath":"stic_Attendances::Sesi\\u00f3n::Evento","sort":"sortable","sortpriority":"","width":200,"jointype":"optional","sqlfunction":"-","summaryfunction":"","customsqlfunction":"","valuetype":"","groupby":"yes","link":"yes","editable":"","fixedvalue":"","assigntovalue":"","formulavalue":"","formulasequence":"","widget":"","overridetype":null,"overridealignment":""},{"fieldid":"k5ac4160fff51e3145c8299f27046","sequence":"04","fieldname":"Estado","name":"Estado","display":"yes","path":"root:stic_Attendances::link:stic_Attendances:stic_attendances_stic_registrations::field:status","displaypath":"stic_Attendances::Inscripci\\u00f3n","sort":"sortable","sortpriority":"","width":"100","jointype":"required","sqlfunction":"-","summaryfunction":"","customsqlfunction":"","valuetype":"","groupby":"yes","link":"no","editable":"","fixedvalue":"","assigntovalue":"","formulavalue":"","formulasequence":"","widget":"","overridetype":"","overridealignment":""},{"fieldid":"kf959e98caa5a7bbbe4702d135ec6","sequence":"05","fieldname":"ID","name":"N\\u00ba asist. totales","display":"yes","path":"root:stic_Attendances::field:id","displaypath":"stic_Attendances","sort":"sortable","sortpriority":"","width":"100","jointype":"required","sqlfunction":"COUNT_DISTINCT","summaryfunction":"","customsqlfunction":"","valuetype":"","groupby":"no","link":"no","editable":"","fixedvalue":"","assigntovalue":"total","formulavalue":"","formulasequence":"","widget":"","overridetype":"","overridealignment":""},{"fieldid":"k6dc0c2373425fb42bcc53190f1fe","sequence":"06","fieldname":"Estado","name":"Blanco","display":"yes","path":"root:stic_Attendances::field:status","displaypath":"stic_Attendances","sort":"sortable","sortpriority":"","width":75,"jointype":"required","sqlfunction":"-","summaryfunction":"","customsqlfunction":"c3VtJTI4aWYlMjAlMjglN0J0JTdELnN0YXR1cyUyMGlzJTIwbnVsbCUyMG9yJTIwJTdCdCU3RC5zdGF0dXMlMjAlM0QlMjAlMjclMjclMkMlMjAxJTJDJTIwMCUyOSUyOQ==","valuetype":"","groupby":"no","link":"no","editable":"","fixedvalue":"","assigntovalue":"blanco","formulavalue":"","formulasequence":"","widget":"","overridetype":"-","overridealignment":""},{"fieldid":"kfd024a0a3ed7a20b02222cade4f5","sequence":"07","fieldname":"","name":"% Blanco","display":"yes","path":"","displaypath":"","sort":"-","sortpriority":"","width":75,"jointype":"required","sqlfunction":"-","summaryfunction":"","customsqlfunction":"","valuetype":"","groupby":"no","link":"no","editable":"","fixedvalue":"","assigntovalue":"","formulavalue":"JTI4JTdCYmxhbmNvJTdEJTIwLyUyMCU3QnRvdGFsJTdEJTI5KjEwMA==","formulasequence":"","widget":"","overridetype":"percentage","overridealignment":""},{"fieldid":"k04a6fb3b8dc047b2c8500af66495","sequence":"08","fieldname":"Estado","name":"S\\u00ed","display":"yes","path":"root:stic_Attendances::field:status","displaypath":"stic_Attendances","sort":"sortable","sortpriority":"","width":75,"jointype":"required","sqlfunction":"-","summaryfunction":"","customsqlfunction":"c3VtJTI4aWYlMjAlMjhzdHJjbXAlMjglN0J0JTdELnN0YXR1cyUyQyUyMCUyN3llcyUyNyUyOSUzRDAlMkMlMjAxJTJDJTIwMCUyOSUyOQ==","valuetype":"","groupby":"no","link":"no","editable":"","fixedvalue":"","assigntovalue":"si","formulavalue":"","formulasequence":"","widget":"","overridetype":"-","overridealignment":""},{"fieldid":"ke15adf8f1cd24658e9ab77815e8f","sequence":"09","fieldname":"","name":"% S\\u00ed","display":"yes","path":"","displaypath":"","sort":"-","sortpriority":"","width":75,"jointype":"required","sqlfunction":"-","summaryfunction":"","customsqlfunction":"","valuetype":"","groupby":"no","link":"no","editable":"","fixedvalue":"","assigntovalue":"","formulavalue":"JTI4JTdCc2klN0QlMjAvJTIwJTdCdG90YWwlN0QlMjkqMTAw","formulasequence":"","widget":"","overridetype":"percentage","overridealignment":""},{"fieldid":"k58ecbbb5806043c6164e7b026914","sequence":10,"fieldname":"Estado","name":"Parcial","display":"yes","path":"root:stic_Attendances::field:status","displaypath":"stic_Attendances","sort":"sortable","sortpriority":"","width":75,"jointype":"required","sqlfunction":"-","summaryfunction":"","customsqlfunction":"c3VtJTI4aWYlMjAlMjhzdHJjbXAlMjglN0J0JTdELnN0YXR1cyUyQyUyMCUyN3BhcnRpYWwlMjclMjklM0QwJTJDJTIwMSUyQyUyMDAlMjklMjk=","valuetype":"","groupby":"no","link":"no","editable":"","fixedvalue":"","assigntovalue":"parcial","formulavalue":"","formulasequence":"","widget":"","overridetype":"-","overridealignment":""},{"fieldid":"k1e84c6bc045548a51780f9a73394","sequence":11,"fieldname":"","name":"% Parcial","display":"yes","path":"","displaypath":"","sort":"-","sortpriority":"","width":75,"jointype":"required","sqlfunction":"-","summaryfunction":"","customsqlfunction":"","valuetype":"","groupby":"no","link":"no","editable":"","fixedvalue":"","assigntovalue":"","formulavalue":"JTI4JTdCcGFyY2lhbCU3RCUyMC8lMjAlN0J0b3RhbCU3RCUyOSoxMDA=","formulasequence":"","widget":"","overridetype":"percentage","overridealignment":""},{"fieldid":"k45310b13c7250be5bad2d6eccfc7","sequence":12,"fieldname":"Estado","name":"No, just.","display":"yes","path":"root:stic_Attendances::field:status","displaypath":"stic_Attendances","sort":"sortable","sortpriority":"","width":75,"jointype":"required","sqlfunction":"-","summaryfunction":"","customsqlfunction":"c3VtJTI4aWYlMjAlMjhzdHJjbXAlMjglN0J0JTdELnN0YXR1cyUyQyUyMCUyN25vX2p1c3RpZmllZGp1c3QlMjclMjklM0QwJTJDJTIwMSUyQyUyMDAlMjklMjk=","valuetype":"","groupby":"no","link":"no","editable":"","fixedvalue":"","assigntovalue":"justificadas","formulavalue":"","formulasequence":"","widget":"","overridetype":"-","overridealignment":""},{"fieldid":"kbef5588f4881e24947d9224d14f8","sequence":13,"fieldname":"","name":"% No, Just","display":"yes","path":"","displaypath":"","sort":"-","sortpriority":"","width":75,"jointype":"required","sqlfunction":"-","summaryfunction":"","customsqlfunction":"","valuetype":"","groupby":"no","link":"no","editable":"","fixedvalue":"","assigntovalue":"","formulavalue":"JTI4JTdCanVzdGlmaWNhZGFzJTdEJTIwLyUyMCU3QnRvdGFsJTdEJTI5KjEwMA==","formulasequence":"","widget":"","overridetype":"percentage","overridealignment":""},{"fieldid":"k94c3b7e93b52287e1b21c2aca254","sequence":14,"fieldname":"Estado","name":"No, injust.","display":"yes","path":"root:stic_Attendances::field:status","displaypath":"stic_Attendances","sort":"sortable","sortpriority":"","width":75,"jointype":"required","sqlfunction":"-","summaryfunction":"","customsqlfunction":"c3VtJTI4aWYlMjAlMjhzdHJjbXAlMjglN0J0JTdELnN0YXR1cyUyQyUyMCUyN25vX3VuanVzdGlmaWVkJTI3JTI5JTNEMCUyQyUyMDElMkMlMjAwJTI5JTI5","valuetype":"","groupby":"no","link":"no","editable":"","fixedvalue":"","assigntovalue":"injustificadas","formulavalue":"","formulasequence":"","widget":"","overridetype":"-","overridealignment":""},{"fieldid":"k72f2c11e4d47cd46d771a26d0e70","sequence":15,"fieldname":"","name":"% No, injust","display":"yes","path":"","displaypath":"","sort":"-","sortpriority":"","width":75,"jointype":"required","sqlfunction":"-","summaryfunction":"","customsqlfunction":"","valuetype":"","groupby":"no","link":"no","editable":"","fixedvalue":"","assigntovalue":"","formulavalue":"JTI4JTdCaW5qdXN0aWZpY2FkYXMlN0QlMjAvJTIwJTdCdG90YWwlN0QlMjkqMTAw","formulasequence":"","widget":"","overridetype":"percentage","overridealignment":""}]', NULL, NULL),
('23960e95-db3c-dee6-f280-5d5a668d612a', 'Exemple - Duplicates - 02 - Duplicate contacts by email', '2020-07-04 10:16:15', '2020-07-04 10:16:15', '1', '1', NULL, 0, '1', 'Contacts', NULL, NULL, '{"resultsFolded":false,"optionsFolded":true,"authCheck":"full","showDeleted":false,"showExport":true,"showSnapshots":false,"showTools":true}', NULL, NULL, NULL, '{"plugin":"standard","pluginData":{}}', '{"1":{"googlecharts":{"uid":"k3b0a1eb27b7558ce5dd02bbc23bf","dims":"111","type":"Pie","dimensions":{"dimension1":"Total direcciones email"},"dataseries":[],"options":{"legend":true,"is3D":"on"},"title":"TOTAL REGISTROS EN LISTA"},"plugin":"googlecharts"},"plugin":"googlecharts","layout":"-","chartheight":300,"height":"300"}', '{"activePlugins":{"kcsvexport":"1","ktargetlistexport":"0","ksnapshots":"0"}}', '[{"unionid":"root","id":"root","group":"root","type":"AND","parent":"-","notexists":""}]', '[{"unionid":"root","sequence":"","fieldid":"k2b2821cb9ce5b6dd61784a311898","name":"Direcci\\u00f3n de Email","fixedvalue":"","groupid":"root","path":"root:Contacts::link:Contacts:email_addresses_primary::field:email_address","displaypath":"Contacts::Direcci\\u00f3n de Email","referencefieldid":"","operator":"isnotempty","type":"varchar","value":"","valuekey":"","valueto":"","valuetokey":"","jointype":"required","context":"","reference":"","include":"","usereditable":"yes","dashleteditable":"no","exportpdf":"no","customsqlfunction":""}]', '[{"fieldid":"kf2e5fe85e9cb952075d9719596f2","sequence":"06","fieldname":"Direcci\\u00f3n de Email","name":"Direcci\\u00f3n de correo","display":"yes","path":"root:Contacts::link:Contacts:email_addresses_primary::field:email_address","displaypath":"Contacts::Direcci\\u00f3n de Email","sort":"asc","sortpriority":2,"width":200,"jointype":"required","sqlfunction":"-","summaryfunction":"","customsqlfunction":"","valuetype":"","groupby":"yes","link":"no","editable":"","fixedvalue":"","assigntovalue":"","formulavalue":"","formulasequence":"","widget":"","overridetype":"","overridealignment":""},{"fieldid":"k4f63ec66c34c7237658e3c63e4a3","sequence":"07","fieldname":"Direcci\\u00f3n de Email","name":"Registros coincidentes","display":"yes","path":"root:Contacts::link:Contacts:email_addresses_primary::field:email_address","displaypath":"Contacts::Direcci\\u00f3n de Email","sort":"desc","sortpriority":1,"width":150,"jointype":"required","sqlfunction":"COUNT","summaryfunction":"","customsqlfunction":"","valuetype":"","groupby":"no","link":"no","editable":"","fixedvalue":"","assigntovalue":"","formulavalue":"","formulasequence":"","widget":"","overridetype":"","overridealignment":""}]', NULL, NULL),
('2d9bf2d1-da73-24e4-f2b7-5da04e4805d4', 'Exemple - Economy - 01 - Payments basic data', '2020-07-04 10:16:15', '2020-07-04 10:16:15', '1', '1', NULL, 0, '1', 'stic_Payments', NULL, NULL, '{"resultsFolded":false,"optionsFolded":true,"authCheck":"full","showDeleted":false,"showExport":true,"showSnapshots":false,"showTools":true}', NULL, '{"standardViewProperties":{"listEntries":100}}', NULL, '{"plugin":"standard","pluginData":{}}', '{"1":{"googlecharts":{"uid":"k6a675c2c0270287f06dc0e98d351","dims":"111","type":"Pie","dimensions":{"dimension1":"kea4081d01154abc3f7c9cfa77db2"},"dataseries":[{"fieldid":"kf582fb2bf3353f4146193959feb2","name":"Importe","chartfunction":"SUM","meaning":"value","axis":"P","renderer":"bars"}],"options":{"legend":true,"is3D":"on"},"title":"Importe por estados"},"plugin":"googlecharts"},"2":{"plugin":"googlecharts","googlecharts":{"uid":"k51350697b5e5f505e8e878e08fd8","dims":"111","type":"Pie","dimensions":{"dimension1":"kb23d3d4d70e3111c2468aed12c3d"},"dataseries":[{"fieldid":"kf582fb2bf3353f4146193959feb2","name":"Importe","chartfunction":"SUM","meaning":"value","axis":"P","renderer":"bars"}],"options":{"legend":true,"is3D":"on"},"title":"Importe por Tipo de Pago"}},"3":{"plugin":"googlecharts","googlecharts":{"uid":"k52ceac45e98cda64391f21ce36eb","dims":"111","type":"Pie","dimensions":{"dimension1":"k2631d861c940a486dccc361c9bde"},"dataseries":[{"fieldid":"kf582fb2bf3353f4146193959feb2","name":"Importe","chartfunction":"SUM","meaning":"value","axis":"P","renderer":"bars"}],"options":{"legend":true,"is3D":"on"},"title":"Importe por medio de pago"}},"4":{"plugin":"googlecharts","googlecharts":{"uid":"k43023d991379147733c5c526ff2e","dims":"111","type":"Pie","dimensions":{"dimension1":"kea4081d01154abc3f7c9cfa77db2"},"dataseries":[{"fieldid":"k433df7861af8b47a28394017afae","name":"ID","chartfunction":"SUM","meaning":"value","axis":"P","renderer":"bars"}],"options":{"legend":true,"is3D":"on"},"title":"Registros por Estado"}},"plugin":"googlecharts","layout":"1x4","chartheight":300,"height":"250"}', '{"activePlugins":{"kcsvexport":"1","ktargetlistexport":"0","ksnapshots":"0"}}', '[{"unionid":"root","id":"root","group":"root","type":"AND","parent":"-","notexists":""}]', '[{"unionid":"root","sequence":"","fieldid":"k43c4b585523ba16ede4544de4b2e","name":"Tipo de pago","fixedvalue":"","groupid":"root","path":"root:stic_Payments::field:payment_type","displaypath":"stic_Payments","referencefieldid":"","operator":"ignore","type":"enum","value":"","valuekey":"","valueto":"","valuetokey":"","jointype":"required","context":"","reference":"","include":"","usereditable":"yes","dashleteditable":"no","exportpdf":"no","customsqlfunction":""},{"unionid":"root","sequence":"","fieldid":"k340f29eed282d6da886ef33fb1d3","name":"Tipo de movimiento","fixedvalue":"","groupid":"root","path":"root:stic_Payments::field:transaction_type","displaypath":"stic_Payments","referencefieldid":"","operator":"ignore","type":"enum","value":"","valuekey":"","valueto":"","valuetokey":"","jointype":"required","context":"","reference":"","include":"","usereditable":"yes","dashleteditable":"no","exportpdf":"no","customsqlfunction":""},{"unionid":"root","sequence":"","fieldid":"k55b19804e62d8bafa91b44441309","name":"Estado","fixedvalue":"","groupid":"root","path":"root:stic_Payments::field:status","displaypath":"stic_Payments","referencefieldid":"","operator":"ignore","type":"enum","value":"","valuekey":"","valueto":"","valuetokey":"","jointype":"required","context":"","reference":"","include":"","usereditable":"yes","dashleteditable":"no","exportpdf":"no","customsqlfunction":""},{"unionid":"root","sequence":"","fieldid":"kbd2c9bebc5906f8a5bf34269ba8d","name":"Fecha de pago","fixedvalue":"","groupid":"root","path":"root:stic_Payments::field:payment_date","displaypath":"stic_Payments","referencefieldid":"","operator":"ignore","type":"date","value":"","valuekey":"","valueto":"","valuetokey":"","jointype":"required","context":"","reference":"","include":"","usereditable":"yes","dashleteditable":"no","exportpdf":"no","customsqlfunction":""},{"unionid":"root","sequence":"","fieldid":"k413e0d5e5382cd879fcb31ac8ca6","name":"Importe","fixedvalue":"","groupid":"root","path":"root:stic_Payments::field:amount","displaypath":"stic_Payments","referencefieldid":"","operator":"ignore","type":"currency","value":"","valuekey":"","valueto":"","valuetokey":"","jointype":"required","context":"","reference":"","include":"","usereditable":"yes","dashleteditable":"no","exportpdf":"no","customsqlfunction":""},{"unionid":"root","sequence":"","fieldid":"k0f5c0d68234baf5c0b34c2ced73f","name":"Medio de pago","fixedvalue":"","groupid":"root","path":"root:stic_Payments::field:payment_method","displaypath":"stic_Payments","referencefieldid":"","operator":"ignore","type":"enum","value":"","valuekey":"","valueto":"","valuetokey":"","jointype":"required","context":"","reference":"","include":"","usereditable":"yes","dashleteditable":"no","exportpdf":"no","customsqlfunction":""}]', '[{"fieldid":"k036d58cd32f4a2d1a55f267bc95b","sequence":"01","fieldname":"Nombre","name":"Registro de Pago","display":"yes","path":"root:stic_Payments::field:name","displaypath":"stic_Payments","sort":"-","sortpriority":"","width":250,"jointype":"required","sqlfunction":"-","summaryfunction":"","customsqlfunction":"","valuetype":"","groupby":"no","link":"yes","editable":"","fixedvalue":"","assigntovalue":"","formulavalue":"","formulasequence":"","widget":"","overridetype":"","overridealignment":""},{"fieldid":"k3d710c3450c548396a6060201489","sequence":"02","fieldname":"Nombre:","name":"Nombre:","display":"yes","path":"root:stic_Payments::link:stic_Payments:stic_payments_contacts::field:first_name","displaypath":"stic_Payments::Personas","sort":"sortable","sortpriority":"","width":"100","jointype":"optional","sqlfunction":"-","summaryfunction":"","customsqlfunction":"","valuetype":"","groupby":"no","link":"no","editable":"","fixedvalue":"","assigntovalue":"","formulavalue":"","formulasequence":"","widget":"","overridetype":"","overridealignment":""},{"fieldid":"k112bc5dd74edbffc338248c222a8","sequence":"03","fieldname":"Apellidos:","name":"Apellidos:","display":"yes","path":"root:stic_Payments::link:stic_Payments:stic_payments_contacts::field:last_name","displaypath":"stic_Payments::Personas","sort":"sortable","sortpriority":"","width":150,"jointype":"optional","sqlfunction":"-","summaryfunction":"","customsqlfunction":"","valuetype":"","groupby":"no","link":"no","editable":"","fixedvalue":"","assigntovalue":"","formulavalue":"","formulasequence":"","widget":"","overridetype":"","overridealignment":""},{"fieldid":"kf113e4d5b976b841f953efe2c389","sequence":"04","fieldname":"Nombre:","name":"Organizaci\\u00f3n","display":"yes","path":"root:stic_Payments::link:stic_Payments:stic_payments_accounts::field:name","displaypath":"stic_Payments::Organizaciones","sort":"sortable","sortpriority":"","width":150,"jointype":"optional","sqlfunction":"-","summaryfunction":"","customsqlfunction":"","valuetype":"","groupby":"no","link":"no","editable":"","fixedvalue":"","assigntovalue":"","formulavalue":"","formulasequence":"","widget":"","overridetype":"","overridealignment":""},{"fieldid":"k433df7861af8b47a28394017afae","sequence":"05","fieldname":"ID","name":"ID","display":"no","path":"root:stic_Payments::field:id","displaypath":"stic_Payments","sort":"-","sortpriority":"","width":"100","jointype":"required","sqlfunction":"-","summaryfunction":"","customsqlfunction":"","valuetype":"","groupby":"yes","link":"no","editable":"","fixedvalue":"","assigntovalue":"","formulavalue":"","formulasequence":"","widget":"","overridetype":"","overridealignment":""},{"fieldid":"k496011d7f5ae2b19b9822ecd1f2f","sequence":"06","fieldname":"Nombre (autom.)","name":"Forma de pago","display":"yes","path":"root:stic_Payments::link:stic_Payments:stic_payments_stic_payment_commitments::field:name","displaypath":"stic_Payments::Formas_de_Pago","sort":"sortable","sortpriority":"","width":250,"jointype":"optional","sqlfunction":"-","summaryfunction":"","customsqlfunction":"","valuetype":"","groupby":"no","link":"yes","editable":"","fixedvalue":"","assigntovalue":"","formulavalue":"","formulasequence":"","widget":"","overridetype":"","overridealignment":""},{"fieldid":"kea4081d01154abc3f7c9cfa77db2","sequence":"07","fieldname":"Estado","name":"Estado","display":"yes","path":"root:stic_Payments::field:status","displaypath":"stic_Payments","sort":"sortable","sortpriority":null,"width":"100","jointype":"optional","sqlfunction":"-","summaryfunction":"","customsqlfunction":"","valuetype":"","groupby":"no","link":"no","editable":"","fixedvalue":"","assigntovalue":"","formulavalue":"","formulasequence":"","widget":"","overridetype":"","overridealignment":""},{"fieldid":"ka3ce9b25cc97e98f90f74444a2ef","sequence":"08","fieldname":"Tipo de movimiento","name":"Tipo de movimiento","display":"yes","path":"root:stic_Payments::field:transaction_type","displaypath":"stic_Payments","sort":"sortable","sortpriority":"","width":"100","jointype":"optional","sqlfunction":"-","summaryfunction":"","customsqlfunction":"","valuetype":"","groupby":"no","link":"no","editable":"","fixedvalue":"","assigntovalue":"","formulavalue":"","formulasequence":"","widget":"","overridetype":"","overridealignment":""},{"fieldid":"k2631d861c940a486dccc361c9bde","sequence":"09","fieldname":"Medio de pago","name":"Medio de pago","display":"yes","path":"root:stic_Payments::field:payment_method","displaypath":"stic_Payments","sort":"sortable","sortpriority":"","width":"100","jointype":"optional","sqlfunction":"-","summaryfunction":"","customsqlfunction":"","valuetype":"","groupby":"no","link":"no","editable":"","fixedvalue":"","assigntovalue":"","formulavalue":"","formulasequence":"","widget":"","overridetype":"","overridealignment":""},{"fieldid":"kb23d3d4d70e3111c2468aed12c3d","sequence":10,"fieldname":"Tipo de pago","name":"Tipo de pago","display":"yes","path":"root:stic_Payments::field:payment_type","displaypath":"stic_Payments","sort":"sortable","sortpriority":"","width":"100","jointype":"optional","sqlfunction":"-","summaryfunction":"","customsqlfunction":"","valuetype":"","groupby":"no","link":"no","editable":"","fixedvalue":"","assigntovalue":"","formulavalue":"","formulasequence":"","widget":"","overridetype":"","overridealignment":""},{"fieldid":"kf582fb2bf3353f4146193959feb2","sequence":11,"fieldname":"Importe","name":"Importe","display":"yes","path":"root:stic_Payments::field:amount","displaypath":"stic_Payments","sort":"sortable","sortpriority":null,"width":"100","jointype":"optional","sqlfunction":"-","summaryfunction":"","customsqlfunction":"","valuetype":"","groupby":"no","link":"no","editable":"","fixedvalue":"","assigntovalue":"","formulavalue":"","formulasequence":"","widget":"","overridetype":"","overridealignment":""},{"fieldid":"k796653a818f4da03aec05a8aabd8","sequence":12,"fieldname":"Fecha de pago","name":"Fecha de pago","display":"yes","path":"root:stic_Payments::field:payment_date","displaypath":"stic_Payments","sort":"desc","sortpriority":1,"width":"100","jointype":"optional","sqlfunction":"-","summaryfunction":"","customsqlfunction":"","valuetype":"","groupby":"no","link":"no","editable":"","fixedvalue":"","assigntovalue":"","formulavalue":"","formulasequence":"","widget":"","overridetype":"","overridealignment":""},{"fieldid":"k197f78b7f102d79cd919f7c515a3","sequence":13,"fieldname":"Fecha devoluci\\u00f3n","name":"Fecha devoluci\\u00f3n","display":"yes","path":"root:stic_Payments::field:rejection_date","displaypath":"stic_Payments","sort":"sortable","sortpriority":"","width":"100","jointype":"optional","sqlfunction":"-","summaryfunction":"","customsqlfunction":"","valuetype":"","groupby":"no","link":"no","editable":"","fixedvalue":"","assigntovalue":"","formulavalue":"","formulasequence":"","widget":"","overridetype":"","overridealignment":""}]', NULL, NULL),
('329b7802-b9c7-13e8-36a0-5d8b235b8c24', 'Exemple - Duplicates - 01 - Duplicate contacts by identification number', '2020-07-04 10:16:15', '2020-07-04 10:16:15', '1', '1', NULL, 0, '1', 'Contacts', NULL, NULL, '{"resultsFolded":false,"optionsFolded":true,"authCheck":"full","showDeleted":false,"showExport":true,"showSnapshots":false,"showTools":true}', NULL, '{"standardViewProperties":{"listEntries":100}}', NULL, '{"plugin":"standard","pluginData":{}}', '{"1":{"googlecharts":{"uid":"kab9c9a8a605176ba86cc2d19d01e","dims":"111","type":"Pie","dimensions":{"dimension1":"ke93310039020d20d4da5d88e8cb4"},"dataseries":[{"fieldid":"k668018930a9d983174c3e5551c63","name":"Apellidos persona 1","chartfunction":"SUM","meaning":"value","axis":"P","renderer":"bars"}],"options":{"legend":true,"is3D":"on"},"title":"TOTAL REGISTROS EN LISTA"},"plugin":"googlecharts"},"plugin":"googlecharts","layout":"-","chartheight":300,"height":"300"}', '{"activePlugins":{"kcsvexport":"1","ktargetlistexport":"0","ksnapshots":"0"}}', '[{"unionid":"root","id":"root","group":"root","type":"AND","parent":"-","notexists":""}]', '[{"unionid":"root","sequence":"","fieldid":"kf48eadf691d12ae2b3842235cec3","name":"N\\u00famero de identificaci\\u00f3n","fixedvalue":"","groupid":"root","path":"root:Contacts::field:stic_identification_number_c","displaypath":"Contacts","referencefieldid":"","operator":"isnotempty","type":"varchar","value":"","valuekey":"","valueto":"","valuetokey":"","jointype":"required","context":"","reference":"","include":"","usereditable":"yes","dashleteditable":"no","exportpdf":"no","customsqlfunction":""}]', '[{"fieldid":"kfd52c4e7572f3e5ccd00a8673808","sequence":"01","fieldname":"N\\u00famero de identificaci\\u00f3n","name":"N\\u00famero de identificaci\\u00f3n","display":"yes","path":"root:Contacts::field:stic_identification_number_c","displaypath":"Contacts","sort":"asc","sortpriority":2,"width":140,"jointype":"required","sqlfunction":"-","summaryfunction":"","customsqlfunction":"","valuetype":"","groupby":"yes","link":"no","editable":"","fixedvalue":"","assigntovalue":"","formulavalue":"","formulasequence":"","widget":"","overridetype":"","overridealignment":""},{"fieldid":"ke93310039020d20d4da5d88e8cb4","sequence":"02","fieldname":"N\\u00famero de identificaci\\u00f3n","name":"Registros coincidentes","display":"yes","path":"root:Contacts::field:stic_identification_number_c","displaypath":"Contacts","sort":"desc","sortpriority":1,"width":125,"jointype":"required","sqlfunction":"COUNT","summaryfunction":"","customsqlfunction":"","valuetype":"","groupby":"no","link":"no","editable":"","fixedvalue":"","assigntovalue":"","formulavalue":"","formulasequence":"","widget":"","overridetype":"","overridealignment":""}]', NULL, NULL);
INSERT INTO `kreports` (`id`, `name`, `date_entered`, `date_modified`, `modified_user_id`, `created_by`, `description`, `deleted`, `assigned_user_id`, `report_module`, `report_status`, `union_modules`, `reportoptions`, `listtype`, `listtypeproperties`, `selectionlimit`, `presentation_params`, `visualization_params`, `integration_params`, `wheregroups`, `whereconditions`, `listfields`, `unionlistfields`, `advancedoptions`) VALUES
('4104523e-b79b-8d5b-6fde-58b06b46d77d', 'Exemple - Economy - 03 - Opportunitties basic data', '2020-07-04 10:16:15', '2020-07-04 10:16:15', '1', '1', NULL, 0, '1', 'Opportunities', '3', NULL, '{"resultsFolded":false,"optionsFolded":true,"authCheck":"full","showDeleted":false,"showExport":true,"showSnapshots":false,"showTools":true}', NULL, '{"standardViewProperties":{"listEntries":50}}', NULL, '{"plugin":"standard","pluginData":{}}', '{"1":{"googlecharts":{"uid":"kab4e98e7c5303fdbf524f57be796","dims":"111","type":"Pie","dimensions":{"dimension1":"k05e2d792d84dac9d4651d4255c4a"},"dataseries":[{"fieldid":"k0805fa0733d39627dfc5978586aa","name":"Importe solicitado  (EUR \\u20ac)","chartfunction":"SUM","meaning":"value","axis":"P","renderer":"bars"}],"options":{"legend":true,"is3D":"on"},"title":"IMPORTE POR TIPO"},"plugin":"googlecharts"},"2":{"plugin":"googlecharts","googlecharts":{"uid":"k6f4bb03e509a06d47f692a170d9b","dims":"111","type":"Pie","dimensions":{"dimension1":"k02fc5af97195b7500fd9156e9d18"},"dataseries":[{"fieldid":"k0805fa0733d39627dfc5978586aa","name":"Importe solicitado  (EUR \\u20ac)","chartfunction":"SUM","meaning":"value","axis":"P","renderer":"bars"}],"options":{"legend":true,"is3D":"on"},"title":"IMPORTE POR ESTADO"}},"plugin":"googlecharts","layout":"1x2","chartheight":300,"height":"300"}', '{"activePlugins":{"kcsvexport":"1","ktargetlistexport":"0","ksnapshots":"0"}}', '[{"unionid":"root","id":"root","group":"root","type":"AND","parent":"-","notexists":""}]', '[{"unionid":"root","sequence":"","fieldid":"k369e4bd8fb9d6d926b147cacf68c","name":"Estado","fixedvalue":"","groupid":"root","path":"root:Opportunities::field:stic_status_c","displaypath":"Opportunities","referencefieldid":"","operator":"ignore","type":"enum","value":"","valuekey":"","valueto":"","valuetokey":"","jointype":"required","context":"","reference":"","include":"","usereditable":"yes","dashleteditable":"no","exportpdf":"no","customsqlfunction":""},{"unionid":"root","sequence":"","fieldid":"k30381e476fd4d433cbc844db0edc","name":"Fecha Presentaci\\u00f3n","fixedvalue":"","groupid":"root","path":"root:Opportunities::field:stic_presentation_date_c","displaypath":"Opportunities","referencefieldid":"","operator":"ignore","type":"date","value":"","valuekey":"","valueto":"","valuetokey":"","jointype":"required","context":"","reference":"","include":"","usereditable":"yes","dashleteditable":"no","exportpdf":"no","customsqlfunction":""},{"unionid":"root","sequence":"","fieldid":"k72b06ae306a4555f55d574fbc1de","name":"Fecha Resoluci\\u00f3n","fixedvalue":"","groupid":"root","path":"root:Opportunities::field:stic_resolution_date_c","displaypath":"Opportunities","referencefieldid":"","operator":"ignore","type":"date","value":"","valuekey":"","valueto":"","valuetokey":"","jointype":"required","context":"","reference":"","include":"","usereditable":"yes","dashleteditable":"no","exportpdf":"no","customsqlfunction":""},{"unionid":"root","sequence":"","fieldid":"kaf824efb43b43a7d4f8ea75feea3","name":"Tipo","fixedvalue":"","groupid":"root","path":"root:Opportunities::field:stic_type_c","displaypath":"Opportunities","referencefieldid":"","operator":"ignore","type":"enum","value":"","valuekey":"","valueto":"","valuetokey":"","jointype":"required","context":"","reference":"","include":"","usereditable":"yes","dashleteditable":"no","exportpdf":"no","customsqlfunction":""},{"unionid":"root","sequence":"","fieldid":"k583a0fdcc64b26354be083d24ed9","name":"Importe solicitado  (EUR \\u20ac)","fixedvalue":"","groupid":"root","path":"root:Opportunities::field:amount","displaypath":"Opportunities","referencefieldid":"","operator":"ignore","type":"currency","value":"","valuekey":"","valueto":"","valuetokey":"","jointype":"required","context":"","reference":"","include":"","usereditable":"yes","dashleteditable":"no","exportpdf":"no","customsqlfunction":""},{"unionid":"root","sequence":"","fieldid":"k919441fb31047b728527c01a7331","name":"Importe concedido (EUR \\u20ac)","fixedvalue":"","groupid":"root","path":"root:Opportunities::field:stic_amount_awarded_c","displaypath":"Opportunities","referencefieldid":"","operator":"ignore","type":"currency","value":"","valuekey":"","valueto":"","valuetokey":"","jointype":"required","context":"","reference":"","include":"","usereditable":"yes","dashleteditable":"no","exportpdf":"no","customsqlfunction":""}]', '[{"fieldid":"k626752f5752a41587e77f403ef4a","sequence":"01","fieldname":"Nom","name":"Proceso","display":"yes","path":"root:Opportunities::field:name","displaypath":"Opportunities","sort":"sortable","sortpriority":"","width":250,"jointype":"required","sqlfunction":"-","summaryfunction":"","customsqlfunction":"","valuetype":"","groupby":"no","link":"yes","editable":"","fixedvalue":"","assigntovalue":"","formulavalue":"","formulasequence":"","widget":"","overridetype":"","overridealignment":""},{"fieldid":"k2cdf13658197fef0d39a360a89c3","sequence":"02","fieldname":"Nom:","name":"Organizaci\\u00f3n","display":"yes","path":"root:Opportunities::link:Opportunities:accounts::field:name","displaypath":"Opportunities::Comptes","sort":"sortable","sortpriority":"","width":200,"jointype":"required","sqlfunction":"-","summaryfunction":"","customsqlfunction":"","valuetype":"","groupby":"no","link":"no","editable":"","fixedvalue":"","assigntovalue":"","formulavalue":"","formulasequence":"","widget":"","overridetype":"","overridealignment":""},{"fieldid":"k02fc5af97195b7500fd9156e9d18","sequence":"04","fieldname":"Estat","name":"Estado","display":"yes","path":"root:Opportunities::field:stic_status_c","displaypath":"Opportunities","sort":"sortable","sortpriority":"","width":"100","jointype":"required","sqlfunction":"-","summaryfunction":"","customsqlfunction":"","valuetype":"","groupby":"no","link":"no","editable":"","fixedvalue":"","assigntovalue":"","formulavalue":"","formulasequence":"","widget":"","overridetype":"","overridealignment":""},{"fieldid":"k0805fa0733d39627dfc5978586aa","sequence":"05","fieldname":"Import sol\\u00b7licitat  (EUR \\u20ac)","name":"Importe solicitado  (EUR \\u20ac)","display":"yes","path":"root:Opportunities::field:amount","displaypath":"Opportunities","sort":"sortable","sortpriority":"","width":"100","jointype":"required","sqlfunction":"-","summaryfunction":"","customsqlfunction":"","valuetype":"","groupby":"no","link":"no","editable":"","fixedvalue":"","assigntovalue":"","formulavalue":"","formulasequence":"","widget":"","overridetype":"","overridealignment":""},{"fieldid":"k6d9c9807bb0de5ba7342bf604107","sequence":"06","fieldname":"Import concedit  (EUR \\u20ac)","name":"Importe concedido  (EUR \\u20ac)","display":"yes","path":"root:Opportunities::field:stic_amount_awarded_c","displaypath":"Opportunities","sort":"sortable","sortpriority":"","width":"100","jointype":"required","sqlfunction":"-","summaryfunction":"","customsqlfunction":"","valuetype":"","groupby":"no","link":"no","editable":"","fixedvalue":"","assigntovalue":"","formulavalue":"","formulasequence":"","widget":"","overridetype":"","overridealignment":""},{"fieldid":"k186f156aa43062fafb60139dd632","sequence":"07","fieldname":"Data Presentaci\\u00f3","name":"Fecha Presentaci\\u00f3n","display":"yes","path":"root:Opportunities::field:stic_presentation_date_c","displaypath":"Opportunities","sort":"sortable","sortpriority":"","width":"100","jointype":"required","sqlfunction":"-","summaryfunction":"","customsqlfunction":"","valuetype":"","groupby":"no","link":"no","editable":"","fixedvalue":"","assigntovalue":"","formulavalue":"","formulasequence":"","widget":"","overridetype":"","overridealignment":""},{"fieldid":"k8ec8016ca87a131946f806502333","sequence":"08","fieldname":"Data Resoluci\\u00f3","name":"Fecha Resoluci\\u00f3n","display":"yes","path":"root:Opportunities::field:stic_resolution_date_c","displaypath":"Opportunities","sort":"sortable","sortpriority":"","width":"100","jointype":"required","sqlfunction":"-","summaryfunction":"","customsqlfunction":"","valuetype":"","groupby":"no","link":"no","editable":"","fixedvalue":"","assigntovalue":"","formulavalue":"","formulasequence":"","widget":"","overridetype":"","overridealignment":""},{"fieldid":"k05e2d792d84dac9d4651d4255c4a","sequence":"09","fieldname":"Tipus","name":"Tipo","display":"yes","path":"root:Opportunities::field:stic_type_c","displaypath":"Opportunities","sort":"sortable","sortpriority":"","width":"100","jointype":"required","sqlfunction":"-","summaryfunction":"","customsqlfunction":"","valuetype":"","groupby":"no","link":"no","editable":"","fixedvalue":"","assigntovalue":"","formulavalue":"","formulasequence":"","widget":"","overridetype":"","overridealignment":""}]', NULL, NULL),
('5229c080-7e3e-8a78-4e5c-5d8b7bb5f9b9', 'Exemple - Duplicates - 04 - Duplicate leads by email', '2020-07-04 10:16:15', '2020-07-04 10:16:15', '1', '1', NULL, 0, '1', 'Leads', NULL, NULL, '{"resultsFolded":false,"optionsFolded":true,"authCheck":"full","showDeleted":false,"showExport":true,"showSnapshots":false,"showTools":true}', NULL, NULL, NULL, '{"plugin":"standard","pluginData":{}}', '{"1":{"googlecharts":{"uid":"kc4c6b1f9b4f9db92e2840f032813","dims":"111","type":"Pie","dimensions":{"dimension1":"k7288aeec245ed8fb07695f0a84d2"},"dataseries":[{"fieldid":"kff11d85806be833fa19fe5f1620c","name":"Direcci\\u00f3n de Email","chartfunction":"SUM","meaning":"value","axis":"P","renderer":"bars"}],"options":{"legend":true,"is3D":"on"},"title":"TOTAL REGISTROS EN LISTA"},"plugin":"googlecharts"},"plugin":"googlecharts","layout":"-","chartheight":300,"height":"300"}', '{"activePlugins":{"kcsvexport":"1","ktargetlistexport":"0","ksnapshots":"0"}}', '[{"unionid":"root","id":"root","group":"root","type":"AND","parent":"-","notexists":""}]', '[{"unionid":"root","sequence":"","fieldid":"k30cd713ff8f18e6e38b7b8651f4b","name":"Direcci\\u00f3n de Email","fixedvalue":"","groupid":"root","path":"root:Leads::link:Leads:email_addresses_primary::field:email_address","displaypath":"Leads::Direcci\\u00f3n de Email","referencefieldid":"","operator":"isnotempty","type":"varchar","value":"","valuekey":"","valueto":"","valuetokey":"","jointype":"required","context":"","reference":"","include":"","usereditable":"yes","dashleteditable":"no","exportpdf":"no","customsqlfunction":""}]', '[{"fieldid":"kff11d85806be833fa19fe5f1620c","sequence":"06","fieldname":"Direcci\\u00f3n de Email","name":"Direcci\\u00f3n de correo","display":"yes","path":"root:Leads::link:Leads:email_addresses_primary::field:email_address","displaypath":"Leads::Direcci\\u00f3n de Email","sort":"asc","sortpriority":2,"width":200,"jointype":"required","sqlfunction":"-","summaryfunction":"","customsqlfunction":"","valuetype":"","groupby":"yes","link":"no","editable":"","fixedvalue":"","assigntovalue":"","formulavalue":"","formulasequence":"","widget":"","overridetype":"","overridealignment":""},{"fieldid":"k7288aeec245ed8fb07695f0a84d2","sequence":"07","fieldname":"Direcci\\u00f3n de Email","name":"Registros coincidentes","display":"yes","path":"root:Leads::link:Leads:email_addresses_primary::field:email_address","displaypath":"Leads::Direcci\\u00f3n de Email","sort":"desc","sortpriority":1,"width":150,"jointype":"required","sqlfunction":"COUNT","summaryfunction":"","customsqlfunction":"","valuetype":"","groupby":"no","link":"no","editable":"","fixedvalue":"","assigntovalue":"","formulavalue":"","formulasequence":"","widget":"","overridetype":"","overridealignment":""}]', NULL, NULL),
('69ab23c8-2d9a-1408-aa93-5da05bb634fa', 'Exemple - Economy - 02 - Remittances basic data', '2020-07-04 10:16:15', '2020-07-04 10:16:15', '1', '1', NULL, 0, '1', 'stic_Remittances', NULL, NULL, '{"resultsFolded":false,"optionsFolded":true,"authCheck":"full","showDeleted":false,"showExport":true,"showSnapshots":false,"showTools":true}', NULL, NULL, NULL, '{"plugin":"standard","pluginData":{}}', '{"1":{"googlecharts":{"uid":"k10683355dde31ff72f37d54c58c9","dims":"111","type":"Pie","dimensions":{"dimension1":"ke3e727a5f27acfa8df63d6853fb2"},"dataseries":[{"fieldid":"kd0df616368caec6d85fda53fef3b","name":"ID","chartfunction":"SUM","meaning":"value","axis":"P","renderer":"bars"}],"options":{"legend":true,"is3D":"on"},"title":"N\\u00daMERO DE REMESAS POR ESTADO"},"plugin":"googlecharts"},"2":{"plugin":"googlecharts","googlecharts":{"uid":"k4592675d81599feba963bb953c4f","dims":"111","type":"Pie","dimensions":{"dimension1":"ke3e727a5f27acfa8df63d6853fb2"},"dataseries":[{"fieldid":"k33926835f5a33b439050a18a84ee","name":"N\\u00famero de pagos","chartfunction":"SUM","meaning":"value","axis":"P","renderer":"bars"}],"options":{"legend":true,"is3D":"on"},"title":"N\\u00daMERO DE PAGOS POR ESTADO REMESA"}},"3":{"plugin":"googlecharts","googlecharts":{"uid":"k75ace852aab23e373cdcad868683","dims":"111","type":"Pie","dimensions":{"dimension1":"ke3e727a5f27acfa8df63d6853fb2"},"dataseries":[{"fieldid":"k88c1e8c249b8bcb2dffe0434f5ce","name":"Importe de los pagos","chartfunction":"SUM","meaning":"value","axis":"P","renderer":"bars"}],"options":{"legend":true,"is3D":"on"},"title":"IMPORTE DE LOS PAGOS POR ESTADO"}},"4":{"plugin":"googlecharts","googlecharts":{"uid":"k2460b46e0a66bf607a8412308fb5","dims":"111","type":"Bar","dimensions":{"dimension1":null},"dataseries":[{"fieldid":"kd0df616368caec6d85fda53fef3b","name":"ID","chartfunction":"SUM","meaning":"value","axis":"P","renderer":"bars"}],"options":{},"title":"N\\u00daMERO DE REMESAS TOTAL"}},"plugin":"googlecharts","layout":"1x4","chartheight":300,"height":"300"}', '{"activePlugins":{"kcsvexport":"1","ktargetlistexport":"0","ksnapshots":"0"}}', '[{"unionid":"root","id":"root","group":"root","type":"AND","parent":"-","notexists":""}]', '[{"unionid":"root","sequence":"","fieldid":"ka9551e4dede041f4d32202b013a9","name":"Estado de la Remesa","fixedvalue":"","groupid":"root","path":"root:stic_Remittances::field:status","displaypath":"stic_Remittances","referencefieldid":"","operator":"ignore","type":"enum","value":"","valuekey":"","valueto":"","valuetokey":"","jointype":"required","context":"","reference":"","include":"","usereditable":"yes","dashleteditable":"no","exportpdf":"no","customsqlfunction":""},{"unionid":"root","sequence":"","fieldid":"kc5e4d353aef4b2cf23ae54954e76","name":"Fecha de la remesa","fixedvalue":"","groupid":"root","path":"root:stic_Remittances::field:remittance_date","displaypath":"stic_Remittances","referencefieldid":"","operator":"ignore","type":"date","value":"","valuekey":"","valueto":"","valuetokey":"","jointype":"required","context":"","reference":"","include":"","usereditable":"yes","dashleteditable":"no","exportpdf":"no","customsqlfunction":""},{"unionid":"root","sequence":"","fieldid":"k8e002b259951e81a4ab875cb064c","name":"Tipo de Remesa","fixedvalue":"","groupid":"root","path":"root:stic_Remittances::field:type","displaypath":"stic_Remittances","referencefieldid":"","operator":"ignore","type":"enum","value":"","valuekey":"","valueto":"","valuetokey":"","jointype":"required","context":"","reference":"","include":"","usereditable":"yes","dashleteditable":"no","exportpdf":"no","customsqlfunction":""},{"unionid":"root","sequence":"","fieldid":"k96ac0dbb77d76ada8064a60088e2","name":"Nombre de la Remesa","fixedvalue":"","groupid":"root","path":"root:stic_Remittances::field:name","displaypath":"stic_Remittances","referencefieldid":"","operator":"ignore","type":"name","value":"","valuekey":"","valueto":"","valuetokey":"","jointype":"required","context":"","reference":"","include":"","usereditable":"yes","dashleteditable":"no","exportpdf":"no","customsqlfunction":""},{"unionid":"root","sequence":"","fieldid":"k492892f715c01a732cd08410cbdf","name":"IBAN de cargo de remesa","fixedvalue":"","groupid":"root","path":"root:stic_Remittances::field:bank_account","displaypath":"stic_Remittances","referencefieldid":"","operator":"ignore","type":"enum","value":"","valuekey":"","valueto":"","valuetokey":"","jointype":"required","context":"","reference":"","include":"","usereditable":"yes","dashleteditable":"no","exportpdf":"no","customsqlfunction":""}]', '[{"fieldid":"kd0df616368caec6d85fda53fef3b","sequence":"01","fieldname":"ID","name":"ID","display":"no","path":"root:stic_Remittances::field:id","displaypath":"stic_Remittances","sort":"-","sortpriority":"","width":"100","jointype":"required","sqlfunction":"-","summaryfunction":"","customsqlfunction":"","valuetype":"","groupby":"yes","link":"no","editable":"","fixedvalue":"","assigntovalue":"","formulavalue":"","formulasequence":"","widget":"","overridetype":"","overridealignment":""},{"fieldid":"k1311a2b7d0dcee99de5dfbf70917","sequence":"02","fieldname":"Nombre","name":"Nombre","display":"yes","path":"root:stic_Remittances::field:name","displaypath":"stic_Remittances","sort":"sortable","sortpriority":"","width":200,"jointype":"optional","sqlfunction":"-","summaryfunction":"","customsqlfunction":"","valuetype":"","groupby":"no","link":"yes","editable":"","fixedvalue":"","assigntovalue":"","formulavalue":"","formulasequence":"","widget":"","overridetype":"","overridealignment":""},{"fieldid":"k248e20f80446ce7c9460e66c2035","sequence":"03","fieldname":"Fecha de la remesa","name":"Fecha de la remesa","display":"yes","path":"root:stic_Remittances::field:remittance_date","displaypath":"stic_Remittances","sort":"sortable","sortpriority":"","width":"100","jointype":"optional","sqlfunction":"-","summaryfunction":"","customsqlfunction":"","valuetype":"","groupby":"no","link":"no","editable":"","fixedvalue":"","assigntovalue":"","formulavalue":"","formulasequence":"","widget":"","overridetype":"","overridealignment":""},{"fieldid":"ke3e727a5f27acfa8df63d6853fb2","sequence":"04","fieldname":"Estado","name":"Estado","display":"yes","path":"root:stic_Remittances::field:status","displaypath":"stic_Remittances","sort":"sortable","sortpriority":"","width":"100","jointype":"optional","sqlfunction":"-","summaryfunction":"","customsqlfunction":"","valuetype":"","groupby":"no","link":"no","editable":"","fixedvalue":"","assigntovalue":"","formulavalue":"","formulasequence":"","widget":"","overridetype":"","overridealignment":""},{"fieldid":"k2aa38208a8f30479607e450f9cce","sequence":"05","fieldname":"Tipo","name":"Tipo","display":"yes","path":"root:stic_Remittances::field:type","displaypath":"stic_Remittances","sort":"sortable","sortpriority":"","width":"100","jointype":"optional","sqlfunction":"-","summaryfunction":"","customsqlfunction":"","valuetype":"","groupby":"no","link":"no","editable":"","fixedvalue":"","assigntovalue":"","formulavalue":"","formulasequence":"","widget":"","overridetype":"","overridealignment":""},{"fieldid":"k05ca5b37e2f0cd0016de8b415f46","sequence":"06","fieldname":"IBAN","name":"IBAN vinculado","display":"yes","path":"root:stic_Remittances::field:bank_account","displaypath":"stic_Remittances","sort":"sortable","sortpriority":"","width":150,"jointype":"optional","sqlfunction":"-","summaryfunction":"","customsqlfunction":"","valuetype":"","groupby":"no","link":"no","editable":"","fixedvalue":"","assigntovalue":"","formulavalue":"","formulasequence":"","widget":"","overridetype":"","overridealignment":""},{"fieldid":"k33926835f5a33b439050a18a84ee","sequence":"07","fieldname":"ID","name":"N\\u00famero de pagos","display":"yes","path":"root:stic_Remittances::link:stic_Remittances:stic_payments_stic_remittances::field:id","displaypath":"stic_Remittances::Pagos","sort":"sortable","sortpriority":"","width":"100","jointype":"optional","sqlfunction":"COUNT","summaryfunction":"","customsqlfunction":"","valuetype":"","groupby":"no","link":"no","editable":"","fixedvalue":"","assigntovalue":"","formulavalue":"","formulasequence":"","widget":"","overridetype":"","overridealignment":""},{"fieldid":"k88c1e8c249b8bcb2dffe0434f5ce","sequence":"08","fieldname":"Importe","name":"Importe de los pagos","display":"yes","path":"root:stic_Remittances::link:stic_Remittances:stic_payments_stic_remittances::field:amount","displaypath":"stic_Remittances::Pagos","sort":"sortable","sortpriority":"","width":"100","jointype":"optional","sqlfunction":"SUM","summaryfunction":"","customsqlfunction":"","valuetype":"","groupby":"no","link":"no","editable":"","fixedvalue":"","assigntovalue":"","formulavalue":"","formulasequence":"","widget":"","overridetype":"","overridealignment":""}]', NULL, NULL),
('6b0e0e7b-39cf-09f2-e80b-5d5ba936505a', 'Exemple - Economy - 04 - Payments by type and month/year', '2020-07-04 10:16:15', '2020-07-04 10:16:15', '1', '1', NULL, 0, '1', 'stic_Payments', '3', NULL, '{"resultsFolded":false,"optionsFolded":true,"authCheck":"full","showDeleted":false,"showExport":true,"showSnapshots":false,"showTools":true}', NULL, NULL, NULL, '{"plugin":"standard","pluginData":{}}', '{"1":{"googlecharts":{"uid":"k6b18f3a8c7a5e4c71a865d5ba94e","dims":"111","type":"Column","dimensions":{"dimension1":"kd378e1ec6599468c00c9ba089ef2"},"dataseries":[{"fieldid":"kb1b457cf6c19cc4b7a34fb43f0a2","name":"Importe total pagos","chartfunction":"SUM","meaning":"value","axis":"P","renderer":"bars"}],"options":{"legend":true},"title":"Totales por tipo de pago"},"plugin":"googlecharts"},"2":{"plugin":"googlecharts","googlecharts":{"uid":"k6b1a7746eef2b36bb0465d5ba988","dims":"111","type":"Pie","dimensions":{"dimension1":"kd378e1ec6599468c00c9ba089ef2"},"dataseries":[{"fieldid":"k951abbffe4f6f80218e48d3e1d88","name":"N\\u00famero de registros de pago","chartfunction":"SUM","meaning":"value","axis":"P","renderer":"bars"}],"options":{"legend":true},"title":"N\\u00famero de registros de pago"}},"plugin":"googlecharts","layout":"1x2","chartheight":300,"height":"300"}', '{"activePlugins":{"kcsvexport":"1","ktargetlistexport":"0","ksnapshots":"0"}}', '[{"unionid":"root","id":"root","group":"root","type":"AND","parent":"-","notexists":""}]', '[{"unionid":"root","sequence":"","fieldid":"kaab1f98bbad0725814130968ca36","name":"Fecha del pago","fixedvalue":"","groupid":"root","path":"root:stic_Payments::field:payment_date","displaypath":"stic_Payments","referencefieldid":"","operator":"ignore","type":"date","value":"","valuekey":"","valueto":"","valuetokey":"","jointype":"required","context":"","reference":"","include":"","usereditable":"yes","dashleteditable":"no","exportpdf":"no","customsqlfunction":""},{"unionid":"root","sequence":"","fieldid":"kbc0876299aa3f9f68f7e9daf6111","name":"Tipo de pago","fixedvalue":"","groupid":"root","path":"root:stic_Payments::field:payment_type","displaypath":"stic_Payments","referencefieldid":"","operator":"ignore","type":"enum","value":"","valuekey":"","valueto":"","valuetokey":"","jointype":"required","context":"","reference":"","include":"","usereditable":"yes","dashleteditable":"no","exportpdf":"no","customsqlfunction":""},{"unionid":"root","sequence":"","fieldid":"k271f6b3cd45e7ea0962b59eb7089","name":"Tipo de movimiento","fixedvalue":"","groupid":"root","path":"root:stic_Payments::field:transaction_type","displaypath":"stic_Payments","referencefieldid":"","operator":"ignore","type":"enum","value":"","valuekey":"","valueto":"","valuetokey":"","jointype":"required","context":"","reference":"","include":"","usereditable":"yes","dashleteditable":"no","exportpdf":"no","customsqlfunction":""},{"unionid":"root","sequence":"","fieldid":"k6a897cbe12c75f0585957e49611b","name":"Estado","fixedvalue":"","groupid":"root","path":"root:stic_Payments::field:status","displaypath":"stic_Payments","referencefieldid":"","operator":"ignore","type":"enum","value":"","valuekey":"","valueto":"","valuetokey":"","jointype":"required","context":"","reference":"","include":"","usereditable":"yes","dashleteditable":"no","exportpdf":"no","customsqlfunction":""}]', '[{"fieldid":"k21a3d96cc8d1603ab9171115a0e0","sequence":"01","fieldname":"Tipo de movimiento","name":"Tipo de movimiento","display":"yes","path":"root:stic_Payments::field:transaction_type","displaypath":"stic_Payments","sort":"sortable","sortpriority":"","width":"100","jointype":"required","sqlfunction":"-","summaryfunction":"","customsqlfunction":"","valuetype":"","groupby":"yes","link":"no","editable":"","fixedvalue":"","assigntovalue":"","formulavalue":"","formulasequence":"","widget":"","overridetype":"","overridealignment":""},{"fieldid":"kd378e1ec6599468c00c9ba089ef2","sequence":"02","fieldname":"Tipo de pago","name":"Tipo de pago","display":"yes","path":"root:stic_Payments::field:payment_type","displaypath":"stic_Payments","sort":"sortable","sortpriority":"","width":200,"jointype":"required","sqlfunction":"-","summaryfunction":"","customsqlfunction":"","valuetype":"","groupby":"yes","link":"no","editable":"","fixedvalue":"","assigntovalue":"","formulavalue":"","formulasequence":"","widget":"","overridetype":"","overridealignment":""},{"fieldid":"k41325a5afd6414f2a77db9447aea","sequence":"03","fieldname":"Fecha Pago","name":"Mes del pago","display":"yes","path":"root:stic_Payments::field:payment_date","displaypath":"stic_Payments","sort":"desc","sortpriority":2,"width":"100","jointype":"required","sqlfunction":"-","summaryfunction":"","customsqlfunction":"REFURV9GT1JNQVQlMjglN0J0JTdELiU3QmYlN0QlMkMlMjclMjVtJTI3JTI5","valuetype":"","groupby":"yes","link":"no","editable":"","fixedvalue":"","assigntovalue":"","formulavalue":"","formulasequence":"","widget":"","overridetype":"","overridealignment":""},{"fieldid":"kf1ce0dc27d16b96024bf8a946f2b","sequence":"04","fieldname":"Fecha de pago","name":"A\\u00f1o del pago","display":"yes","path":"root:stic_Payments::field:payment_date","displaypath":"stic_Payments","sort":"desc","sortpriority":1,"width":"100","jointype":"required","sqlfunction":"-","summaryfunction":"","customsqlfunction":"REFURV9GT1JNQVQlMjglN0J0JTdELiU3QmYlN0QlMkMlMjclMjVZJTI3JTI5","valuetype":"","groupby":"yes","link":"no","editable":"","fixedvalue":"","assigntovalue":"","formulavalue":"","formulasequence":"","widget":"","overridetype":"","overridealignment":""},{"fieldid":"k951abbffe4f6f80218e48d3e1d88","sequence":"05","fieldname":"ID","name":"N\\u00famero de registros de pago","display":"yes","path":"root:stic_Payments::field:id","displaypath":"stic_Payments","sort":"sortable","sortpriority":"","width":150,"jointype":"required","sqlfunction":"COUNT","summaryfunction":"","customsqlfunction":"","valuetype":"","groupby":"no","link":"no","editable":"","fixedvalue":"","assigntovalue":"","formulavalue":"","formulasequence":"","widget":"","overridetype":"","overridealignment":""},{"fieldid":"kb1b457cf6c19cc4b7a34fb43f0a2","sequence":"06","fieldname":"Importe","name":"Importe total pagos","display":"yes","path":"root:stic_Payments::field:amount","displaypath":"stic_Payments","sort":"sortable","sortpriority":"","width":"100","jointype":"required","sqlfunction":"SUM","summaryfunction":"","customsqlfunction":"","valuetype":"","groupby":"no","link":"no","editable":"","fixedvalue":"","assigntovalue":"","formulavalue":"","formulasequence":"","widget":"","overridetype":"","overridealignment":""}]', NULL, NULL),
('831dd2df-23f6-bd76-4b3c-5d9215caa2d1', 'Exemple - Duplicates - 06 - Duplicate contacts and leads by email', '2020-07-04 10:16:15', '2020-07-04 10:16:15', '2', '1', NULL, 0, '1', 'Leads', NULL, NULL, '{"authCheck":"full","showDeleted":false,"optionsFolded":"collapsed","resultsFolded":"open","updateOnRequest":false,"showSnapshots":false,"showTools":true}', NULL, NULL, NULL, '{"plugin":"standard","pluginData":{}}', '{"1":{"googlecharts":{"uid":"k6b44652f5fedc7b28a9b7cdb7e1c","dims":"111","type":"Pie","dimensions":{"dimension1":"k9f6e8e1919c3c61492b99b18b5b5"},"dataseries":[{"fieldid":"k9f6e8e1919c3c61492b99b18b5b5","name":"\\u00bfDuplicado en Personas?","chartfunction":"SUM","meaning":"value","axis":"P","renderer":"bars"}],"options":{"legend":true},"title":"Porcentaje duplicados"},"plugin":"googlecharts"},"plugin":"googlecharts","layout":"1x1","chartheight":300,"height":"300"}', '{"activePlugins":{"kcsvexport":"1","ktargetlistexport":"0","ksnapshots":"0"}}', '[{"unionid":"root","id":"root","group":"root","type":"AND","parent":"-","notexists":""}]', '[{"unionid":"root","sequence":"","fieldid":"k885f425c73bc5f5087b1fffdac04","name":"Estado:","fixedvalue":"","groupid":"root","path":"root:Leads::field:status","displaypath":"Leads","referencefieldid":"","operator":"ignore","type":"enum","value":"","valuekey":"","valueto":"","valuetokey":"","jointype":"required","context":"","reference":"","include":"","usereditable":"yes","dashleteditable":"no","exportpdf":"no","customsqlfunction":""}]', '[{"fieldid":"k26a452791b2c36f1ee14259d3fcc","sequence":"02","fieldname":"Apellidos:","name":"Nombre completo del Interesado","display":"yes","path":"root:Leads::field:last_name","displaypath":"Leads","sort":"-","sortpriority":"","width":150,"jointype":"optional","sqlfunction":"-","summaryfunction":"","customsqlfunction":"U0VMRUNUJTIwQ09OQ0FUJTI4JTdCdCU3RC5maXJzdF9uYW1lJTJDJTI3JTIwJTI3JTJDJTdCdCU3RC4lN0JmJTdEJTI5","valuetype":"","groupby":"no","link":"yes","editable":"","fixedvalue":"","assigntovalue":"","formulavalue":"","formulasequence":"","widget":"","overridetype":"","overridealignment":""},{"fieldid":"k64b4130688d05242197b85ad033d","sequence":"04","fieldname":"Direcci\\u00f3n de Email","name":"Correo electr\\u00f3nico en Interesado","display":"yes","path":"root:Leads::link:Leads:email_addresses_primary::field:email_address","displaypath":"Leads::Direcci\\u00f3n de Email","sort":"desc","sortpriority":2,"width":250,"jointype":"required","sqlfunction":"-","summaryfunction":"","customsqlfunction":"","valuetype":"","groupby":"yes","link":"no","editable":"","fixedvalue":"","assigntovalue":"emaili","formulavalue":"","formulasequence":"","widget":"","overridetype":"","overridealignment":""},{"fieldid":"kb2e4d1765705508d2d7c856368d4","sequence":"05","fieldname":"Direcci\\u00f3n de Email","name":"Correo electr\\u00f3nico en Personas","display":"yes","path":"root:Leads::link:Leads:email_addresses_primary::field:email_address","displaypath":"Leads::Direcci\\u00f3n de Email","sort":"desc","sortpriority":1,"width":250,"jointype":"required","sqlfunction":"-","summaryfunction":"","customsqlfunction":"U0VMRUNUJTIwZW1haWxfYWRkcmVzcyUyMEZST00lMjBlbWFpbF9hZGRyZXNzZXMlMjBJTk5FUiUyMEpPSU4lMjBlbWFpbF9hZGRyX2JlYW5fcmVsJTIwT04lMjBlbWFpbF9hZGRyZXNzZXMuaWQlM0RlbWFpbF9hZGRyX2JlYW5fcmVsLmVtYWlsX2FkZHJlc3NfaWQlMjAlMjBBTkQlMjBlbWFpbF9hZGRyX2JlYW5fcmVsLmRlbGV0ZWQlM0QwJTIwV0hFUkUlMjBlbWFpbF9hZGRyZXNzZXMuZGVsZXRlZCUzRDAlMjBBTkQlMjBlbWFpbF9hZGRyX2JlYW5fcmVsLmJlYW5fbW9kdWxlJTNEJTI3Q29udGFjdHMlMjclMjBBTkQlMjBlbWFpbF9hZGRyZXNzJTNEJTdCdCU3RC4lN0JmJTdEJTIwTElNSVQlMjAx","valuetype":"","groupby":"no","link":"no","editable":"","fixedvalue":"","assigntovalue":"emailp","formulavalue":"","formulasequence":"","widget":"","overridetype":"","overridealignment":""},{"fieldid":"k7e04a58a5323e4fe86e2824d1fc3","sequence":"06","fieldname":"Estado:","name":"Estado","display":"yes","path":"root:Leads::field:status","displaypath":"Leads","sort":"-","sortpriority":"","width":"100","jointype":"optional","sqlfunction":"-","summaryfunction":"","customsqlfunction":"","valuetype":"","groupby":"no","link":"no","editable":"","fixedvalue":"","assigntovalue":"","formulavalue":"","formulasequence":"","widget":"","overridetype":"","overridealignment":""},{"fieldid":"k9f6e8e1919c3c61492b99b18b5b5","sequence":"07","fieldname":"","name":"\\u00bfDuplicado en Personas?","display":"yes","path":"","displaypath":"","sort":"-","sortpriority":null,"width":150,"jointype":"required","sqlfunction":"-","summaryfunction":"","customsqlfunction":"","valuetype":"","groupby":"no","link":"no","editable":"","fixedvalue":"","assigntovalue":"","formulavalue":"JTdCZW1haWxpJTdEJTNEJTNEJTdCZW1haWxwJTdEJTNGJTI3U2klMjclM0ElMjdObyUyNw==","formulasequence":"","widget":"","overridetype":"","overridealignment":""}]', NULL, NULL),
('9d7e9d5f-2d00-1963-d604-5d8e1220fc45', 'Exemple - Duplicates - 05 - Duplicate contacts and leads by identification number', '2020-07-04 10:16:15', '2020-07-04 10:16:15', '2', '1', NULL, 0, '1', 'Leads', NULL, NULL, '{"resultsFolded":false,"optionsFolded":true,"authCheck":"full","showDeleted":false,"showExport":true,"showSnapshots":false,"showTools":true}', NULL, NULL, NULL, '{"plugin":"standard","pluginData":{}}', '{"1":{"googlecharts":{"uid":"k4afeaca9b79855a5f49674a8b9be","dims":"111","type":"Column","dimensions":{"dimension1":"kfbc3fa65dbb2ece50c6bcfd5771d"},"dataseries":[{"fieldid":"kfbc3fa65dbb2ece50c6bcfd5771d","name":"\\u00bfDuplicado en Personas?","chartfunction":"SUM","meaning":"value","axis":"P","renderer":"bars"}],"options":{"legend":true},"title":"Duplicados"},"plugin":"googlecharts"},"2":{"plugin":"googlecharts","googlecharts":{"uid":"kad3398d735ab10d45d1955c57913","dims":"111","type":"Pie","dimensions":{"dimension1":"kfbc3fa65dbb2ece50c6bcfd5771d"},"dataseries":[{"fieldid":"kfbc3fa65dbb2ece50c6bcfd5771d","name":"\\u00bfDuplicado en Personas?","chartfunction":"SUM","meaning":"value","axis":"P","renderer":"bars"}],"options":{"legend":true},"title":"Duplicados %"}},"plugin":"googlecharts","layout":"1x1","chartheight":300,"height":"200"}', '{"activePlugins":{"kcsvexport":"1","ktargetlistexport":"0","ksnapshots":"0"}}', '[{"unionid":"root","id":"root","group":"root","type":"AND","parent":"-","notexists":""}]', '[{"unionid":"root","sequence":"","fieldid":"kf68443ec4561cddb911a7ff6a2b4","name":"N\\u00famero de identificaci\\u00f3n","fixedvalue":"","groupid":"root","path":"root:Leads::field:stic_identification_number_c","displaypath":"Leads","referencefieldid":"","operator":"isnotempty","type":"varchar","value":"","valuekey":"","valueto":"","valuetokey":"","jointype":"required","context":"","reference":"","include":"","usereditable":"yes","dashleteditable":"no","exportpdf":"no","customsqlfunction":""},{"unionid":"root","sequence":"","fieldid":"k5e8767e665640ad41f49e3bb8899","name":"Estado:","fixedvalue":"","groupid":"root","path":"root:Leads::field:status","displaypath":"Leads","referencefieldid":"","operator":"ignore","type":"enum","value":"","valuekey":"","valueto":"","valuetokey":"","jointype":"required","context":"","reference":"","include":"","usereditable":"yes","dashleteditable":"no","exportpdf":"no","customsqlfunction":""}]', '[{"fieldid":"k2fdf9b61e54c25f48c1b9d6a5596","sequence":"03","fieldname":"Apellidos:","name":"Nombre completo Interesado","display":"yes","path":"root:Leads::field:last_name","displaypath":"Leads","sort":"-","sortpriority":"","width":150,"jointype":"required","sqlfunction":"-","summaryfunction":"","customsqlfunction":"U0VMRUNUJTIwQ09OQ0FUJTI4JTdCdCU3RC5maXJzdF9uYW1lJTJDJTIwJTI3JTIwJTI3JTJDJTIwJTdCdCU3RC4lN0JmJTdEJTI5","valuetype":"","groupby":"no","link":"yes","editable":"","fixedvalue":"","assigntovalue":"","formulavalue":"","formulasequence":"","widget":"","overridetype":"","overridealignment":""},{"fieldid":"k19b81f8857be02376994c87fb5c2","sequence":"05","fieldname":"N\\u00famero de identificaci\\u00f3n","name":"NI en Interesados","display":"yes","path":"root:Leads::field:stic_identification_number_c","displaypath":"Leads","sort":"asc","sortpriority":2,"width":120,"jointype":"required","sqlfunction":"-","summaryfunction":"","customsqlfunction":"","valuetype":"","groupby":"no","link":"no","editable":"","fixedvalue":"","assigntovalue":"numid","formulavalue":"","formulasequence":"","widget":"","overridetype":"","overridealignment":""},{"fieldid":"kf06050296b02cf9ff92393570f9c","sequence":"06","fieldname":"ID","name":"NI en Personas","display":"yes","path":"root:Leads::field:id","displaypath":"Leads","sort":"-","sortpriority":null,"width":100,"jointype":"required","sqlfunction":"-","summaryfunction":"","customsqlfunction":"U0VMRUNUJTIwY29udGFjdHNfY3N0bS5zdGljX2lkZW50aWZpY2F0aW9uX251bWJlcl9jJTIwRlJPTSUyMGxlYWRzJTIwSU5ORVIlMjBKT0lOJTIwbGVhZHNfY3N0bSUyME9OJTIwbGVhZHMuaWQlM0RsZWFkc19jc3RtLmlkX2MlMjBJTk5FUiUyMEpPSU4lMjBjb250YWN0c19jc3RtJTIwT04lMjBsZWFkc19jc3RtLnN0aWNfaWRlbnRpZmljYXRpb25fbnVtYmVyX2MlM0Rjb250YWN0c19jc3RtLnN0aWNfaWRlbnRpZmljYXRpb25fbnVtYmVyX2MlMjBJTk5FUiUyMEpPSU4lMjBjb250YWN0cyUyME9OJTIwY29udGFjdHNfY3N0bS5pZF9jJTNEY29udGFjdHMuaWQlMjBXSEVSRSUyMGxlYWRzLmlkJTNEJTdCdCU3RC4lN0JmJTdEJTIwQU5EJTIwY29udGFjdHMuZGVsZXRlZCUzRDAlMjBBTkQlMjBsZWFkcy5kZWxldGVkJTNEMCUyMExJTUlUJTIwMQ==","valuetype":"","groupby":"no","link":"no","editable":"","fixedvalue":"","assigntovalue":"numidin","formulavalue":"","formulasequence":"","widget":"","overridetype":"","overridealignment":""},{"fieldid":"kb5b08bd2310371795e983f970d18","sequence":"07","fieldname":"Estado:","name":"Estado","display":"yes","path":"root:Leads::field:status","displaypath":"Leads","sort":"-","sortpriority":"","width":"100","jointype":"optional","sqlfunction":"-","summaryfunction":"","customsqlfunction":"","valuetype":"","groupby":"no","link":"no","editable":"","fixedvalue":"","assigntovalue":"","formulavalue":"","formulasequence":"","widget":"","overridetype":"","overridealignment":""},{"fieldid":"kfbc3fa65dbb2ece50c6bcfd5771d","sequence":"08","fieldname":"","name":"\\u00bfDuplicado en Personas?","display":"yes","path":"","displaypath":"","sort":"-","sortpriority":null,"width":150,"jointype":"required","sqlfunction":"-","summaryfunction":"","customsqlfunction":"","valuetype":"","groupby":"no","link":"no","editable":"","fixedvalue":"","assigntovalue":"","formulavalue":"JTdCbnVtaWQlN0QlM0QlM0QlN0JudW1pZGluJTdEJTNGJTI3U2klMjclM0ElMjdObyUyNyUzQg==","formulasequence":"","widget":"","overridetype":"","overridealignment":""}]', NULL, NULL),
('a2e4faff-d837-9268-f4c5-5da5fdda0fa0', 'Exemple - Events - 03 - Sessions and attendances control (creation and validation)', '2020-07-04 10:16:15', '2020-07-04 10:16:15', '1', '1', NULL, 0, '1', 'stic_Events', '3', NULL, '{"resultsFolded":false,"optionsFolded":true,"authCheck":"full","showDeleted":false,"showExport":true,"showSnapshots":false,"showTools":true}', NULL, '{"standardViewProperties":{"listEntries":50}}', NULL, '{"plugin":"standard","pluginData":{}}', '{"1":{"googlecharts":{"uid":"ka080ce5cf709d6ec46e76bd3cb00","dims":"111","type":"Bar","dimensions":{"dimension1":null},"dataseries":[{"fieldid":"kf29beac1b796e5cecc2dd6368c8f","name":"Inscripciones","chartfunction":"SUM","meaning":"value","axis":"P","renderer":"bars"}],"options":{"is3D":"on"},"title":"TOTAL INSCRIPCIONES"},"plugin":"googlecharts"},"2":{"plugin":"googlecharts","googlecharts":{"uid":"k1e662efb9b07153977bd87c39f44","dims":"111","type":"Bar","dimensions":{"dimension1":null},"dataseries":[{"fieldid":"k1397a9ab6ba1e064b7678c9f78db","name":"Asistencias totales","chartfunction":"SUM","meaning":"value","axis":"P","renderer":"bars"}],"options":{"is3D":"on"},"title":"TOTAL ASISTENCIAS"}},"3":{"plugin":"googlecharts","googlecharts":{"uid":"kec2de6a1a4770646798b5137535d","dims":"111","type":"Bar","dimensions":{"dimension1":null},"dataseries":[{"fieldid":"k6365f85428a9c9ec1b78b9ff148a","name":"Asistencias validadas","chartfunction":"SUM","meaning":"value","axis":"P","renderer":"bars"}],"options":{"is3D":"on"},"title":"TOTAL ASISTENCIAS VALIDADAS"}},"4":{"plugin":"googlecharts","googlecharts":{"uid":"k518269eb0e2048c8256992eb5b0d","dims":"111","type":"Column","dimensions":{"dimension1":null},"dataseries":[{"fieldid":"k0018227fae1731d50279499849c4","name":"Evento","chartfunction":"SUM","meaning":"value","axis":"P","renderer":"bars"}],"options":{"is3D":"on"},"title":"TOTAL EVENTOS"}},"plugin":"googlecharts","layout":"1x4","chartheight":300,"height":"150"}', '{"activePlugins":{"kcsvexport":"1","ktargetlistexport":"0","ksnapshots":"0"}}', '[{"unionid":"root","id":"root","group":"root","type":"AND","parent":"-","notexists":""}]', '[{"unionid":"root","sequence":"","fieldid":"k8b6bc15a7bac40ff1e7e03dcd134","name":"Evento","fixedvalue":"","groupid":"root","path":"root:stic_Events::field:name","displaypath":"stic_Events","referencefieldid":"","operator":"ignore","type":"name","value":"","valuekey":"","valueto":"","valuetokey":"","jointype":"optional","context":"","reference":"","include":"","usereditable":"yes","dashleteditable":"no","exportpdf":"no","customsqlfunction":""},{"unionid":"root","sequence":"","fieldid":"kd8c645cbb81755910778d20dd384","name":"Estado Inscripci\\u00f3n","fixedvalue":"","groupid":"root","path":"root:stic_Events::link:stic_Events:stic_registrations_stic_events::field:status","displaypath":"stic_Events::Inscripciones","referencefieldid":"","operator":"oneof","type":"enum","value":"Confirmado, Participa","valuekey":["confirmed","participates"],"valueto":"","valuetokey":"","jointype":"required","context":"","reference":"","include":"","usereditable":"yes","dashleteditable":"no","exportpdf":"no","customsqlfunction":""},{"unionid":"root","sequence":"","fieldid":"k477ce1eb3bfb973f2d8e1b669549","name":"Fecha Inscripci\\u00f3n","fixedvalue":"","groupid":"root","path":"root:stic_Events::link:stic_Events:stic_registrations_stic_events::field:registration_date","displaypath":"stic_Events::Inscripciones","referencefieldid":"","operator":"ignore","type":"datetimecombo","value":"","valuekey":"","valueto":"","valuetokey":"","jointype":"required","context":"","reference":"","include":"","usereditable":"yes","dashleteditable":"no","exportpdf":"no","customsqlfunction":""},{"unionid":"root","sequence":"","fieldid":"k775a2c09742bda55e66c3a8bd604","name":"Fecha de inicio Sesi\\u00f3n","fixedvalue":"","groupid":"root","path":"root:stic_Events::link:stic_Events:stic_sessions_stic_events::field:start_date","displaypath":"stic_Events::Sesi\\u00f3n","referencefieldid":"","operator":"ignore","type":"datetimecombo","value":"","valuekey":"","valueto":"","valuetokey":"","jointype":"required","context":"","reference":"","include":"","usereditable":"yes","dashleteditable":"no","exportpdf":"no","customsqlfunction":""}]', '[{"fieldid":"k0497073751389b712f4e084a103a","sequence":"01","fieldname":"ID","name":"ID Evento","display":"no","path":"root:stic_Events::field:id","displaypath":"stic_Events","sort":"sortable","sortpriority":"","width":"100","jointype":"required","sqlfunction":"-","summaryfunction":"","customsqlfunction":"","valuetype":"","groupby":"yes","link":"no","editable":"","fixedvalue":"","assigntovalue":"","formulavalue":"","formulasequence":"","widget":"","overridetype":"","overridealignment":""},{"fieldid":"k0018227fae1731d50279499849c4","sequence":"02","fieldname":"Nombre","name":"Evento","display":"yes","path":"root:stic_Events::field:name","displaypath":"stic_Events","sort":"sortable","sortpriority":"","width":200,"jointype":"required","sqlfunction":"-","summaryfunction":"","customsqlfunction":"","valuetype":"","groupby":"yes","link":"yes","editable":"","fixedvalue":"","assigntovalue":"","formulavalue":"","formulasequence":"","widget":"","overridetype":"","overridealignment":""},{"fieldid":"ked1e33fd46f842048a56b9080488","sequence":"03","fieldname":"ID","name":"ID","display":"no","path":"root:stic_Events::link:stic_Events:stic_sessions_stic_events::field:id","displaypath":"stic_Events::Sesi\\u00f3n","sort":"sortable","sortpriority":"","width":"100","jointype":"required","sqlfunction":"-","summaryfunction":"","customsqlfunction":"","valuetype":"","groupby":"yes","link":"no","editable":"","fixedvalue":"","assigntovalue":"","formulavalue":"","formulasequence":"","widget":"","overridetype":"","overridealignment":""},{"fieldid":"k1aae9889275e89b553a3f7761319","sequence":"04","fieldname":"Nombre (autom.)","name":"Sesi\\u00f3n","display":"yes","path":"root:stic_Events::link:stic_Events:stic_sessions_stic_events::field:name","displaypath":"stic_Events::Sesi\\u00f3n","sort":"sortable","sortpriority":"","width":250,"jointype":"required","sqlfunction":"-","summaryfunction":"","customsqlfunction":"","valuetype":"","groupby":"yes","link":"yes","editable":"","fixedvalue":"","assigntovalue":"","formulavalue":"","formulasequence":"","widget":"","overridetype":"","overridealignment":""},{"fieldid":"kf1ef2512184bf92754f47abdbc12","sequence":"05","fieldname":"Fecha de inicio","name":"Fecha de inicio","display":"yes","path":"root:stic_Events::link:stic_Events:stic_sessions_stic_events::field:start_date","displaypath":"stic_Events::Sesi\\u00f3n","sort":"sortable","sortpriority":"","width":150,"jointype":"required","sqlfunction":"-","summaryfunction":"","customsqlfunction":"","valuetype":"","groupby":"yes","link":"no","editable":"","fixedvalue":"","assigntovalue":"","formulavalue":"","formulasequence":"","widget":"","overridetype":"","overridealignment":""},{"fieldid":"kf29beac1b796e5cecc2dd6368c8f","sequence":"06","fieldname":"Nombre (autom.)","name":"Inscripciones","display":"yes","path":"root:stic_Events::link:stic_Events:stic_registrations_stic_events::field:name","displaypath":"stic_Events::Inscripciones","sort":"sortable","sortpriority":"","width":150,"jointype":"required","sqlfunction":"COUNT","summaryfunction":"","customsqlfunction":"","valuetype":"","groupby":"no","link":"no","editable":"","fixedvalue":"","assigntovalue":"ins","formulavalue":"","formulasequence":"","widget":"","overridetype":"","overridealignment":""},{"fieldid":"k1397a9ab6ba1e064b7678c9f78db","sequence":"08","fieldname":"Asistencias totales","name":"Asistencias totales","display":"yes","path":"root:stic_Events::link:stic_Events:stic_sessions_stic_events::field:total_attendances","displaypath":"stic_Events::Sesi\\u00f3n","sort":"sortable","sortpriority":"","width":150,"jointype":"optional","sqlfunction":"-","summaryfunction":"","customsqlfunction":"","valuetype":"","groupby":"no","link":"no","editable":"","fixedvalue":"","assigntovalue":"ast","formulavalue":"JTdCaW5zJTdEJTNFJTdCYXN0JTdEJTNGJTIwJTI3JTNDc3BhbiUyMHN0eWxlJTNEJTIyY29sb3IlM0FyZWQlM0IlMjIlM0UlMjcuJTdCYXN0JTdELiUyNyUzQy9zcGFuJTNFJTI3JTNBJTIwJTI3JTNDc3BhbiUyMHN0eWxlJTNEJTIyY29sb3IlM0FncmVlbiUzQiUyMiUzRSUyNy4lN0Jhc3QlN0QuJTI3JTNDL3NwYW4lM0UlMjc=","formulasequence":"","widget":"","overridetype":"","overridealignment":""},{"fieldid":"k6365f85428a9c9ec1b78b9ff148a","sequence":"09","fieldname":"Asistencias validadas","name":"Asistencias validadas","display":"yes","path":"root:stic_Events::link:stic_Events:stic_sessions_stic_events::field:validated_attendances","displaypath":"stic_Events::Sesi\\u00f3n","sort":"sortable","sortpriority":"","width":150,"jointype":"optional","sqlfunction":"-","summaryfunction":"","customsqlfunction":"","valuetype":"","groupby":"no","link":"no","editable":"","fixedvalue":"","assigntovalue":"asv","formulavalue":"JTdCaW5zJTdEJTNFJTdCYXN2JTdEJTNGJTIwJTI3JTNDc3BhbiUyMHN0eWxlJTNEJTIyY29sb3IlM0FyZWQlM0IlMjIlM0UlMjcuJTdCYXN2JTdELiUyNyUzQy9zcGFuJTNFJTI3JTNBJTIwJTI3JTNDc3BhbiUyMHN0eWxlJTNEJTIyY29sb3IlM0FncmVlbiUzQiUyMiUzRSUyNy4lN0Jhc3YlN0QuJTI3JTNDL3NwYW4lM0UlMjc=","formulasequence":"","widget":"","overridetype":"","overridealignment":""}]', NULL, NULL);
INSERT INTO `kreports` (`id`, `name`, `date_entered`, `date_modified`, `modified_user_id`, `created_by`, `description`, `deleted`, `assigned_user_id`, `report_module`, `report_status`, `union_modules`, `reportoptions`, `listtype`, `listtypeproperties`, `selectionlimit`, `presentation_params`, `visualization_params`, `integration_params`, `wheregroups`, `whereconditions`, `listfields`, `unionlistfields`, `advancedoptions`) VALUES
('a4e36707-584d-40c0-8b11-552cd7379d2b', 'Exemple - Contacts - 02 - Relationships with contacts basic data', '2020-07-04 10:16:15', '2020-07-04 10:16:15', '1', '1', NULL, 0, '1', 'stic_Contacts_Relationships', '3', NULL, '{"resultsFolded":false,"optionsFolded":true,"authCheck":"full","showDeleted":false,"showExport":true,"showSnapshots":false,"showTools":true}', NULL, NULL, NULL, '{"plugin":"standard","pluginData":{}}', '{"1":{"googlecharts":{"uid":"k995123db7448b0cd2613bece5afd","dims":"111","type":"Pie","dimensions":{"dimension1":"ka8dfaae95493b82f1576af60a127"},"dataseries":[{"fieldid":"kcde0da49fa01ee7b5fa15eca111b","name":"ID","chartfunction":"SUM","meaning":"value","axis":"P","renderer":"bars"}],"options":{"legend":true,"is3D":"on"},"title":"Relaci\\u00f3n Persona"},"plugin":"googlecharts"},"2":{"plugin":"googlecharts","googlecharts":{"uid":"k7b34416079253de28470992656c3","dims":"111","type":"Bar","dimensions":{"dimension1":"kc62f21f043b481885d6e0a514fbc"},"dataseries":[{"fieldid":"kcde0da49fa01ee7b5fa15eca111b","name":"ID","chartfunction":"SUM","meaning":"value","axis":"P","renderer":"bars"}],"options":{"legend":true,"is3D":"on"},"title":"Motivos de baja"}},"3":{"plugin":"googlecharts","googlecharts":{"uid":"k561facb06b5172501be60047902f","dims":"111","type":"Pie","dimensions":{"dimension1":"kf9be748f7427b2d3a89ed91679d7"},"dataseries":[{"fieldid":"kcde0da49fa01ee7b5fa15eca111b","name":"ID","chartfunction":"SUM","meaning":"value","axis":"P","renderer":"bars"}],"options":{"legend":true,"is3D":"on"},"title":"Proyecto"}},"plugin":"googlecharts","layout":"1x3","chartheight":300,"height":"300"}', '{"activePlugins":{"kcsvexport":"1","ktargetlistexport":"0","ksnapshots":"0"},"ksnapshots":null}', '[{"unionid":"root","id":"root","group":"root","type":"AND","parent":"-","notexists":""}]', '[{"unionid":"root","sequence":"","fieldid":"k5ee41e633aa55942767e76b2cd15","name":"Fecha de baja","fixedvalue":"","groupid":"root","path":"root:stic_Contacts_Relationships::field:end_date","displaypath":"stic_Contacts_Relationships","referencefieldid":"","operator":"ignore","type":"date","value":"","valuekey":"","valueto":"","valuetokey":"","jointype":"required","context":"","reference":"","include":"","usereditable":"yes","dashleteditable":"no","exportpdf":"no","customsqlfunction":""},{"unionid":"root","sequence":"","fieldid":"kfdad86c1f2b8c0221d62a0f802a5","name":"Fecha de alta","fixedvalue":"","groupid":"root","path":"root:stic_Contacts_Relationships::field:start_date","displaypath":"stic_Contacts_Relationships","referencefieldid":"","operator":"ignore","type":"date","value":"","valuekey":"","valueto":"","valuetokey":"","jointype":"required","context":"","reference":"","include":"","usereditable":"yes","dashleteditable":"no","exportpdf":"no","customsqlfunction":""},{"unionid":"root","sequence":"","fieldid":"k6d2fc4ec437116bbf8e869f0699a","name":"Tipo relaci\\u00f3n","fixedvalue":"","groupid":"root","path":"root:stic_Contacts_Relationships::field:relationship_type","displaypath":"stic_Contacts_Relationships","referencefieldid":"","operator":"ignore","type":"enum","value":"","valuekey":"","valueto":"","valuetokey":"","jointype":"required","context":"","reference":"","include":"","usereditable":"yes","dashleteditable":"no","exportpdf":"no","customsqlfunction":""},{"unionid":"root","sequence":"","fieldid":"kc2ebe4cfcf856527d76f64f031ef","name":"Motivo Baja","fixedvalue":"","groupid":"root","path":"root:stic_Contacts_Relationships::field:end_reason","displaypath":"stic_Contacts_Relationships","referencefieldid":"","operator":"ignore","type":"enum","value":"","valuekey":"","valueto":"","valuetokey":"","jointype":"required","context":"","reference":"","include":"","usereditable":"yes","dashleteditable":"no","exportpdf":"no","customsqlfunction":""}]', '[{"fieldid":"k190130c49319182879a83cd582fe","sequence":"01","fieldname":"Nombre (autom.)","name":"Rel.Persona","display":"yes","path":"root:stic_Contacts_Relationships::field:name","displaypath":"stic_Contacts_Relationships","sort":"sortable","sortpriority":"","width":250,"jointype":"required","sqlfunction":"-","summaryfunction":"","customsqlfunction":"","valuetype":"","groupby":"yes","link":"yes","editable":"","fixedvalue":"","assigntovalue":"","formulavalue":"","formulasequence":"","widget":"","overridetype":"","overridealignment":""},{"fieldid":"k0f9f3cc09ecc145ea01446320186","sequence":"02","fieldname":"Nombre:","name":"Nombre:","display":"yes","path":"root:stic_Contacts_Relationships::link:stic_Contacts_Relationships:stic_contacts_relationships_contacts::field:first_name","displaypath":"stic_Contacts_Relationships::Personas","sort":"sortable","sortpriority":"","width":"100","jointype":"optional","sqlfunction":"-","summaryfunction":"","customsqlfunction":"","valuetype":"","groupby":"no","link":"no","editable":"","fixedvalue":"","assigntovalue":"","formulavalue":"","formulasequence":"","widget":"","overridetype":"","overridealignment":""},{"fieldid":"k601bcf1f93c2c257740840e72d2e","sequence":"03","fieldname":"Apellidos:","name":"Apellidos:","display":"yes","path":"root:stic_Contacts_Relationships::link:stic_Contacts_Relationships:stic_contacts_relationships_contacts::field:last_name","displaypath":"stic_Contacts_Relationships::Personas","sort":"sortable","sortpriority":"","width":200,"jointype":"optional","sqlfunction":"-","summaryfunction":"","customsqlfunction":"","valuetype":"","groupby":"no","link":"yes","editable":"","fixedvalue":"","assigntovalue":"","formulavalue":"","formulasequence":"","widget":"","overridetype":"","overridealignment":""},{"fieldid":"kc62f21f043b481885d6e0a514fbc","sequence":"04","fieldname":"Motivo Baja","name":"Motivo Baja","display":"yes","path":"root:stic_Contacts_Relationships::field:end_reason","displaypath":"stic_Contacts_Relationships","sort":"sortable","sortpriority":"","width":150,"jointype":"optional","sqlfunction":"-","summaryfunction":"","customsqlfunction":"","valuetype":"","groupby":"no","link":"no","editable":"","fixedvalue":"","assigntovalue":"","formulavalue":"","formulasequence":"","widget":"","overridetype":"","overridealignment":""},{"fieldid":"ka8dfaae95493b82f1576af60a127","sequence":"05","fieldname":"Tipo relaci\\u00f3n","name":"Tipo relaci\\u00f3n","display":"yes","path":"root:stic_Contacts_Relationships::field:relationship_type","displaypath":"stic_Contacts_Relationships","sort":"sortable","sortpriority":"","width":"100","jointype":"optional","sqlfunction":"-","summaryfunction":"","customsqlfunction":"","valuetype":"","groupby":"no","link":"no","editable":"","fixedvalue":"","assigntovalue":"","formulavalue":"","formulasequence":"","widget":"","overridetype":"","overridealignment":""},{"fieldid":"kcde0da49fa01ee7b5fa15eca111b","sequence":"06","fieldname":"ID","name":"ID","display":"no","path":"root:stic_Contacts_Relationships::field:id","displaypath":"stic_Contacts_Relationships","sort":"-","sortpriority":"","width":"100","jointype":"optional","sqlfunction":"-","summaryfunction":"","customsqlfunction":"","valuetype":"","groupby":"no","link":"no","editable":"","fixedvalue":"","assigntovalue":"","formulavalue":"","formulasequence":"","widget":"","overridetype":"","overridealignment":""},{"fieldid":"kd5ae69785c5921426fc4a75ebd23","sequence":"07","fieldname":"Fecha Alta","name":"Fecha de alta","display":"yes","path":"root:stic_Contacts_Relationships::field:start_date","displaypath":"stic_Contacts_Relationships","sort":"sortable","sortpriority":"","width":"100","jointype":"optional","sqlfunction":"-","summaryfunction":"","customsqlfunction":"","valuetype":"","groupby":"no","link":"no","editable":"","fixedvalue":"","assigntovalue":"","formulavalue":"","formulasequence":"","widget":"","overridetype":"","overridealignment":""},{"fieldid":"k0515210f4fd867e82ba78858b1d2","sequence":"08","fieldname":"Fecha de baja","name":"Fecha de baja","display":"yes","path":"root:stic_Contacts_Relationships::field:end_date","displaypath":"stic_Contacts_Relationships","sort":"sortable","sortpriority":"","width":"100","jointype":"optional","sqlfunction":"-","summaryfunction":"","customsqlfunction":"","valuetype":"","groupby":"no","link":"no","editable":"","fixedvalue":"","assigntovalue":"","formulavalue":"","formulasequence":"","widget":"","overridetype":"","overridealignment":""},{"fieldid":"kf9be748f7427b2d3a89ed91679d7","sequence":"09","fieldname":"Nombre","name":"Proyecto","display":"yes","path":"root:stic_Contacts_Relationships::link:stic_Contacts_Relationships:stic_contacts_relationships_project::field:name","displaypath":"stic_Contacts_Relationships::Proyectos","sort":"sortable","sortpriority":"","width":200,"jointype":"optional","sqlfunction":"-","summaryfunction":"","customsqlfunction":"","valuetype":"","groupby":"no","link":"no","editable":"","fixedvalue":"","assigntovalue":"","formulavalue":"","formulasequence":"","widget":"","overridetype":"","overridealignment":""},{"fieldid":"k26e3dea2004eb2c4684d1044b1ea","sequence":10,"fieldname":"Edad","name":"Edad","display":"yes","path":"root:stic_Contacts_Relationships::link:stic_Contacts_Relationships:stic_contacts_relationships_contacts::field:stic_age_c","displaypath":"stic_Contacts_Relationships::Personas","sort":"sortable","sortpriority":"","width":"100","jointype":"optional","sqlfunction":"-","summaryfunction":"","customsqlfunction":"","valuetype":"","groupby":"no","link":"no","editable":"","fixedvalue":"","assigntovalue":"","formulavalue":"","formulasequence":"","widget":"","overridetype":"","overridealignment":""},{"fieldid":"k2d86339e6e6a7bbf84723707b372","sequence":11,"fieldname":"Sexo","name":"Sexo","display":"yes","path":"root:stic_Contacts_Relationships::link:stic_Contacts_Relationships:stic_contacts_relationships_contacts::field:stic_gender_c","displaypath":"stic_Contacts_Relationships::Personas","sort":"sortable","sortpriority":"","width":"100","jointype":"optional","sqlfunction":"-","summaryfunction":"","customsqlfunction":"","valuetype":"","groupby":"no","link":"no","editable":"","fixedvalue":"","assigntovalue":"","formulavalue":"","formulasequence":"","widget":"","overridetype":"","overridealignment":""},{"fieldid":"ka5c90b0b4a9c5b0ce1f205c0941f","sequence":12,"fieldname":"Nombre","name":"Nombre","display":"yes","path":"root:stic_Contacts_Relationships::link:stic_Contacts_Relationships:stic_contacts_relationships_project::field:name","displaypath":"stic_Contacts_Relationships::Proyectos","sort":"-","sortpriority":"","width":"100","jointype":"optional","sqlfunction":"-","summaryfunction":"","customsqlfunction":"","valuetype":"","groupby":"no","link":"no","editable":"","fixedvalue":"","assigntovalue":"","formulavalue":"","formulasequence":"","widget":"","overridetype":"","overridealignment":""}]', NULL, NULL),
('aa1d0703-f55b-7463-32cd-5da0577397c3', 'Exemple - Campaigns - 01 - Campaigns basic data', '2020-07-04 10:16:15', '2020-07-04 10:16:15', '1', '1', NULL, 0, '1', 'Campaigns', NULL, NULL, '{"resultsFolded":false,"optionsFolded":true,"authCheck":"full","showDeleted":false,"showExport":true,"showSnapshots":false,"showTools":true}', NULL, '{"standardViewProperties":{"listEntries":50}}', NULL, '{"plugin":"standard","pluginData":{}}', '{"1":{"googlecharts":{"uid":"ke03b8905a955e6f5d8509e65355a","dims":"111","type":"Bar","dimensions":{"dimension1":null},"dataseries":[{"fieldid":"k9c6dc3065173045e3ec23cd8f50a","name":"ID","chartfunction":"SUM","meaning":"value","axis":"P","renderer":"bars"}],"options":{"is3D":"on"},"title":"N\\u00daMERO DE CAMPA\\u00d1AS"},"plugin":"googlecharts"},"2":{"plugin":"googlecharts","googlecharts":{"uid":"k31fc4902a53b6204e7b1efcd8914","dims":"111","type":"Pie","dimensions":{"dimension1":"kc65aa3fde29267137c0b75029288"},"dataseries":[{"fieldid":"k9c6dc3065173045e3ec23cd8f50a","name":"ID","chartfunction":"SUM","meaning":"value","axis":"P","renderer":"bars"}],"options":{"legend":true,"is3D":"on"},"title":"CAMPA\\u00d1AS POR ESTADO CAMPA\\u00d1A"}},"3":{"plugin":"googlecharts","googlecharts":{"uid":"k50dedd9b265eb63c745315670622","dims":"111","type":"Bar","dimensions":{"dimension1":"ke6b4c7aedbbadf3ef4f8ff756191"},"dataseries":[{"fieldid":"k677361200356f8209eb30c4ec82d","name":"Emails enviados","chartfunction":"SUM","meaning":"value","axis":"P","renderer":"bars"}],"options":{"is3D":"on"},"title":"TOTAL ENV\\u00cdOS"}},"4":{"plugin":"googlecharts","googlecharts":{"uid":"k2d2fda04791450096c4531864bb9","dims":"111","type":"Bar","dimensions":{"dimension1":"ke6b4c7aedbbadf3ef4f8ff756191"},"dataseries":[{"fieldid":"ka673f2ac6c3adf950c36b41ccc36","name":"Enlaces","chartfunction":"SUM","meaning":"value","axis":"P","renderer":"bars"}],"options":{},"title":"TOTAL ENLACES"}},"5":{"plugin":"googlecharts","googlecharts":{"uid":"kc77f8f9dabddeb350472fcce763f","dims":"111","type":"Pie","dimensions":{"dimension1":"ke6b4c7aedbbadf3ef4f8ff756191"},"dataseries":[{"fieldid":"kf6338b9527ef5a9a2961ddb2876b","name":"Vistos","chartfunction":"SUM","meaning":"value","axis":"P","renderer":"bars"}],"options":{"legend":true,"is3D":"on"},"title":"PORCENTAJE VISTOS"}},"plugin":"googlecharts","layout":"2x1x4","chartheight":300,"height":"300"}', '{"activePlugins":{"kcsvexport":"1","ktargetlistexport":"0","ksnapshots":"0"}}', '[{"unionid":"root","id":"root","group":"root","type":"AND","parent":"-","notexists":""}]', '[{"unionid":"root","sequence":"","fieldid":"k3d430731adb81d3e5763ce70499f","name":"Estado:","fixedvalue":"","groupid":"root","path":"root:Campaigns::field:status","displaypath":"Campaigns","referencefieldid":"","operator":"ignore","type":"enum","value":"","valuekey":"","valueto":"","valuetokey":"","jointype":"required","context":"","reference":"","include":"","usereditable":"yes","dashleteditable":"no","exportpdf":"no","customsqlfunction":""},{"unionid":"root","sequence":"","fieldid":"k27ba448259e241031f75c0035c62","name":"Fecha Inicio:","fixedvalue":"","groupid":"root","path":"root:Campaigns::field:start_date","displaypath":"Campaigns","referencefieldid":"","operator":"ignore","type":"date","value":"","valuekey":"","valueto":"","valuetokey":"","jointype":"required","context":"","reference":"","include":"","usereditable":"yes","dashleteditable":"no","exportpdf":"no","customsqlfunction":""},{"unionid":"root","sequence":"","fieldid":"kf79f3dfb9f92be9d99f8fabbdcdf","name":"Nombre campa\\u00f1a","fixedvalue":"","groupid":"root","path":"root:Campaigns::field:name","displaypath":"Campaigns","referencefieldid":"","operator":"ignore","type":"name","value":"","valuekey":"","valueto":"","valuetokey":"","jointype":"required","context":"","reference":"","include":"","usereditable":"yes","dashleteditable":"no","exportpdf":"no","customsqlfunction":""}]', '[{"fieldid":"k9c6dc3065173045e3ec23cd8f50a","sequence":"01","fieldname":"ID","name":"ID","display":"no","path":"root:Campaigns::field:id","displaypath":"Campaigns","sort":"-","sortpriority":"","width":"100","jointype":"required","sqlfunction":"-","summaryfunction":"","customsqlfunction":"","valuetype":"","groupby":"yes","link":"no","editable":"","fixedvalue":"","assigntovalue":"","formulavalue":"","formulasequence":"","widget":"","overridetype":"","overridealignment":""},{"fieldid":"ke6b4c7aedbbadf3ef4f8ff756191","sequence":"02","fieldname":"Nombre:","name":"Nombre:","display":"yes","path":"root:Campaigns::field:name","displaypath":"Campaigns","sort":"asc","sortpriority":2,"width":200,"jointype":"required","sqlfunction":"-","summaryfunction":"","customsqlfunction":"","valuetype":"","groupby":"no","link":"yes","editable":"","fixedvalue":"","assigntovalue":"","formulavalue":"","formulasequence":"","widget":"","overridetype":"","overridealignment":""},{"fieldid":"k75a65b618ec13a1db15055387f02","sequence":"03","fieldname":"Fecha Inicio:","name":"Fecha Inicio:","display":"yes","path":"root:Campaigns::field:start_date","displaypath":"Campaigns","sort":"-","sortpriority":"","width":"100","jointype":"optional","sqlfunction":"-","summaryfunction":"","customsqlfunction":"","valuetype":"","groupby":"no","link":"no","editable":"","fixedvalue":"","assigntovalue":"","formulavalue":"","formulasequence":"","widget":"","overridetype":"","overridealignment":""},{"fieldid":"k2811f5c65d0806ac9f337f5d8813","sequence":"04","fieldname":"Fecha Fin:","name":"Fecha Fin:","display":"yes","path":"root:Campaigns::field:end_date","displaypath":"Campaigns","sort":"-","sortpriority":"","width":"100","jointype":"optional","sqlfunction":"-","summaryfunction":"","customsqlfunction":"","valuetype":"","groupby":"no","link":"no","editable":"","fixedvalue":"","assigntovalue":"","formulavalue":"","formulasequence":"","widget":"","overridetype":"","overridealignment":""},{"fieldid":"kc65aa3fde29267137c0b75029288","sequence":"05","fieldname":"Estado:","name":"Estado:","display":"yes","path":"root:Campaigns::field:status","displaypath":"Campaigns","sort":"-","sortpriority":null,"width":"100","jointype":"optional","sqlfunction":"-","summaryfunction":"","customsqlfunction":"","valuetype":"","groupby":"no","link":"no","editable":"","fixedvalue":"","assigntovalue":"","formulavalue":"","formulasequence":"","widget":"","overridetype":"","overridealignment":""},{"fieldid":"k633057cd09e5d57348eb722f2395","sequence":"06","fieldname":"Tipo:","name":"Tipo:","display":"yes","path":"root:Campaigns::field:campaign_type","displaypath":"Campaigns","sort":"-","sortpriority":"","width":"100","jointype":"optional","sqlfunction":"-","summaryfunction":"","customsqlfunction":"","valuetype":"","groupby":"no","link":"no","editable":"","fixedvalue":"","assigntovalue":"","formulavalue":"","formulasequence":"","widget":"","overridetype":"","overridealignment":""},{"fieldid":"kb784c71f7bccbbba48bfa1715e19","sequence":"07","fieldname":"ID","name":"Total personas","display":"yes","path":"root:Campaigns::field:id","displaypath":"Campaigns","sort":"-","sortpriority":"","width":"100","jointype":"required","sqlfunction":"-","summaryfunction":"","customsqlfunction":"U0VMRUNUJTIwQ09VTlQlMjhwcm9zcGVjdF9saXN0c19wcm9zcGVjdHMucmVsYXRlZF9pZCUyOSUyMEZST00lMjBwcm9zcGVjdF9saXN0cyUyMElOTkVSJTIwSk9JTiUyMHByb3NwZWN0X2xpc3RfY2FtcGFpZ25zJTIwT04lMjBwcm9zcGVjdF9saXN0cy5pZCUzRHByb3NwZWN0X2xpc3RfY2FtcGFpZ25zLnByb3NwZWN0X2xpc3RfaWQlMjBBTkQlMjBwcm9zcGVjdF9saXN0X2NhbXBhaWducy5kZWxldGVkJTNEMCUyMElOTkVSJTIwSk9JTiUyMHByb3NwZWN0X2xpc3RzX3Byb3NwZWN0cyUyME9OJTIwcHJvc3BlY3RfbGlzdHMuaWQlM0Rwcm9zcGVjdF9saXN0c19wcm9zcGVjdHMucHJvc3BlY3RfbGlzdF9pZCUyMEFORCUyMHByb3NwZWN0X2xpc3RzX3Byb3NwZWN0cy5kZWxldGVkJTNEMCUyMFdIRVJFJTIwcHJvc3BlY3RfbGlzdF9jYW1wYWlnbnMuY2FtcGFpZ25faWQlM0QlN0J0JTdELiU3QmYlN0QlMjBBTkQlMjBwcm9zcGVjdF9saXN0c19wcm9zcGVjdHMucmVsYXRlZF90eXBlJTNEJTI3Q29udGFjdHMlMjclMjBBTkQlMjBwcm9zcGVjdF9saXN0cy5kZWxldGVkJTNEMA==","valuetype":"","groupby":"no","link":"no","editable":"","fixedvalue":"","assigntovalue":"","formulavalue":"","formulasequence":"","widget":"","overridetype":"","overridealignment":""},{"fieldid":"kfe6beecfc709eeca6af2cdba4634","sequence":"08","fieldname":"ID","name":"Total organizaciones","display":"yes","path":"root:Campaigns::field:id","displaypath":"Campaigns","sort":"-","sortpriority":"","width":"100","jointype":"required","sqlfunction":"-","summaryfunction":"","customsqlfunction":"U0VMRUNUJTIwQ09VTlQlMjhwcm9zcGVjdF9saXN0c19wcm9zcGVjdHMucmVsYXRlZF9pZCUyOSUyMEZST00lMjBwcm9zcGVjdF9saXN0cyUyMElOTkVSJTIwSk9JTiUyMHByb3NwZWN0X2xpc3RfY2FtcGFpZ25zJTIwT04lMjBwcm9zcGVjdF9saXN0cy5pZCUzRHByb3NwZWN0X2xpc3RfY2FtcGFpZ25zLnByb3NwZWN0X2xpc3RfaWQlMjBBTkQlMjBwcm9zcGVjdF9saXN0X2NhbXBhaWducy5kZWxldGVkJTNEMCUyMElOTkVSJTIwSk9JTiUyMHByb3NwZWN0X2xpc3RzX3Byb3NwZWN0cyUyME9OJTIwcHJvc3BlY3RfbGlzdHMuaWQlM0Rwcm9zcGVjdF9saXN0c19wcm9zcGVjdHMucHJvc3BlY3RfbGlzdF9pZCUyMEFORCUyMHByb3NwZWN0X2xpc3RzX3Byb3NwZWN0cy5kZWxldGVkJTNEMCUyMFdIRVJFJTIwcHJvc3BlY3RfbGlzdF9jYW1wYWlnbnMuY2FtcGFpZ25faWQlM0QlN0J0JTdELiU3QmYlN0QlMjBBTkQlMjBwcm9zcGVjdF9saXN0c19wcm9zcGVjdHMucmVsYXRlZF90eXBlJTNEJTI3QWNjb3VudHMlMjclMjBBTkQlMjBwcm9zcGVjdF9saXN0cy5kZWxldGVkJTNEMA==","valuetype":"","groupby":"no","link":"no","editable":"","fixedvalue":"","assigntovalue":"","formulavalue":"","formulasequence":"","widget":"","overridetype":"","overridealignment":""},{"fieldid":"k7ff902372e176ec8cfbb46673271","sequence":"09","fieldname":"ID","name":"Total interesados","display":"yes","path":"root:Campaigns::field:id","displaypath":"Campaigns","sort":"-","sortpriority":"","width":"100","jointype":"required","sqlfunction":"-","summaryfunction":"","customsqlfunction":"U0VMRUNUJTIwQ09VTlQlMjhwcm9zcGVjdF9saXN0c19wcm9zcGVjdHMucmVsYXRlZF9pZCUyOSUyMEZST00lMjBwcm9zcGVjdF9saXN0cyUyMElOTkVSJTIwSk9JTiUyMHByb3NwZWN0X2xpc3RfY2FtcGFpZ25zJTIwT04lMjBwcm9zcGVjdF9saXN0cy5pZCUzRHByb3NwZWN0X2xpc3RfY2FtcGFpZ25zLnByb3NwZWN0X2xpc3RfaWQlMjBBTkQlMjBwcm9zcGVjdF9saXN0X2NhbXBhaWducy5kZWxldGVkJTNEMCUyMElOTkVSJTIwSk9JTiUyMHByb3NwZWN0X2xpc3RzX3Byb3NwZWN0cyUyME9OJTIwcHJvc3BlY3RfbGlzdHMuaWQlM0Rwcm9zcGVjdF9saXN0c19wcm9zcGVjdHMucHJvc3BlY3RfbGlzdF9pZCUyMEFORCUyMHByb3NwZWN0X2xpc3RzX3Byb3NwZWN0cy5kZWxldGVkJTNEMCUyMFdIRVJFJTIwcHJvc3BlY3RfbGlzdF9jYW1wYWlnbnMuY2FtcGFpZ25faWQlM0QlN0J0JTdELiU3QmYlN0QlMjBBTkQlMjBwcm9zcGVjdF9saXN0c19wcm9zcGVjdHMucmVsYXRlZF90eXBlJTNEJTI3TGVhZHMlMjclMjBBTkQlMjBwcm9zcGVjdF9saXN0cy5kZWxldGVkJTNEMA==","valuetype":"","groupby":"no","link":"no","editable":"","fixedvalue":"","assigntovalue":"","formulavalue":"","formulasequence":"","widget":"","overridetype":"","overridealignment":""},{"fieldid":"k7e3d2b3d76c0d96dffb60924c4f9","sequence":10,"fieldname":"ID","name":"Total LPOs","display":"yes","path":"root:Campaigns::field:id","displaypath":"Campaigns","sort":"-","sortpriority":"","width":"100","jointype":"required","sqlfunction":"-","summaryfunction":"","customsqlfunction":"U0VMRUNUJTIwQ09VTlQlMjhwcm9zcGVjdF9saXN0cy5pZCUyOSUyMEZST00lMjBwcm9zcGVjdF9saXN0cyUyMElOTkVSJTIwSk9JTiUyMHByb3NwZWN0X2xpc3RfY2FtcGFpZ25zJTIwT04lMjBwcm9zcGVjdF9saXN0cy5pZCUzRHByb3NwZWN0X2xpc3RfY2FtcGFpZ25zLnByb3NwZWN0X2xpc3RfaWQlMjBXSEVSRSUyMHByb3NwZWN0X2xpc3RfY2FtcGFpZ25zLmNhbXBhaWduX2lkJTNEJTdCdCU3RC4lN0JmJTdEJTIwQU5EJTIwcHJvc3BlY3RfbGlzdHMuZGVsZXRlZCUzRDA=","valuetype":"","groupby":"no","link":"no","editable":"","fixedvalue":"","assigntovalue":"","formulavalue":"","formulasequence":"","widget":"","overridetype":"","overridealignment":""},{"fieldid":"k677361200356f8209eb30c4ec82d","sequence":11,"fieldname":"ID","name":"Emails enviados","display":"yes","path":"root:Campaigns::field:id","displaypath":"Campaigns","sort":"desc","sortpriority":1,"width":"100","jointype":"required","sqlfunction":"-","summaryfunction":"","customsqlfunction":"U0VMRUNUJTIwQ09VTlQlMjhjYW1wYWlnbl9sb2cuaWQlMjklMjBGUk9NJTIwY2FtcGFpZ25fbG9nJTIwV0hFUkUlMjBjYW1wYWlnbl9sb2cuZGVsZXRlZCUzRDAlMjBBTkQlMjBhY3Rpdml0eV90eXBlJTNEJTI3dGFyZ2V0ZWQlMjclMjBBTkQlMjBjYW1wYWlnbl9sb2cuY2FtcGFpZ25faWQlM0QlN0J0JTdELiU3QmYlN0Q=","valuetype":"","groupby":"no","link":"no","editable":"","fixedvalue":"","assigntovalue":"","formulavalue":"","formulasequence":"","widget":"","overridetype":"","overridealignment":""},{"fieldid":"kafe4551ddc59fa41391bf2f34896","sequence":12,"fieldname":"ID","name":"Emails no v\\u00e1lidos","display":"yes","path":"root:Campaigns::field:id","displaypath":"Campaigns","sort":"-","sortpriority":"","width":"100","jointype":"required","sqlfunction":"-","summaryfunction":"","customsqlfunction":"U0VMRUNUJTIwQ09VTlQlMjhjYW1wYWlnbl9sb2cuaWQlMjklMjBGUk9NJTIwY2FtcGFpZ25fbG9nJTIwV0hFUkUlMjBjYW1wYWlnbl9sb2cuZGVsZXRlZCUzRDAlMjBBTkQlMjBhY3Rpdml0eV90eXBlJTNEJTI3aW52YWxpZCUyMGVtYWlsJTI3JTIwQU5EJTIwY2FtcGFpZ25fbG9nLmNhbXBhaWduX2lkJTNEJTdCdCU3RC4lN0JmJTdE","valuetype":"","groupby":"no","link":"no","editable":"","fixedvalue":"","assigntovalue":"","formulavalue":"","formulasequence":"","widget":"","overridetype":"","overridealignment":""},{"fieldid":"k16985ee3af4a15be92e6dc39a4df","sequence":13,"fieldname":"ID","name":"Emails fallidos","display":"yes","path":"root:Campaigns::field:id","displaypath":"Campaigns","sort":"-","sortpriority":"","width":"100","jointype":"required","sqlfunction":"-","summaryfunction":"","customsqlfunction":"U0VMRUNUJTIwQ09VTlQlMjhjYW1wYWlnbl9sb2cuaWQlMjklMjBGUk9NJTIwY2FtcGFpZ25fbG9nJTIwV0hFUkUlMjBjYW1wYWlnbl9sb2cuZGVsZXRlZCUzRDAlMjBBTkQlMjBhY3Rpdml0eV90eXBlJTNEJTI3c2VuZCUyMGVycm9yJTI3JTIwQU5EJTIwY2FtcGFpZ25fbG9nLmNhbXBhaWduX2lkJTNEJTdCdCU3RC4lN0JmJTdE","valuetype":"","groupby":"no","link":"no","editable":"","fixedvalue":"","assigntovalue":"","formulavalue":"","formulasequence":"","widget":"","overridetype":"","overridealignment":""},{"fieldid":"kf6338b9527ef5a9a2961ddb2876b","sequence":14,"fieldname":"ID","name":"Vistos","display":"yes","path":"root:Campaigns::field:id","displaypath":"Campaigns","sort":"-","sortpriority":"","width":"100","jointype":"required","sqlfunction":"-","summaryfunction":"","customsqlfunction":"U0VMRUNUJTIwQ09VTlQlMjhjYW1wYWlnbl9sb2cuaWQlMjklMjBGUk9NJTIwY2FtcGFpZ25fbG9nJTIwV0hFUkUlMjBjYW1wYWlnbl9sb2cuZGVsZXRlZCUzRDAlMjBBTkQlMjBhY3Rpdml0eV90eXBlJTNEJTI3dmlld2VkJTI3JTIwQU5EJTIwY2FtcGFpZ25fbG9nLmNhbXBhaWduX2lkJTNEJTdCdCU3RC4lN0JmJTdE","valuetype":"","groupby":"no","link":"no","editable":"","fixedvalue":"","assigntovalue":"","formulavalue":"","formulasequence":"","widget":"","overridetype":"","overridealignment":""},{"fieldid":"ka673f2ac6c3adf950c36b41ccc36","sequence":15,"fieldname":"ID","name":"Enlaces","display":"yes","path":"root:Campaigns::field:id","displaypath":"Campaigns","sort":"-","sortpriority":"","width":"100","jointype":"required","sqlfunction":"-","summaryfunction":"","customsqlfunction":"U0VMRUNUJTIwQ09VTlQlMjhjYW1wYWlnbl9sb2cuaWQlMjklMjBGUk9NJTIwY2FtcGFpZ25fbG9nJTIwV0hFUkUlMjBjYW1wYWlnbl9sb2cuZGVsZXRlZCUzRDAlMjBBTkQlMjBhY3Rpdml0eV90eXBlJTNEJTI3bGluayUyNyUyMEFORCUyMGNhbXBhaWduX2xvZy5jYW1wYWlnbl9pZCUzRCU3QnQlN0QuJTdCZiU3RA==","valuetype":"","groupby":"no","link":"no","editable":"","fixedvalue":"","assigntovalue":"","formulavalue":"","formulasequence":"","widget":"","overridetype":"","overridealignment":""},{"fieldid":"k8311750619d195290595d1339211","sequence":16,"fieldname":"ID","name":"Interesados creados","display":"yes","path":"root:Campaigns::field:id","displaypath":"Campaigns","sort":"-","sortpriority":"","width":"100","jointype":"required","sqlfunction":"-","summaryfunction":"","customsqlfunction":"U0VMRUNUJTIwQ09VTlQlMjhsZWFkcy5pZCUyOSUyMEZST00lMjBjYW1wYWlnbl9sb2clMjBJTk5FUiUyMEpPSU4lMjBjYW1wYWlnbnMlMjBPTiUyMGNhbXBhaWduX2xvZy5jYW1wYWlnbl9pZCUzRGNhbXBhaWducy5pZCUyMEFORCUyMGNhbXBhaWducy5kZWxldGVkJTNEMCUyMElOTkVSJTIwSk9JTiUyMCUyMGxlYWRzJTIwT04lMjBjYW1wYWlnbl9sb2cucmVsYXRlZF9pZCUzRGxlYWRzLmlkJTIwQU5EJTIwbGVhZHMuZGVsZXRlZCUzRDAlMjBXSEVSRSUyMGNhbXBhaWduX2xvZy5kZWxldGVkJTIwJTNEJTIwJTI3MCUyNyUyMEFORCUyMGNhbXBhaWducy5pZCUzRCU3QnQlN0QuJTdCZiU3RA==","valuetype":"","groupby":"no","link":"no","editable":"","fixedvalue":"","assigntovalue":"","formulavalue":"","formulasequence":"","widget":"","overridetype":"","overridealignment":""},{"fieldid":"kcb738c53e88d419730c16bf117c0","sequence":17,"fieldname":"ID","name":"Personas creadas","display":"yes","path":"root:Campaigns::field:id","displaypath":"Campaigns","sort":"-","sortpriority":"","width":"100","jointype":"required","sqlfunction":"-","summaryfunction":"","customsqlfunction":"U0VMRUNUJTIwQ09VTlQlMjhjb250YWN0cy5pZCUyOSUyMEZST00lMjBjYW1wYWlnbl9sb2clMjBJTk5FUiUyMEpPSU4lMjBjYW1wYWlnbnMlMjBPTiUyMGNhbXBhaWduX2xvZy5jYW1wYWlnbl9pZCUzRGNhbXBhaWducy5pZCUyMEFORCUyMGNhbXBhaWducy5kZWxldGVkJTNEMCUyMElOTkVSJTIwSk9JTiUyMCUyMGNvbnRhY3RzJTIwT04lMjBjYW1wYWlnbl9sb2cucmVsYXRlZF9pZCUzRGNvbnRhY3RzLmlkJTIwQU5EJTIwY29udGFjdHMuZGVsZXRlZCUzRDAlMjBXSEVSRSUyMGNhbXBhaWduX2xvZy5kZWxldGVkJTIwJTNEJTIwJTI3MCUyNyUyMEFORCUyMGNhbXBhaWducy5pZCUzRCU3QnQlN0QuJTdCZiU3RA==","valuetype":"","groupby":"no","link":"no","editable":"","fixedvalue":"","assigntovalue":"","formulavalue":"","formulasequence":"","widget":"","overridetype":"","overridealignment":""},{"fieldid":"kc03d3996fc356da2a1ef187211d0","sequence":18,"fieldname":"ID","name":"Organizaciones creadas","display":"yes","path":"root:Campaigns::field:id","displaypath":"Campaigns","sort":"-","sortpriority":"","width":"100","jointype":"required","sqlfunction":"-","summaryfunction":"","customsqlfunction":"U0VMRUNUJTIwQ09VTlQlMjhhY2NvdW50cy5pZCUyOSUyMEZST00lMjBjYW1wYWlnbl9sb2clMjBJTk5FUiUyMEpPSU4lMjBjYW1wYWlnbnMlMjBPTiUyMGNhbXBhaWduX2xvZy5jYW1wYWlnbl9pZCUzRGNhbXBhaWducy5pZCUyMEFORCUyMGNhbXBhaWducy5kZWxldGVkJTNEMCUyMElOTkVSJTIwSk9JTiUyMCUyMGFjY291bnRzJTIwT04lMjBjYW1wYWlnbl9sb2cucmVsYXRlZF9pZCUzRGFjY291bnRzLmlkJTIwQU5EJTIwYWNjb3VudHMuZGVsZXRlZCUzRDAlMjBXSEVSRSUyMGNhbXBhaWduX2xvZy5kZWxldGVkJTIwJTNEJTIwJTI3MCUyNyUyMEFORCUyMGNhbXBhaWducy5pZCUzRCU3QnQlN0QuJTdCZiU3RA==","valuetype":"","groupby":"no","link":"no","editable":"","fixedvalue":"","assigntovalue":"","formulavalue":"","formulasequence":"","widget":"","overridetype":"","overridealignment":""}]', NULL, NULL),
('ad78c21c-30c2-0182-60a0-5d8b7906e428', 'Exemple - Duplicates - 03 - Duplicate leads by identification number', '2020-07-04 10:16:15', '2020-07-04 10:16:15', '1', '1', NULL, 0, '1', 'Leads', NULL, NULL, '{"resultsFolded":false,"optionsFolded":true,"authCheck":"full","showDeleted":false,"showExport":true,"showSnapshots":false,"showTools":true}', NULL, NULL, NULL, '{"plugin":"standard","pluginData":{}}', '{"1":{"googlecharts":{"uid":"k7a722038c83e4952f095ac03fe04","dims":"111","type":"Pie","dimensions":{"dimension1":"keed50d867d59921a6d9330c60f2c"},"dataseries":[{"fieldid":"k831b96adf771ba7121fec81eac9d","name":"N\\u00famero de identificaci\\u00f3n","chartfunction":"SUM","meaning":"value","axis":"P","renderer":"bars"}],"options":{"is3D":"on"},"title":"TOTAL REGISTROS EN LISTA"},"plugin":"googlecharts"},"plugin":"googlecharts","layout":"-","chartheight":300,"height":"300"}', '{"activePlugins":{"kcsvexport":"1","ktargetlistexport":"0","ksnapshots":"0"}}', '[{"unionid":"root","id":"root","group":"root","type":"AND","parent":"-","notexists":""}]', '[{"unionid":"root","sequence":"","fieldid":"k415c83b76742dd412e5e14640b3f","name":"N\\u00famero de identificaci\\u00f3n","fixedvalue":"","groupid":"root","path":"root:Leads::field:stic_identification_number_c","displaypath":"Leads","referencefieldid":"","operator":"isnotempty","type":"varchar","value":"","valuekey":"","valueto":"","valuetokey":"","jointype":"required","context":"","reference":"","include":"","usereditable":"yes","dashleteditable":"no","exportpdf":"no","customsqlfunction":""}]', '[{"fieldid":"k831b96adf771ba7121fec81eac9d","sequence":"04","fieldname":"N\\u00famero de identificaci\\u00f3n","name":"N\\u00famero de identificaci\\u00f3n","display":"yes","path":"root:Leads::field:stic_identification_number_c","displaypath":"Leads","sort":"asc","sortpriority":2,"width":150,"jointype":"required","sqlfunction":"-","summaryfunction":"","customsqlfunction":"","valuetype":"","groupby":"yes","link":"yes","editable":"","fixedvalue":"","assigntovalue":"","formulavalue":"","formulasequence":"","widget":"","overridetype":"","overridealignment":""},{"fieldid":"keed50d867d59921a6d9330c60f2c","sequence":"05","fieldname":"N\\u00famero de identificaci\\u00f3n","name":"Registros coincidentes","display":"yes","path":"root:Leads::field:stic_identification_number_c","displaypath":"Leads","sort":"desc","sortpriority":1,"width":150,"jointype":"required","sqlfunction":"COUNT","summaryfunction":"","customsqlfunction":"","valuetype":"","groupby":"no","link":"no","editable":"","fixedvalue":"","assigntovalue":"","formulavalue":"","formulasequence":"","widget":"","overridetype":"","overridealignment":""}]', NULL, NULL),
('b11201b1-ccc9-d670-1556-5da05381e7e5', 'Exemple - Events - 01 - Events basic data', '2020-07-04 10:16:15', '2020-07-04 10:16:15', '1', '1', NULL, 0, '1', 'stic_Events', '3', NULL, '{"resultsFolded":false,"optionsFolded":true,"authCheck":"full","showDeleted":false,"showExport":true,"showSnapshots":false,"showTools":true}', NULL, NULL, NULL, '{"plugin":"standard","pluginData":{}}', '{"1":{"googlecharts":{"uid":"ke7d0d24236fe4a1194a7f7a33211","dims":"111","type":"Bar","dimensions":{"dimension1":null},"dataseries":[{"fieldid":"k85b0db628a2ed6ae9ddd4a15ebee","name":"ID","chartfunction":"SUM","meaning":"value","axis":"P","renderer":"bars"}],"options":{"legend":true,"is3D":"on"},"title":"TOTAL EVENTOS"},"plugin":"googlecharts"},"2":{"plugin":"googlecharts","googlecharts":{"uid":"k9e9b8bd99957c2f335baf626102c","dims":"111","type":"Bar","dimensions":{"dimension1":null},"dataseries":[{"fieldid":"k832c2241ae0b2f8f2423d2a0d193","name":"Total inscripciones","chartfunction":"SUM","meaning":"value","axis":"P","renderer":"bars"}],"options":{"legend":true,"is3D":"on"},"title":"TOTAL INSCRIPCIONES"}},"3":{"plugin":"googlecharts","googlecharts":{"uid":"kd4cd8482f677217c5728deb9627f","dims":"111","type":"Bar","dimensions":{"dimension1":null},"dataseries":[{"fieldid":"k260415e46460ab87fbfe9d0fccff","name":"Horas totales","chartfunction":"SUM","meaning":"value","axis":"P","renderer":"bars"}],"options":{"legend":true,"is3D":"on"},"title":"TOTAL HORAS"}},"4":{"plugin":"googlecharts","googlecharts":{"uid":"k51551dc4d6fc0139de9da3efd576","dims":"111","type":"Pie","dimensions":{"dimension1":"kf0a30b3284d40ff4832eee098f43"},"dataseries":[{"fieldid":"k85b0db628a2ed6ae9ddd4a15ebee","name":"ID","chartfunction":"SUM","meaning":"value","axis":"P","renderer":"bars"}],"options":{"legend":true,"is3D":"on"},"title":"EVENTOS POR ESTADO"}},"5":{"plugin":"googlecharts","googlecharts":{"uid":"k814d1fb7d467352209a1f895b017","dims":"111","type":"Pie","dimensions":{"dimension1":"k6a14b670948d578a32ae05e593f4"},"dataseries":[{"fieldid":"k85b0db628a2ed6ae9ddd4a15ebee","name":"ID","chartfunction":"SUM","meaning":"value","axis":"P","renderer":"bars"}],"options":{"legend":true,"is3D":"on"},"title":"EVENTOS POR PROYECTO"}},"plugin":"googlecharts","layout":"2x1x4","chartheight":300,"height":"400"}', '{"activePlugins":{"kcsvexport":"1","ktargetlistexport":"0","ksnapshots":"0"}}', '[{"unionid":"root","id":"root","group":"root","type":"AND","parent":"-","notexists":""}]', '[{"unionid":"root","sequence":"","fieldid":"k3ee14d983f61cd6c380e444efc07","name":"Fecha inicio","fixedvalue":"","groupid":"root","path":"root:stic_Events::field:start_date","displaypath":"stic_Events","referencefieldid":"","operator":"ignore","type":"date","value":"","valuekey":"","valueto":"","valuetokey":"","jointype":"required","context":"","reference":"","include":"","usereditable":"yes","dashleteditable":"no","exportpdf":"no","customsqlfunction":""},{"unionid":"root","sequence":"","fieldid":"kc5964bfdc7484688aa1173384681","name":"Estado Inscripciones","fixedvalue":"","groupid":"root","path":"root:stic_Events::link:stic_Events:stic_registrations_stic_events::field:status","displaypath":"stic_Events::Inscripciones","referencefieldid":"","operator":"ignore","type":"enum","value":"","valuekey":"","valueto":"","valuetokey":"","jointype":"required","context":"","reference":"","include":"","usereditable":"yes","dashleteditable":"no","exportpdf":"no","customsqlfunction":""},{"unionid":"root","sequence":"","fieldid":"k7de467d3767c4a6ca5d1d1b01e9b","name":"Fecha de la sesi\\u00f3n","fixedvalue":"","groupid":"root","path":"root:stic_Events::link:stic_Events:stic_sessions_stic_events::field:start_date","displaypath":"stic_Events::Sesi\\u00f3n","referencefieldid":"","operator":"ignore","type":"datetimecombo","value":"","valuekey":"","valueto":"","valuetokey":"","jointype":"required","context":"","reference":"","include":"","usereditable":"yes","dashleteditable":"no","exportpdf":"no","customsqlfunction":""},{"unionid":"root","sequence":"","fieldid":"kb085d14f51ef9a474949f48c5009","name":"Estado Evento","fixedvalue":"","groupid":"root","path":"root:stic_Events::field:status","displaypath":"stic_Events","referencefieldid":"","operator":"ignore","type":"enum","value":"","valuekey":"","valueto":"","valuetokey":"","jointype":"required","context":"","reference":"","include":"","usereditable":"yes","dashleteditable":"no","exportpdf":"no","customsqlfunction":""},{"unionid":"root","sequence":"","fieldid":"k2a452e58c0c3252503c5cdd6d234","name":"Horas totales","fixedvalue":"","groupid":"root","path":"root:stic_Events::field:total_hours","displaypath":"stic_Events","referencefieldid":"","operator":"ignore","type":"decimal","value":"","valuekey":"","valueto":"","valuetokey":"","jointype":"required","context":"","reference":"","include":"","usereditable":"yes","dashleteditable":"no","exportpdf":"no","customsqlfunction":""}]', '[{"fieldid":"k85b0db628a2ed6ae9ddd4a15ebee","sequence":"01","fieldname":"ID","name":"ID","display":"no","path":"root:stic_Events::field:id","displaypath":"stic_Events","sort":"-","sortpriority":"","width":"100","jointype":"required","sqlfunction":"-","summaryfunction":"","customsqlfunction":"","valuetype":"","groupby":"yes","link":"no","editable":"","fixedvalue":"","assigntovalue":"","formulavalue":"","formulasequence":"","widget":"","overridetype":"","overridealignment":""},{"fieldid":"k30c72b1da1f04324a98f74f17176","sequence":"02","fieldname":"Nombre","name":"Nombre","display":"yes","path":"root:stic_Events::field:name","displaypath":"stic_Events","sort":"asc","sortpriority":1,"width":200,"jointype":"required","sqlfunction":"-","summaryfunction":"","customsqlfunction":"","valuetype":"","groupby":"no","link":"yes","editable":"","fixedvalue":"","assigntovalue":"","formulavalue":"","formulasequence":"","widget":"","overridetype":"","overridealignment":""},{"fieldid":"k6a14b670948d578a32ae05e593f4","sequence":"03","fieldname":"Nombre","name":"Proyecto","display":"yes","path":"root:stic_Events::link:stic_Events:stic_events_project::field:name","displaypath":"stic_Events::Proyectos","sort":"sortable","sortpriority":"","width":150,"jointype":"optional","sqlfunction":"-","summaryfunction":"","customsqlfunction":"","valuetype":"","groupby":"no","link":"no","editable":"","fixedvalue":"","assigntovalue":"","formulavalue":"","formulasequence":"","widget":"","overridetype":"","overridealignment":""},{"fieldid":"kf0a30b3284d40ff4832eee098f43","sequence":"04","fieldname":"Estado","name":"Estado","display":"yes","path":"root:stic_Events::field:status","displaypath":"stic_Events","sort":"sortable","sortpriority":"","width":"100","jointype":"optional","sqlfunction":"-","summaryfunction":"","customsqlfunction":"","valuetype":"","groupby":"no","link":"no","editable":"","fixedvalue":"","assigntovalue":"","formulavalue":"","formulasequence":"","widget":"","overridetype":"","overridealignment":""},{"fieldid":"k0f2634be6634820a09725ec3c37e","sequence":"05","fieldname":"Fecha inicio","name":"Fecha inicio","display":"yes","path":"root:stic_Events::field:start_date","displaypath":"stic_Events","sort":"sortable","sortpriority":"","width":"100","jointype":"optional","sqlfunction":"-","summaryfunction":"","customsqlfunction":"","valuetype":"","groupby":"no","link":"no","editable":"","fixedvalue":"","assigntovalue":"","formulavalue":"","formulasequence":"","widget":"","overridetype":"","overridealignment":""},{"fieldid":"kb934490ad8ecf303b21c82133cba","sequence":"06","fieldname":"Fecha fin","name":"Fecha fin","display":"yes","path":"root:stic_Events::field:end_date","displaypath":"stic_Events","sort":"sortable","sortpriority":"","width":"100","jointype":"optional","sqlfunction":"-","summaryfunction":"","customsqlfunction":"","valuetype":"","groupby":"no","link":"no","editable":"","fixedvalue":"","assigntovalue":"","formulavalue":"","formulasequence":"","widget":"","overridetype":"","overridealignment":""},{"fieldid":"k260415e46460ab87fbfe9d0fccff","sequence":"07","fieldname":"Horas totales","name":"Horas totales","display":"yes","path":"root:stic_Events::field:total_hours","displaypath":"stic_Events","sort":"sortable","sortpriority":"","width":"100","jointype":"optional","sqlfunction":"-","summaryfunction":"","customsqlfunction":"","valuetype":"","groupby":"no","link":"no","editable":"","fixedvalue":"","assigntovalue":"","formulavalue":"","formulasequence":"","widget":"","overridetype":"","overridealignment":""},{"fieldid":"k832c2241ae0b2f8f2423d2a0d193","sequence":"08","fieldname":"ID","name":"Total inscripciones","display":"yes","path":"root:stic_Events::link:stic_Events:stic_registrations_stic_events::field:id","displaypath":"stic_Events::Inscripciones","sort":"sortable","sortpriority":"","width":"100","jointype":"optional","sqlfunction":"COUNT","summaryfunction":"","customsqlfunction":"","valuetype":"","groupby":"no","link":"no","editable":"","fixedvalue":"","assigntovalue":"","formulavalue":"","formulasequence":"","widget":"","overridetype":"","overridealignment":""},{"fieldid":"k7b963c43cc92c0dbfabfb86507f1","sequence":"09","fieldname":"ID","name":"Total sesiones","display":"yes","path":"root:stic_Events::link:stic_Events:stic_sessions_stic_events::field:id","displaypath":"stic_Events::Sesi\\u00f3n","sort":"sortable","sortpriority":"","width":"100","jointype":"optional","sqlfunction":"COUNT","summaryfunction":"","customsqlfunction":"","valuetype":"","groupby":"no","link":"no","editable":"","fixedvalue":"","assigntovalue":"","formulavalue":"","formulasequence":"","widget":"","overridetype":"","overridealignment":""}]', NULL, NULL);
INSERT INTO `kreports` (`id`, `name`, `date_entered`, `date_modified`, `modified_user_id`, `created_by`, `description`, `deleted`, `assigned_user_id`, `report_module`, `report_status`, `union_modules`, `reportoptions`, `listtype`, `listtypeproperties`, `selectionlimit`, `presentation_params`, `visualization_params`, `integration_params`, `wheregroups`, `whereconditions`, `listfields`, `unionlistfields`, `advancedoptions`) VALUES
('be539e69-225c-5eba-97b1-5da6de6bd381', 'Exemple - Economy - 05 - Paid and pending amounts of periodic payment methods in the current year', '2020-07-04 10:16:15', '2020-07-04 10:16:15', '2', '1', NULL, 0, '1', 'stic_Payment_Commitments', NULL, NULL, '{"resultsFolded":false,"optionsFolded":true,"authCheck":"full","showDeleted":false,"showExport":true,"showSnapshots":false,"showTools":true}', NULL, NULL, NULL, '{"plugin":"standard","pluginData":{}}', '{"1":{"googlecharts":{"uid":"k351b094dfd31259baaa3278850c4","dims":"111","type":"Bar","dimensions":{"dimension1":null},"dataseries":[{"fieldid":"k399dcb1a213c635b6f423c5bb449","name":"Importe anualizado","chartfunction":"SUM","meaning":"value","axis":"P","renderer":"bars"}],"options":{"is3D":"on"},"title":"TOTAL IMPORTE ANUALIZADO"},"plugin":"googlecharts"},"2":{"plugin":"googlecharts","googlecharts":{"uid":"k4c618ab411965ecab015c6c10352","dims":"111","type":"Bar","dimensions":{"dimension1":null},"dataseries":[{"fieldid":"k5b0f2558d22dfe9296ac6c42ea2b","name":"Importe pagado este a\\u00f1o","chartfunction":"SUM","meaning":"value","axis":"P","renderer":"bars"}],"options":{"is3D":"on"},"title":"TOTAL IMPORTES PAGADOS ESTE A\\u00d1O"}},"3":{"plugin":"googlecharts","googlecharts":{"uid":"k03ecf68599b76890831aa36aef00","dims":"111","type":"Bar","dimensions":{"dimension1":null},"dataseries":[{"fieldid":"k112516542e79d77d5c9b6774d18c","name":"Importe pendiente este a\\u00f1o","chartfunction":"SUM","meaning":"value","axis":"P","renderer":"bars"}],"options":{"is3D":"on"},"title":"TOTAL IMPORTES PENDIENTES ESTE A\\u00d1O"}},"plugin":"googlecharts","layout":"1x3","chartheight":300,"height":"300"}', '{"activePlugins":{"kcsvexport":"1","ktargetlistexport":"0","ksnapshots":"0"}}', '[{"unionid":"root","id":"root","group":"root","type":"AND","parent":"-","notexists":""}]', '[{"unionid":"root","sequence":"","fieldid":"k907a8b860f3e91f0aa4a760d3bfd","name":"Fecha de los pagos","fixedvalue":"","groupid":"root","path":"root:stic_Payment_Commitments::link:stic_Payment_Commitments:stic_payments_stic_payment_commitments::field:payment_date","displaypath":"stic_Payment_Commitments::Pagos","referencefieldid":"","operator":"thisyear","type":"date","value":"","valuekey":"","valueto":"","valuetokey":"","jointype":"optional","context":"","reference":"","include":"","usereditable":"yo1","dashleteditable":"no","exportpdf":"no","customsqlfunction":""},{"unionid":"root","sequence":"","fieldid":"k481724003202fd563083882026c5","name":"Estado de los pagos","fixedvalue":"","groupid":"root","path":"root:stic_Payment_Commitments::link:stic_Payment_Commitments:stic_payments_stic_payment_commitments::field:status","displaypath":"stic_Payment_Commitments::Pagos","referencefieldid":"","operator":"equals","type":"enum","value":"Paid out","valuekey":"paid","valueto":"","valuetokey":"","jointype":"optional","context":"","reference":"","include":"","usereditable":"yo1","dashleteditable":"no","exportpdf":"no","customsqlfunction":""},{"unionid":"root","sequence":"","fieldid":"kac9d2a621a768a5fc6fdd461f5c1","name":"Periodicidad","fixedvalue":"","groupid":"root","path":"root:stic_Payment_Commitments::field:periodicity","displaypath":"stic_Payment_Commitments","referencefieldid":"","operator":"oneof","type":"enum","value":"Mensual, Bimestral, Trimestral, Cuatrimestral, Anual","valuekey":["monthly","bimonthly","quarterly","four_monthly","annual"],"valueto":"","valuetokey":"","jointype":"optional","context":"","reference":"","include":"","usereditable":"yo1","dashleteditable":"no","exportpdf":"no","customsqlfunction":""},{"unionid":"root","sequence":"","fieldid":"kc0ef775b2872df42a62e8260fc26","name":"Importe anualizado","fixedvalue":"","groupid":"root","path":"root:stic_Payment_Commitments::field:annualized_fee","displaypath":"stic_Payment_Commitments","referencefieldid":"","operator":"isnotempty","type":"currency","value":"","valuekey":"","valueto":"","valuetokey":"","jointype":"optional","context":"","reference":"","include":"","usereditable":"yo1","dashleteditable":"no","exportpdf":"no","customsqlfunction":""},{"unionid":"root","sequence":"","fieldid":"ka032c853743fa848492ba5ef023d","name":"Fecha de baja FdP","fixedvalue":"","groupid":"root","path":"root:stic_Payment_Commitments::field:end_date","displaypath":"stic_Payment_Commitments","referencefieldid":"","operator":"ignore","type":"date","value":"","valuekey":"","valueto":"","valuetokey":"","jointype":"optional","context":"","reference":"","include":"","usereditable":"yes","dashleteditable":"no","exportpdf":"no","customsqlfunction":""},{"unionid":"root","sequence":"","fieldid":"k6bb2449243d7600f0dfdb610300d","name":"Tipo de pago","fixedvalue":"","groupid":"root","path":"root:stic_Payment_Commitments::field:payment_type","displaypath":"stic_Payment_Commitments","referencefieldid":"","operator":"ignore","type":"enum","value":"","valuekey":"","valueto":"","valuetokey":"","jointype":"optional","context":"","reference":"","include":"","usereditable":"yes","dashleteditable":"no","exportpdf":"no","customsqlfunction":""},{"unionid":"root","sequence":"","fieldid":"k0635ef470dfb1215c7dfcd3408ff","name":"Tipo de movimiento","fixedvalue":"","groupid":"root","path":"root:stic_Payment_Commitments::field:transaction_type","displaypath":"stic_Payment_Commitments","referencefieldid":"","operator":"ignore","type":"enum","value":"","valuekey":"","valueto":"","valuetokey":"","jointype":"optional","context":"","reference":"","include":"","usereditable":"yes","dashleteditable":"no","exportpdf":"no","customsqlfunction":""},{"unionid":"root","sequence":"","fieldid":"k1b73f555ef84d0b3f2f7bcd6d446","name":"Medio de pago","fixedvalue":"","groupid":"root","path":"root:stic_Payment_Commitments::field:payment_method","displaypath":"stic_Payment_Commitments","referencefieldid":"","operator":"ignore","type":"enum","value":"","valuekey":"","valueto":"","valuetokey":"","jointype":"optional","context":"","reference":"","include":"","usereditable":"yes","dashleteditable":"no","exportpdf":"no","customsqlfunction":""}]', '[{"fieldid":"kb6211c2e83751a900fb98204d8e5","sequence":"01","fieldname":"ID","name":"ID","display":"no","path":"root:stic_Payment_Commitments::field:id","displaypath":"stic_Payment_Commitments","sort":"-","sortpriority":"","width":"100","jointype":"required","sqlfunction":"-","summaryfunction":"","customsqlfunction":"","valuetype":"","groupby":"yes","link":"no","editable":"","fixedvalue":"","assigntovalue":"","formulavalue":"","formulasequence":"","widget":"","overridetype":"","overridealignment":""},{"fieldid":"k91baa52139d73899c62a0f0c5a5b","sequence":"02","fieldname":"Nombre:","name":"Nombre:","display":"yes","path":"root:stic_Payment_Commitments::link:stic_Payment_Commitments:stic_payment_commitments_contacts::field:first_name","displaypath":"stic_Payment_Commitments::Persona","sort":"sortable","sortpriority":"","width":"100","jointype":"optional","sqlfunction":"-","summaryfunction":"","customsqlfunction":"","valuetype":"","groupby":"no","link":"no","editable":"","fixedvalue":"","assigntovalue":"","formulavalue":"","formulasequence":"","widget":"","overridetype":"","overridealignment":""},{"fieldid":"kb96e39f1c18f2f9ca8eb363b46de","sequence":"03","fieldname":"Apellidos:","name":"Apellidos:","display":"yes","path":"root:stic_Payment_Commitments::link:stic_Payment_Commitments:stic_payment_commitments_contacts::field:last_name","displaypath":"stic_Payment_Commitments::Persona","sort":"sortable","sortpriority":"","width":"100","jointype":"optional","sqlfunction":"-","summaryfunction":"","customsqlfunction":"","valuetype":"","groupby":"no","link":"no","editable":"","fixedvalue":"","assigntovalue":"","formulavalue":"","formulasequence":"","widget":"","overridetype":"","overridealignment":""},{"fieldid":"kaeb6a555b95acc6508672f62031d","sequence":"04","fieldname":"Nombre:","name":"Organizaci\\u00f3n","display":"yes","path":"root:stic_Payment_Commitments::link:stic_Payment_Commitments:stic_payment_commitments_accounts::field:name","displaypath":"stic_Payment_Commitments::Organizaci\\u00f3n","sort":"sortable","sortpriority":"","width":"100","jointype":"optional","sqlfunction":"-","summaryfunction":"","customsqlfunction":"","valuetype":"","groupby":"no","link":"no","editable":"","fixedvalue":"","assigntovalue":"","formulavalue":"","formulasequence":"","widget":"","overridetype":"","overridealignment":""},{"fieldid":"k5d089abd911f69c7811f348aa6e5","sequence":"05","fieldname":"Nombre (autom.)","name":"Forma de Pago","display":"yes","path":"root:stic_Payment_Commitments::field:name","displaypath":"stic_Payment_Commitments","sort":"sortable","sortpriority":"","width":200,"jointype":"required","sqlfunction":"-","summaryfunction":"","customsqlfunction":"","valuetype":"","groupby":"no","link":"yes","editable":"","fixedvalue":"","assigntovalue":"","formulavalue":"","formulasequence":"","widget":"","overridetype":"","overridealignment":""},{"fieldid":"kbad763d2765fcf036440ca897d91","sequence":"06","fieldname":"Importe","name":"Importe","display":"yes","path":"root:stic_Payment_Commitments::field:amount","displaypath":"stic_Payment_Commitments","sort":"sortable","sortpriority":"","width":"100","jointype":"required","sqlfunction":"-","summaryfunction":"","customsqlfunction":"","valuetype":"","groupby":"no","link":"no","editable":"","fixedvalue":"","assigntovalue":"","formulavalue":"","formulasequence":"","widget":"","overridetype":"","overridealignment":""},{"fieldid":"k8aeb8eb98188bd0ecafa2024bf41","sequence":"07","fieldname":"Periodicidad","name":"Periodicidad","display":"yes","path":"root:stic_Payment_Commitments::field:periodicity","displaypath":"stic_Payment_Commitments","sort":"sortable","sortpriority":"","width":"100","jointype":"required","sqlfunction":"-","summaryfunction":"","customsqlfunction":"","valuetype":"","groupby":"no","link":"no","editable":"","fixedvalue":"","assigntovalue":"","formulavalue":"","formulasequence":"","widget":"","overridetype":"","overridealignment":""},{"fieldid":"k399dcb1a213c635b6f423c5bb449","sequence":"08","fieldname":"Cuota anualizada","name":"Importe anualizado","display":"yes","path":"root:stic_Payment_Commitments::field:annualized_fee","displaypath":"stic_Payment_Commitments","sort":"sortable","sortpriority":"","width":"100","jointype":"required","sqlfunction":"-","summaryfunction":"","customsqlfunction":"","valuetype":"","groupby":"no","link":"no","editable":"","fixedvalue":"","assigntovalue":"total","formulavalue":"","formulasequence":"","widget":"","overridetype":"","overridealignment":""},{"fieldid":"k9946de1edc392664860346b5324e","sequence":"09","fieldname":"Fecha de baja","name":"Fecha de baja FdP","display":"yes","path":"root:stic_Payment_Commitments::field:end_date","displaypath":"stic_Payment_Commitments","sort":"sortable","sortpriority":"","width":"100","jointype":"required","sqlfunction":"-","summaryfunction":"","customsqlfunction":"","valuetype":"","groupby":"no","link":"no","editable":"","fixedvalue":"","assigntovalue":"","formulavalue":"","formulasequence":"","widget":"","overridetype":"","overridealignment":""},{"fieldid":"k760fbab4f7ab43cd9c22f2f21acf","sequence":10,"fieldname":"Tipo de movimiento","name":"Tipo de movimiento","display":"yes","path":"root:stic_Payment_Commitments::field:transaction_type","displaypath":"stic_Payment_Commitments","sort":"sortable","sortpriority":"","width":"100","jointype":"required","sqlfunction":"-","summaryfunction":"","customsqlfunction":"","valuetype":"","groupby":"no","link":"no","editable":"","fixedvalue":"","assigntovalue":"","formulavalue":"","formulasequence":"","widget":"","overridetype":"","overridealignment":""},{"fieldid":"kcf34a7c45bd68ce8f5d01f139730","sequence":11,"fieldname":"Tipo de pago","name":"Tipo de pago","display":"yes","path":"root:stic_Payment_Commitments::field:payment_type","displaypath":"stic_Payment_Commitments","sort":"sortable","sortpriority":"","width":"100","jointype":"required","sqlfunction":"-","summaryfunction":"","customsqlfunction":"","valuetype":"","groupby":"no","link":"no","editable":"","fixedvalue":"","assigntovalue":"","formulavalue":"","formulasequence":"","widget":"","overridetype":"","overridealignment":""},{"fieldid":"k2da1b5d1da5678bd53af6a12cbf7","sequence":12,"fieldname":"ID","name":"N\\u00famero de Pagos este a\\u00f1o","display":"yes","path":"root:stic_Payment_Commitments::link:stic_Payment_Commitments:stic_payments_stic_payment_commitments::field:id","displaypath":"stic_Payment_Commitments::Pagos","sort":"sortable","sortpriority":"","width":150,"jointype":"optional","sqlfunction":"COUNT","summaryfunction":"","customsqlfunction":"","valuetype":"","groupby":"no","link":"no","editable":"","fixedvalue":"","assigntovalue":"","formulavalue":"","formulasequence":"","widget":"","overridetype":"","overridealignment":""},{"fieldid":"k5b0f2558d22dfe9296ac6c42ea2b","sequence":13,"fieldname":"Importe","name":"Importe pagado este a\\u00f1o","display":"yes","path":"root:stic_Payment_Commitments::link:stic_Payment_Commitments:stic_payments_stic_payment_commitments::field:amount","displaypath":"stic_Payment_Commitments::Pagos","sort":"sortable","sortpriority":"","width":150,"jointype":"optional","sqlfunction":"SUM","summaryfunction":"","customsqlfunction":"","valuetype":"","groupby":"no","link":"no","editable":"","fixedvalue":"","assigntovalue":"pagado","formulavalue":"","formulasequence":"","widget":"","overridetype":"","overridealignment":""},{"fieldid":"k112516542e79d77d5c9b6774d18c","sequence":14,"fieldname":"","name":"Importe pendiente este a\\u00f1o","display":"yes","path":"","displaypath":"","sort":"sortable","sortpriority":"","width":150,"jointype":"optional","sqlfunction":"-","summaryfunction":"","customsqlfunction":"","valuetype":"","groupby":"no","link":"no","editable":"","fixedvalue":"","assigntovalue":"","formulavalue":"JTdCdG90YWwlN0QtJTdCcGFnYWRvJTdE","formulasequence":"","widget":"","overridetype":"","overridealignment":""}]', NULL, NULL),
('d538ac9d-1ec5-44ce-cc95-5d9c54a1a7e2', 'Exemple - Accounts - 02 - Relationships with accounts basic data', '2020-07-04 10:16:15', '2020-07-04 10:16:15', '1', '1', NULL, 0, '1', 'stic_Accounts_Relationships', '3', NULL, '{"resultsFolded":false,"optionsFolded":true,"authCheck":"full","showDeleted":false,"showExport":true,"showSnapshots":false,"showTools":true}', NULL, NULL, NULL, '{"plugin":"standard","pluginData":{}}', '{"1":{"googlecharts":{"uid":"k6040e23535d466eb1c76cbf59e28","dims":"111","type":"Bar","dimensions":{"dimension1":"ke713057747a2b270c64a93801c80"},"dataseries":[{"fieldid":"ke713057747a2b270c64a93801c80","name":"Tipo de relaci\\u00f3n","chartfunction":"SUM","meaning":"value","axis":"P","renderer":"bars"}],"options":{"legend":true,"is3D":"on"},"title":"Totales tipo de relaci\\u00f3n"},"plugin":"googlecharts"},"2":{"plugin":"googlecharts","googlecharts":{"uid":"k54df9c23ef2fecc6368460fd2163","dims":"111","type":"Pie","dimensions":{"dimension1":"ke713057747a2b270c64a93801c80"},"dataseries":[{"fieldid":"ke713057747a2b270c64a93801c80","name":"Tipo de relaci\\u00f3n","chartfunction":"SUM","meaning":"value","axis":"P","renderer":"bars"}],"options":{"legend":true,"is3D":"on"},"title":"Porcentajes"}},"3":{"plugin":"googlecharts","googlecharts":{"uid":"kfa2a01e625ca654a84bab500403a","dims":"111","type":"Pie","dimensions":{"dimension1":"k47360d6e0e90c8481b2a741f0fee"},"dataseries":[{"fieldid":"k2f014209740e2a275a3aa8302403","name":"Nombre (autom.)","chartfunction":"SUM","meaning":"value","axis":"P","renderer":"bars"}],"options":{"legend":true,"is3D":"on"},"title":"Por Categor\\u00eda de Organizaci\\u00f3n"}},"4":{"plugin":"googlecharts","googlecharts":{"uid":"k6789b3647f30f4be37eeffe99a58","dims":"111","type":"Pie","dimensions":{"dimension1":"kf39666b902589fd1ff5ce4d08b6d"},"dataseries":[{"fieldid":"k2f014209740e2a275a3aa8302403","name":"Nombre (autom.)","chartfunction":"SUM","meaning":"value","axis":"P","renderer":"bars"}],"options":{"legend":true,"is3D":"on"},"title":"Por proyecto vinculado"}},"plugin":"googlecharts","layout":"1x4","chartheight":300,"height":"300"}', '{"activePlugins":{"kcsvexport":"1","ktargetlistexport":"0","ksnapshots":"0"}}', '[{"unionid":"root","id":"root","group":"root","type":"AND","parent":"-","notexists":""}]', '[{"unionid":"root","sequence":"","fieldid":"k1176340c0a66546948cc020ec78f","name":"Organizaci\\u00f3n","fixedvalue":"","groupid":"root","path":"root:stic_Accounts_Relationships::link:stic_Accounts_Relationships:stic_accounts_relationships_accounts::field:name","displaypath":"stic_Accounts_Relationships::Organizaci\\u00f3n","referencefieldid":"","operator":"ignore","type":"name","value":"","valuekey":"","valueto":"","valuetokey":"","jointype":"required","context":"","reference":"","include":"","usereditable":"yes","dashleteditable":"no","exportpdf":"no","customsqlfunction":""},{"unionid":"root","sequence":"","fieldid":"ka4c722848ff33d8ff6446343634b","name":"Tipo de relaci\\u00f3n","fixedvalue":"","groupid":"root","path":"root:stic_Accounts_Relationships::field:relationship_type","displaypath":"stic_Accounts_Relationships","referencefieldid":"","operator":"ignore","type":"enum","value":"","valuekey":"","valueto":"","valuetokey":"","jointype":"required","context":"","reference":"","include":"","usereditable":"yes","dashleteditable":"no","exportpdf":"no","customsqlfunction":""},{"unionid":"root","sequence":"","fieldid":"k67cfa1afad205a247a924be9502a","name":"Fecha de alta","fixedvalue":"","groupid":"root","path":"root:stic_Accounts_Relationships::field:start_date","displaypath":"stic_Accounts_Relationships","referencefieldid":"","operator":"ignore","type":"date","value":"","valuekey":"","valueto":"","valuetokey":"","jointype":"required","context":"","reference":"","include":"","usereditable":"yes","dashleteditable":"no","exportpdf":"no","customsqlfunction":""},{"unionid":"root","sequence":"","fieldid":"kf69f605521ef5784776ccafd9998","name":"Motivo de baja","fixedvalue":"","groupid":"root","path":"root:stic_Accounts_Relationships::field:end_reason","displaypath":"stic_Accounts_Relationships","referencefieldid":"","operator":"ignore","type":"enum","value":"","valuekey":"","valueto":"","valuetokey":"","jointype":"required","context":"","reference":"","include":"","usereditable":"yes","dashleteditable":"no","exportpdf":"no","customsqlfunction":""},{"unionid":"root","sequence":"","fieldid":"k319787dccbe2ef633117fb6ae61e","name":"Fecha de baja","fixedvalue":"","groupid":"root","path":"root:stic_Accounts_Relationships::field:end_date","displaypath":"stic_Accounts_Relationships","referencefieldid":"","operator":"ignore","type":"date","value":"","valuekey":"","valueto":"","valuetokey":"","jointype":"required","context":"","reference":"","include":"","usereditable":"yes","dashleteditable":"no","exportpdf":"no","customsqlfunction":""},{"unionid":"root","sequence":"","fieldid":"k8e0c259d4909644849d4cb0a9b97","name":"Subcategor\\u00eda","fixedvalue":"","groupid":"root","path":"root:stic_Accounts_Relationships::link:stic_Accounts_Relationships:stic_accounts_relationships_accounts::field:stic_subcategory_c","displaypath":"stic_Accounts_Relationships::Organizaci\\u00f3n","referencefieldid":"","operator":"ignore","type":"enum","value":"","valuekey":"","valueto":"","valuetokey":"","jointype":"required","context":"","reference":"","include":"","usereditable":"yes","dashleteditable":"no","exportpdf":"no","customsqlfunction":""},{"unionid":"root","sequence":"","fieldid":"kf49f7a49f4b941ba1d32cc95b246","name":"Categor\\u00eda","fixedvalue":"","groupid":"root","path":"root:stic_Accounts_Relationships::link:stic_Accounts_Relationships:stic_accounts_relationships_accounts::field:stic_category_c","displaypath":"stic_Accounts_Relationships::Organizaci\\u00f3n","referencefieldid":"","operator":"ignore","type":"enum","value":"","valuekey":"","valueto":"","valuetokey":"","jointype":"required","context":"","reference":"","include":"","usereditable":"yes","dashleteditable":"no","exportpdf":"no","customsqlfunction":""},{"unionid":"root","sequence":"","fieldid":"k511af2e0642088744972218fdc18","name":"Provincia de la direcci\\u00f3n principal","fixedvalue":"","groupid":"root","path":"root:stic_Accounts_Relationships::link:stic_Accounts_Relationships:stic_accounts_relationships_accounts::field:billing_address_state","displaypath":"stic_Accounts_Relationships::Organizaci\\u00f3n","referencefieldid":"","operator":"ignore","type":"enum","value":"","valuekey":"","valueto":"","valuetokey":"","jointype":"required","context":"","reference":"","include":"","usereditable":"yes","dashleteditable":"no","exportpdf":"no","customsqlfunction":""},{"unionid":"root","sequence":"","fieldid":"kded6679d123808e908c2cfce4df8","name":"Proyecto","fixedvalue":"","groupid":"root","path":"root:stic_Accounts_Relationships::link:stic_Accounts_Relationships:stic_accounts_relationships_project::field:name","displaypath":"stic_Accounts_Relationships::Proyecto","referencefieldid":"","operator":"ignore","type":"name","value":"","valuekey":"","valueto":"","valuetokey":"","jointype":"required","context":"","reference":"","include":"","usereditable":"yes","dashleteditable":"no","exportpdf":"no","customsqlfunction":""}]', '[{"fieldid":"k2f014209740e2a275a3aa8302403","sequence":"01","fieldname":"Nombre (autom.)","name":"Nombre (autom.)","display":"yes","path":"root:stic_Accounts_Relationships::field:name","displaypath":"stic_Accounts_Relationships","sort":"sortable","sortpriority":"","width":200,"jointype":"required","sqlfunction":"-","summaryfunction":"","customsqlfunction":"","valuetype":"","groupby":"yes","link":"yes","editable":"","fixedvalue":"","assigntovalue":"","formulavalue":"","formulasequence":"","widget":"","overridetype":"","overridealignment":""},{"fieldid":"kc05d6eca5ac8e5fc7cff7baa2033","sequence":"02","fieldname":"Nombre:","name":"Organizacion","display":"yes","path":"root:stic_Accounts_Relationships::link:stic_Accounts_Relationships:stic_accounts_relationships_accounts::field:name","displaypath":"stic_Accounts_Relationships::Organizaci\\u00f3n","sort":"sortable","sortpriority":null,"width":200,"jointype":"required","sqlfunction":"-","summaryfunction":"","customsqlfunction":"","valuetype":"","groupby":"no","link":"yes","editable":"","fixedvalue":"","assigntovalue":"","formulavalue":"","formulasequence":"","widget":"","overridetype":"","overridealignment":""},{"fieldid":"ke713057747a2b270c64a93801c80","sequence":"03","fieldname":"Tipo de relaci\\u00f3n","name":"Tipo de relaci\\u00f3n","display":"yes","path":"root:stic_Accounts_Relationships::field:relationship_type","displaypath":"stic_Accounts_Relationships","sort":"sortable","sortpriority":null,"width":"100","jointype":"optional","sqlfunction":"-","summaryfunction":"","customsqlfunction":"","valuetype":"","groupby":"no","link":"no","editable":"","fixedvalue":"","assigntovalue":"","formulavalue":"","formulasequence":"","widget":"","overridetype":"","overridealignment":""},{"fieldid":"kf56ef6dd0fd7d87fbf28927bd8b1","sequence":"04","fieldname":"Fecha de alta","name":"Fecha de alta","display":"yes","path":"root:stic_Accounts_Relationships::field:start_date","displaypath":"stic_Accounts_Relationships","sort":"sortable","sortpriority":"","width":"100","jointype":"optional","sqlfunction":"-","summaryfunction":"","customsqlfunction":"","valuetype":"","groupby":"no","link":"no","editable":"","fixedvalue":"","assigntovalue":"","formulavalue":"","formulasequence":"","widget":"","overridetype":"","overridealignment":""},{"fieldid":"k91c5f8a3700600c4a21512e07a61","sequence":"05","fieldname":"Fecha de baja","name":"Fecha de baja","display":"yes","path":"root:stic_Accounts_Relationships::field:end_date","displaypath":"stic_Accounts_Relationships","sort":"sortable","sortpriority":"","width":"100","jointype":"optional","sqlfunction":"-","summaryfunction":"","customsqlfunction":"","valuetype":"","groupby":"no","link":"no","editable":"","fixedvalue":"","assigntovalue":"","formulavalue":"","formulasequence":"","widget":"","overridetype":"","overridealignment":""},{"fieldid":"k9a52c7a756042a22413c0aa2ab93","sequence":"06","fieldname":"Motivo de baja","name":"Motivo de baja","display":"yes","path":"root:stic_Accounts_Relationships::field:end_reason","displaypath":"stic_Accounts_Relationships","sort":"sortable","sortpriority":"","width":"100","jointype":"optional","sqlfunction":"-","summaryfunction":"","customsqlfunction":"","valuetype":"","groupby":"no","link":"no","editable":"","fixedvalue":"","assigntovalue":"","formulavalue":"","formulasequence":"","widget":"","overridetype":"","overridealignment":""},{"fieldid":"kf39666b902589fd1ff5ce4d08b6d","sequence":"07","fieldname":"Nombre","name":"Proyecto","display":"yes","path":"root:stic_Accounts_Relationships::link:stic_Accounts_Relationships:stic_accounts_relationships_project::field:name","displaypath":"stic_Accounts_Relationships::Proyecto","sort":"sortable","sortpriority":"","width":150,"jointype":"optional","sqlfunction":"-","summaryfunction":"","customsqlfunction":"","valuetype":"","groupby":"no","link":"no","editable":"","fixedvalue":"","assigntovalue":"","formulavalue":"","formulasequence":"","widget":"","overridetype":"","overridealignment":""},{"fieldid":"k47360d6e0e90c8481b2a741f0fee","sequence":"08","fieldname":"Categor\\u00eda","name":"Categor\\u00eda","display":"yes","path":"root:stic_Accounts_Relationships::link:stic_Accounts_Relationships:stic_accounts_relationships_accounts::field:stic_category_c","displaypath":"stic_Accounts_Relationships::Organizaci\\u00f3n","sort":"sortable","sortpriority":"","width":"100","jointype":"optional","sqlfunction":"-","summaryfunction":"","customsqlfunction":"","valuetype":"","groupby":"no","link":"no","editable":"","fixedvalue":"","assigntovalue":"","formulavalue":"","formulasequence":"","widget":"","overridetype":"","overridealignment":""},{"fieldid":"k3bc1c38cd2cad55eaf7585cf489d","sequence":"09","fieldname":"Subcategor\\u00eda","name":"Subcategor\\u00eda","display":"yes","path":"root:stic_Accounts_Relationships::link:stic_Accounts_Relationships:stic_accounts_relationships_accounts::field:stic_subcategory_c","displaypath":"stic_Accounts_Relationships::Organizaci\\u00f3n","sort":"sortable","sortpriority":"","width":"100","jointype":"optional","sqlfunction":"-","summaryfunction":"","customsqlfunction":"","valuetype":"","groupby":"no","link":"no","editable":"","fixedvalue":"","assigntovalue":"","formulavalue":"","formulasequence":"","widget":"","overridetype":"","overridealignment":""},{"fieldid":"k99404acae396c6b69b1f64355976","sequence":10,"fieldname":"Provincia de la direcci\\u00f3n principal","name":"Provincia de la direcci\\u00f3n principal","display":"yes","path":"root:stic_Accounts_Relationships::link:stic_Accounts_Relationships:stic_accounts_relationships_accounts::field:billing_address_state","displaypath":"stic_Accounts_Relationships::Organizaci\\u00f3n","sort":"sortable","sortpriority":"","width":"100","jointype":"optional","sqlfunction":"-","summaryfunction":"","customsqlfunction":"","valuetype":"","groupby":"no","link":"no","editable":"","fixedvalue":"","assigntovalue":"","formulavalue":"","formulasequence":"","widget":"","overridetype":"","overridealignment":""}]', NULL, NULL),
('db067730-412c-f5d6-16eb-5da450a46581', 'Exemple - Events - 02 - Registrations basic data', '2020-07-04 10:16:15', '2020-07-04 10:16:15', '1', '1', NULL, 0, '1', 'stic_Registrations', '2', NULL, '{"resultsFolded":false,"optionsFolded":true,"authCheck":"full","showDeleted":false,"showExport":true,"showSnapshots":false,"showTools":true}', NULL, NULL, NULL, '{"plugin":"standard","pluginData":{}}', '{"1":{"googlecharts":{"uid":"k7f5b9cc6be5e5a000b30bc88b469","dims":"111","type":"Pie","dimensions":{"dimension1":"k9bacbe289a16acbff5055095c80a"},"dataseries":[{"fieldid":"k3d9d80d407a98b10fec122302338","name":"Inscripci\\u00f3n","chartfunction":"SUM","meaning":"value","axis":"P","renderer":"bars"}],"options":{"legend":true,"is3D":"on"},"title":"Inscripciones por evento"},"plugin":"googlecharts"},"2":{"plugin":"googlecharts","googlecharts":{"uid":"k3979e5c2e5d5a9e0bcaeae414085","dims":"111","type":"Pie","dimensions":{"dimension1":"ka0100346f489078ced3fead153bb"},"dataseries":[{"fieldid":"k3d9d80d407a98b10fec122302338","name":"Inscripci\\u00f3n","chartfunction":"SUM","meaning":"value","axis":"P","renderer":"bars"}],"options":{"legend":true,"is3D":"on"},"title":"Inscripciones por Estado"}},"3":{"plugin":"googlecharts","googlecharts":{"uid":"k1beb18c943d0b3f968594e4199a6","dims":"111","type":"Pie","dimensions":{"dimension1":"kab7cf0ed0cb353cc734256d1b82f"},"dataseries":[{"fieldid":"k3d9d80d407a98b10fec122302338","name":"Inscripci\\u00f3n","chartfunction":"SUM","meaning":"value","axis":"P","renderer":"bars"}],"options":{"legend":true,"is3D":"on"},"title":"Inscripciones por Tipo de Participaci\\u00f3n"}},"4":{"plugin":"googlecharts","googlecharts":{"uid":"k599e37009fb060fc7c2db6201332","dims":"111","type":"Column","dimensions":{"dimension1":"k3d9d80d407a98b10fec122302338"},"dataseries":[{"fieldid":"kd9b4430252c9febf081e0d00bf93","name":"Horas asistencia","chartfunction":"SUM","meaning":"value","axis":"P","renderer":"bars"}],"options":{},"title":"Horas"}},"plugin":"googlecharts","layout":"1x3","chartheight":300,"height":"250"}', '{"activePlugins":{"kcsvexport":"1","ktargetlistexport":"0","ksnapshots":"0"}}', '[{"unionid":"root","id":"root","group":"root","type":"AND","parent":"-","notexists":""}]', '[{"unionid":"root","sequence":"","fieldid":"k72efcdff4dd175bcf2a95bdb7902","name":"Organizaci\\u00f3n","fixedvalue":"","groupid":"root","path":"root:stic_Registrations::link:stic_Registrations:stic_registrations_accounts::field:name","displaypath":"stic_Registrations::Organizaci\\u00f3n","referencefieldid":"","operator":"ignore","type":"name","value":"","valuekey":"","valueto":"","valuetokey":"","jointype":"required","context":"","reference":"","include":"","usereditable":"yes","dashleteditable":"no","exportpdf":"no","customsqlfunction":""},{"unionid":"root","sequence":"","fieldid":"k8a37f56fa11218c2e236a43deae2","name":"Persona (Apellidos)","fixedvalue":"","groupid":"root","path":"root:stic_Registrations::link:stic_Registrations:stic_registrations_contacts::field:last_name","displaypath":"stic_Registrations::Persona","referencefieldid":"","operator":"ignore","type":"varchar","value":"","valuekey":"","valueto":"","valuetokey":"","jointype":"required","context":"","reference":"","include":"","usereditable":"yes","dashleteditable":"no","exportpdf":"no","customsqlfunction":""},{"unionid":"root","sequence":"","fieldid":"kd285502adf6ada20ecb293bf6923","name":"Fecha Inscripci\\u00f3n","fixedvalue":"","groupid":"root","path":"root:stic_Registrations::field:registration_date","displaypath":"stic_Registrations","referencefieldid":"","operator":"ignore","type":"datetimecombo","value":"","valuekey":"","valueto":"","valuetokey":"","jointype":"required","context":"","reference":"","include":"","usereditable":"yes","dashleteditable":"no","exportpdf":"no","customsqlfunction":""},{"unionid":"root","sequence":"","fieldid":"kc09bcfcee5ff1e6f09ec22d8ce87","name":"Evento","fixedvalue":"","groupid":"root","path":"root:stic_Registrations::link:stic_Registrations:stic_registrations_stic_events::field:name","displaypath":"stic_Registrations::Eventos","referencefieldid":"","operator":"ignore","type":"name","value":"","valuekey":"","valueto":"","valuetokey":"","jointype":"required","context":"","reference":"","include":"","usereditable":"yes","dashleteditable":"no","exportpdf":"no","customsqlfunction":""},{"unionid":"root","sequence":"","fieldid":"k4480cfa4659608875f0cf378895f","name":"Estado","fixedvalue":"","groupid":"root","path":"root:stic_Registrations::field:status","displaypath":"stic_Registrations","referencefieldid":"","operator":"ignore","type":"enum","value":"","valuekey":"","valueto":"","valuetokey":"","jointype":"required","context":"","reference":"","include":"","usereditable":"yes","dashleteditable":"no","exportpdf":"no","customsqlfunction":""}]', '[{"fieldid":"k9bacbe289a16acbff5055095c80a","sequence":"01","fieldname":"Nombre","name":"Evento","display":"yes","path":"root:stic_Registrations::link:stic_Registrations:stic_registrations_stic_events::field:name","displaypath":"stic_Registrations::Eventos","sort":"asc","sortpriority":1,"width":200,"jointype":"required","sqlfunction":"-","summaryfunction":"","customsqlfunction":"","valuetype":"","groupby":"no","link":"no","editable":"","fixedvalue":"","assigntovalue":"","formulavalue":"","formulasequence":"","widget":"","overridetype":"","overridealignment":""},{"fieldid":"k3d9d80d407a98b10fec122302338","sequence":"02","fieldname":"Nombre (autom.)","name":"Inscripci\\u00f3n","display":"yes","path":"root:stic_Registrations::field:name","displaypath":"stic_Registrations","sort":"sortable","sortpriority":null,"width":100,"jointype":"required","sqlfunction":"-","summaryfunction":"","customsqlfunction":"","valuetype":"","groupby":"no","link":"yes","editable":"","fixedvalue":"","assigntovalue":"","formulavalue":"","formulasequence":"","widget":"","overridetype":"","overridealignment":""},{"fieldid":"ke5e2a352da94a73bd3b65b3c0163","sequence":"03","fieldname":"Fecha Inscripci\\u00f3n","name":"Fecha Inscripci\\u00f3n","display":"yes","path":"root:stic_Registrations::field:registration_date","displaypath":"stic_Registrations","sort":"desc","sortpriority":2,"width":"100","jointype":"optional","sqlfunction":"-","summaryfunction":"","customsqlfunction":"","valuetype":"","groupby":"no","link":"no","editable":"","fixedvalue":"","assigntovalue":"","formulavalue":"","formulasequence":"","widget":"","overridetype":"","overridealignment":""},{"fieldid":"ka0100346f489078ced3fead153bb","sequence":"04","fieldname":"Estado","name":"Estado","display":"yes","path":"root:stic_Registrations::field:status","displaypath":"stic_Registrations","sort":"sortable","sortpriority":"","width":"100","jointype":"optional","sqlfunction":"-","summaryfunction":"","customsqlfunction":"","valuetype":"","groupby":"no","link":"no","editable":"","fixedvalue":"","assigntovalue":"","formulavalue":"","formulasequence":"","widget":"","overridetype":"","overridealignment":""},{"fieldid":"kab7cf0ed0cb353cc734256d1b82f","sequence":"05","fieldname":"Tipo de participaci\\u00f3n","name":"Tipo de participaci\\u00f3n","display":"yes","path":"root:stic_Registrations::field:participation_type","displaypath":"stic_Registrations","sort":"sortable","sortpriority":"","width":"100","jointype":"optional","sqlfunction":"-","summaryfunction":"","customsqlfunction":"","valuetype":"","groupby":"no","link":"no","editable":"","fixedvalue":"","assigntovalue":"","formulavalue":"","formulasequence":"","widget":"","overridetype":"","overridealignment":""},{"fieldid":"kcb1ba839c28b7fcf329072fbfb41","sequence":"06","fieldname":"Nombre:","name":"Organizaci\\u00f3n","display":"yes","path":"root:stic_Registrations::link:stic_Registrations:stic_registrations_accounts::field:name","displaypath":"stic_Registrations::Organizaci\\u00f3n","sort":"sortable","sortpriority":"","width":200,"jointype":"optional","sqlfunction":"-","summaryfunction":"","customsqlfunction":"","valuetype":"","groupby":"no","link":"no","editable":"","fixedvalue":"","assigntovalue":"","formulavalue":"","formulasequence":"","widget":"","overridetype":"","overridealignment":""},{"fieldid":"k4fb8fb047eb25fc65f1ba3bec0c6","sequence":"09","fieldname":"Nombre:","name":"Nombre Persona","display":"yes","path":"root:stic_Registrations::link:stic_Registrations:stic_registrations_contacts::field:first_name","displaypath":"stic_Registrations::Persona","sort":"sortable","sortpriority":"","width":200,"jointype":"optional","sqlfunction":"-","summaryfunction":"","customsqlfunction":"","valuetype":"","groupby":"no","link":"no","editable":"","fixedvalue":"","assigntovalue":"","formulavalue":"","formulasequence":"","widget":"","overridetype":"","overridealignment":""},{"fieldid":"kb60783da6404dea9f75984fa7c17","sequence":11,"fieldname":"Apellidos:","name":"Apellidos Persona","display":"yes","path":"root:stic_Registrations::link:stic_Registrations:stic_registrations_contacts::field:last_name","displaypath":"stic_Registrations::Persona","sort":"sortable","sortpriority":"","width":150,"jointype":"optional","sqlfunction":"-","summaryfunction":"","customsqlfunction":"","valuetype":"","groupby":"no","link":"no","editable":"","fixedvalue":"","assigntovalue":"","formulavalue":"","formulasequence":"","widget":"","overridetype":"","overridealignment":""},{"fieldid":"kd9b4430252c9febf081e0d00bf93","sequence":14,"fieldname":"Horas asistencia","name":"Horas asistencia","display":"yes","path":"root:stic_Registrations::field:attended_hours","displaypath":"stic_Registrations","sort":"sortable","sortpriority":"","width":"100","jointype":"optional","sqlfunction":"-","summaryfunction":"","customsqlfunction":"","valuetype":"","groupby":"no","link":"no","editable":"","fixedvalue":"","assigntovalue":"","formulavalue":"","formulasequence":"","widget":"","overridetype":"","overridealignment":""},{"fieldid":"kddf3c670e2ac29a29858b31fe23c","sequence":15,"fieldname":"Porcentaje de asistencia","name":"Porcentaje de asistencia","display":"yes","path":"root:stic_Registrations::field:attendance_percentage","displaypath":"stic_Registrations","sort":"sortable","sortpriority":"","width":"100","jointype":"optional","sqlfunction":"-","summaryfunction":"","customsqlfunction":"","valuetype":"","groupby":"no","link":"no","editable":"","fixedvalue":"","assigntovalue":"","formulavalue":"","formulasequence":"","widget":"","overridetype":"","overridealignment":""}]', NULL, NULL),
('ee52e9d4-36a9-11fd-c093-5da445405077', 'Exemple - Contacts - 05 - Drop out monthly evolution by drop out reason (contacts and accounts)', '2020-07-04 10:16:15', '2020-07-04 10:16:15', '2', '1', NULL, 0, '1', 'stic_Contacts_Relationships', NULL, '[{"unionid":"ka0613b8e56505538b5e28cc16bde","module":"stic_Accounts_Relationships"}]', '{"resultsFolded":false,"optionsFolded":true,"authCheck":"full","showDeleted":false,"showExport":true,"showSnapshots":false,"showTools":true}', NULL, NULL, NULL, '{"plugin":"standard","pluginData":{}}', '{"1":{"googlecharts":{"uid":"k407087259cb8a8a85f77d26e25c4","dims":"111","type":"Pie","dimensions":{"dimension1":"k587f390cf91754969727899d9a11"},"dataseries":[{"fieldid":"ka9511c62f76a6228a8b180952bfc","name":"Total","chartfunction":"SUM","meaning":"value","axis":"P","renderer":"bars"}],"options":{"legend":true},"title":"Porcentaje por motivo de baja"},"plugin":"googlecharts"},"2":{"plugin":"googlecharts","googlecharts":{"uid":"k0e70477bc3844335aa3b77eac79e","dims":"111","type":"Column","dimensions":{"dimension1":"k4d52ab26034f1d9cf75fd43de90f"},"dataseries":[{"fieldid":"ka9511c62f76a6228a8b180952bfc","name":"Total","chartfunction":"SUM","meaning":"value","axis":"P","renderer":"bars"}],"options":{},"title":"Total por fecha"}},"plugin":"googlecharts","layout":"1x2","chartheight":300,"height":"250"}', '{"activePlugins":{"kcsvexport":"1","ktargetlistexport":"0","ksnapshots":"0"}}', '[{"unionid":"root","id":"root","group":"root","type":"AND","parent":"-","notexists":""},{"unionid":"ka0613b8e56505538b5e28cc16bde","id":"root","group":"root","type":"AND","parent":"-","notexists":""}]', '[{"unionid":"root","sequence":"","fieldid":"k8dca6cfa0f0d947a0e9c31b87c93","name":"Fecha de baja (Personas)","fixedvalue":"","groupid":"root","path":"root:stic_Contacts_Relationships::field:end_date","displaypath":"stic_Contacts_Relationships","referencefieldid":"","operator":"isnotempty","type":"date","value":"","valuekey":"","valueto":"","valuetokey":"","jointype":"required","context":"","reference":"","include":"","usereditable":"yes","dashleteditable":"no","exportpdf":"no","customsqlfunction":""},{"unionid":"root","sequence":"","fieldid":"k58de1447eb28e609aae5a06c4482","name":"Tipo de relaci\\u00f3n (Personas)","fixedvalue":"","groupid":"root","path":"root:stic_Contacts_Relationships::field:relationship_type","displaypath":"stic_Contacts_Relationships","referencefieldid":"","operator":"equals","type":"enum","value":"Socio","valuekey":"member","valueto":"","valuetokey":"","jointype":"required","context":"","reference":"","include":"","usereditable":"yes","dashleteditable":"no","exportpdf":"no","customsqlfunction":""},{"unionid":"ka0613b8e56505538b5e28cc16bde","sequence":"","fieldid":"k07ee8799f562b88453f007c8f3c1","name":"Fecha de baja (Organizaciones)","fixedvalue":"","groupid":"root","path":"unionroot::unionka0613b8e56505538b5e28cc16bde:stic_Accounts_Relationships::field:end_date","displaypath":"stic_Accounts_Relationships","referencefieldid":"","operator":"isnotempty","type":"date","value":"","valuekey":"","valueto":"","valuetokey":"","jointype":"required","context":"","reference":"","include":"","usereditable":"yes","dashleteditable":"no","exportpdf":"no","customsqlfunction":""},{"unionid":"ka0613b8e56505538b5e28cc16bde","sequence":"","fieldid":"k8a3c0a4672ab998aa6a9a57cb0bd","name":"Tipo de relaci\\u00f3n (Organizaciones)","fixedvalue":"","groupid":"root","path":"unionroot::unionka0613b8e56505538b5e28cc16bde:stic_Accounts_Relationships::field:relationship_type","displaypath":"stic_Accounts_Relationships","referencefieldid":"","operator":"equals","type":"enum","value":"Socio","valuekey":"member","valueto":"","valuetokey":"","jointype":"required","context":"","reference":"","include":"","usereditable":"yes","dashleteditable":"no","exportpdf":"no","customsqlfunction":""}]', '[{"fieldid":"k587f390cf91754969727899d9a11","sequence":"01","fieldname":"Motivo Baja","name":"Motivo Baja","display":"yes","path":"root:stic_Contacts_Relationships::field:end_reason","displaypath":"stic_Contacts_Relationships","sort":"sortable","sortpriority":null,"width":200,"jointype":"optional","sqlfunction":"-","summaryfunction":"","customsqlfunction":"","valuetype":"","groupby":"yes","link":"no","editable":"","fixedvalue":"","assigntovalue":"","formulavalue":"","formulasequence":"","widget":"","overridetype":"","overridealignment":""},{"fieldid":"k4d52ab26034f1d9cf75fd43de90f","sequence":"03","fieldname":"Fecha de baja","name":"Fecha de baja","display":"yes","path":"root:stic_Contacts_Relationships::field:end_date","displaypath":"stic_Contacts_Relationships","sort":"sortable","sortpriority":null,"width":"100","jointype":"required","sqlfunction":"-","summaryfunction":"","customsqlfunction":"REFURV9GT1JNQVQlMjglN0J0JTdELiU3QmYlN0QlMkMlMjclMjVZJTIwJTI1bSUyNyUyOQ==","valuetype":"","groupby":"no","link":"no","editable":"","fixedvalue":"","assigntovalue":"","formulavalue":"","formulasequence":"","widget":"","overridetype":"","overridealignment":""},{"fieldid":"ka9511c62f76a6228a8b180952bfc","sequence":"04","fieldname":"ID:","name":"Total","display":"yes","path":"root:stic_Contacts_Relationships::link:stic_Contacts_Relationships:stic_contacts_relationships_contacts::field:id","displaypath":"stic_Contacts_Relationships::Personas","sort":"sortable","sortpriority":"","width":"100","jointype":"required","sqlfunction":"COUNT","summaryfunction":"","customsqlfunction":"","valuetype":"","groupby":"no","link":"no","editable":"","fixedvalue":"","assigntovalue":"","formulavalue":"","formulasequence":"","widget":"","overridetype":"","overridealignment":""}]', '[{"joinid":"ka0613b8e56505538b5e28cc16bde","fieldid":"k587f390cf91754969727899d9a11","sequence":"01","name":"Motivo Baja","unionfieldpath":"unionka0613b8e56505538b5e28cc16bde:stic_Accounts_Relationships::field:end_reason","unionfielddisplaypath":"stic_Accounts_Relationships","unionfieldname":"Motivo de baja","path":"root:stic_Contacts_Relationships::field:end_reason","displaypath":"stic_Contacts_Relationships","fixedvalue":""},{"joinid":"ka0613b8e56505538b5e28cc16bde","fieldid":"k4d52ab26034f1d9cf75fd43de90f","sequence":"03","name":"Fecha de baja","unionfieldpath":"unionka0613b8e56505538b5e28cc16bde:stic_Accounts_Relationships::field:end_date","unionfielddisplaypath":"stic_Accounts_Relationships","unionfieldname":"Fecha de baja","path":"root:stic_Contacts_Relationships::field:end_date","displaypath":"stic_Contacts_Relationships","fixedvalue":""},{"joinid":"ka0613b8e56505538b5e28cc16bde","fieldid":"ka9511c62f76a6228a8b180952bfc","sequence":"04","name":"Total","unionfieldpath":"unionka0613b8e56505538b5e28cc16bde:stic_Accounts_Relationships::link:stic_Accounts_Relationships:stic_accounts_relationships_accounts::field:id","unionfielddisplaypath":"stic_Accounts_Relationships::Organizaci\\u00f3n","unionfieldname":"ID","path":"root:stic_Contacts_Relationships::link:stic_Contacts_Relationships:stic_contacts_relationships_contacts::field:id","displaypath":"stic_Contacts_Relationships::Personas","fixedvalue":""}]', NULL);
INSERT INTO `kreports` (`id`, `name`, `date_entered`, `date_modified`, `modified_user_id`, `created_by`, `description`, `deleted`, `assigned_user_id`, `report_module`, `report_status`, `union_modules`, `reportoptions`, `listtype`, `listtypeproperties`, `selectionlimit`, `presentation_params`, `visualization_params`, `integration_params`, `wheregroups`, `whereconditions`, `listfields`, `unionlistfields`, `advancedoptions`) VALUES
('f2cbc637-e47a-a802-aeef-5d9c504e3d69', 'Exemple - Accounts - 01 - Accounts basic data', '2020-07-04 10:16:15', '2020-07-04 10:16:15', '1', '1', NULL, 0, '1', 'Accounts', '3', NULL, '{"resultsFolded":false,"optionsFolded":true,"authCheck":"full","showDeleted":false,"showExport":true,"showSnapshots":false,"showTools":true}', NULL, NULL, NULL, '{"plugin":"standard","pluginData":{}}', '{"1":{"googlecharts":{"uid":"k5a5b2da33578ef7d0f8f37d0c493","dims":"111","type":"Bar","dimensions":{"dimension1":null},"dataseries":[{"fieldid":"ke9384695727a17fcd620e3c263b9","name":"ID","chartfunction":"SUM","meaning":"value","axis":"P","renderer":"bars"}],"options":{"legend":true,"is3D":"on"},"title":"Total de Registros"},"plugin":"googlecharts"},"2":{"plugin":"googlecharts","googlecharts":{"uid":"ka39f157114d8bfa95d7c7a4ecbe9","dims":"111","type":"Pie","dimensions":{"dimension1":"k3cba884c6665d272afa1233213fe"},"dataseries":[{"fieldid":"ke9384695727a17fcd620e3c263b9","name":"ID","chartfunction":"SUM","meaning":"value","axis":"P","renderer":"bars"}],"options":{"legend":true,"is3D":"on"},"title":"Categor\\u00eda de las Organizaciones"}},"3":{"plugin":"googlecharts","googlecharts":{"uid":"k11056f90075481350d1ac6c8e735","dims":"111","type":"Pie","dimensions":{"dimension1":"kcb52a603ec04b89329f1eb9bc4af"},"dataseries":[{"fieldid":"ke9384695727a17fcd620e3c263b9","name":"ID","chartfunction":"SUM","meaning":"value","axis":"P","renderer":"bars"}],"options":{"legend":true,"is3D":"on"},"title":"Por tipo de relaci\\u00f3n (actual)"}},"plugin":"googlecharts","layout":"1x3","chartheight":300,"height":"300"}', '{"activePlugins":{"kcsvexport":"1","ktargetlistexport":"0","ksnapshots":"0"}}', '[{"unionid":"root","id":"root","group":"root","type":"AND","parent":"-","notexists":""}]', '[{"unionid":"root","sequence":"","fieldid":"k5fa4785fbc59fef94fb1f5f934f6","name":"Nombre:","fixedvalue":"","groupid":"root","path":"root:Accounts::field:name","displaypath":"Accounts","referencefieldid":"","operator":"ignore","type":"name","value":"","valuekey":"","valueto":"","valuetokey":"","jointype":"required","context":"","reference":"","include":"","usereditable":"yes","dashleteditable":"no","exportpdf":"no","customsqlfunction":""},{"unionid":"root","sequence":"","fieldid":"k4d43fc6afc1459143069591cec54","name":"Tipo de relaci\\u00f3n (actual)","fixedvalue":"","groupid":"root","path":"root:Accounts::field:stic_relationship_type_c","displaypath":"Accounts","referencefieldid":"","operator":"isnotempty","type":"multienum","value":"","valuekey":"","valueto":"","valuetokey":"","jointype":"required","context":"","reference":"","include":"","usereditable":"yes","dashleteditable":"no","exportpdf":"no","customsqlfunction":""},{"unionid":"root","sequence":"","fieldid":"kafba0547f2ce8f2856f4d74ae95a","name":"Provincia de la direcci\\u00f3n principal","fixedvalue":"","groupid":"root","path":"root:Accounts::field:billing_address_state","displaypath":"Accounts","referencefieldid":"","operator":"ignore","type":"enum","value":"","valuekey":"","valueto":"","valuetokey":"","jointype":"required","context":"","reference":"","include":"","usereditable":"yes","dashleteditable":"no","exportpdf":"no","customsqlfunction":""},{"unionid":"root","sequence":"","fieldid":"ke5972d195a168e69cda4f7d0610c","name":"Categor\\u00eda","fixedvalue":"","groupid":"root","path":"root:Accounts::field:stic_category_c","displaypath":"Accounts","referencefieldid":"","operator":"ignore","type":"enum","value":"","valuekey":"","valueto":"","valuetokey":"","jointype":"required","context":"","reference":"","include":"","usereditable":"yes","dashleteditable":"no","exportpdf":"no","customsqlfunction":""},{"unionid":"root","sequence":"","fieldid":"k09e30729808fbc2f0a9e9146af5b","name":"Subcategor\\u00eda","fixedvalue":"","groupid":"root","path":"root:Accounts::field:stic_subcategory_c","displaypath":"Accounts","referencefieldid":"","operator":"ignore","type":"enum","value":"","valuekey":"","valueto":"","valuetokey":"","jointype":"required","context":"","reference":"","include":"","usereditable":"yes","dashleteditable":"no","exportpdf":"no","customsqlfunction":""}]', '[{"fieldid":"k4769fb0f8a4bca7a980b6585452b","sequence":"01","fieldname":"Nombre:","name":"Nombre:","display":"yes","path":"root:Accounts::field:name","displaypath":"Accounts","sort":"asc","sortpriority":"","width":200,"jointype":"required","sqlfunction":"-","summaryfunction":"","customsqlfunction":"","valuetype":"","groupby":"no","link":"yes","editable":"","fixedvalue":"","assigntovalue":"","formulavalue":"","formulasequence":"","widget":"","overridetype":"","overridealignment":""},{"fieldid":"k3cba884c6665d272afa1233213fe","sequence":"02","fieldname":"Categor\\u00eda","name":"Categor\\u00eda","display":"yes","path":"root:Accounts::field:stic_category_c","displaypath":"Accounts","sort":"sortable","sortpriority":"","width":"100","jointype":"required","sqlfunction":"-","summaryfunction":"","customsqlfunction":"","valuetype":"","groupby":"no","link":"no","editable":"","fixedvalue":"","assigntovalue":"","formulavalue":"","formulasequence":"","widget":"","overridetype":"","overridealignment":""},{"fieldid":"k303022b0dcbef25ebfa95a629a51","sequence":"03","fieldname":"Subcategor\\u00eda","name":"Subcategor\\u00eda","display":"yes","path":"root:Accounts::field:stic_subcategory_c","displaypath":"Accounts","sort":"sortable","sortpriority":"","width":"100","jointype":"required","sqlfunction":"-","summaryfunction":"","customsqlfunction":"","valuetype":"","groupby":"no","link":"no","editable":"","fixedvalue":"","assigntovalue":"","formulavalue":"","formulasequence":"","widget":"","overridetype":"","overridealignment":""},{"fieldid":"k96f85d2cf16d1f8ffe64ff6913f3","sequence":"04","fieldname":"CIF","name":"CIF","display":"yes","path":"root:Accounts::field:stic_identification_number_c","displaypath":"Accounts","sort":"sortable","sortpriority":"","width":"100","jointype":"required","sqlfunction":"-","summaryfunction":"","customsqlfunction":"","valuetype":"","groupby":"no","link":"no","editable":"","fixedvalue":"","assigntovalue":"","formulavalue":"","formulasequence":"","widget":"","overridetype":"","overridealignment":""},{"fieldid":"kca6e41c6a584be35793f1e9340e8","sequence":"05","fieldname":"Tel\\u00e9fono oficina:","name":"Tel\\u00e9fono oficina:","display":"yes","path":"root:Accounts::field:phone_office","displaypath":"Accounts","sort":"sortable","sortpriority":"","width":"100","jointype":"required","sqlfunction":"-","summaryfunction":"","customsqlfunction":"","valuetype":"","groupby":"no","link":"no","editable":"","fixedvalue":"","assigntovalue":"","formulavalue":"","formulasequence":"","widget":"","overridetype":"","overridealignment":""},{"fieldid":"k0ba45d9f9b0cbe785d96ac221a8a","sequence":"06","fieldname":"Tel\\u00e9fono m\\u00f3vil","name":"Tel\\u00e9fono m\\u00f3vil","display":"yes","path":"root:Accounts::field:phone_alternate","displaypath":"Accounts","sort":"sortable","sortpriority":"","width":"100","jointype":"required","sqlfunction":"-","summaryfunction":"","customsqlfunction":"","valuetype":"","groupby":"no","link":"no","editable":"","fixedvalue":"","assigntovalue":"","formulavalue":"","formulasequence":"","widget":"","overridetype":"","overridealignment":""},{"fieldid":"k21a5dfaa7c4e0567d068857a33a1","sequence":"07","fieldname":"Direcci\\u00f3n de Email","name":"Direcci\\u00f3n de Email","display":"yes","path":"root:Accounts::link:Accounts:email_addresses_primary::field:email_address","displaypath":"Accounts::Direcci\\u00f3n de Email","sort":"sortable","sortpriority":"","width":"100","jointype":"optional","sqlfunction":"-","summaryfunction":"","customsqlfunction":"","valuetype":"","groupby":"no","link":"no","editable":"","fixedvalue":"","assigntovalue":"","formulavalue":"","formulasequence":"","widget":"","overridetype":"","overridealignment":""},{"fieldid":"kf740df569158cdf4f9f48f993f33","sequence":"08","fieldname":"V\\u00eda de la direcci\\u00f3n principal","name":"V\\u00eda de la direcci\\u00f3n principal","display":"yes","path":"root:Accounts::field:billing_address_street","displaypath":"Accounts","sort":"sortable","sortpriority":"","width":200,"jointype":"required","sqlfunction":"-","summaryfunction":"","customsqlfunction":"","valuetype":"","groupby":"no","link":"no","editable":"","fixedvalue":"","assigntovalue":"","formulavalue":"","formulasequence":"","widget":"","overridetype":"","overridealignment":""},{"fieldid":"k7315c4dfb3f7315af7f00c9ccde5","sequence":"09","fieldname":"C\\u00f3digo postal de la direcci\\u00f3n principal","name":"C\\u00f3digo postal de la direcci\\u00f3n principal","display":"yes","path":"root:Accounts::field:billing_address_postalcode","displaypath":"Accounts","sort":"sortable","sortpriority":"","width":"100","jointype":"required","sqlfunction":"-","summaryfunction":"","customsqlfunction":"","valuetype":"","groupby":"no","link":"no","editable":"","fixedvalue":"","assigntovalue":"","formulavalue":"","formulasequence":"","widget":"","overridetype":"","overridealignment":""},{"fieldid":"k04ddf3a68ef9845d6d6c5b31b3e8","sequence":10,"fieldname":"Poblaci\\u00f3n de la direcci\\u00f3n principal","name":"Poblaci\\u00f3n de la direcci\\u00f3n principal","display":"yes","path":"root:Accounts::field:billing_address_city","displaypath":"Accounts","sort":"sortable","sortpriority":"","width":"100","jointype":"required","sqlfunction":"-","summaryfunction":"","customsqlfunction":"","valuetype":"","groupby":"no","link":"no","editable":"","fixedvalue":"","assigntovalue":"","formulavalue":"","formulasequence":"","widget":"","overridetype":"","overridealignment":""},{"fieldid":"k0d660a2bff6a2d1d8beea526709e","sequence":11,"fieldname":"Provincia de la direcci\\u00f3n principal","name":"Provincia de la direcci\\u00f3n principal","display":"yes","path":"root:Accounts::field:billing_address_state","displaypath":"Accounts","sort":"sortable","sortpriority":"","width":"100","jointype":"required","sqlfunction":"-","summaryfunction":"","customsqlfunction":"","valuetype":"","groupby":"no","link":"no","editable":"","fixedvalue":"","assigntovalue":"","formulavalue":"","formulasequence":"","widget":"","overridetype":"","overridealignment":""},{"fieldid":"kcb52a603ec04b89329f1eb9bc4af","sequence":12,"fieldname":"Tipo de relaci\\u00f3n","name":"Tipo de relaci\\u00f3n (actual)","display":"yes","path":"root:Accounts::field:stic_relationship_type_c","displaypath":"Accounts","sort":"sortable","sortpriority":"","width":200,"jointype":"required","sqlfunction":"-","summaryfunction":"","customsqlfunction":"","valuetype":"","groupby":"no","link":"no","editable":"","fixedvalue":"","assigntovalue":"","formulavalue":"","formulasequence":"","widget":"","overridetype":"","overridealignment":""},{"fieldid":"ke9384695727a17fcd620e3c263b9","sequence":13,"fieldname":"ID","name":"ID","display":"no","path":"root:Accounts::field:id","displaypath":"Accounts","sort":"-","sortpriority":"","width":"100","jointype":"required","sqlfunction":"-","summaryfunction":"","customsqlfunction":"","valuetype":"","groupby":"yes","link":"no","editable":"","fixedvalue":"","assigntovalue":"","formulavalue":"","formulasequence":"","widget":"","overridetype":"","overridealignment":""},{"fieldid":"k62e5f41083b55b531c3998f77f9b","sequence":14,"fieldname":"ID:","name":"Personas vinculadas","display":"yes","path":"root:Accounts::link:Accounts:contacts::field:id","displaypath":"Accounts::Contactos","sort":"sortable","sortpriority":"","width":"100","jointype":"optional","sqlfunction":"COUNT","summaryfunction":"","customsqlfunction":"","valuetype":"","groupby":"no","link":"no","editable":"","fixedvalue":"","assigntovalue":"","formulavalue":"","formulasequence":"","widget":"","overridetype":"","overridealignment":""}]', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `kreportsnapshots`
--

CREATE TABLE IF NOT EXISTS `kreportsnapshots` (
  `id` char(36) NOT NULL,
  `report_id` char(36) DEFAULT NULL,
  `snapshotdate` datetime DEFAULT NULL,
  `snapshotquery` text,
  `data` longblob
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `kreportsnapshotsdata`
--

CREATE TABLE IF NOT EXISTS `kreportsnapshotsdata` (
  `snapshot_id` char(36) DEFAULT NULL,
  `record_id` double DEFAULT NULL,
  `data` blob
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `leads`
--

CREATE TABLE IF NOT EXISTS `leads` (
  `id` char(36) NOT NULL,
  `date_entered` datetime DEFAULT NULL,
  `date_modified` datetime DEFAULT NULL,
  `modified_user_id` char(36) DEFAULT NULL,
  `created_by` char(36) DEFAULT NULL,
  `description` text,
  `deleted` tinyint(1) DEFAULT '0',
  `assigned_user_id` char(36) DEFAULT NULL,
  `salutation` varchar(255) DEFAULT NULL,
  `first_name` varchar(100) DEFAULT NULL,
  `last_name` varchar(100) DEFAULT NULL,
  `title` varchar(100) DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `department` varchar(100) DEFAULT NULL,
  `do_not_call` tinyint(1) DEFAULT '0',
  `phone_home` varchar(100) DEFAULT NULL,
  `phone_mobile` varchar(100) DEFAULT NULL,
  `phone_work` varchar(100) DEFAULT NULL,
  `phone_other` varchar(100) DEFAULT NULL,
  `phone_fax` varchar(100) DEFAULT NULL,
  `lawful_basis` text,
  `date_reviewed` date DEFAULT NULL,
  `lawful_basis_source` varchar(100) DEFAULT NULL,
  `primary_address_street` varchar(150) DEFAULT NULL,
  `primary_address_city` varchar(100) DEFAULT NULL,
  `primary_address_state` varchar(100) DEFAULT NULL,
  `primary_address_postalcode` varchar(20) DEFAULT NULL,
  `primary_address_country` varchar(255) DEFAULT NULL,
  `alt_address_street` varchar(150) DEFAULT NULL,
  `alt_address_city` varchar(100) DEFAULT NULL,
  `alt_address_state` varchar(100) DEFAULT NULL,
  `alt_address_postalcode` varchar(20) DEFAULT NULL,
  `alt_address_country` varchar(255) DEFAULT NULL,
  `assistant` varchar(75) DEFAULT NULL,
  `assistant_phone` varchar(100) DEFAULT NULL,
  `converted` tinyint(1) DEFAULT '0',
  `refered_by` varchar(100) DEFAULT NULL,
  `lead_source` varchar(100) DEFAULT NULL,
  `lead_source_description` text,
  `status` varchar(100) DEFAULT NULL,
  `status_description` text,
  `reports_to_id` char(36) DEFAULT NULL,
  `account_name` varchar(255) DEFAULT NULL,
  `account_description` text,
  `contact_id` char(36) DEFAULT NULL,
  `account_id` char(36) DEFAULT NULL,
  `opportunity_id` char(36) DEFAULT NULL,
  `opportunity_name` varchar(255) DEFAULT NULL,
  `opportunity_amount` varchar(50) DEFAULT NULL,
  `campaign_id` char(36) DEFAULT NULL,
  `birthdate` date DEFAULT NULL,
  `portal_name` varchar(255) DEFAULT NULL,
  `portal_app` varchar(255) DEFAULT NULL,
  `website` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `leads_audit`
--

CREATE TABLE IF NOT EXISTS `leads_audit` (
  `id` char(36) NOT NULL,
  `parent_id` char(36) NOT NULL,
  `date_created` datetime DEFAULT NULL,
  `created_by` varchar(36) DEFAULT NULL,
  `field_name` varchar(100) DEFAULT NULL,
  `data_type` varchar(100) DEFAULT NULL,
  `before_value_string` varchar(255) DEFAULT NULL,
  `after_value_string` varchar(255) DEFAULT NULL,
  `before_value_text` text,
  `after_value_text` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `leads_cstm`
--

CREATE TABLE IF NOT EXISTS `leads_cstm` (
  `id_c` char(36) NOT NULL,
  `jjwg_maps_lng_c` float(11,8) DEFAULT '0.00000000',
  `jjwg_maps_lat_c` float(10,8) DEFAULT '0.00000000',
  `jjwg_maps_geocode_status_c` varchar(255) DEFAULT NULL,
  `jjwg_maps_address_c` varchar(255) DEFAULT NULL,
  `stic_identification_type_c` varchar(100) DEFAULT NULL,
  `stic_identification_number_c` varchar(255) DEFAULT NULL,
  `stic_language_c` varchar(100) DEFAULT NULL,
  `stic_gender_c` varchar(100) DEFAULT NULL,
  `stic_employment_status_c` varchar(100) DEFAULT NULL,
  `stic_professional_sector_c` varchar(100) DEFAULT NULL,
  `stic_professional_sector_other_c` varchar(255) DEFAULT NULL,
  `stic_primary_address_type_c` varchar(100) DEFAULT NULL,
  `stic_primary_address_county_c` varchar(100) DEFAULT NULL,
  `stic_primary_address_region_c` varchar(100) DEFAULT NULL,
  `stic_alt_address_type_c` varchar(100) DEFAULT NULL,
  `stic_alt_address_county_c` varchar(100) DEFAULT NULL,
  `stic_alt_address_region_c` varchar(100) DEFAULT NULL,
  `stic_acquisition_channel_c` varchar(100) DEFAULT NULL,
  `stic_do_not_send_postal_mail_c` tinyint(1) DEFAULT '0',
  `stic_postal_mail_return_reason_c` varchar(100) DEFAULT NULL,
  `stic_referral_agent_c` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `leads_documents_1_c`
--

CREATE TABLE IF NOT EXISTS `leads_documents_1_c` (
  `id` varchar(36) NOT NULL,
  `date_modified` datetime DEFAULT NULL,
  `deleted` tinyint(1) DEFAULT '0',
  `leads_documents_1leads_ida` varchar(36) DEFAULT NULL,
  `leads_documents_1documents_idb` varchar(36) DEFAULT NULL,
  `document_revision_id` varchar(36) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `linked_documents`
--

CREATE TABLE IF NOT EXISTS `linked_documents` (
  `id` varchar(36) NOT NULL,
  `parent_id` varchar(36) DEFAULT NULL,
  `parent_type` varchar(25) DEFAULT NULL,
  `document_id` varchar(36) DEFAULT NULL,
  `document_revision_id` varchar(36) DEFAULT NULL,
  `date_modified` datetime DEFAULT NULL,
  `deleted` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `meetings`
--

CREATE TABLE IF NOT EXISTS `meetings` (
  `id` char(36) NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `date_entered` datetime DEFAULT NULL,
  `date_modified` datetime DEFAULT NULL,
  `modified_user_id` char(36) DEFAULT NULL,
  `created_by` char(36) DEFAULT NULL,
  `description` text,
  `deleted` tinyint(1) DEFAULT '0',
  `assigned_user_id` char(36) DEFAULT NULL,
  `location` varchar(50) DEFAULT NULL,
  `password` varchar(50) DEFAULT NULL,
  `join_url` varchar(200) DEFAULT NULL,
  `host_url` varchar(400) DEFAULT NULL,
  `displayed_url` varchar(400) DEFAULT NULL,
  `creator` varchar(50) DEFAULT NULL,
  `external_id` varchar(50) DEFAULT NULL,
  `duration_hours` int(3) DEFAULT NULL,
  `duration_minutes` int(2) DEFAULT NULL,
  `date_start` datetime DEFAULT NULL,
  `date_end` datetime DEFAULT NULL,
  `parent_type` varchar(100) DEFAULT NULL,
  `status` varchar(100) DEFAULT 'Planned',
  `type` varchar(255) DEFAULT 'Sugar',
  `parent_id` char(36) DEFAULT NULL,
  `reminder_time` int(11) DEFAULT '-1',
  `email_reminder_time` int(11) DEFAULT '-1',
  `email_reminder_sent` tinyint(1) DEFAULT '0',
  `outlook_id` varchar(255) DEFAULT NULL,
  `sequence` int(11) DEFAULT '0',
  `repeat_type` varchar(36) DEFAULT NULL,
  `repeat_interval` int(3) DEFAULT '1',
  `repeat_dow` varchar(7) DEFAULT NULL,
  `repeat_until` date DEFAULT NULL,
  `repeat_count` int(7) DEFAULT NULL,
  `repeat_parent_id` char(36) DEFAULT NULL,
  `recurring_source` varchar(36) DEFAULT NULL,
  `gsync_id` varchar(1024) DEFAULT NULL,
  `gsync_lastsync` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `meetings_contacts`
--

CREATE TABLE IF NOT EXISTS `meetings_contacts` (
  `id` varchar(36) NOT NULL,
  `meeting_id` varchar(36) DEFAULT NULL,
  `contact_id` varchar(36) DEFAULT NULL,
  `required` varchar(1) DEFAULT '1',
  `accept_status` varchar(25) DEFAULT 'none',
  `date_modified` datetime DEFAULT NULL,
  `deleted` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `meetings_cstm`
--

CREATE TABLE IF NOT EXISTS `meetings_cstm` (
  `id_c` char(36) NOT NULL,
  `jjwg_maps_lng_c` float(11,8) DEFAULT '0.00000000',
  `jjwg_maps_lat_c` float(10,8) DEFAULT '0.00000000',
  `jjwg_maps_geocode_status_c` varchar(255) DEFAULT NULL,
  `jjwg_maps_address_c` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `meetings_leads`
--

CREATE TABLE IF NOT EXISTS `meetings_leads` (
  `id` varchar(36) NOT NULL,
  `meeting_id` varchar(36) DEFAULT NULL,
  `lead_id` varchar(36) DEFAULT NULL,
  `required` varchar(1) DEFAULT '1',
  `accept_status` varchar(25) DEFAULT 'none',
  `date_modified` datetime DEFAULT NULL,
  `deleted` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `meetings_users`
--

CREATE TABLE IF NOT EXISTS `meetings_users` (
  `id` varchar(36) NOT NULL,
  `meeting_id` varchar(36) DEFAULT NULL,
  `user_id` varchar(36) DEFAULT NULL,
  `required` varchar(1) DEFAULT '1',
  `accept_status` varchar(25) DEFAULT 'none',
  `date_modified` datetime DEFAULT NULL,
  `deleted` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `notes`
--

CREATE TABLE IF NOT EXISTS `notes` (
  `assigned_user_id` char(36) DEFAULT NULL,
  `id` char(36) NOT NULL,
  `date_entered` datetime DEFAULT NULL,
  `date_modified` datetime DEFAULT NULL,
  `modified_user_id` char(36) DEFAULT NULL,
  `created_by` char(36) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `file_mime_type` varchar(100) DEFAULT NULL,
  `filename` varchar(255) DEFAULT NULL,
  `parent_type` varchar(255) DEFAULT NULL,
  `parent_id` char(36) DEFAULT NULL,
  `contact_id` char(36) DEFAULT NULL,
  `portal_flag` tinyint(1) DEFAULT NULL,
  `embed_flag` tinyint(1) DEFAULT '0',
  `description` text,
  `deleted` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `oauth2clients`
--

CREATE TABLE IF NOT EXISTS `oauth2clients` (
  `id` char(36) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `date_entered` datetime DEFAULT NULL,
  `date_modified` datetime DEFAULT NULL,
  `modified_user_id` char(36) DEFAULT NULL,
  `created_by` char(36) DEFAULT NULL,
  `description` text,
  `deleted` tinyint(1) DEFAULT '0',
  `secret` varchar(4000) DEFAULT NULL,
  `redirect_url` varchar(255) DEFAULT NULL,
  `is_confidential` tinyint(1) DEFAULT '1',
  `allowed_grant_type` varchar(255) DEFAULT 'password',
  `duration_value` int(11) DEFAULT NULL,
  `duration_amount` int(11) DEFAULT NULL,
  `duration_unit` varchar(255) DEFAULT 'Duration Unit',
  `assigned_user_id` char(36) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `oauth2tokens`
--

CREATE TABLE IF NOT EXISTS `oauth2tokens` (
  `id` char(36) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `date_entered` datetime DEFAULT NULL,
  `date_modified` datetime DEFAULT NULL,
  `modified_user_id` char(36) DEFAULT NULL,
  `created_by` char(36) DEFAULT NULL,
  `description` text,
  `deleted` tinyint(1) DEFAULT '0',
  `token_is_revoked` tinyint(1) DEFAULT NULL,
  `token_type` varchar(255) DEFAULT NULL,
  `access_token_expires` datetime DEFAULT NULL,
  `access_token` varchar(4000) DEFAULT NULL,
  `refresh_token` varchar(4000) DEFAULT NULL,
  `refresh_token_expires` datetime DEFAULT NULL,
  `grant_type` varchar(255) DEFAULT NULL,
  `state` varchar(1024) DEFAULT NULL,
  `client` char(36) DEFAULT NULL,
  `assigned_user_id` char(36) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `oauth_consumer`
--

CREATE TABLE IF NOT EXISTS `oauth_consumer` (
  `id` char(36) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `date_entered` datetime DEFAULT NULL,
  `date_modified` datetime DEFAULT NULL,
  `modified_user_id` char(36) DEFAULT NULL,
  `created_by` char(36) DEFAULT NULL,
  `description` text,
  `deleted` tinyint(1) DEFAULT '0',
  `assigned_user_id` char(36) DEFAULT NULL,
  `c_key` varchar(255) DEFAULT NULL,
  `c_secret` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `oauth_nonce`
--

CREATE TABLE IF NOT EXISTS `oauth_nonce` (
  `conskey` varchar(32) NOT NULL,
  `nonce` varchar(32) NOT NULL,
  `nonce_ts` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `oauth_tokens`
--

CREATE TABLE IF NOT EXISTS `oauth_tokens` (
  `id` char(36) NOT NULL,
  `secret` varchar(32) DEFAULT NULL,
  `tstate` varchar(1) DEFAULT NULL,
  `consumer` char(36) NOT NULL,
  `token_ts` bigint(20) DEFAULT NULL,
  `verify` varchar(32) DEFAULT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  `callback_url` varchar(255) DEFAULT NULL,
  `assigned_user_id` char(36) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `opportunities`
--

CREATE TABLE IF NOT EXISTS `opportunities` (
  `id` char(36) NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `date_entered` datetime DEFAULT NULL,
  `date_modified` datetime DEFAULT NULL,
  `modified_user_id` char(36) DEFAULT NULL,
  `created_by` char(36) DEFAULT NULL,
  `description` text,
  `deleted` tinyint(1) DEFAULT '0',
  `assigned_user_id` char(36) DEFAULT NULL,
  `opportunity_type` varchar(255) DEFAULT NULL,
  `campaign_id` char(36) DEFAULT NULL,
  `lead_source` varchar(50) DEFAULT NULL,
  `amount` double DEFAULT NULL,
  `amount_usdollar` double DEFAULT NULL,
  `currency_id` char(36) DEFAULT NULL,
  `date_closed` date DEFAULT NULL,
  `next_step` varchar(100) DEFAULT NULL,
  `sales_stage` varchar(255) DEFAULT NULL,
  `probability` double DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `opportunities_audit`
--

CREATE TABLE IF NOT EXISTS `opportunities_audit` (
  `id` char(36) NOT NULL,
  `parent_id` char(36) NOT NULL,
  `date_created` datetime DEFAULT NULL,
  `created_by` varchar(36) DEFAULT NULL,
  `field_name` varchar(100) DEFAULT NULL,
  `data_type` varchar(100) DEFAULT NULL,
  `before_value_string` varchar(255) DEFAULT NULL,
  `after_value_string` varchar(255) DEFAULT NULL,
  `before_value_text` text,
  `after_value_text` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `opportunities_contacts`
--

CREATE TABLE IF NOT EXISTS `opportunities_contacts` (
  `id` varchar(36) NOT NULL,
  `contact_id` varchar(36) DEFAULT NULL,
  `opportunity_id` varchar(36) DEFAULT NULL,
  `contact_role` varchar(50) DEFAULT NULL,
  `date_modified` datetime DEFAULT NULL,
  `deleted` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `opportunities_cstm`
--

CREATE TABLE IF NOT EXISTS `opportunities_cstm` (
  `id_c` char(36) NOT NULL,
  `jjwg_maps_lng_c` float(11,8) DEFAULT '0.00000000',
  `jjwg_maps_lat_c` float(10,8) DEFAULT '0.00000000',
  `jjwg_maps_geocode_status_c` varchar(255) DEFAULT NULL,
  `jjwg_maps_address_c` varchar(255) DEFAULT NULL,
  `stic_presentation_date_c` date DEFAULT NULL,
  `stic_amount_received_c` decimal(26,2) DEFAULT NULL,
  `stic_resolution_date_c` date DEFAULT NULL,
  `stic_advance_date_c` date DEFAULT NULL,
  `stic_justification_date_c` date DEFAULT NULL,
  `stic_amount_awarded_c` decimal(26,2) DEFAULT NULL,
  `stic_target_c` varchar(100) DEFAULT NULL,
  `stic_documentation_to_deliver_c` text,
  `stic_payment_date_c` date DEFAULT NULL,
  `stic_status_c` varchar(100) DEFAULT NULL,
  `stic_type_c` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `outbound_email`
--

CREATE TABLE IF NOT EXISTS `outbound_email` (
  `id` char(36) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `type` varchar(15) DEFAULT 'user',
  `user_id` char(36) NOT NULL,
  `smtp_from_name` varchar(255) DEFAULT NULL,
  `smtp_from_addr` varchar(255) DEFAULT NULL,
  `mail_sendtype` varchar(8) DEFAULT 'smtp',
  `mail_smtptype` varchar(20) DEFAULT 'other',
  `mail_smtpserver` varchar(100) DEFAULT NULL,
  `mail_smtpport` varchar(5) DEFAULT '0',
  `mail_smtpuser` varchar(100) DEFAULT NULL,
  `mail_smtppass` varchar(100) DEFAULT NULL,
  `mail_smtpauth_req` tinyint(1) DEFAULT '0',
  `mail_smtpssl` varchar(1) DEFAULT '0',
  `date_entered` datetime DEFAULT NULL,
  `date_modified` datetime DEFAULT NULL,
  `modified_user_id` char(36) DEFAULT NULL,
  `created_by` char(36) DEFAULT NULL,
  `deleted` tinyint(1) DEFAULT '0',
  `assigned_user_id` char(36) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `outbound_email`
--

INSERT INTO `outbound_email` (`id`, `name`, `type`, `user_id`, `smtp_from_name`, `smtp_from_addr`, `mail_sendtype`, `mail_smtptype`, `mail_smtpserver`, `mail_smtpport`, `mail_smtpuser`, `mail_smtppass`, `mail_smtpauth_req`, `mail_smtpssl`, `date_entered`, `date_modified`, `modified_user_id`, `created_by`, `deleted`, `assigned_user_id`) VALUES
('33b6f1fe-4f53-5416-36be-5e830d57e6b7', 'system', 'system', '1', NULL, NULL, 'SMTP', 'other', '', '25', '', '', 1, '0', NULL, NULL, NULL, NULL, 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `outbound_email_audit`
--

CREATE TABLE IF NOT EXISTS `outbound_email_audit` (
  `id` char(36) NOT NULL,
  `parent_id` char(36) NOT NULL,
  `date_created` datetime DEFAULT NULL,
  `created_by` varchar(36) DEFAULT NULL,
  `field_name` varchar(100) DEFAULT NULL,
  `data_type` varchar(100) DEFAULT NULL,
  `before_value_string` varchar(255) DEFAULT NULL,
  `after_value_string` varchar(255) DEFAULT NULL,
  `before_value_text` text,
  `after_value_text` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `project`
--

CREATE TABLE IF NOT EXISTS `project` (
  `id` char(36) NOT NULL,
  `date_entered` datetime DEFAULT NULL,
  `date_modified` datetime DEFAULT NULL,
  `assigned_user_id` char(36) DEFAULT NULL,
  `modified_user_id` char(36) DEFAULT NULL,
  `created_by` char(36) DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL,
  `description` text,
  `deleted` tinyint(1) DEFAULT '0',
  `estimated_start_date` date DEFAULT NULL,
  `estimated_end_date` date DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `priority` varchar(255) DEFAULT NULL,
  `override_business_hours` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `projects_accounts`
--

CREATE TABLE IF NOT EXISTS `projects_accounts` (
  `id` varchar(36) NOT NULL,
  `account_id` varchar(36) DEFAULT NULL,
  `project_id` varchar(36) DEFAULT NULL,
  `date_modified` datetime DEFAULT NULL,
  `deleted` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `projects_bugs`
--

CREATE TABLE IF NOT EXISTS `projects_bugs` (
  `id` varchar(36) NOT NULL,
  `bug_id` varchar(36) DEFAULT NULL,
  `project_id` varchar(36) DEFAULT NULL,
  `date_modified` datetime DEFAULT NULL,
  `deleted` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `projects_cases`
--

CREATE TABLE IF NOT EXISTS `projects_cases` (
  `id` varchar(36) NOT NULL,
  `case_id` varchar(36) DEFAULT NULL,
  `project_id` varchar(36) DEFAULT NULL,
  `date_modified` datetime DEFAULT NULL,
  `deleted` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `projects_contacts`
--

CREATE TABLE IF NOT EXISTS `projects_contacts` (
  `id` varchar(36) NOT NULL,
  `contact_id` varchar(36) DEFAULT NULL,
  `project_id` varchar(36) DEFAULT NULL,
  `date_modified` datetime DEFAULT NULL,
  `deleted` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `projects_opportunities`
--

CREATE TABLE IF NOT EXISTS `projects_opportunities` (
  `id` varchar(36) NOT NULL,
  `opportunity_id` varchar(36) DEFAULT NULL,
  `project_id` varchar(36) DEFAULT NULL,
  `date_modified` datetime DEFAULT NULL,
  `deleted` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `projects_products`
--

CREATE TABLE IF NOT EXISTS `projects_products` (
  `id` varchar(36) NOT NULL,
  `product_id` varchar(36) DEFAULT NULL,
  `project_id` varchar(36) DEFAULT NULL,
  `date_modified` datetime DEFAULT NULL,
  `deleted` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `project_contacts_1_c`
--

CREATE TABLE IF NOT EXISTS `project_contacts_1_c` (
  `id` varchar(36) NOT NULL,
  `date_modified` datetime DEFAULT NULL,
  `deleted` tinyint(1) DEFAULT '0',
  `project_contacts_1project_ida` varchar(36) DEFAULT NULL,
  `project_contacts_1contacts_idb` varchar(36) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `project_cstm`
--

CREATE TABLE IF NOT EXISTS `project_cstm` (
  `id_c` char(36) NOT NULL,
  `jjwg_maps_lng_c` float(11,8) DEFAULT '0.00000000',
  `jjwg_maps_lat_c` float(10,8) DEFAULT '0.00000000',
  `jjwg_maps_geocode_status_c` varchar(255) DEFAULT NULL,
  `jjwg_maps_address_c` varchar(255) DEFAULT NULL,
  `stic_location_c` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `project_opportunities_1_c`
--

CREATE TABLE IF NOT EXISTS `project_opportunities_1_c` (
  `id` varchar(36) NOT NULL,
  `date_modified` datetime DEFAULT NULL,
  `deleted` tinyint(1) DEFAULT '0',
  `project_opportunities_1project_ida` varchar(36) DEFAULT NULL,
  `project_opportunities_1opportunities_idb` varchar(36) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `project_task`
--

CREATE TABLE IF NOT EXISTS `project_task` (
  `id` char(36) NOT NULL,
  `date_entered` datetime DEFAULT NULL,
  `date_modified` datetime DEFAULT NULL,
  `project_id` char(36) NOT NULL,
  `project_task_id` int(11) DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `relationship_type` varchar(255) DEFAULT NULL,
  `description` text,
  `predecessors` text,
  `date_start` date DEFAULT NULL,
  `time_start` int(11) DEFAULT NULL,
  `time_finish` int(11) DEFAULT NULL,
  `date_finish` date DEFAULT NULL,
  `duration` int(11) DEFAULT NULL,
  `duration_unit` text,
  `actual_duration` int(11) DEFAULT NULL,
  `percent_complete` int(11) DEFAULT NULL,
  `date_due` date DEFAULT NULL,
  `time_due` time DEFAULT NULL,
  `parent_task_id` int(11) DEFAULT NULL,
  `assigned_user_id` char(36) DEFAULT NULL,
  `modified_user_id` char(36) DEFAULT NULL,
  `priority` varchar(255) DEFAULT NULL,
  `created_by` char(36) DEFAULT NULL,
  `milestone_flag` tinyint(1) DEFAULT NULL,
  `order_number` int(11) DEFAULT '1',
  `task_number` int(11) DEFAULT NULL,
  `estimated_effort` int(11) DEFAULT NULL,
  `actual_effort` int(11) DEFAULT NULL,
  `deleted` tinyint(1) DEFAULT '0',
  `utilization` int(11) DEFAULT '100'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `project_task_audit`
--

CREATE TABLE IF NOT EXISTS `project_task_audit` (
  `id` char(36) NOT NULL,
  `parent_id` char(36) NOT NULL,
  `date_created` datetime DEFAULT NULL,
  `created_by` varchar(36) DEFAULT NULL,
  `field_name` varchar(100) DEFAULT NULL,
  `data_type` varchar(100) DEFAULT NULL,
  `before_value_string` varchar(255) DEFAULT NULL,
  `after_value_string` varchar(255) DEFAULT NULL,
  `before_value_text` text,
  `after_value_text` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `project_users_1_c`
--

CREATE TABLE IF NOT EXISTS `project_users_1_c` (
  `id` varchar(36) NOT NULL,
  `date_modified` datetime DEFAULT NULL,
  `deleted` tinyint(1) DEFAULT '0',
  `project_users_1project_ida` varchar(36) DEFAULT NULL,
  `project_users_1users_idb` varchar(36) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `prospects`
--

CREATE TABLE IF NOT EXISTS `prospects` (
  `id` char(36) NOT NULL,
  `date_entered` datetime DEFAULT NULL,
  `date_modified` datetime DEFAULT NULL,
  `modified_user_id` char(36) DEFAULT NULL,
  `created_by` char(36) DEFAULT NULL,
  `description` text,
  `deleted` tinyint(1) DEFAULT '0',
  `assigned_user_id` char(36) DEFAULT NULL,
  `salutation` varchar(255) DEFAULT NULL,
  `first_name` varchar(100) DEFAULT NULL,
  `last_name` varchar(100) DEFAULT NULL,
  `title` varchar(100) DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `department` varchar(255) DEFAULT NULL,
  `do_not_call` tinyint(1) DEFAULT '0',
  `phone_home` varchar(100) DEFAULT NULL,
  `phone_mobile` varchar(100) DEFAULT NULL,
  `phone_work` varchar(100) DEFAULT NULL,
  `phone_other` varchar(100) DEFAULT NULL,
  `phone_fax` varchar(100) DEFAULT NULL,
  `lawful_basis` text,
  `date_reviewed` date DEFAULT NULL,
  `lawful_basis_source` varchar(100) DEFAULT NULL,
  `primary_address_street` varchar(150) DEFAULT NULL,
  `primary_address_city` varchar(100) DEFAULT NULL,
  `primary_address_state` varchar(100) DEFAULT NULL,
  `primary_address_postalcode` varchar(20) DEFAULT NULL,
  `primary_address_country` varchar(255) DEFAULT NULL,
  `alt_address_street` varchar(150) DEFAULT NULL,
  `alt_address_city` varchar(100) DEFAULT NULL,
  `alt_address_state` varchar(100) DEFAULT NULL,
  `alt_address_postalcode` varchar(20) DEFAULT NULL,
  `alt_address_country` varchar(255) DEFAULT NULL,
  `assistant` varchar(75) DEFAULT NULL,
  `assistant_phone` varchar(100) DEFAULT NULL,
  `tracker_key` int(11) NOT NULL,
  `birthdate` date DEFAULT NULL,
  `lead_id` char(36) DEFAULT NULL,
  `account_name` varchar(150) DEFAULT NULL,
  `campaign_id` char(36) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `prospects_cstm`
--

CREATE TABLE IF NOT EXISTS `prospects_cstm` (
  `id_c` char(36) NOT NULL,
  `jjwg_maps_lng_c` float(11,8) DEFAULT '0.00000000',
  `jjwg_maps_lat_c` float(10,8) DEFAULT '0.00000000',
  `jjwg_maps_geocode_status_c` varchar(255) DEFAULT NULL,
  `jjwg_maps_address_c` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `prospect_lists`
--

CREATE TABLE IF NOT EXISTS `prospect_lists` (
  `assigned_user_id` char(36) DEFAULT NULL,
  `id` char(36) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `list_type` varchar(100) DEFAULT NULL,
  `date_entered` datetime DEFAULT NULL,
  `date_modified` datetime DEFAULT NULL,
  `modified_user_id` char(36) DEFAULT NULL,
  `created_by` char(36) DEFAULT NULL,
  `deleted` tinyint(1) DEFAULT NULL,
  `description` text,
  `domain_name` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `prospect_lists_prospects`
--

CREATE TABLE IF NOT EXISTS `prospect_lists_prospects` (
  `id` varchar(36) NOT NULL,
  `prospect_list_id` varchar(36) DEFAULT NULL,
  `related_id` varchar(36) DEFAULT NULL,
  `related_type` varchar(25) DEFAULT NULL,
  `date_modified` datetime DEFAULT NULL,
  `deleted` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `prospect_list_campaigns`
--

CREATE TABLE IF NOT EXISTS `prospect_list_campaigns` (
  `id` varchar(36) NOT NULL,
  `prospect_list_id` varchar(36) DEFAULT NULL,
  `campaign_id` varchar(36) DEFAULT NULL,
  `date_modified` datetime DEFAULT NULL,
  `deleted` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `relationships`
--

CREATE TABLE IF NOT EXISTS `relationships` (
  `id` char(36) NOT NULL,
  `relationship_name` varchar(150) DEFAULT NULL,
  `lhs_module` varchar(100) DEFAULT NULL,
  `lhs_table` varchar(64) DEFAULT NULL,
  `lhs_key` varchar(64) DEFAULT NULL,
  `rhs_module` varchar(100) DEFAULT NULL,
  `rhs_table` varchar(64) DEFAULT NULL,
  `rhs_key` varchar(64) DEFAULT NULL,
  `join_table` varchar(64) DEFAULT NULL,
  `join_key_lhs` varchar(64) DEFAULT NULL,
  `join_key_rhs` varchar(64) DEFAULT NULL,
  `relationship_type` varchar(64) DEFAULT NULL,
  `relationship_role_column` varchar(64) DEFAULT NULL,
  `relationship_role_column_value` varchar(50) DEFAULT NULL,
  `reverse` tinyint(1) DEFAULT '0',
  `deleted` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `relationships`
--

INSERT INTO `relationships` (`id`, `relationship_name`, `lhs_module`, `lhs_table`, `lhs_key`, `rhs_module`, `rhs_table`, `rhs_key`, `join_table`, `join_key_lhs`, `join_key_rhs`, `relationship_type`, `relationship_role_column`, `relationship_role_column_value`, `reverse`, `deleted`) VALUES
('10d16b13-66ab-1a14-9a72-5f003b88da69', 'aos_product_categories_assigned_user', 'Users', 'users', 'id', 'AOS_Product_Categories', 'aos_product_categories', 'assigned_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('114648d8-3354-78b3-0b05-5f003b0140a0', 'projects_calls', 'Project', 'project', 'id', 'Calls', 'calls', 'parent_id', NULL, NULL, NULL, 'one-to-many', 'parent_type', 'Project', 0, 0),
('121f550d-2dc9-ba5a-97fc-5f003b8b6a3b', 'securitygroups_aos_product_categories', 'SecurityGroups', 'securitygroups', 'id', 'AOS_Product_Categories', 'aos_product_categories', 'id', 'securitygroups_records', 'securitygroup_id', 'record_id', 'many-to-many', 'module', 'AOS_Product_Categories', 0, 0),
('1260a910-30bc-1915-6d57-5f003b5c2bf3', 'spots_modified_user', 'Users', 'users', 'id', 'Spots', 'spots', 'modified_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('13121103-cdcf-d1f1-741f-5f003b16520c', 'sub_product_categories', 'AOS_Product_Categories', 'aos_product_categories', 'id', 'AOS_Product_Categories', 'aos_product_categories', 'parent_category_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('157dea11-c366-3c21-7042-5f003beceee0', 'projects_emails', 'Project', 'project', 'id', 'Emails', 'emails', 'parent_id', NULL, NULL, NULL, 'one-to-many', 'parent_type', 'Project', 0, 0),
('15a66bad-8545-e7da-266a-5f003b6c427a', 'aos_products_modified_user', 'Users', 'users', 'id', 'AOS_Products', 'aos_products', 'modified_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('15f7d87c-cbcd-5a9a-3a9e-5f003b46e8d4', 'stic_validation_actions_modified_user', 'Users', 'users', 'id', 'stic_Validation_Actions', 'stic_validation_actions', 'modified_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('1674bf35-d4ee-a938-46cc-5f003bc263a8', 'projects_project_tasks', 'Project', 'project', 'id', 'ProjectTask', 'project_task', 'project_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('16b3e040-c7cd-afe2-09d7-5f003ba26e66', 'aos_products_created_by', 'Users', 'users', 'id', 'AOS_Products', 'aos_products', 'created_by', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('17286a2f-f19b-be77-28c3-5f003b7c5656', 'spots_created_by', 'Users', 'users', 'id', 'Spots', 'spots', 'created_by', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('174e095e-79cc-a7ed-895c-5f003b0c5764', 'projects_assigned_user', 'Users', 'users', 'id', 'Project', 'project', 'assigned_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('17b4ec4a-6163-ecb6-2856-5f003bcde520', 'stic_validation_actions_created_by', 'Users', 'users', 'id', 'stic_Validation_Actions', 'stic_validation_actions', 'created_by', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('17b93ae9-5224-812f-ac8b-5f003b0d566d', 'aos_products_assigned_user', 'Users', 'users', 'id', 'AOS_Products', 'aos_products', 'assigned_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('182e5bc6-cbea-3a40-92e1-5f003bb81083', 'projects_modified_user', 'Users', 'users', 'id', 'Project', 'project', 'modified_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('183039ac-8e84-6599-0218-5f003b343d8b', 'spots_assigned_user', 'Users', 'users', 'id', 'Spots', 'spots', 'assigned_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('18d97051-2aaf-8c82-af1c-5f003bf23534', 'stic_validation_actions_assigned_user', 'Users', 'users', 'id', 'stic_Validation_Actions', 'stic_validation_actions', 'assigned_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('190c594c-77a5-2bbe-b161-5f003bf85c61', 'securitygroups_spots', 'SecurityGroups', 'securitygroups', 'id', 'Spots', 'spots', 'id', 'securitygroups_records', 'securitygroup_id', 'record_id', 'many-to-many', 'module', 'Spots', 0, 0),
('191bafc7-9a23-626d-c173-5f003be3e1d5', 'securitygroups_aos_products', 'SecurityGroups', 'securitygroups', 'id', 'AOS_Products', 'aos_products', 'id', 'securitygroups_records', 'securitygroup_id', 'record_id', 'many-to-many', 'module', 'AOS_Products', 0, 0),
('193bd0c8-ad25-69ba-ed78-5f003b6817a6', 'projects_created_by', 'Users', 'users', 'id', 'Project', 'project', 'created_by', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('19c416ba-3d69-68d3-6f19-5f003b8b66f0', 'securitygroups_stic_validation_actions', 'SecurityGroups', 'securitygroups', 'id', 'stic_Validation_Actions', 'stic_validation_actions', 'id', 'securitygroups_records', 'securitygroup_id', 'record_id', 'many-to-many', 'module', 'stic_Validation_Actions', 0, 0),
('19ea9685-1be3-03f9-4846-5f003b80d698', 'product_categories', 'AOS_Product_Categories', 'aos_product_categories', 'id', 'AOS_Products', 'aos_products', 'aos_product_category_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('1b445846-cd56-8979-d986-5f003b11967a', 'aobh_businesshours_modified_user', 'Users', 'users', 'id', 'AOBH_BusinessHours', 'aobh_businesshours', 'modified_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('1c301a0a-7521-22cc-929a-5f003be8b0aa', 'securitygroups_projecttask', 'SecurityGroups', 'securitygroups', 'id', 'ProjectTask', 'project_task', 'id', 'securitygroups_records', 'securitygroup_id', 'record_id', 'many-to-many', 'module', 'ProjectTask', 0, 0),
('1c474272-fcf7-e84f-0e40-5f003ba0c6b0', 'aobh_businesshours_created_by', 'Users', 'users', 'id', 'AOBH_BusinessHours', 'aobh_businesshours', 'created_by', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('1c5096ed-5ead-afa5-115a-5f003b9c894e', 'accounts_bugs', 'Accounts', 'accounts', 'id', 'Bugs', 'bugs', 'id', 'accounts_bugs', 'account_id', 'bug_id', 'many-to-many', NULL, NULL, 0, 0),
('1d1f5747-ab1d-ec77-9c73-5f003bd59e8c', 'aos_products_quotes_modified_user', 'Users', 'users', 'id', 'AOS_Products_Quotes', 'aos_products_quotes', 'modified_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('1d51d69e-6fd6-ac22-d462-5f003bdda5fb', 'accounts_contacts', 'Accounts', 'accounts', 'id', 'Contacts', 'contacts', 'id', 'accounts_contacts', 'account_id', 'contact_id', 'many-to-many', NULL, NULL, 0, 0),
('1e18d6b1-627e-2eac-1d30-5f003b535f05', 'sugarfeed_modified_user', 'Users', 'users', 'id', 'SugarFeed', 'sugarfeed', 'modified_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('1e349142-be3b-0539-45c8-5f003ba2cceb', 'accounts_opportunities', 'Accounts', 'accounts', 'id', 'Opportunities', 'opportunities', 'id', 'accounts_opportunities', 'account_id', 'opportunity_id', 'many-to-many', NULL, NULL, 0, 0),
('1eb908b8-f3c3-fe87-7eef-5f003bb0609b', 'project_tasks_notes', 'ProjectTask', 'project_task', 'id', 'Notes', 'notes', 'parent_id', NULL, NULL, NULL, 'one-to-many', 'parent_type', 'ProjectTask', 0, 0),
('1f092453-cf95-25c0-8330-5f003bb93164', 'calls_contacts', 'Calls', 'calls', 'id', 'Contacts', 'contacts', 'id', 'calls_contacts', 'call_id', 'contact_id', 'many-to-many', NULL, NULL, 0, 0),
('1f4d0cbf-b5bd-cac8-e025-5f003b995cc0', 'sugarfeed_created_by', 'Users', 'users', 'id', 'SugarFeed', 'sugarfeed', 'created_by', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('1fd38ee7-a6f1-7566-42dc-5f003b453c35', 'project_tasks_tasks', 'ProjectTask', 'project_task', 'id', 'Tasks', 'tasks', 'parent_id', NULL, NULL, NULL, 'one-to-many', 'parent_type', 'ProjectTask', 0, 0),
('1fe0fcbf-4796-a3fc-d1c7-5f003b6ec7cf', 'calls_users', 'Calls', 'calls', 'id', 'Users', 'users', 'id', 'calls_users', 'call_id', 'user_id', 'many-to-many', NULL, NULL, 0, 0),
('202a40f7-bdba-4e49-507b-5f003b73418b', 'sugarfeed_assigned_user', 'Users', 'users', 'id', 'SugarFeed', 'sugarfeed', 'assigned_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('20a72f72-baad-8a60-fbf2-5f003b0f3ba0', 'calls_leads', 'Calls', 'calls', 'id', 'Leads', 'leads', 'id', 'calls_leads', 'call_id', 'lead_id', 'many-to-many', NULL, NULL, 0, 0),
('219abbbf-2683-bc57-4763-5f003b035b36', 'cases_bugs', 'Cases', 'cases', 'id', 'Bugs', 'bugs', 'id', 'cases_bugs', 'case_id', 'bug_id', 'many-to-many', NULL, NULL, 0, 0),
('228bfdb5-c61f-2c90-36a0-5f003b9b58dc', 'eapm_modified_user', 'Users', 'users', 'id', 'EAPM', 'eapm', 'modified_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('229fb765-63ab-f342-b391-5f003b99d6ca', 'contacts_bugs', 'Contacts', 'contacts', 'id', 'Bugs', 'bugs', 'id', 'contacts_bugs', 'contact_id', 'bug_id', 'many-to-many', NULL, NULL, 0, 0),
('22edb088-7b71-c6eb-df4b-5f003be9cc95', 'project_tasks_meetings', 'ProjectTask', 'project_task', 'id', 'Meetings', 'meetings', 'parent_id', NULL, NULL, NULL, 'one-to-many', 'parent_type', 'ProjectTask', 0, 0),
('2386f343-b0b0-4408-6c04-5f003bc173d6', 'contacts_cases', 'Contacts', 'contacts', 'id', 'Cases', 'cases', 'id', 'contacts_cases', 'contact_id', 'case_id', 'many-to-many', NULL, NULL, 0, 0),
('23b31da2-9eb5-91f5-aff8-5f003bf18200', 'eapm_created_by', 'Users', 'users', 'id', 'EAPM', 'eapm', 'created_by', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('244824fd-9c78-ced0-5539-5f003b530ae3', 'project_tasks_calls', 'ProjectTask', 'project_task', 'id', 'Calls', 'calls', 'parent_id', NULL, NULL, NULL, 'one-to-many', 'parent_type', 'ProjectTask', 0, 0),
('24493ac9-9dc6-8019-fc2b-5f003be8c62d', 'contacts_users', 'Contacts', 'contacts', 'id', 'Users', 'users', 'id', 'contacts_users', 'contact_id', 'user_id', 'many-to-many', NULL, NULL, 0, 0),
('2495ec76-a1a4-589c-1149-5f003bc1d6af', 'eapm_assigned_user', 'Users', 'users', 'id', 'EAPM', 'eapm', 'assigned_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('24ad10e0-1de7-fb7f-6e72-5f003b76f8ef', 'emails_bugs_rel', 'Emails', 'emails', 'id', 'Bugs', 'bugs', 'id', 'emails_beans', 'email_id', 'bean_id', 'many-to-many', 'bean_module', 'Bugs', 0, 0),
('25bd12ce-94bc-c751-a8f3-5f003b00b08c', 'emails_cases_rel', 'Emails', 'emails', 'id', 'Cases', 'cases', 'id', 'emails_beans', 'email_id', 'bean_id', 'many-to-many', 'bean_module', 'Cases', 0, 0),
('25c3182f-9498-300a-9ec6-5f003b4360f2', 'project_tasks_emails', 'ProjectTask', 'project_task', 'id', 'Emails', 'emails', 'parent_id', NULL, NULL, NULL, 'one-to-many', 'parent_type', 'ProjectTask', 0, 0),
('26692964-b990-ed80-ed92-5f003b9e98a5', 'aos_products_quotes_created_by', 'Users', 'users', 'id', 'AOS_Products_Quotes', 'aos_products_quotes', 'created_by', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('26d1265f-5a98-e76f-696e-5f003bbe197b', 'emails_opportunities_rel', 'Emails', 'emails', 'id', 'Opportunities', 'opportunities', 'id', 'emails_beans', 'email_id', 'bean_id', 'many-to-many', 'bean_module', 'Opportunities', 0, 0),
('27009945-6e22-2f83-56d9-5f003bd0673d', 'oauthkeys_modified_user', 'Users', 'users', 'id', 'OAuthKeys', 'oauth_consumer', 'modified_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('276884a5-79d5-7c06-f24e-5f003b14f17a', 'aos_products_quotes_assigned_user', 'Users', 'users', 'id', 'AOS_Products_Quotes', 'aos_products_quotes', 'assigned_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('27adc336-5ada-ab6a-a1f3-5f003b983dab', 'emails_tasks_rel', 'Emails', 'emails', 'id', 'Tasks', 'tasks', 'id', 'emails_beans', 'email_id', 'bean_id', 'many-to-many', 'bean_module', 'Tasks', 0, 0),
('28779a62-ef54-bf3d-ec4d-5f003bfae687', 'emails_users_rel', 'Emails', 'emails', 'id', 'Users', 'users', 'id', 'emails_beans', 'email_id', 'bean_id', 'many-to-many', 'bean_module', 'Users', 0, 0),
('28f83ca3-c3f9-6118-cac8-5f003b1cc680', 'project_tasks_assigned_user', 'Users', 'users', 'id', 'ProjectTask', 'project_task', 'assigned_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('28fe2cda-77c3-d0d1-d526-5f003ba18088', 'aos_product_quotes_aos_products', 'AOS_Products', 'aos_products', 'id', 'AOS_Products_Quotes', 'aos_products_quotes', 'product_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('29378215-e575-34b7-2d9f-5f003b19f6b2', 'emails_project_task_rel', 'Emails', 'emails', 'id', 'ProjectTask', 'project_task', 'id', 'emails_beans', 'email_id', 'bean_id', 'many-to-many', 'bean_module', 'ProjectTask', 0, 0),
('29957ef4-628d-9fb6-56a5-5f003b30896a', 'emails_projects_rel', 'Emails', 'emails', 'id', 'Project', 'project', 'id', 'emails_beans', 'email_id', 'bean_id', 'many-to-many', 'bean_module', 'Project', 0, 0),
('29e9d944-548c-9e7e-44cd-5f003b5a2532', 'project_tasks_modified_user', 'Users', 'users', 'id', 'ProjectTask', 'project_task', 'modified_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('2a900169-536b-2af4-ca6d-5f003bfabcc5', 'emails_prospects_rel', 'Emails', 'emails', 'id', 'Prospects', 'prospects', 'id', 'emails_beans', 'email_id', 'bean_id', 'many-to-many', 'bean_module', 'Prospects', 0, 0),
('2aa70554-9b6d-20d1-7fcd-5f003b2ca794', 'user_direct_reports', 'Users', 'users', 'id', 'Users', 'users', 'reports_to_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('2afc1e5c-ab1e-76c7-2e94-5f003bb19cae', 'oauthkeys_created_by', 'Users', 'users', 'id', 'OAuthKeys', 'oauth_consumer', 'created_by', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('2b6d8af1-84f5-62ea-3cda-5f003bbddd12', 'aos_line_item_groups_modified_user', 'Users', 'users', 'id', 'AOS_Line_Item_Groups', 'aos_line_item_groups', 'modified_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('2b7bccdc-84c5-2a5c-5b37-5f003b906d7b', 'meetings_contacts', 'Meetings', 'meetings', 'id', 'Contacts', 'contacts', 'id', 'meetings_contacts', 'meeting_id', 'contact_id', 'many-to-many', NULL, NULL, 0, 0),
('2c09281e-f063-9fd9-3ff5-5f003bf7c949', 'oauthkeys_assigned_user', 'Users', 'users', 'id', 'OAuthKeys', 'oauth_consumer', 'assigned_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('2c32defd-514d-8100-a545-5f003b0081a1', 'project_tasks_created_by', 'Users', 'users', 'id', 'ProjectTask', 'project_task', 'created_by', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('2c5e8fae-0e35-e264-35a1-5f003bcbfa73', 'meetings_users', 'Meetings', 'meetings', 'id', 'Users', 'users', 'id', 'meetings_users', 'meeting_id', 'user_id', 'many-to-many', NULL, NULL, 0, 0),
('2cab3400-14fa-b5ee-c573-5f003b92dc2d', 'aos_line_item_groups_created_by', 'Users', 'users', 'id', 'AOS_Line_Item_Groups', 'aos_line_item_groups', 'created_by', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('2d459c49-4a05-baee-2093-5f003b3cf128', 'meetings_leads', 'Meetings', 'meetings', 'id', 'Leads', 'leads', 'id', 'meetings_leads', 'meeting_id', 'lead_id', 'many-to-many', NULL, NULL, 0, 0),
('2dab3688-8396-a8ff-2cbc-5f003b11ad35', 'consumer_tokens', 'OAuthKeys', 'oauth_consumer', 'id', 'OAuthTokens', 'oauth_tokens', 'consumer', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('2e346396-a8a9-159b-4a4c-5f003ba0b4cd', 'opportunities_contacts', 'Opportunities', 'opportunities', 'id', 'Contacts', 'contacts', 'id', 'opportunities_contacts', 'opportunity_id', 'contact_id', 'many-to-many', NULL, NULL, 0, 0),
('2e47eec9-f63e-6953-62d3-5f003b008986', 'aos_line_item_groups_assigned_user', 'Users', 'users', 'id', 'AOS_Line_Item_Groups', 'aos_line_item_groups', 'assigned_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('2f1080ba-e7bc-6683-f4e9-5f003b741eb4', 'groups_aos_product_quotes', 'AOS_Line_Item_Groups', 'aos_line_item_groups', 'id', 'AOS_Products_Quotes', 'aos_products_quotes', 'group_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('2f12b587-1227-9c2c-b682-5f003b100236', 'oauthtokens_assigned_user', 'Users', 'users', 'id', 'OAuthTokens', 'oauth_tokens', 'assigned_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('2f4009bf-cdf0-ae0e-46b0-5f003bd9a846', 'stic_sessions_modified_user', 'Users', 'users', 'id', 'stic_Sessions', 'stic_sessions', 'modified_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('2f78c7b3-feee-63f2-11dd-5f003bbd35ba', 'campaigns_modified_user', 'Users', 'users', 'id', 'Campaigns', 'campaigns', 'modified_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('2f93e088-0382-d218-3733-5f003b295416', 'prospect_list_campaigns', 'ProspectLists', 'prospect_lists', 'id', 'Campaigns', 'campaigns', 'id', 'prospect_list_campaigns', 'prospect_list_id', 'campaign_id', 'many-to-many', NULL, NULL, 0, 0),
('3070bf52-68a8-2b8e-07e1-5f003ba6b758', 'prospect_list_contacts', 'ProspectLists', 'prospect_lists', 'id', 'Contacts', 'contacts', 'id', 'prospect_lists_prospects', 'prospect_list_id', 'related_id', 'many-to-many', 'related_type', 'Contacts', 0, 0),
('30f69e75-ec14-6796-8052-5f003b84b0e7', 'aos_quotes_modified_user', 'Users', 'users', 'id', 'AOS_Quotes', 'aos_quotes', 'modified_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('30f9f4a4-e624-4dbb-f454-5f003b445ddd', 'am_projecttemplates_modified_user', 'Users', 'users', 'id', 'AM_ProjectTemplates', 'am_projecttemplates', 'modified_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('314b51c2-006e-a008-7a52-5f003bc3b7d8', 'prospect_list_prospects', 'ProspectLists', 'prospect_lists', 'id', 'Prospects', 'prospects', 'id', 'prospect_lists_prospects', 'prospect_list_id', 'related_id', 'many-to-many', 'related_type', 'Prospects', 0, 0),
('31abe909-bf32-e5e0-a9bb-5f003bf2a311', 'campaigns_created_by', 'Users', 'users', 'id', 'Campaigns', 'campaigns', 'created_by', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('3220d00d-49b3-9b88-885a-5f003b55bb7a', 'prospect_list_leads', 'ProspectLists', 'prospect_lists', 'id', 'Leads', 'leads', 'id', 'prospect_lists_prospects', 'prospect_list_id', 'related_id', 'many-to-many', 'related_type', 'Leads', 0, 0),
('32ada680-09a5-ceb3-0ee1-5f003b7bf2e8', 'campaigns_assigned_user', 'Users', 'users', 'id', 'Campaigns', 'campaigns', 'assigned_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('32fcbeda-3d1f-0923-4079-5f003b7d686a', 'prospect_list_users', 'ProspectLists', 'prospect_lists', 'id', 'Users', 'users', 'id', 'prospect_lists_prospects', 'prospect_list_id', 'related_id', 'many-to-many', 'related_type', 'Users', 0, 0),
('336fffd4-a50e-1000-dc22-5f003bd102d9', 'securitygroups_campaigns', 'SecurityGroups', 'securitygroups', 'id', 'Campaigns', 'campaigns', 'id', 'securitygroups_records', 'securitygroup_id', 'record_id', 'many-to-many', 'module', 'Campaigns', 0, 0),
('3389877d-bace-8212-7ad0-5f003b632597', 'prospect_list_accounts', 'ProspectLists', 'prospect_lists', 'id', 'Accounts', 'accounts', 'id', 'prospect_lists_prospects', 'prospect_list_id', 'related_id', 'many-to-many', 'related_type', 'Accounts', 0, 0),
('34392ce4-4bad-8beb-aa03-5f003bc8895c', 'campaign_accounts', 'Campaigns', 'campaigns', 'id', 'Accounts', 'accounts', 'campaign_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('3482078a-04b6-3afd-8f10-5f003b976e25', 'roles_users', 'Roles', 'roles', 'id', 'Users', 'users', 'id', 'roles_users', 'role_id', 'user_id', 'many-to-many', NULL, NULL, 0, 0),
('3504e9fc-50d9-86b6-0c82-5f003bbd55c2', 'campaign_contacts', 'Campaigns', 'campaigns', 'id', 'Contacts', 'contacts', 'campaign_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('35d5cae9-1f0b-b5fc-dea1-5f003bc9b8ec', 'campaign_leads', 'Campaigns', 'campaigns', 'id', 'Leads', 'leads', 'campaign_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('362f6725-80b9-6cf0-abdb-5f003bc65f5d', 'campaign_prospects', 'Campaigns', 'campaigns', 'id', 'Prospects', 'prospects', 'campaign_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('367d268c-ba78-eb7a-36ac-5f003b78156d', 'projects_bugs', 'Project', 'project', 'id', 'Bugs', 'bugs', 'id', 'projects_bugs', 'project_id', 'bug_id', 'many-to-many', NULL, NULL, 0, 0),
('36e7867e-a99a-1c18-c881-5f003bf40d18', 'campaign_opportunities', 'Campaigns', 'campaigns', 'id', 'Opportunities', 'opportunities', 'campaign_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('373aae32-f57b-6379-527c-5f003b6fdb89', 'campaign_email_marketing', 'Campaigns', 'campaigns', 'id', 'EmailMarketing', 'email_marketing', 'campaign_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('376411ff-b840-5396-b816-5f003bf3d127', 'projects_cases', 'Project', 'project', 'id', 'Cases', 'cases', 'id', 'projects_cases', 'project_id', 'case_id', 'many-to-many', NULL, NULL, 0, 0),
('3767ad33-a14d-6d61-abfa-5f003be1d2fe', 'aos_quotes_created_by', 'Users', 'users', 'id', 'AOS_Quotes', 'aos_quotes', 'created_by', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('37ef072c-6d66-e5fb-81df-5f003b333573', 'campaign_emailman', 'Campaigns', 'campaigns', 'id', 'EmailMan', 'emailman', 'campaign_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('383b2fa0-9e4d-c914-7c95-5f003bd90097', 'aos_quotes_assigned_user', 'Users', 'users', 'id', 'AOS_Quotes', 'aos_quotes', 'assigned_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('384ad915-91d5-f854-f00d-5f003bead8fe', 'projects_accounts', 'Project', 'project', 'id', 'Accounts', 'accounts', 'id', 'projects_accounts', 'project_id', 'account_id', 'many-to-many', NULL, NULL, 0, 0),
('38af56a0-010a-27cd-4f5f-5f003b183fb4', 'campaign_campaignlog', 'Campaigns', 'campaigns', 'id', 'CampaignLog', 'campaign_log', 'campaign_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('39214d9d-2ad8-b3f2-2b10-5f003b0691f9', 'projects_contacts', 'Project', 'project', 'id', 'Contacts', 'contacts', 'id', 'projects_contacts', 'project_id', 'contact_id', 'many-to-many', NULL, NULL, 0, 0),
('39235fe0-c480-6227-b4f3-5f003b381643', 'campaign_assigned_user', 'Users', 'users', 'id', 'Campaigns', 'campaigns', 'assigned_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('393d0fea-931b-710c-4b18-5f003b2bf323', 'inbound_email_created_by', 'Users', 'users', 'id', 'InboundEmail', 'inbound_email', 'created_by', NULL, NULL, NULL, 'one-to-one', NULL, NULL, 0, 0),
('39eb4702-cb25-4d15-970f-5f003b6c0aba', 'campaign_modified_user', 'Users', 'users', 'id', 'Campaigns', 'campaigns', 'modified_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('3a0e0b42-9247-bc8d-5aa3-5f003bc6d239', 'projects_opportunities', 'Project', 'project', 'id', 'Opportunities', 'opportunities', 'id', 'projects_opportunities', 'project_id', 'opportunity_id', 'many-to-many', NULL, NULL, 0, 0),
('3aaeed50-f490-ad01-59d3-5f003b14b718', 'surveyresponses_campaigns', 'Campaigns', 'campaigns', 'id', 'SurveyResponses', 'surveyresponses', 'campaign_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('3b356b4c-e59d-6bd5-9c47-5f003be150b5', 'securitygroups_aos_quotes', 'SecurityGroups', 'securitygroups', 'id', 'AOS_Quotes', 'aos_quotes', 'id', 'securitygroups_records', 'securitygroup_id', 'record_id', 'many-to-many', 'module', 'AOS_Quotes', 0, 0),
('3bc00ce9-06b1-de73-c23c-5f003bcecac4', 'users_email_addresses', 'Users', 'users', 'id', 'EmailAddresses', 'email_addresses', 'id', 'email_addr_bean_rel', 'bean_id', 'email_address_id', 'many-to-many', 'bean_module', 'Users', 0, 0),
('3cc9dab5-05fa-0607-b0c1-5f003baf2300', 'aos_quotes_aos_product_quotes', 'AOS_Quotes', 'aos_quotes', 'id', 'AOS_Products_Quotes', 'aos_products_quotes', 'parent_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('3cd9108c-9754-813a-26b6-5f003b6d4168', 'prospectlists_assigned_user', 'Users', 'users', 'id', 'prospectlists', 'prospect_lists', 'assigned_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('3dbce0ee-62b4-3d4f-a827-5f003b6712a1', 'aos_quotes_aos_line_item_groups', 'AOS_Quotes', 'aos_quotes', 'id', 'AOS_Line_Item_Groups', 'aos_line_item_groups', 'parent_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('3e16ea25-28ab-8af2-e7f0-5f003b1e31c6', 'securitygroups_prospectlists', 'SecurityGroups', 'securitygroups', 'id', 'ProspectLists', 'prospect_lists', 'id', 'securitygroups_records', 'securitygroup_id', 'record_id', 'many-to-many', 'module', 'ProspectLists', 0, 0),
('3eb253cb-f0b7-2a5f-8dcf-5f003b0324de', 'acl_roles_actions', 'ACLRoles', 'acl_roles', 'id', 'ACLActions', 'acl_actions', 'id', 'acl_roles_actions', 'role_id', 'action_id', 'many-to-many', NULL, NULL, 0, 0),
('3f686597-c633-f4f1-0780-5f003b2d9d6f', 'aow_actions_modified_user', 'Users', 'users', 'id', 'AOW_Actions', 'aow_actions', 'modified_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('3fb0839c-8463-0ceb-9ab2-5f003b625b73', 'acl_roles_users', 'ACLRoles', 'acl_roles', 'id', 'Users', 'users', 'id', 'acl_roles_users', 'role_id', 'user_id', 'many-to-many', NULL, NULL, 0, 0),
('40925c23-2569-0c0b-1b8e-5f003bbcac0e', 'aow_actions_created_by', 'Users', 'users', 'id', 'AOW_Actions', 'aow_actions', 'created_by', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('40ab18e2-0736-317d-1406-5f003bc6b8dd', 'email_marketing_prospect_lists', 'EmailMarketing', 'email_marketing', 'id', 'ProspectLists', 'prospect_lists', 'id', 'email_marketing_prospect_lists', 'email_marketing_id', 'prospect_list_id', 'many-to-many', NULL, NULL, 0, 0),
('41b5fe19-988b-05a6-0041-5f003baa5c5b', 'leads_documents', 'Leads', 'leads', 'id', 'Documents', 'documents', 'id', 'linked_documents', 'parent_id', 'document_id', 'many-to-many', 'parent_type', 'Leads', 0, 0),
('41e25121-e0f4-de0f-7948-5f003b4fc757', 'prospects_modified_user', 'Users', 'users', 'id', 'Prospects', 'prospects', 'modified_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('429b5ffb-e8c0-6d2d-5ded-5f003b888621', 'documents_accounts', 'Documents', 'documents', 'id', 'Accounts', 'accounts', 'id', 'documents_accounts', 'document_id', 'account_id', 'many-to-many', NULL, NULL, 0, 0),
('43016da4-5ffa-8592-89e4-5f003b6f7525', 'documents_contacts', 'Documents', 'documents', 'id', 'Contacts', 'contacts', 'id', 'documents_contacts', 'document_id', 'contact_id', 'many-to-many', NULL, NULL, 0, 0),
('4362af2b-ac07-a1a9-1ac2-5f003b29b786', 'aow_workflow_modified_user', 'Users', 'users', 'id', 'AOW_WorkFlow', 'aow_workflow', 'modified_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('43d9e1d8-7f3f-fe4d-45c5-5f003b9c62c9', 'documents_opportunities', 'Documents', 'documents', 'id', 'Opportunities', 'opportunities', 'id', 'documents_opportunities', 'document_id', 'opportunity_id', 'many-to-many', NULL, NULL, 0, 0),
('44b4338b-0793-c8b9-7d6d-5f003b479118', 'documents_cases', 'Documents', 'documents', 'id', 'Cases', 'cases', 'id', 'documents_cases', 'document_id', 'case_id', 'many-to-many', NULL, NULL, 0, 0),
('44cbc088-17d2-d014-ecfc-5f003bd9019e', 'prospects_created_by', 'Users', 'users', 'id', 'Prospects', 'prospects', 'created_by', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('457208c7-357c-db03-6f12-5f003ba9c6bc', 'stic_sessions_created_by', 'Users', 'users', 'id', 'stic_Sessions', 'stic_sessions', 'created_by', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('458f3e65-054e-9bca-d259-5f003b041e6c', 'documents_bugs', 'Documents', 'documents', 'id', 'Bugs', 'bugs', 'id', 'documents_bugs', 'document_id', 'bug_id', 'many-to-many', NULL, NULL, 0, 0),
('45c35d56-f53d-a746-8074-5f003bd56d84', 'prospects_assigned_user', 'Users', 'users', 'id', 'Prospects', 'prospects', 'assigned_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('45f79285-0498-224a-cf68-5f003b416c70', 'aok_knowledgebase_categories', 'AOK_KnowledgeBase', 'aok_knowledgebase', 'id', 'AOK_Knowledge_Base_Categories', 'aok_knowledge_base_categories', 'id', 'aok_knowledgebase_categories', 'aok_knowledgebase_id', 'aok_knowledge_base_categories_id', 'many-to-many', NULL, NULL, 0, 0),
('4604fc5d-fe1f-fa10-2d2c-5f003b638097', 'aow_workflow_created_by', 'Users', 'users', 'id', 'AOW_WorkFlow', 'aow_workflow', 'created_by', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('46de4bb6-c17d-cbf4-f86a-5f003bbb47be', 'am_projecttemplates_project_1', 'AM_ProjectTemplates', 'am_projecttemplates', 'id', 'Project', 'project', 'id', 'am_projecttemplates_project_1_c', 'am_projecttemplates_project_1am_projecttemplates_ida', 'am_projecttemplates_project_1project_idb', 'many-to-many', NULL, NULL, 0, 0),
('46fad6c8-ad61-b0f8-bf8e-5f003b653179', 'aow_workflow_assigned_user', 'Users', 'users', 'id', 'AOW_WorkFlow', 'aow_workflow', 'assigned_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('47be33e8-2538-1f27-d294-5f003ba4b7f3', 'am_projecttemplates_contacts_1', 'AM_ProjectTemplates', 'am_projecttemplates', 'id', 'Contacts', 'contacts', 'id', 'am_projecttemplates_contacts_1_c', 'am_projecttemplates_ida', 'contacts_idb', 'many-to-many', NULL, NULL, 0, 0),
('47ede659-7171-55f4-6a78-5f003b1241bf', 'securitygroups_aow_workflow', 'SecurityGroups', 'securitygroups', 'id', 'AOW_WorkFlow', 'aow_workflow', 'id', 'securitygroups_records', 'securitygroup_id', 'record_id', 'many-to-many', 'module', 'AOW_WorkFlow', 0, 0),
('48b77fbf-d046-a6ea-c92e-5f003bb63f4a', 'aow_workflow_aow_conditions', 'AOW_WorkFlow', 'aow_workflow', 'id', 'AOW_Conditions', 'aow_conditions', 'aow_workflow_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('48b7ea6f-51db-ac8c-cd53-5f003bba9c41', 'am_projecttemplates_users_1', 'AM_ProjectTemplates', 'am_projecttemplates', 'id', 'Users', 'users', 'id', 'am_projecttemplates_users_1_c', 'am_projecttemplates_ida', 'users_idb', 'many-to-many', NULL, NULL, 0, 0),
('49984fcc-b3d6-6049-f01e-5f003be98c76', 'am_tasktemplates_am_projecttemplates', 'AM_ProjectTemplates', 'am_projecttemplates', 'id', 'AM_TaskTemplates', 'am_tasktemplates', 'id', 'am_tasktemplates_am_projecttemplates_c', 'am_tasktemplates_am_projecttemplatesam_projecttemplates_ida', 'am_tasktemplates_am_projecttemplatesam_tasktemplates_idb', 'many-to-many', NULL, NULL, 0, 0),
('499c0416-6d2a-0f5c-bf79-5f003b34f378', 'users_email_addresses_primary', 'Users', 'users', 'id', 'EmailAddresses', 'email_addresses', 'id', 'email_addr_bean_rel', 'bean_id', 'email_address_id', 'many-to-many', 'primary_address', '1', 0, 0),
('4a63e4de-8ca9-468c-458c-5f003b3d8f35', 'aos_contracts_documents', 'AOS_Contracts', 'aos_contracts', 'id', 'Documents', 'documents', 'id', 'aos_contracts_documents', 'aos_contracts_id', 'documents_id', 'many-to-many', NULL, NULL, 0, 0),
('4ac6d482-96da-e646-195c-5f003bf46dad', 'aos_quotes_aos_contracts', 'AOS_Quotes', 'aos_quotes', 'id', 'AOS_Contracts', 'aos_contracts', 'id', 'aos_quotes_os_contracts_c', 'aos_quotese81e_quotes_ida', 'aos_quotes4dc0ntracts_idb', 'many-to-many', NULL, NULL, 0, 0),
('4b92f00e-c79d-7bc6-7b1f-5f003b959f89', 'aos_quotes_aos_invoices', 'AOS_Quotes', 'aos_quotes', 'id', 'AOS_Invoices', 'aos_invoices', 'id', 'aos_quotes_aos_invoices_c', 'aos_quotes77d9_quotes_ida', 'aos_quotes6b83nvoices_idb', 'many-to-many', NULL, NULL, 0, 0),
('4c2e3e77-6b06-0a18-9af0-5f003ba50edf', 'aow_workflow_aow_actions', 'AOW_WorkFlow', 'aow_workflow', 'id', 'AOW_Actions', 'aow_actions', 'aow_workflow_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('4c607d64-8dc1-8aa0-3446-5f003b24c193', 'aos_quotes_project', 'AOS_Quotes', 'aos_quotes', 'id', 'Project', 'project', 'id', 'aos_quotes_project_c', 'aos_quotes1112_quotes_ida', 'aos_quotes7207project_idb', 'many-to-many', NULL, NULL, 0, 0),
('4d230d61-332b-1c9d-a3be-5f003ba95723', 'aow_processed_aow_actions', 'AOW_Processed', 'aow_processed', 'id', 'AOW_Actions', 'aow_actions', 'id', 'aow_processed_aow_actions', 'aow_processed_id', 'aow_action_id', 'many-to-many', NULL, NULL, 0, 0),
('4d233e64-f739-6a07-7474-5f003b44e62f', 'aow_workflow_aow_processed', 'AOW_WorkFlow', 'aow_workflow', 'id', 'AOW_Processed', 'aow_processed', 'aow_workflow_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('4df3efcf-3576-0de9-bf35-5f003b94daac', 'fp_event_locations_fp_events_1', 'FP_Event_Locations', 'fp_event_locations', 'id', 'FP_events', 'fp_events', 'id', 'fp_event_locations_fp_events_1_c', 'fp_event_locations_fp_events_1fp_event_locations_ida', 'fp_event_locations_fp_events_1fp_events_idb', 'many-to-many', NULL, NULL, 0, 0),
('4e6a0610-beed-2a4b-2f5b-5f003b9a9963', 'fp_events_contacts', 'FP_events', 'fp_events', 'id', 'Contacts', 'contacts', 'id', 'fp_events_contacts_c', 'fp_events_contactsfp_events_ida', 'fp_events_contactscontacts_idb', 'many-to-many', NULL, NULL, 0, 0),
('4f1fa7e8-5f91-9ea6-ef0c-5f003b2f9382', 'aow_processed_modified_user', 'Users', 'users', 'id', 'AOW_Processed', 'aow_processed', 'modified_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('4f3266ac-81a9-d452-28ab-5f003b01e2cd', 'fp_events_fp_event_locations_1', 'FP_events', 'fp_events', 'id', 'FP_Event_Locations', 'fp_event_locations', 'id', 'fp_events_fp_event_locations_1_c', 'fp_events_fp_event_locations_1fp_events_ida', 'fp_events_fp_event_locations_1fp_event_locations_idb', 'many-to-many', NULL, NULL, 0, 0),
('4f8f4037-f9d1-81cd-925a-5f003b25d15e', 'fp_events_leads_1', 'FP_events', 'fp_events', 'id', 'Leads', 'leads', 'id', 'fp_events_leads_1_c', 'fp_events_leads_1fp_events_ida', 'fp_events_leads_1leads_idb', 'many-to-many', NULL, NULL, 0, 0),
('5062c3bd-784c-d5cc-9a61-5f003b637c46', 'fp_events_prospects_1', 'FP_events', 'fp_events', 'id', 'Prospects', 'prospects', 'id', 'fp_events_prospects_1_c', 'fp_events_prospects_1fp_events_ida', 'fp_events_prospects_1prospects_idb', 'many-to-many', NULL, NULL, 0, 0),
('50de9363-f826-146b-965e-5f003be60eaa', 'securitygroups_prospects', 'SecurityGroups', 'securitygroups', 'id', 'Prospects', 'prospects', 'id', 'securitygroups_records', 'securitygroup_id', 'record_id', 'many-to-many', 'module', 'Prospects', 0, 0),
('51311c89-aea2-85b9-1f5f-5f003b7548dd', 'jjwg_maps_jjwg_areas', 'jjwg_Maps', 'jjwg_maps', 'id', 'jjwg_Areas', 'jjwg_areas', 'id', 'jjwg_maps_jjwg_areas_c', 'jjwg_maps_5304wg_maps_ida', 'jjwg_maps_41f2g_areas_idb', 'many-to-many', NULL, NULL, 0, 0),
('518992d9-579d-44af-6db5-5f003b6afae8', 'jjwg_maps_jjwg_markers', 'jjwg_Maps', 'jjwg_maps', 'id', 'jjwg_Markers', 'jjwg_markers', 'id', 'jjwg_maps_jjwg_markers_c', 'jjwg_maps_b229wg_maps_ida', 'jjwg_maps_2e31markers_idb', 'many-to-many', NULL, NULL, 0, 0),
('51ec3c82-a5d1-1234-5373-5f003b1c5cb9', 'prospects_email_addresses', 'Prospects', 'prospects', 'id', 'EmailAddresses', 'email_addresses', 'id', 'email_addr_bean_rel', 'bean_id', 'email_address_id', 'many-to-many', 'bean_module', 'Prospects', 0, 0),
('52633732-890b-a9b3-0d03-5f003b53c51d', 'project_contacts_1', 'Project', 'project', 'id', 'Contacts', 'contacts', 'id', 'project_contacts_1_c', 'project_contacts_1project_ida', 'project_contacts_1contacts_idb', 'many-to-many', NULL, NULL, 0, 0),
('52a78d47-11c6-06cf-bd40-5f003b7ba9a5', 'am_projecttemplates_created_by', 'Users', 'users', 'id', 'AM_ProjectTemplates', 'am_projecttemplates', 'created_by', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('52e87637-1bf6-9d9f-34cc-5f003bb5f8e8', 'prospects_email_addresses_primary', 'Prospects', 'prospects', 'id', 'EmailAddresses', 'email_addresses', 'id', 'email_addr_bean_rel', 'bean_id', 'email_address_id', 'many-to-many', 'primary_address', '1', 0, 0),
('532c7617-ca87-f0ab-a3cd-5f003b0f1a8e', 'project_users_1', 'Project', 'project', 'id', 'Users', 'users', 'id', 'project_users_1_c', 'project_users_1project_ida', 'project_users_1users_idb', 'many-to-many', NULL, NULL, 0, 0),
('53aa75b6-b681-924d-d4bc-5f003b23f76f', 'securitygroups_acl_roles', 'SecurityGroups', 'securitygroups', 'id', 'ACLRoles', 'acl_roles', 'id', 'securitygroups_acl_roles', 'securitygroup_id', 'role_id', 'many-to-many', NULL, NULL, 0, 0),
('53ba0401-e051-f3a0-c9fd-5f003bf8fcb5', 'aos_product_categories_modified_user', 'Users', 'users', 'id', 'AOS_Product_Categories', 'aos_product_categories', 'modified_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('53c13ea8-38d1-c1da-377f-5f003b331c47', 'prospect_tasks', 'Prospects', 'prospects', 'id', 'Tasks', 'tasks', 'parent_id', NULL, NULL, NULL, 'one-to-many', 'parent_type', 'Prospects', 0, 0),
('54933017-32e7-fa10-4148-5f003b7d9b36', 'prospect_notes', 'Prospects', 'prospects', 'id', 'Notes', 'notes', 'parent_id', NULL, NULL, NULL, 'one-to-many', 'parent_type', 'Prospects', 0, 0),
('552c045a-0e1f-1f16-b504-5f003b6b5c12', 'securitygroups_project_task', 'SecurityGroups', 'securitygroups', 'id', 'ProjectTask', 'project_task', 'id', 'securitygroups_records', 'securitygroup_id', 'record_id', 'many-to-many', 'module', 'ProjectTask', 0, 0),
('5565bf72-77cb-8fc9-2808-5f003b11191f', 'prospect_meetings', 'Prospects', 'prospects', 'id', 'Meetings', 'meetings', 'parent_id', NULL, NULL, NULL, 'one-to-many', 'parent_type', 'Prospects', 0, 0),
('560bb062-42bd-e476-bb8b-5f003b08c5ed', 'securitygroups_prospect_lists', 'SecurityGroups', 'securitygroups', 'id', 'ProspectLists', 'prospect_lists', 'id', 'securitygroups_records', 'securitygroup_id', 'record_id', 'many-to-many', 'module', 'ProspectLists', 0, 0),
('56101797-9690-8252-1f8f-5f003bf98b6d', 'am_projecttemplates_assigned_user', 'Users', 'users', 'id', 'AM_ProjectTemplates', 'am_projecttemplates', 'assigned_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('562d63d3-9a3b-0569-ce73-5f003b8c2b03', 'prospect_calls', 'Prospects', 'prospects', 'id', 'Calls', 'calls', 'parent_id', NULL, NULL, NULL, 'one-to-many', 'parent_type', 'Prospects', 0, 0),
('567cf1cb-75d3-f4b3-cf68-5f003bd03e27', 'prospect_emails', 'Prospects', 'prospects', 'id', 'Emails', 'emails', 'parent_id', NULL, NULL, NULL, 'one-to-many', 'parent_type', 'Prospects', 0, 0),
('56f8c17c-00e6-45d6-8ea5-5f003bccc7ba', 'securitygroups_users', 'SecurityGroups', 'securitygroups', 'id', 'Users', 'users', 'id', 'securitygroups_users', 'securitygroup_id', 'user_id', 'many-to-many', NULL, NULL, 0, 0),
('57414ae0-3380-0771-5985-5f003bea0fb9', 'prospect_campaign_log', 'Prospects', 'prospects', 'id', 'CampaignLog', 'campaign_log', 'target_id', NULL, NULL, NULL, 'one-to-many', 'target_type', 'Prospects', 0, 0),
('57e2554c-9d87-01a0-1fd5-5f003b33ea6a', 'surveyquestionoptions_surveyquestionresponses', 'SurveyQuestionOptions', 'surveyquestionoptions', 'id', 'SurveyQuestionResponses', 'surveyquestionresponses', 'id', 'surveyquestionoptions_surveyquestionresponses', 'surveyq72c7options_ida', 'surveyq10d4sponses_idb', 'many-to-many', NULL, NULL, 0, 0),
('58a45b27-afc5-59c0-584b-5f003b26643d', 'stic_attendances_stic_sessions', 'stic_Sessions', 'stic_sessions', 'id', 'stic_Attendances', 'stic_attendances', 'id', 'stic_attendances_stic_sessions_c', 'stic_attendances_stic_sessionsstic_sessions_ida', 'stic_attendances_stic_sessionsstic_attendances_idb', 'many-to-many', NULL, NULL, 0, 0),
('58d6b855-cddb-f5e3-9e02-5f003bf54abd', 'aow_processed_created_by', 'Users', 'users', 'id', 'AOW_Processed', 'aow_processed', 'created_by', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('59608275-ddd4-e65f-3862-5f003bc0e09b', 'email_template_email_marketings', 'EmailTemplates', 'email_templates', 'id', 'EmailMarketing', 'email_marketing', 'template_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('5974ac0f-81a3-788a-5a12-5f003b1dd41b', 'stic_payments_activities_notes', 'stic_Payments', 'stic_payments', 'id', 'Notes', 'notes', 'parent_id', NULL, NULL, NULL, 'one-to-many', 'parent_type', 'stic_Payments', 0, 0),
('5a32ae05-38e4-162b-587c-5f003b21dca6', 'stic_payments_contacts', 'Contacts', 'contacts', 'id', 'stic_Payments', 'stic_payments', 'id', 'stic_payments_contacts_c', 'stic_payments_contactscontacts_ida', 'stic_payments_contactsstic_payments_idb', 'many-to-many', NULL, NULL, 0, 0),
('5af33d11-b71e-9838-09e1-5f003b9bc848', 'campaign_campaigntrakers', 'Campaigns', 'campaigns', 'id', 'CampaignTrackers', 'campaign_trkrs', 'campaign_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('5afbd271-7d88-be81-8327-5f003bfad8f6', 'stic_payments_activities_tasks', 'stic_Payments', 'stic_payments', 'id', 'Tasks', 'tasks', 'parent_id', NULL, NULL, NULL, 'one-to-many', 'parent_type', 'stic_Payments', 0, 0),
('5b4034ec-c33b-6728-1e1f-5f003be6586c', 'aow_conditions_modified_user', 'Users', 'users', 'id', 'AOW_Conditions', 'aow_conditions', 'modified_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('5b76a0ca-9e13-ac21-2409-5f003bb5150b', 'stic_registrations_accounts', 'Accounts', 'accounts', 'id', 'stic_Registrations', 'stic_registrations', 'id', 'stic_registrations_accounts_c', 'stic_registrations_accountsaccounts_ida', 'stic_registrations_accountsstic_registrations_idb', 'many-to-many', NULL, NULL, 0, 0),
('5c282701-7070-08ad-bb76-5f003b113fb4', 'stic_contacts_relationships_project', 'Project', 'project', 'id', 'stic_Contacts_Relationships', 'stic_contacts_relationships', 'id', 'stic_contacts_relationships_project_c', 'stic_contacts_relationships_projectproject_ida', 'stic_conta0d5aonships_idb', 'many-to-many', NULL, NULL, 0, 0),
('5c47a942-100f-9cd1-e9be-5f003b8666cb', 'aow_conditions_created_by', 'Users', 'users', 'id', 'AOW_Conditions', 'aow_conditions', 'created_by', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('5cf30ec8-6bab-6907-9dc7-5f003b317806', 'stic_payment_commitments_campaigns', 'Campaigns', 'campaigns', 'id', 'stic_Payment_Commitments', 'stic_payment_commitments', 'id', 'stic_payment_commitments_campaigns_c', 'stic_payment_commitments_campaignscampaigns_ida', 'stic_payment_commitments_campaignsstic_payment_commitments_idb', 'many-to-many', NULL, NULL, 0, 0),
('5d496288-4a3d-060a-6439-5f003baf0e38', 'stic_registrations_leads', 'Leads', 'leads', 'id', 'stic_Registrations', 'stic_registrations', 'id', 'stic_registrations_leads_c', 'stic_registrations_leadsleads_ida', 'stic_registrations_leadsstic_registrations_idb', 'many-to-many', NULL, NULL, 0, 0),
('5d6e73de-f46f-45c8-dbbf-5f003b1da1d6', 'am_tasktemplates_modified_user', 'Users', 'users', 'id', 'AM_TaskTemplates', 'am_tasktemplates', 'modified_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('5e0142fb-bc08-4417-1cad-5f003b2304c0', 'stic_attendances_stic_registrations', 'stic_Registrations', 'stic_registrations', 'id', 'stic_Attendances', 'stic_attendances', 'id', 'stic_attendances_stic_registrations_c', 'stic_attendances_stic_registrationsstic_registrations_ida', 'stic_attendances_stic_registrationsstic_attendances_idb', 'many-to-many', NULL, NULL, 0, 0),
('5e598e11-57ed-0cf9-7226-5f003bc73abf', 'project_opportunities_1', 'Project', 'project', 'id', 'Opportunities', 'opportunities', 'id', 'project_opportunities_1_c', 'project_opportunities_1project_ida', 'project_opportunities_1opportunities_idb', 'many-to-many', NULL, NULL, 0, 0),
('5ea91cb8-b9b3-8a1d-9e51-5f003b0f53fc', 'jjwg_maps_modified_user', 'Users', 'users', 'id', 'jjwg_Maps', 'jjwg_maps', 'modified_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('5f110649-f3c5-72a3-9a70-5f003b65420d', 'stic_sessions_assigned_user', 'Users', 'users', 'id', 'stic_Sessions', 'stic_sessions', 'assigned_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('5f1be526-4542-e23c-1b36-5f003b8aa480', 'stic_events_project', 'Project', 'project', 'id', 'stic_Events', 'stic_events', 'id', 'stic_events_project_c', 'stic_events_projectproject_ida', 'stic_events_projectstic_events_idb', 'many-to-many', NULL, NULL, 0, 0),
('5fc2f7be-459e-9425-9273-5f003bf173a5', 'jjwg_maps_created_by', 'Users', 'users', 'id', 'jjwg_Maps', 'jjwg_maps', 'created_by', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('5fe95ae9-070c-da44-82b7-5f003bee47f0', 'stic_validation_actions_schedulers', 'stic_Validation_Actions', 'stic_validation_actions', 'id', 'Schedulers', 'schedulers', 'id', 'stic_validation_actions_schedulers_c', 'stic_validation_actions_schedulersstic_validation_actions_ida', 'stic_validation_actions_schedulersschedulers_idb', 'many-to-many', NULL, NULL, 0, 0),
('60369fb1-34a3-2339-25a2-5f003b62c80b', 'stic_payments_stic_registrations', 'stic_Payments', 'stic_payments', 'id', 'stic_Registrations', 'stic_registrations', 'id', 'stic_payments_stic_registrations_c', 'stic_payments_stic_registrationsstic_payments_ida', 'stic_payments_stic_registrationsstic_registrations_idb', 'many-to-many', NULL, NULL, 0, 0),
('6042b569-aff9-ac50-9342-5f003b89cc9b', 'am_tasktemplates_created_by', 'Users', 'users', 'id', 'AM_TaskTemplates', 'am_tasktemplates', 'created_by', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('60a4ef9b-3381-cea3-4afa-5f003bae66df', 'jjwg_maps_assigned_user', 'Users', 'users', 'id', 'jjwg_Maps', 'jjwg_maps', 'assigned_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('60f6034c-9dbb-7d1b-71d3-5f003ba837ab', 'stic_payments_stic_payment_commitments', 'stic_Payment_Commitments', 'stic_payment_commitments', 'id', 'stic_Payments', 'stic_payments', 'id', 'stic_payments_stic_payment_commitments_c', 'stic_paymebfe2itments_ida', 'stic_payments_stic_payment_commitmentsstic_payments_idb', 'many-to-many', NULL, NULL, 0, 0),
('613f7890-4d5c-c90c-49e3-5f003b03cd47', 'am_tasktemplates_assigned_user', 'Users', 'users', 'id', 'AM_TaskTemplates', 'am_tasktemplates', 'assigned_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('618eccec-9c2a-d1ef-9e1b-5f003baa67ba', 'securitygroups_jjwg_maps', 'SecurityGroups', 'securitygroups', 'id', 'jjwg_Maps', 'jjwg_maps', 'id', 'securitygroups_records', 'securitygroup_id', 'record_id', 'many-to-many', 'module', 'jjwg_Maps', 0, 0),
('61b83c9e-6db8-8832-e90a-5f003b24d5cd', 'stic_payments_stic_remittances', 'stic_Remittances', 'stic_remittances', 'id', 'stic_Payments', 'stic_payments', 'id', 'stic_payments_stic_remittances_c', 'stic_payments_stic_remittancesstic_remittances_ida', 'stic_payments_stic_remittancesstic_payments_idb', 'many-to-many', NULL, NULL, 0, 0),
('61e5b9d1-1198-7dc8-71e6-5f003be24a01', 'schedulers_created_by_rel', 'Users', 'users', 'id', 'Schedulers', 'schedulers', 'created_by', NULL, NULL, NULL, 'one-to-one', NULL, NULL, 0, 0),
('6211577a-1d89-3f53-5fb2-5f003bd81ff6', 'stic_payments_activities_emails', 'stic_Payments', 'stic_payments', 'id', 'Emails', 'emails', 'parent_id', NULL, NULL, NULL, 'one-to-many', 'parent_type', 'stic_Payments', 0, 0),
('6269b816-a0a7-74c1-5e64-5f003bc1d3d3', 'jjwg_Maps_accounts', 'jjwg_Maps', 'jjwg_Maps', 'parent_id', 'Accounts', 'accounts', 'id', NULL, NULL, NULL, 'one-to-many', 'parent_type', 'Accounts', 0, 0),
('62dea801-7538-33c7-554a-5f003be95a03', 'stic_accounts_relationships_accounts', 'Accounts', 'accounts', 'id', 'stic_Accounts_Relationships', 'stic_accounts_relationships', 'id', 'stic_accounts_relationships_accounts_c', 'stic_accounts_relationships_accountsaccounts_ida', 'stic_accoub36donships_idb', 'many-to-many', NULL, NULL, 0, 0),
('63199e40-3989-13bf-ca77-5f003bfe04db', 'favorites_modified_user', 'Users', 'users', 'id', 'Favorites', 'favorites', 'modified_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('633638b3-3369-7fbf-d3e2-5f003ba83ac5', 'jjwg_Maps_contacts', 'jjwg_Maps', 'jjwg_Maps', 'parent_id', 'Contacts', 'contacts', 'id', NULL, NULL, NULL, 'one-to-many', 'parent_type', 'Contacts', 0, 0),
('6336a178-baa9-e4b6-9325-5f003b5e98eb', 'stic_payment_commitments_accounts', 'Accounts', 'accounts', 'id', 'stic_Payment_Commitments', 'stic_payment_commitments', 'id', 'stic_payment_commitments_accounts_c', 'stic_payment_commitments_accountsaccounts_ida', 'stic_payment_commitments_accountsstic_payment_commitments_idb', 'many-to-many', NULL, NULL, 0, 0),
('634f0769-27be-dc2e-40a6-5f003b8b000f', 'schedulers_modified_user_id_rel', 'Users', 'users', 'id', 'Schedulers', 'schedulers', 'modified_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('63ee70a3-b0f1-6e7d-cf30-5f003be36359', 'stic_sessions_stic_events', 'stic_Events', 'stic_events', 'id', 'stic_Sessions', 'stic_sessions', 'id', 'stic_sessions_stic_events_c', 'stic_sessions_stic_eventsstic_events_ida', 'stic_sessions_stic_eventsstic_sessions_idb', 'many-to-many', NULL, NULL, 0, 0),
('64146aa9-d32f-9993-2a6b-5f003b2bb373', 'jjwg_Maps_leads', 'jjwg_Maps', 'jjwg_Maps', 'parent_id', 'Leads', 'leads', 'id', NULL, NULL, NULL, 'one-to-many', 'parent_type', 'Leads', 0, 0),
('64677025-f943-0132-4752-5f003b65b25f', 'favorites_created_by', 'Users', 'users', 'id', 'Favorites', 'favorites', 'created_by', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('64a75f0a-063e-7a5c-2fd2-5f003b2929bf', 'schedulers_jobs_rel', 'Schedulers', 'schedulers', 'id', 'SchedulersJobs', 'job_queue', 'scheduler_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('64d6eaa6-d29c-0d75-c431-5f003b1b38bb', 'jjwg_Maps_opportunities', 'jjwg_Maps', 'jjwg_Maps', 'parent_id', 'Opportunities', 'opportunities', 'id', NULL, NULL, NULL, 'one-to-many', 'parent_type', 'Opportunities', 0, 0),
('6552baf3-1c67-7625-55b9-5f003bd30a5e', 'favorites_assigned_user', 'Users', 'users', 'id', 'Favorites', 'favorites', 'assigned_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('65538580-632f-05f1-d62a-5f003b69c166', 'jjwg_Maps_cases', 'jjwg_Maps', 'jjwg_Maps', 'parent_id', 'Cases', 'cases', 'id', NULL, NULL, NULL, 'one-to-many', 'parent_type', 'Cases', 0, 0),
('657d04df-fe71-4f85-96fa-5f003b96c63d', 'stic_payments_accounts', 'Accounts', 'accounts', 'id', 'stic_Payments', 'stic_payments', 'id', 'stic_payments_accounts_c', 'stic_payments_accountsaccounts_ida', 'stic_payments_accountsstic_payments_idb', 'many-to-many', NULL, NULL, 0, 0),
('65ead14c-2ea2-5718-81b8-5f003bfeef74', 'schedulersjobs_assigned_user', 'Users', 'users', 'id', 'SchedulersJobs', 'job_queue', 'assigned_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('66263901-d95b-3f06-8bda-5f003b4c6167', 'jjwg_Maps_projects', 'jjwg_Maps', 'jjwg_Maps', 'parent_id', 'Project', 'project', 'id', NULL, NULL, NULL, 'one-to-many', 'parent_type', 'Project', 0, 0),
('671bc413-b8bd-a18f-6091-5f003b5ae360', 'aok_knowledge_base_categories_modified_user', 'Users', 'users', 'id', 'AOK_Knowledge_Base_Categories', 'aok_knowledge_base_categories', 'modified_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('681f80ff-ef96-96d5-f1b7-5f003bff3899', 'stic_registrations_contacts', 'Contacts', 'contacts', 'id', 'stic_Registrations', 'stic_registrations', 'id', 'stic_registrations_contacts_c', 'stic_registrations_contactscontacts_ida', 'stic_registrations_contactsstic_registrations_idb', 'many-to-many', NULL, NULL, 0, 0),
('68933964-fe9d-9029-5f8e-5f003b05633e', 'aok_knowledge_base_categories_created_by', 'Users', 'users', 'id', 'AOK_Knowledge_Base_Categories', 'aok_knowledge_base_categories', 'created_by', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('68940a29-2376-0362-36c1-5f003b398e7b', 'campaignlog_contact', 'CampaignLog', 'campaign_log', 'related_id', 'Contacts', 'contacts', 'id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0);
INSERT INTO `relationships` (`id`, `relationship_name`, `lhs_module`, `lhs_table`, `lhs_key`, `rhs_module`, `rhs_table`, `rhs_key`, `join_table`, `join_key_lhs`, `join_key_rhs`, `relationship_type`, `relationship_role_column`, `relationship_role_column_value`, `reverse`, `deleted`) VALUES
('69038ad9-ac56-4602-caf3-5f003bb0562d', 'stic_payments_activities_meetings', 'stic_Payments', 'stic_payments', 'id', 'Meetings', 'meetings', 'parent_id', NULL, NULL, NULL, 'one-to-many', 'parent_type', 'stic_Payments', 0, 0),
('699d83bb-1cee-7c1f-b168-5f003bc18fe9', 'aok_knowledge_base_categories_assigned_user', 'Users', 'users', 'id', 'AOK_Knowledge_Base_Categories', 'aok_knowledge_base_categories', 'assigned_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('69e08907-a847-eeb4-f939-5f003b59fe39', 'stic_registrations_stic_events', 'stic_Events', 'stic_events', 'id', 'stic_Registrations', 'stic_registrations', 'id', 'stic_registrations_stic_events_c', 'stic_registrations_stic_eventsstic_events_ida', 'stic_registrations_stic_eventsstic_registrations_idb', 'many-to-many', NULL, NULL, 0, 0),
('6aaf4e7e-ed1b-21c1-ba37-5f003b0d6adb', 'contacts_modified_user', 'Users', 'users', 'id', 'Contacts', 'contacts', 'modified_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('6acb44bd-5051-8b8d-4778-5f003bf59103', 'stic_sessions_documents', 'stic_Sessions', 'stic_sessions', 'id', 'Documents', 'documents', 'id', 'stic_sessions_documents_c', 'stic_sessions_documentsstic_sessions_ida', 'stic_sessions_documentsdocuments_idb', 'many-to-many', NULL, NULL, 0, 0),
('6be6dce7-ea1d-b991-a7af-5f003b9e1c11', 'contacts_created_by', 'Users', 'users', 'id', 'Contacts', 'contacts', 'created_by', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('6bfe4507-197c-bad6-6674-5f003bfcb127', 'aok_knowledgebase_modified_user', 'Users', 'users', 'id', 'AOK_KnowledgeBase', 'aok_knowledgebase', 'modified_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('6ce22927-a27c-a1d3-523c-5f003b3b4eca', 'stic_accounts_relationships_project', 'Project', 'project', 'id', 'stic_Accounts_Relationships', 'stic_accounts_relationships', 'id', 'stic_accounts_relationships_project_c', 'stic_accounts_relationships_projectproject_ida', 'stic_accou2675onships_idb', 'many-to-many', NULL, NULL, 0, 0),
('6d06d31b-e70b-9250-61b7-5f003bb7cfc1', 'contacts_assigned_user', 'Users', 'users', 'id', 'Contacts', 'contacts', 'assigned_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('6de8beca-f86a-3869-88aa-5f003b030744', 'jjwg_Maps_meetings', 'jjwg_Maps', 'jjwg_Maps', 'parent_id', 'Meetings', 'meetings', 'id', NULL, NULL, NULL, 'one-to-many', 'parent_type', 'Meetings', 0, 0),
('6df45b94-8a06-fb7c-874b-5f003b18b38d', 'securitygroups_contacts', 'SecurityGroups', 'securitygroups', 'id', 'Contacts', 'contacts', 'id', 'securitygroups_records', 'securitygroup_id', 'record_id', 'many-to-many', 'module', 'Contacts', 0, 0),
('6ecadfc9-329e-3a04-0414-5f003b8500b2', 'contacts_email_addresses', 'Contacts', 'contacts', 'id', 'EmailAddresses', 'email_addresses', 'id', 'email_addr_bean_rel', 'bean_id', 'email_address_id', 'many-to-many', 'bean_module', 'Contacts', 0, 0),
('6eda4594-b1bb-a15a-9c6b-5f003b82a514', 'stic_events_fp_event_locations', 'FP_Event_Locations', 'fp_event_locations', 'id', 'stic_Events', 'stic_events', 'id', 'stic_events_fp_event_locations_c', 'stic_events_fp_event_locationsfp_event_locations_ida', 'stic_events_fp_event_locationsstic_events_idb', 'many-to-many', NULL, NULL, 0, 0),
('6f1899a6-79ac-3d60-87d5-5f003b83504f', 'jjwg_Maps_prospects', 'jjwg_Maps', 'jjwg_Maps', 'parent_id', 'Prospects', 'prospects', 'id', NULL, NULL, NULL, 'one-to-many', 'parent_type', 'Prospects', 0, 0),
('6f8f08bf-3cd9-fd2c-ab32-5f003bf8b8d3', 'securitygroups_stic_sessions', 'SecurityGroups', 'securitygroups', 'id', 'stic_Sessions', 'stic_sessions', 'id', 'securitygroups_records', 'securitygroup_id', 'record_id', 'many-to-many', 'module', 'stic_Sessions', 0, 0),
('6fa78ede-431c-948e-b8d8-5f003b58d4fe', 'contacts_email_addresses_primary', 'Contacts', 'contacts', 'id', 'EmailAddresses', 'email_addresses', 'id', 'email_addr_bean_rel', 'bean_id', 'email_address_id', 'many-to-many', 'primary_address', '1', 0, 0),
('700e7f30-bff6-38a1-3a31-5f003bb62498', 'stic_payment_commitments_contacts', 'Contacts', 'contacts', 'id', 'stic_Payment_Commitments', 'stic_payment_commitments', 'id', 'stic_payment_commitments_contacts_c', 'stic_payment_commitments_contactscontacts_ida', 'stic_payment_commitments_contactsstic_payment_commitments_idb', 'many-to-many', NULL, NULL, 0, 0),
('70d197ce-7d14-0741-48c0-5f003b8eee94', 'contact_direct_reports', 'Contacts', 'contacts', 'id', 'Contacts', 'contacts', 'reports_to_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('711bb979-b0ca-a90a-d96d-5f003bac4f95', 'accounts_opportunities_1', 'Accounts', 'accounts', 'id', 'Opportunities', 'opportunities', 'id', 'accounts_opportunities_1_c', 'accounts_opportunities_1accounts_ida', 'accounts_opportunities_1opportunities_idb', 'many-to-many', NULL, NULL, 0, 0),
('71ae2632-1908-2dd4-a34b-5f003b374aef', 'contact_leads', 'Contacts', 'contacts', 'id', 'Leads', 'leads', 'contact_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('72065e26-0a96-c0cb-f91d-5f003b090b7e', 'leads_documents_1', 'Leads', 'leads', 'id', 'Documents', 'documents', 'id', 'leads_documents_1_c', 'leads_documents_1leads_ida', 'leads_documents_1documents_idb', 'many-to-many', NULL, NULL, 0, 0),
('72a2065d-c2fc-7007-226c-5f003bbaed2a', 'contact_notes', 'Contacts', 'contacts', 'id', 'Notes', 'notes', 'contact_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('72d6f9d0-9e59-f176-8eb5-5f003b9d8eaa', 'stic_payments_activities_calls', 'stic_Payments', 'stic_payments', 'id', 'Calls', 'calls', 'parent_id', NULL, NULL, NULL, 'one-to-many', 'parent_type', 'stic_Payments', 0, 0),
('7372f531-5e33-776f-a471-5f003b6b2804', 'contact_tasks', 'Contacts', 'contacts', 'id', 'Tasks', 'tasks', 'contact_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('73d5c5d5-c923-87d8-f944-5f003b3176ee', 'contact_tasks_parent', 'Contacts', 'contacts', 'id', 'Tasks', 'tasks', 'parent_id', NULL, NULL, NULL, 'one-to-many', 'parent_type', 'Contacts', 0, 0),
('749a8234-0f13-1936-c910-5f003bae59d5', 'contact_notes_parent', 'Contacts', 'contacts', 'id', 'Notes', 'notes', 'parent_id', NULL, NULL, NULL, 'one-to-many', 'parent_type', 'Contacts', 0, 0),
('74b00aa9-f15b-c429-1e0a-5f003b20ab36', 'stic_contacts_relationships_contacts', 'Contacts', 'contacts', 'id', 'stic_Contacts_Relationships', 'stic_contacts_relationships', 'id', 'stic_contacts_relationships_contacts_c', 'stic_contacts_relationships_contactscontacts_ida', 'stic_contae394onships_idb', 'many-to-many', NULL, NULL, 0, 0),
('7561a9da-8461-20d1-a8fe-5f003b983fc8', 'contact_campaign_log', 'Contacts', 'contacts', 'id', 'CampaignLog', 'campaign_log', 'target_id', NULL, NULL, NULL, 'one-to-many', 'target_type', 'Contacts', 0, 0),
('758b3d9d-1f3d-eb43-7f48-5f003b60183d', 'stic_payment_commitments_project', 'Project', 'project', 'id', 'stic_Payment_Commitments', 'stic_payment_commitments', 'id', 'stic_payment_commitments_project_c', 'stic_payment_commitments_projectproject_ida', 'stic_payment_commitments_projectstic_payment_commitments_idb', 'many-to-many', NULL, NULL, 0, 0),
('75b5ad4c-d097-66b2-b9bc-5f003ba68232', 'contact_aos_quotes', 'Contacts', 'contacts', 'id', 'AOS_Quotes', 'aos_quotes', 'billing_contact_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('76f20f2d-71da-7685-d95c-5f003b669cd5', 'contact_aos_invoices', 'Contacts', 'contacts', 'id', 'AOS_Invoices', 'aos_invoices', 'billing_contact_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('774ce696-8933-34d6-226a-5f003b9320c1', 'aok_knowledgebase_created_by', 'Users', 'users', 'id', 'AOK_KnowledgeBase', 'aok_knowledgebase', 'created_by', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('77b9756e-f777-4a5b-4aab-5f003be3ae47', 'contact_aos_contracts', 'Contacts', 'contacts', 'id', 'AOS_Contracts', 'aos_contracts', 'contact_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('78839db3-be6c-447b-c32b-5f003bc56f4d', 'contacts_aop_case_updates', 'Contacts', 'contacts', 'id', 'AOP_Case_Updates', 'aop_case_updates', 'contact_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('7884f51c-4c61-1eff-0c87-5f003b75d4b4', 'aok_knowledgebase_assigned_user', 'Users', 'users', 'id', 'AOK_KnowledgeBase', 'aok_knowledgebase', 'assigned_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('78b014ff-7c72-213b-efd2-5f003b4d70b3', 'jjwg_markers_modified_user', 'Users', 'users', 'id', 'jjwg_Markers', 'jjwg_markers', 'modified_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('7935044b-61f1-a4ee-7278-5f003b965aaf', 'campaignlog_lead', 'CampaignLog', 'campaign_log', 'related_id', 'Leads', 'leads', 'id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('795fe903-c51c-47ed-6bfb-5f003b34d672', 'securitygroups_aok_knowledgebase', 'SecurityGroups', 'securitygroups', 'id', 'AOK_KnowledgeBase', 'aok_knowledgebase', 'id', 'securitygroups_records', 'securitygroup_id', 'record_id', 'many-to-many', 'module', 'AOK_KnowledgeBase', 0, 0),
('79d88c39-5cf0-8482-7cb3-5f003bccb13c', 'jjwg_markers_created_by', 'Users', 'users', 'id', 'jjwg_Markers', 'jjwg_markers', 'created_by', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('7aba886e-18d4-e4cc-dd52-5f003b36d3d3', 'jjwg_markers_assigned_user', 'Users', 'users', 'id', 'jjwg_Markers', 'jjwg_markers', 'assigned_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('7b8b84c1-f736-ee10-04ff-5f003b8b8a31', 'reminders_modified_user', 'Users', 'users', 'id', 'Reminders', 'reminders', 'modified_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('7bb80782-401e-f712-4458-5f003b49c897', 'securitygroups_jjwg_markers', 'SecurityGroups', 'securitygroups', 'id', 'jjwg_Markers', 'jjwg_markers', 'id', 'securitygroups_records', 'securitygroup_id', 'record_id', 'many-to-many', 'module', 'jjwg_Markers', 0, 0),
('7caeca2d-b6fc-c0e7-bbf9-5f003b6a4038', 'reminders_created_by', 'Users', 'users', 'id', 'Reminders', 'reminders', 'created_by', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('7d6a65af-a505-10b2-8bf6-5f003b623626', 'accounts_modified_user', 'Users', 'users', 'id', 'Accounts', 'accounts', 'modified_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('7e583328-f475-1de7-8d39-5f003b21fd84', 'jjwg_areas_modified_user', 'Users', 'users', 'id', 'jjwg_Areas', 'jjwg_areas', 'modified_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('7e750d33-beda-0e42-6251-5f003b03e236', 'reminders_assigned_user', 'Users', 'users', 'id', 'Reminders', 'reminders', 'assigned_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('7ebdccfe-5bc8-cdb9-d9de-5f003beb2bfd', 'accounts_created_by', 'Users', 'users', 'id', 'Accounts', 'accounts', 'created_by', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('7f646bde-08ff-3406-b136-5f003bb5a28e', 'jjwg_areas_created_by', 'Users', 'users', 'id', 'jjwg_Areas', 'jjwg_areas', 'created_by', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('7f97e273-1a9c-5f89-8358-5f003be7369b', 'accounts_assigned_user', 'Users', 'users', 'id', 'Accounts', 'accounts', 'assigned_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('80373650-117b-37bc-d160-5f003b68687f', 'jjwg_areas_assigned_user', 'Users', 'users', 'id', 'jjwg_Areas', 'jjwg_areas', 'assigned_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('805721ef-5106-6386-0915-5f003b7484cd', 'securitygroups_accounts', 'SecurityGroups', 'securitygroups', 'id', 'Accounts', 'accounts', 'id', 'securitygroups_records', 'securitygroup_id', 'record_id', 'many-to-many', 'module', 'Accounts', 0, 0),
('80a00b29-8c01-d63a-f67e-5f003bb796d4', 'securitygroups_stic_remittances', 'SecurityGroups', 'securitygroups', 'id', 'stic_Remittances', 'stic_remittances', 'id', 'securitygroups_records', 'securitygroup_id', 'record_id', 'many-to-many', 'module', 'stic_Remittances', 0, 0),
('80c6156d-d02d-c901-cd95-5f003bf99cc4', 'reminders_invitees_modified_user', 'Users', 'users', 'id', 'Reminders_Invitees', 'reminders_invitees', 'modified_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('810c161f-bc4c-57d1-52ea-5f003b75c728', 'securitygroups_jjwg_areas', 'SecurityGroups', 'securitygroups', 'id', 'jjwg_Areas', 'jjwg_areas', 'id', 'securitygroups_records', 'securitygroup_id', 'record_id', 'many-to-many', 'module', 'jjwg_Areas', 0, 0),
('81180def-74c0-11df-8113-5f003b89184e', 'accounts_email_addresses', 'Accounts', 'accounts', 'id', 'EmailAddresses', 'email_addresses', 'id', 'email_addr_bean_rel', 'bean_id', 'email_address_id', 'many-to-many', 'bean_module', 'Accounts', 0, 0),
('8171187f-d3da-1dce-4ab3-5f003b2b7cc4', 'accounts_email_addresses_primary', 'Accounts', 'accounts', 'id', 'EmailAddresses', 'email_addresses', 'id', 'email_addr_bean_rel', 'bean_id', 'email_address_id', 'many-to-many', 'primary_address', '1', 0, 0),
('822daacb-bbbc-478e-6486-5f003b33db77', 'member_accounts', 'Accounts', 'accounts', 'id', 'Accounts', 'accounts', 'parent_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('82ebeb87-59d7-f841-014a-5f003b7016bf', 'jjwg_address_cache_modified_user', 'Users', 'users', 'id', 'jjwg_Address_Cache', 'jjwg_address_cache', 'modified_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('82f02b05-e794-2f14-5018-5f003b2b96f1', 'account_cases', 'Accounts', 'accounts', 'id', 'Cases', 'cases', 'account_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('8340857d-381a-0652-8873-5f003b53e73c', 'account_tasks', 'Accounts', 'accounts', 'id', 'Tasks', 'tasks', 'parent_id', NULL, NULL, NULL, 'one-to-many', 'parent_type', 'Accounts', 0, 0),
('83f38b4c-fca5-1912-3932-5f003bcac594', 'jjwg_address_cache_created_by', 'Users', 'users', 'id', 'jjwg_Address_Cache', 'jjwg_address_cache', 'created_by', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('8402048d-164f-33c8-146f-5f003beaf86f', 'account_notes', 'Accounts', 'accounts', 'id', 'Notes', 'notes', 'parent_id', NULL, NULL, NULL, 'one-to-many', 'parent_type', 'Accounts', 0, 0),
('84513a65-b89e-033a-a388-5f003b4f2121', 'account_meetings', 'Accounts', 'accounts', 'id', 'Meetings', 'meetings', 'parent_id', NULL, NULL, NULL, 'one-to-many', 'parent_type', 'Accounts', 0, 0),
('84c80392-f5b6-b5b0-2906-5f003ba99be1', 'jjwg_address_cache_assigned_user', 'Users', 'users', 'id', 'jjwg_Address_Cache', 'jjwg_address_cache', 'assigned_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('850e9b23-2a83-3843-fdac-5f003ba4a7c5', 'account_calls', 'Accounts', 'accounts', 'id', 'Calls', 'calls', 'parent_id', NULL, NULL, NULL, 'one-to-many', 'parent_type', 'Accounts', 0, 0),
('857b175c-3bab-ef9c-1b96-5f003b87486d', 'account_emails', 'Accounts', 'accounts', 'id', 'Emails', 'emails', 'parent_id', NULL, NULL, NULL, 'one-to-many', 'parent_type', 'Accounts', 0, 0),
('863b12b3-acf4-9218-639e-5f003b235779', 'account_leads', 'Accounts', 'accounts', 'id', 'Leads', 'leads', 'account_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('86780d51-4677-2995-b839-5f003b0a9f56', 'campaignlog_created_opportunities', 'CampaignLog', 'campaign_log', 'related_id', 'Opportunities', 'opportunities', 'id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('8706e211-af2d-87ab-a519-5f003b11affa', 'account_campaign_log', 'Accounts', 'accounts', 'id', 'CampaignLog', 'campaign_log', 'target_id', NULL, NULL, NULL, 'one-to-many', 'target_type', 'Accounts', 0, 0),
('8759c85e-9f55-4a74-153f-5f003bdeb7af', 'account_aos_quotes', 'Accounts', 'accounts', 'id', 'AOS_Quotes', 'aos_quotes', 'billing_account_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('87a6af5b-7127-bf70-8e54-5f003b76eaa2', 'calls_reschedule_modified_user', 'Users', 'users', 'id', 'Calls_Reschedule', 'calls_reschedule', 'modified_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('88201ded-a952-9a51-84c2-5f003be7e4e8', 'account_aos_invoices', 'Accounts', 'accounts', 'id', 'AOS_Invoices', 'aos_invoices', 'billing_account_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('88dd4633-6028-c5bf-62bd-5f003b2f9750', 'account_aos_contracts', 'Accounts', 'accounts', 'id', 'AOS_Contracts', 'aos_contracts', 'contract_account_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('88e9e263-0669-9f66-e010-5f003bbb3f07', 'calls_reschedule_created_by', 'Users', 'users', 'id', 'Calls_Reschedule', 'calls_reschedule', 'created_by', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('89a89a2f-ab56-2770-68e6-5f003b2fd50a', 'calls_reschedule_assigned_user', 'Users', 'users', 'id', 'Calls_Reschedule', 'calls_reschedule', 'assigned_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('8c11aaaa-a9d4-2b31-0015-5f003b1299ba', 'securitygroups_modified_user', 'Users', 'users', 'id', 'SecurityGroups', 'securitygroups', 'modified_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('8c6bb56d-ddd5-f813-4af0-5f003bc0d399', 'opportunities_modified_user', 'Users', 'users', 'id', 'Opportunities', 'opportunities', 'modified_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('8d7128d0-fba9-5135-cf3f-5f003be4d07c', 'opportunities_created_by', 'Users', 'users', 'id', 'Opportunities', 'opportunities', 'created_by', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('8e1fcb8f-348e-969e-9aab-5f003b0fcf8c', 'securitygroups_created_by', 'Users', 'users', 'id', 'SecurityGroups', 'securitygroups', 'created_by', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('8e43463b-998f-d7ed-3c47-5f003be10fd0', 'opportunities_assigned_user', 'Users', 'users', 'id', 'Opportunities', 'opportunities', 'assigned_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('8ef43ac3-c080-0ef9-40bf-5f003bb8072d', 'securitygroups_assigned_user', 'Users', 'users', 'id', 'SecurityGroups', 'securitygroups', 'assigned_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('8ef9fc66-991e-58a5-1178-5f003baa3265', 'securitygroups_opportunities', 'SecurityGroups', 'securitygroups', 'id', 'Opportunities', 'opportunities', 'id', 'securitygroups_records', 'securitygroup_id', 'record_id', 'many-to-many', 'module', 'Opportunities', 0, 0),
('8fd90dac-5344-35e3-bbb9-5f003b554f69', 'opportunity_calls', 'Opportunities', 'opportunities', 'id', 'Calls', 'calls', 'parent_id', NULL, NULL, NULL, 'one-to-many', 'parent_type', 'Opportunities', 0, 0),
('905f0c63-320b-45ad-abac-5f003b8f6eef', 'outbound_email_modified_user', 'Users', 'users', 'id', 'OutboundEmailAccounts', 'outbound_email', 'modified_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('90aa548e-f5e9-5a68-39b5-5f003b8feffe', 'opportunity_meetings', 'Opportunities', 'opportunities', 'id', 'Meetings', 'meetings', 'parent_id', NULL, NULL, NULL, 'one-to-many', 'parent_type', 'Opportunities', 0, 0),
('91023d1a-e4b0-0e25-9b21-5f003b88bbfc', 'opportunity_tasks', 'Opportunities', 'opportunities', 'id', 'Tasks', 'tasks', 'parent_id', NULL, NULL, NULL, 'one-to-many', 'parent_type', 'Opportunities', 0, 0),
('9162cd94-d6ea-49d9-aca8-5f003b628def', 'outbound_email_created_by', 'Users', 'users', 'id', 'OutboundEmailAccounts', 'outbound_email', 'created_by', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('92165de6-8369-2d03-391e-5f003b6a1c2e', 'reminders_invitees_created_by', 'Users', 'users', 'id', 'Reminders_Invitees', 'reminders_invitees', 'created_by', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('922eb898-a117-b11d-4667-5f003bdeb7e1', 'outbound_email_assigned_user', 'Users', 'users', 'id', 'OutboundEmailAccounts', 'outbound_email', 'assigned_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('931909c9-c825-72b0-c27a-5f003bd8d6f0', 'reminders_invitees_assigned_user', 'Users', 'users', 'id', 'Reminders_Invitees', 'reminders_invitees', 'assigned_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('93dea512-273b-b241-ea74-5f003bf26bc0', 'templatesectionline_modified_user', 'Users', 'users', 'id', 'TemplateSectionLine', 'templatesectionline', 'modified_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('93e2b9b2-b343-7335-dbe8-5f003b3b68cf', 'opportunity_notes', 'Opportunities', 'opportunities', 'id', 'Notes', 'notes', 'parent_id', NULL, NULL, NULL, 'one-to-many', 'parent_type', 'Opportunities', 0, 0),
('94b5ea7e-599d-d18c-9d55-5f003b38f3d8', 'opportunity_emails', 'Opportunities', 'opportunities', 'id', 'Emails', 'emails', 'parent_id', NULL, NULL, NULL, 'one-to-many', 'parent_type', 'Opportunities', 0, 0),
('94d5ffc1-04b3-ff84-fad6-5f003b9b7643', 'templatesectionline_created_by', 'Users', 'users', 'id', 'TemplateSectionLine', 'templatesectionline', 'created_by', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('952b0231-6ec0-26b0-8d71-5f003b7ac09c', 'campaignlog_targeted_users', 'CampaignLog', 'campaign_log', 'target_id', 'Users', 'users', 'id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('957dc76b-7d60-3337-3cd0-5f003bc9370a', 'opportunity_leads', 'Opportunities', 'opportunities', 'id', 'Leads', 'leads', 'opportunity_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('9591551c-295a-9f44-3289-5f003b9f998b', 'fp_events_modified_user', 'Users', 'users', 'id', 'FP_events', 'fp_events', 'modified_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('960884ff-003b-2c0a-fa00-5f003b3668db', 'opportunity_currencies', 'Opportunities', 'opportunities', 'currency_id', 'Currencies', 'currencies', 'id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('96cbd000-2af6-a411-7533-5f003b674092', 'opportunities_campaign', 'Campaigns', 'campaigns', 'id', 'Opportunities', 'opportunities', 'campaign_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('96f4a248-a19e-4e94-c98f-5f003b422165', 'oauth2tokens_modified_user', 'Users', 'users', 'id', 'OAuth2Tokens', 'oauth2tokens', 'modified_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('98082a0d-53d7-94fc-3d5a-5f003b056968', 'fp_events_created_by', 'Users', 'users', 'id', 'FP_events', 'fp_events', 'created_by', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('990a7ec6-db8d-dc0f-12e0-5f003bf2c908', 'fp_events_assigned_user', 'Users', 'users', 'id', 'FP_events', 'fp_events', 'assigned_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('9925013b-d090-d0f9-2d1c-5f003bc13888', 'stic_settings_modified_user', 'Users', 'users', 'id', 'stic_Settings', 'stic_settings', 'modified_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('99f18692-6837-bf63-ef76-5f003b529c7b', 'securitygroups_fp_events', 'SecurityGroups', 'securitygroups', 'id', 'FP_events', 'fp_events', 'id', 'securitygroups_records', 'securitygroup_id', 'record_id', 'many-to-many', 'module', 'FP_events', 0, 0),
('9cbb66f0-19ea-5f66-6640-5f003bbe7059', 'oauth2tokens_created_by', 'Users', 'users', 'id', 'OAuth2Tokens', 'oauth2tokens', 'created_by', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('9cbf6518-ac48-6bfc-aad1-5f003bebfb20', 'opportunity_aos_quotes', 'Opportunities', 'opportunities', 'id', 'AOS_Quotes', 'aos_quotes', 'opportunity_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('9d04e4cd-7800-548a-5b2c-5f003b64e23c', 'fp_event_locations_modified_user', 'Users', 'users', 'id', 'FP_Event_Locations', 'fp_event_locations', 'modified_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('9d8a096c-598f-002c-5582-5f003b83e430', 'opportunity_aos_contracts', 'Opportunities', 'opportunities', 'id', 'AOS_Contracts', 'aos_contracts', 'opportunity_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('9dd17b0c-92ae-8a15-abdc-5f003b53d042', 'oauth2tokens_assigned_user', 'Users', 'users', 'id', 'OAuth2Tokens', 'oauth2tokens', 'assigned_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('9e11e920-6798-4f17-98bf-5f003bc77e0f', 'fp_event_locations_created_by', 'Users', 'users', 'id', 'FP_Event_Locations', 'fp_event_locations', 'created_by', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('9efd9503-153b-0834-700c-5f003b52e1d3', 'fp_event_locations_assigned_user', 'Users', 'users', 'id', 'FP_Event_Locations', 'fp_event_locations', 'assigned_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('a00b32d5-e34c-184f-8c42-5f003b291c05', 'oauth2clients_modified_user', 'Users', 'users', 'id', 'OAuth2Clients', 'oauth2clients', 'modified_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('a01af1a6-e3f3-afa2-3626-5f003b45d630', 'securitygroups_fp_event_locations', 'SecurityGroups', 'securitygroups', 'id', 'FP_Event_Locations', 'fp_event_locations', 'id', 'securitygroups_records', 'securitygroup_id', 'record_id', 'many-to-many', 'module', 'FP_Event_Locations', 0, 0),
('a03e378c-3e78-8d68-9dd6-5f003bdd6068', 'securitygroups_emailtemplates', 'SecurityGroups', 'securitygroups', 'id', 'EmailTemplates', 'email_templates', 'id', 'securitygroups_records', 'securitygroup_id', 'record_id', 'many-to-many', 'module', 'EmailTemplates', 0, 0),
('a10b6133-08ce-670e-528a-5f003bd67bcc', 'oauth2clients_created_by', 'Users', 'users', 'id', 'OAuth2Clients', 'oauth2clients', 'created_by', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('a14cf247-9055-f965-a297-5f003b0dd3cb', 'optimistic_locking', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0),
('a1cbaf5f-5e54-e1bf-2aac-5f003b0965cb', 'oauth2clients_oauth2tokens', 'OAuth2Clients', 'oauth2clients', 'id', 'OAuth2Tokens', 'oauth2tokens', 'client', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('a21780c7-4ceb-6f62-e1c0-5f003b8bc758', 'unified_search', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0),
('a2320519-0364-0ff3-7e80-5f003b133e83', 'campaignlog_sent_emails', 'CampaignLog', 'campaign_log', 'related_id', 'Emails', 'emails', 'id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('a285d8fa-f20e-b49f-f824-5f003b52fa04', 'oauth2clients_assigned_user', 'Users', 'users', 'id', 'OAuth2Clients', 'oauth2clients', 'assigned_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('a470e1f5-58fb-d089-c414-5f003b7370ae', 'surveyresponses_modified_user', 'Users', 'users', 'id', 'SurveyResponses', 'surveyresponses', 'modified_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('a4fe9e9c-c524-b8b7-818b-5f003bd0a300', 'aod_indexevent_modified_user', 'Users', 'users', 'id', 'AOD_IndexEvent', 'aod_indexevent', 'modified_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('a565f55a-616a-9e04-b0c9-5f003bd858a7', 'surveyresponses_created_by', 'Users', 'users', 'id', 'SurveyResponses', 'surveyresponses', 'created_by', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('a61de213-2be5-5447-a487-5f003b59a628', 'aod_indexevent_created_by', 'Users', 'users', 'id', 'AOD_IndexEvent', 'aod_indexevent', 'created_by', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('a6221a07-55a7-c5cb-5bfa-5f003b8f40d3', 'surveyresponses_assigned_user', 'Users', 'users', 'id', 'SurveyResponses', 'surveyresponses', 'assigned_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('a64bfdae-bf89-053c-27d0-5f003bd4dc57', 'emailtemplates_assigned_user', 'Users', 'users', 'id', 'EmailTemplates', 'email_templates', 'assigned_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('a67dea61-68b6-14ce-996d-5f003ba106bc', 'securitygroups_surveyresponses', 'SecurityGroups', 'securitygroups', 'id', 'SurveyResponses', 'surveyresponses', 'id', 'securitygroups_records', 'securitygroup_id', 'record_id', 'many-to-many', 'module', 'SurveyResponses', 0, 0),
('a6ff7bfe-2bc5-6fc2-8c2a-5f003b5b212e', 'aod_indexevent_assigned_user', 'Users', 'users', 'id', 'AOD_IndexEvent', 'aod_indexevent', 'assigned_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('a7561b50-3630-29de-bf5a-5f003bfb3b16', 'surveyresponses_surveyquestionresponses', 'SurveyResponses', 'surveyresponses', 'id', 'SurveyQuestionResponses', 'surveyquestionresponses', 'surveyresponse_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('a8f03deb-01fc-5b9b-3ce6-5f003b53d40e', 'notes_assigned_user', 'Users', 'users', 'id', 'Notes', 'notes', 'assigned_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('a9566f4b-084a-476e-3f99-5f003bf17914', 'surveys_modified_user', 'Users', 'users', 'id', 'Surveys', 'surveys', 'modified_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('aa081cd4-ce75-db49-bb0b-5f003bd7197d', 'aod_index_modified_user', 'Users', 'users', 'id', 'AOD_Index', 'aod_index', 'modified_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('aa487f3a-b338-88a2-02cf-5f003bb500ea', 'surveys_created_by', 'Users', 'users', 'id', 'Surveys', 'surveys', 'created_by', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('aacc0454-6268-a25e-05f9-5f003b72b647', 'securitygroups_notes', 'SecurityGroups', 'securitygroups', 'id', 'Notes', 'notes', 'id', 'securitygroups_records', 'securitygroup_id', 'record_id', 'many-to-many', 'module', 'Notes', 0, 0),
('ab13d4ed-9b98-955a-6602-5f003b3a8f47', 'surveys_assigned_user', 'Users', 'users', 'id', 'Surveys', 'surveys', 'assigned_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('ab66a954-e82a-0cb7-3a66-5f003bf12ac4', 'securitygroups_surveys', 'SecurityGroups', 'securitygroups', 'id', 'Surveys', 'surveys', 'id', 'securitygroups_records', 'securitygroup_id', 'record_id', 'many-to-many', 'module', 'Surveys', 0, 0),
('ab9feef6-bcd5-2bfb-1aae-5f003b81835c', 'aod_index_created_by', 'Users', 'users', 'id', 'AOD_Index', 'aod_index', 'created_by', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('abcb11ad-5782-a9a8-1678-5f003b5388b9', 'notes_modified_user', 'Users', 'users', 'id', 'Notes', 'notes', 'modified_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('ac1301e5-7836-9b81-dc41-5f003b6503c1', 'surveys_surveyquestions', 'Surveys', 'surveys', 'id', 'SurveyQuestions', 'surveyquestions', 'survey_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('ac6bf35f-e1c5-ef14-a17a-5f003b265130', 'surveys_surveyresponses', 'Surveys', 'surveys', 'id', 'SurveyResponses', 'surveyresponses', 'survey_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('ac96fef3-8830-0a98-44cf-5f003bb3cea9', 'notes_created_by', 'Users', 'users', 'id', 'Notes', 'notes', 'created_by', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('acad6c0b-2477-0535-efd9-5f003bf588bb', 'aod_index_assigned_user', 'Users', 'users', 'id', 'AOD_Index', 'aod_index', 'assigned_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('ae733497-b592-5978-cfcc-5f003bc8fefe', 'surveys_campaigns', 'Surveys', 'surveys', 'id', 'Campaigns', 'campaigns', 'survey_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('af565818-02de-1ddd-534a-5f003bfca060', 'aop_case_events_modified_user', 'Users', 'users', 'id', 'AOP_Case_Events', 'aop_case_events', 'modified_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('af664dd8-5418-e469-78fa-5f003b404044', 'calls_modified_user', 'Users', 'users', 'id', 'Calls', 'calls', 'modified_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('b0862016-251d-a92f-dee3-5f003b5ac19f', 'surveyquestionresponses_modified_user', 'Users', 'users', 'id', 'SurveyQuestionResponses', 'surveyquestionresponses', 'modified_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('b127fc71-ecff-1913-10b3-5f003b4538f6', 'aop_case_events_created_by', 'Users', 'users', 'id', 'AOP_Case_Events', 'aop_case_events', 'created_by', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('b171064c-64ed-9e65-a0bd-5f003b3efbbd', 'inbound_email_modified_user_id', 'Users', 'users', 'id', 'InboundEmail', 'inbound_email', 'modified_user_id', NULL, NULL, NULL, 'one-to-one', NULL, NULL, 0, 0),
('b182a20c-ebbb-660c-4a63-5f003b8edf69', 'surveyquestionresponses_created_by', 'Users', 'users', 'id', 'SurveyQuestionResponses', 'surveyquestionresponses', 'created_by', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('b1abf8f0-e6bc-0202-495a-5f003b886800', 'leads_modified_user', 'Users', 'users', 'id', 'Leads', 'leads', 'modified_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('b2272821-5063-6620-1438-5f003b359887', 'aop_case_events_assigned_user', 'Users', 'users', 'id', 'AOP_Case_Events', 'aop_case_events', 'assigned_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('b267dd57-2807-36a6-d5ce-5f003b626b35', 'surveyquestionresponses_assigned_user', 'Users', 'users', 'id', 'SurveyQuestionResponses', 'surveyquestionresponses', 'assigned_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('b3270bf5-3c82-f9b8-2a1b-5f003b482650', 'cases_aop_case_events', 'Cases', 'cases', 'id', 'AOP_Case_Events', 'aop_case_events', 'case_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('b32720a7-78d8-4727-cd09-5f003ba58fd5', 'securitygroups_surveyquestionresponses', 'SecurityGroups', 'securitygroups', 'id', 'SurveyQuestionResponses', 'surveyquestionresponses', 'id', 'securitygroups_records', 'securitygroup_id', 'record_id', 'many-to-many', 'module', 'SurveyQuestionResponses', 0, 0),
('b34f126f-ac35-984f-c7cc-5f003bcec124', 'leads_created_by', 'Users', 'users', 'id', 'Leads', 'leads', 'created_by', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('b39f09d9-1b19-38d5-f1f1-5f003bfcdcab', 'stic_settings_created_by', 'Users', 'users', 'id', 'stic_Settings', 'stic_settings', 'created_by', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('b43e85e3-acc8-f284-661b-5f003b44f74c', 'leads_assigned_user', 'Users', 'users', 'id', 'Leads', 'leads', 'assigned_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('b50d4f37-8bb7-9388-8b26-5f003b340ab4', 'securitygroups_leads', 'SecurityGroups', 'securitygroups', 'id', 'Leads', 'leads', 'id', 'securitygroups_records', 'securitygroup_id', 'record_id', 'many-to-many', 'module', 'Leads', 0, 0),
('b52148e6-636c-0ba5-f7e2-5f003b3ed391', 'surveyquestions_modified_user', 'Users', 'users', 'id', 'SurveyQuestions', 'surveyquestions', 'modified_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('b5ceb721-f388-89d5-d028-5f003b9605a7', 'aop_case_updates_modified_user', 'Users', 'users', 'id', 'AOP_Case_Updates', 'aop_case_updates', 'modified_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('b623d618-71bf-edf4-97bf-5f003b9aa478', 'surveyquestions_created_by', 'Users', 'users', 'id', 'SurveyQuestions', 'surveyquestions', 'created_by', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('b66b537b-5e8f-1109-6c35-5f003bd6646b', 'leads_email_addresses', 'Leads', 'leads', 'id', 'EmailAddresses', 'email_addresses', 'id', 'email_addr_bean_rel', 'bean_id', 'email_address_id', 'many-to-many', 'bean_module', 'Leads', 0, 0),
('b6f4d7aa-3cca-71c4-a7de-5f003bb05819', 'surveyquestions_assigned_user', 'Users', 'users', 'id', 'SurveyQuestions', 'surveyquestions', 'assigned_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('b7542744-cf29-0810-a45e-5f003b86cc4f', 'leads_email_addresses_primary', 'Leads', 'leads', 'id', 'EmailAddresses', 'email_addresses', 'id', 'email_addr_bean_rel', 'bean_id', 'email_address_id', 'many-to-many', 'primary_address', '1', 0, 0),
('b78ba269-cdf1-fa4a-ad4f-5f003bd5f3fb', 'aop_case_updates_created_by', 'Users', 'users', 'id', 'AOP_Case_Updates', 'aop_case_updates', 'created_by', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('b7b27ead-56ec-737f-ce2d-5f003b59a62d', 'securitygroups_surveyquestions', 'SecurityGroups', 'securitygroups', 'id', 'SurveyQuestions', 'surveyquestions', 'id', 'securitygroups_records', 'securitygroup_id', 'record_id', 'many-to-many', 'module', 'SurveyQuestions', 0, 0),
('b805c35e-f3d7-6201-934c-5f003bb7991e', 'surveyquestions_surveyquestionoptions', 'SurveyQuestions', 'surveyquestions', 'id', 'SurveyQuestionOptions', 'surveyquestionoptions', 'survey_question_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('b833bf04-ecbf-7b24-9ee0-5f003b984196', 'lead_direct_reports', 'Leads', 'leads', 'id', 'Leads', 'leads', 'reports_to_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('b8964713-3ca3-670b-f6b3-5f003b2ba62f', 'aop_case_updates_assigned_user', 'Users', 'users', 'id', 'AOP_Case_Updates', 'aop_case_updates', 'assigned_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('b90ebb41-fa8e-6bb9-99b9-5f003b149a04', 'lead_tasks', 'Leads', 'leads', 'id', 'Tasks', 'tasks', 'parent_id', NULL, NULL, NULL, 'one-to-many', 'parent_type', 'Leads', 0, 0),
('b9881b57-31ee-8c29-9dc4-5f003b95a420', 'cases_aop_case_updates', 'Cases', 'cases', 'id', 'AOP_Case_Updates', 'aop_case_updates', 'case_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('b9e3f1a4-9663-6e79-d1ba-5f003b943ccf', 'surveyquestionoptions_modified_user', 'Users', 'users', 'id', 'SurveyQuestionOptions', 'surveyquestionoptions', 'modified_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('b9f06ffd-4176-301d-5f3a-5f003b23c366', 'lead_notes', 'Leads', 'leads', 'id', 'Notes', 'notes', 'parent_id', NULL, NULL, NULL, 'one-to-many', 'parent_type', 'Leads', 0, 0),
('ba890271-4395-178e-93d4-5f003bcd0f4d', 'aop_case_updates_notes', 'AOP_Case_Updates', 'aop_case_updates', 'id', 'Notes', 'notes', 'parent_id', NULL, NULL, NULL, 'one-to-many', 'parent_type', 'AOP_Case_Updates', 0, 0),
('baedfe23-464f-eb3e-4463-5f003b89446c', 'surveyquestionoptions_created_by', 'Users', 'users', 'id', 'SurveyQuestionOptions', 'surveyquestionoptions', 'created_by', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('bb333d68-6d7a-b76c-e23e-5f003b315dac', 'calls_created_by', 'Users', 'users', 'id', 'Calls', 'calls', 'created_by', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('bbeeb354-4cb6-a1f4-c061-5f003bebe361', 'surveyquestionoptions_assigned_user', 'Users', 'users', 'id', 'SurveyQuestionOptions', 'surveyquestionoptions', 'assigned_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('bc3dd23b-382a-85fd-8d6a-5f003b0653ad', 'calls_assigned_user', 'Users', 'users', 'id', 'Calls', 'calls', 'assigned_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('bc9c8a82-fa52-3850-66de-5f003bc3a06b', 'lead_meetings', 'Leads', 'leads', 'id', 'Meetings', 'meetings', 'parent_id', NULL, NULL, NULL, 'one-to-many', 'parent_type', 'Leads', 0, 0),
('bcd98f3a-991c-4474-6104-5f003bee40c9', 'securitygroups_surveyquestionoptions', 'SecurityGroups', 'securitygroups', 'id', 'SurveyQuestionOptions', 'surveyquestionoptions', 'id', 'securitygroups_records', 'securitygroup_id', 'record_id', 'many-to-many', 'module', 'SurveyQuestionOptions', 0, 0),
('bd5e8ee7-80bb-d332-f91d-5f003b7de7ea', 'aor_reports_modified_user', 'Users', 'users', 'id', 'AOR_Reports', 'aor_reports', 'modified_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('bdb4d918-4103-a4a4-f1c6-5f003b845aaf', 'securitygroups_calls', 'SecurityGroups', 'securitygroups', 'id', 'Calls', 'calls', 'id', 'securitygroups_records', 'securitygroup_id', 'record_id', 'many-to-many', 'module', 'Calls', 0, 0),
('bde0847c-a607-7808-1824-5f003b24412c', 'lead_calls', 'Leads', 'leads', 'id', 'Calls', 'calls', 'parent_id', NULL, NULL, NULL, 'one-to-many', 'parent_type', 'Leads', 0, 0),
('bec0bbae-4619-ab1d-d91c-5f003bd7e395', 'calls_notes', 'Calls', 'calls', 'id', 'Notes', 'notes', 'parent_id', NULL, NULL, NULL, 'one-to-many', 'parent_type', 'Calls', 0, 0),
('bee42973-6ca5-f74e-075e-5f003ba5f1d3', 'lead_emails', 'Leads', 'leads', 'id', 'Emails', 'emails', 'parent_id', NULL, NULL, NULL, 'one-to-many', 'parent_type', 'Leads', 0, 0),
('bfa75dc5-8319-06b5-fbbc-5f003bdabbeb', 'calls_reschedule', 'Calls', 'calls', 'id', 'Calls_Reschedule', 'calls_reschedule', 'call_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('bfb3ffc2-a59a-a2c5-ff6e-5f003b493729', 'lead_campaign_log', 'Leads', 'leads', 'id', 'CampaignLog', 'campaign_log', 'target_id', NULL, NULL, NULL, 'one-to-many', 'target_type', 'Leads', 0, 0),
('c04a0e07-993d-535d-69d6-5f003b98678c', 'aor_reports_created_by', 'Users', 'users', 'id', 'AOR_Reports', 'aor_reports', 'created_by', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('c0522ba1-1160-eb1e-ac0a-5f003be37684', 'dha_plantillasdocumentos_modified_user', 'Users', 'users', 'id', 'DHA_PlantillasDocumentos', 'dha_plantillasdocumentos', 'modified_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('c15e2f82-97b0-866b-2203-5f003bbb82e5', 'aor_reports_assigned_user', 'Users', 'users', 'id', 'AOR_Reports', 'aor_reports', 'assigned_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('c1757f88-1bfd-92ff-8aa2-5f003b867086', 'emails_modified_user', 'Users', 'users', 'id', 'Emails', 'emails', 'modified_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('c17799f7-b881-b887-2c12-5f003b02cfe4', 'dha_plantillasdocumentos_created_by', 'Users', 'users', 'id', 'DHA_PlantillasDocumentos', 'dha_plantillasdocumentos', 'created_by', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('c23900d2-47b8-af94-7809-5f003b36596a', 'stic_settings_assigned_user', 'Users', 'users', 'id', 'stic_Settings', 'stic_settings', 'assigned_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('c2444e7e-befb-6a51-b96b-5f003b9395c6', 'securitygroups_aor_reports', 'SecurityGroups', 'securitygroups', 'id', 'AOR_Reports', 'aor_reports', 'id', 'securitygroups_records', 'securitygroup_id', 'record_id', 'many-to-many', 'module', 'AOR_Reports', 0, 0),
('c2785239-5fe4-cc88-d569-5f003bdb4880', 'dha_plantillasdocumentos_assigned_user', 'Users', 'users', 'id', 'DHA_PlantillasDocumentos', 'dha_plantillasdocumentos', 'assigned_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('c29adbaf-aebb-b016-dc3f-5f003b8844ec', 'emails_created_by', 'Users', 'users', 'id', 'Emails', 'emails', 'created_by', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('c30a86ad-0809-515c-edef-5f003b56b8be', 'cases_modified_user', 'Users', 'users', 'id', 'Cases', 'cases', 'modified_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('c353a3fe-147c-9dbe-4e61-5f003ba3d6cc', 'aor_reports_aor_fields', 'AOR_Reports', 'aor_reports', 'id', 'AOR_Fields', 'aor_fields', 'aor_report_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('c378d91f-67d4-6e89-b996-5f003b4a7535', 'emails_assigned_user', 'Users', 'users', 'id', 'Emails', 'emails', 'assigned_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('c3fa3799-664a-b19f-c6e0-5f003b7bd748', 'securitygroups_emails', 'SecurityGroups', 'securitygroups', 'id', 'Emails', 'emails', 'id', 'securitygroups_records', 'securitygroup_id', 'record_id', 'many-to-many', 'module', 'Emails', 0, 0),
('c42463ed-abde-4b1b-c034-5f003b1ceceb', 'aor_reports_aor_conditions', 'AOR_Reports', 'aor_reports', 'id', 'AOR_Conditions', 'aor_conditions', 'aor_report_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('c4c1226a-e249-19f7-9583-5f003bb993ce', 'emails_notes_rel', 'Emails', 'emails', 'id', 'Notes', 'notes', 'parent_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('c533c026-506f-0e2e-52dc-5f003b307f06', 'aor_scheduled_reports_aor_reports', 'AOR_Reports', 'aor_reports', 'id', 'AOR_Scheduled_Reports', 'aor_scheduled_reports', 'aor_report_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('c571158f-e3f0-9017-7547-5f003b381a58', 'kreports_modified_user', 'Users', 'users', 'id', 'KReports', 'kreports', 'modified_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('c5942183-ac36-7bdc-46cb-5f003b5c6cab', 'emails_contacts_rel', 'Emails', 'emails', 'id', 'Contacts', 'contacts', 'id', 'emails_beans', 'email_id', 'bean_id', 'many-to-many', 'bean_module', 'Contacts', 0, 0),
('c660f08b-fb12-f824-1060-5f003ba36ee0', 'emails_accounts_rel', 'Emails', 'emails', 'id', 'Accounts', 'accounts', 'id', 'emails_beans', 'email_id', 'bean_id', 'many-to-many', 'bean_module', 'Accounts', 0, 0),
('c66c39c4-d4a0-9ab5-b29b-5f003b337300', 'kreports_created_by', 'Users', 'users', 'id', 'KReports', 'kreports', 'created_by', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('c6b4e47a-371b-37b5-5586-5f003bd8cbc5', 'emails_leads_rel', 'Emails', 'emails', 'id', 'Leads', 'leads', 'id', 'emails_beans', 'email_id', 'bean_id', 'many-to-many', 'bean_module', 'Leads', 0, 0),
('c71ce094-86d0-00bb-b8be-5f003be371e8', 'cases_created_by', 'Users', 'users', 'id', 'Cases', 'cases', 'created_by', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('c7896229-b4ca-6912-7601-5f003b584896', 'emails_aos_contracts_rel', 'Emails', 'emails', 'id', 'AOS_Contracts', 'aos_contracts', 'id', 'emails_beans', 'email_id', 'bean_id', 'many-to-many', 'bean_module', 'AOS_Contracts', 0, 0),
('c79f11c7-5a03-6bd5-1e3d-5f003b04267b', 'kreports_assigned_user', 'Users', 'users', 'id', 'KReports', 'kreports', 'assigned_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('c7dbccc7-fd98-eda7-34c8-5f003b204350', 'aor_fields_modified_user', 'Users', 'users', 'id', 'AOR_Fields', 'aor_fields', 'modified_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('c82a89de-5d9b-b50e-2485-5f003bc245c6', 'cases_assigned_user', 'Users', 'users', 'id', 'Cases', 'cases', 'assigned_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('c85075b7-3f84-4c09-fb99-5f003bf37823', 'emails_meetings_rel', 'Emails', 'emails', 'id', 'Meetings', 'meetings', 'id', 'emails_beans', 'email_id', 'bean_id', 'many-to-many', 'bean_module', 'Meetings', 0, 0),
('c86b1729-6f1e-a25b-6d3a-5f003b46811b', 'securitygroups_kreports', 'SecurityGroups', 'securitygroups', 'id', 'KReports', 'kreports', 'id', 'securitygroups_records', 'securitygroup_id', 'record_id', 'many-to-many', 'module', 'KReports', 0, 0),
('c907b53c-ec65-736d-5844-5f003b923f22', 'securitygroups_cases', 'SecurityGroups', 'securitygroups', 'id', 'Cases', 'cases', 'id', 'securitygroups_records', 'securitygroup_id', 'record_id', 'many-to-many', 'module', 'Cases', 0, 0),
('ca26cd51-a6a0-51ed-cb9f-5f003b68d4b9', 'case_calls', 'Cases', 'cases', 'id', 'Calls', 'calls', 'parent_id', NULL, NULL, NULL, 'one-to-many', 'parent_type', 'Cases', 0, 0),
('ca549f81-04aa-9f89-555f-5f003be57012', 'stic_accounts_relationships_modified_user', 'Users', 'users', 'id', 'stic_Accounts_Relationships', 'stic_accounts_relationships', 'modified_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('ca9b7a8d-d15a-ffa9-8eea-5f003b41f2fc', 'aor_fields_created_by', 'Users', 'users', 'id', 'AOR_Fields', 'aor_fields', 'created_by', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('caf56d7f-1dda-21fe-1947-5f003bf72ef0', 'case_tasks', 'Cases', 'cases', 'id', 'Tasks', 'tasks', 'parent_id', NULL, NULL, NULL, 'one-to-many', 'parent_type', 'Cases', 0, 0),
('cb2b23e9-8f2a-5918-d752-5f003b3e9fdf', 'meetings_modified_user', 'Users', 'users', 'id', 'Meetings', 'meetings', 'modified_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('cb5ef336-d548-4175-4f45-5f003bc27e5d', 'stic_accounts_relationships_created_by', 'Users', 'users', 'id', 'stic_Accounts_Relationships', 'stic_accounts_relationships', 'created_by', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('cbc3a684-6081-a87f-88c4-5f003bb0eadc', 'case_notes', 'Cases', 'cases', 'id', 'Notes', 'notes', 'parent_id', NULL, NULL, NULL, 'one-to-many', 'parent_type', 'Cases', 0, 0),
('cc4eba2a-32fa-6c89-fa7a-5f003b0d870e', 'stic_accounts_relationships_assigned_user', 'Users', 'users', 'id', 'stic_Accounts_Relationships', 'stic_accounts_relationships', 'assigned_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('cc83599c-5ade-75d5-0466-5f003b7b4929', 'case_meetings', 'Cases', 'cases', 'id', 'Meetings', 'meetings', 'parent_id', NULL, NULL, NULL, 'one-to-many', 'parent_type', 'Cases', 0, 0),
('ccdf793c-cd92-176b-39eb-5f003b1aeda1', 'aor_charts_modified_user', 'Users', 'users', 'id', 'AOR_Charts', 'aor_charts', 'modified_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('cd24b019-7954-9533-db5f-5f003b7be17d', 'securitygroups_stic_accounts_relationships', 'SecurityGroups', 'securitygroups', 'id', 'stic_Accounts_Relationships', 'stic_accounts_relationships', 'id', 'securitygroups_records', 'securitygroup_id', 'record_id', 'many-to-many', 'module', 'stic_Accounts_Relationships', 0, 0),
('cd4830b3-6032-e8d9-48de-5f003b8e6407', 'case_emails', 'Cases', 'cases', 'id', 'Emails', 'emails', 'parent_id', NULL, NULL, NULL, 'one-to-many', 'parent_type', 'Cases', 0, 0),
('cdc239ff-b2ce-7eac-af08-5f003ba6496d', 'cases_created_contact', 'Contacts', 'contacts', 'id', 'Cases', 'cases', 'contact_created_by_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('ce6eb92c-eeb7-9b29-9e0c-5f003b0168a9', 'aor_charts_created_by', 'Users', 'users', 'id', 'AOR_Charts', 'aor_charts', 'created_by', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('ce8004e4-fea2-e627-7e8e-5f003b2520e1', 'securitygroups_project', 'SecurityGroups', 'securitygroups', 'id', 'Project', 'project', 'id', 'securitygroups_records', 'securitygroup_id', 'record_id', 'many-to-many', 'module', 'Project', 0, 0),
('cf59ea06-0a00-57ee-dbe2-5f003b79608c', 'stic_attendances_modified_user', 'Users', 'users', 'id', 'stic_Attendances', 'stic_attendances', 'modified_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('cf9cd3c7-95b6-4914-a82a-5f003b35c02e', 'aor_charts_aor_reports', 'AOR_Reports', 'aor_reports', 'id', 'AOR_Charts', 'aor_charts', 'aor_report_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('cfad8693-f77c-6432-1c8c-5f003ba9fc68', 'meetings_created_by', 'Users', 'users', 'id', 'Meetings', 'meetings', 'created_by', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('d01dfd89-7b7c-defd-f091-5f003b31542e', 'bugs_modified_user', 'Users', 'users', 'id', 'Bugs', 'bugs', 'modified_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('d0a975ae-8083-6684-be53-5f003b558f39', 'meetings_assigned_user', 'Users', 'users', 'id', 'Meetings', 'meetings', 'assigned_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('d149286e-9ba5-dfd3-a614-5f003bd7cc6b', 'bugs_created_by', 'Users', 'users', 'id', 'Bugs', 'bugs', 'created_by', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('d16f9864-76b3-6e64-6a8c-5f003b0db998', 'stic_attendances_created_by', 'Users', 'users', 'id', 'stic_Attendances', 'stic_attendances', 'created_by', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('d1a4078a-7d4f-d8a5-9160-5f003b943839', 'securitygroups_stic_settings', 'SecurityGroups', 'securitygroups', 'id', 'stic_Settings', 'stic_settings', 'id', 'securitygroups_records', 'securitygroup_id', 'record_id', 'many-to-many', 'module', 'stic_Settings', 0, 0);
INSERT INTO `relationships` (`id`, `relationship_name`, `lhs_module`, `lhs_table`, `lhs_key`, `rhs_module`, `rhs_table`, `rhs_key`, `join_table`, `join_key_lhs`, `join_key_rhs`, `relationship_type`, `relationship_role_column`, `relationship_role_column_value`, `reverse`, `deleted`) VALUES
('d1a5336b-0b5c-f844-b221-5f003bfd2892', 'securitygroups_meetings', 'SecurityGroups', 'securitygroups', 'id', 'Meetings', 'meetings', 'id', 'securitygroups_records', 'securitygroup_id', 'record_id', 'many-to-many', 'module', 'Meetings', 0, 0),
('d1e7dd4a-b2ef-5a67-a850-5f003b4bc846', 'aor_conditions_modified_user', 'Users', 'users', 'id', 'AOR_Conditions', 'aor_conditions', 'modified_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('d2190205-d5bc-0407-201a-5f003bb13237', 'bugs_assigned_user', 'Users', 'users', 'id', 'Bugs', 'bugs', 'assigned_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('d268cfff-63a0-a240-92ce-5f003b570ca6', 'meetings_notes', 'Meetings', 'meetings', 'id', 'Notes', 'notes', 'parent_id', NULL, NULL, NULL, 'one-to-many', 'parent_type', 'Meetings', 0, 0),
('d28372a3-57b3-8e42-96ce-5f003b25fea1', 'securitygroups_bugs', 'SecurityGroups', 'securitygroups', 'id', 'Bugs', 'bugs', 'id', 'securitygroups_records', 'securitygroup_id', 'record_id', 'many-to-many', 'module', 'Bugs', 0, 0),
('d29c336f-9e36-7631-2bcf-5f003bb87780', 'stic_attendances_assigned_user', 'Users', 'users', 'id', 'stic_Attendances', 'stic_attendances', 'assigned_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('d34ecf5f-9daf-a7e0-e14c-5f003bc3dab2', 'bug_tasks', 'Bugs', 'bugs', 'id', 'Tasks', 'tasks', 'parent_id', NULL, NULL, NULL, 'one-to-many', 'parent_type', 'Bugs', 0, 0),
('d3663096-a2bc-3fcf-b12f-5f003b5e8663', 'securitygroups_stic_attendances', 'SecurityGroups', 'securitygroups', 'id', 'stic_Attendances', 'stic_attendances', 'id', 'securitygroups_records', 'securitygroup_id', 'record_id', 'many-to-many', 'module', 'stic_Attendances', 0, 0),
('d3788756-cbd8-19ee-523c-5f003b2930df', 'aor_conditions_created_by', 'Users', 'users', 'id', 'AOR_Conditions', 'aor_conditions', 'created_by', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('d40ef3d5-4946-1958-81b3-5f003b510489', 'bug_meetings', 'Bugs', 'bugs', 'id', 'Meetings', 'meetings', 'parent_id', NULL, NULL, NULL, 'one-to-many', 'parent_type', 'Bugs', 0, 0),
('d46a0fd1-ec5d-1171-23c8-5f003bb152cc', 'bug_calls', 'Bugs', 'bugs', 'id', 'Calls', 'calls', 'parent_id', NULL, NULL, NULL, 'one-to-many', 'parent_type', 'Bugs', 0, 0),
('d51d9235-9a63-7fb5-a15d-5f003b348ec9', 'bug_emails', 'Bugs', 'bugs', 'id', 'Emails', 'emails', 'parent_id', NULL, NULL, NULL, 'one-to-many', 'parent_type', 'Bugs', 0, 0),
('d53b0d03-a1bd-50f7-74ea-5f003b5410e7', 'tasks_modified_user', 'Users', 'users', 'id', 'Tasks', 'tasks', 'modified_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('d57915a5-4dfe-09cf-9bc9-5f003b9aac77', 'bug_notes', 'Bugs', 'bugs', 'id', 'Notes', 'notes', 'parent_id', NULL, NULL, NULL, 'one-to-many', 'parent_type', 'Bugs', 0, 0),
('d5aef1bd-cf9f-9e08-2806-5f003b6bb928', 'stic_contacts_relationships_modified_user', 'Users', 'users', 'id', 'stic_Contacts_Relationships', 'stic_contacts_relationships', 'modified_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('d5e1fbd6-8c73-a770-c953-5f003be866e0', 'aor_scheduled_reports_modified_user', 'Users', 'users', 'id', 'AOR_Scheduled_Reports', 'aor_scheduled_reports', 'modified_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('d6657b20-de32-c36f-6a44-5f003bdc9f2e', 'bugs_release', 'Releases', 'releases', 'id', 'Bugs', 'bugs', 'found_in_release', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('d706d3cb-569c-e64e-6b3a-5f003b967109', 'stic_contacts_relationships_created_by', 'Users', 'users', 'id', 'stic_Contacts_Relationships', 'stic_contacts_relationships', 'created_by', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('d7121503-bcbb-6f0e-7247-5f003b073fb0', 'tasks_created_by', 'Users', 'users', 'id', 'Tasks', 'tasks', 'created_by', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('d72d801c-06e3-3951-184b-5f003b3ae177', 'aor_scheduled_reports_created_by', 'Users', 'users', 'id', 'AOR_Scheduled_Reports', 'aor_scheduled_reports', 'created_by', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('d78d6664-106a-e219-25ae-5f003bf813d7', 'bugs_fixed_in_release', 'Releases', 'releases', 'id', 'Bugs', 'bugs', 'fixed_in_release', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('d7e3b9c9-1daf-9e1d-ea27-5f003b9532b1', 'stic_contacts_relationships_assigned_user', 'Users', 'users', 'id', 'stic_Contacts_Relationships', 'stic_contacts_relationships', 'assigned_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('d805a4e1-1bf6-bc6e-7b4f-5f003bc8558a', 'tasks_assigned_user', 'Users', 'users', 'id', 'Tasks', 'tasks', 'assigned_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('d81be7b3-8584-6f34-d484-5f003bb701b4', 'securitygroups_aor_scheduled_reports', 'SecurityGroups', 'securitygroups', 'id', 'AOR_Scheduled_Reports', 'aor_scheduled_reports', 'id', 'securitygroups_records', 'securitygroup_id', 'record_id', 'many-to-many', 'module', 'AOR_Scheduled_Reports', 0, 0),
('d8b04c67-9592-e680-6c8b-5f003ba268bd', 'securitygroups_stic_contacts_relationships', 'SecurityGroups', 'securitygroups', 'id', 'stic_Contacts_Relationships', 'stic_contacts_relationships', 'id', 'securitygroups_records', 'securitygroup_id', 'record_id', 'many-to-many', 'module', 'stic_Contacts_Relationships', 0, 0),
('d8e57db3-d277-ae39-0fe1-5f003b6ffb2b', 'securitygroups_tasks', 'SecurityGroups', 'securitygroups', 'id', 'Tasks', 'tasks', 'id', 'securitygroups_records', 'securitygroup_id', 'record_id', 'many-to-many', 'module', 'Tasks', 0, 0),
('d9c18c14-6ddd-8793-7437-5f003bdd5640', 'tasks_notes', 'Tasks', 'tasks', 'id', 'Notes', 'notes', 'parent_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('db4dd16a-a009-792f-9e1e-5f003b1c4886', 'aos_contracts_modified_user', 'Users', 'users', 'id', 'AOS_Contracts', 'aos_contracts', 'modified_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('db906114-840f-05ee-a8ee-5f003bf08dd4', 'stic_events_modified_user', 'Users', 'users', 'id', 'stic_Events', 'stic_events', 'modified_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('dd0df8f4-2a48-67d4-4eb1-5f003bddd593', 'stic_events_created_by', 'Users', 'users', 'id', 'stic_Events', 'stic_events', 'created_by', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('dd47eefb-affd-4bf7-3715-5f003b3c76e5', 'aos_contracts_created_by', 'Users', 'users', 'id', 'AOS_Contracts', 'aos_contracts', 'created_by', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('de028e2b-613a-a70b-9038-5f003b012b9d', 'stic_events_assigned_user', 'Users', 'users', 'id', 'stic_Events', 'stic_events', 'assigned_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('de210d2e-3075-2add-7df3-5f003bbef0e0', 'projects_notes', 'Project', 'project', 'id', 'Notes', 'notes', 'parent_id', NULL, NULL, NULL, 'one-to-many', 'parent_type', 'Project', 0, 0),
('de72cc0a-064f-2daa-d741-5f003b840c81', 'aos_contracts_assigned_user', 'Users', 'users', 'id', 'AOS_Contracts', 'aos_contracts', 'assigned_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('ded05596-5dba-660d-2bfe-5f003b6b3dc1', 'securitygroups_stic_events', 'SecurityGroups', 'securitygroups', 'id', 'stic_Events', 'stic_events', 'id', 'securitygroups_records', 'securitygroup_id', 'record_id', 'many-to-many', 'module', 'stic_Events', 0, 0),
('dfdec2e7-2a89-ecae-f715-5f003b375a53', 'alerts_modified_user', 'Users', 'users', 'id', 'Alerts', 'alerts', 'modified_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('e0df91a7-5840-bba8-ec83-5f003b1b6abe', 'stic_payment_commitments_modified_user', 'Users', 'users', 'id', 'stic_Payment_Commitments', 'stic_payment_commitments', 'modified_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('e10b52de-f503-0f7f-9937-5f003bb12577', 'alerts_created_by', 'Users', 'users', 'id', 'Alerts', 'alerts', 'created_by', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('e209f2cd-2d3f-0e0c-3111-5f003b510eb4', 'alerts_assigned_user', 'Users', 'users', 'id', 'Alerts', 'alerts', 'assigned_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('e22cdc08-da7b-b608-f16f-5f003bdbbb44', 'stic_payment_commitments_created_by', 'Users', 'users', 'id', 'stic_Payment_Commitments', 'stic_payment_commitments', 'created_by', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('e27d1f66-1f2a-51a7-7f67-5f003bb13e82', 'securitygroups_aos_contracts', 'SecurityGroups', 'securitygroups', 'id', 'AOS_Contracts', 'aos_contracts', 'id', 'securitygroups_records', 'securitygroup_id', 'record_id', 'many-to-many', 'module', 'AOS_Contracts', 0, 0),
('e3177544-6e8a-a071-7a52-5f003b6400e6', 'stic_payment_commitments_assigned_user', 'Users', 'users', 'id', 'stic_Payment_Commitments', 'stic_payment_commitments', 'assigned_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('e3614ac6-fafa-07e7-ece8-5f003bebdae4', 'aos_contracts_tasks', 'AOS_Contracts', 'aos_contracts', 'id', 'Tasks', 'tasks', 'parent_id', NULL, NULL, NULL, 'one-to-many', 'parent_type', 'AOS_Contracts', 0, 0),
('e3d88565-b316-7e2a-39d9-5f003b40ffb7', 'securitygroups_stic_payment_commitments', 'SecurityGroups', 'securitygroups', 'id', 'stic_Payment_Commitments', 'stic_payment_commitments', 'id', 'securitygroups_records', 'securitygroup_id', 'record_id', 'many-to-many', 'module', 'stic_Payment_Commitments', 0, 0),
('e45632f3-c81e-0c7b-50d2-5f003b8507f1', 'aos_contracts_notes', 'AOS_Contracts', 'aos_contracts', 'id', 'Notes', 'notes', 'parent_id', NULL, NULL, NULL, 'one-to-many', 'parent_type', 'AOS_Contracts', 0, 0),
('e5302114-31f8-9a92-8e4f-5f003b790814', 'aos_contracts_meetings', 'AOS_Contracts', 'aos_contracts', 'id', 'Meetings', 'meetings', 'parent_id', NULL, NULL, NULL, 'one-to-many', 'parent_type', 'AOS_Contracts', 0, 0),
('e58b2238-cee5-adae-8a17-5f003beefb9c', 'documents_modified_user', 'Users', 'users', 'id', 'Documents', 'documents', 'modified_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('e613f229-bdd2-16ea-99bb-5f003b5ac464', 'aos_contracts_calls', 'AOS_Contracts', 'aos_contracts', 'id', 'Calls', 'calls', 'parent_id', NULL, NULL, NULL, 'one-to-many', 'parent_type', 'AOS_Contracts', 0, 0),
('e69fd27f-a492-8c2b-9157-5f003b66031e', 'stic_payments_modified_user', 'Users', 'users', 'id', 'stic_Payments', 'stic_payments', 'modified_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('e6d1e02c-0ad6-36d7-61a1-5f003beedc40', 'documents_created_by', 'Users', 'users', 'id', 'Documents', 'documents', 'created_by', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('e6f46b53-7c84-bc4e-244f-5f003bff6459', 'aos_contracts_aos_products_quotes', 'AOS_Contracts', 'aos_contracts', 'id', 'AOS_Products_Quotes', 'aos_products_quotes', 'parent_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('e74406e7-47e0-c3ac-5b23-5f003bd0df09', 'saved_search_assigned_user', 'Users', 'users', 'id', 'SavedSearch', 'saved_search', 'assigned_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('e7d08446-9fb4-15cb-e447-5f003b71cb7a', 'documents_assigned_user', 'Users', 'users', 'id', 'Documents', 'documents', 'assigned_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('e7d8500e-ccee-fe50-ab75-5f003b4fba95', 'aos_contracts_aos_line_item_groups', 'AOS_Contracts', 'aos_contracts', 'id', 'AOS_Line_Item_Groups', 'aos_line_item_groups', 'parent_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('e7da589e-0770-8a72-1218-5f003bf20a79', 'stic_payments_created_by', 'Users', 'users', 'id', 'stic_Payments', 'stic_payments', 'created_by', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('e8c47b41-aac5-597b-b53f-5f003b8c8823', 'stic_payments_assigned_user', 'Users', 'users', 'id', 'stic_Payments', 'stic_payments', 'assigned_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('e99c77f0-e5c3-1563-a865-5f003b4c9589', 'securitygroups_stic_payments', 'SecurityGroups', 'securitygroups', 'id', 'stic_Payments', 'stic_payments', 'id', 'securitygroups_records', 'securitygroup_id', 'record_id', 'many-to-many', 'module', 'stic_Payments', 0, 0),
('ea2441ab-ad09-21a6-de6f-5f003b6c7b37', 'aos_invoices_modified_user', 'Users', 'users', 'id', 'AOS_Invoices', 'aos_invoices', 'modified_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('eacb0ac8-47c2-778e-71d7-5f003bec12fd', 'projects_tasks', 'Project', 'project', 'id', 'Tasks', 'tasks', 'parent_id', NULL, NULL, NULL, 'one-to-many', 'parent_type', 'Project', 0, 0),
('eb9003b7-c973-af74-78e1-5f003bb132db', 'aos_invoices_created_by', 'Users', 'users', 'id', 'AOS_Invoices', 'aos_invoices', 'created_by', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('ebe1d06e-59f7-3598-11f1-5f003b34cb96', 'stic_registrations_modified_user', 'Users', 'users', 'id', 'stic_Registrations', 'stic_registrations', 'modified_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('ec5fc8d6-bfeb-78b7-3619-5f003b2fee64', 'aos_invoices_assigned_user', 'Users', 'users', 'id', 'AOS_Invoices', 'aos_invoices', 'assigned_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('ed2e592b-ac14-f155-a4bd-5f003b2da809', 'securitygroups_aos_invoices', 'SecurityGroups', 'securitygroups', 'id', 'AOS_Invoices', 'aos_invoices', 'id', 'securitygroups_records', 'securitygroup_id', 'record_id', 'many-to-many', 'module', 'AOS_Invoices', 0, 0),
('eda7e6d3-c5ae-b005-5b93-5f003b1b7254', 'stic_registrations_created_by', 'Users', 'users', 'id', 'stic_Registrations', 'stic_registrations', 'created_by', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('ee0b120e-6099-16f8-683a-5f003be7e2b5', 'aos_invoices_aos_product_quotes', 'AOS_Invoices', 'aos_invoices', 'id', 'AOS_Products_Quotes', 'aos_products_quotes', 'parent_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('eec1c223-3e4e-6f4e-2551-5f003bad589e', 'stic_registrations_assigned_user', 'Users', 'users', 'id', 'stic_Registrations', 'stic_registrations', 'assigned_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('eeeba66c-5d9e-5d34-242e-5f003baa97fa', 'aos_invoices_aos_line_item_groups', 'AOS_Invoices', 'aos_invoices', 'id', 'AOS_Line_Item_Groups', 'aos_line_item_groups', 'parent_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('ef9ccff7-12ac-3338-e8bd-5f003b261d5b', 'securitygroups_stic_registrations', 'SecurityGroups', 'securitygroups', 'id', 'stic_Registrations', 'stic_registrations', 'id', 'securitygroups_records', 'securitygroup_id', 'record_id', 'many-to-many', 'module', 'stic_Registrations', 0, 0),
('f063ee3d-1b24-9419-a3a5-5f003bd3ec4c', 'securitygroups_documents', 'SecurityGroups', 'securitygroups', 'id', 'Documents', 'documents', 'id', 'securitygroups_records', 'securitygroup_id', 'record_id', 'many-to-many', 'module', 'Documents', 0, 0),
('f1073aaf-9960-a528-4c2c-5f003b84208e', 'aos_pdf_templates_modified_user', 'Users', 'users', 'id', 'AOS_PDF_Templates', 'aos_pdf_templates', 'modified_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('f1a9789d-02f1-31fd-561f-5f003b0519f1', 'document_revisions', 'Documents', 'documents', 'id', 'DocumentRevisions', 'document_revisions', 'document_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('f1cc7f65-49bd-6efd-2a5b-5f003bf90fe7', 'stic_remittances_modified_user', 'Users', 'users', 'id', 'stic_Remittances', 'stic_remittances', 'modified_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('f2e0bcfb-d9bf-5faf-bd9e-5f003b3457ee', 'stic_remittances_created_by', 'Users', 'users', 'id', 'stic_Remittances', 'stic_remittances', 'created_by', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('f33977c3-a123-235a-492b-5f003bcac4e1', 'aos_pdf_templates_created_by', 'Users', 'users', 'id', 'AOS_PDF_Templates', 'aos_pdf_templates', 'created_by', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('f3a4527a-018b-2d36-7a09-5f003b5913e9', 'revisions_created_by', 'Users', 'users', 'id', 'DocumentRevisions', 'document_revisions', 'created_by', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('f3cc58d6-3d73-e9c6-fc0f-5f003be0c24f', 'stic_remittances_assigned_user', 'Users', 'users', 'id', 'stic_Remittances', 'stic_remittances', 'assigned_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('f41ffbbf-2ff8-3418-0b8c-5f003ba28b28', 'aos_pdf_templates_assigned_user', 'Users', 'users', 'id', 'AOS_PDF_Templates', 'aos_pdf_templates', 'assigned_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('f79d02bd-e589-c98b-1e08-5f003b36e10e', 'projects_meetings', 'Project', 'project', 'id', 'Meetings', 'meetings', 'parent_id', NULL, NULL, NULL, 'one-to-many', 'parent_type', 'Project', 0, 0),
('f8900f98-968b-5ebb-7b1e-5f003ba1cf75', 'securitygroups_aos_pdf_templates', 'SecurityGroups', 'securitygroups', 'id', 'AOS_PDF_Templates', 'aos_pdf_templates', 'id', 'securitygroups_records', 'securitygroup_id', 'record_id', 'many-to-many', 'module', 'AOS_PDF_Templates', 0, 0),
('fad30d80-2bfa-3f09-8079-5f003bcf338b', 'aos_product_categories_created_by', 'Users', 'users', 'id', 'AOS_Product_Categories', 'aos_product_categories', 'created_by', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `releases`
--

CREATE TABLE IF NOT EXISTS `releases` (
  `id` char(36) NOT NULL,
  `deleted` tinyint(1) DEFAULT NULL,
  `date_entered` datetime DEFAULT NULL,
  `date_modified` datetime DEFAULT NULL,
  `modified_user_id` char(36) DEFAULT NULL,
  `created_by` char(36) DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL,
  `list_order` int(4) DEFAULT NULL,
  `status` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `reminders`
--

CREATE TABLE IF NOT EXISTS `reminders` (
  `id` char(36) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `date_entered` datetime DEFAULT NULL,
  `date_modified` datetime DEFAULT NULL,
  `modified_user_id` char(36) DEFAULT NULL,
  `created_by` char(36) DEFAULT NULL,
  `description` text,
  `deleted` tinyint(1) DEFAULT '0',
  `assigned_user_id` char(36) DEFAULT NULL,
  `popup` tinyint(1) DEFAULT NULL,
  `email` tinyint(1) DEFAULT NULL,
  `email_sent` tinyint(1) DEFAULT NULL,
  `timer_popup` varchar(32) DEFAULT NULL,
  `timer_email` varchar(32) DEFAULT NULL,
  `related_event_module` varchar(32) DEFAULT NULL,
  `related_event_module_id` char(36) NOT NULL,
  `date_willexecute` int(60) DEFAULT '-1',
  `popup_viewed` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `reminders_invitees`
--

CREATE TABLE IF NOT EXISTS `reminders_invitees` (
  `id` char(36) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `date_entered` datetime DEFAULT NULL,
  `date_modified` datetime DEFAULT NULL,
  `modified_user_id` char(36) DEFAULT NULL,
  `created_by` char(36) DEFAULT NULL,
  `description` text,
  `deleted` tinyint(1) DEFAULT '0',
  `assigned_user_id` char(36) DEFAULT NULL,
  `reminder_id` char(36) NOT NULL,
  `related_invitee_module` varchar(32) DEFAULT NULL,
  `related_invitee_module_id` char(36) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE IF NOT EXISTS `roles` (
  `id` char(36) NOT NULL,
  `date_entered` datetime DEFAULT NULL,
  `date_modified` datetime DEFAULT NULL,
  `modified_user_id` char(36) DEFAULT NULL,
  `created_by` char(36) DEFAULT NULL,
  `name` varchar(150) DEFAULT NULL,
  `description` text,
  `modules` text,
  `deleted` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `roles_modules`
--

CREATE TABLE IF NOT EXISTS `roles_modules` (
  `id` varchar(36) NOT NULL,
  `role_id` varchar(36) DEFAULT NULL,
  `module_id` varchar(36) DEFAULT NULL,
  `allow` tinyint(1) DEFAULT '0',
  `date_modified` datetime DEFAULT NULL,
  `deleted` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `roles_users`
--

CREATE TABLE IF NOT EXISTS `roles_users` (
  `id` varchar(36) NOT NULL,
  `role_id` varchar(36) DEFAULT NULL,
  `user_id` varchar(36) DEFAULT NULL,
  `date_modified` datetime DEFAULT NULL,
  `deleted` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `saved_search`
--

CREATE TABLE IF NOT EXISTS `saved_search` (
  `id` char(36) NOT NULL,
  `name` varchar(150) DEFAULT NULL,
  `search_module` varchar(150) DEFAULT NULL,
  `deleted` tinyint(1) DEFAULT NULL,
  `date_entered` datetime DEFAULT NULL,
  `date_modified` datetime DEFAULT NULL,
  `assigned_user_id` char(36) DEFAULT NULL,
  `contents` text,
  `description` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `schedulers`
--

CREATE TABLE IF NOT EXISTS `schedulers` (
  `id` varchar(36) NOT NULL,
  `deleted` tinyint(1) DEFAULT '0',
  `date_entered` datetime DEFAULT NULL,
  `date_modified` datetime DEFAULT NULL,
  `created_by` char(36) DEFAULT NULL,
  `modified_user_id` char(36) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `job` varchar(255) DEFAULT NULL,
  `date_time_start` datetime DEFAULT NULL,
  `date_time_end` datetime DEFAULT NULL,
  `job_interval` varchar(100) DEFAULT NULL,
  `time_from` time DEFAULT NULL,
  `time_to` time DEFAULT NULL,
  `last_run` datetime DEFAULT NULL,
  `status` varchar(100) DEFAULT NULL,
  `catch_up` tinyint(1) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `schedulers`
--

INSERT INTO `schedulers` (`id`, `deleted`, `date_entered`, `date_modified`, `created_by`, `modified_user_id`, `name`, `job`, `date_time_start`, `date_time_end`, `job_interval`, `time_from`, `time_to`, `last_run`, `status`, `catch_up`) VALUES
('1f4425d5-e576-4785-bb9a-5b3c3b0784b2', 0, '2020-07-04 10:16:16', '2020-07-04 10:16:16', '1', '1', 'SinergiaCRM - Update people age', 'function::calculateContactsAge', '2020-07-04 10:16:16', NULL, '0::1::*::*::*', NULL, NULL, NULL, 'Active', 1),
('2a01deb0-04ed-5cd3-86b6-51a3235544fc', 0, '2020-07-04 10:16:16', '2020-07-04 10:16:16', '1', '1', 'SinergiaCRM - Generate recurring payments', 'function::createCurrentMonthRecurringPayments', '2020-07-04 10:16:16', NULL, '30::0::1::*::*', NULL, NULL, NULL, 'Active', 1),
('3faa2520-9b74-2ca3-98cd-5bd6de539b9c', 0, '2020-07-04 10:16:16', '2020-07-04 10:16:16', '1', '1', 'SinergiaCRM - Generate attendances at multisession events', 'function::createAttendances', '2020-07-04 10:16:16', NULL, '05::0::*::*::*', NULL, NULL, NULL, 'Active', 1),
('511fda77-4b76-4b8e-b44f-ff78489b1e5f', 0, '2020-07-04 10:16:16', '2020-07-04 10:16:16', '1', '1', 'SinergiaCRM - Reset optimal settings', 'function::sticCleanConfig', '2020-07-04 10:16:16', NULL, '0::18::*::*::5', NULL, NULL, NULL, 'Active', 1),
('555afabf-a050-da1b-4ab3-5e830d5282f9', 0, '2020-07-04 08:15:46', '2020-07-04 08:15:46', '1', '1', 'Process Workflow Tasks', 'function::processAOW_Workflow', '2015-01-01 06:15:01', NULL, '*::*::*::*::*', NULL, NULL, NULL, 'Active', 1),
('5ad4580e-22d4-fb83-423e-5e830d68041e', 0, '2020-07-04 08:15:46', '2020-07-04 08:15:46', '1', '1', 'Run Report Generation Scheduled Tasks', 'function::aorRunScheduledReports', '2015-01-01 13:45:01', NULL, '*::*::*::*::*', NULL, NULL, NULL, 'Active', 1),
('600e94d1-6aa3-d743-9781-5e830de19e7e', 0, '2020-07-04 08:15:46', '2020-07-04 08:15:46', '1', '1', 'Prune Tracker Tables', 'function::trimTracker', '2015-01-01 19:15:01', NULL, '0::2::1::*::*', NULL, NULL, NULL, 'Active', 1),
('697bb2be-ebf2-5fa4-8e96-5e830de01228', 0, '2020-07-04 08:15:46', '2020-07-04 08:15:46', '1', '1', 'Check Inbound Mailboxes', 'function::pollMonitoredInboxesAOP', '2015-01-01 06:45:01', NULL, '*::*::*::*::*', NULL, NULL, NULL, 'Active', 0),
('6e4062f8-b4eb-9629-aca5-5e830d17031b', 0, '2020-07-04 08:15:46', '2020-07-04 08:15:46', '1', '1', 'Run Nightly Process Bounced Campaign Emails', 'function::pollMonitoredInboxesForBouncedCampaignEmails', '2015-01-01 12:45:01', NULL, '0::2-6::*::*::*', NULL, NULL, NULL, 'Active', 1),
('72ed826e-bd84-758a-d742-5e830d2ce892', 0, '2020-07-04 08:15:46', '2020-07-04 08:15:46', '1', '1', 'Run Nightly Mass Email Campaigns', 'function::runMassEmailCampaign', '2015-01-01 11:30:01', NULL, '0::2-6::*::*::*', NULL, NULL, NULL, 'Active', 1),
('7386c4b1-bcc2-4f6f-be88-7e2a2e5778b5', 0, '2020-07-04 10:16:16', '2020-07-04 10:16:16', '1', '1', 'SinergiaCRM - General data validation', 'function::validationActions', '2020-07-04 10:16:16', NULL, '30::1::*::*::0', NULL, NULL, NULL, 'Active', 1),
('78b6d594-78ab-c7c0-84cc-5e830d70f289', 0, '2020-07-04 08:15:46', '2020-07-04 08:15:46', '1', '1', 'Prune Database on 1st of Month', 'function::pruneDatabase', '2015-01-01 08:15:01', NULL, '0::4::1::*::*', NULL, NULL, NULL, 'Inactive', 0),
('7e8a98ec-1d10-47d9-b5a6-5e830dbac250', 0, '2020-07-04 08:15:46', '2020-07-04 08:15:46', '1', '1', 'Perform Lucene Index', 'function::aodIndexUnindexed', '2015-01-01 06:15:01', NULL, '0::0::*::*::*', NULL, NULL, NULL, 'Active', 0),
('845b6709-63d2-28ab-d999-5e830d6670d2', 0, '2020-07-04 08:15:46', '2020-07-04 08:15:46', '1', '1', 'Optimise AOD Index', 'function::aodOptimiseIndex', '2015-01-01 11:45:01', NULL, '0::*/3::*::*::*', NULL, NULL, NULL, 'Active', 0),
('8942931c-67cf-f446-509b-5e830dbe1a6b', 0, '2020-07-04 08:15:46', '2020-07-04 08:15:46', '1', '1', 'Run Email Reminder Notifications', 'function::sendEmailReminders', '2015-01-01 13:30:01', NULL, '*::*::*::*::*', NULL, NULL, NULL, 'Active', 0),
('8eb78e01-492a-9dbd-eddc-5e830d791c43', 0, '2020-07-04 08:15:46', '2020-07-04 08:15:46', '1', '1', 'Clean Jobs Queue', 'function::cleanJobQueue', '2015-01-01 11:15:01', NULL, '0::5::*::*::*', NULL, NULL, NULL, 'Active', 0),
('93890a43-0d74-f862-58e9-5e830d0964a0', 0, '2020-07-04 08:15:46', '2020-07-04 08:15:46', '1', '1', 'Removal of documents from filesystem', 'function::removeDocumentsFromFS', '2015-01-01 06:15:01', NULL, '0::3::1::*::*', NULL, NULL, NULL, 'Active', 0),
('988ee5ee-336a-4bcd-c698-5e830de4b2d1', 0, '2020-07-04 08:15:46', '2020-07-04 08:15:46', '1', '1', 'Prune SuiteCRM Feed Tables', 'function::trimSugarFeeds', '2015-01-01 12:45:01', NULL, '0::2::1::*::*', NULL, NULL, NULL, 'Active', 1),
('9d7da6e1-ce31-6c19-0ec9-5e830d2d8240', 0, '2020-07-04 08:15:46', '2020-07-04 08:15:46', '1', '1', 'Google Calendar Sync', 'function::syncGoogleCalendar', '2015-01-01 12:30:01', NULL, '*/15::*::*::*::*', NULL, NULL, NULL, 'Active', 0),
('a9bebf7f-8896-46dd-8d06-77e2b5256c83', 0, '2020-07-04 10:16:16', '2020-07-04 10:16:16', '1', '1', 'SinergiaCRM - Economic data validation', 'function::validationActions', '2020-07-04 10:16:16', NULL, '30::1::*::*::6', NULL, NULL, NULL, 'Active', 1),
('b05bde8a-1309-4789-993b-bf85be389f07', 0, '2020-07-04 10:16:16', '2020-07-04 10:16:16', '1', '1', 'SinergiaCRM - Relationship types validation', 'function::validationActions', '2020-07-04 10:16:16', NULL, '05::0::*::*::*', NULL, NULL, NULL, 'Active', 1),
('e3a6b5f4-9d8c-ecc1-a8af-51a71894802d', 0, '2020-07-04 10:16:16', '2020-07-04 10:16:16', '1', '1', 'SinergiaCRM - Opportunities reminder', 'function::opportunitiesReminder', '2020-07-04 10:16:16', NULL, '0::6::*::*::*', NULL, NULL, NULL, 'Active', 1);

-- --------------------------------------------------------

--
-- Table structure for table `securitygroups`
--

CREATE TABLE IF NOT EXISTS `securitygroups` (
  `id` char(36) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `date_entered` datetime DEFAULT NULL,
  `date_modified` datetime DEFAULT NULL,
  `modified_user_id` char(36) DEFAULT NULL,
  `created_by` char(36) DEFAULT NULL,
  `description` text,
  `deleted` tinyint(1) DEFAULT '0',
  `assigned_user_id` char(36) DEFAULT NULL,
  `noninheritable` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `securitygroups_acl_roles`
--

CREATE TABLE IF NOT EXISTS `securitygroups_acl_roles` (
  `id` char(36) NOT NULL,
  `securitygroup_id` char(36) DEFAULT NULL,
  `role_id` char(36) DEFAULT NULL,
  `date_modified` datetime DEFAULT NULL,
  `deleted` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `securitygroups_audit`
--

CREATE TABLE IF NOT EXISTS `securitygroups_audit` (
  `id` char(36) NOT NULL,
  `parent_id` char(36) NOT NULL,
  `date_created` datetime DEFAULT NULL,
  `created_by` varchar(36) DEFAULT NULL,
  `field_name` varchar(100) DEFAULT NULL,
  `data_type` varchar(100) DEFAULT NULL,
  `before_value_string` varchar(255) DEFAULT NULL,
  `after_value_string` varchar(255) DEFAULT NULL,
  `before_value_text` text,
  `after_value_text` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `securitygroups_default`
--

CREATE TABLE IF NOT EXISTS `securitygroups_default` (
  `id` char(36) NOT NULL,
  `securitygroup_id` char(36) DEFAULT NULL,
  `module` varchar(50) DEFAULT NULL,
  `date_modified` datetime DEFAULT NULL,
  `deleted` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `securitygroups_records`
--

CREATE TABLE IF NOT EXISTS `securitygroups_records` (
  `id` char(36) NOT NULL,
  `securitygroup_id` char(36) DEFAULT NULL,
  `record_id` char(36) DEFAULT NULL,
  `module` char(36) DEFAULT NULL,
  `date_modified` datetime DEFAULT NULL,
  `modified_user_id` char(36) DEFAULT NULL,
  `created_by` char(36) DEFAULT NULL,
  `deleted` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `securitygroups_users`
--

CREATE TABLE IF NOT EXISTS `securitygroups_users` (
  `id` varchar(36) NOT NULL,
  `date_modified` datetime DEFAULT NULL,
  `deleted` tinyint(1) DEFAULT '0',
  `securitygroup_id` varchar(36) DEFAULT NULL,
  `user_id` varchar(36) DEFAULT NULL,
  `primary_group` tinyint(1) DEFAULT NULL,
  `noninheritable` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `spots`
--

CREATE TABLE IF NOT EXISTS `spots` (
  `id` char(36) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `date_entered` datetime DEFAULT NULL,
  `date_modified` datetime DEFAULT NULL,
  `modified_user_id` char(36) DEFAULT NULL,
  `created_by` char(36) DEFAULT NULL,
  `description` text,
  `deleted` tinyint(1) DEFAULT '0',
  `assigned_user_id` char(36) DEFAULT NULL,
  `config` longtext,
  `type` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `stic_accounts_relationships`
--

CREATE TABLE IF NOT EXISTS `stic_accounts_relationships` (
  `id` char(36) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `date_entered` datetime DEFAULT NULL,
  `date_modified` datetime DEFAULT NULL,
  `modified_user_id` char(36) DEFAULT NULL,
  `created_by` char(36) DEFAULT NULL,
  `description` text,
  `deleted` tinyint(1) DEFAULT '0',
  `assigned_user_id` char(36) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `relationship_type` varchar(100) DEFAULT NULL,
  `role` varchar(100) DEFAULT NULL,
  `end_reason` varchar(100) DEFAULT NULL,
  `other_end_reasons` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `stic_accounts_relationships_accounts_c`
--

CREATE TABLE IF NOT EXISTS `stic_accounts_relationships_accounts_c` (
  `id` varchar(36) NOT NULL,
  `date_modified` datetime DEFAULT NULL,
  `deleted` tinyint(1) DEFAULT '0',
  `stic_accounts_relationships_accountsaccounts_ida` varchar(36) DEFAULT NULL,
  `stic_accoub36donships_idb` varchar(36) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `stic_accounts_relationships_audit`
--

CREATE TABLE IF NOT EXISTS `stic_accounts_relationships_audit` (
  `id` char(36) NOT NULL,
  `parent_id` char(36) NOT NULL,
  `date_created` datetime DEFAULT NULL,
  `created_by` varchar(36) DEFAULT NULL,
  `field_name` varchar(100) DEFAULT NULL,
  `data_type` varchar(100) DEFAULT NULL,
  `before_value_string` varchar(255) DEFAULT NULL,
  `after_value_string` varchar(255) DEFAULT NULL,
  `before_value_text` text,
  `after_value_text` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `stic_accounts_relationships_project_c`
--

CREATE TABLE IF NOT EXISTS `stic_accounts_relationships_project_c` (
  `id` varchar(36) NOT NULL,
  `date_modified` datetime DEFAULT NULL,
  `deleted` tinyint(1) DEFAULT '0',
  `stic_accounts_relationships_projectproject_ida` varchar(36) DEFAULT NULL,
  `stic_accou2675onships_idb` varchar(36) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `stic_attendances`
--

CREATE TABLE IF NOT EXISTS `stic_attendances` (
  `id` char(36) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `date_entered` datetime DEFAULT NULL,
  `date_modified` datetime DEFAULT NULL,
  `modified_user_id` char(36) DEFAULT NULL,
  `created_by` char(36) DEFAULT NULL,
  `description` text,
  `deleted` tinyint(1) DEFAULT '0',
  `assigned_user_id` char(36) DEFAULT NULL,
  `start_date` datetime DEFAULT NULL,
  `duration` decimal(10,0) DEFAULT NULL,
  `status` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `stic_attendances_audit`
--

CREATE TABLE IF NOT EXISTS `stic_attendances_audit` (
  `id` char(36) NOT NULL,
  `parent_id` char(36) NOT NULL,
  `date_created` datetime DEFAULT NULL,
  `created_by` varchar(36) DEFAULT NULL,
  `field_name` varchar(100) DEFAULT NULL,
  `data_type` varchar(100) DEFAULT NULL,
  `before_value_string` varchar(255) DEFAULT NULL,
  `after_value_string` varchar(255) DEFAULT NULL,
  `before_value_text` text,
  `after_value_text` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `stic_attendances_stic_registrations_c`
--

CREATE TABLE IF NOT EXISTS `stic_attendances_stic_registrations_c` (
  `id` varchar(36) NOT NULL,
  `date_modified` datetime DEFAULT NULL,
  `deleted` tinyint(1) DEFAULT '0',
  `stic_attendances_stic_registrationsstic_registrations_ida` varchar(36) DEFAULT NULL,
  `stic_attendances_stic_registrationsstic_attendances_idb` varchar(36) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `stic_attendances_stic_sessions_c`
--

CREATE TABLE IF NOT EXISTS `stic_attendances_stic_sessions_c` (
  `id` varchar(36) NOT NULL,
  `date_modified` datetime DEFAULT NULL,
  `deleted` tinyint(1) DEFAULT '0',
  `stic_attendances_stic_sessionsstic_sessions_ida` varchar(36) DEFAULT NULL,
  `stic_attendances_stic_sessionsstic_attendances_idb` varchar(36) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `stic_contacts_relationships`
--

CREATE TABLE IF NOT EXISTS `stic_contacts_relationships` (
  `id` char(36) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `date_entered` datetime DEFAULT NULL,
  `date_modified` datetime DEFAULT NULL,
  `modified_user_id` char(36) DEFAULT NULL,
  `created_by` char(36) DEFAULT NULL,
  `description` text,
  `deleted` tinyint(1) DEFAULT '0',
  `assigned_user_id` char(36) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `relationship_type` varchar(100) DEFAULT NULL,
  `end_reason` varchar(100) DEFAULT NULL,
  `other_end_reasons` varchar(255) DEFAULT NULL,
  `role` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `stic_contacts_relationships_audit`
--

CREATE TABLE IF NOT EXISTS `stic_contacts_relationships_audit` (
  `id` char(36) NOT NULL,
  `parent_id` char(36) NOT NULL,
  `date_created` datetime DEFAULT NULL,
  `created_by` varchar(36) DEFAULT NULL,
  `field_name` varchar(100) DEFAULT NULL,
  `data_type` varchar(100) DEFAULT NULL,
  `before_value_string` varchar(255) DEFAULT NULL,
  `after_value_string` varchar(255) DEFAULT NULL,
  `before_value_text` text,
  `after_value_text` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `stic_contacts_relationships_contacts_c`
--

CREATE TABLE IF NOT EXISTS `stic_contacts_relationships_contacts_c` (
  `id` varchar(36) NOT NULL,
  `date_modified` datetime DEFAULT NULL,
  `deleted` tinyint(1) DEFAULT '0',
  `stic_contacts_relationships_contactscontacts_ida` varchar(36) DEFAULT NULL,
  `stic_contae394onships_idb` varchar(36) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `stic_contacts_relationships_project_c`
--

CREATE TABLE IF NOT EXISTS `stic_contacts_relationships_project_c` (
  `id` varchar(36) NOT NULL,
  `date_modified` datetime DEFAULT NULL,
  `deleted` tinyint(1) DEFAULT '0',
  `stic_contacts_relationships_projectproject_ida` varchar(36) DEFAULT NULL,
  `stic_conta0d5aonships_idb` varchar(36) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `stic_events`
--

CREATE TABLE IF NOT EXISTS `stic_events` (
  `id` char(36) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `date_entered` datetime DEFAULT NULL,
  `date_modified` datetime DEFAULT NULL,
  `modified_user_id` char(36) DEFAULT NULL,
  `created_by` char(36) DEFAULT NULL,
  `description` text,
  `deleted` tinyint(1) DEFAULT '0',
  `assigned_user_id` char(36) DEFAULT NULL,
  `type` varchar(100) DEFAULT NULL,
  `status` varchar(100) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `timetable` varchar(255) DEFAULT NULL,
  `discard_reason` varchar(100) DEFAULT NULL,
  `max_attendees` int(255) DEFAULT '0',
  `status_not_invited` int(255) DEFAULT '0',
  `status_invited` int(255) DEFAULT '0',
  `status_confirmed` int(255) DEFAULT '0',
  `status_rejected` int(255) DEFAULT '0',
  `status_maybe` int(255) DEFAULT '0',
  `status_didnt_take_part` int(255) DEFAULT '0',
  `status_took_part` int(255) DEFAULT '0',
  `status_drop_out` int(255) DEFAULT '0',
  `budget` decimal(26,2) DEFAULT '0.00',
  `expected_cost` decimal(26,2) DEFAULT '0.00',
  `actual_cost` decimal(26,2) DEFAULT '0.00',
  `expected_income` decimal(26,2) DEFAULT '0.00',
  `actual_income` int(255) DEFAULT '0',
  `price` decimal(26,2) DEFAULT '0.00',
  `attendees` int(255) DEFAULT '1',
  `total_hours` decimal(10,2) DEFAULT '0.00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `stic_events_audit`
--

CREATE TABLE IF NOT EXISTS `stic_events_audit` (
  `id` char(36) NOT NULL,
  `parent_id` char(36) NOT NULL,
  `date_created` datetime DEFAULT NULL,
  `created_by` varchar(36) DEFAULT NULL,
  `field_name` varchar(100) DEFAULT NULL,
  `data_type` varchar(100) DEFAULT NULL,
  `before_value_string` varchar(255) DEFAULT NULL,
  `after_value_string` varchar(255) DEFAULT NULL,
  `before_value_text` text,
  `after_value_text` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `stic_events_fp_event_locations_c`
--

CREATE TABLE IF NOT EXISTS `stic_events_fp_event_locations_c` (
  `id` varchar(36) NOT NULL,
  `date_modified` datetime DEFAULT NULL,
  `deleted` tinyint(1) DEFAULT '0',
  `stic_events_fp_event_locationsfp_event_locations_ida` varchar(36) DEFAULT NULL,
  `stic_events_fp_event_locationsstic_events_idb` varchar(36) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `stic_events_project_c`
--

CREATE TABLE IF NOT EXISTS `stic_events_project_c` (
  `id` varchar(36) NOT NULL,
  `date_modified` datetime DEFAULT NULL,
  `deleted` tinyint(1) DEFAULT '0',
  `stic_events_projectproject_ida` varchar(36) DEFAULT NULL,
  `stic_events_projectstic_events_idb` varchar(36) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `stic_payments`
--

CREATE TABLE IF NOT EXISTS `stic_payments` (
  `id` char(36) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `date_entered` datetime DEFAULT NULL,
  `date_modified` datetime DEFAULT NULL,
  `modified_user_id` char(36) DEFAULT NULL,
  `created_by` char(36) DEFAULT NULL,
  `description` text,
  `deleted` tinyint(1) DEFAULT '0',
  `assigned_user_id` char(36) DEFAULT NULL,
  `payment_type` varchar(100) DEFAULT NULL,
  `bank_account` varchar(255) DEFAULT NULL,
  `amount` decimal(26,2) DEFAULT NULL,
  `payment_method` varchar(100) DEFAULT NULL,
  `transaction_type` varchar(100) DEFAULT NULL,
  `mandate` varchar(255) DEFAULT NULL,
  `segmentation` varchar(100) DEFAULT NULL,
  `in_kind_description` varchar(255) DEFAULT NULL,
  `banking_concept` varchar(255) DEFAULT NULL,
  `status` varchar(100) DEFAULT NULL,
  `m182_excluded` tinyint(1) DEFAULT NULL,
  `sepa_rejected_reason` varchar(100) DEFAULT NULL,
  `rejection_date` date DEFAULT NULL,
  `c19_rejected_reason` varchar(100) DEFAULT NULL,
  `payment_date` date DEFAULT NULL,
  `transaction_code` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `stic_payments_accounts_c`
--

CREATE TABLE IF NOT EXISTS `stic_payments_accounts_c` (
  `id` varchar(36) NOT NULL,
  `date_modified` datetime DEFAULT NULL,
  `deleted` tinyint(1) DEFAULT '0',
  `stic_payments_accountsaccounts_ida` varchar(36) DEFAULT NULL,
  `stic_payments_accountsstic_payments_idb` varchar(36) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `stic_payments_audit`
--

CREATE TABLE IF NOT EXISTS `stic_payments_audit` (
  `id` char(36) NOT NULL,
  `parent_id` char(36) NOT NULL,
  `date_created` datetime DEFAULT NULL,
  `created_by` varchar(36) DEFAULT NULL,
  `field_name` varchar(100) DEFAULT NULL,
  `data_type` varchar(100) DEFAULT NULL,
  `before_value_string` varchar(255) DEFAULT NULL,
  `after_value_string` varchar(255) DEFAULT NULL,
  `before_value_text` text,
  `after_value_text` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `stic_payments_contacts_c`
--

CREATE TABLE IF NOT EXISTS `stic_payments_contacts_c` (
  `id` varchar(36) NOT NULL,
  `date_modified` datetime DEFAULT NULL,
  `deleted` tinyint(1) DEFAULT '0',
  `stic_payments_contactscontacts_ida` varchar(36) DEFAULT NULL,
  `stic_payments_contactsstic_payments_idb` varchar(36) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `stic_payments_stic_payment_commitments_c`
--

CREATE TABLE IF NOT EXISTS `stic_payments_stic_payment_commitments_c` (
  `id` varchar(36) NOT NULL,
  `date_modified` datetime DEFAULT NULL,
  `deleted` tinyint(1) DEFAULT '0',
  `stic_paymebfe2itments_ida` varchar(36) DEFAULT NULL,
  `stic_payments_stic_payment_commitmentsstic_payments_idb` varchar(36) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `stic_payments_stic_registrations_c`
--

CREATE TABLE IF NOT EXISTS `stic_payments_stic_registrations_c` (
  `id` varchar(36) NOT NULL,
  `date_modified` datetime DEFAULT NULL,
  `deleted` tinyint(1) DEFAULT '0',
  `stic_payments_stic_registrationsstic_payments_ida` varchar(36) DEFAULT NULL,
  `stic_payments_stic_registrationsstic_registrations_idb` varchar(36) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `stic_payments_stic_remittances_c`
--

CREATE TABLE IF NOT EXISTS `stic_payments_stic_remittances_c` (
  `id` varchar(36) NOT NULL,
  `date_modified` datetime DEFAULT NULL,
  `deleted` tinyint(1) DEFAULT '0',
  `stic_payments_stic_remittancesstic_remittances_ida` varchar(36) DEFAULT NULL,
  `stic_payments_stic_remittancesstic_payments_idb` varchar(36) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `stic_payment_commitments`
--

CREATE TABLE IF NOT EXISTS `stic_payment_commitments` (
  `id` char(36) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `date_entered` datetime DEFAULT NULL,
  `date_modified` datetime DEFAULT NULL,
  `modified_user_id` char(36) DEFAULT NULL,
  `created_by` char(36) DEFAULT NULL,
  `description` text,
  `deleted` tinyint(1) DEFAULT '0',
  `assigned_user_id` char(36) DEFAULT NULL,
  `payment_type` varchar(100) DEFAULT NULL,
  `bank_account` varchar(255) DEFAULT NULL,
  `channel` varchar(100) DEFAULT NULL,
  `periodicity` varchar(100) DEFAULT NULL,
  `amount` decimal(26,2) DEFAULT NULL,
  `first_payment_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `payment_method` varchar(100) DEFAULT NULL,
  `transaction_type` varchar(100) DEFAULT NULL,
  `signature_date` date DEFAULT NULL,
  `mandate` varchar(255) DEFAULT NULL,
  `annualized_fee` decimal(26,2) DEFAULT NULL,
  `segmentation` varchar(100) DEFAULT NULL,
  `in_kind_donation` varchar(255) DEFAULT NULL,
  `banking_concept` varchar(255) DEFAULT NULL,
  `destination` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `stic_payment_commitments_accounts_c`
--

CREATE TABLE IF NOT EXISTS `stic_payment_commitments_accounts_c` (
  `id` varchar(36) NOT NULL,
  `date_modified` datetime DEFAULT NULL,
  `deleted` tinyint(1) DEFAULT '0',
  `stic_payment_commitments_accountsaccounts_ida` varchar(36) DEFAULT NULL,
  `stic_payment_commitments_accountsstic_payment_commitments_idb` varchar(36) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `stic_payment_commitments_audit`
--

CREATE TABLE IF NOT EXISTS `stic_payment_commitments_audit` (
  `id` char(36) NOT NULL,
  `parent_id` char(36) NOT NULL,
  `date_created` datetime DEFAULT NULL,
  `created_by` varchar(36) DEFAULT NULL,
  `field_name` varchar(100) DEFAULT NULL,
  `data_type` varchar(100) DEFAULT NULL,
  `before_value_string` varchar(255) DEFAULT NULL,
  `after_value_string` varchar(255) DEFAULT NULL,
  `before_value_text` text,
  `after_value_text` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `stic_payment_commitments_campaigns_c`
--

CREATE TABLE IF NOT EXISTS `stic_payment_commitments_campaigns_c` (
  `id` varchar(36) NOT NULL,
  `date_modified` datetime DEFAULT NULL,
  `deleted` tinyint(1) DEFAULT '0',
  `stic_payment_commitments_campaignscampaigns_ida` varchar(36) DEFAULT NULL,
  `stic_payment_commitments_campaignsstic_payment_commitments_idb` varchar(36) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `stic_payment_commitments_contacts_c`
--

CREATE TABLE IF NOT EXISTS `stic_payment_commitments_contacts_c` (
  `id` varchar(36) NOT NULL,
  `date_modified` datetime DEFAULT NULL,
  `deleted` tinyint(1) DEFAULT '0',
  `stic_payment_commitments_contactscontacts_ida` varchar(36) DEFAULT NULL,
  `stic_payment_commitments_contactsstic_payment_commitments_idb` varchar(36) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `stic_payment_commitments_project_c`
--

CREATE TABLE IF NOT EXISTS `stic_payment_commitments_project_c` (
  `id` varchar(36) NOT NULL,
  `date_modified` datetime DEFAULT NULL,
  `deleted` tinyint(1) DEFAULT '0',
  `stic_payment_commitments_projectproject_ida` varchar(36) DEFAULT NULL,
  `stic_payment_commitments_projectstic_payment_commitments_idb` varchar(36) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `stic_registrations`
--

CREATE TABLE IF NOT EXISTS `stic_registrations` (
  `id` char(36) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `date_entered` datetime DEFAULT NULL,
  `date_modified` datetime DEFAULT NULL,
  `modified_user_id` char(36) DEFAULT NULL,
  `created_by` char(36) DEFAULT NULL,
  `description` text,
  `deleted` tinyint(1) DEFAULT '0',
  `assigned_user_id` char(36) DEFAULT NULL,
  `status` varchar(100) DEFAULT NULL,
  `registration_date` datetime DEFAULT NULL,
  `not_participating_reason` varchar(100) DEFAULT NULL,
  `rejection_reason` varchar(100) DEFAULT NULL,
  `participation_type` varchar(100) DEFAULT NULL,
  `special_needs` varchar(100) DEFAULT NULL,
  `special_needs_description` varchar(255) DEFAULT NULL,
  `attendance_percentage` decimal(10,0) DEFAULT NULL,
  `attended_hours` decimal(10,0) DEFAULT NULL,
  `attendees` int(255) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `stic_registrations_accounts_c`
--

CREATE TABLE IF NOT EXISTS `stic_registrations_accounts_c` (
  `id` varchar(36) NOT NULL,
  `date_modified` datetime DEFAULT NULL,
  `deleted` tinyint(1) DEFAULT '0',
  `stic_registrations_accountsaccounts_ida` varchar(36) DEFAULT NULL,
  `stic_registrations_accountsstic_registrations_idb` varchar(36) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `stic_registrations_audit`
--

CREATE TABLE IF NOT EXISTS `stic_registrations_audit` (
  `id` char(36) NOT NULL,
  `parent_id` char(36) NOT NULL,
  `date_created` datetime DEFAULT NULL,
  `created_by` varchar(36) DEFAULT NULL,
  `field_name` varchar(100) DEFAULT NULL,
  `data_type` varchar(100) DEFAULT NULL,
  `before_value_string` varchar(255) DEFAULT NULL,
  `after_value_string` varchar(255) DEFAULT NULL,
  `before_value_text` text,
  `after_value_text` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `stic_registrations_contacts_c`
--

CREATE TABLE IF NOT EXISTS `stic_registrations_contacts_c` (
  `id` varchar(36) NOT NULL,
  `date_modified` datetime DEFAULT NULL,
  `deleted` tinyint(1) DEFAULT '0',
  `stic_registrations_contactscontacts_ida` varchar(36) DEFAULT NULL,
  `stic_registrations_contactsstic_registrations_idb` varchar(36) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `stic_registrations_leads_c`
--

CREATE TABLE IF NOT EXISTS `stic_registrations_leads_c` (
  `id` varchar(36) NOT NULL,
  `date_modified` datetime DEFAULT NULL,
  `deleted` tinyint(1) DEFAULT '0',
  `stic_registrations_leadsleads_ida` varchar(36) DEFAULT NULL,
  `stic_registrations_leadsstic_registrations_idb` varchar(36) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `stic_registrations_stic_events_c`
--

CREATE TABLE IF NOT EXISTS `stic_registrations_stic_events_c` (
  `id` varchar(36) NOT NULL,
  `date_modified` datetime DEFAULT NULL,
  `deleted` tinyint(1) DEFAULT '0',
  `stic_registrations_stic_eventsstic_events_ida` varchar(36) DEFAULT NULL,
  `stic_registrations_stic_eventsstic_registrations_idb` varchar(36) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `stic_remittances`
--

CREATE TABLE IF NOT EXISTS `stic_remittances` (
  `id` char(36) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `date_entered` datetime DEFAULT NULL,
  `date_modified` datetime DEFAULT NULL,
  `modified_user_id` char(36) DEFAULT NULL,
  `created_by` char(36) DEFAULT NULL,
  `description` text,
  `deleted` tinyint(1) DEFAULT '0',
  `assigned_user_id` char(36) DEFAULT NULL,
  `charge_date` date DEFAULT NULL,
  `status` varchar(100) DEFAULT 'open',
  `bank_account` varchar(100) DEFAULT NULL,
  `type` varchar(100) DEFAULT NULL,
  `log` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `stic_remittances_audit`
--

CREATE TABLE IF NOT EXISTS `stic_remittances_audit` (
  `id` char(36) NOT NULL,
  `parent_id` char(36) NOT NULL,
  `date_created` datetime DEFAULT NULL,
  `created_by` varchar(36) DEFAULT NULL,
  `field_name` varchar(100) DEFAULT NULL,
  `data_type` varchar(100) DEFAULT NULL,
  `before_value_string` varchar(255) DEFAULT NULL,
  `after_value_string` varchar(255) DEFAULT NULL,
  `before_value_text` text,
  `after_value_text` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `stic_sessions`
--

CREATE TABLE IF NOT EXISTS `stic_sessions` (
  `id` char(36) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `date_entered` datetime DEFAULT NULL,
  `date_modified` datetime DEFAULT NULL,
  `modified_user_id` char(36) DEFAULT NULL,
  `created_by` char(36) DEFAULT NULL,
  `description` text,
  `deleted` tinyint(1) DEFAULT '0',
  `assigned_user_id` char(36) DEFAULT NULL,
  `contact_id_c` char(36) DEFAULT NULL,
  `start_date` datetime DEFAULT NULL,
  `end_date` datetime DEFAULT NULL,
  `duration` decimal(10,2) DEFAULT NULL,
  `total_attendances` int(11) DEFAULT '0',
  `validated_attendances` int(11) DEFAULT '0',
  `weekday` varchar(100) DEFAULT NULL,
  `activity_type` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `stic_sessions_audit`
--

CREATE TABLE IF NOT EXISTS `stic_sessions_audit` (
  `id` char(36) NOT NULL,
  `parent_id` char(36) NOT NULL,
  `date_created` datetime DEFAULT NULL,
  `created_by` varchar(36) DEFAULT NULL,
  `field_name` varchar(100) DEFAULT NULL,
  `data_type` varchar(100) DEFAULT NULL,
  `before_value_string` varchar(255) DEFAULT NULL,
  `after_value_string` varchar(255) DEFAULT NULL,
  `before_value_text` text,
  `after_value_text` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `stic_sessions_documents_c`
--

CREATE TABLE IF NOT EXISTS `stic_sessions_documents_c` (
  `id` varchar(36) NOT NULL,
  `date_modified` datetime DEFAULT NULL,
  `deleted` tinyint(1) DEFAULT '0',
  `stic_sessions_documentsstic_sessions_ida` varchar(36) DEFAULT NULL,
  `stic_sessions_documentsdocuments_idb` varchar(36) DEFAULT NULL,
  `document_revision_id` varchar(36) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `stic_sessions_stic_events_c`
--

CREATE TABLE IF NOT EXISTS `stic_sessions_stic_events_c` (
  `id` varchar(36) NOT NULL,
  `date_modified` datetime DEFAULT NULL,
  `deleted` tinyint(1) DEFAULT '0',
  `stic_sessions_stic_eventsstic_events_ida` varchar(36) DEFAULT NULL,
  `stic_sessions_stic_eventsstic_sessions_idb` varchar(36) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `stic_settings`
--

CREATE TABLE IF NOT EXISTS `stic_settings` (
  `id` char(36) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `date_entered` datetime DEFAULT NULL,
  `date_modified` datetime DEFAULT NULL,
  `modified_user_id` char(36) DEFAULT NULL,
  `created_by` char(36) DEFAULT NULL,
  `description` text,
  `deleted` tinyint(1) DEFAULT '0',
  `assigned_user_id` char(36) DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL,
  `value` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `stic_settings`
--

INSERT INTO `stic_settings` (`id`, `name`, `date_entered`, `date_modified`, `modified_user_id`, `created_by`, `description`, `deleted`, `assigned_user_id`, `type`, `value`) VALUES
('10bbe32b-ad76-43a6-872d-2e569d657069', 'SEPA_TRANSFER_DEBITOR_IDENTIFIER', '2020-07-04 10:16:16', '2020-07-04 10:16:16', '1', '1', 'Organization identifier for SEPA transfer remittances. The value may differ from one bank to another. Please ask them which value you should use.', 0, '1', 'SEPA', ''),
('15e3baf6-6e13-e79c-ee76-5235850cf8cd', 'TPV_CURRENCY', '2020-07-04 10:16:16', '2020-07-04 10:16:16', '1', '1', 'Currency code for the payment gateway (978 = Euro).', 0, '1', 'TPV', '978'),
('294f7c6c-5166-cea4-220a-523589f2854f', 'TPV_PASSWORD', '2020-07-04 10:16:16', '2020-07-04 10:16:16', '1', '1', 'Password for real mode, provided by the payment gateway.', 0, '1', 'TPV', ''),
('34e479af-35a4-2d47-26a5-518b8a4d6bc2', 'M182_CLAVE_DONATIVO', '2020-07-04 10:16:16', '2020-07-04 10:16:16', '1', '1', 'Deberá rellenarse por las entidades acogidas al régimen de deducciones recogidas en el Título III de la Ley 49/2002, de 23 de diciembre, según el siguiente detalle:\n\rA. Donativos no incluidos en las actividades o programas prioritarios de mecenazgo establecidos por la Ley de Presupuestos Generales del Estado. Valor por defecto (habitual para las entidades usuarias de SinergiaCRM).\n\rB. Donativos incluidos en las actividades o programas prioritarios de mecenazgo establecidos por la Ley de Presupuestos Generales del Estado.\n\rTratándose de aportaciones o disposiciones relativas a patrimonios protegidos, deberá consignarse alguna de las siguientes claves:\n\rC. Aportación al patrimonio de discapacitados.\n\rD. Disposición del patrimonio de discapacitados.\n\rE. Gasto de dinero y consumo de bienes fungibles aportados al patrimonio protegido en el año natural al que se refiere la declaración informativa o en los cuatro anteriores para atender las necesidades vitales del beneficiario y que no deban considerarse como disposición de bienes o derechos a efectos de lo dispuesto en el artículo 54.5 de la Ley 35/2006, de 28 de noviembre, del Impuesto sobre la Renta de las Personas Físicas.', 0, '1', 'M182', 'A'),
('46a61ece-821c-11e8-9c7c-00163e7f1a26', 'GENERAL_IBAN_VALIDATION', '2020-07-04 10:16:16', '2020-07-04 10:16:16', '1', '1', '1 - The IBAN will be validated. This is the default value and the value used for all those organizations inside SEPA.\r\n0 - The IBAN will not be validated, so the field will accept any value. Only for organizations outside SEPA.', 0, '1', 'GENERAL', '1'),
('46aa0f7b-821c-11e8-9c7c-00163e7f1a26', 'SEPA_TRANSFER_DEFAULT_REMITTANCE_INFO', '2020-07-04 10:16:16', '2020-07-04 10:16:16', '1', '1', 'Information that will appear in the payment order if there is no info in the payment record.', 0, '1', 'SEPA', ''),
('46cd35a1-821c-11e8-9c7c-00163e7f1a26', 'GENERAL_PAYMENT_GENERATION_MONTH', '2020-07-04 10:16:16', '2020-07-04 10:16:16', '1', '1', 'Generate recurring payments for:\r\n0 - Current month\r\n1 - Next month.', 0, '1', 'GENERAL', '0'),
('4efc55c0-0145-4282-3691-518b83e91075', 'M182_PERSONA_CONTACTO_TELEFONO', '2020-07-04 10:16:16', '2020-07-04 10:16:16', '1', '1', '', 0, '1', 'M182', ''),
('5accf323-9416-3c6d-62d9-518b836467fb', 'M182_PERSONA_CONTACTO_APELLIDO_2', '2020-07-04 10:16:16', '2020-07-04 10:16:16', '1', '1', '', 0, '1', 'M182', ''),
('5cd71028-09ec-2b1c-54bf-518b8344004a', 'M182_PERSONA_CONTACTO_NOMBRE', '2020-07-04 10:16:16', '2020-07-04 10:16:16', '1', '1', '', 0, '1', 'M182', ''),
('778903f8-1fcc-2e3d-3fc1-523585707228', 'TPV_MERCHANT_NAME', '2020-07-04 10:16:16', '2020-07-04 10:16:16', '1', '1', 'The name of your organization, that will be shown in the payment gateway.', 0, '1', 'TPV', ''),
('7de47385-e036-bea9-0946-56a129d7b3b1', 'M182_NUMERO_JUSTIFICANTE', '2020-07-04 10:16:16', '2020-07-04 10:16:16', '1', '1', 'Código numérico de 13 dígitos que identifica la declaración. Debe consignarse un nuevo valor antes de generar el fichero de un nuevo ejercicio. Se puede obtener en https://www2.agenciatributaria.gob.es/L/inwinvoc/es.aeat.adht.nume.web.editran.NumRefEditran?mod=182', 0, '1', 'M182', ''),
('846d3469-acef-db6c-41bc-518cccb58b4a', 'M182_PORCENTAJE_DEDUCCION_AUTONOMICA_XX', '2020-07-04 10:16:16', '2020-07-04 10:16:16', '1', '1', 'Las organizaciones que puedan ofrecer deducciones adicionales a sus donantes en determinadas comunidades autónomas deberán crear claves de configuración del tipo M182_PORCENTAJE_DEDUCCION_AUTONOMICA_XX, donde XX deberá ser el código de la comunidad autónoma y el valor de la clave deberá ser el porcentaje de deducción aplicable (con dos decimales y sin símbolo de %). Se pueden consultar los códigos de las comunidades en https://wiki.sinergiacrm.org/index.php?title=Formas_de_pago,_Pagos_y_Remesas#Notas_adicionales_sobre_las_constantes_del_Modelo_182', 0, '1', 'M182', ''),
('921e5673-9d0c-7e04-94c8-523585ccff61', 'TPV_MERCHANT_CODE', '2020-07-04 10:16:16', '2020-07-04 10:16:16', '1', '1', 'Unique numeric code provided by the payment gateway.', 0, '1', 'TPV', ''),
('978a2000-022b-5c5e-0cd7-518b8313e761', 'M182_PERSONA_CONTACTO_APELLIDO_1', '2020-07-04 10:16:16', '2020-07-04 10:16:16', '1', '1', '', 0, '1', 'M182', ''),
('9a3548a7-0588-44d4-29a6-518b827f360c', 'GENERAL_ORGANIZATION_ID', '2020-07-04 10:16:16', '2020-07-04 10:16:16', '1', '1', 'Any code suitable for officially identifying your organization. In Spain, NIF should be used.', 0, '1', 'GENERAL', ''),
('b78ec43e-957d-94c3-b128-530da9b40a84', 'SEPA_BIC_CODE', '2020-07-04 10:16:16', '2020-07-04 10:16:16', '1', '1', 'BIC code of your bank. This will only be used when SEPA_DEBIT_BIC_MODE is 1.', 0, '1', 'SEPA', ''),
('b9755f82-72c9-d361-10b7-5b40699f14c4', 'TPV_TEST', '2020-07-04 10:16:16', '2020-07-04 10:16:16', '1', '1', 'Indicates the working mode (0 = Real, 1 = Test).', 0, '1', 'TPV', '1'),
('ba936db7-e756-6d97-c38e-5b40693b0287', 'TPV_PASSWORD_TEST', '2020-07-04 10:16:16', '2020-07-04 10:16:16', '1', '1', 'Password for test mode, provided by the payment gateway.', 0, '1', 'TPV', ''),
('bbe94f29-25e2-7680-d32e-5b4069f29df2', 'PAYPAL_ID', '2020-07-04 10:16:16', '2020-07-04 10:16:16', '1', '1', 'User identifier or email address of your PayPal BUSINESS account.', 0, '1', 'PAYPAL', ''),
('bc2be16f-38dc-3e4b-e0ae-5b4069cc347a', 'PAYPAL_ID_TEST', '2020-07-04 10:16:16', '2020-07-04 10:16:16', '1', '1', 'Email address of your PayPal SANDBOX account (developer.paypal.com).', 0, '1', 'PAYPAL', ''),
('bc77f99d-ba05-2f65-a967-5b406973ce68', 'PAYPAL_TEST', '2020-07-04 10:16:16', '2020-07-04 10:16:16', '1', '1', 'Indicates the working mode (0 = Real, 1 = Test).', 0, '1', 'PAYPAL', '1'),
('bd7a0703-76c8-eed9-a455-518cba43f3ce', 'M182_NATURALEZA_DECLARANTE', '2020-07-04 10:16:16', '2020-07-04 10:16:16', '1', '1', 'Se hará constar el dígito numérico indicativo de la naturaleza del declarante, de acuerdo con la siguiente relación:\n\r1. Entidad beneficiaria de los incentivos regulados en el Título III de la Ley 49/2002, de 23 de diciembre, de régimen fiscal de las entidades sin fines lucrativos y de los incentivos fiscales al mecenazgo.\n\r2. Fundación legalmente reconocida que rinde cuentas al órgano del protectorado correspondiente o asociación declarada de utilidad pública a que se refieren elartículo 68.3.b) de la Ley del Impuesto sobre la Renta de las Personas Físicas.\n\r3. Titular o administrador de un patrimonio protegido regulado en la Ley 41/2003, de 18 de noviembre, de protección patrimonial de las personas con discapacidad y de modificación del Código Civil, de la Ley de Enjuiciamiento Civil y de la Normativa Tributaria con esta finalidad.\n\r4. Partidos Políticos, Federaciones, Coaliciones o Agrupaciones de Electores en los términos previstos en la Ley Orgánica 8/2007, de 4 de julio, de financiación de partidos políticos.', 0, '1', 'M182', '1'),
('cc9ba14d-e56c-5d8e-ae18-52358805cda7', 'TPV_TERMINAL', '2020-07-04 10:16:16', '2020-07-04 10:16:16', '1', '1', 'Terminal number, provided by the payment gateway. Usually number 1, but could be 2, 3, etc. depending on wether your organization has one or more POS terminals.', 0, '1', 'TPV', '1'),
('d26ed2ae-2574-43ac-93d6-487baeca28d7', 'SEPA_DEBIT_DEFAULT_REMITTANCE_INFO', '2020-07-04 10:16:16', '2020-07-04 10:16:16', '1', '1', 'Information that will appear in the debit order if there is no info in the payment record.', 0, '1', 'SEPA', ''),
('d4f6e75e-e50d-11e9-b361-fa163e94e8de', 'SEPA_DEBIT_BIC_MODE', '2020-07-04 10:16:16', '2020-07-04 10:16:16', '1', '1', 'When set to 0 (default value), BIC is not included in remittances. When set to 1 the value of SEPA_BIC_CODE will be included. Some banks, like Santander, demand it, although it is not necessary.', 0, '1', 'SEPA', '0'),
('dbc49c32-25f7-a1ee-728d-518b824a16fc', 'GENERAL_ORGANIZATION_NAME', '2020-07-04 10:16:16', '2020-07-04 10:16:16', '1', '1', 'The name of your organization. It will be used wherever is needed: bank remittances, tax files, etc.', 0, '1', 'GENERAL', ''),
('eacb2339-7741-96d7-3fd6-531f07485832', 'SEPA_DEBIT_CREDITOR_IDENTIFIER', '2020-07-04 10:16:16', '2020-07-04 10:16:16', '1', '1', 'Unique identifier for SEPA direct debit remittances. More information in https://wiki.sinergiacrm.org/index.php?title=Formas_de_pago,_Pagos_y_Remesas#Constantes_SEPA_para_remesas_de_recibos_domiciliados', 0, '1', 'SEPA', '');

-- --------------------------------------------------------

--
-- Table structure for table `stic_settings_audit`
--

CREATE TABLE IF NOT EXISTS `stic_settings_audit` (
  `id` char(36) NOT NULL,
  `parent_id` char(36) NOT NULL,
  `date_created` datetime DEFAULT NULL,
  `created_by` varchar(36) DEFAULT NULL,
  `field_name` varchar(100) DEFAULT NULL,
  `data_type` varchar(100) DEFAULT NULL,
  `before_value_string` varchar(255) DEFAULT NULL,
  `after_value_string` varchar(255) DEFAULT NULL,
  `before_value_text` text,
  `after_value_text` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `stic_validation_actions`
--

CREATE TABLE IF NOT EXISTS `stic_validation_actions` (
  `id` char(36) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `date_entered` datetime DEFAULT NULL,
  `date_modified` datetime DEFAULT NULL,
  `modified_user_id` char(36) DEFAULT NULL,
  `created_by` char(36) DEFAULT NULL,
  `description` text,
  `deleted` tinyint(1) DEFAULT '0',
  `assigned_user_id` char(36) DEFAULT NULL,
  `last_execution` datetime DEFAULT NULL,
  `function` varchar(100) DEFAULT NULL,
  `report_always` tinyint(1) DEFAULT '0',
  `priority` int(255) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `stic_validation_actions`
--

INSERT INTO `stic_validation_actions` (`id`, `name`, `date_entered`, `date_modified`, `modified_user_id`, `created_by`, `description`, `deleted`, `assigned_user_id`, `last_execution`, `function`, `report_always`, `priority`) VALUES
('14875de6-ed5e-443a-abbc-54d57dec100e', 'Payment methods - Main data validation', '2020-07-04 10:16:16', '2020-07-04 10:16:16', '1', '1', NULL, 0, '1', NULL, '14875de6-ed5e-443a-abbc-54d57dec100e', 0, 45),
('17ade03a-9f60-4e14-bd8b-69ae39243526', 'Contacts - Search for duplicates', '2020-07-04 10:16:16', '2020-07-04 10:16:16', '1', '1', NULL, 0, '1', NULL, '17ade03a-9f60-4e14-bd8b-69ae39243526', 0, 0),
('28874faf-7465-43a4-ad31-357769af3f6f', 'Registrations - Main data validation', '2020-07-04 10:16:16', '2020-07-04 10:16:16', '1', '1', NULL, 0, '1', NULL, '28874faf-7465-43a4-ad31-357769af3f6f', 0, 15),
('2fede90f-5df5-44a2-8c8a-bc1a1813dc70', 'Contacts - Relationship type validation', '2020-07-04 10:16:16', '2020-07-04 10:16:16', '1', '1', NULL, 0, '1', NULL, '2fede90f-5df5-44a2-8c8a-bc1a1813dc70', 0, 80),
('375431dc-a6bb-4c0b-ab4c-af1a06229ee4', 'Remittances - Main data validation', '2020-07-04 10:16:16', '2020-07-04 10:16:16', '1', '1', NULL, 0, '1', NULL, '375431dc-a6bb-4c0b-ab4c-af1a06229ee4', 0, 25),
('430a2764-5e4d-4a54-835c-0a1896ad2fc0', 'Relationships with Contacts - Relationships validation', '2020-07-04 10:16:16', '2020-07-04 10:16:16', '1', '1', NULL, 0, '1', NULL, '430a2764-5e4d-4a54-835c-0a1896ad2fc0', 0, 70),
('88aa01ca-94a1-4313-a24e-a0a637dcf029', 'Registrations - Relationships validation', '2020-07-04 10:16:16', '2020-07-04 10:16:16', '1', '1', NULL, 0, '1', NULL, '88aa01ca-94a1-4313-a24e-a0a637dcf029', 0, 10),
('914c771f-9609-43b8-8229-99395f48d6f9', 'Leads - Main data validation', '2020-07-04 10:16:16', '2020-07-04 10:16:16', '1', '1', NULL, 0, '1', NULL, '914c771f-9609-43b8-8229-99395f48d6f9', 0, 5),
('99d53183-6091-40c0-ac04-ed4fb099528c', 'Relationships with Accounts - Relationships validation', '2020-07-04 10:16:16', '2020-07-04 10:16:16', '1', '1', NULL, 0, '1', NULL, '99d53183-6091-40c0-ac04-ed4fb099528c', 0, 50),
('9b975af1-34c9-8cae-1f60-5db9d528c22a', 'Accounts - Search for duplicates', '2020-07-04 10:16:16', '2020-07-04 10:16:16', '1', '1', NULL, 0, '1', NULL, '9b975af1-34c9-8cae-1f60-5db9d528c22a', 0, 0),
('b07eefb3-20fb-4993-abea-66ce0aa71649', 'Remittances - Relationships validation', '2020-07-04 10:16:16', '2020-07-04 10:16:16', '1', '1', NULL, 0, '1', NULL, 'b07eefb3-20fb-4993-abea-66ce0aa71649', 0, 20),
('b738e9b4-c025-4a96-86c1-c2c6f657d3cf', 'Payments - Relationships validation', '2020-07-04 10:16:16', '2020-07-04 10:16:16', '1', '1', NULL, 0, '1', NULL, 'b738e9b4-c025-4a96-86c1-c2c6f657d3cf', 0, 30),
('ccd95008-28c1-42ff-be53-6722b821e1e5', 'Relationships with Accounts - Main data validation', '2020-07-04 10:16:16', '2020-07-04 10:16:16', '1', '1', NULL, 0, '1', NULL, 'ccd95008-28c1-42ff-be53-6722b821e1e5', 0, 55),
('d1d60459-3713-488d-94ce-ff38bf3e1f98', 'Accounts - Main data validation', '2020-07-04 10:16:16', '2020-07-04 10:16:16', '1', '1', NULL, 0, '1', NULL, 'd1d60459-3713-488d-94ce-ff38bf3e1f98', 0, 65),
('d2baf24e-cd27-47c5-8ee1-84c905b9198d', 'Payment methods - Relationships validation', '2020-07-04 10:16:16', '2020-07-04 10:16:16', '1', '1', NULL, 0, '1', NULL, 'd2baf24e-cd27-47c5-8ee1-84c905b9198d', 0, 40),
('d49627f2-3623-44e3-bdb2-d5af0f8c5165', 'Relationships with Contacts - Main data validation', '2020-07-04 10:16:16', '2020-07-04 10:16:16', '1', '1', NULL, 0, '1', NULL, 'd49627f2-3623-44e3-bdb2-d5af0f8c5165', 0, 75),
('e126ec69-2a9e-4bb9-a731-a05f95b3e4c7', 'Accounts - Relationship type validation', '2020-07-04 10:16:16', '2020-07-04 10:16:16', '1', '1', NULL, 0, '1', NULL, 'e126ec69-2a9e-4bb9-a731-a05f95b3e4c7', 0, 60),
('e39516bb-9acf-4c6f-8e25-d3af9aac0a95', 'Payments - Main data validation', '2020-07-04 10:16:16', '2020-07-04 10:16:16', '1', '1', NULL, 0, '1', NULL, 'e39516bb-9acf-4c6f-8e25-d3af9aac0a95', 0, 35),
('f512af92-7518-4bbe-b583-5b43bc6223da', 'Contacts - Main data validation', '2020-07-04 10:16:16', '2020-07-04 10:16:16', '1', '1', NULL, 0, '1', NULL, 'f512af92-7518-4bbe-b583-5b43bc6223da', 0, 85);

-- --------------------------------------------------------

--
-- Table structure for table `stic_validation_actions_audit`
--

CREATE TABLE IF NOT EXISTS `stic_validation_actions_audit` (
  `id` char(36) NOT NULL,
  `parent_id` char(36) NOT NULL,
  `date_created` datetime DEFAULT NULL,
  `created_by` varchar(36) DEFAULT NULL,
  `field_name` varchar(100) DEFAULT NULL,
  `data_type` varchar(100) DEFAULT NULL,
  `before_value_string` varchar(255) DEFAULT NULL,
  `after_value_string` varchar(255) DEFAULT NULL,
  `before_value_text` text,
  `after_value_text` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `stic_validation_actions_schedulers_c`
--

CREATE TABLE IF NOT EXISTS `stic_validation_actions_schedulers_c` (
  `id` varchar(36) NOT NULL,
  `date_modified` datetime DEFAULT NULL,
  `deleted` tinyint(1) DEFAULT '0',
  `stic_validation_actions_schedulersstic_validation_actions_ida` varchar(36) DEFAULT NULL,
  `stic_validation_actions_schedulersschedulers_idb` varchar(36) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `stic_validation_actions_schedulers_c`
--

INSERT INTO `stic_validation_actions_schedulers_c` (`id`, `date_modified`, `deleted`, `stic_validation_actions_schedulersstic_validation_actions_ida`, `stic_validation_actions_schedulersschedulers_idb`) VALUES
('16085edd-15a4-e6df-c869-5b406a4611ed', '2020-07-04 10:16:16', 0, 'f512af92-7518-4bbe-b583-5b43bc6223da', '7386c4b1-bcc2-4f6f-be88-7e2a2e5778b5'),
('1925c223-f11d-4b63-4ca7-5b406ae57fce', '2020-07-04 10:16:16', 0, '17ade03a-9f60-4e14-bd8b-69ae39243526', '7386c4b1-bcc2-4f6f-be88-7e2a2e5778b5'),
('1c4a0e52-10b0-281e-1a8a-5b406af94151', '2020-07-04 10:16:16', 0, 'd49627f2-3623-44e3-bdb2-d5af0f8c5165', '7386c4b1-bcc2-4f6f-be88-7e2a2e5778b5'),
('1f497438-8d62-ce8d-74a4-5b406a88c50e', '2020-07-04 10:16:16', 0, '430a2764-5e4d-4a54-835c-0a1896ad2fc0', '7386c4b1-bcc2-4f6f-be88-7e2a2e5778b5'),
('2251bd95-7cdd-fba9-a7ea-5b406ac57c01', '2020-07-04 10:16:16', 0, 'd1d60459-3713-488d-94ce-ff38bf3e1f98', '7386c4b1-bcc2-4f6f-be88-7e2a2e5778b5'),
('25698b63-f1e6-05fa-2b6b-5b406a95cd36', '2020-07-04 10:16:16', 0, 'ccd95008-28c1-42ff-be53-6722b821e1e5', '7386c4b1-bcc2-4f6f-be88-7e2a2e5778b5'),
('286879b9-d062-24d6-9192-5b406aa55c2a', '2020-07-04 10:16:16', 0, '99d53183-6091-40c0-ac04-ed4fb099528c', '7386c4b1-bcc2-4f6f-be88-7e2a2e5778b5'),
('298d9945-7c0e-8995-59c0-5b406a085c56', '2020-07-04 10:16:16', 0, '914c771f-9609-43b8-8229-99395f48d6f9', '7386c4b1-bcc2-4f6f-be88-7e2a2e5778b5'),
('2a7466d0-996f-5b1e-b9dd-5b406a5c080c', '2020-07-04 10:16:16', 0, '28874faf-7465-43a4-ad31-357769af3f6f', '7386c4b1-bcc2-4f6f-be88-7e2a2e5778b5'),
('2b55ea0e-966a-fcd1-ed41-5b406a336933', '2020-07-04 10:16:16', 0, '88aa01ca-94a1-4313-a24e-a0a637dcf029', '7386c4b1-bcc2-4f6f-be88-7e2a2e5778b5'),
('4e95a019-c3ef-1bec-bd0a-5b406aba0c10', '2020-07-04 10:16:16', 0, '14875de6-ed5e-443a-abbc-54d57dec100e', 'a9bebf7f-8896-46dd-8d06-77e2b5256c83'),
('4fb6f8b3-5df3-6fbb-5a42-5b406a46d2bd', '2020-07-04 10:16:16', 0, 'd2baf24e-cd27-47c5-8ee1-84c905b9198d', 'a9bebf7f-8896-46dd-8d06-77e2b5256c83'),
('50b61496-7a27-4fcd-2dd6-5b406ab9da18', '2020-07-04 10:16:16', 0, 'e39516bb-9acf-4c6f-8e25-d3af9aac0a95', 'a9bebf7f-8896-46dd-8d06-77e2b5256c83'),
('51973ce8-dce3-41f9-9e39-5b406a37226a', '2020-07-04 10:16:16', 0, 'b738e9b4-c025-4a96-86c1-c2c6f657d3cf', 'a9bebf7f-8896-46dd-8d06-77e2b5256c83'),
('521b0d1c-800e-f0a2-e36e-5b406a145158', '2020-07-04 10:16:16', 0, '375431dc-a6bb-4c0b-ab4c-af1a06229ee4', 'a9bebf7f-8896-46dd-8d06-77e2b5256c83'),
('54461dbd-2ad4-8536-af64-5b406a2821ff', '2020-07-04 10:16:16', 0, 'b07eefb3-20fb-4993-abea-66ce0aa71649', 'a9bebf7f-8896-46dd-8d06-77e2b5256c83'),
('a2b96791-1f65-ee15-18ba-5db9d5e016d3', '2020-07-04 10:16:16', 0, '9b975af1-34c9-8cae-1f60-5db9d528c22a', '7386c4b1-bcc2-4f6f-be88-7e2a2e5778b5'),
('b60f8629-0fb7-ccd6-358e-5b406aecfa9f', '2020-07-04 10:16:16', 0, '2fede90f-5df5-44a2-8c8a-bc1a1813dc70', 'b05bde8a-1309-4789-993b-bf85be389f07'),
('b7cdff60-6c50-7059-3c60-5b406a81e407', '2020-07-04 10:16:16', 0, 'e126ec69-2a9e-4bb9-a731-a05f95b3e4c7', 'b05bde8a-1309-4789-993b-bf85be389f07');

-- --------------------------------------------------------

--
-- Table structure for table `sugarfeed`
--

CREATE TABLE IF NOT EXISTS `sugarfeed` (
  `id` char(36) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `date_entered` datetime DEFAULT NULL,
  `date_modified` datetime DEFAULT NULL,
  `modified_user_id` char(36) DEFAULT NULL,
  `created_by` char(36) DEFAULT NULL,
  `description` text,
  `deleted` tinyint(1) DEFAULT '0',
  `assigned_user_id` char(36) DEFAULT NULL,
  `related_module` varchar(100) DEFAULT NULL,
  `related_id` char(36) DEFAULT NULL,
  `link_url` varchar(255) DEFAULT NULL,
  `link_type` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `surveyquestionoptions`
--

CREATE TABLE IF NOT EXISTS `surveyquestionoptions` (
  `id` char(36) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `date_entered` datetime DEFAULT NULL,
  `date_modified` datetime DEFAULT NULL,
  `modified_user_id` char(36) DEFAULT NULL,
  `created_by` char(36) DEFAULT NULL,
  `description` text,
  `deleted` tinyint(1) DEFAULT '0',
  `assigned_user_id` char(36) DEFAULT NULL,
  `sort_order` int(255) DEFAULT NULL,
  `survey_question_id` char(36) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `surveyquestionoptions_audit`
--

CREATE TABLE IF NOT EXISTS `surveyquestionoptions_audit` (
  `id` char(36) NOT NULL,
  `parent_id` char(36) NOT NULL,
  `date_created` datetime DEFAULT NULL,
  `created_by` varchar(36) DEFAULT NULL,
  `field_name` varchar(100) DEFAULT NULL,
  `data_type` varchar(100) DEFAULT NULL,
  `before_value_string` varchar(255) DEFAULT NULL,
  `after_value_string` varchar(255) DEFAULT NULL,
  `before_value_text` text,
  `after_value_text` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `surveyquestionoptions_surveyquestionresponses`
--

CREATE TABLE IF NOT EXISTS `surveyquestionoptions_surveyquestionresponses` (
  `id` varchar(36) NOT NULL,
  `date_modified` datetime DEFAULT NULL,
  `deleted` tinyint(1) DEFAULT '0',
  `surveyq72c7options_ida` varchar(36) DEFAULT NULL,
  `surveyq10d4sponses_idb` varchar(36) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `surveyquestionresponses`
--

CREATE TABLE IF NOT EXISTS `surveyquestionresponses` (
  `id` char(36) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `date_entered` datetime DEFAULT NULL,
  `date_modified` datetime DEFAULT NULL,
  `modified_user_id` char(36) DEFAULT NULL,
  `created_by` char(36) DEFAULT NULL,
  `description` text,
  `deleted` tinyint(1) DEFAULT '0',
  `assigned_user_id` char(36) DEFAULT NULL,
  `answer` text,
  `answer_bool` tinyint(1) DEFAULT NULL,
  `answer_datetime` datetime DEFAULT NULL,
  `surveyquestion_id` char(36) DEFAULT NULL,
  `surveyresponse_id` char(36) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `surveyquestionresponses_audit`
--

CREATE TABLE IF NOT EXISTS `surveyquestionresponses_audit` (
  `id` char(36) NOT NULL,
  `parent_id` char(36) NOT NULL,
  `date_created` datetime DEFAULT NULL,
  `created_by` varchar(36) DEFAULT NULL,
  `field_name` varchar(100) DEFAULT NULL,
  `data_type` varchar(100) DEFAULT NULL,
  `before_value_string` varchar(255) DEFAULT NULL,
  `after_value_string` varchar(255) DEFAULT NULL,
  `before_value_text` text,
  `after_value_text` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `surveyquestions`
--

CREATE TABLE IF NOT EXISTS `surveyquestions` (
  `id` char(36) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `date_entered` datetime DEFAULT NULL,
  `date_modified` datetime DEFAULT NULL,
  `modified_user_id` char(36) DEFAULT NULL,
  `created_by` char(36) DEFAULT NULL,
  `description` text,
  `deleted` tinyint(1) DEFAULT '0',
  `assigned_user_id` char(36) DEFAULT NULL,
  `sort_order` int(255) DEFAULT NULL,
  `type` varchar(100) DEFAULT NULL,
  `happiness_question` tinyint(1) DEFAULT NULL,
  `survey_id` char(36) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `surveyquestions_audit`
--

CREATE TABLE IF NOT EXISTS `surveyquestions_audit` (
  `id` char(36) NOT NULL,
  `parent_id` char(36) NOT NULL,
  `date_created` datetime DEFAULT NULL,
  `created_by` varchar(36) DEFAULT NULL,
  `field_name` varchar(100) DEFAULT NULL,
  `data_type` varchar(100) DEFAULT NULL,
  `before_value_string` varchar(255) DEFAULT NULL,
  `after_value_string` varchar(255) DEFAULT NULL,
  `before_value_text` text,
  `after_value_text` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `surveyresponses`
--

CREATE TABLE IF NOT EXISTS `surveyresponses` (
  `id` char(36) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `date_entered` datetime DEFAULT NULL,
  `date_modified` datetime DEFAULT NULL,
  `modified_user_id` char(36) DEFAULT NULL,
  `created_by` char(36) DEFAULT NULL,
  `description` text,
  `deleted` tinyint(1) DEFAULT '0',
  `assigned_user_id` char(36) DEFAULT NULL,
  `happiness` int(11) DEFAULT NULL,
  `email_response_sent` tinyint(1) DEFAULT NULL,
  `account_id` char(36) DEFAULT NULL,
  `campaign_id` char(36) DEFAULT NULL,
  `contact_id` char(36) DEFAULT NULL,
  `survey_id` char(36) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `surveyresponses_audit`
--

CREATE TABLE IF NOT EXISTS `surveyresponses_audit` (
  `id` char(36) NOT NULL,
  `parent_id` char(36) NOT NULL,
  `date_created` datetime DEFAULT NULL,
  `created_by` varchar(36) DEFAULT NULL,
  `field_name` varchar(100) DEFAULT NULL,
  `data_type` varchar(100) DEFAULT NULL,
  `before_value_string` varchar(255) DEFAULT NULL,
  `after_value_string` varchar(255) DEFAULT NULL,
  `before_value_text` text,
  `after_value_text` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `surveys`
--

CREATE TABLE IF NOT EXISTS `surveys` (
  `id` char(36) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `date_entered` datetime DEFAULT NULL,
  `date_modified` datetime DEFAULT NULL,
  `modified_user_id` char(36) DEFAULT NULL,
  `created_by` char(36) DEFAULT NULL,
  `description` text,
  `deleted` tinyint(1) DEFAULT '0',
  `assigned_user_id` char(36) DEFAULT NULL,
  `status` varchar(100) DEFAULT 'Draft',
  `submit_text` varchar(255) DEFAULT 'Submit',
  `satisfied_text` varchar(255) DEFAULT 'Satisfied',
  `neither_text` varchar(255) DEFAULT 'Neither Satisfied nor Dissatisfied',
  `dissatisfied_text` varchar(255) DEFAULT 'Dissatisfied'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `surveys_audit`
--

CREATE TABLE IF NOT EXISTS `surveys_audit` (
  `id` char(36) NOT NULL,
  `parent_id` char(36) NOT NULL,
  `date_created` datetime DEFAULT NULL,
  `created_by` varchar(36) DEFAULT NULL,
  `field_name` varchar(100) DEFAULT NULL,
  `data_type` varchar(100) DEFAULT NULL,
  `before_value_string` varchar(255) DEFAULT NULL,
  `after_value_string` varchar(255) DEFAULT NULL,
  `before_value_text` text,
  `after_value_text` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tasks`
--

CREATE TABLE IF NOT EXISTS `tasks` (
  `id` char(36) NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `date_entered` datetime DEFAULT NULL,
  `date_modified` datetime DEFAULT NULL,
  `modified_user_id` char(36) DEFAULT NULL,
  `created_by` char(36) DEFAULT NULL,
  `description` text,
  `deleted` tinyint(1) DEFAULT '0',
  `assigned_user_id` char(36) DEFAULT NULL,
  `status` varchar(100) DEFAULT 'Not Started',
  `date_due_flag` tinyint(1) DEFAULT '0',
  `date_due` datetime DEFAULT NULL,
  `date_start_flag` tinyint(1) DEFAULT '0',
  `date_start` datetime DEFAULT NULL,
  `parent_type` varchar(255) DEFAULT NULL,
  `parent_id` char(36) DEFAULT NULL,
  `contact_id` char(36) DEFAULT NULL,
  `priority` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `templatesectionline`
--

CREATE TABLE IF NOT EXISTS `templatesectionline` (
  `id` char(36) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `date_entered` datetime DEFAULT NULL,
  `date_modified` datetime DEFAULT NULL,
  `modified_user_id` char(36) DEFAULT NULL,
  `created_by` char(36) DEFAULT NULL,
  `description` text,
  `deleted` tinyint(1) DEFAULT '0',
  `thumbnail` varchar(255) DEFAULT NULL,
  `grp` varchar(255) DEFAULT NULL,
  `ord` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tracker`
--

CREATE TABLE IF NOT EXISTS `tracker` (
  `id` int(11) NOT NULL,
  `monitor_id` char(36) NOT NULL,
  `user_id` varchar(36) DEFAULT NULL,
  `module_name` varchar(255) DEFAULT NULL,
  `item_id` varchar(36) DEFAULT NULL,
  `item_summary` varchar(255) DEFAULT NULL,
  `date_modified` datetime DEFAULT NULL,
  `action` varchar(255) DEFAULT NULL,
  `session_id` varchar(36) DEFAULT NULL,
  `visible` tinyint(1) DEFAULT '0',
  `deleted` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `upgrade_history`
--

CREATE TABLE IF NOT EXISTS `upgrade_history` (
  `id` char(36) NOT NULL,
  `filename` varchar(255) DEFAULT NULL,
  `md5sum` varchar(32) DEFAULT NULL,
  `type` varchar(30) DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `version` varchar(64) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `description` text,
  `id_name` varchar(255) DEFAULT NULL,
  `manifest` longtext,
  `date_entered` datetime DEFAULT NULL,
  `enabled` tinyint(1) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` char(36) NOT NULL,
  `user_name` varchar(60) DEFAULT NULL,
  `user_hash` varchar(255) DEFAULT NULL,
  `system_generated_password` tinyint(1) DEFAULT NULL,
  `pwd_last_changed` datetime DEFAULT NULL,
  `authenticate_id` varchar(100) DEFAULT NULL,
  `sugar_login` tinyint(1) DEFAULT '1',
  `first_name` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) DEFAULT NULL,
  `is_admin` tinyint(1) DEFAULT '0',
  `external_auth_only` tinyint(1) DEFAULT '0',
  `receive_notifications` tinyint(1) DEFAULT '1',
  `description` text,
  `date_entered` datetime DEFAULT NULL,
  `date_modified` datetime DEFAULT NULL,
  `modified_user_id` char(36) DEFAULT NULL,
  `created_by` char(36) DEFAULT NULL,
  `title` varchar(50) DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `department` varchar(50) DEFAULT NULL,
  `phone_home` varchar(50) DEFAULT NULL,
  `phone_mobile` varchar(50) DEFAULT NULL,
  `phone_work` varchar(50) DEFAULT NULL,
  `phone_other` varchar(50) DEFAULT NULL,
  `phone_fax` varchar(50) DEFAULT NULL,
  `status` varchar(100) DEFAULT NULL,
  `address_street` varchar(150) DEFAULT NULL,
  `address_city` varchar(100) DEFAULT NULL,
  `address_state` varchar(100) DEFAULT NULL,
  `address_country` varchar(100) DEFAULT NULL,
  `address_postalcode` varchar(20) DEFAULT NULL,
  `deleted` tinyint(1) DEFAULT NULL,
  `portal_only` tinyint(1) DEFAULT '0',
  `show_on_employees` tinyint(1) DEFAULT '1',
  `employee_status` varchar(100) DEFAULT NULL,
  `messenger_id` varchar(100) DEFAULT NULL,
  `messenger_type` varchar(100) DEFAULT NULL,
  `reports_to_id` char(36) DEFAULT NULL,
  `is_group` tinyint(1) DEFAULT NULL,
  `factor_auth` tinyint(1) DEFAULT NULL,
  `factor_auth_interface` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `user_name`, `user_hash`, `system_generated_password`, `pwd_last_changed`, `authenticate_id`, `sugar_login`, `first_name`, `last_name`, `is_admin`, `external_auth_only`, `receive_notifications`, `description`, `date_entered`, `date_modified`, `modified_user_id`, `created_by`, `title`, `photo`, `department`, `phone_home`, `phone_mobile`, `phone_work`, `phone_other`, `phone_fax`, `status`, `address_street`, `address_city`, `address_state`, `address_country`, `address_postalcode`, `deleted`, `portal_only`, `show_on_employees`, `employee_status`, `messenger_id`, `messenger_type`, `reports_to_id`, `is_group`, `factor_auth`, `factor_auth_interface`) VALUES
('1', 'admin', '437d9c260a08f655b558f0287beb98a0', 0, NULL, NULL, 1, NULL, 'Administrator', 1, 0, 1, NULL, '2020-07-04 10:16:13', '2020-07-04 10:16:13', '1', '', 'Administrator', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Active', NULL, NULL, NULL, NULL, NULL, 0, 0, 1, 'Active', NULL, NULL, '', 0, 0, NULL),
('2', 'sinergiacrm', '9a791d3d67960991e67ecf0d44d7d1d3', 0, NULL, NULL, 1, NULL, 'SinergiaCRM', 1, 0, 1, NULL, '2020-07-04 10:16:13', '2020-07-04 10:16:13', '2', '1', 'SinergiaCRM', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Active', NULL, NULL, NULL, NULL, NULL, 0, 0, 1, 'Active', NULL, NULL, '', 0, 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users_feeds`
--

CREATE TABLE IF NOT EXISTS `users_feeds` (
  `user_id` varchar(36) DEFAULT NULL,
  `feed_id` varchar(36) DEFAULT NULL,
  `rank` int(11) DEFAULT NULL,
  `date_modified` datetime DEFAULT NULL,
  `deleted` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `users_last_import`
--

CREATE TABLE IF NOT EXISTS `users_last_import` (
  `id` char(36) NOT NULL,
  `assigned_user_id` char(36) DEFAULT NULL,
  `import_module` varchar(36) DEFAULT NULL,
  `bean_type` varchar(36) DEFAULT NULL,
  `bean_id` char(36) DEFAULT NULL,
  `deleted` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `users_password_link`
--

CREATE TABLE IF NOT EXISTS `users_password_link` (
  `id` char(36) NOT NULL,
  `username` varchar(36) DEFAULT NULL,
  `date_generated` datetime DEFAULT NULL,
  `deleted` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `users_signatures`
--

CREATE TABLE IF NOT EXISTS `users_signatures` (
  `id` char(36) NOT NULL,
  `date_entered` datetime DEFAULT NULL,
  `date_modified` datetime DEFAULT NULL,
  `deleted` tinyint(1) DEFAULT NULL,
  `user_id` varchar(36) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `signature` text,
  `signature_html` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `user_preferences`
--

CREATE TABLE IF NOT EXISTS `user_preferences` (
  `id` char(36) NOT NULL,
  `category` varchar(50) DEFAULT NULL,
  `deleted` tinyint(1) DEFAULT '0',
  `date_entered` datetime DEFAULT NULL,
  `date_modified` datetime DEFAULT NULL,
  `assigned_user_id` char(36) DEFAULT NULL,
  `contents` longtext
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `user_preferences`
--

INSERT INTO `user_preferences` (`id`, `category`, `deleted`, `date_entered`, `date_modified`, `assigned_user_id`, `contents`) VALUES
('155b7177-288a-7245-7b13-5e830d4a0b09', 'Home2_ACCOUNT', 0, '2020-07-04 08:15:47', '2020-07-04 08:15:47', '1', 'YToxOntzOjEzOiJsaXN0dmlld09yZGVyIjthOjI6e3M6Nzoib3JkZXJCeSI7czoxMjoiZGF0ZV9lbnRlcmVkIjtzOjk6InNvcnRPcmRlciI7czo0OiJERVNDIjt9fQ=='),
('1ee9b8e2-b50e-6aab-f99b-5f003c6d5518', 'Contacts2_CONTACT', 0, '2020-07-04 08:21:27', '2020-07-04 08:21:27', '2', 'YToxOntzOjEzOiJsaXN0dmlld09yZGVyIjthOjI6e3M6Nzoib3JkZXJCeSI7czoxMjoiZGF0ZV9lbnRlcmVkIjtzOjk6InNvcnRPcmRlciI7czo0OiJERVNDIjt9fQ=='),
('20c9ad4f-9a92-cad7-0f84-5e830d878db8', 'Home2_SUGARFEED', 0, '2020-07-04 08:15:47', '2020-07-04 08:15:47', '1', 'YToxOntzOjEzOiJsaXN0dmlld09yZGVyIjthOjI6e3M6Nzoib3JkZXJCeSI7czoxMjoiZGF0ZV9lbnRlcmVkIjtzOjk6InNvcnRPcmRlciI7czo0OiJERVNDIjt9fQ=='),
('43761964-24aa-3561-7305-5e830d73a842', 'global', 0, '2020-07-04 08:15:47', '2020-07-04 08:15:47', '1', 'YTo0Mjp7czoyMDoiY2FsZW5kYXJfcHVibGlzaF9rZXkiO3M6MzY6IjNmZjI3OGY1LWYxY2MtMTJhNy1mYTU3LTVlODMwZGM0YjE4MSI7czoxMjoibWFpbG1lcmdlX29uIjtzOjI6Im9uIjtzOjE2OiJzd2FwX2xhc3Rfdmlld2VkIjtzOjA6IiI7czoxNDoic3dhcF9zaG9ydGN1dHMiO3M6MDoiIjtzOjE5OiJuYXZpZ2F0aW9uX3BhcmFkaWdtIjtzOjI6ImdtIjtzOjIwOiJzb3J0X21vZHVsZXNfYnlfbmFtZSI7czowOiIiO3M6MTM6InN1YnBhbmVsX3RhYnMiO3M6MDoiIjtzOjI1OiJjb3VudF9jb2xsYXBzZWRfc3VicGFuZWxzIjtzOjA6IiI7czoxNDoibW9kdWxlX2Zhdmljb24iO3M6MDoiIjtzOjk6ImhpZGVfdGFicyI7YTowOnt9czo3OiJub19vcHBzIjtzOjM6Im9mZiI7czo4OiJ0aW1lem9uZSI7czoxMzoiRXVyb3BlL01hZHJpZCI7czoyOiJ1dCI7aToxO3M6MTU6Im1haWxfc210cHNlcnZlciI7czowOiIiO3M6MTM6Im1haWxfc210cHBvcnQiO3M6MjoiMjUiO3M6MTM6Im1haWxfc210cHVzZXIiO3M6MDoiIjtzOjEzOiJtYWlsX3NtdHBwYXNzIjtzOjA6IiI7czoxNDoidXNlX3JlYWxfbmFtZXMiO3M6Mjoib24iO3M6MTc6Im1haWxfc210cGF1dGhfcmVxIjtzOjA6IiI7czoxMjoibWFpbF9zbXRwc3NsIjtpOjA7czoxNzoiZW1haWxfc2hvd19jb3VudHMiO2k6MDtzOjEwOiJ1c2VyX3RoZW1lIjtzOjY6IlN1aXRlUCI7czoxOToidGhlbWVfY3VycmVudF9ncm91cCI7czozOiJBbGwiO3M6NjoiVXNlcnNRIjthOjE6e3M6MTM6InNlYXJjaEZvcm1UYWIiO3M6MTI6ImJhc2ljX3NlYXJjaCI7fXM6MTE6ImVkaXRvcl90eXBlIjtzOjY6Im1vemFpayI7czoxMToicmVtb3ZlX3RhYnMiO2E6MDp7fXM6MTM6InJlbWluZGVyX3RpbWUiO3M6NDoiMTgwMCI7czoxOToiZW1haWxfcmVtaW5kZXJfdGltZSI7czoyOiI2MCI7czoxNjoicmVtaW5kZXJfY2hlY2tlZCI7czoxOiIxIjtzOjIyOiJlbWFpbF9yZW1pbmRlcl9jaGVja2VkIjtzOjE6IjAiO3M6ODoiY3VycmVuY3kiO3M6MzoiLTk5IjtzOjM1OiJkZWZhdWx0X2N1cnJlbmN5X3NpZ25pZmljYW50X2RpZ2l0cyI7czoxOiIyIjtzOjExOiJudW1fZ3JwX3NlcCI7czoxOiIuIjtzOjc6ImRlY19zZXAiO3M6MToiLCI7czo0OiJmZG93IjtzOjE6IjEiO3M6NToiZGF0ZWYiO3M6NToibS9kL1kiO3M6NToidGltZWYiO3M6MzoiSDppIjtzOjI2OiJkZWZhdWx0X2xvY2FsZV9uYW1lX2Zvcm1hdCI7czozOiJmIGwiO3M6MTY6ImV4cG9ydF9kZWxpbWl0ZXIiO3M6MToiLCI7czoyMjoiZGVmYXVsdF9leHBvcnRfY2hhcnNldCI7czo1OiJVVEYtOCI7czoxNToiZW1haWxfbGlua190eXBlIjtzOjU6InN1Z2FyIjtzOjg6InN1YnRoZW1lIjtzOjQ6IkRhd24iO30='),
('45171795-0c06-e65d-897c-5f003ba79406', 'Home', 0, '2020-07-04 08:20:58', '2020-07-04 08:20:58', '2', 'YToyOntzOjg6ImRhc2hsZXRzIjthOjY6e3M6MzY6ImRkOTg5NDhlLWI0YmMtMTI4Yi1hYzMwLTVmMDAzYjY5ZWY3NyI7YTo0OntzOjk6ImNsYXNzTmFtZSI7czoxNjoiU3VnYXJGZWVkRGFzaGxldCI7czo2OiJtb2R1bGUiO3M6OToiU3VnYXJGZWVkIjtzOjExOiJmb3JjZUNvbHVtbiI7aToxO3M6MTI6ImZpbGVMb2NhdGlvbiI7czo2NDoibW9kdWxlcy9TdWdhckZlZWQvRGFzaGxldHMvU3VnYXJGZWVkRGFzaGxldC9TdWdhckZlZWREYXNobGV0LnBocCI7fXM6MzY6ImRmMzNmNTYxLWI1MjItMmI1MS04MTcxLTVmMDAzYjBkZGEwOSI7YTo1OntzOjk6ImNsYXNzTmFtZSI7czoxNDoiTXlDYWxsc0Rhc2hsZXQiO3M6NjoibW9kdWxlIjtzOjU6IkNhbGxzIjtzOjExOiJmb3JjZUNvbHVtbiI7aTowO3M6MTI6ImZpbGVMb2NhdGlvbiI7czo1NjoibW9kdWxlcy9DYWxscy9EYXNobGV0cy9NeUNhbGxzRGFzaGxldC9NeUNhbGxzRGFzaGxldC5waHAiO3M6Nzoib3B0aW9ucyI7YTowOnt9fXM6MzY6ImUxMWRlNWMyLTc1Y2QtMmNlYS1lZTFjLTVmMDAzYmM2ZDFiYyI7YTo1OntzOjk6ImNsYXNzTmFtZSI7czoxNzoiTXlNZWV0aW5nc0Rhc2hsZXQiO3M6NjoibW9kdWxlIjtzOjg6Ik1lZXRpbmdzIjtzOjExOiJmb3JjZUNvbHVtbiI7aTowO3M6MTI6ImZpbGVMb2NhdGlvbiI7czo2NToibW9kdWxlcy9NZWV0aW5ncy9EYXNobGV0cy9NeU1lZXRpbmdzRGFzaGxldC9NeU1lZXRpbmdzRGFzaGxldC5waHAiO3M6Nzoib3B0aW9ucyI7YTowOnt9fXM6MzY6ImUyNjdhZGRlLTJhOGUtNDQ4NC00ZGM5LTVmMDAzYmRiOTNkNSI7YTo1OntzOjk6ImNsYXNzTmFtZSI7czoyMjoiTXlPcHBvcnR1bml0aWVzRGFzaGxldCI7czo2OiJtb2R1bGUiO3M6MTM6Ik9wcG9ydHVuaXRpZXMiO3M6MTE6ImZvcmNlQ29sdW1uIjtpOjA7czoxMjoiZmlsZUxvY2F0aW9uIjtzOjgwOiJtb2R1bGVzL09wcG9ydHVuaXRpZXMvRGFzaGxldHMvTXlPcHBvcnR1bml0aWVzRGFzaGxldC9NeU9wcG9ydHVuaXRpZXNEYXNobGV0LnBocCI7czo3OiJvcHRpb25zIjthOjA6e319czozNjoiZTNmYWU4MDMtMzNkOS1mNGE0LTA0ZTItNWYwMDNiODZkZjM3IjthOjU6e3M6OToiY2xhc3NOYW1lIjtzOjE3OiJNeUFjY291bnRzRGFzaGxldCI7czo2OiJtb2R1bGUiO3M6ODoiQWNjb3VudHMiO3M6MTE6ImZvcmNlQ29sdW1uIjtpOjA7czoxMjoiZmlsZUxvY2F0aW9uIjtzOjY1OiJtb2R1bGVzL0FjY291bnRzL0Rhc2hsZXRzL015QWNjb3VudHNEYXNobGV0L015QWNjb3VudHNEYXNobGV0LnBocCI7czo3OiJvcHRpb25zIjthOjA6e319czozNjoiZTU5ZjU1MjgtNjE5Mi1kNGYzLWFmYTktNWYwMDNiZjhiZDlhIjthOjU6e3M6OToiY2xhc3NOYW1lIjtzOjE0OiJNeUxlYWRzRGFzaGxldCI7czo2OiJtb2R1bGUiO3M6NToiTGVhZHMiO3M6MTE6ImZvcmNlQ29sdW1uIjtpOjA7czoxMjoiZmlsZUxvY2F0aW9uIjtzOjU2OiJtb2R1bGVzL0xlYWRzL0Rhc2hsZXRzL015TGVhZHNEYXNobGV0L015TGVhZHNEYXNobGV0LnBocCI7czo3OiJvcHRpb25zIjthOjA6e319fXM6NToicGFnZXMiO2E6MTp7aTowO2E6Mzp7czo3OiJjb2x1bW5zIjthOjI6e2k6MDthOjI6e3M6NToid2lkdGgiO3M6MzoiNjAlIjtzOjg6ImRhc2hsZXRzIjthOjU6e2k6MDtzOjM2OiJkZjMzZjU2MS1iNTIyLTJiNTEtODE3MS01ZjAwM2IwZGRhMDkiO2k6MTtzOjM2OiJlMTFkZTVjMi03NWNkLTJjZWEtZWUxYy01ZjAwM2JjNmQxYmMiO2k6MjtzOjM2OiJlMjY3YWRkZS0yYThlLTQ0ODQtNGRjOS01ZjAwM2JkYjkzZDUiO2k6MztzOjM2OiJlM2ZhZTgwMy0zM2Q5LWY0YTQtMDRlMi01ZjAwM2I4NmRmMzciO2k6NDtzOjM2OiJlNTlmNTUyOC02MTkyLWQ0ZjMtYWZhOS01ZjAwM2JmOGJkOWEiO319aToxO2E6Mjp7czo1OiJ3aWR0aCI7czozOiI0MCUiO3M6ODoiZGFzaGxldHMiO2E6MTp7aTowO3M6MzY6ImRkOTg5NDhlLWI0YmMtMTI4Yi1hYzMwLTVmMDAzYjY5ZWY3NyI7fX19czoxMDoibnVtQ29sdW1ucyI7czoxOiIzIjtzOjE0OiJwYWdlVGl0bGVMYWJlbCI7czoyMDoiTEJMX0hPTUVfUEFHRV8xX05BTUUiO319fQ=='),
('4742a327-64e0-2aee-71b2-5e830d65f18c', 'GoogleSync', 0, '2020-07-04 08:15:47', '2020-07-04 08:15:47', '1', 'YToxOntzOjg6InN5bmNHQ2FsIjtpOjA7fQ=='),
('4ac283ea-ab66-d83f-12d2-5f003b8c5bc5', 'Home2_CALL', 0, '2020-07-04 08:20:58', '2020-07-04 08:20:58', '2', 'YToxOntzOjEzOiJsaXN0dmlld09yZGVyIjthOjI6e3M6Nzoib3JkZXJCeSI7czoxMjoiZGF0ZV9lbnRlcmVkIjtzOjk6InNvcnRPcmRlciI7czo0OiJERVNDIjt9fQ=='),
('4dbb0c53-89e5-5d35-c68a-5f003b70f074', 'Home2_MEETING', 0, '2020-07-04 08:20:58', '2020-07-04 08:20:58', '2', 'YToxOntzOjEzOiJsaXN0dmlld09yZGVyIjthOjI6e3M6Nzoib3JkZXJCeSI7czoxMjoiZGF0ZV9lbnRlcmVkIjtzOjk6InNvcnRPcmRlciI7czo0OiJERVNDIjt9fQ=='),
('50a5b9b5-aec8-9fc2-77eb-5f003b1b2f49', 'Home2_OPPORTUNITY', 0, '2020-07-04 08:20:58', '2020-07-04 08:20:58', '2', 'YToxOntzOjEzOiJsaXN0dmlld09yZGVyIjthOjI6e3M6Nzoib3JkZXJCeSI7czoxMjoiZGF0ZV9lbnRlcmVkIjtzOjk6InNvcnRPcmRlciI7czo0OiJERVNDIjt9fQ=='),
('538f62f4-a663-b6aa-1e74-5f003bb4d296', 'Home2_ACCOUNT', 0, '2020-07-04 08:20:58', '2020-07-04 08:20:58', '2', 'YToxOntzOjEzOiJsaXN0dmlld09yZGVyIjthOjI6e3M6Nzoib3JkZXJCeSI7czoxMjoiZGF0ZV9lbnRlcmVkIjtzOjk6InNvcnRPcmRlciI7czo0OiJERVNDIjt9fQ=='),
('56961bcb-09e3-0ccf-2173-5f003bbf7452', 'Home2_LEAD', 0, '2020-07-04 08:20:58', '2020-07-04 08:20:58', '2', 'YToxOntzOjEzOiJsaXN0dmlld09yZGVyIjthOjI6e3M6Nzoib3JkZXJCeSI7czoxMjoiZGF0ZV9lbnRlcmVkIjtzOjk6InNvcnRPcmRlciI7czo0OiJERVNDIjt9fQ=='),
('598feec8-3d15-57f4-fb76-5f003b20ce3b', 'Home2_SUGARFEED', 0, '2020-07-04 08:20:58', '2020-07-04 08:20:58', '2', 'YToxOntzOjEzOiJsaXN0dmlld09yZGVyIjthOjI6e3M6Nzoib3JkZXJCeSI7czoxMjoiZGF0ZV9lbnRlcmVkIjtzOjk6InNvcnRPcmRlciI7czo0OiJERVNDIjt9fQ=='),
('83150dfb-e163-f289-f767-5e830d743394', 'Home2_MEETING', 0, '2020-07-04 08:15:47', '2020-07-04 08:15:47', '1', 'YToxOntzOjEzOiJsaXN0dmlld09yZGVyIjthOjI6e3M6Nzoib3JkZXJCeSI7czoxMjoiZGF0ZV9lbnRlcmVkIjtzOjk6InNvcnRPcmRlciI7czo0OiJERVNDIjt9fQ=='),
('b43735e5-9521-ce20-fab5-5e830d8b62ae', 'Users2_USER', 0, '2020-07-04 08:15:47', '2020-07-04 08:15:47', '1', 'YToxOntzOjEzOiJsaXN0dmlld09yZGVyIjthOjI6e3M6Nzoib3JkZXJCeSI7czoxMjoiZGF0ZV9lbnRlcmVkIjtzOjk6InNvcnRPcmRlciI7czo0OiJERVNDIjt9fQ=='),
('d617af4e-023b-b3f3-9eb0-5f003b28ccb8', 'global', 0, '2020-07-04 08:18:01', '2020-07-04 08:21:27', '2', 'YTo4OntzOjEwOiJ1c2VyX3RoZW1lIjtzOjY6IlN1aXRlUCI7czoxMzoicmVtaW5kZXJfdGltZSI7aToxODAwO3M6MTk6ImVtYWlsX3JlbWluZGVyX3RpbWUiO2k6MzYwMDtzOjEyOiJtYWlsbWVyZ2Vfb24iO3M6Mjoib24iO3M6ODoidGltZXpvbmUiO3M6MTY6IkV1cm9wZS9BbXN0ZXJkYW0iO3M6MTk6InRoZW1lX2N1cnJlbnRfZ3JvdXAiO3M6MzoiQWxsIjtzOjI6InV0IjtpOjE7czo5OiJDb250YWN0c1EiO2E6MTp7czoxMzoic2VhcmNoRm9ybVRhYiI7czoxMjoiYmFzaWNfc2VhcmNoIjt9fQ=='),
('e1e760ee-1e99-8450-cec1-5e830d80164b', 'Home', 0, '2020-07-04 08:15:47', '2020-07-04 08:15:47', '1', 'YToyOntzOjg6ImRhc2hsZXRzIjthOjQ6e3M6MzY6IjI4YzJiOGE0LTQ3ZjEtYjgxYy0zZTI4LTVlODMwZDY4YTZhMyI7YTo0OntzOjk6ImNsYXNzTmFtZSI7czoxNjoiU3VnYXJGZWVkRGFzaGxldCI7czo2OiJtb2R1bGUiO3M6OToiU3VnYXJGZWVkIjtzOjExOiJmb3JjZUNvbHVtbiI7aToxO3M6MTI6ImZpbGVMb2NhdGlvbiI7czo2NDoibW9kdWxlcy9TdWdhckZlZWQvRGFzaGxldHMvU3VnYXJGZWVkRGFzaGxldC9TdWdhckZlZWREYXNobGV0LnBocCI7fXM6MzY6IjJhMTI1ZTgzLTk2MjctNmFlNy02MTE3LTVlODMwZDFjMDU2MiI7YTo1OntzOjk6ImNsYXNzTmFtZSI7czoxNDoiTXlDYWxsc0Rhc2hsZXQiO3M6NjoibW9kdWxlIjtzOjU6IkNhbGxzIjtzOjExOiJmb3JjZUNvbHVtbiI7aTowO3M6MTI6ImZpbGVMb2NhdGlvbiI7czo1NjoibW9kdWxlcy9DYWxscy9EYXNobGV0cy9NeUNhbGxzRGFzaGxldC9NeUNhbGxzRGFzaGxldC5waHAiO3M6Nzoib3B0aW9ucyI7YTowOnt9fXM6MzY6IjJiYjYzOTVlLWQ5OTktMDc1MS00MzE1LTVlODMwZDA2NTE0NiI7YTo1OntzOjk6ImNsYXNzTmFtZSI7czoxNzoiTXlNZWV0aW5nc0Rhc2hsZXQiO3M6NjoibW9kdWxlIjtzOjg6Ik1lZXRpbmdzIjtzOjExOiJmb3JjZUNvbHVtbiI7aTowO3M6MTI6ImZpbGVMb2NhdGlvbiI7czo2NToibW9kdWxlcy9NZWV0aW5ncy9EYXNobGV0cy9NeU1lZXRpbmdzRGFzaGxldC9NeU1lZXRpbmdzRGFzaGxldC5waHAiO3M6Nzoib3B0aW9ucyI7YTowOnt9fXM6MzY6IjJjODk1ZTNlLWRmYzQtOTlkZS0wNmY4LTVlODMwZDRmZjJlYiI7YTo1OntzOjk6ImNsYXNzTmFtZSI7czoxNzoiTXlBY2NvdW50c0Rhc2hsZXQiO3M6NjoibW9kdWxlIjtzOjg6IkFjY291bnRzIjtzOjExOiJmb3JjZUNvbHVtbiI7aTowO3M6MTI6ImZpbGVMb2NhdGlvbiI7czo2NToibW9kdWxlcy9BY2NvdW50cy9EYXNobGV0cy9NeUFjY291bnRzRGFzaGxldC9NeUFjY291bnRzRGFzaGxldC5waHAiO3M6Nzoib3B0aW9ucyI7YTowOnt9fX1zOjU6InBhZ2VzIjthOjE6e2k6MDthOjM6e3M6NzoiY29sdW1ucyI7YToyOntpOjA7YToyOntzOjU6IndpZHRoIjtzOjM6IjYwJSI7czo4OiJkYXNobGV0cyI7YTozOntpOjA7czozNjoiMmExMjVlODMtOTYyNy02YWU3LTYxMTctNWU4MzBkMWMwNTYyIjtpOjE7czozNjoiMmJiNjM5NWUtZDk5OS0wNzUxLTQzMTUtNWU4MzBkMDY1MTQ2IjtpOjI7czozNjoiMmM4OTVlM2UtZGZjNC05OWRlLTA2ZjgtNWU4MzBkNGZmMmViIjt9fWk6MTthOjI6e3M6NToid2lkdGgiO3M6MzoiNDAlIjtzOjg6ImRhc2hsZXRzIjthOjE6e2k6MDtzOjM2OiIyOGMyYjhhNC00N2YxLWI4MWMtM2UyOC01ZTgzMGQ2OGE2YTMiO319fXM6MTA6Im51bUNvbHVtbnMiO3M6MToiMyI7czoxNDoicGFnZVRpdGxlTGFiZWwiO3M6MjA6IkxCTF9IT01FX1BBR0VfMV9OQU1FIjt9fX0='),
('f0d77a6e-084f-e44b-0f83-5e830d9236b4', 'Home2_CALL', 0, '2020-07-04 08:15:47', '2020-07-04 08:15:47', '1', 'YToxOntzOjEzOiJsaXN0dmlld09yZGVyIjthOjI6e3M6Nzoib3JkZXJCeSI7czoxMjoiZGF0ZV9lbnRlcmVkIjtzOjk6InNvcnRPcmRlciI7czo0OiJERVNDIjt9fQ=='),
('f40fe546-9ad6-8479-fd93-5f003b35b82b', 'Home2_LEAD_e59f5528-6192-d4f3-afa9-5f003bf8bd9a', 0, '2020-07-04 08:21:00', '2020-07-04 08:21:00', '2', 'YToxOntzOjEzOiJsaXN0dmlld09yZGVyIjthOjI6e3M6Nzoib3JkZXJCeSI7czoxMjoiZGF0ZV9lbnRlcmVkIjtzOjk6InNvcnRPcmRlciI7czo0OiJERVNDIjt9fQ==');

-- --------------------------------------------------------

--
-- Table structure for table `vcals`
--

CREATE TABLE IF NOT EXISTS `vcals` (
  `id` char(36) NOT NULL,
  `deleted` tinyint(1) DEFAULT NULL,
  `date_entered` datetime DEFAULT NULL,
  `date_modified` datetime DEFAULT NULL,
  `user_id` char(36) NOT NULL,
  `type` varchar(100) DEFAULT NULL,
  `source` varchar(100) DEFAULT NULL,
  `content` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accounts`
--
ALTER TABLE `accounts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_accnt_id_del` (`id`,`deleted`),
  ADD KEY `idx_accnt_name_del` (`name`,`deleted`),
  ADD KEY `idx_accnt_assigned_del` (`deleted`,`assigned_user_id`),
  ADD KEY `idx_accnt_parent_id` (`parent_id`);

--
-- Indexes for table `accounts_audit`
--
ALTER TABLE `accounts_audit`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_accounts_parent_id` (`parent_id`);

--
-- Indexes for table `accounts_bugs`
--
ALTER TABLE `accounts_bugs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_acc_bug_acc` (`account_id`),
  ADD KEY `idx_acc_bug_bug` (`bug_id`),
  ADD KEY `idx_account_bug` (`account_id`,`bug_id`);

--
-- Indexes for table `accounts_cases`
--
ALTER TABLE `accounts_cases`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_acc_case_acc` (`account_id`),
  ADD KEY `idx_acc_acc_case` (`case_id`);

--
-- Indexes for table `accounts_contacts`
--
ALTER TABLE `accounts_contacts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_account_contact` (`account_id`,`contact_id`),
  ADD KEY `idx_contid_del_accid` (`contact_id`,`deleted`,`account_id`);

--
-- Indexes for table `accounts_cstm`
--
ALTER TABLE `accounts_cstm`
  ADD PRIMARY KEY (`id_c`);

--
-- Indexes for table `accounts_opportunities`
--
ALTER TABLE `accounts_opportunities`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_account_opportunity` (`account_id`,`opportunity_id`),
  ADD KEY `idx_oppid_del_accid` (`opportunity_id`,`deleted`,`account_id`);

--
-- Indexes for table `accounts_opportunities_1_c`
--
ALTER TABLE `accounts_opportunities_1_c`
  ADD PRIMARY KEY (`id`),
  ADD KEY `accounts_opportunities_1_ida1` (`accounts_opportunities_1accounts_ida`),
  ADD KEY `accounts_opportunities_1_alt` (`accounts_opportunities_1opportunities_idb`);

--
-- Indexes for table `acl_actions`
--
ALTER TABLE `acl_actions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_aclaction_id_del` (`id`,`deleted`),
  ADD KEY `idx_category_name` (`category`,`name`);

--
-- Indexes for table `acl_roles`
--
ALTER TABLE `acl_roles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_aclrole_id_del` (`id`,`deleted`);

--
-- Indexes for table `acl_roles_actions`
--
ALTER TABLE `acl_roles_actions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_acl_role_id` (`role_id`),
  ADD KEY `idx_acl_action_id` (`action_id`),
  ADD KEY `idx_aclrole_action` (`role_id`,`action_id`);

--
-- Indexes for table `acl_roles_users`
--
ALTER TABLE `acl_roles_users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_aclrole_id` (`role_id`),
  ADD KEY `idx_acluser_id` (`user_id`),
  ADD KEY `idx_aclrole_user` (`role_id`,`user_id`);

--
-- Indexes for table `address_book`
--
ALTER TABLE `address_book`
  ADD KEY `ab_user_bean_idx` (`assigned_user_id`,`bean`);

--
-- Indexes for table `alerts`
--
ALTER TABLE `alerts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `am_projecttemplates`
--
ALTER TABLE `am_projecttemplates`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `am_projecttemplates_audit`
--
ALTER TABLE `am_projecttemplates_audit`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_am_projecttemplates_parent_id` (`parent_id`);

--
-- Indexes for table `am_projecttemplates_contacts_1_c`
--
ALTER TABLE `am_projecttemplates_contacts_1_c`
  ADD PRIMARY KEY (`id`),
  ADD KEY `am_projecttemplates_contacts_1_alt` (`am_projecttemplates_ida`,`contacts_idb`);

--
-- Indexes for table `am_projecttemplates_project_1_c`
--
ALTER TABLE `am_projecttemplates_project_1_c`
  ADD PRIMARY KEY (`id`),
  ADD KEY `am_projecttemplates_project_1_ida1` (`am_projecttemplates_project_1am_projecttemplates_ida`),
  ADD KEY `am_projecttemplates_project_1_alt` (`am_projecttemplates_project_1project_idb`);

--
-- Indexes for table `am_projecttemplates_users_1_c`
--
ALTER TABLE `am_projecttemplates_users_1_c`
  ADD PRIMARY KEY (`id`),
  ADD KEY `am_projecttemplates_users_1_alt` (`am_projecttemplates_ida`,`users_idb`);

--
-- Indexes for table `am_tasktemplates`
--
ALTER TABLE `am_tasktemplates`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `am_tasktemplates_am_projecttemplates_c`
--
ALTER TABLE `am_tasktemplates_am_projecttemplates_c`
  ADD PRIMARY KEY (`id`),
  ADD KEY `am_tasktemplates_am_projecttemplates_ida1` (`am_tasktemplates_am_projecttemplatesam_projecttemplates_ida`),
  ADD KEY `am_tasktemplates_am_projecttemplates_alt` (`am_tasktemplates_am_projecttemplatesam_tasktemplates_idb`);

--
-- Indexes for table `am_tasktemplates_audit`
--
ALTER TABLE `am_tasktemplates_audit`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_am_tasktemplates_parent_id` (`parent_id`);

--
-- Indexes for table `aobh_businesshours`
--
ALTER TABLE `aobh_businesshours`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `aod_index`
--
ALTER TABLE `aod_index`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `aod_indexevent`
--
ALTER TABLE `aod_indexevent`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_record_module` (`record_module`),
  ADD KEY `idx_record_id` (`record_id`);

--
-- Indexes for table `aod_indexevent_audit`
--
ALTER TABLE `aod_indexevent_audit`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_aod_indexevent_parent_id` (`parent_id`);

--
-- Indexes for table `aod_index_audit`
--
ALTER TABLE `aod_index_audit`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_aod_index_parent_id` (`parent_id`);

--
-- Indexes for table `aok_knowledgebase`
--
ALTER TABLE `aok_knowledgebase`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `aok_knowledgebase_audit`
--
ALTER TABLE `aok_knowledgebase_audit`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_aok_knowledgebase_parent_id` (`parent_id`);

--
-- Indexes for table `aok_knowledgebase_categories`
--
ALTER TABLE `aok_knowledgebase_categories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `aok_knowledgebase_categories_alt` (`aok_knowledgebase_id`,`aok_knowledge_base_categories_id`);

--
-- Indexes for table `aok_knowledge_base_categories`
--
ALTER TABLE `aok_knowledge_base_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `aok_knowledge_base_categories_audit`
--
ALTER TABLE `aok_knowledge_base_categories_audit`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_aok_knowledge_base_categories_parent_id` (`parent_id`);

--
-- Indexes for table `aop_case_events`
--
ALTER TABLE `aop_case_events`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `aop_case_events_audit`
--
ALTER TABLE `aop_case_events_audit`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_aop_case_events_parent_id` (`parent_id`);

--
-- Indexes for table `aop_case_updates`
--
ALTER TABLE `aop_case_updates`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `aop_case_updates_audit`
--
ALTER TABLE `aop_case_updates_audit`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_aop_case_updates_parent_id` (`parent_id`);

--
-- Indexes for table `aor_charts`
--
ALTER TABLE `aor_charts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `aor_conditions`
--
ALTER TABLE `aor_conditions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `aor_conditions_index_report_id` (`aor_report_id`);

--
-- Indexes for table `aor_fields`
--
ALTER TABLE `aor_fields`
  ADD PRIMARY KEY (`id`),
  ADD KEY `aor_fields_index_report_id` (`aor_report_id`);

--
-- Indexes for table `aor_reports`
--
ALTER TABLE `aor_reports`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `aor_reports_audit`
--
ALTER TABLE `aor_reports_audit`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_aor_reports_parent_id` (`parent_id`);

--
-- Indexes for table `aor_scheduled_reports`
--
ALTER TABLE `aor_scheduled_reports`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `aos_contracts`
--
ALTER TABLE `aos_contracts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `aos_contracts_audit`
--
ALTER TABLE `aos_contracts_audit`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_aos_contracts_parent_id` (`parent_id`);

--
-- Indexes for table `aos_contracts_documents`
--
ALTER TABLE `aos_contracts_documents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `aos_contracts_documents_alt` (`aos_contracts_id`,`documents_id`);

--
-- Indexes for table `aos_invoices`
--
ALTER TABLE `aos_invoices`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `aos_invoices_audit`
--
ALTER TABLE `aos_invoices_audit`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_aos_invoices_parent_id` (`parent_id`);

--
-- Indexes for table `aos_line_item_groups`
--
ALTER TABLE `aos_line_item_groups`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `aos_line_item_groups_audit`
--
ALTER TABLE `aos_line_item_groups_audit`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_aos_line_item_groups_parent_id` (`parent_id`);

--
-- Indexes for table `aos_pdf_templates`
--
ALTER TABLE `aos_pdf_templates`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `aos_pdf_templates_audit`
--
ALTER TABLE `aos_pdf_templates_audit`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_aos_pdf_templates_parent_id` (`parent_id`);

--
-- Indexes for table `aos_products`
--
ALTER TABLE `aos_products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `aos_products_audit`
--
ALTER TABLE `aos_products_audit`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_aos_products_parent_id` (`parent_id`);

--
-- Indexes for table `aos_products_quotes`
--
ALTER TABLE `aos_products_quotes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_aospq_par_del` (`parent_id`,`parent_type`,`deleted`);

--
-- Indexes for table `aos_products_quotes_audit`
--
ALTER TABLE `aos_products_quotes_audit`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_aos_products_quotes_parent_id` (`parent_id`);

--
-- Indexes for table `aos_product_categories`
--
ALTER TABLE `aos_product_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `aos_product_categories_audit`
--
ALTER TABLE `aos_product_categories_audit`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_aos_product_categories_parent_id` (`parent_id`);

--
-- Indexes for table `aos_quotes`
--
ALTER TABLE `aos_quotes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `aos_quotes_aos_invoices_c`
--
ALTER TABLE `aos_quotes_aos_invoices_c`
  ADD PRIMARY KEY (`id`),
  ADD KEY `aos_quotes_aos_invoices_alt` (`aos_quotes77d9_quotes_ida`,`aos_quotes6b83nvoices_idb`);

--
-- Indexes for table `aos_quotes_audit`
--
ALTER TABLE `aos_quotes_audit`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_aos_quotes_parent_id` (`parent_id`);

--
-- Indexes for table `aos_quotes_os_contracts_c`
--
ALTER TABLE `aos_quotes_os_contracts_c`
  ADD PRIMARY KEY (`id`),
  ADD KEY `aos_quotes_aos_contracts_alt` (`aos_quotese81e_quotes_ida`,`aos_quotes4dc0ntracts_idb`);

--
-- Indexes for table `aos_quotes_project_c`
--
ALTER TABLE `aos_quotes_project_c`
  ADD PRIMARY KEY (`id`),
  ADD KEY `aos_quotes_project_alt` (`aos_quotes1112_quotes_ida`,`aos_quotes7207project_idb`);

--
-- Indexes for table `aow_actions`
--
ALTER TABLE `aow_actions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `aow_action_index_workflow_id` (`aow_workflow_id`);

--
-- Indexes for table `aow_conditions`
--
ALTER TABLE `aow_conditions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `aow_conditions_index_workflow_id` (`aow_workflow_id`);

--
-- Indexes for table `aow_processed`
--
ALTER TABLE `aow_processed`
  ADD PRIMARY KEY (`id`),
  ADD KEY `aow_processed_index_workflow` (`aow_workflow_id`,`status`,`parent_id`,`deleted`),
  ADD KEY `aow_processed_index_status` (`status`),
  ADD KEY `aow_processed_index_workflow_id` (`aow_workflow_id`);

--
-- Indexes for table `aow_processed_aow_actions`
--
ALTER TABLE `aow_processed_aow_actions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_aow_processed_aow_actions` (`aow_processed_id`,`aow_action_id`),
  ADD KEY `idx_actid_del_freid` (`aow_action_id`,`deleted`,`aow_processed_id`);

--
-- Indexes for table `aow_workflow`
--
ALTER TABLE `aow_workflow`
  ADD PRIMARY KEY (`id`),
  ADD KEY `aow_workflow_index_status` (`status`);

--
-- Indexes for table `aow_workflow_audit`
--
ALTER TABLE `aow_workflow_audit`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_aow_workflow_parent_id` (`parent_id`);

--
-- Indexes for table `bugs`
--
ALTER TABLE `bugs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `bugsnumk` (`bug_number`),
  ADD KEY `bug_number` (`bug_number`),
  ADD KEY `idx_bug_name` (`name`),
  ADD KEY `idx_bugs_assigned_user` (`assigned_user_id`);

--
-- Indexes for table `bugs_audit`
--
ALTER TABLE `bugs_audit`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_bugs_parent_id` (`parent_id`);

--
-- Indexes for table `calls`
--
ALTER TABLE `calls`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_call_name` (`name`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_calls_date_start` (`date_start`),
  ADD KEY `idx_calls_par_del` (`parent_id`,`parent_type`,`deleted`),
  ADD KEY `idx_calls_assigned_del` (`deleted`,`assigned_user_id`);

--
-- Indexes for table `calls_contacts`
--
ALTER TABLE `calls_contacts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_con_call_call` (`call_id`),
  ADD KEY `idx_con_call_con` (`contact_id`),
  ADD KEY `idx_call_contact` (`call_id`,`contact_id`);

--
-- Indexes for table `calls_leads`
--
ALTER TABLE `calls_leads`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_lead_call_call` (`call_id`),
  ADD KEY `idx_lead_call_lead` (`lead_id`),
  ADD KEY `idx_call_lead` (`call_id`,`lead_id`);

--
-- Indexes for table `calls_reschedule`
--
ALTER TABLE `calls_reschedule`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `calls_reschedule_audit`
--
ALTER TABLE `calls_reschedule_audit`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_calls_reschedule_parent_id` (`parent_id`);

--
-- Indexes for table `calls_users`
--
ALTER TABLE `calls_users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_usr_call_call` (`call_id`),
  ADD KEY `idx_usr_call_usr` (`user_id`),
  ADD KEY `idx_call_users` (`call_id`,`user_id`);

--
-- Indexes for table `campaigns`
--
ALTER TABLE `campaigns`
  ADD PRIMARY KEY (`id`),
  ADD KEY `camp_auto_tracker_key` (`tracker_key`),
  ADD KEY `idx_campaign_name` (`name`),
  ADD KEY `idx_survey_id` (`survey_id`);

--
-- Indexes for table `campaigns_audit`
--
ALTER TABLE `campaigns_audit`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_campaigns_parent_id` (`parent_id`);

--
-- Indexes for table `campaign_log`
--
ALTER TABLE `campaign_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_camp_tracker` (`target_tracker_key`),
  ADD KEY `idx_camp_campaign_id` (`campaign_id`),
  ADD KEY `idx_camp_more_info` (`more_information`),
  ADD KEY `idx_target_id` (`target_id`),
  ADD KEY `idx_target_id_deleted` (`target_id`,`deleted`);

--
-- Indexes for table `campaign_trkrs`
--
ALTER TABLE `campaign_trkrs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `campaign_tracker_key_idx` (`tracker_key`);

--
-- Indexes for table `cases`
--
ALTER TABLE `cases`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `casesnumk` (`case_number`),
  ADD KEY `case_number` (`case_number`),
  ADD KEY `idx_case_name` (`name`),
  ADD KEY `idx_account_id` (`account_id`),
  ADD KEY `idx_cases_stat_del` (`assigned_user_id`,`status`,`deleted`);

--
-- Indexes for table `cases_audit`
--
ALTER TABLE `cases_audit`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_cases_parent_id` (`parent_id`);

--
-- Indexes for table `cases_bugs`
--
ALTER TABLE `cases_bugs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_cas_bug_cas` (`case_id`),
  ADD KEY `idx_cas_bug_bug` (`bug_id`),
  ADD KEY `idx_case_bug` (`case_id`,`bug_id`);

--
-- Indexes for table `cases_cstm`
--
ALTER TABLE `cases_cstm`
  ADD PRIMARY KEY (`id_c`);

--
-- Indexes for table `config`
--
ALTER TABLE `config`
  ADD KEY `idx_config_cat` (`category`);

--
-- Indexes for table `contacts`
--
ALTER TABLE `contacts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_cont_last_first` (`last_name`,`first_name`,`deleted`),
  ADD KEY `idx_contacts_del_last` (`deleted`,`last_name`),
  ADD KEY `idx_cont_del_reports` (`deleted`,`reports_to_id`,`last_name`),
  ADD KEY `idx_reports_to_id` (`reports_to_id`),
  ADD KEY `idx_del_id_user` (`deleted`,`id`,`assigned_user_id`),
  ADD KEY `idx_cont_assigned` (`assigned_user_id`);

--
-- Indexes for table `contacts_audit`
--
ALTER TABLE `contacts_audit`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_contacts_parent_id` (`parent_id`);

--
-- Indexes for table `contacts_bugs`
--
ALTER TABLE `contacts_bugs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_con_bug_con` (`contact_id`),
  ADD KEY `idx_con_bug_bug` (`bug_id`),
  ADD KEY `idx_contact_bug` (`contact_id`,`bug_id`);

--
-- Indexes for table `contacts_cases`
--
ALTER TABLE `contacts_cases`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_con_case_con` (`contact_id`),
  ADD KEY `idx_con_case_case` (`case_id`),
  ADD KEY `idx_contacts_cases` (`contact_id`,`case_id`);

--
-- Indexes for table `contacts_cstm`
--
ALTER TABLE `contacts_cstm`
  ADD PRIMARY KEY (`id_c`);

--
-- Indexes for table `contacts_users`
--
ALTER TABLE `contacts_users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_con_users_con` (`contact_id`),
  ADD KEY `idx_con_users_user` (`user_id`),
  ADD KEY `idx_contacts_users` (`contact_id`,`user_id`);

--
-- Indexes for table `cron_remove_documents`
--
ALTER TABLE `cron_remove_documents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_cron_remove_document_bean_id` (`bean_id`),
  ADD KEY `idx_cron_remove_document_stamp` (`date_modified`);

--
-- Indexes for table `currencies`
--
ALTER TABLE `currencies`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_currency_name` (`name`,`deleted`);

--
-- Indexes for table `custom_fields`
--
ALTER TABLE `custom_fields`
  ADD KEY `idx_beanid_set_num` (`bean_id`,`set_num`);

--
-- Indexes for table `dha_plantillasdocumentos`
--
ALTER TABLE `dha_plantillasdocumentos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_dhapd_id_del` (`id`,`deleted`),
  ADD KEY `idx_dhapd_name_del` (`document_name`,`deleted`),
  ADD KEY `idx_dhapd_mod_del` (`modulo`,`deleted`);

--
-- Indexes for table `dha_plantillasdocumentos_audit`
--
ALTER TABLE `dha_plantillasdocumentos_audit`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_dha_plantillasdocumentos_parent_id` (`parent_id`);

--
-- Indexes for table `documents`
--
ALTER TABLE `documents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_doc_cat` (`category_id`,`subcategory_id`);

--
-- Indexes for table `documents_accounts`
--
ALTER TABLE `documents_accounts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `documents_accounts_account_id` (`account_id`,`document_id`),
  ADD KEY `documents_accounts_document_id` (`document_id`,`account_id`);

--
-- Indexes for table `documents_bugs`
--
ALTER TABLE `documents_bugs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `documents_bugs_bug_id` (`bug_id`,`document_id`),
  ADD KEY `documents_bugs_document_id` (`document_id`,`bug_id`);

--
-- Indexes for table `documents_cases`
--
ALTER TABLE `documents_cases`
  ADD PRIMARY KEY (`id`),
  ADD KEY `documents_cases_case_id` (`case_id`,`document_id`),
  ADD KEY `documents_cases_document_id` (`document_id`,`case_id`);

--
-- Indexes for table `documents_contacts`
--
ALTER TABLE `documents_contacts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `documents_contacts_contact_id` (`contact_id`,`document_id`),
  ADD KEY `documents_contacts_document_id` (`document_id`,`contact_id`);

--
-- Indexes for table `documents_cstm`
--
ALTER TABLE `documents_cstm`
  ADD PRIMARY KEY (`id_c`);

--
-- Indexes for table `documents_opportunities`
--
ALTER TABLE `documents_opportunities`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_docu_opps_oppo_id` (`opportunity_id`,`document_id`),
  ADD KEY `idx_docu_oppo_docu_id` (`document_id`,`opportunity_id`);

--
-- Indexes for table `document_revisions`
--
ALTER TABLE `document_revisions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `documentrevision_mimetype` (`file_mime_type`);

--
-- Indexes for table `eapm`
--
ALTER TABLE `eapm`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_app_active` (`assigned_user_id`,`application`,`validated`);

--
-- Indexes for table `emailman`
--
ALTER TABLE `emailman`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_eman_list` (`list_id`,`user_id`,`deleted`),
  ADD KEY `idx_eman_campaign_id` (`campaign_id`),
  ADD KEY `idx_eman_relid_reltype_id` (`related_id`,`related_type`,`campaign_id`);

--
-- Indexes for table `emails`
--
ALTER TABLE `emails`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_email_name` (`name`),
  ADD KEY `idx_message_id` (`message_id`),
  ADD KEY `idx_email_parent_id` (`parent_id`),
  ADD KEY `idx_email_assigned` (`assigned_user_id`,`type`,`status`),
  ADD KEY `idx_email_cat` (`category_id`);

--
-- Indexes for table `emails_beans`
--
ALTER TABLE `emails_beans`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_emails_beans_bean_id` (`bean_id`),
  ADD KEY `idx_emails_beans_email_bean` (`email_id`,`bean_id`,`deleted`);

--
-- Indexes for table `emails_email_addr_rel`
--
ALTER TABLE `emails_email_addr_rel`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_eearl_email_id` (`email_id`,`address_type`),
  ADD KEY `idx_eearl_address_id` (`email_address_id`);

--
-- Indexes for table `emails_text`
--
ALTER TABLE `emails_text`
  ADD PRIMARY KEY (`email_id`),
  ADD KEY `emails_textfromaddr` (`from_addr`);

--
-- Indexes for table `email_addresses`
--
ALTER TABLE `email_addresses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_ea_caps_opt_out_invalid` (`email_address_caps`,`opt_out`,`invalid_email`),
  ADD KEY `idx_ea_opt_out_invalid` (`email_address`,`opt_out`,`invalid_email`);

--
-- Indexes for table `email_addresses_audit`
--
ALTER TABLE `email_addresses_audit`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_email_addresses_parent_id` (`parent_id`);

--
-- Indexes for table `email_addr_bean_rel`
--
ALTER TABLE `email_addr_bean_rel`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_email_address_id` (`email_address_id`),
  ADD KEY `idx_bean_id` (`bean_id`,`bean_module`);

--
-- Indexes for table `email_cache`
--
ALTER TABLE `email_cache`
  ADD KEY `idx_ie_id` (`ie_id`),
  ADD KEY `idx_mail_date` (`ie_id`,`mbox`,`senddate`),
  ADD KEY `idx_mail_from` (`ie_id`,`mbox`,`fromaddr`),
  ADD KEY `idx_mail_subj` (`subject`),
  ADD KEY `idx_mail_to` (`toaddr`);

--
-- Indexes for table `email_marketing`
--
ALTER TABLE `email_marketing`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_emmkt_name` (`name`),
  ADD KEY `idx_emmkit_del` (`deleted`);

--
-- Indexes for table `email_marketing_prospect_lists`
--
ALTER TABLE `email_marketing_prospect_lists`
  ADD PRIMARY KEY (`id`),
  ADD KEY `email_mp_prospects` (`email_marketing_id`,`prospect_list_id`);

--
-- Indexes for table `email_templates`
--
ALTER TABLE `email_templates`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_email_template_name` (`name`);

--
-- Indexes for table `favorites`
--
ALTER TABLE `favorites`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fields_meta_data`
--
ALTER TABLE `fields_meta_data`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_meta_id_del` (`id`,`deleted`),
  ADD KEY `idx_meta_cm_del` (`custom_module`,`deleted`);

--
-- Indexes for table `folders`
--
ALTER TABLE `folders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_parent_folder` (`parent_folder`);

--
-- Indexes for table `folders_rel`
--
ALTER TABLE `folders_rel`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_poly_module_poly_id` (`polymorphic_module`,`polymorphic_id`),
  ADD KEY `idx_fr_id_deleted_poly` (`folder_id`,`deleted`,`polymorphic_id`);

--
-- Indexes for table `folders_subscriptions`
--
ALTER TABLE `folders_subscriptions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_folder_id_assigned_user_id` (`folder_id`,`assigned_user_id`);

--
-- Indexes for table `fp_events`
--
ALTER TABLE `fp_events`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fp_events_audit`
--
ALTER TABLE `fp_events_audit`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_fp_events_parent_id` (`parent_id`);

--
-- Indexes for table `fp_events_contacts_c`
--
ALTER TABLE `fp_events_contacts_c`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fp_events_contacts_alt` (`fp_events_contactsfp_events_ida`,`fp_events_contactscontacts_idb`);

--
-- Indexes for table `fp_events_fp_event_delegates_1_c`
--
ALTER TABLE `fp_events_fp_event_delegates_1_c`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fp_events_fp_event_delegates_1_ida1` (`fp_events_fp_event_delegates_1fp_events_ida`),
  ADD KEY `fp_events_fp_event_delegates_1_alt` (`fp_events_fp_event_delegates_1fp_event_delegates_idb`);

--
-- Indexes for table `fp_events_fp_event_locations_1_c`
--
ALTER TABLE `fp_events_fp_event_locations_1_c`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fp_events_fp_event_locations_1_alt` (`fp_events_fp_event_locations_1fp_events_ida`,`fp_events_fp_event_locations_1fp_event_locations_idb`);

--
-- Indexes for table `fp_events_leads_1_c`
--
ALTER TABLE `fp_events_leads_1_c`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fp_events_leads_1_alt` (`fp_events_leads_1fp_events_ida`,`fp_events_leads_1leads_idb`);

--
-- Indexes for table `fp_events_prospects_1_c`
--
ALTER TABLE `fp_events_prospects_1_c`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fp_events_prospects_1_alt` (`fp_events_prospects_1fp_events_ida`,`fp_events_prospects_1prospects_idb`);

--
-- Indexes for table `fp_event_locations`
--
ALTER TABLE `fp_event_locations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fp_event_locations_audit`
--
ALTER TABLE `fp_event_locations_audit`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_fp_event_locations_parent_id` (`parent_id`);

--
-- Indexes for table `fp_event_locations_cstm`
--
ALTER TABLE `fp_event_locations_cstm`
  ADD PRIMARY KEY (`id_c`);

--
-- Indexes for table `fp_event_locations_fp_events_1_c`
--
ALTER TABLE `fp_event_locations_fp_events_1_c`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fp_event_locations_fp_events_1_ida1` (`fp_event_locations_fp_events_1fp_event_locations_ida`),
  ADD KEY `fp_event_locations_fp_events_1_alt` (`fp_event_locations_fp_events_1fp_events_idb`);

--
-- Indexes for table `import_maps`
--
ALTER TABLE `import_maps`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_owner_module_name` (`assigned_user_id`,`module`,`name`,`deleted`);

--
-- Indexes for table `inbound_email`
--
ALTER TABLE `inbound_email`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `inbound_email_autoreply`
--
ALTER TABLE `inbound_email_autoreply`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_ie_autoreplied_to` (`autoreplied_to`);

--
-- Indexes for table `inbound_email_cache_ts`
--
ALTER TABLE `inbound_email_cache_ts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jjwg_address_cache`
--
ALTER TABLE `jjwg_address_cache`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jjwg_address_cache_audit`
--
ALTER TABLE `jjwg_address_cache_audit`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_jjwg_address_cache_parent_id` (`parent_id`);

--
-- Indexes for table `jjwg_areas`
--
ALTER TABLE `jjwg_areas`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jjwg_areas_audit`
--
ALTER TABLE `jjwg_areas_audit`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_jjwg_areas_parent_id` (`parent_id`);

--
-- Indexes for table `jjwg_maps`
--
ALTER TABLE `jjwg_maps`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jjwg_maps_audit`
--
ALTER TABLE `jjwg_maps_audit`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_jjwg_maps_parent_id` (`parent_id`);

--
-- Indexes for table `jjwg_maps_jjwg_areas_c`
--
ALTER TABLE `jjwg_maps_jjwg_areas_c`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jjwg_maps_jjwg_areas_alt` (`jjwg_maps_5304wg_maps_ida`,`jjwg_maps_41f2g_areas_idb`);

--
-- Indexes for table `jjwg_maps_jjwg_markers_c`
--
ALTER TABLE `jjwg_maps_jjwg_markers_c`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jjwg_maps_jjwg_markers_alt` (`jjwg_maps_b229wg_maps_ida`,`jjwg_maps_2e31markers_idb`);

--
-- Indexes for table `jjwg_markers`
--
ALTER TABLE `jjwg_markers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jjwg_markers_audit`
--
ALTER TABLE `jjwg_markers_audit`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_jjwg_markers_parent_id` (`parent_id`);

--
-- Indexes for table `job_queue`
--
ALTER TABLE `job_queue`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_status_scheduler` (`status`,`scheduler_id`),
  ADD KEY `idx_status_time` (`status`,`execute_time`,`date_entered`),
  ADD KEY `idx_status_entered` (`status`,`date_entered`),
  ADD KEY `idx_status_modified` (`status`,`date_modified`);

--
-- Indexes for table `kreports`
--
ALTER TABLE `kreports`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_reminder_name` (`name`);

--
-- Indexes for table `leads`
--
ALTER TABLE `leads`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_lead_acct_name_first` (`account_name`,`deleted`),
  ADD KEY `idx_lead_last_first` (`last_name`,`first_name`,`deleted`),
  ADD KEY `idx_lead_del_stat` (`last_name`,`status`,`deleted`,`first_name`),
  ADD KEY `idx_lead_opp_del` (`opportunity_id`,`deleted`),
  ADD KEY `idx_leads_acct_del` (`account_id`,`deleted`),
  ADD KEY `idx_del_user` (`deleted`,`assigned_user_id`),
  ADD KEY `idx_lead_assigned` (`assigned_user_id`),
  ADD KEY `idx_lead_contact` (`contact_id`),
  ADD KEY `idx_reports_to` (`reports_to_id`),
  ADD KEY `idx_lead_phone_work` (`phone_work`),
  ADD KEY `idx_leads_id_del` (`id`,`deleted`);

--
-- Indexes for table `leads_audit`
--
ALTER TABLE `leads_audit`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_leads_parent_id` (`parent_id`);

--
-- Indexes for table `leads_cstm`
--
ALTER TABLE `leads_cstm`
  ADD PRIMARY KEY (`id_c`);

--
-- Indexes for table `leads_documents_1_c`
--
ALTER TABLE `leads_documents_1_c`
  ADD PRIMARY KEY (`id`),
  ADD KEY `leads_documents_1_alt` (`leads_documents_1leads_ida`,`leads_documents_1documents_idb`);

--
-- Indexes for table `linked_documents`
--
ALTER TABLE `linked_documents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_parent_document` (`parent_type`,`parent_id`,`document_id`);

--
-- Indexes for table `meetings`
--
ALTER TABLE `meetings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_mtg_name` (`name`),
  ADD KEY `idx_meet_par_del` (`parent_id`,`parent_type`,`deleted`),
  ADD KEY `idx_meet_stat_del` (`assigned_user_id`,`status`,`deleted`),
  ADD KEY `idx_meet_date_start` (`date_start`);

--
-- Indexes for table `meetings_contacts`
--
ALTER TABLE `meetings_contacts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_con_mtg_mtg` (`meeting_id`),
  ADD KEY `idx_con_mtg_con` (`contact_id`),
  ADD KEY `idx_meeting_contact` (`meeting_id`,`contact_id`);

--
-- Indexes for table `meetings_cstm`
--
ALTER TABLE `meetings_cstm`
  ADD PRIMARY KEY (`id_c`);

--
-- Indexes for table `meetings_leads`
--
ALTER TABLE `meetings_leads`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_lead_meeting_meeting` (`meeting_id`),
  ADD KEY `idx_lead_meeting_lead` (`lead_id`),
  ADD KEY `idx_meeting_lead` (`meeting_id`,`lead_id`);

--
-- Indexes for table `meetings_users`
--
ALTER TABLE `meetings_users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_usr_mtg_mtg` (`meeting_id`),
  ADD KEY `idx_usr_mtg_usr` (`user_id`),
  ADD KEY `idx_meeting_users` (`meeting_id`,`user_id`);

--
-- Indexes for table `notes`
--
ALTER TABLE `notes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_note_name` (`name`),
  ADD KEY `idx_notes_parent` (`parent_id`,`parent_type`),
  ADD KEY `idx_note_contact` (`contact_id`),
  ADD KEY `idx_notes_assigned_del` (`deleted`,`assigned_user_id`);

--
-- Indexes for table `oauth2clients`
--
ALTER TABLE `oauth2clients`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `oauth2tokens`
--
ALTER TABLE `oauth2tokens`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `oauth_consumer`
--
ALTER TABLE `oauth_consumer`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `ckey` (`c_key`);

--
-- Indexes for table `oauth_nonce`
--
ALTER TABLE `oauth_nonce`
  ADD PRIMARY KEY (`conskey`,`nonce`),
  ADD KEY `oauth_nonce_keyts` (`conskey`,`nonce_ts`);

--
-- Indexes for table `oauth_tokens`
--
ALTER TABLE `oauth_tokens`
  ADD PRIMARY KEY (`id`,`deleted`),
  ADD KEY `oauth_state_ts` (`tstate`,`token_ts`),
  ADD KEY `constoken_key` (`consumer`);

--
-- Indexes for table `opportunities`
--
ALTER TABLE `opportunities`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_opp_name` (`name`),
  ADD KEY `idx_opp_assigned` (`assigned_user_id`),
  ADD KEY `idx_opp_id_deleted` (`id`,`deleted`);

--
-- Indexes for table `opportunities_audit`
--
ALTER TABLE `opportunities_audit`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_opportunities_parent_id` (`parent_id`);

--
-- Indexes for table `opportunities_contacts`
--
ALTER TABLE `opportunities_contacts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_con_opp_con` (`contact_id`),
  ADD KEY `idx_con_opp_opp` (`opportunity_id`),
  ADD KEY `idx_opportunities_contacts` (`opportunity_id`,`contact_id`);

--
-- Indexes for table `opportunities_cstm`
--
ALTER TABLE `opportunities_cstm`
  ADD PRIMARY KEY (`id_c`);

--
-- Indexes for table `outbound_email`
--
ALTER TABLE `outbound_email`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `outbound_email_audit`
--
ALTER TABLE `outbound_email_audit`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_outbound_email_parent_id` (`parent_id`);

--
-- Indexes for table `project`
--
ALTER TABLE `project`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `projects_accounts`
--
ALTER TABLE `projects_accounts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_proj_acct_proj` (`project_id`),
  ADD KEY `idx_proj_acct_acct` (`account_id`),
  ADD KEY `projects_accounts_alt` (`project_id`,`account_id`);

--
-- Indexes for table `projects_bugs`
--
ALTER TABLE `projects_bugs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_proj_bug_proj` (`project_id`),
  ADD KEY `idx_proj_bug_bug` (`bug_id`),
  ADD KEY `projects_bugs_alt` (`project_id`,`bug_id`);

--
-- Indexes for table `projects_cases`
--
ALTER TABLE `projects_cases`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_proj_case_proj` (`project_id`),
  ADD KEY `idx_proj_case_case` (`case_id`),
  ADD KEY `projects_cases_alt` (`project_id`,`case_id`);

--
-- Indexes for table `projects_contacts`
--
ALTER TABLE `projects_contacts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_proj_con_proj` (`project_id`),
  ADD KEY `idx_proj_con_con` (`contact_id`),
  ADD KEY `projects_contacts_alt` (`project_id`,`contact_id`);

--
-- Indexes for table `projects_opportunities`
--
ALTER TABLE `projects_opportunities`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_proj_opp_proj` (`project_id`),
  ADD KEY `idx_proj_opp_opp` (`opportunity_id`),
  ADD KEY `projects_opportunities_alt` (`project_id`,`opportunity_id`);

--
-- Indexes for table `projects_products`
--
ALTER TABLE `projects_products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_proj_prod_project` (`project_id`),
  ADD KEY `idx_proj_prod_product` (`product_id`),
  ADD KEY `projects_products_alt` (`project_id`,`product_id`);

--
-- Indexes for table `project_contacts_1_c`
--
ALTER TABLE `project_contacts_1_c`
  ADD PRIMARY KEY (`id`),
  ADD KEY `project_contacts_1_alt` (`project_contacts_1project_ida`,`project_contacts_1contacts_idb`);

--
-- Indexes for table `project_cstm`
--
ALTER TABLE `project_cstm`
  ADD PRIMARY KEY (`id_c`);

--
-- Indexes for table `project_opportunities_1_c`
--
ALTER TABLE `project_opportunities_1_c`
  ADD PRIMARY KEY (`id`),
  ADD KEY `project_opportunities_1_ida1` (`project_opportunities_1project_ida`),
  ADD KEY `project_opportunities_1_alt` (`project_opportunities_1opportunities_idb`);

--
-- Indexes for table `project_task`
--
ALTER TABLE `project_task`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `project_task_audit`
--
ALTER TABLE `project_task_audit`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_project_task_parent_id` (`parent_id`);

--
-- Indexes for table `project_users_1_c`
--
ALTER TABLE `project_users_1_c`
  ADD PRIMARY KEY (`id`),
  ADD KEY `project_users_1_alt` (`project_users_1project_ida`,`project_users_1users_idb`);

--
-- Indexes for table `prospects`
--
ALTER TABLE `prospects`
  ADD PRIMARY KEY (`id`),
  ADD KEY `prospect_auto_tracker_key` (`tracker_key`),
  ADD KEY `idx_prospects_last_first` (`last_name`,`first_name`,`deleted`),
  ADD KEY `idx_prospecs_del_last` (`last_name`,`deleted`),
  ADD KEY `idx_prospects_id_del` (`id`,`deleted`),
  ADD KEY `idx_prospects_assigned` (`assigned_user_id`);

--
-- Indexes for table `prospects_cstm`
--
ALTER TABLE `prospects_cstm`
  ADD PRIMARY KEY (`id_c`);

--
-- Indexes for table `prospect_lists`
--
ALTER TABLE `prospect_lists`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_prospect_list_name` (`name`);

--
-- Indexes for table `prospect_lists_prospects`
--
ALTER TABLE `prospect_lists_prospects`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_plp_pro_id` (`prospect_list_id`),
  ADD KEY `idx_plp_rel_id` (`related_id`,`related_type`,`prospect_list_id`);

--
-- Indexes for table `prospect_list_campaigns`
--
ALTER TABLE `prospect_list_campaigns`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_pro_id` (`prospect_list_id`),
  ADD KEY `idx_cam_id` (`campaign_id`),
  ADD KEY `idx_prospect_list_campaigns` (`prospect_list_id`,`campaign_id`);

--
-- Indexes for table `relationships`
--
ALTER TABLE `relationships`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_rel_name` (`relationship_name`);

--
-- Indexes for table `releases`
--
ALTER TABLE `releases`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_releases` (`name`,`deleted`);

--
-- Indexes for table `reminders`
--
ALTER TABLE `reminders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_reminder_name` (`name`),
  ADD KEY `idx_reminder_deleted` (`deleted`),
  ADD KEY `idx_reminder_related_event_module` (`related_event_module`),
  ADD KEY `idx_reminder_related_event_module_id` (`related_event_module_id`);

--
-- Indexes for table `reminders_invitees`
--
ALTER TABLE `reminders_invitees`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_reminder_invitee_name` (`name`),
  ADD KEY `idx_reminder_invitee_assigned_user_id` (`assigned_user_id`),
  ADD KEY `idx_reminder_invitee_reminder_id` (`reminder_id`),
  ADD KEY `idx_reminder_invitee_related_invitee_module` (`related_invitee_module`),
  ADD KEY `idx_reminder_invitee_related_invitee_module_id` (`related_invitee_module_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_role_id_del` (`id`,`deleted`);

--
-- Indexes for table `roles_modules`
--
ALTER TABLE `roles_modules`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_role_id` (`role_id`),
  ADD KEY `idx_module_id` (`module_id`);

--
-- Indexes for table `roles_users`
--
ALTER TABLE `roles_users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_ru_role_id` (`role_id`),
  ADD KEY `idx_ru_user_id` (`user_id`);

--
-- Indexes for table `saved_search`
--
ALTER TABLE `saved_search`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_desc` (`name`,`deleted`);

--
-- Indexes for table `schedulers`
--
ALTER TABLE `schedulers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_schedule` (`date_time_start`,`deleted`);

--
-- Indexes for table `securitygroups`
--
ALTER TABLE `securitygroups`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `securitygroups_acl_roles`
--
ALTER TABLE `securitygroups_acl_roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `securitygroups_audit`
--
ALTER TABLE `securitygroups_audit`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_securitygroups_parent_id` (`parent_id`);

--
-- Indexes for table `securitygroups_default`
--
ALTER TABLE `securitygroups_default`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `securitygroups_records`
--
ALTER TABLE `securitygroups_records`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_securitygroups_records_mod` (`module`,`deleted`,`record_id`,`securitygroup_id`),
  ADD KEY `idx_securitygroups_records_del` (`deleted`,`record_id`,`module`,`securitygroup_id`);

--
-- Indexes for table `securitygroups_users`
--
ALTER TABLE `securitygroups_users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `securitygroups_users_idxa` (`securitygroup_id`),
  ADD KEY `securitygroups_users_idxb` (`user_id`),
  ADD KEY `securitygroups_users_idxc` (`user_id`,`deleted`,`securitygroup_id`,`id`),
  ADD KEY `securitygroups_users_idxd` (`user_id`,`deleted`,`securitygroup_id`);

--
-- Indexes for table `spots`
--
ALTER TABLE `spots`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `stic_accounts_relationships`
--
ALTER TABLE `stic_accounts_relationships`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `stic_accounts_relationships_accounts_c`
--
ALTER TABLE `stic_accounts_relationships_accounts_c`
  ADD PRIMARY KEY (`id`),
  ADD KEY `stic_accounts_relationships_accounts_ida1` (`stic_accounts_relationships_accountsaccounts_ida`),
  ADD KEY `stic_accounts_relationships_accounts_alt` (`stic_accoub36donships_idb`);

--
-- Indexes for table `stic_accounts_relationships_audit`
--
ALTER TABLE `stic_accounts_relationships_audit`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_stic_accounts_relationships_parent_id` (`parent_id`);

--
-- Indexes for table `stic_accounts_relationships_project_c`
--
ALTER TABLE `stic_accounts_relationships_project_c`
  ADD PRIMARY KEY (`id`),
  ADD KEY `stic_accounts_relationships_project_ida1` (`stic_accounts_relationships_projectproject_ida`),
  ADD KEY `stic_accounts_relationships_project_alt` (`stic_accou2675onships_idb`);

--
-- Indexes for table `stic_attendances`
--
ALTER TABLE `stic_attendances`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `stic_attendances_audit`
--
ALTER TABLE `stic_attendances_audit`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_stic_attendances_parent_id` (`parent_id`);

--
-- Indexes for table `stic_attendances_stic_registrations_c`
--
ALTER TABLE `stic_attendances_stic_registrations_c`
  ADD PRIMARY KEY (`id`),
  ADD KEY `stic_attendances_stic_registrations_ida1` (`stic_attendances_stic_registrationsstic_registrations_ida`),
  ADD KEY `stic_attendances_stic_registrations_alt` (`stic_attendances_stic_registrationsstic_attendances_idb`);

--
-- Indexes for table `stic_attendances_stic_sessions_c`
--
ALTER TABLE `stic_attendances_stic_sessions_c`
  ADD PRIMARY KEY (`id`),
  ADD KEY `stic_attendances_stic_sessions_ida1` (`stic_attendances_stic_sessionsstic_sessions_ida`),
  ADD KEY `stic_attendances_stic_sessions_alt` (`stic_attendances_stic_sessionsstic_attendances_idb`);

--
-- Indexes for table `stic_contacts_relationships`
--
ALTER TABLE `stic_contacts_relationships`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `stic_contacts_relationships_audit`
--
ALTER TABLE `stic_contacts_relationships_audit`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_stic_contacts_relationships_parent_id` (`parent_id`);

--
-- Indexes for table `stic_contacts_relationships_contacts_c`
--
ALTER TABLE `stic_contacts_relationships_contacts_c`
  ADD PRIMARY KEY (`id`),
  ADD KEY `stic_contacts_relationships_contacts_ida1` (`stic_contacts_relationships_contactscontacts_ida`),
  ADD KEY `stic_contacts_relationships_contacts_alt` (`stic_contae394onships_idb`);

--
-- Indexes for table `stic_contacts_relationships_project_c`
--
ALTER TABLE `stic_contacts_relationships_project_c`
  ADD PRIMARY KEY (`id`),
  ADD KEY `stic_contacts_relationships_project_ida1` (`stic_contacts_relationships_projectproject_ida`),
  ADD KEY `stic_contacts_relationships_project_alt` (`stic_conta0d5aonships_idb`);

--
-- Indexes for table `stic_events`
--
ALTER TABLE `stic_events`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `stic_events_audit`
--
ALTER TABLE `stic_events_audit`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_stic_events_parent_id` (`parent_id`);

--
-- Indexes for table `stic_events_fp_event_locations_c`
--
ALTER TABLE `stic_events_fp_event_locations_c`
  ADD PRIMARY KEY (`id`),
  ADD KEY `stic_events_fp_event_locations_ida1` (`stic_events_fp_event_locationsfp_event_locations_ida`),
  ADD KEY `stic_events_fp_event_locations_alt` (`stic_events_fp_event_locationsstic_events_idb`);

--
-- Indexes for table `stic_events_project_c`
--
ALTER TABLE `stic_events_project_c`
  ADD PRIMARY KEY (`id`),
  ADD KEY `stic_events_project_ida1` (`stic_events_projectproject_ida`),
  ADD KEY `stic_events_project_alt` (`stic_events_projectstic_events_idb`);

--
-- Indexes for table `stic_payments`
--
ALTER TABLE `stic_payments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `transaction_code_autoincrement` (`transaction_code`);

--
-- Indexes for table `stic_payments_accounts_c`
--
ALTER TABLE `stic_payments_accounts_c`
  ADD PRIMARY KEY (`id`),
  ADD KEY `stic_payments_accounts_ida1` (`stic_payments_accountsaccounts_ida`),
  ADD KEY `stic_payments_accounts_alt` (`stic_payments_accountsstic_payments_idb`);

--
-- Indexes for table `stic_payments_audit`
--
ALTER TABLE `stic_payments_audit`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_stic_payments_parent_id` (`parent_id`);

--
-- Indexes for table `stic_payments_contacts_c`
--
ALTER TABLE `stic_payments_contacts_c`
  ADD PRIMARY KEY (`id`),
  ADD KEY `stic_payments_contacts_ida1` (`stic_payments_contactscontacts_ida`),
  ADD KEY `stic_payments_contacts_alt` (`stic_payments_contactsstic_payments_idb`);

--
-- Indexes for table `stic_payments_stic_payment_commitments_c`
--
ALTER TABLE `stic_payments_stic_payment_commitments_c`
  ADD PRIMARY KEY (`id`),
  ADD KEY `stic_payments_stic_payment_commitments_ida1` (`stic_paymebfe2itments_ida`),
  ADD KEY `stic_payments_stic_payment_commitments_alt` (`stic_payments_stic_payment_commitmentsstic_payments_idb`);

--
-- Indexes for table `stic_payments_stic_registrations_c`
--
ALTER TABLE `stic_payments_stic_registrations_c`
  ADD PRIMARY KEY (`id`),
  ADD KEY `stic_payments_stic_registrations_ida1` (`stic_payments_stic_registrationsstic_payments_ida`),
  ADD KEY `stic_payments_stic_registrations_idb2` (`stic_payments_stic_registrationsstic_registrations_idb`);

--
-- Indexes for table `stic_payments_stic_remittances_c`
--
ALTER TABLE `stic_payments_stic_remittances_c`
  ADD PRIMARY KEY (`id`),
  ADD KEY `stic_payments_stic_remittances_ida1` (`stic_payments_stic_remittancesstic_remittances_ida`),
  ADD KEY `stic_payments_stic_remittances_alt` (`stic_payments_stic_remittancesstic_payments_idb`);

--
-- Indexes for table `stic_payment_commitments`
--
ALTER TABLE `stic_payment_commitments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `stic_payment_commitments_accounts_c`
--
ALTER TABLE `stic_payment_commitments_accounts_c`
  ADD PRIMARY KEY (`id`),
  ADD KEY `stic_payment_commitments_accounts_ida1` (`stic_payment_commitments_accountsaccounts_ida`),
  ADD KEY `stic_payment_commitments_accounts_alt` (`stic_payment_commitments_accountsstic_payment_commitments_idb`);

--
-- Indexes for table `stic_payment_commitments_audit`
--
ALTER TABLE `stic_payment_commitments_audit`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_stic_payment_commitments_parent_id` (`parent_id`);

--
-- Indexes for table `stic_payment_commitments_campaigns_c`
--
ALTER TABLE `stic_payment_commitments_campaigns_c`
  ADD PRIMARY KEY (`id`),
  ADD KEY `stic_payment_commitments_campaigns_ida1` (`stic_payment_commitments_campaignscampaigns_ida`),
  ADD KEY `stic_payment_commitments_campaigns_alt` (`stic_payment_commitments_campaignsstic_payment_commitments_idb`);

--
-- Indexes for table `stic_payment_commitments_contacts_c`
--
ALTER TABLE `stic_payment_commitments_contacts_c`
  ADD PRIMARY KEY (`id`),
  ADD KEY `stic_payment_commitments_contacts_ida1` (`stic_payment_commitments_contactscontacts_ida`),
  ADD KEY `stic_payment_commitments_contacts_alt` (`stic_payment_commitments_contactsstic_payment_commitments_idb`);

--
-- Indexes for table `stic_payment_commitments_project_c`
--
ALTER TABLE `stic_payment_commitments_project_c`
  ADD PRIMARY KEY (`id`),
  ADD KEY `stic_payment_commitments_project_ida1` (`stic_payment_commitments_projectproject_ida`),
  ADD KEY `stic_payment_commitments_project_alt` (`stic_payment_commitments_projectstic_payment_commitments_idb`);

--
-- Indexes for table `stic_registrations`
--
ALTER TABLE `stic_registrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `stic_registrations_accounts_c`
--
ALTER TABLE `stic_registrations_accounts_c`
  ADD PRIMARY KEY (`id`),
  ADD KEY `stic_registrations_accounts_ida1` (`stic_registrations_accountsaccounts_ida`),
  ADD KEY `stic_registrations_accounts_alt` (`stic_registrations_accountsstic_registrations_idb`);

--
-- Indexes for table `stic_registrations_audit`
--
ALTER TABLE `stic_registrations_audit`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_stic_registrations_parent_id` (`parent_id`);

--
-- Indexes for table `stic_registrations_contacts_c`
--
ALTER TABLE `stic_registrations_contacts_c`
  ADD PRIMARY KEY (`id`),
  ADD KEY `stic_registrations_contacts_ida1` (`stic_registrations_contactscontacts_ida`),
  ADD KEY `stic_registrations_contacts_alt` (`stic_registrations_contactsstic_registrations_idb`);

--
-- Indexes for table `stic_registrations_leads_c`
--
ALTER TABLE `stic_registrations_leads_c`
  ADD PRIMARY KEY (`id`),
  ADD KEY `stic_registrations_leads_ida1` (`stic_registrations_leadsleads_ida`),
  ADD KEY `stic_registrations_leads_alt` (`stic_registrations_leadsstic_registrations_idb`);

--
-- Indexes for table `stic_registrations_stic_events_c`
--
ALTER TABLE `stic_registrations_stic_events_c`
  ADD PRIMARY KEY (`id`),
  ADD KEY `stic_registrations_stic_events_ida1` (`stic_registrations_stic_eventsstic_events_ida`),
  ADD KEY `stic_registrations_stic_events_alt` (`stic_registrations_stic_eventsstic_registrations_idb`);

--
-- Indexes for table `stic_remittances`
--
ALTER TABLE `stic_remittances`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `stic_remittances_audit`
--
ALTER TABLE `stic_remittances_audit`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_stic_remittances_parent_id` (`parent_id`);

--
-- Indexes for table `stic_sessions`
--
ALTER TABLE `stic_sessions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `stic_sessions_audit`
--
ALTER TABLE `stic_sessions_audit`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_stic_sessions_parent_id` (`parent_id`);

--
-- Indexes for table `stic_sessions_documents_c`
--
ALTER TABLE `stic_sessions_documents_c`
  ADD PRIMARY KEY (`id`),
  ADD KEY `stic_sessions_documents_ida1` (`stic_sessions_documentsstic_sessions_ida`),
  ADD KEY `stic_sessions_documents_alt` (`stic_sessions_documentsdocuments_idb`);

--
-- Indexes for table `stic_sessions_stic_events_c`
--
ALTER TABLE `stic_sessions_stic_events_c`
  ADD PRIMARY KEY (`id`),
  ADD KEY `stic_sessions_stic_events_ida1` (`stic_sessions_stic_eventsstic_events_ida`),
  ADD KEY `stic_sessions_stic_events_alt` (`stic_sessions_stic_eventsstic_sessions_idb`);

--
-- Indexes for table `stic_settings`
--
ALTER TABLE `stic_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `stic_settings_audit`
--
ALTER TABLE `stic_settings_audit`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_stic_settings_parent_id` (`parent_id`);

--
-- Indexes for table `stic_validation_actions`
--
ALTER TABLE `stic_validation_actions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `stic_validation_actions_audit`
--
ALTER TABLE `stic_validation_actions_audit`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_stic_validation_actions_parent_id` (`parent_id`);

--
-- Indexes for table `stic_validation_actions_schedulers_c`
--
ALTER TABLE `stic_validation_actions_schedulers_c`
  ADD PRIMARY KEY (`id`),
  ADD KEY `stic_validation_actions_schedulers_alt` (`stic_validation_actions_schedulersstic_validation_actions_ida`,`stic_validation_actions_schedulersschedulers_idb`);

--
-- Indexes for table `sugarfeed`
--
ALTER TABLE `sugarfeed`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sgrfeed_date` (`date_entered`,`deleted`);

--
-- Indexes for table `surveyquestionoptions`
--
ALTER TABLE `surveyquestionoptions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `surveyquestionoptions_audit`
--
ALTER TABLE `surveyquestionoptions_audit`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_surveyquestionoptions_parent_id` (`parent_id`);

--
-- Indexes for table `surveyquestionoptions_surveyquestionresponses`
--
ALTER TABLE `surveyquestionoptions_surveyquestionresponses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `surveyquestionoptions_surveyquestionresponses_alt` (`surveyq72c7options_ida`,`surveyq10d4sponses_idb`);

--
-- Indexes for table `surveyquestionresponses`
--
ALTER TABLE `surveyquestionresponses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `surveyquestionresponses_audit`
--
ALTER TABLE `surveyquestionresponses_audit`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_surveyquestionresponses_parent_id` (`parent_id`);

--
-- Indexes for table `surveyquestions`
--
ALTER TABLE `surveyquestions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `surveyquestions_audit`
--
ALTER TABLE `surveyquestions_audit`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_surveyquestions_parent_id` (`parent_id`);

--
-- Indexes for table `surveyresponses`
--
ALTER TABLE `surveyresponses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `surveyresponses_audit`
--
ALTER TABLE `surveyresponses_audit`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_surveyresponses_parent_id` (`parent_id`);

--
-- Indexes for table `surveys`
--
ALTER TABLE `surveys`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `surveys_audit`
--
ALTER TABLE `surveys_audit`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_surveys_parent_id` (`parent_id`);

--
-- Indexes for table `tasks`
--
ALTER TABLE `tasks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_tsk_name` (`name`),
  ADD KEY `idx_task_con_del` (`contact_id`,`deleted`),
  ADD KEY `idx_task_par_del` (`parent_id`,`parent_type`,`deleted`),
  ADD KEY `idx_task_assigned` (`assigned_user_id`),
  ADD KEY `idx_task_status` (`status`);

--
-- Indexes for table `templatesectionline`
--
ALTER TABLE `templatesectionline`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tracker`
--
ALTER TABLE `tracker`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_tracker_iid` (`item_id`),
  ADD KEY `idx_tracker_userid_vis_id` (`user_id`,`visible`,`id`),
  ADD KEY `idx_tracker_userid_itemid_vis` (`user_id`,`item_id`,`visible`),
  ADD KEY `idx_tracker_monitor_id` (`monitor_id`),
  ADD KEY `idx_tracker_date_modified` (`date_modified`);

--
-- Indexes for table `upgrade_history`
--
ALTER TABLE `upgrade_history`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `upgrade_history_md5_uk` (`md5sum`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_user_name` (`user_name`,`is_group`,`status`,`last_name`(30),`first_name`(30),`id`);

--
-- Indexes for table `users_feeds`
--
ALTER TABLE `users_feeds`
  ADD KEY `idx_ud_user_id` (`user_id`,`feed_id`);

--
-- Indexes for table `users_last_import`
--
ALTER TABLE `users_last_import`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_user_id` (`assigned_user_id`);

--
-- Indexes for table `users_password_link`
--
ALTER TABLE `users_password_link`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_username` (`username`);

--
-- Indexes for table `users_signatures`
--
ALTER TABLE `users_signatures`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_usersig_uid` (`user_id`);

--
-- Indexes for table `user_preferences`
--
ALTER TABLE `user_preferences`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_userprefnamecat` (`assigned_user_id`,`category`);

--
-- Indexes for table `vcals`
--
ALTER TABLE `vcals`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_vcal` (`type`,`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bugs`
--
ALTER TABLE `bugs`
  MODIFY `bug_number` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `campaigns`
--
ALTER TABLE `campaigns`
  MODIFY `tracker_key` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `campaign_trkrs`
--
ALTER TABLE `campaign_trkrs`
  MODIFY `tracker_key` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `cases`
--
ALTER TABLE `cases`
  MODIFY `case_number` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `emailman`
--
ALTER TABLE `emailman`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `prospects`
--
ALTER TABLE `prospects`
  MODIFY `tracker_key` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `stic_payments`
--
ALTER TABLE `stic_payments`
  MODIFY `transaction_code` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tracker`
--
ALTER TABLE `tracker`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
