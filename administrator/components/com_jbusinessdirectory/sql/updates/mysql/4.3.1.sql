ALTER TABLE `#__jbusinessdirectory_applicationsettings` ADD COLUMN `listing_url_type` TINYINT NOT NULL DEFAULT '1';
ALTER TABLE `#__jbusinessdirectory_applicationsettings` 
ADD COLUMN `show_secondary_map_locations` TINYINT(1) NOT NULL DEFAULT 0 COMMENT '' ;

INSERT INTO `#__jbusinessdirectory_emails` (`email_subject`, `email_name`, `email_type`, `email_content`, `is_default`) VALUES
('An offer has been added', 'Offer Creation', 'Offer Creation Notification', 0x3c703e48692c3c2f703e0d0a3c703e41206e6577206f666665723c7374726f6e673e205b6f666665725f6e616d655d203c2f7374726f6e673e20686173206265656e206164646564206f6e20796f7572206469726563746f72792e3c6272202f3e3c6272202f3e4265737420726567617264732c3c6272202f3e5b636f6d70616e795f6e616d655d3c2f703e0d0a3c703ec2a03c2f703e, 0),
('Your offer has been approved', 'Offer Approval', 'Offer Approval Notification', 0x3c703e48692c3c2f703e0d0a3c703e596f7572206f666665723c7374726f6e673e205b6f666665725f6e616d655d203c2f7374726f6e673e20686173206265656e20617070726f7665642e3c6272202f3e3c6272202f3e4265737420726567617264732c3c6272202f3e5b636f6d70616e795f6e616d655d3c2f703e, 0),
('An event has been added on directory', 'Event Creation Notification', 'Event Creation Notification', 0x3c703e48692c3c2f703e0d0a3c703e41206e6577206576656e743c7374726f6e673e205b6576656e745f6e616d655dc2a03c2f7374726f6e673e20686173206265656e206164646564206f6e20796f7572206469726563746f72792e3c6272202f3e3c6272202f3e4265737420726567617264732c3c6272202f3e5b636f6d70616e795f6e616d655d3c2f703e, 0),
('You event has been approved', 'Event Approval Notificaiton', 'Event Approval Notification', 0x3c703e48692c3c2f703e0d0a3c703e546865206576656e743c7374726f6e673e205b6576656e745f6e616d655dc2a03c2f7374726f6e673e20686173206265656e20617070726f7665642e3c6272202f3e3c6272202f3e4265737420726567617264732c3c6272202f3e5b636f6d70616e795f6e616d655d3c2f703e, 0);

update #__jbusinessdirectory_company_offers set featured = 0 where featured is null;
update #__jbusinessdirectory_company_events set featured = 0 where featured is null;

ALTER TABLE `#__jbusinessdirectory_applicationsettings` 
ADD COLUMN `search_result_grid_view` TINYINT(1) NOT NULL DEFAULT 1;
