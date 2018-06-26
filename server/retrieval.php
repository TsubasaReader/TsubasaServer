<?php
require_once('./config.php');
require_once('./routine/retrieval.php');

// load plugins.
for($pluginArray = scandir('./plugin/'), $i = 0, $size = count($pluginArray);
    $i < $size;
    $i ++) {
        if(isValidPlugin('./plugin/' . $pluginArray[$i])) {
            require_once('./plugin/' . $pluginArray[$i] . '/retrieval.php');
        }
    }


function isValidPlugin($dirPath) {
    return file_exists($dirPath . '/NAME') && file_exists($dirPath . '/retrieval.php');
}

function getExternalEntitySources() {
    $result = [];
    $pluginArray = scandir('./plugin/');
    for($i = 0, $size = count($pluginArray); $i < $size; $i++) {
        if(isValidPlugin('./plugin/' . $pluginArray[$i])) {
            array_push($result, $pluginArray[$i]);
        }
    }
    return $result;
}

function getEntitySources() {
    return array_merge(["internal"], getExternalEntitySources());
}

function hasEntitySource($source) {
    $entitySourceArray = getEntitySources();
    for($len = count($entitySourceArray), $i = 0; $i < $len; $i++) {
        if(strcmp($source, $entitySourceArray[$i]) == 0) {
            return true;
        }
    }
    return false;
}

function getFeed($source, $size) {
    if(strcmp($source, "internal") == 0) {
        return json_encode(getInternalFeed($size));
    } else {
        return ($source . '_getFeed')($size);
    }
    return null;
}

function getTitle($source) {
    if(strcmp($source, "internal") == 0) {
        return retrBlogname();
    } else {
        return ($source . '_getTitle')();
    }
}


function getInternalFeed($size) {
    $conn = startConn();
    $query = mysqli_prepare($conn, "CALL RETRIEVE_BLOGPOSTS()");
    $query->execute();
    $query->bind_result($id,$date,$userid,$title,$body);
    $result = [];
    for($i = 0; $i < $size; $i ++) {
        if(!$query->fetch())break;
        $result[$i] = $id;
    }
    $query->close();
    $conn->close();
    return $result;
}

function getEntity($source, $id) {
    if($source === "internal") {
        return getInternalEntity($id);
    } else {
        return ($source . '_getEntity')($id);
    }
    return NULL;
}

function getMainLink($source) {
    if($source === "internal") {
        return retrSiteAddr();
    } else {
        return ($source . '_getMainLink')();
    }
}

function getInternalEntity($id) {
    $conn = startConn();
    $query = mysqli_prepare($conn, "CALL RETRIEVE_BLOGPOST_BY_ID(?)");
    $query->bind_param('i', $id);
    $query->execute();
    $query->bind_result($id, $date, $userid, $title, $body);
    $query->fetch();
    $res = new stdClass();
    $res->{'title'} = $title;
    $res->{'id'} = $id;
    $res->{'link'} = retrSiteAddr() . "/index.php?id=" . $id;
    $res->{'date'} = DateTime::createFromFormat('Y-m-d H:i:s', $date)->format('r');
    $res->{'body'} = $body;
    $query->close();
    $conn->close();
    return $res;
}


?>