-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th7 16, 2019 lúc 10:46 AM
-- Phiên bản máy phục vụ: 10.1.36-MariaDB
-- Phiên bản PHP: 7.0.32

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `login`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'test1', 'test@gmail.com', '$2y$10$.GLSlo.uHvTimzBJPxfWDeUX9.OTA0TYD6oXM6.c5Biq5ZFtbidx2', 'ARov1AMS4TgXMm79ixwZpyzyJtAoJHQTh21jspm95rO8FukT12ySDuRDhfa6', '2018-01-02 06:19:44', '2018-01-02 06:19:44'),
(2, '1', '1@gmail.com', '$2y$10$2n5d0yb27fQosFI7dlReQOkatYnyw1LSrvC5elC2wF2S1/ByMGzFq', NULL, '2018-10-15 06:57:30', '2018-10-15 06:57:30'),
(3, '1', '2@gmail.com', '$2y$10$OWdPVn4VTnj/o7UqmLpxTO3aeL3vhF/mK.fIOJhwQKcUkizGMunoe', 'AlaWFkvte9d4HMeMxAA2f4QA1V1JWO0z5zhlCl6N0yqrey3y2xvxD7iuPXGI', '2019-07-16 01:14:36', '2019-07-16 01:14:36'),
(4, '3', '3@gmail.com', '$2y$10$hj17724/vfdeTTsn1gH2IOxheFWR451TjofnEGLgINjEEBEVA0u.G', '4NETsboTcKAZPY7QJit4MzShOrZ4CuAhaNnEBMv6aTc9JMlckrOy18YGy1nE', '2019-07-16 01:32:29', '2019-07-16 01:32:29'),
(5, '4', '4@gmail.com', '$2y$10$fVolwrhY1CrmVeTBwQz3TuMkGhxk9vyGUhn3YaQGAwK9yCn8SquLq', 'BVolTdsmuC1rf1tUfPcoxJMt1MQCowznvdll2mFQCsZdPQgAm24n38Ljm2YC', '2019-07-16 01:34:24', '2019-07-16 01:34:24');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
