<?php
// Guru Materi & Tugas - CRUD Lengkap
$act = isset($_GET['act']) ? $_GET['act'] : '';
$get_id = isset($_GET['id']) ? $_GET['id'] : '';

if ($act == '') {
    cek_session_guru();
    $sem = mysql_fetch_array(mysql_query("SELECT * FROM rb_tahun_akademik WHERE aktif='Ya'"));
    ?>
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">Materi & Tugas</h3>
                <a class='pull-right btn btn-primary btn-sm' href='index.php?view=guru_materi_tugas&act=tambah'>
                    <i class='fa fa-plus'></i> Tambah Materi/Tugas
                </a>
            </div>
            <div class="box-body">
                <table id="example1" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th style='width:20px'>No</th>
                            <th>Judul</th>
                            <th>Tipe</th>
                            <th>Kelas</th>
                            <th>Mata Pelajaran</th>
                            <th>Tanggal Upload</th>
                            <th>Batas Waktu</th>
                            <th>File</th>
                            <th style='width:100px'>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    $tampil = mysql_query("SELECT a.*, b.nama_kelas, c.namamatapelajaran, d.nama_kategori_elearning 
                                          FROM rb_elearning a 
                                          JOIN rb_jadwal_pelajaran e ON a.kodejdwl=e.kodejdwl
                                          JOIN rb_kelas b ON e.kode_kelas=b.kode_kelas
                                          JOIN rb_mata_pelajaran c ON e.kode_pelajaran=c.kode_pelajaran
                                          JOIN rb_kategori_elearning d ON a.id_kategori_elearning=d.id_kategori_elearning
                                          WHERE e.nip='$_SESSION[id]' 
                                          ORDER BY a.tanggal_tugas DESC");
                    $no = 1;
                    while ($r = mysql_fetch_array($tampil)) {
                        $tipe = $r['nama_kategori_elearning'];
                        $badge = ($r['id_kategori_elearning'] == '1') ? 'bg-blue' : 'bg-yellow';
                        
                        echo "<tr>
                                <td>$no</td>
                                <td>$r[nama_file]</td>
                                <td><span class='badge $badge'>$tipe</span></td>
                                <td>$r[nama_kelas]</td>
                                <td>$r[namamatapelajaran]</td>
                                <td>" . date('d/m/Y H:i', strtotime($r['tanggal_tugas'])) . "</td>
                                <td>" . date('d/m/Y H:i', strtotime($r['tanggal_selesai'])) . "</td>
                                <td>";
                        if ($r['file_upload'] != '') {
                            echo "<a href='files/$r[file_upload]' target='_blank' class='btn btn-xs btn-success'>
                                    <i class='fa fa-download'></i> Download
                                  </a>";
                        } else {
                            echo "<span class='text-muted'>-</span>";
                        }
                        echo "</td>
                                <td>
                                    <a class='btn btn-warning btn-xs' href='?view=guru_materi_tugas&act=edit&id=$r[id_elearning]'>
                                        <i class='fa fa-edit'></i>
                                    </a>
                                    <a class='btn btn-danger btn-xs' href='javascript:void(0)' 
                                       onclick=\"konfirmasiHapus('index.php?view=guru_materi_tugas&hapus=$r[id_elearning]')\">
                                        <i class='fa fa-trash'></i>
                                    </a>
                                </td>
                              </tr>";
                        $no++;
                    }
                    
                    if (isset($_GET['hapus'])) {
                        $hapus = mysql_fetch_array(mysql_query("SELECT file_upload FROM rb_elearning WHERE id_elearning='$_GET[hapus]'"));
                        if ($hapus['file_upload'] != '' && file_exists("files/" . $hapus['file_upload'])) {
                            unlink("files/" . $hapus['file_upload']);
                        }
                        mysql_query("DELETE FROM rb_elearning WHERE id_elearning='$_GET[hapus]'");
                        echo "<script>
                                setTimeout(function() {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Berhasil',
                                        text: 'Data berhasil dihapus',
                                        showConfirmButton: false,
                                        timer: 1500
                                    }).then(function() {
                                        window.location = 'index.php?view=guru_materi_tugas';
                                    });
                                }, 100);
                              </script>";
                    }
                    ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?php
} elseif ($act == 'tambah') {
    cek_session_guru();
    
    if (isset($_POST['tambah'])) {
        $dir_gambar = 'files/';
        $filename = basename($_FILES['file']['name']);
        $filenamee = date("YmdHis") . '-' . basename($_FILES['file']['name']);
        $uploadfile = $dir_gambar . $filenamee;
        
        if ($filename != '') {
            // Validasi hanya PDF
            $file_ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
            if ($file_ext != 'pdf') {
                echo "<script>
                        setTimeout(function() {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal',
                                text: 'Hanya file PDF yang diperbolehkan!',
                                showConfirmButton: true
                            });
                        }, 100);
                      </script>";
            } elseif (move_uploaded_file($_FILES['file']['tmp_name'], $uploadfile)) {
                mysql_query("INSERT INTO rb_elearning VALUES (
                    NULL,
                    '$_POST[kategori]',
                    '$_POST[jadwal]',
                    '$_POST[judul]',
                    '$filenamee',
                    '$_POST[tanggal_mulai]',
                    '$_POST[tanggal_selesai]',
                    '$_POST[keterangan]'
                )");
            }
        } else {
            mysql_query("INSERT INTO rb_elearning VALUES (
                NULL,
                '$_POST[kategori]',
                '$_POST[jadwal]',
                '$_POST[judul]',
                '',
                '$_POST[tanggal_mulai]',
                '$_POST[tanggal_selesai]',
                '$_POST[keterangan]'
            )");
        }
        
        echo "<script>
                setTimeout(function() {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: 'Data berhasil ditambahkan',
                        showConfirmButton: false,
                        timer: 1500
                    }).then(function() {
                        window.location = 'index.php?view=guru_materi_tugas';
                    });
                }, 100);
              </script>";
    }
    ?>
    <div class='col-md-12'>
        <div class='box box-info'>
            <div class='box-header with-border'>
                <h3 class='box-title'>Tambah Materi / Tugas</h3>
            </div>
            <form method='POST' enctype='multipart/form-data'>
                <div class='box-body'>
                    <div class='col-md-12'>
                        <table class='table table-condensed table-bordered'>
                            <tbody>
                                <tr>
                                    <th width='180px'>Jadwal Pelajaran</th>
                                    <td>
                                        <select class='form-control' name='jadwal' required>
                                            <option value=''>- Pilih Jadwal -</option>
                                            <?php
                                            $sem = mysql_fetch_array(mysql_query("SELECT * FROM rb_tahun_akademik WHERE aktif='Ya'"));
                                            $jadwal = mysql_query("SELECT a.*, b.nama_kelas, c.namamatapelajaran 
                                                                  FROM rb_jadwal_pelajaran a
                                                                  JOIN rb_kelas b ON a.kode_kelas=b.kode_kelas
                                                                  JOIN rb_mata_pelajaran c ON a.kode_pelajaran=c.kode_pelajaran
                                                                  WHERE a.nip='$_SESSION[id]' 
                                                                  AND a.id_tahun_akademik='$sem[id_tahun_akademik]'
                                                                  ORDER BY b.nama_kelas, c.namamatapelajaran");
                                            while ($j = mysql_fetch_array($jadwal)) {
                                                echo "<option value='$j[kodejdwl]'>$j[nama_kelas] - $j[namamatapelajaran]</option>";
                                            }
                                            ?>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Tipe</th>
                                    <td>
                                        <select class='form-control' name='kategori' required>
                                            <option value=''>- Pilih Tipe -</option>
                                            <option value='1'>Materi</option>
                                            <option value='2'>Tugas</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Judul</th>
                                    <td><input type='text' class='form-control' name='judul' required></td>
                                </tr>
                                <tr>
                                    <th>File</th>
                                    <td>
                                        <input type='file' class='form-control' name='file' accept='.pdf'>
                                        <small class='text-muted'>Format: PDF saja (Max 10MB)</small>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Tanggal Upload</th>
                                    <td><input type='datetime-local' class='form-control' name='tanggal_mulai' value='<?php echo date("Y-m-d\TH:i"); ?>' required></td>
                                </tr>
                                <tr>
                                    <th>Batas Waktu Pengumpulan</th>
                                    <td><input type='datetime-local' class='form-control' name='tanggal_selesai' required></td>
                                </tr>
                                <tr>
                                    <th>Keterangan</th>
                                    <td><textarea class='form-control' name='keterangan' rows='3'></textarea></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class='box-footer'>
                    <button type='submit' name='tambah' class='btn btn-info'>Tambahkan</button>
                    <a href='index.php?view=guru_materi_tugas'><button type='button' class='btn btn-default pull-right'>Cancel</button></a>
                </div>
            </form>
        </div>
    </div>
<?php
} elseif ($act == 'edit') {
    cek_session_guru();
    
    if (isset($_POST['update'])) {
        $dir_gambar = 'files/';
        $filename = basename($_FILES['file']['name']);
        $filenamee = date("YmdHis") . '-' . basename($_FILES['file']['name']);
        $uploadfile = $dir_gambar . $filenamee;
        
        if ($filename != '') {
            // Validasi hanya PDF
            $file_ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
            if ($file_ext != 'pdf') {
                echo "<script>
                        setTimeout(function() {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal',
                                text: 'Hanya file PDF yang diperbolehkan!',
                                showConfirmButton: true
                            });
                        }, 100);
                      </script>";
            } else {
                // Hapus file lama
                $old = mysql_fetch_array(mysql_query("SELECT file_upload FROM rb_elearning WHERE id_elearning='$get_id'"));
                if ($old['file_upload'] != '' && file_exists("files/" . $old['file_upload'])) {
                    unlink("files/" . $old['file_upload']);
                }
                
                if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadfile)) {
                mysql_query("UPDATE rb_elearning SET 
                    id_kategori_elearning = '$_POST[kategori]',
                    kodejdwl = '$_POST[jadwal]',
                    nama_file = '$_POST[judul]',
                    file_upload = '$filenamee',
                    tanggal_tugas = '$_POST[tanggal_mulai]',
                    tanggal_selesai = '$_POST[tanggal_selesai]',
                    keterangan = '$_POST[keterangan]'
                    WHERE id_elearning='$get_id'");
            }
        } else {
            mysql_query("UPDATE rb_elearning SET 
                id_kategori_elearning = '$_POST[kategori]',
                kodejdwl = '$_POST[jadwal]',
                nama_file = '$_POST[judul]',
                tanggal_tugas = '$_POST[tanggal_mulai]',
                tanggal_selesai = '$_POST[tanggal_selesai]',
                keterangan = '$_POST[keterangan]'
                WHERE id_elearning='$get_id'");
        }
        
        echo "<script>
                setTimeout(function() {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: 'Data berhasil diupdate',
                        showConfirmButton: false,
                        timer: 1500
                    }).then(function() {
                        window.location = 'index.php?view=guru_materi_tugas';
                    });
                }, 100);
              </script>";
    }
    
    $edit = mysql_query("SELECT a.*, b.nama_kelas, c.namamatapelajaran 
                        FROM rb_elearning a
                        JOIN rb_jadwal_pelajaran e ON a.kodejdwl=e.kodejdwl
                        JOIN rb_kelas b ON e.kode_kelas=b.kode_kelas
                        JOIN rb_mata_pelajaran c ON e.kode_pelajaran=c.kode_pelajaran
                        WHERE a.id_elearning='$get_id'");
    $s = mysql_fetch_array($edit);
    ?>
    <div class='col-md-12'>
        <div class='box box-info'>
            <div class='box-header with-border'>
                <h3 class='box-title'>Edit Materi / Tugas</h3>
            </div>
            <form method='POST' enctype='multipart/form-data'>
                <div class='box-body'>
                    <div class='col-md-12'>
                        <table class='table table-condensed table-bordered'>
                            <tbody>
                                <tr>
                                    <th width='180px'>Jadwal Pelajaran</th>
                                    <td>
                                        <select class='form-control' name='jadwal' required>
                                            <?php
                                            $sem = mysql_fetch_array(mysql_query("SELECT * FROM rb_tahun_akademik WHERE aktif='Ya'"));
                                            $jadwal = mysql_query("SELECT a.*, b.nama_kelas, c.namamatapelajaran 
                                                                  FROM rb_jadwal_pelajaran a
                                                                  JOIN rb_kelas b ON a.kode_kelas=b.kode_kelas
                                                                  JOIN rb_mata_pelajaran c ON a.kode_pelajaran=c.kode_pelajaran
                                                                  WHERE a.nip='$_SESSION[id]' 
                                                                  AND a.id_tahun_akademik='$sem[id_tahun_akademik]'
                                                                  ORDER BY b.nama_kelas, c.namamatapelajaran");
                                            while ($j = mysql_fetch_array($jadwal)) {
                                                $selected = ($j['kodejdwl'] == $s['kodejdwl']) ? 'selected' : '';
                                                echo "<option value='$j[kodejdwl]' $selected>$j[nama_kelas] - $j[namamatapelajaran]</option>";
                                            }
                                            ?>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Tipe</th>
                                    <td>
                                        <select class='form-control' name='kategori' required>
                                            <option value='1' <?php echo ($s['id_kategori_elearning']=='1')?'selected':''; ?>>Materi</option>
                                            <option value='2' <?php echo ($s['id_kategori_elearning']=='2')?'selected':''; ?>>Tugas</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Judul</th>
                                    <td><input type='text' class='form-control' name='judul' value='<?php echo $s['nama_file']; ?>' required></td>
                                </tr>
                                <tr>
                                    <th>Ganti File</th>
                                    <td>
                                        <?php if($s['file_upload'] != ''){ ?>
                                            <p>File saat ini: <a href='files/<?php echo $s['file_upload']; ?>' target='_blank'><?php echo $s['file_upload']; ?></a></p>
                                        <?php } ?>
                                        <input type='file' class='form-control' name='file'>
                                        <small class='text-muted'>Kosongkan jika tidak ingin mengganti file</small>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Tanggal Upload</th>
                                    <td><input type='datetime-local' class='form-control' name='tanggal_mulai' value='<?php echo date("Y-m-d\TH:i", strtotime($s['tanggal_tugas'])); ?>' required></td>
                                </tr>
                                <tr>
                                    <th>Batas Waktu Pengumpulan</th>
                                    <td><input type='datetime-local' class='form-control' name='tanggal_selesai' value='<?php echo date("Y-m-d\TH:i", strtotime($s['tanggal_selesai'])); ?>' required></td>
                                </tr>
                                <tr>
                                    <th>Keterangan</th>
                                    <td><textarea class='form-control' name='keterangan' rows='3'><?php echo $s['keterangan']; ?></textarea></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class='box-footer'>
                    <button type='submit' name='update' class='btn btn-info'>Update</button>
                    <a href='index.php?view=guru_materi_tugas'><button type='button' class='btn btn-default pull-right'>Cancel</button></a>
                </div>
            </form>
        </div>
    </div>
<?php
}
?>
