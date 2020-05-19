<?php
ignore_user_abort(true);
ini_set('max_execution_time', 86400);
//ini_set('implicate_flush','On');
error_reporting(0);
$main_url=$_GET['url'];
$page_number=$_GET['p'];
$number_of_phones=$_GET['n'];
$phone_number=$_GET['phone'];
print('<form action="check_status.php" class="justify-content-center "><input style="margin-top:20px; width:100%; font-size:20px;" type="submit" name="status" value="Отслеживать состояние загрузки">
</form>');
ob_flush();
flush();
//Создание статусазапущено
$php_status = fopen('phpstatus.txt', 'w+');
fwrite($php_status, "run");
fclose($php_status);
//header("Content-type: text/html; charset=UTF-8");
//set_time_limit(3600000000);
//echo ini_get("max_execution_time");
$statusfp = fopen('status.txt', 'w+');
fwrite($statusfp, "$phone_number/$number_of_phones");
fclose($statusfp);
require_once 'simple_html_dom.php';
require_once 'classes.php';
require_once 'functions.php';
$url='https://api.proxyscrape.com/?request=getproxies&proxytype=socks4&timeout=10000&country=all';
download_proxy($url);
$max_pages=100;
//$number_of_phones=1500; //vvodim post
$url='';
$fp = fopen('data.txt', 'a+');
$mistakes = fopen('mistakes.txt', 'a+');
fwrite($mistakes, date('l jS \of F Y h:i:s A'));
//$array0[0]=''; $array1[0]=''; $array2[0]='';
while(1)
{
    $array0[0]='';
    $array1[0]='';
    $array2[0]='';
    //Проверка сигналов
    $signal=htmlentities(file_get_contents("signal.txt"));
    if($signal=="stop")
    {
        $php_status = fopen('phpstatus.txt', 'w+');
        fwrite($php_status, "done");
        fclose($php_status);
        exit;
    }
	$url=$main_url.'?p='.$page_number;
	fwrite($mistakes, $url);
	$time_sleep=rand(5,6);
	$html=Curl_avito($url,$time_sleep,$mistakes);
	//fwrite($mistakes, $html);
	foreach($html->find('div.index-root-2c0gs') as $html_div)
	{
	    $html=$html_div;
	}
	//echo "<br>nulevoy cicl<br>";
	fwrite($mistakes, 'nulevoy cicl');
	//echo $html;
	
	//Проверка сигналов
    $signal=htmlentities(file_get_contents("signal.txt"));
    if($signal=="stop")
    {
    $php_status = fopen('phpstatus.txt', 'w+');
    fwrite($php_status, "done");
    fclose($php_status);
    exit;
    }
    
	foreach($html->find('div.snippet-horizontal') as $href_div)
	{
		$id=$href_div->attr['data-item-id'];
		if($id%2!=0)
		{
			$array0[$id].=$href_div->attr['data-pkey'];
			if($array0[$id]!='')
			{
				$id_array[]=$id;
				//echo $id.") ",$array0[$id],"<br>";
				fwrite($mistakes, $id);
			    foreach($href_div->find('a.snippet-link') as $href_to_check)
				{ fwrite($mistakes, $id.") ".$href_to_check->href."<br>");}								//убрать
			}
		}
	}
    
	$html->clear(); // подчищаем за собой
    unset($html);
	$max_id=count($id_array);
    //echo "<br>perviy cicl<br>";
    fwrite($mistakes, 'perviy cicl');
	$time_sleep=rand(5,6);
	$html=Curl_avito($url,$time_sleep,$mistakes);
	foreach($html->find('div.index-root-2c0gs') as $html_div)
	{
	    $html=$html_div;
	}
	//echo $html;
	
    //Проверка сигналов
    $signal=htmlentities(file_get_contents("signal.txt"));
    if($signal=="stop")
    {
        $php_status = fopen('phpstatus.txt', 'w+');
        fwrite($php_status, "done");
        fclose($php_status);
        exit;
    }
    
	for($id_numer=0;$id_numer<$max_id; $id_numer++)
	{
		$id=$id_array[$id_numer];
		foreach($html->find("div[data-item-id=$id]") as $href_div)
		{
		        if(isset($href_div)){$array1[$id].=$href_div->attr['data-pkey']; fwrite($mistakes, $id.") ".$array1[$id]."<br>");}
		}
	}
	$html->clear(); // подчищаем за собой
    unset($html);
	//echo "<br>vtoroy cicl<br>";
	fwrite($mistakes, 'vtoroy cicl');
	$checked_id=2;
	$time_sleep=rand(6,7);
	$html=Curl_avito($url,$time_sleep,$mistakes);
	foreach($html->find('div.index-root-2c0gs') as $html_div)
	{
	    $html=$html_div;
	}
	//echo $html;
	for($id_numer=0;$id_numer<$max_id; $id_numer++)
	{
		$id=$id_array[$id_numer];
		foreach($html->find("div[data-item-id=$id]") as $href_div)
		{
		if(isset($href_div))
		{$array2[$id].=$href_div->attr['data-pkey']; fwrite($mistakes, $id.") ".$array2[$id]."<br>");}
		if($array0[$id]!='' && $array1[$id]!='' && $array2[$id]!='')
		{           
				    //echo "<br>Номер )",$id,"<br>";
	            	fwrite($mistakes, $checked_id."  Внутри ласт цикла   ".$phone_number.") ".$array0[$id]."   ".$array1[$id]."    ".$array2[$id]."    ");
            		$phone_item_only0=$array0[$id];
            		$phone_item_only1=$array1[$id];
            		$phone_item_only2=$array2[$id];
              		$url=find_phone_url($id, $phone_item_only0, $phone_item_only1, $phone_item_only2);
            		fwrite($mistakes, "<br>Фоне юрл".$checked_id.")".$url."<br>");
            		$time_sleep=rand(6,7);
            		$imgContent = Curl_avito($url,$time_sleep,$mistakes);
            		//echo "<br>".$imgContent."<br>";
            		$avitoContact = new AvitoContact;
            		$imgContent = explode('base64,', $imgContent)[1];
            		//echo "<br>".$imgContent."<br>";
                	$a = fopen('phone.png', 'wb');
                	fwrite($a, base64_decode($imgContent));
            		fclose($a);
	            	$image='phone.png';
	            	$result = $avitoContact->recognize('phone.png');
	            	if ($result) 
	            	{
	            	    //print("<p>Phone number: ".$result."</p>");
	            	    //flush();
	            	    //ob_flush();
	                 	fwrite($mistakes, "<br>Phone number: ".$result."<br>");
	                  	fwrite($fp, $result.",");
	                  	$statusfp = fopen('status.txt', 'w+');
                    	fwrite($statusfp, $phone_number."/".$number_of_phones);
                    	fclose($statusfp);
	                  	if($phone_number==$number_of_phones){break;}
						$phone_number++;
	            	} 
	            	else 
                	{
                		fwrite($mistakes, '<h2 class="text-danger">Ничего не получилось</h2>');
	            	}
	            	$checked_id++;
					//Проверка сигналов
                    $signal=htmlentities(file_get_contents("signal.txt"));
                    if($signal=="stop")
                    {
                     $php_status = fopen('phpstatus.txt', 'w+');
                     fwrite($php_status, "done");
                     fclose($php_status);
                     exit;
                    }
	    	}
	    }
	}
	$html->clear(); // подчищаем за собой
    unset($html);
    $id_array[]->clear();
    unset($id_array[]);
    $array0->clear();
    unset($array0);
    $arra1->clear();
    unset($array1);
    $array2->clear();
    unset($array2);
	fwrite($mistakes, date('l jS \of F Y h:i:s A'));
	fwrite($mistakes, "Страница номер $page_number");
    $page_number=$page_number+1;
	//header("Location:find_href.php?url=$main_url&n=$n&phone=$phone_number&p=$page_number");
	//exit;
}
//Создание статуса закончено
$php_status = fopen('phpstatus.txt', 'w+');
fwrite($php_status, "done");
fclose($php_status);
fwrite($mistakes, date('l jS \of F Y h:i:s A'));
fwrite($mistakes, "Exel");
fclose($fp);
fclose($mistakes);
exit;
?>
