--
-- Table structure for table `google_drive_upload_response_log`
--

CREATE TABLE `google_drive_upload_response_log` (
  `id` int NOT NULL,
  `google_file_id` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `file_base_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `create_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `google_drive_upload_response_log`
--
ALTER TABLE `google_drive_upload_response_log`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `google_drive_upload_response_log`
--
ALTER TABLE `google_drive_upload_response_log`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;
