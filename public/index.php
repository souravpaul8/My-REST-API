<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require '../vendor/autoload.php';
require '../includes/DbOperations.php';

$app = new \Slim\App([
    'settings'=>[
        'displayErrorDetails'=>true
    ]
]);

//POST request to retrieve the development funds
$app->post('/devfund', function(Request $request, Response $response){
    //Check if all required parameters are present
    if(!haveEmptyParameters(array('financialyear', 'state', 'scheme'), $request, $response)){

        $request_data = $request->getParsedBody();

        //Parsing the request data to pass it to the DbOperations
        $financialyear = $request_data['financialyear'];
        $state = $request_data['state'];
        $scheme = $request_data['scheme'];

        //DbOperations Object is created
        $db = new DbOperations; 

        //Executing the function retrieveDevFunds to receive the response data
        $devFunds = $db->retrieveDevFunds($financialyear, $state, $scheme);

        //Creating an array to wrap the response data
        $response_data = array();
        $response_data['error'] = false;
        $response_data['DevelopmentFund'] = $devFunds;

        //Encoding the data into JSON for further operations
        $response->write(json_encode($response_data));
        return $response
                   ->withHeader('Content-type', 'application/json')
                   ->withStatus(101);
    }

    //Executed if required parameters are not present
    return $response
        ->withHeader('Content-type', 'application/json')
        ->withStatus(200);


});

//POST request to retrieve the Maintainence funds
$app->post('/mainfund', function(Request $request, Response $response){
    //Check if all required parameters are present
    if(!haveEmptyParameters(array('financialyear', 'state', 'scheme'), $request, $response)){

        $request_data = $request->getParsedBody();
        //Parsing the request data to pass it to the DbOperations
        $financialyear = $request_data['financialyear'];
        $state = $request_data['state'];
        $scheme = $request_data['scheme'];

        //DbOperations Object is created
        $db = new DbOperations; 

        //Executing the function retrieveDevFunds to receive the response data
        $mainFunds = $db->retreiveMainFunds($financialyear, $state, $scheme);

        //Creating an array to wrap the response data
        $response_data = array();
        $response_data['error'] = false;
        $response_data['MaintainenceFund'] = $mainFunds;

        //Encoding the data into JSON for further operations        
        $response->write(json_encode($response_data));
        return $response
                   ->withHeader('Content-type', 'application/json')
                   ->withStatus(101);
    }

    //Executed if required parameters are not present
    return $response
        ->withHeader('Content-type', 'application/json')
        ->withStatus(200);


});


$app->post('/memberdetails', function(Request $request, Response $response){
    //Check if all required parameters are present
    if(!haveEmptyParameters(array('email'), $request, $response)){

        $request_data = $request->getParsedBody();
        //Parsing the request data to pass it to the DbOperations
        $email = $request_data['email'];

        //DbOperations Object is created
        $db = new DbOperations; 

        //Executing the function retrieveDevFunds to receive the response data
        $memberDetail = $db->getMemberDetail($email);

        //Creating an array to wrap the response data
        $response_data = array();
        $response_data['error'] = false;
        $response_data['Member_Detail'] = $memberDetail;

        //Encoding the data into JSON for further operations        
        $response->write(json_encode($response_data));
        return $response
                   ->withHeader('Content-type', 'application/json')
                   ->withStatus(101);
    }

    //Executed if required parameters are not present
    return $response
        ->withHeader('Content-type', 'application/json')
        ->withStatus(200);


});


$app->post('/userlogin', function(Request $request, Response $response){
    //Check if all required parameters are present
    if(!haveEmptyParameters(array('email', 'password'), $request, $response)){
       //Parsing the request data to pass it to the DbOperations
       $request_data = $request->getParsedBody(); 
       
       $email = $request_data['email'];
       $password = $request_data['password'];
       
       //DbOperations Object is created
        $db = new DbOperations; 

        $result = $db->userLogin($email, $password);

        if($result == USER_AUTHENTICATED){
           
           $user = $db->getUserByEmail($email);

           $response_data = array();
           $response_data['error']=false; 
           $response_data['message'] = 'Login Successful';
           $response_data['user']=$user; 

           $response->write(json_encode($response_data));
           return $response
               ->withHeader('Content-type', 'application/json')
               ->withStatus(200);

        }else if($result == USER_NOT_FOUND){
           
            $response_data = array();
            $response_data['error']=true; 
            $response_data['message'] = 'User does not exist';

            $response->write(json_encode($response_data));
            return $response
               ->withHeader('Content-type', 'application/json')
               ->withStatus(200);   

        }else if($result == USER_PASSWORD_DO_NOT_MATCH){
           $response_data = array();
           $response_data['error']=true; 
           $response_data['message'] = 'Invalid credential';

           $response->write(json_encode($response_data));
           return $response
               ->withHeader('Content-type', 'application/json')
               ->withStatus(200);  
       }
   }

    return $response
       ->withHeader('Content-type', 'application/json')
       ->withStatus(422);    
});

$app->get('/allmembers', function(Request $request, Response $response){
    $db = new DbOperations;
    $Members = $db->getAllMembers();

    $response_data = array();
    $response_data['error'] = false;
    $response_data['Members'] = $Members;

    $response->write(json_encode($response_data));
    return $response
               ->withHeader('Content-type', 'application/json')
               ->withStatus(200); 
});

$app->put('/updatepassword', function(Request $request, Response $response){

    if(!haveEmptyParameters(array('currentpassword','newpassword','email'), $request, $response)){

        $request_data = $request->getParsedBody();

        $currentpassword = $request_data['currentpassword'];
        $newpassword = $request_data['newpassword'];
        $email = $request_data['email'];

        $db = new DbOperations;
        $result = $db->updatePassword($currentpassword, $newpassword, $email);

        if($result == PASSWORD_CHANGED){

            $response_data = array();
            $response_data['error'] = false;
            $response_data['message'] = 'Password Change Successful';
            
        
            $response->write(json_encode($response_data));
            return $response
            ->withHeader('Content-type', 'application/json')
            ->withStatus(200);

        }else if($result == PASSWORD_DO_NOT_MATCH) {

            $response_data = array();
            $response_data['error'] = true;
            $response_data['message'] = 'Password Do Not Match';
            
        
            $response->write(json_encode($response_data));
            return $response
            ->withHeader('Content-type', 'application/json')
            ->withStatus(200);


        }else if($result == PASSWORD_NOT_CHANGED) {

            $response_data = array();
            $response_data['error'] = true;
            $response_data['message'] = 'Try again';
            
        
            $response->write(json_encode($response_data));
            return $response
            ->withHeader('Content-type', 'application/json')
            ->withStatus(200);

        }
    
    }

    return $response
    ->withHeader('Content-type', 'application/json')
    ->withStatus(422); 
});

$app->post('/nhconstruction', function(Request $request, Response $response){
    
    if(!haveEmptyParameters(array('financialyear', 'state_UT', 'scheme'), $request, $response)){

        $request_data = $request->getParsedBody();

        
        $financialyear = $request_data['financialyear'];
        $state_UT = $request_data['state_UT'];
        $scheme = $request_data['scheme'];

        
        $db = new DbOperations; 

        $FullNHDetails = $db->retrieveNHdetails($financialyear, $state_UT, $scheme);

        $response_data = array();
        $response_data['error'] = false;
        $response_data['NH_Construction_Details'] = $FullNHDetails;

        $response->write(json_encode($response_data));
        return $response
                   ->withHeader('Content-type', 'application/json')
                   ->withStatus(101);
    }

    return $response
        ->withHeader('Content-type', 'application/json')
        ->withStatus(200);


});


// Function to check if all the required parameters is present
function haveEmptyParameters($required_params, $request, $response){
     
    $error = false; 
    $error_params = '';
    $request_params = $request->getParsedBody(); 

    foreach($required_params as $param){
        if(!isset($request_params[$param]) || strlen($request_params[$param])<=0){
            $error = true; 
            $error_params .= $param . ', ';
        }
    }

    if($error){
        $error_detail = array();
        $error_detail['error'] = true; 
        $error_detail['message'] = 'Required parameters ' . substr($error_params, 0, -2) . ' are missing or empty';
        $response->write(json_encode($error_detail));
    }
    return $error; 
}



// Run app
$app->run();
