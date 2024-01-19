
<?php
require_once "pdo.php";
require_once "bootstrap.php";
require_once "util.php";
session_start();

if(!isset($_SESSION['user_id']) ) {
  die("กรุณาเข้าสู่ระบบ");
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
<header>
  <h1>ค้นหารายการขายฝาก</h1>
</header>
<?flashMessages()?>
<form method="POST">
<p>ค้นจาก:
  <select name="search">
  <option value="name">ชื่อ</option>
  <option value="id">เลขบัตรประชาชน</option>
</select></p>
<p>ชื่อหรือเลขบัตรประชาชน:
<input type="text" name="nameorid"></p>
<p><input type="submit" value="ค้นหา"> <input type="submit" name="cancel" value="กลับไปหน้าแรก"></p>
</form>

<?php
if (isset($_POST['search']) && isset($_POST['nameorid']) ) {
    if ($_POST['search'] == "name" ) {

    //validate name
        $msg = validateName();
        if (is_string($msg) ) {
        $_SESSION['error'] = $msg;
        header("location: search.php");
        return;
        }

        //ชื่อที่ต้องการค้น
        $name = $_POST['nameorid'];

        $sql = "SELECT * FROM redemption WHERE firstname = :name";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array(
          ':name' => $name
        ));
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

      if ($_POST['search'] == "id" ) {

      //validate name
          $msg = validateID();
          if (is_string($msg) ) {
          $_SESSION['error'] = $msg;
          header("location: search.php");
          return;
          }

          //ชื่อที่ต้องการค้น
          $id = $_POST['nameorid'];

          $sql = "SELECT * FROM redemption WHERE id = :id";
          $stmt = $pdo->prepare($sql);
          $stmt->execute(array(
            ':id' => $id
          ));
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

</body>
