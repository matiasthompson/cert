<?php

    header('Access-Control-Allow-Origin: *'); 
    
    include('./setup/arrays.php');
    
    $postdata = file_get_contents("php://input");
    $request = json_decode($postdata);

    $ud   = $request->ud;
    $user = $request->user;
    $pass = $request->pass;

    if($ud && $user && $pass){
        
        $userPrincipalName = "$user@$ud.infra.d";
        $filter = "(samaccountname=$user)";
        $attrs = array("memberOf");
    
        $ds = @ldap_connect($ldap['host'], $ldap['port']);
        
        if( $ds ) {
            ldap_set_option($ds, LDAP_OPT_PROTOCOL_VERSION, 3);
            ldap_set_option($ds, LDAP_OPT_REFERRALS, 0);
        
            $ldapbind = @ldap_bind($ds, $userPrincipalName, $pass);
            
            if( getErr($ds) == -1 ) {
                $data['auth'] = false;
                $data['error'] = 2;
                echo json_encode($data);
            } else if( getErr($ds) == 49 ) {
                $data['auth'] = false;
                $data['error'] = 3;
                echo json_encode($data);
            } else {
                $userdn = getDN($ds, $user, $ldap['basedn']);
                
                $data['auth'] = true;
                $data['error'] = 0;
                echo json_encode($data);
                
                ldap_unbind($ds);
            }
        }
    } else {
        $data['auth'] = false;
        $data['error'] = 1;
        echo json_encode($data);
    }

    //#########################################################################################//
    //###################################                   ###################################//
    //###################################        LDAP       ###################################//
    //###################################                   ###################################//
    //#########################################################################################//
    
    function getErr($ds){
        $err = ldap_errno($ds);
        return $err;
    }

    function getDN($ds, $user, $basedn) {
        $attributes = array('dn');
        $result = ldap_search($ds, $basedn, "(samaccountname=$user)", $attributes);
        if ($result === FALSE) { return ''; }
        $entries = ldap_get_entries($ds, $result);
        if ($entries['count']>0) { return $entries[0]['dn']; }
        else { return ''; };
    }

    function getCNofDN($userdn) {
        $return=preg_match('/[^cn=]([^,]*)/i',$userdn,$userdn);
        print_r($return);
        return($userdn[0]);
    }

    function checkGroupEx($ds, $userdn, $group) {
        $attributes = array('memberof');
        
        $result = ldap_read($ds, $userdn, '(objectclass=*)', $attributes);
        if ($result === FALSE) { return FALSE; };
        $entries = ldap_get_entries($ds, $result);
        if ($entries['count'] <= 0) { return FALSE; };
        if (empty($entries[0]['memberof'])) { return FALSE; } else {
            for ($i = 0; $i < $entries[0]['memberof']['count']; $i++) {
                if ($entries[0]['memberof'][$i] == $group) { return TRUE; }
                elseif (checkGroupEx($ds, $entries[0]['memberof'][$i], $group)) { return TRUE; };
            };
        };
        return FALSE;
    }



    function getMail($ds, $userdn) {
        $attributes = array('mail');
        
        $result = ldap_read($ds, $userdn, '(objectclass=*)', $attributes);
        if ($result === FALSE) { 
            return FALSE;
        }
        
        $entries = ldap_get_entries($ds, $result);
        
        if ($entries['count'] <= 0) { 
            return FALSE; 
        }
        
        if (empty($entries[0]['mail'])) { 
            return FALSE;
        } else {
            return $entries[0]['mail'][0];
        }
        return FALSE;
    }

?>