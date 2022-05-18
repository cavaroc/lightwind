<?php
session_start();
$incdir = $_SERVER['DOCUMENT_ROOT'].'/includes/';
require($incdir.'opening.php');

$page_title = 'Free Roaming Photography - Nature, night sky, and wildlife photography by Mike Cavaroc';
$page_desc = 'An extensive collection of nature, dark night sky, and wildlife photography across the United States, specializing in the Greater Yellowstone Ecosystem, desert southwest, and grizzly bears of Grand Teton National Park, such as Grizzly Bear 399 and Bear 610.';
require($incdir.'header.php');
?>
<body class="gallery-home">
<div id="page" class="site">
<header class="site-header">
<div class="site-branding">
<a href="https://www.freeroamingphotography.com" class="custom-logo-link" rel="home" itemprop="url"><img width="700" height="82" src="/blog/wp-content/uploads/2018/08/frp-logo.png" class="custom-logo" alt="Free Roaming Photography Logo" itemprop="logo"></a>
</div>
</header>
<?php include($incdir.'mainnav.php'); ?>
<div id="primary" class="content-area">
<main id="main" class="site-main">
<?php include($incdir.'top-nav.php'); ?>
<?php include($incdir.'home_queries.php'); ?>
<h1>Wildlife, Nature, and Dark Sky Photography, Prints, and Workshops by Mike Cavaroc</h1>
<div class="home-slideshow">
<?php 
            $favorites_query = $db->prepare("
                SELECT id, gallery, gallery_id, imgname, filepath, exifdesc, exiftitle, favorite, exifdatetaken 
                FROM photos WHERE homeslide=1 ORDER BY imgname DESC
            ");
            $favorites_query->execute();
            while ($favorites_row = $favorites_query->fetch()) {
                $photo_id[] = $favorites_row['id'];
                $total_slides = count($photo_id);
                $seo_photo = explode('.',$favorites_row['imgname']);
                $thumb_path = ROOT_PATH.'/photos/'.$file_path[0].'/'.$favorites_row['imgname'];
                list($width, $height) = getimagesize($thumb_path);
        ?>
<div class="slide fade">
<a class="slide-image" href="/<?php echo $favorites_row['filepath']; ?>/img/<?php echo $seo_photo[0]; ?>"><img src="/photos/<?php echo $favorites_row['filepath'].'/'.$favorites_row['imgname']; ?>" alt="<?php echo $favorites_row['exiftitle']; ?>" width="<?php echo $width; ?>" height="<?php echo $height; ?>"></a>
<h2 class="home-slide-text"><?php echo $favorites_row['exiftitle']; ?></h2>
</div>
<?php
            }
            ?>
<span class="slide-prev"><i class="fas fa-angle-left"></i></span>
<span class="slide-next"><i class="fas fa-angle-right"></i></span>
</div>
<hr>
<div class="button-container">
<a href="photo-tours" class="home-workshop">Book a Private Photography Tour!</a>
</div>
<hr>
<h2>Explore Themed Nature Photography Galleries</h2>
<div class="gallery-list">
<div>
<?php
                $latest_photos = $db->prepare("SELECT imgname,filepath,exiftitle FROM photos ORDER BY id DESC LIMIT 1");
                $latest_photos->execute();
                $latest_row = $latest_photos->fetchAll();
                ?>
<a class="gallery-header" href="/latest"><h4>Newest Uploads</h4></a>
<a class="gallery-image" href="/latest"><img title="Latest 50 Photos" src="https://www.freeroamingphotography.com/photos/<?php echo $latest_row[0]['filepath']; ?>/thumbnails/<?php echo $latest_row[0]['imgname']; ?>" width="" height="" alt="<?php echo $latest_row[0]['exiftitle']; ?>"></a>
</div>
<div>
<a class="gallery-header" href="/night"><h4>Night Photography</h4></a>
<a class="gallery-image" href="/night"><img title="Night Photography" src="/photos/grand-teton/thumbnails/grand-teton4649.jpg" width="" height="" alt="Night Photography"></a>
</div>
<div>
<a class="gallery-header" href="/favorites"><h4>Artist's Favorites</h4></a>
<a class="gallery-image" href="/favorites"><img title="Artist's Favorites" src="/photos/canyonlands/thumbnails/canyonlands1268.jpg" width="" height="" alt="Artist's Favorites"></a>
</div>
<div>
<a class="gallery-header" href="/black-and-white"><h4>Black and White</h4></a>
<a class="gallery-image" href="/black-and-white"><img title="Black and White" src="/photos/capitol-reef/thumbnails/capitol-reef1341.jpg" width="" height="" alt="Black and White Photography"></a>
</div>
<div>
<a class="gallery-header" href="/grizzly-bear-399"><h4>Grizzly Bear #399 and Family</h4></a>
<a class="gallery-image" href="/grizzly-bear-399"><img title="Grizzly Bear 399 Family" src="/photos/grand-teton/thumbnails/grand-teton3564.jpg" width="" height="" alt="Grizzly Bear 399 and Family"></a>
</div>
<div>
<a class="gallery-header" href="/arizona-trail"><h4>All Arizona Trail Images</h4></a>
<a class="gallery-image" href="/arizona-trail"><img title="Arizona Trail Images" src="/photos/northern-kaibab-plateau/thumbnails/northern-kaibab-plateau6691.jpg" width="" height="" alt="800 miles of Arizona Trail Images"></a>
</div>
</div>
<?php include($incdir.'sharing.php'); ?>
<hr>
<?php $total_photos = count($imgInfoArray); ?>
<p>There are currently <?php echo number_format($total_photos); ?> photos in <?php echo $gal_count; ?> galleries on this website.</p>
</main>
</div>
<?php require($incdir.'footer.php'); ?>