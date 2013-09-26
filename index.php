<!DOCTYPE HTML>
<html>
<head>
 <title>GLITCH EFFECT</title>
    <meta charset="utf-8">
	    <link rel="stylesheet" type="text/css" href="style.css"/>
		<script type="text/javascript" src="http://code.jquery.com/jquery-2.0.3.min.js"></script>
		
</head>
<body > 
    <?php
    $client_id = '3640907'; // ID приложения
    $client_secret = 'tiNav1LD6tt390ZmU9cY'; // Защищённый ключ
    $redirect_uri = 'http://amilkey.ru/oggetto'; // Адрес сайта

    $url = 'http://oauth.vk.com/authorize';

    $params = array(
        'client_id'     => $client_id,
        'redirect_uri'  => $redirect_uri,
        'response_type' => 'code'
    );

    echo $link = '<p><a href="' . $url . '?' . urldecode(http_build_query($params)) . '" class="go">GLITCH EM</a></p>';

if (isset($_GET['code'])) {
    $result = false;
    $params = array(
        'client_id' => $client_id,
        'client_secret' => $client_secret,
        'code' => $_GET['code'],
        'redirect_uri' => $redirect_uri
    );

    $token = json_decode(file_get_contents('https://oauth.vk.com/access_token' . '?' . urldecode(http_build_query($params))), true);

  /*  if (isset($token['access_token'])) {
        $params = array(
            'uids'         => $token['user_id'],
            'fields'       => 'uid,first_name,last_name,screen_name,sex,bdate,photo_big',
            'access_token' => $token['access_token']
        );

        $userInfo = json_decode(file_get_contents('https://api.vk.com/method/users.get' . '?' . urldecode(http_build_query($params))), true);
        if (isset($userInfo['response'][0]['uid'])) {
            $userInfo = $userInfo['response'][0];
            $result = true;
        }
		*/
		$params = array(
            'uids'         => $token['user_id'],
            'fields'       => 'photo_big',
            'access_token' => $token['access_token']
        );
		$friendInfo = json_decode(file_get_contents('https://api.vk.com/method/friends.get' . '?' . urldecode(http_build_query($params))), true);
		if (isset($friendInfo['response'][0]['uid'])) {
            $friendInfo = $friendInfo['response'];
            $result = true;
        }
    }
	
    if ($result) {		
		for($x=0 ; $x<25; $x++){
			$photo = file_get_contents($friendInfo[$x]['photo_big']);
			$base64 = str_split(base64_encode($photo));
			$max = count($base64);
			for($i = 0;$i<6;$i++) {
				$base64[rand(1000, $max)] = $base64[rand(1000,$max)];
			}
			$photo_local = base64_decode(implode('',$base64));
			$img_extension = end(explode('.', $friendInfo[$x]['photo_big']));
			$local_filename = "$x.$img_extension";
			file_put_contents("images/$local_filename",  $photo_local);
			echo '<img src="images/' . $local_filename . '" width="200" height="200"/>'; 
		}
		echo "<script>
					$('.go').remove();
				</script>";
    }
	
	
?>

</body>
</html>