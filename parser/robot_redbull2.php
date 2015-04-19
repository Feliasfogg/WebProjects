<html>
	<meta charset="UTF-8">
</html>
<?php
chdir( "/home/user1137761/www/chehov.akson.by/parser" );
require_once '../simplehtmldom/simple_html_dom.php';
require_once 'parserLib.php';

writeLog("start script");
$token
	= '92b73575a455b69bd32a54215038a3a74e7997d73923a364bd93790912b7f576c18b813f440348dfb5321&expires_in=0&user_id=152223765';
$delta    = '100';
$app_id   = '4832378';
//redbool
$post_id='49259';
$group_id = '24881486';
//$post_id='162';
//$group_id='32434505';
$vk       = new vk( $token, $delta, $app_id, $group_id );

//ищем авторов
$comments=$vk->getComments($post_id, 20, 'desc');



for($i=1; $i<sizeof($comments); ++$i){//проверяем айдишники последних авторов
	if($comments[$i]->from_id!=$comments[$i+1]->from_id){
		$comment=$comments[$i];
		break;
	}
}

$comment_life=time()- $comment->date;
$user=$vk->getOneUser($comment->from_id);
$comment_life_min=$comment_life/60;
$str=$user[0]->first_name." ".$user[0]->last_name." id".$user[0]->uid." ".$comment_life_min." min";
writeLog($str);

if ( $comment_life_min >=900 + rand(0, 30) && $comment_life_min<1430 && $comment->from_id != '152223765'){
	$txt="Я хочу получить автограф легендарного Феликса";
	if($vk->setOnline()) writeLog('set online');
	if($vk_comment=$vk->addComment($txt, $post_id, NULL)) writeLog($txt);
}
if( $comment_life_min >= 1430 && $comment_life_min < 1440) {
		$message = "winner is $user[0]->first_name $user[0]->last_name";
		mail( "pavel.felias@gmail.com", "Winner", $message );
		$sms = file_get_contents( "http://sms.ru/sms/send?api_id=b8646699-0b12-1c14-ad92-7ab16971b8a1&to=375259466591&text="
				                     . urlencode( iconv( "utf-8",
					"utf-8", $message ) ) );
}
writeLog("stop script");
?>