<?php
require_once "pdo.php";
require_once "bootstrap.php";
require_once "util.php";
session_start();

if (!isset($_SESSION['user_id']) ) {
  die ("กรุณาเข้าสู่ระบบ");
}

if (isset($_POST['cancel']) ) {
  header("location: index.php");
  return;
}

 ?>
<head>
  <title>ร้านทองเยาราช</title>
</head>

<body>
  <header><h1>บันทึกถอนทอง</h1></header>
  <?flashMessages();?>
  <form method="POST">
    <p>รหัส:
    <input type="number" name="number"></p>
    <p>ประเภทรายการ:
    <select name = "type">
    <option value="ถอนออก">ถอนออก</option>
    <option value="ขาดสิทธิ์">ขาดสิทธิ์</option>
    </select></p>
    <p>วันที่ถอน(หรือขาดสิทธิ์)ขายฝาก:
    <input type="date" name="date"></p>
    <p>ดอกเบี้ย:
    <input type="number" name="interest"></p>
    <p><input type="submit" value="บันทึก"> <input type="submit" name="cancel" value="กลับไปหน้าแรก"></p>
  </form>
<?php
if (isset($_POST['type']) ) {
if ($_POST['type']=="ถอนออก") {
if (isset($_POST['number']) && isset($_POST['date']) && isset($_POST['interest']) ) {

  //validate
  $msg = validateInterest();
  if (is_string($msg) ) {
    $_SESSION['error'] = $msg;
    header("location: addinterest.php");
    return;
  }

  $number = $_POST['number'];

  $sql = "SELECT * FROM redemption WHERE number ='$number' ";
  $stmt = $pdo->prepare($sql);
  $stmt->execute();
  $row = $stmt->fetch(PDO::FETCH_ASSOC);

  $total = ($row['total'] +$_POST['interest']);
  $sql = "INSERT INTO interest(number, principle, interest, total, date)
  VALUES(:num, :prin, :int, :total, :date)";
  $stmt = $pdo->prepare($sql);
  $stmt->execute(array(
    ':num' => $_POST['number'],
    ':prin'=>$row['total'],
    ':int'=> $_POST['interest'],
    ':total'=> $total,
    ':date'=>$_POST['date']
  ));

  $sql = "UPDATE redemption SET status = 'ถอนออก' WHERE number = '$number' ";
  $stmt = $pdo->prepare($sql);
  $stmt->execute();

  $_SESSION['success'] = "บันทึกสำเร็จ";
  header("location: addinterest.php");
  return;
}
}

if ($_POST['type']=="ขาดสิทธิ์") {
if (isset($_POST['number']) && isset($_POST['date']) ) {

  //validate
  $msg = validateRight();
  if (is_string($msg) ) {
    $_SESSION['error'] = $msg;
    header("location: addinterest.php");
    return;
  }

  $number = $_POST['number'];

  $sql = "INSERT INTO right1(number, date) VALUES(:num,:date)";
  $stmt = $pdo->prepare($sql);
  $stmt->execute(array(
    ':num'=>$number,
    ':date'=>$_POST['date']
  ));

  $sql = "UPDATE redemption SET status = 'ขาดสิทธิ์' WHERE number = '$number' ";
  $stmt = $pdo->prepare($sql);
  $stmt->execute();

  $_SESSION['success'] = "บันทึกสำเร็จ";
  header("location: addinterest.php");
  return;
}
}
}
 ?>
</body>
