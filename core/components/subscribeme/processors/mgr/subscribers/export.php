<?php

// This tricks the getlist processor to return a raw array instead of json object
define('returnasarray',true);
$fields = array('id','fullname','username','email','address','zip','state','city','country');

$results = include('getlist.php');

if (count($results) == 0)
    return $modx->error->failure($modx->lexicon('sm.error.noresults'));

$file = 'subs_'.date('Y-m-d').'-'.rand(000,999);
$filename = $modx->sm->config['core_path'].'/exports/'.$file.'.txt';
if (!$handle = fopen($filename, 'a+')) {
    $modx->log(1,'Unable to open '.$filename.' for writing.');
    return $modx->error->failure($modx->lexicon('sm.error.export_openfile',array('file' => $filename)));
}

/* Write a header line */
$header = implode(';',$fields)."\r\n";
if (fwrite($handle, $header) === FALSE) {
    $modx->log(1,'Unable to write header to '.$filename);
    return $modx->error->failure($modx->lexicon('sm.error.export_writeheader', array('file' => $filename)));
}


foreach ($results as $r) {
    $cl = implode(';',$r)."\r\n";
    if (fwrite($handle, $cl) === FALSE) {
        $modx->log(1,'Unable to write entry to '.$filename);
        return $modx->error->failure($modx->lexicon('sm.error.export_writeentry',array('file' => $filename)));
    }
}

fclose($handle);

return $modx->error->success($file);

?>