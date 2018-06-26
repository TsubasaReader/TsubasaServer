<?php


function setBlogName($newBlogName){
    $conn = startConn();
    $cmd = mysqli_prepare($conn,"CALL SET_BLOGNAME(?)");
    $cmd->bind_param('s',$newBlogName);
    $cmd->execute();
    $cmd->close();
    $conn->close();
}

function setCurrentTemplate($newTemplateName){
    $conn = startConn();
    $cmd = mysqli_prepare($conn,"CALL SET_CURRENT_TEMPLATE(?)");
    $cmd->bind_param('s',$newTemplateName);
    $cmd->execute();
    $cmd->close();
    $conn->close();
}

function setWelcomeMessage($newWelcomeMessage) {
    $conn = startConn();
    $cmd = mysqli_prepare($conn, "CALL SET_WELCOME_MESSAGE(?)");
    $cmd->bind_param('s',$newWelcomeMessage);
    $cmd->execute();
    $cmd->close();
    $conn->close();
}


?>