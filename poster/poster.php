<?php
require_once 'VKclass.php';
$token = '92b73575a455b69bd32a54215038a3a74e7997d73923a364bd93790912b7f576c18b813f440348dfb5321&expires_in=0&user_id=152223765';
$delta = '100';
$app_id = '4832378';
//$group_id = '43932139';//plantonics
//$post_id='5970';//tank post
$group_id='32434505';//умный челвоек
$post_id='105';//пост
$Arr = array(
    "Долго",
    "Хватит с тебя",
    "Все не спиться",
    "Хорошая ночка",
    "Lol^^",
    "OMG",
    "Хачу галду!))",
	"Не спите...",
	"ясно(",
	"сорян((",
	"лолки вы",
	"норм",
	"нормас продержался)",
	"ну ок...",
	"хватит уже",
	"слишком долго",
	"идите спать",
	"че вам все неспится то((("
);
$phrase=$Arr[rand(0, sizeof($Arr)-1)];
$vk = new vk( $token, $delta, $app_id, $group_id );
$vk_online=$vk->setOnline(0);
//$vk_comment = $vk->addComment($phrase, $post_id);
?>