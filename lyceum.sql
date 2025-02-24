-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 24, 2025 at 04:32 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `lyceum`
--

-- --------------------------------------------------------

--
-- Table structure for table `comment`
--

CREATE TABLE `comment` (
  `id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `community_id` int(11) NOT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `comment` varchar(10000) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `modified_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `comment`
--

INSERT INTO `comment` (`id`, `post_id`, `user_id`, `community_id`, `parent_id`, `comment`, `created_at`, `modified_at`) VALUES
(2, 1, 5, 1, NULL, 'sdafasdfa', '2025-02-23 22:52:22', '2025-02-23 22:52:22'),
(3, 1, 5, 1, NULL, 'helloasdf', '2025-02-23 23:09:35', '2025-02-23 23:09:35');

-- --------------------------------------------------------

--
-- Table structure for table `community`
--

CREATE TABLE `community` (
  `id` int(11) NOT NULL,
  `community_name` varchar(100) DEFAULT NULL,
  `university_id` int(11) NOT NULL,
  `institute_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `batch_id` int(11) NOT NULL,
  `description` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `community`
--

INSERT INTO `community` (`id`, `community_name`, `university_id`, `institute_id`, `course_id`, `batch_id`, `description`) VALUES
(1, 'Computer Science Club', 1, 1, 1, 1, 0),
(2, 'Mathematics Society', 1, 2, 2, 1, 0),
(3, 'Physics Group', 1, 3, 3, 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

CREATE TABLE `courses` (
  `id` int(11) NOT NULL,
  `course_code` varchar(20) NOT NULL,
  `course_title` varchar(200) NOT NULL,
  `description` varchar(1000) NOT NULL,
  `institute_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `courses`
--

INSERT INTO `courses` (`id`, `course_code`, `course_title`, `description`, `institute_id`) VALUES
(1, 'CS101', 'Introduction to Computer Science', 'Basic concepts of computer science', 1),
(2, 'MATH101', 'Calculus I', 'Introduction to calculus', 1),
(3, 'PHYS101', 'Physics I', 'Introduction to physics', 1);

-- --------------------------------------------------------

--
-- Table structure for table `institute`
--

CREATE TABLE `institute` (
  `id` int(11) NOT NULL,
  `name` varchar(200) NOT NULL,
  `description` varchar(1000) NOT NULL,
  `website` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `institute`
--

INSERT INTO `institute` (`id`, `name`, `description`, `website`) VALUES
(1, 'School of Computer Science', 'The School of Computer Science offers a variety of programs in computing.', 'http://www.exampleuniversity.com/cs'),
(2, 'School of Mathematics', 'The School of Mathematics provides comprehensive education in mathematics.', 'http://www.exampleuniversity.com/math'),
(3, 'School of Physics', 'The School of Physics is dedicated to advancing the understanding of physical phenomena.', 'http://www.exampleuniversity.com/physics');

-- --------------------------------------------------------

--
-- Table structure for table `institute_in_uni`
--

CREATE TABLE `institute_in_uni` (
  `id` int(11) NOT NULL,
  `institute_id` int(11) NOT NULL,
  `university_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `community_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `post_title` varchar(250) NOT NULL,
  `post_description` varchar(10000) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `modified_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`id`, `user_id`, `community_id`, `category_id`, `post_title`, `post_description`, `created_at`, `modified_at`) VALUES
(1, 5, 1, 1, 'hello', 'this is test post', '2025-02-23 22:29:19', '2025-02-23 22:36:32');

-- --------------------------------------------------------

--
-- Table structure for table `post_category`
--

CREATE TABLE `post_category` (
  `id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `description` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `post_category`
--

INSERT INTO `post_category` (`id`, `title`, `description`) VALUES
(1, 'Notice', 'This is notice area\r\n'),
(2, 'Discussion', 'This is discussion thread');

-- --------------------------------------------------------

--
-- Table structure for table `university`
--

CREATE TABLE `university` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` varchar(1000) NOT NULL,
  `location` varchar(100) NOT NULL,
  `abbr` varchar(20) NOT NULL,
  `website` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `university`
--

INSERT INTO `university` (`id`, `name`, `description`, `location`, `abbr`, `website`) VALUES
(1, 'Example University', 'A leading university in technology and science.', 'Example City', 'EXU', 'http://www.exampleuniversity.com'),
(2, 'Sample University', 'A prestigious university known for its research.', 'Sample City', 'SAMU', 'http://www.sampleuniversity.com');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `dob` date DEFAULT NULL,
  `university_id` int(11) NOT NULL,
  `institution_id` int(11) NOT NULL,
  `batch_id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `role` enum('student','teacher','admin') NOT NULL DEFAULT 'student'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `first_name`, `last_name`, `dob`, `university_id`, `institution_id`, `batch_id`, `email`, `password`, `created_at`, `updated_at`, `role`) VALUES
(5, 'admin', 'syfur', 'rahman', '0000-00-00', 34, 345, 23, 'admin@example.com', '$2y$10$OO7GIrV74I3HEUeI0VwxO.j7dG.MQy2S4B8oVxL6P3AxQTKldKL2m', '2025-02-23 16:22:15', NULL, 'student'),
(6, 'adfa', 'adsfasdfa', 'asdfasdfas', '0000-00-00', 2, 3, 1, 'syfur.cse8.bu@gmail.com', '$2y$10$YQRaRqrqJeHHdHpUgvNzKu7CwKQgAjIEIyV6X8ZtVGkl.klYa2aFm', '2025-02-23 17:18:42', NULL, 'teacher');

-- --------------------------------------------------------

--
-- Table structure for table `user_in_community`
--

CREATE TABLE `user_in_community` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `community_id` int(11) NOT NULL,
  `joined_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `left_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_in_community`
--

INSERT INTO `user_in_community` (`id`, `user_id`, `community_id`, `joined_at`, `left_at`) VALUES
(1, 5, 1, '2024-12-31 18:00:00', NULL),
(2, 5, 2, '2024-12-31 18:00:00', NULL),
(3, 5, 3, '2024-12-31 18:00:00', NULL),
(4, 6, 1, '2024-12-31 18:00:00', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `vote_in_post`
--

CREATE TABLE `vote_in_post` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `community_id` int(11) NOT NULL,
  `vote` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `modified_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `vote_in_post`
--

INSERT INTO `vote_in_post` (`id`, `user_id`, `post_id`, `community_id`, `vote`, `created_at`, `modified_at`) VALUES
(2, 5, 1, 1, -1, '2025-02-23 23:00:37', '2025-02-23 23:06:19'),
(3, 6, 1, 1, 1, '2025-02-24 03:14:06', '2025-02-24 03:22:46');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `comment`
--
ALTER TABLE `comment`
  ADD PRIMARY KEY (`id`),
  ADD KEY `community_id` (`community_id`),
  ADD KEY `post_id` (`post_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `parent_id` (`parent_id`);

--
-- Indexes for table `community`
--
ALTER TABLE `community`
  ADD PRIMARY KEY (`id`),
  ADD KEY `university_id` (`university_id`),
  ADD KEY `institute_id` (`institute_id`);

--
-- Indexes for table `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `institute`
--
ALTER TABLE `institute`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `institute_in_uni`
--
ALTER TABLE `institute_in_uni`
  ADD PRIMARY KEY (`id`),
  ADD KEY `institute_id` (`institute_id`),
  ADD KEY `university_id` (`university_id`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `community_id` (`community_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `post_category`
--
ALTER TABLE `post_category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `university`
--
ALTER TABLE `university`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `user_in_community`
--
ALTER TABLE `user_in_community`
  ADD PRIMARY KEY (`id`),
  ADD KEY `community_id` (`community_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `vote_in_post`
--
ALTER TABLE `vote_in_post`
  ADD PRIMARY KEY (`id`),
  ADD KEY `post_id` (`post_id`),
  ADD KEY `community_id` (`community_id`),
  ADD KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `comment`
--
ALTER TABLE `comment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `community`
--
ALTER TABLE `community`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `courses`
--
ALTER TABLE `courses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `institute`
--
ALTER TABLE `institute`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `institute_in_uni`
--
ALTER TABLE `institute_in_uni`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `post_category`
--
ALTER TABLE `post_category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `university`
--
ALTER TABLE `university`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `user_in_community`
--
ALTER TABLE `user_in_community`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `vote_in_post`
--
ALTER TABLE `vote_in_post`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `comment`
--
ALTER TABLE `comment`
  ADD CONSTRAINT `comment_ibfk_1` FOREIGN KEY (`community_id`) REFERENCES `community` (`id`),
  ADD CONSTRAINT `comment_ibfk_2` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`),
  ADD CONSTRAINT `comment_ibfk_3` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `comment_ibfk_4` FOREIGN KEY (`parent_id`) REFERENCES `comment` (`id`);

--
-- Constraints for table `community`
--
ALTER TABLE `community`
  ADD CONSTRAINT `community_ibfk_1` FOREIGN KEY (`university_id`) REFERENCES `university` (`id`),
  ADD CONSTRAINT `community_ibfk_2` FOREIGN KEY (`institute_id`) REFERENCES `institute` (`id`);

--
-- Constraints for table `institute_in_uni`
--
ALTER TABLE `institute_in_uni`
  ADD CONSTRAINT `institute_in_uni_ibfk_1` FOREIGN KEY (`institute_id`) REFERENCES `institute` (`id`),
  ADD CONSTRAINT `institute_in_uni_ibfk_2` FOREIGN KEY (`university_id`) REFERENCES `university` (`id`);

--
-- Constraints for table `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `posts_ibfk_1` FOREIGN KEY (`community_id`) REFERENCES `community` (`id`),
  ADD CONSTRAINT `posts_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `posts_ibfk_3` FOREIGN KEY (`category_id`) REFERENCES `post_category` (`id`);

--
-- Constraints for table `user_in_community`
--
ALTER TABLE `user_in_community`
  ADD CONSTRAINT `user_in_community_ibfk_1` FOREIGN KEY (`community_id`) REFERENCES `community` (`id`),
  ADD CONSTRAINT `user_in_community_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `vote_in_post`
--
ALTER TABLE `vote_in_post`
  ADD CONSTRAINT `vote_in_post_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`),
  ADD CONSTRAINT `vote_in_post_ibfk_2` FOREIGN KEY (`community_id`) REFERENCES `community` (`id`),
  ADD CONSTRAINT `vote_in_post_ibfk_3` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
