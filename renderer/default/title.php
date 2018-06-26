<?php

function renderTitle() {
    return retrBlogname();
}
function renderTitle_Singl($blogpostID) {
    return (retrBlogname() . " | " . retrBlogpostTitle($blogpostID));
}
?>