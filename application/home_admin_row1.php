            <a style='color:#000' href='index.php?view=siswa'>
            <div class="col-md-3 col-sm-6 col-xs-12">
              <div class="info-box">
                <span class="info-box-icon bg-aqua"><i class="fa fa-users"></i></span>
                <div class="info-box-content">
                <?php 
                  $siswa_query = mysql_query("SELECT count(*) as total FROM rb_siswa");
                  $siswa_total = 0;
                  if ($siswa_query) {
                      $siswa = mysql_fetch_array($siswa_query);
                      $siswa_total = isset($siswa['total']) ? $siswa['total'] : 0;
                  }
                ?>
                  <span class="info-box-text">Siswa</span>
                  <span class="info-box-number"><?php echo $siswa_total; ?></span>
                </div><!-- /.info-box-content -->
              </div><!-- /.info-box -->
            </div><!-- /.col -->
            </a>

            <a style='color:#000' href='index.php?view=guru'>
            <div class="col-md-3 col-sm-6 col-xs-12">
              <div class="info-box">
                <span class="info-box-icon bg-green"><i class="fa fa-user"></i></span>
                <div class="info-box-content">
                <?php 
                  $guru_query = mysql_query("SELECT count(*) as total FROM rb_guru");
                  $guru_total = 0;
                  if ($guru_query) {
                    $guru = mysql_fetch_array($guru_query);
                    $guru_total = isset($guru['total']) ? $guru['total'] : 0;
                  }
                ?>
                  <span class="info-box-text">Guru</span>
                  <span class="info-box-number"><?php echo $guru_total; ?></span>
                </div><!-- /.info-box-content -->
              </div><!-- /.info-box -->
            </div><!-- /.col -->
            </a>

            <a style='color:#000' href='index.php'>
            <div class="col-md-3 col-sm-6 col-xs-12">
              <div class="info-box">
                <span class="info-box-icon bg-yellow"><i class="fa fa-files-o"></i></span>
                <div class="info-box-content">
                <?php 
                  $upload_query = mysql_query("SELECT count(*) as total FROM rb_elearning");
                  $upload_total = 0;
                  if ($upload_query) {
                    $upload = mysql_fetch_array($upload_query);
                    $upload_total = isset($upload['total']) ? $upload['total'] : 0;
                  }
                ?>
                  <span class="info-box-text">Uploads</span>
                  <span class="info-box-number"><?php echo $upload_total; ?></span>
                </div><!-- /.info-box-content -->
              </div><!-- /.info-box -->
            </div><!-- /.col -->
            </a>

            <a style='color:#000' href='index.php?view=forum'>
            <div class="col-md-3 col-sm-6 col-xs-12">
              <div class="info-box">
                <span class="info-box-icon bg-red"><i class="fa fa-star-o"></i></span>
                <div class="info-box-content">
                <?php 
                  $forum_query = mysql_query("SELECT count(*) as total FROM rb_forum_topic");
                  $forum_total = 0;
                  if ($forum_query) {
                    $forum = mysql_fetch_array($forum_query);
                    $forum_total = isset($forum['total']) ? $forum['total'] : 0;
                  }
                ?>
                  <span class="info-box-text">Forum</span>
                  <span class="info-box-number"><?php echo $forum_total; ?></span>
                </div><!-- /.info-box-content -->
              </div><!-- /.info-box -->
            </div><!-- /.col -->
            </a>