<?php
	session_start();
	$titleName = 'Comments';
	$isActive = 'comments';

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
										comments.*, items.itemName, items.item_ID, members.UserName, members.UserID
									FROM
										comments
									INNER JOIN
										items 	ON comments.item_ID = items.item_ID
									INNER JOIN
										members ON comments.user_ID = members.UserID
									ORDER BY
										comID ASC");
			$stmt->execute();
			$rows = $stmt->fetchALL();
			?>
				<div class="container text-center">
					<h1>Comments Manage</h1>

			<?php if(!empty($rows))
			{ ?>
					<div class="table-responsive">
						<table class="main-table table table-hover table-bordered">
							<tr>
								<td>ID</td>
								<td>Comment</td>
								<td>Date & Time</td>
								<td>Item</td>
								<td>User</td>
								<td>Manage</td>
							</tr>

							<?php 
							
							foreach ($rows as $row)
							{
								echo "<tr>";
									echo "<td>" . $row['comID'] 	. "</td>";
									echo "<td>" . $row['comment'] 	. "</a></td>";
									echo "<td>" . $row['date'] 		. "</td>";
									echo "<td><a href='items.php?do=itempage&itemid=" . $row['item_ID'] . "'>" . $row['itemName'] 	. "</a></td>";
									echo "<td><a href='members.php?do=profile&userid=" . $row['UserID'] . "'>" . $row['UserName']	. "</a></td>";
									echo "<td>
											<a href='comments.php?do=edit&id=" . $row["comID"] . "' class='btn btn-success'><i class='fa fa-edit'></i> Edit</a>
											<a href='comments.php?do=suredelete&id=" . $row["comID"] . "' class='btn btn-danger'><i class='fa fa-minus'></i> Delete</a>";

											
											
									"</td>";
							}
							?>			
						</table>
					</div>
				<?php } ?>
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

				$stmt = $con->prepare('	SELECT * FROM comments WHERE comID = ? LIMIT 1');
				$comID = $_GET['id'];

				$stmt->execute(array($comID));
				$result = $stmt->fetch();
				$count = $stmt->rowCount();

				if ($count > 0)
				{
					?>
					<h1 class="text-center">Edit Comment</h1>
					<div class="container">
						<form class="form-horizontal update" action="?do=update" method="POST">
							<input type="hidden" name="comid" value="<?php echo $result['comID']; ?>">
							<!-- Start Comment -->
							<div class="form-group form-group-lg">
								<label class="col-sm-2 col-md-4 control-label">Comment</label>
								<div class="col-sm-9 col-md-5">
									<textarea name="comment" class="form-control" autocomplete="off" required style="resize: vertical;"><?php echo $result['comment']; ?></textarea>
								</div>						
							</div>
							<!-- Ending Comment -->

							
							<!-- Start Submit Button -->
							<div class="form-group form-group-lg">
								<div class="col-sm-offset-2 col-md-5 col-md-offset-4">
									<input type="submit" value="Edit" name="update" class="btn btn-primary btn-lg col-sm-5">
									<a href='members.php?do=suredelete&id=<?php echo $result["comID"];?>' class='btn btn-danger btn-lg col-sm-offset-1 col-sm-5'>Delete</a>

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

					$id 		= $_POST['comid'];
					$comment 	= $_POST['comment'];
					
					$stmt = $con->prepare("UPDATE comments SET comment = ? WHERE comID = ?");
					$stmt->execute(array($comment, $id));
					
					header("location: comments.php");
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


			// ================================
			// == Start The Sure Delete Page ==
			// ================================		
			case 'suredelete':
			echo "<div class='container'>";

			$id = $_GET['id'];
			$stmt = $con->prepare("	SELECT
										comments.*, items.itemName, members.UserName, members.image
									FROM
										comments
									INNER JOIN
										items 	ON comments.item_ID = items.item_ID
									INNER JOIN
										members ON comments.user_ID = members.UserID
									WHERE
										comID = ?");

			$stmt->execute(array($id));
			$comment = $stmt->fetch();

			?>
			
				<div class="alert alert-danger">Are You Sure You Want to Delete This Comment?</div>
				<div class="col-md-10 col-sm-offset-1">
					<div class="comment-del">
						<div class="user-profile-comment-box text-left">
							<span class="user-profile-comment-user text-center">
								<div class="text-center">
									<?php
									if (!empty($comment['image']))
									{
									echo "<img class='img-circle' src='../data/uploads/profile/" . $comment['image'] . "'><br>"; 
									}
									else
									{
										echo "<img class='img-circle' src='300.png'><br>"; 
									}
									?>
								</div>
								<strong><a href="members.php?do=profile&userid=<?php echo $comment['user_ID'] ?>"><?php echo $comment['UserName']; ?></a></strong><br>
								<?php echo $comment['date']; ?><br>
								<strong>Item: </strong><a href="items.php?do=itempage&itemid=<?php echo $comment['item_ID']; ?>"><?php echo $comment['itemName']; ?></a><br>
							</span>
							<div class="user-profile-comment">
								<?php echo $comment['comment']; ?><br><br>
							</div>
						</div>
						<div class="text-center" style="margin-top: 10px;">
							<a class="btn btn-danger" href="comments.php?do=delete&id=<?php echo $comment['comID']; ?>"><i class='fa fa-minus'></i> Yes, Delete it</a>
							<a class="btn btn-success" href="comments.php?do=edit&id=<?php echo $comment['comID']; ?>"><i class='fa fa-edit'></i> No, Edit it</a>
						</div>
					</div>
				</div>
			<?php
			echo "</div>";
			
			break;
			// =================================
			// == Ending The Sure Delete Page ==
			// =================================


			// ===========================
			// == Start The Delete Page ==
			// ===========================		
			case 'delete':
			
			$id 		= $_GET['id'];
			$stmt = $con->prepare("DELETE FROM comments WHERE comID = ?");
			$stmt->execute(array($id));
			header("location: comments.php");
			
			break;
			// ============================
			// == Ending The Delete Page ==
			// ============================
			
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