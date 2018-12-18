<?php
	session_start();
	$titleName = 'Add new Item';

	include 'init.php';

	if (isset($_SESSION['user']))
	{

		if ($_SERVER['REQUEST_METHOD']=='POST')
		{
			$itemname 	= $_POST["itemname"];
			$desc 		= $_POST["description"];
			$price 		= '$' . $_POST["price"];
			$country 	= $_POST["country"];
			$status 	= $_POST["status"];
			$member 	= $ssnid;
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

			if ($category === '0')
			{
				$formErrors[] = 'Category Can\'t be Empty';
			}

			// Validade Image
			if (in_array($imgType, $allowedExtentions))
			{
				move_uploaded_file($imgTmp, "data\uploads\item\\" . $imgName);
			}
			else
			{
				$formErrors[] = 'This Extention is not allowed';	
			}

			if (!empty($formErrors))
			{
				foreach ($formErrors as $error) 
				{
					echo "<div class='container'><div class='alert alert-danger'>" . $error . "</div></div>";
				}
				reDirect("additem.php");
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
					header("refresh: 2;url=additem.php");	
				}
				else
				{
					$stmt = $con->prepare("	INSERT INTO
												items(itemName, itemDesc, image, countryMade, Price, Date, status, Catg_ID, User_ID)
											VALUES
												(?, ?, ?, ?, ?, now(), ?, ?, ?)");
					$stmt->execute(array($itemname, $desc, $imgName, $country, $price, $status, $category, $member));

					echo "<div class='alert alert-success'>Item was Added</div>";
					header("refresh: 2;url=additem.php");	
				}			
			}
		}
		?>
			<h1 class="text-center">Add new Item</h1>
			<div class="container">
				<div class="panel panel-primary">
					<div class="panel-heading">
						Add New Item
					</div>
					<div class="panel-body">	
						<div class="col-sm-8">
							<form class="form-horizontal update" action="<?php echo $_SERVER['PHP_SELF']; ?> " method="POST" enctype="multipart/form-data">
								<!-- Start Item Name -->
								<div class="form-group form-group-lg">
									<label class="col-sm-2 control-label">Item Name</label>
									<div class="col-sm-9">
										<input type="text" name="itemname" class="form-control item-name-form">
									</div>						
								</div>
								<!-- Ending Item Name -->

								<!-- Start Description -->
								<div class="form-group form-group-lg">
									<label class="col-sm-2 control-label">Description</label>
									<div class="col-sm-9 ">
										<textarea name="description" class="form-control item-desc-form" style="resize: vertical;"></textarea>
									</div>						
								</div>
								<!-- Ending Description -->

								<!-- Start Image -->
								<div class="form-group form-group-lg">
									<label class="col-sm-2 control-label">Item Image</label>
									<div class="col-sm-9">
										<input type="file" name="image" class="form-control item-image-form">										
									</div>						
								</div>
								<!-- Ending Image -->

								<!-- Start Price -->
								<div class="form-group form-group-lg">
									<label class="col-sm-2 control-label">Price</label>
									<div class="col-sm-9 ">
										<input type="text" name="price" class="form-control item-price-form">
									</div>						
								</div>
								<!-- Ending Price -->

								<!-- Start Country -->
								<div class="form-group form-group-lg">
									<label class="col-sm-2 control-label">Country</label>
									<div class="col-sm-9 ">
										<input type="text" name="country" class="form-control item-country-form">
									</div>						
								</div>
								<!-- Ending Country -->

								<!-- Start Status -->
								<div class="form-group form-group-lg">
									<label class="col-sm-2 control-label">Status</label>
									<div class="col-sm-9">
										<select class="form-control item-status-form" name="status">
											<option value="0"><strong>...</strong></option>
											<option value="new">New</option>
											<option value="likenew">Like New</option>
											<option value="used">Used</option>
										</select>
									</div>						
								</div>
								<!-- Ending Status -->					

								<!-- Start Category -->
								<div class="form-group form-group-lg">
									<label class="col-sm-2 control-label">Category</label>
									<div class="col-sm-9">
										<select class="form-control item-category-form" name="category">
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
									<div class="col-sm-offset-2 col-md-9">
										<input type="submit" value="Add" name="update" class="btn btn-primary btn-block btn-lg">
									</div>						
								</div>
								<!-- Ending Submit Button -->
							</form>
						</div>
						<div class="col-sm-4">
							<div class="item-pand text-center">
								<div class="item-head">
									<div class="item-name-div">
										
									</div>
								</div>
								<div class="item-pic">
									<img src="300.png" class="item-image-div">
									<div class="item-price">
										<div class="item-price-div">
											
										</div>
									</div>
								</div>
								<div class="item-dtls">
									<strong>Description: </strong> <span class="item-desc-div"></span> <br>
									<strong>Status: </strong> <span class="item-status-div"></span> <br>
									<strong>Country: </strong><span class="item-country-div"></span> <br>
									<strong>Member: </strong> <?php echo $ssnuser; ?> <br>
									<strong>Category: </strong> <span class="item-category-div"></span> <br>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		<?php 
	}
	else
	{
		header("location: index.php");
		exit();
	}

	?>
	
<?php	include $tpl . 'footer.inc.php'; // => includes/templates/footer.php ?>