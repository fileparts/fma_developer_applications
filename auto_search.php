<?php
  include("./config.php");

  $needle = $_POST['input'];

  if(strlen($needle) > 0) {
    $find = $con->prepare("SELECT itemID,itemName,itemType FROM items WHERE itemName LIKE '%$needle%' AND (itemType=3 OR itemType=4)");
    $find->execute();
    $find->store_result();
    if($find->num_rows > 0) {
      $find->bind_result($itemID,$itemName,$itemType);
      while($find->fetch()) {
?>
<script>console.log("<?php echo $itemName. ' ' .$needle; ?>");</script>
<tr>
<td>
<?php
if($itemType == 2) {
?>
  <i class="fa fa-folder"></i>
  <a href="./view.php?id=<?php echo $itemID; ?>"><?php echo $itemName; ?></a>
<?php
} else if($itemType == 3) {
?>
  <i class="fa fa-file"></i>
<?php
  $getFileDetails = $con->prepare("SELECT fileDir FROM files WHERE itemID=?");
  $getFileDetails->bind_param("i", $itemID);
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
  $getLinkDetails->bind_param("i", $itemID);
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
  <a class="confirm" href="./delete.php?id=<?php echo $itemID; ?>"><i class="fa fa-times"></i></a>
<?php
  };
?>
  <a href="./edit.php?id=<?php echo $itemID; ?>"><i class="fa fa-cog"></i></a>
</td>
</tr>
<?php
      };
    } else {
  ?>
  <tr>
    <td colspan="2"><p class="alert">Nothing Found...</p></td>
  </tr>
  <?php
    };
  } else {
  ?>
  <tr>
    <td colspan="2"><p class="alert">Search Something...</p></td>
  </tr>
  <?php
  };
?>
