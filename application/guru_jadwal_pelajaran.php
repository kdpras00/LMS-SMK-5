<?php 
$act = isset($_GET['act']) ? $_GET['act'] : '';
$get_tahun = isset($_GET['tahun']) ? $_GET['tahun'] : '';
$get_id = isset($_GET['id']) ? $_GET['id'] : '';

if ($act==''){ 
    cek_session_guru();
?>
            <div class="col-xs-12">  
              <div class="box">
                <div class="box-header">
                  <h3 class="box-title"><?php if ($get_tahun != ''){ echo "Jadwal Pelajaran Anda"; }else{ echo "Jadwal Pelajaran Anda pada ".date('Y'); } ?></h3>
                  <form style='margin-right:5px; margin-top:0px' class='pull-right' action='' method='GET'>
                    <input type="hidden" name='view' value='jadwalguru'>
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
                        <th>Hari</th>
                        <th>Jam</th>
                        <th>Mata Pelajaran</th>
                        <th>Kelas</th>
                        <th>Ruangan</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
                  <?php
                    $tampil = null;
                    if ($get_tahun != ''){
                      $tampil = mysql_query("SELECT a.*, e.nama_kelas, b.namamatapelajaran, b.kode_pelajaran, c.nama_guru, d.nama_ruangan FROM rb_jadwal_pelajaran a 
                                            JOIN rb_mata_pelajaran b ON a.kode_pelajaran=b.kode_pelajaran
                                              JOIN rb_guru c ON a.nip=c.nip 
                                                JOIN rb_ruangan d ON a.kode_ruangan=d.kode_ruangan
                                                  JOIN rb_kelas e ON a.kode_kelas=e.kode_kelas 
                                                  where a.nip='$_SESSION[id]' AND a.id_tahun_akademik='$get_tahun' ORDER BY 
                                                    CASE a.hari
                                                      WHEN 'Senin' THEN 1
                                                      WHEN 'Selasa' THEN 2
                                                      WHEN 'Rabu' THEN 3
                                                      WHEN 'Kamis' THEN 4
                                                      WHEN 'Jumat' THEN 5
                                                      WHEN 'Sabtu' THEN 6
                                                      ELSE 7
                                                    END, a.jam_mulai");
                    
                    }else{
                      $tampil = mysql_query("SELECT a.*, e.nama_kelas, b.namamatapelajaran, b.kode_pelajaran, c.nama_guru, d.nama_ruangan FROM rb_jadwal_pelajaran a 
                                            JOIN rb_mata_pelajaran b ON a.kode_pelajaran=b.kode_pelajaran
                                              JOIN rb_guru c ON a.nip=c.nip 
                                                JOIN rb_ruangan d ON a.kode_ruangan=d.kode_ruangan
                                                JOIN rb_kelas e ON a.kode_kelas=e.kode_kelas 
                                                  where a.nip='$_SESSION[id]' AND a.id_tahun_akademik LIKE '".date('Y')."%' ORDER BY 
                                                    CASE a.hari
                                                      WHEN 'Senin' THEN 1
                                                      WHEN 'Selasa' THEN 2
                                                      WHEN 'Rabu' THEN 3
                                                      WHEN 'Kamis' THEN 4
                                                      WHEN 'Jumat' THEN 5
                                                      WHEN 'Sabtu' THEN 6
                                                      ELSE 7
                                                    END, a.jam_mulai");
                    }
                    if ($tampil) {
                        $no = 1;
                        while($r=mysql_fetch_array($tampil)){
                        echo "<tr><td>$no</td>
                                  <td>$r[hari]</td>
                                  <td>$r[jam_mulai] - $r[jam_selesai]</td>
                                  <td>$r[namamatapelajaran]</td>
                                  <td>$r[nama_kelas]</td>
                                  <td>$r[nama_ruangan]</td>
                                  <td><a class='btn btn-primary btn-xs' title='Input Absensi Siswa' href='index.php?view=absensiswa&act=detailabsenguru&jdwl=$r[kodejdwl]'><span class='glyphicon glyphicon-check'></span> Input Absensi</a></td>
                              </tr>";
                          $no++;
                          }
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
