<?php
cek_session_siswa();
$get_tahun = isset($_GET['tahun']) ? $_GET['tahun'] : '';
?>
<div class="col-xs-12">  
  <div class="box">
    <div class="box-header">
      <h3 class="box-title">Riwayat Absensi Saya</h3>
      <form style='margin-right:5px; margin-top:0px' class='pull-right' action='' method='GET'>
        <input type="hidden" name='view' value='absensisiswa'>
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
    </div>
    <div class="box-body">
      <table id="example1" class="table table-bordered table-striped">
        <thead>
          <tr>
            <th style='width:20px'>No</th>
            <th>Hari / Tanggal</th>
            <th>Mata Pelajaran</th>
            <th>Status</th>
            <th>Keterangan</th>
          </tr>
        </thead>
        <tbody>
      <?php
        if ($get_tahun != ''){
            $tahun_filter = "AND c.id_tahun_akademik='$get_tahun'";
        }else{
            $tahun_filter = ""; // Show all years if not selected
        }

        $session_id = $_SESSION['id'];
        $tampil = mysql_query("SELECT a.*, d.namamatapelajaran, c.hari, c.jam_mulai, e.nama_kehadiran, a.tanggal
                                FROM rb_absensi_siswa a 
                                LEFT JOIN rb_jadwal_pelajaran c ON a.kodejdwl=c.kodejdwl
                                LEFT JOIN rb_mata_pelajaran d ON c.kode_pelajaran=d.kode_pelajaran
                                JOIN rb_kehadiran e ON a.kode_kehadiran=e.kode_kehadiran
                                WHERE a.nisn='$session_id' 
                                $tahun_filter
                                ORDER BY a.tanggal DESC");
        
        $no = 1;
        while($r=mysql_fetch_array($tampil)){
            $hari_ind = tgl_indo($r['tanggal']); // Using standard function if avail
             
            // If function returns date like 20 Jan 2024, maybe pre-pend Day name if possible?
            // Assuming tgl_indo formats it nicely.
            
            $color = "black";
            if ($r['kode_kehadiran'] == 'H') $color = "green";
            elseif ($r['kode_kehadiran'] == 'S') $color = "blue";
            elseif ($r['kode_kehadiran'] == 'I') $color = "orange";
            elseif ($r['kode_kehadiran'] == 'A') $color = "red";
            
            $mapel = $r['namamatapelajaran'] ? $r['namamatapelajaran'] : '-';

            echo "<tr><td>$no</td>
                      <td>".tgl_indo($r['tanggal'])."</td>
                      <td>$mapel</td>
                      <td><span style='color:$color'>$r[nama_kehadiran]</span></td>
                      <td>-</td>
                  </tr>";
            $no++;
        }
      ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
