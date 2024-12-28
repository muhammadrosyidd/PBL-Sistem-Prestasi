-- Menghapus database 'PRESTASI'
IF EXISTS (SELECT 1 FROM sys.databases WHERE name = 'PRESTASI')
BEGIN
    -- Hapus database jika ada
    DROP DATABASE PRESTASI;
    PRINT 'Database PRESTASI telah dihapus.';
END
ELSE
BEGIN
    PRINT 'Database PRESTASI tidak ditemukan.';
END
GO

-- Membuat database 'PRESTASI'
CREATE DATABASE PRESTASI;
GO
-- Menggunakan database 'PRESTASI'
USE PRESTASI;
GO

--TABEL USER--
CREATE TABLE [user] ( 
    username VARCHAR(20) PRIMARY KEY, 
    password VARBINARY(20) NOT NULL, 
    role INT NOT NULL 
);

--TABEL SUPERADMIN--
CREATE TABLE [superadmin] (
    super_admin_id INT PRIMARY KEY IDENTITY(1,1), 
    username VARCHAR(20) NOT NULL, 
    nama VARCHAR(150) NOT NULL, 
    jeniskelamin CHAR(1) NOT NULL,
    telepon VARCHAR(15) NOT NULL,
    alamat VARCHAR(100) NOT NULL,
    jabatan VARCHAR(100) NOT NULL, 
    FOREIGN KEY (username) REFERENCES [user](username)
);

--table admin--
CREATE TABLE [admin] (
    admin_id INT PRIMARY KEY IDENTITY(1,1), 
    username VARCHAR(20) NOT NULL, 
    nama VARCHAR(150) NOT NULL, 
    jeniskelamin CHAR(1) NOT NULL,
    telepon VARCHAR(15) NOT NULL,
    alamat VARCHAR(100) NOT NULL,
    jabatan VARCHAR(100) NOT NULL, 
    FOREIGN KEY (username) REFERENCES [user](username)
);

--table prodi--
CREATE TABLE [prodi] (
    prodi_id INT PRIMARY KEY IDENTITY(1,1),
    nama_prodi VARCHAR(50) NOT NULL
);

--table dosen--
CREATE TABLE [dosen] (
    dosen_id INT PRIMARY KEY IDENTITY(1,1), 
    nidn VARCHAR(20) NOT NULL, 
    nama VARCHAR(150) NOT NULL, 
    telepon VARCHAR(15) 
);

--table mahasiswa
CREATE TABLE [mahasiswa] (
    nim VARCHAR(20) PRIMARY KEY,   
    username VARCHAR(20) NOT NULL, 
    nama_depan VARCHAR(75) NOT NULL,
    nama_belakang VARCHAR(75) NOT NULL,
	prodi_id INT NOT NULL,
    jeniskelamin CHAR(1) NOT NULL,
    telepon VARCHAR(15) NOT NULL,
    alamat VARCHAR(100) NOT NULL,
    FOREIGN KEY (username) REFERENCES [user](username),
    FOREIGN KEY (prodi_id) REFERENCES [prodi](prodi_id)
);

--table kategori--
CREATE TABLE [kategori] (
    kategori_id INT PRIMARY KEY IDENTITY(1,1), 
    nama_kategori VARCHAR(50) NOT NULL
);

--table tingkat lomba--
CREATE TABLE [tingkatLomba] (
    tingkat_lomba_id INT PRIMARY KEY IDENTITY(1,1), 
    nama_tingkat VARCHAR(20) NOT NULL,
	poin_tingkat INT NOT NULL
);

--table peringkat--
CREATE TABLE [peringkat] (
    peringkat_id INT PRIMARY KEY IDENTITY(1,1), 
    nama_peringkat VARCHAR(50) NOT NULL, 
    poin_peringkat INT NOT NULL
);

--table dokumen--
CREATE TABLE [dokumen] (
    dokumen_id INT PRIMARY KEY IDENTITY(1,1), 
    flyer VARCHAR(MAX) NOT NULL, 
    sertifikat VARCHAR(MAX) NOT NULL, 
    foto_kegiatan VARCHAR(MAX) NOT NULL,
    surat_tugas VARCHAR(MAX) NOT NULL, 
    nomor_surat_tugas VARCHAR(50) NULL,
    proposal VARCHAR(MAX) NULL, 
    komentar VARCHAR(MAX) NULL,
    tanggal_surat_tugas DATE NOT NULL
);


-- Membuat tabel peran mahasiswa
CREATE TABLE [peran_mahasiswa] (
    peran_mahasiswa_id INT PRIMARY KEY IDENTITY(1,1),
    nama_peran VARCHAR(50) NOT NULL
);

-- Membuat tabel peran dosen
CREATE TABLE [peran_dosen] (
    peran_dosen_id INT PRIMARY KEY IDENTITY(1,1),
    nama_peran VARCHAR(MAX) NOT NULL
);

--table prestasi--
CREATE TABLE [prestasi] (
    prestasi_id INT PRIMARY KEY IDENTITY(1,1), 
    judul VARCHAR(255) NOT NULL,  
	tempat VARCHAR (255) NOT NULL,
	link_kompetisi VARCHAR (255) NOT NULL,
    tanggal_mulai DATE NOT NULL, 
	tanggal_akhir DATE NOT NULL,
	jumlah_peserta VARCHAR(50),
    kategori_id INT NOT NULL, 
    tingkat_lomba_id INT NOT NULL, 
    peringkat_id INT NOT NULL, 
    dokumen_id INT NULL, 
	verifikasi_status VARCHAR(20) DEFAULT 'Belum Terverifikasi',
	tanggal_input DATETIME DEFAULT GETDATE()
    FOREIGN KEY (kategori_id) REFERENCES [kategori](kategori_id), 
    FOREIGN KEY (tingkat_lomba_id) REFERENCES [tingkatLomba](tingkat_lomba_id), 
    FOREIGN KEY (peringkat_id) REFERENCES [peringkat](peringkat_id), 
    FOREIGN KEY (dokumen_id) REFERENCES [dokumen](dokumen_id)
);

CREATE TABLE infolomba (
    id_infoLomba INT PRIMARY KEY IDENTITY(1,1),
    gambar_poster VARCHAR(MAX) NULL,
    jenis_lomba VARCHAR(100) NOT NULL,
    tingkat_lomba_id INT NOT NULL, 
    tanggal_pelaksanaan DATE NOT NULL,
    link_pendaftaran VARCHAR(255) NOT NULL,
    penyelenggara VARCHAR(100) NOT NULL,
    CONSTRAINT FK_tingkatLomba FOREIGN KEY (tingkat_lomba_id) REFERENCES tingkatLomba(tingkat_lomba_id)
);

CREATE TABLE [presma] (
	presma_id INT PRIMARY KEY IDENTITY (1,1),
	nim VARCHAR (20) NOT NULL,
	prestasi_id INT NOT NULL,
	peran_mahasiswa_id INT NOT NULL,
	FOREIGN KEY (nim) REFERENCES[mahasiswa](nim),
	FOREIGN KEY (prestasi_id) REFERENCES[prestasi](prestasi_id),
	FOREIGN KEY (peran_mahasiswa_id) REFERENCES[peran_mahasiswa](peran_mahasiswa_id)
);

CREATE TABLE [dospem] (
	dospem_id INT PRIMARY KEY IDENTITY (1,1),
	dosen_id INT NOT NULL,
	prestasi_id INT NOT NULL,
	peran_dosen_id INT NOT NULL,
	FOREIGN KEY (dosen_id) REFERENCES[dosen](dosen_id),
	FOREIGN KEY (prestasi_id) REFERENCES[prestasi](prestasi_id),
	FOREIGN KEY (peran_dosen_id) REFERENCES[peran_dosen](peran_dosen_id)
);


GO

-- Mengisi data dummy ke tabel user
-- Mengisi data dummy ke tabel user
INSERT INTO [user] (username, password, role) VALUES
('superadmin', HASHBYTES('MD5', 'super123'), 1),
('admin', HASHBYTES('MD5', 'admin123'), 2),
('2341760028', HASHBYTES('MD5', 'farel123'), 3), -- Password untuk mahasiswa Farel
('2341760058', HASHBYTES('MD5', 'adinda123'), 3), -- Password untuk mahasiswa Adinda
('2341760088', HASHBYTES('MD5', 'ardi123'), 3), -- Password untuk mahasiswa Ardi
('2341760118', HASHBYTES('MD5', 'keysha123'), 3), -- Password untuk mahasiswa Keysha
('2341760148', HASHBYTES('MD5', 'dimas123'), 3); -- Password untuk mahasiswa Dimas


-- Mengisi data dummy ke tabel superadmin
INSERT INTO [superadmin] (username, nama, jeniskelamin, telepon, alamat, jabatan) VALUES
('superadmin', 'Dr. Budi Hartono', 'L', '081123456789', 'Jl. Merdeka No. 1', 'kepala akademik');

-- Mengisi data dummy ke tabel admin
INSERT INTO [admin] (username, nama, jeniskelamin, telepon, alamat, jabatan) VALUES
('admin', 'Siti Aminah', 'P', '081987654321', 'Jl. Pancasila No. 3', 'admin prodi teknik informatika');

INSERT INTO [prodi] (nama_prodi) VALUES
('D4 Teknik Informatika'),
('D4 Sistem Informasi Bisnis'),
('D2 Pengembangan Piranti Lunak Situs');


-- Mengisi data dummy ke tabel mahasiswa
INSERT INTO [mahasiswa] (nim, username, nama_depan, nama_belakang, jeniskelamin, telepon, alamat, prodi_id) VALUES
('2341760028', '2341760028', 'Farel', 'Maryam', 'P', '081223344556', 'Jl. Kebangsaan No. 12', 2),
('2341760058', '2341760058', 'Adinda', 'Ivanka', 'P', '081334455667', 'Jl. Indonesia Raya No. 8', 2),
('2341760088', '2341760088', 'Ardi', 'Saputra', 'L', '081445566778', 'Jl. Merdeka No. 1', 1),
('2341760118', '2341760118', 'Keysha', 'Arindra Fabian', 'P', '081556677889', 'Jl. Sudirman No. 5', 3),
('2341760148', '2341760148', 'Dimas', 'Prasetyo', 'L', '081667788990', 'Jl. Gatot Subroto No. 3', 1);

--tambah tabel dosen--
INSERT INTO [dosen] (nidn, nama, telepon) VALUES 
('1234567890', 'Dr. Ahmad Fauzi', '081987654321'),
('0987654321', 'Dr. Rina Sari', '082123456789'),
('9876543210', 'Prof. Budiman', '081112223333'),
('8765432109', 'Dr. Ayu Lestari', '082223334444');

-- Mengisi data dummy ke tabel kategori
INSERT INTO [kategori] (nama_kategori) VALUES
('UI/UX'),
('Web Development'),
('Software Development'),
('Mobile Application'),
('Game Development'),
('Data Science'),
('Programming'),
('Cyber Security'),
('Internet of Things (IoT)'),
('Hackathon'),
('Esai'), 
('Karya Tulis Ilmiah (KTI)'),
('Business Plan'),
('Video Competition'),
('Poster Competition')
;

-- Mengisi data dummy ke tabel tingkatLomba
INSERT INTO [tingkatLomba] (nama_tingkat, poin_tingkat)
VALUES 
    ('Regional', 5),      
    ('Nasional', 7),      
    ('Internasional', 10);

-- Mengisi data dummy ke tabel peringkat
INSERT INTO [peringkat] (nama_peringkat, poin_peringkat) 
VALUES
('Juara 1', 10), 
('Juara 2', 8), 
('Juara 3', 6),
('Harapan 1', 4), 
('Harapan 2', 3), 
('Harapan 3', 2),
('Best', 1);

-- Mengisi data dummy ke tabel dokumen
INSERT INTO [dokumen] 
    (flyer, sertifikat, foto_kegiatan, surat_tugas, nomor_surat_tugas, tanggal_surat_tugas, proposal, komentar) 
VALUES
    ('ImageFlyer', 'Sertifikat1', 'FotoKegiatan1', 'SuratTugas1', 'ST123/2024','2024-09-09', NULL, 'Dokumen lengkap untuk prestasi 1'),
    ('ImageFlyer2', 'Sertifikat2', 'FotoKegiatan2', 'SuratTugas2', 'ST124/2024','2024-09-09', NULL, 'Dokumen lengkap untuk prestasi 2'),
    ('ImageFlyer3', 'Sertifikat3', 'FotoKegiatan3', 'SuratTugas3', 'ST125/2024','2024-09-09', NULL, 'Dokumen lengkap untuk prestasi 3'),
    ('ImageFlyer4', 'Sertifikat4', 'FotoKegiatan4', 'SuratTugas4', 'ST126/2024','2024-09-09', 'Proposal1', 'Dokumen lengkap untuk prestasi 4'),
    ('ImageFlyer5', 'Sertifikat5', 'FotoKegiatan5', 'SuratTugas5', 'ST127/2024','2024-09-09', NULL, 'Dokumen lengkap untuk prestasi 5');

-- Mengisi data dummy ke tabel peran_mahasiswa
INSERT INTO [peran_mahasiswa] (nama_peran) VALUES
('Ketua'),
('Anggota'),
('Personal');

-- Mengisi data dummy ke tabel peran_dosen
INSERT INTO [peran_dosen] (nama_peran) VALUES
('Melakukan pembinaan kegiatan mahasiswa di bidang akademik (PA) dan kemahasiswaan (BEM, MAPERWA, dan lain-lain)'),
('Membimbing mahasiswa menghasilkan produk saintifik bereputasi dan mendapat pengakuan tingkat Internasional'),
('Membimbing mahasiswa menghasilkan produk saintifik bereputasi dan mendapat pengakuan tingkat Nasional'),
('Membimbing mahasiswa mengikuti kompetisi di bidang akademik dan kemahasiswaan bereputasi dan mencapai juara tingkat Internasional'),
('Membimbing mahasiswa mengikuti kompetisi di bidang akademik dan kemahasiswaan bereputasi dan mencapai juara tingkat Nasional');


-- Mengisi data dummy ke tabel prestasi
INSERT INTO [prestasi] 
(judul, tempat, link_kompetisi, tanggal_mulai, tanggal_akhir, jumlah_peserta, kategori_id, tingkat_lomba_id, peringkat_id, dokumen_id, verifikasi_status, tanggal_input) 
VALUES
('Mobile UI/UX Competition', 'Jakarta', 'http://linkkompetisi1.com', '2024-01-01', '2024-01-05', '5', 1, 2, 1, 1, 'Belum Terverifikasi', '2024-12-15 10:00:00'),
('Karya Tulis Ilmiah', 'Bandung', 'http://linkkompetisi2.com', '2024-02-01', '2024-02-03', '3', 12, 1, 2, 2, 'Belum Terverifikasi', '2024-12-10 14:30:00'),
('Web Development Competition', 'Surabaya', 'http://linkkompetisi3.com', '2024-03-15', '2024-03-17', '10', 2, 2, 3, 3, 'Terverifikasi', '2024-11-20 16:00:00'),
('Hackathon', 'Yogyakarta', 'http://linkkompetisi4.com', '2024-04-01', '2024-04-03', '20', 10, 3, 1, 4, 'Belum Terverifikasi', '2024-11-16 09:45:00'),
('Mobile App Development', 'Denpasar', 'http://linkkompetisi5.com', '2024-05-10', '2024-05-12', '15', 4, 3, 2, 5, 'Terverifikasi', '2024-10-23 12:15:00');

-- Data dummy for presma table
INSERT INTO [presma] (nim, prestasi_id, peran_mahasiswa_id)
VALUES
    ('2341760028', 1, 1),
    ('2341760058', 2, 2),
    ('2341760028', 3, 1),
    ('2341760118', 4, 2),
    ('2341760148', 5, 1);

-- Data dummy for dospem table
INSERT INTO [dospem] (dosen_id, prestasi_id, peran_dosen_id)
VALUES
    (1, 1, 1),
    (2, 2, 2),
    (3, 3, 3),
    (4, 4, 4),
    (1, 5, 5);
