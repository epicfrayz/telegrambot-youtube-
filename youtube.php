<?php 

  	
// Подтверждение webhook
$video = "null";

if (isset($_GET['hub_challenge'])) { echo $_REQUEST['hub_challenge']; } 
else { $video = parseYoutubeUpdate(file_get_contents('php://input')); }

function parseYoutubeUpdate($data) {
    $xml 		= simplexml_load_string($data, 'SimpleXMLElement', LIBXML_NOCDATA);
    $video_id 	= substr((string)$xml->entry->id, 9);
    $channel_id = substr((string)$xml->entry->author->uri, 32);
    $published  = (string)$xml->entry->published;

    return array(
        'video_id'=>$video_id,
        'channel_id'=>$channel_id,
        'published'=>$published 
    );
}
/*
// Получает строку типа Atom и вернет ссылку на видео(в формате просмотра)
function getLinkVideo($str) {
  // находим начальную позицию ссылки
  $firstPos = strripos($str, "https://www.youtube.com/watch?");

  // обновляем строку
  $str = substr( $str, $firstPos);

  // находим конец ссылки
  $lastPos = strripos($str, "\"");

  // собираем ссылку на видео
  $link = substr( $str, 0, $lastPos);

  return $link;
}
*/
// токен телеграм бота

const TOKEN = "";

// Отправка сообщения
$url = 'https://api.telegram.org/bot' . TOKEN . '/sendMessage';

// ссылка на видео
$linkVideo = "https://www.youtube.com/watch?v=" . $video['video_id']; 

$params = [
	'chat_id' =>712531723,
	//'video' => $linkVideo , //. "\n" . $_GET['hub_challenge'] . "\n" . file_get_contents('php://input')
  "text" => $linkVideo,
];

// Отдать ответ телегрума, что все ОК
//http_response_code(200);

$url = $url . '?' . http_build_query($params);

// Логирование
$time = date("D:H:m:s");
$fileopen=fopen("log.txt", "a+");
$write= "\n" . $time . "| Результат запроса |" . file_get_contents($url) . "\n ------" . file_get_contents('php://input');
fwrite($fileopen,$write);
fclose($fileopen);



//$url = $url . '?' . http_build_query($params);

//$response = json_decode( file_get_contents($url), JSON_OBJECT_AS_ARRAY );

 ?>