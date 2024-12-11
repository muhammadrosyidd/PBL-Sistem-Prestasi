
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
    role INT NOT NULL, 
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
    nama_tingkat VARCHAR(20) NOT NULL
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
    flyer VARBINARY(MAX) NOT NULL, 
    sertifikat VARBINARY(MAX) NOT NULL, 
	foto_kegiatan VARBINARY(MAX) NOT NULL,
    surat_tugas VARBINARY(MAX) NOT NULL, 
	nomor_surat_tugas VARCHAR(50) NULL,
    proposal VARBINARY(MAX) NULL, 
    komentar VARCHAR(MAX) NULL
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
    nim VARCHAR(20) NOT NULL, 
	dosen_id INT NULL,
    judul VARCHAR(255) NOT NULL,  
    tanggal_mulai DATE NOT NULL, 
	tanggal_akhir DATE NOT NULL,
	jumlah_peserta VARCHAR(50),
    kategori_id INT NOT NULL, 
    tingkat_lomba_id INT NOT NULL, 
    peringkat_id INT NOT NULL, 
    dokumen_id INT NULL, 
	peran_mahasiswa_id INT NOT NULL,
	peran_dosen_id INT NOT NULL,
	verifikasi_status VARCHAR(20) DEFAULT 'Belum Terverifikasi',
    FOREIGN KEY (nim) REFERENCES [mahasiswa](nim), 
	FOREIGN KEY (dosen_id) REFERENCES [dosen](dosen_id),
    FOREIGN KEY (kategori_id) REFERENCES [kategori](kategori_id), 
    FOREIGN KEY (tingkat_lomba_id) REFERENCES [tingkatLomba](tingkat_lomba_id), 
    FOREIGN KEY (peringkat_id) REFERENCES [peringkat](peringkat_id), 
    FOREIGN KEY (dokumen_id) REFERENCES [dokumen](dokumen_id),
	FOREIGN KEY (peran_mahasiswa_id) REFERENCES [peran_mahasiswa](peran_mahasiswa_id),
	FOREIGN KEY (peran_dosen_id) REFERENCES [peran_dosen](peran_dosen_id)
);

CREATE TABLE infolomba (
    id_infoLomba INT PRIMARY KEY IDENTITY(1,1),
    gambar_poster VARBINARY(MAX) NULL,
    jenis_lomba VARCHAR(100) NOT NULL,
    tingkat_lomba_id INT NOT NULL, 
    tanggal_pelaksanaan DATE NOT NULL,
    link_pendaftaran VARCHAR(255) NOT NULL,
    penyelenggara VARCHAR(100) NOT NULL,
    CONSTRAINT FK_tingkatLomba FOREIGN KEY (tingkat_lomba_id) REFERENCES tingkatLomba(tingkat_lomba_id)
);

GO

-- Mengisi data dummy ke tabel user
INSERT INTO [user] (username, password, role) VALUES
('superadmin', HASHBYTES('MD5', 'super123'), 1),
('admin', HASHBYTES('MD5', 'admin123'), 2),
('2341760028', HASHBYTES('MD5', '2341760028'), 3),
('2341760058', HASHBYTES('MD5', '2341760058'), 3);


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
('2341760058', '2341760058', 'Adinda', 'Ivanka', 'P', '081334455667', 'Jl. Indonesia Raya No. 8', 2);

--tambah tabel dosen--
INSERT INTO [dosen] (nidn, nama, telepon) VALUES 
('1234567890', 'Dr. Ahmad Fauzi', '081987654321'),
('0987654321', 'Dr. Rina Sari', '082123456789');

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
INSERT INTO [dokumen] (flyer, sertifikat, foto_kegiatan, surat_tugas, nomor_surat_tugas, proposal, komentar) VALUES
(0x, 0x, 0x, 0x, 'ST123/2024', NULL, 'Dokumen lengkap untuk prestasi 1'),
(0x, 0x, 0x, 0x, 'ST124/2024', NULL, 'Dokumen lengkap untuk prestasi 2');

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
INSERT INTO [prestasi] (nim, dosen_id, judul, tanggal_mulai, tanggal_akhir, jumlah_peserta, kategori_id, tingkat_lomba_id, peringkat_id, dokumen_id, peran_mahasiswa_id, peran_dosen_id) VALUES
('2341760028', 1, 'Mobile UI/UX Competition', '2024-01-01', '2024-01-05', '5', 1, 2, 1, 1, 1, 1),
('2341760058', 2, 'Karya Tulis Ilmiah', '2024-02-01', '2024-02-03', '3', 12, 1, 2, 2, 2, 2);





