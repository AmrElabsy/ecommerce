<?php
	session_start();
	$titleName = 'Profile';

	include 'init.php';

	if (isset($_SESSION['user']))
	{
		$stmt = $con->prepare("SELECT * FROM members WHERE UserName = ?");
		$stmt->execute(array($ssnuser));
		$userdtls = $stmt->fetch();

		$stmt = $con->prepare("	SELECT
										items.*, members.UserName, categories.catgName
									FROM
										items
									INNER JOIN
										categories 	ON items.Catg_ID = categories.catgID
									INNER JOIN
										members  	On items.User_ID = members.UserID
									WHERE
										UserName = ?
									ORDER BY item_ID ASC");
		$stmt->execute(array($ssnuser));
		$items = $stmt->fetchAll();

		$stmt = $con->prepare("	SELECT
									comments.*, items.itemName, members.UserName
								FROM
									comments
								INNER JOIN
									items 	ON comments.item_ID = items.item_ID
								INNER JOIN
									members ON comments.user_ID = members.UserID
								WHERE
									UserName = ?");
		$stmt->execute(array($ssnuser));
		$comments = $stmt->fetchALL();


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

			// =====================
			// == Start Main page ==
			// =====================
			case 'main':
				?>
					<div class="profile-page">
						<div class="container">
							<h1 class="text-center">My Profile</h1>
							<div class="panel panel-primary">
								<div class="panel-heading">
									Information
									<div class="hidden-btns">
										<a href="?do=editmember" class="btn btn-success btn-sm"><i class='fa fa-edit'></i> Edit</a>
									</div>
								</div>
								<div class="panel-body">
									<div class="row">
										<div class="col-sm-4 text-center">
											<?php
											if (empty($user['image']))
											{
												echo "<strong>No Image</strong>";
											}
											else
											{
											echo "<img class='img-responsive' src='data/uploads/profile/" . $user['image'] . "'>"; 
											}
											?>
										</div>
										<div class="col-sm-8">
											User Name: <?php echo $userdtls['UserName']; ?><br>
											Account Name: <?php echo $userdtls['AccountName']; ?><br>
											E-mail: <?php echo $userdtls['Email']; ?><br>
											Registered Date: <?php echo $userdtls['Date']; ?><br>
										</div>
									</div>
								</div>						
							</div>

							<div class="panel panel-primary">
								<div class="panel-heading">
									Items
								</div>
								<div class="panel-body">
									<?php
										if(!empty($items))
										{
											echo '<div class="text-center">';

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
														echo "<img src='300.png'>";
													}
													else
													{
													echo "<img class='img-circle' src='data/uploads/item/" . $item['image'] . "'>"; 
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
													<a href="profile.php?do=edit&id=<?php echo $item['item_ID']; ?>" class="btn btn-success"><i class='fa fa-edit'></i> Edit</a>
													<a href="profile.php?do=suredelete&id=<?php echo $item['item_ID']; ?>" class="btn btn-danger"><i class='fa fa-minus'></i> Delete</a>
													<button type="button" class="btn btn-info" data-toggle="modal" data-target="#m-<?php echo $item['item_ID'] ?>">Read More</button>
													<a style="margin-top: 5px;" href="itempage.php?itemid=<?php echo $item['item_ID']; ?>" class="btn btn-info">View Page</a>
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
															<?php
															if (empty($item['image']))
															{
																echo "<img src='300.png'>";
															}
															else
															{
															echo "<img class='img-circle' src='data/uploads/item/" . $item['image'] . "'>"; 
															}
															?>
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
														<a href="itempage.php?itemid=<?php echo $item['item_ID']; ?>" class="btn btn-info">View Page</a>
														<button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
													</div>
												</div>
											</div>
										</div>
									<?php 					
										}
										echo "</div>";
									}		
								?>
							</div>
						</div>

						<div class="panel panel-primary">
							<div class="panel-heading">
								Comments
							</div>
							<div class="panel-body m-l-zero">
								<?php
									if(!empty($comments))
									{ 
										echo '<div class="text-center">';
											foreach ($comments as $comment)
											{
												?>
													<div class="user-profile-comment-box text-left">
														<span class="user-profile-comment-user text-center">
															<div class="text-center">
																<?php
																if (empty($user['image']))
																{
																	echo "<strong>No Image</strong>";
																}
																else
																{
																echo "<img class='img-circle' src='data/uploads/profile/" . $user['image'] . "'>"; 
																}
																?>
															</div>
															<strong><?php echo $comment['UserName']; ?></strong><br>
															<?php echo $comment['date']; ?><br>
															<strong>Item: </strong><a href="items.php?do=itempage&itemid=<?php echo $comment['item_ID']; ?>"><?php echo $comment['itemName']; ?></a><br>
														</span>
														<div class="user-profile-comment">
															<?php echo $comment['comment']; ?><br><br>
														</div>
													</div>
						<?php 				}
										echo '</div>';
									}
								?>
							</div>
						</div>
					</div>
				</div>
			<?php
			break;
			// ===================
			// == End Main Page ==
			// ===================

			// =========================
			// == Start The Edit Page ==
			// =========================
			case 'edit': 

			if (isset($_GET['id']) && is_numeric($_GET['id']))
			{

				$stmt = $con->prepare('	SELECT 
											items.*,members.UserID as userid
										FROM 
											items
										INNER JOIN
											members
										ON
											items.User_ID = members.UserID 
										WHERE 
											item_ID = ? LIMIT 1');
				$itemID = $_GET['id'];

				$stmt->execute(array($itemID));
				$result = $stmt->fetch();
				$count = $stmt->rowCount();

				if ($count > 0)
				{
					if ($ssnid == $result['userid'])
					{
						?>
						<h1 class="text-center">Edit Item</h1>
						<div class="container">
							<form class="form-horizontal update" action="?do=update" method="POST">
								<input type="hidden" name="itemid" value="<?php echo $result['item_ID']; ?>">
								<!-- Start Item Name -->
								<div class="form-group form-group-lg">
									<label class="col-sm-2 col-md-4 control-label">Item Name</label>
									<div class="col-sm-9 col-md-5">
										<input type="text" name="itemname" class="form-control" value="<?php echo $result['itemName']; ?>" autocomplete="off" required>
									</div>						
								</div>
								<!-- Ending Item Name -->

								<!-- Start Description -->
								<div class="form-group form-group-lg">
									<label class="col-sm-2 col-md-4 control-label">Description</label>
									<div class="col-sm-9 col-md-5">
										<textarea name="description" class="form-control" style="resize: vertical;"><?php echo $result['itemDesc'] ?></textarea>
									</div>						
								</div>
								<!-- Ending Description -->

								<!-- Start Price -->
								<div class="form-group form-group-lg">
									<label class="col-sm-2 col-md-4 control-label">Price</label>
									<div class="col-sm-9 col-md-5">
										<input type="text" name="price" class="form-control" value="<?php echo $result['Price']; ?>">
									</div>						
								</div>
								<!-- Ending Price -->

								<!-- Start Country -->
								<div class="form-group form-group-lg">
									<label class="col-sm-2 col-md-4 control-label">Country</label>
									<div class="col-sm-9 col-md-5">
										<input type="text" name="country" class="form-control" value="<?php echo $result['countryMade']; ?>">
									</div>						
								</div>
								<!-- Ending Country -->

								<!-- Start Status -->
								<div class="form-group form-group-lg">
									<label class="col-sm-2 col-md-4 control-label">Status</label>
									<div class="col-sm-9 col-md-5">
										<select class="form-control" name="status">
											<option value="0" 		<?php selectIt($result['status'], "0"); ?>>			<strong>...</strong></option>
											<option value="new" 	<?php selectIt($result['status'], "new"); ?>>		New</option>
											<option value="likenew" <?php selectIt($result['status'], "likenew"); ?>>	Like New</option>
											<option value="used" 	<?php selectIt($result['status'], "used"); ?>>		Used</option>
										</select>
									</div>						
								</div>
								<!-- Ending Status -->

								<!-- Start Category -->
								<div class="form-group form-group-lg">
									<label class="col-sm-2 col-md-4 control-label">Category</label>
									<div class="col-sm-9 col-md-5">
										<select class="form-control" name="category">
											<option value="0"><strong>...</strong></option>
											<?php
												$stmt = $con->prepare('SELECT * FROM categories');
												$stmt->execute();

												$categories = $stmt->fetchAll();

												foreach ($categories as $category)
												{
													echo "<option value='" . $category['catgID'] . "'";
													selectIt($category['catgID'], $result['Catg_ID']);
													echo ">" . $category['catgName'] . "</option>";
												}
											?>
										</select>
									</div>						
								</div>
								<!-- Ending Category -->
								
								<!-- Start Submit Button -->
								<div class="form-group form-group-lg">
									<div class="col-sm-offset-2 col-md-5 col-md-offset-4">
										<input type="submit" value="Edit" name="update" class="btn btn-primary btn-lg col-sm-5">
										<a href='items.php?do=delete&id=<?php echo $result["UserID"];?>' class='btn btn-danger btn-lg col-sm-offset-1 col-sm-5'>Delete</a>

									</div>						
								</div>
								<!-- Ending Submit Button -->
						
							</form>
						</div>
						<?php
					}
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
					$itemID 	= $_POST['itemid'];
					$itemname 	= $_POST["itemname"];
					$desc 		= $_POST["description"];
					$price 		= $_POST["price"];
					$country 	= $_POST["country"];
					$status 	= $_POST["status"];
					$member 	= $ssnid;
					$category 	= $_POST["category"];

					$formErrors = array();

					if (empty($itemname))
					{
						$formErrors[] = 'Item Name Can\'t be Empty';
					}

					if (empty($desc))
					{
						$formErrors[] = 'Description Can\'t be Empty';
					}

					if (empty($price))
					{
						$formErrors[] = 'Price Can\'t be Empty';
					}
					
					if (empty($country))
					{
						$formErrors[] = 'Country Can\'t be Empty';
					}

					if ($status === "0")
					{
						$formErrors[] = 'Status Can\'t be Empty';
					}
					
					if ($member === '0')
					{
						$formErrors[] = 'Member Can\'t be Empty';
					}

					if ($category === '0')
					{
						$formErrors[] = 'Category Can\'t be Empty';
					}

					if (!empty($formErrors))
					{
						foreach ($formErrors as $error) 
						{
							echo "<div class='alert alert-danger'>" . $error . "</div>";
						}
						reDirect("items.php");
					}
					else
					{
						$stmt = $con->prepare("	UPDATE items
												SET 
													itemName = ?,
													itemDesc = ?,
													countryMade = ?,
													Price = ?,
													status = ?,
													Catg_ID = ?,
													User_ID = ?
												WHERE
													item_ID = ?");
						$stmt->execute(array($itemname, $desc, $country, $price, $status, $category, $member, $itemID));

						header("location: profile.php");	
					}
					

				}	
				else
				{
					redirect("You can not be here", "index.php");
				}
				echo "</div>";
			break;
			// ============================
			// == Ending The Update Page ==
			// ============================

			// ================================
			// == Start The Edit Member Page ==
			// ================================
			case 'editmember': 

				$stmt = $con->prepare('	SELECT * FROM members WHERE UserID = ? LIMIT 1');
				

				$stmt->execute(array($ssnid));
				$result = $stmt->fetch();
				$count = $stmt->rowCount();

				if ($count > 0)
				{
					?>
					<h1 class="text-center">Edit Member</h1>
					<div class="container">
						<form class="form-horizontal update" action="?do=updatemember" method="POST" enctype="multipart/form-data">
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












			
			break;
			// =================================
			// == Ending The Edit Member Page ==
			// =================================


			// ==================================
			// == Start The Update Member Page ==
			// ==================================
			case 'updatemember':



			echo "<div class='container'>";
				if ($_SERVER['REQUEST_METHOD'] == 'POST')
				{
					$id 		= $ssnid;
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
					header("location: profile.php");
				}	
				else
				{
					redirect("You can not be here", "members.php");
				}
				echo "</div>";



				
			break;
			// ===================================
			// == Ending The Update Member Page ==
			// ===================================

			// ==================================
			// == Start Make Sure For Deleting ==
			// ==================================
			case 'suredelete':
			echo "<div class='container'>";

			$id = $_GET['id'];
			$stmt = $con->prepare("	SELECT
										items.*, members.UserName, categories.catgName
									FROM
										items
									INNER JOIN
										categories 	ON items.Catg_ID = categories.catgID
									INNER JOIN
										members  	On items.User_ID = members.UserID
									
									WHERE
										item_ID = ?");
			$stmt->execute(array($id));
			$item = $stmt->fetch();
			?>
			
				<div class="alert alert-danger">Are You Sure You Want to Delete This Item?</div>
				<div class="col-md-8 col-sm-offset-2">
					<div class="item-pand">
						<div class="item-head">
							<?php echo $item['item_ID']; ?>: <?php echo $item['itemName']; ?>		
						</div>
						<div class="item-pic">
							<img src="300.png" class="img-thumbnai">
							<div class="item-price">
								<?php echo $item['Price']; ?>
							</div>
						</div>
						<div class="item-dtls">
							<strong>Description: </strong> <?php echo $item['itemDesc']; ?> <br>
							<strong>Status: </strong> <?php echo $item['status']; ?> <br>
							<strong>Description: </strong> <?php echo $item['countryMade']; ?> <br>
							<strong>Member: </strong> <?php echo $item['UserName']; ?> <br>
							<strong>Category: </strong> <?php echo $item['catgName']; ?> <br>
						</div>
						<div class="item-btns text-center">
							<a href="?do=edit&id=<?php echo $id; ?>" class="btn btn-success"><i class='fa fa-edit'></i> No, edit</a>
							<a href="?do=delete&id=<?php echo $id ?>" class="btn btn-danger"><i class='fa fa-minus'></i> Yes, delete it.</a>
						</div>
					</div>
				</div>


			<?php
			echo "</div>";
			break;
			// ===========================
			// == Start The Delete Page ==
			// ===========================		
			case 'delete':
			echo "<div class='container'>";

			$id 		= $_GET['id'];
						
			$stmt = $con->prepare("DELETE FROM items WHERE item_ID = ?");
			$stmt->execute(array($id));
			
			header("location: profile.php");
			
			echo "</div>";
			break;
			// ============================
			// == Ending The Delete Page ==
			// ============================
	
			default:
				echo 'Wrong';
				break;
		}
	}
	else
	{
		header("location: index.php");
		exit();
	}
	?>
<?php	include $tpl . 'footer.inc.php'; // => includes/templates/footer.php ?>