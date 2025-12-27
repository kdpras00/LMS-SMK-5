<?php
if ($_GET['act'] == '') {
?>
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">Rekap Data Kegiatan Guru - Semua Data</h3>
                <form style='margin-right:5px; margin-top:0px' class='pull-right' action='' method='GET'>
                    <input type="hidden" name='view' value='rekapkegiatan'>
                    <select name='tahun' style='padding:4px'>
                        <?php
                        echo "<option value=''>- Pilih Tahun Akademik -</option>";
                        $tahun = mysql_query("SELECT * FROM rb_tahun_akademik");
                        while ($k = mysql_fetch_array($tahun)) {
                            if ($_GET['tahun'] == $k['id_tahun_akademik']) {
                                echo "<option value='$k[id_tahun_akademik]' selected>$k[nama_tahun]</option>";
                            } else {
                                echo "<option value='$k[id_tahun_akademik]'>$k[nama_tahun]</option>";
                            }
                        }
                        ?>
                    </select>
                    <input type="submit" style='margin-top:-4px' class='btn btn-success btn-sm' value='Lihat'>
                </form>
            </div><!-- /.box-header -->
            <div class="box-body">
                <?php
                if (isset($_GET['tahun'])) {
                    echo "<div class='pull-right' style='margin-bottom:5px'>
                            <a target='_blank' href='index.php?view=cetakrekapkegiatan&tahun=$_GET[tahun]' class='btn btn-primary btn-sm'><i class='fa fa-print'></i> Cetak Laporan</a>
                          </div>";
                }
                ?>
                <table id="example1" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th style='width:20px'>No</th>
                            <th>Nama Guru</th>
                            <th>Mata Pelajaran</th>
                            <th>Kelas</th>
                            <th>File RPP</th>
                            <th>Jml Tugas</th>
                            <th>Keaktifan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Build filter condition
                        $filter_condition = "1=1";
                        if (isset($_GET['tahun']) && $_GET['tahun'] != '') {
                            $filter_condition = "a.id_tahun_akademik='$_GET[tahun]'";
                        }
                        
                        // Query jadwal pelajaran to get the list of active teaching schedules
                        $tampil = mysql_query("SELECT a.kodejdwl, a.nip, a.kode_pelajaran, a.kode_kelas, 
                                                b.nama_guru, c.namamatapelajaran, d.nama_kelas 
                                                FROM rb_jadwal_pelajaran a 
                                                JOIN rb_guru b ON a.nip=b.nip 
                                                JOIN rb_mata_pelajaran c ON a.kode_pelajaran=c.kode_pelajaran
                                                JOIN rb_kelas d ON a.kode_kelas=d.kode_kelas
                                                WHERE $filter_condition 
                                                ORDER BY b.nama_guru ASC");
                        $no = 1;
                        while ($r = mysql_fetch_array($tampil)) {
                            // 1. Check File RPP (Category 1)
                            // Assuming '1' is the category ID for Bahan/RPP as verified
                            $cek_rpp = mysql_num_rows(mysql_query("SELECT * FROM rb_elearning WHERE kodejdwl='$r[kodejdwl]' AND id_kategori_elearning='1'"));
                            $status_rpp = ($cek_rpp > 0) ? "<span class='label label-success'>Ada ($cek_rpp)</span>" : "<span class='label label-danger'>Tidak Ada</span>";

                            // 2. Count Jumlah Tugas (Category 2)
                            $jml_tugas = mysql_num_rows(mysql_query("SELECT * FROM rb_elearning WHERE kodejdwl='$r[kodejdwl]' AND id_kategori_elearning='2'"));

                            // 3. Persentase Keaktifan
                            // Logic: Based on Journal Entries vs Standard Meetings (e.g., 16 meetings per semester)
                            // Or maybe just based on number of journals filled.
                            // Let's try to count journal entries.
                            $jml_journal = mysql_num_rows(mysql_query("SELECT * FROM rb_journal_list WHERE kodejdwl='$r[kodejdwl]'"));
                            
                            // Assuming target is 16 meetings per semester ? Or should we use dynamic target?
                            // For now, let's use a standard 16.
                            $target_absen = 16; 
                            $persentase = ($jml_journal / $target_absen) * 100;
                            if ($persentase > 100) $persentase = 100;
                            
                            echo "<tr>
                                    <td>$no</td>
                                    <td>$r[nama_guru]</td>
                                    <td>$r[namamatapelajaran]</td>
                                    <td>$r[nama_kelas]</td>
                                    <td align='center'>$status_rpp</td>
                                    <td align='center'>$jml_tugas</td>
                                    <td align='center'>".number_format($persentase,0)."% ($jml_journal/$target_absen)</td>
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
