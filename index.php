<?php require('includes/config.php');

//if logged in redirect to members page
if( $user->is_logged_in() ){ header('Location: dashboard.php'); exit(); }

//if form has been submitted process it
if(isset($_POST['submit'])){

    if (!isset($_POST['username'])) $error[] = "Please fill out all fields";
    if (!isset($_POST['email'])) $error[] = "Please fill out all fields";
    if (!isset($_POST['password'])) $error[] = "Please fill out all fields";

	$username = $_POST['username'];

	//very basic validation
	if(!$user->isValidUsername($username)){
		$error[] = 'Usernames must be at least 3 Alphanumeric characters';
	} else {
		$stmt = $db->prepare('SELECT username FROM members WHERE username = :username');
		$stmt->execute(array(':username' => $username));
		$row = $stmt->fetch(PDO::FETCH_ASSOC);

		if(!empty($row['username'])){
			$error[] = 'Username provided is already in use.';
		}

	}

	if(strlen($_POST['password']) < 3){
		$error[] = 'Password is too short.';
	}

	if(strlen($_POST['passwordConfirm']) < 3){
		$error[] = 'Confirm password is too short.';
	}

	if($_POST['password'] != $_POST['passwordConfirm']){
		$error[] = 'Passwords do not match.';
	}

	//email validation
	$email = htmlspecialchars_decode($_POST['email'], ENT_QUOTES);
	if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
	    $error[] = 'Please enter a valid email address';
	} else {
		$stmt = $db->prepare('SELECT email FROM members WHERE email = :email');
		$stmt->execute(array(':email' => $email));
		$row = $stmt->fetch(PDO::FETCH_ASSOC);

		if(!empty($row['email'])){
			$error[] = 'Email provided is already in use.';
		}

	}


	//if no errors have been created carry on
	if(!isset($error)){

		//hash the password
		$hashedpassword = $user->password_hash($_POST['password'], PASSWORD_BCRYPT);

		//create the activasion code
		$activasion = md5(uniqid(rand(),true));

		try {

			//insert into database with a prepared statement
			$stmt = $db->prepare('INSERT INTO members (username,password,email,active) VALUES (:username, :password, :email, :active)');
			$stmt->execute(array(
				':username' => $username,
				':password' => $hashedpassword,
				':email' => $email,
				':active' => $activasion
			));
			$id = $db->lastInsertId('memberID');

			//send email
			$to = $_POST['email'];
			$subject = "Registration Confirmation";
			$body = "<p>Thank you for registering at demo site.</p>
			<p>To activate your account, please click on this link: <a href='".DIR."activate.php?x=$id&y=$activasion'>".DIR."activate.php?x=$id&y=$activasion</a></p>
			<p>Regards Site Admin</p>";

			$mail = new Mail();
			$mail->setFrom(SITEEMAIL);
			$mail->addAddress($to);
			$mail->subject($subject);
			$mail->body($body);
			$mail->send();

			//redirect to index page
			header('Location: index.php?action=joined');
			exit;

		//else catch the exception and show the error.
		} catch(PDOException $e) {
		    $error[] = $e->getMessage();
		}

	}

}

//define page title
$title = 'MaxDS.com';

require('layout/head_index.php');
?>


<div class="container">

	<div class="row">

	    <div class="col-xs-12 col-sm-8 col-md-6 col-sm-offset-2 col-md-offset-3">
			<form role="form" method="post" action="" autocomplete="off">
				<h2>Please Sign Up</h2>
				<p>Already a member? <a href='login.php'>Login</a></p>
				<hr>

				<?php
				//check for any errors
				if(isset($error)){
					foreach($error as $error){
						echo '<p class="bg-danger">'.$error.'</p>';
					}
				}

				//if action is joined show sucess
				if(isset($_GET['action']) && $_GET['action'] == 'joined'){
					echo "<h2 class='bg-success'>Registration successful, please check your email to activate your account.</h2>";
				}
				?>

				<div class="form-group">
					<input type="text" name="username" id="username" class="form-control input-lg" placeholder="User Name" value="<?php if(isset($error)){ echo htmlspecialchars($_POST['username'], ENT_QUOTES); } ?>" tabindex="1">
				</div>
				<div class="form-group">
					<input type="email" name="email" id="email" class="form-control input-lg" placeholder="Email Address" value="<?php if(isset($error)){ echo htmlspecialchars($_POST['email'], ENT_QUOTES); } ?>" tabindex="2">
				</div>
				<div class="row">
					<div class="col-xs-6 col-sm-6 col-md-6">
						<div class="form-group">
							<input type="password" name="password" id="password" class="form-control input-lg" placeholder="Password" tabindex="3">
						</div>
					</div>
					<div class="col-xs-6 col-sm-6 col-md-6">
						<div class="form-group">
							<input type="password" name="passwordConfirm" id="passwordConfirm" class="form-control input-lg" placeholder="Confirm Password" tabindex="4">
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col-xs-6 col-md-6"><input type="submit" name="submit" value="Register" class="btn btn-primary btn-block btn-lg" tabindex="5"></div>
				</div>
			</form>
		</div>
	</div>
	<div class="cont">
		<h2>About MaxDS.com</h2>
		<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
		tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
		quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
		consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
		cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
		proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
	</div>
	<div class="cont">
		<h2>Pricing</h2>
			<div class="row">
    			<div class="col-xs-6 col-sm-6 col-md-3 col-lg-3">
				
					<!-- PRICE ITEM -->
					<div class="panel price panel-red">
						<div class="panel-heading  text-center">
						<h3>FREE</h3>
						</div>
						<div class="panel-body text-center">
							<p class="lead" style="font-size:40px"><strong>$00.00 / month</strong></p>
						</div>
						<ul class="list-group list-group-flush text-center">
							<li class="list-group-item"><span class="glyphicon glyphicon glyphicon-ok"></span> 10 Lisitngs</li>
							<li class="list-group-item"><span class="glyphicon glyphicon glyphicon-remove"></span> Price Monitor</li>
							<li class="list-group-item"><span class="glyphicon glyphicon glyphicon-remove"></span> Auto-order</li>
							<li class="list-group-item"><span class="glyphicon glyphicon glyphicon-remove"></span> Profitability Monitor</li>
							<li class="list-group-item"><span class="glyphicon glyphicon glyphicon-remove"></span> Auto Messanger</li>
							<li class="list-group-item"><span class="glyphicon glyphicon glyphicon-ok"></span> Help Centre</li>
							<li class="list-group-item"><span class="glyphicon glyphicon glyphicon-remove"></span> Reply in 24hrs Support</li>
						</ul>
						<div class="panel-footer">
							<a class="btn btn-lg btn-block btn-danger" href="#">BUY NOW!</a>
						</div>
					</div>
					<!-- /PRICE ITEM -->
					
					
				</div>
				
				<div class="col-xs-6 col-sm-6 col-md-3 col-lg-3">
				
					<!-- PRICE ITEM -->
					<div class="panel price panel-blue">
						<div class="panel-heading arrow_box text-center">
						<h3>Just Started</h3>
						</div>
						<div class="panel-body text-center">
							<p class="lead" style="font-size:40px"><strong>$12.99 / month</strong></p>
						</div>
						<ul class="list-group list-group-flush text-center">
							<li class="list-group-item"><span class="glyphicon glyphicon glyphicon-ok"></span> 100 Lisitngs</li>
							<li class="list-group-item"><span class="glyphicon glyphicon glyphicon-ok"></span> Price Monitor</li>
							<li class="list-group-item"><span class="glyphicon glyphicon glyphicon-ok"></span> Auto-order(0.30$ per order)</li>
							<li class="list-group-item"><span class="glyphicon glyphicon glyphicon-ok"></span> Profitability Monitor</li>
							<li class="list-group-item"><span class="glyphicon glyphicon glyphicon-ok"></span> Auto Messanger</li>
							<li class="list-group-item"><span class="glyphicon glyphicon glyphicon-ok"></span> Help Centre</li>
							<li class="list-group-item"><span class="glyphicon glyphicon glyphicon-ok"></span> Reply in 24hrs Support</li>
						</ul>
						<div class="panel-footer">
							<a class="btn btn-lg btn-block btn-info" href="#">BUY NOW!</a>
						</div>
					</div>
					<!-- /PRICE ITEM -->
					
					
				</div>
				
				<div class="col-xs-6 col-sm-6 col-md-3 col-lg-3">
				
					<!-- PRICE ITEM -->
					<div class="panel price panel-green">
						<div class="panel-heading arrow_box text-center">
						<h3>Moving on</h3>
						</div>
						<div class="panel-body text-center">
							<p class="lead" style="font-size:40px"><strong>$25.99 / month</strong></p>
						</div>
						<ul class="list-group list-group-flush text-center">
							<li class="list-group-item"><span class="glyphicon glyphicon glyphicon-ok"></span> 750 Lisitngs</li>
							<li class="list-group-item"><span class="glyphicon glyphicon glyphicon-ok"></span> Price Monitor</li>
							<li class="list-group-item"><span class="glyphicon glyphicon glyphicon-ok"></span> Auto-order(0.20$ per order)</li>
							<li class="list-group-item"><span class="glyphicon glyphicon glyphicon-ok"></span> Profitability Monitor</li>
							<li class="list-group-item"><span class="glyphicon glyphicon glyphicon-ok"></span> Auto Messanger</li>
							<li class="list-group-item"><span class="glyphicon glyphicon glyphicon-ok"></span> Help Centre</li>
							<li class="list-group-item"><span class="glyphicon glyphicon glyphicon-ok"></span> Reply in 24hrs Support</li>
						</ul>
						<div class="panel-footer">
							<a class="btn btn-lg btn-block btn-success" href="#">BUY NOW!</a>
						</div>
					</div>
					<!-- /PRICE ITEM -->
				</div>
				<div class="col-xs-6 col-sm-6 col-md-3 col-lg-3">
				
					<!-- PRICE ITEM -->
					<div class="panel price panel-grey">
						<div class="panel-heading arrow_box text-center">
						<h3>Kick it!</h3>
						</div>
						<div class="panel-body text-center">
							<p class="lead" style="font-size:40px"><strong>$50.00 / month</strong></p>
						</div>
						<ul class="list-group list-group-flush text-center">
							<li class="list-group-item"><span class="glyphicon glyphicon glyphicon-ok"></span> 2500 Lisitngs</li>
							<li class="list-group-item"><span class="glyphicon glyphicon glyphicon-ok"></span> Price Monitor</li>
							<li class="list-group-item"><span class="glyphicon glyphicon glyphicon-ok"></span> Auto-order(0.10$ per order)</li>
							<li class="list-group-item"><span class="glyphicon glyphicon glyphicon-ok"></span> Profitability Monitor</li>
							<li class="list-group-item"><span class="glyphicon glyphicon glyphicon-ok"></span> Auto Messanger</li>
							<li class="list-group-item"><span class="glyphicon glyphicon glyphicon-ok"></span> Help Centre</li>
							<li class="list-group-item"><span class="glyphicon glyphicon glyphicon-ok"></span> 24/7 Support</li>
						</ul>
						<div class="panel-footer">
							<a class="btn btn-lg btn-block btn-primary" href="#">BUY NOW!</a>
						</div>
					</div>
					<!-- /PRICE ITEM -->
					
					
				</div>
			</div>
					<div class="row">
    			<div class="col-xs-6 col-sm-6 col-md-3 col-lg-3">
				
					<!-- PRICE ITEM -->
					<div class="panel price panel-red">
						<div class="panel-heading  text-center">
						<h3>I'm warrior!</h3>
						</div>
						<div class="panel-body text-center">
							<p class="lead" style="font-size:40px"><strong>$74.99 / month</strong></p>
						</div>
						<ul class="list-group list-group-flush text-center">
							<li class="list-group-item"><span class="glyphicon glyphicon glyphicon-ok"></span> 7500 Lisitngs</li>
							<li class="list-group-item"><span class="glyphicon glyphicon glyphicon-ok"></span> Price Monitor</li>
							<li class="list-group-item"><span class="glyphicon glyphicon glyphicon-ok"></span> Auto-order(0.05$ per order)</li>
							<li class="list-group-item"><span class="glyphicon glyphicon glyphicon-ok"></span> Profitability Monitor</li>
							<li class="list-group-item"><span class="glyphicon glyphicon glyphicon-ok"></span> Auto Messanger</li>
							<li class="list-group-item"><span class="glyphicon glyphicon glyphicon-ok"></span> Help Centre</li>
							<li class="list-group-item"><span class="glyphicon glyphicon glyphicon-ok"></span> 24/7 Support</li>
						</ul>
						<div class="panel-footer">
							<a class="btn btn-lg btn-block btn-danger" href="#">BUY NOW!</a>
						</div>
					</div>
					<!-- /PRICE ITEM -->
					
					
				</div>
				
				<div class="col-xs-6 col-sm-6 col-md-3 col-lg-3">
				
					<!-- PRICE ITEM -->
					<div class="panel price panel-blue">
						<div class="panel-heading arrow_box text-center">
						<h3>I'm ninja!</h3>
						</div>
						<div class="panel-body text-center">
							<p class="lead" style="font-size:40px"><strong>99.99 / month</strong></p>
						</div>
						<ul class="list-group list-group-flush text-center">
							<li class="list-group-item"><span class="glyphicon glyphicon glyphicon-ok"></span> 15000 Lisitngs</li>
							<li class="list-group-item"><span class="glyphicon glyphicon glyphicon-ok"></span> Price Monitor</li>
							<li class="list-group-item"><span class="glyphicon glyphicon glyphicon-ok"></span> Auto-order(0.05$ per order)</li>
							<li class="list-group-item"><span class="glyphicon glyphicon glyphicon-ok"></span> Profitability Monitor</li>
							<li class="list-group-item"><span class="glyphicon glyphicon glyphicon-ok"></span> Auto Messanger</li>
							<li class="list-group-item"><span class="glyphicon glyphicon glyphicon-ok"></span> Help Centre</li>
							<li class="list-group-item"><span class="glyphicon glyphicon glyphicon-ok"></span> 24/7 Support</li>
						</ul>
						<div class="panel-footer">
							<a class="btn btn-lg btn-block btn-info" href="#">BUY NOW!</a>
						</div>
					</div>
					<!-- /PRICE ITEM -->
					
					
				</div>
				
				<div class="col-xs-6 col-sm-6 col-md-3 col-lg-3">
				
					<!-- PRICE ITEM -->
					<div class="panel price panel-green">
						<div class="panel-heading arrow_box text-center">
						<h3>Kung-fu Panda</h3>
						</div>
						<div class="panel-body text-center">
							<p class="lead" style="font-size:40px"><strong>$125.99 / month</strong></p>
						</div>
						<ul class="list-group list-group-flush text-center">
							<li class="list-group-item"><span class="glyphicon glyphicon glyphicon-ok"></span> 90000 Lisitngs</li>
							<li class="list-group-item"><span class="glyphicon glyphicon glyphicon-ok"></span> Price Monitor</li>
							<li class="list-group-item"><span class="glyphicon glyphicon glyphicon-ok"></span> Auto-order(0.05$ per order)</li>
							<li class="list-group-item"><span class="glyphicon glyphicon glyphicon-ok"></span> Profitability Monitor</li>
							<li class="list-group-item"><span class="glyphicon glyphicon glyphicon-ok"></span> Auto Messanger</li>
							<li class="list-group-item"><span class="glyphicon glyphicon glyphicon-ok"></span> Help Centre</li>
							<li class="list-group-item"><span class="glyphicon glyphicon glyphicon-ok"></span> 24/7 Support</li>
						</ul>
						<div class="panel-footer">
							<a class="btn btn-lg btn-block btn-success" href="#">BUY NOW!</a>
						</div>
					</div>
					<!-- /PRICE ITEM -->
				</div>
				<div class="col-xs-6 col-sm-6 col-md-3 col-lg-3">
				
					<!-- PRICE ITEM -->
					<div class="panel price panel-grey">
						<div class="panel-heading arrow_box text-center">
						<h3>Slunicko</h3>
						</div>
						<div class="panel-body text-center">
							<p class="lead" style="font-size:40px"><strong>$199.00 / month</strong></p>
						</div>
						<ul class="list-group list-group-flush text-center">
							<li class="list-group-item"><span class="glyphicon glyphicon glyphicon-ok"></span> Unlimited Lisitngs</li>
							<li class="list-group-item"><span class="glyphicon glyphicon glyphicon-ok"></span> Price Monitor</li>
							<li class="list-group-item"><span class="glyphicon glyphicon glyphicon-ok"></span> Auto-order(FREE)</li>
							<li class="list-group-item"><span class="glyphicon glyphicon glyphicon-ok"></span> Profitability Monitor</li>
							<li class="list-group-item"><span class="glyphicon glyphicon glyphicon-ok"></span> Auto Messanger</li>
							<li class="list-group-item"><span class="glyphicon glyphicon glyphicon-ok"></span> Help Centre</li>
							<li class="list-group-item"><span class="glyphicon glyphicon glyphicon-ok"></span> 24/7 Support</li>
						</ul>
						<div class="panel-footer">
							<a class="btn btn-lg btn-block btn-primary" href="#">BUY NOW!</a>
						</div>
					</div>
					<!-- /PRICE ITEM -->
					
					
				</div>
			</div>
		</div>
	<div class="cont">
		<h2>Suppliers</h2>
		<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
		tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
		quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
		consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
		cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
		proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
	</div>
</div>

<?php
//include header template
require('layout/footer.php');
?>
