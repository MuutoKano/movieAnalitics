<?php
function deletingBlank($noun){
  return $word;
}

function pickingNoun($text){
  $searchWord = '名詞';
  $noun = strstr($text, $searchWord, true);
  if ($noun){
    $noun = urlencode($noun);
    $brank = '%09';
    $word = strstr($noun, $brank, true);
    $noun = urldecode($word);
  }
  return $noun;
}

function pickingProperNoun($text){
  $searchWord = '固有名詞';
  $properNoun = strstr($text, $searchWord, true);
  if ($properNoun){
    $properNoun = urlencode($properNoun);
    $brank = '%09';
    $word = strstr($properNoun, $brank, true);
    $properNoun = urldecode($word);
  } else {
    $properNoun = false;
  }
  return $properNoun;
}



$originTexts = file(__DIR__ . '/mophological.txt');
$result = pickingNoun($originTexts[0]);
$nounsArray = array();
$properNounsArray = array();
$pickingProperResult = '';
$pickingResult = '';

for ($i = 0; $i < count($originTexts); $i++){
  $pickingProperResult = pickingProperNoun($originTexts[$i]);
  $pickingResult = pickingNoun($originTexts[$i]);
  if ($pickingProperResult != false){
  array_push($properNounsArray, $pickingProperResult);
  }
  $noun ='';
  $properNoun;

  #checking the continuous nous , and if it exist, putting together. 
  while ($pickingResult){
    $noun = $noun . $pickingResult;
    $pickingResult = pickingNoun($originTexts[++$i]);
    $pickingProperResult = pickingProperNoun($originTexts[$i]);
    if (!$pickingResult){
      array_push($nounsArray, $noun);
      $noun = '';
    }
    if ($pickingProperResult != false) {
      array_push($properNounsArray, $pickingProperResult);
    }
  }
}

$properNumber = array_count_values($properNounsArray);
$isRankingArray = arsort($properNumber, SORT_NUMERIC);
/*echo "最も関連度の高いワード<br>";
foreach ($properNumber as $val => $key){
  echo $key . " : " .$val . "<br>";
}
 */
# counting the number of occurences of each value in '$nounsArray'  
$countValues = array_count_values($nounsArray);

#sorting '$countValues' in ascending numbers.
#echo "<br><br>登順<br>";
$isRankingArray = arsort($countValues, SORT_NUMERIC);
foreach ($countValues as $key => $val){
  echo "$key = $val\n" . "<br>";
}
