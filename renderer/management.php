<?php

require_once("./routine/retrieval.php");

# Escape
# template management

function openDesc($idname) {
    return json_decode(file_get_contents('./' . $idname . '/description.json'));
}
function checkTemplate($idname) {
    $r = openDesc($idname);
    return (strcmp($r["idname"],$idname)==0);
}

?>