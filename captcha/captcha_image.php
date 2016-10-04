<?php
/**
* captcha generator
*/
    session_start();

    $table = array(
        'cat' => 'cat',
        'dog' => 'dog'
    );

    $index = rand(0,1);

    $arr = array('cat', 'dog');

    $value = $table[$arr[$index]];
    $_SESSION['captcha_code'] = $value;

    $filename = dirname(__FILE__).'/images/'.$arr[$index].'.jpg';
    $contents = file_get_contents($filename);

    header('content-type: image/png');

    echo $contents;
