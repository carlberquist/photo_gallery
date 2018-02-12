<?php
echo __FILE__ . "<br />" . __LINE__ . "<br />" . __DIR__;



//write to a file (OVERWRITES, a, a+ do not move pointer)
$file = "filetest.txt";
if ($handle = fopen($file, 'w')) {
    fwrite($handle, "123\n456\789");

    $pos = ftell($handle);
    fseek($handle, $pos - 6);
    fwrite($handle, "abcde");

    rewind($handle);
    fwrite($handle, "xyz");

    fclose($handle);
}

//Shorthand file_put_contents (fopen/fwrite/fclose)
$content = "123\n456\789";
if ($size = file_put_contents($file, $content)) {
    echo ("File has {$size} number of bytes");
}

if ($handle = fopen($file, "r")) {
//each character is 1 byte
//fread($handle, filesize($file); //gets the whole file
    $content = fread($handle, 3);
    fclose($handle);
}
echo nl2br($content);//turns /n and /r to <br />
echo ("hr /");

//Shorthand file_get_contents (fopen/fread/fclose)
$content = file_get_contents($file);
echo nl2br($content);//turns /n and /r to <br />
echo ("hr /");

//read one line at a time
if ($handle = fopen($file, 'r')){
    $content = '';
    while(!feof($handle)){
    $content .= fgets($handle);
}
    fclose($handle);
}
?>