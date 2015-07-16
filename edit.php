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
				$getItemType->store_result();
				if($getItemType->num_rows < 0) {
		?>
		<p class="alert">Invalid ID, Redirecting...</p>
		<?php
					redirect("./");
				} else {
					$getItemType->bind_result($itemType);
					while($getItemType->fetch()) {
						$itemType = $itemType;
					};
				};
				$getItemType->close();

				if($itemType === 1) {
					$getSectionDetails = $con->prepare("SELECT itemName,itemPerms FROM items WHERE itemID=?");
					$getSectionDetails->bind_param("i", $itemID);
					$getSectionDetails->execute();
					$getSectionDetails->store_result();
					$getSectionDetails->bind_result($itemName,$itemPerms);
					while($getSectionDetails->fetch()) {
						$itemName = $itemName;
						$itemPerms = $itemPerms;
					};
					$getSectionDetails->close();
		?>
		<form method="post" action="./action.php?a=edit">
			<input name="formType" type="hidden" value="section" required />
			<input name="formID" type="hidden" value="<?php echo $itemID; ?>" required />
			<table class="fixed">
				<tr>
					<td>
						<p>Edit Section Name</p>
					</td>
					<td>
						<input name="formName" type="text" value="<?php echo $itemName; ?>" autofocus autocomplete="off" required />
					</td>
				</tr>
				<tr>
					<td>
						<p>Edit Visiblity</p>
					</td>
					<td>
						<select name="formPerms" required>
							<option disabled>Select an Option</option>
		<?php
					if($itemPerms == 1) {
		?>
							<option value="1" selected>Visible</option>
		<?php
					} else {
		?>
							<option value="1">Visible</option>
		<?php
					};
					if($itemPerms == 2) {
		?>
							<option value="0" selected>Not Visible</option>
		<?php
					} else {
		?>
							<option value="0">Not Visible</option>
		<?php
					};
		?>
						</select>
					</td>
				</tr>
				<tr>
					<td></td>
					<td>
						<button class="confirm btn-warning" type="submit">Submit</button>
					</td>
				</tr>
			</table>
		</form>
		<?php
				} else if($itemType == 2) {
					$getSubDetails = $con->prepare("SELECT itemName,itemPerms FROM items WHERE itemID=?");
					$getSubDetails->bind_param("i", $itemID);
					$getSubDetails->execute();
					$getSubDetails->store_result();
					$getSubDetails->bind_result($itemName,$itemPerms);
					while($getSubDetails->fetch()) {
						$itemName = $itemName;
						$itemPerms = $itemPerms;
					};
					$getSubDetails->close();
		?>
		<form method="post" action="./action.php?a=edit">
			<input name="formType" type="hidden" value="sub" required />
			<input name="formID" type="hidden" value="<?php echo $itemID; ?>" required />
			<table class="fixed">
				<tr>
					<td>
						<p>Edit Sub Section Name</p>
					</td>
					<td>
						<input name="formName" type="text" value="<?php echo $itemName; ?>" autofocus autocomplete="off" required />
					</td>
				</tr>
				<tr>
					<td>
						<p>Edit Visiblity</p>
					</td>
					<td>
						<select name="formPerms" required>
							<option disabled>Select an Option</option>
		<?php
					if($itemPerms == 1) {
		?>
							<option value="1" selected>Visible</option>
		<?php
					} else {
		?>
							<option value="1">Visible</option>
		<?php
					};
					if($itemPerms == 2) {
		?>
							<option value="0" selected>Not Visible</option>
		<?php
					} else {
		?>
							<option value="0">Not Visible</option>
		<?php
					};
		?>
						</select>
					</td>
				</tr>
				<tr>
					<td></td>
					<td>
						<button class="confirm btn-warning" type="submit">Submit</button>
					</td>
				</tr>
			</table>
		</form>
		<?php
				} else if($itemType == 3) {
					$getItemDetails = $con->prepare("SELECT itemName,itemPerms FROM items WHERE itemID=?");
					$getItemDetails->bind_param("i", $itemID);
					$getItemDetails->execute();
					$getItemDetails->store_result();
					$getItemDetails->bind_result($itemName,$itemPerms);
					while($getItemDetails->fetch()) {
						$itemName = $itemName;
						$itemPerms = $itemPerms;
					};
					$getItemDetails->close();
		?>
		<form method="post" action="./action.php?a=edit" enctype="multipart/form-data">
			<input name="formType" type="hidden" value="file" required />
			<input name="formID" type="hidden" value="<?php echo $itemID; ?>" required />
			<table class="fixed">
				<tr>
					<td>
						<p>Edit File Name *</p>
					</td>
					<td>
						<input name="formName" type="text" value="<?php echo $itemName; ?>" autofocus autocomplete="off" required />
					</td>
				</tr>
				<tr>
					<td>
						<p>Edit Visiblity *</p>
					</td>
					<td>
						<select name="formPerms" required>
							<option disabled>Select an Option</option>
		<?php
					if($itemPerms == 1) {
		?>
							<option value="1" selected>Visible</option>
		<?php
					} else {
		?>
							<option value="1">Visible</option>
		<?php
					};
					if($itemPerms == 2) {
		?>
							<option value="0" selected>Not Visible</option>
		<?php
					} else {
		?>
							<option value="0">Not Visible</option>
		<?php
					};
		?>
						</select>
					</td>
				</tr>
				<tr>
					<td>
						<p>Edit File</p>
					</td>
					<td>
						<input name="formFile" type="file" />
					</td>
				</tr>
				<tr>
					<td></td>
					<td>
						<button class="confirm btn-warning" type="submit">Submit</button>
					</td>
				</tr>
				<tr>
					<td></td>
					<td>
						<p>Options with a * are <b>Required</b></p>
					</td>
				</tr>
			</table>
		</form>
		<?php
				} else if($itemType == 4) {
					$getItemDetails = $con->prepare("SELECT itemName,itemPerms FROM items WHERE itemID=?");
					$getItemDetails->bind_param("i", $itemID);
					$getItemDetails->execute();
					$getItemDetails->store_result();
					$getItemDetails->bind_result($itemName,$itemPerms);
					while($getItemDetails->fetch()) {
						$itemName = $itemName;
						$itemPerms = $itemPerms;
					};
					$getItemDetails->close();

					$getLinkDetails = $con->prepare("SELECT outboundLink FROM outbound WHERE itemID=?");
					$getLinkDetails->bind_param("i", $itemID);
					$getLinkDetails->execute();
					$getLinkDetails->store_result();
					$getLinkDetails->bind_result($outboundLink);
					while($getLinkDetails->fetch()) {
						$outboundLink = $outboundLink;
					};
					$getLinkDetails->close();
		?>
		<form method="post" action="./action.php?a=edit">
			<input name="formType" type="hidden" value="link" required />
			<input name="formID" type="hidden" value="<?php echo $itemID; ?>" required />
			<table class="fixed">
				<tr>
					<td>
						<p>Edit Link Name</p>
					</td>
					<td>
						<input name="formName" type="text" value="<?php echo $itemName; ?>" autofocus autocomplete="off" required />
					</td>
				</tr>
				<tr>
					<td>
						<p>Edit Visiblity</p>
					</td>
					<td>
						<select name="formPerms" required>
							<option disabled>Select an Option</option>
		<?php
					if($itemPerms == 1) {
		?>
							<option value="1" selected>Visible</option>
		<?php
					} else {
		?>
							<option value="1">Visible</option>
		<?php
					};
					if($itemPerms == 2) {
		?>
							<option value="0" selected>Not Visible</option>
		<?php
					} else {
		?>
							<option value="0">Not Visible</option>
		<?php
					};
		?>
						</select>
					</td>
				</tr>
				<tr>
					<td>
						<p>Edit URL</p>
					</td>
					<td>
						<input name="formURL" type="text" value="<?php echo $outboundLink; ?>" autocomplete="off" required />
					</td>
				</tr>
				<tr>
					<td></td>
					<td>
						<button class="confirm btn-warning" type="submit">Submit</button>
					</td>
				</tr>
			</table>
		</form>
		<?php
				} else {
		?>
		<p class="alert">Invalid Item Type, Redirecting...</p>
		<?php
					redirect("./");
				};
			};
		?>
		<div class="clr"><a class="da-back" href="javascript:history.go(-1)">Back</a></div>
	</div>
</body>
</html>
