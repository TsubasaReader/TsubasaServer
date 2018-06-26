<?php

require_once('./rss.php');
require_once('./routine.php');
require_once('./server/interface.php');


$entitySource = $_REQUEST['source'];
if(hasEntitySource($entitySource)) {
    $feedsize =
        isset($_REQUEST['feedsize'])?
        intval($_REQUEST['feedsize'])
        :25;
    $feed = json_decode(getFeed($entitySource, $feedsize));
    $feeds = [];
    for($len = count($feed), $i = 0; $i < $len; $i++) {
        $entity = getEntity($entitySource, $feed[$i]);
        $feeds[$i] = 
        Entry(
            $entity->{'title'},
            $entity->{'link'},
            $entity->{'date'},
            $entity->{'body'}
        );
    }
    echo Feed(
            getTitle($entitySource),
            getMainLink($entitySource),
            ItemList($feeds)
    );
} else echo '';

?>