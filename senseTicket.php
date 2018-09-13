<?php

  header('Access-Control-Allow-Origin: *'); 
      
  include('./setup/arrays.php');

  $postdata = file_get_contents("php://input");
  $request = json_decode($postdata);

  $ud   = $request->ud;
  $user = $request->user;
  $sec = $request->sec;

  $url = $reqTicket['requestUrl'] . $reqTicket['secret'];

  $reqFields = array(
      'UserDirectory' => $ud,
      'UserId'        => $user,
      'Attributes'    => array(),
  );

  $options = array(
      CURLOPT_URL            => $url,
      CURLOPT_SSLCERT        => $certs['cert'],
      CURLOPT_SSLCERTPASSWD  => $certs['pass'],
      CURLOPT_SSLKEY         => $certs['key'],
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_SSL_VERIFYHOST => false,
      CURLOPT_SSL_VERIFYPEER => false,
      CURLOPT_HTTPHEADER     => array('X-qlik-xrfkey: ' . $reqTicket['secret'], 'Content-Type: application/json'),
      CURLOPT_POSTFIELDS     => json_encode($reqFields)
  );

  if($ud && $user && $sec){
    $ticket = reqTicket($options);
    $data['ticket'] = $reqTicket['redirectUrl'] . $reqTicket[$sec] . $ticket;
    echo json_encode($data);
    //header("location: " . $reqTicket['redirectUrl'] . $reqTicket[$sec] . $ticket);
    die();
  }else{
    $data['error'] = 4;
    echo json_encode($data);
  }

  //#########################################################################################//
  //###################################                   ###################################//
  //###################################       TICKET      ###################################//
  //###################################                   ###################################//
  //#########################################################################################//

  function reqTicket($options){
    $request = curl_init();
    curl_setopt_array($request, $options);
    $response = curl_exec($request);

    if (!$response){
        return null;
    }else{
        $ticket = json_decode($response, true);
        return $ticket["Ticket"];
    }
  }

?>