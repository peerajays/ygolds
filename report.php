
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
 ?>

<!DOCTYPE html>
<head>
  <title>ร้านทองเยาวราช</title>
</head>

<body>
  <header><h1>รายงานขายฝากทอง</h1></header>
  <form method = "POST">
  <p>เลือกปี:
    <select name="select_year">
    <option value="2562">2562</option>
    <option value="2563">2563</option>
  </select></p>
  <p>เลือกเดือน:
    <select name="select_month">
    <option value="01">มกราคม</option>
    <option value="02">กุมภาพันธ์</option>
    <option value="03">มีนาคม</option>
    <option value="04">เมษายน</option>
    <option value="05">พฤษภาคม</option>
    <option value="06">มิถุนายน</option>
    <option value="07">กรกฎาคม</option>
    <option value="08">สิงหาคม</option>
    <option value="09">กันยายน</option>
    <option value="10">พฤศจิกายน</option>
    <option value="11">พฤศจิกายน</option>
    <option value="12">ธันวาคม</option>
  </select></p>
  <p>แสดงรายการ:
    <select name="type">
    <option value="ทั้งหมด">ทั้งหมด</option>
    <option value="ถอนออก">เฉพาะรายการที่ลูกค้าถอนออกแล้ว</option>
    <option value="ขาดสิทธิ์">เฉพาะรายการที่ลูกค้าขาดสิทธิ์</option>
  </select></p>
  <p><input type = "submit" name="submit" value="ตกลง"> <input type = "submit" name = "cancel" value="ยกเลิก"></p>

    <?php
    if (isset($_POST['select_year']) && isset($_POST['select_month']) && isset($_POST['type']) ) {
      if ($_POST['type']=="ทั้งหมด") {
      $year = $_POST['select_year'];
      $month = $_POST['select_month'];
      $stmt = $pdo->prepare('SELECT COUNT(*) FROM redemption WHERE MONTH(date)="'.$month.'" and YEAR(date)="'.$year.'"');
      $stmt->execute();
      $row = $stmt->fetch(PDO::FETCH_ASSOC);
      //var_dump($row);
      echo('<p>จำนวนรายการขายฝากทั้งหมด '.$row['COUNT(*)'].' รายการ</p>');

      $stmt = $pdo->prepare('SELECT COUNT(*) FROM redemption WHERE MONTH(date)="'.$month.'" and YEAR(date)="'.$year.'" and status="ปกติ"');
      $stmt->execute();
      $row = $stmt->fetch(PDO::FETCH_ASSOC);
      //var_dump($row);
      echo('<p>จำนวนรายการขายฝากสถานะ ปกติ ทั้งหมด '.$row['COUNT(*)'].' รายการ</p>');

      $stmt = $pdo->prepare('SELECT COUNT(*) FROM redemption WHERE MONTH(date)="'.$month.'" and YEAR(date)="'.$year.'" and status="ถอนออก"');
      $stmt->execute();
      $row = $stmt->fetch(PDO::FETCH_ASSOC);
      //var_dump($row);
      echo('<p>จำนวนรายการขายฝากที่ลูกค้าถอนออกทั้งหมด '.$row['COUNT(*)'].' รายการ</p>');

      $stmt = $pdo->prepare('SELECT COUNT(*) FROM redemption WHERE MONTH(date)="'.$month.'" and YEAR(date)="'.$year.'" and status="ขาดสิทธิ์"');
      $stmt->execute();
      $row = $stmt->fetch(PDO::FETCH_ASSOC);
      //var_dump($row);
      echo('<p>จำนวนรายการขายฝากที่ลูกค้าขาดสิทธิ์ทั้งหมด '.$row['COUNT(*)'].' รายการ</p>');

      $stmt = $pdo->prepare('SELECT COUNT(*) FROM redemption WHERE (sex = "male" or sex ="นาย") and MONTH(date)="'.$month.'" and YEAR(date)="'.$year.'"');
      $stmt->execute();
      $row = $stmt->fetch(PDO::FETCH_ASSOC);
      echo('<ul>');
      echo('<li><p>แบ่งเป็นลูกค้าชายทั้งหมด '.$row['COUNT(*)'].' คน</p></li>');

      $stmt = $pdo->prepare('SELECT COUNT(*) FROM redemption WHERE (sex = "female" or sex = "นาง" or sex = "นางสาว") and MONTH(date)="'.$month.'" and YEAR(date)="'.$year.'"');
      $stmt->execute();
      $row = $stmt->fetch(PDO::FETCH_ASSOC);
      echo('<li><p>แบ่งเป็นลูกค้าหญิงทั้งหมด '.$row['COUNT(*)'].' คน</p></li>');
      echo('</ul>');

      $stmt = $pdo->prepare('SELECT SUM(total) FROM redemption WHERE MONTH(date)="'.$month.'" and YEAR(date)="'.$year.'"');
      $stmt->execute();
      $row = $stmt->fetch(PDO::FETCH_ASSOC);
      echo('<p>รายจ่ายรายการขายฝากทั้งหมด '.$row['SUM(total)'].' บาท</p>');

      echo('<p><h3>รายงานขายฝากทอง เดือน '.$month.' ปี '.$year.'</h3></p>');
      $stmt = $pdo->prepare('SELECT * FROM redemption WHERE MONTH(date)="'.$month.'" and YEAR(date)="'.$year.'"');
      $stmt->execute();
      $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
      echo('<table border="1" padding="15px">');
      echo('<thead>');
      echo('<tr>');
      echo('<th>หมายเลขซอง</th>');
      echo('<th>วันที่ขายฝาก</th>');
      echo('<th>ชื่อ</th>');
      echo('<th>นามสกุล</th>');
      echo('<th>หมายเลขบัตรประชาชน</th>');
      echo('<th>บ้านเลขที่</th>');
      echo('<th>ตำบล</th>');
      echo('<th>อำเภอ</th>');
      echo('<th>จังหวัด</th>');
      echo('<th>รายการขายฝาก</th>');
      echo('<th>ยอดเงินขายฝาก</th>');
      echo('<th>สถานะ</th>');
      echo('</tr>');
      echo('</thead>');
      foreach ($rows as $row) {
        echo('<tbody>');
        echo('<tr>');
        echo('<td>'.$row['number'].'</td>');
        echo('<td>'.$row['date'].'</td>');
        echo('<td>'.$row['firstname'].'</td>');
        echo('<td>'.$row['lastname'].'</td>');
        echo('<td>'.$row['id'].'</td>');
        echo('<td>'.$row['address'].'</td>');
        echo('<td>'.$row['city'].'</td>');
        echo('<td>'.$row['district'].'</td>');
        echo('<td>'.$row['province'].'</td>');
        echo('<td>'.$row['menu'].'</td>');
        echo('<td>'.$row['total'].'</td>');
        echo('<td>'.$row['status'].'</td>');
        echo('</tr>');
        echo('</tbody>');
      } echo('</table>');
    }

    if ($_POST['type']=="ถอนออก") {
    $year = $_POST['select_year'];
    $month = $_POST['select_month'];

    $stmt = $pdo->prepare('SELECT COUNT(*) FROM redemption WHERE MONTH(date)="'.$month.'" and YEAR(date)="'.$year.'" and status="ถอนออก"');
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    //var_dump($row);
    echo('<p>จำนวนรายการขายฝากที่ลูกค้าถอนออกทั้งหมด '.$row['COUNT(*)'].' รายการ</p>');

    $stmt = $pdo->prepare('SELECT COUNT(*) FROM redemption WHERE (sex = "male" or sex ="นาย") and MONTH(date)="'.$month.'" and YEAR(date)="'.$year.'" and status="ถอนออก" ');
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    echo('<ul>');
    echo('<li><p>แบ่งเป็นลูกค้าชายทั้งหมด '.$row['COUNT(*)'].' คน</p></li>');

    $stmt = $pdo->prepare('SELECT COUNT(*) FROM redemption WHERE (sex = "female" or sex = "นาง" or sex = "นางสาว") and MONTH(date)="'.$month.'" and YEAR(date)="'.$year.'" and status="ถอนออก"');
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    echo('<li><p>แบ่งเป็นลูกค้าหญิงทั้งหมด '.$row['COUNT(*)'].' คน</p></li>');
    echo('</ul>');

    $stmt = $pdo->prepare('SELECT SUM(total) FROM redemption WHERE MONTH(date)="'.$month.'" and YEAR(date)="'.$year.'" and status="ถอนออก"');
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    echo('<p>จำนวนเงินต้นทั้งหมด '.$row['SUM(total)'].' บาท</p>');

    $stmt = $pdo->prepare('SELECT SUM(interest) FROM interest WHERE MONTH(date)="'.$month.'" and YEAR(date)="'.$year.'" ');
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    echo('<p>ดอกเบี้ยที่ได้รับทั้งหมด '.$row['SUM(interest)'].' บาท</p>');

    echo('<p><h3>รายงานขายฝากทอง เดือน '.$month.' ปี '.$year.'</h3></p>');
    $stmt = $pdo->prepare('SELECT * FROM redemption WHERE MONTH(date)="'.$month.'" and YEAR(date)="'.$year.'" and status="ถอนออก"');
    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo('<table border="1" padding="15px">');
    echo('<thead>');
    echo('<tr>');
    echo('<th>หมายเลขซอง</th>');
    echo('<th>วันที่ขายฝาก</th>');
    echo('<th>ชื่อ</th>');
    echo('<th>นามสกุล</th>');
    echo('<th>หมายเลขบัตรประชาชน</th>');
    echo('<th>บ้านเลขที่</th>');
    echo('<th>ตำบล</th>');
    echo('<th>อำเภอ</th>');
    echo('<th>จังหวัด</th>');
    echo('<th>รายการขายฝาก</th>');
    echo('<th>ยอดเงินขายฝาก</th>');
    echo('<th>สถานะ</th>');
    echo('</tr>');
    echo('</thead>');
    foreach ($rows as $row) {
      echo('<tbody>');
      echo('<tr>');
      echo('<td>'.$row['number'].'</td>');
      echo('<td>'.$row['date'].'</td>');
      echo('<td>'.$row['firstname'].'</td>');
      echo('<td>'.$row['lastname'].'</td>');
      echo('<td>'.$row['id'].'</td>');
      echo('<td>'.$row['address'].'</td>');
      echo('<td>'.$row['city'].'</td>');
      echo('<td>'.$row['district'].'</td>');
      echo('<td>'.$row['province'].'</td>');
      echo('<td>'.$row['menu'].'</td>');
      echo('<td>'.$row['total'].'</td>');
      echo('<td>'.$row['status'].'</td>');
      echo('</tr>');
      echo('</tbody>');
    } echo('</table>');
  }

  if ($_POST['type']=="ขาดสิทธิ์") {
  $year = $_POST['select_year'];
  $month = $_POST['select_month'];

  $stmt = $pdo->prepare('SELECT COUNT(*) FROM redemption WHERE MONTH(date)="'.$month.'" and YEAR(date)="'.$year.'" and status="ขาดสิทธิ์"');
  $stmt->execute();
  $row = $stmt->fetch(PDO::FETCH_ASSOC);
  //var_dump($row);
  echo('<p>จำนวนรายการขายฝากที่ลูกค้าขาดสิทธิ์ทั้งหมด '.$row['COUNT(*)'].' รายการ</p>');

  $stmt = $pdo->prepare('SELECT COUNT(*) FROM redemption WHERE (sex = "male" or sex ="นาย") and MONTH(date)="'.$month.'" and YEAR(date)="'.$year.'" and status="ขาดสิทธิ์" ');
  $stmt->execute();
  $row = $stmt->fetch(PDO::FETCH_ASSOC);
  echo('<ul>');
  echo('<li><p>แบ่งเป็นลูกค้าชายทั้งหมด '.$row['COUNT(*)'].' คน</p></li>');

  $stmt = $pdo->prepare('SELECT COUNT(*) FROM redemption WHERE (sex = "female" or sex = "นาง" or sex = "นางสาว") and MONTH(date)="'.$month.'" and YEAR(date)="'.$year.'" and status="ขาดสิทธิ์"');
  $stmt->execute();
  $row = $stmt->fetch(PDO::FETCH_ASSOC);
  echo('<li><p>แบ่งเป็นลูกค้าหญิงทั้งหมด '.$row['COUNT(*)'].' คน</p></li>');
  echo('</ul>');

  $stmt = $pdo->prepare('SELECT SUM(total) FROM redemption WHERE MONTH(date)="'.$month.'" and YEAR(date)="'.$year.'" and status="ขาดสิทธิ์"');
  $stmt->execute();
  $row = $stmt->fetch(PDO::FETCH_ASSOC);
  echo('<p>จำนวนเงินต้นทั้งหมด '.$row['SUM(total)'].' บาท</p>');

  echo('<p><h3>รายงานขายฝากทอง เดือน '.$month.' ปี '.$year.'</h3></p>');
  $stmt = $pdo->prepare('SELECT * FROM redemption WHERE MONTH(date)="'.$month.'" and YEAR(date)="'.$year.'" and status="ขาดสิทธิ์"');
  $stmt->execute();
  $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
  echo('<table border="1" padding="15px">');
  echo('<thead>');
  echo('<tr>');
  echo('<th>หมายเลขซอง</th>');
  echo('<th>วันที่ขายฝาก</th>');
  echo('<th>ชื่อ</th>');
  echo('<th>นามสกุล</th>');
  echo('<th>หมายเลขบัตรประชาชน</th>');
  echo('<th>บ้านเลขที่</th>');
  echo('<th>ตำบล</th>');
  echo('<th>อำเภอ</th>');
  echo('<th>จังหวัด</th>');
  echo('<th>รายการขายฝาก</th>');
  echo('<th>ยอดเงินขายฝาก</th>');
  echo('<th>สถานะ</th>');
  echo('</tr>');
  echo('</thead>');
  foreach ($rows as $row) {
    echo('<tbody>');
    echo('<tr>');
    echo('<td>'.$row['number'].'</td>');
    echo('<td>'.$row['date'].'</td>');
    echo('<td>'.$row['firstname'].'</td>');
    echo('<td>'.$row['lastname'].'</td>');
    echo('<td>'.$row['id'].'</td>');
    echo('<td>'.$row['address'].'</td>');
    echo('<td>'.$row['city'].'</td>');
    echo('<td>'.$row['district'].'</td>');
    echo('<td>'.$row['province'].'</td>');
    echo('<td>'.$row['menu'].'</td>');
    echo('<td>'.$row['total'].'</td>');
    echo('<td>'.$row['status'].'</td>');
    echo('</tr>');
    echo('</tbody>');
  } echo('</table>');
}
}

     ?>
  </p>
</body>
