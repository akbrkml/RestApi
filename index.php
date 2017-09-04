<?php 
require __DIR__ . '/vendor/autoload.php';
require_once 'include/DbConnect.php';

use \Slim\App;

$app = new App();

$db = $con;

$app-> get('/', function(){
    echo "Rest API with Slim Framework";
});

/* *
 * URL: http://localhost/paud/login
 * Parameters: email, password
 * Method: POST
 * */
$app->post('/login', function($request, $response, $args) use($app, $db){
    $data = $request->getParams();
    
    $login = $db->user()
        ->where("email", $data['email'])
        ->where("password", md5($data['password']));
    
    if ($login->fetch()){
        foreach($login as $data){    
            $responseJson["error"]   = false;
            $responseJson["message"] = "Login successful";
            $responseJson['data']['id_user'] = $data['user_id'];
            $responseJson['data']['nama'] = $data['nama'];
            $responseJson['data']['email'] = $data['email'];
        }
    } else {
        $responseJson['error']   = true;
        $responseJson['message'] = "Invalid email or password";
    }
    
    echo json_encode($responseJson);
});

/* *
 * URL: http://localhost/paud/register
 * Parameters: name, email, password
 * Method: POST
 * */
$app->post('/register', function($request, $response, $args) use($app, $db){
    $data = $request->getParams();
    $data['password'] = md5($data['password']);
    $register = $db->user()
        ->select("id_user")
        ->where("email", $data['email']);
    
    if (!$register->fetch()){        
        $result = $db->user->insert($data);
        if($result){
            $responseJson["status"]  = "Success";
            $responseJson["error"]   = false;
            $responseJson["message"] = "You are successfully registered";
        } else {
            $responseJson["status"]  = "Failed";
            $responseJson["error"]   = true;
            $responseJson["message"] = "Oops! An error occurred while registering";
        }
    } else {
        $responseJson["status"]  = "Failed";
        $responseJson["error"]   = true;
        $responseJson["message"] = "Sorry, this user already existed";
    }
    
    echo json_encode($responseJson);
});

//run App
$app->run();
