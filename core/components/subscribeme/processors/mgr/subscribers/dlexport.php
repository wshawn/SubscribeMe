<?php
/**
 * SubscribeMe
 *
 * Copyright 2011 by Mark Hamstra <business@markhamstra.nl>
 *
 * This file is part of SubscribeMe, a subscriptions management extra for MODX Revolution
 *
 * SubscribeMe is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License as published by the Free Software
 * Foundation; either version 2 of the License, or (at your option) any later
 * version.
 *
 * SubscribeMe is distributed in the hope that it will be useful, but WITHOUT ANY
 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR
 * A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with
 * SubscribeMe; if not, write to the Free Software Foundation, Inc., 59 Temple Place,
 * Suite 330, Boston, MA 02111-1307 USA
*/

$file = $modx->getOption('export',$scriptProperties,null);
if (!$file) {
    die ($modx->lexicon('sm.error.notspecified',array('what' => 'ID')));
}
$path = $modx->sm->config['core_path'] . '/exports/' . $file . '.txt';
$o = file_get_contents($path);
if ($o) {
    header("Content-Disposition: attachment; filename=export_subscribers_".substr($file,5,10).".csv");
    return $o;
}
else {
    die($modx->lexicon('sm.error.exportnotfound'));
}
?>