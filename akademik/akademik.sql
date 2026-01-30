-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--


SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

-- Struktur dari tabel `fakultas`
--

CREATE TABLE `fakultas` (
  `id` int(11) NOT NULL,
  `nama` varchar(40) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `fakultas`
--

INSERT INTO `fakultas` (`id`, `nama`) VALUES
(1, 'Fakultas Sains dan Teknologi'),
(6, 'Fakultas Psikologi');

-- --------------------------------------------------------

--
-- Struktur dari tabel `jurusan`
--

CREATE TABLE `jurusan` (
  `id` int(11) NOT NULL,
  `fakultas_id` int(11) NOT NULL,
  `nama` varchar(40) NOT NULL,
  `tanggal_berdiri` date NOT NULL,
  `keterangan` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `jurusan`
--

INSERT INTO `jurusan` (`id`, `fakultas_id`, `nama`, `tanggal_berdiri`, `keterangan`) VALUES
(1, 1, 'Teknik Informatika', '0000-00-00', ''),
(2, 1, 'Teknik Industri', '0000-00-00', ''),
(3, 1, 'Sistem Informasi', '0000-00-00', ''),
(4, 1, 'Matematika Terapan', '0000-00-00', ''),
(5, 1, 'Teknik Elektro', '0000-00-00', '');

-- --------------------------------------------------------

--
-- Struktur dari tabel `konten`
--

CREATE TABLE `konten` (
  `id` int(11) NOT NULL,
  `judul` varchar(80) NOT NULL,
  `kategori` varchar(100) NOT NULL,
  `tanggal` date NOT NULL,
  `isi` text NOT NULL,
  `foto` varchar(100) DEFAULT NULL,
  `publikasi` tinyint(1) NOT NULL DEFAULT 0,
  `url` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `konten`
--

INSERT INTO `konten` (`id`, `judul`, `kategori`, `tanggal`, `isi`, `foto`, `publikasi`, `url`) VALUES
(1, 'Komisi X DPR Minta Sekolah Swasta 3T Jadi Prioritas Pendidikan Gratis', 'Informasi\r\n', '2025-06-02', '<p>JAKARTA, KOMPAS.com - Anggota Komisi X DPR RI Illiza Sa\'aduddin Djamal meminta pemerintah memprioritaskan sekolah swasta di daerah 3T (terdepan, terluar, tertinggal) dalam program pendidikan gratis.</p><p>Hal ini disampaikan Illiza menanggapi rencana pemerintah yang akan menggratiskan biaya pendidikan dari jenjang PAUD hingga SMA.</p><p>\"Saya minta pemerintah memprioritaskan sekolah swasta di daerah 3T untuk program pendidikan gratis,\" kata Illiza dalam keterangan tertulis, Minggu (1/6/2025).</p><p>Illiza mengatakan, sekolah swasta di daerah 3T umumnya memiliki keterbatasan sumber daya dan fasilitas. Dengan adanya program pendidikan gratis, diharapkan dapat meringankan beban sekolah swasta tersebut.</p><p>\"Sekolah swasta di daerah 3T ini sangat membutuhkan bantuan pemerintah. Mereka berperan penting dalam memberikan akses pendidikan di daerah yang sulit dijangkau,\" ujarnya.</p><p>Selain itu, Illiza juga meminta pemerintah memastikan program pendidikan gratis ini tidak hanya sebatas menggratiskan biaya sekolah, tetapi juga meningkatkan kualitas pendidikan.</p><p>\"Pendidikan gratis harus diimbangi dengan peningkatan kualitas. Jangan sampai gratis tapi kualitasnya rendah,\" tegasnya.</p>', 'berita1.jpg', 1, 'https://www.kompas.com/edu/read/2025/06/02/185951471/komisi-x-dpr-minta-sekolah-swasta-3t-jadi-prioritas-pendidikan-gratis'),
(2, 'Menteri HAM Dukung Program Siswa Nakal Masuk Barak Militer Diterapkan', 'Informasi', '2025-06-02', '<p>JAKARTA, KOMPAS.com - Menteri Hukum dan HAM (Menkumham) Supratman Andi Agtas mendukung program memasukkan siswa nakal ke barak militer untuk pembinaan karakter.</p><p>Menurutnya, program tersebut dapat menjadi solusi alternatif untuk menangani kenakalan remaja yang semakin meresahkan.</p><p>\"Saya mendukung program ini sebagai upaya pembinaan karakter bagi siswa yang bermasalah,\" kata Supratman dalam keterangan pers di Jakarta, Senin (2/6/2025).</p><p>Supratman menjelaskan, program ini bukan bertujuan untuk menghukum, melainkan untuk membina kedisiplinan dan karakter siswa.</p><p>\"Ini bukan hukuman, tapi pembinaan. Dengan masuk ke barak militer, siswa akan belajar disiplin, tanggung jawab, dan kerja sama tim,\" ujarnya.</p><p>Meski demikian, Supratman menekankan bahwa program ini harus dilaksanakan dengan memperhatikan hak-hak anak dan prinsip kepentingan terbaik bagi anak.</p><p>\"Pelaksanaannya harus tetap menghormati hak-hak anak dan tidak boleh ada unsur kekerasan. Ini murni untuk pembinaan,\" tegasnya.</p><p>Program ini rencananya akan diujicobakan di beberapa sekolah yang memiliki tingkat kenakalan remaja tinggi.</p>', 'berita2.jpg', 1, 'https://www.kompas.com/edu/read/2025/06/02/154441271/menteri-ham-dukung-program-siswa-nakal-masuk-barak-militer-diterapkan'),
(3, 'Biaya Sekolah Rakyat Rp 48,2 Juta Tiap Siswa per Tahun, untuk Apa Saja?', 'Informasi', '2025-06-01', '<p>JAKARTA, KOMPAS.com - Sekolah Rakyat (SR) yang digagas oleh pemerintah membutuhkan biaya operasional sebesar Rp 48,2 juta per siswa per tahun. Angka ini jauh lebih tinggi dibandingkan biaya operasional sekolah negeri pada umumnya.</p><p>Direktur Jenderal Pendidikan Dasar dan Menengah Kementerian Pendidikan, Kebudayaan, Riset, dan Teknologi (Kemendikbudristek) Iwan Syahril menjelaskan rincian penggunaan dana tersebut.</p><p>\"Dari total Rp 48,2 juta, sekitar 60 persen atau Rp 28,9 juta digunakan untuk gaji guru dan tenaga kependidikan,\" kata Iwan dalam konferensi pers di Jakarta, Sabtu (31/5/2025).</p><p>Sementara itu, 25 persen atau sekitar Rp 12 juta digunakan untuk biaya operasional sekolah, termasuk listrik, air, internet, dan pemeliharaan fasilitas.</p><p>\"Sisanya, 15 persen atau sekitar Rp 7,3 juta, digunakan untuk program pengembangan siswa, seperti kegiatan ekstrakurikuler, kunjungan edukatif, dan program pembinaan karakter,\" tambahnya.</p><p>Iwan menegaskan, meski biayanya tinggi, Sekolah Rakyat menawarkan kualitas pendidikan yang setara dengan sekolah internasional.</p><p>\"Sekolah Rakyat menerapkan kurikulum nasional yang diperkaya dengan pendekatan pembelajaran internasional. Rasio guru dan siswa juga lebih kecil, 1:15, sehingga pembelajaran lebih efektif,\" jelasnya.</p><p>Program Sekolah Rakyat akan dimulai tahun ajaran 2025/2026 di 10 kota besar di Indonesia.</p>', 'berita3.jpeg', 1, 'https://www.kompas.com/edu/read/2025/06/01/155800471/biaya-sekolah-rakyat-rp-48-2-juta-tiap-siswa-per-tahun-untuk-apa-saja-'),
(4, 'Kebijakan Trump Picu Gangguan Mental dan Beri Tekanan bagi Mahasiswa Asing', 'Informasi', '2025-06-01', '<p>KOMPAS.com - Kebijakan imigrasi yang dikeluarkan oleh Presiden Amerika Serikat Donald Trump pada masa jabatan keduanya telah memicu gangguan mental dan memberikan tekanan bagi mahasiswa asing yang belajar di negara tersebut.</p><p>Hal ini terungkap dalam studi terbaru yang dilakukan oleh peneliti dari University of California, Berkeley.</p><p>Studi yang melibatkan 1.500 mahasiswa asing dari 75 negara ini menemukan bahwa 68 persen responden mengalami gejala kecemasan dan 42 persen menunjukkan gejala depresi sejak kebijakan tersebut diberlakukan.</p><p>\"Kebijakan pembatasan visa dan pernyataan-pernyataan yang bernada xenofobia telah menciptakan lingkungan yang tidak bersahabat bagi mahasiswa internasional,\" kata Dr. Sarah Chen, peneliti utama dalam studi tersebut.</p><p>Salah satu kebijakan yang paling berdampak adalah pembatasan masa tinggal pasca-lulus (Optional Practical Training/OPT) dari 36 bulan menjadi hanya 12 bulan.</p><p>\"Ini membuat mahasiswa asing kesulitan mencari pekerjaan dan mendapatkan pengalaman kerja yang cukup di AS setelah lulus,\" tambah Dr. Chen.</p><p>Studi ini juga menemukan bahwa mahasiswa dari negara-negara mayoritas Muslim dan China mengalami tingkat stres yang lebih tinggi dibandingkan mahasiswa dari negara lain.</p><p>Asosiasi Pendidikan Internasional Amerika (NAFSA) telah menyerukan pemerintah AS untuk meninjau kembali kebijakan tersebut, mengingat kontribusi positif mahasiswa internasional bagi ekonomi dan inovasi AS.</p>', 'berita4.jpg', 1, 'https://www.kompas.com/edu/read/2025/06/01/114713771/kebijakan-trump-picu-gangguan-mental-dan-beri-tekanan-bagi-mahasiswa-asing'),
(5, 'Jumlah PHK Meningkat, Dosen UGM: Fleksibilitas Rekrutmen Karyawan Sangat Diperlu', 'Artikel', '2025-05-30', '<p>YOGYAKARTA, KOMPAS.com - Dosen Fakultas Ekonomika dan Bisnis Universitas Gadjah Mada (FEB UGM) Eko Suwardi menyatakan, fleksibilitas dalam rekrutmen karyawan sangat diperlukan di tengah meningkatnya jumlah pemutusan hubungan kerja (PHK) akibat transformasi digital.</p><p>\"Perusahaan perlu lebih fleksibel dalam merekrut karyawan, tidak hanya berdasarkan ijazah formal, tetapi juga keterampilan yang relevan dengan kebutuhan industri,\" kata Eko dalam seminar daring \"Transformasi Digital dan Dampaknya terhadap Ketenagakerjaan\" yang diselenggarakan FEB UGM, Kamis (29/5/2025).</p><p>Menurut Eko, transformasi digital telah mengubah lanskap ketenagakerjaan secara signifikan. Banyak pekerjaan konvensional tergantikan oleh otomatisasi dan kecerdasan buatan.</p><p>\"Data menunjukkan, sepanjang tahun 2024 hingga Mei 2025, terjadi PHK terhadap lebih dari 50.000 pekerja di Indonesia, terutama di sektor manufaktur dan ritel,\" ungkapnya.</p><p>Untuk mengatasi hal tersebut, Eko menyarankan agar perusahaan mengadopsi model rekrutmen berbasis keterampilan (skill-based hiring) dan memberikan pelatihan berkelanjutan bagi karyawan.</p><p>\"Perusahaan perlu berinvestasi pada program reskilling dan upskilling karyawan agar mereka dapat beradaptasi dengan tuntutan pekerjaan di era digital,\" tambahnya.</p><p>Sementara itu, dari sisi pencari kerja, Eko menekankan pentingnya mengembangkan keterampilan digital dan soft skills seperti kemampuan beradaptasi, berpikir kritis, dan kolaborasi.</p><p>\"Lulusan perguruan tinggi harus siap untuk terus belajar dan mengembangkan diri sepanjang karier mereka,\" pungkasnya.</p>', 'berita5.jpeg', 1, 'https://www.kompas.com/edu/read/2025/05/30/200522671/jumlah-phk-meningkat-dosen-ugm-fleksibilitas-rekrutmen-karyawan-sangat'),
(6, 'Pemerintah Blokir Sementara Archive.org, Komdigi Ungkap Alasannya', 'Informasi', '2025-05-30', '<p>JAKARTA, KOMPAS.com - Kementerian Komunikasi dan Informatika (Kominfo) memblokir sementara situs Internet Archive (archive.org) sejak Rabu (28/5/2025). Pemblokiran ini menuai kritik dari berbagai kalangan, terutama akademisi dan peneliti yang sering menggunakan layanan tersebut.</p><p>Menanggapi hal tersebut, Ketua Komisi Digital Indonesia (Komdigi) Donny Budi Utoyo mengungkapkan alasan di balik pemblokiran tersebut.</p><p>\"Pemblokiran dilakukan karena adanya konten yang melanggar hak cipta dan konten negatif yang tidak sesuai dengan regulasi di Indonesia,\" kata Donny dalam konferensi pers virtual, Jumat (30/5/2025).</p><p>Menurut Donny, pihaknya telah mengirimkan peringatan kepada pengelola archive.org untuk menghapus konten-konten bermasalah tersebut, namun tidak mendapat respons yang memadai.</p><p>\"Kami sudah mengirimkan tiga kali peringatan sejak Maret 2025, tetapi tidak ada tindak lanjut yang signifikan dari pihak Internet Archive,\" jelasnya.</p><p>Donny menegaskan bahwa pemblokiran ini bersifat sementara dan akan dicabut jika pihak Internet Archive bersedia berkoordinasi dengan pemerintah Indonesia untuk menyelesaikan masalah tersebut.</p><p>\"Kami menyadari pentingnya archive.org bagi dunia akademik dan penelitian. Karena itu, kami berharap masalah ini bisa segera diselesaikan,\" ujarnya.</p><p>Internet Archive sendiri merupakan perpustakaan digital non-profit yang menyimpan jutaan buku, film, perangkat lunak, musik, dan snapshot halaman web. Layanan ini sering digunakan oleh peneliti dan akademisi untuk mengakses informasi yang sudah tidak tersedia di internet.</p>', '1748937712_berita6.png', 1, 'https://tekno.kompas.com/read/2025/05/30/09462757/pemerintah-blokir-sementara-archiveorg-komdigi-ungkap-alasannya'),
(7, 'Riset: AI Bakal Lebih Boros Listrik dari Tambang Bitcoin', 'Artikel', '2025-06-02', '<p>KOMPAS.com - Sebuah riset terbaru mengungkapkan bahwa penggunaan kecerdasan buatan (AI) diprediksi akan mengonsumsi listrik lebih banyak dibandingkan penambangan Bitcoin dalam beberapa tahun ke depan.</p><p>Studi yang dilakukan oleh peneliti dari Massachusetts Institute of Technology (MIT) dan University of California, Berkeley ini memperkirakan bahwa pada tahun 2030, pusat data AI global akan mengonsumsi sekitar 85-134 terawatt jam (TWh) listrik per tahun, sementara penambangan Bitcoin diperkirakan mengonsumsi sekitar 60-77 TWh.</p><p>\"Pertumbuhan penggunaan AI yang eksponensial, terutama untuk model bahasa besar (LLM) seperti GPT-5 dan Claude-3, akan mendorong peningkatan konsumsi energi yang signifikan,\" kata Dr. Alex Morgan, peneliti utama dari MIT.</p><p>Riset ini juga menyoroti bahwa pelatihan model AI besar membutuhkan energi yang sangat besar. Sebagai contoh, pelatihan GPT-5 diperkirakan mengonsumsi listrik setara dengan konsumsi 10.000 rumah tangga Amerika selama satu tahun.</p><p>\"Yang lebih mengkhawatirkan adalah penggunaan inferensi AI dalam kehidupan sehari-hari. Ketika miliaran orang menggunakan asisten AI untuk berbagai keperluan, konsumsi energi akan meningkat secara eksponensial,\" tambah Dr. Morgan.</p><p>Para peneliti menyarankan agar industri AI lebih fokus pada pengembangan model yang lebih efisien secara energi dan menggunakan sumber energi terbarukan untuk pusat data mereka.</p>', 'berita7.jpeg', 1, 'https://tekno.kompas.com/read/2025/06/02/19010047/riset--ai-bakal-lebih-boros-listrik-dari-tambang-bitcoin'),
(8, 'Amankah Menggunakan TWS Saat Ponsel Sedang Dicas?', 'Artikel', '2025-05-19', '<p>KOMPAS.com - Penggunaan earphone True Wireless Stereo (TWS) saat ponsel sedang diisi daya (charging) sering menimbulkan kekhawatiran di kalangan pengguna. Banyak yang bertanya-tanya apakah praktik ini aman atau justru berbahaya.</p><p>Menurut pakar keamanan perangkat elektronik, Dr. Budi Santoso dari Institut Teknologi Bandung (ITB), secara umum menggunakan TWS saat ponsel sedang dicas adalah aman.</p><p>\"Dari segi teknis, tidak ada masalah menggunakan TWS saat ponsel sedang dicas. Bluetooth dan pengisian daya bekerja pada sistem yang berbeda dan tidak saling mengganggu,\" jelas Dr. Budi.</p><p>Namun, ia mengingatkan ada beberapa hal yang perlu diperhatikan untuk memastikan keamanan:</p><p>Pertama, pastikan menggunakan charger original atau bersertifikasi. Charger berkualitas rendah bisa menyebabkan lonjakan listrik yang berpotensi merusak ponsel atau TWS.</p><p>Kedua, hindari menggunakan ponsel secara intensif saat sedang dicas, karena bisa menyebabkan perangkat terlalu panas.</p><p>\"Jika ponsel terasa sangat panas saat dicas dan digunakan bersamaan dengan TWS, sebaiknya hentikan penggunaan untuk sementara,\" saran Dr. Budi.</p><p>Ia juga menambahkan bahwa mitos tentang bahaya radiasi dari penggunaan TWS saat ponsel dicas tidak memiliki dasar ilmiah yang kuat.</p><p>\"Radiasi yang dihasilkan ponsel dan TWS termasuk dalam kategori radiasi non-pengion yang tingkatnya sangat rendah dan tidak membahayakan tubuh manusia,\" pungkasnya.</p>', 'berita8.jpg', 1, 'https://tekno.kompas.com/read/2025/05/19/17050017/amankah-menggunakan-tws-saat-ponsel-sedang-dicas-'),
(9, 'Perubahan Iklim Bikin Separuh Dunia Rasakan Panas Ekstrem Sebulan', 'Informasi', '2025-06-02', '<p>KOMPAS.com - Sebuah studi terbaru mengungkapkan bahwa hampir separuh populasi dunia kini mengalami suhu panas ekstrem selama sebulan penuh setiap tahunnya akibat perubahan iklim.</p><p>Penelitian yang dipublikasikan dalam jurnal Nature Climate Change ini menunjukkan peningkatan dramatis dari kondisi 40 tahun lalu, di mana rata-rata orang hanya mengalami panas ekstrem selama 5 hari per tahun.</p><p>\"Ini adalah bukti nyata bahwa perubahan iklim bukan lagi ancaman masa depan, tetapi realitas yang kita hadapi sekarang,\" kata Dr. Maria Rodriguez, peneliti utama dari University of Oxford.</p><p>Studi tersebut mendefinisikan \"panas ekstrem\" sebagai suhu yang melampaui 95 persentil dari rata-rata historis suatu wilayah antara tahun 1979-1989.</p><p>Wilayah tropis, termasuk Asia Tenggara, Afrika Tengah, dan Amerika Selatan, mengalami dampak paling parah dengan penduduknya mengalami panas ekstrem hingga 50 hari per tahun.</p><p>\"Indonesia termasuk negara yang terdampak signifikan. Jakarta, misalnya, kini mengalami suhu di atas 35 derajat Celsius hampir 40 hari per tahun, dibandingkan hanya 10 hari per tahun pada 1980-an,\" jelas Dr. Rodriguez.</p><p>Para peneliti memperingatkan bahwa tanpa upaya mitigasi yang agresif, pada tahun 2050 sekitar 75 persen populasi dunia akan mengalami panas ekstrem selama dua bulan setiap tahunnya.</p><p>\"Ini bukan hanya masalah ketidaknyamanan. Panas ekstrem berkepanjangan berdampak serius pada kesehatan publik, produktivitas ekonomi, dan ketahanan pangan,\" tambahnya.</p>', 'berita9.png', 1, 'https://lestari.kompas.com/read/2025/06/02/152801586/perubahan-iklim-bikin-separuh-dunia-rasakan-panas-ekstrem-sebulan'),
(10, 'Oknum Polisi di Medan yang Aniaya Warga Pakai Botol Alkohol Disanksi Demosi', 'Informasi', '2025-06-02', '<p>MEDAN, KOMPAS.com - Seorang oknum polisi berpangkat Brigadir di Polrestabes Medan dijatuhi sanksi demosi setelah terbukti menganiaya warga dengan menggunakan botol minuman beralkohol.</p><p>Kepala Bidang Humas Polda Sumatra Utara Kombes Pol Hadi Wahyudi membenarkan adanya sanksi tersebut.</p><p>\"Benar, yang bersangkutan telah dijatuhi sanksi demosi dan mutasi ke satuan yang lebih rendah setelah melalui proses sidang disiplin,\" kata Hadi saat dihubungi, Senin (2/6/2025).</p><p>Kasus ini bermula ketika oknum polisi berinisial BP sedang bertugas patroli di kawasan Jalan Gatot Subroto, Medan, pada Jumat (30/5/2025) malam. Saat itu, BP melihat sekelompok pemuda yang sedang berkumpul di pinggir jalan.</p><p>Merasa curiga, BP menghampiri kelompok tersebut dan melakukan pemeriksaan. Namun, salah seorang pemuda berinisial RS dianggap tidak kooperatif, sehingga memicu emosi BP.</p><p>\"Tersangka kemudian memukul korban menggunakan botol minuman beralkohol yang ditemukan di lokasi kejadian,\" jelas Hadi.</p><p>Akibat pemukulan tersebut, RS mengalami luka robek di bagian kepala dan harus mendapatkan perawatan medis.</p><p>Selain sanksi demosi, BP juga diwajibkan meminta maaf kepada korban dan keluarganya, serta menanggung seluruh biaya pengobatan.</p><p>\"Ini menjadi pelajaran bagi seluruh anggota kepolisian bahwa tindakan kekerasan dalam menjalankan tugas tidak dapat ditoleransi,\" tegas Hadi.</p>', 'berita10.jpg', 1, 'https://regional.kompas.com/read/2025/06/02/204405778/oknum-polisi-di-medan-yang-aniaya-warga-pakai-botol-alkohol-disanksi-demosi');

-- --------------------------------------------------------

--
-- Struktur dari tabel `mahasiswa`
--

CREATE TABLE `mahasiswa` (
  `nim` varchar(11) NOT NULL,
  `nama` varchar(30) NOT NULL,
  `tempat_lahir` varchar(25) NOT NULL,
  `tanggal_lahir` date NOT NULL,
  `jenis_kelamin` enum('Laki-laki','Perempuan') NOT NULL,
  `jurusan_id` int(11) NOT NULL,
  `tahun_masuk` year(4) NOT NULL,
  `foto` varchar(40) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `mahasiswa`
--

INSERT INTO `mahasiswa` (`nim`, `nama`, `tempat_lahir`, `tanggal_lahir`, `jenis_kelamin`, `jurusan_id`, `tahun_masuk`, `foto`) VALUES
('12350124024', 'Rosyidah Asarunnisa', 'Karanganyar', '2004-11-20', 'Perempuan', 1, '2023', '');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pengguna`
--

CREATE TABLE `pengguna` (
  `id` int(11) NOT NULL,
  `username` varchar(20) NOT NULL,
  `password` varchar(100) NOT NULL,
  `nama` varchar(30) NOT NULL,
  `level_akses` enum('Administrator','Dosen','Mahasiswa') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `pengguna`
--

INSERT INTO `pengguna` (`id`, `username`, `password`, `nama`, `level_akses`) VALUES
(1, 'admin', '$2y$13$HLg8jF4J3XBh1I48CjXkI.B5f7PuT9cZvvO3uIaEmR.VePrdstIrW', 'Administrator', 'Administrator'),
(2, 'mfikry', '$2y$13$HLg8jF4J3XBh1I48CjXkI.B5f7PuT9cZvvO3uIaEmR.VePrdstIrW', 'Muhammad Fikry', 'Dosen'),
(3, 'myydy', '$2y$13$HLg8jF4J3XBh1I48CjXkI.B5f7PuT9cZvvO3uIaEmR.VePrdstIrW', 'Rosyid', 'Mahasiswa'),
(4, 'dndndn', '$2y$13$HLg8jF4J3XBh1I48CjXkI.B5f7PuT9cZvvO3uIaEmR.VePrdstIrW', 'Operator', 'Administrator'),
(5, 'bdn', '$2y$13$HLg8jF4J3XBh1I48CjXkI.B5f7PuT9cZvvO3uIaEmR.VePrdstIrW', 'Budiono', 'Dosen');

--
-- Indeks untuk tabel `fakultas`
--
ALTER TABLE `fakultas`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `jurusan`
--
ALTER TABLE `jurusan`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `konten`
--
ALTER TABLE `konten`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `mahasiswa`
--
ALTER TABLE `mahasiswa`
  ADD PRIMARY KEY (`nim`);

--
-- Indeks untuk tabel `pengguna`
--
ALTER TABLE `pengguna`
  ADD PRIMARY KEY (`id`);

-- AUTO_INCREMENT untuk tabel `fakultas`
--
ALTER TABLE `fakultas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT untuk tabel `jurusan`
--
ALTER TABLE `jurusan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `konten`
--
ALTER TABLE `konten`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT untuk tabel `pengguna`
--
ALTER TABLE `pengguna`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;


