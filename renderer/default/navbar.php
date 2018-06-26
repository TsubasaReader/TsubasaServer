<?php

function dispManagementNavbar() {
  echo '<a href="./admin.php">New post</a> ';
  echo '<a href="./server/admin.php">Server settings</a> ';
  echo '<a href="./admin.php?mode=manage">Manage posts</a> ';
  echo '<a href="./admin.php?mode=userm">Manage users</a> ';
  echo '<a href="./admin.php?mode=backup">Backup</a> ';
  echo '<a href="./admin.php?method=logout">Logout</a>';
}

function dispMainsiteNavbar() {
  echo '
  <div id="navbar">
  <a style="color:#ff0000" href="./index.php">Main page</a>
  <a style="color:#ff0000" href="./admin.php">Admin</a>
  </div>
  ';
}


?>