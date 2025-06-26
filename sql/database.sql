-- Membuat database baru bernama 'toko_kopi_db' jika belum ada.
-- Karakter set utf8mb4 digunakan untuk mendukung berbagai macam karakter, termasuk emoji.
CREATE DATABASE IF NOT EXISTS `toko_kopi_db` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;

-- Memilih database 'toko_kopi_db' untuk digunakan.
USE `toko_kopi_db`;

-- --------------------------------------------------------

--
-- Struktur tabel untuk `users`
-- Tabel ini menyimpan data pengguna, baik 'admin' maupun 'user'.
--
CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL COMMENT 'Password harus selalu di-hash sebelum disimpan',
  `role` enum('admin','user') NOT NULL DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Menambahkan data contoh untuk user dan admin
-- Password untuk 'admin' dan 'user' adalah 'password123'. 
-- Di aplikasi nyata, HASH password ini! Contoh hash untuk 'password123': $2y$10$9.B2JVo9iP3VKO7j8aLw.eF1Gkprl5pD.N2Gk.DGrP2YDCWc5Jq12
INSERT INTO `users` (`id`, `nama`, `email`, `username`, `password`, `role`) VALUES
(1, 'Admin Toko', 'admin@example.com', 'admin', '$2y$10$9.B2JVo9iP3VKO7j8aLw.eF1Gkprl5pD.N2Gk.DGrP2YDCWc5Jq12', 'admin'),
(2, 'User Biasa', 'user@example.com', 'user', '$2y$10$9.B2JVo9iP3VKO7j8aLw.eF1Gkprl5pD.N2Gk.DGrP2YDCWc5Jq12', 'user');


-- --------------------------------------------------------

--
-- Struktur tabel untuk `products`
-- Tabel ini menyimpan semua data produk yang dijual.
--
CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `nama_produk` varchar(150) NOT NULL,
  `deskripsi` text NOT NULL,
  `harga` decimal(10,2) NOT NULL,
  `gambar` varchar(255) NOT NULL COMMENT 'Hanya nama file gambar, misal: "kopi-gayo.jpg"',
  `tanggal_upload` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Menambahkan data contoh untuk produk
INSERT INTO `products` (`id`, `nama_produk`, `deskripsi`, `harga`, `gambar`) VALUES
(1, 'Biji Kopi Gayo', 'Biji kopi arabika asli dari dataran tinggi Gayo, Aceh. Memiliki aroma khas dan cita rasa yang kompleks.', 85000.00, 'kopi-gayo.jpg'),
(2, 'French Press 350ml', 'Alat seduh kopi manual dengan metode tekanan. Kapasitas 350ml, cocok untuk 1-2 cangkir.', 120000.00, 'french-press.jpg'),
(3, 'V60 Dripper Keramik', 'Alat seduh kopi pour-over yang ikonik. Terbuat dari keramik untuk menjaga stabilitas suhu.', 95000.00, 'v60.jpg');


-- --------------------------------------------------------

--
-- Struktur tabel untuk `orders`
-- Tabel ini mencatat semua transaksi pesanan dari pengguna.
--
CREATE TABLE `orders` (
  `id_order` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `id_produk` int(11) NOT NULL,
  `jumlah` int(11) NOT NULL DEFAULT 1,
  `total_harga` decimal(10,2) NOT NULL,
  `tanggal_order` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('Proses','Selesai','Batal') NOT NULL DEFAULT 'Proses'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Menambahkan data contoh untuk pesanan
INSERT INTO `orders` (`id_order`, `id_user`, `id_produk`, `jumlah`, `total_harga`, `status`) VALUES
(1, 2, 1, 1, 85000.00, 'Selesai'),
(2, 2, 3, 1, 95000.00, 'Proses');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id_order`),
  ADD KEY `id_user` (`id_user`),
  ADD KEY `id_produk` (`id_produk`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id_order` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`id_produk`) REFERENCES `products` (`id`);
COMMIT; 