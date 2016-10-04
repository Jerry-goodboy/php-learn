<?php
/**
* captcha generator
*/
    session_start();

    $image = imagecreatetruecolor(100, 30);
    $bgcolor = imagecolorallocate($image,255,255,255);
    imagefill($image,0,0,$bgcolor);

    $captcha_code = '';
    $fontface = 'fonts/pen_jt.ttf';
    $str = "日历扩展包含了简化不同日历格式间的转换的数脚本运行的服务器上获取日期和时间并进行格式化获得关于录及其内容的信息对错误进行处理和记录集成开发环境是一种集成了软件开发过中所需主要工具的集成开发环境其功能包括但不仅限于代码高代码补全调试构建版本控制等程序开发快运行快技术本身学习快入于因为可以被嵌入于语言它相对于其他语言编辑简单软件开我实用性强更适合初学者";
    // $strdb = array('慕', '课', '网', '赞');
    $strdb = str_split($str,3);

    // number only
    // for($i=0; $i<4; $i++){
    //     $fontsize = 6;
    //     $fontcolor = imagecolorallocate($image, rand(0, 120), rand(0, 120),rand(0, 120));
    //     $fontcontent = rand(0, 9);

    //     $x = ($i*100/4) + rand(5, 10);
    //     $y = rand(5, 10);

    //     imagestring($image, $fontsize, $x, $y, $fontcontent, $fontcolor);
    // }

    // number and abc 
    for($i=0; $i<4; $i++){
        $fontcolor = imagecolorallocate($image, rand(0, 120), rand(0, 120),rand(0, 120));

        // $cn = $strdb[$i];
        $index = rand(0,count($strdb));
        $cn = $strdb[$index];
        $captcha_code .= $cn;

        imagettftext($image, mt_rand(10,24), mt_rand(-30,30), (20*$i + 10), mt_rand(15,30),$fontcolor,$fontface,$cn);
        
    }

    $_SESSION['captcha_code'] = $captcha_code;

    for($i=0; $i<200; $i++){
        $pointcolor = imagecolorallocate($image, rand(50, 200), rand(50, 200), rand(50, 200));
        imagesetpixel($image, rand(1, 99), rand(1, 29), $pointcolor);
    }

    for($i=0; $i<3; $i++){
        $linecolor = imagecolorallocate($image, rand(80, 220), rand(80, 220), rand(80, 220));
        imageline($image, rand(1, 99), rand(1, 29), rand(1, 99), rand(1, 29), $linecolor);
    }

    header('content-type: image/png');
    imagepng($image);

    //end
    imagedestroy($image);