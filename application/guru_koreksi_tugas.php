<?php 
$act = isset($_GET['act']) ? $_GET['act'] : '';
$get_kelas = isset($_GET['kelas']) ? $_GET['kelas'] : '';
$get_tahun = isset($_GET['tahun']) ? $_GET['tahun'] : '';
$get_id = isset($_GET['id']) ? $_GET['id'] : '';
$get_kd = isset($_GET['kd']) ? $_GET['kd'] : '';
$get_jdwl = isset($_GET['jdwl']) ? $_GET['jdwl'] : '';
$get_ide = isset($_GET['ide']) ? $_GET['ide'] : '';
$get_idj = isset($_GET['idj']) ? $_GET['idj'] : '';

if ($act==''){ 
    cek_session_guru();
?>
            <div class="col-xs-12">  
              <div class="box">
                <div class="box-header">
                  <h3 class="box-title"><?php if ($get_tahun != ''){ echo "Koreksi Tugas Siswa"; }else{ echo "Koreksi Tugas Siswa pada ".date('Y'); } ?></h3>
                  <form style='margin-right:5px; margin-top:0px' class='pull-right' action='' method='GET'>
                    <input type="hidden" name='view' value='koreksitugas'>
                    <select name='tahun' style='padding:4px'>
                        <?php 
                            echo "<option value=''>- Pilih Tahun Akademik -</option>";
                            $tahun = mysql_query("SELECT * FROM rb_tahun_akademik");
                            while ($k = mysql_fetch_array($tahun)){
                              if ($get_tahun==$k['id_tahun_akademik']){
                                echo "<option value='$k[id_tahun_akademik]' selected>$k[nama_tahun]</option>";
                              }else{
                                echo "<option value='$k[id_tahun_akademik]'>$k[nama_tahun]</option>";
                              }
                            }
                        ?>
                    </select>
                    <input type="submit" style='margin-top:-4px' class='btn btn-success btn-sm' value='Lihat'>
                  </form>

                </div><!-- /.box-header -->
                <div class="box-body">
                  <table id="example1" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th style='width:20px'>No</th>
                        <th>Mata Pelajaran</th>
                        <th>Kelas</th>
                        <th>Judul Tugas</th>
                        <th>Total Pengumpulan</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
                  <?php
                    $session_id = $_SESSION['id'];
                    
                    if ($get_tahun != ''){
                      $tampil = mysql_query("SELECT a.*, b.namamatapelajaran, c.nama_kelas, d.kodejdwl
                                            FROM rb_elearning a 
                                            JOIN rb_jadwal_pelajaran d ON a.kodejdwl=d.kodejdwl
                                            JOIN rb_mata_pelajaran b ON d.kode_pelajaran=b.kode_pelajaran
                                            JOIN rb_kelas c ON d.kode_kelas=c.kode_kelas
                                            WHERE d.nip='$session_id' 
                                              AND d.id_tahun_akademik='$get_tahun'
                                              AND a.id_kategori_elearning='2'
                                            ORDER BY a.tanggal_tugas DESC");
                    }else{
                      $tampil = mysql_query("SELECT a.*, b.namamatapelajaran, c.nama_kelas, d.kodejdwl
                                            FROM rb_elearning a 
                                            JOIN rb_jadwal_pelajaran d ON a.kodejdwl=d.kodejdwl
                                            JOIN rb_mata_pelajaran b ON d.kode_pelajaran=b.kode_pelajaran
                                            JOIN rb_kelas c ON d.kode_kelas=c.kode_kelas
                                            WHERE d.nip='$session_id' 
                                              AND d.id_tahun_akademik LIKE '".date('Y')."%'
                                              AND a.id_kategori_elearning='2'
                                            ORDER BY a.tanggal_tugas DESC");
                    }
                    
                    $no = 1;
                    if ($tampil && mysql_num_rows($tampil) > 0) {
                        while($r=mysql_fetch_array($tampil)){
                          $total_pengumpulan = mysql_num_rows(mysql_query("SELECT * FROM rb_elearning_jawab WHERE id_elearning='$r[id_elearning]'"));
                          
                          echo "<tr><td>$no</td>
                                    <td>$r[namamatapelajaran]</td>
                                    <td>$r[nama_kelas]</td>
                                    <td>$r[nama_file]</td>
                                    <td style='color:red'>$total_pengumpulan Siswa</td>
                                    <td><a class='btn btn-success btn-xs' title='Lihat & Koreksi' href='index.php?view=koreksitugas&act=koreksi&ide=$r[id_elearning]&jdwl=$r[kodejdwl]'><span class='glyphicon glyphicon-edit'></span> Koreksi</a></td>
                                </tr>";
                          $no++;
                        }
                    } else {
                        echo "<tr><td colspan='6' style='text-align:center; color:red'>Belum ada tugas yang perlu dikoreksi</td></tr>";
                    }
                  ?>
                    </tbody>
                  </table>
                </div><!-- /.box-body -->
                </div>
            </div>

<?php 
}elseif($act=='koreksi'){
    cek_session_guru();
    
    // Handle update nilai
    if (isset($_POST['update_nilai'])){
        $idj = $_POST['idj'];
        $nilai = $_POST['nilai'];
        mysql_query("UPDATE rb_elearning_jawab SET nilai='$nilai' WHERE id_elearning_jawab='$idj'");
        echo "<script>alert('Nilai berhasil disimpan!'); document.location='index.php?view=koreksitugas&act=koreksi&ide=$get_ide&jdwl=$get_jdwl';</script>";
    }
    
    // Handle hapus jawaban
    if (isset($_GET['hapus'])){
        mysql_query("DELETE FROM rb_elearning_jawab WHERE id_elearning_jawab='$_GET[hapus]'");
        echo "<script>document.location='index.php?view=koreksitugas&act=koreksi&ide=$get_ide&jdwl=$get_jdwl';</script>";
    }
    
    $tugas = mysql_fetch_array(mysql_query("SELECT a.*, b.namamatapelajaran, c.nama_kelas 
                                             FROM rb_elearning a
                                             JOIN rb_jadwal_pelajaran d ON a.kodejdwl=d.kodejdwl
                                             JOIN rb_mata_pelajaran b ON d.kode_pelajaran=b.kode_pelajaran
                                             JOIN rb_kelas c ON d.kode_kelas=c.kode_kelas
                                             WHERE a.id_elearning='$get_ide'"));
?>
            <div class="col-xs-12">  
              <div class="box">
                <div class="box-header">
                  <h3 class="box-title">Koreksi Tugas: <?php echo $tugas['nama_file']; ?></h3>
                  <a class='btn btn-danger btn-sm pull-right' href='index.php?view=koreksitugas'>Kembali</a>
                </div>

                <div class='col-md-12' style='margin-top:10px'>
                  <table class='table table-condensed table-hover'>
                      <tbody>
                        <tr><th width='150px'>Mata Pelajaran</th> <td><?php echo $tugas['namamatapelajaran']; ?></td></tr>
                        <tr><th>Kelas</th> <td><?php echo $tugas['nama_kelas']; ?></td></tr>
                        <tr><th>Judul Tugas</th> <td><?php echo $tugas['nama_file']; ?></td></tr>
                        <tr><th>Batas Waktu</th> <td><?php echo $tugas['tanggal_selesai']; ?> WIB</td></tr>
                      </tbody>
                  </table>
                </div>

                <div class="box-body">
                  <table class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th style='width:20px'>No</th>
                        <th>Nama Siswa</th>
                        <th>Tanggal Kirim</th>
                        <th>File Jawaban</th>
                        <th>Nilai Tugas</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
                  <?php
                    $tampil = mysql_query("SELECT a.*, b.nama, b.nisn 
                                          FROM rb_elearning_jawab a 
                                          JOIN rb_siswa b ON a.nisn=b.nisn 
                                          WHERE a.id_elearning='$get_ide' 
                                          ORDER BY a.waktu DESC");
                    $no = 1;
                    while($r=mysql_fetch_array($tampil)){
                      echo "<tr><td>$no</td>
                                <td>$r[nama]</td>
                                <td>$r[waktu]</td>
                                <td><a class='btn btn-info btn-xs' href='download.php?file=$r[file_tugas]'><span class='glyphicon glyphicon-download'></span> Unduh</a></td>
                                <td>
                                  <form method='POST' style='display:inline' action=''>
                                    <input type='hidden' name='idj' value='$r[id_elearning_jawab]'>
                                    <input type='number' name='nilai' value='$r[nilai]' style='width:60px; display:inline' min='0' max='100'>
                                    <button type='submit' name='update_nilai' class='btn btn-success btn-xs'><span class='glyphicon glyphicon-save'></span> Simpan</button>
                                  </form>
                                </td>
                                <td>
                                  <a class='btn btn-danger btn-xs' href='index.php?view=koreksitugas&act=koreksi&ide=$get_ide&jdwl=$get_jdwl&hapus=$r[id_elearning_jawab]' onclick=\"return confirm('Yakin hapus jawaban ini?')\"><span class='glyphicon glyphicon-trash'></span></a>
                                </td>
                            </tr>";
                      $no++;
                    }
                  ?>
                    </tbody>
                  </table>
                </div><!-- /.box-body -->
                </div>
            </div>
<?php 
}
?>
