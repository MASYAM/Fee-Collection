<?php
ob_start();
require('config2.php');


//Pre-define validation
$validationresults=TRUE;
$registered=TRUE;
$recaptchavalidation=TRUE;

//Trapped brute force attackers and give them more hard work by providing a captcha-protected page

$iptocheck= $_SERVER['REMOTE_ADDR'];
$iptocheck= mysqli_real_escape_string($dbhandle, $iptocheck);

    if ($fetch = mysqli_fetch_array( mysqli_query($dbhandle, "SELECT `loggedip` FROM `ipcheck_for_setup` WHERE `loggedip`='".$iptocheck."'")) )
  {
        //Already has some IP address records in the database
        //Get the total failed login attempts associated with this IP address

      $resultx = mysqli_query($dbhandle, "SELECT `failedattempts` FROM `ipcheck_for_setup` WHERE `loggedip`='".$iptocheck."'");
        $rowx = mysqli_fetch_array($resultx);
        $loginattempts_total = $rowx['failedattempts'];

        If ($loginattempts_total>$maxfailedattempt) 
        {
            //too many failed attempts allowed, redirect and give 403 forbidden.

            //header(sprintf("Location: %s", $forbidden_url));	
            //exit;
        }
  }

//Check if a user has logged-in

if (!isset($_SESSION['logged_in2'])) 
  {
    $_SESSION['logged_in2'] = FALSE;
  }

//Check if the form is submitted

if ((isset($_POST["pass2"])) && (isset($_POST["user2"])) && ($_SESSION['LAST_ACTIVITY2']==FALSE)) 
{

//Username and password has been submitted by the user
//Receive and sanitize the submitted information

    $user= filter_var($_POST["user2"], FILTER_SANITIZE_STRING);
    $pass= filter_var($_POST["pass2"], FILTER_SANITIZE_STRING);


//validate username
if (!($fetch = mysqli_fetch_array( mysqli_query($dbhandle, "SELECT `username` FROM `authentication_for_setup` WHERE `username`='".$user."'"))))
  {
    //no records of username in database
    //user is not yet registered

    $registered=FALSE;
 }

if ($registered==TRUE) 
  {

    //Grab login attempts from MySQL database for a corresponding username
    $result1 = mysqli_query($dbhandle, "SELECT `loginattempt` FROM `authentication_for_setup` WHERE `username`='".$user."'");
    $row = mysqli_fetch_array($result1);
    $loginattempts_username = $row['loginattempt'];

 }

/*if(($loginattempts_username>3) || ($loginattempts_total>3)) 
  {
        //Require those user with login attempts failed records to 
        //submit captcha and validate recaptcha

        require_once('recaptchalib.php');
        $resp = recaptcha_check_answer ($privatekey, $_SERVER["REMOTE_ADDR"], $_POST["recaptcha_challenge_field"], $_POST["recaptcha_response_field"]);
        if (!$resp->is_valid) {

        //captcha validation fails

        $recaptchavalidation=FALSE;
        }else 
            {
                $recaptchavalidation=TRUE;	
            }
}*/

//Get correct hashed password based on given username stored in MySQL database

if ($registered==TRUE) 
 {
	
    //username is registered in database, now get the hashed password

    $result = mysqli_query($dbhandle, "SELECT `password` FROM `authentication_for_setup` WHERE `username`='".$user."'");
    $row = mysqli_fetch_array($result);
    $correctpassword = $row['password'];
    $salt = substr($correctpassword, 0, 64);
    $correcthash = substr($correctpassword, 64, 64);
    $userhash = hash("sha256", $salt . $pass);

  }
 if ((!($userhash == $correcthash)) || ($registered==FALSE) || ($recaptchavalidation==FALSE)) 
    {

            //user login validation fails

            $validationresults=FALSE;

            //log login failed attempts to database

            if ($registered==TRUE) 
            {
                    $loginattempts_username= $loginattempts_username + 1;
                    $loginattempts_username=intval($loginattempts_username);

                    //update login attempt records

                mysqli_query($dbhandle, "UPDATE `authentication_for_setup` SET `loginattempt` = '".$loginattempts_username."' WHERE `username` = '".$user."'");

                    //Possible brute force attacker is targeting registered usernames
                    //check if has some IP address records

                if (!($fetch = mysqli_fetch_array( mysqli_query($dbhandle, "SELECT `loggedip` FROM `ipcheck_for_setup` WHERE `loggedip`='".$iptocheck."'"))))
                     {

                       //no records
                       //insert failed attempts

                       $loginattempts_total=1;
                       $loginattempts_total=intval($loginattempts_total);
                         mysqli_query($dbhandle, "INSERT INTO `ipcheck_for_setup` (`loggedip`, `failedattempts`) VALUES ('".$iptocheck."', '".$loginattempts_total."')");
                     } else
                          {
                            //has some records, increment attempts

                             $loginattempts_total= $loginattempts_total + 1;
                              mysqli_query($dbhandle, "UPDATE `ipcheck_for_setup` SET `failedattempts` = '".$loginattempts_total."' WHERE `loggedip` = '".$iptocheck."'");
                          }
            }

            //Possible brute force attacker is targeting randomly

            if ($registered==FALSE) 
            {
                if (!($fetch = mysqli_fetch_array( mysqli_query($dbhandle, "SELECT `loggedip` FROM `ipcheck_for_setup` WHERE `loggedip`='".$iptocheck."'"))))
                 {

                   //no records
                   //insert failed attempts

                   $loginattempts_total=1;
                   $loginattempts_total=intval($loginattempts_total);
                     mysqli_query($dbhandle, "INSERT INTO `ipcheck_for_setup` (`loggedip`, `failedattempts`) VALUES ('".$iptocheck."', '".$loginattempts_total."')");
                 }else 
                     {
                        //has some records, increment attempts

                        $loginattempts_total= $loginattempts_total + 1;
                         mysqli_query($dbhandle, "UPDATE `ipcheck_for_setup` SET `failedattempts` = '".$loginattempts_total."' WHERE `loggedip` = '".$iptocheck."'");
                     }
             }
  }else 
      {
            //user successfully authenticates with the provided username and password

            //Reset login attempts for a specific username to 0 as well as the ip address

            $loginattempts_username=0;
            $loginattempts_total=0;
            $loginattempts_username=intval($loginattempts_username);
            $loginattempts_total=intval($loginattempts_total);
          mysqli_query($dbhandle, "UPDATE `authentication_for_setup` SET `loginattempt` = '".$loginattempts_username."' WHERE `username` = '".$user."'");
          mysqli_query($dbhandle, "UPDATE `ipcheck_for_setup` SET `failedattempts` = '".$loginattempts_total."' WHERE `loggedip` = '".$iptocheck."'");

            //Generate unique signature of the user based on IP address
            //and the browser then append it to session
            //This will be used to authenticate the user session 
            //To make sure it belongs to an authorized user and not to anyone else.
            //generate random salt
            function genRandomString() {
                $length = 50;
                $characters = "0123456789abcdef";      
                for ($p = 0; $p < $length ; $p++) {
                    $string .= $characters[mt_rand(0, strlen($characters))];
                }

                return $string;
            }
            $random=genRandomString();
            $salt_ip= substr($random, 0, $length_salt);

            //hash the ip address, user-agent and the salt
            $useragent=$_SERVER["HTTP_USER_AGENT"];
            $hash_user= sha1($salt_ip.$iptocheck.$useragent);

            //concatenate the salt and the hash to form a signature
            $signature= $salt_ip.$hash_user;


            $_SESSION['signature'] = $signature;
            $_SESSION['logged_in2'] = TRUE;
            $_SESSION['LAST_ACTIVITY2'] = time(); 
            $_SESSION['username2'] = $user;
            ini_set('max_execution_time', 1800); //3600 seconds = 60 minutes
            ini_set("memory_limit","1500M");
            
            $query_welcome_message = mysqli_query($dbhandle, "SELECT welcome_message FROM authentication_for_setup WHERE username='".$user."' ") or die(mysql_error());
            $result_query_welcome_message = mysqli_fetch_array($query_welcome_message);
            $welcome_message = $result_query_welcome_message['welcome_message'];
            $_SESSION['welcome_message'] = $welcome_message;
            
      }
}//end of if ((isset($_POST["pass"])) && (isset($_POST["user"])) && ($_SESSION['LAST_ACTIVITY']==FALSE))

if (!$_SESSION['logged_in2'])
{
?>

            
						
                        <div style="width:350px;height:200px;padding:10px; border:1.5px solid #6700AA;border-radius:10px; margin:20px auto;"> 
						   
						   <div style="width: 250px;height:35px;margin:10px auto;text-align:center;color:#ff3300;">
                              <span style="font-weight:bold;font-size:20px;">AUTHORIZE LOGIN (SETUP)</span>
                           </div>
						<form action="" method="POST">
						
                        	                        <table style="margin:auto;font-weight:bold;" cellpadding="10">
                                                            <tr>
								<td>Username</td>
								<td><input type="text" style="border:2px solid #9933ff;width:200px;height:20px;border-radius:10px;" id="user" name="user2" ></td>
                                                            </tr>
							     <tr>
								<td>Password</td>
								<td><input type="password" style="border:2px solid #9933ff;width:200px;height:20px;border-radius:10px;"  id="pass" name="pass2" ></td>
                                                             </tr>
                                                        </table>
                                                        <?php
                                                            if (($loginattempts_username > 3) || ($loginattempts_total>3)) 
                                                            { 
                                                          ?>
                                                          <!--<table style="margin:auto;">
                                                          <tr>
                                                           <td>
                                                          <span style="color:#000;"><b>Type the captcha below:</b></span>-->
                                                          <?php
                                                          //require_once('recaptchalib.php');
                                                          //echo recaptcha_get_html($publickey);
                                                          ?>
                                                           <!--</td>
                                                          </tr>
                                                          </table>-->
                                                         <?php } ?>
							<p><input type="submit" id="submit_button" name="Submit" value="Login" style="float:right;"></p>
                                                        
						
					        </form>
                                              <?php if ($validationresults==FALSE) echo '<br><div style="width:420px;margin-top:50px;color:red;">Please enter valid username, password or captcha ( if required )</div>'; ?>
                                           </div>
                          </div>   

   </div>
<?php require 'includes/footer.php';   ?>           
<?php
 exit;  }
?>
