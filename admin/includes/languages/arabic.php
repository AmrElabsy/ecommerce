<?php 

	function lang($word)
	{
		static $lang = array(
			# NavBar

			'eCommerce'			=> 'متجر عمرو العبسي',
			'Dashboard'			=> 'الإدارة',
			'Ctgrs'				=> 'التحكم',
			'members'			=> 'الأعضاء',
			'admindrop'			=> 'لوحة التحكم',
            'items'             => 'المنتجات',
			'Stats'				=> 'إحصائيات الموقع',
			'membersmanage'		=> 'إدارة الأعضاء',
			'profile'			=> 'الملف الشخصي',
			'stngs'				=> 'الإعدادات',
			'logout'			=> 'تسجيل الخروج'

		);

		return $lang[$word];
	}