<html>
	<meta charset="UTF-8">
</html>
<?php
require_once "parserLib.php";
function screen($url) 
{
    //$url = "http://api.s-shot.ru/".$extn."/".$size."/".$format."/?".urlencode($url);
    $url="http://mini.s-shot.ru/2000*10000/1980/jpeg/?https://vk.com/plantro?w=wall-43932139_29045";
    $str = file_get_contents($url);
    file_put_contents("../"."screen.jpeg", $str); // тут лучше указать путь куда сохранять
}

$token
	= "92b73575a455b69bd32a54215038a3a74e7997d73923a364bd93790912b7f576c18b813f440348dfb5321&expires_in=0&user_id=152223765";
$delta    = "100";
$app_id   = "4832378";
$post_id="29045";
$group_id = "43932139";
$post_string="wall-43932139_29045";
$log_file="../tank_log.txt";
$users_log="../users_log.txt";
$last_num=false;
$vk = new vk( $token, $delta, $app_id, $group_id );

writeLog("start", $log_file);
for($k=2; $k<10; ++$k){
	$comments=$vk->getComments($post_id, $k,"desc");
	for($i=1; $i<sizeof($comments)-1;++$i){
		(int) $comment1=(int) $comments[$i]->text;
		(int) $comment2=(int) $comments[$i+1]->text;
		if($comment1 && $comment2) {//блок запустится, если оба комментария являются числами
			if(($comment2-$comment1)!=1){//если два числа отличаются более чем на единицу, выбираем большее из них
				$comment2>$comment1?$last_num=$comment2:$last_num=$comment1;
				for($j=0; $j < 2; ++$j){//записываем обоих пользователей в лог
					$user=$vk->getOneUser($comments[$i+$j]->from_id);
					$date = date("H:i", $comments[$i+$j]->date);
					writeLog("$user->first_name $user->last_name id$user->uid date: $date "."text: ".$comments[$i+$j]->text, $users_log);
				}
			} else { //Если отлчиче в 1  то выбираем последний из них
				$last_num=$comment1;
				break;
			}
		} else{
			//определяем какой именно комментарий является числом, либо у нас обе строки
			if($comment1 !=0 ) $last_num = $comment1;
			if($comment2 !=0 ) $last_num = $comment2;
			if($last_num) break;//если нашли число - выходим из внутреннего цикла
		}
	}
	if($last_num) break;//если нашли число - выходим из внешнего цикла
	//иначе - берем массив из уже 3,4,5,6 комментариев до тех пор пока не дойдем до числа
}
writeLog("last_num = $last_num", $log_file);
if(rand(0, 99) < 10 && $comments[1]->from_id != "152223765" && $last_num > 0 && $last_num < 25000) {
	if($vk->setOnline()) writeLog("set online", $log_file);
	else writeLog("cant set online", $log_file);
	$count=rand(1,2);//определяет вероятность запостить 2 или 1 пост
	for($i=0; $i < $count; ++$i){
		if($last_num > 0) {
			-- $last_num;
			if ( $vk_comment = $vk->addComment( $last_num, $post_id, null ) ) writeLog( "add comment: $last_num", $log_file );
			else	writeLog( "cant add comment" , $log_file );
		}
	}
}
if($last_num > 0 && $last_num < 45) {
	$mail="good-1991@mail.ru";
	mail( $mail, "Танки!!!", $message );
	writeLog( "send email $mail", $log_file );
}
if($last_num > 20 && $last_num < 30){
	if($vk->getRepost($post_string, null)) writeLog("get repost from vk.com/$post_string", $log_file);
	else writeLog("cant repost from vk.com/$post_string", $log_file);
}
//screen(NULL);
writeLog("stop", $log_file);
?>