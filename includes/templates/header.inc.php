<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title><?php getTitle(); ?></title>

		<link rel="stylesheet" type="text/css" href="<?php echo $css; ?>bootstrap.min.css">
		<link rel="stylesheet" type="text/css" href="<?php echo $css; ?>font-awesome.min.css">
		<link rel="stylesheet" type="text/css" href="<?php echo $css; ?>adminstyle.css">
		
	</head>
	<body>
		<div class="upper-nav">

			<?php
			if (isset($_SESSION['user']))
			{
				?>
					<div class="container">
						
						<div class="pull-right">
							<a href="additem.php" class="btn btn-success">Add New Item</a>
							<a href="profile.php" class="btn btn-primary">My Profile</a>
							<a href="logout.php" class="btn btn-danger">Log Out</a> 
						</div>
					</div>
				<?php
			}
			else
			{
				?>
				<nav class="container">
					<div class="pull-right">
						<a href="login.php" class="btn btn-primary">Log In | Sign Up</a>
					</div>
				</nav>
		<?php } ?>
		</div>
		<nav class="navbar navbar-inverse">
			<div class="container-fluid">
				<!-- Brand and toggle get grouped for better mobile display -->
				<div class="navbar-header">
					<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navBar" aria-expanded="false">
						<span class="sr-only">Toggle navigation</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
					<a class="navbar-brand" href="index.php"><?php echo lang('eCommerce'); ?></a>
				</div>
				<!-- Collect the nav links, forms, and other content for toggling -->
				<div class="collapse navbar-collapse" id="navBar">
					<ul class="nav navbar-nav navbar-right">
						<?php
							$catgs = getCatgs();

							foreach ($catgs as $catg)
							{
								echo "	<li>
											<a href='categories.php?id=" . $catg['catgID'] . "&catgname=" . str_replace(" ", "-", $catg['catgName']) . "'>
											" . $catg['catgName'] . "
											</a>
										</li>";
							}
						?>
					</ul>
				</div>
			</div>
		</nav>