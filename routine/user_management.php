<?php

# Escape
# basic routine.

require_once("retrieval.php");

function calcHash($s) { return password_hash($s,PASSWORD_BCRYPT); }

function hashvalValid() {
    return checkSession(intval($_COOKIE["user_id"]),$_COOKIE["session_id"]);
}

function login($username,$password) {
    $conn = startConn();
    $realPassword = retrUserPasswordByName_raw($conn,$username);
    if(!password_verify($password,$realPassword)) {
        echo 'Wrong password.'; return false; }
    $userID = retrUserIDByName($username);
    $session_hashval = calcHash($userID . $password . time());
    $query = mysqli_prepare($conn,"CALL INSERT_LOGIN_HASH(?,?)");
    $query->bind_param('is',$userID,$session_hashval);
    $query->execute();
    $query->close();
    setcookie("user_id",intval(retrUserIDByName($username)));
    setcookie("session_id",$session_hashval);
    $conn->close();
    return true;
}

function clearCookies() {
    setcookie("user_id",null,time()-3600);
    setcookie("session_id",null,time()-3600);
}
function logout() {
    $conn = startConn();
    $query = mysqli_prepare($conn, "CALL CLEAR_USER_SESSION(?)");
    $query->bind_param('i',intval($_COOKIE["user_id"]));
    $query->execute();
    clearCookies();
    $query->close();
    $conn->close();
}

function regUser($username,$userpassword) {
    $conn = startConn();
    $query = mysqli_prepare($conn,"CALL INSERT_USER(?,?)");
    $hash = calcHash($userpassword);
    $query->bind_param('ss',$username,$hash);
    if(!$query->execute()) { $conn->close(); return false; }
    else {
        $query->close();
        $conn->close();
        return true;
    }
}

function modifyUserByID($id,$username,$userpassword) {
    $conn = startConn();
    $query = mysqli_prepare($conn,"CALL MODIFY_USER_BY_ID(?,?,?)");
    $newhash = calcHash($userpassword);
    $query->bind_param('iss',$id,$username,$newhash);
    $query->execute();
    $query->close();
    $conn->close();
}

function deleteUserByID($id) {
    $conn = startConn();
    $query = mysqli_prepare($conn,"CALL DELETE_USER(?)");
    $query->bind_param('i',$id);
    $query->execute();
    $query->close();
    $conn->close();
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

function insertUser_Exact($id,$name,$password){
    $conn = startConn();
    $cmd = mysqli_prepare($conn,"CALL INSERT_USER_EXACT(?,?,?)");
    $cmd->bind_param('iss',$id,$name,$password);
    $cmd->execute();
    $cmd->close();
    $conn->close();
}

?>