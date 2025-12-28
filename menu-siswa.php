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
            <li><a href="index.php?view=home"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a></li>
            <li class="treeview">
              <a href="javascript:void(0)"><i class="fa fa-calendar"></i> <span>Akademik</span><i class="fa fa-angle-left pull-right"></i></a>
              <ul class="treeview-menu">
                <li><a href="index.php?view=jadwalsiswa"><i class="fa fa-circle-o"></i> Jadwal Pelajaran</a></li>
                <li><a href="index.php?view=bahantugas&act=listbahantugassiswa"><i class="fa fa-circle-o"></i> Materi & Tugas</a></li>
              </ul>
            </li>
            <li><a href="index.php?view=absensisiswa"><i class="fa fa-th-large"></i> <span>Riwayat Absensi</span></a></li>
            <li><a href="index.php?view=laporannilai"><i class="fa fa-file-text"></i> <span>Laporan Nilai</span></a></li>
           
        </section>
       