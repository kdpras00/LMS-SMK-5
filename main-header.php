
        <!-- Logo -->
        <div align="left"><a href="index.php" class="logo">
          <!-- mini logo for sidebar mini 50x50 pixels -->
          <span class="logo-mini"></span>
          <!-- logo for regular state and mobile devices -->
          <span class="logo-lg"><img src="foto_pegawai/logosmkn5.jpg" width="32" /><b> NELITA | LMS </b></span>
        </a>
          <!-- Header Navbar: style can be found in header.less -->
        </div>
        <nav class="navbar navbar-static-top" role="navigation">
          <!-- Sidebar toggle button-->
          <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
          </a>

          <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
              <!-- User Account: style can be found in dropdown.less -->
              <li class="dropdown user user-menu">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                  <img src="<?php echo $foto; ?>" class="user-image" alt="User Image">
                  <span class="hidden-xs"><?php echo $nama; ?></span> <span class='caret'></span>
                </a>
                <ul class="dropdown-menu">
                  <!-- User image -->
                  <li class="user-header">
                    <img src="<?php echo $foto; ?>" class="img-circle" alt="User Image">
                    <p>
                      <?php echo $nama; ?>
                      <small><?php echo $level; ?></small>
                    </p>
                  </li>
                  <!-- Menu Body -->
                  
                  <!-- Menu Footer-->
                  <li class="user-footer">
                    <div class="pull-left">
                      <a href="index.php" class="btn btn-default btn-flat">Dashboard</a>
                    </div>
                    <div class="pull-right">
                      <?php 
                        if ($_SESSION['level']=='superuser'){
                            echo "<a href='index.php?view=admin&act=edit&id=$_SESSION[id]' class='btn btn-default btn-flat'>Edit Profile</a>";
                        }elseif($_SESSION['level']=='kepala'){
                            echo "<a href='index.php?view=admin&act=edit&id=$_SESSION[id]' class='btn btn-default btn-flat'>Edit Profile</a>";    
                        }elseif($_SESSION['level']=='guru'){
                            echo "<a href='index.php?view=guru&act=detailguru&id=$_SESSION[id]' class='btn btn-default btn-flat'>View Profile</a>";
                        }elseif($_SESSION['level']=='siswa'){
                            echo "<a href='index.php?view=siswa&act=detailsiswa&id=$_SESSION[id]' class='btn btn-default btn-flat'>View Profile</a>";
                        }
                      ?>
                    </div>
                  </li>
                </ul>
              </li>
              <li><a href="#" onclick="konfirmasiLogout('logout.php'); return false;">Logout</a></li>

                            <!-- Messages: style can be found in dropdown.less-->
           
            </ul>
          </div>
        </nav>