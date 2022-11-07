<html>
<head>
<title>Моя форма</title>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
</head>
<body>

<?php 
  $email=$_POST['email'];
$telefone=$_POST['telefone']; 
  $data=$_POST['data']; 

  $dbhost="localhost";
  $dbname="lab3";
  $username="root";
  $password="";
?>

<?php echo "e-mail:  $email<br>";?>
<?php echo "Дата:    $data<br>";?>
<?php echo "Телефон: $telefone<br><br>";?>

<?php
if(!preg_match("/^([a-z0-9_\.-]+)@([a-z0-9_\.-]+)\.([a-z\.]{2,6})$/ui",$email) || !preg_match("/^\+380([0-9]{9})$/",$telefone))
{
if(!preg_match("/^([a-z0-9_\.-]+)@([a-z0-9_\.-]+)\.([a-z\.]{2,6})$/ui",$email))
{
echo "<h2>Помилка!</h2>";
echo "<h3>Введіть e-mail у форматі user@gmail<h3><br>";
echo "<p><a href=\"form.html\">";
echo "<h4>У формі некоректні дані!</4></a></p>"; 
}
else if(!preg_match("/^\+380([0-9]{9})$/",$telefone))
{
echo "<h2>Помилка!</h2>";
echo "<h3>Введіть телефон у форматі: +380000000000</h3><br><br>";
echo "<p><a href=\"form.html\">";
echo "_Повернутись до форми_</a></p>"; 
}
else
{
  echo "<h2>Помилка!</h2>";
  echo "<h3>Email повинен бути у форматі login@domen<h3><br>Телефон повинен бути в наступголму форматі: +380000000000</h3><br>";
  echo "<p><a href=\"form.html\">";
  echo "<h4>У формі некоректні дані</4></a></p>"; 
}
}
else {
  echo "<h2>Вітаю</h2>";
echo "<h3>Ви ввели всі дані правильно</h3><br>";

try {
  $db = new PDO("mysql:host=$dbhost; dbname=$dbname", $username, $password); 

  $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $sql = "INSERT INTO `info`(`email`, `telefone`, `data`) 
  VALUES ('$email','$telefone','$data')";

  $db->exec($sql);
  echo "<h3>інформація збережена в Базі Даних:<br></h3>";
} catch(PDOException $e) {
  echo $sql . "<br>" . $e->getMessage();
}

$db = null;
}
?>

<?php
echo "<h4>Дані з БД:</h4>";
echo "<table style='border: solid 1px black;'>";
echo "<tr><th>Id</th><th>E-mail</th><th>Телефон</th><th>Дата</th></tr>";

class TableRows extends RecursiveIteratorIterator {
  function __construct($it) {
    parent::__construct($it, self::LEAVES_ONLY);
  }

  function current() {
    return "<td style='width:150px;border:1px solid black;'>" . parent::current(). "</td>";
  }

  function beginChildren() {
    echo "<tr>";
  }

  function endChildren()
  {
    echo "</tr>" . "\n";
  }
}

try {
  $conn = new PDO("mysql:host=$dbhost; dbname=$dbname", $username, $password);
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $stmt = $conn->prepare("SELECT id, email, telefone, data FROM info");
  $stmt->execute();


  $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
  foreach(new TableRows(new RecursiveArrayIterator($stmt->fetchAll())) as $k=>$v) {
    echo $v;
  }
} catch(PDOException $e) {
  echo "Помилка: " . $e->getMessage();
}
$conn = null;
echo "</table><br><br><br>";
?>

<?php
echo "<br><br><h3>Збережені дати у форматі день.місяць.рік:</h3>";

try {
  $conn = new PDO("mysql:host=$dbhost; dbname=$dbname", $username, $password);
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $stmt = $conn->prepare("SELECT data FROM info");
  $stmt->execute();
  

  $result = $stmt->setFetchMode(PDO::FETCH_COLUMN, 0);

  function DateEdit($Date)
 {
 $date_explode=explode("-", $Date);
 if($date_explode[0] > 31)
 {
 $day= $date_explode[2];
 $year = $date_explode[0];
 }
 else
 {
 $day= $date_explode[0];
 $year = $date_explode[2];
 }
 $result_date = $date_explode[1].'.'.$day.'.'.$year;
 return $result_date;
 }

  foreach($stmt->fetchAll() as $k=>$v) {
	$v = DateEdit($v);
	echo "<br>$v";
  }
} catch(PDOException $e) {
  echo "Помилка: " . $e->getMessage();
}
$conn = null;
echo "<br><br><br>";
?>
</body>
</html>
