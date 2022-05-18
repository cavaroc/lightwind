<?php
session_start();
$incdir = $_SERVER['DOCUMENT_ROOT'].'/includes/';
require($incdir.'opening.php');

if (isset($_GET['a_id'])) {
    $album_id = htmlspecialchars($_GET['a_id']);
    $get_album_name = $db->prepare("SELECT album_id,albumname,albumdesc FROM albums WHERE album_id=:albumid");
    $get_name_array = array(':albumid' => $album_id);
    $get_album_name->execute($get_name_array);
    while ($album_name_row = $get_album_name->fetch()) {
        $album_name[] = $album_name_row['albumname'];
        $album_desc[] = $album_name_row['albumdesc'];
    }
    $get_album = $db->prepare("SELECT gallery_id,galleryname,galleryurlname,album_id,featured_pic FROM galleries WHERE
        album_id=:albumid ORDER BY galleryname ASC");
    $get_array = array(':albumid' => $album_id);
    $get_album->execute($get_array);
    while ($album_row = $get_album->fetch()) {
        $gallery_id[] = $album_row['gallery_id'];
        $gallery_name[] = $album_row['galleryname'];
        $gallery_url_name[] = $album_row['galleryurlname'];
        $featured_pic[] = $album_row['featured_pic'];
    }
    $page_title = $album_name[0];
    $page_desc = $album_desc[0];
}
require($incdir.'header.php');
?>

<body class="album-page">

<div id="page" class="site">
    <header class="site-header">
        <div class="site-branding">
            <a href="https://www.freeroamingphotography.com" class="custom-logo-link" rel="home" itemprop="url"><img width="700" height="82" src="https://www.freeroamingphotography.com/blog/wp-content/uploads/2018/08/frp-logo.png" class="custom-logo" alt="Free Roaming Photography Logo" itemprop="logo"></a>
        </div>
    </header> <!-- #header -->

    <?php include($incdir.'mainnav.php'); ?>
   	
    <div id="primary" class="content-area">
        <main id="main" class="site-main">
	
            <?php include($incdir.'top-nav.php'); ?>
            
            <h1><?php echo $album_name[0]; ?> Photography Collection</h1>

            <hr>

            <div class="gallery-list">
            <?php 
            $x = 0;
            foreach ($gallery_id as $gallery) {
                echo '<div>';
                echo '<a class="gallery-header" href="/'.$gallery_url_name[$x].'"><h4>'.$gallery_name[$x].'</h4></a>';
                echo '<a class="gallery-image" href="/'.$gallery_url_name[$x].'"><img 
                    src="https://www.freeroamingphotography.com/photos/'.$gallery_url_name[$x].'/thumbnails/'.$featured_pic[$x].'"
                    width="" height="" alt="'.$gallery_name[$x].'"></a>';
                echo '</div>';
                $x++;
            }
            ?>
            </div><!-- .gallery-list -->
            
            <hr>

            <h2 class="album-desc-title"><?php echo $album_name[0]; ?> Information</h2>
            <p class="album-desc"><?php echo $album_desc[0]; ?></p>
            <p class="item-count">This collection contains <?php echo $x; ?> galleries.</p>

            <?php include($incdir.'sharing.php'); ?>

		</main>
	</div> <!-- .content-area -->

<?php require($incdir.'footer.php'); ?>
