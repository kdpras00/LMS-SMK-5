<?php 
$act = isset($_GET['act']) ? $_GET['act'] : '';
$get_kelas = isset($_GET['kelas']) ? $_GET['kelas'] : '';
$get_angkatan = isset($_GET['angkatan']) ? $_GET['angkatan'] : '';

if ($act==''){ 
?>
            <div class="col-xs-12">  
              <div class="box">
                <div class="box-header">
                  <h3 class="box-title">Cetak Buku Induk</h3>
                  <form style='margin-right:5px; margin-top:0px' class='pull-right' action='' method='GET'>
                    <input type="hidden" name='view' value='bukuinduk'>
                    <input type="number" name='angkatan' style='padding:3px' placeholder='Angkatan' value='<?php echo $get_angkatan; ?>'>
                    <select name='kelas' style='padding:4px'>
                        <?php 
                            echo "<option value=''>- Filter Kelas -</option>";
                            $kelas = mysql_query("SELECT * FROM rb_kelas");
                            while ($k = mysql_fetch_array($kelas)){
                              if ($get_kelas==$k['kode_kelas']){
                                echo "<option value='$k[kode_kelas]' selected>$k[kode_kelas] - $k[nama_kelas]</option>";
                              }else{
                                echo "<option value='$k[kode_kelas]'>$k[kode_kelas] - $k[nama_kelas]</option>";
                              }
                            }
                        ?>
                    </select>
                    <input type="submit" style='margin-top:-4px' class='btn btn-info btn-sm' value='Lihat'>
                  </form>
                </div><!-- /.box-header -->
                <div class="box-body">
                <?php 
                  echo "<table id='example' class='table table-bordered table-striped'>
                    <thead>
                      <tr><th rowspan='2'>No</th>
                        <th>NIPD</th>
                        <th>NISN</th>
                        <th>Nama Siswa</th>
                        <th>Jenis Kelamin</th>
                        <th><center>Action</center></th>
                      </tr>
                    </thead>
                    <tbody>";

                    $tampil = null;
                    if ($get_kelas != '' AND $get_angkatan != ''){
                        $tampil = mysql_query("SELECT * FROM rb_siswa a LEFT JOIN rb_kelas b ON a.kode_kelas=b.kode_kelas 
                                                  LEFT JOIN rb_jenis_kelamin c ON a.id_jenis_kelamin=c.id_jenis_kelamin 
                                                    LEFT JOIN rb_jurusan d ON b.kode_jurusan=d.kode_jurusan 
                                                      where a.kode_kelas='$get_kelas' AND a.angkatan='$get_angkatan' ORDER BY a.id_siswa");
                    }
                    if ($tampil) {
                        $no = 1;
                        while($r=mysql_fetch_array($tampil)){ 
                        echo "<tr><td>$no</td>
                                  <td>$r[nipd]</td>
                                  <td>$r[nisn]</td>
                                  <td>$r[nama]</td>
                                  <td>$r[jenis_kelamin]</td>
                                  <td width='170px' align=center><a target='_BLANK' class='btn btn-success btn-xs' href='print-bukuinduk.php?id=$r[id_siswa]&kelas=$r[kode_kelas]'><span class='glyphicon glyphicon-print'></span> Print Buku Induk</a></td>
                                </tr>";
                          $no++;
                          }
                    }
                  ?>
                    </tbody>
                  </table>
                </div><!-- /.box-body -->
                <?php 
                    if ($get_kelas == '' AND $get_angkatan == ''){
                        echo "<center style='padding:60px; color:red'>Silahkan Memilih Tahun akademik dan Kelas Terlebih dahulu...</center>";
                    }
                ?>
              </div><!-- /.box -->
              
            </div>
<?php }  ?>