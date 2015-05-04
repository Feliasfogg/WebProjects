<?php
function add_folder($pathdir, $zip){
	global $len;
	$current_folder=substr($pathdir, $len);
    $res=$zip->addEmptyDir($current_folder);
    $pathdir.="/"; $current_folder.="/";
    $dir=opendir($pathdir);
    while($file=readdir($dir)){
        if (is_file($pathdir.$file)){ // проверяем файл ли мы взяли из папки
                    $zip -> addFile($pathdir.$file, $current_folder.$file); // и архивируем
                }
        if(is_dir($pathdir.$file) && $file!="." && $file!=".."){
                    add_folder($pathdir.$file, $zip);
                }    
    }
}
function make_archive($pathdir, $nameArchive, $zip){
    if ($zip -> open($nameArchive, ZipArchive::CREATE) === TRUE){ // создаем архив, если все прошло удачно продолжаем
        $dir = opendir($pathdir); // открываем папку с файлами
        while( $file = readdir($dir)){ // перебираем все файлы из нашей папки
                if (is_file($pathdir.$file)){ // проверяем файл ли мы взяли из папки
                    $zip -> addFile($pathdir.$file, $file); // и архивируем
                }
                if(is_dir($pathdir.$file) && $file!='..' && $file!='.'){
                    add_folder($pathdir.$file, $zip);
                }
        }
        $zip -> close(); // закрываем архив.
    /*
    //Высылаем пользователю архив
    header ("Content-Type: application/octet-stream");
    header ("Accept-Ranges: bytes");
    header ("Content-Length: ".filesize($nameArhive));
    header ("Content-Disposition: attachment; filename=".$nameArhive);  
    readfile($filepath);
    //Удаляем файл
    unlink($nameArhive);
    */
    }else{
        die ('Произошла ошибка при создании архива');
    }
}
 $zip = new ZipArchive; // класс для работы с архивами
 $name="../archives/".date("d.m.y")." "."bsmu.akson.by.zip";
 $path='../../bsmu.akson.by/';
 $len=strlen($path);
 make_archive($path, $name, $zip);
?>