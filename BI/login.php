<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta content="text/html; charset=utf-8" http-equiv="Content-Type" /> 
    <meta charset="utf-8" />
    <?php 
    $page_name = 'login';
    $page_title = "登录";
    ?>
    <?php
        $title = isset($page_title) ? $page_title . ' - 大众点评商户卫星系统' : '大众点评商户卫星系统';
        echo '<title>' . $title . '</title>';
    ?>
    <link rel="stylesheet" href="css/common.css">

</head>
<?php session_start(); ?>
<?php
    if (isset($_SESSION["online"])) {
        echo "<script language=\"JavaScript\">alert(\"已经登录！\");</script>";
        $username = $_SESSION['online'];
    }
    else{
        if ($_POST["name"]) {
        include("verify.php");
        $name = $_POST['name'];
        $password = $_POST['password'];
        if(!Verify::isNames($name,5,20,'EN')){
             echo "<script language=\"JavaScript\">alert(\"用户名不合法！\");</script>"; 
        }
        else{
            include('class/DBhandler.class.php');
            $DBhandler=new DBhandler();
            $sql='select user_pw from user where user_name ='.$name;
            $results = $DBhandler->fetchQuery($sql);
            if($results[0]==$password){
                echo "<script language=\"JavaScript\">self.location='index.php?username='+$name;</script>";
                $_SESSION["online"] = $name; 
                }
            else{
                echo "<script language=\"JavaScript\">alert(\"用户名或密码不正确！\");</script>"; 
                }
            }
        }
    }
?>
<body id="<?php echo isset($page_name) ? $page_name : ''; ?>">
<header id="hdw">
    <div id="hd">
        <h1>大众点评商户卫星系统</h1>
    </div>
</header>

<div class="login_content">
    <h1>登录</h1>
    <form id="login_form" method="post" action="">
    <input id="login_name" type="text" name="name"></input></br>
    <input id="login_password" type="text" name="password"></input></br>
    <input id="login_submit" class="login_btn" type="submit" value="登录"></br>
    </form>
    <a class="forget_password" href="">忘记密码</a>
    <a class="register" href="register.php">注册</a>
</div>	
<?php include 'common/footer.php' ; 
?>
