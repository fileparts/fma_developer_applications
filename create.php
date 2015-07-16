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
			if(!isset($_GET['t'])) {
		?>
		<p class="alert">A Creation Type is Required, Redirecting...</p>
		<?php
				redirect("./");
			} else {
				$type = $_GET['t'];
				if($type == "section") {
		?>
		<form method="post" action="./action.php?a=create">
			<input name="formType" type="hidden" value="section" required />
			<table class="fixed">
				<tr>
					<td>
						<p>Enter Section Name</p>
					</td>
					<td>
						<input name="formName" type="text" placeholder="Enter Section Name" autofocus autocomplete="off" required />
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
					if($type == "sub" || $type == "file" || $type == "link") {
						if(!isset($_GET['id'])) {
		?>
		<p class="alert">A Parent ID is Required, Redirecting...</p>
		<?php
							redirect("./");
						} else {
							$parentID = $_GET['id'];

							$getParent = $con->prepare("SELECT itemName FROM items WHERE itemID=? AND itemPerms=1");
							$getParent->bind_param("i", $parentID);
							$getParent->execute();
							$getParent->store_result();
							if($getParent->num_rows > 0) {
								$getParent->bind_result($parentName);
								while($getParent->fetch()) {
									$parentName = $parentName;
								};
							} else {
		?>
		<p class="alert">Invalid Parent ID, Redirecting...</p>
		<?php
								redirect("./");
							};

							if(isset($parentName)) {
								if($type == "sub") {
		?>
		<form method="post" action="./action.php?a=create">
			<input name="formType" type="hidden" value="sub" required />
			<input name="formParent" type="hidden" value="<?php echo $parentID; ?>" required />
			<table class="fixed">
				<tr>
					<td>
						<p>Enter Sub Section Name</p>
					</td>
					<td>
						<input name="formName" type="text" placeholder="Enter Sub Section Name" autofocus autocomplete="off" required />
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
								} else if($type == "file") {
		?>
		<form method="post" action="./action.php?a=create" enctype="multipart/form-data">
			<input name="formType" type="hidden" value="file" required />
			<input name="formParent" type="hidden" value="<?php echo $parentID; ?>" required />
			<table class="fixed">
				<tr>
					<td>
						<p>Enter File Name</p>
					</td>
					<td>
						<input name="formName" type="text" placeholder="Enter File Name" autofocus autocomplete="off" required />
					</td>
				</tr>
				<tr>
					<td>
						<p>Select a File</p>
					</td>
					<td>
						<input name="formFile" type="file" placeholder="Select a File" required />
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
								} else if($type == "link") {
		?>
		<form method="post" action="./action.php?a=create">
			<input name="formType" type="hidden" value="link" required />
			<input name="formParent" type="hidden" value="<?php echo $parentID; ?>" required />
			<table class="fixed">
				<tr>
					<td>
						<p>Enter Link Name</p>
					</td>
					<td>
						<input name="formName" type="text" placeholder="Enter Link Name" autofocus autocomplete="off" required />
					</td>
				</tr>
				<tr>
					<td>
						<p>Enter a URL</p>
					</td>
					<td>
						<input name="formURL" type="text" placeholder="Enter a URL" required />
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
								};
							};
						};
					} else {
		?>
		<p class="alert">Invalid Creation Type, Redirecting...</p>
		<?php
						redirect("./");
					};
				};
			};
		?>
		<div class="clr"><a class="da-back" href="javascript:history.go(-1)">Back</a></div>
	</div>
</body>
</html>
