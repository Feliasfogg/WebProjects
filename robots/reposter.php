<?php
//reposter
//mktime(1, 2, 3, 4, 5, 2006) hour min sec month day year
class Post{
	public function __construct($link, $date){
		$this->link=substr($link, 15);
		$this->date=$date;
	}
	public function getLink(){
		return $this->link;
	}
	public function getDate(){
		return $this->date;
	}
	private $link;
	private $date;
}
require_once('parserLib.php');

$token
	= "92b73575a455b69bd32a54215038a3a74e7997d73923a364bd93790912b7f576c18b813f440348dfb5321&expires_in=0&user_id=152223765";
$delta    = "100";
$app_id   = "4832378";
$post_id="29045";
$group_id = "43932139";
$vk = new vk( $token, $delta, $app_id, $group_id );
$currentDate=time();
$logFile='../konkurs.log';

writeLog('start', $logFile);
$postsArray = array(
	'mac' =>new Post('https://vk.com/wall-58641250_761017', mktime(0, 0, 0, 7, 31, 2015)), 
	'iphone'=>new Post('https://vk.com/wall-62398803_400000', mktime(0, 0, 0, 6, 26, 2015)),
	'velo'=>new Post('https://vk.com/wall-57422635_30121', mktime(0, 0, 0, 6, 26, 2015)),
	'lumix'=>new Post('https://vk.com/wall-62124383_2215', mktime(0, 0, 0, 6, 26, 2015)),
	'strikalo'=>new Post('https://vk.com/wall-57422635_31725', mktime(0,0,0,6,26,2015))
	);
foreach ($postsArray as $post){
	if($post->getDate() <= $currentTime){
		//echo (date("d.m.Y", $post->getDate()))."</br>";

		sleep(5);
		if($vk->setOnline()) writeLog('set online', $logFile);
		else writeLog('cant set online', $logFile);
		if($vk->getRepost($post->getLink(), null)) writeLog("get repost from https://vk.com/".$post->getLink(), $logFile);
		else writeLog("cant repost from http://vk.com/".$post->getLink(), $logFile);
	}
}
writeLog('stop', $logFile);
?>