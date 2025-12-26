<?php 
if ($_GET['act']==''){ 
    if (isset($_GET['tahun']) && isset($_GET['kelas'])){
        $t = mysql_fetch_array(mysql_query("SELECT * FROM rb_tahun_akademik where id_tahun_akademik='$_GET[tahun]'"));
        $k = mysql_fetch_array(mysql_query("SELECT * FROM rb_kelas where kode_kelas='$_GET[kelas]'"));
    }
?>
            <div class="col-xs-12">  
              <div class="box">
                <div class="box-header">
                  <h3 class="box-title">Rekap Nilai Siswa <?php if (isset($_GET['tahun'])){ echo "Tahun $_GET[tahun]"; } ?></h3>
                  <form style='margin-right:5px; margin-top:0px' class='pull-right' action='' method='GET'>
                    <input type="hidden" name='view' value='rekapnilai'>
                    <select name='tahun' style='padding:4px'>
                        <?php 
                            echo "<option value=''>- Pilih Tahun Akademik -</option>";
                            $tahun = mysql_query("SELECT * FROM rb_tahun_akademik");
                            while ($k = mysql_fetch_array($tahun)){
                              if ($_GET['tahun']==$k['id_tahun_akademik']){
                                echo "<option value='$k[id_tahun_akademik]' selected>$k[nama_tahun]</option>";
                              }else{
                                echo "<option value='$k[id_tahun_akademik]'>$k[nama_tahun]</option>";
                              }
                            }
                        ?>
                    </select>
                    <select name='kelas' style='padding:4px'>
                        <?php 
                            echo "<option value=''>- Pilih Kelas -</option>";
                            $kelas = mysql_query("SELECT * FROM rb_kelas");
                            while ($k = mysql_fetch_array($kelas)){
                              if ($_GET['kelas']==$k['kode_kelas']){
                                echo "<option value='$k[kode_kelas]' selected>$k[kode_kelas] - $k[nama_kelas]</option>";
                              }else{
                                echo "<option value='$k[kode_kelas]'>$k[kode_kelas] - $k[nama_kelas]</option>";
                              }
                            }
                        ?>
                    </select>
                    <?php if (isset($_GET['kelas']) AND isset($_GET['tahun'])){ ?>
                    <select name='mapel' style='padding:4px'>
                        <?php 
                            echo "<option value=''>- Pilih Mata Pelajaran -</option>";
                            $mapel = mysql_query("SELECT distinct b.kode_pelajaran, b.namamatapelajaran 
                                                FROM rb_jadwal_pelajaran a 
                                                JOIN rb_mata_pelajaran b ON a.kode_pelajaran=b.kode_pelajaran 
                                                WHERE a.kode_kelas='$_GET[kelas]' 
                                                AND a.id_tahun_akademik='$_GET[tahun]'");
                            while ($m = mysql_fetch_array($mapel)){
                              if ($_GET['mapel']==$m['kode_pelajaran']){
                                echo "<option value='$m[kode_pelajaran]' selected>$m[namamatapelajaran]</option>";
                              }else{
                                echo "<option value='$m[kode_pelajaran]'>$m[namamatapelajaran]</option>";
                              }
                            }
                        ?>
                    </select>
                    <?php } ?>
                    <input type="submit" style='margin-top:-4px' class='btn btn-success btn-sm' value='Lihat'>
                  </form>
                </div><!-- /.box-header -->
                <div class="box-body" style="overflow-x:auto;">
                <?php
                if (isset($_GET['kelas']) AND isset($_GET['tahun']) AND isset($_GET['mapel'])){
                    $d = mysql_fetch_array(mysql_query("SELECT * FROM rb_mata_pelajaran where kode_pelajaran='$_GET[mapel]'"));
                    $kkm = $d['kkm'];

                    echo "<div class='alert alert-info'><b>Mata Pelajaran:</b> $d[namamatapelajaran] <br> <b>KKM:</b> $kkm</div>";
                    echo "<table id='example1' class='table table-bordered table-striped table-condensed'>
                            <thead>
                                <tr>
                                    <th style='width:40px'>No</th>
                                    <th>Nama Siswa</th>
                                    <th>Nilai Tugas 1</th>
                                    <th>Nilai Tugas 2</th>
                                    <th>Rata-rata Nilai</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>";
                    
                    // Get Students
                    $siswa = mysql_query("SELECT * FROM rb_siswa where kode_kelas='$_GET[kelas]' ORDER BY nama ASC");
                    $no = 1;
                    
                    // Get Schedule ID
                    $jdwl = mysql_fetch_array(mysql_query("SELECT kodejdwl FROM rb_jadwal_pelajaran 
                                                             WHERE kode_kelas='$_GET[kelas]' 
                                                             AND id_tahun_akademik='$_GET[tahun]' 
                                                             AND kode_pelajaran='$_GET[mapel]' LIMIT 1"));
                    
                    while($s = mysql_fetch_array($siswa)){
                        $nilai1 = 0;
                        $nilai2 = 0;
                        $rata = 0;
                        $status = "Tidak Tuntas";

                        if ($jdwl){
                             $n = mysql_fetch_array(mysql_query("SELECT * FROM rb_nilai_pengetahuan where kodejdwl='$jdwl[kodejdwl]' AND nisn='$s[nisn]'"));
                             $nilai1 = $n['nilai1'];
                             $nilai2 = $n['nilai2'];
                             
                             // Calculate Average (assuming only these two matter for this view, or user wants avg of these 2)
                             // Or should it be avg of ALL grades? User said "rata rata nilai". 
                             // Usually "rata rata" implies the final average. 
                             // But let's calculate based on what we show if that's the context. 
                             // However, if there are more grades in DB, (n1+n2)/2 might be misleading if n3 exists.
                             // But checking the previous request "isinya ada kolom nama siswa, kolom nilai tugas 1, tugas 2, rata rata nilai dan status"
                             // I will use (nilai1 + nilai2)/2 for now as it aligns with the displayed columns.
                             if($nilai1 > 0 || $nilai2 > 0) {
                                $rata = ($nilai1 + $nilai2) / 2;
                             }
                        }

                        $status = ($rata >= $kkm) ? "<span style='color:green'>Tuntas</span>" : "<span style='color:red'>Tidak Tuntas</span>";

                        echo "<tr>
                                <td>$no</td>
                                <td>$s[nama]</td>
                                <td align='center'>$nilai1</td>
                                <td align='center'>$nilai2</td>
                                <td align='center'>".number_format($rata,1)."</td>
                                <td align='center'>$status</td>
                              </tr>";
                        $no++;
                    }
                    echo "</tbody>
                        </table>";

                } elseif (isset($_GET['kelas']) AND isset($_GET['tahun'])){
                    echo "<center style='padding:60px; color:orange'>Silahkan Pilih Mata Pelajaran Terlebih Dahulu...</center>";
                } else {
                    echo "<center style='padding:60px; color:red'>Silahkan Memilih Tahun akademik dan Kelas Terlebih dahulu...</center>";
                }
                ?>
                </div><!-- /.box-body -->
              </div>
            </div>
<?php } ?>
