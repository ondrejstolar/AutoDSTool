CREATE TABLE `listings` (
  `user` text NOT NULL,
  `sourceID` text NOT NULL,
  `ebayID` text NOT NULL,
  `pic` text NOT NULL,
  `sellPrice` text NOT NULL,
  `sourcePrice` text DEFAULT NULL,
  `profit` text DEFAULT NULL,
  `title` text DEFAULT NULL,
  `status` text DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;
