<?php
session_start();
$incdir = $_SERVER['DOCUMENT_ROOT'].'/includes/';
require($incdir.'header.php');
setlocale(LC_MONETARY, 'en_US.UTF-8');
// include($incdir.'img_queries.php');

?>

<body>

<header class="clearFix">

	<?php include($incdir.'mainnav.php'); ?>

</header> <!-- #header -->

<section id="mainContainer"><section id="watermarkContainer">
	<div id="pageContent" class="clearFix">
<form action="/your-server-side-code" method="POST">
  <script
    src="https://checkout.stripe.com/checkout.js" class="stripe-button"
    data-key="pk_iBaExLRHgPgTKgkZyLufuQdTv9nSr"
    data-amount="999"
    data-name="Free Roaming Photography"
    data-description="Widget"
    data-image="https://s3.amazonaws.com/stripe-uploads/uKBcaJM9SWUFX4yrTEZOzo7IwPZsFeDRmerchant-icon-1449117652916-frp-logo.gif"
    data-locale="auto">
  </script>
</form>
	</div> <!-- #pageContent -->
	
<script src="//code.jquery.com/jquery-2.0.2.min.js"></script>

<?php require($incdir.'footer.php'); ?>
