<html>
	<meta charset="UTF-8">
</html>
<?php
require_once '../app_config.php';
require_once '../vk_auth/VKclass.php';
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
	writeLog($query);
	return $row;
}

function addUser(
	$first_name, $last_name, $author_id
) {//добавление пользователя
	$query = "INSERT INTO vk_users (first_name, last_name, vk_id)" .
	         "VALUES ('{$first_name}', '{$last_name}', '{$author_id}');";
	$result = mysql_query( $query )
	or die( "<p>Невозможно добавить пользователя " . mysql_error() . "</p>" );
	writeLog($query);
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
			writeLog($query);
		} else {
			$query = "UPDATE vk_comments SET comment_life='{$comment_life}' WHERE user_id='{$user_id}' AND id='{$maxID}';";
			$res = mysql_query( $query ) or die( "<p>addComment Невозможно обновить время жизни комментария для пользователя $user_id: "
	                                     . mysql_error() . "</p>" );
			writeLog($query);
		}
	} else {
		$query
			= "INSERT INTO vk_comments (user_id, comment_life, comment_time, day) VALUES ('{$user_id}', '{$comment_life}', '{$comment_time}', '{$currentDay}');";
		$res = mysql_query( $query )
		or die( "<p>Невозможно сделать запись комментария: " . mysql_error()
		        . "</p>" );
		writeLog($query);
	}

	return $res;
}
function addError(){
	$currentDay=date("d.m.Y");
	$currentTime = date( "H:i" );
	$query = "INSERT INTO vk_errors (time, day) VALUES ('{$currentTime}', '{$currentDay}');";
	$res = mysql_query( $query )
	or die( "<p>commentStat Невозможно сделать запрос для анализа статистики: "
	        . mysql_error() . "</p>" );
	writeLog($query);
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
				writeLog($query);
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
	mysql_select_db( $db_name );
	}
function writeLog($log_string){
	$filename = '../../../robot_log.txt';
	$currentDate = date("d.m.Y");
	$currentTime = date( "H:i" );
	$log_string=$currentDate." ".$currentTime." ".$log_string."\n";
	$handle = fopen($filename,'a');
	//флаг а позволяет только
	//записывать в файл помещая указатель на конeц строки
	if(!$handle) exit;
	if (is_writable($filename)) {
		if (fwrite($handle, $log_string) === FALSE) exit;
		fclose($handle);
	}
}
function wallComment($txt, $group, $post){
		$token = '92b73575a455b69bd32a54215038a3a74e7997d73923a364bd93790912b7f576c18b813f440348dfb5321&expires_in=0&user_id=152223765';
		$delta = '100';
		$app_id = '4832378';
		$group_id = $group;
		$post_id=$post;
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
		writeLog($query);
	}
?>