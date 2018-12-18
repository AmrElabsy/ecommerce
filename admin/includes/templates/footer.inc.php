		<footer id="footer">
			<div class="container">
				<div class="row">
					<div class="col-sm-4">
						<h2>Categories</h2>
						<?php
						$stmt = $con->prepare("SELECT * FROM categories");
						$stmt->execute();
						$result = $stmt->fetchALL();

						foreach ($result as $catg)
						{
							echo "<a class='btn btn-success btn-lg btn-block' href='categories.php'>" . $catg['catgName'] . "</a>";
						}
						?>
					</div>
					<div class="col-sm-8">
						<h2>Contact</h2>
						<form>
							<div class="row">
								<div class="col-sm-6">
									<!-- Start Name -->
									<div class="form-group form-group-lg">
										<div class="col-sm-12">
											<input type="text" placeholder="Your Name" name="username" class="form-control" autocomplete="off" required>
										</div>						
									</div>
									<!-- Ending Name -->
									<!-- Start Name -->
									<div class="form-group form-group-lg">
										<div class="col-sm-12">
											<input type="email" placeholder="Your E-mail" name="email" class="form-control" autocomplete="off" required>
										</div>						
									</div>
									<!-- Ending Name -->
									<!-- Start Name -->
									<div class="form-group form-group-lg">
										<div class="col-sm-12">
											<input type="text" placeholder="Your Phone" name="phone" class="form-control" autocomplete="off">
										</div>						
									</div>
									<!-- Ending Name -->
								</div>
								<div class="col-sm-6">
									<textarea name="description" class="form-control" style="resize: vertical;">Your Message</textarea>
									<input type="submit" name="send" class="btn btn-primary">
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</footer>

		<script src="<?php echo $js; ?>jquery-3.3.1.min.js"></script> <!-- layout/js/bootstrap.min.js -->
		<script src="<?php echo $js; ?>bootstrap.min.js"></script>
		<script src="<?php echo $js; ?>adminscript.js"></script>		
	</body>
</html>