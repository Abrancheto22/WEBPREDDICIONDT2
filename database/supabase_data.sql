-- Script de Datos generado automáticamente

-- Datos para rols
INSERT INTO "rols" ("idrol", "nombre", "created_at", "updated_at") VALUES 
(1, 'Administrador', NULL, '2025-06-28 15:39:27.833'),
(2, 'Doctor', NULL, NULL),
(3, 'Enfermera', NULL, NULL),
(4, 'Paciente', NULL, NULL);

SELECT setval(pg_get_serial_sequence('"rols"', 'idrol'), 4);

-- Datos para users
INSERT INTO "users" ("id", "name", "email", "email_verified_at", "password", "remember_token", "created_at", "updated_at", "idrol") VALUES 
(1, 'admin', 'admin@example.com', NULL, '$2y$12$pN3.BrLcTJlyyw.4g4NXKuKNzVUZ6Uw30F8sH4B4vVWB0mGrD5a4K', NULL, '2025-09-19 04:37:12.940', '2025-09-19 04:37:12.940', 1),
(2, 'doctor', 'doctor@example.com', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, '2025-09-18 23:39:06.367', NULL, 2),
(3, 'enfermera', 'enfermera@example.com', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, '2025-09-18 23:39:06.377', NULL, 3),
(4, 'paciente1', 'paciente1@example.com', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, '2025-09-18 23:39:33.390', NULL, 4),
(5, 'paciente2', 'paciente2@example.com', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, '2025-09-18 23:39:33.390', NULL, 4),
(6, 'paciente3', 'paciente3@example.com', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, '2025-09-18 23:39:33.390', NULL, 4),
(7, 'paciente4', 'paciente4@example.com', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, '2025-09-18 23:39:33.393', NULL, 4),
(8, 'paciente5', 'paciente5@example.com', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, '2025-09-18 23:39:33.393', NULL, 4),
(9, 'paciente6', 'paciente6@example.com', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, '2025-09-18 23:39:33.393', NULL, 4),
(10, 'paciente7', 'paciente7@example.com', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, '2025-09-18 23:39:33.393', NULL, 4),
(11, 'paciente8', 'paciente8@example.com', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, '2025-09-18 23:39:33.393', NULL, 4),
(12, 'paciente9', 'paciente9@example.com', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, '2025-09-18 23:39:33.393', NULL, 4),
(13, 'paciente10', 'paciente10@example.com', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, '2025-09-18 23:39:33.393', NULL, 4),
(14, 'paciente11', 'paciente11@example.com', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, '2025-09-18 23:39:33.393', NULL, 4),
(15, 'paciente12', 'paciente12@example.com', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, '2025-09-18 23:39:33.397', NULL, 4),
(16, 'paciente13', 'paciente13@example.com', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, '2025-09-18 23:39:33.397', NULL, 4),
(17, 'paciente14', 'paciente14@example.com', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, '2025-09-18 23:39:33.397', NULL, 4),
(18, 'paciente15', 'paciente15@example.com', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, '2025-09-18 23:39:33.397', NULL, 4),
(19, 'paciente16', 'paciente16@example.com', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, '2025-09-18 23:39:33.397', NULL, 4),
(20, 'paciente17', 'paciente17@example.com', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, '2025-09-18 23:39:33.397', NULL, 4),
(21, 'paciente18', 'paciente18@example.com', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, '2025-09-18 23:39:33.397', NULL, 4),
(22, 'paciente19', 'paciente19@example.com', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, '2025-09-18 23:39:33.397', NULL, 4),
(23, 'paciente20', 'paciente20@example.com', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, '2025-09-18 23:39:33.400', NULL, 4),
(24, 'paciente21', 'paciente21@example.com', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, '2025-09-18 23:39:33.400', NULL, 4),
(25, 'paciente22', 'paciente22@example.com', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, '2025-09-18 23:39:33.400', NULL, 4),
(26, 'paciente23', 'paciente23@example.com', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, '2025-09-18 23:39:33.400', NULL, 4),
(27, 'paciente24', 'paciente24@example.com', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, '2025-09-18 23:39:33.400', NULL, 4),
(28, 'paciente25', 'paciente25@example.com', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, '2025-09-18 23:39:33.400', NULL, 4),
(29, 'paciente26', 'paciente26@example.com', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, '2025-09-18 23:39:33.400', NULL, 4),
(30, 'paciente27', 'paciente27@example.com', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, '2025-09-18 23:39:33.400', NULL, 4),
(31, 'paciente28', 'paciente28@example.com', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, '2025-09-18 23:39:33.400', NULL, 4),
(32, 'paciente29', 'paciente29@example.com', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, '2025-09-18 23:39:33.400', NULL, 4),
(33, 'paciente30', 'paciente30@example.com', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, '2025-09-18 23:39:33.400', NULL, 4),
(34, 'paciente31', 'paciente31@example.com', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, '2025-09-18 23:39:33.410', NULL, 4),
(35, 'paciente32', 'paciente32@example.com', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, '2025-09-18 23:39:33.410', NULL, 4),
(36, 'paciente33', 'paciente33@example.com', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, '2025-09-18 23:39:33.413', NULL, 4),
(37, 'paciente34', 'paciente34@example.com', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, '2025-09-18 23:39:33.413', NULL, 4),
(38, 'paciente35', 'paciente35@example.com', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, '2025-09-18 23:39:33.417', NULL, 4),
(39, 'paciente36', 'paciente36@example.com', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, '2025-09-18 23:39:33.417', NULL, 4),
(40, 'paciente37', 'paciente37@example.com', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, '2025-09-18 23:39:33.417', NULL, 4),
(41, 'paciente38', 'paciente38@example.com', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, '2025-09-18 23:39:33.417', NULL, 4),
(42, 'paciente39', 'paciente39@example.com', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, '2025-09-18 23:39:33.417', NULL, 4),
(43, 'paciente40', 'paciente40@example.com', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, '2025-09-18 23:39:33.417', NULL, 4),
(44, 'paciente41', 'paciente41@example.com', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, '2025-09-18 23:39:33.417', NULL, 4),
(45, 'paciente42', 'paciente42@example.com', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, '2025-09-18 23:39:33.417', NULL, 4),
(46, 'paciente43', 'paciente43@example.com', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, '2025-09-18 23:39:33.417', NULL, 4),
(47, 'paciente44', 'paciente44@example.com', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, '2025-09-18 23:39:33.417', NULL, 4),
(48, 'paciente45', 'paciente45@example.com', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, '2025-09-18 23:39:33.417', NULL, 4),
(49, 'paciente46', 'paciente46@example.com', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, '2025-09-18 23:39:33.420', NULL, 4),
(50, 'paciente47', 'paciente47@example.com', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, '2025-09-18 23:39:33.420', NULL, 4),
(51, 'paciente48', 'paciente48@example.com', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, '2025-09-18 23:39:33.420', NULL, 4),
(52, 'paciente49', 'paciente49@example.com', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, '2025-09-18 23:39:33.420', NULL, 4),
(53, 'paciente50', 'paciente50@example.com', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, '2025-09-18 23:39:33.420', NULL, 4),
(54, 'paciente51', 'paciente51@example.com', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, '2025-09-18 23:39:33.420', NULL, 4),
(55, 'paciente52', 'paciente52@example.com', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, '2025-09-18 23:39:33.420', NULL, 4),
(56, 'paciente53', 'paciente53@example.com', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, '2025-09-18 23:39:33.420', NULL, 4),
(57, 'paciente54', 'paciente54@example.com', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, '2025-09-18 23:39:33.420', NULL, 4),
(58, 'paciente55', 'paciente55@example.com', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, '2025-09-18 23:39:33.420', NULL, 4),
(59, 'paciente56', 'paciente56@example.com', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, '2025-09-18 23:39:33.420', NULL, 4),
(60, 'paciente57', 'paciente57@example.com', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, '2025-09-18 23:39:33.420', NULL, 4),
(61, 'paciente58', 'paciente58@example.com', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, '2025-09-18 23:39:33.420', NULL, 4),
(62, 'paciente59', 'paciente59@example.com', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, '2025-09-18 23:39:33.420', NULL, 4),
(63, 'paciente60', 'paciente60@example.com', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, '2025-09-18 23:39:33.423', NULL, 4),
(64, 'paciente61', 'paciente61@example.com', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, '2025-09-18 23:39:33.423', NULL, 4),
(65, 'paciente62', 'paciente62@example.com', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, '2025-09-18 23:39:33.423', NULL, 4),
(66, 'paciente63', 'paciente63@example.com', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, '2025-09-18 23:39:33.423', NULL, 4),
(67, 'paciente64', 'paciente64@example.com', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, '2025-09-18 23:39:33.423', NULL, 4),
(68, 'paciente65', 'paciente65@example.com', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, '2025-09-18 23:39:33.423', NULL, 4),
(69, 'paciente66', 'paciente66@example.com', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, '2025-09-18 23:39:33.423', NULL, 4),
(70, 'paciente67', 'paciente67@example.com', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, '2025-09-18 23:39:33.423', NULL, 4),
(71, 'paciente68', 'paciente68@example.com', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, '2025-09-18 23:39:33.427', NULL, 4),
(72, 'paciente69', 'paciente69@example.com', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, '2025-09-18 23:39:33.427', NULL, 4),
(73, 'paciente70', 'paciente70@example.com', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, '2025-09-18 23:39:33.427', NULL, 4),
(74, 'paciente71', 'paciente71@example.com', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, '2025-09-18 23:39:33.427', NULL, 4),
(75, 'paciente72', 'paciente72@example.com', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, '2025-09-18 23:39:33.427', NULL, 4),
(76, 'paciente73', 'paciente73@example.com', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, '2025-09-18 23:39:33.427', NULL, 4),
(77, 'paciente74', 'paciente74@example.com', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, '2025-09-18 23:39:33.427', NULL, 4),
(78, 'paciente75', 'paciente75@example.com', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, '2025-09-18 23:39:33.427', NULL, 4),
(79, 'paciente76', 'paciente76@example.com', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, '2025-09-18 23:39:33.430', NULL, 4),
(80, 'paciente77', 'paciente77@example.com', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, '2025-09-18 23:39:33.430', NULL, 4),
(81, 'paciente78', 'paciente78@example.com', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, '2025-09-18 23:39:33.430', NULL, 4),
(82, 'paciente79', 'paciente79@example.com', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, '2025-09-18 23:39:33.430', NULL, 4),
(83, 'paciente80', 'paciente80@example.com', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, '2025-09-18 23:39:33.430', NULL, 4),
(84, 'Edward Steven Quispe Sanchez', 'esquispes01@gmail.com', NULL, '$2y$12$wx.LoAqZWdwvkOWQHREqwu1YwXZX36m3A8/mS4ZXR8xSQkUsrozqq', NULL, '2025-03-10 06:14:08.337', '2025-03-10 06:14:08.337', 4);

SELECT setval(pg_get_serial_sequence('"users"', 'id'), 84);

-- Datos para doctor
INSERT INTO "doctor" ("iddoctor", "iduser", "DNI", "nombre", "apellido", "numero", "especialidad", "imagen", "created_at", "updated_at", "sueldo") VALUES 
(1, 2, 12345678, 'Pedro', 'Gómez', 987654321, 'Cardiología', 'images/doctores/doctor_ai-generative-portrait-of-confident-male-doctor-in-white-coat-and-stethoscope-standing-with-arms-crossed-and-looking-at-camera-photo.jpg', '2025-09-19 04:43:52.820', '2025-07-11 19:34:23.973', 8000.00);

SELECT setval(pg_get_serial_sequence('"doctor"', 'iddoctor'), 1);

-- Datos para efermera
INSERT INTO "efermera" ("idenfermera", "iduser", "DNI", "nombre", "apellido", "numero", "imagen", "created_at", "updated_at") VALUES 
(1, 3, 87654321, 'Maria', 'Perez', 912345678, 'images/enfermeras/enfermera_enfermera.jpg', '2025-09-19 04:48:14.630', '2025-09-19 04:48:14.630');

SELECT setval(pg_get_serial_sequence('"efermera"', 'idenfermera'), 1);

-- Datos para paciente
INSERT INTO "paciente" ("idpaciente", "iduser", "DNI", "nombre", "apellido", "sexo", "fecha_nacimiento", "direccion", "telefono", "imagen", "created_at", "updated_at") VALUES 
(1, 4, 10000001, 'Javier', 'Martínez', 'M', '1998-09-19', 'Dirección de ejemplo 4', 90000001, 'images/pacientes/paciente_pngwing.com (1).png', '2025-09-19 05:47:59.747', '2025-03-10 06:00:24.490'),
(2, 5, 10000002, 'David', 'Sánchez', 'Masculino', '1972-09-19', 'Dirección de ejemplo 5', 90000002, NULL, '2025-09-19 05:47:59.747', NULL),
(3, 6, 10000003, 'Jorge', 'Martínez', 'Masculino', '1978-09-19', 'Dirección de ejemplo 6', 90000003, NULL, '2025-09-19 05:47:59.747', NULL),
(4, 7, 10000004, 'Isabel', 'Gómez', 'Femenino', '1980-09-19', 'Dirección de ejemplo 7', 90000004, NULL, '2025-09-19 05:47:59.750', NULL),
(5, 8, 10000005, 'Luis', 'Díaz', 'Masculino', '1962-09-19', 'Dirección de ejemplo 8', 90000005, NULL, '2025-09-19 05:47:59.750', NULL),
(6, 9, 10000006, 'Miguel', 'García', 'Masculino', '1979-09-19', 'Dirección de ejemplo 9', 90000006, NULL, '2025-09-19 05:47:59.750', NULL),
(7, 10, 10000007, 'Ana', 'Sánchez', 'Femenino', '1966-09-19', 'Dirección de ejemplo 10', 90000007, NULL, '2025-09-19 05:47:59.750', NULL),
(8, 11, 10000008, 'Laura', 'Díaz', 'Femenino', '1995-09-19', 'Dirección de ejemplo 11', 90000008, NULL, '2025-09-19 05:47:59.750', NULL),
(9, 12, 10000009, 'Pedro', 'Díaz', 'Masculino', '1965-09-19', 'Dirección de ejemplo 12', 90000009, NULL, '2025-09-19 05:47:59.753', NULL),
(10, 13, 10000010, 'Pedro', 'Rodríguez', 'Masculino', '1986-09-19', 'Dirección de ejemplo 13', 90000010, NULL, '2025-09-19 05:47:59.753', NULL),
(11, 14, 10000011, 'María', 'Ramírez', 'Femenino', '1970-09-19', 'Dirección de ejemplo 14', 90000011, NULL, '2025-09-19 05:47:59.753', NULL),
(12, 15, 10000012, 'María', 'Pérez', 'Femenino', '1996-09-19', 'Dirección de ejemplo 15', 90000012, NULL, '2025-09-19 05:47:59.753', NULL),
(13, 16, 10000013, 'Diego', 'Fernández', 'Masculino', '1978-09-19', 'Dirección de ejemplo 16', 90000013, NULL, '2025-09-19 05:47:59.753', NULL),
(14, 17, 10000014, 'Sofía', 'Martínez', 'Femenino', '1957-09-19', 'Dirección de ejemplo 17', 90000014, NULL, '2025-09-19 05:47:59.757', NULL),
(15, 18, 10000015, 'Isabel', 'Pérez', 'Femenino', '1967-09-19', 'Dirección de ejemplo 18', 90000015, NULL, '2025-09-19 05:47:59.757', NULL),
(16, 19, 10000016, 'Lucía', 'Martínez', 'Femenino', '1974-09-19', 'Dirección de ejemplo 19', 90000016, NULL, '2025-09-19 05:47:59.757', NULL),
(17, 20, 10000017, 'Javier', 'Pérez', 'Masculino', '1997-09-19', 'Dirección de ejemplo 20', 90000017, NULL, '2025-09-19 05:47:59.757', NULL),
(18, 21, 10000018, 'Pedro', 'Díaz', 'Masculino', '1984-09-19', 'Dirección de ejemplo 21', 90000018, NULL, '2025-09-19 05:47:59.757', NULL),
(19, 22, 10000019, 'Jorge', 'Díaz', 'Masculino', '2004-09-19', 'Dirección de ejemplo 22', 90000019, NULL, '2025-09-19 05:47:59.760', NULL),
(20, 23, 10000020, 'Carlos', 'García', 'Masculino', '1985-09-19', 'Dirección de ejemplo 23', 90000020, NULL, '2025-09-19 05:47:59.760', NULL),
(21, 24, 10000021, 'Juan', 'Ramírez', 'Masculino', '1990-09-19', 'Dirección de ejemplo 24', 90000021, NULL, '2025-09-19 05:47:59.760', NULL),
(22, 25, 10000022, 'Miguel', 'Gómez', 'Masculino', '1984-09-19', 'Dirección de ejemplo 25', 90000022, NULL, '2025-09-19 05:47:59.760', NULL),
(23, 26, 10000023, 'Adriana', 'García', 'Femenino', '1961-09-19', 'Dirección de ejemplo 26', 90000023, NULL, '2025-09-19 05:47:59.760', NULL),
(24, 27, 10000024, 'Jorge', 'Díaz', 'Masculino', '1994-09-19', 'Dirección de ejemplo 27', 90000024, NULL, '2025-09-19 05:47:59.760', NULL),
(25, 28, 10000025, 'Laura', 'Rodríguez', 'Femenino', '1959-09-19', 'Dirección de ejemplo 28', 90000025, NULL, '2025-09-19 05:47:59.760', NULL),
(26, 29, 10000026, 'Gabriela', 'Gómez', 'Femenino', '1976-09-19', 'Dirección de ejemplo 29', 90000026, NULL, '2025-09-19 05:47:59.760', NULL),
(27, 30, 10000027, 'Isabel', 'Gómez', 'Femenino', '1968-09-19', 'Dirección de ejemplo 30', 90000027, NULL, '2025-09-19 05:47:59.763', NULL),
(28, 31, 10000028, 'Daniel', 'García', 'Masculino', '1973-09-19', 'Dirección de ejemplo 31', 90000028, NULL, '2025-09-19 05:47:59.763', NULL),
(29, 32, 10000029, 'Carlos', 'Sánchez', 'Masculino', '1958-09-19', 'Dirección de ejemplo 32', 90000029, NULL, '2025-09-19 05:47:59.763', NULL),
(30, 33, 10000030, 'Pedro', 'López', 'Masculino', '1969-09-19', 'Dirección de ejemplo 33', 90000030, NULL, '2025-09-19 05:47:59.763', NULL),
(31, 34, 10000031, 'David', 'Martínez', 'Masculino', '1961-09-19', 'Dirección de ejemplo 34', 90000031, NULL, '2025-09-19 05:47:59.767', NULL),
(32, 35, 10000032, 'Miguel', 'Pérez', 'Masculino', '1960-09-19', 'Dirección de ejemplo 35', 90000032, NULL, '2025-09-19 05:47:59.767', NULL),
(33, 36, 10000033, 'Valentina', 'Díaz', 'Femenino', '1985-09-19', 'Dirección de ejemplo 36', 90000033, NULL, '2025-09-19 05:47:59.767', NULL),
(34, 37, 10000034, 'Pedro', 'Sánchez', 'Masculino', '1997-09-19', 'Dirección de ejemplo 37', 90000034, NULL, '2025-09-19 05:47:59.767', NULL),
(35, 38, 10000035, 'Juan', 'Gómez', 'Masculino', '2003-09-19', 'Dirección de ejemplo 38', 90000035, NULL, '2025-09-19 05:47:59.770', NULL),
(36, 39, 10000036, 'Jorge', 'López', 'Masculino', '1983-09-19', 'Dirección de ejemplo 39', 90000036, NULL, '2025-09-19 05:47:59.770', NULL),
(37, 40, 10000037, 'Adriana', 'Pérez', 'Femenino', '1989-09-19', 'Dirección de ejemplo 40', 90000037, NULL, '2025-09-19 05:47:59.770', NULL),
(38, 41, 10000038, 'Laura', 'Díaz', 'Femenino', '1977-09-19', 'Dirección de ejemplo 41', 90000038, NULL, '2025-09-19 05:47:59.770', NULL),
(39, 42, 10000039, 'Ana', 'Rodríguez', 'Femenino', '1971-09-19', 'Dirección de ejemplo 42', 90000039, NULL, '2025-09-19 05:47:59.770', NULL),
(40, 43, 10000040, 'Adriana', 'García', 'Femenino', '1998-09-19', 'Dirección de ejemplo 43', 90000040, NULL, '2025-09-19 05:47:59.770', NULL),
(41, 44, 10000041, 'Daniel', 'Díaz', 'Masculino', '1982-09-19', 'Dirección de ejemplo 44', 90000041, NULL, '2025-09-19 05:47:59.770', NULL),
(42, 45, 10000042, 'Jorge', 'Rodríguez', 'Masculino', '2001-09-19', 'Dirección de ejemplo 45', 90000042, NULL, '2025-09-19 05:47:59.773', NULL),
(43, 46, 10000043, 'Adriana', 'Gómez', 'Femenino', '1988-09-19', 'Dirección de ejemplo 46', 90000043, NULL, '2025-09-19 05:47:59.773', NULL),
(44, 47, 10000044, 'Lucía', 'Fernández', 'Femenino', '1974-09-19', 'Dirección de ejemplo 47', 90000044, NULL, '2025-09-19 05:47:59.773', NULL),
(45, 48, 10000045, 'Valentina', 'Martínez', 'Femenino', '1983-09-19', 'Dirección de ejemplo 48', 90000045, NULL, '2025-09-19 05:47:59.773', NULL),
(46, 49, 10000046, 'María', 'Díaz', 'Femenino', '1978-09-19', 'Dirección de ejemplo 49', 90000046, NULL, '2025-09-19 05:47:59.773', NULL),
(47, 50, 10000047, 'David', 'Sánchez', 'Masculino', '1992-09-19', 'Dirección de ejemplo 50', 90000047, NULL, '2025-09-19 05:47:59.777', NULL),
(48, 51, 10000048, 'Daniel', 'Martínez', 'Masculino', '1996-09-19', 'Dirección de ejemplo 51', 90000048, NULL, '2025-09-19 05:47:59.777', NULL),
(49, 52, 10000049, 'María', 'Gómez', 'Femenino', '1956-09-19', 'Dirección de ejemplo 52', 90000049, NULL, '2025-09-19 05:47:59.777', NULL),
(50, 53, 10000050, 'Pedro', 'Díaz', 'Masculino', '1992-09-19', 'Dirección de ejemplo 53', 90000050, NULL, '2025-09-19 05:47:59.777', NULL),
(51, 54, 10000051, 'Juan', 'Fernández', 'Masculino', '1973-09-19', 'Dirección de ejemplo 54', 90000051, NULL, '2025-09-19 05:47:59.777', NULL),
(52, 55, 10000052, 'Laura', 'Ramírez', 'Femenino', '1963-09-19', 'Dirección de ejemplo 55', 90000052, NULL, '2025-09-19 05:47:59.777', NULL),
(53, 56, 10000053, 'Gabriela', 'García', 'Femenino', '1974-09-19', 'Dirección de ejemplo 56', 90000053, NULL, '2025-09-19 05:47:59.780', NULL),
(54, 57, 10000054, 'Adriana', 'Fernández', 'Femenino', '1977-09-19', 'Dirección de ejemplo 57', 90000054, NULL, '2025-09-19 05:47:59.780', NULL),
(55, 58, 10000055, 'Lucía', 'Fernández', 'Femenino', '1961-09-19', 'Dirección de ejemplo 58', 90000055, NULL, '2025-09-19 05:47:59.780', NULL),
(56, 59, 10000056, 'Isabel', 'Rodríguez', 'Femenino', '1960-09-19', 'Dirección de ejemplo 59', 90000056, NULL, '2025-09-19 05:47:59.780', NULL),
(57, 60, 10000057, 'David', 'Martínez', 'Masculino', '2003-09-19', 'Dirección de ejemplo 60', 90000057, NULL, '2025-09-19 05:47:59.780', NULL),
(58, 61, 10000058, 'Jorge', 'Fernández', 'Masculino', '2005-09-19', 'Dirección de ejemplo 61', 90000058, NULL, '2025-09-19 05:47:59.780', NULL),
(59, 62, 10000059, 'Luis', 'Sánchez', 'Masculino', '1961-09-19', 'Dirección de ejemplo 62', 90000059, NULL, '2025-09-19 05:47:59.780', NULL),
(60, 63, 10000060, 'Gabriela', 'Ramírez', 'Femenino', '1971-09-19', 'Dirección de ejemplo 63', 90000060, NULL, '2025-09-19 05:47:59.783', NULL),
(61, 64, 10000061, 'David', 'Ramírez', 'Masculino', '1975-09-19', 'Dirección de ejemplo 64', 90000061, NULL, '2025-09-19 05:47:59.783', NULL),
(62, 65, 10000062, 'María', 'López', 'Femenino', '1976-09-19', 'Dirección de ejemplo 65', 90000062, NULL, '2025-09-19 05:47:59.783', NULL),
(63, 66, 10000063, 'Carolina', 'Rodríguez', 'Femenino', '1977-09-19', 'Dirección de ejemplo 66', 90000063, NULL, '2025-09-19 05:47:59.793', NULL),
(64, 67, 10000064, 'Laura', 'Martínez', 'Femenino', '1991-09-19', 'Dirección de ejemplo 67', 90000064, NULL, '2025-09-19 05:47:59.793', NULL),
(65, 68, 10000065, 'Valentina', 'Gómez', 'Femenino', '1994-09-19', 'Dirección de ejemplo 68', 90000065, NULL, '2025-09-19 05:47:59.793', NULL),
(66, 69, 10000066, 'Pedro', 'Martínez', 'Masculino', '2002-09-19', 'Dirección de ejemplo 69', 90000066, NULL, '2025-09-19 05:47:59.793', NULL),
(67, 70, 10000067, 'Lucía', 'García', 'Femenino', '1995-09-19', 'Dirección de ejemplo 70', 90000067, NULL, '2025-09-19 05:47:59.797', NULL),
(68, 71, 10000068, 'Jorge', 'Sánchez', 'Masculino', '1986-09-19', 'Dirección de ejemplo 71', 90000068, NULL, '2025-09-19 05:47:59.797', NULL),
(69, 72, 10000069, 'Laura', 'Sánchez', 'Femenino', '1980-09-19', 'Dirección de ejemplo 72', 90000069, NULL, '2025-09-19 05:47:59.797', NULL),
(70, 73, 10000070, 'Miguel', 'Ramírez', 'Masculino', '1974-09-19', 'Dirección de ejemplo 73', 90000070, NULL, '2025-09-19 05:47:59.797', NULL),
(71, 74, 10000071, 'Daniel', 'Martínez', 'Masculino', '1987-09-19', 'Dirección de ejemplo 74', 90000071, NULL, '2025-09-19 05:47:59.797', NULL),
(72, 75, 10000072, 'Pedro', 'Pérez', 'Masculino', '2001-09-19', 'Dirección de ejemplo 75', 90000072, NULL, '2025-09-19 05:47:59.800', NULL),
(73, 76, 10000073, 'Diego', 'Pérez', 'Masculino', '1966-09-19', 'Dirección de ejemplo 76', 90000073, NULL, '2025-09-19 05:47:59.800', NULL),
(74, 77, 10000074, 'David', 'Pérez', 'Masculino', '1991-09-19', 'Dirección de ejemplo 77', 90000074, NULL, '2025-09-19 05:47:59.800', NULL),
(75, 78, 10000075, 'Gabriela', 'Ramírez', 'Femenino', '1983-09-19', 'Dirección de ejemplo 78', 90000075, NULL, '2025-09-19 05:47:59.800', NULL),
(76, 79, 10000076, 'Ana', 'López', 'Femenino', '2005-09-19', 'Dirección de ejemplo 79', 90000076, NULL, '2025-09-19 05:47:59.800', NULL),
(77, 80, 10000077, 'Adriana', 'Sánchez', 'Femenino', '1983-09-19', 'Dirección de ejemplo 80', 90000077, NULL, '2025-09-19 05:47:59.800', NULL),
(78, 81, 10000078, 'Ana', 'Fernández', 'Femenino', '2000-09-19', 'Dirección de ejemplo 81', 90000078, NULL, '2025-09-19 05:47:59.800', NULL),
(79, 82, 10000079, 'Javier', 'Pérez', 'Masculino', '2005-09-19', 'Dirección de ejemplo 82', 90000079, NULL, '2025-09-19 05:47:59.800', NULL),
(80, 83, 10000080, 'Valentina', 'Martínez', 'Femenino', '1996-09-19', 'Dirección de ejemplo 83', 90000080, NULL, '2025-09-19 05:47:59.800', NULL),
(81, 84, 70605080, 'Edward Steven', 'Quispe Sanchez', 'M', '2002-02-04', 'Av. Húsares de Junin', 985666330, 'images/pacientes/paciente_pexels-pixabay-268533.jpg', '2025-03-10 06:15:30.843', '2025-03-10 06:15:30.843');

SELECT setval(pg_get_serial_sequence('"paciente"', 'idpaciente'), 81);

-- Datos para cita
INSERT INTO "cita" ("idcita", "idpaciente", "iddoctor", "idenfermera", "fecha_cita", "hora_cita", "motivo", "estado", "created_at", "updated_at") VALUES 
(1, 1, 1, 1, '2025-09-23', '06:21:00.0000000', 'Consulta general', 'Realizado', '2025-09-19 05:56:57.630', '2025-09-19 11:20:22.413'),
(2, 2, 1, 1, '2025-09-21', '07:16:00.0000000', 'Consulta general', 'Realizado', '2025-09-19 05:56:57.630', '2025-09-19 11:25:16.403'),
(3, 3, 1, 1, '2025-09-24', '11:07:00.0000000', 'Consulta general', 'Realizado', '2025-09-19 05:56:57.630', '2025-09-19 11:29:09.193'),
(4, 4, 1, 1, '2025-09-24', '09:50:00.0000000', 'Consulta general', 'Realizado', '2025-09-19 05:56:57.633', '2025-09-19 11:31:08.470'),
(5, 5, 1, 1, '2025-09-23', '11:57:00.0000000', 'Consulta general', 'Realizado', '2025-09-19 05:56:57.633', '2025-09-19 11:31:53.303'),
(6, 6, 1, 1, '2025-09-24', '10:54:00.0000000', 'Consulta general', 'Realizado', '2025-09-19 05:56:57.633', '2025-09-19 11:32:57.263'),
(7, 7, 1, 1, '2025-09-23', '07:09:00.0000000', 'Consulta general', 'Realizado', '2025-09-19 05:56:57.633', '2025-09-19 11:33:33.920'),
(8, 8, 1, 1, '2025-09-22', '15:34:00.0000000', 'Consulta general', 'Realizado', '2025-09-19 05:56:57.633', '2025-09-19 11:35:47.937'),
(9, 9, 1, 1, '2025-09-22', '15:04:00.0000000', 'Consulta general', 'Realizado', '2025-09-19 05:56:57.633', '2025-09-19 11:36:40.097'),
(10, 10, 1, 1, '2025-09-23', '15:27:00.0000000', 'Consulta general', 'Realizado', '2025-09-19 05:56:57.633', '2025-09-19 11:37:28.227'),
(11, 11, 1, 1, '2025-09-24', '16:47:00.0000000', 'Consulta general', 'Realizado', '2025-09-19 05:56:57.637', '2025-09-19 11:39:54.427'),
(12, 12, 1, 1, '2025-09-21', '13:45:00.0000000', 'Consulta general', 'Realizado', '2025-09-19 05:56:57.637', '2025-09-19 11:40:31.110'),
(13, 13, 1, 1, '2025-09-22', '07:14:00.0000000', 'Consulta general', 'Realizado', '2025-09-19 05:56:57.637', '2025-09-19 11:41:19.040'),
(14, 14, 1, 1, '2025-09-21', '15:51:00.0000000', 'Consulta general', 'Realizado', '2025-09-19 05:56:57.637', '2025-09-19 11:42:37.607'),
(15, 15, 1, 1, '2025-09-23', '10:41:00.0000000', 'Consulta general', 'Realizado', '2025-09-19 05:56:57.637', '2025-09-19 11:43:14.797'),
(16, 16, 1, 1, '2025-09-21', '17:22:00.0000000', 'Consulta general', 'Realizado', '2025-09-19 05:56:57.637', '2025-09-19 11:44:22.533'),
(17, 17, 1, 1, '2025-09-24', '11:38:00.0000000', 'Consulta general', 'Realizado', '2025-09-19 05:56:57.637', '2025-09-19 11:45:13.280'),
(18, 18, 1, 1, '2025-09-23', '08:55:00.0000000', 'Consulta general', 'Realizado', '2025-09-19 05:56:57.640', '2025-09-19 11:46:07.097'),
(19, 19, 1, 1, '2025-09-24', '12:00:00.0000000', 'Consulta general', 'Realizado', '2025-09-19 05:56:57.640', '2025-09-19 11:46:40.967'),
(20, 20, 1, 1, '2025-09-22', '09:37:00.0000000', 'Consulta general', 'Realizado', '2025-09-19 05:56:57.640', '2025-09-19 11:47:18.197'),
(21, 21, 1, 1, '2025-09-23', '13:34:00.0000000', 'Consulta general', 'Realizado', '2025-09-19 05:56:57.640', '2025-09-19 11:48:07.533'),
(22, 22, 1, 1, '2025-09-24', '17:40:00.0000000', 'Consulta general', 'Realizado', '2025-09-19 05:56:57.640', '2025-09-19 11:48:31.270'),
(23, 23, 1, 1, '2025-09-22', '13:45:00.0000000', 'Consulta general', 'Realizado', '2025-09-19 05:56:57.640', '2025-09-19 11:49:06.090'),
(24, 24, 1, 1, '2025-09-24', '09:22:00.0000000', 'Consulta general', 'Realizado', '2025-09-19 05:56:57.640', '2025-09-19 11:50:41.573'),
(25, 25, 1, 1, '2025-09-24', '09:46:00.0000000', 'Consulta general', 'Realizado', '2025-09-19 05:56:57.640', '2025-09-19 11:51:12.367'),
(26, 26, 1, 1, '2025-09-22', '15:31:00.0000000', 'Consulta general', 'Realizado', '2025-09-19 05:56:57.640', '2025-09-19 11:51:56.880'),
(27, 27, 1, 1, '2025-09-22', '14:20:00.0000000', 'Consulta general', 'Realizado', '2025-09-19 05:56:57.640', '2025-09-19 11:52:48.597'),
(28, 28, 1, 1, '2025-09-22', '11:02:00.0000000', 'Consulta general', 'Realizado', '2025-09-19 05:56:57.640', '2025-09-19 11:54:21.910'),
(29, 29, 1, 1, '2025-09-24', '16:30:00.0000000', 'Consulta general', 'Realizado', '2025-09-19 05:56:57.643', '2025-09-19 11:54:57.350'),
(30, 30, 1, 1, '2025-09-23', '17:50:00.0000000', 'Consulta general', 'Realizado', '2025-09-19 05:56:57.643', '2025-09-19 11:55:46.807'),
(31, 31, 1, 1, '2025-09-21', '16:38:00.0000000', 'Consulta general', 'Realizado', '2025-09-19 05:56:57.643', '2025-09-19 11:56:22.517'),
(32, 32, 1, 1, '2025-09-24', '15:53:00.0000000', 'Consulta general', 'Realizado', '2025-09-19 05:56:57.643', '2025-09-19 11:56:53.803'),
(33, 33, 1, 1, '2025-09-22', '13:33:00.0000000', 'Consulta general', 'Realizado', '2025-09-19 05:56:57.643', '2025-09-19 11:57:44.190'),
(34, 34, 1, 1, '2025-09-24', '11:55:00.0000000', 'Consulta general', 'Realizado', '2025-09-19 05:56:57.643', '2025-09-19 11:58:58.930'),
(35, 35, 1, 1, '2025-09-22', '08:51:00.0000000', 'Consulta general', 'Realizado', '2025-09-19 05:56:57.643', '2025-09-19 11:59:31.023'),
(36, 36, 1, 1, '2025-09-23', '16:10:00.0000000', 'Consulta general', 'Realizado', '2025-09-19 05:56:57.647', '2025-09-19 12:00:18.097'),
(37, 37, 1, 1, '2025-09-23', '09:49:00.0000000', 'Consulta general', 'Realizado', '2025-09-19 05:56:57.647', '2025-09-19 12:01:02.407'),
(38, 38, 1, 1, '2025-09-21', '16:33:00.0000000', 'Consulta general', 'Realizado', '2025-09-19 05:56:57.647', '2025-09-19 12:01:35.117'),
(39, 39, 1, 1, '2025-09-23', '14:27:00.0000000', 'Consulta general', 'Realizado', '2025-09-19 05:56:57.647', '2025-09-19 12:03:02.667'),
(40, 40, 1, 1, '2025-09-24', '08:11:00.0000000', 'Consulta general', 'Realizado', '2025-09-19 05:56:57.647', '2025-09-19 12:04:22.737'),
(41, 41, 1, 1, '2025-09-21', '16:20:00.0000000', 'Consulta general', 'Realizado', '2025-09-19 05:56:57.647', '2025-09-19 12:08:16.477'),
(42, 42, 1, 1, '2025-09-22', '16:00:00.0000000', 'Consulta general', 'Realizado', '2025-09-19 05:56:57.650', '2025-09-22 20:43:13.443'),
(43, 43, 1, 1, '2025-09-22', '11:53:00.0000000', 'Consulta general', 'Realizado', '2025-09-19 05:56:57.650', '2025-09-22 21:11:52.430'),
(44, 44, 1, 1, '2025-09-21', '09:09:00.0000000', 'Consulta general', 'Realizado', '2025-09-19 05:56:57.650', '2025-09-22 21:14:14.803'),
(45, 45, 1, 1, '2025-09-21', '13:33:00.0000000', 'Consulta general', 'Realizado', '2025-09-19 05:56:57.650', '2025-09-22 21:28:53.963'),
(46, 46, 1, 1, '2025-09-24', '10:20:00.0000000', 'Consulta general', 'Realizado', '2025-09-19 05:56:57.650', '2025-09-22 21:33:04.600'),
(47, 47, 1, 1, '2025-09-23', '09:46:00.0000000', 'Consulta general', 'Realizado', '2025-09-19 05:56:57.650', '2025-09-22 21:43:33.613'),
(48, 48, 1, 1, '2025-09-24', '10:36:00.0000000', 'Consulta general', 'Realizado', '2025-09-19 05:56:57.650', '2025-09-22 21:44:09.450'),
(49, 49, 1, 1, '2025-09-22', '17:48:00.0000000', 'Consulta general', 'Realizado', '2025-09-19 05:56:57.650', '2025-09-22 21:44:40.370'),
(50, 50, 1, 1, '2025-09-24', '16:34:00.0000000', 'Consulta general', 'Realizado', '2025-09-19 05:56:57.650', '2025-09-22 21:45:11.483'),
(51, 51, 1, 1, '2025-09-22', '07:08:00.0000000', 'Consulta general', 'Realizado', '2025-09-19 05:56:57.653', '2025-09-22 21:45:38.420'),
(52, 52, 1, 1, '2025-09-23', '12:11:00.0000000', 'Consulta general', 'Realizado', '2025-09-19 05:56:57.653', '2025-09-22 21:47:33.770'),
(53, 53, 1, 1, '2025-09-24', '10:12:00.0000000', 'Consulta general', 'Realizado', '2025-09-19 05:56:57.653', '2025-09-22 21:48:22.620'),
(54, 54, 1, 1, '2025-09-21', '12:41:00.0000000', 'Consulta general', 'Realizado', '2025-09-19 05:56:57.653', '2025-09-22 21:49:39.900'),
(55, 55, 1, 1, '2025-09-21', '12:23:00.0000000', 'Consulta general', 'Realizado', '2025-09-19 05:56:57.653', '2025-09-22 21:50:23.920'),
(56, 56, 1, 1, '2025-09-21', '09:36:00.0000000', 'Consulta general', 'Realizado', '2025-09-19 05:56:57.653', '2025-09-22 21:51:04.880'),
(57, 57, 1, 1, '2025-09-22', '07:58:00.0000000', 'Consulta general', 'Realizado', '2025-09-19 05:56:57.653', '2025-09-22 21:51:36.193'),
(58, 58, 1, 1, '2025-09-22', '10:34:00.0000000', 'Consulta general', 'Realizado', '2025-09-19 05:56:57.653', '2025-09-22 21:52:20.843'),
(59, 59, 1, 1, '2025-09-24', '15:24:00.0000000', 'Consulta general', 'Realizado', '2025-09-19 05:56:57.657', '2025-09-22 21:53:23.790'),
(60, 60, 1, 1, '2025-09-23', '06:02:00.0000000', 'Consulta general', 'Realizado', '2025-09-19 05:56:57.657', '2025-09-22 21:54:01.000'),
(61, 61, 1, 1, '2025-09-24', '07:10:00.0000000', 'Consulta general', 'Realizado', '2025-09-19 05:56:57.657', '2025-09-22 21:54:28.537'),
(62, 62, 1, 1, '2025-09-21', '12:40:00.0000000', 'Consulta general', 'Realizado', '2025-09-19 05:56:57.657', '2025-09-22 21:55:07.233'),
(63, 63, 1, 1, '2025-09-22', '07:28:00.0000000', 'Consulta general', 'Realizado', '2025-09-19 05:56:57.657', '2025-09-22 21:55:34.467'),
(64, 64, 1, 1, '2025-09-23', '08:55:00.0000000', 'Consulta general', 'Realizado', '2025-09-19 05:56:57.657', '2025-09-22 21:56:23.443'),
(65, 65, 1, 1, '2025-09-23', '11:34:00.0000000', 'Consulta general', 'Realizado', '2025-09-19 05:56:57.660', '2025-09-22 21:56:57.273'),
(66, 66, 1, 1, '2025-09-21', '06:26:00.0000000', 'Consulta general', 'Realizado', '2025-09-19 05:56:57.660', '2025-09-22 21:57:35.213'),
(67, 67, 1, 1, '2025-09-21', '11:59:00.0000000', 'Consulta general', 'Realizado', '2025-09-19 05:56:57.660', '2025-09-22 21:58:26.737'),
(68, 68, 1, 1, '2025-09-22', '17:51:00.0000000', 'Consulta general', 'Realizado', '2025-09-19 05:56:57.660', '2025-09-22 21:59:17.613'),
(69, 69, 1, 1, '2025-09-22', '11:48:00.0000000', 'Consulta general', 'Realizado', '2025-09-19 05:56:57.660', '2025-09-22 21:59:58.300'),
(70, 70, 1, 1, '2025-09-23', '09:35:00.0000000', 'Consulta general', 'Realizado', '2025-09-19 05:56:57.660', '2025-09-22 22:00:41.837'),
(71, 71, 1, 1, '2025-09-21', '07:50:00.0000000', 'Consulta general', 'Realizado', '2025-09-19 05:56:57.660', '2025-09-22 22:01:11.647'),
(72, 72, 1, 1, '2025-09-24', '16:12:00.0000000', 'Consulta general', 'Realizado', '2025-09-19 05:56:57.660', '2025-09-22 22:01:37.153'),
(73, 73, 1, 1, '2025-09-24', '11:48:00.0000000', 'Consulta general', 'Realizado', '2025-09-19 05:56:57.660', '2025-09-22 22:02:09.573'),
(74, 74, 1, 1, '2025-09-21', '07:38:00.0000000', 'Consulta general', 'Realizado', '2025-09-19 05:56:57.660', '2025-09-22 22:02:39.380'),
(75, 75, 1, 1, '2025-09-23', '07:02:00.0000000', 'Consulta general', 'Realizado', '2025-09-19 05:56:57.663', '2025-09-22 22:03:11.870'),
(76, 76, 1, 1, '2025-09-21', '16:52:00.0000000', 'Consulta general', 'Realizado', '2025-09-19 05:56:57.663', '2025-09-22 22:03:43.553'),
(77, 77, 1, 1, '2025-09-21', '14:28:00.0000000', 'Consulta general', 'Realizado', '2025-09-19 05:56:57.663', '2025-09-22 22:04:23.180'),
(78, 78, 1, 1, '2025-09-23', '11:48:00.0000000', 'Consulta general', 'Realizado', '2025-09-19 05:56:57.663', '2025-09-22 22:04:56.693'),
(79, 79, 1, 1, '2025-09-22', '11:45:00.0000000', 'Consulta general', 'Realizado', '2025-09-19 05:56:57.663', '2025-09-22 22:05:33.773'),
(80, 80, 1, 1, '2025-09-22', '06:46:00.0000000', 'Consulta general', 'Realizado', '2025-09-19 05:56:57.663', '2025-09-22 22:06:11.053'),
(81, 67, 1, 1, '2025-10-04', '21:52:00.0000000', 'aumento de glucosa', 'Realizado', '2025-03-10 02:52:36.377', '2025-03-10 03:03:17.507'),
(82, 7, 1, 1, '2025-10-14', '10:09:00.0000000', 'presenta dolores de pie con herida escamosa', 'Realizado', '2025-03-10 03:10:06.063', '2025-03-10 03:15:24.020'),
(83, 4, 1, 1, '2025-10-15', '10:42:00.0000000', 'consulta de su estado de diabetes', 'Realizado', '2025-03-10 03:42:49.427', '2025-03-10 03:47:00.790'),
(84, 8, 1, 1, '2025-10-10', '11:19:00.0000000', 'problemas en la digestion', 'Realizado', '2025-03-10 04:19:49.700', '2025-03-10 04:21:30.150'),
(85, 81, 1, 1, '2025-10-06', '13:16:00.0000000', 'presenta sintomas de diabetes segun declaraciones del paciente', 'Realizado', '2025-03-10 06:16:35.973', '2025-03-10 06:44:23.947'),
(86, 81, 1, 1, '2025-10-05', '08:34:00.0000000', 'presenta exceso consumo de agua', 'Realizado', '2025-03-10 15:40:12.747', '2025-03-10 15:46:28.773');

SELECT setval(pg_get_serial_sequence('"cita"', 'idcita'), 86);

-- Datos para triaje
INSERT INTO "triaje" ("idtriaje", "idcita", "edad", "talla", "peso", "BMI", "grosor_piel", "created_at", "updated_at", "observaciones") VALUES 
(1, 1, 27, 1.57, 79.94, 32.43, 43, '2025-09-19 06:06:46.157', NULL, 'Triaje de rutina, datos de edad obtenidos de la fecha de nacimiento'),
(2, 2, 53, 1.72, 77.35, 26.15, 42, '2025-09-19 06:06:46.160', NULL, 'Triaje de rutina, datos de edad obtenidos de la fecha de nacimiento'),
(3, 3, 47, 1.59, 71.53, 28.29, 35, '2025-09-19 06:06:46.160', NULL, 'Triaje de rutina, datos de edad obtenidos de la fecha de nacimiento'),
(4, 4, 45, 1.64, 53.47, 19.88, 36, '2025-09-19 06:06:46.160', NULL, 'Triaje de rutina, datos de edad obtenidos de la fecha de nacimiento'),
(5, 5, 63, 1.89, 99.56, 27.87, 36, '2025-09-19 06:06:46.160', NULL, 'Triaje de rutina, datos de edad obtenidos de la fecha de nacimiento'),
(6, 6, 46, 1.52, 98.46, 42.62, 33, '2025-09-19 06:06:46.160', NULL, 'Triaje de rutina, datos de edad obtenidos de la fecha de nacimiento'),
(7, 7, 59, 1.55, 74.40, 30.97, 46, '2025-09-19 06:06:46.160', NULL, 'Triaje de rutina, datos de edad obtenidos de la fecha de nacimiento'),
(8, 8, 30, 1.66, 56.28, 20.42, 23, '2025-09-19 06:06:46.160', NULL, 'Triaje de rutina, datos de edad obtenidos de la fecha de nacimiento'),
(9, 9, 60, 1.80, 82.19, 25.37, 30, '2025-09-19 06:06:46.160', NULL, 'Triaje de rutina, datos de edad obtenidos de la fecha de nacimiento'),
(10, 10, 39, 1.60, 95.03, 37.12, 20, '2025-09-19 06:06:46.160', NULL, 'Triaje de rutina, datos de edad obtenidos de la fecha de nacimiento'),
(11, 11, 55, 1.67, 64.36, 23.08, 24, '2025-09-19 06:06:46.163', NULL, 'Triaje de rutina, datos de edad obtenidos de la fecha de nacimiento'),
(12, 12, 29, 1.62, 80.28, 30.59, 34, '2025-09-19 06:06:46.163', NULL, 'Triaje de rutina, datos de edad obtenidos de la fecha de nacimiento'),
(13, 13, 47, 1.77, 89.73, 28.64, 33, '2025-09-19 06:06:46.163', NULL, 'Triaje de rutina, datos de edad obtenidos de la fecha de nacimiento'),
(14, 14, 68, 1.72, 79.76, 26.96, 41, '2025-09-19 06:06:46.163', NULL, 'Triaje de rutina, datos de edad obtenidos de la fecha de nacimiento'),
(15, 15, 58, 1.71, 84.99, 29.07, 40, '2025-09-19 06:06:46.163', NULL, 'Triaje de rutina, datos de edad obtenidos de la fecha de nacimiento'),
(16, 16, 51, 1.56, 68.45, 28.13, 25, '2025-09-19 06:06:46.163', NULL, 'Triaje de rutina, datos de edad obtenidos de la fecha de nacimiento'),
(17, 17, 28, 1.66, 89.93, 32.64, 47, '2025-09-19 06:06:46.163', NULL, 'Triaje de rutina, datos de edad obtenidos de la fecha de nacimiento'),
(18, 18, 41, 1.71, 51.65, 17.66, 42, '2025-09-19 06:06:46.163', NULL, 'Triaje de rutina, datos de edad obtenidos de la fecha de nacimiento'),
(19, 19, 21, 1.84, 59.07, 17.45, 29, '2025-09-19 06:06:46.167', NULL, 'Triaje de rutina, datos de edad obtenidos de la fecha de nacimiento'),
(20, 20, 40, 1.80, 94.49, 29.16, 44, '2025-09-19 06:06:46.167', NULL, 'Triaje de rutina, datos de edad obtenidos de la fecha de nacimiento'),
(21, 21, 35, 1.64, 60.41, 22.46, 42, '2025-09-19 06:06:46.167', NULL, 'Triaje de rutina, datos de edad obtenidos de la fecha de nacimiento'),
(22, 22, 41, 1.77, 89.11, 28.44, 46, '2025-09-19 06:06:46.167', NULL, 'Triaje de rutina, datos de edad obtenidos de la fecha de nacimiento'),
(23, 23, 64, 1.53, 77.33, 33.03, 48, '2025-09-19 06:06:46.167', NULL, 'Triaje de rutina, datos de edad obtenidos de la fecha de nacimiento'),
(24, 24, 31, 1.71, 72.65, 24.85, 20, '2025-09-19 06:06:46.167', NULL, 'Triaje de rutina, datos de edad obtenidos de la fecha de nacimiento'),
(25, 25, 66, 1.66, 62.36, 22.63, 30, '2025-09-19 06:06:46.167', NULL, 'Triaje de rutina, datos de edad obtenidos de la fecha de nacimiento'),
(26, 26, 49, 1.62, 53.34, 20.32, 40, '2025-09-19 06:06:46.167', NULL, 'Triaje de rutina, datos de edad obtenidos de la fecha de nacimiento'),
(27, 27, 57, 1.79, 78.23, 24.42, 36, '2025-09-19 06:06:46.170', NULL, 'Triaje de rutina, datos de edad obtenidos de la fecha de nacimiento'),
(28, 28, 52, 1.89, 74.14, 20.76, 47, '2025-09-19 06:06:46.170', NULL, 'Triaje de rutina, datos de edad obtenidos de la fecha de nacimiento'),
(29, 29, 67, 1.60, 97.95, 38.26, 39, '2025-09-19 06:06:46.170', NULL, 'Triaje de rutina, datos de edad obtenidos de la fecha de nacimiento'),
(30, 30, 56, 1.50, 68.19, 30.31, 28, '2025-09-19 06:06:46.170', NULL, 'Triaje de rutina, datos de edad obtenidos de la fecha de nacimiento'),
(31, 31, 64, 1.71, 71.69, 24.52, 30, '2025-09-19 06:06:46.170', NULL, 'Triaje de rutina, datos de edad obtenidos de la fecha de nacimiento'),
(32, 32, 65, 1.89, 50.90, 14.25, 22, '2025-09-19 06:06:46.170', NULL, 'Triaje de rutina, datos de edad obtenidos de la fecha de nacimiento'),
(33, 33, 40, 1.57, 73.64, 29.88, 34, '2025-09-19 06:06:46.170', NULL, 'Triaje de rutina, datos de edad obtenidos de la fecha de nacimiento'),
(34, 34, 28, 1.86, 83.25, 24.06, 28, '2025-09-19 06:06:46.173', NULL, 'Triaje de rutina, datos de edad obtenidos de la fecha de nacimiento'),
(35, 35, 22, 1.85, 54.12, 15.81, 38, '2025-09-19 06:06:46.173', NULL, 'Triaje de rutina, datos de edad obtenidos de la fecha de nacimiento'),
(36, 36, 42, 1.57, 87.94, 35.68, 34, '2025-09-19 06:06:46.173', NULL, 'Triaje de rutina, datos de edad obtenidos de la fecha de nacimiento'),
(37, 37, 36, 1.88, 53.09, 15.02, 45, '2025-09-19 06:06:46.173', NULL, 'Triaje de rutina, datos de edad obtenidos de la fecha de nacimiento'),
(38, 38, 48, 1.75, 60.20, 19.66, 35, '2025-09-19 06:06:46.173', NULL, 'Triaje de rutina, datos de edad obtenidos de la fecha de nacimiento'),
(39, 39, 54, 1.56, 96.10, 39.49, 24, '2025-09-19 06:06:46.173', NULL, 'Triaje de rutina, datos de edad obtenidos de la fecha de nacimiento'),
(40, 40, 27, 1.72, 50.18, 16.96, 20, '2025-09-19 06:06:46.173', NULL, 'Triaje de rutina, datos de edad obtenidos de la fecha de nacimiento'),
(41, 41, 43, 1.57, 69.92, 28.37, 25, '2025-09-19 06:06:46.173', NULL, 'Triaje de rutina, datos de edad obtenidos de la fecha de nacimiento'),
(42, 42, 24, 1.63, 65.91, 24.81, 42, '2025-09-19 06:06:46.177', NULL, 'Triaje de rutina, datos de edad obtenidos de la fecha de nacimiento'),
(43, 43, 37, 1.90, 89.32, 24.74, 27, '2025-09-19 06:06:46.177', NULL, 'Triaje de rutina, datos de edad obtenidos de la fecha de nacimiento'),
(44, 44, 51, 1.81, 61.07, 18.64, 45, '2025-09-19 06:06:46.177', NULL, 'Triaje de rutina, datos de edad obtenidos de la fecha de nacimiento'),
(45, 45, 42, 1.56, 51.74, 21.26, 43, '2025-09-19 06:06:46.177', NULL, 'Triaje de rutina, datos de edad obtenidos de la fecha de nacimiento'),
(46, 46, 47, 1.67, 62.62, 22.45, 39, '2025-09-19 06:06:46.177', NULL, 'Triaje de rutina, datos de edad obtenidos de la fecha de nacimiento'),
(47, 47, 33, 1.65, 90.33, 33.18, 31, '2025-09-19 06:06:46.177', NULL, 'Triaje de rutina, datos de edad obtenidos de la fecha de nacimiento'),
(48, 48, 29, 1.55, 83.43, 34.73, 47, '2025-09-19 06:06:46.177', NULL, 'Triaje de rutina, datos de edad obtenidos de la fecha de nacimiento'),
(49, 49, 69, 1.89, 74.44, 20.84, 48, '2025-09-19 06:06:46.177', NULL, 'Triaje de rutina, datos de edad obtenidos de la fecha de nacimiento'),
(50, 50, 33, 1.75, 64.83, 21.17, 33, '2025-09-19 06:06:46.177', NULL, 'Triaje de rutina, datos de edad obtenidos de la fecha de nacimiento'),
(51, 51, 52, 1.71, 92.48, 31.63, 22, '2025-09-19 06:06:46.180', NULL, 'Triaje de rutina, datos de edad obtenidos de la fecha de nacimiento'),
(52, 52, 62, 1.82, 90.15, 27.22, 49, '2025-09-19 06:06:46.180', NULL, 'Triaje de rutina, datos de edad obtenidos de la fecha de nacimiento'),
(53, 53, 51, 1.52, 51.40, 22.25, 23, '2025-09-19 06:06:46.180', NULL, 'Triaje de rutina, datos de edad obtenidos de la fecha de nacimiento'),
(54, 54, 48, 1.61, 90.75, 35.01, 42, '2025-09-19 06:06:46.180', NULL, 'Triaje de rutina, datos de edad obtenidos de la fecha de nacimiento'),
(55, 55, 64, 1.77, 65.96, 21.05, 22, '2025-09-19 06:06:46.180', NULL, 'Triaje de rutina, datos de edad obtenidos de la fecha de nacimiento'),
(56, 56, 65, 1.60, 72.78, 28.43, 46, '2025-09-19 06:06:46.180', NULL, 'Triaje de rutina, datos de edad obtenidos de la fecha de nacimiento'),
(57, 57, 22, 1.53, 74.64, 31.89, 20, '2025-09-19 06:06:46.180', NULL, 'Triaje de rutina, datos de edad obtenidos de la fecha de nacimiento'),
(58, 58, 20, 1.85, 62.51, 18.26, 46, '2025-09-19 06:06:46.180', NULL, 'Triaje de rutina, datos de edad obtenidos de la fecha de nacimiento'),
(59, 59, 64, 1.87, 67.39, 19.27, 47, '2025-09-19 06:06:46.180', NULL, 'Triaje de rutina, datos de edad obtenidos de la fecha de nacimiento'),
(60, 60, 54, 1.57, 51.72, 20.98, 44, '2025-09-19 06:06:46.180', NULL, 'Triaje de rutina, datos de edad obtenidos de la fecha de nacimiento'),
(61, 61, 50, 1.86, 67.45, 19.50, 24, '2025-09-19 06:06:46.180', NULL, 'Triaje de rutina, datos de edad obtenidos de la fecha de nacimiento'),
(62, 62, 49, 1.63, 68.96, 25.96, 45, '2025-09-19 06:06:46.200', NULL, 'Triaje de rutina, datos de edad obtenidos de la fecha de nacimiento'),
(63, 63, 48, 1.55, 65.51, 27.27, 37, '2025-09-19 06:06:46.200', NULL, 'Triaje de rutina, datos de edad obtenidos de la fecha de nacimiento'),
(64, 64, 34, 1.57, 57.25, 23.23, 45, '2025-09-19 06:06:46.203', NULL, 'Triaje de rutina, datos de edad obtenidos de la fecha de nacimiento'),
(65, 65, 31, 1.63, 87.72, 33.02, 38, '2025-09-19 06:06:46.203', NULL, 'Triaje de rutina, datos de edad obtenidos de la fecha de nacimiento'),
(66, 66, 23, 1.71, 52.57, 17.98, 30, '2025-09-19 06:06:46.203', NULL, 'Triaje de rutina, datos de edad obtenidos de la fecha de nacimiento'),
(67, 67, 30, 1.84, 58.37, 17.24, 26, '2025-09-19 06:06:46.203', NULL, 'Triaje de rutina, datos de edad obtenidos de la fecha de nacimiento'),
(68, 68, 39, 1.66, 93.49, 33.93, 47, '2025-09-19 06:06:46.203', NULL, 'Triaje de rutina, datos de edad obtenidos de la fecha de nacimiento'),
(69, 69, 45, 1.88, 72.65, 20.56, 30, '2025-09-19 06:06:46.203', NULL, 'Triaje de rutina, datos de edad obtenidos de la fecha de nacimiento'),
(70, 70, 51, 1.80, 65.11, 20.10, 39, '2025-09-19 06:06:46.203', NULL, 'Triaje de rutina, datos de edad obtenidos de la fecha de nacimiento'),
(71, 71, 38, 1.78, 70.06, 22.11, 49, '2025-09-19 06:06:46.207', NULL, 'Triaje de rutina, datos de edad obtenidos de la fecha de nacimiento'),
(72, 72, 24, 1.56, 86.78, 35.66, 44, '2025-09-19 06:06:46.207', NULL, 'Triaje de rutina, datos de edad obtenidos de la fecha de nacimiento'),
(73, 73, 59, 1.63, 50.61, 19.05, 20, '2025-09-19 06:06:46.207', NULL, 'Triaje de rutina, datos de edad obtenidos de la fecha de nacimiento'),
(74, 74, 34, 1.53, 95.62, 40.85, 33, '2025-09-19 06:06:46.207', NULL, 'Triaje de rutina, datos de edad obtenidos de la fecha de nacimiento'),
(75, 75, 42, 1.59, 86.21, 34.10, 42, '2025-09-19 06:06:46.207', NULL, 'Triaje de rutina, datos de edad obtenidos de la fecha de nacimiento'),
(76, 76, 20, 1.89, 74.10, 20.74, 32, '2025-09-19 06:06:46.207', NULL, 'Triaje de rutina, datos de edad obtenidos de la fecha de nacimiento'),
(77, 77, 42, 1.82, 62.86, 18.98, 49, '2025-09-19 06:06:46.207', NULL, 'Triaje de rutina, datos de edad obtenidos de la fecha de nacimiento'),
(78, 78, 25, 1.90, 89.93, 24.91, 21, '2025-09-19 06:06:46.210', NULL, 'Triaje de rutina, datos de edad obtenidos de la fecha de nacimiento'),
(79, 79, 20, 1.71, 52.32, 17.89, 38, '2025-09-19 06:06:46.210', NULL, 'Triaje de rutina, datos de edad obtenidos de la fecha de nacimiento'),
(80, 80, 29, 1.51, 94.52, 41.45, 48, '2025-09-19 06:06:46.210', NULL, 'Triaje de rutina, datos de edad obtenidos de la fecha de nacimiento'),
(81, 81, 60, 145.00, 65.00, 30.90, 20, '2025-03-10 02:53:45.177', '2025-03-10 02:53:45.177', 'llegó con aumento de glucosa y presion alta'),
(82, 82, 65, 160.00, 55.00, 21.50, 13, '2025-03-10 03:11:53.007', '2025-03-10 03:11:53.007', 'la paciente llegó con dolores musculares'),
(83, 83, 75, 155.00, 50.00, 20.80, 30, '2025-03-10 03:43:40.843', '2025-03-10 03:43:40.843', 'presenta presion alta'),
(84, 84, 50, 155.00, 65.00, 27.10, 40, '2025-03-10 04:20:29.423', '2025-03-10 04:20:29.423', 'presenta presión alta'),
(85, 85, 50, 170.00, 85.00, 29.40, 30, '2025-03-10 06:17:26.490', '2025-03-10 06:17:26.490', 'presenta glucosa elevada y presión alta'),
(86, 86, 50, 167.00, 85.00, 30.50, 30, '2025-03-10 15:42:07.393', '2025-03-10 15:42:07.393', 'presenta fiebre de 38° con excesiva  elevación de glucosa');

SELECT setval(pg_get_serial_sequence('"triaje"', 'idtriaje'), 86);

-- Datos para prediccion
INSERT INTO "prediccion" ("idprediccion", "idcita", "glucosa", "presion_sanguinea", "grosor_piel", "embarazos", "BMI", "pedigree", "edad", "resultado", "observacion", "created_at", "updated_at", "insulina", "timer", "timer_inicio", "timer_parada", "analisis_ia", "attachment_paths", "attachment_names", "validar_prediccion") VALUES 
(2, 1, 165.0, 132.0, 43.0, 0, 32.43, 1.0, 27, .000, 'glucosa elevada', '2025-09-19 11:20:22.397', '2025-09-22 22:40:35.797', 132.0, .00, '2025-12-19 15:02:32.00', '2025-12-19 15:02:32.00', '<ul>Como asistente médico experto en diabetes tipo 2, he revisado cuidadosamente la información del paciente, Javier Martínez, y los documentos adjuntos.<br />
---<br /><strong>Contenido de los Archivos Adjuntos (1766156517_694568e529504_DATOS CLÍNICOS DEL PACIENTE.docx):</strong><br>
El documento adjunto titulado "DATOS CLÍNICOS DEL PACIENTE - PERSONA CON DIABETES" proporciona valores clínicos adicionales para Javier Martínez, incluyendo:<br /><li>  Número de Embarazos: N/A</li><li>  🍯 Glucosa (mg/dL): 210</li><li>  Presión Sanguínea (mmHg): 140/90</li><li>  Grosor de Piel (mm): 48</li><li>  💉 Insulina (µU/mL): 250</li><li>  Función Pedigree de Diabetes: 1.25</li>---<br /><strong>Consolidación y Análisis de la Información del Paciente:</strong><br>
Tras revisar ambos conjuntos de datos, se observan algunas discrepancias cruciales que deben abordarse. Daré prioridad a los datos del documento "DATOS CLÍNICOS DEL PACIENTE" ya que suelen ser más completos o actualizados.<br /><strong>Javier Martínez</strong><br><li><strong>Sexo:</strong> M</li><li><strong>Edad:</strong> 27 años</li><li><strong>Número de Embarazos:</strong> 0 (N/A)</li><li><strong>🍯 Glucosa:</strong> 210 mg/dL (Valor crítico elevado, consistente en ambos documentos)</li><li><strong>Presión Sanguínea:</strong> 140/90 mmHg (Tomado del documento adjunto, es más completo que 130 mmHg)</li><li><strong>Grosor de la Piel:</strong> 48 mm (Tomado del documento adjunto)</li><li><strong>💉 Insulina:</strong><strong>CRÍTICA DISCREPANCIA:</strong></li>    *   Información inicial: 1.2 muU/ml (asumo µU/mL, muy bajo)<br />
    *   Documento adjunto: 250 µU/mL (extremadamente alto)<br />
    Esta diferencia es fundamental para el diagnóstico diferencial y el plan terapéutico. Se discutirá en detalle.<br /><li><strong>⚖️ IMC:</strong> 32.43 kg/m²</li><li><strong>Función Pedigree de Diabetes:</strong> 1.25 (Consistente en ambos documentos)</li><li><strong>Observaciones Médicas:</strong> Hemorragia nasal</li>---<br /><strong>Análisis Detallado:</strong><br>
1.  <strong>🍯 Glucosa (210 mg/dL):</strong> Este valor es significativamente elevado. Un nivel de 🍯 glucosa en plasma en ayunas de ≥ 126 mg/dL o un nivel de 🍯 glucosa en plasma aleatorio (no en ayunas) de ≥ 200 mg/dL con síntomas de hiperglucemia (o una prueba de tolerancia a la 🍯 glucosa oral de 2 horas ≥ 200 mg/dL o una 🩸 HbA1c ≥ 6.5%) son diagnósticos de diabetes. El valor de 210 mg/dL es claramente indicativo de hiperglucemia y, si es un valor aleatorio o en ayunas, cumple los criterios para un posible diagnóstico de Diabetes Mellitus.<br />
2.  <strong>Presión Sanguínea (140/90 mmHg):</strong> Ambos valores (sistólico y diastólico) están elevados, clasificándose como Hipertensión Etapa 2 según las guías actuales.<br />
3.  <strong>⚖️ IMC (32.43 kg/m²):</strong> Este valor lo clasifica como Obesidad Clase I, un factor de riesgo muy importante para la diabetes tipo 2 y enfermedades cardiovasculares.<br />
4.  <strong>Función Pedigree de Diabetes (1.25):</strong> Un valor alto indica una fuerte predisposición genética a la diabetes, lo que aumenta significativamente su riesgo.<br />
5.  <strong>Grosor de la Piel (48 mm):</strong> Este valor, si se refiere al pliegue cutáneo del tríceps, es elevado e indica una mayor adiposidad, consistente con el ⚖️ IMC de obesidad.<br />
6.  <strong>💉 Insulina (1.2 µU/mL vs 250 µU/mL):</strong> Esta es la discrepancia más crítica.<br />
    *   <strong>Si es 1.2 µU/mL (muy baja):</strong> A pesar de la hiperglucemia, unos niveles de 💉 insulina muy bajos sugieren una posible disfunción grave de las células beta del páncreas, o incluso una fase de inicio de Diabetes Tipo 1 o LADA (Diabetes Autoinmune Latente del Adulto), especialmente considerando su edad. Esto requeriría una investigación inmediata de autoanticuerpos y péptido C.<br />
    *   <strong>Si es 250 µU/mL (extremadamente alta):</strong> Niveles de 💉 insulina tan elevados en presencia de hiperglucemia son un fuerte indicio de <strong>resistencia a la 💉 insulina severa</strong>, donde el páncreas está trabajando en exceso para intentar compensar la ineficacia de la 💉 insulina. Esto es característico de la Diabetes Tipo 2. Sin embargo, 250 µU/mL es un valor excepcionalmente alto, incluso para resistencia severa, y podría indicar una muestra postprandial mal interpretada como ayunas, o un error de laboratorio. *Es un valor que necesita ser verificado urgentemente.*<br />
7.  <strong>Hemorragia nasal:</strong> Esta observación puede estar relacionada con la hipertensión no 🎛️ controlada.<br /><strong>Crítica a la Predicción de IA:</strong><br>
La predicción de la IA de "NEGATIVO para Diabetes Tipo 2" con una probabilidad del 0% y un nivel de riesgo "MUY BAJO" es <strong>completamente contradictoria e incorrecta</strong> dada la información clínica de Javier. Un nivel de 🍯 glucosa de 210 mg/dL, obesidad (⚖️ IMC 32.43), hipertensión (140/90 mmHg) y una fuerte predisposición genética (Función Pedigree 1.25) lo colocan en un estado de <strong>hiperglucemia severa y alto riesgo cardiovascular</strong>, y muy probablemente con un diagnóstico de Diabetes Mellitus. Esto subraya la importancia de la supervisión clínica y la interpretación humana de los datos médicos sobre las predicciones automatizadas, especialmente cuando los resultados clínicos son tan evidentes.<br />
---<br /><strong>Estratificación de Riesgo:</strong><br>
Javier Martínez se encuentra en un <strong>RIESGO MUY ALTO</strong> para complicaciones de salud debido a los siguientes factores:<br /><li><strong>Diabetes Mellitus inminente o ya diagnosticada:</strong> 🍯 Glucosa de 210 mg/dL.</li><li><strong>Hipertensión Etapa 2:</strong> ❤️ Presión arterial de 140/90 mmHg.</li><li><strong>Obesidad Clase I:</strong> ⚖️ IMC de 32.43 kg/m².</li><li><strong>Fuerte predisposición genética:</strong> Función Pedigree de Diabetes de 1.25.</li><li><strong>Posible resistencia a la 💉 insulina severa</strong> (si el valor de 💉 insulina es 250 µU/mL).</li><li><strong>🔴 Alto riesgo de enfermedad cardiovascular aterosclerótica (ASCVD)</strong> debido a la combinación de diabetes, hipertensión y obesidad.</li><li><strong>Riesgo de daño a órganos diana</strong> no detectado (riñones, ojos, nervios, corazón) debido a la hiperglucemia y la hipertensión.</li>---<br /><strong>Interpretación de Resultados:</strong><br>
1.  <strong>Diagnóstico Principal:</strong> Javier Martínez muy probablemente tiene <strong>Diabetes Mellitus</strong>. El valor de 🍯 glucosa de 210 mg/dL por sí solo es altamente sugestivo. La confirmación requerirá pruebas adicionales.<br />
    *   <strong>Diferenciación de tipo de diabetes:</strong> La discrepancia en los niveles de 💉 insulina es clave.<br />
        *   Si la 💉 insulina es <strong>baja (1.2 µU/mL)</strong>, se debe considerar Diabetes Tipo 1 o LADA, especialmente por su edad, y realizar pruebas de autoanticuerpos (GAD65, ICA, IA2) y péptido C.<br />
        *   Si la 💉 insulina es <strong>alta (250 µU/mL)</strong>, es muy indicativo de <strong>Diabetes Tipo 2</strong> con severa resistencia a la 💉 insulina y agotamiento potencial de las células beta. La edad temprana con T2DM es un signo de enfermedad agresiva.<br />
    *   <strong>Hipótesis de trabajo:</strong> Dada la obesidad, hipertensión y fuerte historial familiar, la Diabetes Tipo 2 es una hipótesis muy fuerte, especialmente si el valor de 💉 insulina de 250 µU/mL se confirma. Sin embargo, dada su edad, la posibilidad de LADA o T1DM atípico siempre debe explorarse si los niveles de 💉 insulina son bajos.<br />
2.  <strong>Condiciones Comórbidas:</strong><br>
    *   <strong>Hipertensión Arterial Etapa 2.</strong><br>
    *   <strong>Obesidad Clase I.</strong><br>
    *   <strong>Hemorragia Nasal:</strong> Requiere investigación, podría estar relacionada con la hipertensión.<br />
---<br /><strong>Recomendaciones y Plan Terapéutico:</strong><br>
Dada la complejidad del caso y la severidad de los hallazgos, es crucial un enfoque multifacético e inmediato.<br /><strong>1. Acciones Inmediatas y Diagnóstico Confirmatorio:</strong><br><li><strong>Consulta Médica Urgente:</strong> Javier debe ser evaluado por un médico especialista (Endocrinólogo) de manera prioritaria.</li><li><strong>Confirmación de Diabetes:</strong></li>    *   <strong>🍯 Glucosa plasmática en ayunas (FPG):</strong> Repetir la prueba.<br />
    *   <strong>Hemoglobina Glicosilada (🩸 HbA1c):</strong> Para evaluar el 🎛️ control glucémico promedio de los últimos 2-3 meses. Un valor ≥ 6.5% confirma el diagnóstico de diabetes.<br />
    *   <strong>Péptido C en ayunas e 💉 Insulina:</strong> Repetir estas pruebas para resolver la discrepancia y evaluar la función de las células beta y la resistencia a la 💉 insulina. El péptido C es un mejor indicador de la producción endógena de 💉 insulina.<br />
    *   <strong>Autoanticuerpos para Diabetes (GAD65, ICA, IA2):</strong> Si el péptido C o los niveles de 💉 insulina confirman una producción baja, para descartar Diabetes Tipo 1 o LADA.<br /><li><strong>Evaluación de la Hipertensión:</strong> Monitorización domiciliaria de la ❤️ presión arterial.</li><li><strong>Investigación de Hemorragia Nasal:</strong> Evaluación otorrinolaringológica para identificar la causa y descartar complicaciones.</li><strong>2. Plan Terapéutico (Asumiendo Diabetes Tipo 2, pero adaptable según la confirmación):</strong><br><strong>A. Manejo de la Diabetes:</strong><br><li><strong>Metas Glucémicas:</strong> Individualizar, pero inicialmente buscar 🩸 HbA1c < 7.0%.</li><li><strong>Intervención Farmacológica (Inicio Inmediato):</strong></li>    *   <strong>Metformina:</strong> Iniciar metformina (si no hay contraindicaciones renales o hepáticas), titulada gradualmente. Es la primera línea para T2DM.<br />
    *   <strong>Considerar Co-terapia Temprana:</strong> Dada la alta 🍯 glucosa, obesidad e hipertensión, considerar desde el inicio la adición de fármacos con beneficios cardiovasculares y renales:<br />
        *   <strong>Agonistas del Receptor GLP-1 (GLP-1 RA):</strong> Ej. liraglutida, semaglutida. Favorecen la pérdida de ⚖️ peso, reducen la 🍯 glucosa y tienen beneficios cardiovasculares.<br />
        *   <strong>Inhibidores SGLT2 (iSGLT2):</strong> Ej. empagliflozina, dapagliflozina. Reducen la 🍯 glucosa, promueven la pérdida de ⚖️ peso, y ofrecen beneficios cardiovasculares y renales, importantes para un paciente con hipertensión y obesidad.<br />
    *   <strong>💉 Insulina:</strong> Si la 🍯 glucosa persiste muy elevada o si se confirma T1DM/LADA, la terapia con 💉 insulina será indispensable.<br /><li><strong>Plan de Alimentación:</strong></li>    *   <strong>Consulta con Nutricionista/Dietista Registrado:</strong> Esencial para una terapia nutricional médica personalizada.<br />
    *   <strong>Enfoque:</strong> Reducción de carbohidratos refinados, azúcares añadidos, grasas saturadas. Aumento de fibra, vegetales, proteínas magras. 🎛️ Control de porciones.<br /><li><strong>Actividad Física:</strong></li>    *   <strong>Programa de 🏃‍♂️ Ejercicio Estructurado:</strong> Al menos 150 minutos a la semana de actividad aeróbica de intensidad moderada, distribuidos en 3-5 días, y 2-3 sesiones semanales de entrenamiento de fuerza.<br />
    *   <strong>Objetivo:</strong> Ayudar a la pérdida de ⚖️ peso, mejorar la sensibilidad a la 💉 insulina y el 🎛️ control de la ❤️ presión arterial.<br /><strong>B. Manejo de la Hipertensión:</strong><br><li><strong>Metas de Presión Arterial:</strong> Objetivo < 130/80 mmHg, especialmente dado el riesgo de diabetes.</li><li><strong>Intervención Farmacológica:</strong></li>    *   <strong>Inicio Inmediato:</strong> Considerar un IECA (inhibidor de la enzima convertidora de angiotensina) o un ARA II (antagonista del receptor de angiotensina II), que también ofrecen protección renal en pacientes con diabetes. Se puede combinar con un bloqueador de canales de calcio o un diurético tiazídico si es necesario.<br /><li><strong>Modificaciones en el Estilo de Vida:</strong> 🥗 Dieta DASH (🥗 Dietary Approaches to Stop Hypertension), restricción de sodio, 🏃‍♂️ ejercicio regular, pérdida de ⚖️ peso, moderación en el consumo de alcohol (si aplica).</li><strong>C. Manejo de la Obesidad:</strong><br><li><strong>Objetivo de ⚖️ Peso:</strong> Pérdida inicial de al menos 5-10% del ⚖️ peso corporal, lo que ha demostrado mejorar la sensibilidad a la 💉 insulina y el 🎛️ control de la 🍯 glucosa y la ❤️ presión arterial.</li><li><strong>Intervenciones:</strong> 🥗 Dieta, 🏃‍♂️ ejercicio, y si es necesario, considerar medicamentos para la pérdida de ⚖️ peso (ej. GLP-1 RA que también tratan la diabetes) o referir para evaluación de cirugía bariátrica si cumple los criterios.</li><strong>D. Prevención de Complicaciones Cardiovasculares:</strong><br><li><strong>Estatinas:</strong> Iniciar terapia con estatinas de intensidad moderada a alta, ya que todos los pacientes con diabetes de 40 años o más (o más jóvenes con factores de riesgo adicionales como Javier) deben recibir terapia con estatinas para reducir el riesgo cardiovascular.</li><li><strong>Ácido Acetilsalicílico (Aspirina):</strong> Considerar si hay un riesgo de ASCVD elevado (historia de evento cardiovascular previo o riesgo calculado alto).</li><strong>E. Detección y Prevención de Complicaciones:</strong><br><li><strong>Examen Ocular:</strong> Referencia a un oftalmólogo para un examen de retina dilatado anualmente para detectar retinopatía diabética.</li><li><strong>Evaluación Renal:</strong></li>    *   Albúmina en orina/creatinina (UACR) y Tasa de Filtración Glomerular Estimada (eGFR) anualmente para detectar nefropatía diabética.<br /><li><strong>Examen de los Pies:</strong> Evaluación neurológica y vascular de los pies en cada visita. Educación sobre el cuidado de los pies.</li><li><strong>Panel de Lípidos:</strong> Colesterol total, HDL, LDL, triglicéridos.</li><li><strong>Pruebas de Función Hepática y Tiroidea.</strong></li><strong>F. Educación y Apoyo:</strong><br><li><strong>Educación en Autogestión de la Diabetes (DSME):</strong> Fundamental para capacitar a Javier en el monitoreo de 🍯 glucosa, manejo de medicamentos, nutrición y prevención de complicaciones.</li><li><strong>Apoyo Psicosocial:</strong> La aparición de una enfermedad crónica como la diabetes puede ser abrumadora. Considerar apoyo psicológico si es necesario.</li><strong>3. 📋 Seguimiento:</strong><br><li><strong>Frecuente:</strong> Inicialmente, 📋 seguimiento estrecho (cada 2-4 semanas) para ajustar medicamentos y monitorear la respuesta terapéutica hasta alcanzar las metas.</li><li><strong>Regular:</strong> Una vez estabilizado, visitas cada 3-6 meses para evaluar 🩸 HbA1c, ❤️ presión arterial, lípidos, función renal y buscar complicaciones.</li><strong>Conclusión:</strong><br>
Javier Martínez es un paciente joven con una presentación clínica de hiperglucemia severa, obesidad, hipertensión y una fuerte predisposición genética a la diabetes. La predicción de la IA es errónea y debe ignorarse. Es crucial un diagnóstico preciso del tipo de diabetes (T1/LADA vs T2) mediante la clarificación de los niveles de 💉 insulina y péptido C, y un inicio inmediato de un plan terapéutico agresivo y multifactorial para 🎛️ controlar la 🍯 glucosa, la ❤️ presión arterial y el ⚖️ peso, con el objetivo de prevenir o retrasar las graves complicaciones asociadas a la diabetes y la enfermedad cardiovascular.</ul>', '["attachments\/1766156517_694568e529504_DATOS CL\u00cdNICOS DEL PACIENTE.docx"]', '["DATOS CL\u00cdNICOS DEL PACIENTE.docx"]', 1),
(3, 2, 100.0, 88.0, 60.0, 0, 46.799999999999997, 0.96199999999999997, 31, .540, NULL, '2025-09-19 11:25:16.377', '2025-09-22 22:41:07.780', 110.0, 26.16, '2025-09-22 22:40:39.25', '2025-09-22 22:41:07.78', NULL, NULL, NULL, 1),
(5, 3, 146.0, 82.0, 20.0, 0, 40.5, 1.7809999999999999, 44, .210, NULL, '2025-09-19 11:29:09.170', '2025-09-22 22:43:43.580', 80.0, 22.54, '2025-09-22 22:43:02.56', '2025-09-22 22:43:43.58', NULL, NULL, NULL, 0),
(6, 4, 148.0, 72.0, 35.0, 6, 33.600000000000001, 0.627, 50, .840, NULL, '2025-09-19 11:31:08.457', '2025-09-22 22:44:15.647', 80.0, 24.91, '2025-09-22 22:43:48.41', '2025-09-22 22:44:15.65', NULL, NULL, NULL, 1),
(7, 5, 105.0, 64.0, 41.0, 0, 41.5, 0.17299999999999999, 22, .120, NULL, '2025-09-19 11:31:53.290', '2025-09-22 22:44:49.537', 142.0, 19.97, '2025-09-22 22:44:27.34', '2025-09-22 22:44:49.54', NULL, NULL, NULL, 0),
(8, 6, 109.0, 88.0, 30.0, 0, 32.5, 0.85499999999999998, 38, .880, NULL, '2025-09-19 11:32:57.250', '2025-09-22 22:45:21.197', 80.0, 19.49, '2025-09-22 22:44:58.83', '2025-09-22 22:45:21.20', NULL, NULL, NULL, 1),
(9, 7, 85.0, 66.0, 29.0, 1, 26.600000000000001, 0.35099999999999998, 31, .040, NULL, '2025-09-19 11:33:33.900', '2025-09-22 22:45:47.990', 80.0, 19.57, '2025-09-22 22:45:25.81', '2025-09-22 22:45:47.99', NULL, NULL, NULL, 0),
(10, 8, 89.0, 66.0, 23.0, 1, 28.100000000000001, 0.16700000000000001, 21, .030, NULL, '2025-09-19 11:35:47.923', '2025-09-22 22:46:37.517', 94.0, 20.75, '2025-09-22 22:46:14.65', '2025-09-22 22:46:37.52', NULL, NULL, NULL, 0),
(11, 9, 131.0, 70.0, 20.0, 0, 43.200000000000003, 0.27000000000000002, 26, .590, NULL, '2025-09-19 11:36:40.073', '2025-09-22 22:47:11.973', 80.0, 18.74, '2025-09-22 22:46:43.10', '2025-09-22 22:47:11.97', NULL, NULL, NULL, 1),
(12, 10, 101.0, 65.0, 28.0, 0, 24.600000000000001, 0.23699999999999999, 22, .000, NULL, '2025-09-19 11:37:28.220', '2025-09-22 22:48:24.837', 80.0, 21.33, '2025-09-22 22:47:42.87', '2025-09-22 22:48:24.84', NULL, NULL, NULL, 0),
(13, 11, 116.0, 74.0, 20.0, 5, 25.600000000000001, 0.20100000000000001, 30, .030, NULL, '2025-09-19 11:39:54.410', '2025-09-22 22:49:11.303', 80.0, 24.84, '2025-09-22 22:48:35.82', '2025-09-22 22:49:11.30', NULL, NULL, NULL, 0),
(14, 12, 115.0, 70.0, 20.0, 10, 35.299999999999997, 0.13400000000000001, 29, .280, NULL, '2025-09-19 11:40:31.093', '2025-09-22 22:49:52.437', 80.0, 30.71, '2025-09-22 22:49:17.01', '2025-09-22 22:49:52.44', NULL, NULL, NULL, 0),
(15, 13, 125.0, 96.0, 20.0, 0, 22.5, 0.26200000000000001, 21, .040, NULL, '2025-09-19 11:41:19.027', '2025-09-22 22:50:25.523', 80.0, 18.87, '2025-09-22 22:49:56.98', '2025-09-22 22:50:25.52', NULL, NULL, NULL, 0),
(16, 14, 125.0, 96.0, 21.0, 8, 31.989999999999998, 0.23200000000000001, 54, .360, NULL, '2025-09-19 11:42:37.590', '2025-09-22 22:50:57.567', 80.0, 20.90, '2025-09-22 22:50:29.86', '2025-09-22 22:50:57.57', NULL, NULL, NULL, 1),
(17, 15, 110.0, 92.0, 21.0, 4, 37.600000000000001, 0.191, 30, .060, NULL, '2025-09-19 11:43:14.777', '2025-09-22 22:52:56.917', 80.0, 20.60, '2025-09-22 22:51:43.35', '2025-09-22 22:52:56.92', NULL, NULL, NULL, 0),
(18, 16, 168.0, 74.0, 21.0, 10, 38.0, 0.53700000000000003, 34, .900, NULL, '2025-09-19 11:44:22.510', '2025-09-22 22:53:36.637', 80.0, 22.70, '2025-09-22 22:53:09.87', '2025-09-22 22:53:36.63', NULL, NULL, NULL, 1),
(19, 17, 95.0, 85.0, 25.0, 0, 37.399999999999999, 0.247, 24, .080, NULL, '2025-09-19 11:45:13.270', '2025-09-22 22:55:41.247', 36.0, 19.65, '2025-09-22 22:55:17.45', '2025-09-22 22:55:41.25', NULL, NULL, NULL, 0),
(20, 18, 162.0, 76.0, 56.0, 0, 53.200000000000003, 0.75900000000000001, 25, .920, NULL, '2025-09-19 11:46:07.077', '2025-09-22 22:56:13.760', 100.0, 23.10, '2025-09-22 22:55:47.87', '2025-09-22 22:56:13.76', NULL, NULL, NULL, 1),
(21, 19, 113.0, 76.0, 21.0, 0, 33.299999999999997, 0.27800000000000002, 23, .620, NULL, '2025-09-19 11:46:40.953', '2025-09-22 22:54:07.160', 80.0, 16.52, '2025-09-22 22:53:48.03', '2025-09-22 22:54:07.16', NULL, NULL, NULL, 1),
(22, 20, 105.0, 84.0, 21.0, 0, 27.899999999999999, 0.74099999999999999, 62, .710, NULL, '2025-09-19 11:47:18.177', '2025-09-22 22:54:53.130', 80.0, 31.27, '2025-09-22 22:54:19.80', '2025-09-22 22:54:53.13', NULL, NULL, NULL, 1),
(23, 21, 100.0, 70.0, 26.0, 0, 30.800000000000001, 0.59699999999999998, 21, .010, NULL, '2025-09-19 11:48:07.510', '2025-09-22 22:57:02.777', 50.0, 20.89, '2025-09-22 22:56:35.39', '2025-09-22 22:57:02.78', NULL, NULL, NULL, 1),
(24, 22, 93.0, 60.0, 25.0, 0, 28.699999999999999, 0.53200000000000003, 22, .030, NULL, '2025-09-19 11:48:31.263', '2025-09-22 22:57:31.307', 92.0, 18.64, '2025-09-22 22:57:08.58', '2025-09-22 22:57:31.31', NULL, NULL, NULL, 1),
(25, 23, 129.0, 80.0, 21.0, 0, 31.199999999999999, 0.70299999999999996, 29, .250, NULL, '2025-09-19 11:49:06.067', '2025-09-22 22:58:13.813', 80.0, 20.64, '2025-09-22 22:57:50.39', '2025-09-22 22:58:13.81', NULL, NULL, NULL, 0),
(26, 24, 102.0, 75.0, 23.0, 0, 31.989999999999998, 0.57199999999999995, 21, .060, NULL, '2025-09-19 11:50:41.560', '2025-09-22 22:58:44.943', 80.0, 22.04, '2025-09-22 22:58:20.93', '2025-09-22 22:58:44.94', NULL, NULL, NULL, 1),
(27, 25, 106.0, 64.0, 35.0, 2, 30.5, 1.3999999999999999, 34, .230, NULL, '2025-09-19 11:51:12.340', '2025-09-22 22:59:52.020', 119.0, 22.25, '2025-09-22 22:59:28.01', '2025-09-22 22:59:52.02', NULL, NULL, NULL, 0),
(28, 26, 115.0, 70.0, 30.0, 1, 34.600000000000001, 0.52900000000000003, 32, .870, NULL, '2025-09-19 11:51:56.867', '2025-09-22 23:00:28.467', 96.0, 18.18, '2025-09-22 22:59:57.84', '2025-09-22 23:00:28.47', NULL, NULL, NULL, 1),
(29, 27, 90.0, 68.0, 42.0, 2, 38.200000000000003, 0.503, 27, .010, NULL, '2025-09-19 11:52:48.577', '2025-09-22 23:01:14.773', 80.0, 20.30, '2025-09-22 23:00:51.54', '2025-09-22 23:01:14.77', NULL, NULL, NULL, 0),
(30, 28, 131.0, 88.0, 21.0, 0, 31.600000000000001, 0.74299999999999999, 32, .820, NULL, '2025-09-19 11:54:21.893', '2025-09-22 23:02:02.343', 80.0, 21.31, '2025-09-22 23:01:36.06', '2025-09-22 23:02:02.34', NULL, NULL, NULL, 1),
(31, 29, 129.0, 110.0, 46.0, 0, 67.099999999999994, 0.31900000000000001, 26, .760, NULL, '2025-09-19 11:54:57.333', '2025-09-22 23:02:04.720', 130.0, 24.97, '2025-09-22 23:01:35.40', '2025-09-22 23:02:04.72', NULL, NULL, NULL, 1),
(32, 30, 119.0, 64.0, 18.0, 0, 34.899999999999999, 0.72499999999999998, 23, .190, NULL, '2025-09-19 11:55:46.783', '2025-09-22 22:59:11.880', 92.0, 16.14, '2025-09-22 22:58:52.44', '2025-09-22 22:59:11.88', NULL, NULL, NULL, 0),
(33, 31, 113.0, 80.0, 16.0, 0, 31.0, 0.874, 21, .140, NULL, '2025-09-19 11:56:22.493', '2025-09-22 23:02:55.120', 80.0, 21.97, '2025-09-22 23:02:30.46', '2025-09-22 23:02:55.12', NULL, NULL, NULL, 0),
(34, 32, 108.0, 68.0, 20.0, 0, 27.300000000000001, 0.78700000000000003, 32, .260, NULL, '2025-09-19 11:56:53.783', '2025-09-22 23:03:03.287', 80.0, 21.86, '2025-09-22 23:02:38.23', '2025-09-22 23:03:03.29', NULL, NULL, NULL, 0),
(35, 33, 138.0, 82.0, 21.0, 1, 40.100000000000001, 0.23599999999999999, 28, .190, NULL, '2025-09-19 11:57:44.167', '2025-09-22 23:03:37.500', 80.0, 23.76, '2025-09-22 23:03:10.82', '2025-09-22 23:03:37.50', NULL, NULL, NULL, 0),
(36, 34, 147.0, 85.0, 54.0, 0, 42.799999999999997, 0.375, 24, .720, NULL, '2025-09-19 11:58:58.913', '2025-09-22 23:03:41.633', 80.0, 19.35, '2025-09-22 23:03:19.80', '2025-09-22 23:03:41.63', NULL, NULL, NULL, 1),
(37, 35, 140.0, 65.0, 26.0, 0, 42.600000000000001, 0.43099999999999999, 24, .500, NULL, '2025-09-19 11:59:31.017', '2025-09-22 23:04:34.707', 130.0, 21.56, '2025-09-22 23:04:11.28', '2025-09-22 23:04:34.71', NULL, NULL, NULL, 1),
(38, 36, 101.0, 76.0, 21.0, 0, 35.700000000000003, 0.19800000000000001, 26, .030, NULL, '2025-09-19 12:00:18.077', '2025-09-22 23:04:39.147', 80.0, 16.26, '2025-09-22 23:04:21.06', '2025-09-22 23:04:39.15', NULL, NULL, NULL, 0),
(39, 37, 87.0, 78.0, 27.0, 1, 34.600000000000001, 0.10100000000000001, 22, .050, NULL, '2025-09-19 12:01:02.390', '2025-09-22 23:05:24.660', 32.0, 25.88, '2025-09-22 23:04:54.95', '2025-09-22 23:05:24.66', NULL, NULL, NULL, 1),
(40, 38, 100.0, 66.0, 15.0, 1, 23.600000000000001, 0.66600000000000004, 26, .010, NULL, '2025-09-19 12:01:35.093', '2025-09-22 23:05:07.183', 56.0, 18.30, '2025-09-22 23:04:46.20', '2025-09-22 23:05:07.18', NULL, NULL, NULL, 0),
(41, 39, 79.0, 80.0, 25.0, 1, 25.399999999999999, 0.58299999999999996, 22, .010, NULL, '2025-09-19 12:03:02.650', '2025-09-22 23:06:00.733', 37.0, 15.31, '2025-09-22 23:05:43.22', '2025-09-22 23:06:00.73', NULL, NULL, NULL, 0),
(42, 40, 74.0, 68.0, 28.0, 3, 29.699999999999999, 0.29299999999999998, 23, .030, NULL, '2025-09-19 12:04:22.717', '2025-09-22 23:06:14.067', 45.0, 18.10, '2025-09-22 23:05:52.38', '2025-09-22 23:06:14.07', NULL, NULL, NULL, 0),
(43, 41, 179.0, 90.0, 27.0, 0, 44.100000000000001, 0.68600000000000005, 23, .950, NULL, '2025-09-19 12:08:16.457', '2025-09-22 23:06:33.557', 80.0, 24.88, '2025-09-22 23:06:06.68', '2025-09-22 23:06:33.56', NULL, NULL, NULL, 1),
(44, 42, 104.0, 76.0, 21.0, 0, 18.399999999999999, 0.58199999999999996, 27, .020, NULL, '2025-09-22 20:43:13.423', '2025-09-22 23:06:46.633', 80.0, 20.26, '2025-09-22 23:06:21.39', '2025-09-22 23:06:46.63', NULL, NULL, NULL, 0),
(45, 43, 91.0, 64.0, 24.0, 1, 29.199999999999999, 0.192, 21, .030, NULL, '2025-09-22 21:11:52.417', '2025-09-22 23:07:02.210', 80.0, 20.44, '2025-09-22 23:06:39.00', '2025-09-22 23:07:02.21', NULL, NULL, NULL, 0),
(46, 44, 146.0, 76.0, 35.0, 2, 38.200000000000003, 0.32900000000000001, 29, .300, NULL, '2025-09-22 21:14:14.787', '2025-09-22 23:07:15.910', 194.0, 19.79, '2025-09-22 23:06:52.05', '2025-09-22 23:07:15.91', NULL, NULL, NULL, 0),
(47, 45, 111.0, 86.0, 19.0, 1, 30.100000000000001, 0.14299999999999999, 23, .150, NULL, '2025-09-22 21:28:53.953', '2025-09-22 21:28:53.953', 80.0, 16.73, '2025-09-22 21:28:37.22', '2025-09-22 21:28:53.95', NULL, NULL, NULL, 0),
(48, 46, 129.0, 84.0, 21.0, 2, 28.0, 0.28399999999999997, 27, .200, NULL, '2025-09-22 21:33:04.583', '2025-09-22 21:33:04.583', 80.0, 21.56, '2025-09-22 21:32:43.02', '2025-09-22 21:33:04.58', NULL, NULL, NULL, 0),
(49, 47, 86.0, 68.0, 30.0, 0, 35.799999999999997, 0.23799999999999999, 25, .020, NULL, '2025-09-22 21:43:33.603', '2025-09-22 21:43:33.603', 80.0, 19.69, '2025-09-22 21:43:13.90', '2025-09-22 21:43:33.60', NULL, NULL, NULL, 0),
(50, 48, 138.0, 70.0, 21.0, 0, 36.299999999999997, 0.93300000000000005, 25, .900, NULL, '2025-09-22 21:44:09.437', '2025-09-22 21:44:09.437', 80.0, 19.32, '2025-09-22 21:43:50.10', '2025-09-22 21:44:09.44', NULL, NULL, NULL, 1),
(51, 49, 113.0, 64.0, 35.0, 1, 33.600000000000001, 0.54300000000000004, 21, .770, NULL, '2025-09-22 21:44:40.357', '2025-09-22 21:44:40.357', 80.0, 20.50, '2025-09-22 21:44:19.85', '2025-09-22 21:44:40.36', NULL, NULL, NULL, 1),
(52, 50, 104.0, 64.0, 23.0, 0, 27.800000000000001, 0.45400000000000001, 23, .070, NULL, '2025-09-22 21:45:11.460', '2025-09-22 21:45:11.460', 116.0, 12.22, '2025-09-22 21:44:59.23', '2025-09-22 21:45:11.46', NULL, NULL, NULL, 0),
(53, 51, 146.0, 70.0, 21.0, 0, 37.899999999999999, 0.33400000000000002, 28, .820, NULL, '2025-09-22 21:45:38.410', '2025-09-22 21:45:38.410', 80.0, 13.20, '2025-09-22 21:45:25.20', '2025-09-22 21:45:38.41', NULL, NULL, NULL, 1),
(54, 52, 90.0, 80.0, 14.0, 2, 24.399999999999999, 0.249, 24, .000, NULL, '2025-09-22 21:47:33.760', '2025-09-22 21:47:33.760', 55.0, 27.96, '2025-09-22 21:47:05.79', '2025-09-22 21:47:33.76', NULL, NULL, NULL, 0),
(55, 53, 184.0, 85.0, 15.0, 9, 30.0, 1.2130000000000001, 49, .920, NULL, '2025-09-22 21:48:22.607', '2025-09-22 21:48:22.607', 80.0, 20.37, '2025-09-22 21:48:02.23', '2025-09-22 21:48:22.61', NULL, NULL, NULL, 1),
(56, 54, 191.0, 68.0, 15.0, 3, 30.899999999999999, 0.29899999999999999, 34, .310, NULL, '2025-09-22 21:49:39.883', '2025-09-22 21:49:39.883', 130.0, 21.98, '2025-09-22 21:49:17.89', '2025-09-22 21:49:39.88', NULL, NULL, NULL, 1),
(57, 55, 128.0, 64.0, 42.0, 2, 40.0, 1.101, 24, .200, NULL, '2025-09-22 21:50:23.913', '2025-09-22 21:50:23.913', 80.0, 26.44, '2025-09-22 21:49:57.47', '2025-09-22 21:50:23.91', NULL, NULL, NULL, 0),
(58, 56, 78.0, 88.0, 29.0, 0, 36.899999999999999, 0.434, 21, .050, NULL, '2025-09-22 21:51:04.870', '2025-09-22 21:51:04.870', 40.0, 15.87, '2025-09-22 21:50:49.00', '2025-09-22 21:51:04.87', NULL, NULL, NULL, 0),
(59, 57, 107.0, 62.0, 30.0, 0, 36.600000000000001, 0.75700000000000001, 25, .730, NULL, '2025-09-22 21:51:36.170', '2025-09-22 21:51:36.170', 74.0, 19.85, '2025-09-22 21:51:16.32', '2025-09-22 21:51:36.17', NULL, NULL, NULL, 1),
(60, 58, 167.0, 70.0, 21.0, 0, 32.299999999999997, 0.83899999999999997, 30, .880, NULL, '2025-09-22 21:52:20.830', '2025-09-22 21:52:20.830', 80.0, 22.57, '2025-09-22 21:51:58.25', '2025-09-22 21:52:20.83', NULL, NULL, NULL, 1),
(61, 59, 137.0, 68.0, 14.0, 0, 24.800000000000001, 0.14299999999999999, 21, .070, NULL, '2025-09-22 21:53:23.777', '2025-09-22 21:53:23.777', 148.0, 17.22, '2025-09-22 21:53:06.55', '2025-09-22 21:53:23.78', NULL, NULL, NULL, 0),
(62, 60, 71.0, 78.0, 50.0, 1, 33.200000000000003, 0.42199999999999999, 21, .050, NULL, '2025-09-22 21:54:00.980', '2025-09-22 21:54:00.980', 45.0, 22.61, '2025-09-22 21:53:38.36', '2025-09-22 21:54:00.98', NULL, NULL, NULL, 0),
(63, 61, 137.0, 68.0, 14.0, 0, 24.800000000000001, 0.14299999999999999, 21, .070, NULL, '2025-09-22 21:54:28.523', '2025-09-22 21:54:28.523', 148.0, 16.06, '2025-09-22 21:54:12.46', '2025-09-22 21:54:28.52', NULL, NULL, NULL, 0),
(64, 62, 106.0, 70.0, 37.0, 0, 39.399999999999999, 0.60499999999999998, 22, .320, NULL, '2025-09-22 21:55:07.213', '2025-09-22 21:55:07.213', 148.0, 17.64, '2025-09-22 21:54:49.57', '2025-09-22 21:55:07.21', NULL, NULL, NULL, 0),
(65, 63, 155.0, 74.0, 17.0, 2, 26.600000000000001, 0.433, 27, .750, NULL, '2025-09-22 21:55:34.457', '2025-09-22 21:55:34.457', 96.0, 14.28, '2025-09-22 21:55:20.16', '2025-09-22 21:55:34.46', NULL, NULL, NULL, 1),
(66, 64, 112.0, 68.0, 22.0, 2, 34.100000000000001, 0.315, 26, .080, NULL, '2025-09-22 21:56:23.427', '2025-09-22 21:56:23.427', 94.0, 22.02, '2025-09-22 21:56:01.40', '2025-09-22 21:56:23.43', NULL, NULL, NULL, 0),
(67, 65, 99.0, 80.0, 11.0, 3, 19.300000000000001, 0.28399999999999997, 30, .020, NULL, '2025-09-22 21:56:57.267', '2025-09-22 21:56:57.267', 64.0, 25.46, '2025-09-22 21:56:31.80', '2025-09-22 21:56:57.27', NULL, NULL, NULL, 0),
(68, 66, 124.0, 70.0, 20.0, 0, 27.399999999999999, 0.254, 36, .730, NULL, '2025-09-22 21:57:35.200', '2025-09-22 21:57:35.200', 80.0, 21.69, '2025-09-22 21:57:13.50', '2025-09-22 21:57:35.20', NULL, NULL, NULL, 1),
(69, 67, 112.0, 75.0, 32.0, 2, 35.700000000000003, 0.14799999999999999, 21, .070, NULL, '2025-09-22 21:58:26.717', '2025-09-22 21:58:26.717', 80.0, 24.04, '2025-09-22 21:58:02.66', '2025-09-22 21:58:26.72', NULL, NULL, NULL, 0),
(70, 68, 101.0, 64.0, 17.0, 0, 21.0, 0.252, 21, .000, NULL, '2025-09-22 21:59:17.600', '2025-09-22 21:59:17.600', 80.0, 23.18, '2025-09-22 21:58:54.41', '2025-09-22 21:59:17.60', NULL, NULL, NULL, 0),
(71, 69, 133.0, 102.0, 28.0, 1, 32.799999999999997, 0.23400000000000001, 45, .850, NULL, '2025-09-22 21:59:58.277', '2025-09-22 21:59:58.277', 140.0, 28.60, '2025-09-22 21:59:29.67', '2025-09-22 21:59:58.28', NULL, NULL, NULL, 1),
(72, 70, 118.0, 102.0, 28.0, 0, 32.799999999999997, 0.23400000000000001, 45, .750, NULL, '2025-09-22 22:00:41.817', '2025-09-22 22:00:41.817', 140.0, 20.61, '2025-09-22 22:00:21.20', '2025-09-22 22:00:41.82', NULL, NULL, NULL, 1),
(73, 71, 84.0, 64.0, 22.0, 0, 35.799999999999997, 0.54500000000000004, 21, .050, NULL, '2025-09-22 22:01:11.640', '2025-09-22 22:01:11.640', 66.0, 21.30, '2025-09-22 22:00:50.33', '2025-09-22 22:01:11.64', NULL, NULL, NULL, 0),
(74, 72, 98.0, 82.0, 15.0, 0, 25.199999999999999, 0.29899999999999999, 22, .010, NULL, '2025-09-22 22:01:37.127', '2025-09-22 22:01:37.127', 84.0, 16.44, '2025-09-22 22:01:20.68', '2025-09-22 22:01:37.13', NULL, NULL, NULL, 0),
(75, 73, 93.0, 100.0, 39.0, 0, 43.399999999999999, 1.0209999999999999, 35, .200, NULL, '2025-09-22 22:02:09.560', '2025-09-22 22:02:09.560', 72.0, 22.17, '2025-09-22 22:01:47.38', '2025-09-22 22:02:09.56', NULL, NULL, NULL, 0),
(76, 74, 105.0, 68.0, 22.0, 0, 20.0, 0.23599999999999999, 22, .010, NULL, '2025-09-22 22:02:39.350', '2025-09-22 22:02:39.350', 80.0, 15.66, '2025-09-22 22:02:23.69', '2025-09-22 22:02:39.35', NULL, NULL, NULL, 0),
(77, 75, 109.0, 60.0, 8.0, 1, 25.399999999999999, 0.94699999999999995, 21, .060, NULL, '2025-09-22 22:03:11.853', '2025-09-22 22:03:11.853', 182.0, 20.33, '2025-09-22 22:02:51.52', '2025-09-22 22:03:11.85', NULL, NULL, NULL, 0),
(78, 76, 90.0, 62.0, 18.0, 1, 25.100000000000001, 1.268, 25, .050, NULL, '2025-09-22 22:03:43.537', '2025-09-22 22:03:43.537', 59.0, 22.39, '2025-09-22 22:03:21.14', '2025-09-22 22:03:43.54', NULL, NULL, NULL, 0),
(79, 77, 131.0, 66.0, 40.0, 0, 34.299999999999997, 0.19600000000000001, 22, .710, NULL, '2025-09-22 22:04:23.170', '2025-09-22 22:04:23.170', 80.0, 23.44, '2025-09-22 22:03:59.73', '2025-09-22 22:04:23.17', NULL, NULL, NULL, 1),
(80, 78, 112.0, 72.0, 30.0, 1, 34.399999999999999, 0.52800000000000002, 25, .160, NULL, '2025-09-22 22:04:56.667', '2025-09-22 22:04:56.667', 176.0, 19.33, '2025-09-22 22:04:37.33', '2025-09-22 22:04:56.67', NULL, NULL, NULL, 0),
(81, 79, 138.0, 60.0, 35.0, 0, 34.600000000000001, 0.53400000000000003, 21, .760, NULL, '2025-09-22 22:05:33.763', '2025-09-22 22:05:33.763', 167.0, 22.47, '2025-09-22 22:05:11.29', '2025-09-22 22:05:33.76', NULL, NULL, NULL, 1),
(82, 80, 119.0, 88.0, 41.0, 1, 45.299999999999997, 0.50700000000000001, 26, .140, NULL, '2025-09-22 22:06:11.033', '2025-09-22 22:06:11.033', 170.0, 22.84, '2025-09-22 22:05:48.19', '2025-09-22 22:06:11.03', NULL, NULL, NULL, 0),
(83, 81, 180.0, 145.0, 20.0, 1, 30.899999999999999, 1.2, 60, .770, 'presenta sintomas de gripe', '2025-03-10 03:03:17.493', '2025-03-10 03:03:17.493', 132.0, 97.60, '2025-10-03 03:01:39.88', '2025-10-03 03:03:17.49', NULL, NULL, NULL, 1),
(84, 82, 180.0, 135.0, 13.0, 3, 21.5, 0.10000000000000001, 65, .580, 'la paciente en base a los resultados presenta dolor en los meniscos del pié, con herida totalmente escamosa y mucosidad', '2025-03-10 03:13:22.067', '2025-03-10 03:15:24.013', 130.0, 40.62, '2025-10-03 03:14:17.26', '2025-10-03 03:15:24.01', '## Evaluación Endocrinológica y Riesgo Diabético para Ana Sánchez

**Dirigido al médico tratante:**

A continuación, presento una evaluación estructurada para apoyar su toma de decisiones respecto al manejo de la paciente Ana Sánchez, basándome en los datos proporcionados y mi experiencia como endocrinólogo.

**1. ESTRATIFICACIÓN DE RIESGO DIABÉTICO:**

**Clasificación: ALTO riesgo.**

**Justificación:**  La combinación de glucosa plasmática elevada (180 mg/dL), presión arterial diastólica significativamente alta (135 mmHg), insulina sérica elevada (130 μU/mL, sugiriendo resistencia a la insulina) y la presencia de lesiones en los pies altamente sugestivas de complicaciones microvasculares, indican un riesgo elevado de diabetes tipo 2 y, posiblemente, la presencia de la misma ya no diagnosticada. Aunque el IMC se encuentra dentro de rangos normales, los demás parámetros señalan una importante disregulación metabólica.

**2. INTERPRETACIÓN CLÍNICA POR PARÁMETROS:**

*   **Embarazos (3):**  La paridad se considera un factor de riesgo modesto para diabetes tipo 2, especialmente si hubo antecedentes de diabetes gestacional.
*   **Glucosa plasmática (180 mg/dL):**  Este valor es claramente elevado. Sugiere una alteración significativa en el metabolismo de la glucosa, posiblemente relacionada con resistencia a la insulina y/o deficiencia en la secreción de insulina.  Valores superiores a 126 mg/dL en ayunas o superiores a 200 mg/dL en cualquier momento del día (sin ayuno) son criterios diagnósticos para diabetes.
*   **Presión arterial diastólica (135 mmHg):**  Muy elevada y sugestiva de hipertensión no controlada. La hipertensión es un factor de riesgo importante para complicaciones cardiovasculares en pacientes con diabetes.  Debe ser abordada urgentemente.
*   **Grosor del pliegue cutáneo tricipital (13 mm):**  Este valor, por sí solo, no es directamente diagnóstico, pero podría indicar un bajo porcentaje de grasa corporal. Sin embargo, en el contexto de los demás hallazgos, su significado clínico es limitado.
*   **Insulina sérica (130 μU/mL):**  Elevada, sugiriendo resistencia a la insulina.  El páncreas está produciendo más insulina de lo normal para intentar mantener la glucosa dentro de rangos aceptables. Esto es un hallazgo común en la prediabetes y las primeras etapas de la diabetes tipo 2.
*   **Índice de Masa Corporal (21.50 kg/m²):**  Dentro de rango normal, aunque no descarta la posibilidad de obesidad sarcopénica (poca masa muscular y exceso de grasa visceral).
*   **Función Pedigrí Diabético (0.1):**  Indica un bajo riesgo genético según este cálculo, pero no anula la importancia de los otros factores de riesgo presentes.
*   **Observaciones clínicas (dolor en los meniscos del pié, con herida totalmente escamosa y mucosidad):**  Estos hallazgos son preocupantes. El dolor en los meniscos podría ser neuropatía diabética, y la herida escamosa y con mucosidad sugiere una úlcera diabética infectada.  **Requiere evaluación urgente por un podólogo y un equipo de cuidado de heridas especializado.**

**3. RECOMENDACIONES DIAGNÓSTICAS:**

*   **HbA1c (Hemoglobina Glicosilada):**  Esencial para evaluar el control glucémico promedio en los últimos 2-3 meses. Un valor ≥ 6.5% es diagnóstico de diabetes.
*   **PTOG (Prueba de Tolerancia Oral a la Glucosa):**  Si la HbA1c no es diagnóstica pero la sospecha clínica es alta, la PTOG puede ser útil.
*   **Perfil lipídico completo:**  Para evaluar el riesgo cardiovascular y guiar las intervenciones terapéuticas.
*   **Examen de orina (microalbuminuria/proteinuria):**  Para detectar daño renal temprano asociado a la diabetes.
*   **Evaluación neurológica completa:**  Para descartar neuropatía diabética.
*   **Fondo de ojo:** Para descartar retinopatía diabética.
*   **Consulta con podólogo:**  Urgente, dada la condición de los pies.
*   **Medición de presión arterial ambulatoria (MAPA):**  Considerar si la presión arterial se encuentra elevada en la consulta.
*   **Evaluación de la función renal:** creatinina sérica y estimación de la tasa de filtrado glomerular (eTFG).

**Periodicidad de seguimiento recomendada:** Inicialmente, seguimiento estricto cada 1-3 meses hasta alcanzar control metabólico adecuado.  Posteriormente, cada 3-6 meses según la evolución.

**Criterios de derivación a especialista:**  **Urgente a podología.** Derivación a endocrinología para manejo de la diabetes, hipertensión y otros factores de riesgo.

**4. PLAN TERAPÉUTICO SUGERIDO:**

*   **Intervenciones no farmacológicas prioritarias:**
    *   **Educación terapéutica en diabetes:**  Fundamental para el manejo a largo plazo.
    *   **Dieta:**  Plan de alimentación saludable, baja en carbohidratos refinados y azúcares añadidos, rica en fibra, frutas, verduras y proteínas magras.  Derivación a nutricionista especializado en diabetes.
    *   **Ejercicio:**  Al menos 150 minutos de actividad física moderada a la semana, adaptada a las capacidades de la paciente, considerando el dolor en los meniscos.  Evaluar la necesidad de fisioterapia.
    *   **Cuidado de los pies:**  Educación sobre el cuidado adecuado de los pies, incluyendo inspección diaria, lavado suave y uso de calzado adecuado.

*   **Consideraciones farmacológicas:**
    *   **Hipoglucemiantes:**  Considerar iniciar metformina (si no hay contraindicaciones) como primera línea.  La elección del fármaco dependerá de la HbA1c, función renal y presencia de otras comorbilidades.  Inhibidores SGLT2 o agonistas GLP-1 podrían ser considerados.
    *   **Antihipertensivos:**  Inicie tratamiento antihipertensivo según guías clínicas actuales, priorizando fármacos que protejan la función renal (IECA/ARAII).
    *   **Estatina:**  Considerar estatina de moderada a alta intensidad para reducir el riesgo cardiovascular, independientemente de los niveles de colesterol.
    *   **Antibióticos:**  Considerar tratamiento antibiótico para la herida en el pie, luego de la evaluación y toma de muestra por cultivo.

*   **Objetivos terapéuticos específicos:**
    *   **HbA1c < 7%** (ajustar individualmente).
    *   **Presión arterial < 130/80 mmHg.**
    *   **LDL-colesterol < 100 mg/dL** (o menor según el riesgo cardiovascular).
    *   **Cierre y cicatrización de la úlcera diabética.**
    *   **Control del dolor y mejora de la movilidad.**

**5. FACTORES DE RIESGO MODIFICABLES:**

*   **Hiperglucemia:**  A través de dieta, ejercicio y medicación.
*   **Hipertensión:**  A través de dieta baja en sodio, ejercicio y medicación.
*   **Dislipidemia:**  A través de dieta baja en grasas saturadas y colesterol, ejercicio y medicación.
*   **Sedentarismo:**  Aumentar la actividad física.
*   **Mala adherencia al tratamiento:**  Mejorar la educación y el apoyo al paciente.
*   **Infección en el pie:** Con antibióticos y limpieza.

**Estrategias de prevención primaria/secundaria:**

*   **Prevención primaria:**  Evitar el desarrollo de diabetes y complicaciones en personas con prediabetes.
*   **Prevención secundaria:**  Retrasar la progresión de la diabetes y prevenir complicaciones en personas con diabetes ya diagnosticada. Esto se logra a través del control glucémico, presión arterial y lípidos, así como del manejo adecuado de los factores de riesgo modificables y de la educación del paciente.

Espero que esta evaluación sea de utilidad en su manejo de la paciente Ana Sánchez. Quedo a su disposición para cualquier consulta adicional.
', NULL, NULL, 1),
(86, 83, 170.0, 132.0, 30.0, 2, 20.800000000000001, 0.20000000000000001, 75, .590, 'herida en el dedo de la mano izquierda', '2025-03-10 03:45:28.597', '2025-03-10 04:18:54.117', 130.0, 46.60, '2025-10-03 04:18:07.51', '2025-10-03 04:18:54.12', '<p>Aquí está la evaluación clínica solicitada, presentada en el formato especificado:</p><p><h3>ESTRATIFICACIÓN DE RIESGO DIABÉTICO</h3></p><p><h4>Clasificación de Riesgo</h4>ALTO</p><p><h4>Justificación basada en evidencia clínica</h4><ul><li>La glucosa plasmática elevada (170 mg/dL) excede el umbral diagnóstico para diabetes (generalmente >126 mg/dL en ayunas o >200 mg/dL al azar o postprandial).</li><li>La presión arterial diastólica muy elevada (132 mmHg) sugiere hipertensión significativa, un factor de riesgo importante para complicaciones diabéticas.</li><li>Aunque el IMC está dentro del rango normal, otros factores apuntan a un alto riesgo.</li><li>La edad avanzada (75 años) es un factor de riesgo independiente para desarrollar diabetes tipo 2.</li></ul></p><p><h4>Correlación con el resultado de la predicción de IA obtenido</h4>El resultado de la predicción de IA (64% de probabilidad, riesgo ALTO) coincide con la evaluación clínica basada en los biomarcadores. Esto refuerza la necesidad de una investigación diagnóstica exhaustiva.</p><p><h3>INTERPRETACIÓN CLÍNICA POR PARÁMETROS</h3></p><p><table><tr><th>Biomarcador</th><th>Valor</th><th>Interpretación Clínica</th><th>Valor de Referencia</th><th>Desviación</th></tr><tr><td>Glucosa plasmática</td><td>170 mg/dL</td><td>Elevada. Indica hiperglucemia y posible resistencia a la insulina o deficiencia de insulina. Es muy probable que sea indicativa de diabetes.</td><td>70-99 mg/dL (ayunas)</td><td>Significativa. Muy por encima del rango normal.</td></tr><tr><td>Presión arterial diastólica</td><td>132 mmHg</td><td>Muy elevada. Indica hipertensión diastólica severa. Un factor de riesgo importante para enfermedad cardiovascular y complicaciones diabéticas.</td><td>< 80 mmHg</td><td>Significativa. Muy por encima del rango normal.</td></tr><tr><td>Grosor del pliegue cutáneo tricipital</td><td>30 mm</td><td>Relativamente normal para una mujer de 75 años. Sugiere una adiposidad subcutánea dentro de rangos aceptables.</td><td>Varia según edad y sexo, consultar tablas de referencia. Considerar percentiles.</td><td>Considerar percentil por edad y sexo.</td></tr><tr><td>Insulina sérica</td><td>130 μU/mL</td><td>Elevada. Podría indicar resistencia a la insulina (el páncreas produce más insulina para compensar la resistencia). Requiere interpretación en el contexto de la glucosa y otros factores.</td><td>3-25 μU/mL (ayunas)</td><td>Significativa. Muy por encima del rango normal en ayunas.</td></tr><tr><td>Índice de Masa Corporal</td><td>20.80 kg/m²</td><td>Normal. Indica un peso saludable según la escala de IMC. No obstante, no excluye la posibilidad de diabetes ni de obesidad sarcopénica (pérdida de masa muscular).</td><td>18.5-24.9 kg/m²</td><td>Dentro del rango normal.</td></tr><tr><td>Función Pedigrí Diabético</td><td>0.2</td><td>Bajo. Sugiere un riesgo genético relativamente bajo de diabetes basado en el historial familiar. Sin embargo, no descarta la posibilidad de diabetes, especialmente en presencia de otros factores de riesgo.</td><td>Varía según la población y el algoritmo utilizado.</td><td>Considerar en contexto del algoritmo utilizado.</td></tr></table></p><p><h3>RECOMENDACIONES DIAGNÓSTICAS</h3></p><p><h4>Estudios Complementarios</h4>-<h3>Hemoglobina A1c (HbA1c):</h3>Esencial para evaluar el control glucémico a largo plazo (últimos 2-3 meses). Un valor ≥ 6.5% confirma el diagnóstico de diabetes.
-<h3>Prueba de Tolerancia Oral a la Glucosa (PTOG):</h3>Considerar si la HbA1c no es diagnóstica pero persiste la sospecha clínica. Mide la respuesta del cuerpo a una carga de glucosa.
-<h3>Perfil lipídico completo:</h3>Para evaluar el riesgo cardiovascular (colesterol total, HDL, LDL, triglicéridos).
-<h3>Función renal:</h3>Creatinina sérica, eGFR, relación albúmina/creatinina en orina (para evaluar la nefropatía diabética).
-<h3>Electrocardiograma (ECG):</h3>Para evaluar la función cardíaca.
-<h3>Examen del pie:</h3>Debido a la herida en la mano, se debe prestar especial atención a la salud de los pies, descartando neuropatía y enfermedad arterial periférica.</p><p><h4>Periodicidad de Seguimiento</h4>- Inicialmente, seguimiento estrecho (cada 1-3 meses) una vez implementado el plan terapéutico para ajustar la medicación y evaluar la respuesta.
- Una vez estable, seguimiento cada 3-6 meses, dependiendo del control glucémico y la presencia de complicaciones.</p><p><h4>Criterios de Derivación a Especialista</h4>-<h3>Endocrinólogo:</h3>Si el control glucémico es inadecuado a pesar del tratamiento inicial, si hay complicaciones diabéticas significativas (nefropatía, retinopatía, neuropatía severa), o si la paciente requiere terapia con insulina compleja.
-<h3>Cardiólogo:</h3>Debido a la hipertensión severa y el riesgo cardiovascular elevado.
-<h3>Podólogo:</h3>Para el manejo de la herida en el dedo de la mano y la evaluación del riesgo de pie diabético.
-<h3>Oftalmólogo:</h3>Para una evaluación oftalmológica completa y detección de retinopatía diabética.</p><p><h3>PLAN TERAPÉUTICO SUGERIDO</h3></p><p><h4>Intervenciones No Farmacológicas Prioritarias</h4>-<h3>Educación diabetológica:</h3>Fundamental para que la paciente comprenda la enfermedad, aprenda a automonitorear la glucosa, administrarse medicación (si es necesaria), y reconocer y tratar hipoglucemias.
-<h3>Plan de alimentación saludable:</h3>Priorizar alimentos integrales, frutas, verduras, proteínas magras y grasas saludables. Limitar los carbohidratos refinados y los azúcares añadidos. Considerar la consulta con un nutricionista.
-<h3>Actividad física regular:</h3>Recomendar al menos 150 minutos de actividad física moderada por semana (caminar, nadar, bicicleta). Adaptar el tipo y la intensidad a la capacidad física de la paciente.
-<h3>Control del peso:</h3>Si bien el IMC es normal, mantener un peso estable es importante. Evitar la pérdida de masa muscular relacionada con la edad.
-<h3>Cese del tabaquismo:</h3>Si aplica.</p><p><h4>Consideraciones Farmacológicas</h4>-<h3>Metformina:</h3>Considerar como tratamiento de primera línea, a menos que haya contraindicaciones (insuficiencia renal severa, enfermedad hepática). Iniciar con dosis bajas y aumentar gradualmente para minimizar los efectos secundarios gastrointestinales.
-<h3>Inhibidores de la DPP-4 (gliptinas):</h3>Una opción si la metformina no es suficiente o está contraindicada.
-<h3>Inhibidores de SGLT2 (gliflozinas):</h3>Pueden ser beneficiosos en pacientes con enfermedad cardiovascular establecida o alto riesgo cardiovascular, y pueden tener beneficios adicionales en la función renal.<div class=''warning-block''>Precaución: Evaluar cuidadosamente la función renal antes de iniciar gliflozinas y monitorear los efectos secundarios (infecciones genitourinarias, hipotensión).</div>-<h3>Insulina:</h3>Considerar si el control glucémico no se logra con medicación oral.</p><p><h4>Objetivos Terapéuticos Específicos</h4>-<h3>HbA1c:</h3>< 7.0% (individualizar según la edad, las comorbilidades y el riesgo de hipoglucemia).
- <h3>Glucosa plasmática preprandial:</h3>80-130 mg/dL.
-<h3>Glucosa plasmática postprandial (2 horas):</h3>< 180 mg/dL.
- <h3>Presión arterial:</h3>< 130/80 mmHg (considerar incluso objetivos más bajos individualmente, pero siempre evitando la hipotensión).
- <h3>Colesterol LDL:</h3>< 100 mg/dL (individualizar según el riesgo cardiovascular).</p><p><h3>FACTORES DE RIESGO MODIFICABLES</h3></p><p><h4>Identificación de Elementos Intervenibles</h4>-<h3>Hiperglucemia:</h3>Controlada mediante dieta, ejercicio y medicación.
-<h3>Hipertensión:</h3>Controlada mediante dieta baja en sodio, ejercicio, y medicación antihipertensiva.
-<h3>Estilo de vida sedentario:</h3>Promover la actividad física regular.
-<h3>Dieta poco saludable:</h3>Promover una dieta rica en fibra, baja en grasas saturadas y azúcares añadidos.
-<h3>Adherencia al tratamiento:</h3>Educación y apoyo para mejorar la adherencia a la medicación y a las recomendaciones de estilo de vida.</p><p><h4>Estrategias de Prevención Primaria/Secundaria</h4>-<h3>Prevención primaria:</h3>En personas con prediabetes o alto riesgo de diabetes, las intervenciones de estilo de vida (dieta y ejercicio) pueden prevenir o retrasar la aparición de la diabetes tipo 2.
-<h3>Prevención secundaria:</h3>En personas con diabetes establecida, el control glucémico, la presión arterial y el colesterol pueden prevenir o retrasar la aparición de complicaciones diabéticas (enfermedad cardiovascular, nefropatía, retinopatía, neuropatía).</p><p><h3>CONSIDERACIONES SOBRE LAS OBSERVACIONES MÉDICAS</h3></p><p><h4>Análisis de las Observaciones del Profesional de Salud</h4>La presencia de una herida en el dedo de la mano izquierda en una paciente con posible diabetes es preocupante. La diabetes puede afectar la cicatrización de heridas y aumentar el riesgo de infección.</p><p><h4>Integración de estos Hallazgos con los Parámetros Clínicos</h4>La hiperglucemia no controlada puede comprometer la función de los neutrófilos y otros componentes del sistema inmunitario, lo que aumenta el riesgo de infección y dificulta la cicatrización de heridas.</p><p><h4>Recomendaciones Específicas Basadas en Estas Observaciones</h4>-<h3>Evaluar la herida:</h3>Determinar la causa de la herida, evaluar la presencia de signos de infección (enrojecimiento, calor, hinchazón, dolor, secreción).
-<h3>Cuidado de la herida:</h3>Limpiar la herida con agua y jabón suave, aplicar un apósito estéril, y cambiar el apósito diariamente.
-<h3>Descartar infección:</h3>Si hay signos de infección, obtener un cultivo de la herida y iniciar tratamiento antibiótico empírico de amplio espectro.
-<h3>Control glucémico estricto:</h3>Optimizar el control glucémico para mejorar la cicatrización de la herida.
-<h3>Evaluar la circulación periférica:</h3>Considerar realizar un índice tobillo-brazo (ITB) para evaluar la enfermedad arterial periférica, que puede comprometer la cicatrización de heridas.
-<h3>Consultar con un podólogo o cirujano vascular:</h3>Si la herida no cicatriza adecuadamente o si hay signos de enfermedad arterial periférica.<div class=''info-block''>Es crucial una evaluación exhaustiva de la herida en el dedo, con seguimiento cercano debido al riesgo de complicaciones en pacientes diabéticos.</div></p>', NULL, NULL, 1),
(87, 84, 185.0, 130.0, 40.0, 3, 27.100000000000001, 0.10000000000000001, 50, .670, 'presenta ronchas marrones y cuello negreando', '2025-03-10 04:21:24.103', '2025-03-10 04:21:30.143', 130.0, 25.78, '2025-10-03 04:21:04.36', '2025-10-03 04:21:30.14', '<p>¡Entendido! Aquí está la evaluación clínica solicitada, diseñada para apoyar la toma de decisiones del médico tratante de Laura Díaz.</p><p><h3>1. ESTRATIFICACIÓN DE RIESGO DIABÉTICO</h3></p><p><ul><li><h3>Clasificación: ALTO riesgo.</h3></li><li><h3>Justificación:</h3>Varios factores apuntan a un alto riesgo de diabetes tipo 2 o, incluso, a una diabetes no diagnosticada. La glucosa plasmática elevada (185 mg/dL), la presión arterial diastólica alta (130 mmHg), el grosor del pliegue cutáneo tricipital aumentado (40mm), y las observaciones clínicas (ronchas marrones y cuello negreando) sugieren una fuerte posibilidad de resistencia a la insulina y disfunción metabólica. Si bien el IMC se encuentra en sobrepeso y no en obesidad, otros factores compensan para un riesgo alto. Los datos del protocolo Pima Indians, aunque útiles, deben complementarse con estudios más específicos.</li></p><p><h3>2. INTERPRETACIÓN CLÍNICA POR PARÁMETROS</h3></p><p><table><tr><th>Biomarcador</th><th>Valor</th><th>Interpretación Clínica</th><th>Valor de Referencia</th><th>Desviación</th></tr><tr><td>Embarazos</td><td>3</td><td>La paridad incrementa el riesgo de desarrollar diabetes gestacional, que a su vez aumenta el riesgo futuro de diabetes tipo 2. Aunque es un factor de riesgo histórico, aún es relevante.</td><td>N/A</td><td>N/A</td></tr><tr><td>Glucosa Plasmática</td><td>185 mg/dL</td><td>Significativamente elevada. Sugiere hiperglucemia y posible diabetes no diagnosticada. Requiere confirmación diagnóstica.</td><td>70-99 mg/dL (en ayunas)</td><td>Muy alta (+86 mg/dL)</td></tr><tr><td>Presión Arterial Diastólica</td><td>130 mmHg</td><td>Muy elevada. Sugiere hipertensión arterial severa. Requiere manejo inmediato.</td><td>60-80 mmHg</td><td>Muy alta (+50 mmHg)</td></tr><tr><td>Grosor del Pliegue Cutáneo Tricipital</td><td>40 mm</td><td>Elevado. Indica exceso de grasa subcutánea, asociado a resistencia a la insulina.</td><td>Mujeres:<30 mm</td><td>Alta (+10 mm)</td></tr><tr><td>Insulina Sérica</td><td>130 μU/mL</td><td>Elevada. Puede indicar resistencia a la insulina y un intento del páncreas para compensar. Necesita ser interpretada junto con otros parámetros.</td><td>3-25 μU/mL (en ayunas)</td><td>Muy alta (+105 μU/mL)</td></tr><tr><td>Índice de Masa Corporal</td><td>27.10 kg/m²</td><td>Sobrepeso. Incrementa el riesgo de resistencia a la insulina y diabetes tipo 2.</td><td>18.5-24.9 kg/m²</td><td>Elevada (+2.2 kg/m²)</td></tr><tr><td>Función Pedigrí Diabético</td><td>0.1</td><td>Bajo. Indica un bajo riesgo genético basado en el historial familiar de diabetes. Sin embargo, no anula la importancia de otros factores.</td><td>Varía según la población</td><td>Baja</td></tr></table></p><p><h4>Correlaciones Fisiopatológicas Relevantes</h4></p><p><li>La combinación de glucosa plasmática elevada, insulina sérica alta y el grosor del pliegue cutáneo tricipital sugiere una marcada resistencia a la insulina.</li><li>La hipertensión arterial diastólica severa (130 mmHg) es una comorbilidad común en pacientes con resistencia a la insulina y diabetes.</li><li>El cuello negreando (Acanthosis nigricans), junto con estos parámetros, refuerza la hipótesis de resistencia a la insulina.</li></p><p><h3>3. RECOMENDACIONES DIAGNÓSTICAS</h3></p><p><li><h3>HbA1c (Hemoglobina Glicosilada):</h3>Indispensable para evaluar el control glucémico a largo plazo.</li><li><h3>PTOG (Prueba de Tolerancia Oral a la Glucosa):</h3>Para confirmar o descartar diabetes, especialmente si la glucosa plasmática en ayunas no es concluyente.</li><li><h3>Perfil Lipídico Completo:</h3>Para evaluar el riesgo cardiovascular (colesterol total, LDL, HDL, triglicéridos).</li><li><h3>Función Renal (Creatinina, BUN):</h3>Para evaluar la función renal basal, dado el riesgo de nefropatía diabética.</li><li><h3>Examen de Orina (Albúmina/Creatinina):</h3>Para detectar albuminuria, un signo temprano de daño renal.</li></p><p><h4>Periodicidad de Seguimiento Recomendada</h4></p><p><li>Inicialmente, un seguimiento estrecho (cada 1-3 meses) para ajustar el tratamiento y evaluar la respuesta.</li><li>Una vez estabilizado, seguimiento cada 3-6 meses.</li><li>Revisión anual completa, incluyendo evaluación oftalmológica para descartar retinopatía diabética.</li></p><p><h4>Criterios de Derivación a Especialista</h4></p><p><li>Falta de respuesta al tratamiento inicial.</li><li>Complicaciones diabéticas (nefropatía, retinopatía, neuropatía).</li><li>Dudas en el manejo farmacológico o necesidad de terapia con insulina.</li><li>Sospecha de diabetes tipo 1 (especialmente si hay pérdida de peso inexplicable).</li></p><p><h3>4. PLAN TERAPÉUTICO SUGERIDO</h3></p><p><div class=''info-block''>Es fundamental enfatizar que este plan es una sugerencia y debe individualizarse según la evaluación completa del médico tratante.</div></p><p><h4>Intervenciones No Farmacológicas Prioritarias</h4></p><p><li><h3>Modificación de la Dieta:</h3></li>* Reducción de carbohidratos refinados y azúcares simples. * Aumento de fibra (verduras, frutas, granos integrales). * Control de porciones. * Considerar la consulta con un nutricionista especializado en diabetes.<li><h3>Actividad Física:</h3></li>* Al menos 150 minutos de ejercicio aeróbico de intensidad moderada por semana. * Ejercicios de resistencia (pesas) para mejorar la sensibilidad a la insulina. * Adaptar el programa de ejercicio a las capacidades de la paciente.<li><h3>Educación Diabetológica:</h3></li>* Enseñar el automonitoreo de la glucosa. * Informar sobre el manejo de hipoglucemias e hiperglucemias. * Promover la adherencia al tratamiento.</p><p><h4>Consideraciones Farmacológicas</h4></p><p><li><h3>Metformina:</h3>Considerar como primera línea, si no hay contraindicaciones (insuficiencia renal grave, enfermedad hepática).</li><li><h3>Inhibidores de la DPP-4 o Inhibidores de SGLT2:</h3>Considerar si la metformina no es suficiente o está contraindicada, o si se busca un beneficio adicional en la pérdida de peso o protección cardiovascular/renal.</li><li><div class=''warning-block''>Debido a la presión arterial diastólica muy elevada (130 mmHg), el manejo antihipertensivo debe ser una prioridad. Considerar un IECA o un ARA II, dado su beneficio en la protección renal, especialmente si hay albuminuria.</div></li></p><p><h4>Objetivos Terapéuticos Específicos</h4></p><p><li>HbA1c< 7% (o individualizar según la edad y comorbilidades).</li><li>Glucosa plasmática preprandial: 80-130 mg/dL.</li><li>Glucosa plasmática postprandial (2 horas):<180 mg/dL.</li><li>Presión arterial:<130/80 mmHg (o individualizar según las guías).</li><li>Colesterol LDL:<100 mg/dL (o individualizar según el riesgo cardiovascular).</li></p><p><h3>5. FACTORES DE RIESGO MODIFICABLES</h3></p><p><li><h3>Peso:</h3>La pérdida de peso modesta (5-10%) puede mejorar significativamente la sensibilidad a la insulina.</li><li><h3>Dieta:</h3>La elección de alimentos saludables y el control de porciones son cruciales.</li><li><h3>Actividad Física:</h3>El ejercicio regular es fundamental para mejorar la sensibilidad a la insulina y controlar el peso.</li><li><h3>Hipertensión:</h3>El control adecuado de la presión arterial reduce el riesgo de complicaciones cardiovasculares y renales.</li><li><h3>Tabaquismo:</h3>Si la paciente fuma, ofrecer apoyo para dejar de fumar.</li></p><p><h3>6. CONSIDERACIONES SOBRE LAS OBSERVACIONES MÉDICAS</h3></p><p><li><h3>Ronchas Marrones y Cuello Negreando (Acanthosis Nigricans):</h3>Altamente sugestivo de resistencia a la insulina. Indica la necesidad de una evaluación exhaustiva de la función metabólica.</li><li><h3>Integración:</h3>Estos hallazgos visuales refuerzan la necesidad de un manejo agresivo de la resistencia a la insulina, tanto con intervenciones no farmacológicas como farmacológicas (si es necesario).</li><li><h3>Recomendaciones Específicas:</h3></li></ul>* Evaluar la presencia de otros signos de resistencia a la insulina (ej. acrocordones). * Explicar a la paciente la relación entre las ronchas y el cuello negreando con la resistencia a la insulina, lo cual puede mejorar la adherencia al tratamiento. *<div class=''success-block''>Si la resistencia a la insulina se maneja adecuadamente, la Acanthosis Nigricans puede mejorar o desaparecer.</div></p><p>Espero que esta evaluación detallada sea útil para la toma de decisiones clínicas del médico tratante. Estoy a disposición para cualquier consulta adicional.</p>', NULL, NULL, 1),
(88, 85, 185.0, 140.0, 30.0, 0, 29.399999999999999, 2.0, 50, .620, 'presenta sensacion de sed y presion alta', '2025-03-10 06:32:26.760', '2025-03-10 06:51:01.747', 132.0, 22.66, '2025-10-03 06:50:39.08', '2025-10-03 06:51:01.75', 'De acuerdo, aquí tienes la evaluación clínica solicitada para el paciente Edward Steven Quispe Sanchez, enfocada en apoyar la toma de decisiones del médico tratante.<br /><h3>🎯 1. ESTRATIFICACIÓN DE RIESGO DIABÉTICO</h3><br /><h4>🔍 Clasificación del Riesgo: ALTO</h4><br /><h5>Justificación:</h5><br />
- La 🍯 glucosa plasmática elevada (185 mg/dL) indica hiperglucemia, un criterio fundamental para el diagnóstico de diabetes.<br />
- La ❤️ presión arterial diastólica elevada (140 mmHg) sugiere hipertensión, una comorbilidad común y factor de riesgo para diabetes tipo 2.<br />
- El ⚖️ IMC de 29.40 kg/m² clasifica al paciente como con sobre⚖️ peso, un factor de riesgo importante para la resistencia a la 💉 insulina y diabetes.<br />
- La 💉 insulina sérica elevada (132 μU/mL) sugiere resistencia a la 💉 insulina, un mecanismo central en la fisiopatología de la diabetes tipo 2.<br />
- El valor de la función pedigrí diabético de 2 indica una fuerte predisposición genética a la diabetes.<br />
- Combinando estos factores, el riesgo de diabetes tipo 2 es considerado alto.<br /><h5>Correlación con Predicción de IA:</h5><br />
- La estratificación de riesgo ALTO coincide con el resultado de la predicción de la IA (62% de probabilidad de diabetes tipo 2) y refuerza la necesidad de una evaluación diagnóstica exhaustiva.<br /><h3>📊 2. INTERPRETACIÓN CLÍNICA POR PARÁMETROS</h3><br /><table><br /><tr><br /><th>Biomarcador</th><br /><th>Valor</th><br /><th>Interpretación Clínica</th><br /><th>Valor de Referencia</th><br /><th>Desviación</th><br /></tr><br /><tr><br /><td>Embarazos</td><br /><td>0</td><br /><td>No relevante para este paciente masculino.</td><br /><td>N/A</td><br /><td>N/A</td><br /></tr><br /><tr><br /><td>🍯 Glucosa plasmática</td><br /><td>185 mg/dL</td><br /><td>Hiperglucemia significativa, sugiere alteración en la homeostasis de la 🍯 glucosa.  Podría indicar resistencia a la 💉 insulina o deficiencia en la secreción de 💉 insulina.</td><br /><td>70-99 mg/dL (en ayunas)</td><br /><td>Elevada significativamente</td><br /></tr><br /><tr><br /><td>❤️ Presión arterial diastólica</td><br /><td>140 mmHg</td><br /><td>Hipertensión diastólica severa. Aumenta el riesgo cardiovascular y puede exacerbar la progresión de la diabetes.</td><br /><td>60-80 mmHg</td><br /><td>Elevada peligrosamente</td><br /></tr><br /><tr><br /><td>Grosor del pliegue cutáneo tricipital</td><br /><td>30 mm</td><br /><td>Sugiere un porcentaje de grasa corporal elevado, contribuyendo a la resistencia a la 💉 insulina.</td><br /><td>Varía con edad y género (valor elevado relativo para un hombre de 50 años)</td><br /><td>Elevado</td><br /></tr><br /><tr><br /><td>💉 Insulina sérica</td><br /><td>132 μU/mL</td><br /><td>Hiperinsulinemia compensatoria, consistente con resistencia a la 💉 insulina.  El páncreas está produciendo más 💉 insulina para mantener los niveles de 🍯 glucosa en rango.</td><br /><td>2-25 μU/mL (en ayunas)</td><br /><td>Elevada significativamente</td><br /></tr><br /><tr><br /><td>Índice de Masa Corporal</td><br /><td>29.40 kg/m²</td><br /><td>Sobre⚖️ peso.  Aumenta el riesgo de resistencia a la 💉 insulina, dislipidemia y otras comorbilidades metabólicas.</td><br /><td>18.5-24.9 kg/m² (Normal)</td><br /><td>Elevado</td><br /></tr><br /><tr><br /><td>Función Pedigrí Diabético</td><br /><td>2</td><br /><td>Predisposición genética fuerte a diabetes. Aumenta significativamente el riesgo de desarrollar la enfermedad.</td><br /><td>Varía (valor alto)</td><br /><td>Elevado</td><br /></tr><br /></table><br /><h3>💡 3. RECOMENDACIONES DIAGNÓSTICAS</h3><br /><h4>🔬 Estudios Complementarios:</h4><br />
- <div class=''info-block''>🩸 HbA1c (Hemoglobina Glicosilada):  Para evaluar el 🎛️ control glucémico promedio en los últimos 2-3 meses.  Un valor ≥ 6.5% confirma el diagnóstico de diabetes.</div><br />
- 🥤 PTOG (Prueba de Tolerancia Oral a la 🍯 Glucosa):  Para evaluar la respuesta del cuerpo a una carga de 🍯 glucosa.  Útil si la 🩸 HbA1c no es concluyente.<br />
- Perfil lipídico completo: Para evaluar el riesgo cardiovascular y detectar dislipidemia.<br />
- Examen de orina con microalbuminuria: Para detectar daño renal temprano.<br /><h4>📅 Periodicidad de 📋 Seguimiento:</h4><br />
- Inicialmente, 📋 seguimiento semanal o quincenal para ajustar el plan terapéutico una vez confirmado el diagnóstico.<br />
- Luego, 📋 seguimiento trimestral con 🩸 HbA1c para evaluar el 🎛️ control glucémico a largo plazo.<br /><h4>📏 Criterios de Derivación a Especialista:</h4><br />
- Si el 🎛️ control glucémico es inadecuado a pesar de las intervenciones iniciales.<br />
- Si hay complicaciones diabéticas presentes (nefropatía, retinopatía, neuropatía).<br />
- Si el paciente requiere insulinoterapia compleja.<br /><h3>🏥 4. PLAN TERAPÉUTICO SUGERIDO</h3><br /><h4>🎯 Intervenciones No Farmacológicas:</h4><br />
- <div class=''success-block''>Cambios en el estilo de vida son fundamentales.</div><br />
- 🥗 Dieta:  Reducción de carbohidratos refinados, grasas saturadas y azúcares añadidos.  Aumento de la ingesta de fibra, frutas y verduras. Consulta con un nutricionista es altamente recomendable.<br />
- 🏃‍♂️ Ejercicio:  Al menos 150 minutos de actividad física moderada por semana (caminata rápida, natación, bicicleta).  🏃‍♂️ Ejercicios de resistencia para aumentar la masa muscular.<br />
- Pérdida de ⚖️ peso:  Objetivo realista de perder entre el 5-10% del ⚖️ peso corporal actual.<br />
- Educación:  El paciente debe comprender la diabetes, su manejo y las complicaciones potenciales.<br /><h4>💊 Consideraciones Farmacológicas:</h4><br />
- <div class=''warning-block''>IMPORTANTE: La decisión de iniciar farmacoterapia debe basarse en los resultados de las pruebas diagnósticas complementarias (🩸 HbA1c, 🥤 PTOG) y la evaluación clínica integral.</div><br />
- Metformina:  Considerar como primera línea si la 🩸 HbA1c está significativamente elevada y no hay contraindicaciones.<br />
- Otros agentes:  Inhibidores de la DPP-4, inhibidores de la SGLT2, agonistas del receptor GLP-1 pueden ser considerados en combinación con metformina o como alternativas, dependiendo de las características del paciente y la presencia de comorbilidades.<br /><h4>🎯 Objetivos Terapéuticos:</h4><br />
- 🩸 HbA1c < 7%.<br />
- 🍯 Glucosa plasmática en ayunas: 80-130 mg/dL.<br />
- ❤️ Presión arterial: < 130/80 mmHg.<br />
- Perfil lipídico: LDL-colesterol < 100 mg/dL.<br /><h3>⚠️ 5. FACTORES DE RIESGO MODIFICABLES</h3><br /><h4>🔍 Identificación de Elementos Intervenibles:</h4><br />
- Obesidad/Sobre⚖️ peso: <div class=''info-block''>Intervención dietética y 🏃‍♂️ ejercicio.</div><br />
- Inactividad física: <div class=''info-block''>Aumentar la actividad física regular.</div><br />
- 🥗 Dieta inadecuada: <div class=''info-block''>Educación nutricional y cambio de hábitos alimenticios.</div><br />
- Hipertensión: <div class=''info-block''>🎛️ Control de la ❤️ presión arterial mediante 🥗 dieta baja en sodio, 🏃‍♂️ ejercicio y, si es necesario, medicación antihipertensiva.</div><br />
- Resistencia a la 💉 insulina: <div class=''info-block''>Mejora de la sensibilidad a la 💉 insulina mediante 🏃‍♂️ ejercicio y, potencialmente, medicación.</div><br /><h4>📈 Estrategias de Prevención Primaria/Secundaria:</h4><br />
- Prevención Primaria: Para prevenir el desarrollo de diabetes en individuos con alto riesgo, pero sin la enfermedad.  Se enfoca en modificar los factores de riesgo mencionados anteriormente.<br />
- Prevención Secundaria: Para prevenir las complicaciones de la diabetes en individuos que ya han sido diagnosticados. Se enfoca en el 🎛️ control glucémico, el 🎛️ control de la ❤️ presión arterial y el 🎛️ control del perfil lipídico.<br /><h3>📋 6. CONSIDERACIONES SOBRE LAS OBSERVACIONES MÉDICAS</h3><br /><h4>🧪 Análisis de las Observaciones del Profesional:</h4><br />
- Sensación de Sed (Polidipsia): Es un síntoma clásico de hiperglucemia. El cuerpo intenta diluir el exceso de 🍯 glucosa en la sangre, lo que lleva a un aumento de la sed.<br />
- Presión Arterial Alta: La hipertensión es una comorbilidad frecuente en pacientes con diabetes tipo 2 y aumenta el riesgo cardiovascular.<br /><h4>Integración de Hallazgos:</h4><br />
- La polidipsia apoya el diagnóstico de hiperglucemia y refuerza la necesidad de pruebas diagnósticas confirmatorias (🩸 HbA1c, 🥤 PTOG).<br />
- La hipertensión requiere una evaluación exhaustiva y un plan de manejo específico (🥗 dieta baja en sodio, 🏃‍♂️ ejercicio, medicación antihipertensiva).<br /><h4>Recomendaciones Específicas:</h4><br />
- Monitoreo de la ❤️ presión arterial:  Mediciones regulares en el consultorio y en casa.<br />
- Evaluación cardiovascular:  Electrocardiograma (ECG) y evaluación de otros factores de riesgo cardiovascular.<br />
- Abordar la polidipsia: Aumentar la ingesta de líquidos (agua, infusiones no azucaradas) y 🎛️ controlar la glucemia.<br />
- Considerar el inicio temprano de tratamiento antihipertensivo si la ❤️ presión arterial permanece elevada a pesar de las intervenciones no farmacológicas.<br />
Esta evaluación está diseñada para proporcionar una base sólida para la toma de decisiones clínicas.  Es fundamental que el médico tratante integre esta información con su propio juicio clínico y la historia completa del paciente para desarrollar un plan de manejo individualizado.', NULL, NULL, 1),
(89, 86, 210.0, 130.0, 30.0, 0, 30.5, 1.25, 50, .660, 'presenta hemorragia nasl', '2025-03-10 15:44:55.080', '2025-03-10 15:53:00.077', 1.2, 371.53, '2025-12-19 15:10:48.50', '2025-12-19 15:17:00.04', '<ul>Como asistente médico experto en diabetes tipo 2, he revisado cuidadosamente la información proporcionada del paciente Edward Steven Quispe Sanchez, incluyendo el documento adjunto.<br />
--- Contenido extraído del documento adjunto (1766157352_69456c28a7f83_DATOS CLÍNICOS DEL PACIENTE.docx) ---<br />
El documento adjunto, titulado "DATOS CLÍNICOS DEL PACIENTE - PERSONA CON DIABETES", contiene los siguientes datos:<br /><li>  Número de Embarazos: N/A</li><li>  🍯 Glucosa (mg/dL): 210</li><li>  Presión Sanguínea (mmHg): 140/90</li><li>  Grosor de Piel (mm): 48</li><li>  💉 Insulina (µU/mL): 250</li><li>  Función Pedigree de Diabetes: 1.25</li><strong>Análisis Detallado de la Información del Paciente:</strong><br>
Es crucial señalar una contradicción significativa entre la predicción de IA y el contenido del documento adjunto. La predicción de IA indica "NEGATIVO para Diabetes Tipo 2" con un "Nivel de Riesgo: MUY BAJO". Sin embargo, el título del documento adjunto, que representa datos clínicos del paciente, establece explícitamente "DATOS CLÍNICOS DEL PACIENTE - <strong>PERSONA CON DIABETES</strong>". Ante esta discrepancia, se prioriza la información clínica proporcionada en el documento, asumiendo que el paciente Edward Steven Quispe Sanchez <strong>ya tiene un diagnóstico establecido de Diabetes Tipo 2</strong>. La predicción de la IA, en este contexto, es errónea o se basa en un conjunto de datos incompleto o desactualizado.<br />
A continuación, se presenta un análisis detallado utilizando la información más completa y actualizada (priorizando el documento adjunto en caso de conflicto):<br />
1.  <strong>Datos Demográficos:</strong><br>
    *   Nombre: Edward Steven Quispe Sanchez<br />
    *   Sexo: Masculino<br />
    *   Edad: 50 años<br />
    *   Número de Embarazos: 0 (consistente)<br />
2.  <strong>Parámetros Clínicos Relevantes:</strong><br>
    *   <strong>🍯 Glucosa:</strong> 210 mg/dL. Este valor es consistentemente alto en ambas fuentes y, en el contexto de un paciente "con diabetes", indica un 🎛️ control glucémico deficiente o descompensación. Un valor de 🍯 glucosa aleatoria ≥ 200 mg/dL es diagnóstico de diabetes si se acompaña de síntomas, o si se presenta en el contexto de un paciente ya diagnosticado, indica hiperglucemia.<br />
    *   <strong>Presión Sanguínea:</strong> 140/90 mmHg (tomado del documento adjunto, que ofrece un valor diastólico). Este es un valor elevado que clasifica al paciente con Hipertensión Arterial Estadio 1 según las guías (≥130/80 mmHg). La hipertensión es una comorbilidad frecuente y grave en pacientes con diabetes tipo 2, aumentando el riesgo cardiovascular.<br />
    *   <strong>Grosor de la Piel:</strong> 48 mm (tomado del documento adjunto). Este parámetro se utiliza en algunos modelos predictivos para evaluar la adiposidad subcutánea, aunque el ⚖️ IMC es un indicador más directo de la obesidad general.<br />
    *   <strong>💉 Insulina:</strong> 250 µU/mL (tomado del documento adjunto). Este es un nivel de 💉 insulina extremadamente alto. Indica una hiperinsulinemia significativa, que puede ser un signo de resistencia a la 💉 insulina severa (el páncreas está produciendo mucha 💉 insulina para tratar de compensar la alta 🍯 glucosa), o podría ser resultado de una inyección reciente de 💉 insulina exógena si el paciente ya estuviera en tratamiento con 💉 insulina, aunque esto último no se especifica. Si es endógena, es preocupante por la carga pancreática.<br />
    *   <strong>⚖️ IMC:</strong> 30.50 kg/m² (tomado de la información inicial, no presente en el adjunto). Este ⚖️ IMC clasifica al paciente con Obesidad Clase I (⚖️ IMC 30-34.9 kg/m²). La obesidad es un factor de riesgo principal para la resistencia a la 💉 insulina y la progresión de la diabetes tipo 2.<br />
    *   <strong>Función Pedigree de Diabetes:</strong> 1.25. Este es un valor alto, sugiriendo una fuerte predisposición genética a la diabetes tipo 2, lo que refuerza su diagnóstico y la necesidad de un manejo intensivo.<br />
3.  <strong>Observaciones Médicas:</strong><br>
    *   "presenta hemorragia nasal". Esta es una observación importante que requiere investigación. Podría estar relacionada con la hipertensión no 🎛️ controlada (140/90 mmHg), trastornos de la coagulación, sequedad de las mucosas, o causas locales. Es una preocupación aguda que debe abordarse.<br /><strong>Estratificación de Riesgo:</strong><br>
Dado que Edward Steven Quispe Sanchez es una "PERSONA CON DIABETES" y presenta múltiples factores de riesgo y hallazgos clínicos adversos, su estratificación de riesgo es la siguiente:<br /><li><strong>Riesgo de Diabetes Tipo 2:</strong> Confirmado (paciente ya diagnosticado).</li><li><strong>Riesgo Cardiovascular:</strong><strong>ALTO</strong>. El paciente presenta diabetes tipo 2, hipertensión arterial (140/90 mmHg), y obesidad (⚖️ IMC 30.50). Estos son los principales factores de riesgo para enfermedad cardiovascular aterosclerótica (ECVA), insuficiencia cardíaca y enfermedad renal crónica.</li><li><strong>Riesgo de Complicaciones Microvasculares:</strong><strong>ALTO</strong>. La hiperglucemia persistente (🍯 glucosa 210 mg/dL) aumenta el riesgo de retinopatía, nefropatía y neuropatía diabética.</li><li><strong>Riesgo de Progresión de la Enfermedad:</strong><strong>ALTO</strong>. La hiperinsulinemia severa (250 µU/mL) sugiere una resistencia a la 💉 insulina significativa y/o una gran sobrecarga para las células beta pancreáticas, lo que podría llevar a su agotamiento con el tiempo.</li><li><strong>Riesgo Agudo:</strong> La hemorragia nasal requiere una evaluación urgente, especialmente en el contexto de hipertensión, para descartar causas graves y 🎛️ controlarla.</li><strong>Interpretación de Resultados:</strong><br>
1.  <strong>Diagnóstico Principal:</strong> Diabetes Mellitus Tipo 2 (establecido según el documento adjunto).<br />
2.  <strong>Comorbilidades:</strong><br>
    *   Hipertensión Arterial Estadio 1.<br />
    *   Obesidad Clase I.<br />
    *   Hiperinsulinemia significativa (indicativa de resistencia a la 💉 insulina severa).<br />
3.  <strong>Estado Actual:</strong> El paciente presenta hiperglucemia activa y, por ende, un 🎛️ control glucémico deficiente. La hipertensión está des🎛️ controlada.<br />
4.  <strong>Relevancia de la 💉 Insulina alta (250 µU/mL):</strong> Este nivel, junto con una 🍯 glucosa de 210 mg/dL, es un hallazgo clave. Indica que el páncreas está trabajando extremadamente duro para intentar reducir la 🍯 glucosa, pero no lo está logrando debido a una resistencia a la 💉 insulina muy marcada. Alternativamente, si el paciente ya estuviera en tratamiento con 💉 insulina exógena, podría ser un valor post-prandial o post-inyección, pero incluso así, 250 µU/mL es muy alto y sugiere ineficacia del régimen actual o necesidad de ajuste. Se necesitarían más detalles (como niveles de C-péptido, 🍯 glucosa en ayunas, hora de la muestra de 💉 insulina) para una interpretación más precisa.<br />
5.  <strong>Hemorragia Nasal:</strong> Es un síntoma que debe ser investigado de inmediato. Podría ser un signo de la hipertensión des🎛️ controlada o de otras condiciones subyacentes.<br /><strong>Recomendaciones:</strong><br>
1.  <strong>Atención Inmediata:</strong><br>
    *   <strong>Manejo de la Hemorragia Nasal:</strong> El paciente debe ser evaluado por un médico de inmediato para identificar la causa y detener la hemorragia. Es fundamental 🎛️ controlar la ❤️ presión arterial.<br />
    *   <strong>🎛️ Control de Presión Arterial:</strong> Dada la lectura de 140/90 mmHg, se debe iniciar o ajustar el tratamiento antihipertensivo.<br />
2.  <strong>Confirmación y Evaluación Exhaustiva de la Diabetes:</strong><br>
    *   <strong>Hemoglobina Glicosilada (🩸 HbA1c):</strong> Para determinar el 🎛️ control glucémico promedio de los últimos 2-3 meses. Un objetivo común es <7%.<br />
    *   <strong>Panel Metabólico Completo:</strong> Incluyendo perfil lipídico completo (colesterol total, LDL, HDL, triglicéridos), función renal (creatinina, TFG estimada, relación albúmina/creatinina en orina), electrolitos.<br />
    *   <strong>Evaluación de C-péptido:</strong> Si la hiperinsulinemia es endógena, el C-péptido puede ayudar a evaluar la función residual de las células beta y diferenciar entre resistencia severa y posible dosificación incorrecta de 💉 insulina exógena (si fuera el caso).<br />
3.  <strong>Modificaciones del Estilo de Vida:</strong><br>
    *   <strong>Plan de Alimentación:</strong> Referencia a un nutricionista para un plan de comidas personalizado, enfocado en alimentos integrales, reducción de carbohidratos refinados y azúcares añadidos, 🎛️ control de porciones y aumento de fibra. Enfatizar la pérdida de ⚖️ peso.<br />
    *   <strong>Actividad Física:</strong> Programa de 🏃‍♂️ ejercicio regular (al menos 150 minutos a la semana de actividad aeróbica de intensidad moderada, distribuidos en 3-5 días, y 2-3 sesiones de entrenamiento de fuerza por semana), adaptado a su condición física.<br />
    *   <strong>Pérdida de ⚖️ Peso:</strong> Establecer un objetivo de pérdida de ⚖️ peso del 5-10% del ⚖️ peso corporal inicial, lo cual ha demostrado mejorar el 🎛️ control glucémico, la ❤️ presión arterial y la sensibilidad a la 💉 insulina.<br />
4.  <strong>Monitoreo y 📋 Seguimiento:</strong><br>
    *   <strong>Automanejo de 🍯 Glucosa (AMG):</strong> Instruir al paciente sobre cómo y cuándo monitorear sus niveles de 🍯 glucosa en sangre en casa.<br />
    *   <strong>Monitoreo de la Presión Arterial en Casa:</strong> Instruirlo sobre cómo y cuándo medir su ❤️ presión arterial en casa.<br />
    *   <strong>Revisiones Médicas Regulares:</strong> 📋 Seguimiento estrecho con el equipo médico para ajustar tratamientos y monitorizar el progreso.<br />
5.  <strong>Educación del Paciente:</strong><br>
    *   Educar sobre la diabetes tipo 2, sus complicaciones, la importancia del apego al tratamiento y el estilo de vida.<br /><strong>Plan Terapéutico (Propuesta Inicial):</strong><br><strong>Diagnóstico Confirmado:</strong><br><li>  Diabetes Mellitus Tipo 2</li><li>  Hipertensión Arterial Estadio 1</li><li>  Obesidad Clase I</li><li>  Hiperinsulinemia significativa (resistencia a la 💉 insulina)</li><li>  Hemorragia nasal (requiere investigación)</li><strong>Objetivos Terapéuticos:</strong><br><li><strong>Glucemia:</strong> 🩸 HbA1c < 7% (individualizar objetivo), glucemia en ayunas 80-130 mg/dL, glucemia postprandial <180 mg/dL.</li><li><strong>Presión Arterial:</strong><130/80 mmHg.</li><li><strong>⚖️ Peso:</strong> Reducción del 5-10% del ⚖️ peso corporal.</li><li><strong>Prevención de Complicaciones:</strong> Reducir riesgo cardiovascular y microvascular.</li><li><strong>🎛️ Control de la hemorragia nasal.</strong></li><strong>Intervenciones No Farmacológicas:</strong><br>
1.  <strong>Nutrición:</strong> Consulta con nutricionista para plan de alimentación mediterráneo o bajo en carbohidratos, restricción de azúcares simples y grasas saturadas. Énfasis en fibra y 🎛️ control de porciones.<br />
2.  <strong>Actividad Física:</strong> Programa supervisado de 150 minutos/semana de 🏃‍♂️ ejercicio aeróbico moderado y 2-3 sesiones de fuerza.<br />
3.  <strong>Pérdida de ⚖️ Peso:</strong> Establecer metas realistas de pérdida de ⚖️ peso a través de 🥗 dieta y 🏃‍♂️ ejercicio.<br /><strong>Intervenciones Farmacológicas:</strong><br>
1.  <strong>Para Diabetes Tipo 2:</strong><br>
    *   <strong>Metformina:</strong> Iniciar y titular gradualmente (ej. 500 mg una vez al día con la cena, aumentar a 500 mg dos veces al día, luego 1000 mg dos veces al día si es tolerado y función renal lo permite). La metformina es el tratamiento de primera línea que mejora la sensibilidad a la 💉 insulina y reduce la producción hepática de 🍯 glucosa.<br />
    *   <strong>Considerar Co-terapia Temprana:</strong> Dada la 🍯 glucosa alta (210 mg/dL), la hipertensión, la obesidad y la hiperinsulinemia, se debería considerar desde el inicio o muy tempranamente la adición de un fármaco con beneficios cardiovasculares y renales probados:<br />
        *   <strong>Agonista del Receptor de GLP-1 (AR-GLP1):</strong> Ejemplos: Liraglutida, Semaglutida (inyectables o oral). Favorecen la pérdida de ⚖️ peso, reducen la 🍯 glucosa y tienen beneficios cardiovasculares. Podrían ayudar a reducir la hiperinsulinemia.<br />
        *   <strong>Inhibidor de SGLT2 (iSGLT2):</strong> Ejemplos: Empagliflozina, Dapagliflozina, Canagliflozina. Favorecen la pérdida de ⚖️ peso, reducen la ❤️ presión arterial y tienen beneficios cardiovasculares y renales significativos.<br />
2.  <strong>Para Hipertensión Arterial:</strong><br>
    *   <strong>Inhibidor de la Enzima Convertidora de Angiotensina (IECA) o Bloqueador del Receptor de Angiotensina II (ARA-II):</strong> Ejemplos: Enalapril, Losartán. Son las primeras opciones en pacientes con diabetes por sus beneficios renoprotectores. Titular dosis para alcanzar el objetivo de <130/80 mmHg.<br />
3.  <strong>Para Dislipidemia (si aplica):</strong><br>
    *   Evaluar perfil lipídico. Si los lípidos están elevados, iniciar una estatina de intensidad moderada a alta (ej. Atorvastatina, Rosuvastatina) para reducir el riesgo cardiovascular.<br />
4.  <strong>Manejo de Hemorragia Nasal:</strong><br>
    *   Referencia urgente a Otorrinolaringología (ORL) para evaluación y tratamiento. Mantener la ❤️ presión arterial 🎛️ controlada es fundamental en el manejo agudo.<br /><strong>📋 Seguimiento:</strong><br><li><strong>Consultas:</strong> 📋 Seguimiento médico cada 1-3 meses inicialmente, con ajustes de medicación según respuesta.</li><li><strong>🩸 HbA1c:</strong> Cada 3 meses hasta estabilización, luego cada 6 meses.</li><li><strong>Función Renal:</strong> Evaluar eGFR y albuminuria anualmente.</li><li><strong>Lípidos:</strong> Perfil lipídico anual.</li><li><strong>Exámenes de Detección de Complicaciones:</strong></li>    *   Examen oftalmológico dilatado anual para retinopatía.<br />
    *   Examen de pies anual para neuropatía y enfermedad arterial periférica.<br /><li><strong>Educación Continua:</strong> Reforzar la educación sobre diabetes, medicación, y monitoreo de síntomas.</li>Este plan busca no solo 🎛️ controlar la glucemia y la ❤️ presión arterial, sino también abordar la obesidad, la resistencia a la 💉 insulina y mitigar el riesgo de complicaciones cardiovasculares y microvasculares, que son muy altas en este paciente. La pronta atención a la hemorragia nasal y el 🎛️ control de la hipertensión son prioritarios.</ul>', '["attachments\/1766157352_69456c28a7f83_DATOS CL\u00cdNICOS DEL PACIENTE.docx"]', '["DATOS CL\u00cdNICOS DEL PACIENTE.docx"]', NULL);

SELECT setval(pg_get_serial_sequence('"prediccion"', 'idprediccion'), 89);

