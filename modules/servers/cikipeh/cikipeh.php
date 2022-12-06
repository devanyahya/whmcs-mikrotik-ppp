<?php

//mengapa banyak cikipeh?, watch this https://www.youtube.com/watch?v=CKzcPTO8ifA
if (!defined("WHMCS")) {
    die("This file cannot be accessed directly");
}


function cikipeh_MetaData()
{
    return array(
        'DisplayName' => 'Cikipeh (Whmcs Module Mikrotik Rest API)',
        'APIVersion' => '1.1', 
        'RequiresServer' => true, 
        'DefaultNonSSLPort' => '443', 
        'DefaultSSLPort' => '443', 
    );
}


function cikipeh_ConfigOptions()
{
    return array(
        'Profile' => array(
            'Type' => 'text',
            'Size' => '10',
            'Default' => '64',
            'Description' => 'Masukan nama Profile yang ada pada mikrotik untuk paket ini',
        ),
        'Service' => array(
            'Type' => 'dropdown',
            'Options' => array(
                'pppoe' => 'pppoe',
                'any' => 'any',
                'l2tp' => 'l2tp',
                'ovpn' => 'ovpn',
                'async' => 'async',
                'sstp' => 'sstp',
				//tipe layanannya
            ),
            'Description' => 'Pilih tipe layanan yang ingin digunakan untuk paket ini'
            
        ),
    );
}


function cikipeh_CreateAccount($params)
{

    $idpelanggan = $params['username'];
    $passinternet = $params['password'];
    $userserver = $params['serverusername'];
    $passserver =  $params['serverpassword'];
    $brasip = $params['serverip'];
    $aman = 'https://';
    $apinya = '/rest/ppp/secret';
    $serverPort = $params['serverport'];
    $urlpanggil = $aman . $brasip . ':' . $serverPort . $apinya ;
    $paket = $params['configoption1'];
    $tipe = $params['configoption2'];
    $miliksiapa = 'WHMCS Nama Customer: ' . $params['clientsdetails']['firstname'] . ' ' . $params['clientsdetails']['lastname'] . ', ID Customer: ' . $params['userid'];
    $yangmaudipake = array(
        "name" => $idpelanggan,
        "password" => $passinternet,
        "profile" => $paket,
        "service" => $tipe,
        "comment" => $miliksiapa,
        "disabled" => "false");
    $dipake_json = json_encode($yangmaudipake);
    $brrcikipeh = curl_init();
    curl_setopt($brrcikipeh, CURLOPT_URL, $urlpanggil);
    curl_setopt($brrcikipeh, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($brrcikipeh, CURLOPT_SSL_VERIFYHOST, FALSE);
    curl_setopt($brrcikipeh, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($brrcikipeh, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    curl_setopt($brrcikipeh, CURLOPT_USERPWD, $userserver . ':' . $passserver);
    curl_setopt($brrcikipeh, CURLOPT_CUSTOMREQUEST, 'PUT');
    curl_setopt($brrcikipeh, CURLOPT_POSTFIELDS, $dipake_json);
    curl_setopt($brrcikipeh, CURLOPT_HEADER, true);
    curl_setopt($brrcikipeh, CURLOPT_HTTPHEADER,
    array(
        'Content-Type:application/json',
        'Content-Length: ' . strlen($dipake_json)
    ));
    $eksekusi = curl_exec($brrcikipeh);

    
 return 'success';

}


function cikipeh_SuspendAccount($params)
{
    $idpelanggan = $params['username'];
    $passinternet = $params['password'];
    $userserver = $params['serverusername'];
    $passserver =  $params['serverpassword'];
    $brasip = $params['serverip'];
    $aman = 'https://';
    $apinya = '/rest/ppp/secret';
    $serverPort = $params['serverport'];
    $urlpanggil = $aman . $brasip . ':' . $serverPort . $apinya;
    $urlubah = $urlpanggil . '/' . $idpelanggan;
    $apisuspend = '/rest/ppp/active';
    $urlapisuspend = $aman . $brasip . ':' . $serverPort . $apisuspend;
    $paket = $params['configoption1'];
    $tipe = $params['configoption2'];
    $urlkill = $urlapisuspend . '/' . $idpelanggan;
    $yangmaudipake = array(
        "name" => $idpelanggan,
        "password" => $passinternet,
        "service" => $tipe,
        "profile" => "isolir",);
		//apabila ingin disable ppp account daripada memasukan ke profile isolir. bisa replace "profile" => "isolir", menjadi "disabled" => "true"
    $dipake_json = json_encode($yangmaudipake);
    $brrcikipeh = curl_init();
    curl_setopt($brrcikipeh, CURLOPT_URL, $urlubah);
    curl_setopt($brrcikipeh, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($brrcikipeh, CURLOPT_SSL_VERIFYHOST, FALSE);
    curl_setopt($brrcikipeh, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($brrcikipeh, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    curl_setopt($brrcikipeh, CURLOPT_USERPWD, $userserver . ':' . $passserver);
    curl_setopt($brrcikipeh, CURLOPT_CUSTOMREQUEST, 'PATCH');
    curl_setopt($brrcikipeh, CURLOPT_POSTFIELDS, $dipake_json);
    curl_setopt($brrcikipeh, CURLOPT_HEADER, true);
    curl_setopt($brrcikipeh, CURLOPT_HTTPHEADER,
    array(
        'Content-Type:application/json',
        'Content-Length: ' . strlen($dipake_json)
    ));
    $eksekusi = curl_exec($brrcikipeh);
    

    //ini untuk kill session
    $brrcikipeh2 = curl_init();
    curl_setopt($brrcikipeh2, CURLOPT_URL, $urlkill);
    curl_setopt($brrcikipeh2, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($brrcikipeh2, CURLOPT_SSL_VERIFYHOST, FALSE);
    curl_setopt($brrcikipeh2, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($brrcikipeh2, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    curl_setopt($brrcikipeh2, CURLOPT_USERPWD, $userserver . ':' . $passserver);

    curl_setopt($brrcikipeh2, CURLOPT_CUSTOMREQUEST, 'DELETE');
    curl_setopt($brrcikipeh2, CURLOPT_POSTFIELDS, $dipake_json);
    curl_setopt($brrcikipeh2, CURLOPT_HEADER, true);
    curl_setopt($brrcikipeh2, CURLOPT_HTTPHEADER,
    array(
        'Content-Type:application/json',
        'Content-Length: ' . strlen($dipake_json)
    ));
    $eksekusi2 = curl_exec($brrcikipeh2);
    return 'success';
}


function cikipeh_UnsuspendAccount($params)
{
  
    $idpelanggan = $params['username'];
    $passinternet = $params['password'];
    $userserver = $params['serverusername'];
    $passserver =  $params['serverpassword'];
    $brasip = $params['serverip'];
    $aman = 'https://';
    $apinya = '/rest/ppp/secret';
    $serverPort = $params['serverport'];
    $urlpanggil = $aman . $brasip . ':' . $serverPort . $apinya;
    $urlunsuspend = $urlpanggil . '/' . $idpelanggan;
    $urlubah = $urlpanggil . '/' . $idpelanggan;
    $apisuspend = '/rest/ppp/active';
    $urlapisuspend = $aman . $brasip . ':' . $serverPort . $apisuspend;
    $paket = $params['configoption1'];
    $tipe = $params['configoption2'];
    $urlkill = $urlapisuspend . '/' . $idpelanggan;
    $yangmaudipake = array(
        "name" => $idpelanggan,
        "password" => $passinternet,
        "profile" => $paket,
        "service" => $tipe,
        "disabled" => "false");
    $dipake_json = json_encode($yangmaudipake);
    $brrcikipeh = curl_init();
    curl_setopt($brrcikipeh, CURLOPT_URL, $urlunsuspend);
    curl_setopt($brrcikipeh, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($brrcikipeh, CURLOPT_SSL_VERIFYHOST, FALSE);
    curl_setopt($brrcikipeh, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($brrcikipeh, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    curl_setopt($brrcikipeh, CURLOPT_USERPWD, $userserver . ':' . $passserver);

    curl_setopt($brrcikipeh, CURLOPT_CUSTOMREQUEST, 'PATCH');
    curl_setopt($brrcikipeh, CURLOPT_POSTFIELDS, $dipake_json);
    curl_setopt($brrcikipeh, CURLOPT_HEADER, true);
    curl_setopt($brrcikipeh, CURLOPT_HTTPHEADER,
    array(
        'Content-Type:application/json',
        'Content-Length: ' . strlen($dipake_json)
    ));
    $eksekusi = curl_exec($brrcikipeh);
    
    $brrcikipeh2 = curl_init();
    curl_setopt($brrcikipeh2, CURLOPT_URL, $urlkill);
    curl_setopt($brrcikipeh2, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($brrcikipeh2, CURLOPT_SSL_VERIFYHOST, FALSE);
    curl_setopt($brrcikipeh2, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($brrcikipeh2, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    curl_setopt($brrcikipeh2, CURLOPT_USERPWD, $userserver . ':' . $passserver);

    curl_setopt($brrcikipeh2, CURLOPT_CUSTOMREQUEST, 'DELETE');
    curl_setopt($brrcikipeh2, CURLOPT_POSTFIELDS, $dipake_json);
    curl_setopt($brrcikipeh2, CURLOPT_HEADER, true);
    curl_setopt($brrcikipeh2, CURLOPT_HTTPHEADER,
    array(
        'Content-Type:application/json',
        'Content-Length: ' . strlen($dipake_json)
    ));
    $eksekusi2 = curl_exec($brrcikipeh2);
    
    return 'success';

    return 'success';
}


function cikipeh_TerminateAccount($params)
{
    $idpelanggan = $params['username'];
    $passinternet = $params['password'];
    $userserver = $params['serverusername'];
    $passserver =  $params['serverpassword'];
    $brasip = $params['serverip'];
    $aman = 'https://';
    $apinya = '/rest/ppp/secret';
    $serverPort = $params['serverport'];
    $urlpanggil = $aman . $brasip . ':' . $serverPort . $apinya;
    $urlterminate = $urlpanggil . '/' . $idpelanggan;
    $paket = $params['configoption1'];
    $dipake_json = json_encode($yangmaudipake);
    $brrcikipeh = curl_init();
    curl_setopt($brrcikipeh, CURLOPT_URL, $urlterminate);
    curl_setopt($brrcikipeh, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($brrcikipeh, CURLOPT_SSL_VERIFYHOST, FALSE);
    curl_setopt($brrcikipeh, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($brrcikipeh, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    curl_setopt($brrcikipeh, CURLOPT_USERPWD, $userserver . ':' . $passserver);

    curl_setopt($brrcikipeh, CURLOPT_CUSTOMREQUEST, 'DELETE');
    $eksekusi = curl_exec($brrcikipeh);

    return 'success';
}

function cikipeh_KillSession($params)
{
    $idpelanggan = $params['username'];
    $passinternet = $params['password'];
    $userserver = $params['serverusername'];
    $passserver =  $params['serverpassword'];
    $brasip = $params['serverip'];
    $aman = 'https://';
    $apinya = '/rest/ppp/secret';
    $serverPort = $params['serverport'];
    $urlpanggil = $aman . $brasip . ':' . $serverPort . $apinya;
    $urlubah = $urlpanggil . '/' . $idpelanggan;
    $paket = $params['configoption1'];
    $tipe = $params['configoption2'];
    $apisuspend = '/rest/ppp/active';
    $urlapisuspend = $aman . $brasip . ':' . $serverPort . $apisuspend;
    $urlkill = $urlapisuspend . '/' . $idpelanggan;
    $yangmaudipake = array(
        "name" => $idpelanggan,
        "password" => $passinternet,
        "service" => $tipe,
        "profile" => $paket,);
    $dipake_json = json_encode($yangmaudipake);
    $brrcikipeh2 = curl_init();
    curl_setopt($brrcikipeh2, CURLOPT_URL, $urlkill);
    curl_setopt($brrcikipeh2, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($brrcikipeh2, CURLOPT_SSL_VERIFYHOST, FALSE);
    curl_setopt($brrcikipeh2, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($brrcikipeh2, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    curl_setopt($brrcikipeh2, CURLOPT_USERPWD, $userserver . ':' . $passserver);

    curl_setopt($brrcikipeh2, CURLOPT_CUSTOMREQUEST, 'DELETE');
    curl_setopt($brrcikipeh2, CURLOPT_POSTFIELDS, $dipake_json);
    curl_setopt($brrcikipeh2, CURLOPT_HEADER, true);
    curl_setopt($brrcikipeh2, CURLOPT_HTTPHEADER,
    array(
        'Content-Type:application/json',
        'Content-Length: ' . strlen($dipake_json)
    ));
    $eksekusi2 = curl_exec($brrcikipeh2);
}


function cikipeh_ChangePackage($params)
{
    $idpelanggan = $params['username'];
    $passinternet = $params['password'];
    $userserver = $params['serverusername'];
    $passserver =  $params['serverpassword'];
    $brasip = $params['serverip'];
    $aman = 'https://';
    $apinya = '/rest/ppp/secret';
    $serverPort = $params['serverport'];
    $urlpanggil = $aman . $brasip . ':' . $serverPort . $apinya;
    $urlubah = $urlpanggil . '/' . $idpelanggan;
    $paket = $params['configoption1'];
    $tipe = $params['configoption2'];
    $apisuspend = '/rest/ppp/active';
    $urlapisuspend = $aman . $brasip . ':' . $serverPort . $apisuspend;
    $urlkill = $urlapisuspend . '/' . $idpelanggan;
    $yangmaudipake = array(
        "name" => $idpelanggan,
        "password" => $passinternet,
        "service" => $tipe,
        "profile" => $paket,);
    $dipake_json = json_encode($yangmaudipake);
    $brrcikipeh = curl_init();
    curl_setopt($brrcikipeh, CURLOPT_URL, $urlubah);
    curl_setopt($brrcikipeh, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($brrcikipeh, CURLOPT_SSL_VERIFYHOST, FALSE);
    curl_setopt($brrcikipeh, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($brrcikipeh, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    curl_setopt($brrcikipeh, CURLOPT_USERPWD, $userserver . ':' . $passserver);

    curl_setopt($brrcikipeh, CURLOPT_CUSTOMREQUEST, 'PATCH');
    curl_setopt($brrcikipeh, CURLOPT_POSTFIELDS, $dipake_json);
    curl_setopt($brrcikipeh, CURLOPT_HEADER, true);
    curl_setopt($brrcikipeh, CURLOPT_HTTPHEADER,
    array(
        'Content-Type:application/json',
        'Content-Length: ' . strlen($dipake_json)
    ));
    $eksekusi = curl_exec($brrcikipeh);
    $brrcikipeh2 = curl_init();
    curl_setopt($brrcikipeh2, CURLOPT_URL, $urlkill);
    curl_setopt($brrcikipeh2, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($brrcikipeh2, CURLOPT_SSL_VERIFYHOST, FALSE);
    curl_setopt($brrcikipeh2, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($brrcikipeh2, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    curl_setopt($brrcikipeh2, CURLOPT_USERPWD, $userserver . ':' . $passserver);

    curl_setopt($brrcikipeh2, CURLOPT_CUSTOMREQUEST, 'DELETE');
    curl_setopt($brrcikipeh2, CURLOPT_POSTFIELDS, $dipake_json);
    curl_setopt($brrcikipeh2, CURLOPT_HEADER, true);
    curl_setopt($brrcikipeh2, CURLOPT_HTTPHEADER,
    array(
        'Content-Type:application/json',
        'Content-Length: ' . strlen($dipake_json)
    ));
    $eksekusi2 = curl_exec($brrcikipeh2);
    
}

function cikipeh_TestConnection(array $params)
{
    try {

    $userserver = $params['serverusername'];
    $passserver =  $params['serverpassword'];
    $brasip = $params['serverip'];
    $aman = 'https://';
    $apinya = '/rest';
    $serverPort = $params['serverport'];
    $urlpanggil = $aman . $brasip . ':' . $serverPort . $apinya;
    $brrcikipeh = curl_init();
    curl_setopt($brrcikipeh, CURLOPT_URL, $urlpanggil);
    curl_setopt($brrcikipeh, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($brrcikipeh, CURLOPT_SSL_VERIFYHOST, FALSE);
    curl_setopt($brrcikipeh, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($brrcikipeh, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    curl_setopt($brrcikipeh, CURLOPT_USERPWD, $userserver . ':' . $passserver);
    curl_setopt($brrcikipeh, CURLOPT_CUSTOMREQUEST, 'GET');
    $eksekusi = curl_exec($brrcikipeh);
    $httpcode = curl_getinfo($brrcikipeh, CURLINFO_HTTP_CODE);
    if($httpcode == '400'){$success = true;
        $errorMsg = '';}
        else if($httpcode == '401'){
            $success = false;
        $errorMsg = 'Username atau Password Salah';
        }       
        else if($httpcode == '0'){
            $success = false;
        $errorMsg = 'IP Atau Port Salah, atau Server tidak dapat terhubung dengan router, mohon cek kembali';
        // Call the service's connection test function.
        }
        
    } catch (Exception $e) {
        // Record the error in WHMCS's module log.
        logModuleCall(
            'cikipeh',
            __FUNCTION__,
            $params,
            $e->getMessage(),
            $e->getTraceAsString()
        );

        $success = false;
        $errorMsg = $e->getMessage();
    }

    return array(
        'success' => $success,
        'error' => $errorMsg,
    );
}


function cikipeh_buttonOneFunction(array $params)
{
    try {
        // Call the service's function, using the values provided by WHMCS in
        // `$params`.
    } catch (Exception $e) {
        // Record the error in WHMCS's module log.
        logModuleCall(
            'cikipeh',
            __FUNCTION__,
            $params,
            $e->getMessage(),
            $e->getTraceAsString()
        );

        return $e->getMessage();
    }

    return 'success';
}


function cikipeh_actionOneFunction(array $params)
{
    try {
        // Call the service's function, using the values provided by WHMCS in
        // `$params`.
    } catch (Exception $e) {
        // Record the error in WHMCS's module log.
        logModuleCall(
            'cikipeh',
            __FUNCTION__,
            $params,
            $e->getMessage(),
            $e->getTraceAsString()
        );

        return $e->getMessage();
    }

    return 'success';
}


function cikipeh_AdminServicesTabFieldsSave(array $params)
{
    // Fetch form submission variables.
    $originalFieldValue = isset($_REQUEST['cikipeh_original_uniquefieldname'])
        ? $_REQUEST['cikipeh_original_uniquefieldname']
        : '';

    $newFieldValue = isset($_REQUEST['cikipeh_uniquefieldname'])
        ? $_REQUEST['cikipeh_uniquefieldname']
        : '';

    // Look for a change in value to avoid making unnecessary service calls.
    if ($originalFieldValue != $newFieldValue) {
        try {
            // Call the service's function, using the values provided by WHMCS
            // in `$params`.
        } catch (Exception $e) {
            // Record the error in WHMCS's module log.
            logModuleCall(
                'cikipeh',
                __FUNCTION__,
                $params,
                $e->getMessage(),
                $e->getTraceAsString()
            );

            // Otherwise, error conditions are not supported in this operation.
        }
    }
}


function cikipeh_ServiceSingleSignOn(array $params)
{
    try {
        // Call the service's single sign-on token retrieval function, using the
        // values provided by WHMCS in `$params`.
        $response = array();

        return array(
            'success' => true,
            'redirectTo' => $response['redirectUrl'],
        );
    } catch (Exception $e) {
        // Record the error in WHMCS's module log.
        logModuleCall(
            'cikipeh',
            __FUNCTION__,
            $params,
            $e->getMessage(),
            $e->getTraceAsString()
        );

        return array(
            'success' => false,
            'errorMsg' => $e->getMessage(),
        );
    }
}


function cikipeh_AdminSingleSignOn(array $params)
{
    try {
        // Call the service's single sign-on admin token retrieval function,
        // using the values provided by WHMCS in `$params`.
        $response = array();

        return array(
            'success' => true,
            'redirectTo' => $response['redirectUrl'],
        );
    } catch (Exception $e) {
        // Record the error in WHMCS's module log.
        logModuleCall(
            'cikipeh',
            __FUNCTION__,
            $params,
            $e->getMessage(),
            $e->getTraceAsString()
        );

        return array(
            'success' => false,
            'errorMsg' => $e->getMessage(),
        );
    }
}


function cikipeh_ClientArea(array $params)
{
    // Determine the requested action and set service call parameters based on
    // the action.
    $requestedAction = isset($_REQUEST['customAction']) ? $_REQUEST['customAction'] : '';

    if ($requestedAction == 'manage') {
        $serviceAction = 'get_usage';
        $templateFile = 'templates/manage.tpl';
    } else {
        $serviceAction = 'get_stats';
        $templateFile = 'templates/overview.tpl';
    }

    try {
        // Call the service's function based on the request action, using the
        // values provided by WHMCS in `$params`.
    $idpelanggan = $params['username'];
    $passinternet = $params['password'];
    $userserver = $params['serverusername'];
    $passserver =  $params['serverpassword'];
    $brasip = $params['serverip'];
    $paketnya = $params['configoption1'];
    $aman = 'https://';
    $apinya = '/rest/ppp/active';
    $apisatunya = '/rest/ppp/secret';
    $apisatunya2 = '/rest/ppp/profile';
    $serverPort = $params['serverport'];
    $urlpanggil = $aman . $brasip . ':' . $serverPort . $apinya;
    $urlpanggil2 = $aman . $brasip . ':' . $serverPort . $apisatunya;
    $urlpanggil3 = $aman . $brasip . ':' . $serverPort . $apisatunya2;
    $urlambil = $urlpanggil . '/' . $idpelanggan;
    $urlambil2 = $urlpanggil2 . '/' . $idpelanggan;
    $urlambil3 = $urlpanggil3 . '/' . $paketnya;
    $brrcikipeh = curl_init();
    curl_setopt($brrcikipeh, CURLOPT_URL, $urlambil);
    curl_setopt($brrcikipeh, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($brrcikipeh, CURLOPT_SSL_VERIFYHOST, FALSE);
    curl_setopt($brrcikipeh, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($brrcikipeh, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    curl_setopt($brrcikipeh, CURLOPT_USERPWD, $userserver . ':' . $passserver);
    curl_setopt($brrcikipeh, CURLOPT_CUSTOMREQUEST, 'GET');
    $eksekusi = curl_exec($brrcikipeh);
    
    $data = json_decode($eksekusi, true);
    
    $brrcikipeh3 = curl_init();
    curl_setopt($brrcikipeh3, CURLOPT_URL, $urlambil2);
    curl_setopt($brrcikipeh3, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($brrcikipeh3, CURLOPT_SSL_VERIFYHOST, FALSE);
    curl_setopt($brrcikipeh3, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($brrcikipeh3, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    curl_setopt($brrcikipeh3, CURLOPT_USERPWD, $userserver . ':' . $passserver);
    curl_setopt($brrcikipeh3, CURLOPT_CUSTOMREQUEST, 'GET');
    $eksekusi3 = curl_exec($brrcikipeh3);
    
    $data3 = json_decode($eksekusi3, true);
    
    $brrcikipeh5 = curl_init();
    curl_setopt($brrcikipeh5, CURLOPT_URL, $urlambil3);
    curl_setopt($brrcikipeh5, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($brrcikipeh5, CURLOPT_SSL_VERIFYHOST, FALSE);
    curl_setopt($brrcikipeh5, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($brrcikipeh5, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    curl_setopt($brrcikipeh5, CURLOPT_USERPWD, $userserver . ':' . $passserver);
    curl_setopt($brrcikipeh5, CURLOPT_CUSTOMREQUEST, 'GET');
    $eksekusi5 = curl_exec($brrcikipeh5);
    
    $data5 = json_decode($eksekusi5, true);
   



       $response = array();


        

        return array(
            'tabOverviewReplacementTemplate' => $templateFile,
            'templateVariables' => array(
                'ipaddress' => $data['address'],
                'macaddress' => $data['caller-id'],
                'uptime' => $data['uptime'],
                'sessionid' => $data['session-id'],
                'servis' => $data['service'],
                'lastdiscconectreason'=> $data3['last-disconnect-reason'],
                'lastdiscconect'=> $data3['last-logged-out'],
                'limitberapa' => $data5['rate-limit']
            ),
        );
    } catch (Exception $e) {
        // Record the error in WHMCS's module log.
        logModuleCall(
            'cikipeh',
            __FUNCTION__,
            $params,
            $e->getMessage(),
            $e->getTraceAsString()
        );

        // In an error condition, display an error page.
        return array(
            'tabOverviewReplacementTemplate' => 'error.tpl',
            'templateVariables' => array(
                'usefulErrorHelper' => $e->getMessage(),
            ),
        );
    }
}
