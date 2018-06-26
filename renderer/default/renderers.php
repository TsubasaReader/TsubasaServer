<?php
function dispStylesheetLink() {
    echo '<link rel="stylesheet" type="text/css" href="./renderer/default/style.css" />';
}
function dispBackstageStylesheetLink() {
    echo '<link rel="stylesheet" type="text/css" href="./renderer/default/backstage_style.css" />';
}

function renderBlogpostEntry($id,$date,$userid,$title,$body) {
    return ('
    <li class="blogpost_entry">
    <b><a href="./index.php?id=' . $id . '">' . $date . ' by '
    . retrUsername($userid) . ' :: '
    . ((strlen($title)==0)?"&lt;noname&gt;":$title)
    . '</a></b></li>'
    );
}
function dispBlogpostEntry($id,$date,$userid,$title,$body) {
    echo renderBlogpostEntry($id,$date,$userid,$title,$body);
}
function dispBlogpostEntryWrapperWith($thunk) {
    echo '<ul>';
    forBlogposts($thunk);
    echo '</ul>';
}

function dispBlogpost($id,$date,$userid,$title,$body) {
    echo '<div class="blogpost_header">';
    echo '<h2>' . $title . '</h2>';
    echo 'ID' . $id . ', ' . $date . ' by ' . retrUsername($userid) . '</div><br />';
    echo '<br />-<br />';
    echo '<div class="blogpost_body">';
    echo $body;
    echo '</div>';
}
function dispBlogpostWrapperWith($id,$func) {
    echo '<div class="blogpost">';
    forBlogpostWithID($id,$func);
    echo '</div>';
}


function dispComment($commentid,$blogpostid,$date,$name,$body) {
    echo '<div class="comment">';
    echo '<div class="comment_header">';
    echo $date . ' :: ' . $name . ' says:</div>';
    echo '<div class="comment_body">';
    echo $body . '<br />';
    echo '</div></div><br />';
}
function dispCommentsWrapperWith($id,$func) {
    echo '<h3>Comments</h3>';
    echo '<div class="comments">';
    forCommentsAtBlogID($id,$func);
    echo '</div>';
}

function dispSectionDivider() {
    echo '<div class="hr"> </div>';
}
function dispSoftDivider() {
    echo '<br />-<br />';
}

function dispBlogpostManagementLink($id,$date,$userid,$title,$body) {
    echo '
    <li><a href="./admin.php?mode=manage&id=' . $id . '">Manage: <b>' . $date . ' :: ' . $title . '</b></a></li>
    ';
}
function dispUserManagementLink($userid,$username,$userpassword) {
    echo '<li><a href="./admin.php?mode=userm&id=' . $userid . '">Manage: ';
    echo '<b>UID' . $userid . ', ' . $username . '.</b></a></li>';
}
function dispCommentManagementLink($id,$blogpostid,$date,$name,$body) {
    echo '<li>Manage: <b><a href="./admin.php?mode=commentm&id=' . $id . '">';
    echo 'CID' . $id . ', ' . $date . ' :: ' . $name . ' says: ';
    echo ((strlen($body)<30)?$body:substr($body,0,30) . '...');
    echo '</a></b></li>';
} function dispCommentManagementWrapperWith($id,$func) {
    echo '<ul>';
    forCommentsAtBlogID($id,$func);
    echo '</ul>';
}

?>