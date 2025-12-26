<?php
cek_session_siswa();
?>
<div class="col-xs-12">  
  <div class="box">
    <div class="box-header">
      <h3 class="box-title">Laporan Nilai Saya</h3>
      <form style='margin-right:5px; margin-top:0px' class='pull-right' action='' method='GET'>
        <input type="hidden" name='view' value='laporannilai'>
        <select name='tahun' style='padding:4px'>
            <?php 
                echo "<option value=''>- Pilih Tahun Akademik -</option>";
                $tahun = mysql_query("SELECT * FROM rb_tahun_akademik");
                while ($k = mysql_fetch_array($tahun)){
                  if (isset($_GET['tahun']) && $_GET['tahun']==$k['id_tahun_akademik']){
                    echo "<option value='$k[id_tahun_akademik]' selected>$k[nama_tahun]</option>";
                  }else{
                    echo "<option value='$k[id_tahun_akademik]'>$k[nama_tahun]</option>";
                  }
                }
            ?>
        </select>
        <input type="submit" style='margin-top:-4px' class='btn btn-success btn-sm' value='Lihat'>
      </form>
    </div>
    <div class="box-body">
      <table id="example1" class="table table-bordered table-striped">
        <thead>
          <tr>
            <th style='width:20px'>No</th>
            <th>Mata Pelajaran</th>
            <th>KKM</th>
            <th>Jumlah Tugas</th>
            <th>Tugas Terkumpul</th>
            <th>Rata-rata Nilai</th>
            <th>Status Akhir</th>
          </tr>
        </thead>
        <tbody>
      <?php
        if (isset($_GET['tahun'])){
            $tahun_filter = "AND a.id_tahun_akademik='$_GET[tahun]'";
        }else{
            $tahun_filter = "AND a.id_tahun_akademik LIKE '".date('Y')."%'";
        }

        // Get Distinct Subjects for the Student's Class and Schedule
        $session_kode_kelas = $_SESSION['kode_kelas'];
        $session_id = $_SESSION['id'];
        
        $tampil = mysql_query("SELECT a.kodejdwl, b.namamatapelajaran, b.kkm 
                                FROM rb_jadwal_pelajaran a 
                                JOIN rb_mata_pelajaran b ON a.kode_pelajaran=b.kode_pelajaran
                                WHERE a.kode_kelas='$session_kode_kelas' 
                                $tahun_filter
                                ORDER BY b.namamatapelajaran ASC");
        
        if ($tampil) {
            $no = 1;
            while($r=mysql_fetch_array($tampil)){
                // Count Total Tasks
                $query_tugas = mysql_query("SELECT * FROM rb_elearning WHERE kodejdwl='$r[kodejdwl]' AND id_kategori_elearning != '1'");
                $total_tugas = ($query_tugas) ? mysql_num_rows($query_tugas) : 0;
                
                // Count Submitted & Average
                $q_nilai = mysql_query("SELECT l.nilai FROM rb_elearning_jawab l 
                                        JOIN rb_elearning e ON l.id_elearning=e.id_elearning 
                                        WHERE e.kodejdwl='$r[kodejdwl]' AND l.nisn='$session_id'");
                $terkumpul = ($q_nilai) ? mysql_num_rows($q_nilai) : 0;
                
                $total_nilai = 0;
                if ($q_nilai) {
                    while($n = mysql_fetch_array($q_nilai)){
                        $total_nilai += $n['nilai'];
                    }
                }
                
                if ($terkumpul > 0){
                    $rata = $total_nilai / $terkumpul;
                } else {
                    $rata = 0;
                }
    
                $status = ($rata >= $r['kkm']) ? "<span style='color:green'>Tuntas</span>" : "<span style='color:red'>Tidak Tuntas</span>";
    
                echo "<tr><td>$no</td>
                          <td>$r[namamatapelajaran]</td>
                          <td>$r[kkm]</td>
                          <td>$total_tugas</td>
                          <td>$terkumpul</td>
                          <td>".number_format($rata, 1)."</td>
                          <td>$status</td>
                      </tr>";
                $no++;
            }
        }
      ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
