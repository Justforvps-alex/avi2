<?php
$string_phone=0;
$all_phones=htmlentities(file_get_contents("data.txt"));
//echo $all_phones;
$array_phones=preg_split("/[\s,]+/",$all_phones);
//print_r($array_phones);
require_once 'PHPExcel/Classes/PHPExcel.php'; //Подключаем библиотеку
$phpexcel = new PHPExcel(); //Создаем новый Excel файл
$page_excel = $phpexcel->setActiveSheetIndex(0); //Устанавливаем активный лист
$page_excel->setTitle("Phones"); //Записываем название 
$page_excel->setCellValue("A1", "Телефоны");
while($array_phones[$string_phone]!='')
{
	$number=$string_phone+2;
	$page_excel->setCellValue("A$number", $array_phones[$string_phone]);
	$string_phone++;
}
$objWriter = PHPExcel_IOFactory::createWriter($phpexcel, 'Excel2007'); //Формат
$objWriter->save("phones.xlsx"); //Сохраняем
//echo 'File created successfuly'; //Сообщаем о создании файла
$file="phones.xlsx";
file_force_download($file);
function file_force_download($file) {
  if (file_exists($file)) {
    // сбрасываем буфер вывода PHP, чтобы избежать переполнения памяти выделенной под скрипт
    // если этого не сделать файл будет читаться в память полностью!
    if (ob_get_level()) {
      ob_end_clean();
    }
    // заставляем браузер показать окно сохранения файла
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename=' . basename($file));
    header('Content-Transfer-Encoding: binary');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($file));
    // читаем файл и отправляем его пользователю
    readfile($file);
    exit;
}
}
?>