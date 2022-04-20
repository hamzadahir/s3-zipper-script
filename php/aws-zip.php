$userKey = 'o4fzLypE5dvjHHkUNEoFzG';
$userSecret = '13ed081eZad48Z47d9Z8f11Z1058f95abb59';
$awsKey = 'AKIA325RUJVPXRCKA6WY';
$awsSecret = '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';
$awsBucket = 'zipbucket-test';

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
    $token = $response;
}

$filePaths = $_POST['filePaths'];

$url = 'https://api.s3zipper.com/v1/streamzip';

$fields = array(
    'awsKey' => $awsKey,
    'awsSecret' => $awsSecret,
    'awsBucket' => $awsBucket,
    'expireLink' => 24,
    //'resultsEmail' => 'email@yahoo.com',
    'bucketAsDir' => 'true',
    'filePaths' => $filePaths
);


$payload = json_encode( $fields, JSON_UNESCAPED_SLASHES );

curl_setopt( $curl, CURLOPT_POSTFIELDS, $payload );
curl_setopt($curl,CURLOPT_URL, $url);
curl_setopt($curl,CURLOPT_POST, 1);
curl_setopt($curl,CURLOPT_HTTPHEADER, array(
        'Content-Type:application/json; charset=utf-8',
        "Authorization: Bearer $token"
    )
);


$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
    echo "cURL Error #:" . $err;
} else {
    //echo $response;
    header('Location: '.$response);

}