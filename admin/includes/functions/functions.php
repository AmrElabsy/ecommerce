<?php

	function getTitle()
	{
		global $titleName;

		if (isset($titleName))
		{
			echo $titleName;
		}
		else
		{
			echo 'Default';
		}
	}

	function isActive($pageName)
	{
		global $isActive;

		if ($isActive == $pageName)
		{
		echo 'class="active"';
		}
	}


	function reDirect($url = "index.php", $time = 3)
	{
		echo "<div class='alert alert-info'>You will be Redirected in " . $time . " Seconds</div>";

		header("refresh:$time; url=$url");
		exit();
	}


	function isExist($select /*Item*/, $from /*Table*/, $value, $tableIDKey, $id = 0)
	{
		global $con;
		$stmt = $con->prepare("SELECT $select FROM $from WHERE $select = ? AND $tableIDKey != ?");
		$stmt->execute(array($value, $id));

		$count = $stmt->rowCount();

		if ($count >= 1)
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}

	function countItems($item, $table, $query = null)
	{
		global $con;

		$where = "";
		
		if ($query != null)
		{
			$where = "WHERE";
		}


		$stmt = $con->prepare("SELECT count($item) FROM $table $where $query");
		$stmt->execute();
		$count = $stmt->fetchColumn();

		return $count;
	}

	function latest($table, $number = 5)
	{
		global $con;

		$tbl = $table;
		$num = $number;
		$stmt = $con->prepare("SELECT * FROM $tbl ORDER BY date DESC LIMIT $num");
		$stmt->execute();

		$rows = $stmt->fetchALL();
		return $rows;
	}

	function catgVis($value)
	{
		if ($value == 1)
		{
			return "Visible";
		}
		else
		{
			return "Not Visible";
		}
	}

	function catgCom($value)
	{
		if ($value == 1)
		{
			return "Commentable";
		}
		else
		{
			return "Not Commentable";
		}
	}

	function catgAds($value)
	{
		if ($value == 1)
		{
			return "Allow Advetisements";
		}
		else
		{
			return "Don't Allow Advertisement";
		}
	}

	function selectIt($toSelect, $value, $select = 'selected')
	{
		if ($toSelect == $value)
		{
			echo "$select";
		}
	}