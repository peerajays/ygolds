<?php

require_once "pdo.php";
require_once "bootstrap.php";
require_once "util.php";
session_start();
if(isset($_POST['cancel'])){
header("location: index.php");
return;
}
if(isset($_POST['email']) && isset($_POST['pass'])){
$_SESSION['email'] = $_POST['email'];
$stmt = $pdo->prepare('SELECT * FROM users
    WHERE email = :em AND pass = :pw');
$password = $_POST['pass'];
$salt = 'dhskjhfkjsdhkfhdkjshf734928dfhs02k1';
$check = hash('md5',$salt.$_POST['pass']);
//var_dump($check);
$stmt->execute(array(
  ':em' => $_SESSION['email'],
  ':pw' => $check));
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if ($row == true) {
    $_SESSION['user_id'] = $row['user_id'];
    // Redirect the browser to index.php
    header("Location: index.php");
    return;
} else {
  if (strlen($_POST['email'])==0 || strlen($_POST['pass'])==0) {
    $_SESSION['error'] = "ต้องใส่อีเมลล์และรหัสผ่าน";
    header("location: login.php");
    return;
  } if (strpos($_POST['email'],'@')==false) {
    $_SESSION['error'] = "ต้องมีเครื่องหมาย @ ในอีเมลล์";
    header("location: login.php");
    return;
  } else {
    $_SESSION['error'] = "อีเมลล์หรือรหัสผ่านผิด";
    header("location: login.php");
    return;
  }
}
}
?>
<head>
<title>ร้านทองเยาวราช</title>
</head>
<body>
<header><h1>กรุณาเข้าสู่ระบบ</h1></header>
<?flashMessages();?>
<form method = "POST">
<p>อีเมลล์:
<input type="text" size ="40" name="email" id="email"></p>
<p>พาสเวิร์ด:
<input type="password" size="40" name="pass" id="pass"></p>
<p><input type="submit" value="เข้าสู่ระบบ"/> <input type="submit" name="cancel" value="ยกเลิก"/></p>

</body>
