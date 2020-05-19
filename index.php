<?php
	if($_POST['start'])
	{
		$url = $_POST['link'];
		$n= $_POST['phone_numbers'];
		$phpstatus=htmlentities(file_get_contents("phpstatus.txt"));
	if($phpstatus=="run")
	{
		$change_signal = fopen('signal.txt', 'w+');
        fwrite($change_signal, "stop");
        fclose($change_signal);
	}
	$time=0;
	while($phpstatus!="done")
	{
	    $phpstatus=htmlentities(file_get_contents("phpstatus.txt"));
	    sleep(5);
	    $time++;
	    if($time==36){break;}
	}
		$change_signal = fopen('signal.txt', 'w+');
		    fwrite($change_signal, "run");
		    fclose($change_signal);
		    $proxy=NULL;
		    $fp = fopen('data.txt', 'w');
		fwrite($fp, $main_url.",");
		    fclose($fp);
 		   $fp = fopen('peoxy.txt', 'w');
 		   fclose($fp);
  		  $mistakes = fopen('mistakes.txt', 'w');
    		fclose($mistakes);
		header("Location:find_href.php?url=$url&n=$n&phone=0&p=1");
	}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Домашняя страница</title>
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
<h3 style='text-align:center;'>Домашняя страница</h3>
<form method="post" class="justify-content-center ">
	<input style="margin-top:20px;" class="form-control" type="text" name="link" placeholder="Введите ссылку">
	<label for="phone_numbers">Количество телефонов</label>
	<select class="form-control" id="phone_numbers" name="phone_numbers">
	 <option>25</option>
	<option>100</option>
	<option>200</option>
	<option>500</option>
	<option>1000</option>
	<option>1500</option>
	<option>3000</option>
	</select>
	<input style="margin-top:20px; width:100%; font-size:20px;" type="submit" name="start" class="btn btn-primary" value="Начать новую загрузку">
</form>
<form action="check_status.php" class="justify-content-center ">
	<input style="margin-top:20px; width:100%; font-size:20px;" type="submit" name="status" class="btn btn-primary" value="Посмотреть статус последней загрузки">
</form>
</div>
</body>
</html>
