<?php
	session_start();
	$titleName = 'Categories';
	$isActive = 'Ctgrs';

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

			if (isset($_GET['sort']))
			{
				$_SESSION['sort'] = $_GET['sort']; 
				$sort = 			$_GET['sort'];
			}
			elseif (isset($_SESSION['sort']))
			{
				$sort = $_SESSION['sort']; 
			}
			else
			{
				$sort = 'ASC';
			}

			if (isset($_GET['sortby']))
			{
				$_SESSION['sortby'] = 	$_GET['sortby']; 
				$sortby 			=	$_GET['sortby']; 
			}
			elseif (isset($_SESSION['sortby']))
			{
				$sortby = $_SESSION['sortby'];
			}
			else
			{
				$sortby = 'catgID';
			}
			
			$sortArr = array('ASC' , 'DESC');
			$sortByArr = array('catgID' , 'Ordering');

			if (isset($_GET['sort']) && in_array($_GET['sort'], $sortArr))
			{
				$sort = $_GET['sort']; 				
			}

			if (isset($_GET['sortby']) && in_array($_GET['sortby'], $sortByArr))
			{
				$sortby = $_GET['sortby']; 				
			}

			$stmt = $con->prepare("SELECT * FROM categories ORDER BY $sortby $sort");
			$stmt->execute();

			$rows = $stmt->fetchALL();
			?>
			<h1 class="text-center">Manage Categories Page</h1>
			<div class="container">
				<div style="margin-bottom: 10px;">
					Sort:
					<a href="?sortby=catgID" class="btn btn-primary">ID</a>
					<a href="?sortby=Ordering" class="btn btn-primary">Ordering</a>
					
					By:
					<a href="?sort=ASC" class="btn btn-primary">ASC</a>
					<a href="?sort=DESC" class="btn btn-primary">DESC</a>
				
					View:
					<button class="btn btn-primary" id="full-view">Full</button>
					<button class="btn btn-primary" id="main-view">Main</button>
				</div>
				<?php 	
					foreach ($rows as $row)
					{
						?>
						<div class="panel panel-default">
							<div class="panel-heading panel-heading-toggle">
								<h4>
									<strong>
										<?php echo $row['catgID'] . ": " . $row['catgName']; ?>
										<!-- (ID: Name) -->
									</strong>
								</h4>
								<div class="hidden-btns">
									<a href="?do=edit&id=<?php echo $row['catgID']; ?>" class="btn btn-sm btn-success"><i class='fa fa-edit'></i> Edit</a>
									<a href="?do=suredelete&id=<?php echo $row['catgID']; ?>" class="btn btn-sm btn-danger"><i class='fa fa-minus'></i> Delete</a>
								</div>
							</div>
							<div class="panel-body panel-cat">
								<strong>Description</strong>: 		<?php echo $row['Description']; ?><br>
								<strong>Ordering</strong>: 			<?php echo $row['Ordering']; ?><br>
								<strong>Visibilty</strong>: 		<?php echo catgVis($row['isVisible']); ?><br>
								<strong>Comments</strong>: 			<?php echo catgCom($row['canComment']); ?><br>
								<strong>Advertisements</strong>: 	<?php echo catgAds($row['adsAds']); ?><br>
							</div>
						</div>	
						<?php 	
					}
				?>						
				<a href="?do=add" class="btn btn-primary"><i class="fa fa-plus"></i> Add New Category</a>		
			</div>
		</div>
		<?php
			break;
			// ==========================
			// == Ending The Main Page ==
			// ==========================


			// ========================
			// == Start The Add Page ==
			// ========================
			case 'add':
				?>
				<h1 class="text-center">Add Category</h1>
				<div class="container">
					<form class="form-horizontal update" action="?do=insert" method="POST">
						<!-- Start Category Name -->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 col-md-4 control-label">Category Name</label>
							<div class="col-sm-9 col-md-5">
								<input type="text" name="catgname" class="form-control" autocomplete="off" required>
							</div>						
						</div>
						<!-- Ending User Name -->

						<!-- Start Description -->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 col-md-4 control-label">Description</label>
							<div class="col-sm-9 col-md-5">
								<textarea name="description" class="form-control" style="resize: vertical;"></textarea>
							</div>						
						</div>
						<!-- Ending Description -->

						<!-- Start Ordering -->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 col-md-4 control-label">Ordering</label>
							<div class="col-sm-9 col-md-5">
								<input type="text" name="order" class="form-control" autocomplete="off">
							</div>						
						</div>
						<!-- Ending Ordering -->

						<!-- Start isVisible -->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 col-md-4 control-label">Visibilty</label>
							<div class="col-sm-9 col-md-5">
								<div>
									<input id="vis-yes" type="radio" name="vis" value="1" checked>
									<label for="vis-yes">Yes</label>
								</div>
								<div>
									<input id="vis-no" type="radio" name="vis" value="0">
									<label for="vis-no">No</label>
								</div>
							</div>						
						</div>
						<!-- Ending isVisible -->

						<!-- Start canComment -->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 col-md-4 control-label">Commentable</label>
							<div class="col-sm-9 col-md-5">
								<div>
									<input id="comment-yes" type="radio" name="comment" value="1" checked>
									<label for="comment-yes">Yes</label>
								</div>
								<div>
									<input id="comment-no" type="radio" name="comment" value="0">
									<label for="comment-no">No</label>
								</div>
							</div>						
						</div>
						<!-- Ending canComment -->

						<!-- Start addAds -->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 col-md-4 control-label">Advertisements</label>
							<div class="col-sm-9 col-md-5">
								<div>
									<input id="ads-yes" type="radio" name="ads" value="1" checked>
									<label for="ads-yes">Yes</label>
								</div>
								<div>
									<input id="ads-no" type="radio" name="ads" value="0">
									<label for="ads-no">No</label>
								</div>
							</div>						
						</div>
						<!-- Ending isVisible -->

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
					$catgname 	= $_POST["catgname"];
					$desc 		= $_POST["description"];
					$order 		= $_POST["order"];
					$visibilty 	= $_POST["vis"];
					$comment 	= $_POST["comment"];
					$ads 		= $_POST["ads"];
					
					$formDuplicate = array();
					if (isExist("catgName", "categories", $catgname, "catgID"))
					{
						$formDuplicate[] = "This User Name Exists";
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
													categories(catgName, Description, Ordering, isVisible, canComment, adsAds)
												VALUES
													(?, ?, ?, ?, ?, ?)");
						$stmt->execute(array($catgname, $desc, $order, $visibilty, $comment, $ads));

						echo "<div class='alert alert-success'>Category was Added</div>";
						header("refresh: 2;url=categories.php");
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
					$catgID = $_GET['id'];
					$stmt = $con->prepare('SELECT * FROM categories WHERE catgID = ? LIMIT 1');
					$stmt->execute(array($catgID));
					$result = $stmt->fetch();
					$count = $stmt->rowCount();

					if ($count > 0)
					{
						?>
						<h1 class="text-center">Edit Category</h1>
						<div class="container">
							<form class="form-horizontal update" action="?do=update" method="POST">
								<input type="hidden" name="catgID" value="<?php echo $result['catgID']; ?>">
								<!-- Start Category Name -->
								<div class="form-group form-group-lg">
									<label class="col-sm-2 col-md-4 control-label">Category Name</label>
									<div class="col-sm-9 col-md-5">
										<input type="text" name="catgname" class="form-control" value="<?php echo $result['catgName']; ?>" autocomplete="off" required>
									</div>						
								</div>
								<!-- Ending Category Name -->

								<!-- Start Description -->
								<div class="form-group form-group-lg">
									<label class="col-sm-2 col-md-4 control-label">Description</label>
									<div class="col-sm-9 col-md-5">
										<textarea name="description" class="form-control" style="resize: vertical;"><?php echo $result['Description']; ?></textarea>								
									</div>						
									
								</div>
								<!-- Ending Description -->

								<!-- Start Ordering -->
							<div class="form-group form-group-lg">
								<label class="col-sm-2 col-md-4 control-label">Ordering</label>
								<div class="col-sm-9 col-md-5">
									<input type="text" name="order" class="form-control" autocomplete="off" value="<?php echo $result['Ordering']; ?>">
								</div>						
							</div>
							<!-- Ending Ordering -->

							<!-- Start isVisible -->
							<div class="form-group form-group-lg">
								<label class="col-sm-2 col-md-4 control-label">Visibilty</label>
								<div class="col-sm-9 col-md-5">
									<div>
										<input id="vis-yes" type="radio" name="vis" value="1" <?php if($result['isVisible'] == 1 ) {echo 'checked';} ?>>
										<label for="vis-yes">Yes</label>
									</div>
									<div>
										<input id="vis-no" type="radio" name="vis" value="0" <?php if($result['isVisible'] == 0 ) {echo 'checked';} ?>>
										<label for="vis-no">No</label>
									</div>
								</div>						
							</div>
							<!-- Ending isVisible -->

							<!-- Start canComment -->
							<div class="form-group form-group-lg">
								<label class="col-sm-2 col-md-4 control-label">Commentable</label>
								<div class="col-sm-9 col-md-5">
									<div>
										<input id="comment-yes" type="radio" name="comment" value="1" <?php if($result['canComment'] == 1 ) {echo 'checked';} ?>>
										<label for="comment-yes">Yes</label>
									</div>
									<div>
										<input id="comment-no" type="radio" name="comment" value="0" <?php if($result['canComment'] == 0 ) {echo 'checked';} ?>>
										<label for="comment-no">No</label>
									</div>
								</div>						
							</div>
							<!-- Ending canComment -->

							<!-- Start addAds -->
							<div class="form-group form-group-lg">
								<label class="col-sm-2 col-md-4 control-label">Advertisements</label>
								<div class="col-sm-9 col-md-5">
									<div>
										<input id="ads-yes" type="radio" name="ads" value="1" <?php if($result['adsAds'] == 1 ) {echo 'checked';} ?>>
										<label for="ads-yes">Yes</label>
									</div>
									<div>
										<input id="ads-no" type="radio" name="ads" value="0" <?php if($result['adsAds'] == 0 ) {echo 'checked';} ?>>
										<label for="ads-no">No</label>
									</div>
								</div>						
							</div>
							<!-- Ending isVisible -->

								<!-- Start Submit Button -->
								<div class="form-group form-group-lg">
									<div class="col-sm-offset-2 col-md-5 col-md-offset-4">
										<input type="submit" value="Edit" name="update" class="btn btn-primary btn-lg col-sm-5">
										<a href='categories.php?do=suredelete&id=<?php echo $result["catgID"];?>' class='btn btn-danger btn-lg col-sm-offset-1 col-sm-5'>Delete</a>

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
					$id 		= $_POST['catgID'];
					$catgname 	= $_POST['catgname'];
					$desc 		= $_POST['description'];
					$order 	 	= $_POST['order'];
					$vis 	 	= $_POST['vis'];
					$comment 	= $_POST['comment'];
					$ads 	 	= $_POST['ads'];	
					$catgID 	= "catgID";

					// Validation 
					$formErrors = array();

					if (empty($catgname))
					{
						$formErrors[] = 'Category Name Can\'t be Empty';
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
						if (isExist("catgName", "categories", $catgname, $catgID, $id)) // "SELECT "catgName" FROM "categories" WHERE "catgName" = ? AND ? != ?"
						{
							$formDuplicate[] = "This Category Name Exists";
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
							$stmt = $con->prepare("UPDATE categories SET
																		catgName = ?,
																		Description = ?,
																		Ordering = ?,
																		isVisible = ?,
																		canComment = ?,
																		adsAds = ?
																	WHERE catgID = ?");
							$stmt->execute(array($catgname, $desc, $order, $vis, $comment, $ads, $id));
						}
					}
					header("location: categories.php");
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
				$stmt = $con->prepare("	SELECT * FROM categories WHERE catgID = ?");
				$stmt->execute(array($id));
				$catg = $stmt->fetch();
				?>			
				<div class="alert alert-danger">Are You Sure You Want to Delete This Category?</div>
				<div class="col-md-8 col-sm-offset-2">
					<div class="panel panel-default">
						<div class="panel-heading panel-heading-toggle">
							<h4>
								<strong>
									<?php echo $catg['catgID'] . ": " . $catg['catgName']; ?>		
								</strong>
							</h4>
						</div>
						<div class="panel-body panel-cat">
							<strong>Description</strong>: 		<?php echo $catg['Description']; ?><br>
							<strong>Ordering</strong>: 			<?php echo $catg['Ordering']; ?><br>
							<strong>Visibilty</strong>: 		<?php echo catgVis($catg['isVisible']); ?><br>
							<strong>Comments</strong>: 			<?php echo catgCom($catg['canComment']); ?><br>
							<strong>Advertisements</strong>: 	<?php echo catgAds($catg['adsAds']); ?><br>
							<div class="text-center">
								<br>
								<a href="?do=edit&id=<?php echo $catg['catgID']; ?>" class="btn btn-success"><i class='fa fa-edit'></i> No, Edit</a>
								<a href="?do=delete&id=<?php echo $catg['catgID']; ?>" class="btn btn-danger"><i class='fa fa-minus'></i> Yes, Delete</a>
							</div>
						</div>
					</div>	
				</div>
				<?php
				echo "</div>";
			break;
			// ===========================
			// == Start The Delete Page ==
			// ===========================

			// ===========================
			// == Start The Delete Page ==
			// ===========================		

			case 'delete':
				echo "<div class='container'>";

				$id	= $_GET['id'];
							
				$stmt = $con->prepare("DELETE FROM categories WHERE catgID = ?");
				$stmt->execute(array($id));
				
				header("location: categories.php");
				
				echo "</div>";
			break;
			// ============================
			// == Ending The Delete Page ==
			// ============================


			default:
				echo '<div class="container"><div class="alert alert-info">Sorry There is a Problem</div></div>';
			break;
		}
	
	}
	else
	{
		header('location: index.php');
	}
	?>
	<?php include $tpl . 'footer.inc.php'; // => includes/templates/footer.php ?>