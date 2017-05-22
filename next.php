<?php
use Facebook\Facebook;
use Facebook\Exceptions\FacebookResponseException;
use Facebook\Exceptions\FacebookSDKException;

session_start();
require_once __DIR__ . '/Facebook/autoload.php';
$fb = new Facebook([
  'app_id' => '1861118167487958',
  'app_secret' => '55f84f46112c6ee78bc6eedff46604d8',
  'default_graph_version' => 'v2.9',
  ]);
$helper = $fb->getRedirectLoginHelper();
//$permissions = ['email']; // optional

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
    
    
    // Getting user facebook profile info
    try {
 
        $profileRequest = $fb->get('/me?fields=name,first_name,last_name,birthday,email,link,gender,locale,picture',$_SESSION['facebook_access_token']);
        $profileRequest1 = $fb->get('/me?fields=name');
        $requestPicture = $fb->get('/me/picture?redirect=false&height=310&width=300'); //getting user picture
        $profileRequest3 = $fb->get('/me?fields=gender');
        $requestFriends = $fb->get('/me/taggable_friends?fields=name&limit=20');
		//$requestFriends = $fb->get('/me/taggable_friends?fields=name&limit=20,picture');
		$fbUserProfile = $profileRequest->getGraphNode()->asArray();
		$friends = $requestFriends->getGraphEdge();
		$birthday= $fb->get('/me?fields=age_range,timezone');
		$a = $fb->get('/me/friends?fields=name,gender');
		$b = $a ->getGraphEdge();
        $fbUserProfile1 = $profileRequest1->getGraphNode();
        $picture = $requestPicture->getGraphNode();
 		$bday = $birthday->getGraphNode();
        $fbUserProfile3 = $profileRequest3->getGraphNode();
        
		
		// If button is clicked a photo with a caption will be uploaded to facebook
		if(isset($_POST['insert'])){
     	$data = ['source' => $fb->fileToUpload(__DIR__.'/dedapp_2.jpg'), 'message' => 'Check out this app! It is awesome http://localhost/fbapp/next.php '];
		$request = $fb->post('/me/photos', $data);
		$response = $request->getGraphNode()->asArray();
		header("Location:https://www.facebook.com/");
     
    }
        
        
        
    } catch(FacebookResponseException $e) {
    
    	
        echo 'Graph returned an errrrrrror: ' . $e->getMessage();
        session_destroy();
        header("Location: ./");
        exit;
    } catch(FacebookSDKException $e) {
        echo 'Facebook SDK returned an error: ' . $e->getMessage();
        exit;
    }
   // assigning a country according to the timezone
  $randomInteger = rand(0,19);
  $name= $friends[$randomInteger]['name'];
  $fid= $friends[$randomInteger]['id'];
  //$frdpic= $friends[$fid]['picture'];
  
  
       // $picture1= $fb->get('/'.$friend[$fid].'/photo?token='.$_SESSION['facebook_access_token']);
    
  $timeZone=$bday['timezone'];
  if($timeZone=='5.5'){
  	
  	$country = array("America","France","Somaliya","Italy","Nigeria");
  }
  else{
  	$country = array("Sri Lanka","India","Ethiopia","Uganda","Gana");
  }

  $selected_country=$country[array_rand($country)];
  $output = $fbUserProfile1;
  
  
  
  // getting gender
  if ($fbUserProfile['gender']=='male'){
  	$gender = 'female';
  }
  else{
  	$gender = 'male';
  }
  
  // Reasons
  
  $reasons = array(
  "When I saw you,I was afraid to meet you. When I met you I was afraid to kiss you. When I kissed you, I was afraid to love you. Now that I love you, I am afraid to lose you.",
  "No matter what has happened. No matter what you’ve done. No matter what you will do. I will always love you. I swear it.",
  "Just wanted to let you know that I love you even though you aren't naked right now.",
  "I wanted to tell you that wherever I am, whatever happens, I’ll always think of you, and the time we spent together, as my happiest time. I’d do it all over again, if I had the choice. No regrets.",
  "I never loved you any more than I do, right this second. And I’ll never love you any less than I do, right this second."
  );
  $selected_reason=$reasons[array_rand($reasons)];
  
  
  
    
}else{

}
?>
<html>
<head>
<title>Find Your True Lover...</title>
 <script src="html2canvas.js"></script> 
<style type="text/css">
body { padding: 2em;
    background-color: black;
	background-image: url("wall.jpg");
    background-size: 1600px 800px;
  	background-repeat: no-repeat;
                                
        }
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
        .head {
    font-family: "Arial", sans-serif;
    font-style: normal;
    color: red;
		}

p{
    font-family: "Arial", sans-serif;
    font-style: normal;
    color: white;
}
    .result{font-family:Futura,Trebuchet MS, Arial, sans-serif;color:#FFF; position: relative;align:center}
    .you { position: relative; top: -200px; left: 300px; } 
    .board{ position:absolute; top:-200px; left:800px;}
    .content{ font-family:Papyrus,fantasy;top:-450px;left:900px;position:relative;font-size:20px; }
	
	p.content {
    width: 17em; 
    word-wrap: break-word;
}
    
    
    .loader{
    
    border: 16px solid #f3f3f3;
    border-radius: 50%;
    border-top: 16px solid #3498db;
    width: 120px;
    height: 120px;
    -webkit-animation: spin 1s linear 3;
    animation: spin 1s linear 3;
    position:relative;
    top:130px;
    left:350px;
    
    
    }
    .loader2{
    
    border: 16px solid #f3f3f3;
    border-radius: 50%;
    border-top: 16px solid #3498db;
    width: 120px;
    height: 120px;
    -webkit-animation: spin 1s linear 3;
    animation: spin 1s linear 3;
    position:relative;
    top:-35px;
    left:900px;
    
    
    }
    
    
    @-webkit-keyframes spin {
    0% { -webkit-transform: rotate(0deg); }
    100% { -webkit-transform: rotate(360deg); }
    }

    @keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
    }
    
    .button{
    background-image: url("share.png");
    background-size: 400px 50px;
    width: 400px;
    height:50px;
    }
 
    
    </style>
    <script>
    var hidden = false;


setTimeout(function(){


document.getElementById("you").style.visibility='hidden';
document.getElementById("board").style.visibility='hidden';
document.getElementById("content").style.visibility='hidden';
},1);


setTimeout(function(){


document.getElementById("you").style.visibility='visible';
document.getElementById("board").style.visibility='visible';
document.getElementById("content").style.visibility='visible';
},3000);



</script>

 
</head>
<body>
<center> <h1 class="head">Who's Gonna Love You Like Crazy?</h1> </center>


<!--<form method="post"><center><input type="submit" name="insert" class="button" value=""/></center></form>-->
<form method="post"><center><button id="goButton" class="loginBtn loginBtn--facebook">Share on Facebook</center></form>


	<!--<h1 class="result"><b><?php echo '<center>.$name." is your True Love!".</center>'; ?></b></h1>-->
	
	<?php echo '<center><h1 class="result"><b>'.$name.' is your True Love!</center>'; ?>
    <section><div class="loader"></div><div class="loader2"></div><div class="images" style="position:relative;left:0;"><?php echo "<img src='".$picture['url']."' class='you' id='you' />
	<img src='border2.jpg'  width='550' height='350' class='board' id='board'/> 
	<p class='content' id='content' style='color:white;'><b>$selected_reason</b></p>"; 
	?></div></section>
	

    </body>
</html>
