-- phpMyAdmin SQL Dump
-- version 4.4.15.10
-- https://www.phpmyadmin.net
--
-- Host: 10.200.1.104
-- Generation Time: Jul 04, 2020 at 10:32 AM
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
('10a6a59b-1a49-1a2e-d394-5f003e68a4b9', 'securitygroups_surveyresponses', 'SecurityGroups', 'securitygroups', 'id', 'SurveyResponses', 'surveyresponses', 'id', 'securitygroups_records', 'securitygroup_id', 'record_id', 'many-to-many', 'module', 'SurveyResponses', 0, 0),
('11881810-eefc-a038-8d1e-5f003e55ac11', 'surveyresponses_surveyquestionresponses', 'SurveyResponses', 'surveyresponses', 'id', 'SurveyQuestionResponses', 'surveyquestionresponses', 'surveyresponse_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('12610366-d2e3-80b0-d065-5f003e589412', 'stic_payments_activities_meetings', 'stic_Payments', 'stic_payments', 'id', 'Meetings', 'meetings', 'parent_id', NULL, NULL, NULL, 'one-to-many', 'parent_type', 'stic_Payments', 0, 0),
('14cc104d-c13c-39e0-227e-5f003e8c06b6', 'surveys_modified_user', 'Users', 'users', 'id', 'Surveys', 'surveys', 'modified_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('15c2097a-c64f-9ce2-7d07-5f003ed2846c', 'surveys_created_by', 'Users', 'users', 'id', 'Surveys', 'surveys', 'created_by', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('15f8c04e-5f0e-e464-e505-5f003e61f6ae', 'leads_modified_user', 'Users', 'users', 'id', 'Leads', 'leads', 'modified_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('168953bb-7aa4-f6b0-1897-5f003ebb6810', 'surveys_assigned_user', 'Users', 'users', 'id', 'Surveys', 'surveys', 'assigned_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('16dbc67f-c5b5-ec96-1745-5f003eb64fb1', 'securitygroups_surveys', 'SecurityGroups', 'securitygroups', 'id', 'Surveys', 'surveys', 'id', 'securitygroups_records', 'securitygroup_id', 'record_id', 'many-to-many', 'module', 'Surveys', 0, 0),
('179a296f-b3bf-ea4c-136e-5f003ef290de', 'surveys_surveyquestions', 'Surveys', 'surveys', 'id', 'SurveyQuestions', 'surveyquestions', 'survey_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('17f40b82-0786-1f71-40f0-5f003e7d242c', 'stic_registrations_stic_events', 'stic_Events', 'stic_events', 'id', 'stic_Registrations', 'stic_registrations', 'id', 'stic_registrations_stic_events_c', 'stic_registrations_stic_eventsstic_events_ida', 'stic_registrations_stic_eventsstic_registrations_idb', 'many-to-many', NULL, NULL, 0, 0),
('17f6b125-56c4-cf2b-5296-5f003e601a1b', 'surveys_surveyresponses', 'Surveys', 'surveys', 'id', 'SurveyResponses', 'surveyresponses', 'survey_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('18dacafa-61a2-77c0-71c7-5f003ec1f86c', 'surveys_campaigns', 'Surveys', 'surveys', 'id', 'Campaigns', 'campaigns', 'survey_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('18fcccc1-ee26-cc71-2d13-5f003e0d727b', 'tasks_assigned_user', 'Users', 'users', 'id', 'Tasks', 'tasks', 'assigned_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('19e771c7-3010-e7a5-9ca6-5f003e221f2c', 'securitygroups_tasks', 'SecurityGroups', 'securitygroups', 'id', 'Tasks', 'tasks', 'id', 'securitygroups_records', 'securitygroup_id', 'record_id', 'many-to-many', 'module', 'Tasks', 0, 0),
('19efcca2-391d-49a6-18b1-5f003e167fae', 'leads_created_by', 'Users', 'users', 'id', 'Leads', 'leads', 'created_by', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('1a16ea08-07b6-d0e4-1355-5f003ea04318', 'aos_products_quotes_created_by', 'Users', 'users', 'id', 'AOS_Products_Quotes', 'aos_products_quotes', 'created_by', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('1a418a60-8ecc-483f-660e-5f003e6aea6d', 'stic_validation_actions_modified_user', 'Users', 'users', 'id', 'stic_Validation_Actions', 'stic_validation_actions', 'modified_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('1ab242bb-f397-727e-b2b7-5f003e140fcc', 'tasks_notes', 'Tasks', 'tasks', 'id', 'Notes', 'notes', 'parent_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('1af8f11b-724e-9df7-b2e8-5f003e171bab', 'surveyquestionresponses_modified_user', 'Users', 'users', 'id', 'SurveyQuestionResponses', 'surveyquestionresponses', 'modified_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('1b01c7c5-afcc-9fd8-654c-5f003e7a0b46', 'leads_assigned_user', 'Users', 'users', 'id', 'Leads', 'leads', 'assigned_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('1b3c8835-f5dc-9046-8419-5f003e8db917', 'stic_validation_actions_created_by', 'Users', 'users', 'id', 'stic_Validation_Actions', 'stic_validation_actions', 'created_by', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('1bf251e2-1d9e-76ef-c862-5f003eaa9ced', 'securitygroups_leads', 'SecurityGroups', 'securitygroups', 'id', 'Leads', 'leads', 'id', 'securitygroups_records', 'securitygroup_id', 'record_id', 'many-to-many', 'module', 'Leads', 0, 0),
('1c19008d-1a40-174a-bac4-5f003e89296f', 'stic_validation_actions_assigned_user', 'Users', 'users', 'id', 'stic_Validation_Actions', 'stic_validation_actions', 'assigned_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('1c5d3526-1d34-ab38-6293-5f003e51fb48', 'surveyquestionresponses_created_by', 'Users', 'users', 'id', 'SurveyQuestionResponses', 'surveyquestionresponses', 'created_by', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('1cd5deb9-bfe6-4a8c-532d-5f003e4afb22', 'leads_email_addresses', 'Leads', 'leads', 'id', 'EmailAddresses', 'email_addresses', 'id', 'email_addr_bean_rel', 'bean_id', 'email_address_id', 'many-to-many', 'bean_module', 'Leads', 0, 0),
('1d16573d-c50d-0ac2-ab49-5f003ec357bd', 'securitygroups_stic_validation_actions', 'SecurityGroups', 'securitygroups', 'id', 'stic_Validation_Actions', 'stic_validation_actions', 'id', 'securitygroups_records', 'securitygroup_id', 'record_id', 'many-to-many', 'module', 'stic_Validation_Actions', 0, 0),
('1d26f491-bee3-0018-c1c0-5f003e323374', 'surveyquestionresponses_assigned_user', 'Users', 'users', 'id', 'SurveyQuestionResponses', 'surveyquestionresponses', 'assigned_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('1dd05fdf-eb65-98d8-7eac-5f003e888c7e', 'leads_email_addresses_primary', 'Leads', 'leads', 'id', 'EmailAddresses', 'email_addresses', 'id', 'email_addr_bean_rel', 'bean_id', 'email_address_id', 'many-to-many', 'primary_address', '1', 0, 0),
('1e1b9406-f256-35f1-1722-5f003eb2f542', 'securitygroups_surveyquestionresponses', 'SecurityGroups', 'securitygroups', 'id', 'SurveyQuestionResponses', 'surveyquestionresponses', 'id', 'securitygroups_records', 'securitygroup_id', 'record_id', 'many-to-many', 'module', 'SurveyQuestionResponses', 0, 0),
('1ef60ccf-3f3b-a24d-a24b-5f003ea2686c', 'lead_direct_reports', 'Leads', 'leads', 'id', 'Leads', 'leads', 'reports_to_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('1ff32d2e-0003-d992-03d8-5f003ea25a27', 'accounts_bugs', 'Accounts', 'accounts', 'id', 'Bugs', 'bugs', 'id', 'accounts_bugs', 'account_id', 'bug_id', 'many-to-many', NULL, NULL, 0, 0),
('203ded6e-2052-f42b-686d-5f003edf327e', 'lead_tasks', 'Leads', 'leads', 'id', 'Tasks', 'tasks', 'parent_id', NULL, NULL, NULL, 'one-to-many', 'parent_type', 'Leads', 0, 0),
('204a9743-c1a8-a980-bab2-5f003e3cd366', 'surveyquestions_modified_user', 'Users', 'users', 'id', 'SurveyQuestions', 'surveyquestions', 'modified_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('20ff1ba2-020a-814d-036e-5f003e1a7672', 'accounts_contacts', 'Accounts', 'accounts', 'id', 'Contacts', 'contacts', 'id', 'accounts_contacts', 'account_id', 'contact_id', 'many-to-many', NULL, NULL, 0, 0),
('211070dd-c19f-6a07-b0a9-5f003ed3de67', 'lead_notes', 'Leads', 'leads', 'id', 'Notes', 'notes', 'parent_id', NULL, NULL, NULL, 'one-to-many', 'parent_type', 'Leads', 0, 0),
('21430152-291d-c883-601d-5f003e15ba4f', 'surveyquestions_created_by', 'Users', 'users', 'id', 'SurveyQuestions', 'surveyquestions', 'created_by', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('21d413be-156d-e766-93b2-5f003eff0120', 'accounts_opportunities', 'Accounts', 'accounts', 'id', 'Opportunities', 'opportunities', 'id', 'accounts_opportunities', 'account_id', 'opportunity_id', 'many-to-many', NULL, NULL, 0, 0),
('21e19cfa-62ff-1baa-dcc7-5f003e757e63', 'lead_meetings', 'Leads', 'leads', 'id', 'Meetings', 'meetings', 'parent_id', NULL, NULL, NULL, 'one-to-many', 'parent_type', 'Leads', 0, 0),
('2219574a-faee-beff-2d49-5f003ed8d5bd', 'surveyquestions_assigned_user', 'Users', 'users', 'id', 'SurveyQuestions', 'surveyquestions', 'assigned_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('22a89a72-2237-311c-c85c-5f003e4a24a9', 'lead_calls', 'Leads', 'leads', 'id', 'Calls', 'calls', 'parent_id', NULL, NULL, NULL, 'one-to-many', 'parent_type', 'Leads', 0, 0),
('22b2466e-61fa-2e9c-e49b-5f003ebbf4eb', 'calls_contacts', 'Calls', 'calls', 'id', 'Contacts', 'contacts', 'id', 'calls_contacts', 'call_id', 'contact_id', 'many-to-many', NULL, NULL, 0, 0),
('22e5148d-9d29-6d61-1750-5f003ed61c31', 'securitygroups_surveyquestions', 'SecurityGroups', 'securitygroups', 'id', 'SurveyQuestions', 'surveyquestions', 'id', 'securitygroups_records', 'securitygroup_id', 'record_id', 'many-to-many', 'module', 'SurveyQuestions', 0, 0),
('237df6ac-fb48-b404-f478-5f003eddb57b', 'calls_users', 'Calls', 'calls', 'id', 'Users', 'users', 'id', 'calls_users', 'call_id', 'user_id', 'many-to-many', NULL, NULL, 0, 0),
('238f805e-4bd1-16af-b9b0-5f003e883750', 'lead_emails', 'Leads', 'leads', 'id', 'Emails', 'emails', 'parent_id', NULL, NULL, NULL, 'one-to-many', 'parent_type', 'Leads', 0, 0),
('23a9e095-f1dd-f040-87fc-5f003e6885c1', 'surveyquestions_surveyquestionoptions', 'SurveyQuestions', 'surveyquestions', 'id', 'SurveyQuestionOptions', 'surveyquestionoptions', 'survey_question_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('2452a235-58c1-dbf3-901b-5f003ee26eb2', 'calls_leads', 'Calls', 'calls', 'id', 'Leads', 'leads', 'id', 'calls_leads', 'call_id', 'lead_id', 'many-to-many', NULL, NULL, 0, 0),
('246b3805-b0d2-bfaa-2efe-5f003ec12612', 'lead_campaign_log', 'Leads', 'leads', 'id', 'CampaignLog', 'campaign_log', 'target_id', NULL, NULL, NULL, 'one-to-many', 'target_type', 'Leads', 0, 0),
('24b138a7-8193-ce75-af0b-5f003e4a7556', 'cases_bugs', 'Cases', 'cases', 'id', 'Bugs', 'bugs', 'id', 'cases_bugs', 'case_id', 'bug_id', 'many-to-many', NULL, NULL, 0, 0),
('24b63558-3c85-6e92-640b-5f003eef2857', 'alerts_modified_user', 'Users', 'users', 'id', 'Alerts', 'alerts', 'modified_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('24c6063f-77a1-583a-aea3-5f003eaeda3f', 'stic_sessions_documents', 'stic_Sessions', 'stic_sessions', 'id', 'Documents', 'documents', 'id', 'stic_sessions_documents_c', 'stic_sessions_documentsstic_sessions_ida', 'stic_sessions_documentsdocuments_idb', 'many-to-many', NULL, NULL, 0, 0),
('25b97db6-8c8c-9300-b18d-5f003ef62c32', 'contacts_bugs', 'Contacts', 'contacts', 'id', 'Bugs', 'bugs', 'id', 'contacts_bugs', 'contact_id', 'bug_id', 'many-to-many', NULL, NULL, 0, 0),
('25fa1fc6-a780-6ce9-0ec6-5f003ed9d572', 'surveyquestionoptions_modified_user', 'Users', 'users', 'id', 'SurveyQuestionOptions', 'surveyquestionoptions', 'modified_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('261d6e46-4bbb-57e2-2aa5-5f003e437863', 'alerts_created_by', 'Users', 'users', 'id', 'Alerts', 'alerts', 'created_by', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('26ab5ee8-1bf6-997e-020b-5f003edcba04', 'contacts_cases', 'Contacts', 'contacts', 'id', 'Cases', 'cases', 'id', 'contacts_cases', 'contact_id', 'case_id', 'many-to-many', NULL, NULL, 0, 0),
('26df20cf-f4d9-fa2e-36f0-5f003e9280eb', 'alerts_assigned_user', 'Users', 'users', 'id', 'Alerts', 'alerts', 'assigned_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('273cf9d8-69fa-8e35-838e-5f003eb89488', 'surveyquestionoptions_created_by', 'Users', 'users', 'id', 'SurveyQuestionOptions', 'surveyquestionoptions', 'created_by', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('278b6ab0-e629-7cb3-d02b-5f003e138c69', 'contacts_users', 'Contacts', 'contacts', 'id', 'Users', 'users', 'id', 'contacts_users', 'contact_id', 'user_id', 'many-to-many', NULL, NULL, 0, 0),
('28068e53-64bc-b1ea-2515-5f003ef4dfbc', 'surveyquestionoptions_assigned_user', 'Users', 'users', 'id', 'SurveyQuestionOptions', 'surveyquestionoptions', 'assigned_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('28426727-c6c4-fa12-6656-5f003e4beb5f', 'aos_products_quotes_assigned_user', 'Users', 'users', 'id', 'AOS_Products_Quotes', 'aos_products_quotes', 'assigned_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('289889f7-fca7-1409-cf11-5f003e11d6a3', 'emails_bugs_rel', 'Emails', 'emails', 'id', 'Bugs', 'bugs', 'id', 'emails_beans', 'email_id', 'bean_id', 'many-to-many', 'bean_module', 'Bugs', 0, 0),
('28d04584-82b6-5613-1a67-5f003ec93336', 'securitygroups_surveyquestionoptions', 'SecurityGroups', 'securitygroups', 'id', 'SurveyQuestionOptions', 'surveyquestionoptions', 'id', 'securitygroups_records', 'securitygroup_id', 'record_id', 'many-to-many', 'module', 'SurveyQuestionOptions', 0, 0),
('28fb8ac3-1317-84ff-6645-5f003efcae24', 'cases_modified_user', 'Users', 'users', 'id', 'Cases', 'cases', 'modified_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('2996f775-95ca-5ae9-289b-5f003ed30267', 'emails_cases_rel', 'Emails', 'emails', 'id', 'Cases', 'cases', 'id', 'emails_beans', 'email_id', 'bean_id', 'many-to-many', 'bean_module', 'Cases', 0, 0),
('2a2f6bb8-e174-eb77-a928-5f003e2a0680', 'documents_modified_user', 'Users', 'users', 'id', 'Documents', 'documents', 'modified_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('2a57a3e4-5db1-b524-554a-5f003e9fbd10', 'cases_created_by', 'Users', 'users', 'id', 'Cases', 'cases', 'created_by', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('2a977a7e-1ea8-6336-4038-5f003e4e7979', 'emails_opportunities_rel', 'Emails', 'emails', 'id', 'Opportunities', 'opportunities', 'id', 'emails_beans', 'email_id', 'bean_id', 'many-to-many', 'bean_module', 'Opportunities', 0, 0),
('2aba2803-be12-f859-cfa1-5f003e3d3a06', 'aos_product_quotes_aos_products', 'AOS_Products', 'aos_products', 'id', 'AOS_Products_Quotes', 'aos_products_quotes', 'product_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('2b325310-5715-201e-c317-5f003e81b79d', 'documents_created_by', 'Users', 'users', 'id', 'Documents', 'documents', 'created_by', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('2b43329e-cfa7-d50d-865e-5f003e728bd2', 'cases_assigned_user', 'Users', 'users', 'id', 'Cases', 'cases', 'assigned_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('2b827882-38df-4dd2-dbcf-5f003eb227c5', 'emails_tasks_rel', 'Emails', 'emails', 'id', 'Tasks', 'tasks', 'id', 'emails_beans', 'email_id', 'bean_id', 'many-to-many', 'bean_module', 'Tasks', 0, 0),
('2bf3cc85-531a-753f-7bb1-5f003e7bf355', 'documents_assigned_user', 'Users', 'users', 'id', 'Documents', 'documents', 'assigned_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('2c462ccc-2e0b-8878-e06b-5f003e067c79', 'securitygroups_cases', 'SecurityGroups', 'securitygroups', 'id', 'Cases', 'cases', 'id', 'securitygroups_records', 'securitygroup_id', 'record_id', 'many-to-many', 'module', 'Cases', 0, 0),
('2c574138-a908-215b-ca5d-5f003eb29a64', 'emails_users_rel', 'Emails', 'emails', 'id', 'Users', 'users', 'id', 'emails_beans', 'email_id', 'bean_id', 'many-to-many', 'bean_module', 'Users', 0, 0),
('2cb16360-fb7b-58e0-6115-5f003ead9d95', 'securitygroups_documents', 'SecurityGroups', 'securitygroups', 'id', 'Documents', 'documents', 'id', 'securitygroups_records', 'securitygroup_id', 'record_id', 'many-to-many', 'module', 'Documents', 0, 0),
('2d4a4469-b85e-4084-dd6d-5f003eb52543', 'case_calls', 'Cases', 'cases', 'id', 'Calls', 'calls', 'parent_id', NULL, NULL, NULL, 'one-to-many', 'parent_type', 'Cases', 0, 0),
('2d57bf64-5f04-cabb-67dc-5f003e9c227a', 'emails_project_task_rel', 'Emails', 'emails', 'id', 'ProjectTask', 'project_task', 'id', 'emails_beans', 'email_id', 'bean_id', 'many-to-many', 'bean_module', 'ProjectTask', 0, 0),
('2d6bdf2d-57b0-5fdc-2754-5f003efdd907', 'document_revisions', 'Documents', 'documents', 'id', 'DocumentRevisions', 'document_revisions', 'document_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('2d823c14-6970-c305-5bd6-5f003eb84ec7', 'aos_line_item_groups_modified_user', 'Users', 'users', 'id', 'AOS_Line_Item_Groups', 'aos_line_item_groups', 'modified_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('2e3ac6a5-d43c-d92b-4bdf-5f003ef5b600', 'case_tasks', 'Cases', 'cases', 'id', 'Tasks', 'tasks', 'parent_id', NULL, NULL, NULL, 'one-to-many', 'parent_type', 'Cases', 0, 0),
('2e4ab119-7fd6-24cb-6d17-5f003e3307d6', 'emails_projects_rel', 'Emails', 'emails', 'id', 'Project', 'project', 'id', 'emails_beans', 'email_id', 'bean_id', 'many-to-many', 'bean_module', 'Project', 0, 0),
('2eb3e81f-5de6-67ab-35ef-5f003e0ef58e', 'aos_line_item_groups_created_by', 'Users', 'users', 'id', 'AOS_Line_Item_Groups', 'aos_line_item_groups', 'created_by', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('2f112844-d4a1-4ac8-26ec-5f003eaf9ba0', 'case_notes', 'Cases', 'cases', 'id', 'Notes', 'notes', 'parent_id', NULL, NULL, NULL, 'one-to-many', 'parent_type', 'Cases', 0, 0),
('2f35e2a1-663e-b221-37d9-5f003ee40856', 'emails_prospects_rel', 'Emails', 'emails', 'id', 'Prospects', 'prospects', 'id', 'emails_beans', 'email_id', 'bean_id', 'many-to-many', 'bean_module', 'Prospects', 0, 0),
('2f7841c0-a88e-4cac-5c40-5f003e956cbe', 'aos_line_item_groups_assigned_user', 'Users', 'users', 'id', 'AOS_Line_Item_Groups', 'aos_line_item_groups', 'assigned_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('2f78eb02-a652-b0cd-be18-5f003eed6f48', 'revisions_created_by', 'Users', 'users', 'id', 'DocumentRevisions', 'document_revisions', 'created_by', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('2f79225f-d060-3068-7583-5f003e6d4e9b', 'case_meetings', 'Cases', 'cases', 'id', 'Meetings', 'meetings', 'parent_id', NULL, NULL, NULL, 'one-to-many', 'parent_type', 'Cases', 0, 0),
('3040aedc-e5f3-fdb6-b0f7-5f003e4d0bef', 'meetings_contacts', 'Meetings', 'meetings', 'id', 'Contacts', 'contacts', 'id', 'meetings_contacts', 'meeting_id', 'contact_id', 'many-to-many', NULL, NULL, 0, 0),
('3058bace-2c3b-befe-e007-5f003ee1d28e', 'case_emails', 'Cases', 'cases', 'id', 'Emails', 'emails', 'parent_id', NULL, NULL, NULL, 'one-to-many', 'parent_type', 'Cases', 0, 0),
('3090370b-b7de-6ec3-2b2c-5f003e23e5ca', 'groups_aos_product_quotes', 'AOS_Line_Item_Groups', 'aos_line_item_groups', 'id', 'AOS_Products_Quotes', 'aos_products_quotes', 'group_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('3131b8f6-a3d9-05cf-9aec-5f003ea08e66', 'meetings_users', 'Meetings', 'meetings', 'id', 'Users', 'users', 'id', 'meetings_users', 'meeting_id', 'user_id', 'many-to-many', NULL, NULL, 0, 0),
('316b881c-e8c2-19b4-547e-5f003e80fdc7', 'cases_created_contact', 'Contacts', 'contacts', 'id', 'Cases', 'cases', 'contact_created_by_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('319d0bc8-76a5-d2fc-48de-5f003e6f4aff', 'stic_accounts_relationships_project', 'Project', 'project', 'id', 'stic_Accounts_Relationships', 'stic_accounts_relationships', 'id', 'stic_accounts_relationships_project_c', 'stic_accounts_relationships_projectproject_ida', 'stic_accou2675onships_idb', 'many-to-many', NULL, NULL, 0, 0),
('326272e9-5564-2921-be04-5f003eb543d2', 'meetings_leads', 'Meetings', 'meetings', 'id', 'Leads', 'leads', 'id', 'meetings_leads', 'meeting_id', 'lead_id', 'many-to-many', NULL, NULL, 0, 0),
('338dfe0d-d4be-f0f9-c29b-5f003e7a8e94', 'opportunities_contacts', 'Opportunities', 'opportunities', 'id', 'Contacts', 'contacts', 'id', 'opportunities_contacts', 'opportunity_id', 'contact_id', 'many-to-many', NULL, NULL, 0, 0),
('33df375b-dd2c-b96f-5e95-5f003e48b73b', 'aos_quotes_modified_user', 'Users', 'users', 'id', 'AOS_Quotes', 'aos_quotes', 'modified_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('348a9662-5b10-98e9-50a2-5f003e22a45a', 'prospect_list_campaigns', 'ProspectLists', 'prospect_lists', 'id', 'Campaigns', 'campaigns', 'id', 'prospect_list_campaigns', 'prospect_list_id', 'campaign_id', 'many-to-many', NULL, NULL, 0, 0),
('3539ab16-21e2-0220-777c-5f003e862e47', 'bugs_modified_user', 'Users', 'users', 'id', 'Bugs', 'bugs', 'modified_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('3574d1d1-ac3c-31c0-5c8c-5f003e968311', 'prospect_list_contacts', 'ProspectLists', 'prospect_lists', 'id', 'Contacts', 'contacts', 'id', 'prospect_lists_prospects', 'prospect_list_id', 'related_id', 'many-to-many', 'related_type', 'Contacts', 0, 0),
('3632b152-6cad-3b8f-5561-5f003e9d16b8', 'aos_quotes_created_by', 'Users', 'users', 'id', 'AOS_Quotes', 'aos_quotes', 'created_by', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('36766da1-e177-f05e-9938-5f003e4efbe5', 'prospect_list_prospects', 'ProspectLists', 'prospect_lists', 'id', 'Prospects', 'prospects', 'id', 'prospect_lists_prospects', 'prospect_list_id', 'related_id', 'many-to-many', 'related_type', 'Prospects', 0, 0),
('369aad91-b354-a60c-c6bb-5f003ea493e9', 'bugs_created_by', 'Users', 'users', 'id', 'Bugs', 'bugs', 'created_by', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('37087c86-f063-e65d-7208-5f003e9bd9b9', 'aos_quotes_assigned_user', 'Users', 'users', 'id', 'AOS_Quotes', 'aos_quotes', 'assigned_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('377983ad-e411-d3b5-daa8-5f003e8530b6', 'prospect_list_leads', 'ProspectLists', 'prospect_lists', 'id', 'Leads', 'leads', 'id', 'prospect_lists_prospects', 'prospect_list_id', 'related_id', 'many-to-many', 'related_type', 'Leads', 0, 0),
('37991452-75a8-1303-1d80-5f003e740066', 'bugs_assigned_user', 'Users', 'users', 'id', 'Bugs', 'bugs', 'assigned_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('379c0938-3bcc-f23f-92a7-5f003e21c87d', 'stic_events_fp_event_locations', 'FP_Event_Locations', 'fp_event_locations', 'id', 'stic_Events', 'stic_events', 'id', 'stic_events_fp_event_locations_c', 'stic_events_fp_event_locationsfp_event_locations_ida', 'stic_events_fp_event_locationsstic_events_idb', 'many-to-many', NULL, NULL, 0, 0),
('37cc3364-054d-201f-6ca7-5f003e65679e', 'securitygroups_aos_quotes', 'SecurityGroups', 'securitygroups', 'id', 'AOS_Quotes', 'aos_quotes', 'id', 'securitygroups_records', 'securitygroup_id', 'record_id', 'many-to-many', 'module', 'AOS_Quotes', 0, 0),
('3835c6a5-111d-cb17-f724-5f003eda1cea', 'aos_quotes_aos_product_quotes', 'AOS_Quotes', 'aos_quotes', 'id', 'AOS_Products_Quotes', 'aos_products_quotes', 'parent_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('38960d3b-9b01-120d-b117-5f003e966856', 'securitygroups_bugs', 'SecurityGroups', 'securitygroups', 'id', 'Bugs', 'bugs', 'id', 'securitygroups_records', 'securitygroup_id', 'record_id', 'many-to-many', 'module', 'Bugs', 0, 0),
('38b50ed7-7714-be34-9a84-5f003ed9caf3', 'aos_products_quotes_modified_user', 'Users', 'users', 'id', 'AOS_Products_Quotes', 'aos_products_quotes', 'modified_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('38eea1a0-4acc-ce1d-9b08-5f003e0c6f42', 'aos_quotes_aos_line_item_groups', 'AOS_Quotes', 'aos_quotes', 'id', 'AOS_Line_Item_Groups', 'aos_line_item_groups', 'parent_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('39210f2c-aced-4c1d-ce7b-5f003ed1605e', 'meetings_modified_user', 'Users', 'users', 'id', 'Meetings', 'meetings', 'modified_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('39a5a87d-db56-a1a6-8a51-5f003e662a52', 'inbound_email_created_by', 'Users', 'users', 'id', 'InboundEmail', 'inbound_email', 'created_by', NULL, NULL, NULL, 'one-to-one', NULL, NULL, 0, 0),
('39a61874-a5c8-026e-bfa7-5f003ed4b99d', 'bug_tasks', 'Bugs', 'bugs', 'id', 'Tasks', 'tasks', 'parent_id', NULL, NULL, NULL, 'one-to-many', 'parent_type', 'Bugs', 0, 0),
('3a88448d-645a-3163-d2e9-5f003e274203', 'bug_meetings', 'Bugs', 'bugs', 'id', 'Meetings', 'meetings', 'parent_id', NULL, NULL, NULL, 'one-to-many', 'parent_type', 'Bugs', 0, 0),
('3ac2cce8-8740-ae1a-eacd-5f003e08fe22', 'inbound_email_modified_user_id', 'Users', 'users', 'id', 'InboundEmail', 'inbound_email', 'modified_user_id', NULL, NULL, NULL, 'one-to-one', NULL, NULL, 0, 0),
('3b419a4b-8b31-ab73-32ab-5f003ed2e8af', 'aow_actions_modified_user', 'Users', 'users', 'id', 'AOW_Actions', 'aow_actions', 'modified_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('3c3df349-dd35-3437-8093-5f003e77aca0', 'bug_calls', 'Bugs', 'bugs', 'id', 'Calls', 'calls', 'parent_id', NULL, NULL, NULL, 'one-to-many', 'parent_type', 'Bugs', 0, 0),
('3c4ebf95-a988-d827-726b-5f003e6cda10', 'aow_actions_created_by', 'Users', 'users', 'id', 'AOW_Actions', 'aow_actions', 'created_by', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('3d1e0cd5-30ce-b955-db1e-5f003ee8984c', 'stic_settings_created_by', 'Users', 'users', 'id', 'stic_Settings', 'stic_settings', 'created_by', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('3d45b5ff-ed60-497b-f849-5f003e15c3fe', 'bug_emails', 'Bugs', 'bugs', 'id', 'Emails', 'emails', 'parent_id', NULL, NULL, NULL, 'one-to-many', 'parent_type', 'Bugs', 0, 0),
('3d659d6f-1763-eb11-98ea-5f003e2bb9de', 'prospect_list_users', 'ProspectLists', 'prospect_lists', 'id', 'Users', 'users', 'id', 'prospect_lists_prospects', 'prospect_list_id', 'related_id', 'many-to-many', 'related_type', 'Users', 0, 0),
('3d7c909c-e116-e598-8e9a-5f003e68005d', 'saved_search_assigned_user', 'Users', 'users', 'id', 'SavedSearch', 'saved_search', 'assigned_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('3e1eab34-d9dc-6f04-f536-5f003e527883', 'bug_notes', 'Bugs', 'bugs', 'id', 'Notes', 'notes', 'parent_id', NULL, NULL, NULL, 'one-to-many', 'parent_type', 'Bugs', 0, 0),
('3ef98f35-b053-7352-f036-5f003edfe443', 'bugs_release', 'Releases', 'releases', 'id', 'Bugs', 'bugs', 'found_in_release', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('3f1cfcae-1b8b-6b53-249c-5f003e2d2abd', 'aow_workflow_modified_user', 'Users', 'users', 'id', 'AOW_WorkFlow', 'aow_workflow', 'modified_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('3fcc57b5-fde7-975e-de42-5f003e493232', 'bugs_fixed_in_release', 'Releases', 'releases', 'id', 'Bugs', 'bugs', 'fixed_in_release', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('40538d03-f948-e54b-8f5d-5f003e14d2a2', 'dha_plantillasdocumentos_modified_user', 'Users', 'users', 'id', 'DHA_PlantillasDocumentos', 'dha_plantillasdocumentos', 'modified_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('41156331-89df-b1c8-51e7-5f003e1bde83', 'user_direct_reports', 'Users', 'users', 'id', 'Users', 'users', 'reports_to_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('4201b965-9f58-c917-783a-5f003e2b5c61', 'users_email_addresses', 'Users', 'users', 'id', 'EmailAddresses', 'email_addresses', 'id', 'email_addr_bean_rel', 'bean_id', 'email_address_id', 'many-to-many', 'bean_module', 'Users', 0, 0),
('42609d96-427e-4413-2236-5f003e8ec594', 'spots_modified_user', 'Users', 'users', 'id', 'Spots', 'spots', 'modified_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('430ac857-8911-ba0e-7d5e-5f003e0c09c3', 'users_email_addresses_primary', 'Users', 'users', 'id', 'EmailAddresses', 'email_addresses', 'id', 'email_addr_bean_rel', 'bean_id', 'email_address_id', 'many-to-many', 'primary_address', '1', 0, 0),
('43b2bec4-e801-d006-938f-5f003e0b705b', 'spots_created_by', 'Users', 'users', 'id', 'Spots', 'spots', 'created_by', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('446a0efa-3e96-5697-825f-5f003eb20a62', 'stic_payment_commitments_contacts', 'Contacts', 'contacts', 'id', 'stic_Payment_Commitments', 'stic_payment_commitments', 'id', 'stic_payment_commitments_contacts_c', 'stic_payment_commitments_contactscontacts_ida', 'stic_payment_commitments_contactsstic_payment_commitments_idb', 'many-to-many', NULL, NULL, 0, 0),
('4473024a-9ec2-6776-4696-5f003e5c5921', 'spots_assigned_user', 'Users', 'users', 'id', 'Spots', 'spots', 'assigned_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('44d3849f-4d57-44fd-ec80-5f003eda7544', 'securitygroups_spots', 'SecurityGroups', 'securitygroups', 'id', 'Spots', 'spots', 'id', 'securitygroups_records', 'securitygroup_id', 'record_id', 'many-to-many', 'module', 'Spots', 0, 0),
('4521252e-39ae-e003-506d-5f003e807133', 'campaignlog_contact', 'CampaignLog', 'campaign_log', 'related_id', 'Contacts', 'contacts', 'id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('467607d8-032b-16ef-b692-5f003edb83d8', 'campaignlog_lead', 'CampaignLog', 'campaign_log', 'related_id', 'Leads', 'leads', 'id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('46ac22e0-f108-b932-c227-5f003e35904b', 'aobh_businesshours_modified_user', 'Users', 'users', 'id', 'AOBH_BusinessHours', 'aobh_businesshours', 'modified_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('46c0050b-538d-5a4e-8f95-5f003e957a96', 'stic_registrations_contacts', 'Contacts', 'contacts', 'id', 'stic_Registrations', 'stic_registrations', 'id', 'stic_registrations_contacts_c', 'stic_registrations_contactscontacts_ida', 'stic_registrations_contactsstic_registrations_idb', 'many-to-many', NULL, NULL, 0, 0),
('475719ff-c150-4549-3067-5f003e004db9', 'campaignlog_created_opportunities', 'CampaignLog', 'campaign_log', 'related_id', 'Opportunities', 'opportunities', 'id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('47ad3c02-692a-6456-de78-5f003e16f838', 'prospect_list_accounts', 'ProspectLists', 'prospect_lists', 'id', 'Accounts', 'accounts', 'id', 'prospect_lists_prospects', 'prospect_list_id', 'related_id', 'many-to-many', 'related_type', 'Accounts', 0, 0),
('47ee1c2b-fd67-4f7b-f697-5f003e55b6ba', 'aobh_businesshours_created_by', 'Users', 'users', 'id', 'AOBH_BusinessHours', 'aobh_businesshours', 'created_by', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('482a212d-fc41-cdc1-f769-5f003e56a3f1', 'campaignlog_targeted_users', 'CampaignLog', 'campaign_log', 'target_id', 'Users', 'users', 'id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('48bd194d-4517-6f47-2a26-5f003e1ea413', 'roles_users', 'Roles', 'roles', 'id', 'Users', 'users', 'id', 'roles_users', 'role_id', 'user_id', 'many-to-many', NULL, NULL, 0, 0),
('48ecef58-252c-6c1c-7944-5f003e17abe6', 'campaignlog_sent_emails', 'CampaignLog', 'campaign_log', 'related_id', 'Emails', 'emails', 'id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('499d276f-c23a-33c1-40ff-5f003edd862b', 'sugarfeed_modified_user', 'Users', 'users', 'id', 'SugarFeed', 'sugarfeed', 'modified_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('49a3b0a7-e2da-d440-d3bd-5f003ee169e4', 'projects_bugs', 'Project', 'project', 'id', 'Bugs', 'bugs', 'id', 'projects_bugs', 'project_id', 'bug_id', 'many-to-many', NULL, NULL, 0, 0),
('4a2d0485-79a3-f6ee-89b7-5f003e501103', 'meetings_created_by', 'Users', 'users', 'id', 'Meetings', 'meetings', 'created_by', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('4a770b45-168d-8175-422e-5f003e22ccaa', 'oauth2clients_created_by', 'Users', 'users', 'id', 'OAuth2Clients', 'oauth2clients', 'created_by', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('4a8d7ed4-9820-6e6e-c37d-5f003eab1170', 'sugarfeed_created_by', 'Users', 'users', 'id', 'SugarFeed', 'sugarfeed', 'created_by', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('4b5b8115-83fa-0f12-a01f-5f003e4b94dd', 'sugarfeed_assigned_user', 'Users', 'users', 'id', 'SugarFeed', 'sugarfeed', 'assigned_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('4b671ad1-9df7-32c9-670e-5f003e11223c', 'aow_workflow_created_by', 'Users', 'users', 'id', 'AOW_WorkFlow', 'aow_workflow', 'created_by', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('4c01284a-4d6c-af2f-007d-5f003ea0ccd7', 'securitygroups_project', 'SecurityGroups', 'securitygroups', 'id', 'Project', 'project', 'id', 'securitygroups_records', 'securitygroup_id', 'record_id', 'many-to-many', 'module', 'Project', 0, 0),
('4c827e78-07ca-e32d-4c57-5f003ec996d3', 'aow_workflow_assigned_user', 'Users', 'users', 'id', 'AOW_WorkFlow', 'aow_workflow', 'assigned_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('4d5e200f-3efc-0986-f445-5f003e4c492b', 'securitygroups_aow_workflow', 'SecurityGroups', 'securitygroups', 'id', 'AOW_WorkFlow', 'aow_workflow', 'id', 'securitygroups_records', 'securitygroup_id', 'record_id', 'many-to-many', 'module', 'AOW_WorkFlow', 0, 0),
('4d6f9c1b-893a-bc0b-da3d-5f003ee28e16', 'projects_notes', 'Project', 'project', 'id', 'Notes', 'notes', 'parent_id', NULL, NULL, NULL, 'one-to-many', 'parent_type', 'Project', 0, 0),
('4dbeb8d0-f659-64ac-c1f7-5f003e98b4b2', 'aow_workflow_aow_conditions', 'AOW_WorkFlow', 'aow_workflow', 'id', 'AOW_Conditions', 'aow_conditions', 'aow_workflow_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('4e0c85c5-7710-060e-547a-5f003e51aeb5', 'eapm_modified_user', 'Users', 'users', 'id', 'EAPM', 'eapm', 'modified_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('4e68ad83-b03f-888b-42c6-5f003eae8c67', 'projects_tasks', 'Project', 'project', 'id', 'Tasks', 'tasks', 'parent_id', NULL, NULL, NULL, 'one-to-many', 'parent_type', 'Project', 0, 0),
('4e8d3934-805a-8bba-0024-5f003eb27129', 'aow_workflow_aow_actions', 'AOW_WorkFlow', 'aow_workflow', 'id', 'AOW_Actions', 'aow_actions', 'aow_workflow_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('4f12d72d-7d1a-89e7-10e0-5f003ef10e30', 'eapm_created_by', 'Users', 'users', 'id', 'EAPM', 'eapm', 'created_by', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('4f4b9ba6-0f51-488a-640d-5f003ea151a9', 'aow_workflow_aow_processed', 'AOW_WorkFlow', 'aow_workflow', 'id', 'AOW_Processed', 'aow_processed', 'aow_workflow_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('4f4e4149-3723-9221-8f74-5f003eb43ad9', 'projects_meetings', 'Project', 'project', 'id', 'Meetings', 'meetings', 'parent_id', NULL, NULL, NULL, 'one-to-many', 'parent_type', 'Project', 0, 0),
('4fb2d382-998e-d895-8775-5f003edf7b1c', 'projects_calls', 'Project', 'project', 'id', 'Calls', 'calls', 'parent_id', NULL, NULL, NULL, 'one-to-many', 'parent_type', 'Project', 0, 0),
('4fd65469-542a-7c7a-16b4-5f003eb4e72c', 'eapm_assigned_user', 'Users', 'users', 'id', 'EAPM', 'eapm', 'assigned_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('507aee39-8eb5-73ee-1a13-5f003e0c2a5a', 'projects_emails', 'Project', 'project', 'id', 'Emails', 'emails', 'parent_id', NULL, NULL, NULL, 'one-to-many', 'parent_type', 'Project', 0, 0),
('50e8ba94-f6e3-3f31-ca3d-5f003e098e9a', 'aow_processed_modified_user', 'Users', 'users', 'id', 'AOW_Processed', 'aow_processed', 'modified_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('5176a794-8d41-ccc6-fd07-5f003edc694c', 'projects_project_tasks', 'Project', 'project', 'id', 'ProjectTask', 'project_task', 'project_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('51b52e83-00a7-50cb-1517-5f003e84f0c6', 'projects_cases', 'Project', 'project', 'id', 'Cases', 'cases', 'id', 'projects_cases', 'project_id', 'case_id', 'many-to-many', NULL, NULL, 0, 0),
('51b641c7-3119-3a0c-141c-5f003e36dc0c', 'oauthkeys_modified_user', 'Users', 'users', 'id', 'OAuthKeys', 'oauth_consumer', 'modified_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('52615d9e-8be8-84a9-cc51-5f003e43cd5d', 'projects_assigned_user', 'Users', 'users', 'id', 'Project', 'project', 'assigned_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('52aab79d-3cc3-6c16-32cd-5f003ed76560', 'oauthkeys_created_by', 'Users', 'users', 'id', 'OAuthKeys', 'oauth_consumer', 'created_by', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('532c3acb-2c95-1036-ff05-5f003e5a4fc4', 'projects_accounts', 'Project', 'project', 'id', 'Accounts', 'accounts', 'id', 'projects_accounts', 'project_id', 'account_id', 'many-to-many', NULL, NULL, 0, 0),
('535cea56-e828-7787-9b8f-5f003e75da48', 'projects_modified_user', 'Users', 'users', 'id', 'Project', 'project', 'modified_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('538feb46-aa37-8a2c-6046-5f003e3fefb6', 'oauthkeys_assigned_user', 'Users', 'users', 'id', 'OAuthKeys', 'oauth_consumer', 'assigned_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('53f80242-5f0f-e066-a7e6-5f003ed79572', 'projects_contacts', 'Project', 'project', 'id', 'Contacts', 'contacts', 'id', 'projects_contacts', 'project_id', 'contact_id', 'many-to-many', NULL, NULL, 0, 0),
('543ed83b-ac90-30ef-387a-5f003e9aaf64', 'projects_created_by', 'Users', 'users', 'id', 'Project', 'project', 'created_by', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('54cb3cf9-ebf8-4e96-7180-5f003e4227c8', 'projects_opportunities', 'Project', 'project', 'id', 'Opportunities', 'opportunities', 'id', 'projects_opportunities', 'project_id', 'opportunity_id', 'many-to-many', NULL, NULL, 0, 0),
('552ad412-b02f-48ff-5a11-5f003e7cd6d6', 'consumer_tokens', 'OAuthKeys', 'oauth_consumer', 'id', 'OAuthTokens', 'oauth_tokens', 'consumer', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('55aaefb4-daaf-bd5e-cf1a-5f003e30bf9a', 'acl_roles_actions', 'ACLRoles', 'acl_roles', 'id', 'ACLActions', 'acl_actions', 'id', 'acl_roles_actions', 'role_id', 'action_id', 'many-to-many', NULL, NULL, 0, 0),
('561a462b-138f-441d-a126-5f003e469533', 'dha_plantillasdocumentos_created_by', 'Users', 'users', 'id', 'DHA_PlantillasDocumentos', 'dha_plantillasdocumentos', 'created_by', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('561aef98-7a80-e4e2-9817-5f003e2ed39e', 'oauthtokens_assigned_user', 'Users', 'users', 'id', 'OAuthTokens', 'oauth_tokens', 'assigned_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('567f0d40-a6b0-967d-0112-5f003e6c8e8f', 'stic_settings_assigned_user', 'Users', 'users', 'id', 'stic_Settings', 'stic_settings', 'assigned_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('56a8a599-0e0c-72bb-2e1f-5f003eedb633', 'acl_roles_users', 'ACLRoles', 'acl_roles', 'id', 'Users', 'users', 'id', 'acl_roles_users', 'role_id', 'user_id', 'many-to-many', NULL, NULL, 0, 0),
('571102bb-4e76-81bc-97cc-5f003ed28c64', 'dha_plantillasdocumentos_assigned_user', 'Users', 'users', 'id', 'DHA_PlantillasDocumentos', 'dha_plantillasdocumentos', 'assigned_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('57a24528-657c-e168-cbac-5f003e84aede', 'securitygroups_projecttask', 'SecurityGroups', 'securitygroups', 'id', 'ProjectTask', 'project_task', 'id', 'securitygroups_records', 'securitygroup_id', 'record_id', 'many-to-many', 'module', 'ProjectTask', 0, 0),
('58070bf3-28e4-38ac-20c9-5f003e0df8e9', 'meetings_assigned_user', 'Users', 'users', 'id', 'Meetings', 'meetings', 'assigned_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('58665e26-6703-b8f5-dc98-5f003ec9d488', 'am_projecttemplates_modified_user', 'Users', 'users', 'id', 'AM_ProjectTemplates', 'am_projecttemplates', 'modified_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('58c0f746-1dd0-a503-7ef5-5f003efe08ae', 'project_tasks_notes', 'ProjectTask', 'project_task', 'id', 'Notes', 'notes', 'parent_id', NULL, NULL, NULL, 'one-to-many', 'parent_type', 'ProjectTask', 0, 0),
('596923b9-7d50-b395-583d-5f003e3b77c5', 'am_projecttemplates_created_by', 'Users', 'users', 'id', 'AM_ProjectTemplates', 'am_projecttemplates', 'created_by', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('599d9843-206e-8a9c-0ef2-5f003e8bb004', 'project_tasks_tasks', 'ProjectTask', 'project_task', 'id', 'Tasks', 'tasks', 'parent_id', NULL, NULL, NULL, 'one-to-many', 'parent_type', 'ProjectTask', 0, 0),
('5a1a0619-8ba8-b395-bb02-5f003ef050cb', 'oauth2clients_oauth2tokens', 'OAuth2Clients', 'oauth2clients', 'id', 'OAuth2Tokens', 'oauth2tokens', 'client', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('5a56199a-ea4f-9f5a-a83e-5f003ef5e37c', 'am_projecttemplates_assigned_user', 'Users', 'users', 'id', 'AM_ProjectTemplates', 'am_projecttemplates', 'assigned_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('5a67c33b-a265-622f-eab7-5f003e5ce74e', 'project_tasks_meetings', 'ProjectTask', 'project_task', 'id', 'Meetings', 'meetings', 'parent_id', NULL, NULL, NULL, 'one-to-many', 'parent_type', 'ProjectTask', 0, 0),
('5b35a251-c355-ea79-eb6b-5f003e87fdfb', 'project_tasks_calls', 'ProjectTask', 'project_task', 'id', 'Calls', 'calls', 'parent_id', NULL, NULL, NULL, 'one-to-many', 'parent_type', 'ProjectTask', 0, 0),
('5c053696-8f47-3c78-6272-5f003e63afa3', 'project_tasks_emails', 'ProjectTask', 'project_task', 'id', 'Emails', 'emails', 'parent_id', NULL, NULL, NULL, 'one-to-many', 'parent_type', 'ProjectTask', 0, 0),
('5c802ecd-c12d-aa69-5ae5-5f003e5724b1', 'project_tasks_assigned_user', 'Users', 'users', 'id', 'ProjectTask', 'project_task', 'assigned_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('5ca797b4-00e7-16fd-705d-5f003ec5bf61', 'am_tasktemplates_modified_user', 'Users', 'users', 'id', 'AM_TaskTemplates', 'am_tasktemplates', 'modified_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('5cba49d5-1a6d-271b-3a72-5f003e8c6cc9', 'aow_processed_created_by', 'Users', 'users', 'id', 'AOW_Processed', 'aow_processed', 'created_by', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('5ccf0920-024b-34f3-6afe-5f003e3f624b', 'kreports_modified_user', 'Users', 'users', 'id', 'KReports', 'kreports', 'modified_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('5d5b17c7-dc01-0aa1-7857-5f003edfcdc9', 'project_tasks_modified_user', 'Users', 'users', 'id', 'ProjectTask', 'project_task', 'modified_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('5db35dd2-1859-72b0-8370-5f003ebf9f8f', 'am_tasktemplates_created_by', 'Users', 'users', 'id', 'AM_TaskTemplates', 'am_tasktemplates', 'created_by', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('5e898130-46d6-d11e-77da-5f003e9b71b0', 'am_tasktemplates_assigned_user', 'Users', 'users', 'id', 'AM_TaskTemplates', 'am_tasktemplates', 'assigned_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('5eb3256f-3ab3-7d27-14df-5f003e78db18', 'project_tasks_created_by', 'Users', 'users', 'id', 'ProjectTask', 'project_task', 'created_by', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('5f2d20e3-6380-f776-e987-5f003eabbd3d', 'aow_conditions_modified_user', 'Users', 'users', 'id', 'AOW_Conditions', 'aow_conditions', 'modified_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('6024634a-84fd-1b49-630b-5f003ed06677', 'favorites_modified_user', 'Users', 'users', 'id', 'Favorites', 'favorites', 'modified_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('602ef4bb-71fa-3bbc-a2a0-5f003e1c78f8', 'aow_conditions_created_by', 'Users', 'users', 'id', 'AOW_Conditions', 'aow_conditions', 'created_by', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('60fd0805-f2a7-7fcf-aae6-5f003ef67c99', 'accounts_opportunities_1', 'Accounts', 'accounts', 'id', 'Opportunities', 'opportunities', 'id', 'accounts_opportunities_1_c', 'accounts_opportunities_1accounts_ida', 'accounts_opportunities_1opportunities_idb', 'many-to-many', NULL, NULL, 0, 0),
('6100b6a1-af13-3a7f-5205-5f003ece30ea', 'favorites_created_by', 'Users', 'users', 'id', 'Favorites', 'favorites', 'created_by', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('61d88dc9-7125-d4cb-c531-5f003ec00d82', 'favorites_assigned_user', 'Users', 'users', 'id', 'Favorites', 'favorites', 'assigned_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('6207280d-f4fa-9ac0-5b92-5f003edc559d', 'campaigns_modified_user', 'Users', 'users', 'id', 'Campaigns', 'campaigns', 'modified_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('633f2349-d13d-8e93-cd2d-5f003ecdfbb5', 'campaigns_created_by', 'Users', 'users', 'id', 'Campaigns', 'campaigns', 'created_by', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('63c29a85-c693-f093-3c37-5f003e28310c', 'jjwg_maps_modified_user', 'Users', 'users', 'id', 'jjwg_Maps', 'jjwg_maps', 'modified_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('63d29c10-ae8b-5830-ddc2-5f003e985b7c', 'aok_knowledge_base_categories_modified_user', 'Users', 'users', 'id', 'AOK_Knowledge_Base_Categories', 'aok_knowledge_base_categories', 'modified_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('642a1c60-3cf6-a8b6-f5bc-5f003e18cd11', 'campaigns_assigned_user', 'Users', 'users', 'id', 'Campaigns', 'campaigns', 'assigned_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('64a0023e-a701-8a8e-d3c6-5f003e8464bb', 'securitygroups_meetings', 'SecurityGroups', 'securitygroups', 'id', 'Meetings', 'meetings', 'id', 'securitygroups_records', 'securitygroup_id', 'record_id', 'many-to-many', 'module', 'Meetings', 0, 0),
('64d076d1-f8e6-536c-c68d-5f003e76e719', 'aok_knowledge_base_categories_created_by', 'Users', 'users', 'id', 'AOK_Knowledge_Base_Categories', 'aok_knowledge_base_categories', 'created_by', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('64ecd188-878c-8333-14c9-5f003e365505', 'jjwg_maps_created_by', 'Users', 'users', 'id', 'jjwg_Maps', 'jjwg_maps', 'created_by', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('6509e1ab-1252-cf4f-bbee-5f003eb7b379', 'securitygroups_campaigns', 'SecurityGroups', 'securitygroups', 'id', 'Campaigns', 'campaigns', 'id', 'securitygroups_records', 'securitygroup_id', 'record_id', 'many-to-many', 'module', 'Campaigns', 0, 0),
('65be124e-d762-3927-4413-5f003e4ec4f1', 'jjwg_maps_assigned_user', 'Users', 'users', 'id', 'jjwg_Maps', 'jjwg_maps', 'assigned_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('65d9a171-6b8d-389d-351f-5f003ee9fcad', 'campaign_accounts', 'Campaigns', 'campaigns', 'id', 'Accounts', 'accounts', 'campaign_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('66079229-0e1c-1189-b51a-5f003eba92e6', 'aok_knowledge_base_categories_assigned_user', 'Users', 'users', 'id', 'AOK_Knowledge_Base_Categories', 'aok_knowledge_base_categories', 'assigned_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('661db281-6f4e-ff0a-511a-5f003eb03862', 'securitygroups_jjwg_maps', 'SecurityGroups', 'securitygroups', 'id', 'jjwg_Maps', 'jjwg_maps', 'id', 'securitygroups_records', 'securitygroup_id', 'record_id', 'many-to-many', 'module', 'jjwg_Maps', 0, 0),
('662400c3-2f2f-a2eb-2d62-5f003ef3ddff', 'securitygroups_stic_settings', 'SecurityGroups', 'securitygroups', 'id', 'stic_Settings', 'stic_settings', 'id', 'securitygroups_records', 'securitygroup_id', 'record_id', 'many-to-many', 'module', 'stic_Settings', 0, 0),
('66b02339-43b9-c4f2-f18e-5f003e2e01d1', 'campaign_contacts', 'Campaigns', 'campaigns', 'id', 'Contacts', 'contacts', 'campaign_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('66d7c236-d832-6c27-d608-5f003eaab337', 'jjwg_Maps_accounts', 'jjwg_Maps', 'jjwg_Maps', 'parent_id', 'Accounts', 'accounts', 'id', NULL, NULL, NULL, 'one-to-many', 'parent_type', 'Accounts', 0, 0),
('6705dfa8-dcbe-6011-e0af-5f003e2e234b', 'campaign_leads', 'Campaigns', 'campaigns', 'id', 'Leads', 'leads', 'campaign_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('679f3be2-d58b-18a9-6e0b-5f003e53482b', 'jjwg_Maps_contacts', 'jjwg_Maps', 'jjwg_Maps', 'parent_id', 'Contacts', 'contacts', 'id', NULL, NULL, NULL, 'one-to-many', 'parent_type', 'Contacts', 0, 0),
('67f1ace8-636b-cc66-dfc5-5f003e5e999c', 'campaign_prospects', 'Campaigns', 'campaigns', 'id', 'Prospects', 'prospects', 'campaign_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('67fe3c77-dc62-eefd-9d50-5f003e2ecb95', 'jjwg_Maps_leads', 'jjwg_Maps', 'jjwg_Maps', 'parent_id', 'Leads', 'leads', 'id', NULL, NULL, NULL, 'one-to-many', 'parent_type', 'Leads', 0, 0),
('68724bc8-4a3d-f656-74b6-5f003e75100d', 'aok_knowledgebase_modified_user', 'Users', 'users', 'id', 'AOK_KnowledgeBase', 'aok_knowledgebase', 'modified_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('68c03878-51b2-f800-1e9f-5f003ee08c56', 'jjwg_Maps_opportunities', 'jjwg_Maps', 'jjwg_Maps', 'parent_id', 'Opportunities', 'opportunities', 'id', NULL, NULL, NULL, 'one-to-many', 'parent_type', 'Opportunities', 0, 0),
('68c31554-dc97-e41d-59fc-5f003ea17801', 'campaign_opportunities', 'Campaigns', 'campaigns', 'id', 'Opportunities', 'opportunities', 'campaign_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('68f20484-643b-44b8-0d49-5f003e873626', 'oauth2clients_assigned_user', 'Users', 'users', 'id', 'OAuth2Clients', 'oauth2clients', 'assigned_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('698c1a25-a835-cde3-1417-5f003ef88858', 'jjwg_Maps_cases', 'jjwg_Maps', 'jjwg_Maps', 'parent_id', 'Cases', 'cases', 'id', NULL, NULL, NULL, 'one-to-many', 'parent_type', 'Cases', 0, 0),
('69a52c2f-3546-7f5f-fa3b-5f003e43dca2', 'campaign_email_marketing', 'Campaigns', 'campaigns', 'id', 'EmailMarketing', 'email_marketing', 'campaign_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('69c706f4-0d93-8162-b548-5f003efc1390', 'aok_knowledgebase_created_by', 'Users', 'users', 'id', 'AOK_KnowledgeBase', 'aok_knowledgebase', 'created_by', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('69f56c88-d4ec-800a-b349-5f003ea33e6b', 'jjwg_Maps_projects', 'jjwg_Maps', 'jjwg_Maps', 'parent_id', 'Project', 'project', 'id', NULL, NULL, NULL, 'one-to-many', 'parent_type', 'Project', 0, 0),
('69f6dd5a-0761-c279-a4df-5f003e69dd22', 'campaign_emailman', 'Campaigns', 'campaigns', 'id', 'EmailMan', 'emailman', 'campaign_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('6aa6cbb0-8f70-6993-6e2e-5f003e9528d1', 'aok_knowledgebase_assigned_user', 'Users', 'users', 'id', 'AOK_KnowledgeBase', 'aok_knowledgebase', 'assigned_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('6ab897fe-ace8-ed14-deb9-5f003ec89ae1', 'jjwg_Maps_meetings', 'jjwg_Maps', 'jjwg_Maps', 'parent_id', 'Meetings', 'meetings', 'id', NULL, NULL, NULL, 'one-to-many', 'parent_type', 'Meetings', 0, 0),
('6ad5d701-6b09-3fa8-e8c2-5f003ef145f7', 'campaign_campaignlog', 'Campaigns', 'campaigns', 'id', 'CampaignLog', 'campaign_log', 'campaign_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0);
INSERT INTO `relationships` (`id`, `relationship_name`, `lhs_module`, `lhs_table`, `lhs_key`, `rhs_module`, `rhs_table`, `rhs_key`, `join_table`, `join_key_lhs`, `join_key_rhs`, `relationship_type`, `relationship_role_column`, `relationship_role_column_value`, `reverse`, `deleted`) VALUES
('6b07592e-4ffa-115b-c72b-5f003e0a35ab', 'securitygroups_aok_knowledgebase', 'SecurityGroups', 'securitygroups', 'id', 'AOK_KnowledgeBase', 'aok_knowledgebase', 'id', 'securitygroups_records', 'securitygroup_id', 'record_id', 'many-to-many', 'module', 'AOK_KnowledgeBase', 0, 0),
('6b9df245-fc67-be8e-63e8-5f003ebf8b85', 'campaign_assigned_user', 'Users', 'users', 'id', 'Campaigns', 'campaigns', 'assigned_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('6bf6fb39-e9dc-1ddb-3ff5-5f003e2d9ca3', 'campaign_modified_user', 'Users', 'users', 'id', 'Campaigns', 'campaigns', 'modified_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('6c075acd-3e3d-66da-e0eb-5f003edcf429', 'jjwg_Maps_prospects', 'jjwg_Maps', 'jjwg_Maps', 'parent_id', 'Prospects', 'prospects', 'id', NULL, NULL, NULL, 'one-to-many', 'parent_type', 'Prospects', 0, 0),
('6cd1a2f9-6d2e-375a-b008-5f003e7a25a3', 'surveyresponses_campaigns', 'Campaigns', 'campaigns', 'id', 'SurveyResponses', 'surveyresponses', 'campaign_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('6db88659-b41f-b48a-9e24-5f003e488266', 'reminders_modified_user', 'Users', 'users', 'id', 'Reminders', 'reminders', 'modified_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('6e9d840b-fa9d-27ad-6a50-5f003ecf7653', 'jjwg_markers_modified_user', 'Users', 'users', 'id', 'jjwg_Markers', 'jjwg_markers', 'modified_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('6ec99b54-22e9-92f1-27e7-5f003e36af71', 'reminders_created_by', 'Users', 'users', 'id', 'Reminders', 'reminders', 'created_by', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('6f47019b-a0b9-2ea3-41b7-5f003ef9679a', 'leads_documents_1', 'Leads', 'leads', 'id', 'Documents', 'documents', 'id', 'leads_documents_1_c', 'leads_documents_1leads_ida', 'leads_documents_1documents_idb', 'many-to-many', NULL, NULL, 0, 0),
('6fb3034d-36ac-8d79-21e7-5f003e80eb33', 'reminders_assigned_user', 'Users', 'users', 'id', 'Reminders', 'reminders', 'assigned_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('6fe671ec-f15f-17af-2f3e-5f003e8f9674', 'prospectlists_assigned_user', 'Users', 'users', 'id', 'prospectlists', 'prospect_lists', 'assigned_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('71270a50-a24a-d541-94e3-5f003e0970c1', 'meetings_notes', 'Meetings', 'meetings', 'id', 'Notes', 'notes', 'parent_id', NULL, NULL, NULL, 'one-to-many', 'parent_type', 'Meetings', 0, 0),
('71e58984-0140-388b-1d31-5f003ea33341', 'reminders_invitees_modified_user', 'Users', 'users', 'id', 'Reminders_Invitees', 'reminders_invitees', 'modified_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('73009473-728e-2339-a0a3-5f003ede479f', 'reminders_invitees_created_by', 'Users', 'users', 'id', 'Reminders_Invitees', 'reminders_invitees', 'created_by', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('73c8bd4b-f4d4-5f82-d550-5f003e172644', 'reminders_invitees_assigned_user', 'Users', 'users', 'id', 'Reminders_Invitees', 'reminders_invitees', 'assigned_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('73e5fc7c-d507-6ca6-1620-5f003e878ec3', 'securitygroups_prospectlists', 'SecurityGroups', 'securitygroups', 'id', 'ProspectLists', 'prospect_lists', 'id', 'securitygroups_records', 'securitygroup_id', 'record_id', 'many-to-many', 'module', 'ProspectLists', 0, 0),
('7688f741-a8ad-d481-b705-5f003ec0af10', 'fp_events_modified_user', 'Users', 'users', 'id', 'FP_events', 'fp_events', 'modified_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('777c5feb-d56d-fc66-e49d-5f003eb21038', 'fp_events_created_by', 'Users', 'users', 'id', 'FP_events', 'fp_events', 'created_by', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('77a3ab79-1809-5426-fd92-5f003e69824c', 'prospects_modified_user', 'Users', 'users', 'id', 'Prospects', 'prospects', 'modified_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('785af2fc-503b-ac87-6a1b-5f003e456a63', 'fp_events_assigned_user', 'Users', 'users', 'id', 'FP_events', 'fp_events', 'assigned_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('78bb4fa6-4646-b6e1-a7a4-5f003ed3ea25', 'securitygroups_fp_events', 'SecurityGroups', 'securitygroups', 'id', 'FP_events', 'fp_events', 'id', 'securitygroups_records', 'securitygroup_id', 'record_id', 'many-to-many', 'module', 'FP_events', 0, 0),
('7930de37-8492-9db7-2730-5f003e81264a', 'email_marketing_prospect_lists', 'EmailMarketing', 'email_marketing', 'id', 'ProspectLists', 'prospect_lists', 'id', 'email_marketing_prospect_lists', 'email_marketing_id', 'prospect_list_id', 'many-to-many', NULL, NULL, 0, 0),
('7a5d9312-a4c3-023e-5c5c-5f003ea4a4d4', 'leads_documents', 'Leads', 'leads', 'id', 'Documents', 'documents', 'id', 'linked_documents', 'parent_id', 'document_id', 'many-to-many', 'parent_type', 'Leads', 0, 0),
('7a95164b-795d-131a-7a4c-5f003ec2d94b', 'jjwg_markers_created_by', 'Users', 'users', 'id', 'jjwg_Markers', 'jjwg_markers', 'created_by', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('7a98a763-e38d-d943-9501-5f003ec828b4', 'prospects_created_by', 'Users', 'users', 'id', 'Prospects', 'prospects', 'created_by', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('7b618f2e-98fa-8fcd-76e8-5f003ea39242', 'fp_event_locations_modified_user', 'Users', 'users', 'id', 'FP_Event_Locations', 'fp_event_locations', 'modified_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('7b7c78ae-7ea1-48cd-6fbb-5f003e5854e0', 'prospects_assigned_user', 'Users', 'users', 'id', 'Prospects', 'prospects', 'assigned_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('7b895a21-3e77-be30-e8b6-5f003eb34956', 'jjwg_markers_assigned_user', 'Users', 'users', 'id', 'jjwg_Markers', 'jjwg_markers', 'assigned_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('7c4b79dc-0224-b954-17de-5f003e8a5bb6', 'securitygroups_prospects', 'SecurityGroups', 'securitygroups', 'id', 'Prospects', 'prospects', 'id', 'securitygroups_records', 'securitygroup_id', 'record_id', 'many-to-many', 'module', 'Prospects', 0, 0),
('7c6d1505-a736-9261-560d-5f003ed1806e', 'securitygroups_jjwg_markers', 'SecurityGroups', 'securitygroups', 'id', 'jjwg_Markers', 'jjwg_markers', 'id', 'securitygroups_records', 'securitygroup_id', 'record_id', 'many-to-many', 'module', 'jjwg_Markers', 0, 0),
('7c7c0291-893d-9828-b177-5f003e5ae571', 'stic_payments_activities_calls', 'stic_Payments', 'stic_payments', 'id', 'Calls', 'calls', 'parent_id', NULL, NULL, NULL, 'one-to-many', 'parent_type', 'stic_Payments', 0, 0),
('7c816cf7-a97b-48e1-5ab2-5f003eded831', 'fp_event_locations_created_by', 'Users', 'users', 'id', 'FP_Event_Locations', 'fp_event_locations', 'created_by', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('7d155168-f5bf-32fc-6a55-5f003e02c5b0', 'prospects_email_addresses', 'Prospects', 'prospects', 'id', 'EmailAddresses', 'email_addresses', 'id', 'email_addr_bean_rel', 'bean_id', 'email_address_id', 'many-to-many', 'bean_module', 'Prospects', 0, 0),
('7d6344b3-413a-fc9e-d2ca-5f003ece5ba3', 'fp_event_locations_assigned_user', 'Users', 'users', 'id', 'FP_Event_Locations', 'fp_event_locations', 'assigned_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('7d6bb65f-fe2b-31a9-e7f9-5f003e87a89e', 'prospects_email_addresses_primary', 'Prospects', 'prospects', 'id', 'EmailAddresses', 'email_addresses', 'id', 'email_addr_bean_rel', 'bean_id', 'email_address_id', 'many-to-many', 'primary_address', '1', 0, 0),
('7e03671f-3796-26de-db84-5f003e84e98e', 'documents_accounts', 'Documents', 'documents', 'id', 'Accounts', 'accounts', 'id', 'documents_accounts', 'document_id', 'account_id', 'many-to-many', NULL, NULL, 0, 0),
('7e357643-28a1-322c-6824-5f003e9f63fb', 'prospect_tasks', 'Prospects', 'prospects', 'id', 'Tasks', 'tasks', 'parent_id', NULL, NULL, NULL, 'one-to-many', 'parent_type', 'Prospects', 0, 0),
('7e3841d4-c7a3-92af-2c25-5f003eaa3a87', 'securitygroups_fp_event_locations', 'SecurityGroups', 'securitygroups', 'id', 'FP_Event_Locations', 'fp_event_locations', 'id', 'securitygroups_records', 'securitygroup_id', 'record_id', 'many-to-many', 'module', 'FP_Event_Locations', 0, 0),
('7e877662-11f8-2beb-2516-5f003ec76fd0', 'prospect_notes', 'Prospects', 'prospects', 'id', 'Notes', 'notes', 'parent_id', NULL, NULL, NULL, 'one-to-many', 'parent_type', 'Prospects', 0, 0),
('7eb8785b-174d-6659-ee28-5f003e08570c', 'optimistic_locking', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0),
('7f2c7f76-13f7-1fb6-6f38-5f003e26d87d', 'jjwg_areas_modified_user', 'Users', 'users', 'id', 'jjwg_Areas', 'jjwg_areas', 'modified_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('7f5744e8-5d90-5e8b-0be8-5f003e716365', 'prospect_meetings', 'Prospects', 'prospects', 'id', 'Meetings', 'meetings', 'parent_id', NULL, NULL, NULL, 'one-to-many', 'parent_type', 'Prospects', 0, 0),
('7f87bc33-9378-d6a1-8594-5f003e75b0d8', 'unified_search', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0),
('8019c123-7bce-d284-7617-5f003e4efc79', 'jjwg_areas_created_by', 'Users', 'users', 'id', 'jjwg_Areas', 'jjwg_areas', 'created_by', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('803065c2-8c79-7bfc-974f-5f003e911169', 'prospect_calls', 'Prospects', 'prospects', 'id', 'Calls', 'calls', 'parent_id', NULL, NULL, NULL, 'one-to-many', 'parent_type', 'Prospects', 0, 0),
('80900e67-6e34-e69a-7976-5f003e9d0514', 'product_categories', 'AOS_Product_Categories', 'aos_product_categories', 'id', 'AOS_Products', 'aos_products', 'aos_product_category_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('80a4747c-15a2-cd22-cd90-5f003e753123', 'prospect_emails', 'Prospects', 'prospects', 'id', 'Emails', 'emails', 'parent_id', NULL, NULL, NULL, 'one-to-many', 'parent_type', 'Prospects', 0, 0),
('80dd0f77-c041-9abe-eccb-5f003eb2e0b9', 'jjwg_areas_assigned_user', 'Users', 'users', 'id', 'jjwg_Areas', 'jjwg_areas', 'assigned_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('8164b25c-d4b8-f30b-42a0-5f003eff8d5d', 'prospect_campaign_log', 'Prospects', 'prospects', 'id', 'CampaignLog', 'campaign_log', 'target_id', NULL, NULL, NULL, 'one-to-many', 'target_type', 'Prospects', 0, 0),
('82321ca8-1089-7d3f-0d12-5f003e40386d', 'securitygroups_jjwg_areas', 'SecurityGroups', 'securitygroups', 'id', 'jjwg_Areas', 'jjwg_areas', 'id', 'securitygroups_records', 'securitygroup_id', 'record_id', 'many-to-many', 'module', 'jjwg_Areas', 0, 0),
('82532dd4-9ae7-1aa7-4421-5f003e81d8f1', 'aod_indexevent_modified_user', 'Users', 'users', 'id', 'AOD_IndexEvent', 'aod_indexevent', 'modified_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('82d3fcd1-539f-3fdb-9e46-5f003e799c34', 'kreports_created_by', 'Users', 'users', 'id', 'KReports', 'kreports', 'created_by', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('83c168ed-8975-8899-1ff8-5f003eb49bb8', 'kreports_assigned_user', 'Users', 'users', 'id', 'KReports', 'kreports', 'assigned_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('8431bf46-6169-fc80-a6ee-5f003e945d61', 'aod_indexevent_created_by', 'Users', 'users', 'id', 'AOD_IndexEvent', 'aod_indexevent', 'created_by', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('846138ed-00aa-42d2-e6d4-5f003e35ba94', 'securitygroups_emailmarketing', 'SecurityGroups', 'securitygroups', 'id', 'EmailMarketing', 'email_marketing', 'id', 'securitygroups_records', 'securitygroup_id', 'record_id', 'many-to-many', 'module', 'EmailMarketing', 0, 0),
('8492b3a0-7ed9-f8f2-afd6-5f003e45aa24', 'securitygroups_kreports', 'SecurityGroups', 'securitygroups', 'id', 'KReports', 'kreports', 'id', 'securitygroups_records', 'securitygroup_id', 'record_id', 'many-to-many', 'module', 'KReports', 0, 0),
('84dadfc8-bdb0-9218-4749-5f003e2d15b4', 'jjwg_address_cache_modified_user', 'Users', 'users', 'id', 'jjwg_Address_Cache', 'jjwg_address_cache', 'modified_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('851f659e-2d9f-4c09-f485-5f003e3849fb', 'aod_indexevent_assigned_user', 'Users', 'users', 'id', 'AOD_IndexEvent', 'aod_indexevent', 'assigned_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('866fab40-66cb-f260-7fb8-5f003e0ba0e7', 'email_template_email_marketings', 'EmailTemplates', 'email_templates', 'id', 'EmailMarketing', 'email_marketing', 'template_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('870e50d5-3054-6444-8e34-5f003e04653c', 'stic_accounts_relationships_modified_user', 'Users', 'users', 'id', 'stic_Accounts_Relationships', 'stic_accounts_relationships', 'modified_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('87eec16f-4bbd-dcdd-3dae-5f003e287f83', 'campaign_campaigntrakers', 'Campaigns', 'campaigns', 'id', 'CampaignTrackers', 'campaign_trkrs', 'campaign_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('87f40cf1-4a21-9e2b-8a61-5f003e608870', 'stic_contacts_relationships_contacts', 'Contacts', 'contacts', 'id', 'stic_Contacts_Relationships', 'stic_contacts_relationships', 'id', 'stic_contacts_relationships_contacts_c', 'stic_contacts_relationships_contactscontacts_ida', 'stic_contae394onships_idb', 'many-to-many', NULL, NULL, 0, 0),
('8961d160-dd5e-ef77-959a-5f003edfac11', 'aod_index_modified_user', 'Users', 'users', 'id', 'AOD_Index', 'aod_index', 'modified_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('8b1b9d2d-0840-51d2-1c1f-5f003e65b9ec', 'documents_contacts', 'Documents', 'documents', 'id', 'Contacts', 'contacts', 'id', 'documents_contacts', 'document_id', 'contact_id', 'many-to-many', NULL, NULL, 0, 0),
('8da45326-b977-812a-4fa5-5f003e6f756c', 'schedulers_created_by_rel', 'Users', 'users', 'id', 'Schedulers', 'schedulers', 'created_by', NULL, NULL, NULL, 'one-to-one', NULL, NULL, 0, 0),
('8ed52109-c8f3-7466-96df-5f003ef914c7', 'aod_index_created_by', 'Users', 'users', 'id', 'AOD_Index', 'aod_index', 'created_by', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('8f7c02a5-0bc3-b419-09e4-5f003ea58f56', 'jjwg_address_cache_created_by', 'Users', 'users', 'id', 'jjwg_Address_Cache', 'jjwg_address_cache', 'created_by', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('8fdf39c0-9b72-8766-23ef-5f003e270b44', 'aod_index_assigned_user', 'Users', 'users', 'id', 'AOD_Index', 'aod_index', 'assigned_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('9060d208-9c61-a558-cd28-5f003ea3aea8', 'jjwg_address_cache_assigned_user', 'Users', 'users', 'id', 'jjwg_Address_Cache', 'jjwg_address_cache', 'assigned_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('92449665-29bd-f4f9-244c-5f003e5682ef', 'aop_case_events_modified_user', 'Users', 'users', 'id', 'AOP_Case_Events', 'aop_case_events', 'modified_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('928f1761-ce7a-1030-b39f-5f003e2dce84', 'stic_accounts_relationships_created_by', 'Users', 'users', 'id', 'stic_Accounts_Relationships', 'stic_accounts_relationships', 'created_by', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('92c54813-06fe-dbb2-4c90-5f003ef6378b', 'calls_reschedule_modified_user', 'Users', 'users', 'id', 'Calls_Reschedule', 'calls_reschedule', 'modified_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('93898f38-6e4d-1885-ee06-5f003e9c4ae4', 'stic_accounts_relationships_assigned_user', 'Users', 'users', 'id', 'stic_Accounts_Relationships', 'stic_accounts_relationships', 'assigned_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('939d0a06-535a-6103-57d2-5f003e4abe07', 'stic_payment_commitments_project', 'Project', 'project', 'id', 'stic_Payment_Commitments', 'stic_payment_commitments', 'id', 'stic_payment_commitments_project_c', 'stic_payment_commitments_projectproject_ida', 'stic_payment_commitments_projectstic_payment_commitments_idb', 'many-to-many', NULL, NULL, 0, 0),
('93a3ffd7-ffd2-2f8b-3f81-5f003eed5505', 'documents_opportunities', 'Documents', 'documents', 'id', 'Opportunities', 'opportunities', 'id', 'documents_opportunities', 'document_id', 'opportunity_id', 'many-to-many', NULL, NULL, 0, 0),
('93f7fd44-7b48-505d-bc80-5f003e8c91ee', 'securitygroups_stic_accounts_relationships', 'SecurityGroups', 'securitygroups', 'id', 'stic_Accounts_Relationships', 'stic_accounts_relationships', 'id', 'securitygroups_records', 'securitygroup_id', 'record_id', 'many-to-many', 'module', 'stic_Accounts_Relationships', 0, 0),
('9460f859-a7ac-c971-ce03-5f003ed79d0e', 'schedulers_modified_user_id_rel', 'Users', 'users', 'id', 'Schedulers', 'schedulers', 'modified_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('95535c34-1d68-077b-341d-5f003eb1d4e5', 'schedulers_jobs_rel', 'Schedulers', 'schedulers', 'id', 'SchedulersJobs', 'job_queue', 'scheduler_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('955b7577-0e31-f271-f3c5-5f003e3e4760', 'aop_case_events_created_by', 'Users', 'users', 'id', 'AOP_Case_Events', 'aop_case_events', 'created_by', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('9671369d-946c-4b26-7078-5f003ecaad4f', 'stic_attendances_modified_user', 'Users', 'users', 'id', 'stic_Attendances', 'stic_attendances', 'modified_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('9676b13b-fce8-3a2d-b5fd-5f003e618ba8', 'aop_case_events_assigned_user', 'Users', 'users', 'id', 'AOP_Case_Events', 'aop_case_events', 'assigned_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('969ab64b-977b-b148-f1fa-5f003e28ba50', 'schedulersjobs_assigned_user', 'Users', 'users', 'id', 'SchedulersJobs', 'job_queue', 'assigned_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('9795101d-d3fa-a091-211c-5f003ec7eb6b', 'stic_attendances_created_by', 'Users', 'users', 'id', 'stic_Attendances', 'stic_attendances', 'created_by', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('97ab65be-583e-c9ea-ebb1-5f003eb347f7', 'cases_aop_case_events', 'Cases', 'cases', 'id', 'AOP_Case_Events', 'aop_case_events', 'case_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('986948e6-089e-6908-33cb-5f003eaed6b6', 'stic_attendances_assigned_user', 'Users', 'users', 'id', 'stic_Attendances', 'stic_attendances', 'assigned_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('9934117e-9003-6d4c-c7dc-5f003e7d8c91', 'securitygroups_stic_attendances', 'SecurityGroups', 'securitygroups', 'id', 'stic_Attendances', 'stic_attendances', 'id', 'securitygroups_records', 'securitygroup_id', 'record_id', 'many-to-many', 'module', 'stic_Attendances', 0, 0),
('9afcea88-7ff6-ec15-f9b1-5f003e92e62b', 'aop_case_updates_modified_user', 'Users', 'users', 'id', 'AOP_Case_Updates', 'aop_case_updates', 'modified_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('9b4173be-e7bb-c345-a0e1-5f003e2869df', 'contacts_modified_user', 'Users', 'users', 'id', 'Contacts', 'contacts', 'modified_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('9b46193c-f35e-dd3c-5b19-5f003eca9488', 'stic_contacts_relationships_modified_user', 'Users', 'users', 'id', 'stic_Contacts_Relationships', 'stic_contacts_relationships', 'modified_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('9c5d6e1b-de23-f87c-67a7-5f003e73fa95', 'stic_contacts_relationships_created_by', 'Users', 'users', 'id', 'stic_Contacts_Relationships', 'stic_contacts_relationships', 'created_by', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('9d32d449-7151-e92f-3e57-5f003ee3cfa3', 'stic_contacts_relationships_assigned_user', 'Users', 'users', 'id', 'stic_Contacts_Relationships', 'stic_contacts_relationships', 'assigned_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('9d3df36e-537e-e0dc-ed58-5f003e6abb39', 'aop_case_updates_created_by', 'Users', 'users', 'id', 'AOP_Case_Updates', 'aop_case_updates', 'created_by', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('9d608bc0-b74f-948c-a512-5f003e0d3e89', 'calls_reschedule_created_by', 'Users', 'users', 'id', 'Calls_Reschedule', 'calls_reschedule', 'created_by', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('9df7324e-8658-e0a6-823c-5f003e43f21a', 'securitygroups_stic_contacts_relationships', 'SecurityGroups', 'securitygroups', 'id', 'stic_Contacts_Relationships', 'stic_contacts_relationships', 'id', 'securitygroups_records', 'securitygroup_id', 'record_id', 'many-to-many', 'module', 'stic_Contacts_Relationships', 0, 0),
('9e4d1189-1713-f9b8-8017-5f003ea41fc4', 'aop_case_updates_assigned_user', 'Users', 'users', 'id', 'AOP_Case_Updates', 'aop_case_updates', 'assigned_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('9fbe0635-a42d-66c6-18c6-5f003e61393d', 'surveyresponses_modified_user', 'Users', 'users', 'id', 'SurveyResponses', 'surveyresponses', 'modified_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('a0ae36d1-7fce-11e7-fbd3-5f003e766b52', 'stic_events_modified_user', 'Users', 'users', 'id', 'stic_Events', 'stic_events', 'modified_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('a2240fee-3ea4-4354-3a43-5f003e67e7e0', 'tasks_modified_user', 'Users', 'users', 'id', 'Tasks', 'tasks', 'modified_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('a253309b-998e-27f4-64b8-5f003e8cff38', 'calls_reschedule_assigned_user', 'Users', 'users', 'id', 'Calls_Reschedule', 'calls_reschedule', 'assigned_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('a4427533-2169-acbe-6021-5f003ecff0c8', 'contacts_created_by', 'Users', 'users', 'id', 'Contacts', 'contacts', 'created_by', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('a444784c-786a-cea0-740f-5f003ea2f99b', 'securitygroups_modified_user', 'Users', 'users', 'id', 'SecurityGroups', 'securitygroups', 'modified_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('a525a2cb-c03e-08c5-1109-5f003e78caa0', 'contacts_assigned_user', 'Users', 'users', 'id', 'Contacts', 'contacts', 'assigned_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('a58058f9-8738-75dc-4973-5f003ef0515b', 'securitygroups_contacts', 'SecurityGroups', 'securitygroups', 'id', 'Contacts', 'contacts', 'id', 'securitygroups_records', 'securitygroup_id', 'record_id', 'many-to-many', 'module', 'Contacts', 0, 0),
('a67fdfe9-531c-d952-7c32-5f003e0ba4f3', 'contacts_email_addresses', 'Contacts', 'contacts', 'id', 'EmailAddresses', 'email_addresses', 'id', 'email_addr_bean_rel', 'bean_id', 'email_address_id', 'many-to-many', 'bean_module', 'Contacts', 0, 0),
('a74532d2-3acb-63d6-42b5-5f003ee26b05', 'contacts_email_addresses_primary', 'Contacts', 'contacts', 'id', 'EmailAddresses', 'email_addresses', 'id', 'email_addr_bean_rel', 'bean_id', 'email_address_id', 'many-to-many', 'primary_address', '1', 0, 0),
('a7fbeac5-9279-548f-a7ab-5f003ebb7cc8', 'cases_aop_case_updates', 'Cases', 'cases', 'id', 'AOP_Case_Updates', 'aop_case_updates', 'case_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('a80b8cb5-eba8-2ec6-4868-5f003edd23ff', 'contact_direct_reports', 'Contacts', 'contacts', 'id', 'Contacts', 'contacts', 'reports_to_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('a86c9542-222a-290b-bbdb-5f003e958b43', 'contact_leads', 'Contacts', 'contacts', 'id', 'Leads', 'leads', 'contact_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('a9209d5f-8097-b41a-e1ee-5f003e5c0e6f', 'contact_notes', 'Contacts', 'contacts', 'id', 'Notes', 'notes', 'contact_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('a9784675-aadf-648d-3b04-5f003e3015e0', 'contact_tasks', 'Contacts', 'contacts', 'id', 'Tasks', 'tasks', 'contact_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('aa51bf17-e7d2-b833-1cae-5f003e31d6b0', 'contact_tasks_parent', 'Contacts', 'contacts', 'id', 'Tasks', 'tasks', 'parent_id', NULL, NULL, NULL, 'one-to-many', 'parent_type', 'Contacts', 0, 0),
('aa6f8fb1-2a4e-f718-a822-5f003efd057f', 'stic_events_created_by', 'Users', 'users', 'id', 'stic_Events', 'stic_events', 'created_by', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('ab2e7341-ec62-46a0-c343-5f003e79af9f', 'contact_notes_parent', 'Contacts', 'contacts', 'id', 'Notes', 'notes', 'parent_id', NULL, NULL, NULL, 'one-to-many', 'parent_type', 'Contacts', 0, 0),
('ab5f42b7-7986-19f0-c0fe-5f003e14003b', 'documents_cases', 'Documents', 'documents', 'id', 'Cases', 'cases', 'id', 'documents_cases', 'document_id', 'case_id', 'many-to-many', NULL, NULL, 0, 0),
('ab683aef-4ca7-308b-92d3-5f003ebdfd2d', 'stic_events_assigned_user', 'Users', 'users', 'id', 'stic_Events', 'stic_events', 'assigned_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('abeb98a9-8b3b-3a22-7251-5f003e897716', 'contact_campaign_log', 'Contacts', 'contacts', 'id', 'CampaignLog', 'campaign_log', 'target_id', NULL, NULL, NULL, 'one-to-many', 'target_type', 'Contacts', 0, 0),
('ac4ace32-337a-3611-34ab-5f003e1c07d3', 'contact_aos_quotes', 'Contacts', 'contacts', 'id', 'AOS_Quotes', 'aos_quotes', 'billing_contact_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('acc06781-1252-ab86-71b5-5f003e721cf1', 'securitygroups_stic_events', 'SecurityGroups', 'securitygroups', 'id', 'stic_Events', 'stic_events', 'id', 'securitygroups_records', 'securitygroup_id', 'record_id', 'many-to-many', 'module', 'stic_Events', 0, 0),
('ae1398ad-f581-e9b1-c238-5f003e76de32', 'contact_aos_invoices', 'Contacts', 'contacts', 'id', 'AOS_Invoices', 'aos_invoices', 'billing_contact_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('ae4cc438-7d1a-e078-d79b-5f003ec8357d', 'aop_case_updates_notes', 'AOP_Case_Updates', 'aop_case_updates', 'id', 'Notes', 'notes', 'parent_id', NULL, NULL, NULL, 'one-to-many', 'parent_type', 'AOP_Case_Updates', 0, 0),
('aed5469d-a244-50fc-046c-5f003e5c6b19', 'securitygroups_created_by', 'Users', 'users', 'id', 'SecurityGroups', 'securitygroups', 'created_by', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('aee85c09-0a6a-97c4-a21b-5f003e00411f', 'contact_aos_contracts', 'Contacts', 'contacts', 'id', 'AOS_Contracts', 'aos_contracts', 'contact_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('af57f831-6658-9935-a8b8-5f003e26315d', 'stic_payment_commitments_modified_user', 'Users', 'users', 'id', 'stic_Payment_Commitments', 'stic_payment_commitments', 'modified_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('afaf9214-8972-dd11-f42b-5f003e0243fe', 'contacts_aop_case_updates', 'Contacts', 'contacts', 'id', 'AOP_Case_Updates', 'aop_case_updates', 'contact_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('afdf5c26-29aa-208b-9bd4-5f003efa6fa2', 'securitygroups_assigned_user', 'Users', 'users', 'id', 'SecurityGroups', 'securitygroups', 'assigned_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('b0696ff6-a1f3-0a21-e98c-5f003ef812de', 'stic_payment_commitments_created_by', 'Users', 'users', 'id', 'stic_Payment_Commitments', 'stic_payment_commitments', 'created_by', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('b10607f3-345f-ad7a-952a-5f003e9bb485', 'surveyresponses_created_by', 'Users', 'users', 'id', 'SurveyResponses', 'surveyresponses', 'created_by', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('b17886ce-7eb3-c4c5-4130-5f003eb1b330', 'stic_payment_commitments_assigned_user', 'Users', 'users', 'id', 'stic_Payment_Commitments', 'stic_payment_commitments', 'assigned_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('b1f644d6-f3b1-9a8b-a838-5f003e33fa03', 'outbound_email_modified_user', 'Users', 'users', 'id', 'OutboundEmailAccounts', 'outbound_email', 'modified_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('b24f1cfb-4adb-32ff-1cce-5f003ec4f1d9', 'securitygroups_stic_payment_commitments', 'SecurityGroups', 'securitygroups', 'id', 'stic_Payment_Commitments', 'stic_payment_commitments', 'id', 'securitygroups_records', 'securitygroup_id', 'record_id', 'many-to-many', 'module', 'stic_Payment_Commitments', 0, 0),
('b30e31d4-aba2-33fe-864c-5f003e11ce00', 'aor_reports_modified_user', 'Users', 'users', 'id', 'AOR_Reports', 'aor_reports', 'modified_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('b3f6492d-af19-c73e-e6b9-5f003e6b26c7', 'documents_bugs', 'Documents', 'documents', 'id', 'Bugs', 'bugs', 'id', 'documents_bugs', 'document_id', 'bug_id', 'many-to-many', NULL, NULL, 0, 0),
('b42f7099-7d73-61eb-8c3d-5f003e93392b', 'aor_reports_created_by', 'Users', 'users', 'id', 'AOR_Reports', 'aor_reports', 'created_by', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('b4cdd3b4-baa6-206a-7324-5f003e1cfcd4', 'stic_payments_modified_user', 'Users', 'users', 'id', 'stic_Payments', 'stic_payments', 'modified_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('b51fa0f4-6690-de43-2c35-5f003e1a5ef5', 'aor_reports_assigned_user', 'Users', 'users', 'id', 'AOR_Reports', 'aor_reports', 'assigned_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('b5f9abb5-f24e-4e87-0012-5f003e9f7a82', 'accounts_modified_user', 'Users', 'users', 'id', 'Accounts', 'accounts', 'modified_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('b6203780-0609-eb43-f866-5f003e39595d', 'securitygroups_aor_reports', 'SecurityGroups', 'securitygroups', 'id', 'AOR_Reports', 'aor_reports', 'id', 'securitygroups_records', 'securitygroup_id', 'record_id', 'many-to-many', 'module', 'AOR_Reports', 0, 0),
('b70b5d9d-31b7-3617-4f6d-5f003ee13fa4', 'accounts_created_by', 'Users', 'users', 'id', 'Accounts', 'accounts', 'created_by', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('b70c32c8-360b-1b8b-a783-5f003e418701', 'aor_reports_aor_fields', 'AOR_Reports', 'aor_reports', 'id', 'AOR_Fields', 'aor_fields', 'aor_report_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('b7db8b10-1165-6fd6-01f0-5f003ec49478', 'accounts_assigned_user', 'Users', 'users', 'id', 'Accounts', 'accounts', 'assigned_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('b8018a9d-ebba-857e-fe23-5f003ee7bf14', 'aor_reports_aor_conditions', 'AOR_Reports', 'aor_reports', 'id', 'AOR_Conditions', 'aor_conditions', 'aor_report_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('b8b44297-3b27-5103-8027-5f003e72d0d3', 'securitygroups_accounts', 'SecurityGroups', 'securitygroups', 'id', 'Accounts', 'accounts', 'id', 'securitygroups_records', 'securitygroup_id', 'record_id', 'many-to-many', 'module', 'Accounts', 0, 0),
('b8e09284-5c01-efad-22f3-5f003e356566', 'aor_scheduled_reports_aor_reports', 'AOR_Reports', 'aor_reports', 'id', 'AOR_Scheduled_Reports', 'aor_scheduled_reports', 'aor_report_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('b90ff435-80d0-8591-4c99-5f003ee704ea', 'accounts_email_addresses', 'Accounts', 'accounts', 'id', 'EmailAddresses', 'email_addresses', 'id', 'email_addr_bean_rel', 'bean_id', 'email_address_id', 'many-to-many', 'bean_module', 'Accounts', 0, 0),
('ba3f9514-8827-67f8-a2c0-5f003e5c579f', 'accounts_email_addresses_primary', 'Accounts', 'accounts', 'id', 'EmailAddresses', 'email_addresses', 'id', 'email_addr_bean_rel', 'bean_id', 'email_address_id', 'many-to-many', 'primary_address', '1', 0, 0),
('bacbcec6-57e6-11d8-e97d-5f003e3bf4d5', 'outbound_email_created_by', 'Users', 'users', 'id', 'OutboundEmailAccounts', 'outbound_email', 'created_by', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('bb1b0c0e-1be9-40f5-2f68-5f003e35f512', 'member_accounts', 'Accounts', 'accounts', 'id', 'Accounts', 'accounts', 'parent_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('bb73abc8-e570-7904-97a3-5f003e746c19', 'aor_fields_modified_user', 'Users', 'users', 'id', 'AOR_Fields', 'aor_fields', 'modified_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('bbd10479-8ea2-4195-8b5f-5f003e43a154', 'outbound_email_assigned_user', 'Users', 'users', 'id', 'OutboundEmailAccounts', 'outbound_email', 'assigned_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('bbe45f5e-27ff-df31-9e4a-5f003e5acf35', 'account_cases', 'Accounts', 'accounts', 'id', 'Cases', 'cases', 'account_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('bcaa3f80-7306-0a40-210b-5f003eb14cda', 'account_tasks', 'Accounts', 'accounts', 'id', 'Tasks', 'tasks', 'parent_id', NULL, NULL, NULL, 'one-to-many', 'parent_type', 'Accounts', 0, 0),
('bd0743fa-26c4-34a9-6ff8-5f003e2a4bc4', 'account_notes', 'Accounts', 'accounts', 'id', 'Notes', 'notes', 'parent_id', NULL, NULL, NULL, 'one-to-many', 'parent_type', 'Accounts', 0, 0),
('bdc5d135-3127-9dcf-4dc7-5f003eb336c7', 'account_meetings', 'Accounts', 'accounts', 'id', 'Meetings', 'meetings', 'parent_id', NULL, NULL, NULL, 'one-to-many', 'parent_type', 'Accounts', 0, 0),
('be201520-7d12-048e-2ea0-5f003e19b0ee', 'templatesectionline_modified_user', 'Users', 'users', 'id', 'TemplateSectionLine', 'templatesectionline', 'modified_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('be875f9b-abd3-0877-3d8a-5f003e4d7ac3', 'account_calls', 'Accounts', 'accounts', 'id', 'Calls', 'calls', 'parent_id', NULL, NULL, NULL, 'one-to-many', 'parent_type', 'Accounts', 0, 0),
('bead6c97-7c69-e4d4-8086-5f003e4da595', 'aor_fields_created_by', 'Users', 'users', 'id', 'AOR_Fields', 'aor_fields', 'created_by', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('bee26c48-9dd9-b290-8a92-5f003e072a6d', 'account_emails', 'Accounts', 'accounts', 'id', 'Emails', 'emails', 'parent_id', NULL, NULL, NULL, 'one-to-many', 'parent_type', 'Accounts', 0, 0),
('bf8c4561-2814-2499-4229-5f003e7ef775', 'stic_payments_created_by', 'Users', 'users', 'id', 'stic_Payments', 'stic_payments', 'created_by', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('bfaa34e1-9bb7-5b7e-561b-5f003e8753ad', 'account_leads', 'Accounts', 'accounts', 'id', 'Leads', 'leads', 'account_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('bfaf8928-f934-d202-fbac-5f003e625f3f', 'templatesectionline_created_by', 'Users', 'users', 'id', 'TemplateSectionLine', 'templatesectionline', 'created_by', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('c00b386e-aba1-cdb2-a14a-5f003eb3c977', 'account_campaign_log', 'Accounts', 'accounts', 'id', 'CampaignLog', 'campaign_log', 'target_id', NULL, NULL, NULL, 'one-to-many', 'target_type', 'Accounts', 0, 0),
('c0b686de-82d6-fb17-0cbc-5f003e0ee1fe', 'stic_payments_assigned_user', 'Users', 'users', 'id', 'stic_Payments', 'stic_payments', 'assigned_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('c10aa948-0f09-e5a7-decf-5f003e5e449d', 'account_aos_quotes', 'Accounts', 'accounts', 'id', 'AOS_Quotes', 'aos_quotes', 'billing_account_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('c19cf3bd-aa45-53a8-99a6-5f003ec27cb5', 'aor_charts_modified_user', 'Users', 'users', 'id', 'AOR_Charts', 'aor_charts', 'modified_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('c1af4d7e-8673-e557-4d94-5f003e712f0b', 'securitygroups_stic_payments', 'SecurityGroups', 'securitygroups', 'id', 'stic_Payments', 'stic_payments', 'id', 'securitygroups_records', 'securitygroup_id', 'record_id', 'many-to-many', 'module', 'stic_Payments', 0, 0),
('c1cf1f64-1b9c-84de-3daf-5f003e8b2541', 'account_aos_invoices', 'Accounts', 'accounts', 'id', 'AOS_Invoices', 'aos_invoices', 'billing_account_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('c215e024-2d9f-95a7-0797-5f003e52e90e', 'oauth2tokens_modified_user', 'Users', 'users', 'id', 'OAuth2Tokens', 'oauth2tokens', 'modified_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('c28b8faa-e32f-cc10-8cb6-5f003edef58d', 'account_aos_contracts', 'Accounts', 'accounts', 'id', 'AOS_Contracts', 'aos_contracts', 'contract_account_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('c2b2b42e-709f-e725-2a16-5f003e5cfc8b', 'aor_charts_created_by', 'Users', 'users', 'id', 'AOR_Charts', 'aor_charts', 'created_by', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('c39240cf-10ed-1190-a94c-5f003e26f867', 'aor_charts_aor_reports', 'AOR_Reports', 'aor_reports', 'id', 'AOR_Charts', 'aor_charts', 'aor_report_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('c443d350-06fd-d735-064c-5f003eac152d', 'stic_registrations_modified_user', 'Users', 'users', 'id', 'stic_Registrations', 'stic_registrations', 'modified_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('c56eedc2-70c6-02d3-9e46-5f003e82b5fe', 'stic_registrations_created_by', 'Users', 'users', 'id', 'stic_Registrations', 'stic_registrations', 'created_by', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('c64aee63-2628-7fdb-1652-5f003e0fe76e', 'stic_registrations_assigned_user', 'Users', 'users', 'id', 'stic_Registrations', 'stic_registrations', 'assigned_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('c6895e74-205d-ebe5-198a-5f003e36e826', 'opportunities_modified_user', 'Users', 'users', 'id', 'Opportunities', 'opportunities', 'modified_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('c68e7f8d-2ab2-abe7-ddb7-5f003e4524fd', 'aor_conditions_modified_user', 'Users', 'users', 'id', 'AOR_Conditions', 'aor_conditions', 'modified_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('c730aae4-9eae-4c65-ffe3-5f003e157270', 'securitygroups_stic_registrations', 'SecurityGroups', 'securitygroups', 'id', 'stic_Registrations', 'stic_registrations', 'id', 'securitygroups_records', 'securitygroup_id', 'record_id', 'many-to-many', 'module', 'stic_Registrations', 0, 0),
('c78d7716-606d-ae9b-ec1d-5f003eefedaf', 'opportunities_created_by', 'Users', 'users', 'id', 'Opportunities', 'opportunities', 'created_by', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('c809f473-3665-7c49-780b-5f003eaed695', 'aok_knowledgebase_categories', 'AOK_KnowledgeBase', 'aok_knowledgebase', 'id', 'AOK_Knowledge_Base_Categories', 'aok_knowledge_base_categories', 'id', 'aok_knowledgebase_categories', 'aok_knowledgebase_id', 'aok_knowledge_base_categories_id', 'many-to-many', NULL, NULL, 0, 0),
('c824bdcd-6ae8-96ab-f7be-5f003ee228a3', 'aor_conditions_created_by', 'Users', 'users', 'id', 'AOR_Conditions', 'aor_conditions', 'created_by', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('c861ee0a-a815-55f1-9285-5f003ef085ae', 'opportunities_assigned_user', 'Users', 'users', 'id', 'Opportunities', 'opportunities', 'assigned_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('c8c17cad-3d31-7a62-267a-5f003ed9a69b', 'securitygroups_opportunities', 'SecurityGroups', 'securitygroups', 'id', 'Opportunities', 'opportunities', 'id', 'securitygroups_records', 'securitygroup_id', 'record_id', 'many-to-many', 'module', 'Opportunities', 0, 0),
('c9295666-8bc2-9cad-bdc7-5f003edfa1e5', 'stic_remittances_modified_user', 'Users', 'users', 'id', 'stic_Remittances', 'stic_remittances', 'modified_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('c9b1fe5d-eacb-d754-9838-5f003e5a0fad', 'opportunity_calls', 'Opportunities', 'opportunities', 'id', 'Calls', 'calls', 'parent_id', NULL, NULL, NULL, 'one-to-many', 'parent_type', 'Opportunities', 0, 0),
('caf3883f-216a-8af4-b383-5f003ef2e690', 'opportunity_meetings', 'Opportunities', 'opportunities', 'id', 'Meetings', 'meetings', 'parent_id', NULL, NULL, NULL, 'one-to-many', 'parent_type', 'Opportunities', 0, 0),
('cb08cc48-ca5b-e1aa-5346-5f003eb77b92', 'am_projecttemplates_project_1', 'AM_ProjectTemplates', 'am_projecttemplates', 'id', 'Project', 'project', 'id', 'am_projecttemplates_project_1_c', 'am_projecttemplates_project_1am_projecttemplates_ida', 'am_projecttemplates_project_1project_idb', 'many-to-many', NULL, NULL, 0, 0),
('cbc2c908-f759-472e-12f1-5f003e2f5c29', 'opportunity_tasks', 'Opportunities', 'opportunities', 'id', 'Tasks', 'tasks', 'parent_id', NULL, NULL, NULL, 'one-to-many', 'parent_type', 'Opportunities', 0, 0),
('cbffe929-bd2a-d65c-b154-5f003e37a0ab', 'am_projecttemplates_contacts_1', 'AM_ProjectTemplates', 'am_projecttemplates', 'id', 'Contacts', 'contacts', 'id', 'am_projecttemplates_contacts_1_c', 'am_projecttemplates_ida', 'contacts_idb', 'many-to-many', NULL, NULL, 0, 0),
('cc6e5274-2a41-273b-7d1b-5f003e6f319b', 'aor_scheduled_reports_modified_user', 'Users', 'users', 'id', 'AOR_Scheduled_Reports', 'aor_scheduled_reports', 'modified_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('cca0e732-3a98-55b8-ee8e-5f003efc744d', 'opportunity_notes', 'Opportunities', 'opportunities', 'id', 'Notes', 'notes', 'parent_id', NULL, NULL, NULL, 'one-to-many', 'parent_type', 'Opportunities', 0, 0),
('ccd4a394-1baf-66d0-acb8-5f003eb0693f', 'am_projecttemplates_users_1', 'AM_ProjectTemplates', 'am_projecttemplates', 'id', 'Users', 'users', 'id', 'am_projecttemplates_users_1_c', 'am_projecttemplates_ida', 'users_idb', 'many-to-many', NULL, NULL, 0, 0),
('cd5716e2-42e4-3ba6-d8ee-5f003e52ef07', 'opportunity_emails', 'Opportunities', 'opportunities', 'id', 'Emails', 'emails', 'parent_id', NULL, NULL, NULL, 'one-to-many', 'parent_type', 'Opportunities', 0, 0),
('cdbd06a5-163d-5963-c01a-5f003e50edfc', 'am_tasktemplates_am_projecttemplates', 'AM_ProjectTemplates', 'am_projecttemplates', 'id', 'AM_TaskTemplates', 'am_tasktemplates', 'id', 'am_tasktemplates_am_projecttemplates_c', 'am_tasktemplates_am_projecttemplatesam_projecttemplates_ida', 'am_tasktemplates_am_projecttemplatesam_tasktemplates_idb', 'many-to-many', NULL, NULL, 0, 0),
('ce1934a9-2af6-f123-107b-5f003e64487c', 'aor_scheduled_reports_created_by', 'Users', 'users', 'id', 'AOR_Scheduled_Reports', 'aor_scheduled_reports', 'created_by', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('ce1e18cc-4445-4a60-14fd-5f003e9c2b91', 'opportunity_leads', 'Opportunities', 'opportunities', 'id', 'Leads', 'leads', 'opportunity_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('ce7e6e62-3a78-c38d-88c5-5f003ecc8042', 'opportunity_currencies', 'Opportunities', 'opportunities', 'currency_id', 'Currencies', 'currencies', 'id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('ced48474-f88d-9d3d-cc73-5f003eed69fd', 'aos_contracts_documents', 'AOS_Contracts', 'aos_contracts', 'id', 'Documents', 'documents', 'id', 'aos_contracts_documents', 'aos_contracts_id', 'documents_id', 'many-to-many', NULL, NULL, 0, 0),
('cf12c5dc-b5d7-28a5-0075-5f003e610208', 'securitygroups_aor_scheduled_reports', 'SecurityGroups', 'securitygroups', 'id', 'AOR_Scheduled_Reports', 'aor_scheduled_reports', 'id', 'securitygroups_records', 'securitygroup_id', 'record_id', 'many-to-many', 'module', 'AOR_Scheduled_Reports', 0, 0),
('cfad330a-29b8-f93c-6f96-5f003e3aa60a', 'aos_quotes_aos_contracts', 'AOS_Quotes', 'aos_quotes', 'id', 'AOS_Contracts', 'aos_contracts', 'id', 'aos_quotes_os_contracts_c', 'aos_quotese81e_quotes_ida', 'aos_quotes4dc0ntracts_idb', 'many-to-many', NULL, NULL, 0, 0),
('cfafd917-8ee7-2dd2-4846-5f003e0d5330', 'opportunities_campaign', 'Campaigns', 'campaigns', 'id', 'Opportunities', 'opportunities', 'campaign_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('d07af989-586e-dd92-4fc4-5f003ed6292c', 'opportunity_aos_quotes', 'Opportunities', 'opportunities', 'id', 'AOS_Quotes', 'aos_quotes', 'opportunity_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('d0b4b1fb-11c7-ccc7-49bf-5f003e8f4d3d', 'aos_quotes_aos_invoices', 'AOS_Quotes', 'aos_quotes', 'id', 'AOS_Invoices', 'aos_invoices', 'id', 'aos_quotes_aos_invoices_c', 'aos_quotes77d9_quotes_ida', 'aos_quotes6b83nvoices_idb', 'many-to-many', NULL, NULL, 0, 0),
('d155e8d8-5701-ab56-dc65-5f003e020ebb', 'opportunity_aos_contracts', 'Opportunities', 'opportunities', 'id', 'AOS_Contracts', 'aos_contracts', 'opportunity_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('d18bb388-497d-38db-b30a-5f003ecfa5d3', 'aos_quotes_project', 'AOS_Quotes', 'aos_quotes', 'id', 'Project', 'project', 'id', 'aos_quotes_project_c', 'aos_quotes1112_quotes_ida', 'aos_quotes7207project_idb', 'many-to-many', NULL, NULL, 0, 0),
('d2707a53-9dda-a9ae-365f-5f003e13ce88', 'aow_processed_aow_actions', 'AOW_Processed', 'aow_processed', 'id', 'AOW_Actions', 'aow_actions', 'id', 'aow_processed_aow_actions', 'aow_processed_id', 'aow_action_id', 'many-to-many', NULL, NULL, 0, 0),
('d2a76339-5613-bd09-4128-5f003e3e487d', 'aos_contracts_modified_user', 'Users', 'users', 'id', 'AOS_Contracts', 'aos_contracts', 'modified_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('d346e8de-3774-aed2-b68b-5f003e20d362', 'fp_event_locations_fp_events_1', 'FP_Event_Locations', 'fp_event_locations', 'id', 'FP_events', 'fp_events', 'id', 'fp_event_locations_fp_events_1_c', 'fp_event_locations_fp_events_1fp_event_locations_ida', 'fp_event_locations_fp_events_1fp_events_idb', 'many-to-many', NULL, NULL, 0, 0),
('d403456a-496f-6e33-4586-5f003e409962', 'aos_contracts_created_by', 'Users', 'users', 'id', 'AOS_Contracts', 'aos_contracts', 'created_by', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('d435d53e-544d-e406-d992-5f003e6f6151', 'fp_events_contacts', 'FP_events', 'fp_events', 'id', 'Contacts', 'contacts', 'id', 'fp_events_contacts_c', 'fp_events_contactsfp_events_ida', 'fp_events_contactscontacts_idb', 'many-to-many', NULL, NULL, 0, 0),
('d4bafd16-2672-92d0-8b0c-5f003e823afa', 'securitygroups_emailtemplates', 'SecurityGroups', 'securitygroups', 'id', 'EmailTemplates', 'email_templates', 'id', 'securitygroups_records', 'securitygroup_id', 'record_id', 'many-to-many', 'module', 'EmailTemplates', 0, 0),
('d4e25bba-bef2-4ccb-4c8b-5f003e58fe36', 'aos_contracts_assigned_user', 'Users', 'users', 'id', 'AOS_Contracts', 'aos_contracts', 'assigned_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('d5267468-aa7f-dbbf-b40c-5f003ecbe98c', 'fp_events_fp_event_locations_1', 'FP_events', 'fp_events', 'id', 'FP_Event_Locations', 'fp_event_locations', 'id', 'fp_events_fp_event_locations_1_c', 'fp_events_fp_event_locations_1fp_events_ida', 'fp_events_fp_event_locations_1fp_event_locations_idb', 'many-to-many', NULL, NULL, 0, 0),
('d5a90e2c-3ece-45c6-98da-5f003e53cdf3', 'fp_events_leads_1', 'FP_events', 'fp_events', 'id', 'Leads', 'leads', 'id', 'fp_events_leads_1_c', 'fp_events_leads_1fp_events_ida', 'fp_events_leads_1leads_idb', 'many-to-many', NULL, NULL, 0, 0),
('d5b29aba-b336-f270-7a7d-5f003e6b8f68', 'securitygroups_aos_contracts', 'SecurityGroups', 'securitygroups', 'id', 'AOS_Contracts', 'aos_contracts', 'id', 'securitygroups_records', 'securitygroup_id', 'record_id', 'many-to-many', 'module', 'AOS_Contracts', 0, 0),
('d649d03e-4f6c-9dc6-4a9e-5f003e9b0c04', 'emailtemplates_assigned_user', 'Users', 'users', 'id', 'EmailTemplates', 'email_templates', 'assigned_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('d67d6010-db96-2e77-9a48-5f003e801dc8', 'fp_events_prospects_1', 'FP_events', 'fp_events', 'id', 'Prospects', 'prospects', 'id', 'fp_events_prospects_1_c', 'fp_events_prospects_1fp_events_ida', 'fp_events_prospects_1prospects_idb', 'many-to-many', NULL, NULL, 0, 0),
('d68318d7-ebc5-646e-abbc-5f003ec41f17', 'aos_contracts_tasks', 'AOS_Contracts', 'aos_contracts', 'id', 'Tasks', 'tasks', 'parent_id', NULL, NULL, NULL, 'one-to-many', 'parent_type', 'AOS_Contracts', 0, 0),
('d745c3d6-a1b5-40e7-eb58-5f003e7058b6', 'aos_contracts_notes', 'AOS_Contracts', 'aos_contracts', 'id', 'Notes', 'notes', 'parent_id', NULL, NULL, NULL, 'one-to-many', 'parent_type', 'AOS_Contracts', 0, 0),
('d74a7548-db67-9693-3003-5f003e413533', 'jjwg_maps_jjwg_areas', 'jjwg_Maps', 'jjwg_maps', 'id', 'jjwg_Areas', 'jjwg_areas', 'id', 'jjwg_maps_jjwg_areas_c', 'jjwg_maps_5304wg_maps_ida', 'jjwg_maps_41f2g_areas_idb', 'many-to-many', NULL, NULL, 0, 0),
('d815cad3-62fd-ec7b-5cd6-5f003e8e4d86', 'aos_contracts_meetings', 'AOS_Contracts', 'aos_contracts', 'id', 'Meetings', 'meetings', 'parent_id', NULL, NULL, NULL, 'one-to-many', 'parent_type', 'AOS_Contracts', 0, 0),
('d82822c8-7f4a-c387-ccf2-5f003e1f1cb3', 'jjwg_maps_jjwg_markers', 'jjwg_Maps', 'jjwg_maps', 'id', 'jjwg_Markers', 'jjwg_markers', 'id', 'jjwg_maps_jjwg_markers_c', 'jjwg_maps_b229wg_maps_ida', 'jjwg_maps_2e31markers_idb', 'many-to-many', NULL, NULL, 0, 0),
('d876c5ff-6817-8d28-86cc-5f003efd307d', 'aos_contracts_calls', 'AOS_Contracts', 'aos_contracts', 'id', 'Calls', 'calls', 'parent_id', NULL, NULL, NULL, 'one-to-many', 'parent_type', 'AOS_Contracts', 0, 0),
('d933f627-864d-5b56-4225-5f003edccd7e', 'aos_contracts_aos_products_quotes', 'AOS_Contracts', 'aos_contracts', 'id', 'AOS_Products_Quotes', 'aos_products_quotes', 'parent_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('d9ae71b6-b48d-a4d6-39ca-5f003e8055f5', 'notes_assigned_user', 'Users', 'users', 'id', 'Notes', 'notes', 'assigned_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('d9fcc7d3-896a-3060-0270-5f003e1a90e3', 'aos_contracts_aos_line_item_groups', 'AOS_Contracts', 'aos_contracts', 'id', 'AOS_Line_Item_Groups', 'aos_line_item_groups', 'parent_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('dac38eca-75e6-71bf-ef69-5f003e391e6b', 'securitygroups_notes', 'SecurityGroups', 'securitygroups', 'id', 'Notes', 'notes', 'id', 'securitygroups_records', 'securitygroup_id', 'record_id', 'many-to-many', 'module', 'Notes', 0, 0),
('dafe9342-df27-535e-7c7c-5f003ea49311', 'project_contacts_1', 'Project', 'project', 'id', 'Contacts', 'contacts', 'id', 'project_contacts_1_c', 'project_contacts_1project_ida', 'project_contacts_1contacts_idb', 'many-to-many', NULL, NULL, 0, 0),
('db387800-6cf2-180d-2e13-5f003e9dfa41', 'stic_remittances_created_by', 'Users', 'users', 'id', 'stic_Remittances', 'stic_remittances', 'created_by', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('dba0de31-9079-2f0c-ee4c-5f003e15e3bc', 'notes_modified_user', 'Users', 'users', 'id', 'Notes', 'notes', 'modified_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('dbffb036-07da-85fe-b237-5f003ebe9f67', 'project_users_1', 'Project', 'project', 'id', 'Users', 'users', 'id', 'project_users_1_c', 'project_users_1project_ida', 'project_users_1users_idb', 'many-to-many', NULL, NULL, 0, 0),
('dc301c2c-c838-4881-657e-5f003eec9f6a', 'stic_remittances_assigned_user', 'Users', 'users', 'id', 'stic_Remittances', 'stic_remittances', 'assigned_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('dc72b8eb-dfba-f68b-7115-5f003ed1b3a3', 'notes_created_by', 'Users', 'users', 'id', 'Notes', 'notes', 'created_by', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0);
INSERT INTO `relationships` (`id`, `relationship_name`, `lhs_module`, `lhs_table`, `lhs_key`, `rhs_module`, `rhs_table`, `rhs_key`, `join_table`, `join_key_lhs`, `join_key_rhs`, `relationship_type`, `relationship_role_column`, `relationship_role_column_value`, `reverse`, `deleted`) VALUES
('dcc333b6-c8eb-b59e-5b60-5f003ea55bc7', 'aos_invoices_modified_user', 'Users', 'users', 'id', 'AOS_Invoices', 'aos_invoices', 'modified_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('dd0c32d4-6abb-ff5a-58e1-5f003e61d56e', 'securitygroups_stic_remittances', 'SecurityGroups', 'securitygroups', 'id', 'stic_Remittances', 'stic_remittances', 'id', 'securitygroups_records', 'securitygroup_id', 'record_id', 'many-to-many', 'module', 'stic_Remittances', 0, 0),
('ddcaafc5-74e0-d071-1ff8-5f003e43d026', 'aos_invoices_created_by', 'Users', 'users', 'id', 'AOS_Invoices', 'aos_invoices', 'created_by', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('dec23aa4-cf06-7fcc-68dd-5f003e49ee7d', 'aos_invoices_assigned_user', 'Users', 'users', 'id', 'AOS_Invoices', 'aos_invoices', 'assigned_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('deda551a-b4d5-c51b-d5dd-5f003eee54f5', 'securitygroups_acl_roles', 'SecurityGroups', 'securitygroups', 'id', 'ACLRoles', 'acl_roles', 'id', 'securitygroups_acl_roles', 'securitygroup_id', 'role_id', 'many-to-many', NULL, NULL, 0, 0),
('dfb00d18-b00c-8581-1aa7-5f003e5e656e', 'securitygroups_aos_invoices', 'SecurityGroups', 'securitygroups', 'id', 'AOS_Invoices', 'aos_invoices', 'id', 'securitygroups_records', 'securitygroup_id', 'record_id', 'many-to-many', 'module', 'AOS_Invoices', 0, 0),
('dfb89d25-2326-1825-ae95-5f003e65ee44', 'calls_modified_user', 'Users', 'users', 'id', 'Calls', 'calls', 'modified_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('e06dc2b3-7dc1-0973-0543-5f003ebc9252', 'securitygroups_project_task', 'SecurityGroups', 'securitygroups', 'id', 'ProjectTask', 'project_task', 'id', 'securitygroups_records', 'securitygroup_id', 'record_id', 'many-to-many', 'module', 'ProjectTask', 0, 0),
('e0769c15-281d-40e1-4478-5f003ef2bf06', 'aos_invoices_aos_product_quotes', 'AOS_Invoices', 'aos_invoices', 'id', 'AOS_Products_Quotes', 'aos_products_quotes', 'parent_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('e0a8033f-0739-92a2-b143-5f003e36967a', 'oauth2tokens_created_by', 'Users', 'users', 'id', 'OAuth2Tokens', 'oauth2tokens', 'created_by', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('e0c05ac4-1ca1-5b85-2727-5f003ec03cec', 'calls_created_by', 'Users', 'users', 'id', 'Calls', 'calls', 'created_by', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('e13b4ae1-2c1e-d846-df59-5f003e1f2f4e', 'aos_invoices_aos_line_item_groups', 'AOS_Invoices', 'aos_invoices', 'id', 'AOS_Line_Item_Groups', 'aos_line_item_groups', 'parent_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('e14d2dee-db27-5e17-2ee4-5f003e9bad21', 'securitygroups_prospect_lists', 'SecurityGroups', 'securitygroups', 'id', 'ProspectLists', 'prospect_lists', 'id', 'securitygroups_records', 'securitygroup_id', 'record_id', 'many-to-many', 'module', 'ProspectLists', 0, 0),
('e1750223-7285-9fc4-2319-5f003e787c35', 'surveyresponses_assigned_user', 'Users', 'users', 'id', 'SurveyResponses', 'surveyresponses', 'assigned_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('e19aa723-a222-ecb6-df16-5f003e11350c', 'calls_assigned_user', 'Users', 'users', 'id', 'Calls', 'calls', 'assigned_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('e1c39207-6cbf-6cb2-8881-5f003e88e797', 'oauth2tokens_assigned_user', 'Users', 'users', 'id', 'OAuth2Tokens', 'oauth2tokens', 'assigned_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('e24885b4-bee0-54b6-0488-5f003e0d0133', 'securitygroups_users', 'SecurityGroups', 'securitygroups', 'id', 'Users', 'users', 'id', 'securitygroups_users', 'securitygroup_id', 'user_id', 'many-to-many', NULL, NULL, 0, 0),
('e26e403f-72b1-be5f-366d-5f003e77ef8b', 'securitygroups_calls', 'SecurityGroups', 'securitygroups', 'id', 'Calls', 'calls', 'id', 'securitygroups_records', 'securitygroup_id', 'record_id', 'many-to-many', 'module', 'Calls', 0, 0),
('e3162800-ac8f-5277-a3ab-5f003e3c30b6', 'surveyquestionoptions_surveyquestionresponses', 'SurveyQuestionOptions', 'surveyquestionoptions', 'id', 'SurveyQuestionResponses', 'surveyquestionresponses', 'id', 'surveyquestionoptions_surveyquestionresponses', 'surveyq72c7options_ida', 'surveyq10d4sponses_idb', 'many-to-many', NULL, NULL, 0, 0),
('e32d612f-1cd4-7519-8e4c-5f003ea2c3f2', 'calls_notes', 'Calls', 'calls', 'id', 'Notes', 'notes', 'parent_id', NULL, NULL, NULL, 'one-to-many', 'parent_type', 'Calls', 0, 0),
('e3c9f6d7-11b4-9b58-e906-5f003e963ce8', 'aos_pdf_templates_modified_user', 'Users', 'users', 'id', 'AOS_PDF_Templates', 'aos_pdf_templates', 'modified_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('e3e5a2e8-efa5-c4d0-08c8-5f003ec9bceb', 'calls_reschedule', 'Calls', 'calls', 'id', 'Calls_Reschedule', 'calls_reschedule', 'call_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('e405009c-b3e0-2878-4c2a-5f003edc012a', 'stic_attendances_stic_sessions', 'stic_Sessions', 'stic_sessions', 'id', 'stic_Attendances', 'stic_attendances', 'id', 'stic_attendances_stic_sessions_c', 'stic_attendances_stic_sessionsstic_sessions_ida', 'stic_attendances_stic_sessionsstic_attendances_idb', 'many-to-many', NULL, NULL, 0, 0),
('e42d41c1-bfc0-b404-c143-5f003e89a78c', 'oauth2clients_modified_user', 'Users', 'users', 'id', 'OAuth2Clients', 'oauth2clients', 'modified_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('e4d3fc51-f1d8-60df-7349-5f003e1749f4', 'aos_pdf_templates_created_by', 'Users', 'users', 'id', 'AOS_PDF_Templates', 'aos_pdf_templates', 'created_by', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('e4e99b8e-e1b9-0f48-be05-5f003e32b211', 'stic_payments_activities_notes', 'stic_Payments', 'stic_payments', 'id', 'Notes', 'notes', 'parent_id', NULL, NULL, NULL, 'one-to-many', 'parent_type', 'stic_Payments', 0, 0),
('e4f9d8c6-5112-3252-edb9-5f003e505689', 'stic_sessions_modified_user', 'Users', 'users', 'id', 'stic_Sessions', 'stic_sessions', 'modified_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('e5bf6c7b-b6e0-4bda-0642-5f003e5b701e', 'stic_payments_contacts', 'Contacts', 'contacts', 'id', 'stic_Payments', 'stic_payments', 'id', 'stic_payments_contacts_c', 'stic_payments_contactscontacts_ida', 'stic_payments_contactsstic_payments_idb', 'many-to-many', NULL, NULL, 0, 0),
('e5da8963-c5d9-2e95-631f-5f003eb2eb7a', 'emails_modified_user', 'Users', 'users', 'id', 'Emails', 'emails', 'modified_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('e60a3b62-e641-8f4e-5220-5f003ebc1425', 'aos_pdf_templates_assigned_user', 'Users', 'users', 'id', 'AOS_PDF_Templates', 'aos_pdf_templates', 'assigned_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('e61ecd9f-a1df-fc6f-8695-5f003ee54d98', 'stic_payments_activities_tasks', 'stic_Payments', 'stic_payments', 'id', 'Tasks', 'tasks', 'parent_id', NULL, NULL, NULL, 'one-to-many', 'parent_type', 'stic_Payments', 0, 0),
('e6600ecf-0a1c-8dde-8799-5f003e6e848b', 'stic_sessions_created_by', 'Users', 'users', 'id', 'stic_Sessions', 'stic_sessions', 'created_by', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('e6d78702-c935-51cc-ca37-5f003ea7924e', 'securitygroups_aos_pdf_templates', 'SecurityGroups', 'securitygroups', 'id', 'AOS_PDF_Templates', 'aos_pdf_templates', 'id', 'securitygroups_records', 'securitygroup_id', 'record_id', 'many-to-many', 'module', 'AOS_PDF_Templates', 0, 0),
('e730d0ed-0300-47aa-aa00-5f003eab89e1', 'stic_registrations_accounts', 'Accounts', 'accounts', 'id', 'stic_Registrations', 'stic_registrations', 'id', 'stic_registrations_accounts_c', 'stic_registrations_accountsaccounts_ida', 'stic_registrations_accountsstic_registrations_idb', 'many-to-many', NULL, NULL, 0, 0),
('e739db14-c959-ab4f-7212-5f003efde020', 'stic_sessions_assigned_user', 'Users', 'users', 'id', 'stic_Sessions', 'stic_sessions', 'assigned_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('e7f9f978-06ef-899a-bc8d-5f003e8d6dcb', 'securitygroups_stic_sessions', 'SecurityGroups', 'securitygroups', 'id', 'stic_Sessions', 'stic_sessions', 'id', 'securitygroups_records', 'securitygroup_id', 'record_id', 'many-to-many', 'module', 'stic_Sessions', 0, 0),
('ea02baf4-a7b5-10de-f820-5f003e4526ed', 'stic_contacts_relationships_project', 'Project', 'project', 'id', 'stic_Contacts_Relationships', 'stic_contacts_relationships', 'id', 'stic_contacts_relationships_project_c', 'stic_contacts_relationships_projectproject_ida', 'stic_conta0d5aonships_idb', 'many-to-many', NULL, NULL, 0, 0),
('ea52b81d-c12b-282f-911d-5f003eb58574', 'stic_settings_modified_user', 'Users', 'users', 'id', 'stic_Settings', 'stic_settings', 'modified_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('ea8d9aff-20aa-2bc2-8b1f-5f003e529cba', 'aos_product_categories_modified_user', 'Users', 'users', 'id', 'AOS_Product_Categories', 'aos_product_categories', 'modified_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('eb03b7d8-af2c-fa51-4ca2-5f003eb885a7', 'stic_payment_commitments_campaigns', 'Campaigns', 'campaigns', 'id', 'stic_Payment_Commitments', 'stic_payment_commitments', 'id', 'stic_payment_commitments_campaigns_c', 'stic_payment_commitments_campaignscampaigns_ida', 'stic_payment_commitments_campaignsstic_payment_commitments_idb', 'many-to-many', NULL, NULL, 0, 0),
('ebd77166-6c9c-5cfd-86e4-5f003e8b204f', 'stic_registrations_leads', 'Leads', 'leads', 'id', 'stic_Registrations', 'stic_registrations', 'id', 'stic_registrations_leads_c', 'stic_registrations_leadsleads_ida', 'stic_registrations_leadsstic_registrations_idb', 'many-to-many', NULL, NULL, 0, 0),
('ec2edacc-c569-a01a-89c9-5f003eb340f4', 'aos_product_categories_created_by', 'Users', 'users', 'id', 'AOS_Product_Categories', 'aos_product_categories', 'created_by', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('eca8b674-f2bc-ed33-2102-5f003e202da8', 'stic_attendances_stic_registrations', 'stic_Registrations', 'stic_registrations', 'id', 'stic_Attendances', 'stic_attendances', 'id', 'stic_attendances_stic_registrations_c', 'stic_attendances_stic_registrationsstic_registrations_ida', 'stic_attendances_stic_registrationsstic_attendances_idb', 'many-to-many', NULL, NULL, 0, 0),
('ed0b9b2b-4c8f-7b2e-58ac-5f003ec2e43a', 'aos_product_categories_assigned_user', 'Users', 'users', 'id', 'AOS_Product_Categories', 'aos_product_categories', 'assigned_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('ed6845ba-6174-252b-9824-5f003e9058d4', 'project_opportunities_1', 'Project', 'project', 'id', 'Opportunities', 'opportunities', 'id', 'project_opportunities_1_c', 'project_opportunities_1project_ida', 'project_opportunities_1opportunities_idb', 'many-to-many', NULL, NULL, 0, 0),
('edcba512-cb3b-c215-ebe3-5f003e9debea', 'securitygroups_aos_product_categories', 'SecurityGroups', 'securitygroups', 'id', 'AOS_Product_Categories', 'aos_product_categories', 'id', 'securitygroups_records', 'securitygroup_id', 'record_id', 'many-to-many', 'module', 'AOS_Product_Categories', 0, 0),
('edcc6dce-4ab9-f8ba-5cd3-5f003e0470af', 'stic_events_project', 'Project', 'project', 'id', 'stic_Events', 'stic_events', 'id', 'stic_events_project_c', 'stic_events_projectproject_ida', 'stic_events_projectstic_events_idb', 'many-to-many', NULL, NULL, 0, 0),
('edd68ee5-8b27-cf08-e4fa-5f003e245274', 'emails_created_by', 'Users', 'users', 'id', 'Emails', 'emails', 'created_by', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('ee8e44a5-d46b-72e3-7bd8-5f003e606b16', 'sub_product_categories', 'AOS_Product_Categories', 'aos_product_categories', 'id', 'AOS_Product_Categories', 'aos_product_categories', 'parent_category_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('ee9653bc-da90-68e2-cb2f-5f003ec26c8f', 'stic_validation_actions_schedulers', 'stic_Validation_Actions', 'stic_validation_actions', 'id', 'Schedulers', 'schedulers', 'id', 'stic_validation_actions_schedulers_c', 'stic_validation_actions_schedulersstic_validation_actions_ida', 'stic_validation_actions_schedulersschedulers_idb', 'many-to-many', NULL, NULL, 0, 0),
('eeb9618c-5c22-0eaa-c1fe-5f003e3e4050', 'emails_assigned_user', 'Users', 'users', 'id', 'Emails', 'emails', 'assigned_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('ef501813-f2a6-2cfa-7e44-5f003ee6d4a9', 'stic_payments_stic_registrations', 'stic_Payments', 'stic_payments', 'id', 'stic_Registrations', 'stic_registrations', 'id', 'stic_payments_stic_registrations_c', 'stic_payments_stic_registrationsstic_payments_ida', 'stic_payments_stic_registrationsstic_registrations_idb', 'many-to-many', NULL, NULL, 0, 0),
('ef8362c2-8773-15cf-dd52-5f003e5d9d63', 'securitygroups_emails', 'SecurityGroups', 'securitygroups', 'id', 'Emails', 'emails', 'id', 'securitygroups_records', 'securitygroup_id', 'record_id', 'many-to-many', 'module', 'Emails', 0, 0),
('efcac557-4119-5f4d-8792-5f003e00fef4', 'stic_payments_stic_payment_commitments', 'stic_Payment_Commitments', 'stic_payment_commitments', 'id', 'stic_Payments', 'stic_payments', 'id', 'stic_payments_stic_payment_commitments_c', 'stic_paymebfe2itments_ida', 'stic_payments_stic_payment_commitmentsstic_payments_idb', 'many-to-many', NULL, NULL, 0, 0),
('efdf91a2-4d76-5aed-678c-5f003e1921c5', 'emails_notes_rel', 'Emails', 'emails', 'id', 'Notes', 'notes', 'parent_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('f085d07c-4e9d-3790-3764-5f003e8c0d92', 'stic_payments_stic_remittances', 'stic_Remittances', 'stic_remittances', 'id', 'stic_Payments', 'stic_payments', 'id', 'stic_payments_stic_remittances_c', 'stic_payments_stic_remittancesstic_remittances_ida', 'stic_payments_stic_remittancesstic_payments_idb', 'many-to-many', NULL, NULL, 0, 0),
('f0a86da1-41bd-298f-eecc-5f003e964cb9', 'emails_contacts_rel', 'Emails', 'emails', 'id', 'Contacts', 'contacts', 'id', 'emails_beans', 'email_id', 'bean_id', 'many-to-many', 'bean_module', 'Contacts', 0, 0),
('f10dc956-e823-6bb0-f349-5f003ee88952', 'aos_products_modified_user', 'Users', 'users', 'id', 'AOS_Products', 'aos_products', 'modified_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('f13d7ac7-6672-df91-e9ae-5f003e5b6892', 'stic_payments_activities_emails', 'stic_Payments', 'stic_payments', 'id', 'Emails', 'emails', 'parent_id', NULL, NULL, NULL, 'one-to-many', 'parent_type', 'stic_Payments', 0, 0),
('f1792161-15c2-657c-4e94-5f003e46b0f9', 'emails_accounts_rel', 'Emails', 'emails', 'id', 'Accounts', 'accounts', 'id', 'emails_beans', 'email_id', 'bean_id', 'many-to-many', 'bean_module', 'Accounts', 0, 0),
('f1924dee-3008-5ef2-11b6-5f003e2cf5d7', 'stic_accounts_relationships_accounts', 'Accounts', 'accounts', 'id', 'stic_Accounts_Relationships', 'stic_accounts_relationships', 'id', 'stic_accounts_relationships_accounts_c', 'stic_accounts_relationships_accountsaccounts_ida', 'stic_accoub36donships_idb', 'many-to-many', NULL, NULL, 0, 0),
('f1d11212-1b6d-f4be-9c4a-5f003e77e12a', 'emails_leads_rel', 'Emails', 'emails', 'id', 'Leads', 'leads', 'id', 'emails_beans', 'email_id', 'bean_id', 'many-to-many', 'bean_module', 'Leads', 0, 0),
('f22a6053-1874-4602-ce22-5f003ecd2b04', 'aos_products_created_by', 'Users', 'users', 'id', 'AOS_Products', 'aos_products', 'created_by', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('f25f1491-e028-4f16-ffbb-5f003eb9e5dd', 'stic_payment_commitments_accounts', 'Accounts', 'accounts', 'id', 'stic_Payment_Commitments', 'stic_payment_commitments', 'id', 'stic_payment_commitments_accounts_c', 'stic_payment_commitments_accountsaccounts_ida', 'stic_payment_commitments_accountsstic_payment_commitments_idb', 'many-to-many', NULL, NULL, 0, 0),
('f299de76-6588-3271-e93f-5f003e5cd046', 'emails_aos_contracts_rel', 'Emails', 'emails', 'id', 'AOS_Contracts', 'aos_contracts', 'id', 'emails_beans', 'email_id', 'bean_id', 'many-to-many', 'bean_module', 'AOS_Contracts', 0, 0),
('f2c3544a-f539-2967-7006-5f003e719162', 'stic_sessions_stic_events', 'stic_Events', 'stic_events', 'id', 'stic_Sessions', 'stic_sessions', 'id', 'stic_sessions_stic_events_c', 'stic_sessions_stic_eventsstic_events_ida', 'stic_sessions_stic_eventsstic_sessions_idb', 'many-to-many', NULL, NULL, 0, 0),
('f2eeb7da-b1ba-9fd4-e3d7-5f003ea42b9e', 'aos_products_assigned_user', 'Users', 'users', 'id', 'AOS_Products', 'aos_products', 'assigned_user_id', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0),
('f3578852-fc15-befb-2a40-5f003e9846f0', 'emails_meetings_rel', 'Emails', 'emails', 'id', 'Meetings', 'meetings', 'id', 'emails_beans', 'email_id', 'bean_id', 'many-to-many', 'bean_module', 'Meetings', 0, 0),
('f399f08b-65ce-7e30-b536-5f003e8c4fd1', 'stic_payments_accounts', 'Accounts', 'accounts', 'id', 'stic_Payments', 'stic_payments', 'id', 'stic_payments_accounts_c', 'stic_payments_accountsaccounts_ida', 'stic_payments_accountsstic_payments_idb', 'many-to-many', NULL, NULL, 0, 0),
('f3b406db-cb58-8e77-932f-5f003e0b4cc8', 'securitygroups_aos_products', 'SecurityGroups', 'securitygroups', 'id', 'AOS_Products', 'aos_products', 'id', 'securitygroups_records', 'securitygroup_id', 'record_id', 'many-to-many', 'module', 'AOS_Products', 0, 0),
('fd500b83-e1c4-01db-079a-5f003e262bdb', 'tasks_created_by', 'Users', 'users', 'id', 'Tasks', 'tasks', 'created_by', NULL, NULL, NULL, 'one-to-many', NULL, NULL, 0, 0);

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

--
-- Dumping data for table `upgrade_history`
--

INSERT INTO `upgrade_history` (`id`, `filename`, `md5sum`, `type`, `status`, `version`, `name`, `description`, `id_name`, `manifest`, `date_entered`, `enabled`) VALUES
('9b5666c4-3ebe-4624-b7e9-5f003de4ae5e', 'upload://upgrades/patch/SuiteCRM-Upgrade-7.11.x-to-7.11.15 (1).zip', '4c11ba3301f1f3d57908ee1b01f6952c', 'patch', 'installed', '7.11.8', NULL, NULL, NULL, 'YTozOntzOjg6Im1hbmlmZXN0IjthOjg6e3M6NDoibmFtZSI7czo4OiJTdWl0ZUNSTSI7czo2OiJhdXRob3IiO3M6MTI6IlNhbGVzQWdpbGl0eSI7czo0OiJ0eXBlIjtzOjU6InBhdGNoIjtzOjE0OiJwdWJsaXNoZWRfZGF0ZSI7czoxOToiMjAyMC0wNi0xMSAxMjowMDowMCI7czo3OiJ2ZXJzaW9uIjtzOjc6IjcuMTEuMTUiO3M6MTY6ImlzX3VuaW5zdGFsbGFibGUiO2I6MDtzOjI4OiJhY2NlcHRhYmxlX3N1aXRlY3JtX3ZlcnNpb25zIjthOjE6e3M6MTM6InJlZ2V4X21hdGNoZXMiO2E6Mjp7aTowO3M6OToiXjcuMTEoLSopIjtpOjE7czo5OiJeNy4xMSguKikiO319czoxMDoiY29weV9maWxlcyI7YToyOntzOjg6ImZyb21fZGlyIjtzOjM0OiJTdWl0ZUNSTS1VcGdyYWRlLTcuMTEueC10by03LjExLjE1IjtzOjY6InRvX2RpciI7czowOiIiO319czoxMToiaW5zdGFsbGRlZnMiO3M6MDoiIjtzOjE2OiJ1cGdyYWRlX21hbmlmZXN0IjtzOjA6IiI7fQ==', '2020-07-04 08:28:48', 1);

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
  ADD KEY `idx_plp_rel_id` (`related_id`,`related_type`,`prospect_list_id`),
  ADD KEY `idx_plp_pro_id` (`prospect_list_id`,`deleted`);

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
