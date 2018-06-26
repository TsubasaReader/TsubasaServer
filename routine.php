<?php

if(file_exists("./config.php")){require_once("./routine/interface.php");}
else {
    die("config.php does not exist. Please click <a href=\"./install/\">here</a> to start the install script.");
}

?>