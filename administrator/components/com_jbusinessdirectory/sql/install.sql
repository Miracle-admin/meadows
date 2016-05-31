DROP TABLE IF EXISTS `#__jbusinessdirectory_applicationsettings`;
DROP TABLE IF EXISTS `#__jbusinessdirectory_categories`;
DROP TABLE IF EXISTS `#__jbusinessdirectory_companies`;
DROP TABLE IF EXISTS `#__jbusinessdirectory_company_category`;
DROP TABLE IF EXISTS `#__jbusinessdirectory_company_contact`;
DROP TABLE IF EXISTS `#__jbusinessdirectory_company_claim`;
DROP TABLE IF EXISTS `#__jbusinessdirectory_company_images`;
DROP TABLE IF EXISTS `#__jbusinessdirectory_company_contact`;
DROP TABLE IF EXISTS `#__jbusinessdirectory_company_offers`;
DROP TABLE IF EXISTS `#__jbusinessdirectory_company_offer_pictures`;
DROP TABLE IF EXISTS `#__jbusinessdirectory_company_offer_category`;
DROP TABLE IF EXISTS `#__jbusinessdirectory_company_pictures`;
DROP TABLE IF EXISTS `#__jbusinessdirectory_company_ratings`;
DROP TABLE IF EXISTS `#__jbusinessdirectory_company_reviews`;
DROP TABLE IF EXISTS `#__jbusinessdirectory_company_review_abuses`;
DROP TABLE IF EXISTS `#__jbusinessdirectory_company_review_responses`;
DROP TABLE IF EXISTS `#__jbusinessdirectory_company_reviews_criteria`;
DROP TABLE IF EXISTS `#__jbusinessdirectory_company_reviews_user_criteria`;
DROP TABLE IF EXISTS `#__jbusinessdirectory_company_types`;
DROP TABLE IF EXISTS `#__jbusinessdirectory_company_videos`;
DROP TABLE IF EXISTS `#__jbusinessdirectory_countries`;
DROP TABLE IF EXISTS `#__jbusinessdirectory_currencies`;
DROP TABLE IF EXISTS `#__jbusinessdirectory_date_formats`;
DROP TABLE IF EXISTS `#__jbusinessdirectory_emails`;
DROP TABLE IF EXISTS `#__jbusinessdirectory_packages`;
DROP TABLE IF EXISTS `#__jbusinessdirectory_package_fields`;
DROP TABLE IF EXISTS `#__jbusinessdirectory_orders`;
DROP TABLE IF EXISTS `#__jbusinessdirectory_payment_processors`;
DROP TABLE IF EXISTS `#__jbusinessdirectory_payment_processor_fields`;
DROP TABLE IF EXISTS `#__jbusinessdirectory_attributes`;
DROP TABLE IF EXISTS `#__jbusinessdirectory_attribute_options`;
DROP TABLE IF EXISTS `#__jbusinessdirectory_attribute_types`;
DROP TABLE IF EXISTS `#__jbusinessdirectory_company_attributes`;
DROP TABLE IF EXISTS `#__jbusinessdirectory_payments`;
DROP TABLE IF EXISTS `#__jbusinessdirectory_company_events`;
DROP TABLE IF EXISTS `#__jbusinessdirectory_company_event_pictures`;
DROP TABLE IF EXISTS `#__jbusinessdirectory_company_event_types`;
DROP TABLE IF EXISTS `#__jbusinessdirectory_default_attributes`;
DROP TABLE IF EXISTS `#__jbusinessdirectory_cities`;
DROP TABLE IF EXISTS `#__jbusinessdirectory_company_activity_city`;
DROP TABLE IF EXISTS `#__jbusinessdirectory_reports`;
DROP TABLE IF EXISTS `#__jbusinessdirectory_company_locations`;
DROP TABLE IF EXISTS `#__jbusinessdirectory_discounts`;
DROP TABLE IF EXISTS `#__jbusinessdirectory_language_translations`;
DROP TABLE IF EXISTS `#__jbusinessdirectory_company_attachments`;
DROP TABLE IF EXISTS `#__jbusinessdirectory_billing_details`;
DROP TABLE IF EXISTS `#__jbusinessdirectory_bookmarks`;
--
-- Table structure for table `#__jbusinessdirectory_applicationsettings`
--

CREATE TABLE IF NOT EXISTS `#__jbusinessdirectory_applicationsettings` (
  `applicationsettings_id` int(10) NOT NULL DEFAULT '1',
  `company_name` char(255) NOT NULL,
  `company_email` char(255) NOT NULL,
  `currency_id` int(10) NOT NULL,
  `css_style` char(255) NOT NULL,
  `css_module_style` char(255) NOT NULL,
  `show_frontend_language` tinyint(1) NOT NULL DEFAULT '1',
  `default_frontend_language` char(50) NOT NULL DEFAULT 'en-GB',
  `date_format_id` int(5) NOT NULL,
  `enable_packages` tinyint(1) NOT NULL DEFAULT '0',
  `enable_ratings` tinyint(1) NOT NULL DEFAULT '1',
  `enable_reviews` tinyint(1) NOT NULL DEFAULT '1',
  `enable_offers` tinyint(1) NOT NULL DEFAULT '1',
  `enable_events` tinyint(1) NOT NULL DEFAULT '1',
  `enable_seo` tinyint(1) NOT NULL DEFAULT '1',
  `enable_search_filter` tinyint(1) NOT NULL DEFAULT '1',
  `enable_reviews_users` tinyint(4) NOT NULL DEFAULT '0',
  `enable_numbering` tinyint(1) NOT NULL DEFAULT '1',
  `enable_search_filter_offers` tinyint(1) NOT NULL DEFAULT '1',
  `enable_search_filter_events` tinyint(1) NOT NULL DEFAULT '1',
  `show_search_map` tinyint(1) NOT NULL DEFAULT '1',
  `show_search_description` tinyint(1) NOT NULL DEFAULT '1',
  `show_details_user` tinyint(1) NOT NULL DEFAULT '0',
  `company_view` tinyint(1) NOT NULL DEFAULT '1',
  `category_view` tinyint(1) NOT NULL DEFAULT '2',
  `search_result_view` tinyint(1) NOT NULL DEFAULT '1',
  `captcha` tinyint(1) NOT NULL DEFAULT '0',
  `nr_images_slide` tinyint(4) NOT NULL DEFAULT '5',
  `show_pending_approval` tinyint(1) NOT NULL DEFAULT '0',
  `allow_multiple_companies` tinyint(1) NOT NULL DEFAULT '1',
  `meta_description` text,
  `meta_keywords` text,
  `meta_description_facebook` text,
  `limit_cities` varchar(1) NOT NULL DEFAULT '0',
  `metric` varchar(1) NOT NULL DEFAULT '1',
  `user_location` varchar(1) NOT NULL DEFAULT '1',
  `search_type` varchar(1) NOT NULL DEFAULT '0',
  `zipcode_search_type` varchar(1) DEFAULT '0',
  `map_auto_show` varchar(1) DEFAULT '0',
  `menu_item_id` varchar(10) DEFAULT NULL,
  `order_id` varchar(255) DEFAULT NULL,
  `order_email` varchar(255) DEFAULT NULL,
  `claim_business` varchar(1) DEFAULT '1',
  `terms_conditions` blob,
  `vat` tinyint(4) DEFAULT '0',
  `expiration_day_notice` tinyint(2) DEFAULT NULL,
  `show_cat_description` tinyint(1) DEFAULT NULL,
  `direct_processing` tinyint(1) DEFAULT NULL,
  `max_video` tinyint(2) DEFAULT '10',
  `max_pictures` tinyint(2) DEFAULT '15',
  `show_secondary_locations` tinyint(1) DEFAULT '0',
  `search_view_mode` tinyint(1) DEFAULT '0',
  `address_format` tinyint(1) NOT NULL DEFAULT '0',
  `offer_search_results_grid_view` tinyint(1) DEFAULT '0',
  `enable_multilingual` tinyint(1) NOT NULL DEFAULT '0',
  `offers_view_mode` TINYINT(1) NOT NULL DEFAULT 0,
  `enable_geolocation` tinyint(1) NOT NULL DEFAULT '0',
  `enable_google_map_clustering` tinyint(1) NOT NULL DEFAULT '0',
  `add_url_id` tinyint(1) NOT NULL DEFAULT '0',
  `currency_display` tinyint(1) NOT NULL DEFAULT '1',
  `amount_separator` tinyint(1) NOT NULL DEFAULT '1',
  `currency_location` tinyint(1) DEFAULT '1',
  `currency_symbol` varchar(45) DEFAULT NULL,
  `show_email` TINYINT(1) NOT NULL DEFAULT 0,
  `enable_attachments` TINYINT(1) NULL DEFAULT 1,
  `order_search_listings` varchar(45) DEFAULT NULL,
  `order_search_offers` varchar(45) DEFAULT NULL,
  `order_search_events` varchar(45) DEFAULT NULL,
  `events_search_view` tinyint(1) DEFAULT '2',
  `enable_bookmarks` tinyint(1) NOT NULL DEFAULT '1',
  `max_attachments` TINYINT(4) NOT NULL DEFAULT '5',
  `max_categories` TINYINT(4) NOT NULL DEFAULT '10',
  `time_format` varchar(45) NOT NULL DEFAULT 'H:i:s',
  `front_end_acl` tinyint(1) NOT NULL DEFAULT '0',
  `listing_url_type` TINYINT NOT NULL DEFAULT '1',
  `show_secondary_map_locations` TINYINT(1) NOT NULL DEFAULT 0,
  `search_result_grid_view` TINYINT(1) NOT NULL DEFAULT 1,
  `facebook` VARCHAR(45) DEFAULT NULL,
  `twitter` VARCHAR(45) DEFAULT NULL,
  `googlep` VARCHAR(45) DEFAULT NULL,
  `linkedin` VARCHAR(45) DEFAULT NULL,
  `youtube` VARCHAR(45) DEFAULT NULL,
  `logo` VARCHAR(145) DEFAULT NULL,
  PRIMARY KEY (`applicationsettings_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `#__jbusinessdirectory_applicationsettings`
--

INSERT INTO `#__jbusinessdirectory_applicationsettings` (`applicationsettings_id`, `company_name`, `company_email`, `currency_id`, `css_style`, `css_module_style`, `show_frontend_language`, `default_frontend_language`, `date_format_id`, `enable_packages`, `enable_ratings`, `enable_reviews`, `enable_offers`, `enable_events`, `enable_seo`, `enable_search_filter`, `enable_reviews_users`, `enable_numbering`, `enable_search_filter_offers`, `enable_search_filter_events`, `show_search_map`, `show_search_description`, `show_details_user`, `company_view`, `category_view`, `search_result_view`, `captcha`, `nr_images_slide`, `show_pending_approval`, `allow_multiple_companies`, `meta_description`, `meta_keywords`, `meta_description_facebook`, `limit_cities`, `metric`, `user_location`, `search_type`, `zipcode_search_type`, `map_auto_show`, `menu_item_id`, `order_id`, `order_email`, `claim_business`, `terms_conditions`, `vat`, `expiration_day_notice`, `show_cat_description`, `direct_processing`, `max_video`, `max_pictures`, `show_secondary_locations`, `search_view_mode`, `address_format`, `offer_search_results_grid_view`, `enable_multilingual`, `offers_view_mode`, `enable_geolocation`, `add_url_id`, `currency_display`, `amount_separator`, `currency_location`, `currency_symbol`, `show_email`, `enable_attachments`, `facebook`, `twitter`, `googlep`, `linkedin`, `youtube`, `logo`) VALUES
(1, 'JBusinessDirectory', 'office@site.com', 143, '', 'style.css', 1, 'en-GB', 2, 0, 1, 1, 1, 1, 1, 1, 0, 1, 1, 1, 1, 1, 0, 3, 3, 1, 0, 5, 0, 1, '', '', '', '0', '1', '1', '0', '0', '0', '', NULL, NULL, '1', '', 0, 0, 1, 0, 10, 15, 0, 0, 0, 0, 0, 0, 0, 0, 1, 1, 1, '', 0, 1, 'http://www.facebook.com', 'http://www.twiter.com', 'http://www.googleplus.com', 'http://www.linkedin.com', 'http://www.youtube.com', '/companies/mydirectory-logo-1444760776.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `#__jbusinessdirectory_attributes`
--

CREATE TABLE IF NOT EXISTS `#__jbusinessdirectory_attributes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `code` varchar(100) DEFAULT NULL,
  `type` int(11) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `is_mandatory` int(1) NOT NULL DEFAULT '0',
  `show_in_filter` int(1) NOT NULL DEFAULT '1',
  `show_in_front` tinyint(1) NOT NULL DEFAULT '0',
  `show_on_search` tinyint(1) NOT NULL,
  `ordering` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=10 ;

-- --------------------------------------------------------

--
-- Table structure for table `#__jbusinessdirectory_attribute_options`
--

CREATE TABLE IF NOT EXISTS `#__jbusinessdirectory_attribute_options` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `attribute_id` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=51 ;

-- --------------------------------------------------------

--
-- Table structure for table `#__jbusinessdirectory_attribute_types`
--

CREATE TABLE IF NOT EXISTS `#__jbusinessdirectory_attribute_types` (
  `id` int(11) NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `#__jbusinessdirectory_attribute_types`
--

INSERT INTO `#__jbusinessdirectory_attribute_types` (`id`, `code`, `name`) VALUES
(1, 'input', 'Input'),
(2, 'select_box', 'Select Box'),
(3, 'checkbox', 'Checkbox(Multiple Select)'),
(4, 'radio', 'Radio(Single Select)'),
(5, 'header', 'Header'),
(6, 'textarea', 'Textarea');

-- --------------------------------------------------------

--
-- Table structure for table `#__jbusinessdirectory_billing_details`
--

CREATE TABLE IF NOT EXISTS `#__jbusinessdirectory_billing_details` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(45) DEFAULT NULL,
  `last_name` varchar(45) DEFAULT NULL,
  `company_name` varchar(55) DEFAULT NULL,
  `address` varchar(55) DEFAULT NULL,
  `postal_code` varchar(45) DEFAULT NULL,
  `city` varchar(45) DEFAULT NULL,
  `region` varchar(45) DEFAULT NULL,
  `country` varchar(45) DEFAULT NULL,
  `email` varchar(45) DEFAULT NULL,
  `phone` varchar(45) DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;


-- --------------------------------------------------------

--
-- Table structure for table `#__jbusinessdirectory_bookmarks`
--

CREATE TABLE IF NOT EXISTS `#__jbusinessdirectory_bookmarks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `company_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `note` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;


--
-- Table structure for table `#__jbusinessdirectory_categories`
--

CREATE TABLE IF NOT EXISTS `#__jbusinessdirectory_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) NOT NULL DEFAULT '0',
  `lft` int(11) NOT NULL DEFAULT '0',
  `rgt` int(11) NOT NULL DEFAULT '0',
  `level` int(10) unsigned NOT NULL DEFAULT '0',
  `name` varchar(100) NOT NULL,
  `alias` varchar(100) NOT NULL DEFAULT '',
  `description` varchar(500) DEFAULT NULL,
  `published` tinyint(4) NOT NULL,
  `imageLocation` varchar(250) DEFAULT NULL,
  `markerLocation` varchar(250) DEFAULT NULL,
  `path` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_alias` (`alias`),
  KEY `idx_parent` (`parent_id`),
  KEY `idx_name` (`name`),
  KEY `idx_state` (`published`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

--
-- Dumping data for table `#__jbusinessdirectory_categories`
--

INSERT INTO `#__jbusinessdirectory_categories` (`id`, `parent_id`, `lft`, `rgt`, `level`, `name`, `alias`, `description`, `published`, `imageLocation`, `markerLocation`, `path`) VALUES
(1, 0, 0, 137, 0, 'Root', 'root', '', 1, '', '', ''),
(12, 1, 1, 8, 1, 'Books', 'books-1', '', 1, '/categories/image5-1427100316.jpg', '/categories/marker_book-1411754028.png', 'books-1'),
(34, 7, 24, 25, 2, 'Software', 'software', '', 1, '', NULL, 'electronics/software'),
(35, 8, 38, 39, 2, 'Women', 'women', '', 1, '', NULL, 'fashion/women'),
(36, 8, 34, 35, 2, 'Man', 'man', '', 1, '', NULL, 'fashion/man'),
(37, 8, 32, 33, 2, 'Kids & Baby', 'kids-&-baby', '', 1, '', NULL, 'fashion/kids-&-baby'),
(38, 8, 36, 37, 2, 'Shoes', 'shoes', '', 1, '', NULL, 'fashion/shoes'),
(39, 29, 42, 43, 2, 'Grocery & Gourmet Food', 'grocery-&-gourmet-food', '', 1, '', NULL, 'grocery-health-beauty/grocery-&-gourmet-food'),
(40, 29, 48, 49, 2, 'Wine', 'wine', '', 1, '', NULL, 'grocery-health-beauty/wine'),
(41, 29, 46, 47, 2, 'Natural & Organic', 'natural-&-organic', '', 1, '', NULL, 'grocery-health-beauty/natural-&-organic'),
(42, 29, 44, 45, 2, 'Health & Personal Care', 'health-&-personal-care', '', 1, '', NULL, 'grocery-health-beauty/health-&-personal-care'),
(43, 11, 58, 59, 2, 'Kitchen & Dining', 'kitchen-&-dining', '', 1, '', NULL, 'home-garden/kitchen-&-dining'),
(44, 11, 56, 57, 2, 'Furniture & D', 'furniture-&-d', '', 1, '', NULL, 'home-garden/furniture-&-d'),
(45, 11, 54, 55, 2, 'Bedding & Bath', 'bedding-&-bath', '', 1, '', NULL, 'home-garden/bedding-&-bath'),
(46, 11, 60, 61, 2, 'Patio, Lawn & Garden', 'patio,-lawn-&-garden', '', 1, '', NULL, 'home-garden/patio,-lawn-&-garden'),
(47, 11, 52, 53, 2, 'Arts, Crafts & Sewing', 'arts,-crafts-&-sewing', '', 1, '', NULL, 'home-garden/arts,-crafts-&-sewing'),
(48, 30, 72, 73, 2, 'Watches', 'watches', '', 1, '', NULL, 'jewelry-watches/watches'),
(49, 30, 70, 71, 2, 'Fine Jewelry', 'fine-jewelry', '', 1, '', NULL, 'jewelry-watches/fine-jewelry'),
(51, 30, 66, 67, 2, 'Fashion Jewelry', 'fashion-jewelry', '', 1, '', NULL, 'jewelry-watches/fashion-jewelry'),
(52, 30, 68, 69, 2, 'Fashion Jewelry', 'fashion-jewelry', '', 1, '', NULL, 'jewelry-watches/fashion-jewelry'),
(53, 30, 64, 65, 2, 'Engagement & Wedding', 'engagement-&-wedding', '', 1, '', NULL, 'jewelry-watches/engagement-&-wedding'),
(54, 10, 80, 81, 2, 'Movies & TV', 'movies-&-tv', '', 1, '', NULL, 'movies-music-games/movies-&-tv'),
(55, 10, 76, 77, 2, 'Blu-ray', 'blu-ray', '', 1, '', NULL, 'movies-music-games/blu-ray'),
(30, 1, 63, 74, 1, 'Jewelry & Watches', 'jewelry-watches', 'Pellentesque convallis est vel velit luctus, in consequat tortor rutrum. In lectus quam, tempor eu diam efficitur, fringilla aliquet sapien. Praesent quis tellus id enim imperdiet tempor. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices po', 1, '/categories/watch-1427100687.jpg', '/categories/marker_electronics-1411754433.png', 'jewelry-watches'),
(5, 1, 93, 104, 1, 'Sports & Outdors', 'sports-outdors', 'Pellentesque convallis est vel velit luctus, in consequat tortor rutrum. In lectus quam, tempor eu diam efficitur, fringilla aliquet sapien. Praesent quis tellus id enim imperdiet tempor. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices po', 1, '/categories/image3-1411751531.jpg', '/categories/marker_sport-1411754446.png', 'sports-outdors'),
(31, 7, 16, 17, 2, 'Cell Phones & Accessories', 'cell-phones-&-accessories', '', 1, '', NULL, 'electronics/cell-phones-&-accessories'),
(7, 1, 15, 30, 1, 'Electronics', 'electronics', 'Pellentesque convallis est vel velit luctus, in consequat tortor rutrum. In lectus quam, tempor eu diam efficitur, fringilla aliquet sapien. Praesent quis tellus id enim imperdiet tempor. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices po', 1, '/categories/image6-1427100355.jpg', '/categories/marker_electronics-1411754102.png', 'electronics'),
(8, 1, 31, 40, 1, 'Fashion', 'fashion', 'Pellentesque convallis est vel velit luctus, in consequat tortor rutrum. In lectus quam, tempor eu diam efficitur, fringilla aliquet sapien. Praesent quis tellus id enim imperdiet tempor. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices po', 1, '/categories/slide-image-8-1427100458.jpg', '/categories/marker_mask-1411754410.png', 'fashion'),
(9, 1, 105, 116, 1, 'Toy,Kids & Babies', 'toy-kids-babies', 'Pellentesque convallis est vel velit luctus, in consequat tortor rutrum. In lectus quam, tempor eu diam efficitur, fringilla aliquet sapien. Praesent quis tellus id enim imperdiet tempor. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices po', 1, '/categories/image1-1427100760.jpg', '/categories/marker_electronics-1411754456.png', 'toy-kids-babies'),
(10, 1, 75, 86, 1, 'Movies, Music & Games', 'movies-music-games', '', 1, '/categories/image2-1427100712.jpg', '/categories/marker_music-1411754173.png', 'movies-music-games'),
(11, 1, 51, 62, 1, 'Home & Garden', 'home-garden', 'Pellentesque convallis est vel velit luctus, in consequat tortor rutrum. In lectus quam, tempor eu diam efficitur, fringilla aliquet sapien. Praesent quis tellus id enim imperdiet tempor. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices po', 1, '/categories/image7-1427100616.jpg', '/categories/marker_home-1411754161.png', 'home-garden'),
(32, 7, 28, 29, 2, 'Video Games', 'video-games', '', 1, '', NULL, 'electronics/video-games'),
(33, 7, 18, 19, 2, 'Computer Parts & Components', 'computer-parts-&-components', '', 1, '', NULL, 'electronics/computer-parts-&-components'),
(28, 7, 20, 21, 2, 'Electronics Accessories', 'electronics-accessories', '', 1, '', NULL, 'electronics/electronics-accessories'),
(26, 7, 22, 23, 2, 'Home, Audio & Theater', 'home,-audio-&-theater', '', 1, '', NULL, 'electronics/home,-audio-&-theater'),
(25, 7, 26, 27, 2, 'TV ', 'tv-', '', 1, '', NULL, 'electronics/tv-'),
(24, 13, 12, 13, 2, 'Photography', 'photography', '', 1, '', NULL, 'camera-photography/photography'),
(23, 13, 10, 11, 2, 'Camera', 'camera', '', 1, '', NULL, 'camera-photography/camera'),
(21, 12, 6, 7, 2, 'Textbooks', 'textbooks', '', 1, '', NULL, 'books-1/textbooks'),
(22, 12, 4, 5, 2, 'Children''s Books', 'children''s-books', '', 1, '', NULL, 'books-1/children''s-books'),
(19, 12, 2, 3, 2, 'Books', 'books', '', 1, '', NULL, 'books-1/books'),
(18, 74, 134, 135, 2, 'Tires ', 'tires-', '', 1, '', NULL, 'automotive-motors/tires-'),
(17, 74, 132, 133, 2, 'Car Electronics', 'car-electronics', '', 1, '', NULL, 'automotive-motors/car-electronics'),
(16, 74, 130, 131, 2, 'Automotive Tools', 'automotive-tools', '', 1, '', NULL, 'automotive-motors/automotive-tools'),
(14, 74, 128, 129, 2, 'Automotive Parts', 'automotive-parts', '', 1, '', NULL, 'automotive-motors/automotive-parts'),
(13, 1, 9, 14, 1, 'Camera & Photography', 'camera-photography', 'Pellentesque convallis est vel velit luctus, in consequat tortor rutrum. In lectus quam, tempor eu diam efficitur, fringilla aliquet sapien. Praesent quis tellus id enim imperdiet tempor. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices po', 1, '/categories/image7-1427100335.jpg', '/categories/marker_photo-1411754091.png', 'camera-photography'),
(56, 10, 84, 85, 2, 'Musical Instruments', 'musical-instruments', '', 1, '', NULL, 'movies-music-games/musical-instruments'),
(57, 10, 82, 83, 2, 'MP3 Downloads', 'mp3-downloads', '', 1, '', NULL, 'movies-music-games/mp3-downloads'),
(58, 10, 78, 79, 2, 'Game Downloads', 'game-downloads', '', 1, '', NULL, 'movies-music-games/game-downloads'),
(59, 5, 96, 97, 2, 'Exercise & Fitness', 'exercise-&-fitness', '', 1, '', NULL, 'sports-outdors/exercise-&-fitness'),
(60, 5, 100, 101, 2, 'Outdoor Recreation', 'outdoor-recreation', '', 1, '', NULL, 'sports-outdors/outdoor-recreation'),
(61, 5, 98, 99, 2, 'Hunting & Fishing', 'hunting-&-fishing', '', 1, '', NULL, 'sports-outdors/hunting-&-fishing'),
(62, 5, 94, 95, 2, 'Cycling', 'cycling', '', 1, '', NULL, 'sports-outdors/cycling'),
(63, 5, 102, 103, 2, 'Team Sports', 'team-sports', '', 1, '', NULL, 'sports-outdors/team-sports'),
(64, 9, 112, 113, 2, 'Toys & Games', 'toys-&-games', '', 1, '', NULL, 'toy-kids-babies/toys-&-games'),
(65, 9, 106, 107, 2, 'Baby', 'baby', '', 1, '', NULL, 'toy-kids-babies/baby'),
(66, 9, 110, 111, 2, 'Clothing (Kids & Baby)', 'clothing-(kids-&-baby)', '', 1, '', NULL, 'toy-kids-babies/clothing-(kids-&-baby)'),
(67, 9, 114, 115, 2, 'Video Games for Kids', 'video-games-for-kids', '', 1, '', NULL, 'toy-kids-babies/video-games-for-kids'),
(68, 9, 108, 109, 2, 'Baby Registry', 'baby-registry', '', 1, '', NULL, 'toy-kids-babies/baby-registry'),
(69, 3, 90, 91, 2, 'Services', 'services', '', 1, '', NULL, 'services/services'),
(70, 3, 88, 89, 2, 'IT Services', 'it-services', '', 1, '', NULL, 'services/it-services'),
(29, 1, 41, 50, 1, 'Health & Beauty', 'grocery-health-beauty', '', 1, '/categories/slide1-the-health-and-beauty-world-1427100497.jpg', '/categories/marker_health-1411754146.png', 'grocery-health-beauty'),
(3, 1, 87, 92, 1, 'Services', 'services', 'Pellentesque convallis est vel velit luctus, in consequat tortor rutrum. In lectus quam, tempor eu diam efficitur, fringilla aliquet sapien. Praesent quis tellus id enim imperdiet tempor. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices po', 1, '/categories/image9-1411751440.png', '/categories/marker_service-1411754187.png', 'services'),
(74, 1, 127, 136, 1, 'Automotive & Motors', 'automotive-motors', 'Pellentesque convallis est vel velit luctus, in consequat tortor rutrum. In lectus quam, tempor eu diam efficitur, fringilla aliquet sapien. Praesent quis tellus id enim imperdiet tempor. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices po', 1, '/categories/image5-1427100860.jpg', '/categories/marker_auto-1411754020.png', 'automotive-motors'),
(75, 1, 117, 124, 1, 'Restaurants', 'restaurants', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam purus libero, luctus id felis at, porta malesuada massa. Vestibulum vitae imperdiet justo, eget ullamcorper nunc. Cras eget ligula sodales, congue lacus eget, tincidunt justo. Ut vestibulum bibendum ante, vitae scelerisque leo faucibus id. Nunc congue, justo id porttitor fringilla, ipsum ex rutrum lorem, in sodales nisi ipsum at massa. Duis a cursus ipsum. Aliquam vitae est tortor. Aenean aliquet ultrices magna et efficitur. Mauris c', 1, '/categories/image3-1427100807.jpg', '', 'restaurants'),
(76, 75, 118, 119, 2, 'Asian Restaurants', 'asian-restaurants', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam purus libero, luctus id felis at, porta malesuada massa. Vestibulum vitae imperdiet justo, eget ullamcorper nunc. Cras eget ligula sodales, congue lacus eget, tincidunt justo. Ut vestibulum bibendum ante, vitae scelerisque leo faucibus id. Nunc congue, justo id porttitor fringilla, ipsum ex rutrum lorem, in sodales nisi ipsum at massa. Duis a cursus ipsum. Aliquam vitae est tortor. Aenean aliquet ultrices magna et efficitur.', 1, '', '', 'restaurants/asian-restaurants'),
(77, 75, 120, 121, 2, 'French Restaurants', 'french-restaurants', '', 1, '', '', 'restaurants/french-restaurants'),
(78, 75, 122, 123, 2, 'Italian Restaurants', 'italian-restaurants', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam purus libero, luctus id felis at, porta malesuada massa. Vestibulum vitae imperdiet justo, eget ullamcorper nunc. Cras eget ligula sodales, congue lacus eget, tincidunt justo. Ut vestibulum bibendum ante, vitae scelerisque leo faucibus id. Nunc congue, justo id porttitor fringilla, ipsum ex rutrum lorem, in sodales nisi ipsum at massa. Duis a cursus ipsum. Aliquam vitae est tortor. Aenean aliquet ultrices magna et efficitur.', 1, '', '', 'restaurants/italian-restaurants'),
(79, 1, 125, 126, 1, 'Real Estate', 'real-estate', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam purus libero, luctus id felis at, porta malesuada massa. Vestibulum vitae imperdiet justo, eget ullamcorper nunc. Cras eget ligula sodales, congue lacus eget, tincidunt justo. Ut vestibulum bibendum ante, vitae scelerisque leo faucibus id. Nunc congue, justo id porttitor fringilla, ipsum ex rutrum lorem, in sodales nisi ipsum at massa. Duis a cursus ipsum. Aliquam vitae est tortor. Aenean aliquet ultrices magna et efficitur.', 1, '/categories/image9-1427100841.jpg', '', 'real-estate');

-- --------------------------------------------------------

--
-- Table structure for table `#__jbusinessdirectory_cities`
--

CREATE TABLE IF NOT EXISTS `#__jbusinessdirectory_cities` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(65) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `#__jbusinessdirectory_cities`
--

INSERT INTO `#__jbusinessdirectory_cities` (`id`, `name`) VALUES
(1, 'Toronto'),
(2, 'Montreal');

-- --------------------------------------------------------

--
-- Table structure for table `#__jbusinessdirectory_companies`
--

CREATE TABLE IF NOT EXISTS `#__jbusinessdirectory_companies` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `alias` varchar(100) NOT NULL DEFAULT '',
  `comercialName` varchar(120) DEFAULT NULL,
  `short_description` varchar(255) DEFAULT NULL,
  `description` text,
  `street_number` varchar(20) DEFAULT NULL,
  `address` varchar(100) NOT NULL,
  `city` varchar(60) DEFAULT NULL,
  `county` varchar(60) DEFAULT NULL,
  `countryId` int(11) DEFAULT NULL,
  `website` varchar(100) DEFAULT NULL,
  `keywords` varchar(100) DEFAULT NULL,
  `registrationCode` varchar(20) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `fax` varchar(20) DEFAULT NULL,
  `state` tinyint(4) DEFAULT '1',
  `typeId` int(11) NOT NULL,
  `logoLocation` varchar(245) DEFAULT NULL,
  `creationDate` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `modified` DATETIME  DEFAULT NULL ,
  `mainSubcategory` int(11) DEFAULT NULL,
  `latitude` varchar(45) DEFAULT NULL,
  `longitude` varchar(45) DEFAULT NULL,
  `activity_radius` float DEFAULT NULL,
  `userId` int(11) NOT NULL DEFAULT '0',
  `averageRating` float NOT NULL DEFAULT '0',
  `review_score` float DEFAULT NULL,
  `approved` tinyint(1) NOT NULL DEFAULT '0',
  `viewCount` int(11) NOT NULL DEFAULT '0',
  `websiteCount` int(11) NOT NULL DEFAULT '0',
  `contactCount` int(11) NOT NULL DEFAULT '0',
  `taxCode` varchar(45) DEFAULT NULL,
  `package_id` int(11) NOT NULL DEFAULT '0',
  `facebook` varchar(100) DEFAULT NULL,
  `twitter` varchar(100) DEFAULT NULL,
  `googlep` varchar(100) DEFAULT NULL,
  `skype` VARCHAR(100) DEFAULT NULL,
  `linkedin` VARCHAR(100)DEFAULT NULL,
  `youtube` VARCHAR(100) DEFAULT NULL,
  `postalCode` varchar(55) DEFAULT NULL,
  `mobile` varchar(55) DEFAULT NULL,
  `slogan` varchar(255) DEFAULT NULL,
  `publish_only_city` tinyint(1) DEFAULT '0',
  `featured` tinyint(1) NOT NULL DEFAULT '0',
  `business_hours` varchar(145) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_type` (`typeId`),
  KEY `idx_user` (`userId`),
  KEY `idx_state` (`state`),
  KEY `idx_approved` (`approved`),
  KEY `idx_country` (`countryId`),
  KEY `idx_package` (`package_id`),
  KEY `idx_name` (`name`),
  KEY `idx_keywords` (`keywords`),
  KEY `idx_description` (`description`(100)),
  KEY `idx_city` (`city`),
  KEY `idx_county` (`county`),
  KEY `idx_maincat` (`mainSubcategory`),
  KEY `idx_zipcode` (`latitude`,`longitude`),
  KEY `idx_phone` (`phone`),
  KEY `idx_alis` (`alias`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=29 ;

--
-- Dumping data for table `#__jbusinessdirectory_companies`
--

INSERT INTO `#__jbusinessdirectory_companies` (`id`, `name`, `alias`, `comercialName`, `short_description`, `description`, `street_number`, `address`, `city`, `county`, `countryId`, `website`, `keywords`, `registrationCode`, `phone`, `email`, `fax`, `state`, `typeId`, `logoLocation`, `creationDate`, `modified`, `mainSubcategory`, `latitude`, `longitude`, `activity_radius`, `userId`, `averageRating`, `approved`, `viewCount`, `websiteCount`, `contactCount`, `taxCode`, `package_id`, `facebook`, `twitter`, `googlep`, `postalCode`, `mobile`, `slogan`, `publish_only_city`, `featured`, `business_hours`) VALUES
(1, 'Wedding Venue', 'wedding-venue', 'Home & Gardem', '', '<p>Quisque cursus nunc ut diam pulvinar luctus. Nulla facilisi. Donec porta lorem id diam malesuada nec pretium enim euismod. Donec massa augue, lobortis eu cursus in, tincidunt ut nunc. Proin pellentesque, lorem porttitor commodo hendrerit, enim leo mattis risus, ac viverra ante tellus quis velit. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Morbi dignissim tristique sapien ut pretium. Duis sollicitudin dolor sed nisi venenatis quis fringilla diam suscipit. Sed convallis lectus non nibh suscipit ullamcorper. Fusce in magna ac lacus semper convallis. Morbi sagittis auctor massa vel consequat. Nulla fermentum, sapien a sagittis accumsan, tellus ipsum posuere tellus, a lacinia tortor lacus in nisl. Vestibulum posuere dictum ipsum ac viverra. Integer neque neque, blandit non adipiscing vel, auctor non odio. Maecenas quis nibh a diam eleifend rhoncus sed in turpis. Pellentesque mollis fermentum dolor et mollis. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Sed ullamcorper ante ac nunc commodo vitae rutrum sem placerat. Morbi et nisi metus.</p>', '123', 'Old San Francisco Rd', 'Sunnyvale', 'California', 226, 'http://www.garden.com', 'wedding, planning, venue', '342423422', '34242123123', 'email@decoration.com', '434312312321', 1, 6, '/companies/1/image1-1426883774.jpg', '0000-00-00 00:00:00', '2015-03-20 20:37:45', 8, '37.3681865', '-122.031385', 0, 0, 4, 2, 14, 0, 0, '123123', 0, '', '', '', '94086', '', '', 0, 0, NULL),
(4, 'Property inc', 'property-inc', 'Rent a car', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut mollis justo nulla, a tempus elit pulvinar eget. Nunc tempus leo in arcu mattis lobortis. Fusce ut sollicitudin nulla. Lorem ipsum dolor sit amet, consectetur adipiscing elit.', '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut mollis justo nulla, a tempus elit pulvinar eget. Nunc tempus leo in arcu mattis lobortis. Fusce ut sollicitudin nulla. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut laoreet feugiat lectus id ornare. Nulla ut odio eget justo faucibus consectetur. Ut faucibus ultrices accumsan. Aenean leo neque, accumsan ac eleifend vel, pulvinar id urna. Phasellus non malesuada augue. Maecenas id egestas quam, at molestie tortor. Sed quis dictum eros.Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut mollis justo nulla, a tempus elit pulvinar eget. Nunc tempus leo in arcu mattis lobortis. Fusce ut sollicitudin nulla. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut laoreet feugiat lectus id ornare. Nulla ut odio eget justo faucibus consectetur. Ut faucibus ultrices accumsan. Aenean leo neque, accumsan ac eleifend vel, pulvinar id urna. Phasellus non malesuada augue. Maecenas id egestas quam, at molestie tortor. Sed quis dictum eros.</p>', '11', 'Young Street', 'Toronto', 'Ontario', 36, 'http://google.com', '', '123123123', '0010727321321', 'office@email.com', '0010269/220123', 1, 6, '/companies/4/house-1426877663.jpg', '0000-00-00 00:00:00', '2015-03-20 20:24:55', 79, '43.64208175305137', '-79.3842687603319', 0, 0, 5, 2, 4, 0, 0, '123123', 0, '', '', '', '23123213', '', 'We find the property of your dreams', 0, 0, NULL),
(5, 'Organic food', 'organic-food', 'AQUACON PROJECT', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc interdum mauris vitae urna ultrices, et fermentum magna convallis. Nullam quis vulputate magna. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. ', '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc interdum mauris vitae urna ultrices, et fermentum magna convallis. Nullam quis vulputate magna. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Nunc scelerisque enim ut magna vulputate feugiat. Suspendisse rutrum lectus et diam congue, sed pretium eros facilisis. Pellentesque pretium lectus orci, non accumsan velit vestibulum a. Fusce orci dui, tincidunt et tortor non, auctor rutrum mauris. Vestibulum sed ultricies enim, at ultrices quam.</p>\r\n<p>Quisque pellentesque libero eget dui elementum scelerisque. Pellentesque tempor arcu in hendrerit molestie. Phasellus euismod nisi in malesuada convallis. Praesent sapien neque, fermentum a laoreet eget, tempus ultricies nulla. Mauris mi tortor, maximus eu risus vitae, bibendum vestibulum leo. Nulla vitae efficitur lectus. Aenean aliquet massa magna. Nullam at dapibus mi. Vivamus massa nibh, venenatis mattis nibh pretium, pretium volutpat leo. Vestibulum eu sem elit. Duis consequat, magna id semper elementum, est nisi pharetra orci, eget molestie diam purus sed sem. Vestibulum est purus, sollicitudin eget lectus ut, molestie aliquam purus. Praesent suscipit vitae sem vel sodales.</p>', '44', 'Young Street', 'Toronto', 'Ontario', 36, '', '', '2321111', '0727321321', 'office@site.com', '0269/220123', 1, 5, '/companies/5/image2-1426876816.jpg', '2011-11-24 05:30:40', '2015-03-24 11:15:10', 61, '43.650081332730466', '-79.37849521636963', 15, 0, 3, 2, 7, 0, 0, '123', 0, '', '', '', '1312312', '', 'The food that counts', 0, 0, '10am,5pm,10am,5pm,10am,5pm,10am,5pm,10am,5pm,10am,5pm,closed,'),
(7, 'Professional Photo ', 'professional-photo', 'FINE JEWELERY', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc interdum mauris vitae urna ultrices, et fermentum magna convallis. Nullam quis vulputate magna. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. ', '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc interdum mauris vitae urna ultrices, et fermentum magna convallis. Nullam quis vulputate magna. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Nunc scelerisque enim ut magna vulputate feugiat. Suspendisse rutrum lectus et diam congue, sed pretium eros facilisis. Pellentesque pretium lectus orci, non accumsan velit vestibulum a. Fusce orci dui, tincidunt et tortor non, auctor rutrum mauris. Vestibulum sed ultricies enim, at ultrices quam.</p>\r\n<p>Quisque pellentesque libero eget dui elementum scelerisque. Pellentesque tempor arcu in hendrerit molestie. Phasellus euismod nisi in malesuada convallis. Praesent sapien neque, fermentum a laoreet eget, tempus ultricies nulla. Mauris mi tortor, maximus eu risus vitae, bibendum vestibulum leo. Nulla vitae efficitur lectus. Aenean aliquet massa magna. Nullam at dapibus mi. Vivamus massa nibh, venenatis mattis nibh pretium, pretium volutpat leo. Vestibulum eu sem elit. Duis consequat, magna id semper elementum, est nisi pharetra orci, eget molestie diam purus sed sem. Vestibulum est purus, sollicitudin eget lectus ut, molestie aliquam purus. Praesent suscipit vitae sem vel sodales.</p>', '33', 'Richmong', 'Toronto', 'Ontario', 36, 'http://www.cmsjunkie.com', 'keywords1', '343243', '123123', 'office@shopping.com', '213123', 1, 6, '/companies/7/image4-1426874747.jpg', '2011-11-24 05:31:39', '2015-03-23 08:44:07', 24, '43.649677652720534', '-79.37798023223877', 30, 0, 4.25, 2, 11, 0, 0, '123123', 0, 'http://www.facebook.com/cmsjunkie', 'http://www.twiter.com', 'http://www.googleplus.com', '23213', '', 'We save the special moments for eternity. ', 0, 0, NULL),
(8, 'Electronics Store', 'electronics-store', 'Contruction Company', '', '<p>Quisque cursus nunc ut diam pulvinar luctus. Nulla facilisi. Donec porta lorem id diam malesuada nec pretium enim euismod. Donec massa augue, lobortis eu cursus in, tincidunt ut nunc. Proin pellentesque, lorem porttitor commodo hendrerit, enim leo mattis risus, ac viverra ante tellus quis velit. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Morbi dignissim tristique sapien ut pretium. Duis sollicitudin dolor sed nisi venenatis quis fringilla diam suscipit. Sed convallis lectus non nibh suscipit ullamcorper. Fusce in magna ac lacus semper convallis. Morbi sagittis auctor massa vel consequat. Nulla fermentum, sapien a sagittis accumsan, tellus ipsum posuere tellus, a lacinia tortor lacus in nisl. Vestibulum posuere dictum ipsum ac viverra. Integer neque neque, blandit non adipiscing vel, auctor non odio. Maecenas quis nibh a diam eleifend rhoncus sed in turpis. Pellentesque mollis fermentum dolor et mollis. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Sed ullamcorper ante ac nunc commodo vitae rutrum sem placerat. Morbi et nisi metus.</p>', '22', 'Lawrance', 'Toronto', 'Ontario', 36, 'http://google.com', '', '343412', '0727321321', 'office@site.com', '0269/220123', 1, 5, '/companies/8/image3-1426867001.jpg', '2011-11-24 05:32:07', '2015-03-24 11:13:04', 23, '43.65057816594119', '-79.37493324279785', 0, 0, 4.5, 2, 36, 0, 0, '12312', 0, '', '', '', '23123123', '', '', 0, 0, '8am,8pm,8am,8pm,8am,8pm,8am,8pm,8am,8pm,8am,5pm,closed,'),
(9, 'Organic Store', 'organic-store', 'IT Services', 'Quisque cursus nunc ut diam pulvinar luctus. Nulla facilisi. Donec porta lorem id diam malesuada nec pretium enim euismod. Donec massa augue, lobortis eu cursus in, tincidunt ut nunc.', '<p>Quisque cursus nunc ut diam pulvinar luctus. Nulla facilisi. Donec porta lorem id diam malesuada nec pretium enim euismod. Donec massa augue, lobortis eu cursus in, tincidunt ut nunc. Proin pellentesque, lorem porttitor commodo hendrerit, enim leo mattis risus, ac viverra ante tellus quis velit. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Morbi dignissim tristique sapien ut pretium. Duis sollicitudin dolor sed nisi venenatis quis fringilla diam suscipit. Sed convallis lectus non nibh suscipit ullamcorper. Fusce in magna ac lacus semper convallis. Morbi sagittis auctor massa vel consequat. Nulla fermentum, sapien a sagittis accumsan, tellus ipsum posuere tellus, a lacinia tortor lacus in nisl. Vestibulum posuere dictum ipsum ac viverra. Integer neque neque, blandit non adipiscing vel, auctor non odio. Maecenas quis nibh a diam eleifend rhoncus sed in turpis. Pellentesque mollis fermentum dolor et mollis. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Sed ullamcorper ante ac nunc commodo vitae rutrum sem placerat. Morbi et nisi metus.</p>', '32', 'Queen Street', 'Toronto', 'Ontario', 36, 'http://google.com', '', '3424234221212', '0727321321', 'office@company.com', '0010269/220123', 1, 4, '/companies/9/store-interior-design-ideas-boutique-1426867467.jpg', '2011-12-01 10:24:29', '2015-03-20 16:16:40', 29, '43.650391853968806', '-79.38038349151611', 20, 0, 1.5, 2, 13, 0, 0, '123123', 0, '', '', '', '123213123', '', 'The best organic products', 0, 0, NULL),
(12, 'Better Health', 'better-health', 'Almedia', 'Quisque cursus nunc ut diam pulvinar luctus. Nulla facilisi. Donec porta lorem id diam malesuada nec pretium enim euismod. Donec massa augue, lobortis eu cursus in, tincidunt ut nunc.', '<p>Quisque cursus nunc ut diam pulvinar luctus. Nulla facilisi. Donec porta lorem id diam malesuada nec pretium enim euismod. Donec massa augue, lobortis eu cursus in, tincidunt ut nunc. Proin pellentesque, lorem porttitor commodo hendrerit, enim leo mattis risus, ac viverra ante tellus quis velit. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Morbi dignissim tristique sapien ut pretium. Duis sollicitudin dolor sed nisi venenatis quis fringilla diam suscipit. Sed convallis lectus non nibh suscipit ullamcorper. Fusce in magna ac lacus semper convallis. Morbi sagittis auctor massa vel consequat. Nulla fermentum, sapien a sagittis accumsan, tellus ipsum posuere tellus, a lacinia tortor lacus in nisl. Vestibulum posuere dictum ipsum ac viverra. Integer neque neque, blandit non adipiscing vel, auctor non odio. Maecenas quis nibh a diam eleifend rhoncus sed in turpis. Pellentesque mollis fermentum dolor et mollis. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Sed ullamcorper ante ac nunc commodo vitae rutrum sem placerat. Morbi et nisi metus.</p>', '74444', 'Peg Keller Rd', 'Abita Springs', 'Louisiana', 226, 'http://www.cmsjunkie.com', '', 'RT545412SD', '001010727321321', 'directory@director.com', '', 1, 8, '/companies/12/health-store-1426861912.jpg', '2011-12-02 11:32:19', '2015-03-24 11:13:49', 29, '43.65538688599313', '-79.35828778892756', 20, 0, 4, 2, 97, 0, 0, '123123123', 0, 'http://https://www.facebook.com/cmsjunkie', 'http://https://twitter.com/cmsjunkie', 'http://https://plus.google.com/100376620356699373069/posts', '70420', '', 'The best healthcare products you can find', 0, 0, '9:30am,6pm,9:30am,6pm,9:30am,6pm,9:30am,6pm,9:30am,6pm,10am,2pm,closed,'),
(29, 'Photo Store', 'photo-store', NULL, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc interdum mauris vitae urna ultrices, et fermentum magna convallis. Nullam quis vulputate magna. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. ', '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc interdum mauris vitae urna ultrices, et fermentum magna convallis. Nullam quis vulputate magna. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Nunc scelerisque enim ut magna vulputate feugiat. Suspendisse rutrum lectus et diam congue, sed pretium eros facilisis. Pellentesque pretium lectus orci, non accumsan velit vestibulum a. Fusce orci dui, tincidunt et tortor non, auctor rutrum mauris. Vestibulum sed ultricies enim, at ultrices quam.</p>\r\n<p>Quisque pellentesque libero eget dui elementum scelerisque. Pellentesque tempor arcu in hendrerit molestie. Phasellus euismod nisi in malesuada convallis. Praesent sapien neque, fermentum a laoreet eget, tempus ultricies nulla. Mauris mi tortor, maximus eu risus vitae, bibendum vestibulum leo. Nulla vitae efficitur lectus. Aenean aliquet massa magna. Nullam at dapibus mi. Vivamus massa nibh, venenatis mattis nibh pretium, pretium volutpat leo. Vestibulum eu sem elit. Duis consequat, magna id semper elementum, est nisi pharetra orci, eget molestie diam purus sed sem. Vestibulum est purus, sollicitudin eget lectus ut, molestie aliquam purus. Praesent suscipit vitae sem vel sodales.</p>', '77877', 'Mosby Creek Rd', 'Cottage Grove', 'Oregon', 226, '', '', '', '+1 232 883 9932', 'office@site.com', '', 1, 2, '/companies/29/image1-1427207898.jpg', '2015-03-20 18:44:58', '2015-03-24 14:38:20', 23, '43.77616', '-123.00627400000002', 0, 0, 0, 2, 5, 0, 0, NULL, 0, 'http://www.facebook.com/cmsjunkie', 'http://www.twiter.com', 'http://www.googleplus.com', '97424', '+1 555 883 9932', 'We provide any camera you want.', 0, 0, NULL),
(30, 'Real Property', 'real-property', NULL, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc interdum mauris vitae urna ultrices, et fermentum magna convallis. Nullam quis vulputate magna. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. ', '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc interdum mauris vitae urna ultrices, et fermentum magna convallis. Nullam quis vulputate magna. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Nunc scelerisque enim ut magna vulputate feugiat. Suspendisse rutrum lectus et diam congue, sed pretium eros facilisis. Pellentesque pretium lectus orci, non accumsan velit vestibulum a. Fusce orci dui, tincidunt et tortor non, auctor rutrum mauris. Vestibulum sed ultricies enim, at ultrices quam.</p>\r\n<p>Quisque pellentesque libero eget dui elementum scelerisque. Pellentesque tempor arcu in hendrerit molestie. Phasellus euismod nisi in malesuada convallis. Praesent sapien neque, fermentum a laoreet eget, tempus ultricies nulla. Mauris mi tortor, maximus eu risus vitae, bibendum vestibulum leo. Nulla vitae efficitur lectus. Aenean aliquet massa magna. Nullam at dapibus mi. Vivamus massa nibh, venenatis mattis nibh pretium, pretium volutpat leo. Vestibulum eu sem elit. Duis consequat, magna id semper elementum, est nisi pharetra orci, eget molestie diam purus sed sem. Vestibulum est purus, sollicitudin eget lectus ut, molestie aliquam purus. Praesent suscipit vitae sem vel sodales.</p>', '1123', 'New York Ave', 'New York', 'New York', 226, '', '', '', '+1 232 883 9932', 'office@site.com', '', 1, 6, '/companies/30/image9-1427207944.jpg', '2015-03-20 19:12:27', '2015-03-24 14:39:07', 79, '40.645796', '-73.94583599999999', 0, 0, 0, 2, 2, 0, 0, NULL, 0, 'http://www.facebook.com/cmsjunkie', 'http://www.twiter.com', 'http://www.googleplus.com', '11203', '+1 555 883 9932', 'We can sell it for you', 0, 0, NULL),
(31, 'Restaurant One', 'restaurant-one', NULL, 'Quisque pellentesque libero eget dui elementum scelerisque. Pellentesque tempor arcu in hendrerit molestie. Phasellus euismod nisi in malesuada convallis. Praesent sapien neque, fermentum a laoreet eget, tempus ultricies nulla.', '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc interdum mauris vitae urna ultrices, et fermentum magna convallis. Nullam quis vulputate magna. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Nunc scelerisque enim ut magna vulputate feugiat. Suspendisse rutrum lectus et diam congue, sed pretium eros facilisis. Pellentesque pretium lectus orci, non accumsan velit vestibulum a. Fusce orci dui, tincidunt et tortor non, auctor rutrum mauris. Vestibulum sed ultricies enim, at ultrices quam.</p>\r\n<p>Quisque pellentesque libero eget dui elementum scelerisque. Pellentesque tempor arcu in hendrerit molestie. Phasellus euismod nisi in malesuada convallis. Praesent sapien neque, fermentum a laoreet eget, tempus ultricies nulla. Mauris mi tortor, maximus eu risus vitae, bibendum vestibulum leo. Nulla vitae efficitur lectus. Aenean aliquet massa magna. Nullam at dapibus mi. Vivamus massa nibh, venenatis mattis nibh pretium, pretium volutpat leo. Vestibulum eu sem elit. Duis consequat, magna id semper elementum, est nisi pharetra orci, eget molestie diam purus sed sem. Vestibulum est purus, sollicitudin eget lectus ut, molestie aliquam purus. Praesent suscipit vitae sem vel sodales.</p>', '12', 'New Hartford St', 'Wolcott', 'New York', 226, '', '', '', '+1 232 883 9932', 'office@site.com', '', 1, 1, '/companies/31/image5-1427207983.jpg', '2015-03-20 20:30:06', '2015-03-24 14:39:44', 77, '43.2157525', '-76.81465800000001', 0, 0, 0, 2, 4, 0, 0, NULL, 0, '', '', '', '14590', '+1 555 883 9932', 'The food taste like never before.', 0, 0, NULL),
(32, 'Fashion Inc.', 'fashion-inc', NULL, 'Donec eleifend purus nulla, non vehicula nisi dictum quis. Maecenas in odio purus. Etiam vulputate nisi eget pharetra tincidunt. Morbi et eros consectetur, ultricies ligula quis, ullamcorper neque. Donec pellentesque felis vel luctus tempus. Curabitu', '<p>Donec eleifend purus nulla, non vehicula nisi dictum quis. Maecenas in odio purus. Etiam vulputate nisi eget pharetra tincidunt. Morbi et eros consectetur, ultricies ligula quis, ullamcorper neque. Donec pellentesque felis vel luctus tempus. Curabitur blandit dui purus, non viverra magna consequat vitae. Nunc volutpat malesuada orci vitae varius.</p>\r\n<p>Suspendisse accumsan nunc non dictum bibendum. Sed suscipit id ipsum ut tincidunt. Vivamus condimentum diam at condimentum scelerisque. Etiam vulputate pellentesque maximus. Curabitur tincidunt nibh et nisl porttitor, eget ultrices turpis maximus. Fusce molestie elit eget felis cursus volutpat. Nam tincidunt lacus nec massa sagittis, eu dapibus purus bibendum. Ut hendrerit, felis nec congue posuere, lorem urna eleifend est, ac venenatis quam augue a arcu. Nullam sit amet finibus diam. Aenean placerat gravida mi at eleifend. Sed felis nulla, tempus ac vulputate vitae, condimentum vel nunc. Nam egestas, nunc sit amet tempor pellentesque, sapien justo aliquam tortor, at posuere elit purus eget orci. Aliquam hendrerit enim turpis, vitae ultrices libero accumsan nec. Pellentesque placerat volutpat fermentum. Sed tempor volutpat massa a auctor.</p>', '1', 'Rue Sherbrooke Est', 'Montréal', 'Québec', 36, '', '', '', '+1 555 888 9932', 'office@site.com', '', 1, 2, '/companies/32/image7-1427206356.jpg', '2015-03-23 08:36:50', '2015-03-24 14:12:42', 8, '45.5125554', '-73.56943790000003', 0, 0, 0, 2, 6, 0, 0, NULL, 0, 'http://www.facebook.com/cmsjunkie', 'http://www.twiter.com', 'http://www.googleplus.com', 'H2X 3V8', '+1 555 883 9932', 'Fashion is art!', 0, 0, '10am,5pm,10am,5pm,10am,5pm,10am,5pm,10am,5pm,10am,1pm,closed,'),
(33, 'Auto show', 'auto-show', NULL, 'Donec eleifend purus nulla, non vehicula nisi dictum quis. Maecenas in odio purus. Etiam vulputate nisi eget pharetra tincidunt. Morbi et eros consectetur, ultricies ligula quis, ullamcorper neque. Donec pellentesque felis vel luctus tempus.', '<p>Donec eleifend purus nulla, non vehicula nisi dictum quis. Maecenas in odio purus. Etiam vulputate nisi eget pharetra tincidunt. Morbi et eros consectetur, ultricies ligula quis, ullamcorper neque. Donec pellentesque felis vel luctus tempus. Curabitur blandit dui purus, non viverra magna consequat vitae. Nunc volutpat malesuada orci vitae varius.</p>\r\n<p>Suspendisse accumsan nunc non dictum bibendum. Sed suscipit id ipsum ut tincidunt. Vivamus condimentum diam at condimentum scelerisque. Etiam vulputate pellentesque maximus. Curabitur tincidunt nibh et nisl porttitor, eget ultrices turpis maximus. Fusce molestie elit eget felis cursus volutpat. Nam tincidunt lacus nec massa sagittis, eu dapibus purus bibendum. Ut hendrerit, felis nec congue posuere, lorem urna eleifend est, ac venenatis quam augue a arcu. Nullam sit amet finibus diam. Aenean placerat gravida mi at eleifend. Sed felis nulla, tempus ac vulputate vitae, condimentum vel nunc. Nam egestas, nunc sit amet tempor pellentesque, sapien justo aliquam tortor, at posuere elit purus eget orci. Aliquam hendrerit enim turpis, vitae ultrices libero accumsan nec. Pellentesque placerat volutpat fermentum. Sed tempor volutpat massa a auctor.</p>', '12', 'Hopkins Ave', 'Jersey City', 'New Jersey', 226, '', '', '', '+1 444 777 9999', 'office@site.com', '', 1, 6, '/companies/33/image4-1427207844.jpg', '2015-03-23 08:41:43', '2015-03-24 14:37:28', 74, '40.7343489', '-74.05115409999996', 0, 0, 0, 2, 6, 0, 0, NULL, 0, '', '', '', '07306', '+1 555 883 9932', 'We present the latest trends and technology. ', 0, 0, '9am,5pm,9am,5pm,9am,5pm,9am,5pm,9am,5pm,9am,2pm,closed,');

-- --------------------------------------------------------

--
-- Table structure for table `#__jbusinessdirectory_company_activity_city`
--

CREATE TABLE IF NOT EXISTS `#__jbusinessdirectory_company_activity_city` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `company_id` int(11) DEFAULT NULL,
  `city_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `IND_UNQ` (`company_id`,`city_id`),
  KEY `idx_company` (`company_id`),
  KEY `idx_city` (`city_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=28 ;

--
-- Dumping data for table `#__jbusinessdirectory_company_activity_city`
--

INSERT INTO `#__jbusinessdirectory_company_activity_city` (`id`, `company_id`, `city_id`) VALUES
(24, 1, -1),
(27, 4, -1),
(23, 8, -1),
(26, 9, -1),
(25, 12, -1);

-- --------------------------------------------------------
--
-- Table structure for table `#__jbusinessdirectory_company_attachments`
--

CREATE TABLE IF NOT EXISTS `#__jbusinessdirectory_company_attachments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` tinyint(4) DEFAULT NULL,
  `object_id` int(11) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `path` varchar(155) DEFAULT NULL,
  `status` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_type` (`type`),
  KEY `idx_object` (`object_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=19 ;

--
-- Dumping data for table `#__jbusinessdirectory_company_attachments`
--

INSERT INTO `#__jbusinessdirectory_company_attachments` (`id`, `type`, `object_id`, `name`, `path`, `status`) VALUES
(22, 2, 13, 'Treatment instructions', '/offers/13/SPA_Woman_Face_1280_770_d-1426864490.jpg', 1),
(23, 1, 12, 'Healthcare catalog', '/companies/12/natural-health-1426863987.jpg', 1);

-- --------------------------------------------------------
--
-- Table structure for table `#__jbusinessdirectory_company_attributes`
--

CREATE TABLE IF NOT EXISTS `#__jbusinessdirectory_company_attributes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `company_id` int(11) NOT NULL,
  `attribute_id` int(11) NOT NULL,
  `value` varchar(250) DEFAULT NULL,
  `option_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `company_UNIQUE` (`company_id`,`attribute_id`,`value`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=105 ;

-- --------------------------------------------------------

--
-- Table structure for table `#__jbusinessdirectory_company_category`
--

CREATE TABLE IF NOT EXISTS `#__jbusinessdirectory_company_category` (
  `companyId` int(11) NOT NULL,
  `categoryId` int(11) NOT NULL,
  PRIMARY KEY (`companyId`,`categoryId`),
  KEY `idx_category` (`companyId`,`categoryId`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `#__jbusinessdirectory_company_category`
--
INSERT INTO `#__jbusinessdirectory_company_category` (`companyId`, `categoryId`) VALUES
(1, 8),
(1, 35),
(4, 79),
(5, 60),
(5, 61),
(5, 64),
(5, 68),
(6, 5),
(6, 17),
(7, 13),
(7, 24),
(8, 23),
(8, 26),
(8, 28),
(8, 31),
(8, 33),
(9, 29),
(9, 39),
(9, 41),
(9, 42),
(12, 29),
(12, 41),
(12, 42),
(20, 290),
(20, 292),
(29, 23),
(29, 24),
(30, 79),
(31, 77),
(31, 78),
(32, 8),
(32, 35),
(32, 38),
(33, 14),
(33, 16),
(33, 18),
(33, 74);


-- --------------------------------------------------------

--
-- Table structure for table `#__jbusinessdirectory_company_claim`
--

CREATE TABLE IF NOT EXISTS `#__jbusinessdirectory_company_claim` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `companyId` int(11) DEFAULT NULL,
  `firstName` varchar(55) DEFAULT NULL,
  `lastName` varchar(55) DEFAULT NULL,
  `function` varchar(45) DEFAULT NULL,
  `phone` varchar(45) DEFAULT NULL,
  `email` varchar(65) DEFAULT NULL,
  `status` int(3) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=78 ;

-- --------------------------------------------------------

--
-- Table structure for table `#__jbusinessdirectory_company_contact`
--

CREATE TABLE IF NOT EXISTS `#__jbusinessdirectory_company_contact` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `companyId` int(11) NOT NULL,
  `contact_name` varchar(50) DEFAULT NULL,
  `contact_function` varchar(50) DEFAULT NULL,
  `contact_department` varchar(100) DEFAULT NULL,
  `contact_email` varchar(60) DEFAULT NULL,
  `contact_phone` varchar(20) DEFAULT NULL,
  `contact_fax` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`,`companyId`),
  KEY `R_13` (`companyId`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `#__jbusinessdirectory_company_contact`
--

INSERT INTO `#__jbusinessdirectory_company_contact` (`id`, `companyId`, `contact_name`, `contact_function`, `contact_department`, `contact_email`, `contact_phone`, `contact_fax`) VALUES
(1, 8, '', NULL, NULL, '', '', ''),
(2, 1, '', NULL, NULL, '', '', ''),
(3, 12, 'Joanne Smith', NULL, NULL, 'joaan@joann.com', '+1 323 999 672', '+1 323 231 754'),
(4, 9, 'John rice', NULL, NULL, 'john@organic.co', '+1 221 359 888', ''),
(5, 4, '', NULL, NULL, '', '', ''),
(6, 7, 'John Doe', NULL, NULL, 'john@john.com', '01 232 495 999', ''),
(7, 5, '', NULL, NULL, '', '', ''),
(8, 29, 'John Smith', NULL, NULL, 'john@smith.com', '+1 221 359 888', ''),
(9, 30, '', NULL, NULL, '', '', ''),
(10, 31, 'Chef Michael', NULL, NULL, 'joaan@joann.com', '', ''),
(11, 32, '', NULL, NULL, '', '', ''),
(12, 33, 'Brian Lindow', NULL, NULL, 'office@site.com', '+1 323 999 672', '');

-- --------------------------------------------------------

--
-- Table structure for table `#__jbusinessdirectory_company_events`
--

CREATE TABLE IF NOT EXISTS `#__jbusinessdirectory_company_events` (
   `id` int(11) NOT NULL AUTO_INCREMENT,
  `company_id` int(11) DEFAULT NULL,
  `name` varchar(245) DEFAULT NULL,
  `alias` varchar(100) NOT NULL DEFAULT '',
  `short_description` varchar(245) DEFAULT NULL,
  `description` text,
  `type` int(11) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `start_time` time DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `end_time` time DEFAULT NULL,
  `address` varchar(50) DEFAULT NULL,
  `city` varchar(45) DEFAULT NULL,
  `county` varchar(45) DEFAULT NULL,
  `location` varchar(45) DEFAULT NULL,
  `latitude` varchar(45) DEFAULT NULL,
  `longitude` varchar(45) DEFAULT NULL,
  `featured` tinyint(1) NOT NULL DEFAULT '0',
  `created` timestamp NULL DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `view_count` int(11) NOT NULL,
  `approved` tinyint(1) NOT NULL,
  `state` tinyint(1) DEFAULT NULL,
  `recurring_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_company` (`company_id`),
  KEY `idx_search` (`start_date`,`end_time`,`end_date`,`state`,`approved`),
  KEY `idx_alias` (`alias`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=12 ;

--
-- Dumping data for table `#__jbusinessdirectory_company_events`
--

INSERT INTO `#__jbusinessdirectory_company_events` (`id`, `company_id`, `name`, `alias`, `short_description`, `description`, `type`, `start_date`, `start_time`, `end_date`, `end_time`, `address`, `city`, `county`, `location`, `latitude`, `longitude`, `featured`, `created`, `price`, `view_count`, `approved`, `state`, `recurring_id`) VALUES
(9, 8, 'Celebration Party', 'celebration-party', NULL, ' Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla accumsan enim dignissim consectetur viverra. Vestibulum a erat vitae quam pellentesque varius vel at ipsum. Pellentesque ultricies porttitor bibendum. Donec et justo quis tortor egestas rhoncus hendrerit ac massa. Sed tempus iaculis mi at sollicitudin. Etiam a ligula eget magna condimentum consectetur non pulvinar purus. Phasellus quis lobortis mauris. Nullam eleifend iaculis sem, nec hendrerit quam molestie vitae.\r\n\r\nCras condimentum, augue at pretium pellentesque, ipsum arcu ullamcorper erat, eu laoreet libero erat id erat. Vestibulum ut dolor commodo, condimentum purus in, egestas purus. In hac habitasse platea dictumst. Nam rutrum sapien quam, in viverra libero interdum a. Vivamus sollicitudin dolor eget tincidunt faucibus. Cras pretium justo neque, quis imperdiet nulla vehicula a. Nulla facilisi. Aliquam justo ligula, fringilla vel orci in, imperdiet aliquam elit. Vivamus ac lorem blandit, tempor felis eget, placerat lectus. ', 4, '2014-11-21', '15:00:00', '2016-11-22', '19:00:00', NULL, NULL, NULL, 'Toronto, Canada', NULL, NULL, 0, NULL, NULL, 1, 1, 1, NULL),
(10, 12, 'Natural Health', 'natural-health', NULL, 'Nulla consectetur magna et cursus sagittis. Quisque ac consectetur elit. Ut volutpat tellus non orci fermentum, sit amet tincidunt quam scelerisque. Integer eleifend congue eros pellentesque pharetra. Integer sed diam lectus. Donec ultricies, arcu a vulputate fringilla, nisi quam vestibulum libero, faucibus bibendum nunc justo sed ante. Etiam luctus quis nisl nec ornare. Fusce urna leo, tincidunt at commodo non, vestibulum et erat. In faucibus posuere purus, at egestas dolor dictum ac. Maecenas volutpat lectus eget purus hendrerit, sit amet hendrerit diam mattis. Nulla imperdiet metus ac metus molestie, sed imperdiet leo eleifend. Fusce non tellus porta risus convallis vehicula. Donec quis convallis ligula. ', 5, '2014-11-21', '17:00:00', '2016-11-21', '20:00:00', NULL, NULL, NULL, 'Gamble Avenue, Toronto, Canada', NULL, NULL, 0, NULL, NULL, 5, 1, 1, NULL),
(11, 1, 'Improve your communication skills', 'improve-your-communication-skills', NULL, 'Nulla sagittis pretium sagittis. Aliquam tincidunt sodales dui, a facilisis nisi sollicitudin quis. Sed nec mattis augue. Sed hendrerit odio non mauris fermentum semper. Praesent vehicula nec libero a imperdiet. Proin posuere nibh libero, ac euismod nulla tincidunt in. Mauris est nunc, fringilla ac facilisis a, ornare a leo. Nam lobortis tortor fringilla, lobortis nisl sit amet, cursus dolor. In at lectus massa. Integer ut nulla dapibus, volutpat nisi vitae, laoreet tellus. Quisque hendrerit blandit leo at dapibus. ', 3, '2014-11-21', '15:00:00', '2016-11-21', '12:00:00', NULL, NULL, NULL, 'Gamble Avenue, Toronto, Canada', NULL, NULL, 0, NULL, NULL, 2, 1, 1, NULL),
(12, 7, 'Photography Course', 'photography-course', NULL, 'Suspendisse accumsan nunc non dictum bibendum. Sed suscipit id ipsum ut tincidunt. Vivamus condimentum diam at condimentum scelerisque. Etiam vulputate pellentesque maximus. Curabitur tincidunt nibh et nisl porttitor, eget ultrices turpis maximus. Fusce molestie elit eget felis cursus volutpat. Nam tincidunt lacus nec massa sagittis, eu dapibus purus bibendum. Ut hendrerit, felis nec congue posuere, lorem urna eleifend est, ac venenatis quam augue a arcu. Nullam sit amet finibus diam. Aenean placerat gravida mi at eleifend. Sed felis nulla, tempus ac vulputate vitae, condimentum vel nunc. Nam egestas, nunc sit amet tempor pellentesque, sapien justo aliquam tortor, at posuere elit purus eget orci. Aliquam hendrerit enim turpis, vitae ultrices libero accumsan nec. Pellentesque placerat volutpat fermentum. Sed tempor volutpat massa a auctor. ', 3, '2015-03-25', '10:00:00', '2016-03-23', '19:30:00', NULL, NULL, NULL, 'London, UK', NULL, NULL, 0, NULL, NULL, 2, 1, 1, NULL),
(13, 32, 'Fashion Presentation', 'fashion-presentation', NULL, 'Suspendisse accumsan nunc non dictum bibendum. Sed suscipit id ipsum ut tincidunt. Vivamus condimentum diam at condimentum scelerisque. Etiam vulputate pellentesque maximus. Curabitur tincidunt nibh et nisl porttitor, eget ultrices turpis maximus. Fusce molestie elit eget felis cursus volutpat. Nam tincidunt lacus nec massa sagittis, eu dapibus purus bibendum. Ut hendrerit, felis nec congue posuere, lorem urna eleifend est, ac venenatis quam augue a arcu. Nullam sit amet finibus diam. Aenean placerat gravida mi at eleifend. Sed felis nulla, tempus ac vulputate vitae, condimentum vel nunc. Nam egestas, nunc sit amet tempor pellentesque, sapien justo aliquam tortor, at posuere elit purus eget orci. Aliquam hendrerit enim turpis, vitae ultrices libero accumsan nec. Pellentesque placerat volutpat fermentum. Sed tempor volutpat massa a auctor. ', 5, '2015-05-20', '17:00:00', '2016-03-23', '19:30:00', NULL, NULL, NULL, 'Paris, France', NULL, NULL, 0, NULL, NULL, 0, 1, 1, NULL),
(14, 31, 'Wine testing', 'wine-testing', NULL, 'Aliquam dignissim sagittis urna eu ultrices. Curabitur hendrerit mi leo, sed tincidunt libero venenatis eu. Curabitur non feugiat diam. Proin pharetra, leo ut pellentesque dignissim, orci libero tempus odio, congue volutpat arcu eros consectetur tortor. Cras eget volutpat felis. Ut lobortis lectus eget ligula condimentum hendrerit. Curabitur et justo nunc. Duis malesuada, est vel pellentesque viverra, nulla lorem ornare dui, eget elementum sapien elit et sapien. Nulla placerat laoreet arcu, eu ullamcorper massa viverra quis. Cras quis faucibus leo, ut iaculis sem. Cras et egestas odio, quis mollis mi. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus', 5, '2015-08-28', '02:00:00', '2016-03-23', '02:00:00', NULL, NULL, NULL, 'Burgundy, France', NULL, NULL, 0, NULL, NULL, 2, 1, 1, NULL),
(15, 30, 'Design Trends', 'design-trends', NULL, 'Mauris quis finibus tellus, eget dignissim tellus. Cras eget lorem libero. Nulla facilisi. Aliquam ac volutpat erat. Nunc id metus nunc. Phasellus finibus ante et finibus viverra. Mauris scelerisque dignissim mauris, sit amet congue nisi sagittis vel. Etiam lacinia sapien in nulla ultricies eleifend. Aliquam feugiat vitae magna id aliquet. Nam sem ligula, sollicitudin in placerat quis, scelerisque elementum tortor. Aenean imperdiet dictum lorem. Praesent sit amet arcu id mi hendrerit scelerisque. Integer tincidunt massa eget lectus laoreet porttitor. Fusce quis luctus orci. ', 5, '2016-03-23', '11:00:00', '2016-03-23', '18:30:00', NULL, NULL, NULL, 'New York, US', NULL, NULL, 0, NULL, NULL, 1, 1, 1, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `#__jbusinessdirectory_company_event_pictures`
--

CREATE TABLE IF NOT EXISTS `#__jbusinessdirectory_company_event_pictures` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `eventId` int(10) NOT NULL DEFAULT '0',
  `picture_info` varchar(255) NOT NULL,
  `picture_path` varchar(255) NOT NULL,
  `picture_enable` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=17 ;

--
-- Dumping data for table `#__jbusinessdirectory_company_event_pictures`
--
INSERT INTO `#__jbusinessdirectory_company_event_pictures` (`id`, `eventId`, `picture_info`, `picture_path`, `picture_enable`) VALUES
(33, 12, '', 0x2f6576656e74732f31322f696d616765322d313432373130313436372e6a7067, 1),
(32, 12, '', 0x2f6576656e74732f31322f696d616765372d313432373130313436332e6a7067, 1),
(24, 11, '', 0x2f6576656e74732f31312f636f6d6d756e69636174696f6e5f736b696c6c2d313338353033383832352e6a7067, 1),
(29, 9, '', 0x2f6576656e74732f392f70617274795f74696d655f77616c6c70617065725f65613364302d313338353033383239322e6a7067, 1),
(28, 9, '', 0x2f6576656e74732f392f70696e6b20706172747920776964652077616c6c70617065722d313338353033383239302e6a7067, 1),
(31, 12, '', 0x2f6576656e74732f31322f696d616765352d313432373130313435392e6a7067, 1),
(50, 15, '', 0x2f6576656e74732f31352f696d616765312d313432373130333337362e6a706567, 1),
(41, 14, '', 0x2f6576656e74732f31342f696d616765312d313432373130333236382e6a7067, 1),
(40, 14, '', 0x2f6576656e74732f31342f696d616765332d313432373130333236332e6a7067, 1),
(30, 9, '', 0x2f6576656e74732f392f696d616765732d313338353033383239342e6a7067, 1),
(34, 13, '', 0x2f6576656e74732f31332f696d616765362d313432373130313631322e6a7067, 1),
(35, 13, '', 0x2f6576656e74732f31332f696d616765372d313432373130313635302e6a7067, 1),
(36, 13, '', 0x2f6576656e74732f31332f736c6964652d696d6167652d382d313432373130313635332e6a7067, 1),
(37, 10, '', 0x2f6576656e74732f31302f6e61747572616c2d6865616c74682d313432373130313733372e6a7067, 1),
(38, 10, '', 0x2f6576656e74732f31302f736c696465312d7468652d6865616c74682d616e642d6265617574792d776f726c642d313432373130313737352e6a7067, 1),
(39, 10, '', 0x2f6576656e74732f31302f70722d666f722d6e61747572616c2d6865616c74682d313432373130313738352e6a7067, 1),
(49, 15, '', 0x2f6576656e74732f31352f696d616765332d313432373130333337312e6a7067, 1),
(48, 15, '', 0x2f6576656e74732f31352f77312d313432373130333336382e6a7067, 1);



-- --------------------------------------------------------

--
-- Table structure for table `#__jbusinessdirectory_company_event_types`
--

CREATE TABLE IF NOT EXISTS `#__jbusinessdirectory_company_event_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `ordering` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `#__jbusinessdirectory_company_event_types`
--

INSERT INTO `#__jbusinessdirectory_company_event_types` (`id`, `name`, `ordering`) VALUES
(1, 'Seminar', NULL),
(2, 'Training', NULL),
(3, 'Workshop', NULL),
(4, 'Party', NULL),
(5, 'Presentation', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `#__jbusinessdirectory_company_images`
--

CREATE TABLE IF NOT EXISTS `#__jbusinessdirectory_company_images` (
  `companyId` char(18) NOT NULL,
  `id` char(18) NOT NULL,
  `imagePath` char(18) DEFAULT NULL,
  `typeId` char(18) NOT NULL,
  PRIMARY KEY (`companyId`,`id`,`typeId`),
  KEY `R_9` (`companyId`,`typeId`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `#__jbusinessdirectory_company_locations`
--

CREATE TABLE IF NOT EXISTS `#__jbusinessdirectory_company_locations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(80) DEFAULT NULL,
  `company_id` int(11) DEFAULT NULL,
  `street_number` varchar(20) DEFAULT NULL,
  `address` varchar(100) NOT NULL,
  `city` varchar(60) DEFAULT NULL,
  `county` varchar(60) DEFAULT NULL,
  `postalCode` varchar(45) DEFAULT NULL,
  `countryId` int(11) DEFAULT NULL,
  `latitude` varchar(45) DEFAULT NULL,
  `longitude` varchar(45) DEFAULT NULL,
  `phone` VARCHAR(45) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=15 ;

-- --------------------------------------------------------

--
-- Table structure for table `#__jbusinessdirectory_company_offers`
--

CREATE TABLE IF NOT EXISTS `#__jbusinessdirectory_company_offers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `companyId` int(11) NOT NULL,
  `subject` varchar(45) NOT NULL,
  `description` text,
  `price` float DEFAULT NULL,
  `specialPrice` float DEFAULT NULL,
  `startDate` date DEFAULT NULL,
  `endDate` date DEFAULT NULL,
  `state` tinyint(1) NOT NULL DEFAULT '1',
  `approved` tinyint(1) NOT NULL DEFAULT '0',
  `offerOfTheDay` tinyint(1) NOT NULL DEFAULT '0',
  `viewCount` int(10) DEFAULT '0',
  `alias` varchar(100) NOT NULL DEFAULT '',
  `address` varchar(45) DEFAULT NULL,
  `city` varchar(45) DEFAULT NULL,
  `short_description` varchar(255) DEFAULT NULL,
  `county` varchar(45) DEFAULT NULL,
  `publish_start_date` date DEFAULT NULL,
  `publish_end_date` date DEFAULT NULL,
  `view_type` tinyint(2) NOT NULL DEFAULT '1',
  `url` varchar(145) DEFAULT NULL,
  `article_id` int(11) DEFAULT NULL,
  `latitude` varchar(45) DEFAULT NULL,
  `longitude` varchar(45) DEFAULT NULL,
  `featured` tinyint(1) NOT NULL DEFAULT '0',
  `created` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_alias` (`alias`),
  KEY `idx_company` (`companyId`),
  KEY `idx_search` (`state`,`endDate`,`startDate`,`publish_end_date`,`publish_start_date`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=17 ;

--
-- Dumping data for table `#__jbusinessdirectory_company_offers`
--

INSERT INTO `#__jbusinessdirectory_company_offers` (`id`, `companyId`, `subject`, `description`, `price`, `specialPrice`, `startDate`, `endDate`, `state`, `approved`, `offerOfTheDay`, `viewCount`, `alias`, `address`, `city`, `short_description`, `county`, `publish_start_date`, `publish_end_date`, `view_type`, `url`, `article_id`, `latitude`, `longitude`, `featured`, `created`) VALUES
(3, 12, 'Facial treatment', 'Etiam eget urna est. Nullam turpis magna, pharetra id venenatis id, adipiscing at velit. In lobortis ornare congue. Sed vitae neque lacus, et rutrum lorem. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Pellentesque quis rhoncus felis. Sed adipiscing tellus laoreet neque adipiscing ac euismod felis gravida. Aenean fermentum, nulla non adipiscing tristique, lacus justo ornare nunc, eu aliquam nunc massa non justo. Sed at sapien vitae eros luctus condimentum non at libero. Morbi id arcu nec mi suscipit molestie. Integer ullamcorper suscipit erat, quis convallis quam interdum convallis. Sed lectus justo, vehicula et euismod rhoncus, tempus vel magna. Pellentesque laoreet, odio id iaculis bibendum, erat quam mollis urna, ac pretium neque mi vitae nisl. Fusce euismod bibendum risus vel suscipit. Suspendisse sapien tortor, vehicula sed lobortis tempus, pellentesque ut lectus.', 120, 90, '2015-02-01', '2015-12-10', 1, 1, 1, 14, 'facial-treatment', '7777 Forest Blvd', 'Dallas', 'Etiam eget urna est. Nullam turpis magna, pharetra id venenatis id, adipiscing at velit. In lobortis ornare congue. Sed vitae neque lacus, et rutrum lorem. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae;', 'Texas', '0000-00-00', '0000-00-00', 1, '', 0, '32.9113547', '-96.77428509999999', 0, '2015-03-20 09:04:23'),
(13, 12, 'New Healthcare Products', 'Quisque cursus nunc ut diam pulvinar luctus. Nulla facilisi. Donec porta lorem id diam malesuada nec pretium enim euismod. Donec massa augue, lobortis eu cursus in, tincidunt ut nunc. Proin pellentesque, lorem porttitor commodo hendrerit, enim leo mattis risus, ac viverra ante tellus quis velit. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Morbi dignissim tristique sapien ut pretium. Duis sollicitudin dolor sed nisi venenatis quis fringilla diam suscipit. Sed convallis lectus non nibh suscipit ullamcorper. Fusce in magna ac lacus semper convallis. Morbi sagittis auctor massa vel consequat. Nulla fermentum, sapien a sagittis accumsan, tellus ipsum posuere tellus, a lacinia tortor lacus in nisl. Vestibulum posuere dictum ipsum ac viverra. Integer neque neque, blandit non adipiscing vel, auctor non odio. Maecenas quis nibh a diam eleifend rhoncus sed in turpis. Pellentesque mollis fermentum dolor et mollis. Cum sociis natoque penatibus et mag', 69.99, 49.99, '2015-01-03', '2015-01-04', 1, 1, 1, 26, 'new-healthcare-products', 'Country Hills Blvd NW', 'Beaumont', 'Quisque cursus nunc ut diam pulvinar luctus. Nulla facilisi. Donec porta lorem id diam malesuada nec pretium enim euismod. Donec massa augue, lobortis eu cursus in, tincidunt ut nunc', 'Texas', '0000-00-00', '0000-00-00', 1, '', 0, '43.70957890823799', '-79.50366217643023', 0, '2015-03-20 09:04:23'),
(14, 8, 'Components sale', ' Duis faucibus odio quis sapien imperdiet, nec congue turpis pellentesque. Integer mi turpis, eleifend et mollis eu, dapibus quis elit. Pellentesque at turpis urna. Sed scelerisque Diam scelerisque fermentum finibus. Mauris elementum euismod erat sed condimentum. Nulla imperdiet mattis massa, at fermentum erat tristique ac. Praesent eget velit maximus, blandit nisi at, porta ligula. Etiam quis libero nisl. Vestibulum quis ornare dui. Suspendisse quis lobortis nunc. Pellentesque quis pharetra metus. Phasellus vulputate orci in pharetra feugiat. Etiam vehicula lacus augue, et lacinia turpis mollis id.\r\n\r\nPhasellus sed feugiat nunc, sed pharetra risus. Etiam eleifend quis lectus et gravida. Nunc pretium nisi id mi maximus mollis. Aliquam tempus dictum mi. Donec cursus pharetra neque, at gravida dolor vestibulum sit amet. Donec quam urna, molestie pharetra venenatis in, tincidunt quis elit. Praesent pharetra eget metus vitae vestibulum. Mauris gravida turpis lorem, aliquam semper justo auc.', 88, 55, '2015-05-25', '2015-09-25', 1, 1, 0, 5, 'components-sale', 'Chopin Ave', 'Toronto', 'Diam scelerisque fermentum finibus. Mauris elementum euismod erat sed condimentum. Nulla imperdiet mattis massa, at fermentum erat tristique ac. Praesent eget velit maximus, blandit nisi at, porta ligula.', 'Ontario', '0000-00-00', '0000-00-00', 1, '', 0, '43.737594787503966', '-79.27854752537314', 0, '2015-03-20 09:04:23'),
(15, 1, 'Book now and get 20 % off', 'Morbi porta luctus enim at scelerisque. Cras imperdiet nibh eget commodo blandit. Aliquam nec commodo lectus. Donec pellentesque, massa quis porta aliquet, massa metus accumsan metus, nec dignissim tortor mi eu erat. Phasellus pulvinar metus a tortor eleifend, a hendrerit tortor rutrum. Aliquam in tellus gravida, varius sem quis, interdum elit. Pellentesque nec egestas augue. Donec ullamcorper ante eu libero hendrerit, vel tempus dolor dapibus. Quisque finibus nisi eu sem venenatis porta. Praesent tempor nisi urna.\r\n\r\nInteger convallis dolor id ullamcorper consectetur. Morbi sodales mi et orci sollicitudin, sit amet pretium ante vulputate. Nullam ultrices vehicula urna in condimentum. Nulla lacus tortor, lobortis pulvinar turpis vitae, hendrerit gravida enim. Vestibulum eros magna, elementum ut pulvinar eget, placerat et augue. Vestibulum eget sapien vitae dui facilisis maximus a vel ligula. Nunc urna tortor, lobortis eu interdum vitae, mattis sit amet libero. Phasellus quis dapibus arcu, vulputate hendrerit est. Ut mattis bibendum gravida. Ut molestie ornare sapien nec dictum. ', 0, 0, '2014-09-26', '2015-12-31', 1, 1, 0, 2, 'book-now-and-get-20-off', 'Chopin Ave', 'Toronto', 'Morbi porta luctus enim at scelerisque. Cras imperdiet nibh eget commodo blandit. Aliquam nec commodo lectus. Donec pellentesque, massa quis porta aliquet, massa metus accumsan metus, nec dignissim tortor mi eu erat. Phasellus pulvinar metus a torto', 'Ontario', '0000-00-00', '0000-00-00', 1, '', 0, '43.737032009283475', '-79.27838659283225', 0, '2015-03-20 09:04:23'),
(16, 1, 'Special Offer', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed egestas maximus arcu a posuere. Phasellus eget tellus ac purus vulputate auctor. Donec nec semper elit, quis iaculis purus. Praesent vitae facilisis enim. Vestibulum laoreet tristique velit quis porttitor. Nam venenatis vestibulum est ut aliquam. Vivamus placerat sollicitudin est ut aliquet. Fusce imperdiet auctor felis, ut egestas sem condimentum sed. Pellentesque porta sit amet justo non imperdiet. Mauris leo lorem, ultricies eu consectetur eu, laoreet nec tortor.\r\n\r\nIn hac habitasse platea dictumst. Donec facilisis nulla vitae est vulputate feugiat. Nam consequat orci elit, quis condimentum massa aliquet id. Sed tortor est, dictum ac viverra a, aliquam vel lectus. Ut non ipsum sodales, cursus neque id, sollicitudin nunc. Duis vitae placerat lacus. Vestibulum sit amet neque congue, euismod diam id, sagittis felis. Aenean fringilla tempor velit sit amet pretium. Praesent sollicitudin libero in quam semper, ac vestibulum libero tempus. Integer euismod ipsum et varius mollis. ', 0, 0, '2014-09-26', '2016-09-26', 1, 1, 0, 2, 'special-offer', '130 Yorkland Boulevard', 'Beaumont', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed egestas maximus arcu a posuere. Phasellus eget tellus ac purus vulputate auctor. Donec nec semper elit, quis iaculis purus.', 'Texas', '0000-00-00', '0000-00-00', 1, '', 0, '29.874468564024614', '-94.1099853347987', 0, '2015-03-20 09:04:23'),
(17, 8, 'Spring offer - 25% off', ' Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc interdum mauris vitae urna ultrices, et fermentum magna convallis. Nullam quis vulputate magna. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Nunc scelerisque enim ut magna vulputate feugiat. Suspendisse rutrum lectus et diam congue, sed pretium eros facilisis. Pellentesque pretium lectus orci, non accumsan velit vestibulum a. Fusce orci dui, tincidunt et tortor non, auctor rutrum mauris. Vestibulum sed ultricies enim, at ultrices quam.\r\n\r\nQuisque pellentesque libero eget dui elementum scelerisque. Pellentesque tempor arcu in hendrerit molestie. Phasellus euismod nisi in malesuada convallis. Praesent sapien neque, fermentum a laoreet eget, tempus ultricies nulla. Mauris mi tortor, maximus eu risus vitae, bibendum vestibulum leo. Nulla vitae efficitur lectus. Aenean aliquet massa magna. Nullam at dapibus mi. Vivamus massa nibh, venenatis mattis nibh pretium, pretium volutpat leo. Vestibulum eu sem elit. Duis consequat, magna id semper elementum, est nisi pharetra orci, eget molestie diam purus sed sem. Vestibulum est purus, sollicitudin eget lectus ut, molestie aliquam purus. P', 0, 0, '2015-03-20', '2015-10-20', 1, 1, 0, 0, 'spring-offer-25-off', '', '', ' Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc interdum mauris vitae urna ultrices, et fermentum magna convallis. Nullam quis vulputate magna. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos.', '', '0000-00-00', '0000-00-00', 1, '', 0, '', '', 0, '2015-03-20 12:12:33'),
(18, 8, 'Super Deal', ' Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc interdum mauris vitae urna ultrices, et fermentum magna convallis. Nullam quis vulputate magna. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Nunc scelerisque enim ut magna vulputate feugiat. Suspendisse rutrum lectus et diam congue, sed pretium eros facilisis. Pellentesque pretium lectus orci, non accumsan velit vestibulum a. Fusce orci dui, tincidunt et tortor non, auctor rutrum mauris. Vestibulum sed ultricies enim, at ultrices quam.\r\n\r\nQuisque pellentesque libero eget dui elementum scelerisque. Pellentesque tempor arcu in hendrerit molestie. Phasellus euismod nisi in malesuada convallis. Praesent sapien neque, fermentum a laoreet eget, tempus ultricies nulla. Mauris mi tortor, maximus eu risus vitae, bibendum vestibulum leo. Nulla vitae efficitur lectus. Aenean aliquet massa magna. Nullam at dapibus mi. Vivamus massa nibh, venenatis mattis nibh pretium, pretium volutpat leo. Vestibulum eu sem elit. Duis consequat, magna id semper elementum, est nisi pharetra orci, eget molestie diam purus sed sem. Vestibulum est purus, sollicitudin eget lectus ut, molestie aliquam purus. P', 0, 0, '2015-03-20', '2015-12-20', 1, 1, 0, 1, 'super-deal', 'Coon Creek Rd', 'Armada', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc interdum mauris vitae urna ultrices, et fermentum magna convallis. Nullam quis vulputate magna. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos.', 'Michigan', '0000-00-00', '0000-00-00', 1, '', 0, '42.870074', '-82.92174699999998', 0, '2015-03-20 14:01:35'),
(19, 7, 'Photograpy course', ' Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc interdum mauris vitae urna ultrices, et fermentum magna convallis. Nullam quis vulputate magna. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Nunc scelerisque enim ut magna vulputate feugiat. Suspendisse rutrum lectus et diam congue, sed pretium eros facilisis. Pellentesque pretium lectus orci, non accumsan velit vestibulum a. Fusce orci dui, tincidunt et tortor non, auctor rutrum mauris. Vestibulum sed ultricies enim, at ultrices quam.\r\n\r\nQuisque pellentesque libero eget dui elementum scelerisque. Pellentesque tempor arcu in hendrerit molestie. Phasellus euismod nisi in malesuada convallis. Praesent sapien neque, fermentum a laoreet eget, tempus ultricies nulla. Mauris mi tortor, maximus eu risus vitae, bibendum vestibulum leo. Nulla vitae efficitur lectus. Aenean aliquet massa magna. Nullam at dapibus mi. Vivamus massa nibh, venenatis mattis nibh pretium, pretium volutpat leo. Vestibulum eu sem elit. Duis consequat, magna id semper elementum, est nisi pharetra orci, eget molestie diam purus sed sem. Vestibulum est purus, sollicitudin eget lectus ut, molestie aliquam purus. ', 150, 100, '2015-03-20', '2015-12-20', 1, 1, 0, 0, 'photograpy-course', 'Mont Steet', 'Loretto', ' Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc interdum mauris vitae urna ultrices, et fermentum magna convallis. Nullam quis vulputate magna. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos.', 'Minnesota', '0000-00-00', '0000-00-00', 1, '', 0, '45.11', '-93.67000000000002', 0, '2015-03-20 14:46:55'),
(20, 4, 'Real estate offer', ' Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc interdum mauris vitae urna ultrices, et fermentum magna convallis. Nullam quis vulputate magna. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Nunc scelerisque enim ut magna vulputate feugiat. Suspendisse rutrum lectus et diam congue, sed pretium eros facilisis. Pellentesque pretium lectus orci, non accumsan velit vestibulum a. Fusce orci dui, tincidunt et tortor non, auctor rutrum mauris. Vestibulum sed ultricies enim, at ultrices quam.\r\n\r\nQuisque pellentesque libero eget dui elementum scelerisque. Pellentesque tempor arcu in hendrerit molestie. Phasellus euismod nisi in malesuada convallis. Praesent sapien neque, fermentum a laoreet eget, tempus ultricies nulla. Mauris mi tortor, maximus eu risus vitae, bibendum vestibulum leo. Nulla vitae efficitur lectus. Aenean aliquet massa magna. Nullam at dapibus mi. Vivamus massa nibh, venenatis mattis nibh pretium, pretium volutpat leo. Vestibulum eu sem elit. Duis consequat, magna id semper elementum, est nisi pharetra orci, eget molestie diam purus sed sem. Vestibulum est purus, sollicitudin eget lectus ut, molestie aliquam purus.', 1000000, 800000, '2015-03-20', '2015-03-20', 1, 1, 0, 4, 'real-estate-offer', 'U.S. 101', 'Florence', ' Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc interdum mauris vitae urna ultrices, et fermentum magna convallis. Nullam quis vulputate magna. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos.', 'Oregon', '0000-00-00', '0000-00-00', 1, '', 0, '44.244779', '-124.11095399999999', 0, '2015-03-20 15:02:25'),
(21, 33, 'Best car deal', 'Donec eleifend purus nulla, non vehicula nisi dictum quis. Maecenas in odio purus. Etiam vulputate nisi eget pharetra tincidunt. Morbi et eros consectetur, ultricies ligula quis, ullamcorper neque. Donec pellentesque felis vel luctus tempus. Curabitur blandit dui purus, non viverra magna consequat vitae. Nunc volutpat malesuada orci vitae varius.\r\n\r\nSuspendisse accumsan nunc non dictum bibendum. Sed suscipit id ipsum ut tincidunt. Vivamus condimentum diam at condimentum scelerisque. Etiam vulputate pellentesque maximus. Curabitur tincidunt nibh et nisl porttitor, eget ultrices turpis maximus. Fusce molestie elit eget felis cursus volutpat. Nam tincidunt lacus nec massa sagittis, eu dapibus purus bibendum. Ut hendrerit, felis nec congue posuere, lorem urna eleifend est, ac venenatis quam augue a arcu. Nullam sit amet finibus diam. Aenean placerat gravida mi at eleifend. Sed felis nulla, tempus ac vulputate vitae, condimentum vel nunc. Nam egestas, nunc sit amet tempor pellentesque, sapien justo aliquam tortor, at posuere elit purus eget orci. Aliquam hendrerit enim turpis, vitae ultrices libero accumsan nec.', 0, 0, '2015-03-23', '2015-03-23', 1, 1, 0, 1, 'best-car-deal', 'Hopkins Ave', 'Jersey City', 'Donec eleifend purus nulla, non vehicula nisi dictum quis. Maecenas in odio purus. Etiam vulputate nisi eget pharetra tincidunt. Morbi et eros consectetur, ultricies ligula quis, ullamcorper neque. Donec pellentesque felis vel luctus tempus. ', 'New Jersey', '0000-00-00', '0000-00-00', 1, '', 0, '40.7367335', '-74.05566350000004', 0, '2015-03-23 05:01:16');


-- --------------------------------------------------------

--
-- Table structure for table `#__jbusinessdirectory_company_offer_category`
--

CREATE TABLE IF NOT EXISTS `#__jbusinessdirectory_company_offer_category` (
  `offerId` int(11) NOT NULL,
  `categoryId` int(11) NOT NULL,
  PRIMARY KEY (`offerId`,`categoryId`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `#__jbusinessdirectory_company_offer_category`
--

INSERT INTO `#__jbusinessdirectory_company_offer_category` (`offerId`, `categoryId`) VALUES
(3, 29),
(3, 39),
(3, 41),
(3, 42),
(13, 29),
(13, 41),
(13, 42),
(14, 7),
(14, 28),
(14, 31),
(14, 33),
(16, 1),
(17, 7),
(17, 28),
(17, 31),
(17, 33),
(18, 7),
(18, 23),
(18, 24),
(18, 28),
(18, 31),
(18, 33),
(19, 24),
(20, 79),
(21, 14),
(21, 16),
(21, 17),
(21, 74);


-- --------------------------------------------------------

--
-- Table structure for table `#__jbusinessdirectory_company_offer_pictures`
--

CREATE TABLE IF NOT EXISTS `#__jbusinessdirectory_company_offer_pictures` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `offerId` int(10) NOT NULL DEFAULT '0',
  `picture_info` varchar(255) NOT NULL,
  `picture_path` varchar(255) NOT NULL,
  `picture_enable` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `idx_offer` (`offerId`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=49 ;

--
-- Dumping data for table `#__jbusinessdirectory_company_offer_pictures`
--

INSERT INTO `#__jbusinessdirectory_company_offer_pictures` (`id`, `offerId`, `picture_info`, `picture_path`, `picture_enable`) VALUES
(53, 13, '', 0x2f6f66666572732f31332f6e61747572616c2d6865616c74682d313432363836343436352e6a7067, 1),
(61, 3, '', 0x2f6f66666572732f332f5350415f576f6d616e5f466163655f313238305f3737305f642d313432363836353539392e6a7067, 1),
(43, 15, '', 0x2f6f66666572732f31352f4b696e67526f6f6d2d313431313734343133302e6a7067, 1),
(44, 15, '', 0x2f6f66666572732f31352f696e742d67756573742d726f6f6d73312d313431313734343133342e6a7067, 1),
(42, 15, '', 0x2f6f66666572732f31352f6e65775f796f726b5f6e65775f796f726b5f686f74656c5f636173696e6f5f6c61735f76656761735f73747269702d6c61735f76656761732d313431313734343133392e6a7067, 1),
(46, 16, '', 0x2f6f66666572732f31362f3133353746756c6c4d6f6f6e526973696e675f706963312d313431313734343339392e6a7067, 1),
(86, 14, '', 0x2f6f66666572732f31342f696d616765312d313432363836373139382e6a7067, 1),
(85, 14, '', 0x2f6f66666572732f31342f696d616765322d313432363836373139362e6a7067, 1),
(84, 14, '', 0x2f6f66666572732f31342f696d616765342d313432363836373139322e6a7067, 1),
(106, 19, '', 0x2f6f66666572732f31392f69616d6765382d313432363837373231322e6a7067, 1),
(83, 14, '', 0x2f6f66666572732f31342f696d616765362d313432363836373138342e6a7067, 1),
(105, 19, '', 0x2f6f66666572732f31392f696d616765322d313432363837373230382e6a7067, 1),
(54, 13, '', 0x2f6f66666572732f31332f4e61747572616c2d537570706c656d656e7473312d313432363836343436382e6a7067, 1),
(55, 13, '', 0x2f6f66666572732f31332f70722d666f722d6e61747572616c2d6865616c74682d313432363836343437312e6a7067, 1),
(56, 13, '', 0x2f6f66666572732f31332f736d616c6c312d313432363836343437392e6a7067, 1),
(104, 19, '', 0x2f6f66666572732f31392f696d616765372d313432363837373230332e6a7067, 1),
(62, 3, '', 0x2f6f66666572732f332f736d616c6c312d313432363836353539362e6a7067, 1),
(63, 3, '', 0x2f6f66666572732f332f6e61747572616c2d6865616c74682d313432363836353630332e6a7067, 1),
(64, 3, '', 0x2f6f66666572732f332f70722d666f722d6e61747572616c2d6865616c74682d313432363836353630372e6a7067, 1),
(103, 19, '', 0x2f6f66666572732f31392f696d616765352d313432363837373230312e6a7067, 1),
(91, 17, '', 0x2f6f66666572732f31372f696d616765362d313432363836373934312e6a7067, 1),
(92, 17, '', 0x2f6f66666572732f31372f696d616765322d313432363836373934342e6a7067, 1),
(93, 17, '', 0x2f6f66666572732f31372f696d616765342d313432363836373934372e6a7067, 1),
(94, 17, '', 0x2f6f66666572732f31372f696d616765352d313432363836373935302e6a7067, 1),
(99, 18, '', 0x2f6f66666572732f31382f696d616765392d313432363837343437392e6a7067, 1),
(100, 18, '', 0x2f6f66666572732f31382f696d616765342d313432363837343436372e6a7067, 1),
(101, 18, '', 0x2f6f66666572732f31382f69616d6765382d313432363837343438332e6a7067, 1),
(102, 18, '', 0x2f6f66666572732f31382f696d616765332d313432363837343439322e6a7067, 1),
(130, 20, '', 0x2f6f66666572732f32302f747261646974696f6e616c2d6c6976696e672d726f6f6d2d313432363837383133352e6a7067, 1),
(131, 20, '', 0x2f6f66666572732f32302f706f6f6c322d313432363837383237322e6a7067, 1),
(129, 20, '', 0x2f6f66666572732f32302f7368696e676c652d7374796c652d6d616e6f722d686f7573652d6c75787572792d6964656173322d313432363837383133322e6a7067, 1),
(128, 20, '', 0x2f6f66666572732f32302f726574726f2d746f6e652d666f722d6c75787572696f75732d6c75787572792d61706172746d656e742d6c6976696e672d726f6f6d2d6465636f726174696e672d69646561732d6461696c792d313432363837383132382e6a7067, 1),
(127, 20, '', 0x2f6f66666572732f32302f7265616c2d6573746174652d70686f746f6772617068792d6261636b796172642d30352d31303234783638322d313432363837383132312e6a7067, 1),
(133, 21, '', 0x2f6f66666572732f32312f696d616765322d313432373130313236392e6a7067, 1),
(132, 21, '', 0x2f6f66666572732f32312f696d616765332d313432373130313236372e6a7067, 1);

-- --------------------------------------------------------

--
-- Table structure for table `#__jbusinessdirectory_company_pictures`
--

CREATE TABLE IF NOT EXISTS `#__jbusinessdirectory_company_pictures` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `companyId` int(10) NOT NULL DEFAULT '0',
  `picture_info` varchar(255) NOT NULL,
  `picture_path` varchar(255) NOT NULL,
  `picture_enable` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=232 ;

--
-- Dumping data for table `#__jbusinessdirectory_company_pictures`
--

INSERT INTO `#__jbusinessdirectory_company_pictures` (`id`, `companyId`, `picture_info`, `picture_path`, `picture_enable`) VALUES
(415, 30, '', 0x2f636f6d70616e6965732f33302f747261646974696f6e616c2d6c6976696e672d726f6f6d2d313432363837383932352e6a7067, 1),
(405, 33, '', 0x2f636f6d70616e6965732f33332f696d616765312d313432373130303039392e6a7067, 1),
(410, 29, '', 0x2f636f6d70616e6965732f32392f696d616765332d313432363837373035342e6a7067, 1),
(385, 12, '', 0x2f636f6d70616e6965732f31322f5350415f576f6d616e5f466163655f313238305f3737305f642d313432363836333036372e6a7067, 1),
(403, 33, '', 0x2f636f6d70616e6965732f33332f696d616765332d313432373130303039332e6a7067, 1),
(299, 9, '', 0x2f636f6d70616e6965732f392f5350415f576f6d616e5f466163655f313238305f3737305f642d313432363836373433322e6a7067, 1),
(298, 9, '', 0x2f636f6d70616e6965732f392f6e61747572616c2d6865616c74682d313432363836373432382e6a7067, 1),
(384, 12, '', 0x2f636f6d70616e6965732f31322f4c535f42413535302d313432363836313933352e6a7067, 1),
(383, 12, '', 0x2f636f6d70616e6965732f31322f3136333031303334395f6772616e64652d313432363836333035392e6a7067, 1),
(382, 12, '', 0x2f636f6d70616e6965732f31322f736b696e636172653130312d696e6772656469656e74735f7762616a2d313432363836333035362e6a7067, 1),
(381, 12, '', 0x2f636f6d70616e6965732f31322f6865616c74682d73746f72652d313432363836313936312e6a7067, 1),
(400, 32, '', 0x2f636f6d70616e6965732f33322f696d616765342d313432373039393739332e6a7067, 1),
(399, 32, '', 0x2f636f6d70616e6965732f33322f696d616765362d313432373039393738382e6a7067, 1),
(398, 32, '', 0x2f636f6d70616e6965732f33322f696d616765352d313432373039393738352e6a7067, 1),
(397, 32, '', 0x2f636f6d70616e6965732f33322f696d616765332d313432373039393738322e6a7067, 1),
(396, 32, '', 0x2f636f6d70616e6965732f33322f696d616765372d313432373039393737382e6a7067, 1),
(297, 9, '', 0x2f636f6d70616e6965732f392f736c696465312d7468652d6865616c74682d616e642d6265617574792d776f726c642d313432363836373432352e6a7067, 1),
(296, 9, '', 0x2f636f6d70616e6965732f392f736b696e636172653130312d696e6772656469656e74735f7762616a2d313432363836373432302e6a7067, 1),
(295, 9, '', 0x2f636f6d70616e6965732f392f73746f72652d696e746572696f722d64657369676e2d69646561732d626f7574697175652d313432363836373431332e6a7067, 1),
(414, 30, '', 0x2f636f6d70616e6965732f33302f747261646974696f6e616c2d66616d696c792d726f6f6d2d313432363837383932322e6a7067, 1),
(395, 5, '', 0x2f636f6d70616e6965732f352f696e6465782d313432363837363833352e6a7067, 1),
(394, 5, '', 0x2f636f6d70616e6965732f352f696d616765332d313432363837363833322e6a7067, 1),
(393, 5, '', 0x2f636f6d70616e6965732f352f696d616765312d313432363837363832392e6a7067, 1),
(392, 5, '', 0x2f636f6d70616e6965732f352f696d616765322d313432363837363832362e6a7067, 1),
(300, 9, '', 0x2f636f6d70616e6965732f392f70722d666f722d6e61747572616c2d6865616c74682d313432363836373433362e6a7067, 1),
(404, 33, '', 0x2f636f6d70616e6965732f33332f696d616765322d313432373130303039362e6a7067, 1),
(402, 33, '', 0x2f636f6d70616e6965732f33332f696d616765352d313432373130303039302e6a7067, 1),
(401, 33, '', 0x2f636f6d70616e6965732f33332f696d616765342d313432373130303038382e6a7067, 1),
(413, 30, '', 0x2f636f6d70616e6965732f33302f696d616765342d313432363837383931372e6a7067, 1),
(412, 30, '', 0x2f636f6d70616e6965732f33302f636c61737369632d6c6976696e672d313432363837383931332e6a7067, 1),
(411, 30, '', 0x2f636f6d70616e6965732f33302f696d616765732d313432363837383636312e6a7067, 1),
(419, 31, '', 0x2f636f6d70616e6965732f33312f696d616765332d313432363838333337302e6a7067, 1),
(418, 31, '', 0x2f636f6d70616e6965732f33312f696d616765322d313432363838333336372e6a7067, 1),
(334, 4, '', 0x2f636f6d70616e6965732f342f686f7573652d313432363837373637362e6a7067, 1),
(348, 1, '', 0x2f636f6d70616e6965732f312f696d616765332d313432363838333835362e6a7067, 1),
(347, 1, '', 0x2f636f6d70616e6965732f312f696d616765322d313432363838333835332e6a7067, 1),
(346, 1, '', 0x2f636f6d70616e6965732f312f696d616765312d313432363838333835312e6a7067, 1),
(417, 31, '', 0x2f636f6d70616e6965732f33312f696d616765312d313432363838333336332e6a7067, 1),
(416, 31, '', 0x2f636f6d70616e6965732f33312f696d616765352d313432363838333336302e6a7067, 1),
(335, 4, '', 0x2f636f6d70616e6965732f342f666f746f6c69615f333832353031345f782d313432363837373638332e6a7067, 1),
(336, 4, '', 0x2f636f6d70616e6965732f342f696d616765732d313432363837373638362e6a7067, 1),
(337, 4, '', 0x2f636f6d70616e6965732f342f7265616c2d6573746174652d70686f746f6772617068792d6261636b796172642d30352d31303234783638322d313432363837373639332e6a7067, 1),
(349, 1, '', 0x2f636f6d70616e6965732f312f696d616765342d313432363838333835392e6a7067, 1),
(350, 1, '', 0x2f636f6d70616e6965732f312f696d616765352d313432363838333836322e6a7067, 1),
(409, 29, '', 0x2f636f6d70616e6965732f32392f696d616765372d313432363837373034392e6a7067, 1),
(408, 29, '', 0x2f636f6d70616e6965732f32392f696d616765322d313432363837373031392e6a7067, 1),
(407, 29, '', 0x2f636f6d70616e6965732f32392f69616d6765382d313432363837373031322e6a7067, 1),
(406, 29, '', 0x2f636f6d70616e6965732f32392f696d616765312d313432363837373032342e6a7067, 1),
(380, 8, '', 0x2f636f6d70616e6965732f382f696d616765312d313432363836363937352e6a7067, 1),
(379, 8, '', 0x2f636f6d70616e6965732f382f696d616765322d313432363836363937312e6a7067, 1),
(378, 8, '', 0x2f636f6d70616e6965732f382f696d616765362d313432363836363936372e6a7067, 1),
(377, 8, '', 0x2f636f6d70616e6965732f382f696d616765342d313432363836363936332e6a7067, 1),
(376, 8, '', 0x2f636f6d70616e6965732f382f696d616765332d313432363836363935392e6a7067, 1),
(366, 7, '', 0x2f636f6d70616e6965732f372f696d616765342d313432363837343737392e6a7067, 1),
(367, 7, '', 0x2f636f6d70616e6965732f372f696d616765372d313432363837343739302e6a7067, 1),
(368, 7, '', 0x2f636f6d70616e6965732f372f696d616765392d313432363837343739322e6a7067, 1),
(369, 7, '', 0x2f636f6d70616e6965732f372f69616d6765382d313432363837343938362e6a7067, 1),
(370, 7, '', 0x2f636f6d70616e6965732f372f696d616765322d313432363837343939342e6a7067, 1),
(386, 12, '', 0x2f636f6d70616e6965732f31322f6e61747572616c2d6865616c74682d313432363836333730352e6a7067, 1),
(387, 12, '', 0x2f636f6d70616e6965732f31322f70722d666f722d6e61747572616c2d6865616c74682d313432363836333731322e6a7067, 1);

-- --------------------------------------------------------

--
-- Table structure for table `#__jbusinessdirectory_company_ratings`
--

CREATE TABLE IF NOT EXISTS `#__jbusinessdirectory_company_ratings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `companyId` int(11) NOT NULL,
  `rating` float NOT NULL,
  `ipAddress` varchar(45) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_company` (`companyId`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=10 ;

--
-- Dumping data for table `#__jbusinessdirectory_company_ratings`
--

INSERT INTO `#__jbusinessdirectory_company_ratings` (`id`, `companyId`, `rating`, `ipAddress`) VALUES
(1, 8, 4, '5.15.238.52'),
(2, 12, 4, '5.15.238.52'),
(3, 5, 3, '5.15.238.52'),
(4, 4, 5, '5.15.238.52'),
(5, 1, 3, '5.15.238.52'),
(6, 7, 3.5, '5.15.238.52'),
(7, 9, 1.5, '5.15.238.52'),
(8, 8, 5, '127.0.0.1'),
(9, 1, 5, '127.0.0.1'),
(10, 7, 5, '127.0.0.1');


-- --------------------------------------------------------

--
-- Table structure for table `#__jbusinessdirectory_company_reviews`
--

CREATE TABLE IF NOT EXISTS `#__jbusinessdirectory_company_reviews` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `subject` varchar(100) DEFAULT NULL,
  `description` text,
  `userId` int(11) NOT NULL,
  `likeCount` smallint(6) DEFAULT '0',
  `dislikeCount` smallint(6) DEFAULT '0',
  `state` tinyint(4) NOT NULL DEFAULT '1',
  `companyId` int(11) NOT NULL,
  `creationDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `aproved` tinyint(1) NOT NULL DEFAULT '0',
  `ipAddress` varchar(45) DEFAULT NULL,
  `abuseReported` tinyint(1) NOT NULL DEFAULT '0',
  `rating` float NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `#__jbusinessdirectory_company_reviews`
--

INSERT INTO `#__jbusinessdirectory_company_reviews` (`id`, `name`, `subject`, `description`, `userId`, `likeCount`, `dislikeCount`, `state`, `companyId`, `creationDate`, `aproved`, `ipAddress`, `abuseReported`, `rating`) VALUES
(8, 'Kelly', 'The best experience ever', 'Ut scelerisque eget mi eget porttitor. Nunc risus enim, volutpat et tempor eu, pretium et est. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam turpis nisl, laoreet varius mauris ac, porta pulvinar felis. Vestibulum placerat, velit eleifend facilisis cursus, turpis nisl ullamcorper eros, eu tristique est nisl a dui. Vestibulum elementum diam sed iaculis porttitor.', 439, 0, 0, 1, 12, '2015-03-24 11:03:37', 0, '127.0.0.1', 0, 4.17),
(9, 'Sam', 'A happy customer', 'Sed non risus erat. Cras ac dapibus augue. Pellentesque non purus at massa viverra tempus vel vestibulum elit. Quisque in libero in diam consectetur convallis at sed dolor. Nunc finibus arcu sed maximus lacinia. Vestibulum et eleifend lectus, ut laoreet elit. ', 439, 0, 0, 1, 12, '2015-03-24 11:09:42', 0, '127.0.0.1', 0, 4.67),
(4, 'Loren Jonson', 'Love the products', 'I had such a good experience on this store.', 0, 0, 0, 1, 9, '2015-03-20 16:09:48', 0, '127.0.0.1', 0, 5),
(5, 'John', 'This is what I was looking for', 'Praesent quis tellus id enim imperdiet tempor. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Cras interdum ut ante non porta. Pellentesque convallis est vel velit luctus, in consequat tortor rutrum. In lectus quam, tempor eu diam efficitur, fringilla aliquet sapien.', 439, 0, 0, 1, 8, '2015-03-24 09:59:05', 0, '127.0.0.1', 0, 5),
(6, 'Michael', 'Greate store', 'Praesent id leo ex. Donec condimentum tincidunt metus at auctor. Nulla facilisis orci vitae ipsum volutpat pharetra. Proin eleifend lobortis nunc, in fringilla justo. Ut sollicitudin lacinia ex eget dapibus. Cras pharetra diam eu malesuada sagittis. Mauris eget ligula gravida, imperdiet ligula a, dictum ex. ', 439, 0, 0, 1, 29, '2015-03-24 10:49:41', 0, '127.0.0.1', 0, 4.33),
(7, 'Kevin', 'The best experience ever', 'Pellentesque convallis est vel velit luctus, in consequat tortor rutrum. In lectus quam, tempor eu diam efficitur, fringilla aliquet sapien. Praesent quis tellus id enim imperdiet tempor. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Cras interdum ut ante non porta. ', 439, 0, 0, 1, 8, '2015-03-24 10:58:42', 0, '127.0.0.1', 0, 4.83),
(10, 'John', 'Great food, great service', 'Maecenas convallis malesuada iaculis. Nullam non maximus velit, id molestie est. Pellentesque lobortis sodales tortor. Proin non sollicitudin felis, aliquam ornare massa. Fusce vel turpis est. Nam tempus turpis at orci rutrum tincidunt quis ac odio. Maecenas nec molestie arcu. Suspendisse potenti. Donec gravida diam urna, rutrum malesuada nisi tempor in. Vivamus vel ligula sed ante mattis venenatis. Cras ultricies ornare elit nec blandit. Morbi convallis tellus laoreet, egestas sapien non, condimentum ante. Donec egestas scelerisque est ut aliquam. Nam vulputate felis eu massa imperdiet facilisis. Interdum et malesuada fames ac ante ipsum primis in faucibus. ', 439, 0, 0, 1, 31, '2015-03-24 14:16:10', 0, '127.0.0.1', 0, 3.83);


-- --------------------------------------------------------

--
-- Table structure for table `#__jbusinessdirectory_company_reviews_criteria`
--

CREATE TABLE IF NOT EXISTS `#__jbusinessdirectory_company_reviews_criteria` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(77) DEFAULT NULL,
  `ordering` tinyint(4) DEFAULT NULL,
  `published` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;


INSERT INTO `#__jbusinessdirectory_company_reviews_criteria` (`id`, `name`, `ordering`, `published`) VALUES
(1, 'Service', 1, NULL),
(2, 'Quality', 2, NULL),
(3, 'Staff', 3, NULL);
-- --------------------------------------------------------
--
-- Table structure for table `#__jbusinessdirectory_company_reviews_user_criteria`
--

CREATE TABLE IF NOT EXISTS `#__jbusinessdirectory_company_reviews_user_criteria` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `review_id` int(11) DEFAULT NULL,
  `criteria_id` int(11) DEFAULT NULL,
  `score` float DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=28 ;

-- --------------------------------------------------------

INSERT INTO `#__jbusinessdirectory_company_reviews_user_criteria` (`id`, `review_id`, `criteria_id`, `score`) VALUES
(28, 3, 1, 5),
(29, 3, 2, 4),
(30, 3, 3, 5),
(31, 4, 1, 5),
(32, 4, 2, 5),
(33, 4, 3, 5),
(34, 5, 1, 5),
(35, 5, 2, 5),
(36, 5, 3, 5),
(37, 6, 1, 4),
(38, 6, 2, 4),
(39, 6, 3, 5),
(40, 7, 1, 5),
(41, 7, 2, 5),
(42, 7, 3, 5),
(43, 8, 1, 4),
(44, 8, 2, 5),
(45, 8, 3, 4),
(46, 9, 1, 5),
(47, 9, 2, 5),
(48, 9, 3, 5),
(49, 10, 1, 4),
(50, 10, 2, 4),
(51, 10, 3, 4);


--
-- Table structure for table `#__jbusinessdirectory_company_review_abuses`
--

CREATE TABLE IF NOT EXISTS `#__jbusinessdirectory_company_review_abuses` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `reviewId` int(11) NOT NULL,
  `email` varchar(45) NOT NULL,
  `description` text NOT NULL,
  `state` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `#__jbusinessdirectory_company_review_responses`
--

CREATE TABLE IF NOT EXISTS `#__jbusinessdirectory_company_review_responses` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `state` tinyint(4) NOT NULL DEFAULT '0',
  `reviewId` int(11) NOT NULL,
  `firstName` varchar(45) DEFAULT NULL,
  `lastName` varchar(45) DEFAULT NULL,
  `response` text,
  `email` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`,`reviewId`),
  KEY `R_19` (`reviewId`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `#__jbusinessdirectory_company_types`
--

CREATE TABLE IF NOT EXISTS `#__jbusinessdirectory_company_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `ordering` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

--
-- Dumping data for table `#__jbusinessdirectory_company_types`
--

INSERT INTO `#__jbusinessdirectory_company_types` (`id`, `name`, `ordering`) VALUES
(1, 'Manufacturer/producer', 1),
(2, 'Distributor ', 2),
(4, 'Wholesaler ', 3),
(5, 'Retailer', 4),
(6, 'Service Provider', 5),
(7, 'Subcontractor', 6),
(8, 'Agent/Representative', 7);

-- --------------------------------------------------------

--
-- Table structure for table `#__jbusinessdirectory_company_videos`
--

CREATE TABLE IF NOT EXISTS `#__jbusinessdirectory_company_videos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `companyId` int(11) DEFAULT NULL,
  `url` varchar(245) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=47 ;

-- --------------------------------------------------------

--
-- Table structure for table `#__jbusinessdirectory_countries`
--

CREATE TABLE IF NOT EXISTS `#__jbusinessdirectory_countries` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `country_name` char(255) NOT NULL,
  `country_currency` char(255) NOT NULL,
  `country_currency_short` char(50) NOT NULL,
  `logo` varchar(100) DEFAULT NULL,
  `description` varchar(245) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=244 ;

--
-- Dumping data for table `#__jbusinessdirectory_countries`
--

INSERT INTO `#__jbusinessdirectory_countries` (`id`, `country_name`, `country_currency`, `country_currency_short`) VALUES
(1, 'Andorra', 'Euro', 'EUR'),
(2, 'United Arab Emirates', 'UAE Dirham', 'AED'),
(3, 'Afghanistan', 'Afghani', 'AFA'),
(4, 'Antigua and Barbuda', 'East Caribbean Dollar', 'XCD'),
(5, 'Anguilla', 'East Caribbean Dollar', 'XCD'),
(6, 'Albania', 'Lek', 'ALL'),
(7, 'Armenia', 'Armenian Dram', 'AMD'),
(8, 'Netherlands Antilles', 'Netherlands Antillean guilder', 'ANG'),
(9, 'Angola', 'Kwanza', 'AOA'),
(11, 'Argentina', 'Argentine Peso', 'ARS'),
(12, 'American Samoa', 'US Dollar', 'USD'),
(13, 'Austria', 'Euro', 'EUR'),
(14, 'Australia', 'Australian dollar', 'AUD'),
(15, 'Aruba', 'Aruban Guilder', 'AWG'),
(16, 'Azerbaijan', 'Azerbaijani Manat', 'AZM'),
(17, 'Bosnia and Herzegovina', 'Convertible Marka', 'BAM'),
(18, 'Barbados', 'Barbados Dollar', 'BBD'),
(19, 'Bangladesh', 'Taka', 'BDT'),
(20, 'Belgium', 'Euro', 'EUR'),
(21, 'Burkina Faso', 'CFA Franc BCEAO', 'XOF'),
(22, 'Bulgaria', 'Lev', 'BGL'),
(23, 'Bahrain', 'Bahraini Dinar', 'BHD'),
(24, 'Burundi', 'Burundi Franc', 'BIF'),
(25, 'Benin', 'CFA Franc BCEAO', 'XOF'),
(26, 'Bermuda', 'Bermudian Dollar', 'BMD'),
(27, 'Brunei Darussalam', 'Brunei Dollar', 'BND'),
(28, 'Bolivia', 'Boliviano', 'BOB'),
(29, 'Brazil', 'Brazilian Real', 'BRL'),
(30, 'The Bahamas', 'Bahamian Dollar', 'BSD'),
(31, 'Bhutan', 'Ngultrum', 'BTN'),
(32, 'Bouvet Island', 'Norwegian Krone', 'NOK'),
(33, 'Botswana', 'Pula', 'BWP'),
(34, 'Belarus', 'Belarussian Ruble', 'BYR'),
(35, 'Belize', 'Belize Dollar', 'BZD'),
(36, 'Canada', 'Canadian Dollar', 'CAD'),
(37, 'Cocos (Keeling) Islands', 'Australian Dollar', 'AUD'),
(39, 'Central African Republic', 'CFA Franc BEAC', 'XAF'),
(41, 'Switzerland', 'Swiss Franc', 'CHF'),
(42, 'Cote d''Ivoire', 'CFA Franc BCEAO', 'XOF'),
(43, 'Cook Islands', 'New Zealand Dollar', 'NZD'),
(44, 'Chile', 'Chilean Peso', 'CLP'),
(45, 'Cameroon', 'CFA Franc BEAC', 'XAF'),
(46, 'China', 'Yuan Renminbi', 'CNY'),
(47, 'Colombia', 'Colombian Peso', 'COP'),
(48, 'Costa Rica', 'Costa Rican Colon', 'CRC'),
(49, 'Cuba', 'Cuban Peso', 'CUP'),
(50, 'Cape Verde', 'Cape Verdean Escudo', 'CVE'),
(51, 'Christmas Island', 'Australian Dollar', 'AUD'),
(52, 'Cyprus', 'Cyprus Pound', 'CYP'),
(53, 'Czech Republic', 'Czech Koruna', 'CZK'),
(54, 'Germany', 'Euro', 'EUR'),
(55, 'Djibouti', 'Djibouti Franc', 'DJF'),
(56, 'Denmark', 'Danish Krone', 'DKK'),
(57, 'Dominica', 'East Caribbean Dollar', 'XCD'),
(58, 'Dominican Republic', 'Dominican Peso', 'DOP'),
(59, 'Algeria', 'Algerian Dinar', 'DZD'),
(60, 'Ecuador', 'US dollar', 'USD'),
(61, 'Estonia', 'Kroon', 'EEK'),
(62, 'Egypt', 'Egyptian Pound', 'EGP'),
(63, 'Western Sahara', 'Moroccan Dirham', 'MAD'),
(64, 'Eritrea', 'Nakfa', 'ERN'),
(65, 'Spain', 'Euro', 'EUR'),
(66, 'Ethiopia', 'Ethiopian Birr', 'ETB'),
(67, 'Finland', 'Euro', 'EUR'),
(68, 'Fiji', 'Fijian Dollar', 'FJD'),
(69, 'Falkland Islands (Islas Malvinas)', 'Falkland Islands Pound', 'FKP'),
(71, 'Faroe Islands', 'Danish Krone', 'DKK'),
(72, 'France', 'Euro', 'EUR'),
(74, 'Gabon', 'CFA Franc BEAC', 'XAF'),
(75, 'Grenada', 'East Caribbean Dollar', 'XCD'),
(76, 'Georgia', 'Lari', 'GEL'),
(77, 'French Guiana', 'Euro', 'EUR'),
(78, 'Guernsey', 'Pound Sterling', 'GBP'),
(79, 'Ghana', 'Cedi', 'GHC'),
(80, 'Gibraltar', 'Gibraltar Pound', 'GIP'),
(81, 'Greenland', 'Danish Krone', 'DKK'),
(82, 'The Gambia', 'Dalasi', 'GMD'),
(83, 'Guinea', 'Guinean Franc', 'GNF'),
(84, 'Guadeloupe', 'Euro', 'EUR'),
(85, 'Equatorial Guinea', 'CFA Franc BEAC', 'XAF'),
(86, 'Greece', 'Euro', 'EUR'),
(87, 'South Georgia and the South Sandwich Islands', 'Pound Sterling', 'GBP'),
(88, 'Guatemala', 'Quetzal', 'GTQ'),
(89, 'Guam', 'US Dollar', 'USD'),
(90, 'Guinea-Bissau', 'CFA Franc BCEAO', 'XOF'),
(91, 'Guyana', 'Guyana Dollar', 'GYD'),
(92, 'Hong Kong (SAR)', 'Hong Kong Dollar', 'HKD'),
(93, 'Heard Island and McDonald Islands', 'Australian Dollar', 'AUD'),
(94, 'Honduras', 'Lempira', 'HNL'),
(95, 'Croatia', 'Kuna', 'HRK'),
(96, 'Haiti', 'Gourde', 'HTG'),
(97, 'Hungary', 'Forint', 'HUF'),
(98, 'Indonesia', 'Rupiah', 'IDR'),
(99, 'Ireland', 'Euro', 'EUR'),
(100, 'Israel', 'New Israeli Sheqel', 'ILS'),
(102, 'India', 'Indian Rupee', 'INR'),
(103, 'British Indian Ocean Territory', 'US Dollar', 'USD'),
(104, 'Iraq', 'Iraqi Dinar', 'IQD'),
(105, 'Iran', 'Iranian Rial', 'IRR'),
(106, 'Iceland', 'Iceland Krona', 'ISK'),
(107, 'Italy', 'Euro', 'EUR'),
(108, 'Jersey', 'Pound Sterling', 'GBP'),
(109, 'Jamaica', 'Jamaican dollar', 'JMD'),
(110, 'Jordan', 'Jordanian Dinar', 'JOD'),
(111, 'Japan', 'Yen', 'JPY'),
(112, 'Kenya', 'Kenyan shilling', 'KES'),
(113, 'Kyrgyzstan', 'Som', 'KGS'),
(114, 'Cambodia', 'Riel', 'KHR'),
(115, 'Kiribati', 'Australian dollar', 'AUD'),
(116, 'Comoros', 'Comoro Franc', 'KMF'),
(117, 'Saint Kitts and Nevis', 'East Caribbean Dollar', 'XCD'),
(118, 'Korea North', 'North Korean Won', 'KPW'),
(119, 'Korea South', 'Won', 'KRW'),
(120, 'Kuwait', 'Kuwaiti Dinar', 'KWD'),
(121, 'Cayman Islands', 'Cayman Islands Dollar', 'KYD'),
(122, 'Kazakhstan', 'Tenge', 'KZT'),
(123, 'Laos', 'Kip', 'LAK'),
(124, 'Lebanon', 'Lebanese Pound', 'LBP'),
(125, 'Saint Lucia', 'East Caribbean Dollar', 'XCD'),
(126, 'Liechtenstein', 'Swiss Franc', 'CHF'),
(127, 'Sri Lanka', 'Sri Lanka Rupee', 'LKR'),
(128, 'Liberia', 'Liberian Dollar', 'LRD'),
(129, 'Lesotho', 'Loti', 'LSL'),
(130, 'Lithuania', 'Lithuanian Litas', 'LTL'),
(131, 'Luxembourg', 'Euro', 'EUR'),
(132, 'Latvia', 'Latvian Lats', 'LVL'),
(133, 'Libya', 'Libyan Dinar', 'LYD'),
(134, 'Morocco', 'Moroccan Dirham', 'MAD'),
(135, 'Monaco', 'Euro', 'EUR'),
(136, 'Moldova', 'Moldovan Leu', 'MDL'),
(137, 'Madagascar', 'Malagasy Franc', 'MGF'),
(138, 'Marshall Islands', 'US dollar', 'USD'),
(140, 'Mali', 'CFA Franc BCEAO', 'XOF'),
(141, 'Burma', 'kyat', 'MMK'),
(142, 'Mongolia', 'Tugrik', 'MNT'),
(143, 'Macao', 'Pataca', 'MOP'),
(144, 'Northern Mariana Islands', 'US Dollar', 'USD'),
(145, 'Martinique', 'Euro', 'EUR'),
(146, 'Mauritania', 'Ouguiya', 'MRO'),
(147, 'Montserrat', 'East Caribbean Dollar', 'XCD'),
(148, 'Malta', 'Maltese Lira', 'MTL'),
(149, 'Mauritius', 'Mauritius Rupee', 'MUR'),
(150, 'Maldives', 'Rufiyaa', 'MVR'),
(151, 'Malawi', 'Kwacha', 'MWK'),
(152, 'Mexico', 'Mexican Peso', 'MXN'),
(153, 'Malaysia', 'Malaysian Ringgit', 'MYR'),
(154, 'Mozambique', 'Metical', 'MZM'),
(155, 'Namibia', 'Namibian Dollar', 'NAD'),
(156, 'New Caledonia', 'CFP Franc', 'XPF'),
(157, 'Niger', 'CFA Franc BCEAO', 'XOF'),
(158, 'Norfolk Island', 'Australian Dollar', 'AUD'),
(159, 'Nigeria', 'Naira', 'NGN'),
(160, 'Nicaragua', 'Cordoba Oro', 'NIO'),
(161, 'Netherlands', 'Euro', 'EUR'),
(162, 'Norway', 'Norwegian Krone', 'NOK'),
(163, 'Nepal', 'Nepalese Rupee', 'NPR'),
(164, 'Nauru', 'Australian Dollar', 'AUD'),
(165, 'Niue', 'New Zealand Dollar', 'NZD'),
(166, 'New Zealand', 'New Zealand Dollar', 'NZD'),
(167, 'Oman', 'Rial Omani', 'OMR'),
(168, 'Panama', 'balboa', 'PAB'),
(169, 'Peru', 'Nuevo Sol', 'PEN'),
(170, 'French Polynesia', 'CFP Franc', 'XPF'),
(171, 'Papua New Guinea', 'Kina', 'PGK'),
(172, 'Philippines', 'Philippine Peso', 'PHP'),
(173, 'Pakistan', 'Pakistan Rupee', 'PKR'),
(174, 'Poland', 'Zloty', 'PLN'),
(175, 'Saint Pierre and Miquelon', 'Euro', 'EUR'),
(176, 'Pitcairn Islands', 'New Zealand Dollar', 'NZD'),
(177, 'Puerto Rico', 'US dollar', 'USD'),
(179, 'Portugal', 'Euro', 'EUR'),
(180, 'Palau', 'US dollar', 'USD'),
(181, 'Paraguay', 'Guarani', 'PYG'),
(182, 'Qatar', 'Qatari Rial', 'QAR'),
(183, 'R', 'Euro', 'EUR'),
(184, 'Romania', 'Leu', 'RON'),
(185, 'Russia', 'Russian Ruble', 'RUB'),
(186, 'Rwanda', 'Rwanda Franc', 'RWF'),
(187, 'Saudi Arabia', 'Saudi Riyal', 'SAR'),
(188, 'Solomon Islands', 'Solomon Islands Dollar', 'SBD'),
(189, 'Seychelles', 'Seychelles Rupee', 'SCR'),
(190, 'Sudan', 'Sudanese Dinar', 'SDD'),
(191, 'Sweden', 'Swedish Krona', 'SEK'),
(192, 'Singapore', 'Singapore Dollar', 'SGD'),
(193, 'Saint Helena', 'Saint Helenian Pound', 'SHP'),
(194, 'Slovenia', 'Tolar', 'SIT'),
(195, 'Svalbard', 'Norwegian Krone', 'NOK'),
(196, 'Slovakia', 'Slovak Koruna', 'SKK'),
(197, 'Sierra Leone', 'Leone', 'SLL'),
(198, 'San Marino', 'Euro', 'EUR'),
(199, 'Senegal', 'CFA Franc BCEAO', 'XOF'),
(200, 'Somalia', 'Somali Shilling', 'SOS'),
(201, 'Suriname', 'Suriname Guilder', 'SRG'),
(202, 'S', 'Dobra', 'STD'),
(203, 'El Salvador', 'El Salvador Colon', 'SVC'),
(204, 'Syria', 'Syrian Pound', 'SYP'),
(205, 'Swaziland', 'Lilangeni', 'SZL'),
(206, 'Turks and Caicos Islands', 'US Dollar', 'USD'),
(207, 'Chad', 'CFA Franc BEAC', 'XAF'),
(208, 'French Southern and Antarctic Lands', 'Euro', 'EUR'),
(209, 'Togo', 'CFA Franc BCEAO', 'XOF'),
(210, 'Thailand', 'Baht', 'THB'),
(211, 'Tajikistan', 'Somoni', 'TJS'),
(212, 'Tokelau', 'New Zealand Dollar', 'NZD'),
(213, 'Turkmenistan', 'Manat', 'TMM'),
(214, 'Tunisia', 'Tunisian Dinar', 'TND'),
(215, 'Tonga', 'Pa''anga', 'TOP'),
(216, 'East Timor', 'Timor Escudo', 'TPE'),
(217, 'Turkey', 'Turkish Lira', 'TRL'),
(218, 'Trinidad and Tobago', 'Trinidad and Tobago Dollar', 'TTD'),
(219, 'Tuvalu', 'Australian Dollar', 'AUD'),
(220, 'Taiwan', 'New Taiwan Dollar', 'TWD'),
(221, 'Tanzania', 'Tanzanian Shilling', 'TZS'),
(222, 'Ukraine', 'Hryvnia', 'UAH'),
(223, 'Uganda', 'Uganda Shilling', 'UGX'),
(224, 'United Kingdom', 'Pound Sterling', 'GBP'),
(225, 'United States Minor Outlying Islands', 'US Dollar', 'USD'),
(226, 'United States', 'US Dollar', 'USD'),
(227, 'Uruguay', 'Peso Uruguayo', 'UYU'),
(228, 'Uzbekistan', 'Uzbekistan Sum', 'UZS'),
(229, 'Holy See (Vatican City)', 'Euro', 'EUR'),
(230, 'Saint Vincent and the Grenadines', 'East Caribbean Dollar', 'XCD'),
(231, 'Venezuela', 'Bolivar', 'VEB'),
(232, 'British Virgin Islands', 'US dollar', 'USD'),
(233, 'Virgin Islands', 'US Dollar', 'USD'),
(234, 'Vietnam', 'Dong', 'VND'),
(235, 'Vanuatu', 'Vatu', 'VUV'),
(236, 'Wallis and Futuna', 'CFP Franc', 'XPF'),
(237, 'Samoa', 'Tala', 'WST'),
(238, 'Yemen', 'Yemeni Rial', 'YER'),
(239, 'Mayotte', 'Euro', 'EUR'),
(240, 'Yugoslavia', 'Yugoslavian Dinar', 'YUM'),
(241, 'South Africa', 'Rand', 'ZAR'),
(242, 'Zambia', 'Kwacha', 'ZMK'),
(243, 'Zimbabwe', 'Zimbabwe Dollar', 'ZWD');

-- --------------------------------------------------------

--
-- Table structure for table `#__jbusinessdirectory_currencies`
--

CREATE TABLE IF NOT EXISTS `#__jbusinessdirectory_currencies` (
  `currency_id` int(10) NOT NULL AUTO_INCREMENT,
  `currency_name` char(10) NOT NULL,
  `currency_description` varchar(70) DEFAULT NULL,
  `currency_symbol` varchar(5) DEFAULT NULL,
  PRIMARY KEY (`currency_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=171 ;

--
-- Dumping data for table `#__jbusinessdirectory_currencies`
--

INSERT INTO `#__jbusinessdirectory_currencies` (`currency_id`, `currency_name`, `currency_description`, `currency_symbol`) VALUES
(2, 'AED', 'UAE Dirham', '#'),
(3, 'AFN', 'Afghani', '?'),
(4, 'ALL', 'Lek', 'Lek'),
(5, 'AMD', 'Armenian Dram', '#'),
(6, 'ANG', 'Netherlands Antillian Guilder', 'f'),
(7, 'AOA', 'Kwanza', '#'),
(8, 'ARS', 'Argentine Peso', '$'),
(9, 'AUD', 'Australian Dollar', '$'),
(10, 'AWG', 'Aruban Guilder', 'f'),
(11, 'AZN', 'Azerbaijanian Manat', '???'),
(12, 'BAM', 'Convertible Marks', 'KM'),
(13, 'BBD', 'Barbados Dollar', '$'),
(14, 'BDT', 'Taka', '#'),
(15, 'BGN', 'Bulgarian Lev', '??'),
(16, 'BHD', 'Bahraini Dinar', '#'),
(17, 'BIF', 'Burundi Franc', '#'),
(18, 'BMD', 'Bermudian Dollar (customarily known as Bermuda Dollar)', '$'),
(19, 'BND', 'Brunei Dollar', '$'),
(20, 'BOB BOV', 'Boliviano Mvdol', '$b'),
(21, 'BRL', 'Brazilian Real', 'R$'),
(22, 'BSD', 'Bahamian Dollar', '$'),
(23, 'BWP', 'Pula', 'P'),
(24, 'BYR', 'Belarussian Ruble', 'p.'),
(25, 'BZD', 'Belize Dollar', 'BZ$'),
(26, 'CAD', 'Canadian Dollar', '$'),
(27, 'CDF', 'Congolese Franc', '#'),
(28, 'CHF', 'Swiss Franc', 'CHF'),
(29, 'CLP CLF', 'Chilean Peso Unidades de fomento', '$'),
(30, 'CNY', 'Yuan Renminbi', 'Y'),
(31, 'COP COU', 'Colombian Peso Unidad de Valor Real', '$'),
(32, 'CRC', 'Costa Rican Colon', '?'),
(33, 'CUP CUC', 'Cuban Peso Peso Convertible', '?'),
(34, 'CVE', 'Cape Verde Escudo', '#'),
(35, 'CZK', 'Czech Koruna', 'Kč'),
(36, 'DJF', 'Djibouti Franc', '#'),
(37, 'DKK', 'Danish Krone', 'kr'),
(38, 'DOP', 'Dominican Peso', 'RD$'),
(39, 'DZD', 'Algerian Dinar', '#'),
(40, 'EEK', 'Kroon', '#'),
(41, 'EGP', 'Egyptian Pound', 'L'),
(42, 'ERN', 'Nakfa', '#'),
(43, 'ETB', 'Ethiopian Birr', '#'),
(44, 'EUR', 'Euro', '€'),
(45, 'FJD', 'Fiji Dollar', '$'),
(46, 'FKP', 'Falkland Islands Pound', 'L'),
(47, 'GBP', 'Pound Sterling', 'L'),
(48, 'GEL', 'Lari', '#'),
(49, 'GHS', 'Cedi', '#'),
(50, 'GIP', 'Gibraltar Pound', 'L'),
(51, 'GMD', 'Dalasi', '#'),
(52, 'GNF', 'Guinea Franc', '#'),
(53, 'GTQ', 'Quetzal', 'Q'),
(54, 'GYD', 'Guyana Dollar', '$'),
(55, 'HKD', 'Hong Kong Dollar', '$'),
(56, 'HNL', 'Lempira', 'L'),
(57, 'HRK', 'Croatian Kuna', 'kn'),
(58, 'HTG USD', 'Gourde US Dollar', '#'),
(59, 'HUF', 'Forint', 'Ft'),
(60, 'IDR', 'Rupiah', 'Rp'),
(61, 'ILS', 'New Israeli Sheqel', '?'),
(62, 'INR', 'Indian Rupee', '#'),
(63, 'INR BTN', 'Indian Rupee Ngultrum', '#'),
(64, 'IQD', 'Iraqi Dinar', '#'),
(65, 'IRR', 'Iranian Rial', '?'),
(66, 'ISK', 'Iceland Krona', 'kr'),
(67, 'JMD', 'Jamaican Dollar', 'J$'),
(68, 'JOD', 'Jordanian Dinar', '#'),
(69, 'JPY', 'Yen', 'Y'),
(70, 'KES', 'Kenyan Shilling', '#'),
(71, 'KGS', 'Som', '??'),
(72, 'KHR', 'Riel', '?'),
(73, 'KMF', 'Comoro Franc', '#'),
(74, 'KPW', 'North Korean Won', '?'),
(75, 'KRW', 'Won', '?'),
(76, 'KWD', 'Kuwaiti Dinar', '#'),
(77, 'KYD', 'Cayman Islands Dollar', '$'),
(78, 'KZT', 'Tenge', '??'),
(79, 'LAK', 'Kip', '?'),
(80, 'LBP', 'Lebanese Pound', 'L'),
(81, 'LKR', 'Sri Lanka Rupee', '?'),
(82, 'LRD', 'Liberian Dollar', '$'),
(83, 'LTL', 'Lithuanian Litas', 'Lt'),
(84, 'LVL', 'Latvian Lats', 'Ls'),
(85, 'LYD', 'Libyan Dinar', '#'),
(86, 'MAD', 'Moroccan Dirham', '#'),
(87, 'MDL', 'Moldovan Leu', '#'),
(88, 'MGA', 'Malagasy Ariary', '#'),
(89, 'MKD', 'Denar', '???'),
(90, 'MMK', 'Kyat', '#'),
(91, 'MNT', 'Tugrik', '?'),
(92, 'MOP', 'Pataca', '#'),
(93, 'MRO', 'Ouguiya', '#'),
(94, 'MUR', 'Mauritius Rupee', '?'),
(95, 'MVR', 'Rufiyaa', '#'),
(96, 'MWK', 'Kwacha', '#'),
(97, 'MXN MXV', 'Mexican Peso Mexican Unidad de Inversion (UDI)', '$'),
(98, 'MYR', 'Malaysian Ringgit', 'RM'),
(99, 'MZN', 'Metical', 'MT'),
(100, 'NGN', 'Naira', '?'),
(101, 'NIO', 'Cordoba Oro', 'C$'),
(102, 'NOK', 'Norwegian Krone', 'kr'),
(103, 'NPR', 'Nepalese Rupee', '?'),
(104, 'NZD', 'New Zealand Dollar', '$'),
(105, 'OMR', 'Rial Omani', '?'),
(106, 'PAB USD', 'Balboa US Dollar', 'B/.'),
(107, 'PEN', 'Nuevo Sol', 'S/.'),
(108, 'PGK', 'Kina', '#'),
(109, 'PHP', 'Philippine Peso', 'Php'),
(110, 'PKR', 'Pakistan Rupee', '?'),
(111, 'PLN', 'Zloty', 'zł'),
(112, 'PYG', 'Guarani', 'Gs'),
(113, 'QAR', 'Qatari Rial', '?'),
(114, 'RON', 'New Leu', 'lei'),
(115, 'RSD', 'Serbian Dinar', '???.'),
(116, 'RUB', 'Russian Ruble', '???'),
(117, 'RWF', 'Rwanda Franc', '#'),
(118, 'SAR', 'Saudi Riyal', '?'),
(119, 'SBD', 'Solomon Islands Dollar', '$'),
(120, 'SCR', 'Seychelles Rupee', '?'),
(121, 'SDG', 'Sudanese Pound', '#'),
(122, 'SEK', 'Swedish Krona', 'kr'),
(123, 'SGD', 'Singapore Dollar', '$'),
(124, 'SHP', 'Saint Helena Pound', 'L'),
(125, 'SLL', 'Leone', '#'),
(126, 'SOS', 'Somali Shilling', 'S'),
(127, 'SRD', 'Surinam Dollar', '$'),
(128, 'STD', 'Dobra', '#'),
(129, 'SVC USD', 'El Salvador Colon US Dollar', '$'),
(130, 'SYP', 'Syrian Pound', 'L'),
(131, 'SZL', 'Lilangeni', '#'),
(132, 'THB', 'Baht', '?'),
(133, 'TJS', 'Somoni', '#'),
(134, 'TMT', 'Manat', '#'),
(135, 'TND', 'Tunisian Dinar', '#'),
(136, 'TOP', 'Pa''anga', '#'),
(137, 'TRY', 'Turkish Lira', 'TL'),
(138, 'TTD', 'Trinidad and Tobago Dollar', 'TT$'),
(139, 'TWD', 'New Taiwan Dollar', 'NT$'),
(140, 'TZS', 'Tanzanian Shilling', '#'),
(141, 'UAH', 'Hryvnia', '?'),
(142, 'UGX', 'Uganda Shilling', '#'),
(143, 'USD', 'US Dollar', '$'),
(144, 'UYU UYI', 'Peso Uruguayo Uruguay Peso en Unidades Indexadas', '$U'),
(145, 'UZS', 'Uzbekistan Sum', '??'),
(146, 'VEF', 'Bolivar Fuerte', 'Bs'),
(147, 'VND', 'Dong', '?'),
(148, 'VUV', 'Vatu', '#'),
(149, 'WST', 'Tala', '#'),
(150, 'XAF', 'CFA Franc BEAC', '#'),
(151, 'XAG', 'Silver', '#'),
(152, 'XAU', 'Gold', '#'),
(153, 'XBA', 'Bond Markets Units European Composite Unit (EURCO)', '#'),
(154, 'XBB', 'European Monetary Unit (E.M.U.-6)', '#'),
(155, 'XBC', 'European Unit of Account 9(E.U.A.-9)', '#'),
(156, 'XBD', 'European Unit of Account 17(E.U.A.-17)', '#'),
(157, 'XCD', 'East Caribbean Dollar', '$'),
(158, 'XDR', 'SDR', '#'),
(159, 'XFU', 'UIC-Franc', '#'),
(160, 'XOF', 'CFA Franc BCEAO', '#'),
(161, 'XPD', 'Palladium', '#'),
(162, 'XPF', 'CFP Franc', '#'),
(163, 'XPT', 'Platinum', '#'),
(164, 'XTS', 'Codes specifically reserved for testing purposes', '#'),
(165, 'YER', 'Yemeni Rial', '?'),
(166, 'ZAR', 'Rand', 'R'),
(167, 'ZAR LSL', 'Rand Loti', '#'),
(168, 'ZAR NAD', 'Rand Namibia Dollar', '#'),
(169, 'ZMK', 'Zambian Kwacha', '#'),
(170, 'ZWL', 'Zimbabwe Dollar', '#');

-- --------------------------------------------------------

--
-- Table structure for table `#__jbusinessdirectory_date_formats`
--

CREATE TABLE IF NOT EXISTS `#__jbusinessdirectory_date_formats` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  `dateFormat` varchar(45) DEFAULT NULL,
  `calendarFormat` varchar(45) NOT NULL,
  `defaultDateValue` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `#__jbusinessdirectory_date_formats`
--

INSERT INTO `#__jbusinessdirectory_date_formats` (`id`, `name`, `dateFormat`, `calendarFormat`, `defaultDateValue`) VALUES
(1, 'y-m-d', 'Y-m-d', '%Y-%m-%d', '0000-00-00'),
(2, 'd-m-y', 'd-m-Y', '%d-%m-%Y', '00-00-0000'),
(3, 'm/d/y', 'm/d/Y', '%m/%d/%Y', '00-00-0000');


-- --------------------------------------------------------

--
-- Table structure for table `#__jbusinessdirectory_default_attributes`
--

CREATE TABLE IF NOT EXISTS `#__jbusinessdirectory_default_attributes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(55) DEFAULT NULL,
  `config` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=29 ;

--
-- Dumping data for table `#__jbusinessdirectory_default_attributes`
--

INSERT INTO `#__jbusinessdirectory_default_attributes` (`id`, `name`, `config`) VALUES
(2, 'comercial_name', 3),
(3, 'tax_code', 3),
(4, 'registration_code', 2),
(5, 'website', 2),
(6, 'company_type', 1),
(7, 'slogan', 2),
(8, 'description', 1),
(9, 'keywords', 2),
(10, 'category', 1),
(11, 'logo', 1),
(12, 'street_number', 1),
(13, 'address', 1),
(14, 'city', 1),
(15, 'region', 1),
(16, 'country', 1),
(17, 'postal_code', 1),
(18, 'map', 1),
(20, 'phone', 1),
(21, 'mobile_phone', 2),
(22, 'fax', 2),
(23, 'email', 1),
(24, 'pictures', 2),
(25, 'video', 2),
(26, 'social_networks', 2),
(27, 'short_description', 2),
(28, 'contact_person', 2);

-- --------------------------------------------------------

--
-- Table structure for table `#__jbusinessdirectory_discounts`
--

CREATE TABLE IF NOT EXISTS `#__jbusinessdirectory_discounts` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` char(255) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `value` float(6,2) NOT NULL,
  `percent` tinyint(1) NOT NULL DEFAULT '0',
  `price_type` tinyint(1) NOT NULL DEFAULT '1',
  `package_ids` varchar(255) NOT NULL,
  `code` varchar(50) NOT NULL DEFAULT '0',
  `state` tinyint(1) NOT NULL DEFAULT '1',
  `uses_per_coupon` int(11) DEFAULT NULL,
  `coupon_used` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_code` (`code`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


--
-- Table structure for table `#__jbusinessdirectory_emails`
--

CREATE TABLE IF NOT EXISTS `#__jbusinessdirectory_emails` (
  `email_id` int(10) NOT NULL AUTO_INCREMENT,
  `email_subject` char(255) NOT NULL,
  `email_name` char(255) NOT NULL,
  `email_type` varchar(255) NOT NULL,
  `email_content` blob NOT NULL,
  `is_default` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`email_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=12 ;

--
-- Dumping data for table `#__jbusinessdirectory_emails`
--
INSERT INTO `#__jbusinessdirectory_emails` (`email_id`, `email_subject`, `email_name`, `email_type`, `email_content`, `is_default`) VALUES
(2, 'A new review has been posted for your business listing', 'Review Email', 'Review Email', 0x3c646976207374796c653d226d617267696e3a203070783b206261636b67726f756e642d636f6c6f723a20236634663366343b20666f6e742d66616d696c793a2048656c7665746963612c417269616c2c73616e732d73657269663b20666f6e742d73697a653a20313270783b223e0d0a3c7461626c6520626f726465723d2230222077696474683d2231303025222063656c6c73706163696e673d2230222063656c6c70616464696e673d223022206267636f6c6f723d2223463446334634223e0d0a3c74626f64793e0d0a3c74723e0d0a3c7464207374796c653d2270616464696e673a20313570783b223e3c63656e7465723e0d0a3c7461626c652077696474683d22353730222063656c6c73706163696e673d2230222063656c6c70616464696e673d22302220616c69676e3d2263656e74657222206267636f6c6f723d2223666666666666223e0d0a3c74626f64793e0d0a3c74723e0d0a3c746420616c69676e3d226c656674223e0d0a3c646976207374796c653d22626f726465723a20736f6c69642031707820236439643964393b223e0d0a3c7461626c65207374796c653d226c696e652d6865696768743a20312e363b20666f6e742d73697a653a20313270783b20666f6e742d66616d696c793a2048656c7665746963612c417269616c2c73616e732d73657269663b20626f726465723a20736f6c69642031707820236666666666663b20636f6c6f723a20233434343b2220626f726465723d2230222077696474683d2231303025222063656c6c73706163696e673d2230222063656c6c70616464696e673d223022206267636f6c6f723d2223666666666666223e0d0a3c74626f64793e0d0a3c74723e0d0a3c7464207374796c653d22636f6c6f723a20236666666666663b2220636f6c7370616e3d2232222076616c69676e3d22626f74746f6d22206865696768743d223330223ec2a03c2f74643e0d0a3c2f74723e0d0a3c74723e0d0a3c7464207374796c653d226c696e652d6865696768743a20333270783b2070616464696e672d6c6566743a20333070783b222076616c69676e3d22626173656c696e65223e5b636f6d70616e795f6c6f676f5d3c2f74643e0d0a3c2f74723e0d0a3c2f74626f64793e0d0a3c2f7461626c653e0d0a3c7461626c65207374796c653d226d617267696e2d746f703a20313570783b206d617267696e2d72696768743a20333070783b206d617267696e2d6c6566743a20333070783b20636f6c6f723a20233434343b206c696e652d6865696768743a20312e363b20666f6e742d73697a653a20313270783b20666f6e742d66616d696c793a20417269616c2c73616e732d73657269663b2220626f726465723d2230222077696474683d22353130222063656c6c73706163696e673d2230222063656c6c70616464696e673d223022206267636f6c6f723d2223666666666666223e0d0a3c74626f64793e0d0a3c74723e0d0a3c7464207374796c653d22626f726465722d746f703a20736f6c69642031707820236439643964393b20626f726465722d626f74746f6d3a20736f6c69642031707820236439643964393b2220636f6c7370616e3d2232223e0d0a3c646976207374796c653d2270616464696e673a203135707820303b223e48656c6c6f2c203c6272202f3e3c6272202f3e2041206e6577207265766965772077617320706f7374656420666f7220627573696e657373206c697374696e67205b627573696e6573735f6e616d655d3c6272202f3e596f752063616e207669657720746865207265766965772061743a205b7265766965775f6c696e6b5d203c6272202f3e3c6272202f3e205468616e6b20796f752c0d0a3c6469763e5b636f6d70616e795f6e616d655d205465616d3c2f6469763e0d0a3c2f6469763e0d0a3c2f74643e0d0a3c2f74723e0d0a3c2f74626f64793e0d0a3c2f7461626c653e0d0a3c7461626c65207374796c653d226c696e652d6865696768743a20312e353b20666f6e742d73697a653a20313270783b20666f6e742d66616d696c793a20417269616c2c73616e732d73657269663b206d617267696e2d72696768743a20333070783b206d617267696e2d6c6566743a20333070783b2220626f726465723d2230222077696474683d22353130222063656c6c73706163696e673d2230222063656c6c70616464696e673d223022206267636f6c6f723d2223666666666666223e0d0a3c74626f64793e0d0a3c7472207374796c653d22666f6e742d73697a653a20313170783b20636f6c6f723a20233939393939393b222076616c69676e3d226d6964646c65223e0d0a3c74643e5b6469726563746f72795f776562736974655d3c2f74643e0d0a3c74643e0d0a3c646976207374796c653d22666c6f61743a2072696768743b2070616464696e672d746f703a20313070783b223e5b636f6d70616e795f736f6369616c5f6e6574776f726b735d3c2f6469763e0d0a3c2f74643e0d0a3c2f74723e0d0a3c74723e0d0a3c7464207374796c653d22636f6c6f723a20236666666666663b2220636f6c7370616e3d223222206865696768743d223135223ec2a03c2f74643e0d0a3c2f74723e0d0a3c2f74626f64793e0d0a3c2f7461626c653e0d0a3c2f6469763e0d0a3c2f74643e0d0a3c2f74723e0d0a3c2f74626f64793e0d0a3c2f7461626c653e0d0a3c2f63656e7465723e3c2f74643e0d0a3c2f74723e0d0a3c2f74626f64793e0d0a3c2f7461626c653e0d0a3c2f6469763e, 1),
(3, 'Your review has received a response', 'Review Response Email', 'Review Response Email', 0x3c646976207374796c653d226d617267696e3a203070783b206261636b67726f756e642d636f6c6f723a20236634663366343b20666f6e742d66616d696c793a2048656c7665746963612c417269616c2c73616e732d73657269663b20666f6e742d73697a653a20313270783b223e0d0a3c7461626c6520626f726465723d2230222077696474683d2231303025222063656c6c73706163696e673d2230222063656c6c70616464696e673d223022206267636f6c6f723d2223463446334634223e0d0a3c74626f64793e0d0a3c74723e0d0a3c7464207374796c653d2270616464696e673a20313570783b223e3c63656e7465723e0d0a3c7461626c652077696474683d22353730222063656c6c73706163696e673d2230222063656c6c70616464696e673d22302220616c69676e3d2263656e74657222206267636f6c6f723d2223666666666666223e0d0a3c74626f64793e0d0a3c74723e0d0a3c746420616c69676e3d226c656674223e0d0a3c646976207374796c653d22626f726465723a20736f6c69642031707820236439643964393b223e0d0a3c7461626c65207374796c653d226c696e652d6865696768743a20312e363b20666f6e742d73697a653a20313270783b20666f6e742d66616d696c793a2048656c7665746963612c417269616c2c73616e732d73657269663b20626f726465723a20736f6c69642031707820236666666666663b20636f6c6f723a20233434343b2220626f726465723d2230222077696474683d2231303025222063656c6c73706163696e673d2230222063656c6c70616464696e673d223022206267636f6c6f723d2223666666666666223e0d0a3c74626f64793e0d0a3c74723e0d0a3c7464207374796c653d22636f6c6f723a20236666666666663b2220636f6c7370616e3d2232222076616c69676e3d22626f74746f6d22206865696768743d223330223ec2a03c2f74643e0d0a3c2f74723e0d0a3c74723e0d0a3c7464207374796c653d226c696e652d6865696768743a20333270783b2070616464696e672d6c6566743a20333070783b222076616c69676e3d22626173656c696e65223e5b636f6d70616e795f6c6f676f5d3c2f74643e0d0a3c2f74723e0d0a3c2f74626f64793e0d0a3c2f7461626c653e0d0a3c7461626c65207374796c653d226d617267696e2d746f703a20313570783b206d617267696e2d72696768743a20333070783b206d617267696e2d6c6566743a20333070783b20636f6c6f723a20233434343b206c696e652d6865696768743a20312e363b20666f6e742d73697a653a20313270783b20666f6e742d66616d696c793a20417269616c2c73616e732d73657269663b2220626f726465723d2230222077696474683d22353130222063656c6c73706163696e673d2230222063656c6c70616464696e673d223022206267636f6c6f723d2223666666666666223e0d0a3c74626f64793e0d0a3c74723e0d0a3c7464207374796c653d22626f726465722d746f703a20736f6c69642031707820236439643964393b20626f726465722d626f74746f6d3a20736f6c69642031707820236439643964393b2220636f6c7370616e3d2232223e0d0a3c646976207374796c653d2270616464696e673a203135707820303b223e48656c6c6f2c203c6272202f3e3c6272202f3e20596f752068617665207265636569766564206120726573706f6e736520666f72207468652072657669657720706f7374656420666f7220636f6d70616e79203c623e5b627573696e6573735f6e616d655d3c2f623e2e203c6272202f3e596f752063616e2076696577207468652072657669657720726573706f6e73652061743a205b7265766965775f6c696e6b5d2e3c6272202f3e3c6272202f3e205468616e6b20796f752c0d0a3c6469763e5b636f6d70616e795f6e616d655d205465616d3c2f6469763e0d0a3c2f6469763e0d0a3c2f74643e0d0a3c2f74723e0d0a3c2f74626f64793e0d0a3c2f7461626c653e0d0a3c7461626c65207374796c653d226c696e652d6865696768743a20312e353b20666f6e742d73697a653a20313270783b20666f6e742d66616d696c793a20417269616c2c73616e732d73657269663b206d617267696e2d72696768743a20333070783b206d617267696e2d6c6566743a20333070783b2220626f726465723d2230222077696474683d22353130222063656c6c73706163696e673d2230222063656c6c70616464696e673d223022206267636f6c6f723d2223666666666666223e0d0a3c74626f64793e0d0a3c7472207374796c653d22666f6e742d73697a653a20313170783b20636f6c6f723a20233939393939393b222076616c69676e3d226d6964646c65223e0d0a3c74643e5b6469726563746f72795f776562736974655d3c2f74643e0d0a3c74643e0d0a3c646976207374796c653d22666c6f61743a2072696768743b2070616464696e672d746f703a20313070783b223e5b636f6d70616e795f736f6369616c5f6e6574776f726b735d3c2f6469763e0d0a3c2f74643e0d0a3c2f74723e0d0a3c74723e0d0a3c7464207374796c653d22636f6c6f723a20236666666666663b2220636f6c7370616e3d223222206865696768743d223135223ec2a03c2f74643e0d0a3c2f74723e0d0a3c2f74626f64793e0d0a3c2f7461626c653e0d0a3c2f6469763e0d0a3c2f74643e0d0a3c2f74723e0d0a3c2f74626f64793e0d0a3c2f7461626c653e0d0a3c2f63656e7465723e3c2f74643e0d0a3c2f74723e0d0a3c2f74626f64793e0d0a3c2f7461626c653e0d0a3c2f6469763e, 1),
(4, 'Payment Receipt from [company_name]', 'Order E-mail', 'Order Email', 0x3c703e44656172205b637573746f6d65725f6e616d655d2c3c6272202f3e3c6272202f3e596f7572207061796d656e7420666f7220796f7572206f6e6c696e65206f7264657220706c61636564206f6e3c6272202f3e5b736974655f616464726573735d206f6e205b6f726465725f646174655d20686173206265656e20617070726f7665642e3c6272202f3e3c6272202f3e596f7572207061796d656e742069732063757272656e746c79206265696e672070726f6365737365642e204f726465722070726f63657373696e6720757375616c6c793c6272202f3e74616b6573206120666577206d696e757465732e3c6272202f3e3c6272202f3e2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a3c6272202f3ec2a0c2a0c2a0c2a0c2a0204f4e4c494e45204f52444552202d205041594d454e542044455441494c5320285041594d454e542052454345495054293c6272202f3e2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a3c6272202f3e3c6272202f3e576562736974653a205b736974655f616464726573735d3c6272202f3e4f72646572207265666572656e6365206e6f2e3a205b6f726465725f69645d3c6272202f3e5061796d656e74206d6574686f643a205b7061796d656e745f6d6574686f645d3c6272202f3e446174652f74696d653a5b6f726465725f646174655d3c6272202f3e4f726465722047656e6572616c20546f74616c3a205b746f74616c5f70726963655d3c6272202f3e3c6272202f3e2d2d2d2d2d2d3c6272202f3e50726f647563742f53657276696365206e616d653a5b736572766963655f6e616d655d3c6272202f3e50726963652f756e69743a205b756e69745f70726963655d3c6272202f3e54617865732028564154293a205b7461785f616d6f756e745d3c6272202f3e546f74616c3a205b746f74616c5f70726963655d3c6272202f3e3c6272202f3e2d2d2d2d2d2d3c6272202f3e3c6272202f3e4f7264657220737562746f74616c3a205b746f74616c5f70726963655d3c6272202f3e4f7264657220746f74616c3a205b746f74616c5f70726963655d3c6272202f3e3c6272202f3e42696c6c696e6720696e666f726d6174696f6e2069733a3c6272202f3e5b62696c6c696e675f696e666f726d6174696f6e5d3c6272202f3e3c6272202f3e2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a3c6272202f3e3c6272202f3e3c6272202f3e4265737420726567617264732c3c6272202f3e5b636f6d70616e795f6e616d655d3c2f703e, 1),
(5, 'You have been contacted on JBusinessDirectory', 'Contact E-Mail', 'Contact Email', 0x3c646976207374796c653d226d617267696e3a203070783b206261636b67726f756e642d636f6c6f723a20236634663366343b20666f6e742d66616d696c793a2048656c7665746963612c417269616c2c73616e732d73657269663b20666f6e742d73697a653a20313270783b223e0d0a3c7461626c6520626f726465723d2230222077696474683d2231303025222063656c6c73706163696e673d2230222063656c6c70616464696e673d223022206267636f6c6f723d2223463446334634223e0d0a3c74626f64793e0d0a3c74723e0d0a3c7464207374796c653d2270616464696e673a20313570783b223e3c63656e7465723e0d0a3c7461626c652077696474683d22353730222063656c6c73706163696e673d2230222063656c6c70616464696e673d22302220616c69676e3d2263656e74657222206267636f6c6f723d2223666666666666223e0d0a3c74626f64793e0d0a3c74723e0d0a3c746420616c69676e3d226c656674223e0d0a3c646976207374796c653d22626f726465723a20736f6c69642031707820236439643964393b223e0d0a3c7461626c65207374796c653d226c696e652d6865696768743a20312e363b20666f6e742d73697a653a20313270783b20666f6e742d66616d696c793a2048656c7665746963612c417269616c2c73616e732d73657269663b20626f726465723a20736f6c69642031707820236666666666663b20636f6c6f723a20233434343b2220626f726465723d2230222077696474683d2231303025222063656c6c73706163696e673d2230222063656c6c70616464696e673d223022206267636f6c6f723d2223666666666666223e0d0a3c74626f64793e0d0a3c74723e0d0a3c7464207374796c653d22636f6c6f723a20236666666666663b2220636f6c7370616e3d2232222076616c69676e3d22626f74746f6d22206865696768743d223330223ec2a03c2f74643e0d0a3c2f74723e0d0a3c74723e0d0a3c7464207374796c653d226c696e652d6865696768743a20333270783b2070616464696e672d6c6566743a20333070783b222076616c69676e3d22626173656c696e65223e5b636f6d70616e795f6c6f676f5d3c2f74643e0d0a3c2f74723e0d0a3c2f74626f64793e0d0a3c2f7461626c653e0d0a3c7461626c65207374796c653d226d617267696e2d746f703a20313570783b206d617267696e2d72696768743a20333070783b206d617267696e2d6c6566743a20333070783b20636f6c6f723a20233434343b206c696e652d6865696768743a20312e363b20666f6e742d73697a653a20313270783b20666f6e742d66616d696c793a20417269616c2c73616e732d73657269663b2220626f726465723d2230222077696474683d22353130222063656c6c73706163696e673d2230222063656c6c70616464696e673d223022206267636f6c6f723d2223666666666666223e0d0a3c74626f64793e0d0a3c74723e0d0a3c7464207374796c653d22626f726465722d746f703a20736f6c69642031707820236439643964393b20626f726465722d626f74746f6d3a20736f6c69642031707820236439643964393b2220636f6c7370616e3d2232223e0d0a3c646976207374796c653d2270616464696e673a203135707820303b223e48656c6c6f2c203c6272202f3e3c6272202f3e204e616d653a5b66697273745f6e616d655d205b6c6173745f6e616d655d3c6272202f3e452d6d61696c3a205b636f6e746163745f656d61696c5d3c6272202f3e3c6272202f3e5b636f6e746163745f656d61696c5f636f6e74656e745d3c6272202f3e3c6272202f3e205468616e6b20796f752c0d0a3c6469763e5b636f6d70616e795f6e616d655d205465616d3c2f6469763e0d0a3c2f6469763e0d0a3c2f74643e0d0a3c2f74723e0d0a3c2f74626f64793e0d0a3c2f7461626c653e0d0a3c7461626c65207374796c653d226c696e652d6865696768743a20312e353b20666f6e742d73697a653a20313270783b20666f6e742d66616d696c793a20417269616c2c73616e732d73657269663b206d617267696e2d72696768743a20333070783b206d617267696e2d6c6566743a20333070783b2220626f726465723d2230222077696474683d22353130222063656c6c73706163696e673d2230222063656c6c70616464696e673d223022206267636f6c6f723d2223666666666666223e0d0a3c74626f64793e0d0a3c7472207374796c653d22666f6e742d73697a653a20313170783b20636f6c6f723a20233939393939393b222076616c69676e3d226d6964646c65223e0d0a3c74643e5b6469726563746f72795f776562736974655d3c2f74643e0d0a3c74643e0d0a3c646976207374796c653d22666c6f61743a2072696768743b2070616464696e672d746f703a20313070783b223e5b636f6d70616e795f736f6369616c5f6e6574776f726b735d3c2f6469763e0d0a3c2f74643e0d0a3c2f74723e0d0a3c74723e0d0a3c7464207374796c653d22636f6c6f723a20236666666666663b2220636f6c7370616e3d223222206865696768743d223135223ec2a03c2f74643e0d0a3c2f74723e0d0a3c2f74626f64793e0d0a3c2f7461626c653e0d0a3c2f6469763e0d0a3c2f74643e0d0a3c2f74723e0d0a3c2f74626f64793e0d0a3c2f7461626c653e0d0a3c2f63656e7465723e3c2f74643e0d0a3c2f74723e0d0a3c2f74626f64793e0d0a3c2f7461626c653e0d0a3c2f6469763e, 0),
(6, 'A new review abuse was reported', 'Report Abuse', 'Report Abuse Email', 0x3c646976207374796c653d226d617267696e3a203070783b206261636b67726f756e642d636f6c6f723a20236634663366343b20666f6e742d66616d696c793a2048656c7665746963612c417269616c2c73616e732d73657269663b20666f6e742d73697a653a20313270783b223e0d0a3c7461626c6520626f726465723d2230222077696474683d2231303025222063656c6c73706163696e673d2230222063656c6c70616464696e673d223022206267636f6c6f723d2223463446334634223e0d0a3c74626f64793e0d0a3c74723e0d0a3c7464207374796c653d2270616464696e673a20313570783b223e3c63656e7465723e0d0a3c7461626c652077696474683d22353730222063656c6c73706163696e673d2230222063656c6c70616464696e673d22302220616c69676e3d2263656e74657222206267636f6c6f723d2223666666666666223e0d0a3c74626f64793e0d0a3c74723e0d0a3c746420616c69676e3d226c656674223e0d0a3c646976207374796c653d22626f726465723a20736f6c69642031707820236439643964393b223e0d0a3c7461626c65207374796c653d226c696e652d6865696768743a20312e363b20666f6e742d73697a653a20313270783b20666f6e742d66616d696c793a2048656c7665746963612c417269616c2c73616e732d73657269663b20626f726465723a20736f6c69642031707820236666666666663b20636f6c6f723a20233434343b2220626f726465723d2230222077696474683d2231303025222063656c6c73706163696e673d2230222063656c6c70616464696e673d223022206267636f6c6f723d2223666666666666223e0d0a3c74626f64793e0d0a3c74723e0d0a3c7464207374796c653d22636f6c6f723a20236666666666663b2220636f6c7370616e3d2232222076616c69676e3d22626f74746f6d22206865696768743d223330223ec2a03c2f74643e0d0a3c2f74723e0d0a3c74723e0d0a3c7464207374796c653d226c696e652d6865696768743a20333270783b2070616464696e672d6c6566743a20333070783b222076616c69676e3d22626173656c696e65223e5b636f6d70616e795f6c6f676f5d3c2f74643e0d0a3c2f74723e0d0a3c2f74626f64793e0d0a3c2f7461626c653e0d0a3c7461626c65207374796c653d226d617267696e2d746f703a20313570783b206d617267696e2d72696768743a20333070783b206d617267696e2d6c6566743a20333070783b20636f6c6f723a20233434343b206c696e652d6865696768743a20312e363b20666f6e742d73697a653a20313270783b20666f6e742d66616d696c793a20417269616c2c73616e732d73657269663b2220626f726465723d2230222077696474683d22353130222063656c6c73706163696e673d2230222063656c6c70616464696e673d223022206267636f6c6f723d2223666666666666223e0d0a3c74626f64793e0d0a3c74723e0d0a3c7464207374796c653d22626f726465722d746f703a20736f6c69642031707820236439643964393b20626f726465722d626f74746f6d3a20736f6c69642031707820236439643964393b2220636f6c7370616e3d2232223e0d0a3c646976207374796c653d2270616464696e673a203135707820303b223e48656c6c6f2c203c6272202f3e3c6272202f3e2041206e657720616275736520776173207265706f7274656420666f722074686520726576696577c2a03c7374726f6e673e5b7265766965775f6e616d655d3c2f7374726f6e673e2c20666f7220746865205b627573696e6573735f6e616d655d2e3c6272202f3e20596f752063616e207669657720746865207265766965772061743a205b7265766965775f6c696e6b5d3c6272202f3e20452d6d61696c3a205b636f6e746163745f656d61696c5d3c6272202f3e3c6272202f3e3c623e4162757365206465736372697074696f6e3a3c2f623e3c6272202f3e5b61627573655f6465736372697074696f6e5d203c6272202f3e3c6272202f3e205468616e6b20796f752c0d0a3c6469763e5b636f6d70616e795f6e616d655d205465616d3c2f6469763e0d0a3c2f6469763e0d0a3c2f74643e0d0a3c2f74723e0d0a3c2f74626f64793e0d0a3c2f7461626c653e0d0a3c7461626c65207374796c653d226c696e652d6865696768743a20312e353b20666f6e742d73697a653a20313270783b20666f6e742d66616d696c793a20417269616c2c73616e732d73657269663b206d617267696e2d72696768743a20333070783b206d617267696e2d6c6566743a20333070783b2220626f726465723d2230222077696474683d22353130222063656c6c73706163696e673d2230222063656c6c70616464696e673d223022206267636f6c6f723d2223666666666666223e0d0a3c74626f64793e0d0a3c7472207374796c653d22666f6e742d73697a653a20313170783b20636f6c6f723a20233939393939393b222076616c69676e3d226d6964646c65223e0d0a3c74643e5b6469726563746f72795f776562736974655d3c2f74643e0d0a3c74643e0d0a3c646976207374796c653d22666c6f61743a2072696768743b2070616464696e672d746f703a20313070783b223e5b636f6d70616e795f736f6369616c5f6e6574776f726b735d3c2f6469763e0d0a3c2f74643e0d0a3c2f74723e0d0a3c74723e0d0a3c7464207374796c653d22636f6c6f723a20236666666666663b2220636f6c7370616e3d223222206865696768743d223135223ec2a03c2f74643e0d0a3c2f74723e0d0a3c2f74626f64793e0d0a3c2f7461626c653e0d0a3c2f6469763e0d0a3c2f74643e0d0a3c2f74723e0d0a3c2f74626f64793e0d0a3c2f7461626c653e0d0a3c2f63656e7465723e3c2f74643e0d0a3c2f74723e0d0a3c2f74626f64793e0d0a3c2f7461626c653e0d0a3c2f6469763e, 0),
(7, 'Your business listing is about to expire', 'Expiration Notification', 'Expiration Notification Email', 0x3c646976207374796c653d226d617267696e3a203070783b206261636b67726f756e642d636f6c6f723a20236634663366343b20666f6e742d66616d696c793a2048656c7665746963612c417269616c2c73616e732d73657269663b20666f6e742d73697a653a20313270783b223e0d0a3c7461626c6520626f726465723d2230222077696474683d2231303025222063656c6c73706163696e673d2230222063656c6c70616464696e673d223022206267636f6c6f723d2223463446334634223e0d0a3c74626f64793e0d0a3c74723e0d0a3c7464207374796c653d2270616464696e673a20313570783b223e3c63656e7465723e0d0a3c7461626c652077696474683d22353730222063656c6c73706163696e673d2230222063656c6c70616464696e673d22302220616c69676e3d2263656e74657222206267636f6c6f723d2223666666666666223e0d0a3c74626f64793e0d0a3c74723e0d0a3c746420616c69676e3d226c656674223e0d0a3c646976207374796c653d22626f726465723a20736f6c69642031707820236439643964393b223e0d0a3c7461626c65207374796c653d226c696e652d6865696768743a20312e363b20666f6e742d73697a653a20313270783b20666f6e742d66616d696c793a2048656c7665746963612c417269616c2c73616e732d73657269663b20626f726465723a20736f6c69642031707820236666666666663b20636f6c6f723a20233434343b2220626f726465723d2230222077696474683d2231303025222063656c6c73706163696e673d2230222063656c6c70616464696e673d223022206267636f6c6f723d2223666666666666223e0d0a3c74626f64793e0d0a3c74723e0d0a3c7464207374796c653d22636f6c6f723a20236666666666663b2220636f6c7370616e3d2232222076616c69676e3d22626f74746f6d22206865696768743d223330223ec2a03c2f74643e0d0a3c2f74723e0d0a3c74723e0d0a3c7464207374796c653d226c696e652d6865696768743a20333270783b2070616464696e672d6c6566743a20333070783b222076616c69676e3d22626173656c696e65223e5b636f6d70616e795f6c6f676f5d3c2f74643e0d0a3c2f74723e0d0a3c2f74626f64793e0d0a3c2f7461626c653e0d0a3c7461626c65207374796c653d226d617267696e2d746f703a20313570783b206d617267696e2d72696768743a20333070783b206d617267696e2d6c6566743a20333070783b20636f6c6f723a20233434343b206c696e652d6865696768743a20312e363b20666f6e742d73697a653a20313270783b20666f6e742d66616d696c793a20417269616c2c73616e732d73657269663b2220626f726465723d2230222077696474683d22353130222063656c6c73706163696e673d2230222063656c6c70616464696e673d223022206267636f6c6f723d2223666666666666223e0d0a3c74626f64793e0d0a3c74723e0d0a3c7464207374796c653d22626f726465722d746f703a20736f6c69642031707820236439643964393b20626f726465722d626f74746f6d3a20736f6c69642031707820236439643964393b2220636f6c7370616e3d2232223e0d0a3c646976207374796c653d2270616464696e673a203135707820303b223e48656c6c6f2c203c6272202f3e3c6272202f3e20596f757220627573696e657373206c697374696e672077697468206e616d65205b627573696e6573735f6e616d655d2069732061626f757420746f2065787069726520696e205b6578705f646179735d20646179732e3c6272202f3e596f752063616e20657874656e642074686520627573696e657373206c697374696e6720627920636c69636b696e672022457874656e6420706572696f6422206f6e20796f757220627573696e657373206c697374696e672064657461696c732e3c6272202f3e3c6272202f3e205468616e6b20796f752c0d0a3c6469763e5b636f6d70616e795f6e616d655d205465616d3c2f6469763e0d0a3c2f6469763e0d0a3c2f74643e0d0a3c2f74723e0d0a3c2f74626f64793e0d0a3c2f7461626c653e0d0a3c7461626c65207374796c653d226c696e652d6865696768743a20312e353b20666f6e742d73697a653a20313270783b20666f6e742d66616d696c793a20417269616c2c73616e732d73657269663b206d617267696e2d72696768743a20333070783b206d617267696e2d6c6566743a20333070783b2220626f726465723d2230222077696474683d22353130222063656c6c73706163696e673d2230222063656c6c70616464696e673d223022206267636f6c6f723d2223666666666666223e0d0a3c74626f64793e0d0a3c7472207374796c653d22666f6e742d73697a653a20313170783b20636f6c6f723a20233939393939393b222076616c69676e3d226d6964646c65223e0d0a3c74643e5b6469726563746f72795f776562736974655d3c2f74643e0d0a3c74643e0d0a3c646976207374796c653d22666c6f61743a2072696768743b2070616464696e672d746f703a20313070783b223e5b636f6d70616e795f736f6369616c5f6e6574776f726b735d3c2f6469763e0d0a3c2f74643e0d0a3c2f74723e0d0a3c74723e0d0a3c7464207374796c653d22636f6c6f723a20236666666666663b2220636f6c7370616e3d223222206865696768743d223135223ec2a03c2f74643e0d0a3c2f74723e0d0a3c2f74626f64793e0d0a3c2f7461626c653e0d0a3c2f6469763e0d0a3c2f74643e0d0a3c2f74723e0d0a3c2f74626f64793e0d0a3c2f7461626c653e0d0a3c2f63656e7465723e3c2f74643e0d0a3c2f74723e0d0a3c2f74626f64793e0d0a3c2f7461626c653e0d0a3c2f6469763e, 0),
(8, 'New Business Listing', 'New Business Listing Notification', 'New Company Notification Email', 0x3c646976207374796c653d226d617267696e3a203070783b206261636b67726f756e642d636f6c6f723a20236634663366343b20666f6e742d66616d696c793a2048656c7665746963612c417269616c2c73616e732d73657269663b20666f6e742d73697a653a20313270783b223e0d0a3c7461626c6520626f726465723d2230222077696474683d2231303025222063656c6c73706163696e673d2230222063656c6c70616464696e673d223022206267636f6c6f723d2223463446334634223e0d0a3c74626f64793e0d0a3c74723e0d0a3c7464207374796c653d2270616464696e673a20313570783b223e3c63656e7465723e0d0a3c7461626c652077696474683d22353730222063656c6c73706163696e673d2230222063656c6c70616464696e673d22302220616c69676e3d2263656e74657222206267636f6c6f723d2223666666666666223e0d0a3c74626f64793e0d0a3c74723e0d0a3c746420616c69676e3d226c656674223e0d0a3c646976207374796c653d22626f726465723a20736f6c69642031707820236439643964393b223e0d0a3c7461626c65207374796c653d226c696e652d6865696768743a20312e363b20666f6e742d73697a653a20313270783b20666f6e742d66616d696c793a2048656c7665746963612c417269616c2c73616e732d73657269663b20626f726465723a20736f6c69642031707820236666666666663b20636f6c6f723a20233434343b2220626f726465723d2230222077696474683d2231303025222063656c6c73706163696e673d2230222063656c6c70616464696e673d223022206267636f6c6f723d2223666666666666223e0d0a3c74626f64793e0d0a3c74723e0d0a3c7464207374796c653d22636f6c6f723a20236666666666663b2220636f6c7370616e3d2232222076616c69676e3d22626f74746f6d22206865696768743d223330223ec2a03c2f74643e0d0a3c2f74723e0d0a3c74723e0d0a3c7464207374796c653d226c696e652d6865696768743a20323570783b2070616464696e672d6c6566743a20333070783b222076616c69676e3d22626173656c696e65223e5b636f6d70616e795f6c6f676f5d3c2f74643e0d0a3c2f74723e0d0a3c2f74626f64793e0d0a3c2f7461626c653e0d0a3c7461626c65207374796c653d226d617267696e2d746f703a20313570783b206d617267696e2d72696768743a20333070783b206d617267696e2d6c6566743a20333070783b20636f6c6f723a20233434343b206c696e652d6865696768743a20312e363b20666f6e742d73697a653a20313270783b20666f6e742d66616d696c793a20417269616c2c73616e732d73657269663b2220626f726465723d2230222077696474683d22353130222063656c6c73706163696e673d2230222063656c6c70616464696e673d223022206267636f6c6f723d2223666666666666223e0d0a3c74626f64793e0d0a3c74723e0d0a3c7464207374796c653d22626f726465722d746f703a20736f6c69642031707820236439643964393b20626f726465722d626f74746f6d3a20736f6c69642031707820236439643964393b2220636f6c7370616e3d2232223e0d0a3c646976207374796c653d2270616464696e673a203135707820303b223e48656c6c6f2c203c6272202f3e3c6272202f3e2041206e657720627573696e657373206c697374696e67203c623e205b627573696e6573735f6e616d655d203c2f623e20776173206164646564206f6e20796f7572206469726563746f72792e3c6272202f3e3c6272202f3e0d0a3c7461626c65207374796c653d2270616464696e673a203570783b22206267636f6c6f723d2223464146394641223e0d0a3c74626f64793e0d0a3c74723e0d0a3c7464207374796c653d226c696e652d6865696768743a203070783b2070616464696e672d72696768743a20313070783b2220726f777370616e3d2235222076616c69676e3d226d6964646c65223e5b627573696e6573735f6c6f676f5d3c2f74643e0d0a3c74643e3c623e205b627573696e6573735f6e616d655d203c2f623e3c2f74643e0d0a3c2f74723e0d0a3c74723e0d0a3c74643e5b627573696e6573735f616464726573735d3c2f74643e0d0a3c2f74723e0d0a3c74723e0d0a3c74643e5b627573696e6573735f63617465676f72795d3c2f74643e0d0a3c2f74723e0d0a3c74723e0d0a3c74643e5b627573696e6573735f776562736974655d3c2f74643e0d0a3c2f74723e0d0a3c74723e0d0a3c74643e5b627573696e6573735f636f6e746163745f706572736f6e5d3c2f74643e0d0a3c2f74723e0d0a3c2f74626f64793e0d0a3c2f7461626c653e0d0a3c6272202f3e3c6272202f3e205468616e6b20796f752c0d0a3c6469763e5b636f6d70616e795f6e616d655d205465616d3c2f6469763e0d0a3c2f6469763e0d0a3c2f74643e0d0a3c2f74723e0d0a3c2f74626f64793e0d0a3c2f7461626c653e0d0a3c7461626c65207374796c653d226c696e652d6865696768743a20312e353b20666f6e742d73697a653a20313270783b20666f6e742d66616d696c793a20417269616c2c73616e732d73657269663b206d617267696e2d72696768743a20333070783b206d617267696e2d6c6566743a20333070783b2220626f726465723d2230222077696474683d22353130222063656c6c73706163696e673d2230222063656c6c70616464696e673d223022206267636f6c6f723d2223666666666666223e0d0a3c74626f64793e0d0a3c7472207374796c653d22666f6e742d73697a653a20313170783b20636f6c6f723a20233939393939393b222076616c69676e3d226d6964646c65223e0d0a3c74643e5b6469726563746f72795f776562736974655d3c2f74643e0d0a3c74643e0d0a3c646976207374796c653d22666c6f61743a2072696768743b2070616464696e672d746f703a20313070783b223e5b636f6d70616e795f736f6369616c5f6e6574776f726b735d3c2f6469763e0d0a3c2f74643e0d0a3c2f74723e0d0a3c74723e0d0a3c7464207374796c653d22636f6c6f723a20236666666666663b2220636f6c7370616e3d223222206865696768743d223135223ec2a03c2f74643e0d0a3c2f74723e0d0a3c2f74626f64793e0d0a3c2f7461626c653e0d0a3c2f6469763e0d0a3c2f74643e0d0a3c2f74723e0d0a3c2f74626f64793e0d0a3c2f7461626c653e0d0a3c2f63656e7465723e3c2f74643e0d0a3c2f74723e0d0a3c2f74626f64793e0d0a3c2f7461626c653e0d0a3c2f6469763e, 0),
(9, 'Your business listing was approved', 'Business Listing Approval', 'Approve Email', 0x3c646976207374796c653d226d617267696e3a203070783b206261636b67726f756e642d636f6c6f723a20236634663366343b20666f6e742d66616d696c793a2048656c7665746963612c417269616c2c73616e732d73657269663b20666f6e742d73697a653a20313270783b223e0d0a3c7461626c6520626f726465723d2230222077696474683d2231303025222063656c6c73706163696e673d2230222063656c6c70616464696e673d223022206267636f6c6f723d2223463446334634223e0d0a3c74626f64793e0d0a3c74723e0d0a3c7464207374796c653d2270616464696e673a20313570783b223e3c63656e7465723e0d0a3c7461626c652077696474683d22353730222063656c6c73706163696e673d2230222063656c6c70616464696e673d22302220616c69676e3d2263656e74657222206267636f6c6f723d2223666666666666223e0d0a3c74626f64793e0d0a3c74723e0d0a3c746420616c69676e3d226c656674223e0d0a3c646976207374796c653d22626f726465723a20736f6c69642031707820236439643964393b223e0d0a3c7461626c65207374796c653d226c696e652d6865696768743a20312e363b20666f6e742d73697a653a20313270783b20666f6e742d66616d696c793a2048656c7665746963612c417269616c2c73616e732d73657269663b20626f726465723a20736f6c69642031707820236666666666663b20636f6c6f723a20233434343b2220626f726465723d2230222077696474683d2231303025222063656c6c73706163696e673d2230222063656c6c70616464696e673d223022206267636f6c6f723d2223666666666666223e0d0a3c74626f64793e0d0a3c74723e0d0a3c7464207374796c653d22636f6c6f723a20236666666666663b2220636f6c7370616e3d2232222076616c69676e3d22626f74746f6d22206865696768743d223330223ec2a03c2f74643e0d0a3c2f74723e0d0a3c74723e0d0a3c7464207374796c653d226c696e652d6865696768743a20333270783b2070616464696e672d6c6566743a20333070783b222076616c69676e3d22626173656c696e65223e5b636f6d70616e795f6c6f676f5d3c2f74643e0d0a3c2f74723e0d0a3c2f74626f64793e0d0a3c2f7461626c653e0d0a3c7461626c65207374796c653d226d617267696e2d746f703a20313570783b206d617267696e2d72696768743a20333070783b206d617267696e2d6c6566743a20333070783b20636f6c6f723a20233434343b206c696e652d6865696768743a20312e363b20666f6e742d73697a653a20313270783b20666f6e742d66616d696c793a20417269616c2c73616e732d73657269663b2220626f726465723d2230222077696474683d22353130222063656c6c73706163696e673d2230222063656c6c70616464696e673d223022206267636f6c6f723d2223666666666666223e0d0a3c74626f64793e0d0a3c74723e0d0a3c7464207374796c653d22626f726465722d746f703a20736f6c69642031707820236439643964393b20626f726465722d626f74746f6d3a20736f6c69642031707820236439643964393b2220636f6c7370616e3d2232223e0d0a3c646976207374796c653d2270616464696e673a203135707820303b223e48656c6c6f2c203c6272202f3e3c6272202f3e20596f757220627573696e657373206c697374696e672077697468206e616d65203c7374726f6e673e5b627573696e6573735f6e616d655d3c2f7374726f6e673e2077617320617070726f7665642062792061646d696e6973747261746f722e3c6272202f3e3c6272202f3e205468616e6b20796f752c0d0a3c6469763e5b636f6d70616e795f6e616d655d205465616d3c2f6469763e0d0a3c2f6469763e0d0a3c2f74643e0d0a3c2f74723e0d0a3c2f74626f64793e0d0a3c2f7461626c653e0d0a3c7461626c65207374796c653d226c696e652d6865696768743a20312e353b20666f6e742d73697a653a20313270783b20666f6e742d66616d696c793a20417269616c2c73616e732d73657269663b206d617267696e2d72696768743a20333070783b206d617267696e2d6c6566743a20333070783b2220626f726465723d2230222077696474683d22353130222063656c6c73706163696e673d2230222063656c6c70616464696e673d223022206267636f6c6f723d2223666666666666223e0d0a3c74626f64793e0d0a3c7472207374796c653d22666f6e742d73697a653a20313170783b20636f6c6f723a20233939393939393b222076616c69676e3d226d6964646c65223e0d0a3c74643e5b6469726563746f72795f776562736974655d3c2f74643e0d0a3c74643e0d0a3c646976207374796c653d22666c6f61743a2072696768743b2070616464696e672d746f703a20313070783b223e5b636f6d70616e795f736f6369616c5f6e6574776f726b735d3c2f6469763e0d0a3c2f74643e0d0a3c2f74723e0d0a3c74723e0d0a3c7464207374796c653d22636f6c6f723a20236666666666663b2220636f6c7370616e3d223222206865696768743d223135223ec2a03c2f74643e0d0a3c2f74723e0d0a3c2f74626f64793e0d0a3c2f7461626c653e0d0a3c2f6469763e0d0a3c2f74643e0d0a3c2f74723e0d0a3c2f74626f64793e0d0a3c2f7461626c653e0d0a3c2f63656e7465723e3c2f74643e0d0a3c2f74723e0d0a3c2f74626f64793e0d0a3c2f7461626c653e0d0a3c2f6469763e, 0),
(10, 'Payment details', 'Payment details', 'Payment Details Email', 0x3c703e44656172205b637573746f6d65725f6e616d655d2c3c6272202f3e3c6272202f3e596f7572206861766520706c6163656420616e206f7264657220666f72205b736572766963655f6e616d655d206f6e205b736974655f616464726573735d206f6e205b6f726465725f646174655d2e3c2f703e0d0a3c703e506c656173652066696e6420746865207061796d656e742064657461696c732062656c6c6f772e3c2f703e0d0a3c703e3c6272202f3e2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a3c6272202f3ec2a0c2a0c2a0c2a0c2a0205041594d454e542044455441494c533c6272202f3e2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a3c2f703e0d0a3c703e5b7061796d656e745f64657461696c735d3c2f703e0d0a3c703e3c6272202f3e2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a3c6272202f3ec2a0c2a0c2a0c2a0c2a0204f4e4c494e45204f524445522044455441494c533c6272202f3e2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a3c6272202f3e3c6272202f3e576562736974653a205b736974655f616464726573735d3c6272202f3e4f72646572207265666572656e6365206e6f2e3a205b6f726465725f69645d3c6272202f3e5061796d656e74206d6574686f643a205b7061796d656e745f6d6574686f645d3c6272202f3e446174652f74696d653a5b6f726465725f646174655d3c6272202f3e4f726465722047656e6572616c20546f74616c3a205b746f74616c5f70726963655d3c6272202f3e3c6272202f3e2d2d2d2d2d2d3c6272202f3e50726f647563742f53657276696365206e616d653a5b736572766963655f6e616d655d3c6272202f3e50726963652f756e69743a205b756e69745f70726963655d3c6272202f3e54617865732028564154293a205b7461785f616d6f756e745d3c6272202f3e546f74616c3a205b746f74616c5f70726963655d3c6272202f3e3c6272202f3e2d2d2d2d2d2d3c6272202f3e3c6272202f3e42696c6c696e6720696e666f726d6174696f6e2069733a3c6272202f3e5b62696c6c696e675f696e666f726d6174696f6e5d3c6272202f3e3c6272202f3e2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a3c6272202f3e3c6272202f3e4265737420726567617264732c3c6272202f3e5b636f6d70616e795f6e616d655d3c2f703e, 0),
(11, 'A new quote request was posted', 'Request Quote', 'Request Quote Email', 0x3c646976207374796c653d226d617267696e3a203070783b206261636b67726f756e642d636f6c6f723a20236634663366343b20666f6e742d66616d696c793a2048656c7665746963612c417269616c2c73616e732d73657269663b20666f6e742d73697a653a20313270783b223e0d0a3c7461626c6520626f726465723d2230222077696474683d2231303025222063656c6c73706163696e673d2230222063656c6c70616464696e673d223022206267636f6c6f723d2223463446334634223e0d0a3c74626f64793e0d0a3c74723e0d0a3c7464207374796c653d2270616464696e673a20313570783b223e3c63656e7465723e0d0a3c7461626c652077696474683d22353730222063656c6c73706163696e673d2230222063656c6c70616464696e673d22302220616c69676e3d2263656e74657222206267636f6c6f723d2223666666666666223e0d0a3c74626f64793e0d0a3c74723e0d0a3c746420616c69676e3d226c656674223e0d0a3c646976207374796c653d22626f726465723a20736f6c69642031707820236439643964393b223e0d0a3c7461626c65207374796c653d226c696e652d6865696768743a20312e363b20666f6e742d73697a653a20313270783b20666f6e742d66616d696c793a2048656c7665746963612c417269616c2c73616e732d73657269663b20626f726465723a20736f6c69642031707820236666666666663b20636f6c6f723a20233434343b2220626f726465723d2230222077696474683d2231303025222063656c6c73706163696e673d2230222063656c6c70616464696e673d223022206267636f6c6f723d2223666666666666223e0d0a3c74626f64793e0d0a3c74723e0d0a3c7464207374796c653d22636f6c6f723a20236666666666663b2220636f6c7370616e3d2232222076616c69676e3d22626f74746f6d22206865696768743d223330223ec2a03c2f74643e0d0a3c2f74723e0d0a3c74723e0d0a3c7464207374796c653d226c696e652d6865696768743a20333270783b2070616464696e672d6c6566743a20333070783b222076616c69676e3d22626173656c696e65223e5b636f6d70616e795f6c6f676f5d3c2f74643e0d0a3c2f74723e0d0a3c2f74626f64793e0d0a3c2f7461626c653e0d0a3c7461626c65207374796c653d226d617267696e2d746f703a20313570783b206d617267696e2d72696768743a20333070783b206d617267696e2d6c6566743a20333070783b20636f6c6f723a20233434343b206c696e652d6865696768743a20312e363b20666f6e742d73697a653a20313270783b20666f6e742d66616d696c793a20417269616c2c73616e732d73657269663b2220626f726465723d2230222077696474683d22353130222063656c6c73706163696e673d2230222063656c6c70616464696e673d223022206267636f6c6f723d2223666666666666223e0d0a3c74626f64793e0d0a3c74723e0d0a3c7464207374796c653d22626f726465722d746f703a20736f6c69642031707820236439643964393b20626f726465722d626f74746f6d3a20736f6c69642031707820236439643964393b2220636f6c7370616e3d2232223e0d0a3c646976207374796c653d2270616464696e673a203135707820303b223e48656c6c6f2c203c6272202f3e3c6272202f3e2041206e65772071756f746520726571756573742077617320706f73746564206f6e205b6469726563746f72795f776562736974655d2e3c6272202f3e4e616d653a3c623e5b66697273745f6e616d655d205b6c6173745f6e616d655d3c2f623e3c6272202f3e452d6d61696c3a205b636f6e746163745f656d61696c5d3c6272202f3e43617465676f72793a205b63617465676f72795d3c6272202f3e3c6272202f3e3c623e5265717565737420636f6e74656e743c2f623e3c6272202f3e5b636f6e746163745f656d61696c5f636f6e74656e745d3c6272202f3e3c6272202f3e205468616e6b20796f752c0d0a3c6469763e5b636f6d70616e795f6e616d655d205465616d3c2f6469763e0d0a3c2f6469763e0d0a3c2f74643e0d0a3c2f74723e0d0a3c2f74626f64793e0d0a3c2f7461626c653e0d0a3c7461626c65207374796c653d226c696e652d6865696768743a20312e353b20666f6e742d73697a653a20313270783b20666f6e742d66616d696c793a20417269616c2c73616e732d73657269663b206d617267696e2d72696768743a20333070783b206d617267696e2d6c6566743a20333070783b2220626f726465723d2230222077696474683d22353130222063656c6c73706163696e673d2230222063656c6c70616464696e673d223022206267636f6c6f723d2223666666666666223e0d0a3c74626f64793e0d0a3c7472207374796c653d22666f6e742d73697a653a20313170783b20636f6c6f723a20233939393939393b222076616c69676e3d226d6964646c65223e0d0a3c74643e5b6469726563746f72795f776562736974655d3c2f74643e0d0a3c74643e0d0a3c646976207374796c653d22666c6f61743a2072696768743b2070616464696e672d746f703a20313070783b223e5b636f6d70616e795f736f6369616c5f6e6574776f726b735d3c2f6469763e0d0a3c2f74643e0d0a3c2f74723e0d0a3c74723e0d0a3c7464207374796c653d22636f6c6f723a20236666666666663b2220636f6c7370616e3d223222206865696768743d223135223ec2a03c2f74643e0d0a3c2f74723e0d0a3c2f74626f64793e0d0a3c2f7461626c653e0d0a3c2f6469763e0d0a3c2f74643e0d0a3c2f74723e0d0a3c2f74626f64793e0d0a3c2f7461626c653e0d0a3c2f63656e7465723e3c2f74643e0d0a3c2f74723e0d0a3c2f74626f64793e0d0a3c2f7461626c653e0d0a3c2f6469763e0d0a3c703ec2a03c2f703e, 0),
(12, 'Your business was added on our directory', 'Listing creation notification', 'Listing Creation Notification', 0x3c646976207374796c653d226d617267696e3a203070783b206261636b67726f756e642d636f6c6f723a20236634663366343b20666f6e742d66616d696c793a2048656c7665746963612c417269616c2c73616e732d73657269663b20666f6e742d73697a653a20313270783b223e0d0a3c7461626c6520626f726465723d2230222077696474683d2231303025222063656c6c73706163696e673d2230222063656c6c70616464696e673d223022206267636f6c6f723d2223463446334634223e0d0a3c74626f64793e0d0a3c74723e0d0a3c7464207374796c653d2270616464696e673a20313570783b223e3c63656e7465723e0d0a3c7461626c652077696474683d22353730222063656c6c73706163696e673d2230222063656c6c70616464696e673d22302220616c69676e3d2263656e74657222206267636f6c6f723d2223666666666666223e0d0a3c74626f64793e0d0a3c74723e0d0a3c746420616c69676e3d226c656674223e0d0a3c646976207374796c653d22626f726465723a20736f6c69642031707820236439643964393b223e0d0a3c7461626c65207374796c653d226c696e652d6865696768743a20312e363b20666f6e742d73697a653a20313270783b20666f6e742d66616d696c793a2048656c7665746963612c417269616c2c73616e732d73657269663b20626f726465723a20736f6c69642031707820236666666666663b20636f6c6f723a20233434343b2220626f726465723d2230222077696474683d2231303025222063656c6c73706163696e673d2230222063656c6c70616464696e673d223022206267636f6c6f723d2223666666666666223e0d0a3c74626f64793e0d0a3c74723e0d0a3c7464207374796c653d22636f6c6f723a20236666666666663b2220636f6c7370616e3d2232222076616c69676e3d22626f74746f6d22206865696768743d223330223ec2a03c2f74643e0d0a3c2f74723e0d0a3c74723e0d0a3c7464207374796c653d226c696e652d6865696768743a20323570783b2070616464696e672d6c6566743a20333070783b222076616c69676e3d22626173656c696e65223e5b636f6d70616e795f6c6f676f5d3c2f74643e0d0a3c2f74723e0d0a3c2f74626f64793e0d0a3c2f7461626c653e0d0a3c7461626c65207374796c653d226d617267696e2d746f703a20313570783b206d617267696e2d72696768743a20333070783b206d617267696e2d6c6566743a20333070783b20636f6c6f723a20233434343b206c696e652d6865696768743a20312e363b20666f6e742d73697a653a20313270783b20666f6e742d66616d696c793a20417269616c2c73616e732d73657269663b2220626f726465723d2230222077696474683d22353130222063656c6c73706163696e673d2230222063656c6c70616464696e673d223022206267636f6c6f723d2223666666666666223e0d0a3c74626f64793e0d0a3c74723e0d0a3c7464207374796c653d22626f726465722d746f703a20736f6c69642031707820236439643964393b20626f726465722d626f74746f6d3a20736f6c69642031707820236439643964393b2220636f6c7370616e3d2232223e0d0a3c646976207374796c653d2270616464696e673a203135707820303b223e48656c6c6f2c203c6272202f3e3c6272202f3e20596f757220627573696e657373203c623e205b627573696e6573735f6e616d655d203c2f623e20776173206164646564206f6e206f7572206469726563746f72792e3c6272202f3e3c6272202f3e0d0a3c7461626c65207374796c653d2270616464696e673a203570783b22206267636f6c6f723d2223464146394641223e0d0a3c74626f64793e0d0a3c74723e0d0a3c7464207374796c653d226c696e652d6865696768743a203070783b2070616464696e672d72696768743a20313070783b2220726f777370616e3d2235222076616c69676e3d226d6964646c65223e5b627573696e6573735f6c6f676f5d3c2f74643e0d0a3c74643e3c623e205b627573696e6573735f6e616d655d203c2f623e3c2f74643e0d0a3c2f74723e0d0a3c74723e0d0a3c74643e5b627573696e6573735f616464726573735d3c2f74643e0d0a3c2f74723e0d0a3c74723e0d0a3c74643e5b627573696e6573735f63617465676f72795d3c2f74643e0d0a3c2f74723e0d0a3c74723e0d0a3c74643e5b627573696e6573735f776562736974655d3c2f74643e0d0a3c2f74723e0d0a3c74723e0d0a3c74643e5b627573696e6573735f636f6e746163745f706572736f6e5d3c2f74643e0d0a3c2f74723e0d0a3c2f74626f64793e0d0a3c2f7461626c653e0d0a3c6272202f3e3c6272202f3e205468616e6b20796f752c0d0a3c6469763e5b636f6d70616e795f6e616d655d205465616d3c2f6469763e0d0a3c2f6469763e0d0a3c2f74643e0d0a3c2f74723e0d0a3c2f74626f64793e0d0a3c2f7461626c653e0d0a3c7461626c65207374796c653d226c696e652d6865696768743a20312e353b20666f6e742d73697a653a20313270783b20666f6e742d66616d696c793a20417269616c2c73616e732d73657269663b206d617267696e2d72696768743a20333070783b206d617267696e2d6c6566743a20333070783b2220626f726465723d2230222077696474683d22353130222063656c6c73706163696e673d2230222063656c6c70616464696e673d223022206267636f6c6f723d2223666666666666223e0d0a3c74626f64793e0d0a3c7472207374796c653d22666f6e742d73697a653a20313170783b20636f6c6f723a20233939393939393b222076616c69676e3d226d6964646c65223e0d0a3c74643e5b6469726563746f72795f776562736974655d3c2f74643e0d0a3c74643e0d0a3c646976207374796c653d22666c6f61743a2072696768743b2070616464696e672d746f703a20313070783b223e5b636f6d70616e795f736f6369616c5f6e6574776f726b735d3c2f6469763e0d0a3c2f74643e0d0a3c2f74723e0d0a3c74723e0d0a3c7464207374796c653d22636f6c6f723a20236666666666663b2220636f6c7370616e3d223222206865696768743d223135223ec2a03c2f74643e0d0a3c2f74723e0d0a3c2f74626f64793e0d0a3c2f7461626c653e0d0a3c2f6469763e0d0a3c2f74643e0d0a3c2f74723e0d0a3c2f74626f64793e0d0a3c2f7461626c653e0d0a3c2f63656e7465723e3c2f74643e0d0a3c2f74723e0d0a3c2f74626f64793e0d0a3c2f7461626c653e0d0a3c2f6469763e, 0),
(13, 'Your claim was approved', 'Positive claim response', 'Claim Response Email', 0x3c646976207374796c653d226d617267696e3a203070783b206261636b67726f756e642d636f6c6f723a20236634663366343b20666f6e742d66616d696c793a2048656c7665746963612c417269616c2c73616e732d73657269663b20666f6e742d73697a653a20313270783b223e0d0a3c7461626c6520626f726465723d2230222077696474683d2231303025222063656c6c73706163696e673d2230222063656c6c70616464696e673d223022206267636f6c6f723d2223463446334634223e0d0a3c74626f64793e0d0a3c74723e0d0a3c7464207374796c653d2270616464696e673a20313570783b223e3c63656e7465723e0d0a3c7461626c652077696474683d22353730222063656c6c73706163696e673d2230222063656c6c70616464696e673d22302220616c69676e3d2263656e74657222206267636f6c6f723d2223666666666666223e0d0a3c74626f64793e0d0a3c74723e0d0a3c746420616c69676e3d226c656674223e0d0a3c646976207374796c653d22626f726465723a20736f6c69642031707820236439643964393b223e0d0a3c7461626c65207374796c653d226c696e652d6865696768743a20312e363b20666f6e742d73697a653a20313270783b20666f6e742d66616d696c793a2048656c7665746963612c417269616c2c73616e732d73657269663b20626f726465723a20736f6c69642031707820236666666666663b20636f6c6f723a20233434343b2220626f726465723d2230222077696474683d2231303025222063656c6c73706163696e673d2230222063656c6c70616464696e673d223022206267636f6c6f723d2223666666666666223e0d0a3c74626f64793e0d0a3c74723e0d0a3c7464207374796c653d22636f6c6f723a20236666666666663b2220636f6c7370616e3d2232222076616c69676e3d22626f74746f6d22206865696768743d223330223ec2a03c2f74643e0d0a3c2f74723e0d0a3c74723e0d0a3c7464207374796c653d226c696e652d6865696768743a20333270783b2070616464696e672d6c6566743a20333070783b222076616c69676e3d22626173656c696e65223e5b636f6d70616e795f6c6f676f5d3c2f74643e0d0a3c2f74723e0d0a3c2f74626f64793e0d0a3c2f7461626c653e0d0a3c7461626c65207374796c653d226d617267696e2d746f703a20313570783b206d617267696e2d72696768743a20333070783b206d617267696e2d6c6566743a20333070783b20636f6c6f723a20233434343b206c696e652d6865696768743a20312e363b20666f6e742d73697a653a20313270783b20666f6e742d66616d696c793a20417269616c2c73616e732d73657269663b2220626f726465723d2230222077696474683d22353130222063656c6c73706163696e673d2230222063656c6c70616464696e673d223022206267636f6c6f723d2223666666666666223e0d0a3c74626f64793e0d0a3c74723e0d0a3c7464207374796c653d22626f726465722d746f703a20736f6c69642031707820236439643964393b20626f726465722d626f74746f6d3a20736f6c69642031707820236439643964393b2220636f6c7370616e3d2232223e0d0a3c646976207374796c653d2270616464696e673a203135707820303b223e48656c6c6f2c203c6272202f3e3c6272202f3e20436f6e67726174756c6174696f6e732c20796f757220636c61696d20666f72206c697374696e67205b636c61696d65645f636f6d70616e795f6e616d655d20686173206265656e20617070726f7665642e3c6272202f3e3c6272202f3e205468616e6b20796f752c0d0a3c6469763e5b636f6d70616e795f6e616d655d205465616d3c2f6469763e0d0a3c2f6469763e0d0a3c2f74643e0d0a3c2f74723e0d0a3c2f74626f64793e0d0a3c2f7461626c653e0d0a3c7461626c65207374796c653d226c696e652d6865696768743a20312e353b20666f6e742d73697a653a20313270783b20666f6e742d66616d696c793a20417269616c2c73616e732d73657269663b206d617267696e2d72696768743a20333070783b206d617267696e2d6c6566743a20333070783b2220626f726465723d2230222077696474683d22353130222063656c6c73706163696e673d2230222063656c6c70616464696e673d223022206267636f6c6f723d2223666666666666223e0d0a3c74626f64793e0d0a3c7472207374796c653d22666f6e742d73697a653a20313170783b20636f6c6f723a20233939393939393b222076616c69676e3d226d6964646c65223e0d0a3c74643e5b6469726563746f72795f776562736974655d3c2f74643e0d0a3c74643e0d0a3c646976207374796c653d22666c6f61743a2072696768743b2070616464696e672d746f703a20313070783b223e5b636f6d70616e795f736f6369616c5f6e6574776f726b735d3c2f6469763e0d0a3c2f74643e0d0a3c2f74723e0d0a3c74723e0d0a3c7464207374796c653d22636f6c6f723a20236666666666663b2220636f6c7370616e3d223222206865696768743d223135223ec2a03c2f74643e0d0a3c2f74723e0d0a3c2f74626f64793e0d0a3c2f7461626c653e0d0a3c2f6469763e0d0a3c2f74643e0d0a3c2f74723e0d0a3c2f74626f64793e0d0a3c2f7461626c653e0d0a3c2f63656e7465723e3c2f74643e0d0a3c2f74723e0d0a3c2f74626f64793e0d0a3c2f7461626c653e0d0a3c2f6469763e, 0),
(14, 'You claim was not approved', 'Negative claim response', 'Claim Negative Response Email', 0x3c646976207374796c653d226d617267696e3a203070783b206261636b67726f756e642d636f6c6f723a20236634663366343b20666f6e742d66616d696c793a2048656c7665746963612c417269616c2c73616e732d73657269663b20666f6e742d73697a653a20313270783b223e0d0a3c7461626c6520626f726465723d2230222077696474683d2231303025222063656c6c73706163696e673d2230222063656c6c70616464696e673d223022206267636f6c6f723d2223463446334634223e0d0a3c74626f64793e0d0a3c74723e0d0a3c7464207374796c653d2270616464696e673a20313570783b223e3c63656e7465723e0d0a3c7461626c652077696474683d22353730222063656c6c73706163696e673d2230222063656c6c70616464696e673d22302220616c69676e3d2263656e74657222206267636f6c6f723d2223666666666666223e0d0a3c74626f64793e0d0a3c74723e0d0a3c746420616c69676e3d226c656674223e0d0a3c646976207374796c653d22626f726465723a20736f6c69642031707820236439643964393b223e0d0a3c7461626c65207374796c653d226c696e652d6865696768743a20312e363b20666f6e742d73697a653a20313270783b20666f6e742d66616d696c793a2048656c7665746963612c417269616c2c73616e732d73657269663b20626f726465723a20736f6c69642031707820236666666666663b20636f6c6f723a20233434343b2220626f726465723d2230222077696474683d2231303025222063656c6c73706163696e673d2230222063656c6c70616464696e673d223022206267636f6c6f723d2223666666666666223e0d0a3c74626f64793e0d0a3c74723e0d0a3c7464207374796c653d22636f6c6f723a20236666666666663b2220636f6c7370616e3d2232222076616c69676e3d22626f74746f6d22206865696768743d223330223ec2a03c2f74643e0d0a3c2f74723e0d0a3c74723e0d0a3c7464207374796c653d226c696e652d6865696768743a20333270783b2070616464696e672d6c6566743a20333070783b222076616c69676e3d22626173656c696e65223e5b636f6d70616e795f6c6f676f5d3c2f74643e0d0a3c2f74723e0d0a3c2f74626f64793e0d0a3c2f7461626c653e0d0a3c7461626c65207374796c653d226d617267696e2d746f703a20313570783b206d617267696e2d72696768743a20333070783b206d617267696e2d6c6566743a20333070783b20636f6c6f723a20233434343b206c696e652d6865696768743a20312e363b20666f6e742d73697a653a20313270783b20666f6e742d66616d696c793a20417269616c2c73616e732d73657269663b2220626f726465723d2230222077696474683d22353130222063656c6c73706163696e673d2230222063656c6c70616464696e673d223022206267636f6c6f723d2223666666666666223e0d0a3c74626f64793e0d0a3c74723e0d0a3c7464207374796c653d22626f726465722d746f703a20736f6c69642031707820236439643964393b20626f726465722d626f74746f6d3a20736f6c69642031707820236439643964393b2220636f6c7370616e3d2232223e0d0a3c646976207374796c653d2270616464696e673a203135707820303b223e48656c6c6f2c203c6272202f3e3c6272202f3e20596f757220636c61696d20666f72206c697374696e67203c623e5b636c61696d65645f636f6d70616e795f6e616d655d3c2f623e20776173206e6f7420617070726f7665642e3c6272202f3e3c6272202f3e205468616e6b20796f752c0d0a3c6469763e5b636f6d70616e795f6e616d655d205465616d3c2f6469763e0d0a3c2f6469763e0d0a3c2f74643e0d0a3c2f74723e0d0a3c2f74626f64793e0d0a3c2f7461626c653e0d0a3c7461626c65207374796c653d226c696e652d6865696768743a20312e353b20666f6e742d73697a653a20313270783b20666f6e742d66616d696c793a20417269616c2c73616e732d73657269663b206d617267696e2d72696768743a20333070783b206d617267696e2d6c6566743a20333070783b2220626f726465723d2230222077696474683d22353130222063656c6c73706163696e673d2230222063656c6c70616464696e673d223022206267636f6c6f723d2223666666666666223e0d0a3c74626f64793e0d0a3c7472207374796c653d22666f6e742d73697a653a20313170783b20636f6c6f723a20233939393939393b222076616c69676e3d226d6964646c65223e0d0a3c74643e5b6469726563746f72795f776562736974655d3c2f74643e0d0a3c74643e0d0a3c646976207374796c653d22666c6f61743a2072696768743b2070616464696e672d746f703a20313070783b223e5b636f6d70616e795f736f6369616c5f6e6574776f726b735d3c2f6469763e0d0a3c2f74643e0d0a3c2f74723e0d0a3c74723e0d0a3c7464207374796c653d22636f6c6f723a20236666666666663b2220636f6c7370616e3d223222206865696768743d223135223ec2a03c2f74643e0d0a3c2f74723e0d0a3c2f74626f64793e0d0a3c2f7461626c653e0d0a3c2f6469763e0d0a3c2f74643e0d0a3c2f74723e0d0a3c2f74626f64793e0d0a3c2f7461626c653e0d0a3c2f63656e7465723e3c2f74643e0d0a3c2f74723e0d0a3c2f74626f64793e0d0a3c2f7461626c653e0d0a3c2f6469763e, 0),
(15, 'A new offer was added on your directory', 'Offer Creation', 'Offer Creation Notification', 0x3c646976207374796c653d226d617267696e3a203070783b206261636b67726f756e642d636f6c6f723a20236634663366343b20666f6e742d66616d696c793a2048656c7665746963612c417269616c2c73616e732d73657269663b20666f6e742d73697a653a20313270783b223e0d0a3c7461626c6520626f726465723d2230222077696474683d2231303025222063656c6c73706163696e673d2230222063656c6c70616464696e673d223022206267636f6c6f723d2223463446334634223e0d0a3c74626f64793e0d0a3c74723e0d0a3c7464207374796c653d2270616464696e673a20313570783b223e3c63656e7465723e0d0a3c7461626c652077696474683d22353730222063656c6c73706163696e673d2230222063656c6c70616464696e673d22302220616c69676e3d2263656e74657222206267636f6c6f723d2223666666666666223e0d0a3c74626f64793e0d0a3c74723e0d0a3c746420616c69676e3d226c656674223e0d0a3c646976207374796c653d22626f726465723a20736f6c69642031707820236439643964393b223e0d0a3c7461626c65207374796c653d226c696e652d6865696768743a20312e363b20666f6e742d73697a653a20313270783b20666f6e742d66616d696c793a2048656c7665746963612c417269616c2c73616e732d73657269663b20626f726465723a20736f6c69642031707820236666666666663b20636f6c6f723a20233434343b2220626f726465723d2230222077696474683d2231303025222063656c6c73706163696e673d2230222063656c6c70616464696e673d223022206267636f6c6f723d2223666666666666223e0d0a3c74626f64793e0d0a3c74723e0d0a3c7464207374796c653d22636f6c6f723a20236666666666663b2220636f6c7370616e3d2232222076616c69676e3d22626f74746f6d22206865696768743d223330223ec2a03c2f74643e0d0a3c2f74723e0d0a3c74723e0d0a3c7464207374796c653d226c696e652d6865696768743a20333270783b2070616464696e672d6c6566743a20333070783b222076616c69676e3d22626173656c696e65223e5b636f6d70616e795f6c6f676f5d3c2f74643e0d0a3c2f74723e0d0a3c2f74626f64793e0d0a3c2f7461626c653e0d0a3c7461626c65207374796c653d226d617267696e2d746f703a20313570783b206d617267696e2d72696768743a20333070783b206d617267696e2d6c6566743a20333070783b20636f6c6f723a20233434343b206c696e652d6865696768743a20312e363b20666f6e742d73697a653a20313270783b20666f6e742d66616d696c793a20417269616c2c73616e732d73657269663b2220626f726465723d2230222077696474683d22353130222063656c6c73706163696e673d2230222063656c6c70616464696e673d223022206267636f6c6f723d2223666666666666223e0d0a3c74626f64793e0d0a3c74723e0d0a3c7464207374796c653d22626f726465722d746f703a20736f6c69642031707820236439643964393b20626f726465722d626f74746f6d3a20736f6c69642031707820236439643964393b2220636f6c7370616e3d2232223e0d0a3c646976207374796c653d2270616464696e673a203135707820303b223e48656c6c6f2c203c6272202f3e3c6272202f3e2041206e6577206f666665723c7374726f6e673e205b6f666665725f6e616d655d203c2f7374726f6e673e20686173206265656e206164646564206f6e20796f7572206469726563746f72792e3c6272202f3e3c6272202f3e205468616e6b20796f752c0d0a3c6469763e5b636f6d70616e795f6e616d655d205465616d3c2f6469763e0d0a3c2f6469763e0d0a3c2f74643e0d0a3c2f74723e0d0a3c2f74626f64793e0d0a3c2f7461626c653e0d0a3c7461626c65207374796c653d226c696e652d6865696768743a20312e353b20666f6e742d73697a653a20313270783b20666f6e742d66616d696c793a20417269616c2c73616e732d73657269663b206d617267696e2d72696768743a20333070783b206d617267696e2d6c6566743a20333070783b2220626f726465723d2230222077696474683d22353130222063656c6c73706163696e673d2230222063656c6c70616464696e673d223022206267636f6c6f723d2223666666666666223e0d0a3c74626f64793e0d0a3c7472207374796c653d22666f6e742d73697a653a20313170783b20636f6c6f723a20233939393939393b222076616c69676e3d226d6964646c65223e0d0a3c74643e5b6469726563746f72795f776562736974655d3c2f74643e0d0a3c74643e0d0a3c646976207374796c653d22666c6f61743a2072696768743b2070616464696e672d746f703a20313070783b223e5b636f6d70616e795f736f6369616c5f6e6574776f726b735d3c2f6469763e0d0a3c2f74643e0d0a3c2f74723e0d0a3c74723e0d0a3c7464207374796c653d22636f6c6f723a20236666666666663b2220636f6c7370616e3d223222206865696768743d223135223ec2a03c2f74643e0d0a3c2f74723e0d0a3c2f74626f64793e0d0a3c2f7461626c653e0d0a3c2f6469763e0d0a3c2f74643e0d0a3c2f74723e0d0a3c2f74626f64793e0d0a3c2f7461626c653e0d0a3c2f63656e7465723e3c2f74643e0d0a3c2f74723e0d0a3c2f74626f64793e0d0a3c2f7461626c653e0d0a3c2f6469763e0d0a3c703ec2a03c2f703e, 0),
(16, 'Your new offer was  approved', 'Offer Approval', 'Offer Approval Notification', 0x3c646976207374796c653d226d617267696e3a203070783b206261636b67726f756e642d636f6c6f723a20236634663366343b20666f6e742d66616d696c793a2048656c7665746963612c417269616c2c73616e732d73657269663b20666f6e742d73697a653a20313270783b223e0d0a3c7461626c6520626f726465723d2230222077696474683d2231303025222063656c6c73706163696e673d2230222063656c6c70616464696e673d223022206267636f6c6f723d2223463446334634223e0d0a3c74626f64793e0d0a3c74723e0d0a3c7464207374796c653d2270616464696e673a20313570783b223e3c63656e7465723e0d0a3c7461626c652077696474683d22353730222063656c6c73706163696e673d2230222063656c6c70616464696e673d22302220616c69676e3d2263656e74657222206267636f6c6f723d2223666666666666223e0d0a3c74626f64793e0d0a3c74723e0d0a3c746420616c69676e3d226c656674223e0d0a3c646976207374796c653d22626f726465723a20736f6c69642031707820236439643964393b223e0d0a3c7461626c65207374796c653d226c696e652d6865696768743a20312e363b20666f6e742d73697a653a20313270783b20666f6e742d66616d696c793a2048656c7665746963612c417269616c2c73616e732d73657269663b20626f726465723a20736f6c69642031707820236666666666663b20636f6c6f723a20233434343b2220626f726465723d2230222077696474683d2231303025222063656c6c73706163696e673d2230222063656c6c70616464696e673d223022206267636f6c6f723d2223666666666666223e0d0a3c74626f64793e0d0a3c74723e0d0a3c7464207374796c653d22636f6c6f723a20236666666666663b2220636f6c7370616e3d2232222076616c69676e3d22626f74746f6d22206865696768743d223330223ec2a03c2f74643e0d0a3c2f74723e0d0a3c74723e0d0a3c7464207374796c653d226c696e652d6865696768743a20333270783b2070616464696e672d6c6566743a20333070783b222076616c69676e3d22626173656c696e65223e5b636f6d70616e795f6c6f676f5d3c2f74643e0d0a3c2f74723e0d0a3c2f74626f64793e0d0a3c2f7461626c653e0d0a3c7461626c65207374796c653d226d617267696e2d746f703a20313570783b206d617267696e2d72696768743a20333070783b206d617267696e2d6c6566743a20333070783b20636f6c6f723a20233434343b206c696e652d6865696768743a20312e363b20666f6e742d73697a653a20313270783b20666f6e742d66616d696c793a20417269616c2c73616e732d73657269663b2220626f726465723d2230222077696474683d22353130222063656c6c73706163696e673d2230222063656c6c70616464696e673d223022206267636f6c6f723d2223666666666666223e0d0a3c74626f64793e0d0a3c74723e0d0a3c7464207374796c653d22626f726465722d746f703a20736f6c69642031707820236439643964393b20626f726465722d626f74746f6d3a20736f6c69642031707820236439643964393b2220636f6c7370616e3d2232223e0d0a3c646976207374796c653d2270616464696e673a203135707820303b223e48656c6c6f2c203c6272202f3e3c6272202f3e20596f7572206f66666572203c623e5b6f666665725f6e616d655d3c2f623e2077617320617070726f766564206279207468652061646d696e6973747261746f722e3c6272202f3e3c6272202f3e205468616e6b20796f752c0d0a3c6469763e5b636f6d70616e795f6e616d655d205465616d3c2f6469763e0d0a3c2f6469763e0d0a3c2f74643e0d0a3c2f74723e0d0a3c2f74626f64793e0d0a3c2f7461626c653e0d0a3c7461626c65207374796c653d226c696e652d6865696768743a20312e353b20666f6e742d73697a653a20313270783b20666f6e742d66616d696c793a20417269616c2c73616e732d73657269663b206d617267696e2d72696768743a20333070783b206d617267696e2d6c6566743a20333070783b2220626f726465723d2230222077696474683d22353130222063656c6c73706163696e673d2230222063656c6c70616464696e673d223022206267636f6c6f723d2223666666666666223e0d0a3c74626f64793e0d0a3c7472207374796c653d22666f6e742d73697a653a20313170783b20636f6c6f723a20233939393939393b222076616c69676e3d226d6964646c65223e0d0a3c74643e5b6469726563746f72795f776562736974655d3c2f74643e0d0a3c74643e0d0a3c646976207374796c653d22666c6f61743a2072696768743b2070616464696e672d746f703a20313070783b223e5b636f6d70616e795f736f6369616c5f6e6574776f726b735d3c2f6469763e0d0a3c2f74643e0d0a3c2f74723e0d0a3c74723e0d0a3c7464207374796c653d22636f6c6f723a20236666666666663b2220636f6c7370616e3d223222206865696768743d223135223ec2a03c2f74643e0d0a3c2f74723e0d0a3c2f74626f64793e0d0a3c2f7461626c653e0d0a3c2f6469763e0d0a3c2f74643e0d0a3c2f74723e0d0a3c2f74626f64793e0d0a3c2f7461626c653e0d0a3c2f63656e7465723e3c2f74643e0d0a3c2f74723e0d0a3c2f74626f64793e0d0a3c2f7461626c653e0d0a3c2f6469763e, 0),
(17, 'A new event has been added to your directory', 'Event Creation Notification', 'Event Creation Notification', 0x3c646976207374796c653d226d617267696e3a203070783b206261636b67726f756e642d636f6c6f723a20236634663366343b20666f6e742d66616d696c793a2048656c7665746963612c417269616c2c73616e732d73657269663b20666f6e742d73697a653a20313270783b223e0d0a3c7461626c6520626f726465723d2230222077696474683d2231303025222063656c6c73706163696e673d2230222063656c6c70616464696e673d223022206267636f6c6f723d2223463446334634223e0d0a3c74626f64793e0d0a3c74723e0d0a3c7464207374796c653d2270616464696e673a20313570783b223e3c63656e7465723e0d0a3c7461626c652077696474683d22353730222063656c6c73706163696e673d2230222063656c6c70616464696e673d22302220616c69676e3d2263656e74657222206267636f6c6f723d2223666666666666223e0d0a3c74626f64793e0d0a3c74723e0d0a3c746420616c69676e3d226c656674223e0d0a3c646976207374796c653d22626f726465723a20736f6c69642031707820236439643964393b223e0d0a3c7461626c65207374796c653d226c696e652d6865696768743a20312e363b20666f6e742d73697a653a20313270783b20666f6e742d66616d696c793a2048656c7665746963612c417269616c2c73616e732d73657269663b20626f726465723a20736f6c69642031707820236666666666663b20636f6c6f723a20233434343b2220626f726465723d2230222077696474683d2231303025222063656c6c73706163696e673d2230222063656c6c70616464696e673d223022206267636f6c6f723d2223666666666666223e0d0a3c74626f64793e0d0a3c74723e0d0a3c7464207374796c653d22636f6c6f723a20236666666666663b2220636f6c7370616e3d2232222076616c69676e3d22626f74746f6d22206865696768743d223330223ec2a03c2f74643e0d0a3c2f74723e0d0a3c74723e0d0a3c7464207374796c653d226c696e652d6865696768743a20333270783b2070616464696e672d6c6566743a20333070783b222076616c69676e3d22626173656c696e65223e5b636f6d70616e795f6c6f676f5d3c2f74643e0d0a3c2f74723e0d0a3c2f74626f64793e0d0a3c2f7461626c653e0d0a3c7461626c65207374796c653d226d617267696e2d746f703a20313570783b206d617267696e2d72696768743a20333070783b206d617267696e2d6c6566743a20333070783b20636f6c6f723a20233434343b206c696e652d6865696768743a20312e363b20666f6e742d73697a653a20313270783b20666f6e742d66616d696c793a20417269616c2c73616e732d73657269663b2220626f726465723d2230222077696474683d22353130222063656c6c73706163696e673d2230222063656c6c70616464696e673d223022206267636f6c6f723d2223666666666666223e0d0a3c74626f64793e0d0a3c74723e0d0a3c7464207374796c653d22626f726465722d746f703a20736f6c69642031707820236439643964393b20626f726465722d626f74746f6d3a20736f6c69642031707820236439643964393b2220636f6c7370616e3d2232223e0d0a3c646976207374796c653d2270616464696e673a203135707820303b223e48656c6c6f2c203c6272202f3e3c6272202f3e2041206e6577206576656e74203c7374726f6e673e5b6576656e745f6e616d655d3c2f7374726f6e673e20686173206265656e206164646564206f6e20796f7572206469726563746f72792e3c6272202f3e3c6272202f3e205468616e6b20796f752c0d0a3c6469763e5b636f6d70616e795f6e616d655d205465616d3c2f6469763e0d0a3c2f6469763e0d0a3c2f74643e0d0a3c2f74723e0d0a3c2f74626f64793e0d0a3c2f7461626c653e0d0a3c7461626c65207374796c653d226c696e652d6865696768743a20312e353b20666f6e742d73697a653a20313270783b20666f6e742d66616d696c793a20417269616c2c73616e732d73657269663b206d617267696e2d72696768743a20333070783b206d617267696e2d6c6566743a20333070783b2220626f726465723d2230222077696474683d22353130222063656c6c73706163696e673d2230222063656c6c70616464696e673d223022206267636f6c6f723d2223666666666666223e0d0a3c74626f64793e0d0a3c7472207374796c653d22666f6e742d73697a653a20313170783b20636f6c6f723a20233939393939393b222076616c69676e3d226d6964646c65223e0d0a3c74643e5b6469726563746f72795f776562736974655d3c2f74643e0d0a3c74643e0d0a3c646976207374796c653d22666c6f61743a2072696768743b2070616464696e672d746f703a20313070783b223e5b636f6d70616e795f736f6369616c5f6e6574776f726b735d3c2f6469763e0d0a3c2f74643e0d0a3c2f74723e0d0a3c74723e0d0a3c7464207374796c653d22636f6c6f723a20236666666666663b2220636f6c7370616e3d223222206865696768743d223135223ec2a03c2f74643e0d0a3c2f74723e0d0a3c2f74626f64793e0d0a3c2f7461626c653e0d0a3c2f6469763e0d0a3c2f74643e0d0a3c2f74723e0d0a3c2f74626f64793e0d0a3c2f7461626c653e0d0a3c2f63656e7465723e3c2f74643e0d0a3c2f74723e0d0a3c2f74626f64793e0d0a3c2f7461626c653e0d0a3c2f6469763e, 0),
(18, 'Your new event was published', 'Event Approval Notificaiton', 'Event Approval Notification', 0x3c646976207374796c653d226d617267696e3a203070783b206261636b67726f756e642d636f6c6f723a20236634663366343b20666f6e742d66616d696c793a2048656c7665746963612c417269616c2c73616e732d73657269663b20666f6e742d73697a653a20313270783b223e0d0a3c7461626c6520626f726465723d2230222077696474683d2231303025222063656c6c73706163696e673d2230222063656c6c70616464696e673d223022206267636f6c6f723d2223463446334634223e0d0a3c74626f64793e0d0a3c74723e0d0a3c7464207374796c653d2270616464696e673a20313570783b223e3c63656e7465723e0d0a3c7461626c652077696474683d22353730222063656c6c73706163696e673d2230222063656c6c70616464696e673d22302220616c69676e3d2263656e74657222206267636f6c6f723d2223666666666666223e0d0a3c74626f64793e0d0a3c74723e0d0a3c746420616c69676e3d226c656674223e0d0a3c646976207374796c653d22626f726465723a20736f6c69642031707820236439643964393b223e0d0a3c7461626c65207374796c653d226c696e652d6865696768743a20312e363b20666f6e742d73697a653a20313270783b20666f6e742d66616d696c793a2048656c7665746963612c417269616c2c73616e732d73657269663b20626f726465723a20736f6c69642031707820236666666666663b20636f6c6f723a20233434343b2220626f726465723d2230222077696474683d2231303025222063656c6c73706163696e673d2230222063656c6c70616464696e673d223022206267636f6c6f723d2223666666666666223e0d0a3c74626f64793e0d0a3c74723e0d0a3c7464207374796c653d22636f6c6f723a20236666666666663b2220636f6c7370616e3d2232222076616c69676e3d22626f74746f6d22206865696768743d223330223ec2a03c2f74643e0d0a3c2f74723e0d0a3c74723e0d0a3c7464207374796c653d226c696e652d6865696768743a20333270783b2070616464696e672d6c6566743a20333070783b222076616c69676e3d22626173656c696e65223e5b636f6d70616e795f6c6f676f5d3c2f74643e0d0a3c2f74723e0d0a3c2f74626f64793e0d0a3c2f7461626c653e0d0a3c7461626c65207374796c653d226d617267696e2d746f703a20313570783b206d617267696e2d72696768743a20333070783b206d617267696e2d6c6566743a20333070783b20636f6c6f723a20233434343b206c696e652d6865696768743a20312e363b20666f6e742d73697a653a20313270783b20666f6e742d66616d696c793a20417269616c2c73616e732d73657269663b2220626f726465723d2230222077696474683d22353130222063656c6c73706163696e673d2230222063656c6c70616464696e673d223022206267636f6c6f723d2223666666666666223e0d0a3c74626f64793e0d0a3c74723e0d0a3c7464207374796c653d22626f726465722d746f703a20736f6c69642031707820236439643964393b20626f726465722d626f74746f6d3a20736f6c69642031707820236439643964393b2220636f6c7370616e3d2232223e0d0a3c646976207374796c653d2270616464696e673a203135707820303b223e48656c6c6f2c203c6272202f3e3c6272202f3e20596f7572206576656e74203c623e5b6576656e745f6e616d655d3c2f623e2077617320617070726f766564206279207468652061646d696e6973747261746f722e3c6272202f3e3c6272202f3e205468616e6b20796f752c0d0a3c6469763e5b636f6d70616e795f6e616d655d205465616d3c2f6469763e0d0a3c2f6469763e0d0a3c2f74643e0d0a3c2f74723e0d0a3c2f74626f64793e0d0a3c2f7461626c653e0d0a3c7461626c65207374796c653d226c696e652d6865696768743a20312e353b20666f6e742d73697a653a20313270783b20666f6e742d66616d696c793a20417269616c2c73616e732d73657269663b206d617267696e2d72696768743a20333070783b206d617267696e2d6c6566743a20333070783b2220626f726465723d2230222077696474683d22353130222063656c6c73706163696e673d2230222063656c6c70616464696e673d223022206267636f6c6f723d2223666666666666223e0d0a3c74626f64793e0d0a3c7472207374796c653d22666f6e742d73697a653a20313170783b20636f6c6f723a20233939393939393b222076616c69676e3d226d6964646c65223e0d0a3c74643e5b6469726563746f72795f776562736974655d3c2f74643e0d0a3c74643e0d0a3c646976207374796c653d22666c6f61743a2072696768743b2070616464696e672d746f703a20313070783b223e5b636f6d70616e795f736f6369616c5f6e6574776f726b735d3c2f6469763e0d0a3c2f74643e0d0a3c2f74723e0d0a3c74723e0d0a3c7464207374796c653d22636f6c6f723a20236666666666663b2220636f6c7370616e3d223222206865696768743d223135223ec2a03c2f74643e0d0a3c2f74723e0d0a3c2f74626f64793e0d0a3c2f7461626c653e0d0a3c2f6469763e0d0a3c2f74643e0d0a3c2f74723e0d0a3c2f74626f64793e0d0a3c2f7461626c653e0d0a3c2f63656e7465723e3c2f74643e0d0a3c2f74723e0d0a3c2f74626f64793e0d0a3c2f7461626c653e0d0a3c2f6469763e, 0);

-- --------------------------------------------------------

--
-- Table structure for table `#__jbusinessdirectory_language_translations`
--

CREATE TABLE IF NOT EXISTS `#__jbusinessdirectory_language_translations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` int(11) NOT NULL,
  `object_id` int(11) NOT NULL,
  `language_tag` varchar(10) NOT NULL,
  `name` varchar(75) DEFAULT NULL,
  `content_short` varchar(255) DEFAULT NULL,
  `content` text,
  PRIMARY KEY (`id`),
  KEY `idx_object` (`object_id`),
  KEY `ids_langauge` (`language_tag`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=83 ;



-- --------------------------------------------------------

--
-- Table structure for table `#__jbusinessdirectory_orders`
--

CREATE TABLE IF NOT EXISTS `#__jbusinessdirectory_orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` varchar(145) DEFAULT NULL,
  `company_id` int(11) NOT NULL,
  `package_id` int(11) NOT NULL,
  `initial_amount` decimal(8,2) DEFAULT NULL,
  `amount` decimal(8,2) DEFAULT NULL,
  `amount_paid` decimal(8,2) DEFAULT NULL,
  `created` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `paid_at` datetime DEFAULT NULL,
  `state` tinyint(4) DEFAULT NULL,
  `transaction_id` varchar(145) DEFAULT NULL,
  `user_name` varchar(145) DEFAULT NULL,
  `service` varchar(145) DEFAULT NULL,
  `description` varchar(145) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `type` tinyint(4) NOT NULL DEFAULT '0',
  `currency` varchar(4) DEFAULT NULL,
  `expiration_email_date` datetime DEFAULT NULL,
  `discount_code` varchar(50) DEFAULT NULL,
  `discount_amount` decimal(6,2) DEFAULT '0.00',
  `vat_amount` decimal(6,2) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_company` (`company_id`),
  KEY `idx_package` (`package_id`),
  KEY `idx_date` (`start_date`),
  KEY `idx_order` (`order_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;


--
-- Dumping data for table `#__jbusinessdirectory_orders`
--

INSERT INTO `#__jbusinessdirectory_orders` (`id`, `order_id`, `company_id`, `package_id`, `amount`, `amount_paid`, `created`, `paid_at`, `state`, `transaction_id`, `user_name`, `service`, `description`, `start_date`, `type`, `currency`, `expiration_email_date`) VALUES
(1, 'Upgrade-Package: Premium Package', 8, 4, '99.99', NULL, '2014-09-26 13:46:35', '2014-09-26 00:00:00', 1, '', NULL, 'It Company', 'Upgrade-Package: Premium Package', '2014-09-26', 1, 'USD', NULL),
(2, 'Upgrade-Package: Premium Package', 1, 4, '99.99', NULL, '2014-09-26 13:46:47', '2014-09-26 00:00:00', 1, '', NULL, 'Wedding company', 'Upgrade-Package: Premium Package', '2014-09-26', 1, 'USD', NULL),
(3, 'Upgrade-Package: Gold Package', 12, 3, '59.99', NULL, '2014-09-26 13:46:57', '2014-09-26 00:00:00', 1, '', NULL, 'Better Health', 'Upgrade-Package: Gold Package', '2014-09-26', 1, 'USD', NULL),
(4, 'Upgrade-Package: Silver Package', 9, 1, '49.99', NULL, '2014-09-26 14:17:51', '2014-09-26 00:00:00', 1, '', NULL, 'Coffe delights', 'Upgrade-Package: Silver Package', '2014-09-26', 1, 'USD', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `#__jbusinessdirectory_packages`
--


-- --------------------------------------------------------

--
-- Table structure for table `#__jbusinessdirectory_packages`
--

CREATE TABLE IF NOT EXISTS `#__jbusinessdirectory_packages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(145) DEFAULT NULL,
  `description` varchar(200) DEFAULT NULL,
  `price` decimal(8,2) DEFAULT NULL,
  `special_price` decimal(8,2) DEFAULT NULL,
  `special_from_date` date DEFAULT NULL,
  `special_to_date` date DEFAULT NULL,
  `days` smallint(6) NOT NULL DEFAULT '1',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `ordering` tinyint(4) NOT NULL,
  `time_unit` varchar(10) NOT NULL DEFAULT 'D',
  `time_amount` mediumint(9) NOT NULL DEFAULT '1',
  `max_pictures` tinyint(4) NOT NULL DEFAULT '15',
  `max_videos` tinyint(4) NOT NULL DEFAULT '5',
  `max_attachments` tinyint(4) NOT NULL DEFAULT '5',
  `max_categories` tinyint(4) NOT NULL DEFAULT '10',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;


--
-- Dumping data for table `#__jbusinessdirectory_packages`
--

INSERT INTO `#__jbusinessdirectory_packages` (`id`, `name`, `description`, `price`, `special_price`, `special_from_date`, `special_to_date`, `days`, `status`, `ordering`, `time_unit`, `time_amount`, `max_pictures`, `max_videos`) VALUES
(1, 'Silver Package', 'Silver Package', '49.99', '12.00', '1970-01-01', '1970-01-01', 70, 1, 2, 'W', 10, 15, 5),
(2, 'Basic', 'Basic Package', '0.00', '12.00', '1970-01-01', '1970-01-01', 0, 1, 1, 'D', 0, 15, 5),
(3, 'Gold Package', 'Gold Package', '59.99', '0.00', '1970-01-01', '1970-01-01', 180, 1, 3, 'M', 6, 15, 5),
(4, 'Premium Package', 'Premium Package', '99.99', '0.00', '1970-01-01', '1970-01-01', 365, 1, 4, 'Y', 1, 15, 5);

-- --------------------------------------------------------

--
-- Table structure for table `#__jbusinessdirectory_package_fields`
--

CREATE TABLE IF NOT EXISTS `#__jbusinessdirectory_package_fields` (
  `int` int(11) NOT NULL AUTO_INCREMENT,
  `package_id` int(11) DEFAULT NULL,
  `feature` varchar(145) DEFAULT NULL,
  PRIMARY KEY (`int`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=147 ;

--
-- Dumping data for table `#__jbusinessdirectory_package_fields`
--

INSERT INTO `#__jbusinessdirectory_package_fields` (`int`, `package_id`, `feature`) VALUES
(142, 1, 'image_upload'),
(141, 1, 'html_description'),
(122, 3, 'website_address'),
(121, 3, 'company_logo'),
(120, 3, 'html_description'),
(138, 4, 'company_offers'),
(137, 4, 'contact_form'),
(136, 4, 'google_map'),
(135, 4, 'videos'),
(134, 4, 'image_upload'),
(133, 4, 'website_address'),
(132, 4, 'company_logo'),
(131, 4, 'featured_companies'),
(130, 4, 'html_description'),
(123, 3, 'image_upload'),
(124, 3, 'videos'),
(125, 3, 'google_map'),
(126, 3, 'contact_form'),
(127, 3, 'company_offers'),
(128, 3, 'company_events'),
(129, 3, 'social_networks'),
(139, 4, 'company_events'),
(140, 4, 'social_networks'),
(143, 1, 'website_address'),
(144, 1, 'videos'),
(145, 1, 'contact_form'),
(146, 1, 'google_map'),
(147, 4, 'phone'),
(148, 4, 'attachments');

-- --------------------------------------------------------

--
-- Table structure for table `#__jbusinessdirectory_payments`
--

CREATE TABLE IF NOT EXISTS `#__jbusinessdirectory_payments` (
  `payment_id` int(10) NOT NULL AUTO_INCREMENT,
  `order_id` int(10) NOT NULL,
  `processor_type` varchar(100) NOT NULL,
  `created` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `payment_date` date NOT NULL,
  `transaction_id` varchar(80) NOT NULL,
  `amount` decimal(8,2) NOT NULL,
  `currency` char(5) NOT NULL,
  `payment_method` varchar(100) DEFAULT NULL,
  `response_code` varchar(45) DEFAULT NULL,
  `message` blob,
  `payment_status` tinyint(4) NOT NULL,
  PRIMARY KEY (`payment_id`),
  KEY `NewIndex` (`order_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `#__jbusinessdirectory_payment_processors`
--

CREATE TABLE IF NOT EXISTS `#__jbusinessdirectory_payment_processors` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  `mode` enum('live','test') NOT NULL DEFAULT 'live',
  `timeout` int(7) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `ordering` tinyint(4) DEFAULT NULL,
  `displayfront` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `#__jbusinessdirectory_payment_processors`
--

INSERT INTO `#__jbusinessdirectory_payment_processors` (`id`, `name`, `type`, `mode`, `timeout`, `status`, `ordering`, `displayfront`) VALUES
(1, 'Paypal', 'Paypal', 'test', NULL, 1, NULL, 1),
(2, 'Bank Transfer', 'wiretransfer', 'live', 0, 1, 2, 1),
(3, 'Cash', 'cash', 'live', 0, 1, 3, 0),
(4, 'Buckaroo', 'buckaroo', 'test', 60, 1, NULL, 1),
(6, 'Cardsave', 'cardsave', 'test', 15, 1, NULL, 1),
(7, 'EWay', 'eway', 'live', 10, 1, NULL, 1),
(8, 'Authorize', 'authorize', 'test', 10, 1, NULL, 1),
(9, '2checkout', 'twocheckout', 'test', 10, 1, NULL, 1),
(10, 'PayFast', 'payfast', 'test', 10, 1, NULL, 1);


-- --------------------------------------------------------

--
-- Table structure for table `#__jbusinessdirectory_payment_processor_fields`
--

CREATE TABLE IF NOT EXISTS `#__jbusinessdirectory_payment_processor_fields` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `column_name` varchar(100) DEFAULT NULL,
  `column_value` varchar(255) DEFAULT NULL,
  `processor_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=101 ;

--
-- Dumping data for table `#__jbusinessdirectory_payment_processor_fields`
--

INSERT INTO `#__jbusinessdirectory_payment_processor_fields` (`id`, `column_name`, `column_value`, `processor_id`) VALUES
(17, 'paypal_email', '', 1),
(88, 'bank_name', 'Bank Name', 2),
(86, 'bank_city', 'City', 2),
(87, 'bank_address', 'Address', 2),
(85, 'bank_country', 'Counntry', 2),
(84, 'swift_code', 'SW1321', 2),
(83, 'iban', 'BR213 123 123 123', 2),
(82, 'bank_account_number', '123123123123 ', 2),
(81, 'bank_holder_name', 'Account holder name', 2),
(89, 'secretKey', '', 4),
(90, 'merchantId', '', 4),
(100, 'merchantId', '', 6),
(98, 'preSharedKey', '', 6),
(99, 'password', '1M75C4R8', 6),
(116, 'user_name', '', 7),
(115, 'customer_id', '87654321', 7),
(120, 'transaction_key', '9eD5LC7e6h68jFxY', 8),
(119, 'api_login_id', '2bd3DEG6JZ', 8),
(123, 'account_number', '901265403', 9),
(124, 'secret_word', 'tango', 9),
(125, 'merchant_id', '10001965', 10),
(126, 'merchant_key', 'hz7almlp6ma90', 10);


-- --------------------------------------------------------

--
-- Table structure for table `#__jbusinessdirectory_reports`
--

CREATE TABLE IF NOT EXISTS `#__jbusinessdirectory_reports` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(145) DEFAULT NULL,
  `description` text,
  `selected_params` text,
  `custom_params` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `#__jbusinessdirectory_reports`
--

INSERT INTO `#__jbusinessdirectory_reports` (`id`, `name`, `description`, `selected_params`, `custom_params`) VALUES
(1, 'Simple Report', 'Simple Report', 'name,short_description,website,email,averageRating,viewCount,contactCount,websiteCount', NULL);

