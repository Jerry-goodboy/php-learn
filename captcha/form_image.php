<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>captcha confirm</title>
</head>
<body>

<?php
    if(isset($_REQUEST['captcha_code'])){
        session_start();

        if(strtolower($_REQUEST['captcha_code'])  == $_SESSION['captcha_code']){
            echo '<font color="#0000cc">right</font>';
        }else {
            echo '<font color="#cc0000">wrong</font>';
        }
        exit();
    }
?>


    <form action="./form_image.php" method="post">
        <p>pic: <img id="captcha_img" src="./captcha_image.php?r=<?php echo rand();?>" alt="captcha" border="1" width="200px" height="200px" > <a href="javascript:void(0)" onclick="document.getElementById('captcha_img').src='./captcha_image.php?r='+Math.random()">change another</a>  </p> 
        <p>input: <input type="text" name="captcha_code" value="" > </p>
        <p> <input type="submit" value="submit" > </p>  
    </form>
</body>
</html>