<?php 
$act = isset($_GET['act']) ? $_GET['act'] : '';
$get_tahun = isset($_GET['tahun']) ? $_GET['tahun'] : '';
$get_guru = isset($_GET['guru']) ? $_GET['guru'] : '';
$get_id = isset($_GET['id']) ? $_GET['id'] : '';

// Handle approval/rejection
if (isset($_GET['approve'])) {
    $id_rpp = $_GET['approve'];
    // For now, just mark as viewed by admin (you can add approval table later)
    echo "<script>Swal.fire('Berhasil', 'RPP telah disetujui', 'success').then(() => { window.location='index.php?view=adminrpp'; });</script>";
}

if (isset($_GET['reject'])) {
    $id_rpp = $_GET['reject'];
    echo "<script>Swal.fire('Berhasil', 'RPP telah ditolak', 'info').then(() => { window.location='index.php?view=adminrpp'; });</script>";
}

if ($act==''){ 
cek_session_admin();
?>
            <div class="col-xs-12">  
              <div class="box">
                <div class="box-header">
                  <h3 class="box-title">Perangkat Pembelajaran Guru (RPP)</h3>
                  <form style='margin-right:5px; margin-top:0px' class='pull-right' action='' method='GET'>
                    <input type="hidden" name='view' value='adminrpp'>
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
                    <select name='guru' style='padding:4px'>
                        <?php 
                            echo "<option value=''>- Pilih Guru -</option>";
                            $guru = mysql_query("SELECT * FROM rb_guru ORDER BY nama_guru");
                            while ($g = mysql_fetch_array($guru)){
                              if ($get_guru==$g['nip']){
                                echo "<option value='$g[nip]' selected>$g[nama_guru]</option>";
                              }else{
                                echo "<option value='$g[nip]'>$g[nama_guru]</option>";
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
                        <th>Jadwal Pelajaran</th>
                        <th>Nama Guru</th>
                        <th>Kelas</th>
                        <th>Nama File RPP</th>
                        <th>Tanggal Upload</th>
                        <th>File</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
                  <?php
                    $where = "1=1";
                    if ($get_tahun != ''){
                        $where .= " AND a.id_tahun_akademik='$get_tahun'";
                    }
                    if ($get_guru != ''){
                        $where .= " AND a.nip='$get_guru'";
                    }
                    
                    $tampil = mysql_query("SELECT e.*, a.kodejdwl, a.hari, a.jam_mulai, a.jam_selesai, b.namamatapelajaran, c.nama_guru, d.nama_kelas, a.id_tahun_akademik
                                          FROM rb_elearning1 e
                                          JOIN rb_jadwal_pelajaran a ON e.kodejdwl=a.kodejdwl
                                          JOIN rb_mata_pelajaran b ON a.kode_pelajaran=b.kode_pelajaran
                                          JOIN rb_guru c ON a.nip=c.nip 
                                          JOIN rb_kelas d ON a.kode_kelas=d.kode_kelas 
                                          WHERE $where
                                          ORDER BY e.id_elearning DESC");
                    
                    $no = 1;
                    if(mysql_num_rows($tampil) > 0){
                        while($r=mysql_fetch_array($tampil)){
                        $jadwal_info = "$r[namamatapelajaran] - $r[hari] ($r[jam_mulai]-$r[jam_selesai])";
                        echo "<tr><td>$no</td>
                                  <td>$jadwal_info</td>
                                  <td>$r[nama_guru]</td>
                                  <td>$r[nama_kelas]</td>
                                  <td>$r[nama_file]</td>
                                  <td>-</td>
                                  <td><a class='btn btn-info btn-xs' href='download.php?file=$r[file_upload]'><i class='fa fa-download'></i> Download</a></td>
                                  <td>
                                    <a class='btn btn-success btn-xs' href='index.php?view=adminrpp&approve=$r[id_elearning]' onclick=\"return confirm('Setujui RPP ini?')\"><i class='fa fa-check'></i> Setujui</a>
                                  </td>
                              </tr>";
                          $no++;
                          }
                    } else {
                        echo "<tr><td colspan='8' style='text-align:center; color:red'>Belum ada RPP yang diupload</td></tr>";
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
