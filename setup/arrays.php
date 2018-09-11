<?php

    $countries = array(
        'AR',
        'BR',
        'CL',
        'CO',
        'EC',
        'MX',
        'PE',
        'UY',
        'VE'
    );

    $ldap = array(
        'host'        => '10.40.54.10',
        'port'        => '3268',
        'basedn'      => "DC=infra, DC=d",
        'accessGroup' => 'CN=QlikSense,OU=Grupos,DC=infra,DC=d',
        'adminGroup'  => 'CN=QlikView_Administrators,OU=Grupos,DC=infra,DC=d'
    );

    $certs = array(
        'cert'        => '/home/despegar/siteManage/php/certs/client.pem',
        'key'         => '/home/despegar/siteManage/php/certs/client_key.pem',
        'pass'        => ''
    );

    $reqTicket = array(
        'secret'      => '0123456789abcdef',
        'requestUrl'  => 'https://bi-aws-qs-00.despexds.net:4243/qps/login/ticket?xrfkey=',
        'redirectUrl' => 'http://qliksense.despegar.com/login/',
        'hub'         => 'hub/?QlikTicket=',
        'qmc'         => 'qmc/?QlikTicket='
    );

    $notify = array(
        array(
            'Error de autenticación!!!',
            'El país, usuario y contraseña no deben estar vacíos, por favor completelos e intentelo nuevamente.'
        ),
        array(
            'Sin acceso al servidor LDAP!!!',
            'En este momento no podemos comprobar sus credenciales de acceso, por favor intentelo mas tarde.'
        ),
        array(
            'Error en sus credenciales!!!',
            'Las credenciales ingresadas son incorrectas, por favor reviselas e intentelo nuevamente.'
        ),
        array(
            'Sin acceso a Qlik Sense!!!',
            'Usted se ha autenticado con exito, pero aun no tien acceso a Qlik Sense, por favor solicitelo a BI. <br /> Desea enviar la solicitud ahora mismo?'
        ),
        array(
            'Autenticación exitosa!!!',
            'Usted se ha autenticado con exito, elija el entorno a donde desee acceder.'
        )
        
    );

?>