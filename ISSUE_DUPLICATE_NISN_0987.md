## Masalah Data Duplikat NISN 0987

### Temuan
Ditemukan 2 siswa dengan NISN yang sama `0987`:

| ID Siswa | NISN | Nama | Kelas |
|----------|------|------|-------|
| 1563 | 0987 | siswa multimedia | XI.MM |
| 1564 | 0987 | Yudha Setiawan | XI.TKJ |

### Dampak
- Saat login dengan NISN 0987, sistem mengambil data pertama (id_siswa: 1563)
- Session `kode_kelas` akan di-set ke `XI.MM`
- Menyebabkan konflik dan data tidak tampil dengan benar

### Solusi yang Diperlukan
**Pilih salah satu:**
1. Hapus siswa dengan id_siswa: 1564 (Yudha Setiawan - XI.TKJ)
2. Hapus siswa dengan id_siswa: 1563 (siswa multimedia - XI.MM)
3. Ubah NISN salah satu siswa agar tidak duplikat

### Status Materi & Tugas
✅ Materi & tugas sudah dibuat untuk kedua kelas:
- XI.MM: 4 mata pelajaran (8 entries)
- XI.TKJ: 1 mata pelajaran (2 entries)

Menunggu konfirmasi user untuk menentukan data mana yang benar.
