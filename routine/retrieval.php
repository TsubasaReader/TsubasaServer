<?php

# Escape
# basic routine.


function startConn() {
    global $database_server, $database_port, $database_name, $database_username, $database_password;
    if(!($conn=mysqli_connect($database_server,$database_username,$database_password,$database_name,$database_port))) {
        die("Failed to establish a connection:<br />" . mysqli_connect_error());
    } else return $conn;
}

function forBlogposts_raw($conn, $callback) {
    $query = mysqli_prepare($conn,"CALL RETRIEVE_BLOGPOSTS()");
    $query->execute();
    $query->bind_result($id,$date,$userid,$title,$body);
    while($query->fetch()) {
        $callback($id,$date,$userid,$title,$body);
    } $query->close();
} function forBlogposts($callback) {
    $conn = startConn();
    forBlogposts_raw($conn,$callback);
    $conn->close();
}

function forBlogpostsRaw_raw($conn, $callback) {
    $query = mysqli_prepare($conn,"CALL RETRIEVE_BLOGPOSTS_RAW()");
    $query->execute();
    $query->bind_result($id,$date,$userid,$title,$body);
    while($query->fetch()) {
        $callback($id,$date,$userid,$title,$body);
    } $query->close();
} function forBlogpostsRaw($callback) {
    $conn = startConn();
    forBlogpostsRaw_raw($conn);
    $conn->close();
}

function forBlogpostWithID_raw($conn, $id, $callback) {
    $query = mysqli_prepare($conn,"CALL RETRIEVE_BLOGPOST_BY_ID(?)");
    $query->bind_param('i',$id);
    $query->execute();
    $query->bind_result($id,$date,$userid,$title,$body);
    $query->fetch();
    $callback($id,$date,$userid,$title,$body);
    $query->close();
} function forBlogpostWithID($id,$callback) {
    $conn = startConn();
    forBlogpostWithID_raw($conn,$id,$callback);
    $conn->close();
}

function forCommentsAtBlogID_raw($conn, $id, $callback) {
    $query = mysqli_prepare($conn,"CALL RETRIEVE_COMMENTS_AT(?)");
    $query->bind_param('i',$id);
    $query->execute();
    $query->bind_result($commentid,$blogpostid,$date,$name,$body);
    while($query->fetch()) {
        $callback($commentid,$blogpostid,$date,$name,$body);
    } $query->close();
} function forCommentsAtBlogID($id, $callback) {
    $conn = startConn();
    forCommentsAtBlogID_raw($conn, $id, $callback);
    $conn->close();
}

function forCommentWithID_raw($conn, $cid, $callback) {
    $query = mysqli_prepare($conn,"CALL RETRIEVE_COMMENT_BY_ID(?)");
    $query->bind_param('i',$cid);
    $query->execute();
    $query->bind_result($commentid,$blogpostid,$date,$name,$body);
    $query->fetch();
    $callback($commentid,$blogpostid,$date,$name,$body);
    $query->close();
} function forCommentWithID($cid,$callback) {
    $conn = startConn();
    forCommentWithID_raw($conn,$cid,$callback);
    $conn->close();
}

function forUsers_raw($conn, $callback) {
    $query = mysqli_prepare($conn,"CALL RETRIEVE_USERS()");
    $query->execute();
    $query->bind_result($userid,$username,$userpassword);
    while($query->fetch()) {
        $callback($userid,$username,$userpassword);
    } $query->close();
} function forUsers($callback) {
    $conn = startConn();
    forUsers_raw($conn,$callback);
    $conn->close();
}

function retrSiteAddr() {
    // in config.php.
    global $global_site_addr;
    return $global_site_addr;
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

function retrBlogpostTitle_raw($conn,$id) {
    $query = mysqli_prepare($conn, "CALL RETRIEVE_BLOGPOST_TITLE(?)");
    $query->bind_param('i',intval($id));
    $query->execute();
    $query->bind_result($title);
    $query->fetch();
    $query->close();
    return $title;
} function retrBlogpostTitle($id) {
    $conn = startConn();
    $res = retrBlogpostTitle_raw($conn,$id);
    $conn->close();
    return $res;
}

function retrBlogpostIDWithCommentID_raw($conn,$cid) {
    $query = mysqli_prepare($conn, "CALL RETRIEVE_BLOGPOST_ID_WITH_COMMENT_ID(?)");
    $query->bind_param('i',$cid);
    $query->execute();
    $query->bind_result($res);
    $query->fetch();
    return $res;
} function retrBlogpostIDWithCommentID($cid) {
    $conn = startConn();
    $res = retrBlogpostIDWithCommentID_raw($conn,$cid);
    $conn->close();
    return $res;
}

function retrUsername_raw($conn,$userid) {
    $query = mysqli_prepare($conn, "CALL RETRIEVE_USERNAME(?)");
    $query->bind_param('i',$userid);
    $query->execute();
    $query->bind_result($res);
    $query->fetch();
    return $res;
} function retrUsername($userid) {
    $conn = startConn();
    $res = retrUsername_raw($conn,$userid);
    $conn->close();
    return $res;
}

function commentExistsUnderPost_raw($conn,$id) {
    $query = mysqli_prepare($conn,"CALL IF_COMMENT_EXISTS_UNDER(?)");
    $query->bind_param('i',$id);
    $query->execute();
    $query->bind_result($res);
    $query->fetch();
    $query->close();
    return $res;
} function commentExistsUnderPost($id) {
    $conn = startConn();
    $res = commentExistsUnderPost_raw($conn,$id);
    $conn->close();
    return $res;
}

function retrUserIDByName($username) {
    $conn = startConn();
    $query = mysqli_prepare($conn, "CALL RETRIEVE_USER_ID_BY_NAME(?)");
    $query->bind_param('s',$username);
    $query->execute();
    $query->bind_result($userid);
    $query->fetch();
    $query->close();
    $conn->close();
    return $userid;
}

function retrUserPasswordByName_raw($conn,$username) {
    $query = mysqli_prepare($conn,"CALL RETRIEVE_USER_PASSWORD_BY_NAME(?)");
    $query->bind_param('s',$username);
    $query->execute();
    $query->bind_result($realPassword);
    $query->fetch();
    $query->close();
    return $realPassword;
}

function retrCurrentUserID() {
    return intval($_COOKIE["user_id"]);
}

function retrCurrentTemplate() {
    $conn = startConn();
    $query = mysqli_prepare($conn,"CALL RETRIEVE_CURRENT_TEMPLATE()");
    $query->execute(); $query->bind_result($res); $query->fetch();
    $query->close(); $conn->close();
    return $res;
}

function retrWelcomeMessage() {
    $conn = startConn();
    $query = mysqli_prepare($conn, "CALL RETRIEVE_WELCOME_MESSAGE()");
    $query->execute(); $query->bind_result($res); $query->fetch();
    $query->close(); $conn->close();
    return $res;
}
?>