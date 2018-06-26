<?php

function insertComment_raw($conn,$blogpostid,$date,$name,$body) {
    $query = mysqli_prepare($conn, "CALL INSERT_COMMENT_FOR(?,?,?,?)");
    if(!$query->bind_param('isss',intval($blogpostid),$date,$name,$body))
    die("Error!");
    $query->execute();
    $query->close();
} function insertComment($blogpostid,$date,$name,$body) {
    $conn = startConn();
    insertComment_raw($conn,$blogpostid,$date,$name,$body);
    $conn->close();
}

function insertComment_Exact($cid,$pid,$date,$name,$body){
    $conn = startConn();
    $cmd = mysqli_prepare($conn,"CALL INSERT_COMMENT_EXACT(?,?,?,?,?)");
    $cmd->bind_param('iisss',$cid,$pid,$date,$name,$body);
    $cmd->execute();
    $cmd->close();
    $conn->close();
}

function deleteCommentByID_raw($conn,$cid) {
    $query = mysqli_prepare($conn, "CALL DELETE_COMMENT(?)");
    $query->bind_param('i',$cid);
    $query->execute();
    $query->close();
} function deleteCommentByID($cid) {
    $conn = startConn();
    deleteCommentByID_raw($conn,$cid);
    $conn->close();
}

function modifyCommentByID_raw($conn,$cid,$date,$name,$body) {
    $query = mysqli_prepare($conn, "CALL MODIFY_COMMENT_WITH_DATE_UPDATE(?,?,?,?)");
    $query->bind_param('isss',$cid,$date,$name,$body);
    $query->execute();
    $query->close();
} function modifyCommentByID($cid,$date,$name,$body) {
    $conn = startConn();
    modifyCommentByID_raw($conn,$cid,$date,$name,$body);
    $conn->close();
}

?>