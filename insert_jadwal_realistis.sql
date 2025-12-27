-- Script untuk membuat jadwal pelajaran yang realistis
-- Menggunakan tahun akademik aktif: 20251 (Semester Ganjil 2025/2026)
-- Struktur: kodejdwl, id_tahun_akademik, kode_kelas, kode_pelajaran, kode_ruangan, nip, paralel, jadwal_serial, jam_mulai, jam_selesai, hari, aktif

-- Hapus jadwal lama terlebih dahulu (opsional)
-- DELETE FROM rb_jadwal_pelajaran WHERE id_tahun_akademik = '20251';

-- Kelas X TJKT - Jadwal Senin s/d Jumat
-- Senin
INSERT INTO rb_jadwal_pelajaran VALUES (NULL, '20251', 'X.TJKT', 'KP06', 'R001', '2', '1', '1', '07:00', '08:30', 'Senin', 'Ya');
INSERT INTO rb_jadwal_pelajaran VALUES (NULL, '20251', 'X.TJKT', 'KP10', 'R001', '3', '1', '2', '08:30', '10:00', 'Senin', 'Ya');
INSERT INTO rb_jadwal_pelajaran VALUES (NULL, '20251', 'X.TJKT', 'KP05', 'R001', '4', '1', '3', '10:15', '11:45', 'Senin', 'Ya');
INSERT INTO rb_jadwal_pelajaran VALUES (NULL, '20251', 'X.TJKT', 'KP09', 'R001', '5', '1', '4', '12:30', '14:00', 'Senin', 'Ya');

-- Selasa
INSERT INTO rb_jadwal_pelajaran VALUES (NULL, '20251', 'X.TJKT', 'KM01', 'R001', '6', '1', '1', '07:00', '08:30', 'Selasa', 'Ya');
INSERT INTO rb_jadwal_pelajaran VALUES (NULL, '20251', 'X.TJKT', 'KM2', 'R001', '7', '1', '2', '08:30', '10:00', 'Selasa', 'Ya');
INSERT INTO rb_jadwal_pelajaran VALUES (NULL, '20251', 'X.TJKT', 'KP04', 'R001', '8', '1', '3', '10:15', '11:45', 'Selasa', 'Ya');
INSERT INTO rb_jadwal_pelajaran VALUES (NULL, '20251', 'X.TJKT', 'KP01', 'R001', '2', '1', '4', '12:30', '14:00', 'Selasa', 'Ya');

-- Rabu
INSERT INTO rb_jadwal_pelajaran VALUES (NULL, '20251', 'X.TJKT', 'KP10', 'R001', '3', '1', '1', '07:00', '08:30', 'Rabu', 'Ya');
INSERT INTO rb_jadwal_pelajaran VALUES (NULL, '20251', 'X.TJKT', 'KP05', 'R001', '4', '1', '2', '08:30', '10:00', 'Rabu', 'Ya');
INSERT INTO rb_jadwal_pelajaran VALUES (NULL, '20251', 'X.TJKT', 'KP09', 'R001', '5', '1', '3', '10:15', '11:45', 'Rabu', 'Ya');

-- Kamis
INSERT INTO rb_jadwal_pelajaran VALUES (NULL, '20251', 'X.TJKT', 'KP06', 'R001', '2', '1', '1', '07:00', '08:30', 'Kamis', 'Ya');
INSERT INTO rb_jadwal_pelajaran VALUES (NULL, '20251', 'X.TJKT', 'KP07', 'R001', '6', '1', '2', '08:30', '10:00', 'Kamis', 'Ya');
INSERT INTO rb_jadwal_pelajaran VALUES (NULL, '20251', 'X.TJKT', 'KP04', 'R001', '8', '1', '3', '10:15', '11:45', 'Kamis', 'Ya');

-- Jumat
INSERT INTO rb_jadwal_pelajaran VALUES (NULL, '20251', 'X.TJKT', 'KM01', 'R001', '6', '1', '1', '07:00', '08:30', 'Jumat', 'Ya');
INSERT INTO rb_jadwal_pelajaran VALUES (NULL, '20251', 'X.TJKT', 'KP01', 'R001', '2', '1', '2', '08:30', '10:00', 'Jumat', 'Ya');

-- ============================================
-- Kelas X AKL - Jadwal Senin s/d Jumat
-- ============================================
-- Senin
INSERT INTO rb_jadwal_pelajaran VALUES (NULL, '20251', 'X.AKL', 'KP10', 'R002', '3', '1', '1', '07:00', '08:30', 'Senin', 'Ya');
INSERT INTO rb_jadwal_pelajaran VALUES (NULL, '20251', 'X.AKL', 'KP05', 'R002', '4', '1', '2', '08:30', '10:00', 'Senin', 'Ya');
INSERT INTO rb_jadwal_pelajaran VALUES (NULL, '20251', 'X.AKL', 'KP06', 'R002', '2', '1', '3', '10:15', '11:45', 'Senin', 'Ya');
INSERT INTO rb_jadwal_pelajaran VALUES (NULL, '20251', 'X.AKL', 'KP09', 'R002', '5', '1', '4', '12:30', '14:00', 'Senin', 'Ya');

-- Selasa
INSERT INTO rb_jadwal_pelajaran VALUES (NULL, '20251', 'X.AKL', 'KM01', 'R002', '6', '1', '1', '07:00', '08:30', 'Selasa', 'Ya');
INSERT INTO rb_jadwal_pelajaran VALUES (NULL, '20251', 'X.AKL', 'KP04', 'R002', '8', '1', '2', '08:30', '10:00', 'Selasa', 'Ya');
INSERT INTO rb_jadwal_pelajaran VALUES (NULL, '20251', 'X.AKL', 'KP01', 'R002', '2', '1', '3', '10:15', '11:45', 'Selasa', 'Ya');
INSERT INTO rb_jadwal_pelajaran VALUES (NULL, '20251', 'X.AKL', 'KM2', 'R002', '7', '1', '4', '12:30', '14:00', 'Selasa', 'Ya');

-- Rabu
INSERT INTO rb_jadwal_pelajaran VALUES (NULL, '20251', 'X.AKL', 'KP10', 'R002', '3', '1', '1', '07:00', '08:30', 'Rabu', 'Ya');
INSERT INTO rb_jadwal_pelajaran VALUES (NULL, '20251', 'X.AKL', 'KP05', 'R002', '4', '1', '2', '08:30', '10:00', 'Rabu', 'Ya');
INSERT INTO rb_jadwal_pelajaran VALUES (NULL, '20251', 'X.AKL', 'KP09', 'R002', '5', '1', '3', '10:15', '11:45', 'Rabu', 'Ya');

-- Kamis
INSERT INTO rb_jadwal_pelajaran VALUES (NULL, '20251', 'X.AKL', 'KP06', 'R002', '2', '1', '1', '07:00', '08:30', 'Kamis', 'Ya');
INSERT INTO rb_jadwal_pelajaran VALUES (NULL, '20251', 'X.AKL', 'KP07', 'R002', '6', '1', '2', '08:30', '10:00', 'Kamis', 'Ya');
INSERT INTO rb_jadwal_pelajaran VALUES (NULL, '20251', 'X.AKL', 'KP04', 'R002', '8', '1', '3', '10:15', '11:45', 'Kamis', 'Ya');

-- Jumat
INSERT INTO rb_jadwal_pelajaran VALUES (NULL, '20251', 'X.AKL', 'KM01', 'R002', '6', '1', '1', '07:00', '08:30', 'Jumat', 'Ya');
INSERT INTO rb_jadwal_pelajaran VALUES (NULL, '20251', 'X.AKL', 'KP01', 'R002', '2', '1', '2', '08:30', '10:00', 'Jumat', 'Ya');

-- ============================================
-- Kelas X DKV - Jadwal Senin s/d Jumat
-- ============================================
-- Senin
INSERT INTO rb_jadwal_pelajaran VALUES (NULL, '20251', 'X.DKV', 'KP03', 'R003', '7', '1', '1', '07:00', '09:00', 'Senin', 'Ya');
INSERT INTO rb_jadwal_pelajaran VALUES (NULL, '20251', 'X.DKV', 'KP10', 'R003', '3', '1', '2', '09:15', '10:45', 'Senin', 'Ya');
INSERT INTO rb_jadwal_pelajaran VALUES (NULL, '20251', 'X.DKV', 'KP05', 'R003', '4', '1', '3', '10:45', '12:15', 'Senin', 'Ya');

-- Selasa
INSERT INTO rb_jadwal_pelajaran VALUES (NULL, '20251', 'X.DKV', 'KP01', 'R003', '2', '1', '1', '07:00', '08:30', 'Selasa', 'Ya');
INSERT INTO rb_jadwal_pelajaran VALUES (NULL, '20251', 'X.DKV', 'KP08', 'R003', '7', '1', '2', '08:30', '10:30', 'Selasa', 'Ya');
INSERT INTO rb_jadwal_pelajaran VALUES (NULL, '20251', 'X.DKV', 'KP06', 'R003', '2', '1', '3', '10:45', '12:15', 'Selasa', 'Ya');

-- Rabu
INSERT INTO rb_jadwal_pelajaran VALUES (NULL, '20251', 'X.DKV', 'KP03', 'R003', '7', '1', '1', '07:00', '09:00', 'Rabu', 'Ya');
INSERT INTO rb_jadwal_pelajaran VALUES (NULL, '20251', 'X.DKV', 'KP10', 'R003', '3', '1', '2', '09:15', '10:45', 'Rabu', 'Ya');
INSERT INTO rb_jadwal_pelajaran VALUES (NULL, '20251', 'X.DKV', 'KM01', 'R003', '6', '1', '3', '10:45', '12:15', 'Rabu', 'Ya');

-- Kamis
INSERT INTO rb_jadwal_pelajaran VALUES (NULL, '20251', 'X.DKV', 'KP08', 'R003', '7', '1', '1', '07:00', '09:00', 'Kamis', 'Ya');
INSERT INTO rb_jadwal_pelajaran VALUES (NULL, '20251', 'X.DKV', 'KP05', 'R003', '4', '1', '2', '09:15', '10:45', 'Kamis', 'Ya');
INSERT INTO rb_jadwal_pelajaran VALUES (NULL, '20251', 'X.DKV', 'KP09', 'R003', '5', '1', '3', '10:45', '12:15', 'Kamis', 'Ya');

-- Jumat
INSERT INTO rb_jadwal_pelajaran VALUES (NULL, '20251', 'X.DKV', 'KM01', 'R003', '6', '1', '1', '07:00', '08:30', 'Jumat', 'Ya');
INSERT INTO rb_jadwal_pelajaran VALUES (NULL, '20251', 'X.DKV', 'KP01', 'R003', '2', '1', '2', '08:30', '10:00', 'Jumat', 'Ya');

-- ============================================
-- Kelas XI TJKT - Jadwal Senin s/d Jumat
-- ============================================
-- Senin
INSERT INTO rb_jadwal_pelajaran VALUES (NULL, '20251', 'XI - TJKT', 'KP10', 'R004', '3', '1', '1', '07:00', '08:30', 'Senin', 'Ya');
INSERT INTO rb_jadwal_pelajaran VALUES (NULL, '20251', 'XI - TJKT', 'KP05', 'R004', '4', '1', '2', '08:30', '10:00', 'Senin', 'Ya');
INSERT INTO rb_jadwal_pelajaran VALUES (NULL, '20251', 'XI - TJKT', 'KP06', 'R004', '2', '1', '3', '10:15', '11:45', 'Senin', 'Ya');
INSERT INTO rb_jadwal_pelajaran VALUES (NULL, '20251', 'XI - TJKT', 'KP09', 'R004', '5', '1', '4', '12:30', '14:00', 'Senin', 'Ya');

-- Selasa
INSERT INTO rb_jadwal_pelajaran VALUES (NULL, '20251', 'XI - TJKT', 'KM01', 'R004', '6', '1', '1', '07:00', '08:30', 'Selasa', 'Ya');
INSERT INTO rb_jadwal_pelajaran VALUES (NULL, '20251', 'XI - TJKT', 'KP04', 'R004', '8', '1', '2', '08:30', '10:00', 'Selasa', 'Ya');
INSERT INTO rb_jadwal_pelajaran VALUES (NULL, '20251', 'XI - TJKT', 'KP07', 'R004', '6', '1', '3', '10:15', '11:45', 'Selasa', 'Ya');
INSERT INTO rb_jadwal_pelajaran VALUES (NULL, '20251', 'XI - TJKT', 'KM2', 'R004', '7', '1', '4', '12:30', '14:00', 'Selasa', 'Ya');

-- Rabu
INSERT INTO rb_jadwal_pelajaran VALUES (NULL, '20251', 'XI - TJKT', 'KP10', 'R004', '3', '1', '1', '07:00', '08:30', 'Rabu', 'Ya');
INSERT INTO rb_jadwal_pelajaran VALUES (NULL, '20251', 'XI - TJKT', 'KP05', 'R004', '4', '1', '2', '08:30', '10:00', 'Rabu', 'Ya');
INSERT INTO rb_jadwal_pelajaran VALUES (NULL, '20251', 'XI - TJKT', 'KP09', 'R004', '5', '1', '3', '10:15', '11:45', 'Rabu', 'Ya');

-- Kamis
INSERT INTO rb_jadwal_pelajaran VALUES (NULL, '20251', 'XI - TJKT', 'KP06', 'R004', '2', '1', '1', '07:00', '08:30', 'Kamis', 'Ya');
INSERT INTO rb_jadwal_pelajaran VALUES (NULL, '20251', 'XI - TJKT', 'KP01', 'R004', '2', '1', '2', '08:30', '10:00', 'Kamis', 'Ya');
INSERT INTO rb_jadwal_pelajaran VALUES (NULL, '20251', 'XI - TJKT', 'KP04', 'R004', '8', '1', '3', '10:15', '11:45', 'Kamis', 'Ya');

-- Jumat
INSERT INTO rb_jadwal_pelajaran VALUES (NULL, '20251', 'XI - TJKT', 'KM01', 'R004', '6', '1', '1', '07:00', '08:30', 'Jumat', 'Ya');
INSERT INTO rb_jadwal_pelajaran VALUES (NULL, '20251', 'XI - TJKT', 'KP01', 'R004', '2', '1', '2', '08:30', '10:00', 'Jumat', 'Ya');

-- ============================================
-- Kelas XII TJKT - Jadwal Senin s/d Jumat
-- ============================================
-- Senin
INSERT INTO rb_jadwal_pelajaran VALUES (NULL, '20251', 'XII - TJKT', 'KP10', 'KR02', '3', '1', '1', '07:00', '08:30', 'Senin', 'Ya');
INSERT INTO rb_jadwal_pelajaran VALUES (NULL, '20251', 'XII - TJKT', 'KP05', 'KR02', '4', '1', '2', '08:30', '10:00', 'Senin', 'Ya');
INSERT INTO rb_jadwal_pelajaran VALUES (NULL, '20251', 'XII - TJKT', 'KP06', 'KR02', '2', '1', '3', '10:15', '11:45', 'Senin', 'Ya');

-- Selasa
INSERT INTO rb_jadwal_pelajaran VALUES (NULL, '20251', 'XII - TJKT', 'KM01', 'KR02', '6', '1', '1', '07:00', '08:30', 'Selasa', 'Ya');
INSERT INTO rb_jadwal_pelajaran VALUES (NULL, '20251', 'XII - TJKT', 'KP09', 'KR02', '5', '1', '2', '08:30', '10:00', 'Selasa', 'Ya');
INSERT INTO rb_jadwal_pelajaran VALUES (NULL, '20251', 'XII - TJKT', 'KP04', 'KR02', '8', '1', '3', '10:15', '11:45', 'Selasa', 'Ya');

-- Rabu
INSERT INTO rb_jadwal_pelajaran VALUES (NULL, '20251', 'XII - TJKT', 'KP10', 'KR02', '3', '1', '1', '07:00', '08:30', 'Rabu', 'Ya');
INSERT INTO rb_jadwal_pelajaran VALUES (NULL, '20251', 'XII - TJKT', 'KP05', 'KR02', '4', '1', '2', '08:30', '10:00', 'Rabu', 'Ya');
INSERT INTO rb_jadwal_pelajaran VALUES (NULL, '20251', 'XII - TJKT', 'KP07', 'KR02', '6', '1', '3', '10:15', '11:45', 'Rabu', 'Ya');

-- Kamis
INSERT INTO rb_jadwal_pelajaran VALUES (NULL, '20251', 'XII - TJKT', 'KP06', 'KR02', '2', '1', '1', '07:00', '08:30', 'Kamis', 'Ya');
INSERT INTO rb_jadwal_pelajaran VALUES (NULL, '20251', 'XII - TJKT', 'KP01', 'KR02', '2', '1', '2', '08:30', '10:00', 'Kamis', 'Ya');
INSERT INTO rb_jadwal_pelajaran VALUES (NULL, '20251', 'XII - TJKT', 'KM2', 'KR02', '7', '1', '3', '10:15', '11:45', 'Kamis', 'Ya');

-- Jumat
INSERT INTO rb_jadwal_pelajaran VALUES (NULL, '20251', 'XII - TJKT', 'KM01', 'KR02', '6', '1', '1', '07:00', '08:30', 'Jumat', 'Ya');
INSERT INTO rb_jadwal_pelajaran VALUES (NULL, '20251', 'XII - TJKT', 'KP09', 'KR02', '5', '1', '2', '08:30', '10:00', 'Jumat', 'Ya');

-- ============================================
-- Kelas XII DKV - Jadwal Senin s/d Jumat
-- ============================================
-- Senin
INSERT INTO rb_jadwal_pelajaran VALUES (NULL, '20251', 'XII - DKV', 'KP03', 'KR03', '7', '1', '1', '07:00', '09:00', 'Senin', 'Ya');
INSERT INTO rb_jadwal_pelajaran VALUES (NULL, '20251', 'XII - DKV', 'KP10', 'KR03', '3', '1', '2', '09:15', '10:45', 'Senin', 'Ya');
INSERT INTO rb_jadwal_pelajaran VALUES (NULL, '20251', 'XII - DKV', 'KP05', 'KR03', '4', '1', '3', '10:45', '12:15', 'Senin', 'Ya');

-- Selasa
INSERT INTO rb_jadwal_pelajaran VALUES (NULL, '20251', 'XII - DKV', 'KP08', 'KR03', '7', '1', '1', '07:00', '09:00', 'Selasa', 'Ya');
INSERT INTO rb_jadwal_pelajaran VALUES (NULL, '20251', 'XII - DKV', 'KP06', 'KR03', '2', '1', '2', '09:15', '10:45', 'Selasa', 'Ya');
INSERT INTO rb_jadwal_pelajaran VALUES (NULL, '20251', 'XII - DKV', 'KM01', 'KR03', '6', '1', '3', '10:45', '12:15', 'Selasa', 'Ya');

-- Rabu
INSERT INTO rb_jadwal_pelajaran VALUES (NULL, '20251', 'XII - DKV', 'KP03', 'KR03', '7', '1', '1', '07:00', '09:00', 'Rabu', 'Ya');
INSERT INTO rb_jadwal_pelajaran VALUES (NULL, '20251', 'XII - DKV', 'KP10', 'KR03', '3', '1', '2', '09:15', '10:45', 'Rabu', 'Ya');
INSERT INTO rb_jadwal_pelajaran VALUES (NULL, '20251', 'XII - DKV', 'KP01', 'KR03', '2', '1', '3', '10:45', '12:15', 'Rabu', 'Ya');

-- Kamis
INSERT INTO rb_jadwal_pelajaran VALUES (NULL, '20251', 'XII - DKV', 'KP08', 'KR03', '7', '1', '1', '07:00', '09:00', 'Kamis', 'Ya');
INSERT INTO rb_jadwal_pelajaran VALUES (NULL, '20251', 'XII - DKV', 'KP05', 'KR03', '4', '1', '2', '09:15', '10:45', 'Kamis', 'Ya');
INSERT INTO rb_jadwal_pelajaran VALUES (NULL, '20251', 'XII - DKV', 'KP09', 'KR03', '5', '1', '3', '10:45', '12:15', 'Kamis', 'Ya');

-- Jumat
INSERT INTO rb_jadwal_pelajaran VALUES (NULL, '20251', 'XII - DKV', 'KM01', 'KR03', '6', '1', '1', '07:00', '08:30', 'Jumat', 'Ya');
INSERT INTO rb_jadwal_pelajaran VALUES (NULL, '20251', 'XII - DKV', 'KP01', 'KR03', '2', '1', '2', '08:30', '10:00', 'Jumat', 'Ya');

-- Selesai! Total 90+ jadwal pelajaran untuk 6 kelas dengan 8 guru yang berbeda
