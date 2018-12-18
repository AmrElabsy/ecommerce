<?php ?>

        <nav class="navbar navbar-inverse">
        	<div class="container-fluid">
                	<!-- Brand and toggle get grouped for better mobile display -->
            	<div class="navbar-header">
            		<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navBar" aria-expanded="false">
                		<span class="sr-only">Toggle navigation</span>
                		<span class="icon-bar"></span>
                		<span class="icon-bar"></span>
                		<span class="icon-bar"></span>
              		</button>
                    <a class="navbar-brand" href="dashboard.php"><?php echo lang('eCommerce'); ?></a>
                </div>

                <!-- Collect the nav links, forms, and other content for toggling -->
                <div class="collapse navbar-collapse" id="navBar">
                	<ul class="nav navbar-nav">
                    	<li <?php isActive('dashboard'); ?>><a href="dashboard.php"><?php echo lang('Dashboard');?><span class="sr-only">(current)</span></a></li>
                    	<li <?php isActive('Ctgrs'); ?>><a href="categories.php"><?php echo lang('Ctgrs');?></a></li>
                        <li <?php isActive('members'); ?>><a href="members.php"><?php echo lang('members');?></a></li>
                        <li <?php isActive('items'); ?>><a href="items.php"><?php echo lang('items');?></a></li>
                        <li <?php isActive('comments'); ?>><a href="comments.php">Comments</a></li>


                    </ul>
                  	<ul class="nav navbar-nav navbar-right">
                  		<!-- Start Dropdown -->
                    	<li class="dropdown">
                    		<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?php echo $_SESSION['UserName']; ?><span class="caret"></span></a>
                      		<ul class="dropdown-menu">
                        		<li><a href="members.php?do=edit&id=<?php echo $_SESSION['ID'];?>"><?php echo lang('profile'); ?></a></li>
                        		<li><a href="#"><?php echo lang('stngs'); ?></a></li>
                        		<li><a href="logout.php"><?php echo lang('logout'); ?></a></li>
                      		</ul>
                    	</li>
                    	<!-- End Dropdown -->

                	</ul>
                </div><!-- /.navbar-collapse -->
            </div><!-- /.container-fluid -->
        </nav>