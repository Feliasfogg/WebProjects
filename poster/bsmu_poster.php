<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; CHARSET=windows-1251">
</head>
</html>
<?php
require_once '../simplehtmldom/simple_html_dom.php';
require_once 'VKclass.php';
require_once '../app_config.php';
ini_set('display_errors','Off');
function connect($dbhost, $dbusername, $dbpass, $db_name){
	$dbconnect = mysql_connect( $dbhost, $dbusername, $dbpass )
	or die( "<p>Ошибка подключения к базе данных: " . mysql_error() . "</p>" );
	mysql_select_db( $db_name ) or die ( "<p>Невозможно выбрать базу: "
	                                     . mysql_error() . "</p>" );
}
function searchArticle($link) {
	$query = "SELECT * FROM Articles WHERE link='{$link}';";
	$res = mysql_query( $query )
	or die( "<p>searchUser Невозможно сделать запрос поиска новости: "
	        . mysql_error() . "</p>" );
	$row = mysql_fetch_array( $res );//получение результата запроса из базы;
	return $row;
}

function addArticle($title, $link ) {//добавление новости
	$query = "INSERT INTO Articles (title, link) VALUES ('{$title}', '{$link}');";
	$result = mysql_query( $query )
	or die( "<p>Невозможно добавить новость " . mysql_error() . "</p>" );
}
//инациализация вк
$token = '92b73575a455b69bd32a54215038a3a74e7997d73923a364bd93790912b7f576c18b813f440348dfb5321&expires_in=0&user_id=152223765';
$delta = '100';
$app_id = '4832378';
$group_id = '85303665';
$vk = new vk( $token, $delta, $app_id, $group_id );

//парсинг
$url  = "http://www.bsmu.by/allarticles/";
$html=file_get_html($url);
$links=$html->find('.InText a');

connect($dbhost, $dbusername, $dbpass, $db_name);

if(count($links)){
	foreach($links as $href) {
			$res=searchArticle($href->href);
			if($res[0]==NULL){
				addArticle( $href->innertext, $href->href );
				$vk_post = $vk->post( null, null, $href->href );
			}
	}
}
?>