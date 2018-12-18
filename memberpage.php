<?php

	session_start();
	$titleName = "Member Profile";

 	include 'init.php'; 

 	// Get member info
 	$userid = $_GET['id'];
 	$stmt = $con->prepare("SELECT * FROM members WHERE UserID = ?");
	$stmt->execute(array($userid));
	$userdtls = $stmt->fetch();

	// Get items info
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
	$stmt->execute(array($userid));
	$items = $stmt->fetchAll();



	// Get Comments info

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
	$stmt->execute(array($userid));
	$comments = $stmt->fetchALL(); 	

 	?>
 		<div class="profile-page">
			<div class="container">
				<h1 class="text-center"><?php echo $userdtls['UserName']; ?></h1>
				<div class="panel panel-primary">
					<div class="panel-heading">
						Information
					</div>
					<div class="panel-body">
						<div class="row">
							<div class="col-sm-4 text-center panel-img-manege">
								<div>
									<?php
									if (empty($userdtls['image']))
									{
										echo "<strong>No Image</strong>";
									}
									else
									{
									echo "<img src='data/uploads/profile/" . $userdtls['image'] . "'>"; 
									}
									?>
								</div>
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
										<img src="300.png">
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
										<button type="button" class="btn btn-info" data-toggle="modal" data-target="#m-<?php echo $item['item_ID'] ?>">Read More</button>
										<a href="itempage.php?itemid=<?php echo $item['item_ID']; ?>" class="btn btn-info">View Page</a>
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
							echo '<table class="text-center">';
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
												<strong><?php echo $comment['UserName']; ?></strong><br>
												<?php echo $comment['date']; ?><br>
												<strong>Item: </strong><a href="items.php?do=itempage&itemid=<?php echo $comment['item_ID']; ?>"><?php echo $comment['itemName']; ?></a><br>
											</span>
											<div class="user-profile-comment">
												<?php echo $comment['comment']; ?><br><br>
											</div>
										</div>
			<?php 				}
							echo '</table>';
						}
					?>
				</div>
			</div>
		</div>
	</div>
	
<?php 	include $tpl . 'footer.inc.php'; ?>