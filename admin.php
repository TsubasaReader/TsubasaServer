<?php
require_once 'routine.php';
require_once 'renderer.php';
require_once 'server/interface.php';

error_reporting(E_ALL & ~E_NOTICE);
?>
<html>
    <head>
      <meta charset="UTF-8" />
    <?php
    dispBackstageStylesheetLink();
    ?>
    </head><body>
<h1>Backstage</h1>
<div class="navlink">
<a href="./index.php">Main site</a>
<?php
if($_COOKIE["session_id"]&&hashvalValid()) {
  dispManagementNavbar();
}
dispSectionDivider();
?>
</div><br />
<br />
<?php
if(!((strcmp($_REQUEST["method"],"login")==0)||$_COOKIE["session_id"])) {
  dispLoginForm();
} else if(strcmp($_REQUEST["method"],"login")==0) {
  if(login($_REQUEST["login_name"],$_REQUEST["login_password"]))
    echo "You're now logged in. Click <a href=\"./admin.php\">here</a> to reload.";
} else if(strcmp($_REQUEST["method"],"logout")==0) {
  logout();
  echo 'You\'re now logged out. Click <a href="./admin.php">here</a> to go back.';
} else if(hashvalValid()) {
  if(!$_REQUEST["mode"]) {
    dispNewBlogpostForm();
  } else if(strcmp($_REQUEST["mode"],"preview")==0) {
    echo '
    Preview: <br /> ' . $_REQUEST["body"] . '<hr />
    ';
    dispBlogpostEditingFormWith($_REQUEST["title"],$_REQUEST["body"]);
  } else if(strcmp($_REQUEST["mode"],"post")==0) {
      insertBlogpost(retrCurrentUserID(),$_REQUEST["title"],$_REQUEST["body"]);
      echo 'Posted.<br />';
      dispNewBlogpostForm();
  } else if(strcmp($_REQUEST["mode"],"manage")==0) {
      $conn = startConn();
      if(!$_REQUEST["id"]) {
        echo '<div class="body"><ul>';
        forBlogposts_raw($conn,'dispBlogpostManagementLink');
        echo '</ul></div>';
      } else {
        echo '<a href="./admin.php?mode=manage&id=' . $_REQUEST["id"] . '&submod=delete">Delete post</a> ';
        echo '<a href="./admin.php?mode=manage&id=' . $_REQUEST["id"] . '&submod=commentm">Manage Comments</a><br />';
        if(!$_REQUEST["submod"]) {
          forBlogpostWithID_raw($conn,intval($_REQUEST["id"]),'dispBlogpostEditingForm');
        } else if(strcmp($_REQUEST["submod"],"delete")==0) {
            if(commentExistsUnderPost(intval($_REQUEST["id"]))) {
                echo 'Comments exists. Delete all comments under this post?<br />';
                echo '<a href="./admin.php?mode=manage&id=' . $_REQUEST["id"] . '&submod=forcedelete">Yes</a> ';
                echo '<a href="./admin.php?mode=mangage&id=' . $_REQUEST["id"] . '">Back</a>';
            } else {
              deleteBlogpost(intval($_REQUEST["id"]));
              echo '
                ID' . $_REQUEST["id"] . ' deleted.<br />
              ';
            }
        } else if(strcmp($_REQUEST["submod"],"forcedelete")==0) {
            forCommentsAtBlogID(intval($_REQUEST["id"]),
              function($commentid,$blogpostid,$date,$name,$body) {
                deleteCommentByID(intval($commentid));
              });
            deleteBlogpost(intval($_REQUEST["id"]));
            echo '
                ID' . $_REQUEST["id"] . ' deleted.<br />
            ';
        } else if(strcmp($_REQUEST["submod"],"editpost")==0) {
          modifyBlogpost_raw($conn,intval($_REQUEST["id"]),$_REQUEST["title"],$_REQUEST["body"]);
          echo '
            ID' . $_REQUEST["id"] . ' modified.<br />
          ';
        } else if(strcmp($_REQUEST["submod"],"commentm")==0) {
          dispCommentManagementWrapperWith(intval($_REQUEST["id"]),'dispCommentManagementLink');
        }
      }
      $conn->close();
  } else if(strcmp($_REQUEST["mode"],"commentm")==0) {
      echo '<div class="navlink">';
      echo '<a href="./admin.php?mode=commentm&id=' . $_REQUEST["id"] . '&submod=delete">Delete comment</a></div>';
      echo '<br />';
      if(!$_REQUEST["submod"]) {
        forCommentWithID(intval($_REQUEST["id"]),dispCommentEditingForm);
      } else if(strcmp($_REQUEST["submod"],"delete")==0) {
          $blogpostid = retrBlogpostIDWithCommentID(intval($_REQUEST["id"]));
          deleteCommentByID(intval($_REQUEST["id"]));
          echo 'CID' . $_REQUEST["id"] . ' deleted.<br />';
          echo '<a href="./admin.php?mode=manage&id=' . $blogpostid . '&submod=commentm">Back</a>';
      } else if(strcmp($_REQUEST["submod"],"edit")==0) {
          modifyCommentByID(intval($_REQUEST["id"]),date("Y-m-d H:i:s"),$_REQUEST["comment_name"],$_REQUEST["comment_body"]);
          echo 'CID' . $_REQUEST["id"] . ' modified.<br />';
          echo '<a href="./admin.php?mode=commentm&id=' . $_REQUEST["id"] . '">Back</a>';
      }
  } else if(strcmp($_REQUEST["mode"],"userm")==0) {
    echo '<a href="./admin.php?mode=userm&submod=newuser">New user</a><br />';
    if(!(strcmp($_REQUEST["submod"],"newuser")==0||$_REQUEST["id"])) {
      echo '<ul>';
      forUsers('dispUserManagementLink');
      echo '</ul>';
    } else if(strcmp($_REQUEST["submod"],"newuser")==0) {
      if((!$_REQUEST["state"])) {
        dispNewUserForm_Backstage();
      } else if(strcmp($_REQUEST["state"],"phase1")==0) {
        if(regUser($_REQUEST["newuser_name"],$_REQUEST["newuser_password"])) {
          $newuserID = retrUserIDByName($_REQUEST["newuser_name"]);
          echo 'New user created, UID' . $newuserID . '.<br />';
          echo 'Click <a href="./admin.php?mode=userm">here</a> to go back, ';
          echo 'or click <a href="./admin.php?mode=userm&id=' . $newuserID . '">here</a> to edit this user.';
        } else {
          echo 'User already exists. ';
          echo 'Click <a href="./admin.php?mode=userm">here</a> to go back.';
        }
      }
    } else if(strcmp($_REQUEST["submod"],"deluser")==0) {
      deleteUserByID(intval($_REQUEST["id"]));
      echo 'UID' . $_REQUEST["id"] . ' deleted. ';
      echo 'Click <a href="./admin.php?mode=userm">here</a> to go back.';
    } else {
      if((!$_REQUEST["state"])) {
        dispUserEditingForm(intval($_REQUEST["id"]));
      } else if(strcmp($_REQUEST["state"],"phase1")==0) {
        modifyUserByID(intval($_REQUEST["id"]),$_REQUEST["user_name"],$_REQUEST["user_password"]);
        echo 'UID' . $_REQUEST["id"] . ' modified. ';
        echo 'Click <a href="./admin.php?mode=userm">here</a> to go back.';
      }
    }
  } else if(strcmp($_REQUEST["mode"],"backup")==0){
    echo 'Click <a href="./backup.php">here</a> to start the backup utility';
  } else if(strcmp($_REQUEST["mode"], "plugin") == 0) {

  }
}
?>
</body>
</html>