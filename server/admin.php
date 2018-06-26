<b>TsubasaServer Server Config</b><br />
<a href="../admin.php">Back to backstage</a> | <a href="./admin.php?mode=basic">Basic settings</a> | <a href="./admin.php?mode=plugin">Plugin management</a><hr />
<?php
require_once('../config.php');

function startConn() {
    global $database_server, $database_port, $database_name, $database_username, $database_password;
    if(!($conn=mysqli_connect($database_server,$database_username,$database_password,$database_name,$database_port))) {
        die("Failed to establish a connection:<br />" . mysqli_connect_error());
    } else return $conn;
}
function setBlogName($newBlogName){
    $conn = startConn();
    $cmd = mysqli_prepare($conn,"CALL SET_BLOGNAME(?)");
    $cmd->bind_param('s',$newBlogName);
    $cmd->execute();
    $cmd->close();
    $conn->close();
}
function retrBlogname() {
    $conn = startConn();
    $query = mysqli_prepare($conn, "CALL RETRIEVE_CURRENT_BLOGNAME()");
    $query->execute();
    $query->bind_result($res);
    $query->fetch();
    $query->close();
    $conn->close();
    return $res;
}
function retrWelcomeMessage() {
    $conn = startConn();
    $query = mysqli_prepare($conn, "CALL RETRIEVE_WELCOME_MESSAGE()");
    $query->execute(); $query->bind_result($res); $query->fetch();
    $query->close(); $conn->close();
    return $res;
}
function setWelcomeMessage($newWelcomeMessage) {
    $conn = startConn();
    $cmd = mysqli_prepare($conn, "CALL SET_WELCOME_MESSAGE(?)");
    $cmd->bind_param('s',$newWelcomeMessage);
    $cmd->execute();
    $cmd->close();
    $conn->close();
}

function dispServerSettingForm() {
    echo '
    <form method="post" action="./admin.php">
    <input type="hidden" name="mode" value="basic" />
    <input type="hidden" name="submod" value="set" />
    Server name: <input type="text" name="servername" value="' . retrBlogname() . '" /><br />
    Welcome message: <textarea name="welcomemsg">' . retrWelcomeMessage() . '</textarea><br />
    <input type="submit" value="Submit" />
    </form>
    ';
}
function hashvalValid() {
    return checkSession(intval($_COOKIE["user_id"]),$_COOKIE["session_id"]);
}
function checkSession_raw($conn,$userid,$hashval) {
    $query = mysqli_prepare($conn,"CALL CHECK_HASHVAL(?,?)");
    $query->bind_param('is',$userid,$hashval);
    $query->execute();
    $query->bind_result($res);
    $query->fetch();
    $query->close();
    return $res;
} function checkSession($userid,$hashval) {
    $conn = startConn();
    $res = checkSession_raw($conn,$userid,$hashval);
    $conn->close();
    return $res;
}
function dispInstallNewPluginForm() {
    echo '
    <form action="./admin.php" method="post" enctype="multipart/form-data">
    <input type="hidden" name="mode" value="plugin" />
    <input type="hidden" name="action" value="install" />
    Plugin name (all lowercase): <input name="plugin-name" /><br />
    Plugin file: <input type="file" name="plugin-file" /><br />
    <input type="submit" value="Upload" />
    </form>
    Please note that the plugin name will be the name of the directory where the plugin file lives in.
    ';
}
function deltree($dir) {
    if(is_dir($dir)) {
        foreach(scandir($dir) as $subx) {
            if(strcmp($subx, '.') == 0 || strcmp($subx, '..') == 0) continue;
            else deltree($dir.'/'.$subx);
        }
        rmdir($dir);
    } else unlink($dir);
}
if(isset($_COOKIE['user_id']) && isset($_COOKIE['session_id']) && hashvalValid()) {
    if(isset($_REQUEST['mode'])) {
        if(strcmp($_REQUEST['mode'], 'basic') == 0) {
            if(isset($_REQUEST['submod'])) {
                if(strcmp($_REQUEST['submod'], 'set') == 0) {
                    setBlogname($_REQUEST['servername']);
                    setWelcomeMessage($_REQUEST['welcomemsg']);
                    echo 'done.<br />';
                }
            }
            dispServerSettingForm();
        } else if(strcmp($_REQUEST['mode'], 'plugin') == 0) {
            if(isset($_REQUEST['action'])) {
                if(strcmp($_REQUEST['action'], 'delete') == 0) {
                    if(isset($_REQUEST['id'])) {
                        deltree('../plugin/'.$_REQUEST['id']);
                        echo 'done.';
                    }
                } else if(strcmp($_REQUEST['action'], 'install') == 0) {
                    $dirName = '../plugin/'.$_REQUEST['plugin-name'];
                    if(!mkdir($dirName)) {
                        die('failed to create dir.');
                    }
                    // write name.
                    if(!($nameFile = fopen($dirName.'/NAME', 'w+'))) {
                        die('failed to open a NAME file.');
                    }
                    if(!fwrite($nameFile, $_REQUEST['plugin-name'])) {
                        die('failed to write the NAME file.');
                    }
                    if(!fclose($nameFile)) {
                        die('failed to close the file.');
                    }
                    move_uploaded_file($_FILES['plugin-file']['tmp_name'], '../plugin/'.$_REQUEST['plugin-name'].'/retrieva.php');
                    echo 'done.';
                }
            }
            echo 'Install new plugin: <br />';
            dispInstallNewPluginForm();
            echo '<ul>';
            $pluginArray = scandir('../plugin/');
            for($i = 0; $i < count($pluginArray); $i++) {
                if(strcmp($pluginArray[$i], '.') == 0
                || strcmp($pluginArray[$i], '..') == 0) {
                    continue;
                }
                echo '<li><a href="./admin.php?mode=plugin&action=delete&id=' . $pluginArray[$i] . '">Delete plugin ' . $pluginArray[$i] . '</a></li>';
            }
            echo '</ul>';
        }
    }
} else {
    echo 'wrong login information. Click <a href="../admin.php">here</a> to login (again).';
}
?>