<?php

require_once('retrieval.php');

function Response($x) {
    $x['messageType'] = "response";
    $x['protocolVer'] = 1;
    return $x;
}
function MessageSlot($type,$x) {
    $x['messageSlot'] = $type;
    return $x;
}
function ContentType($type,$x) {
    $x['contentType'] = $type;
    return $x;
}
function One($x) { return ContentType('one', $x); }
function Multiple($x) { return ContentType('multiple', $x); }
function Message($x) { return ContentType('message', $x); }
function Multimsg($x) { return ContentType('multimsg', $x); }
function Fields($f, $x) {
    $x['fields'] = $f;
    return $x;
}
function Content($c, $x) {
    $x['content'] = $c;
    return $x;
}

function Eid($source, $id) {
    return array('source' => $source, 'id' => $id);
}

function RespCatalog() {
    return Response(
        MessageSlot('catalog',
            One(
                Fields(['values'],
                    Content(array('values' => getEntitySources()), array())
                )
            )
        )
    );
}

function RespDetail($content) {
    return Response(
        MessageSlot('detail',
            One(
                Fields(['title','eid','body'],
                    Content($content, array())
                )
            )
        )
    );
}

function RespFeed($feed) {
    return Response(
        MessageSlot('feed',
            Multiple(
                Fields(['title', 'eid', 'body'],
                    Content($feed, array())
                )
            )
        )
    );
}

function RespEntity($entity) {
    return Response(
        MessageSlot('detail',
            One(
                Fields(['title', 'eid', 'body'],
                    Content($entity, array())
                )
            )
        )
    );
}

function RespError($errormsg) {
    return Response(
        MessageSlot('error',
            One(
                Fields(['message'],
                    Content(array('message' => $errormsg), array())
                )
            )
        )
    );
}

?>