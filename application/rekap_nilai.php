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
                    <input type="submit" style='margin-top:-4px' class='btn btn-success btn-sm' value='Lihat'>
                  </form>
                </div><!-- /.box-header -->
                <div class="box-body" style="overflow-x:auto;">
                <?php
                if (isset($_GET['kelas']) AND isset($_GET['tahun'])){
                    // Get All Subjects for this Class/Year (based on Curriculum)
                    // Logic derived from print-hal4.php
                    // We need distinct subjects attached to the schedule for this class & year
                     $mapel_query = mysql_query("SELECT distinct b.kode_pelajaran, b.namamatapelajaran, b.kkm 
                                                FROM rb_jadwal_pelajaran a 
                                                JOIN rb_mata_pelajaran b ON a.kode_pelajaran=b.kode_pelajaran 
                                                WHERE a.kode_kelas='$_GET[kelas]' 
                                                AND a.id_tahun_akademik='$_GET[tahun]'
                                                ORDER BY b.namamatapelajaran ASC");
                    
                    $subjects = array();
                    while($row = mysql_fetch_assoc($mapel_query)){
                        $subjects[] = $row;
                    }

                    if (count($subjects) > 0){
                        echo "<a href='application/rekap_nilai_export.php?tahun=$_GET[tahun]&kelas=$_GET[kelas]' target='_blank' class='btn btn-primary btn-sm' style='margin-bottom:10px'><i class='fa fa-file-excel-o'></i> Export Excel</a>";
                        echo "<table id='example1' class='table table-bordered table-striped table-condensed'>
                                <thead>
                                    <tr>
                                        <th style='width:40px'>No</th>
                                        <th>NISN</th>
                                        <th>Nama Siswa</th>";
                                        foreach($subjects as $subj){
                                            echo "<th title='$subj[namamatapelajaran] ($subj[kode_pelajaran])' style='text-align:center; min-width:80px'>$subj[namamatapelajaran]</th>";
                                        }
                        echo "      </tr>
                                </thead>
                                <tbody>";
                        
                        // Get Students
                        $siswa = mysql_query("SELECT * FROM rb_siswa where kode_kelas='$_GET[kelas]' ORDER BY nama ASC");
                        $no = 1;
                        while($s = mysql_fetch_array($siswa)){
                            echo "<tr>
                                    <td>$no</td>
                                    <td>$s[nisn]</td>
                                    <td>$s[nama]</td>";
                            
                            foreach($subjects as $subj){
                                // Get Grade for this Student + Subject
                                // Logic: Find schedule ID (kodejdwl) first? Or directly query grades if linked by mapel?
                                // rb_nilai_pengetahuan links via kodejdwl.
                                // We need to find the kodejdwl for this class + mapel + tahun.
                                
                                $jdwl = mysql_fetch_array(mysql_query("SELECT kodejdwl FROM rb_jadwal_pelajaran 
                                                                         WHERE kode_kelas='$_GET[kelas]' 
                                                                         AND id_tahun_akademik='$_GET[tahun]' 
                                                                         AND kode_pelajaran='$subj[kode_pelajaran]' LIMIT 1"));
                                
                                $nilai = 0;
                                if ($jdwl){
                                    // Average of 5 values usually
                                    $n = mysql_fetch_array(mysql_query("SELECT sum((nilai1+nilai2+nilai3+nilai4+nilai5)/5)/count(nisn) as rata FROM rb_nilai_pengetahuan where kodejdwl='$jdwl[kodejdwl]' AND nisn='$s[nisn]'"));
                                    $nilai = number_format($n['rata']);
                                }
                                
                                echo "<td align='center'>$nilai</td>";
                            }

                            echo "</tr>";
                            $no++;
                        }
                        echo "</tbody>
                            </table>";
                    } else {
                         echo "<center style='padding:60px; color:red'>Belum ada Jadwal Pelajaran / Mata Pelajaran untuk filter ini.</center>";
                    }

                } else {
                    echo "<center style='padding:60px; color:red'>Silahkan Memilih Tahun akademik dan Kelas Terlebih dahulu...</center>";
                }
                ?>
                </div><!-- /.box-body -->
              </div>
            </div>
<?php } ?>
