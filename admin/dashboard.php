<?php
	session_start();
	$titleName = 'Dashboard'; 	// Title of the page
	$isActive = "dashboard";	// The Acive li in the nav
	
	include 'init.php';

	if (isset($_SESSION['UserName']))
	{
	?>
		<h1 class="text-center">Dashboard</h1>
		<!-- Start the Statistcs -->
		<div class="container home-stats text-center">
			<div class="col-md-3 members">
				<div>
					Total Members
					<span><a href="members.php"><?php echo countItems("UserId", "members"); ?></a></span>
				</div>
			</div>
			<div class="col-md-3 pending">
				<div>
					Pending Members
					<span><a href="members.php?do=main&pending=pendingmembers"><?php echo countItems("UserId", "members", "RegAccess = 0"); ?></a></span>
				</div>
			</div>
			<div class="col-md-3 items">
				<div>
					Total Items
					<span><a href="items.php"><?php echo countItems("item_ID", "items"); ?></a></span>
				</div>
			</div>
			<div class="col-md-3 comments">
				<div>
					Total Comments
					<span><a href="comments.php"><?php echo countItems("comID", "comments"); ?></a></span>
				</div>
			</div>			
		</div>
		<!-- Ending the Statistcs -->

		<!-- Start the Latest Members & Items -->
		<div class="latest">
			<div class="container">
				<div class="col-md-6">
					<div class="panel panel-default">
						<div class="panel-heading">
							<i class="fa fa-users"> The Latest Registered Users</i>
						</div>
						<div class="panel-body">
							<?php
								$latestUsers = latest('members', 5);
							?>
							<div class="table-responsive text-center">
								<table class="main-table table table-hover table-bordered">
									<tr>
										<td>No.</td>
										<td>ID</td>
										<td>User Name</td>
										<td>Date</td>
									</tr>
									<?php
										$No = 1;
										foreach ($latestUsers as $member)
										{

											echo "<tr>";
												echo "<td>" . $No . "</td>";
												echo "<td>" . $member['UserID'] . "</td>";
												echo "<td><a href='members.php?do=profile&userid=" . $member['UserID'] . "'>" . $member['UserName'] . "</a></td>";
												echo "<td>" . $member['Date'] . "</td>";
											echo "<tr>";
											$No = $No + 1;

										}
									?>
								</table>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-6">
					<div class="panel panel-default">
						<div class="panel-heading">
							<i class="fa fa-tag"> The Latest Added Items</i>
						</div>
						<div class="panel-body">	
							<?php
								$latestItems = latest('items', 5);
							?>
							<div class="table-responsive text-center">
								<table class="main-table table table-hover table-bordered">
									<tr>
										<td>No.</td>
										<td>ID</td>
										<td>Item Name</td>
										<td>Date</td>
									</tr>
									<?php
										$No = 1;
										foreach ($latestItems as $item)
										{

											echo "<tr>";
												echo "<td>" . $No . "</td>";
												echo "<td>" . $item['item_ID'] . "</td>";
												echo "<td><a href='items.php?do=itempage&itemid=" . $item['item_ID'] . "'>" . $item['itemName'] . "</a></td>";
												echo "<td>" . $item['Date'] . "</td>";
											echo "<tr>";
											$No = $No + 1;
										}
									?>
								</table>
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
		header('location: index.php');
	}
	include $tpl . 'footer.inc.php'; // => includes/templates/footer.php ?>