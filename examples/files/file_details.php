<?php
$filename = 'filetest.txt';

echo filesize($filename) . "<br />"; //bytes

//return UNIX timestamp
//filemtime: last modified (content)
//filectime: last changes (content or metadata)
//fileatime: last accessed (read or change)

echo strftime('%m/%d/%Y %H:%M', filemtime($filename));

touch($filename); //careful of cache (could be old data)

//look at docs for array key names
$path_array = pathinfo(__FILE__);

?>