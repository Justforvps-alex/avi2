<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Статус последнего запроса</title>
<link rel="stylesheet" type="text/css" href="bootstrap.css">
<style type="text/css" media="screen, projection, print">
.text-center {
    margin-right: auto;
    margin-left: auto;
}
</style>
</head> 
<body>
<div style="margin-top:20px;" class="container">
<?php 
header("refresh: 10;");
$status=htmlentities(file_get_contents("status.txt"));
echo "<h3 style='text-align:center;'>Статус загрузки номеров: ".$status."</h3>";
?>
<a style="margin-top:20px; width:100%; font-size:20px;" class="btn btn-primary" href="txttoexcel.php" target="_blank">Скачать файл</a>
</div>
</body>
</html>