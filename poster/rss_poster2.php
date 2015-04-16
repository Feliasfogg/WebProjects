<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; CHARSET=UTF-8">
</head>
</html>
<?php
require_once '../app_config.php';
require_once '../vk_auth/VKclass.php';

ini_set( 'display_errors', 'Off' );
//инациализация вк
$token    = '92b73575a455b69bd32a54215038a3a74e7997d73923a364bd93790912b7f576c18b813f440348dfb5321&expires_in=0&user_id=152223765';
$delta    = '100';
$app_id   = '4832378';
$group_id = '85303665';
$vk       = new vk( $token, $delta, $app_id, $group_id );

$rss_url = "http://www.bsmu.by/rss/rss.xml";
$rss     = simplexml_load_file( $rss_url );
$items   = $rss->channel->item;

$filter="owner"; $count=30;
$posts = $vk->getPosts($filter, $count);

if ( sizeof($items) ) {
	$flag=1;
	foreach ( $items as $item ) {
		$news_title = $item->title;
		$news_link  = $item->link;
		for($i=1; $i<sizeof($posts); ++$i){
			if($posts[$i]->text == $news_title){
				$flag=0;
				break;
			} else $flag=1;
		}
		if($flag) $vk->post(null, null, $news_link);
	}
}
?>