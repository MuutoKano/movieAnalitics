<?php
  $videoTitle = $_POST['videoTitle'];
$language = $_POST['language'];

$videoTitle = urlencode($videoTitle);
$language = urlencode($language);

if(is_uploaded_file($_FILES['userVideo']['tmp_name'])){
  if(move_uploaded_file($_FILES['userVideo']['tmp_name'],"./".$_FILES['userVideo']['name'])){
    $fileName = $_FILES['userVideo']['name'];
    $isChangingFileName = rename($fileName, "upload.mp4");
    if ($isChangingFileName) {
      $ch = curl_init();
      $headers = array(
        "Content-Type: multipart/form-data",
        "Ocp-Apim-Subscription-Key:1fb39f00ae48491f81a35300aeaa690a",
      );
      $fp = fopen('/var/www/html/dev/movieAnalitics/upload/curl.log', 'a');
      $curlUrl = 'https://videobreakdown.azure-api.net/Breakdowns/Api/Partner/Breakdowns?name=' . $videoTitle . '&privacy=Private&language=' . $language;
      #$cfile = new CURLFile('/var/www/html/dev/movieAnalitics/upload/upload.mp4', 'video/mp4', 'uptest.mp4');

      curl_setopt_array($ch, [ 
        CURLOPT_HTTPHEADER => $headers,
        CURLOPT_URL => $curlUrl,
        CURLOPT_POST => true,
        CURLOPT_VERBOSE => true,
        CURLOPT_POSTFIELDS  => [
         'userfile[]' => new CURLFile('./upload.mp4'),
        ],
        CURLOPT_STDERR => $fp,
      ]);
      echo "video ID : ";
      curl_exec($ch);
    }else{
       echo "error name changing";
    }
  } else {
      echo "error file moving.";  
  }
}else{
  echo "file not uploaded.";
}
