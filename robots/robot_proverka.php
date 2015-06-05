<html>
	<meta charset="UTF-8">
</html>
<?php
require_once 'parserLib.php';
$token
	= '92b73575a455b69bd32a54215038a3a74e7997d73923a364bd93790912b7f576c18b813f440348dfb5321&expires_in=0&user_id=152223765';
$delta    = '100';
$app_id   = '4832378';
$plantronics='43932139';
function searchArticle( $post_id ) {
	$query = "SELECT * FROM vk_posts WHERE post_id='{$post_id}';";
	$res = mysql_query( $query );
	if($res) {
		$row = mysql_fetch_array( $res );//получение результата запроса из базы;
		return $row;
	}else return NULL;
}

function addArticle( $post_id, $link) {//добавление новости
	$query
		= "INSERT INTO vk_posts (post_id, link) VALUES ('{$post_id}', '{$link}');";
	$result = mysql_query( $query );
}

$vk_plantr =new vk($token, $delta, $app_id, $plantronics);

$plantr_posts=$vk_plantr->searchPost('World of Tanks', 3);
if(sizeof($plantr_posts)){
	//оповещалка о новом конкурсе в плантрониксе
	connect($dbhost, $dbusername, $dbpass, $db_name);
	for($i=1; $i<sizeof($plantr_posts); ++$i){
		$res=searchArticle($plantr_posts[$i]->id);
		if ( !$res ) {
			$link="http://vk.com/wall-".$plantronics."_".$plantr_posts[$i]->id;
			addArticle( $plantr_posts[$i]->id, $link);
			mail( "good-1991@mail.ru", "Конкурс", "Возможно, конкурс" );
		}
	}
}
?>