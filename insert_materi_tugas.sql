-- Script untuk insert Materi (Bahan) dan Tugas untuk semua siswa
-- Database: lms
-- Tabel: rb_elearning
-- Struktur: id_elearning, id_kategori_elearning, kodejdwl, nama_file, file_upload, tanggal_tugas, tanggal_selesai, keterangan

-- Kategori: 1 = Bahan/Materi, 2 = Tugas

-- ========================================
-- MATERI DAN TUGAS UNTUK KELAS X - DKV 1
-- ========================================

-- Mata Pelajaran: Pendidikan Agama Islam (KP01) - Guru: 3
INSERT INTO rb_elearning VALUES (NULL, 1, 253, 'Materi Pendidikan Agama Islam - Akhlak Mulia', '', NOW(), DATE_ADD(NOW(), INTERVAL 7 DAY), 'Materi tentang akhlak mulia dalam Islam. Siswa diharapkan memahami pentingnya akhlak yang baik dalam kehidupan sehari-hari.');
INSERT INTO rb_elearning VALUES (NULL, 2, 253, 'Tugas 1: Analisis Akhlak Mulia', '', NOW(), DATE_ADD(NOW(), INTERVAL 7 DAY), 'Buatlah essay tentang penerapan akhlak mulia dalam kehidupan sehari-hari. Minimal 500 kata.');

-- Mata Pelajaran: Bahasa Indonesia (KP02) - Guru: 32
INSERT INTO rb_elearning VALUES (NULL, 1, 257, 'Materi Teks Laporan Hasil Observasi', '', NOW(), DATE_ADD(NOW(), INTERVAL 7 DAY), 'Materi tentang struktur dan ciri-ciri teks laporan hasil observasi. Pelajari dengan seksama untuk tugas minggu depan.');
INSERT INTO rb_elearning VALUES (NULL, 2, 257, 'Tugas 1: Membuat Teks Laporan', '', NOW(), DATE_ADD(NOW(), INTERVAL 7 DAY), 'Buatlah teks laporan hasil observasi tentang lingkungan sekolah. Perhatikan struktur dan kaidah kebahasaan.');

-- Mata Pelajaran: Matematika (KP04) - Guru: 8
INSERT INTO rb_elearning VALUES (NULL, 1, 258, 'Materi Persamaan Linear', '', NOW(), DATE_ADD(NOW(), INTERVAL 7 DAY), 'Materi tentang persamaan linear satu variabel dan dua variabel. Pelajari contoh soal yang diberikan.');
INSERT INTO rb_elearning VALUES (NULL, 2, 258, 'Tugas 1: Latihan Soal Persamaan Linear', '', NOW(), DATE_ADD(NOW(), INTERVAL 7 DAY), 'Kerjakan 10 soal persamaan linear yang ada di buku paket halaman 45-47.');

-- Mata Pelajaran: Sejarah Indonesia (KP05) - Guru: 13
INSERT INTO rb_elearning VALUES (NULL, 1, 259, 'Materi Kerajaan Hindu-Buddha di Indonesia', '', NOW(), DATE_ADD(NOW(), INTERVAL 7 DAY), 'Materi tentang perkembangan kerajaan Hindu-Buddha di Indonesia. Fokus pada Kerajaan Kutai, Tarumanegara, dan Majapahit.');
INSERT INTO rb_elearning VALUES (NULL, 2, 259, 'Tugas 1: Rangkuman Kerajaan', '', NOW(), DATE_ADD(NOW(), INTERVAL 7 DAY), 'Buatlah rangkuman tentang salah satu kerajaan Hindu-Buddha di Indonesia. Sertakan peta lokasi kerajaan.');

-- Mata Pelajaran: Bahasa Inggris (KP06) - Guru: 15
INSERT INTO rb_elearning VALUES (NULL, 1, 260, 'Materi Introduction and Greeting', '', NOW(), DATE_ADD(NOW(), INTERVAL 7 DAY), 'Materi tentang cara memperkenalkan diri dan menyapa dalam bahasa Inggris. Pelajari ungkapan-ungkapan yang umum digunakan.');
INSERT INTO rb_elearning VALUES (NULL, 2, 260, 'Tugas 1: Self Introduction Video', '', NOW(), DATE_ADD(NOW(), INTERVAL 7 DAY), 'Buatlah video perkenalan diri dalam bahasa Inggris. Durasi 2-3 menit. Upload ke Google Drive dan kirim linknya.');

-- Mata Pelajaran: Seni Budaya (KP07) - Guru: 16
INSERT INTO rb_elearning VALUES (NULL, 1, 261, 'Materi Dasar-Dasar Desain Grafis', '', NOW(), DATE_ADD(NOW(), INTERVAL 7 DAY), 'Materi tentang prinsip-prinsip dasar desain grafis: komposisi, warna, tipografi, dan layout.');
INSERT INTO rb_elearning VALUES (NULL, 2, 261, 'Tugas 1: Membuat Poster Sederhana', '', NOW(), DATE_ADD(NOW(), INTERVAL 7 DAY), 'Buatlah poster sederhana dengan tema "Kebersihan Lingkungan". Gunakan aplikasi desain grafis pilihan Anda.');

-- Mata Pelajaran: Pendidikan Jasmani (KP08) - Guru: 28
INSERT INTO rb_elearning VALUES (NULL, 1, 304, 'Materi Permainan Bola Voli', '', NOW(), DATE_ADD(NOW(), INTERVAL 7 DAY), 'Materi tentang teknik dasar permainan bola voli: passing, servis, dan smash.');
INSERT INTO rb_elearning VALUES (NULL, 2, 304, 'Tugas 1: Analisis Teknik Bola Voli', '', NOW(), DATE_ADD(NOW(), INTERVAL 7 DAY), 'Tonton video pertandingan bola voli dan analisis teknik yang digunakan. Buat laporan singkat.');

-- Mata Pelajaran: Prakarya (KP12) - Guru: 5
INSERT INTO rb_elearning VALUES (NULL, 1, 306, 'Materi Kerajinan dari Bahan Bekas', '', NOW(), DATE_ADD(NOW(), INTERVAL 7 DAY), 'Materi tentang cara membuat kerajinan tangan dari bahan bekas. Fokus pada prinsip reduce, reuse, recycle.');
INSERT INTO rb_elearning VALUES (NULL, 2, 306, 'Tugas 1: Proyek Kerajinan', '', NOW(), DATE_ADD(NOW(), INTERVAL 7 DAY), 'Buatlah satu produk kerajinan dari bahan bekas. Dokumentasikan proses pembuatan dan hasil akhir.');

-- Mata Pelajaran: Simulasi Digital (KP17) - Guru: 22
INSERT INTO rb_elearning VALUES (NULL, 1, 307, 'Materi Pengenalan Microsoft Office', '', NOW(), DATE_ADD(NOW(), INTERVAL 7 DAY), 'Materi tentang pengenalan Microsoft Word, Excel, dan PowerPoint. Pelajari fungsi dasar masing-masing aplikasi.');
INSERT INTO rb_elearning VALUES (NULL, 2, 307, 'Tugas 1: Membuat Dokumen Word', '', NOW(), DATE_ADD(NOW(), INTERVAL 7 DAY), 'Buatlah dokumen laporan dengan Microsoft Word. Gunakan fitur heading, table of contents, dan page number.');

-- Mata Pelajaran: Fisika (KP03) - Guru: 25
INSERT INTO rb_elearning VALUES (NULL, 1, 305, 'Materi Gerak Lurus', '', NOW(), DATE_ADD(NOW(), INTERVAL 7 DAY), 'Materi tentang gerak lurus beraturan (GLB) dan gerak lurus berubah beraturan (GLBB). Pelajari rumus dan contoh soal.');
INSERT INTO rb_elearning VALUES (NULL, 2, 305, 'Tugas 1: Soal Gerak Lurus', '', NOW(), DATE_ADD(NOW(), INTERVAL 7 DAY), 'Kerjakan 5 soal tentang GLB dan GLBB yang ada di buku paket halaman 30-32.');

-- ========================================
-- MATERI DAN TUGAS UNTUK KELAS X - DKV 2
-- ========================================

-- Mata Pelajaran: Pendidikan Agama Islam (KP01) - Guru: 3
INSERT INTO rb_elearning VALUES (NULL, 1, 254, 'Materi Pendidikan Agama Islam - Iman kepada Malaikat', '', NOW(), DATE_ADD(NOW(), INTERVAL 7 DAY), 'Materi tentang iman kepada malaikat Allah. Pelajari nama-nama malaikat dan tugasnya.');
INSERT INTO rb_elearning VALUES (NULL, 2, 254, 'Tugas 1: Rangkuman Malaikat', '', NOW(), DATE_ADD(NOW(), INTERVAL 7 DAY), 'Buatlah rangkuman tentang 10 malaikat Allah beserta tugasnya masing-masing.');

-- Mata Pelajaran: Bahasa Indonesia (KP02) - Guru: 28
INSERT INTO rb_elearning VALUES (NULL, 1, 263, 'Materi Teks Eksposisi', '', NOW(), DATE_ADD(NOW(), INTERVAL 7 DAY), 'Materi tentang struktur dan ciri-ciri teks eksposisi. Pelajari contoh-contoh teks eksposisi yang baik.');
INSERT INTO rb_elearning VALUES (NULL, 2, 263, 'Tugas 1: Menulis Teks Eksposisi', '', NOW(), DATE_ADD(NOW(), INTERVAL 7 DAY), 'Buatlah teks eksposisi tentang pentingnya pendidikan. Minimal 400 kata dengan struktur yang benar.');

-- Mata Pelajaran: Seni Budaya (KP07) - Guru: 16
INSERT INTO rb_elearning VALUES (NULL, 1, 264, 'Materi Teori Warna dalam Desain', '', NOW(), DATE_ADD(NOW(), INTERVAL 7 DAY), 'Materi tentang teori warna: warna primer, sekunder, tersier, dan harmoni warna dalam desain grafis.');
INSERT INTO rb_elearning VALUES (NULL, 2, 264, 'Tugas 1: Palet Warna', '', NOW(), DATE_ADD(NOW(), INTERVAL 7 DAY), 'Buatlah 3 palet warna yang harmonis untuk tema: modern, tradisional, dan natural. Gunakan tools online atau aplikasi desain.');

-- Mata Pelajaran: Bahasa Inggris (KP06) - Guru: 15
INSERT INTO rb_elearning VALUES (NULL, 1, 266, 'Materi Simple Present Tense', '', NOW(), DATE_ADD(NOW(), INTERVAL 7 DAY), 'Materi tentang penggunaan Simple Present Tense dalam kalimat positif, negatif, dan interogatif.');
INSERT INTO rb_elearning VALUES (NULL, 2, 266, 'Tugas 1: Latihan Simple Present', '', NOW(), DATE_ADD(NOW(), INTERVAL 7 DAY), 'Buatlah 10 kalimat menggunakan Simple Present Tense. Masing-masing 3 kalimat positif, 3 negatif, dan 4 interogatif.');

-- Tambahan materi untuk jadwal lainnya
-- Total: 28 materi dan tugas untuk berbagai mata pelajaran
