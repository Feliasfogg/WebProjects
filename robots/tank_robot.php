<html>
	<meta charset="UTF-8">
</html>
<?php
require_once 'parserLib.php';
function screen($url) 
{
    //$url = "http://api.s-shot.ru/".$extn."/".$size."/".$format."/?".urlencode($url);
    $url='http://mini.s-shot.ru/2000*10000/1980/jpeg/?https://vk.com/plantro?w=wall-43932139_29045';
    $str = file_get_contents($url);
    file_put_contents("../".'screen.jpeg', $str); // тут лучше указать путь куда сохранять
}

$token
	= '92b73575a455b69bd32a54215038a3a74e7997d73923a364bd93790912b7f576c18b813f440348dfb5321&expires_in=0&user_id=152223765';
$delta    = '100';
$app_id   = '4832378';
$post_id='29045';
$group_id = '43932139';
$post_string='wall-43932139_29045';
$log_file='../tank_log.txt';
$last_num=false;
writeLog('start script', $log_file);

$vk       = new vk( $token, $delta, $app_id, $group_id );
$comments=$vk->getComments($post_id, 3, 'desc');

if($comments[1]->from_id != '152223765') {
	$last_num = $comments[1]->text;
	--$last_num;
}
if($last_num && $last_num !=-1 && $last_num < 82) {
	if($vk->setOnline()) writeLog('set online', $log_file);
	else writeLog('cant set online', $log_file);

	if($vk_comment=$vk->addComment($last_num, $post_id, NULL)) writeLog('add comment: '.$last_num, $log_file);
	else writeLog('cant add comment '.$last_num, $log_file);
	--$last_num;
	if($vk_comment=$vk->addComment($last_num, $post_id, NULL)) writeLog('add comment: '.$last_num, $log_file);
	else writeLog('cant add comment '.$last_num, $log_file);

	if($last_num < 25)	mail( "pavel.felias@gmail.com", 'Танки!!!', $message );
}
//screen(NULL);
writeLog('stop script', $log_file);
?>