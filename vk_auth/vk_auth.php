<?
$email = '375296367752';
$pass = 'felias4ehonte';


/*Авторизация в вконтакте Апрель 2013*/

$auth = curl('http://login.vk.com/?act=login&amp;email=' . $login . '&amp;pass=' . $pass);
if (preg_match('/hash=([a-z0-9]{1,32})/', $auth, $hash)) {
 $url= 'http://vk.com/login.php?act=slogin&amp;role=fast&amp;redirect=1&amp;to=&amp;s=1&amp;<strong>__q_hash=</strong>' . $hash[1];
 $res = curl($url);
 preg_match('/remixsid=(.*?);/', $res, $sid);
 $cookie = 'remixdt=-3600; remixlang=0; audio_vol=100; remixseenads=2; remixflash=11.4.402; remixsid=' . $sid[1];
} else {
	die('Authorization error');
}

/*Вызываем любое действие */
//$post = array('act' =&gt; 'upload_box', 'al' =&gt; '1', 'oid' =&gt; 'your id group', );
$post = array('oid'=>'id152223765');
$result = setActionVk($url, $post, $acook, true);


/*Вспомогательные функции*/

function curl($url, $cookie = null, $post = null) {
	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0);
	curl_setopt($ch, CURLOPT_HEADER, 1);
	curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.9.0.3) Gecko/2008092417 Firefox/3.0.3');
	if (isset($cookie)) {
		curl_setopt($ch, CURLOPT_COOKIE, $cookie);
	}
	if (isset($post)) {
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
	}

	$res = curl_exec($ch);
	curl_close($ch);
	return $res;
}


function setActionVk($url, $post, $acook, $type) {
	if ($ch = curl_init()) {
		$user_agent = "Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10_6_7; da-dk) AppleWebKit/533.21.1 (KHTML, like Gecko) Version/5.0.5 Safari/533.21.1";

		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, $type);
		curl_setopt($cl, CURLOPT_HEADER, 1);
		curl_setopt($ch, CURLOPT_USERAGENT, $user_agent);
		// ответ сервера будем записывать в переменную
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		if ($type) {
			curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post));
		}
		curl_setopt($ch, CURLOPT_COOKIE, $acook);

		curl_setopt($ch, CURLOPT_HEADER, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
		curl_setopt($ch, CURLOPT_VERBOSE, 1);

		$out = curl_exec($ch);
		return $out;
		curl_close($ch);
	}
}
?>