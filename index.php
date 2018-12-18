<?php
	session_start();
	$titleName = 'Main Page';

	include 'init.php';


	$stmt = $con->prepare("	SELECT
								items.*, members.UserName,members.UserID, categories.catgName
							FROM
								items
							INNER JOIN
								categories 	ON items.Catg_ID = categories.catgID
							INNER JOIN
								members  	On items.User_ID = members.UserID
							ORDER BY item_ID DESC");
	$stmt->execute();
	$items = $stmt->fetchAll();	
?>
	<h1 class="text-center">Main Page</h1>
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
							<?php echo $item['itemName']; ?>		
						</div>
						<div class="item-pic fixed-height2">
							<?php
							if (empty($item['image']))
							{
								echo "<img class='img-responsive' src='300.png'>";
							}
							else
							{
							echo "<img class='img-responsive' src='data/uploads/item/" . $item['image'] . "'>"; 
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
							<strong>Member: </strong> <a href="memberpage.php?id=<?php echo $item['UserID']; ?>"><?php echo $item['UserName']; ?></a><br>
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
									<strong>Member: </strong> <a href="memberpage.php?id=<?php echo $item['UserID']; ?>"><?php echo $item['UserName']; ?></a><br>
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
<?php }	?>
		</div>
	</div>

<?php
	include $tpl . 'footer.inc.php'; // => includes/templates/footer.php ?>