<?php include('config.php'); ?>
<html>
<head>
	<?php include('head.php'); ?>
</head>
<body>
	<?php include('nav.php'); ?>
	<div class="clr wrp mrg-top-lrg">
		<?php include('brand.php'); ?>
	</div>
	<div class="clr wrp mrg-btm-lrg">
		<p><?php include('motd.php'); ?></p>
	</div>
	<div class="clr wrp">
		<table class="full mrg-btm-lrg da-create">
			<tr>
				<td>
					<a href="./create.php?t=section">Create Section</a>
				</td>
			</tr>
		</table>
		<?php
			$getSections = $con->prepare("SELECT itemID,itemName FROM items WHERE itemType=1 AND itemPerms=1");
			$getSections->execute();
			$getSections->store_result();
			if($getSections->num_rows > 0) {
				$getSections->bind_result($sectionID,$sectionName);
				while($getSections->fetch()) {
		?>
		<table class="full outline mrg-btm-lrg da-items">
			<tr class="head">
				<td>
					<p><?php echo $sectionName; ?></p>
				</td>
				<td class="fixed-100 text-right">
					<a href="./create.php?t=sub&id=<?php echo $sectionID; ?>" title="Create a Sub Section">
						<i class="fa fa-folder"></i>
					</a>
					<a href="./create.php?t=file&id=<?php echo $sectionID; ?>" title="Create a File">
						<i class="fa fa-file"></i>
					</a>
					<a href="./create.php?t=link&id=<?php echo $sectionID; ?>" title="Create a Link">
						<i class="fa fa-link"></i>
					</a>
					<a href="./edit.php?id=<?php echo $sectionID; ?>" title="Edit <?php echo $sectionName; ?>">
						<i class="fa fa-cog"></i>
					</a>
				</td>
			</tr>
		<?php
					$getSectionItems = $con->prepare("SELECT childID FROM links WHERE parentID=?");
					$getSectionItems->bind_param("i", $sectionID);
					$getSectionItems->execute();
					$getSectionItems->store_result();
					if($getSectionItems->num_rows > 0) {
						$getSectionItems->bind_result($childID);
						while($getSectionItems->fetch()) {
							$getItemDetails = $con->prepare("SELECT itemName,itemType FROM items WHERE itemID=? AND itemPerms=1 ORDER BY itemType ASC");
							$getItemDetails->bind_param("i", $childID);
							$getItemDetails->execute();
							$getItemDetails->store_result();
							if($getItemDetails->num_rows > 0) {
							$getItemDetails->bind_result($itemName,$itemType);
							while($getItemDetails->fetch()) {
		?>
			<tr>
				<td>
		<?php
				if($itemType == 2) {
		?>
					<i class="fa fa-folder"></i>
					<a href="./view.php?id=<?php echo $childID; ?>"><?php echo $itemName; ?></a>
		<?php
				} else if($itemType == 3) {
		?>
					<i class="fa fa-file"></i>
		<?php
					$getFileDetails = $con->prepare("SELECT fileDir FROM files WHERE itemID=?");
					$getFileDetails->bind_param("i", $childID);
					$getFileDetails->execute();
					$getFileDetails->store_result();
					$getFileDetails->bind_result($fileDir);
					while($getFileDetails->fetch()) {
		?>
					<a href="<?php echo $fileDir; ?>" download><?php echo $itemName; ?></a>
		<?php
					};
					$getFileDetails->close();
				} else if($itemType == 4) {
		?>
					<i class="fa fa-link"></i>
		<?php
					$getLinkDetails = $con->prepare("SELECT outboundLink FROM outbound WHERE itemID=?");
					$getLinkDetails->bind_param("i", $childID);
					$getLinkDetails->execute();
					$getLinkDetails->store_result();
					$getLinkDetails->bind_result($outboundLink);
					while($getLinkDetails->fetch()) {
		?>
					<a href="<?php echo $outboundLink; ?>" target="_blank"><?php echo $itemName; ?></a>
		<?php
					};
					$getLinkDetails->close();
				};
		?>
				</td>
				<td class="fixed-100 text-right">
		<?php
					if($itemType == 3 || $itemType == 4) {
		?>
					<a class="confirm" href="./delete.php?id=<?php echo $childID; ?>"><i class="fa fa-times"></i></a>
		<?php
					};
		?>
					<a href="./edit.php?id=<?php echo $childID; ?>"><i class="fa fa-cog"></i></a>
				</td>
			</tr>
		<?php
							};
						} else {
		?>
			<tr>
				<td colspan="2">
					<p class="alert">No Children Found</p>
				</td>
			</tr>
		<?php
						};
						};
					} else {
		?>
			<tr>
				<td colspan="2">
					<p class="alert">No Children Found</p>
				</td>
			</tr>
		<?php
					};
					$getSectionItems->close();
		?>
		</table>
		<?php
				};
			} else {
		?>
		<p class="alert">No Sections Found</p>
		<?php
			};
			$getSections->close();
		?>
		<!--
		<i class="spacer"></i>
		<table class="full outline items">
			<tr class="section">
				<td>
					<p>Internal Applications</p>
				</td>
				<td class="fixed-100 text-right">
					<a href="./"><i class="fa fa-plus"></i></a>
					<a href="./"><i class="fa fa-cog"></i></a>
				</td>
			</tr>
			<tr class="item">
				<td>
					<i class="fa fa-folder-o"></i>
					<a href="./">FDS Management</a>
				</td>
				<td class="fixed-100 text-right">
					<a href="./"><i class="fa fa-plus"></i></a>
					<a href="./"><i class="fa fa-cog"></i></a>
				</td>
			</tr>
			<tr class="item">
				<td>
					<i class="fa fa-file-o"></i>
					<a href="./">XML Notepad</a>
				</td>
				<td class="fixed-100 text-right">
					<a href="./"><i class="fa fa-cog"></i></a>
				</td>
			</tr>
			<tr class="item">
				<td>
					<i class="fa fa-link"></i>
					<a href="./">Google</a>
				</td>
				<td class="fixed-100 text-right">
					<a href="./"><i class="fa fa-cog"></i></a>
				</td>
			</tr>
		</table>
		-->
	</div>
</body>
</html>
