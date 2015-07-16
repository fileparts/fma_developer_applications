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
		<?php
			if(!isset($_GET['a'])) {
		?>
		<p class="alert">An Action is Required, Redirecting...</p>
		<?php
				redirect("./");
			} else {
				$action = $_GET['a'];
				$okay = false;

				if($action == "create") {
					if(!isset($_POST['formType'])) {
		?>
		<p class="alert">A Type is Required, Redirecting...</p>
		<?php
						redirect("./");
					} else {
						$type = $_POST['formType'];

						if($type == "section") {
							$formType = 1;
							$formName = $_POST['formName'];

							$createSection = $con->prepare("INSERT INTO items(itemName,itemType) VALUES(?,?)");
							$createSection->bind_param("si", $formName,$formType);
							if($createSection->execute()) {
		?>
		<p class="alert">Section Created, Redirecting...</p>
		<?php
								redirect("./");
							} else {
		?>
		<p class="alert">Execution Error: Section Creation, Redirecting...</p>
		<?php
								redirect("./");
							};
							$createSection->close();
						} else if($type == "sub") {
							$formType = 2;
							$formParent = $_POST['formParent'];
							$formName = $_POST['formName'];

							$createSub = $con->prepare("INSERT INTO items(itemName,itemType) VALUES(?,?)");
							$createSub->bind_param("si", $formName,$formType);
							if($createSub->execute()) {
								$itemID = $createSub->insert_id;
								$okay = true;
							} else {
		?>
		<p class="alert">Execution Error: Sub Section Creation, Redirecting...</p>
		<?php
								redirect("./create.php?t=sub&id=$formParent");
							};
							$createSub->close();

							if($okay == true) {
								$createLink = $con->prepare("INSERT INTO links(childID,parentID) VALUES(?,?)");
								$createLink->bind_param("ii", $itemID,$formParent);
								if($createLink->execute()) {
		?>
		<p class="alert">Sub Section Created, Redirecting...</p>
		<?php
									redirect("./view.php?id=$formParent");
								} else {
									$okay = false;
								};
								$createLink->close();
							};

							if($okay == false) {
								$errorDelete = $con->prepare("DELETE FROM items WHERE itemID=?");
								$errorDelete->bind_param("i", $itemID);
								if($errorDelete->execute()) {
		?>
		<p class="alert">Execution Error: Link Creation, Redirecting...</p>
		<?php
									redirect("./create.php?t=sub&id=$formParent");
								} else {
		?>
		<p class="alert">Database Error: Check Item <?php echo $itemID; ?>!</p>
		<?php
								};
								$errorDelete->close();
							};
						} else if($type == "file") {
							$formType = 3;
							$formParent = $_POST['formParent'];
							$formName = $_POST['formName'];
							$formFile = preg_replace("/[^A-Z0-9._-]/i", "_", $_FILES['formFile']['name']);

							$uploadto = './uploads/';
							$fileto = $uploadto. $formFile;

							if(file_exists($fileto)) {
		?>
		<p class="alert">File Already Exists, Redirecting...</p>
		<?php
								redirect("./create.php?t=file&id=$formParent");
							} else {
								if(move_uploaded_file($_FILES['formFile']['tmp_name'], $fileto)) {
									$createItem = $con->prepare("INSERT INTO items(itemName,itemType) VALUES(?,?)");
									$createItem->bind_param("si", $formName,$formType);
									if($createItem->execute()) {
										$itemID = $createItem->insert_id;
										$okay = true;
									} else {
										unlink($fileto);
									};
									$createItem->close();

									if($okay = true) {
										$createLink = $con->prepare("INSERT INTO links(childID,parentID) VALUES(?,?)");
										$createLink->bind_param("ii", $itemID,$formParent);
										if($createLink->execute()) {
											$okay = true;
										} else {
											$okay = false;
											unlink($fileto);
										};
										$createLink->close();
									};

									if($okay == true) {
										$createFile = $con->prepare("INSERT INTO files(itemID,fileDir) VALUES(?,?)");
										$createFile->bind_param("is", $itemID,$fileto);
										if($createFile->execute()) {
		?>
		<p class="alert">File Created, Redirecting...</p>
		<?php
											redirect("./view.php?id=$formParent");
										} else {
											$okay = false;
											unlink($fileto);
										};
										$createFile->close();
									};

									if($okay == false) {
										$errorDelete = $con->prepare("DELETE FROM items WHERE itemID=?");
										$errorDelete->bind_param("i", $itemID);
										if($errorDelete->execute()) {
		?>
		<p class="alert">Execution Error: Link Creation, Redirecting...</p>
		<?php
									redirect("./create.php?t=file&id=$formParent");
								} else {
		?>
		<p class="alert">Database Error: Check Item <?php echo $itemID; ?>!</p>
		<?php
										};
										$errorDelete->close();
									};

								} else {
		?>
		<p class="alert">Failed to Upload File, Redirecting...</p>
		<?php
									redirect("./create.php?t=file&id=$formParent");
								};
							};
						} else if($type == "link") {
							$formType = 4;
							$formParent = $_POST['formParent'];
							$formName = $_POST['formName'];
							$formURL = $_POST['formURL'];

							if(strpos($formURL, "http") === FALSE) {
								$formURL = 'http://' .$formURL;
							};

							$createItem = $con->prepare("INSERT INTO items(itemName,itemType) VALUES(?,?)");
							$createItem->bind_param("si", $formName,$formType);
							if($createItem->execute()) {
								$itemID = $createItem->insert_id;
								$okay = true;
							} else {
		?>
		<p class="alert">Execution Error: Link Creation, Redirecting...</p>
		<?php
								redirect("./create.php?t=link&id=$formParent");
							};

							if($okay == true) {
								$createLink = $con->prepare("INSERT INTO links(childID,parentID) VALUES(?,?)");
								$createLink->bind_param("ii", $itemID,$formParent);
								if($createLink->execute()) {
									$okay = true;
								} else {
									$okay = false;
								};
								$createLink->close();
							};

							if($okay == true) {
								$createOutbound = $con->prepare("INSERT INTO outbound(itemID,outboundLink) VALUES(?,?)");
								$createOutbound->bind_param("is", $itemID,$formURL);
								if($createOutbound->execute()) {
		?>
		<p class="alert">Link Created, Redirecting...</p>
		<?php
									redirect("./view.php?id=$formParent");
								} else {
									$okay = false;
								};
								$createOutbound->close();
							};

							if($okay == false) {
								$errorDelete = $con->prepare("DELETE FROM items WHERE itemID=?");
								$errorDelete->bind_param("i", $itemID);
								if($errorDelete->execute()) {
		?>
		<p class="alert">Execution Error: Link Creation, Redirecting...</p>
		<?php
									redirect("./create.php?t=link&id=$formParent");
								} else {
		?>
		<p class="alert">Database Error: Check Item <?php echo $itemID; ?>!</p>
		<?php
								};
								$errorDelete->close();
							};

						} else {
		?>
		<p class="alert">Invalid Type, Redirecting...</p>
		<?php
							redirect("./");
						};
					};
				} else if($action == "edit") {
					if(!isset($_POST['formType'])) {
		?>
		<p class="alert">A Type is Required, Redirecting...</p>
		<?php
							redirect("./");
					} else {
						$type = $_POST['formType'];

						if($type == "section") {
							$formID = $_POST['formID'];
							$formName = $_POST['formName'];
							$formPerms = $_POST['formPerms'];

							$editSection = $con->prepare("UPDATE items SET itemName=?,itemPerms=? WHERE itemID=?");
							$editSection->bind_param("sii", $formName,$formPerms,$formID);
							if($editSection->execute()) {
		?>
		<p class="alert">Section Updated, Redirecting...</p>
		<?php
								redirect("./");
							} else {
		?>
		<p class="alert">Execution Error: Section Update, Redirecting...</p>
		<?php
								redirect("./edit.php?id=$formID");
							};
							$editSection->close();
						} else if($type == "sub") {
							$formID = $_POST['formID'];
							$formName = $_POST['formName'];
							$formPerms = $_POST['formPerms'];

							$editSection = $con->prepare("UPDATE items SET itemName=?,itemPerms=? WHERE itemID=?");
							$editSection->bind_param("sii", $formName,$formPerms,$formID);
							if($editSection->execute()) {
		?>
		<p class="alert">Sub Section Updated, Redirecting...</p>
		<?php
								redirect("./");
							} else {
		?>
		<p class="alert">Execution Error: Sub Section Update, Redirecting...</p>
		<?php
								redirect("./edit.php?id=$formID");
							};
							$editSection->close();
						} else if($type == "file") {
							$formID = $_POST['formID'];
							$formName = $_POST['formName'];
							$formPerms = $_POST['formPerms'];
							$formFile = preg_replace("/[^A-Z0-9._-]/i", "_", $_FILES['formFile']['name']);

							$uploadto = './uploads/';
							$fileto = $uploadto. $formFile;

							if(!is_uploaded_file($_FILES['formFile']['tmp_name'])) {
								$editItem = $con->prepare("UPDATE items SET itemName=?,itemPerms=? WHERE itemID=?");
								$editItem->bind_param("sii", $formName,$formPerms,$formID);
								if($editItem->execute()) {
		?>
		<p class="alert">File Updated, Redirecting...</p>
		<?php
									redirect("./");
								} else {
		?>
		<p class="alert">Execution Error: File Update, Redirecting...</p>
		<?php
									redirect("./edit.php?id=$formID");
								};
								$editItem->close();
							} else {
								if(file_exists($fileto)) {
		?>
		<p class="alert">File Already Exists, Redirecting...</p>
		<?php
									redirect("./edit.php?id=$formID");
								} else {
									if(move_uploaded_file($_FILES['formFile']['tmp_name'], $fileto)) {
										$getOldFile = $con->prepare("SELECT fileDir FROM files WHERE itemID=?");
										$getOldFile->bind_param("i", $formID);
										$getOldFile->execute();
										$getOldFile->bind_result($fileDir);
										while($getOldFile->fetch()) {
											if (!unlink($fileDir)) {
												echo ("<p>Old File was not Deleted</p>");
											};
										};

										$editItem = $con->prepare("UPDATE items SET itemName=?,itemPerms=? WHERE itemID=?");
										$editItem->bind_param("sii", $formName,$formPerms,$formID);
										if($editItem->execute()) {
											$okay = true;
										} else {
		?>
		<p class="alert">Execution Error: File Update, Redirecting...</p>
		<?php
											redirect("./edit.php?id=$formID");
										};
										$editItem->close();

										if($okay == true) {
											$editFile = $con->prepare("UPDATE files SET fileDir=? WHERE itemID=?");
											$editFile->bind_param("si", $fileto,$formID);
											if($editFile->execute()) {
		?>
		<p class="alert">File Location Updated, Redirecting...</p>
		<?php
											redirect("./");
											} else {
		?>
		<p class="alert">Execution Error: File Location Update, Redirecting...</p>
		<?php
											redirect("./edit.php?id=$formID");
											};
											$editFile->close();
										};
									} else {
		?>
		<p class="alert">Failed To Upload File, Redirecting...</p>
		<?php
									redirect("./edit.php?id=$formID");
									};
								};
							};
						} else if($type == "link") {
							$formID = $_POST['formID'];
							$formName = $_POST['formName'];
							$formPerms = $_POST['formPerms'];
							$formURL = $_POST['formURL'];

							if(strpos($formURL, "http") === FALSE) {
								$formURL = 'http://' .$formURL;
							};

							$editItem = $con->prepare("UPDATE items SET itemName=?,itemPerms=? WHERE itemID=?");
							$editItem->bind_param("sii", $formName,$formPerms,$formID);
							if($editItem->execute()) {
								$okay = true;
							} else {
		?>
		<p class="alert">Execution Error: Link Update, Redirecting...</p>
		<?php
								redirect("./edit.php?id=$formID");
							};
							$editItem->close();

							if($okay == true) {
								$editLink = $con->prepare("UPDATE outbound SET outboundLink=? WHERE itemID=?");
								$editLink->bind_param("si", $formURL,$formID);
								if($editLink->execute()) {
		?>
		<p class="alert">Link Updated, Redirecting...</p>
		<?php
									redirect("./");
								} else {
		?>
		<p class="alert">Execution Error: Link Update, Redirecting...</p>
		<?php
									redirect("./edit.php?id=$formID");
								};
								$editLink->close();
							};
						} else {
		?>
		<p class="alert">Invalid Type, Redirecting...</p>
		<?php
							redirect("./");
						};
					};
				} else {
		?>
		<p class="alert">Invalid Action, Redirecting...</p>
		<?php
					redirect("./");
				};
			};
		?>
	</div>
</body>
</html>
