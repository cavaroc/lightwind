<?php
session_start();
$incdir = $_SERVER['DOCUMENT_ROOT'].'/includes/';
require($incdir.'opening.php');

$pageTitle = 'Shopping Cart - Free Roaming Photography';
$pageDesc = 'A shopping cart system for ordering photographic prints of fine art nature photography by Mike Cavaroc of Free Roaming Photography';

require($incdir.'header.php');

if(isset($_GET['remove'])) {
	$removeKey = $_GET['remove'];
	$removeSize = $_GET['xsize'];
	$_SESSION['cart'][$removeKey][$removeSize]['quantity']--;
	if ($_SESSION['cart'][$removeKey][$removeSize]['quantity'] < 1) {
		unset($_SESSION['cart'][$removeKey][$removeSize]);
	}
	header("Location: thecart.php");
}
?>

<body class="cart-page">

<div id="page" class="site">
    <header class="site-header">
        <div class="site-branding">
            <a href="https://www.freeroamingphotography.com" class="custom-logo-link" rel="home" itemprop="url"><img width="700" height="82" src="https://www.freeroamingphotography.com/blog/wp-content/uploads/2018/08/frp-logo.png" class="custom-logo" alt="Free Roaming Photography Logo" itemprop="logo"></a>
        </div>
    </header> <!-- #header -->

    <?php include($incdir.'mainnav.php'); ?>
    
    <?php include($incdir.'home_queries.php'); ?>

	<div id="primary" class="content-area">
        <main id="main" class="site-main">
            
        <?php include($incdir.'top-nav.php'); ?>


<h1>View cart</h1>

<form method="get" action="thecart.php">
    <table class="cart-table">
        <tr> 
			<th>Remove</th>
            <th>Image</th>
			<th>Image Name</th>
			<th>Size</th>
            <th class="textRight">Price Each</th> 
            <th class="textRight">Full Price</th>
        </tr> 
          
        <?php 
		$totalPrice = 0;
		foreach ($_SESSION['cart'] as $key => $value) {
			foreach ($value as $subkey => $subvalue) {
                ?>
                <tr>
                    <td><a href="thecart.php?remove=<?php echo $key; ?>&xsize=<?php echo $subkey; ?>"><i class="red-text fas fa-minus-square" aria-hidden="true"></i></a></td>
                    <?php
                    $thumbGet = $db->prepare("SELECT filepath,exiftitle FROM photos WHERE imgname=:imgname");
                    $thumbGet->execute(array(':imgname' => $key));
                    $thumbnail = $thumbGet->fetch();
                    ?>
                    <td><img src="https://www.freeroamingphotography.com/photos/<?php echo $thumbnail['filepath']; ?>/thumbs/<?php echo $key; ?>" alt="<?php echo $thumbnail['exiftitle']; ?>" width=200></td>
                    <td><?php echo $key; ?></td>
                    <td><?php echo $subkey; ?></td>
                    <td><?php echo money_format("%.2n", $subvalue['price']); ?></td>
                    <td><?php echo '$'.money_format("%.2n", $subvalue['price']*$subvalue['quantity']); $totalPrice += $subvalue['price']*$subvalue['quantity']; ?></td>
                </tr>
        <?php } }
        if ($totalPrice != 0) { ?>
            <tr>
                <td colspan="4"></td>
                <td>Shipping:</td>
                <td class="textRight">$5.00</td>
            </tr>
            <?php $totalPrice += 5; ?>
            <tr> 
                <td colspan="4"></td>
                <td>Total Price:</td>
                <td class="textRight"><?php echo '$'.money_format("%.2n", $totalPrice); ?></td> 
            </tr>
            <?php } else { unset($_SESSION['cart']); ?>
            <tr> 
                <td colspan="6">
                <p>Your cart is currently empty. Browse the <a href="/index.php">Galleries</a> to find something to add!</p>
                </td>
            </tr>
		<?php } ?>
    </table>
</form>
<?php $totalPrice *= 100; ?>

<?php require_once('cart_config.php'); ?>

<form action="charge.php" method="post">
<div id="cart-form">
		<div class="cartFormInfo" id="shipForm">
			<h2>Shipping Information</h2>
			<input type="text" name="ship_name" placeholder="Name"><br>
			<input type="text" name="ship_address" placeholder="Address"><br>
			<input type="text" name="ship_city" placeholder="City"><br>
            <select name="ship_state">
            <option value="AL">Alabama</option>
            <option value="AK">Alaska</option>
            <option value="AZ">Arizona</option>
            <option value="AR">Arkansas</option>
            <option value="CA">California</option>
            <option value="CO">Colorado</option>
            <option value="CT">Connecticut</option>
            <option value="DE">Delaware</option>
            <option value="DC">District Of Columbia</option>
            <option value="FL">Florida</option>
            <option value="GA">Georgia</option>
            <option value="HI">Hawaii</option>
            <option value="ID">Idaho</option>
            <option value="IL">Illinois</option>
            <option value="IN">Indiana</option>
            <option value="IA">Iowa</option>
            <option value="KS">Kansas</option>
            <option value="KY">Kentucky</option>
            <option value="LA">Louisiana</option>
            <option value="ME">Maine</option>
            <option value="MD">Maryland</option>
            <option value="MA">Massachusetts</option>
            <option value="MI">Michigan</option>
            <option value="MN">Minnesota</option>
            <option value="MS">Mississippi</option>
            <option value="MO">Missouri</option>
            <option value="MT">Montana</option>
            <option value="NE">Nebraska</option>
            <option value="NV">Nevada</option>
            <option value="NH">New Hampshire</option>
            <option value="NJ">New Jersey</option>
            <option value="NM">New Mexico</option>
            <option value="NY">New York</option>
            <option value="NC">North Carolina</option>
            <option value="ND">North Dakota</option>
            <option value="OH">Ohio</option>
            <option value="OK">Oklahoma</option>
            <option value="OR">Oregon</option>
            <option value="PA">Pennsylvania</option>
            <option value="RI">Rhode Island</option>
            <option value="SC">South Carolina</option>
            <option value="SD">South Dakota</option>
            <option value="TN">Tennessee</option>
            <option value="TX">Texas</option>
            <option value="UT">Utah</option>
            <option value="VT">Vermont</option>
            <option value="VA">Virginia</option>
            <option value="WA">Washington</option>
            <option value="WV">West Virginia</option>
            <option value="WI">Wisconsin</option>
            <option value="WY">Wyoming</option>
            </select>	
            <input type="text" class="zip" name="ship_zip" placeholder="Zip">
	
			<p><input type="checkbox"  name="billinginput" id="billinginput"> Billing Address is different than Shipping</p>
		</div> <!-- .cartFormInfo -->
		<div class="cartFormInfo" id="billform">
			<h2>Billing Information</h2>
			<input type="text" name="bill_name" placeholder="Name"><br>
			<input type="text" name="bill_address" placeholder="Address"><br>
			<input type="text" name="bill_city" placeholder="City"><br>
			<div>
				<select name="bill_state">
				<option value="AL">Alabama</option>
				<option value="AK">Alaska</option>
				<option value="AZ">Arizona</option>
				<option value="AR">Arkansas</option>
				<option value="CA">California</option>
				<option value="CO">Colorado</option>
				<option value="CT">Connecticut</option>
				<option value="DE">Delaware</option>
				<option value="DC">District Of Columbia</option>
				<option value="FL">Florida</option>
				<option value="GA">Georgia</option>
				<option value="HI">Hawaii</option>
				<option value="ID">Idaho</option>
				<option value="IL">Illinois</option>
				<option value="IN">Indiana</option>
				<option value="IA">Iowa</option>
				<option value="KS">Kansas</option>
				<option value="KY">Kentucky</option>
				<option value="LA">Louisiana</option>
				<option value="ME">Maine</option>
				<option value="MD">Maryland</option>
				<option value="MA">Massachusetts</option>
				<option value="MI">Michigan</option>
				<option value="MN">Minnesota</option>
				<option value="MS">Mississippi</option>
				<option value="MO">Missouri</option>
				<option value="MT">Montana</option>
				<option value="NE">Nebraska</option>
				<option value="NV">Nevada</option>
				<option value="NH">New Hampshire</option>
				<option value="NJ">New Jersey</option>
				<option value="NM">New Mexico</option>
				<option value="NY">New York</option>
				<option value="NC">North Carolina</option>
				<option value="ND">North Dakota</option>
				<option value="OH">Ohio</option>
				<option value="OK">Oklahoma</option>
				<option value="OR">Oregon</option>
				<option value="PA">Pennsylvania</option>
				<option value="RI">Rhode Island</option>
				<option value="SC">South Carolina</option>
				<option value="SD">South Dakota</option>
				<option value="TN">Tennessee</option>
				<option value="TX">Texas</option>
				<option value="UT">Utah</option>
				<option value="VT">Vermont</option>
				<option value="VA">Virginia</option>
				<option value="WA">Washington</option>
				<option value="WV">West Virginia</option>
				<option value="WI">Wisconsin</option>
				<option value="WY">Wyoming</option>
				</select>
				<input type="text" class="zip" name="bill_zip" placeholder="Zip"><br>
			</div>
		</div> <!-- .cartFormInfo -->
        <div id="cartButton">
            <script src="https://checkout.stripe.com/checkout.js" class="stripe-button"
                data-key="<?php echo $stripe['publishable_key']; ?>"
                data-description="Print Order"
                data-amount="<?php echo $totalPrice; ?>"
                data-image="https://s3.amazonaws.com/stripe-uploads/uKBcaJM9SWUFX4yrTEZOzo7IwPZsFeDRmerchant-icon-1449117652916-frp-logo.gif"
                data-name="Free Roaming Photography"
                data-locale="auto">
            </script>
            <input type="hidden" name="amount" value="<?php echo $totalPrice; ?>">
        </div> <!-- #cartButton -->
    </div> <!-- #cartForm -->
</form>	
        </main>
	</div> <!-- #pageContent -->
	
<script src="//code.jquery.com/jquery-2.0.2.min.js"></script>
<script type="text/javascript">
$('#billform').hide();

$('#billinginput').click(function(){
    $('#billform').fadeToggle(200);
});
</script>

<?php require($incdir.'footer.php'); ?>
