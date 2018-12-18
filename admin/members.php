<?php
	session_start();
	$titleName = 'Members';
	$isActive = 'members';

	include 'init.php';

	if (isset($_SESSION['UserName']))
	{
		if (isset($_GET['do']))
		{
			$do = $_GET['do'];
		}
		else
		{
			$do = 'main';
		}

		switch ($do)
		{
			// =========================
			// == Start The Main Page ==
			// =========================
			case 'main':

				$query = '';

				if (isset($_GET['pending']) && $_GET['pending'] == 'pendingmembers')
				{
					$query = " AND RegAccess = 0";
				}

				$stmt = $con->prepare("SELECT * FROM members WHERE AccessPermission != 1 $query");
				$stmt->execute();

				$rows = $stmt->fetchALL();
				?>
				<h1 class="text-center">Manage Members Page</h1>
				<div class="container">
					<div class="table-responsive text-center members-manage">
						<table class="main-table table table-hover table-bordered">
							<tr>
								<td>ID</td>
								<td>Image</td>
								<td>User Name</td>
								<td>E-mail</td>
								<td>Account Name</td>
								<td>Date</td>
								<td>Manage</td>
							</tr>
							<?php 
							foreach ($rows as $row)
							{
								echo "<tr>";
									echo "<td>" . $row['UserID'] . "</td>";
									echo "<td class='img-data'>";
										if (empty($row['image']))
										{
											echo '<div class="img-div">';
												echo '<img src="300.png">' ;
											echo '</div>';
										}
										else
										{
											echo '<div class="img-div text-center">';
												echo '<img src="../data/uploads/profile/' . $row['image'] . '">' ;
											echo '</div>';
										}
									echo "</td>";
									echo "<td><a href='?do=profile&userid=" .$row['UserID']."'>" . $row['UserName'] 	. "</a></td>";
									echo "<td>" . $row['Email'] 		. "</td>";
									echo "<td>" . $row['AccountName'] 	. "</td>";
									echo "<td>" . $row['Date']			. "</td>";
									echo "<td>
											<a href='members.php?do=edit&id=" . $row["UserID"] . "' class='btn btn-success'><i class='fa fa-edit'></i> Edit</a>
											<a href='members.php?do=suredelete&id=" . $row["UserID"] . "' class='btn btn-danger'><i class='fa fa-minus'></i> Delete</a>";

											if ($row['RegAccess'] == 0)
											{
												echo "<a href='members.php?do=activate&id=" . $row["UserID"] . "' class='btn btn-info activate-btn'><i class='fa fa-check'></i> Activate</a>";
											}
											else
											{
												echo "<a href='members.php?do=deactivate&id=" . $row["UserID"] . "' class='btn btn-warning activate-btn'><i class='fa fa-ban'></i> Deactivate</a>";	
											}
									echo "</td>";
							}
							?>			
						</table>
					</div>
						<a href="?do=add" class="btn btn-primary"><i class="fa fa-plus"></i> Add New Member</a>
				</div>
				<?php
			break;
			// ==========================
			// == Ending The Main Page ==
			// ==========================


			// =========================
			// == Start The Edit Page ==
			// =========================
			case 'edit': 
				if (isset($_GET['id']) && is_numeric($_GET['id']))
				{
					$stmt = $con->prepare('	SELECT * FROM members WHERE UserID = ? LIMIT 1');
					$userID = $_GET['id'];

					$stmt->execute(array($userID));
					$result = $stmt->fetch();
					$count = $stmt->rowCount();

					if ($count > 0)
					{
						?>
						<h1 class="text-center">Edit Member</h1>
						<div class="container">
							<form class="form-horizontal update" action="?do=update" method="POST" enctype="multipart/form-data">
								<input type="hidden" name="userid" value="<?php echo $result['UserID']; ?>">
								<!-- Start User Name -->
								<div class="form-group form-group-lg">
									<label class="col-sm-2 col-md-4 control-label">User Name</label>
									<div class="col-sm-9 col-md-5">
										<input type="text" name="username" class="form-control" value="<?php echo $result['UserName']; ?>" autocomplete="off" required>
									</div>						
								</div>
								<!-- Ending User Name -->

								<!-- Start Password -->
								<div class="form-group form-group-lg">
									<label class="col-sm-2 col-md-4 control-label">Password</label>
									<div class="col-sm-9 col-md-5">
										<input type="hidden" 	name="oldpass" class="form-control" autocomplete="new-password" value="<?php echo $result['Password']; ?>">
										<input type="password" 	name="newpass" class="form-control" autocomplete="new-password">
									</div>						
								</div>
								<!-- Ending Password -->

								<!-- Start E-mail -->
								<div class="form-group form-group-lg">
									<label class="col-sm-2 col-md-4 control-label">E-mail</label>
									<div class="col-sm-9 col-md-5">
										<input type="email" name="email" class="form-control" value="<?php echo $result['Email']; ?>" autocomplete="off" required>
									</div>						
								</div>
								<!-- Ending E-mail -->

								<!-- Start Full name -->
								<div class="form-group form-group-lg">
									<label class="col-sm-2 col-md-4 control-label">Full Name</label>
									<div class="col-sm-9 col-md-5">
										<input type="text" name="full" class="form-control" value="<?php echo $result ['AccountName']; ?>" autocomplete="off" required>
									</div>						
								</div>
								<!-- Ending Full Name -->

								<!-- Start Image -->
								<div class="form-group form-group-lg">
									<label class="col-sm-2 col-md-4 control-label">Profile Image</label>
									<div class="col-sm-9 col-md-5">
										<input type="file" name="image" class="form-control">										
									</div>						
								</div>
								<!-- Ending Image -->

								<!-- Start Submit Button -->
								<div class="form-group form-group-lg">
									<div class="col-sm-offset-2 col-md-5 col-md-offset-4">
										<input type="submit" value="Edit" name="update" class="btn btn-primary btn-lg col-sm-5">
										<a href='members.php?do=delete&id=<?php echo $result["UserID"];?>' class='btn btn-danger btn-lg col-sm-offset-1 col-sm-5'>Delete</a>
									</div>						
								</div>
								<!-- Ending Submit Button -->
							</form>
						</div>
						<?php
					}
					else
					{
						echo "There is no Such ID";
					}
				}
				else
				{
					echo "There is something wrong with ID";
				}
			break;
			// ==========================
			// == Ending The Edit Page ==
			// ==========================


			// ===========================
			// == Start The Update Page ==
			// ===========================
			case 'update':
				echo "<div class='container'>";
				if ($_SERVER['REQUEST_METHOD'] == 'POST')
				{


					$id 		= $_POST['userid'];
					$username 	= $_POST['username'];
					$email 		= $_POST['email'];
					$fullname 	= $_POST['full'];
					$UserID 	= "UserID";
					$image 		= $_FILES['image'];

				

					$imgName	= rand(1000000, 9999999) . $image['name'];
					$imgTmp 	= $image['tmp_name'];

					$allowedExtentions = array('jpeg', 'jpg', 'png' , 'gif');
					$imgType = strtolower(end(explode('.', $imgName)));

					$pass = '';
					$hashed = '';
					// Password
					if (empty($_POST['newpass']))
					{
						$pass = $_POST['oldpass'];
						$hashed = $pass;
					}
					else
					{
						$pass = $_POST['newpass'];
						$hashed = sha1($pass);
					}

					// Validation 

					$formErrors = array();

					if (empty($username))
					{
						$formErrors[] = 'User Name Can\'t be Empty';
					}

					if (empty($email))
					{
						$formErrors[] = 'E-mail Can\'t be Empty';
					}

					if (empty($fullname))
					{
						$formErrors[] = 'Full Name Can\'t be Empty';
					}

					if(!empty($_FILES['image']['name']))
					{
						if (in_array($imgType, $allowedExtentions))
						{
							move_uploaded_file($imgTmp, "..\data\uploads\profile\\" . $imgName);
						}
						else
						{
							$formErrors[] = 'This Extention is not allowed';	
						}
					}

					if (!empty($formErrors))
					{
						foreach ($formErrors as $error) 
						{
							echo "<div class='alert alert-danger'>" . $error . "</div>";
							reDirect(".");
						}										
					}
					else
					{
						$formDuplicate = array();
						if (isExist("UserName", "members", $username, $UserID, $id))
						{
							$formDuplicate[] = "This User Name Exists";
						}
						if (isExist("Email", "members", $email, $UserID, $id))
						{
							$formDuplicate[] = "This E-mail Exists";
						}
						if (isExist("AccountName", "members", $fullname, $UserID, $id))
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
							if (empty($_FILES['image']['name']))
							{
								$stmt = $con->prepare("UPDATE members SET UserName = ?, Password = ?, Email = ?, AccountName = ? WHERE UserID = ?");
								$stmt->execute(array($username, $hashed, $email, $fullname, $id));
							}
							else
							{
								$stmt = $con->prepare("UPDATE members SET UserName = ?, Password = ?, Email = ?, AccountName = ?, image = ? WHERE UserID = ?");
								$stmt->execute(array($username, $hashed, $email, $fullname, $imgName, $id));
							}
						}
					}
					header("location: members.php");
				}	
				else
				{
					redirect("You can not be here", "members.php");
				}
				echo "</div>";
			break;
			// ============================
			// == Ending The Update Page ==
			// ============================


			// ========================
			// == Start The Add Page ==
			// ========================
			case "add": 
				?>
				<h1 class="text-center">Add Member</h1>
				<div class="container">
					<form class="form-horizontal update" action="?do=insert" method="POST" enctype="multipart/form-data">
						<!-- Start User Name -->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 col-md-4 control-label">User Name</label>
							<div class="col-sm-9 col-md-5">
								<input type="text" name="username" class="form-control" autocomplete="off" required>
							</div>						
						</div>
						<!-- Ending User Name -->

						<!-- Start Password -->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 col-md-4 control-label">Password</label>
							<div class="col-sm-9 col-md-5">
								<input type="password" 	name="pass" class="form-control" autocomplete="new-password" required="">
							</div>						
						</div>
						<!-- Ending Password -->

						<!-- Start E-mail -->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 col-md-4 control-label">E-mail</label>
							<div class="col-sm-9 col-md-5">
								<input type="email" name="email" class="form-control" autocomplete="off" required>
							</div>						
						</div>
						<!-- Ending E-mail -->

						<!-- Start Full name -->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 col-md-4 control-label">Full Name</label>
							<div class="col-sm-9 col-md-5">
								<input type="text" name="full" class="form-control" autocomplete="off" required>
							</div>						
						</div>
						<!-- Ending Full Name -->

						<!-- Start Image -->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 col-md-4 control-label">Profile Image</label>
							<div class="col-sm-9 col-md-5">
								<input type="file" name="image" class="form-control" required>
							</div>						
						</div>
						<!-- Ending Image -->

						<!-- Start Submit Button -->
						<div class="form-group form-group-lg">
							<div class="col-sm-offset-2 col-md-5 col-md-offset-4 col-sm-10">
								<input type="submit" value="Add" name="update" class="btn btn-primary btn-block btn-lg">
							</div>						
						</div>
						<!-- Ending Submit Button -->
					</form>
				</div>
				<?php
			break;
			// =========================
			// == Ending The Add Page ==
			// =========================


			// ===========================
			// == Start The Insert Page ==
			// ===========================
			case 'insert':
				echo "<div class='container'>";
				if ($_SERVER['REQUEST_METHOD'] == 'POST')
				{
					$username 	= $_POST['username'];
					$pass 		= $_POST['pass'];
					$email 		= $_POST['email'];
					$fullname 	= $_POST['full'];
					$UserID 	= "UserID";
					$image 		= $_FILES['image'];

					$imgName	= rand(1000000, 9999999) . $image['name'];
					$imgTmp 	= $image['tmp_name'];

					$allowedExtentions = array('jpeg', 'jpg', 'png' , 'gif');
					$imgType = strtolower(end(explode('.', $imgName)));

					$hashed 	= sha1($pass);

					// Validation 

					$formErrors = array();

					if (empty($username))
					{
						$formErrors[] = 'User Name Can\'t be Empty';
					}
					if (empty($pass))
					{
						$formErrors[] = 'Password Can\'t be Empty';
					}
					if (empty($email))
					{
						$formErrors[] = 'E-mail Can\'t be Empty';
					}
					if (empty($fullname))
					{
						$formErrors[] = 'Full Name Can\'t be Empty';
					}

					if(!empty($_FILES['image']['name']))
					{
						if (in_array($imgType, $allowedExtentions))
						{
							move_uploaded_file($imgTmp, "..\data\uploads\profile\\" . $imgName);
						}
						else
						{
							$formErrors[] = 'This Extention is not allowed';	
						}
					}

					if (!empty($formErrors))
					{
						foreach ($formErrors as $error) 
						{
							echo "<div class='alert alert-danger'>" . $error . "</div>";
						}						
					}
					else
					{
						$formDuplicate = array();
						if (isExist("UserName", "members", $username, $UserID, 0))
						{
							$formDuplicate[] = "This User Name Exists";
						}
						if (isExist("Email", "members", $email, $UserID, 0))
						{
							$formDuplicate[] = "This E-mail Exists";
						}
						if (isExist("AccountName", "members", $fullname, $UserID, 0))
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
							
							$stmt = $con->prepare("	INSERT INTO
														members(UserName, Password, Email, AccountName, RegAccess, Date, image)
													VALUES
														(?, ?, ?, ?, ?, now(), ?)");
							$stmt->execute(array($username, $hashed, $email, $fullname, "1", $imgName));

							echo "<div class='alert alert-success'>Member was Added</div>";
							header("refresh: 2;url=members.php");
						}
					}
				}	
				else
				{
					echo "You Can't be here";
				}
				echo "</div>";
			break;
			// ============================
			// == Ending The Insert Page ==
			// ============================	

			// ================================
			// == Start The Sure Delete Page ==
			// ================================
			case 'suredelete';

			$userID = $_GET['id'];
			$stmt = $con->prepare("SELECT * FROM members WHERE UserID = ?");
			$stmt->execute(array($userID));
			$user = $stmt->fetch();
?>
			<h1 class="text-center"><?php echo $user['UserName'] . " Profile"; ?></h1>
				<div class="container text-center user-profile">
					<div class="user-profile-pic col-sm-3">
						<div class="text-center">
							<?php
							if (!empty($user['image']))
							{
							echo "<img src='../data/uploads/profile/" . $user['image'] . "'><br>"; 
							}
							else
							{
								echo "<img class='img-circle' src='300.png'><br>"; 
							}
							?>
						</div>
					</div>
					<div class="user-profile-info text-left col-sm-9">
						<strong>User Name: </strong>	<?php echo $user['UserName']; ?><br>
						<strong>E-Mail: </strong>		<?php echo $user['Email']; ?><br>
						<strong>Account Name: </strong>	<?php echo $user['AccountName']; ?><br>
					</div>
					<div class="item-btns text-center col-sm-12">
						<a href="members.php?do=edit&id=<?php echo $userID; ?>" class="btn btn-success"><i class='fa fa-edit'></i> No, edit</a>
						<a href="members.php?do=delete&id=<?php echo $userID ?>" class="btn btn-danger"><i class='fa fa-minus'></i> Yes, delete him.</a>
					</div>
				</div>
<?php
			break;
			// ==============================
			// == End The Sure Delete Page ==
			// ==============================

			// ===========================
			// == Start The Delete Page ==
			// ===========================		

			case 'delete':
			echo "<div class='container'>";

			$id 		= $_GET['id'];
						
			$stmt = $con->prepare("DELETE FROM members WHERE UserID = ?");
			$stmt->execute(array($id));
			
			header("location: members.php");
			
			echo "</div>";
			break;
			// ============================
			// == Ending The Delete Page ==
			// ============================


			// =============================
			// == Start The Activate Page ==
			// =============================		

			case 'activate':
			echo "<div class='container'>";

			$id 		= $_GET['id'];
						
			$stmt = $con->prepare("UPDATE members SET RegAccess = 1 WHERE UserID = ?");
			$stmt->execute(array($id));
			
			header('Location: ' . $_SERVER['HTTP_REFERER']);
			
			echo "</div>";
			break;
			// ==============================
			// == Ending The Activate Page ==
			// ==============================

			// ===============================
			// == Start The Deactivate Page ==
			// ===============================		

			case 'deactivate':
			echo "<div class='container'>";

			$id 		= $_GET['id'];
						
			$stmt = $con->prepare("UPDATE members SET RegAccess = 0 WHERE UserID = ?");
			$stmt->execute(array($id));
			
			header('Location: ' . $_SERVER['HTTP_REFERER']);
			
			echo "</div>";
			break;
			// ================================
			// == Ending The Deactivate Page ==
			// ================================

			// ============================
			// == Start The Profile Page ==
			// ============================		

			case 'profile':
			$userID = $_GET['userid'];
			$stmt = $con->prepare("SELECT * FROM members WHERE UserID = ?");
			$stmt->execute(array($userID));

			$user = $stmt->fetch();

			$stmt = $con->prepare("	SELECT
											items.*, members.UserName, categories.catgName
										FROM
											items
										INNER JOIN
											categories 	ON items.Catg_ID = categories.catgID
										INNER JOIN
											members  	On items.User_ID = members.UserID
										WHERE
											User_ID = ?
										ORDER BY item_ID ASC");
			$stmt->execute(array($userID));
			$items = $stmt->fetchAll();

			$stmt = $con->prepare("	SELECT
										comments.*, items.itemName, members.UserName, members.image
									FROM
										comments
									INNER JOIN
										items 	ON comments.item_ID = items.item_ID
									INNER JOIN
										members ON comments.user_ID = members.UserID
									WHERE
										comments.user_ID = ?");
			$stmt->execute(array($userID));
			$comments = $stmt->fetchALL();
			?>
				<h1 class="text-center"><?php echo $user['UserName'] . " Profile"; ?></h1>
				<div class="container text-center user-profile">
					<div class="user-profile-pic col-sm-3">
						<?php
							if (empty($user['image']))
							{
								echo "<strong>No Image</strong>";
							}
							else
							{
							echo "<img src='../data/uploads/profile/" . $user['image'] . "'>"; 
							}
							?>
					</div>
					<div class="user-profile-info text-left col-sm-9">
						<strong>User Name: </strong>	<?php echo $user['UserName']; ?><br>
						<strong>E-Mail: </strong>		<?php echo $user['Email']; ?><br>
						<strong>Account Name: </strong>	<?php echo $user['AccountName']; ?><br>
					</div>
				</div>

				<h1 class="text-center"><?php echo $user['UserName'] . " Items"; ?></h1>
				<?php
					if(!empty($items))
					{
						echo '<div class="container user-profile text-center">';

						foreach ($items as $item)
						{
							$desc = $item['itemDesc'];

							if (strlen($desc) > 100)
							{
								$desc = substr($desc, 0, 99);
								$desc .= '...';
							}	
						?>
							<div class="col-md-4">
								<div class="item-pand">
									<div class="item-head">
										<?php echo $item['item_ID']; ?>: <?php echo $item['itemName']; ?>		
									</div>
									<div class="item-pic fixed-height2">
										<?php
										if (empty($item['image']))
										{
											echo '<div class="img-div">';
												echo '<img src="300.png">' ;
											echo '</div>';
										}
										else
										{
											echo '<div class="img-div text-center">';
												echo '<img class ="img-thumbnail" src="../data/uploads/item/' . $item['image'] . '">' ;
											echo '</div>';
										}
										?>
										<div class="item-price">
											<?php echo $item['Price']; ?>
										</div>
									</div>
									<div class="item-dtls fixed-height">
										<strong>Description: </strong> <?php echo $desc; ?> <br>
										<strong>Status: </strong> <?php echo $item['status']; ?> <br>
										<strong>Country: </strong> <?php echo $item['countryMade']; ?> <br>
										<strong>Category: </strong> <?php echo $item['catgName']; ?> <br>
									</div>
									<div class="item-btns">
										<a href="items.php?do=edit&id=<?php echo $item['item_ID']; ?>" class="btn btn-success"><i class='fa fa-edit'></i> Edit</a>
										<a href="items.php?do=suredelete&id=<?php echo $item['item_ID']; ?>" class="btn btn-danger"><i class='fa fa-minus'></i> Delete</a>
										<button type="button" class="btn btn-info" data-toggle="modal" data-target="#m-<?php echo $item['item_ID'] ?>">Read More</button>
										<a style="margin-top: 5px;" href="items.php?do=itempage&itemid=<?php echo $item['item_ID']; ?>" class="btn btn-info">View Page</a>
									</div>
								</div>
							</div>

							<div id="m-<?php echo $item['item_ID'] ?>" class="modal fade" role="dialog">
								<div class="modal-dialog modal-lg">

								<!-- Modal content -->
									<div class="modal-content">
										<div class="item-head item-head-modal">
				 							<button type="button" class="close" data-dismiss="modal">&times;</button>
											<h4 class="modal-title"><?php echo $item['item_ID']; ?>: <?php echo $item['itemName'] ?></h4>
										</div>
										<div class="modal-body">
											<div class="item-pic-modal">
												<img src="300.png">
												<div class="item-price">
													<?php echo $item['Price']; ?>
												</div>
											</div>
											<div class="item-dtls">
												<strong>Description: </strong> <?php echo $item['itemDesc'] ?> <br>
												<strong>Status: </strong> <?php echo $item['status']; ?> <br>
												<strong>Country: </strong> <?php echo $item['countryMade']; ?> <br>
												<strong>Member: </strong> <?php echo $item['UserName']; ?> <br>
												<strong>Category: </strong> <?php echo $item['catgName']; ?> <br>
											</div>	
										</div>
										<div class="modal-footer">
											<a href="items.php?do=itempage&itemid=<?php echo $item['item_ID']; ?>" class="btn btn-info">View Page</a>
											<button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
										</div>
									</div>
								</div>
							</div>
<?php 					}
					echo '</div>';
					}
				?>
				
				
				<h1 class="text-center"><?php echo $user['UserName'] . " Comments"; ?></h1>
				<?php if(!empty($comments))
				{ 
				echo '<div class="container text-center user-profile">';
					foreach ($comments as $comment)
					{?>
					
						<div class="user-profile-comment-box text-left">
							<span class="user-profile-comment-user text-center">
								<div class="text-center">
									<?php
									if (!empty($user['image']))
									{
									echo "<img class='img-circle' src='../data/uploads/profile/" . $user['image'] . "'><br>"; 
									}
									else
									{
										echo "<img class='img-circle' src='300.png'><br>"; 
									}
									?>
								</div>
								<strong><?php echo $user['UserName']; ?></strong><br>
								<?php echo $comment['date']; ?><br>
								<strong>Item: </strong><a href="items.php?do=itempage&itemid=<?php echo $comment['item_ID']; ?>"><?php echo $comment['itemName']; ?></a><br>
							</span>
							<div class="user-profile-comment">
								<?php echo $comment['comment']; ?><br><br>
							</div>
						</div>
			<?php }
				echo '</div>';
				}
				
			break;
			// ================================
			// == Ending The Profile Page ==
			// ================================
			
			default:
				echo 'Sorry There is a Problem';
				break;
		}
	
	}
	else
	{
		header('location: index.php');
	}
?>
	<?php include $tpl . 'footer.inc.php'; // => includes/templates/footer.php ?>