<?php
//The php code.
//include ('config.php'); //including the file externally

		if(isset($_POST['submit'])){//This is for when the submit button is clicked
        
        //setting variable names.
		$hostname ='localhost';
		$username ='ai_admin';
		$password='admin';
		
		try{
        
        //connection mode using PDO
		$dbh = new PDO("mysql:host=$hostname;dbname=conference",$username,$password);
		$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		
        //declaring the variables from html input
		$firstName =$_POST['firstName'];
		$lastName =$_POST['lastName'];
		$Email =$_POST['email'];
		$phoneNumber =$_POST['phoneNumber'];
		
		//Let the validation begin
		if(!filter_var($Email,FILTER_VALIDATE_EMAIL)){//validating the email
            $err_email="Enter a valid Email address";
            echo "<div class='error_field'>{$err_email}</div>";//displaying in the err_field div.
        }
        else{
            
		if(!preg_match("/^[[:alpha:]]+$/",$firstName)){
			//die ("Your First Name shouldnt contain Numbers.");
            $err_firstName="Your first name shouldnt contain numbers or space.";
            echo "<div class='error_field'>{$err_firstName}</div>";
		}
		else{
			if(!preg_match("/^[[:alpha:]]+$/",$lastName)){
			//die ("Your Last Name shouldnt contain Numbers.");
            $err_lastName="Your last name shouldnt contain numbers or space";
            echo "<div class='error_field'>{$err_lastName}</div>";
			}
			else{
				if(!preg_match("/^[0-9]+$/",$phoneNumber)){
				//die ("Your Phone Number shouldnt contain letters.");
                $err_phoneNumber="Enter a valid phone number.";
                echo "<div class='error_field'>{$err_phoneNumber}</div>";
				}
				else{
                    if(!isset($_POST['gender'])){
                        $err_gender="Select your gender";
                        echo"<div class='error_field'>{$err_gender}</div>";
                    }
                    else{
                       //Checking for Duplicate Email addresses.
                        $dupEmail="SELECT * FROM attendees WHERE email='$Email'";
                        $dupResult= $dbh->query($dupEmail);
                        $dupArray=$dupResult->fetch(PDO::FETCH_ASSOC);
                        
                        //an empty array returns null
                        if($dupArray==null){
                            
                            //Inserting the values into the database.
						      $sql ="INSERT INTO attendees(firstName, lastName, Email, phoneNumber, Gender) VALUES('".$_POST['firstName']."',
                              '".$_POST['lastName']."','".$_POST['email']."','".$_POST['phoneNumber']."','".$_POST['gender']."')";
					
					       //$dbh->exec($sql);
                           if($dbh->query($sql)){//if inserted to echo a javascript alert.

                            echo"<script type='text/javascript'>alert('An Email with Your reservation details has been sent to you.');</script>";

                            //Getting the ID value from the table
                            $IdSel ="SELECT id FROM attendees WHERE email='$Email'";
                            $result =$dbh->query($IdSel);
                            $IdArray =$result->fetch(PDO::FETCH_ASSOC);
                            $IdVal=$IdArray['id'];

                            //echo $IdVal;


                            //Variables to send mail.
                            $text="You have made a reservation at the AI conference, your reservation ID is ".$IdVal;
                            $header="From: dawn.idoko@gmail.com";
                            $subject="Welcome to the AI Conference";

                            //sending the mail. make the correct modifications to your php.ini and sendmail files
                            mail($Email,$subject,$text,$header);
                            //echo "Success!!!";
                            
                            $_POST['firstName'] ="";
                            $_POST['lastName'] ="";
                            $_POST['email'] ="";
                            $_POST['phoneNumber'] ="";
                            
                           }
                            else{
                               echo "<script type='text/javascript'>alert('Data not successfully Inserted');</script>";}
                        }
                        
                    
                        else{ 
                          $err_dup="Sorry this Email address has been used.";
                          echo"<div class='error_field'>{$err_dup}</div>";}
                    
                    
                        }//closes else after check for phoneNumber.                    
                        }//closes else after radio button
					   }//closes else after check for lastName
                      

					
				}//closes else after validation.
				
				
			}//closes else for email.
				
			//}//closes try
        //}//closes first if for submit.
		
		
		
		
		$dbh = null;
		}
		catch(PDOException $e)
		{echo $e->getMessage();}
		}

?>

<html>
<head>
	<title>
		AI Conference
	</title>
	<link rel="stylesheet" href="index_css.css"/>
</head>

<body>
    <div class="header"><a href="#">AI Conference Registration</a></div>
	<div class="center">
       
		<form class="form" action="me.php" method="POST">
			<input class="input" required type="text" name="firstName" placeholder="First Name" value="<?php echo isset($_POST['firstName']) ? $_POST['firstName'] : '' ?>" /><br/>
            
			<input class="input" required type="text" name="lastName" placeholder="Last Name" value="<?php echo isset($_POST['lastName']) ? $_POST['lastName'] : '' ?>" /><br/>
            
			<input class="input"  type="email" name="email" placeholder="Email address" value="<?php echo isset($_POST['email']) ? $_POST['email'] : '' ?>" /><br/>
            
			<input class="input" required type="tel" name="phoneNumber" placeholder="Phone Number" value="<?php echo isset($_POST['phoneNumber']) ? $_POST['phoneNumber'] : '' ?>" /><br/>
            
			<input class="radio"  type="radio" name="gender" value="Male"/><span>Male</span>
            <input class="radio"  type="radio" name="gender" value="Female"/><span>Female</span><br/>
            
			<input class="button"required type="submit" value="submit" name="submit" />
			
		</form>
	</div>
	
	
</body>
</html>
