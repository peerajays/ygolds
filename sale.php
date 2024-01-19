
<?php
require_once "pdo.php";
require_once "bootstrap.php";
require_once "util.php";
session_start();

if (isset($_POST['cancel']) ) {
  header("location: index.php");
  return;
}

if(!isset($_SESSION['user_id'])){
die ("Not logged in");
return;
}

if (isset($_POST['date']) && isset($_POST['type']) && isset($_POST['menu']) && isset($_POST['price']) ) {

  //validate data;
  $msg = validateSale();
  if (is_string($msg) ) {
    $_SESSION['error'] = $msg;
    header("location: sale.php");
    return;
  }

  $sql = "INSERT INTO sale(date, type, menu, price, weight) VALUES(:date, :type, :menu, :price, :weight)";
  $stmt = $pdo->prepare($sql);
  $stmt->execute(array(
    ':date' => $_POST['date'],
    ':type' => $_POST['type'],
    ':menu' => $_POST['menu'],
    ':price' => $_POST['price'],
    ':weight'=> $_POST['weight']
  ));

  $_SESSION['success'] = 'บันทึกเรียบร้อยแล้ว';
  header('location: sale.php');
  return;
}
 ?>

<!DOCTYPE html>
<head>
   <title>ร้านทองเยาวราช</title>
</head>

<body>
  <header><h1>บันทึกรายการขาย/แลกเปลี่ยน</h1></header>
  <? flashMessages(); ?>
  <form method="post">
  <p>วันที่:
  <input type="date" name="date"></p>
  <p>ประเภทรายการ:
    <select name="type">
    <option value="ขาย">ขาย</option>
    <option value="เปลี่ยน" class="female">เปลี่ยน</option>
  </select></p>
  <p>รายการ:
  <input type="text" name="menu"></p>
  <p>นํ้าหนัก:
  <input type="text" name="weight"></p>
  <p>ราคาขาย/เปลี่ยน:
  <input type="text" name="price"></p>
  <p><input type="submit" value="บันทึก"> <input type="submit" name="cancel" value="ยกเลิก"></p>
</form>
</body>
