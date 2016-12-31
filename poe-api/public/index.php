<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use be\kunstmaan\multichain\MultichainClient;
use be\kunstmaan\multichain\MultichainHelper;

if (PHP_SAPI == 'cli-server') {
    // To help the built-in PHP dev server, check if the request was actually for
    // something which should probably be served as a static file
    $url  = parse_url($_SERVER['REQUEST_URI']);
    $file = __DIR__ . $url['path'];
    if (is_file($file)) {
        return false;
    }
}

require __DIR__ . '/../vendor/autoload.php';

//session_start();

// Instantiate the app
$settings = require __DIR__ . '/../src/settings.php';
$app = new \Slim\App($settings);

// Set up dependencies
require __DIR__ . '/../src/dependencies.php';

// Register middleware
require __DIR__ . '/../src/middleware.php';

// Short URL Function middleware
require __DIR__ . '/../src/functions.php';

// Register routes
require __DIR__ . '/../src/routes.php';

$app->post('/publish/{signature}', function (Request $request, Response $response) {
    
    $signature = $request->getAttribute('signature');
    $client = new MultichainClient("http://<MultiChain Node IP>:<RPC Port>", 'multichainrpc', '<RPC Password>', 3);
    //$response->getBody()->write("Hello, $signature");
    $data = $request->getParsedBody();
    if(isset($data['name']))
        $name = filter_var($data['name'], FILTER_SANITIZE_STRING);
    else
        $name = "Unknown";
    
    if(isset($data['email']))
        $email = filter_var($data['email'], FILTER_SANITIZE_STRING);
    else
        $email = "Unknown";

    if(isset($data['message']))
        $message = filter_var($data['message'], FILTER_SANITIZE_STRING);
    else
        $message = "NA";
    
    $dataArray = array("signature" => $signature,"name" => $name, "email"=> $email, "message"=>$message);
    $dataJSON = json_encode($dataArray);
    $dataBase64 = base64_encode($dataJSON );
    $dataHex = bin2hex($dataBase64);

    //$info = $client->setDebug(true)->getInfo();
    $dataToReturn = array();
    $tx_id = $client->setDebug(true)->executeApi('publish', array("poe", $signature, $dataHex));
    
    $longUrl = $_SERVER['HTTP_HOST']."/details.php?signature=".$signature;
    $shorUrl = shortUrl($longUrl);

    $block_info = $client->setDebug(true)->executeApi('getwallettransaction', array($tx_id));
    $confirmations = $block_info['confirmations'];
    if($confirmations == 0){
        $blockhash = "NA";
        $blocktime = "NA";
    }
    else{
        $blockhash = $block_info['blockhash'];
        $blocktime = $block_info['blocktime'];
    }

    $dataToReturn['long_url'] = "http://".$longUrl;
    $dataToReturn['short_url'] = $shorUrl;
    $dataToReturn['signature'] = $signature;
    $dataToReturn['transaction_id'] = $tx_id;
    $dataToReturn['confirmations'] = $confirmations;
    $dataToReturn['blockhash'] = $blockhash;
    $dataToReturn['blocktime'] = $blocktime;
    $dataToReturn['name'] = $name;
    $dataToReturn['email'] = $email;
    $dataToReturn['message'] = $message;
    $dataToReturn['timestamp'] = date('g:i A \o\n l jS F Y \(\T\i\m\e\z\o\n\e \U\T\C\)', time());;
    
    return $response->withJson($dataToReturn)->withHeader('Content-Type', 'application/json');
    //return $response;
});

$app->get('/verify/{signature}', function (Request $request, Response $response) {
    $signature = $request->getAttribute('signature');
    $client = new MultichainClient("http://<MultiChain Node IP>:<RPC Port>", 'multichainrpc', '<RPC Password>', 3);
    $data = $client->setDebug(true)->executeApi('liststreamkeyitems', array("poe", $signature));
    $data = array_reverse($data);
    $dataToReturn = array();
    foreach($data as $key => $value){
        $d = array();
        $d['signature'] = $signature;
        $d['transaction_id'] = $value['txid'];
        $d['confirmations'] = $value['confirmations'];
        $d['blocktime'] = date('g:i A \o\n l jS F Y \(\T\i\m\e\z\o\n\e \U\T\C\)', $value['blocktime']);
        $meta_data = json_decode(base64_decode(hex2bin($value['data'])));
        $d['name'] = $meta_data->name;
        $d['email'] = $meta_data->email;
        $d['message'] = $meta_data->message;
        $d['recorded_timestamp_UTC'] = $value['blocktime'];
        $d['readable_time_UTC'] = date('g:i A \o\n l jS F Y \(\T\i\m\e\z\o\n\e \U\T\C\)', $value['blocktime']);
        $dataToReturn[$key] = $d;
    }
    
    return $response->withJson($dataToReturn)->withHeader('Content-Type', 'application/json');
    
});

$app->get('/latest/published/{count}', function (Request $request, Response $response) {
    //$app->response->headers->set('Content-Type', 'application/json');
    $count = $request->getAttribute('count');
    $client = new MultichainClient("http://<MultiChain Node IP>:<RPC Port>", 'multichainrpc', '<RPC Password>', 3);
    $data = $client->setDebug(true)->executeApi('liststreamitems', array("poe"));
    $data = array_reverse($data);
    $dataToReturn = array();
    for($i=0;$i<$count;$i++)
    {
        $d = array();
        $d['signature'] = $data[$i]['key'];
        $d['blocktime'] = date("Y-m-d H:i:s", $data[$i]['blocktime'])." UTC";
        $d['confirmations'] = $data[$i]['confirmations'];
        $dataToReturn[$i] = $d;
    }
    
    //$dataArray = json_decode($temp_json);
    
    return $response->withJson($dataToReturn)->withHeader('Content-Type', 'application/json');
    //return $response;
});
// Run app
$app->run();
