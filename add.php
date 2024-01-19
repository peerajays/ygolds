<!DOCTYPE html>
<?php
require_once "pdo.php";
require_once "bootstrap.php";
require_once "util.php";
session_start();

if(!isset($_SESSION['user_id'])){
die ("Not logged in");
return;
}

if(isset($_POST['cancel'])) {
header("location: index.php");
return;
}


if (isset($_POST['number']) && isset($_POST['first_name']) && isset($_POST['last_name']) ) {

  // validate data
  $msg = validateProfile();
  if (is_string($msg) ) {
    $_SESSION['error'] = $msg;
    header("location: add.php");
    return;}

  //$sql = "INSERT INTO redemption (number, date, sex, firstname, lastname, id, address, city, district, province, menu, total, status)
  //VALUES (:num, :date, ,:sex, :fn, :ln, :id, :add, :ct, :dt, :pv, :mn, :tt, :stt)";
  $stmt = $pdo->prepare("INSERT INTO redemption (number, date, firstname, lastname, id, address, city, province, menu, total, status, district, sex)
  VALUES (:num, :date, :fn, :ln, :id, :add, :ct, :pv, :mn, :tt, :stt, :dt, :sex)");
  $stmt->execute(array(
    ':num'    =>  $_POST['number'],
    ':date'   =>  $_POST['date'],
    ':sex'    =>  $_POST['sex'],
    ':fn'     =>  $_POST['first_name'],
    ':ln'     =>  $_POST['last_name'],
    ':id'     =>  $_POST['id'],
    ':add'    =>  $_POST['address'],
    ':ct'     =>  $_POST['city'],
    ':dt'     =>  $_POST['district'],
    ':pv'     =>  $_POST['province'],
    ':mn'     =>  $_POST['menu'],
    ':tt'     =>  $_POST['total'],
    ':stt'    =>  $_POST['status']));

    $_SESSION['success'] = 'บันทึกเรียบร้อยแล้ว';
    header('location: add.php');
    return;
  }


 ?>
<head>
  <title> ร้านทองเยาวราช </title>
  <meta charset="UTF-8">
</head>

<body>
  <header><h1>เพิ่มรายการขายฝาก</h1></header>
  <?php
   flashMessages();
   ?>
  <form method="POST">
  <div = "personal_info">
  <p>หมายเลขซอง:
  <input type="number" name="number"></p>
  <p>วันที่นำขายฝาก:
  <input type="date" name="date"></p>
  <p>คำนำหน้าชื่อ:
    <select name="sex">
    <option value="นาย">นาย</option>
    <option value="นางสาว" class="female">นางสาว</option>
    <option value="นาง">นาง</option>
  </select></p>
  <p>ชื่อ:
  <input type="text" name="first_name"></p>
  <p>นามสกุล:
  <input type="text" name="last_name"></p>
  <p>หมายเลขบัตรประชาชน:
  <input type="text" name="id"></p>
  </div>
  <div = "address">
  <p>บ้านเลขที่:
  <input type="text" name="address"></p>
  <p>ตำบล:
  <input type="text" name="city"></p>
  <p>อำเภอ:
  <input type="text" name="district"></p>
  <p>จังหวัด:
  <input type="text" name="province"></p>
  </div>
  <div id="item">
  <p>รายการขายฝาก:
  <textarea row="4" col="50" name="menu">
  </textarea>
  <p>จำนวนเงิน:
  <input type="text" name="total"></p>
  <p>สถานะ:
    <select name="status">
    <option value="ปกติ">ปกติ</option>
    <option value="ต่อดอก">ต่อดอก</option>
  </select></p>
  <p><input type="submit" name="submit" value="ตกลง">
  <input type="submit" name="cancel" value="ยกเลิก">
  </div>
  </form>
</body>
