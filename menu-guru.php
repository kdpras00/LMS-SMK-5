<section class="sidebar">
          <!-- Sidebar user panel -->
          <div class="user-panel">
            <div class="pull-left image">
              <img src="<?php echo $foto; ?>" class="img-circle" alt="User Image">
            </div>
            <div class="pull-left info">
              <p><?php echo $nama; ?></p>
              <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
          </div>

          <!-- sidebar menu: : style can be found in sidebar.less -->
          <ul class="sidebar-menu">
            <li class="header" style='color:#fff; text-transform:uppercase; border-bottom:2px solid #00c0ef'>MENU <?php echo $level; ?></li>
            <li><a href="index.php"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a></li>
            
            <li class="treeview">
              <a href="javascript:void(0)"><i class="fa fa-book"></i> <span>Akademik</span><i class="fa fa-angle-left pull-right"></i></a>
              <ul class="treeview-menu">
                <li><a href="index.php?view=kompetensidasar&act=listguru"><i class="fa fa-circle-o"></i> Data Kompetensi Dasar</a></li>
                <li><a href="index.php?view=bahantugas&act=listbahantugasguru"><i class="fa fa-circle-o"></i> Materi & Tugas</a></li>
              </ul>
            </li>
            
            <li><a href="index.php?view=uploadrpp"><i class="fa fa-file-pdf-o"></i> <span>Upload RPP</span></a></li>
            
            <li class="treeview">
              <a href="javascript:void(0)"><i class="fa fa-calendar"></i> <span>KBM</span><i class="fa fa-angle-left pull-right"></i></a>
              <ul class="treeview-menu">
                <li><a href="index.php?view=jadwalguru"><i class="fa fa-circle-o"></i> Jadwal Pelajaran</a></li>
                <li><a href="index.php?view=journalguru"><i class="fa fa-circle-o"></i> Isi Jurnal KBM</a></li>
              </ul>
            </li>
            
            <li class="treeview">
              <a href="javascript:void(0)"><i class="fa fa-check-square"></i> <span>Nilai Siswa</span><i class="fa fa-angle-left pull-right"></i></a>
              <ul class="treeview-menu">
                <li><a href="index.php?view=koreksitugas"><i class="fa fa-circle-o"></i> Koreksi Tugas</a></li>
                <li><a href="index.php?view=rekapnilai"><i class="fa fa-circle-o"></i> Rekap Nilai</a></li>
              </ul>
            </li>
            
            <!--
            <li><a href="index.php?view=kompetensiguru"><i class="fa fa-tags"></i> <span>Kompetensi Dasar</span></a></li>
            <li><a href="index.php?view=journalkbm"><i class="fa fa-tags"></i> <span>Jurnal KBM</span></a></li>
            <li><a href="index.php?view=soal&act=detailguru"><i class="fa fa-users"></i><span>Quiz / Ujian Online</span></a></li>
            -->
            
            

            
            
           
        </section>