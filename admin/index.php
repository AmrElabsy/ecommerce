<?php
	session_start();
	$titleName = 'Log In';
	if (isset($_SESSION['UserName']))
	{
		header('location: dashboard.php');
		exit();
	}

	$noNav = '';

	include 'init.php';

	if ($_SERVER['REQUEST_METHOD'] == 'POST')
	{
		$username = $_POST['username'];
		$pass = $_POST['pass'];
		$hashed = sha1($pass);

		$stmt = $con->prepare('	SELECT
									UserID, UserName, Password
								FROM
									members
								WHERE
									UserName = ? AND Password = ? AND AccessPermission = 1 /* Access Permission = 1 to be an Admin */
								LIMIT 1');
		// $con is a object from the class called PDO
		// $stmt is a method from the class PDO specified for the object $con
		// So I will make a preparation for a statement with PARAMETERS called (?)
		
		$stmt->execute(array($username, $hashed));
		// execute is a method from the class PDO
		// array is the Agrument
		// this method contains the ARGUMENTS for 'prepare' method

		$result = $stmt->fetch();

		$count = $stmt->rowCount();

		if ($count > 0)
		{
			
			$_SESSION['UserName'] = $username;
			$_SESSION['ID'] = $result['UserID'];
			header('location: dashboard.php');
			
		}
		else
		{
			echo 'Sorry you are not a member';
		}

	}

	?> 
		<form class="login" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
			<h3 class="text-center">Log in</h3>
			<input class="form-control input-lg" type="text" name="username" placeholder="User Name" autocomplete="off">
			<input class="form-control input-lg" type="password" name="pass" placeholder="Password" autocomplete="new-password">
			<input class="btn btn-primary btn-block btn-lg" type="submit" name="login" value="Log In">			
		</form>

<?php include $tpl . 'footer.inc.php'; // => includes/templates/footer.php ?>