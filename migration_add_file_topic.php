<?php
include "config/koneksi.php";

$query = "ALTER TABLE rb_forum_topic ADD COLUMN file_topic VARCHAR(255) AFTER isi_topic";
$result = mysql_query($query);

if ($result) {
    echo "Successfully added 'file_topic' column to 'rb_forum_topic' table.";
} else {
    echo "Error or column might already exist: " . mysql_error();
}
?>
