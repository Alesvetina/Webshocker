<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$full_name = $_POST["full_name"];
	$shop_name = $_POST["shop_name"];
	$phone_number = $_POST["phone_number"];
	$email = $_POST["email"];
	$countries =$_POST["countries"];

	if(empty($full_name) || empty($shop_name) || empty($phone_number) || empty($email) || empty($countries))
		$errors = "Please fill the whole form.";

	if (empty($errors)) {
		include('mail_data.php');
		$headers = array(
			'From: alesvetina@gmail.com',
			'Content-Type: text/html'
		);
		$subject = "New mail from ELAN";
		$message = '<h1>Hello '.$full_name.'!</h1>';
		$message .= '<p>Prosim uporabi spodnjo povezavo za aktivacijo tvojega OutdoorSlovenia računa.</p>';
		$message .= '<p><a href="'.$homepage.'activation.php?username='.$username.'&code='.$active_code.'">'.$homepage.'activation.php?username='.$username.'&code='.$active_code.'</a></p>';
		
		if (mail($email, $subject, $message, implode("\r\n", $headers))) {
			$insert_new_user = $mysqli->prepare("INSERT INTO users (first_name, last_name, email, username, password, pickup_place, role, active, active_code) VALUES (?,?,?,?,?,?,0,0,?)");
			$code_pass = code_pass($password);
			$insert_new_user->bind_param("sssssss", $first_name, $last_name, $email, $username, $code_pass, $pickup_place, $active_code);
			$insert_new_user->execute();
			
			$errors[] = $lang['ERR_USER_ADDED_1'].' <span style="font-weight:bold">'.$username.'</span> '.$lang['ERR_USER_ADDED_2'];
		}
	}
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Webshocker - Hello World</title>
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link href='http://fonts.googleapis.com/css?family=PT+Sans' rel='stylesheet' type='text/css'>
    <script src="js/jquery-1.11.3.min.js"></script>
    <script src="js/functions.js"></script>
  </head>
  <body>
  	<div class="wrapper">
	  	<div class="Title">
	  		<a href="index.html"><img src="images/logo.png" class="logo" /></a>
	  		<div class="slogan">Experience the new DIMENSION</div>
	  		<div class="applyTxt">Apply for an exclusive get together with Elan.</div>
	  	</div><!-- Title -->
	  	<div class='Forma clearfix'>
	  		<?php
	        if(isset($errors)) "<p>".$errors."</p>";
	      ?>
	  		<form id="mail_form" name="mail_form" method="post" action="">		
		  		<ul>
		  			<li>
				  		<label>Name & Surname</label>
				  		<input type="text" class="input" id="full_name" required onchange="checkFilled($(this));" />
				  	</li>
				  	<li class="middle">
				  		<label>Shop Name</label>
				  		<input type="text" class="input" id="shop_name" required onchange="checkFilled($(this));" />
			  		</li>
				  	<li>
				  		<label>Phone Number</label>
				  		<input type="text" class="input" id="phone_number" required onchange="checkFilled($(this));" />
			  		</li>
				  	<li>
				  		<label>Email</label>
				  		<input type="email" class="input" id="email" required onchange="checkFilled($(this));" />
			  		</li>
				  	<li class="middle">
				  		<label>Country</label>
			  			<select id="countries" class="input" required onchange="checkFilled($(this));">
			  				<option value=""></option>
			  			</select>
			  		</li>
			  		<li>
				  		<label></label>
			  			<input type="submit" class="input btn" value="SUBMIT" />
			  		</li>
			  	</ul>
			  </form>
		  </div><!-- content -->
		  <footer>
				<img src="images/elan_logo.png" class="elanLogo" />
				<div class="copyright">Copyright 2014, Elan d.o.o. All rights reserved.</div>
			</footer>
		</div><!-- wrapper -->
  </body>
</html>