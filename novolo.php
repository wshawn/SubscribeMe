<?php

// First instantiate the Gallery package
$modx->addPackage('gallery',$modx->getOption('gallery.core_path',$config,$modx->getOption('core_path').'components/gallery/').'model/');

$curItem = $modx->getOption('curItem',$scriptProperties,1);
if ($modx->getOption('checkForRequestVar',$scriptProperties,true)) {
    $getParam = $modx->getOption('getParam',$scriptProperties,'galItem');
    if (!empty($_REQUEST[$getParam])) { $curItem = (int)$_REQUEST[$getParam]; }
}
if (empty($curItem)) return '';

$album = $modx->getOption('album',$scriptProperties);
if ($modx->getOption('checkForRequestAlbumVar',$scriptProperties,true)) {
    $albumRequestVar = $modx->getOption('albumRequestVar',$scriptProperties,'galAlbum');
    if (!empty($_REQUEST[$albumRequestVar])) $album = $_REQUEST[$albumRequestVar];
}
// We pass the album name/ID to an &album property and find the gallery object
if (!is_int($album)) {
    $gallery = $modx->getObject('galAlbum',array('name' => $album));
    if ($gallery instanceof galAlbum)
        $album = $gallery->get('id');
}

$c = $modx->newQuery('galAlbumItem');
$c->innerJoin('galItem','Item');
$c->where(
    array(
        'album' => $album,
    )
);
$c->select(
    array(
        'galAlbumItem.*',
        'Item.*',
    )
);

$c->sortby('rank','asc');

$collection = $modx->getCollection('galAlbumItem',$c);

$items = array();
foreach ($collection as $i) {
    $items[] = $i->toArray();
}

$continue = true;
$i = 0; $prev = array(); $cur = array(); $next = array();
while ($continue) {
    $prev = $cur;
    $cur = $items[$i];
    if ($cur['id'] == $curItem) {
        $next = $items[$i+1];
        $continue = false;
    }
    $i++;
}
$first = $items[0];
$last = $items[count($items)-1];
$phs['galitem.cur.'] = $cur;
$phs['galitem.prev.'] = $prev;
$phs['galitem.next.'] = $next;
$phs['galitem.first.'] = $first;
$phs['galitem.last.'] = $last;

$modx->setPlaceholders($phs);

return '';
