<?php include('config.php'); ?>
<html>
<head>
	<?php include('head.php'); ?>
</head>
<body>
	<?php include('nav.php'); ?>
	<div class="clear wrp margin-top-lrg">
		<?php include('brand.php'); ?>
	</div>
	<div class="clear wrp margin-btm-lrg">
		<p><?php include('motd.php'); ?></p>
	</div>
	<div class="clear wrp">
		<?php
			if(!isset($_GET['id'])) {
		?>
		<p class="alert">An ID is Required, Redirecting...</p>
		<?php
				redirect("./");
			} else {
				$itemID = $_GET['id'];
				
				$getItemType = $con->prepare("SELECT itemType FROM items WHERE itemID=?");
				$getItemType->bind_param("i", $itemID);
				$getItemType->execute();
				$getItemType->bind_result($itemType);
				while($getItemType->fetch()) {
					$itemType = $itemType;
				};
				
				if($itemType == 3 || $itemType == 4) {
					$deleteChain = $con->prepare("DELETE FROM links WHERE childID=?");
					$deleteChain->bind_param("i", $itemID);
					if($deleteChain->execute()) {
						if($itemType == 3) {
							$getFile = $con->prepare("SELECT fileDir FROM files WHERE itemID=?");
							$getFile->bind_param("i", $itemID);
							$getFile->execute();
							$getFile->store_result();
							$getFile->bind_result($fileDir);
							while($getFile->fetch()) {
								unlink($fileDir);
							};
							$getFile->close();
							
							$deleteFile = $con->prepare("DELETE FROM files WHERE itemID=?");
							$deleteFile->bind_param("i", $itemID);
							$deleteFile->execute();
							$deleteFile->close();
							
							$deleteItem = $con->prepare("DELETE FROM items WHERE itemID=?");
							$deleteItem->bind_param("i", $itemID);
							if($deleteItem->execute()) {
		?>
		<p class="alert">Deleted File, Redirecting...</p>
		<?php
								redirect("./");
							} else {
		?>
		<p class="alert">Execution Error: Delete File, Redirecting...</p>
		<?php
								redirect("./");
							};
							$deleteItem->close();
						};
						if($itemType == 4) {
							$deleteLink = $con->prepare("DELETE FROM outbound WHERE itemID=?");
							$deleteLink->bind_param("i", $itemID);
							$deleteLink->execute();
							$deleteLink->close();
							
							$deleteItem = $con->prepare("DELETE FROM items WHERE itemID=?");
							$deleteItem->bind_param("i", $itemID);
							if($deleteItem->execute()) {
		?>
		<p class="alert">Deleted Link, Redirecting...</p>
		<?php
								redirect("./");
							} else {
		?>
		<p class="alert">Execution Error: Delete Link, Redirecting...</p>
		<?php
								redirect("./");
							};
							$deleteItem->close();
						};
					} else {
		?>
		<p class="alert">Execution Error: Delete Item Links, Redirecting...</p>
		<?php
						redirect("./");
					};
					$deleteChain->close();
				} else {
		?>
		<p class="alert">Invalid Item Type. Toggle (sub) Sections to be hidden, Redirecting...</p>
		<?php
					redirect("./edit.php?id=$itemID");
				};
			};
		?>
	</div>
</body>
</html>