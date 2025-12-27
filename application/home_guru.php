<?php
$hari_ini = date('w');
$sem = mysql_fetch_array(mysql_query("SELECT * FROM rb_tahun_akademik WHERE aktif='Ya'"));
$nama_hari = array("Minggu","Senin","Selasa","Rabu","Kamis","Jumat","Sabtu");
$hari_now = $nama_hari[$hari_ini];

// Stats
$total_mengajar = mysql_num_rows(mysql_query("SELECT * FROM rb_jadwal_pelajaran WHERE nip='$_SESSION[id]' AND id_tahun_akademik='$sem[id_tahun_akademik]'"));
$total_materi = mysql_num_rows(mysql_query("SELECT * FROM rb_elearning WHERE id_kategori_elearning='1' AND kodejdwl IN (SELECT kodejdwl FROM rb_jadwal_pelajaran WHERE nip='$_SESSION[id]')"));
$total_tugas = mysql_num_rows(mysql_query("SELECT * FROM rb_elearning WHERE id_kategori_elearning='2' AND kodejdwl IN (SELECT kodejdwl FROM rb_jadwal_pelajaran WHERE nip='$_SESSION[id]')"));

// New Stats
$total_siswa = mysql_num_rows(mysql_query("SELECT DISTINCT a.nisn FROM rb_siswa a 
                                           JOIN rb_kelas b ON a.kode_kelas=b.kode_kelas 
                                           WHERE b.kode_kelas IN (SELECT DISTINCT kode_kelas FROM rb_jadwal_pelajaran WHERE nip='$_SESSION[id]')"));

$tugas_belum_dikoreksi = mysql_num_rows(mysql_query("SELECT DISTINCT a.id_elearning_jawab FROM rb_elearning_jawab a 
                                                      JOIN rb_elearning b ON a.id_elearning=b.id_elearning 
                                                      WHERE b.kodejdwl IN (SELECT kodejdwl FROM rb_jadwal_pelajaran WHERE nip='$_SESSION[id]') 
                                                      AND (a.nilai IS NULL OR a.nilai = 0)"));

$rpp_uploaded = mysql_num_rows(mysql_query("SELECT * FROM rb_elearning1 WHERE kodejdwl IN (SELECT kodejdwl FROM rb_jadwal_pelajaran WHERE nip='$_SESSION[id]')"));

$jurnal_bulan_ini = mysql_num_rows(mysql_query("SELECT * FROM rb_journal_list 
                                                 WHERE kodejdwl IN (SELECT kodejdwl FROM rb_jadwal_pelajaran WHERE nip='$_SESSION[id]') 
                                                 AND MONTH(tanggal) = MONTH(CURDATE()) 
                                                 AND YEAR(tanggal) = YEAR(CURDATE())"));

?>

<div class="row">
    <!-- Info Login -->
    <div class="col-md-12">
        <div class="callout callout-info">
            <h4>Selamat Datang, <?php echo $nama; ?>!</h4>
            <p>Anda login sebagai <b><?php echo $level; ?></b>. Silahkan gunakan menu di sebelah kiri untuk mengelola kegiatan akademik.</p>
        </div>
    </div>

    <!-- Stats Row 1 -->
    <div class="col-lg-3 col-xs-6">
        <div class="small-box bg-aqua">
            <div class="inner">
                <h3><?php echo $total_mengajar; ?></h3>
                <p>Jam Mengajar</p>
            </div>
            <div class="icon"><i class="fa fa-calendar"></i></div>
            <a href="index.php?view=jadwalguru" class="small-box-footer">Lihat Detail <i class="fa fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <div class="col-lg-3 col-xs-6">
        <div class="small-box bg-green">
            <div class="inner">
                <h3><?php echo $total_siswa; ?></h3>
                <p>Total Siswa Diajar</p>
            </div>
            <div class="icon"><i class="fa fa-users"></i></div>
            <a href="index.php?view=absensiswa&act=detailabsenguru" class="small-box-footer">Lihat Detail <i class="fa fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <div class="col-lg-3 col-xs-6">
        <div class="small-box bg-yellow">
            <div class="inner">
                <h3><?php echo $tugas_belum_dikoreksi; ?></h3>
                <p>Tugas Belum Dikoreksi</p>
            </div>
            <div class="icon"><i class="fa fa-pencil-square-o"></i></div>
            <a href="index.php?view=koreksitugas" class="small-box-footer">Koreksi Sekarang <i class="fa fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <div class="col-lg-3 col-xs-6">
        <div class="small-box bg-red">
            <div class="inner">
                <h3><?php echo $rpp_uploaded; ?></h3>
                <p>RPP Diupload</p>
            </div>
            <div class="icon"><i class="fa fa-file-text"></i></div>
            <a href="index.php?view=bahantugas1&act=listbahantugasguru" class="small-box-footer">Lihat Detail <i class="fa fa-arrow-circle-right"></i></a>
        </div>
    </div>
</div>

<div class="row">
    <!-- Stats Row 2 -->
    <div class="col-lg-3 col-xs-6">
        <div class="small-box bg-purple">
            <div class="inner">
                <h3><?php echo $total_materi; ?></h3>
                <p>Materi Diupload</p>
            </div>
            <div class="icon"><i class="fa fa-book"></i></div>
            <a href="index.php?view=bahantugas&act=listbahantugasguru" class="small-box-footer">Lihat Detail <i class="fa fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <div class="col-lg-3 col-xs-6">
        <div class="small-box bg-maroon">
            <div class="inner">
                <h3><?php echo $total_tugas; ?></h3>
                <p>Tugas Diberikan</p>
            </div>
            <div class="icon"><i class="fa fa-tasks"></i></div>
            <a href="index.php?view=bahantugas&act=listbahantugasguru" class="small-box-footer">Lihat Detail <i class="fa fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <div class="col-lg-3 col-xs-6">
        <div class="small-box bg-teal">
            <div class="inner">
                <h3><?php echo $jurnal_bulan_ini; ?></h3>
                <p>Jurnal Bulan Ini</p>
            </div>
            <div class="icon"><i class="fa fa-list-alt"></i></div>
            <a href="index.php?view=journalguru" class="small-box-footer">Lihat Detail <i class="fa fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <div class="col-lg-3 col-xs-6">
        <div class="small-box bg-navy">
            <div class="inner">
                <h3><?php echo date('H:i'); ?></h3>
                <p>Waktu Login</p>
            </div>
            <div class="icon"><i class="fa fa-clock-o"></i></div>
            <a href="#" class="small-box-footer">NIP: <?php echo $_SESSION['id']; ?> <i class="fa fa-user"></i></a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xs-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Jadwal Mengajar Hari Ini (<?php echo $hari_now; ?>)</h3>
            </div>
            <div class="box-body">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th style='width:20px'>No</th>
                            <th>Mata Pelajaran</th>
                            <th>Kelas</th>
                            <th>Jam</th>
                            <th>Ruangan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Filter by Day
                        // Note: Database 'hari' might depend on implementation. Assuming standard Indonesian names.
                        // If empty, show message.
                        $tampil = mysql_query("SELECT a.*, e.nama_kelas, b.namamatapelajaran, d.nama_ruangan FROM rb_jadwal_pelajaran a 
                                                JOIN rb_mata_pelajaran b ON a.kode_pelajaran=b.kode_pelajaran
                                                JOIN rb_ruangan d ON a.kode_ruangan=d.kode_ruangan
                                                JOIN rb_kelas e ON a.kode_kelas=e.kode_kelas 
                                                WHERE a.nip='$_SESSION[id]' 
                                                AND a.id_tahun_akademik='$sem[id_tahun_akademik]' 
                                                AND a.hari='$hari_now' 
                                                ORDER BY a.jam_mulai ASC");
                        $no = 1;
                        if (mysql_num_rows($tampil) > 0) {
                            while ($r = mysql_fetch_array($tampil)) {
                                echo "<tr>
                                        <td>$no</td>
                                        <td>$r[namamatapelajaran]</td>
                                        <td>$r[nama_kelas]</td>
                                        <td>$r[jam_mulai] - $r[jam_selesai]</td>
                                        <td>$r[nama_ruangan]</td>
                                        <td>
                                            <a class='btn btn-success btn-xs' href='index.php?view=absensiswa&act=tampilabsen&id=$r[kode_kelas]&kd=$r[kode_pelajaran]'><i class='fa fa-check-square-o'></i> Absen</a>
                                            <a class='btn btn-warning btn-xs' href='index.php?view=journalguru&act=lihat&id=$r[kodejdwl]'><i class='fa fa-book'></i> Jurnal</a>
                                        </td>
                                      </tr>";
                                $no++;
                            }
                        } else {
                            echo "<tr><td colspan='6' align='center' style='color:red'>Tidak ada jadwal mengajar hari ini.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>