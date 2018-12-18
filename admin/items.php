<?php
	session_start();
	$titleName = 'Items';
	$isActive = 'items';

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

				$stmt = $con->prepare("	SELECT
											items.*, members.UserName, members.UserID, categories.catgName
										FROM
											items
										INNER JOIN
											categories 	ON items.Catg_ID = categories.catgID
										INNER JOIN
											members  	On items.User_ID = members.UserID
										ORDER BY item_ID ASC");
				$stmt->execute();
				$items = $stmt->fetchAll();				
				?>
				<h1 class="text-center">Main Items Page</h1>
				<div class="container">
					<div class="item-main text-center">
						<?php
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
										<strong>Member: </strong><a href="members.php?do=profile&userid=<?php echo $item['UserID'] ?>"><?php echo $item['UserName']; ?></a><br>
										<strong>Category: </strong> <?php echo $item['catgName']; ?> <br>
									</div>
									<div class="item-btns">
										<a href="?do=edit&id=<?php echo $item['item_ID']; ?>" class="btn btn-success"><i class='fa fa-edit'></i> Edit</a>
										<a href="?do=suredelete&id=<?php echo $item['item_ID']; ?>" class="btn btn-danger"><i class='fa fa-minus'></i> Delete</a>
										<button type="button" class="btn btn-info" data-toggle="modal" data-target="#m-<?php echo $item['item_ID'] ?>">Read More</button>
										<a style="margin-top: 5px;" href="?do=itempage&itemid=<?php echo $item['item_ID']; ?>" class="btn btn-info">View Page</a>
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
													echo '<div class="img-div">';
														echo '<img src="300.png">' ;
													echo '</div>';
												}
												else
												{
													echo '<div class="img-div text-center">';
														echo '<img src="../data/uploads/item/' . $item['image'] . '">' ;
													echo '</div>';
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
											<a href="?do=itempage&itemid=<?php echo $item['item_ID']; ?>" class="btn btn-info">View Page</a>
											<button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
										</div>
									</div>
								</div>
							</div>
<?php }	?>
					</div>
				</div>
				<div class="container">
					<a href="?do=add" class="btn btn-primary">Add</a>
				</div>
				<?php
			break;
			// =======================
			// == End The Main Page ==
			// =======================


			// ========================
			// == Start The Add Page ==
			// ========================
			case 'add': ?>

				<h1 class="text-center">Add Item</h1>
				<div class="container">
					<form class="form-horizontal update" action="?do=insert" method="POST" enctype="multipart/form-data">
						<!-- Start Item Name -->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 col-md-4 control-label">Item Name</label>
							<div class="col-sm-9 col-md-5">
								<input type="text" name="itemname" class="form-control">
							</div>						
						</div>
						<!-- Ending Item Name -->

						<!-- Start Description -->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 col-md-4 control-label">Description</label>
							<div class="col-sm-9 col-md-5">
								<textarea name="description" class="form-control" style="resize: vertical;"></textarea>
							</div>						
						</div>
						<!-- Ending Description -->

						<!-- Start Image -->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 col-md-4 control-label">Item Image</label>
							<div class="col-sm-9 col-md-5">
								<input type="file" name="image" class="form-control">										
							</div>						
						</div>
						<!-- Ending Image -->

						<!-- Start Price -->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 col-md-4 control-label">Price</label>
							<div class="col-sm-9 col-md-5">
								<input type="text" name="price" class="form-control">
							</div>						
						</div>
						<!-- Ending Price -->

						<!-- Start Country -->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 col-md-4 control-label">Country</label>
							<div class="col-sm-9 col-md-5">
								<input type="text" name="country" class="form-control">
							</div>						
						</div>
						<!-- Ending Country -->

						<!-- Start Status -->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 col-md-4 control-label">Status</label>
							<div class="col-sm-9 col-md-5">
								<select class="form-control" name="status">
									<option value="0"><strong>...</strong></option>
									<option value="new">New</option>
									<option value="likenew">Like New</option>
									<option value="used">Used</option>
								</select>
							</div>						
						</div>
						<!-- Ending Status -->

						<!-- Start Member -->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 col-md-4 control-label">Member</label>
							<div class="col-sm-9 col-md-5">
								<select class="form-control" name="member">
									<option value="0"><strong>...</strong></option>
									<?php
										$stmt = $con->prepare('SELECT * FROM members');
										$stmt->execute();

										$members = $stmt->fetchAll();

										foreach ($members as $member)
										{
											echo "<option value='". $member['UserID'] ."'>" . $member['UserName'] . "</option>";
										}
									?>
								</select>
							</div>						
						</div>
						<!-- Ending Member -->

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
											echo "<option value='". $category['catgID'] ."' >" . $category['catgName'] . "</option>";
										}
									?>
								</select>
							</div>						
						</div>
						<!-- Ending Category -->					
						
						<!-- Start Submit Button -->
						<div class="form-group form-group-lg">
							<div class="col-sm-offset-2 col-md-5 col-md-offset-4">
								<input type="submit" value="Add" name="update" class="btn btn-primary btn-block btn-lg">
							</div>						
						</div>
						<!-- Ending Submit Button -->
				
					</form>
				</div>
			<?php 
				
			break;
			// ======================
			// == End The Add Page ==
			// ======================


			// ===========================
			// == Start The Insert Page ==
			// ===========================
			case 'insert':
				echo "<div class='container'>";
				if ($_SERVER['REQUEST_METHOD'] == 'POST')
				{

					$itemname 	= $_POST["itemname"];
					$desc 		= $_POST["description"];
					$price 		= $_POST["price"];
					$country 	= $_POST["country"];
					$status 	= $_POST["status"];
					$member 	= $_POST["member"];
					$category 	= $_POST["category"];

					// Adding Image Info
					$image 		= $_FILES['image'];

				

					$imgName	= rand(1000000, 9999999) . $image['name'];
					$imgTmp 	= $image['tmp_name'];


					$allowedExtentions = array('jpeg', 'jpg', 'png' , 'gif');
					$imgType = strtolower(end(explode('.', $imgName)));


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

					// Validade Image
					if(!empty($_FILES['image']['name']))
					{
						if (in_array($imgType, $allowedExtentions))
						{
							move_uploaded_file($imgTmp, "..\data\uploads\item\\" . $imgName);
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
						reDirect("items.php");
					}
					else
					{
						if(empty($_FILES['image']['name']))
						{
							$stmt = $con->prepare("	INSERT INTO
														items(itemName, itemDesc, countryMade, Price, Date, status, Catg_ID, User_ID)
													VALUES
														(?, ?, ?, ?, now(), ?, ?, ?)");
							$stmt->execute(array($itemname, $desc, $country, $price, $status, $category, $member));

							echo "<div class='alert alert-success'>Item was Added</div>";
							header("refresh: 2;url=items.php");	
						}
						else
						{
							$stmt = $con->prepare("	INSERT INTO
														items(itemName, itemDesc, image, countryMade, Price, Date, status, Catg_ID, User_ID)
													VALUES
														(?, ?, ?, ?, ?, now(), ?, ?, ?)");
							$stmt->execute(array($itemname, $desc, $imgName, $country, $price, $status, $category, $member));

							echo "<div class='alert alert-success'>Item was Added</div>";
							header("refresh: 2;url=items.php");	
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


			// =========================
			// == Start The Edit Page ==
			// =========================
			case 'edit': 

			if (isset($_GET['id']) && is_numeric($_GET['id']))
			{

				$stmt = $con->prepare('	SELECT * FROM items WHERE item_ID = ? LIMIT 1');
				$itemID = $_GET['id'];

				$stmt->execute(array($itemID));
				$result = $stmt->fetch();
				$count = $stmt->rowCount();

				if ($count > 0)
				{
					?>
					<h1 class="text-center">Edit Item</h1>
					<div class="container">
						<form class="form-horizontal update" action="?do=update" method="POST" enctype="multipart/form-data">
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

							<!-- Start Image -->
							<div class="form-group form-group-lg">
								<label class="col-sm-2 col-md-4 control-label">Item Image</label>
								<div class="col-sm-9 col-md-5">
									<input type="file" name="image" class="form-control">										
								</div>						
							</div>
							<!-- Ending Image -->

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

							<!-- Start Member -->
							<div class="form-group form-group-lg">
								<label class="col-sm-2 col-md-4 control-label">Member</label>
								<div class="col-sm-9 col-md-5">
									<select class="form-control" name="member">
										<option value="0"><strong>...</strong></option>
										<?php
											$stmt = $con->prepare('SELECT * FROM members');
											$stmt->execute();

											$members = $stmt->fetchAll();

											foreach ($members as $member)
											{
												echo "<option value='" . $member['UserID'] . "'";
												selectIt($member['UserID'], $result['User_ID']);
												echo ">" . $member['UserName'] . "</option>";
												/* <option value="ID" #selected>Name</option> */
											}
										?>
									</select>
								</div>						
							</div>
							<!-- Ending Member -->

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
					$member 	= $_POST["member"];
					$category 	= $_POST["category"];

					$image 		= $_FILES['image'];

				

					$imgName	= rand(1000000, 9999999) . $image['name'];
					$imgTmp 	= $image['tmp_name'];


					$allowedExtentions = array('jpeg', 'jpg', 'png' , 'gif');
					$imgType = strtolower(end(explode('.', $imgName)));



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

					if(!empty($_FILES['image']['name']))
					{
						if (in_array($imgType, $allowedExtentions))
						{
							move_uploaded_file($imgTmp, "..\data\uploads\item\\" . $imgName);
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
						reDirect("items.php");
					}
					else
					{
						if (empty($_FILES['image']['name']))
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

							header("location: items.php");
						}
						else
						{
							$stmt = $con->prepare("	UPDATE items
													SET 
														itemName = ?,
														itemDesc = ?,
														image = ?,
														countryMade = ?,
														Price = ?,
														status = ?,
														Catg_ID = ?,
														User_ID = ?
													WHERE
														item_ID = ?");
							$stmt->execute(array($itemname, $desc, $imgName, $country, $price, $status, $category, $member, $itemID));

							header("location: items.php");
						}	
					}
					

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


			// ==================================
			// == Start Make Sure For Deleting ==
			// ==================================
			case 'suredelete':
			echo "<div class='container'>";

			$id = $_GET['id'];
			$stmt = $con->prepare("	SELECT
										items.*, members.UserName, categories.catgName, members.UserID
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
			
				<div class="alert alert-danger col-md-8 col-sm-offset-2">Are You Sure You Want to Delete This Item?</div>
				<div class="col-md-8 col-sm-offset-2">
					<div class="item-pand">
						<div class="item-head">
							<?php echo $item['item_ID']; ?>: <?php echo $item['itemName']; ?>		
						</div>
						<div class="item-pic">
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
						<div class="item-dtls">
							<strong>Description: </strong> <?php echo $item['itemDesc']; ?> <br>
							<strong>Status: </strong> <?php echo $item['status']; ?> <br>
							<strong>Description: </strong> <?php echo $item['countryMade']; ?> <br>
							<strong>Member: </strong> <a href="members.php?do=profile&userid=<?php echo $item['UserID'] ?>"><?php echo $item['UserName']; ?></a> <br>
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
			
			header("location: items.php");
			
			echo "</div>";
			break;
			// ============================
			// == Ending The Delete Page ==
			// ============================


			// ============================
			// == Start The Item Page ==
			// ============================		

			case 'itempage':
			$itemid = $_GET['itemid'];

			$stmt = $con->prepare("	SELECT
										items.*, members.UserName, members.UserID, categories.catgName
									FROM
										items
									INNER JOIN
										categories 	ON items.Catg_ID = categories.catgID
									INNER JOIN
										members  	On items.User_ID = members.UserID
									WHERE item_ID = ?");
			$stmt->execute(array($itemid));
			$item = $stmt->fetch();

			$stmt = $con->prepare("	SELECT
										comments.*, items.itemName, members.UserName, members.image AS userimage
									FROM
										comments
									INNER JOIN
										items 	ON comments.item_ID = items.item_ID
									INNER JOIN
										members ON comments.user_ID = members.UserID
									WHERE
										items.item_ID = ?");
			$stmt->execute(array($itemid));
			$comments = $stmt->fetchALL();
			?>
				<div class="container text-center">
					<h1><?php echo $item['itemName']; ?></h1>
					<div class="col-md-offset-1 col-md-10 item-page-main">
						<div class="col-md-5">
							<div class="item-page-img">
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
						</div>
						<div class="col-md-7">
							<div class="item-page-details">
								<strong>Description: </strong> <?php echo $item['itemDesc'] ?> <br>
								<strong>Status: </strong> <?php echo $item['status']; ?> <br>
								<strong>Country: </strong> <?php echo $item['countryMade']; ?> <br>
								<strong>Member: </strong> <a href="members.php?do=profile&userid=<?php echo $item['UserID'] ?>"><?php echo $item['UserName']; ?></a> <br>
								<strong>Category: </strong> <?php echo $item['catgName']; ?> <br>
							</div>
						</div>	
					</div>
				</div>

				<?php if(!empty($comments))
				{ 
				echo "<h1 class='text-center'>Comments</h1>";
				echo '<div class="container text-center user-profile">';
					foreach ($comments as $comment)
					{?>
					
						<div class="user-profile-comment-box text-left">
							<span class="user-profile-comment-user text-center">
								<div class="text-center">
									<?php
									if (!empty($item['userimage']))
									{
									echo "<img class='img-circle' src='../data/uploads/profile/" . $item['image'] . "'><br>"; 
									}
									else
									{
										echo "<img class='img-circle' src='300.png'><br>"; 
									}
									?>
								</div>
								<strong><?php echo "<a href='members.php?do=profile&userid=" . $item['UserID'] . "'>" . $item['UserName'] . "</a>"; ?></strong><br>
								<?php echo $comment['date']; ?><br>
							</span>
							<div class="user-profile-comment">
								<?php echo $comment['comment']; ?><br><br>
							</div>
						</div>
	<?php 			} 
			echo "</div>";
				}
			break;
			// =============================
			// == Ending The Item Page ==
			// =============================
			
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