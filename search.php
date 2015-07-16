<?php include('config.php'); ?>
<html>
<head>
	<?php include('head.php'); ?>
	<script>
		$(document).ready(function() {
			var searchInput = "input[name=search]";

			$(searchInput).keyup(function() {
				searchInput = $(this).val();
				liveSearch();
			});

			function liveSearch() {
				$.ajax({
					method: "POST",
					url: "auto_search.php",
					data: { input: searchInput }
				})
				.done(function(html) {
					$('table.search').html('<tr class="head">'
						+'<td colspan="2"><p>Search Results</p></td>'
						+'</tr>'
						+html
					);
				});
			};
		});
	</script>
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
		<form class="mrg-btm-med" method="post">
			<input name="search" type="text" placeholder="Search..." autofocus autocomplete="off" required />
		</form>
		<table class="search full fixed outline">
			<tr class="head">
				<td colspan="2"><p>Search Results</p></td>
			</tr>
			<tr>
				<td colspan="2">
					<p class="alert">Search Something...</p>
				</td>
			</tr>
		</table>
	</div>
</body>
</html>
