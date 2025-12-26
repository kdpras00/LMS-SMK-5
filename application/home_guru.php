<?php
$hari_ini = date('w');
$sem = mysql_fetch_array(mysql_query("SELECT * FROM rb_tahun_akademik WHERE aktif='Ya'"));
$nama_hari = array("Minggu","Senin","Selasa","Rabu","Kamis","Jumat","Sabtu");
$hari_now = $nama_hari[$hari_ini];

// Stats
$total_mengajar = mysql_num_rows(mysql_query("SELECT * FROM rb_jadwal_pelajaran WHERE nip='$_SESSION[id]' AND id_tahun_akademik='$sem[id_tahun_akademik]'"));
$total_materi = mysql_num_rows(mysql_query("SELECT * FROM rb_elearning WHERE id_kategori_elearning='1' AND kodejdwl IN (SELECT kodejdwl FROM rb_jadwal_pelajaran WHERE nip='$_SESSION[id]')"));
$total_tugas = mysql_num_rows(mysql_query("SELECT * FROM rb_elearning WHERE id_kategori_elearning='2' AND kodejdwl IN (SELECT kodejdwl FROM rb_jadwal_pelajaran WHERE nip='$_SESSION[id]')"));

?>

<div class="row">
    <!-- Info Login -->
    <div class="col-md-12">
        <div class="callout callout-info">
            <h4>Selamat Datang, <?php echo $nama; ?>!</h4>
            <p>Anda login sebagai <b><?php echo $level; ?></b>. Silahkan gunakan menu di sebelah kiri untuk mengelola kegiatan akademik.</p>
        </div>
    </div>

    <!-- Stats -->
    <div class="col-lg-4 col-xs-6">
        <div class="small-box bg-aqua">
            <div class="inner">
                <h3><?php echo $total_mengajar; ?></h3>
                <p>Jam Mengajar</p>
            </div>
            <div class="icon"><i class="fa fa-calendar"></i></div>
            <a href="index.php?view=absensiswa&act=detailabsenguru" class="small-box-footer">Lihat Detail <i class="fa fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <div class="col-lg-4 col-xs-6">
        <div class="small-box bg-green">
            <div class="inner">
                <h3><?php echo $total_materi; ?></h3>
                <p>Materi Diupload</p>
            </div>
            <div class="icon"><i class="fa fa-book"></i></div>
            <a href="index.php?view=bahantugas&act=listbahantugasguru" class="small-box-footer">Lihat Detail <i class="fa fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <div class="col-lg-4 col-xs-6">
        <div class="small-box bg-yellow">
            <div class="inner">
                <h3><?php echo $total_tugas; ?></h3>
                <p>Tugas Diberikan</p>
            </div>
            <div class="icon"><i class="fa fa-pencil"></i></div>
            <a href="index.php?view=bahantugas&act=listbahantugasguru" class="small-box-footer">Lihat Detail <i class="fa fa-arrow-circle-right"></i></a>
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