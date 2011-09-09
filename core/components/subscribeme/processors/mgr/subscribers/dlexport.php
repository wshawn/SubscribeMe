<?php
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