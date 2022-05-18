<?php
$incdir = $_SERVER['DOCUMENT_ROOT'].'/includes/';
require($incdir.'header.php');
setlocale(LC_MONETARY, 'en_US.UTF-8');
require($incdir.'header.php');

if(isset($_GET['ordernum'])) {
	$orderId = intval($_GET['ordernum']);
	$numIds = intval($_GET['ids']);
} else {
	echo 'Sorry - your info can\'t be retrieved right now. If it keeps happening, please contact 
		<a href="mailto:mike@freeroamingphotography.com">Mike@FreeRoamingPhotography.com</a>.';
}
?>

<body>

<header class="clearFix">

	<?php include($incdir.'mainnav.php'); ?>

</header> <!-- #header -->

<section id="subheader" class="clearFix">

	<?php include($incdir.'subheader.php'); ?>

</section> <!-- #subheader -->

<section id="mainContainer"><section id="watermarkContainer">
	<div id="pageContent" class="clearFix">
		<?php
		$orderQuery = $db->prepare("SELECT id,filename,size,totalprice,shipping_name,shipping_address,shipping_city,shipping_state,shipping_zip,order_date
			FROM orders	WHERE id = :the_id LIMIT :ids");
		$orderArray = array(':the_id' => $orderId, ':ids' => $numIds);
		$orderQuery->execute($orderArray);
		
		while ($row = $orderQuery->fetch()) {
			$shipName 	= $row['shipping_name'];
			$fileName	= $row['filename'];
			$size		= $row['size'];
			$price		= $row['totalprice'];
			$shipAddress= $row['shipping_address'];
			$shipCity	= $row['shipping_city'];
			$shipState	= $row['shipping_state'];
			$shipZip	= $row['shipping_zip'];
			$orderDate	= $row['order_date'];
			?>

		<h1>Order Details for <?php $firstName = explode(' ',$shipName); echo $firstName[0]; ?></h1>
		<h3>Your order was placed on <?php echo $orderDate; ?> and is set to be delivered to:</h3>
		<p class="orderDetails">
		<?php
		echo '<strong>'.$shipName.'</strong><br>';
		echo $shipAddress.'<br>';
		echo $shipCity.' '.$shipState.', '.$shipZip.'<br>';
		?></p>
		
    <table class="cartTable">
        <tr> 
	        <th>Image</th>
			<th>Image Name</th>
			<th>Size</th>
        </tr> 
          
        <tr>
			<?php
			$thumbGet = $db->prepare("SELECT filepath,exiftitle FROM photos WHERE imgname=:imgname");
			$thumbGet->execute(array(':imgname' => $fileName));
			$thumbnail = $thumbGet->fetch();
			?>
            <td><img src="/photos/<?php echo $thumbnail['filepath']; ?>/thumbs/<?php echo $fileName; ?>" 
				alt="<?php echo $thumbnail['exiftitle']; ?>" width=200></td>
			<td><?php echo $fileName; ?></td>
			<td><?php echo $size; ?></td>
		</tr>
		<tr>
			<td class="textRight" colspan="2">Total Charged:</td>
			<td><?php echo money_format("%.2n", ($price+5)); ?></td> 
        </tr>
    </table>
		<?php } ?>
		
	<p>Want something to do? <a href="/index.php">Head to the galleries</a> to browse some more!</p>
	
	</div>
<?php require($incdir.'footer.php'); ?>