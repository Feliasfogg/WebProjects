<html>
	<meta charset="UTF-8">
</html>
<?php
chdir( "/home/user1137761/www/chehov.akson.by/parser" );
require_once '../simplehtmldom/simple_html_dom.php';
require_once 'parserLib.php';

$url  = "https://m.vk.com/wall-32434505_162";
$group = '32434505';
$post='162';


$html = file_get_html( $url );
if ( ! $html ) {
	connect($dbhost, $dbusername, $dbpass, $db_name);
	addError();
}

//ищем авторов
$fnd_author  = $html->find( 'a.pi_author' );//в масссиве вк всегда 51 коммент
$author = end( $fnd_author );
if ( $author->innertext == "Официальное сообщество Plantronics" ) {
		$message = "Message from admin";
		mail( "pavel.felias@gmail.com", "Chat", $message );
	}


$count=sizeof($fnd_author)-1;
while(1){//проверяем айдишники последних авторов
	sscanf( $fnd_author[$count], "<a class=\"pi_author\" href=\"/%s\">", $temp_author1 );
	$temp_author1 = substr( $temp_author1, 0, strpos( $temp_author1, "\">" ) );
	sscanf( $fnd_author[$count-1], "<a class=\"pi_author\" href=\"/%s\">", $temp_author2 );
	$temp_author2 = substr( $temp_author2, 0, strpos( $temp_author2, "\">" ) );

	if($temp_author1==$temp_author2){
		--$count;
	}
	else {
		$author=$fnd_author[$count];
		break;
	}
}
//обрабатываем последнего комментировавшего автора
sscanf( $author, "<a class=\"pi_author\" href=\"/%s\">", $author_id );
$author = trim( $author->innertext );
sscanf( $author, "%s %s", $first_name, $last_name );
echo "$first_name $last_name </br>";
$author_id = substr( $author_id, 0, strpos( $author_id, "\">" ) );
echo "<a href=\"https://vk.com/$author_id\">$author_id<a/></br>";


//ищем время
$fnd_time         = $html->find( 'a.item_date' );
$comment_time = $fnd_time[$count-1];
$comment_time = $comment_time->innertext;
$comment_time = substr($comment_time, -5, 5);
echo "$comment_time ";

sscanf( $comment_time, "%d:%d", $hour, $min );
$minLastComm = $hour * 60 + $min;
echo "$minLastComm </br>";//минуты последнего коммента

$currentDate = date("d.m.Y");
$currentTime = date( "H:i" );
echo "$currentTime ";//текущее время

sscanf( $currentTime, "%d:%d", $hour, $min );
$currentMin = $hour * 60 + $min;
$comment_life = $currentMin - $minLastComm;//разница в минутах
if($comment_life<0){
	$currentMin+=1440;
	$comment_life=$currentMin-$minLastComm;
}
echo "$currentMin</br>";
echo "DIFF = $comment_life </br>";
echo "<a href=\"".$url."?post_add#post_add\">ADD POST</a></br>";
$html->clear();//очистка памяти от объекта
unset( $html );

if($comment_life<0 && $currentMin>=6 && $currentMin<=12 && $author_id != "id152223765"){
	$arr = array( "Эх", "доброй ночи ребят", "хорошей ночки", "продуктивно посидеть",
		"Доброй ночи", "спокойной", "эх...", "удачи неспящим", "все неспят", "все неспите)))",
		"интересно, какой будет рекорд", "стерегите голду))", "наступает ночь", "у меня уже стемнело",
		"&#128522;", "&#128515;", "&#128521;", "&#128540;", "&#128518;", "&#128527;", "всем спать &#128540;",
		"стемнело на дворе..."
		);
	$txt=$arr[rand(0, sizeof($arr)-1)];

	connect($dbhost, $dbusername, $dbpass, $db_name);
	wallComment($txt, $group, $post);
}

//connect($dbhost, $dbusername, $dbpass, $db_name);
//searchUser("id152223765");
//wallComment(NULL, $group, $post);

if ( $comment_life >=200 && $comment_life<65){
	if (($comment_life >= 45+rand(0, 5)) && $comment_life < 65 && $author_id != "id152223765"){
	 connect($dbhost, $dbusername, $dbpass, $db_name);
	 wallComment(NULL, $group, $post);//самая важная функция
	}

	connect($dbhost, $dbusername, $dbpass, $db_name);
	$row = searchUser( $author_id );
	if ( $row ) {
		$user_id = $row['id'];
		if ( $user_id ) {
			addComment( $user_id, $comment_life, $comment_time, $currentDate);
		}
		getCommentsCount( $user_id );
	} else {
		addUser( $first_name, $last_name, $author_id );
		$row     = searchUser( $author_id );
		$user_id = $row['id'];//получаем id вновь добавленного пользователя
		addComment( $user_id, $comment_life, $comment_time, $currentDate );
		getCommentsCount( $user_id );
	}
	commentStat($currentDate);
}
if( $comment_life >= 200 && $comment_life<65) {
		$message = "winner is $first_name $last_name";
		mail( "pavel.felias@gmail.com", "Winner", $message );
		$sms = file_get_contents( "http://sms.ru/sms/send?api_id=b8646699-0b12-1c14-ad92-7ab16971b8a1&to=375259466591&text="
				                     . urlencode( iconv( "utf-8",
					"utf-8",
					"Winner is $first_name $last_name" ) ) );
}

 connect($dbhost, $dbusername, $dbpass, $db_name);
 $n=getCommentNum($author_id);
 if($n) echo "LONG = $n";
?>