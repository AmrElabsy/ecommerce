<?php
	session_start();
	$titleName = 'Main Page';

	include 'init.php';

	$itemid = $_GET['itemid'];
	$stmt = $con->prepare(" SELECT
								items.*,members.UserName, members.UserID, categories.catgName
							FROM
								items
							INNER JOIN
								members
							ON
								items.User_ID = members.UserID
							INNER JOIN
								categories
							ON items.Catg_ID = categories.catgID
							WHERE
								items.item_ID = ? ");
	$stmt->execute(array($itemid));
	$item = $stmt->fetch();

	if ($_SERVER['REQUEST_METHOD'] == "POST")
	{
		$comment = filter_var($_POST['comment'], FILTER_SANITIZE_STRING);

		if (!empty($comment))
		{
			if(!empty($ssnid))
			{
			$stmt = $con->prepare(" INSERT INTO comments(comment, date, user_ID, item_ID, status)
												VALUES (?, now(), ?, ?, 0)");
			$stmt->execute(array($comment, $ssnid, $itemid));
			}
			else
			{
				echo "<div class='container'><div class='alert alert-danger'>Sorry You are not A member</div></div>";
			}
			
		}

	}

	$stmt = $con->prepare("	SELECT
								comments.*, members.UserName, members.UserID, members.image
							FROM
								comments
							INNER JOIN
								members
							ON
								comments.user_ID = members.UserID
							WHERE
								comments.item_ID = ?");
	$stmt->execute(array($itemid));
	$comments = $stmt->fetchALL();

	
		
		?>
		<div class="container">
			<h1 class="text-center"><?php echo $item['itemName']; ?></h1>
			<div class="panel panel-primary">
				<div class="panel-heading">
					Item Info
				</div>
				<div class="panel-body">
					<div class="col-md-4">
						<div class="item-page-img text-center">
							<?php
							if (empty($item['image']))
							{
								echo "<strong>No Image</strong>";
							}
							else
							{
							echo "<img class='img-responsive' src='data/uploads/item/" . $item['image'] . "'>"; 
							}
							?>
						</div>
					</div>
					<div class="col-md-8">
						<div class="item-page-details">
							<strong>Description: </strong> <?php echo $item['itemDesc'] ?> <br>
							<strong>Status: </strong> <?php echo $item['status']; ?> <br>
							<strong>Country: </strong> <?php echo $item['countryMade']; ?> <br>
							<strong>Member: </strong> <a href="memberpage.php?id=<?php echo $item['UserID']; ?>"><?php echo $item['UserName']; ?></a><br>
							<strong>Category: </strong> <?php echo $item['catgName']; ?> <br>
						</div>
					</div>
				</div>	
			</div>
		</div>
	
		<div class="container">
			<div class="panel panel-primary">
				<div class="panel-heading">
					Comments
				</div>
				<div class="col-md-offset-3 col-md-8">
					<div class="addcomment">
						<form class="form-horizontal" method="POST" action="?itemid=<?php echo $item['item_ID'] ?>">
							<textarea style="resize: vertical;" class="form-control" name="comment" placeholder="Write Your comment Here" required></textarea>
							<button class="btn btn-primary">Add Comment</button>
						</form>
					</div>
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
													if (!empty($comment['image']))
													{
													echo "<img class='img-circle' src='data/uploads/profile/" . $comment['image'] . "'><br>"; 
													}
													else
													{
														echo "<img class='img-circle' src='300.png'><br>"; 
													}
													?>
												</div>
												<strong><a href="memberpage.php?id=<?php echo $comment['UserID']; ?>"><?php echo $comment['UserName']; ?></a></strong><br>
												<?php echo $comment['date']; ?><br>
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
<?php
	include $tpl . 'footer.inc.php'; ?>