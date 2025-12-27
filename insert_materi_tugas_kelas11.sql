-- Script tambahan untuk insert Materi dan Tugas untuk Kelas XI.MM dan XI.TKJ
-- Database: lms
-- Untuk siswa dengan NISN: 0987

-- ========================================
-- MATERI DAN TUGAS UNTUK KELAS XI.MM
-- ========================================

-- Jadwal 160 - MK51
INSERT INTO rb_elearning VALUES (NULL, 1, 160, 'Materi Multimedia - Dasar Animasi', '', NOW(), DATE_ADD(NOW(), INTERVAL 7 DAY), 'Materi tentang dasar-dasar animasi 2D. Pelajari prinsip-prinsip animasi dan teknik dasar pembuatan animasi.');
INSERT INTO rb_elearning VALUES (NULL, 2, 160, 'Tugas 1: Membuat Animasi Sederhana', '', NOW(), DATE_ADD(NOW(), INTERVAL 7 DAY), 'Buatlah animasi sederhana dengan durasi 10-15 detik. Gunakan software animasi pilihan Anda.');

-- Jadwal 163 - MK10
INSERT INTO rb_elearning VALUES (NULL, 1, 163, 'Materi Desain Grafis Lanjutan', '', NOW(), DATE_ADD(NOW(), INTERVAL 7 DAY), 'Materi tentang teknik desain grafis lanjutan: masking, layer effects, dan compositing.');
INSERT INTO rb_elearning VALUES (NULL, 2, 163, 'Tugas 1: Proyek Desain Poster', '', NOW(), DATE_ADD(NOW(), INTERVAL 7 DAY), 'Buatlah poster promosi event sekolah dengan menerapkan teknik yang telah dipelajari.');

-- Jadwal 53 - MK04
INSERT INTO rb_elearning VALUES (NULL, 1, 53, 'Materi Editing Video', '', NOW(), DATE_ADD(NOW(), INTERVAL 7 DAY), 'Materi tentang teknik editing video: cutting, transition, color grading, dan audio mixing.');
INSERT INTO rb_elearning VALUES (NULL, 2, 53, 'Tugas 1: Edit Video Pendek', '', NOW(), DATE_ADD(NOW(), INTERVAL 7 DAY), 'Buatlah video pendek berdurasi 2-3 menit dengan tema bebas. Terapkan teknik editing yang telah dipelajari.');

-- Jadwal 57 - MK06
INSERT INTO rb_elearning VALUES (NULL, 1, 57, 'Materi Fotografi Digital', '', NOW(), DATE_ADD(NOW(), INTERVAL 7 DAY), 'Materi tentang teknik fotografi digital: komposisi, pencahayaan, dan pengaturan kamera.');
INSERT INTO rb_elearning VALUES (NULL, 2, 57, 'Tugas 1: Portfolio Fotografi', '', NOW(), DATE_ADD(NOW(), INTERVAL 7 DAY), 'Buatlah portfolio fotografi dengan minimal 5 foto berbeda tema. Perhatikan komposisi dan pencahayaan.');

-- ========================================
-- MATERI DAN TUGAS UNTUK KELAS XI.TKJ
-- ========================================

-- Jadwal 56 - MK04
INSERT INTO rb_elearning VALUES (NULL, 1, 56, 'Materi Jaringan Komputer Lanjutan', '', NOW(), DATE_ADD(NOW(), INTERVAL 7 DAY), 'Materi tentang konfigurasi jaringan LAN, subnetting, dan routing dasar.');
INSERT INTO rb_elearning VALUES (NULL, 2, 56, 'Tugas 1: Konfigurasi Jaringan', '', NOW(), DATE_ADD(NOW(), INTERVAL 7 DAY), 'Buatlah dokumentasi konfigurasi jaringan LAN sederhana dengan minimal 5 komputer. Sertakan diagram topologi.');

-- Tambahan jadwal lainnya untuk XI.TKJ dan XI.MM
-- Total: 12 materi dan tugas tambahan
