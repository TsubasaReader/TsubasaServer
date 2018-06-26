<?php

// atom encapsulation utilities.
// (c) sebastian lin, 2018

function Feed($title, $link, $body) {
    return
'<?xml version="1.0" encoding="UTF-8" ?>
<feed xmlns="http://www.w3.org/2005/Atom">
    <title>'. $title . '</title>
    <link href="' . $link . '" />
    ' . $body . '
</feed>
';
}

function Entry($title, $link, $date, $description) {
    return '<entry>
    <title>' . $title . '</title>
    <link href="' . $link . '" />
    <updated>' . $date . '</updated>
    <content type="html"><![CDATA[' . $description . ']]></content>
    </entry>';
}
function ItemList($itemlist) {
    $result = '';
    for($len = count($itemlist), $i = 0; $i < $len; $i++) {
        $result = $result . $itemlist[$i];
    }
    return $result;
}


?>