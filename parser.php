<html>
	<meta charset="UTF-8">
</html>
<?php
chdir( "/home/user1137761/www/chehov.akson.by" );
require_once 'simplehtmldom/simple_html_dom.php';
require_once 'app_config.php';
require_once 'poster/VKclass.php';
/*$e->tag            Читает или записывает имя тега элемента.
$e->outertext   Читает или записывает весь HTML элемента, включая его самого.
$e->innertext   Читает или записывает внутренний HTML элемента
$e->plaintext    Читает или записывает простой текст элемента, это эквивалентно функции strip_tags($e->innertext).
Хотя поле доступно для записи, запись в него ничего не даст, и исходный html не изменит
*/
function searchUser(
	$author_id
) {//ищем юзера по url возвращаем в качестве результата всю строку row
	$query = "SELECT * FROM vk_users WHERE vk_id='{$author_id}';";
	$res = mysql_query( $query )
	or die( "<p>searchUser Невозможно сделать запрос поиска пользователя: "
	        . mysql_error() . "</p>" );
	$row = mysql_fetch_array( $res );//получение результата запроса из базы;
	return $row;
}

function addUser(
	$first_name, $last_name, $author_id
) {//добавление пользователя
	$query = "INSERT INTO vk_users (first_name, last_name, vk_id)" .
	         "VALUES ('{$first_name}', '{$last_name}', '{$author_id}');";
	$result = mysql_query( $query )
	or die( "<p>Невозможно добавить пользователя " . mysql_error() . "</p>" );
}

function addComment(
	$user_id, $comment_life, $comment_time, $currentDay
) {//добавляем комментари

	$query="SELECT MAX(id) FROM vk_comments WHERE user_id='{$user_id}';";
	$res = mysql_query( $query ) or die( "<p>searchUser Невозможно сделать запрос поиска комментария для пользователя $user_id: "
	                                     . mysql_error() . "</p>" );
	$row=mysql_fetch_row($res);
	$maxID=$row[0];

	$query="SELECT * FROM vk_comments WHERE id='{$maxID}';";
	$res = mysql_query( $query ) or die( "<p>searchUser Невозможно сделать запрос поиска комментария для пользователя $user_id: "
	                                   . mysql_error() . "</p>" );
	$row=mysql_fetch_row($res);

	if ( $row ) {
		if ( $row[3] != $comment_time ) {
			$query
				= "INSERT INTO vk_comments (user_id, comment_life, comment_time, day) VALUES ('{$user_id}', '{$comment_life}', '{$comment_time}', '{$currentDay}');";
			$res = mysql_query( $query )
			or die( "<p>Невозможно сделать запись комментария: " . mysql_error()
			        . "</p>" );
		} else {
			$query = "UPDATE vk_comments SET comment_life='{$comment_life}' WHERE user_id='{$user_id}' AND id='{$maxID}';";
			$res = mysql_query( $query ) or die( "<p>addComment Невозможно обновить время жизни комментария для пользователя $user_id: "
	                                     . mysql_error() . "</p>" );
		}
	} else {
		$query
			= "INSERT INTO vk_comments (user_id, comment_life, comment_time, day) VALUES ('{$user_id}', '{$comment_life}', '{$comment_time}', '{$currentDay}');";
		$res = mysql_query( $query )
		or die( "<p>Невозможно сделать запись комментария: " . mysql_error()
		        . "</p>" );
	}

	return $res;
}

function getCommentsCount( $u_id ) {
	$query = "SELECT * FROM vk_comments WHERE user_id='{$u_id}';";
	$res = mysql_query( $query )
	or die( "<p>getCommentsCountНевозможно сделать запрос поиска пользователя: "
	        . mysql_error() . "</p>" );
	$rows  = mysql_num_rows( $res );
	$query = "UPDATE vk_users SET all_comments='{$rows}' WHERE id='$u_id';";
	$res = mysql_query( $query )
	or die( "<p>Невозможно сделать запрос поиска пользователя: " . mysql_error()
	        . "</p>" );
}

function getCommentNum($vk_id){
	$query="SELECT * FROM vk_users WHERE vk_id='{$vk_id}';";
	$res=mysql_query($query);
	$row=mysql_fetch_row($res);
	if($row){
		$comments_num=$row[4];
		return $comments_num;
	} else return 0;
}

function commentStat($currentDay){
 	$all_results=array(0=>20, 1=>30, 2=>40, 3=>50);
 	$fields=array(0=>'comment_life_20', 1=>'comment_life_30', 2=>'comment_life_40', 3=>'comment_life_50');

 	for($i=0; $i<4; $i++){
 		$query="SELECT * FROM vk_comments WHERE comment_life>=$all_results[$i]&&comment_life<($all_results[$i]+10) AND day='{$currentDay}';";
 		$res = mysql_query( $query )
			or die( "<p>commentStat Невозможно сделать запрос для анализа статистики: "
	        . mysql_error() . "</p>" );
		$rows_num=mysql_num_rows($res);

		if($rows_num>=0){
			$query="SELECT * FROM vk_comment_stat WHERE day='{$currentDay}';";
			$res = mysql_query( $query )
			or die( "<p>getCommentsCountНевозможно сделать запрос для анализа статистика: "
	        . mysql_error() . "</p>" );
			$row=mysql_fetch_row($res);
			if($row){
				$query="UPDATE vk_comment_stat SET $fields[$i]='$rows_num' WHERE day='{$currentDay}';";
				$res = mysql_query( $query )
					or die( "<p>getCommentsCountНевозможно сделать запрос для анализа статистика: "
	        		. mysql_error() . "</p>" );
			} else {
				$query="INSERT INTO vk_comment_stat (comment_life_20, comment_life_30, comment_life_40, comment_life_50, day) 
				VALUES ('0','0','0','0','{$currentDay}');";
				$res = mysql_query( $query )
					or die( "<p>getCommentsCountНевозможно сделать запрос для анализа статистики: "
	        		. mysql_error() . "</p>" );
	        	$query="UPDATE vk_comment_stat SET $fields[$i]='$rows_num' WHERE day='{$currentDay}';";
				$res = mysql_query( $query )
					or die( "<p>getCommentsCountНевозможно сделать запрос для анализа статистика: "
	        		. mysql_error() . "</p>" );
			}
		}

 	}
}
	function connect($dbhost, $dbusername, $dbpass, $db_name){
	$dbconnect = mysql_connect( $dbhost, $dbusername, $dbpass )
	or die( "<p>Ошибка подключения к базе данных: " . mysql_error() . "</p>" );
	//говорим базе что записываем в нее все в utf8
	mysql_query( "SET NAMES 'utf8';" );
	mysql_query( "SET CHARACTER SET 'utf8';" );
	mysql_query( "SET SESSION collation_connection = 'utf8_general_ci';" );
	mysql_select_db( $db_name ) or die ( "<p>Невозможно выбрать базу: "
	                                     . mysql_error() . "</p>" );
	}
	function wallComment($txt){
		$token = '92b73575a455b69bd32a54215038a3a74e7997d73923a364bd93790912b7f576c18b813f440348dfb5321&expires_in=0&user_id=152223765';
		$delta = '100';
		$app_id = '4832378';
		$group_id = '43932139';//plantonics
		$post_id='5970';//tank post
		$phrases = array(
   		    "Долго","Хватит с тебя","это еще не все","Хорошая попытка)","Lol^^",
  		  	"OMG","Хачу галду!))","отдохните","ясно(","сорян((","лолки вы", "хмммм &#128529;",
			"норм","нормас продержался)","ну ок...","хватит уже","слишком долго",
			"идите отдыхать","и чего вам все неймется","так-то","эх", "и даже так..",
			"все тут сидите","ну почти))", "хорош", "много минут", "хватит наверное", 
			"достаточно", "круто однако", "ну как вы тут?","однако, долго","Ап","UP",
			"тутэ", "написал пост - пошел спать", "сделал дело и спать", "up", "norm",
			"ага)", "угу...", "aga;)", "отдыхать идите))", "лал", "вы так не шутите",
			"почти испугался", "фух, пронесло", "сгонял за чаем)", "не надо так", "лалки",
			"эээээээ, не шутите так", "лолд", "-_-", "^_^" , "бываит)))", "от оно как...",
			"ничоси", "нифигаси", "фигасе))", "на страже)", "стерегу голду)))", "ничавоси",
			"&#128522;", "&#128515;", "&#128521;", " &#128518;", "&#128540;", "&#128523;", "&#128526;",
			"&#128527;", " &#128528;", "&#128516;", " &#128556;", "&#128512;", "&#128517;"
			);
		$phrase=$phrases[rand(0, sizeof($phrases)-1)];
		$vk = new vk( $token, $delta, $app_id, $group_id );
		$vk_online=$vk->setOnline(0);
		$currentDay=date("d.m.Y");
		$currentTime = date( "H:i" );
		$query;
		if($txt){//если задана строка- постим строку
		 $vk_comment = $vk->addComment($txt, $post_id, NULL);
		 $query = "INSERT INTO vk_answers (phrase, time, day) VALUES ('{$txt}', '{$currentTime}', '{$currentDay}');";
		}
		else {
			if(rand(0, 3) == 3){
			 $stckr=rand(97, 117);
			 $vk_comment = $vk->addComment(NULL, $post_id, $stckr);//30% шанс запостить стикер 
			 $query="INSERT INTO vk_answers (phrase, time, day) VALUES ('{$stckr}', '{$currentTime}', '{$currentDay}');";
			}
			else{
				$query="SELECT MAX(num) FROM vk_answers;";
				$res = mysql_query( $query );
				$row=mysql_fetch_row($res);
				while(1){//проверка не совпадает ли последняя фраза в базе с текущей выбранной рандомом
					if($row[1]==$phrase) $phrase=[rand(0, sizeof($phrases)-1)];
					else break;
				}
				if(rand(0, 3) == 3) {//30%шанс добавить к фразе смайл
					$smiles=array(
					"&#128522;", "&#128515;", "&#128521;", " &#128518;", "&#128540;", "&#128523;", "&#128526;",
					"&#128527;", " &#128528;", "&#128516;", " &#128556;", "&#128512;", "&#128517;"
					);
					$smile=$smiles[rand(0, sizeof($smiles)-1)];
					if($phrase!=$smile) $phrase.=$smile;
				}
				$vk_comment = $vk->addComment($phrase, $post_id, NULL);
				$query = "INSERT INTO vk_answers (phrase, time, day) VALUES ('{$phrase}', '{$currentTime}', '{$currentDay}');";
			}
		}

		$res = mysql_query( $query )
			or die( "<p>commentStat Невозможно сделать добавление ответа в базу"
	        . mysql_error() . "</p>" );
	}

$url  = "https://m.vk.com/wall-43932139_5970";
$html = file_get_html( $url );
if ( ! $html ) {
	connect($dbhost, $dbusername, $dbpass, $db_name);
	$currentDay=date("d.m.Y");
	$currentTime = date( "H:i" );
	$query = "INSERT INTO vk_errors (time, day) VALUES ('{$currentTime}', '{$currentDay}');";
	$res = mysql_query( $query )
			or die( "<p>commentStat Невозможно сделать запрос для анализа статистики: "
	        . mysql_error() . "</p>" );
}
$fnd_author  = $html->find( 'a.pi_author' );//в масссиве вк всегда 51 коммент
$fnd_comment = $html->find( 'div.pi_text' );

$author = end( $fnd_author );
if ( $author == "Официальное сообщество Plantronics" ) {
		$message = "Message from admin";
		mail( "pavel.felias@gmail.com", "Chat", $message );
	}
sscanf( $author, "<a class=\"pi_author\" href=\"/%s\">", $author_id );
$author = trim( $author->innertext );
sscanf( $author, "%s %s", $first_name, $last_name );
echo "$first_name $last_name </br>";
$author_id = substr( $author_id, 0, strpos( $author_id, "\">" ) );
echo "<a href=\"https://vk.com/$author_id\">$author_id<a/></br>";

$fnd          = $html->find( 'a.item_date' );
$comment_time = end( $fnd );
$comment_time = $comment_time->innertext;
$comment_time = substr( $comment_time, 18 );
echo "$comment_time ";

sscanf( $comment_time, "%d:%d", $hour, $min );
$minLastComm = $hour * 60 + $min;
echo "$minLastComm </br>";//минуты последнего коммента

$currentDay=date("d.m.Y");
$currentDate = date( "H:i" );
echo "$currentDate ";//текущее время

sscanf( $currentDate, "%d:%d", $hour, $min );
$currentMin = $hour * 60 + $min;
echo "$currentMin</br>";
$comment_life = $currentMin - $minLastComm;//разница в минутах
echo "DIFF = $comment_life </br>";
echo "<a href=\"https://m.vk.com/wall-43932139_5970?post_add#post_add\">ADD POST</a></br>";
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
	wallComment($txt);
}

//wallComment($res_str);
if ( $comment_life >=20 ){
	if (($comment_life >= 45+rand(0, 5)) && $comment_life < 65 && $author_id != "id152223765"){
	 connect($dbhost, $dbusername, $dbpass, $db_name);
	 wallComment(NULL);//самая важная функция
	}

	connect($dbhost, $dbusername, $dbpass, $db_name);
	$row = searchUser( $author_id );
	if ( $row ) {
		$user_id = $row['id'];
		if ( $user_id ) {
			addComment( $user_id, $comment_life, $comment_time, $currentDay);
		}
		getCommentsCount( $user_id );
	} else {
		addUser( $first_name, $last_name, $author_id );
		$row     = searchUser( $author_id );
		$user_id = $row['id'];//получаем id вновь добавленного пользователя
		addComment( $user_id, $comment_life, $comment_time, $currentDay );
		getCommentsCount( $user_id );
	}
	commentStat($currentDay);
}
if( $comment_life >= 60 && $comment_life<65) {
		$message = "winner is $first_name $last_name";
		mail( "pavel.felias@gmail.com", "Winner", $message );
		$sms = file_get_contents( "http://sms.ru/sms/send?api_id=b8646699-0b12-1c14-ad92-7ab16971b8a1&to=375259466591&text="
				                     . urlencode( iconv( "windows-1251",
					"utf-8",
					"Winner is $first_name $last_name" ) ) );
}

 connect($dbhost, $dbusername, $dbpass, $db_name);
 $n=getCommentNum($author_id);
 if($n) echo "LONG = $n";
?>