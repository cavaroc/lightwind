<?php
session_start();
require_once('cart_config.php');

$incdir = $_SERVER['DOCUMENT_ROOT'].'/includes/';
require($incdir.'opening.php');
require($incdir.'header.php');
setlocale(LC_MONETARY, 'en_US.UTF-8');

$token			= $_POST['stripeToken'];
$amount 		= $_POST['amount'];
$shipName		= $_POST['ship_name'];
$shipAddress 	= $_POST['ship_address'];
$shipCity		= $_POST['ship_city'];
$shipState		= $_POST['ship_state'];
$shipZip		= $_POST['ship_zip'];
$billName		= $_POST['bill_name'];
$billAddress 	= $_POST['bill_address'];
$billCity		= $_POST['bill_city'];
$billState		= $_POST['bill_state'];
$billZip		= $_POST['bill_zip'];

$stripeinfo = \Stripe\Token::retrieve($token);
	$email = $stripeinfo->email;
	
$customer = \Stripe\Customer::create(array(
    'email' => $_POST["stripeEmail"],
    'source'  => $token
));

$charge = \Stripe\Charge::create(array(
    'customer'	=> $customer->id,
    'amount'	=> $amount,
    'currency'	=> 'usd'
));
$amount = $amount / 100;
?>


<body>

<div id="page" class="site">
    <header class="site-header">
        <div class="site-branding">
            <a href="https://www.freeroamingphotography.com" class="custom-logo-link" rel="home" itemprop="url"><img width="700" height="82" src="https://www.freeroamingphotography.com/blog/wp-content/uploads/2018/08/frp-logo.png" class="custom-logo" alt="Free Roaming Photography Logo" itemprop="logo"></a>
        </div>
    </header> <!-- #header -->

    <?php include($incdir.'mainnav.php'); ?>

    <div id="primary" class="content-area">
        <main id="main" class="site-main">

		<h1 class="greenText">Successfully charged <?php echo money_format("%.2n", $amount); ?>!</h1>


		<h2>Thank you for your order, <?php $firstName = explode(' ',$shipName); echo $firstName[0]; ?>!</h2>
		<p>You should receive an email confirmation within 15 minutes. If you don't, please contact Mike at
			<a href="mailto:mike@freeroamingphotography.com" target="_blank">Mike@FreeRoamingPhotography.com</a>.</p>
		<p>Your order is now in processing and will be completed as soon as possible. Typical delivery times vary from 1-2 weeks. This is to ensure
			quality control and proper handling.</p>
		<p>Your order details are listed below:</p>
		
    <table class="cartTable">
        <tr> 
	        <th>Image</th>
			<th>Image Name</th>
			<th>Size</th>
            <th class="textRight">Price Each</th> 
            <th class="textRight">Full Price</th>
        </tr> 
          
        <?php 
		$idCount = 0;
		foreach ($_SESSION['cart'] as $key => $value) {
			foreach ($value as $subkey => $subvalue) {
				echo '<tr>';
			
				$thumb_get = $db->prepare("SELECT filepath,exiftitle FROM photos WHERE imgname=:imgname");
				$thumb_get_array = array(':imgname' => $key);
				$thumb_get->execute($thumb_get_array);
				$thumbnail = $thumb_get->fetch();
			?>
            <td><img src="/photos/<?php echo $thumbnail['filepath']; ?>/thumbs/<?php echo $key; ?>" 
				alt="<?php echo $thumbnail['exiftitle']; ?>" width=200></td>
			<td><?php echo $key; ?></td>
			<td><?php echo $subkey; ?></td>
			<td class="textRight"><?php echo money_format("%.2n", $subvalue['price']); ?></td>
			<td class="textRight"><?php echo money_format("%.2n", $subvalue['price']*$subvalue['quantity']); ?></td>
		
				<?php
				echo '</tr>';
		$updateOrder = $db->prepare("INSERT INTO orders
			(email,filename,size,totalprice,shipping_name,shipping_address,shipping_city,shipping_state,shipping_zip,
				billing_name,billing_address,billing_city,billing_state,billing_zip,order_date) VALUES 
			(:email,:filename,:size,:totalprice,:shipname,:shipaddress,:shipcity,:shipstate,:shipzip,:billname,:billaddress,:billcity,:billstate,:billzip,now())");
		$updateArray = array(
			':email' => $email,
			':filename' => $key,
			':size' => $subkey,
			':totalprice' => $subvalue['price']*$subvalue['quantity'],
			':shipname' => $shipName,
			':shipaddress' => $shipAddress,
			':shipcity' => $shipCity,
			':shipstate' => $shipState,
			':shipzip' => $shipZip,
			':billname' => $billName,
			':billaddress' => $billAddress,
			':billcity' => $billCity,
			':billstate' => $billState,
			':billzip' => $billZip
			);
		$updateOrder->execute($updateArray);

		} 
		$idCount++;
		}

		$lastId = $db->prepare("SELECT id FROM orders ORDER BY id DESC LIMIT 1");
		$lastId->execute();
		$lastIdRow = $lastId->fetchAll();
		//var_dump($lastIdRow);
		?>
		<tr> 
            <td colspan="3"></td>
			<td>Total Charged:</td>
			<td class="textRight"><?php echo money_format("%.2n", $amount); ?></td> 
        </tr>
    </table>
		
	<p>Looking for an adventure? <a href="https://freeroaminghiker.com" target="_blank">Check out Free Roaming Hiker</a> to explore all kinds of hikes!</p>
	
	</main></div>
<?php
$custSubject = "Free Roaming Photography Order Confirmation";

$custMessage = '
<html>
<head>
<title>Free Roaming Photography Order Confirmation</title>
</head>
<body>
<table width="100%">
<tr>
<th>
<a href="https://www.freeroamingphotography.com" target="_blank"><img src="https://www.freeroamingphotography.com/graphics/free-roaming.png"
	alt="Free Roaming Photography"></a>
</th>
</tr>
<tr><td>
<p>Thank you for your order, '.$firstName[0].'!</p>
<p>This email confirms your order for '.money_format("%.2n", $amount).' is now in processing. The details of your order can be reviewed on your 
	<a href="https://www.freeroamingphotography.com/orderdetails.php?ordernum='.$lastIdRow[0]['id'].'&ids='.$idCount.'">order details page here</a>.</p>
<p>If you have any questions, please feel free to contact me at 
<a href="mailto:mike@freeroamingphotography.com">Mike@FreeRoamingPhotography.com</a>.</p>
<p>Also, please allow 1-2 weeks for delivery. This is to allow for quality control and proper handling.</p>
<p>Thank you!</p>
</td></tr>
</table>
</body>
</html>
';

// Always set content-type when sending HTML email
$headers = "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

// More headers
$headers .= 'From: <Mike@FreeRoamingPhotography.com>' . "\r\n";
$emails = 'mike@freeroamingphotography.com, '.$email;

mail($emails,$custSubject,$custMessage,$headers);

unset($_SESSION['cart']);
require($incdir.'footer.php');
?>