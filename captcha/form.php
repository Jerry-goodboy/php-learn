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

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>captcha confirm</title>
</head>
<body>
    <form action="./form.php" method="post">
        <p>pic: <img src="./captcha.php?r=<?php echo rand();?>" alt="captcha" border="1" width="100px" height="30px" > </p> 
        <p>input: <input type="text" name="captcha_code" value="" > </p>
        <p> <input type="submit" value="submit" > </p>  
    </form>
</body>
</html>