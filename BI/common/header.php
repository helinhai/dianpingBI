<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta content="text/html; charset=utf-8" http-equiv="Content-Type" /> 
    <meta charset="utf-8" />
    <?php
        $title = isset($page_title) ? $page_title . ' - 大众点评商户卫星系统' : '大众点评商户卫星系统';
        echo '<title>' . $title . '</title>';
    ?>
    <link rel="stylesheet" href="./css/common.css">
    <link rel="stylesheet" href="./css/index.css">
</head>
<body id="<?php echo isset($page_name) ? $page_name : ''; ?>">
<header id="hdw">
    <div id="hd">
        <h1>大众点评O2O系统</h1></div>
    <div class="hd status">
        <div class="username">
            <span>欢迎您</span>
            <?php 
            if($_GET['username']!=""){
                    echo $_GET['username'];
            }else{
                echo "<script language=\"JavaScript\">self.location='login.php';</script>"; 
            }    
            ?>
            <a class="logout" href="logout.php">退出</a>
        </div>
    </div>    
</header>