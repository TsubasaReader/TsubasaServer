<?php

function insertBlogpost_raw($conn,$id,$title,$body) {
    $query = mysqli_prepare($conn,"CALL INSERT_POST(?,?,?,?)");
    $query->bind_param('siss',date("Y-m-d H:i:s"),$id,$title,$body);
    $query->execute();
    $query->close();
} function insertBlogpost($id,$title,$body) {
    $conn = startConn();
    insertBlogpost_raw($conn,$id,$title,$body);
    $conn->close();
}

function insertBlogpost_Exact($id,$date,$userid,$title,$body){
    $conn = startConn();
    $cmd = mysqli_prepare($conn,"CALL INSERT_POST_EXACT(?,?,?,?,?)");
    $cmd->bind_param('isiss',$id,$date,$userid,$title,$body);
    $cmd->execute();
    $cmd->close();
    $conn->close();
}

function deleteBlogpost_raw($conn,$id) {
    $query = mysqli_prepare($conn, "CALL DELETE_BLOGPOST(?)");
    $query->bind_param('i',$id);
    $query->execute();
    $query->close();
} function deleteBlogpost($id) {
    $conn = startConn();
    deleteBlogpost_raw($conn,$id);
    $conn->close();
}

function modifyBlogpost_raw($conn,$id,$title,$body) {
    $query = mysqli_prepare($conn,"CALL MODIFY_BLOGPOST(?,?,?)");
    $query->bind_param('iss',$id,$title,$body);
    $query->execute();
    $query->close();
} function modifyBlogpost($id,$title,$body) {
    $conn = startConn();
    modifyBlogpost_raw($conn,$id,$title,$body);
    $conn->close();
}

?>