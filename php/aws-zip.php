<?php
$userKey = 'TJWgaqvBNPyZEzPCPSq2JB'; // important! key from https://docs.s3zipper.com/
$userSecret = '41b05daaZ6098Z456aZb2a3Za0024dec178c'; // important! secret from https://docs.s3zipper.com/
$awsKey = 'AKIA325RUJVPTDFY4CM6'; // important! key from AWS
$awsSecret = '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'; // important! secret from AWS
$awsBucket = 'alttimelines.hamza'; // important! bucket name
$awsRegion = 'us-east-1'; // important! bucket region

$curl = curl_init();

curl_setopt_array($curl, array(
    CURLOPT_URL => "https://api.s3zipper.com/gentoken",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_SSL_VERIFYHOST => false,
    CURLOPT_IPRESOLVE => CURL_IPRESOLVE_V4,
    CURLOPT_FOLLOWLOCATION => false,
    CURLOPT_PROXY => null,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "POST",
    CURLOPT_POSTFIELDS => array('userKey' => $userKey,'userSecret' => $userSecret),
));


$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

$token = '';
if ($err) {
    echo "cURL Error #:" . $err;
    exit();
} else {
    $token = json_decode($response)->token;
}

//$filePaths = json_decode($_POST);
$filePaths = $_POST["files"];

/*$filePaths = array( //for test
   'zipbucket-test/v1.mov',
   'zipbucket-test/v2.mov',
);*/

$url = 'https://api.s3zipper.com/v1/streamzip';

$fields = array(
    'awsKey' => $awsKey,
    'awsSecret' => $awsSecret,
    'awsBucket' => $awsBucket,
    'awsRegion' => $awsRegion,
    'expireLink' => 24,
    //'resultsEmail' => 'email@yahoo.com', //in need send link to email
    'bucketAsDir' => 'false',
    'filePaths' => $filePaths
    //'zipFileName' => 'file.zip' //in need specific file name
);


$payload = json_encode( $fields, JSON_UNESCAPED_SLASHES );

$curl = curl_init();

curl_setopt($curl, CURLOPT_POSTFIELDS, $payload);
curl_setopt($curl, CURLOPT_URL, $url);
curl_setopt($curl, CURLOPT_POST, 1);
curl_setopt($curl, CURLOPT_HTTPHEADER, array(
        'Content-Type:application/json; charset=utf-8',
        "Authorization: Bearer $token"
    )
);
curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

$urldata = '';
if ($err) {
    echo "cURL Error #:" . $err;
    exit();
} else {
    $urldata = $response;
}

//echo $urldata;

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://api.s3zipper.com/v1/zipresult',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS =>$urldata,
  CURLOPT_HTTPHEADER => array(
    "Authorization: Bearer $token",
    'Content-Type: application/json'
  ),
));

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);
$urlfile = '';
if ($err) {
    echo "cURL Error #:" . $err;
    exit();
} else {
    $urlfile = json_decode($response)->results;
    echo $urlfile[0];
}

?>