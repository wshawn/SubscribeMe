<?php
$file = $modx->getOption('export',$scriptProperties,null);
if (!$file) {
    die ('An error occured.');
}
$path = $modx->sm->config['core_path'] . '/exports/' . $file . '.txt';
$o = file_get_contents($path);
if ($o) {
    header("Content-Disposition: attachment; filename=export_subscribers_".substr($file,5,10).".csv");
    return $o;
}
else {
    die('Could not find export.');
}
?>