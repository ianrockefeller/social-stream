<?php
    require '../../includes/functions.php';
    require('Client.php');
    require('GrantType/IGrantType.php');
    require('GrantType/AuthorizationCode.php');
    session_start();

    // app information
    $client_id = 'SfYJ4wd8Dm3n1A';
    $client_secret = 'ej5pAjjrueHjZyxjnb7NW_j8WF4';
    

    $redirect_uri = 'http://ec2-54-214-14-238.us-west-2.compute.amazonaws.com/pStream/reddit';
    $id = $_SESSION['id'];
    $con = dbCon();
    $authorizationUrl = 'https://ssl.reddit.com/api/v1/authorize';
    $accessTokenUrl = 'https://ssl.reddit.com/api/v1/access_token';

    $client = new OAUTH2\Client($client_id, $client_secret, OAuth2\Client::AUTH_TYPE_AUTHORIZATION_BASIC);

    // if this is empty, user hasnt gone to the site to authenticate our ap
    $code = $_REQUEST['code'];
    if (empty($code)){
	    $_SESSION['reddit_state'] = md5(uniqid(rand(), TRUE)); //CSRF protection
	    // we just need to read their frontpage timeline
	    $params = array("scope" => "read",
			    "state" => $_SESSION['reddit_state'],
		        );
		// build the auth URL
	    $auth_url = $client->getAuthenticationUrl($authorizationUrl, $redirect_uri, $params);
	    // send em there
	    header('Location: ' . $auth_url);
	    die('Redirect');
    }

    if ($_SESSION['reddit_state'] && ($_SESSION['reddit_state'] == $_REQUEST['state'])){
        // state matches
        // using the code reddit gave us
	    $token_params = array(  'code' => $code,
				                'redirect_uri' => $redirect_uri,
			            );
        // use the code to get a json file containing an access token and refresh token
	    $response = $client->getAccessToken($accessTokenUrl, 'authorization_code', $token_params);
	    $accessTokenResult = $response['result'];
	    // reddit access token store in the state variable
	    $_SESSION['reddit_access_token'] = $accessTokenResult['access_token'];
	    // used to make API calls
	    $client->setAccessToken($accessTokenResult['access_token']);
	    $client->setAccessTokenType(OAuth2\Client::ACCESS_TOKEN_BEARER);
	    if ($con){
	        // store the access token in the DB
	        // LATER: add  refresher token as 4th param for permanent offline access
	        setRedditInfo($con, $id, $_SESSION['reddit_access_token']);
	    }

	    header('Location: ../../index.php');
        }
?>
