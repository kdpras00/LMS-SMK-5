<?php
// Guru Jadwal Pelajaran & Input Absen
$act = isset($_GET['act']) ? $_GET['act'] : '';
$get_id = isset($_GET['id']) ? $_GET['id'] : '';
$get_kelas = isset($_GET['kelas']) ? $_GET['kelas'] : '';
$get_mapel = isset($_GET['mapel']) ? $_GET['mapel'] : '';

if ($act == '') {
    cek_session_guru();
    $sem = mysql_fetch_array(mysql_query("SELECT * FROM rb_tahun_akademik WHERE aktif='Ya'"));
    ?>
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">Jadwal Pelajaran</h3>
            </div>
            <div class="box-body">
                <table id="example1" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th style='width:20px'>No</th>
                            <th>Hari</th>
                            <th>Mata Pelajaran</th>
                            <th>Kelas</th>
                            <th>Jam</th>
                            <th>Ruangan</th>
                            <th style='width:150px'>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    $tampil = mysql_query("SELECT a.*, e.nama_kelas, b.namamatapelajaran, d.nama_ruangan 
                                          FROM rb_jadwal_pelajaran a 
                                          JOIN rb_mata_pelajaran b ON a.kode_pelajaran=b.kode_pelajaran
                                          JOIN rb_ruangan d ON a.kode_ruangan=d.kode_ruangan
                                          JOIN rb_kelas e ON a.kode_kelas=e.kode_kelas 
                                          WHERE a.nip='$_SESSION[id]' 
                                          AND a.id_tahun_akademik='$sem[id_tahun_akademik]' 
                                          ORDER BY 
                                            FIELD(a.hari, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'),
                                            a.jam_mulai ASC");
                    $no = 1;
                    while ($r = mysql_fetch_array($tampil)) {
                        echo "<tr>
                                <td>$no</td>
                                <td>$r[hari]</td>
                                <td>$r[namamatapelajaran]</td>
                                <td>$r[nama_kelas]</td>
                                <td>$r[jam_mulai] - $r[jam_selesai]</td>
                                <td>$r[nama_ruangan]</td>
                                <td>
                                    <a class='btn btn-success btn-xs' href='?view=guru_jadwal_absen&act=absen&kelas=$r[kode_kelas]&mapel=$r[kode_pelajaran]'>
                                        <i class='fa fa-check-square-o'></i> Input Absen
                                    </a>
                                </td>
                              </tr>";
                        $no++;
                    }
                    ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?php
} elseif ($act == 'absen') {
    cek_session_guru();
    
    if (isset($_POST['simpan_absen'])) {
        $tanggal = $_POST['tanggal'];
        $kelas = $_POST['kelas'];
        $mapel = $_POST['mapel'];
        
        // Hapus absen lama untuk tanggal yang sama
        mysql_query("DELETE FROM rb_absensi_siswa WHERE kode_kelas='$kelas' AND kode_pelajaran='$mapel' AND tanggal='$tanggal'");
        
        // Insert absen baru
        foreach ($_POST['absen'] as $nisn => $status) {
            mysql_query("INSERT INTO rb_absensi_siswa VALUES (
                NULL,
                '$nisn',
                '$kelas',
                '$mapel',
                '$tanggal',
                '$status'
            )");
        }
        
        echo "<script>
                setTimeout(function() {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: 'Absensi berhasil disimpan',
                        showConfirmButton: false,
                        timer: 1500
                    }).then(function() {
                        window.location = 'index.php?view=guru_jadwal_absen';
                    });
                }, 100);
              </script>";
    }
    
    $kelas_info = mysql_fetch_array(mysql_query("SELECT * FROM rb_kelas WHERE kode_kelas='$get_kelas'"));
    $mapel_info = mysql_fetch_array(mysql_query("SELECT * FROM rb_mata_pelajaran WHERE kode_pelajaran='$get_mapel'"));
    ?>
    <div class='col-md-12'>
        <div class='box box-info'>
            <div class='box-header with-border'>
                <h3 class='box-title'>Input Absensi Siswa</h3>
            </div>
            <form method='POST'>
                <div class='box-body'>
                    <div class='col-md-12'>
                        <table class='table table-condensed table-bordered'>
                            <tr>
                                <th width='150px'>Kelas</th>
                                <td><?php echo $kelas_info['nama_kelas']; ?></td>
                            </tr>
                            <tr>
                                <th>Mata Pelajaran</th>
                                <td><?php echo $mapel_info['namamatapelajaran']; ?></td>
                            </tr>
                            <tr>
                                <th>Tanggal</th>
                                <td><input type='date' class='form-control' name='tanggal' value='<?php echo date("Y-m-d"); ?>' required></td>
                            </tr>
                        </table>
                        
                        <input type='hidden' name='kelas' value='<?php echo $get_kelas; ?>'>
                        <input type='hidden' name='mapel' value='<?php echo $get_mapel; ?>'>
                        
                        <h4>Daftar Siswa</h4>
                        <table class='table table-bordered table-striped'>
                            <thead>
                                <tr>
                                    <th style='width:30px'>No</th>
                                    <th>NISN</th>
                                    <th>Nama Siswa</th>
                                    <th style='width:200px'>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                            $siswa = mysql_query("SELECT * FROM rb_siswa WHERE kode_kelas='$get_kelas' ORDER BY nama ASC");
                            $no = 1;
                            while ($s = mysql_fetch_array($siswa)) {
                                echo "<tr>
                                        <td>$no</td>
                                        <td>$s[nisn]</td>
                                        <td>$s[nama]</td>
                                        <td>
                                            <select class='form-control' name='absen[$s[nisn]]' required>
                                                <option value='H'>Hadir</option>
                                                <option value='S'>Sakit</option>
                                                <option value='I'>Izin</option>
                                                <option value='A'>Alpha</option>
                                            </select>
                                        </td>
                                      </tr>";
                                $no++;
                            }
                            ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class='box-footer'>
                    <button type='submit' name='simpan_absen' class='btn btn-info'>Simpan Absensi</button>
                    <a href='index.php?view=guru_jadwal_absen'><button type='button' class='btn btn-default pull-right'>Kembali</button></a>
                </div>
            </form>
        </div>
    </div>
<?php
}
?>
