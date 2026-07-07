-- Tabel User (Admin & Mahasiswa)
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'mahasiswa') NOT NULL
);

-- Tabel Laporan Pengaduan
CREATE TABLE IF NOT EXISTS laporan (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    judul VARCHAR(100) NOT NULL,
    deskripsi TEXT NOT NULL,
    foto VARCHAR(255) NOT NULL,
    status ENUM('Pending', 'Diterima', 'Ditolak', 'Selesai') DEFAULT 'Pending',
    tanggal TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Masukkan Akun Demo (Password: admin123 & mhs123)
INSERT INTO users (username, password, role) VALUES 
('admin_kampus', '$2y$10$v7mZ0W0Wj8vC6gNlFEexO.Y.e5O4yvD7tT2pUf1u3fR7lH6YvO1uq', 'admin'),
('mahasiswa1', '$2y$10$V08K6fL.N3X89WvW/4G8EuWk1rO3bZ7dE6Qz9H1V1vX8Z7lH6YvO1uq', 'mahasiswa')
ON DUPLICATE KEY UPDATE username=username;