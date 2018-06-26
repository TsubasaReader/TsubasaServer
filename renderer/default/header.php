<?php

function renderHeader() {
    return (
        '<h1>' . retrBlogname() . '</h1>'
    );
}
function renderMainsiteNavbar() {
    return (
        '<div class="navbar">
         <a class="navbar_link" href="./index.php">Main page</a>
         <a class="navbar_link" href="./server.php">Server</a>
         <a class="navbar_link" href="./admin.php">Admin</a>
         </div>
         '
    );
}
function dispHeader() {
    echo '<div class="header">';
    echo renderHeader();
    echo renderMainsiteNavbar();
    echo '</div>';
}
?>