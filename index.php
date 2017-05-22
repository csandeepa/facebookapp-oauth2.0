<html>
<head>
<title>Find Your True Lover...</title>
 
<style type="text/css"> 
body { padding: 2em;
               background-color: black;
                                
        }


/* Shared */
.loginBtn {
  box-sizing: border-box;
  position: relative;
  /* width: 13em;  - apply for fixed size */
  margin: 0.2em;
  padding: 0 15px 0 46px;
  border: none;
  text-align: left;
  line-height: 34px;
  white-space: nowrap;
  border-radius: 0.2em;
  font-size: 16px;
  color: #FFF;
}
.loginBtn:before {
  content: "";
  box-sizing: border-box;
  position: absolute;
  top: 0;
  left: 0;
  width: 34px;
  height: 100%;
}
.loginBtn:focus {
  outline: none;
}
.loginBtn:active {
  box-shadow: inset 0 0 0 32px rgba(0,0,0,0.1);
}


/* Facebook */
.loginBtn--facebook {
  background-color: #4C69BA;
  background-image: linear-gradient(#4C69BA, #3B55A0);
  /*font-family: "Helvetica neue", Helvetica Neue, Helvetica, Arial, sans-serif;*/
  text-shadow: 0 -1px 0 #354C8C;
}
.loginBtn--facebook:before {
  border-right: #364e92 1px solid;
  background: url('https://s3-us-west-2.amazonaws.com/s.cdpn.io/14082/icon_facebook.png') 6px 6px no-repeat;
}
.loginBtn--facebook:hover,
.loginBtn--facebook:focus {
  background-color: #5B7BD5;
  background-image: linear-gradient(#5B7BD5, #4864B1);
  
}

        .button {
            background-color: #4286f4;
            border: none;
            color: white;
            padding: 15px 32px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            margin: 4px 2px;
            cursor: pointer;
        }
        h1 {
    font-family: "Arial", sans-serif;
    font-style: normal;
    color: red;
}
p{
    font-family: "Arial", sans-serif;
    font-style: normal;
    color: white;
}
/* unvisited link */
a:link {
    color: white;
}

/* visited link */
a:visited {
    color: white;
}

/* mouse over link */
a:hover {
    color: white;
}

/* selected link */
a:active {
    color: white;
}
a:link {
    text-decoration: none;
}

a:visited {
    text-decoration: none;
}

a:hover {
    text-decoration: none;
}

a:active {
    text-decoration: none;
}
    </style>
    <script>var hidden = false;
var count = 1;
setInterval(function(){ // This function is here for the blink effect of the button
	
    document.getElementById("link").style.visibility= hidden ? "visible" : "hidden"; // setInterval will execute this infinite time
    																				// within interval of 300 seconds
  
   hidden = !hidden;

},300);


</script>

 
</head>
<body>


	<center> <h1>Who's Gonna Love You Like Crazy?</h1> </center>

<center> <img src="dedapp_3.jpg" align="middle"> </center>
<br/>
<center>
    <p>Love is an endless mystery, for it has nothing else to explain it.</p>
    <p>Find out who is going to change your world with love!</p></center>
<br/>

<!--<button id="goButton" type="button" class="button">Continue with Facebook !</button>-->
<!--<center><button id="goButton" class="loginBtn loginBtn--facebook" >
  Continue with Facebook
    </button></center>-->

<div id="results"></div>
	
 
    </body>
</html>



<?php
// new 
session_start();
require_once __DIR__ . '/Facebook/autoload.php';
$fb = new Facebook\Facebook([
  'app_id' => '1861118167487958',
  'app_secret' => '55f84f46112c6ee78bc6eedff46604d8',
  'default_graph_version' => 'v2.9',
  ]);
$helper = $fb->getRedirectLoginHelper();
//$permissions = ['email']; // optional
//$permissions = ['friendlist'];
$permissions =  array("email","user_friends");	
try {
	if (isset($_SESSION['facebook_access_token'])) {
		$accessToken = $_SESSION['facebook_access_token'];
	} else {
  		$accessToken = $helper->getAccessToken();
	}
} catch(Facebook\Exceptions\FacebookResponseException $e) {
 	// When Graph returns an error
 	echo 'Graph returned an error: ' . $e->getMessage();
  	exit;
} catch(Facebook\Exceptions\FacebookSDKException $e) {
 	// When validation fails or other local issues
	echo 'Facebook SDK returned an error: ' . $e->getMessage();
  	exit;
 }
if (isset($accessToken)) {
	if (isset($_SESSION['facebook_access_token'])) {
		$fb->setDefaultAccessToken($_SESSION['facebook_access_token']);
		header('Location: http://localhost/fbapp/next.php');
	} else {
		// getting short-lived access token
		$_SESSION['facebook_access_token'] = (string) $accessToken;
	  	// OAuth 2.0 client handler
		$oAuth2Client = $fb->getOAuth2Client();
		// Exchanges a short-lived access token for a long-lived one
		$longLivedAccessToken = $oAuth2Client->getLongLivedAccessToken($_SESSION['facebook_access_token']);
		$_SESSION['facebook_access_token'] = (string) $longLivedAccessToken;
		// setting default access token to be used in script
		$fb->setDefaultAccessToken($_SESSION['facebook_access_token']);
	}
	// redirect the user back to the same page if it has "code" GET variable
	if (isset($_GET['code'])) {
		
		header('Location: ./');
	}
	//header('Location: http://localhost/fbapp/next.php');

} else {
	// replace your website URL same as added in the developers.facebook.com/apps e.g. if you used http instead of https and you used non-www version or www version of your website then you must add the same here
	$loginUrl = $helper->getLoginUrl('http://localhost/fbapp/index.php', $permissions);
	
	echo '<center><button id="goButton" class="loginBtn loginBtn--facebook"><a href="' . $loginUrl . '" target="_blank">
	Continue with Facebook
	</a></button></center>';
	
	
   
  

}



?>