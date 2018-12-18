<?php

	session_start();
	$titleName = 'Log In';
	if (isset($_SESSION['user']))
	{
		header('location: index.php');
		exit();
	}

	include 'init.php';

	if ($_SERVER['REQUEST_METHOD'] == 'POST')
	{
		if (isset($_POST['login']))
		{
			$user = $_POST['user'];
			$pass = $_POST['pass'];
			$hashed = sha1($pass);

			$stmt = $con->prepare('	SELECT
										UserName, Password
									FROM
										members
									WHERE
										UserName = ? AND Password = ?');

			$stmt->execute(array($user, $hashed));
			$count = $stmt->rowCount();

			if ($count > 0)
			{
				$_SESSION['user'] = $user;
				header('location: index.php');
				exit();	
			}
			else
			{
				echo 'Sorry you are not a member';
			}
		}
		else //Sign up
		{	
			////////////////
			// Validation //
			////////////////

			$formErrors = array();

			// Validation Name
			$filteredname = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
			if (empty($filteredname))
			{
				$formErrors[] = 'User Name Can not be Empty';
			}

			// Validation Pass
			$pass1 = $_POST['pass1'];
			$pass2 = $_POST['pass2'];
			if ($pass1 == $pass2)
			{
				if(!empty($_POST['pass1']))
				{
					$pass1 = sha1($pass1);
				}
				else
				{
					$formErrors[] = 'Password Can not be Empty';
				}
			}
			else
			{
				$formErrors[] = 'Your Two Passwords are not identicat';
			}

			// Validation mail (b3deen)


			if (!empty($formErrors))
				{
					foreach ($formErrors as $error)
					{
						echo $error . '<br>';
					}
				}
				else
				{
					$email = $_POST['mail'];
					$accName = $_POST['accountname'];
					$formDuplicate = array();
					if (isExist("UserName", "members", $filteredname, 'UserID', 0)) // "SELECT "UserName" FROM "members" WHERE "UserName" = ? AND ? != ?"
					{
						$formDuplicate[] = "This User Name Exists";
					}
					if (isExist("Email", "members", $email, 'UserID', 0))
					{
						$formDuplicate[] = "This E-mail Exists";
					}
					if (isExist("AccountName", "members", $accName, 'UserID', 0))
					{
						$formDuplicate[] = "This Account Name Exists";
					}

					if (!empty($formDuplicate))
					{
						foreach ($formDuplicate as $duplicate) 
						{
	   						echo "<div class='alert alert-danger'>" . $duplicate . "</div>";
						}
					}
					else
					{
						$stmt = $con->prepare("INSERT INTO members(UserName, Password, Email, AccountName, Date) VALUES (?, ?, ?, ?, now())");
						$stmt->execute(array($filteredname, $pass1, $email, $accName));
						$_SESSION['success'] = "<div class='aler alert-success'>You Have Signed Up Successfully<div>";
						header('location: login.php');
						exit();
						
					}
				}
		}
	}
	
	?>
	<div class="container">
		<h1 class="text-center">
			<div class="login-signup">
				<span class="loginspan active">Log In </span>
				<span class="signupspan"> Sign Up</span>
			</div>
		</h1>
		<div class="logindiv text-center col-sm-6 col-sm-offset-3">
			<?php 
			if (isset($_SESSION['success']))
			{
				echo $_SESSION['success'];
			}
			?>
			<form class="login" method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>">
				<h2 class="text-center">Log in</h2>
				<input class="form-control input-lg" type="text" name="user" placeholder="User Name" autocomplete="off">
				<input class="form-control input-lg" type="password" name="pass" placeholder="Password" autocomplete="new-password">
				<input class="btn btn-primary btn-block btn-lg" type="submit" name="login" value="Log In">			
			</form>
		</div>

		<div class="signupdiv text-center col-sm-6 col-sm-offset-3">
			<form class="signupform" method="post" action="">
				<h2 class="text-center">Sign Up</h2>
				<input class="form-control input-lg" type="text" name="username" placeholder="User Name" autocomplete="off">
				<input class="form-control input-lg" type="text" name="accountname" placeholder="Acount Name" autocomplete="off">
				<input class="form-control input-lg" type="Email" name="mail" placeholder="Email" autocomplete="off">
				<input class="form-control input-lg" type="password" name="pass1" placeholder="Password" autocomplete="off">
				<input class="form-control input-lg" type="password" name="pass2" placeholder="Re-Password" autocomplete="new-password">
				<input class="btn btn-success btn-block btn-lg" type="submit" name="signup" value="Log In">			
			</form>
		</div>
	</div>


<?php include $tpl . 'footer.inc.php'; // => includes/templates/footer.php ?>