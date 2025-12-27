<?php 
$get_tahun = isset($_GET['tahun']) ? $_GET['tahun'] : '';
$get_guru = isset($_GET['guru']) ? $_GET['guru'] : '';
$get_periode = isset($_GET['periode']) ? $_GET['periode'] : 'bulan_ini';

cek_session_admin();

// Determine date range based on period
$date_condition = "";
if ($get_periode == 'bulan_ini') {
    $date_condition = "AND MONTH(tanggal) = MONTH(CURDATE()) AND YEAR(tanggal) = YEAR(CURDATE())";
} elseif ($get_periode == 'minggu_ini') {
    $date_condition = "AND WEEK(tanggal) = WEEK(CURDATE()) AND YEAR(tanggal) = YEAR(CURDATE())";
} elseif ($get_periode == 'hari_ini') {
    $date_condition = "AND DATE(tanggal) = CURDATE()";
}
?>
            <div class="col-xs-12">  
              <div class="box">
                <div class="box-header">
                  <h3 class="box-title">Rekap Kegiatan Guru</h3>
                  <form style='margin-right:5px; margin-top:0px' class='pull-right' action='' method='GET'>
                    <input type="hidden" name='view' value='adminrekapkegiatan'>
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
                            echo "<option value=''>- Semua Guru -</option>";
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
                    <select name='periode' style='padding:4px'>
                        <option value='hari_ini' <?php echo ($get_periode=='hari_ini')?'selected':''; ?>>Hari Ini</option>
                        <option value='minggu_ini' <?php echo ($get_periode=='minggu_ini')?'selected':''; ?>>Minggu Ini</option>
                        <option value='bulan_ini' <?php echo ($get_periode=='bulan_ini')?'selected':''; ?>>Bulan Ini</option>
                        <option value='semua' <?php echo ($get_periode=='semua')?'selected':''; ?>>Semua</option>
                    </select>
                    <input type="submit" style='margin-top:-4px' class='btn btn-success btn-sm' value='Lihat'>
                  </form>

                </div><!-- /.box-header -->
                <div class="box-body">
                  <div class="row">
                    <div class="col-md-12">
                      <h4>Ringkasan Kegiatan</h4>
                    </div>
                  </div>
                  
                  <table id="example1" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th style='width:20px'>No</th>
                        <th>Tanggal</th>
                        <th>Nama Guru</th>
                        <th>Jenis Kegiatan</th>
                        <th>Detail</th>
                        <th>File</th>
                      </tr>
                    </thead>
                    <tbody>
                  <?php
                    $where_guru = "";
                    if ($get_guru != ''){
                        $where_guru = "AND c.nip='$get_guru'";
                    }
                    
                    $activities = array();
                    
                    // 1. Upload RPP
                    $rpp_query = "SELECT 'Upload RPP' as jenis, e.nama_file as detail, e.file_upload as file, c.nama_guru, 
                                  DATE(NOW()) as tanggal
                                  FROM rb_elearning1 e
                                  JOIN rb_jadwal_pelajaran a ON e.kodejdwl=a.kodejdwl
                                  JOIN rb_guru c ON a.nip=c.nip
                                  WHERE 1=1 $where_guru
                                  ORDER BY e.id_elearning DESC
                                  LIMIT 50";
                    $rpp_result = mysql_query($rpp_query);
                    while($r = mysql_fetch_array($rpp_result)){
                        $activities[] = $r;
                    }
                    
                    // 2. Input Jurnal
                    $jurnal_query = "SELECT 'Input Jurnal KBM' as jenis, CONCAT(j.hari, ' - ', j.materi) as detail, '' as file, c.nama_guru,
                                     j.tanggal
                                     FROM rb_journal_list j
                                     JOIN rb_jadwal_pelajaran a ON j.kodejdwl=a.kodejdwl
                                     JOIN rb_guru c ON a.nip=c.nip
                                     WHERE 1=1 $where_guru $date_condition
                                     ORDER BY j.tanggal DESC
                                     LIMIT 50";
                    $jurnal_result = mysql_query($jurnal_query);
                    while($r = mysql_fetch_array($jurnal_result)){
                        $activities[] = $r;
                    }
                    
                    // 3. Input Nilai
                    $nilai_query = "SELECT 'Input Nilai Tugas' as jenis, CONCAT('Nilai untuk tugas: ', e.nama_file) as detail, '' as file, c.nama_guru,
                                    DATE(NOW()) as tanggal
                                    FROM rb_elearning_jawab ej
                                    JOIN rb_elearning e ON ej.id_elearning=e.id_elearning
                                    JOIN rb_jadwal_pelajaran a ON e.kodejdwl=a.kodejdwl
                                    JOIN rb_guru c ON a.nip=c.nip
                                    WHERE ej.nilai > 0 $where_guru
                                    ORDER BY ej.id_elearning_jawab DESC
                                    LIMIT 50";
                    $nilai_result = mysql_query($nilai_query);
                    while($r = mysql_fetch_array($nilai_result)){
                        $activities[] = $r;
                    }
                    
                    // Sort by date
                    usort($activities, function($a, $b) {
                        return strtotime($b['tanggal']) - strtotime($a['tanggal']);
                    });
                    
                    $no = 1;
                    if(count($activities) > 0){
                        foreach($activities as $r){
                        $tgl = tgl_indo($r['tanggal']);
                        $file_btn = ($r['file'] != '') ? "<a class='btn btn-info btn-xs' href='download.php?file=$r[file]'><i class='fa fa-download'></i> Download</a>" : "-";
                        
                        echo "<tr><td>$no</td>
                                  <td>$tgl</td>
                                  <td>$r[nama_guru]</td>
                                  <td><span class='label label-primary'>$r[jenis]</span></td>
                                  <td>$r[detail]</td>
                                  <td>$file_btn</td>
                              </tr>";
                          $no++;
                          }
                    } else {
                        echo "<tr><td colspan='6' style='text-align:center; color:red'>Belum ada kegiatan</td></tr>";
                    }
                  ?>
                    </tbody>
                  </table>
                </div><!-- /.box-body -->
                </div>
            </div>
