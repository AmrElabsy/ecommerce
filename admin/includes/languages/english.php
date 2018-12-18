<?php 

	function lang($word)
	{
		static $lang = array(

			# NavBar

			'eCommerce'			=> 'Amr El-Absy Shop',
			'Dashboard'			=> 'Dashboard',
			'Ctgrs'				=> 'Categories',
			'members'			=> 'Members',
			'admindrop'			=> 'Admin Area',
			'Stats'				=> 'The Statistics of the Website',
			'items'				=> 'Items',
			'membersmanage'		=> 'Management of the Members',
			'profile'			=> 'Edit Profile',
			'stngs'				=> 'Sittings',
			'logout'			=> 'Log Out'

		);

		return $lang[$word];
	}