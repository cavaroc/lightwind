<?php
session_start();
$incdir = $_SERVER['DOCUMENT_ROOT'].'/includes/';
require($incdir.'opening.php');

if (isset($_GET['i_id'])) {
    $photo_get = htmlspecialchars($_GET['i_id']);
    $photo_get .= '.jpg';
    
    $get_photos = $db->prepare("SELECT 
        id,gallery,gallery_id,imgname,filepath,exifdatetaken,exifdesc,exiftitle,exifkeywords,exifcamera,ontandem,tandemid,pricing
        FROM photos WHERE imgname=:imgname ORDER BY exifdatetaken DESC");
    $get_photo_array = array(':imgname' => $photo_get);
    $get_photos->execute($get_photo_array);
    while ($photo_row = $get_photos->fetch()) {
        $photo_id[] = $photo_row['id'];
        $gallery_name[] = $photo_row['gallery'];
        $gallery_id[] = $photo_row['gallery_id'];
        $image_name[] = $photo_row['imgname'];
        $file_path[] = $photo_row['filepath'];
        $date_taken[] = $photo_row['exifdatetaken'];
        $photo_desc[] = $photo_row['exifdesc'];
        $photo_title[] = $photo_row['exiftitle'];
        $photo_keywords[] = explode(',',$photo_row['exifkeywords']);
        $camera_model[] = $photo_row['exifcamera'];
        $on_tandem[] = $photo_row['ontandem'];
        $tandem_id[] = $photo_row['tandemid'];
        $pricing[] = $photo_row['pricing'];
    }
    
    $get_gallery = $db->prepare("SELECT gallerydesc,album_id,lat,lng FROM galleries WHERE gallery_id=:galleryid");
    $get_gallery_array = array(':galleryid' => $gallery_id[0]);
    $get_gallery->execute($get_gallery_array);
    while ($gallery_row = $get_gallery->fetch()) {
        $gallery_desc[] = $gallery_row['gallerydesc'];
    }
    
    $page_title = $photo_title[0] . ' - ' . $gallery_name[0];
    $page_desc = $photo_desc[0];
}

$addedFlag = 0;
				
if (isset($_GET['printid'])) { 

    $buyImgName = $_GET['i_id'].'.jpg';
	$printSize = $_GET['printid'];
	$gname = $_GET['g_id'];
    
    if(isset($_SESSION['cart'][$buyImgName][$printSize])) { 
        $_SESSION['cart'][$buyImgName][$printSize]['quantity']++;
		$addedFlag = 1;
    } else { 
        $imgAdd=$db->prepare("SELECT id FROM photos WHERE imgname=:imgname"); 
        $imgAdd->execute(array(':imgname' => $buyImgName));
		$imgBuy = $imgAdd->fetchAll();
		$numRows = count($imgBuy);
        $priceQ=$db->prepare("SELECT prices FROM print_sizes WHERE sizes=:sizes");
		$priceQ->execute(array(':sizes' => $printSize));
		$cost = $priceQ->fetch();
        if($numRows != 0) {
			$_SESSION['cart'][$buyImgName][$printSize]=array( 
                "quantity" => 1, 
                "price" => $cost['prices'],
				"size" => $printSize,
				"image" => $buyImgName
            );
			$addedFlag = 1;
        } else {
            echo $message="This product id is invalid!"; 
        }
    }
}

$getPricing = $db->prepare("SELECT sizes,prices FROM print_sizes WHERE format=:format");
$getPricing->execute(array(':format' => $pricing[0]));
$prices = $getPricing->fetchAll();


require($incdir.'header.php');
?>

<body class="photo-page">

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
            
        <?php if(!$themed) {
            echo '<a href="/'.$file_path[0].'">&lt; Return to '.$gallery_name[0].'</a>';
        }
        ?>
		<h1><?php echo $photo_title[0]; ?></h1>

        <?php            
        $get_img_nav = $db->prepare("
        (SELECT id,imgname,filepath,exiftitle FROM photos
        WHERE gallery_id=:gal_id1 AND id < :img_id1 
        ORDER BY id DESC LIMIT 1)
        UNION
        (SELECT id,imgname,filepath,exiftitle FROM photos
        WHERE gallery_id=:gal_id2 AND id > :img_id2 
        ORDER BY id LIMIT 1)
        ");
            
	    $get_img_nav_array = array(
            ':gal_id1' => $gallery_id[0],
            ':img_id1' => $photo_id[0],
            ':gal_id2' => $gallery_id[0],
            ':img_id2' => $photo_id[0]
        );
	    $get_img_nav->execute($get_img_nav_array);
	    $img_nav_row = $get_img_nav->fetchAll();
            
		$prev_id 		= $img_nav_row[1]['id'];
        $prev_name 		= $img_nav_row[1]['imgname'];
		$prev_title		= $img_nav_row[1]['exiftitle'];
		$seo_url_prev	= explode('.',$prev_name);
			
		$next_id 		= $img_nav_row[0]['id'];
		$next_name 		= $img_nav_row[0]['imgname'];
		$next_title		= $img_nav_row[0]['exiftitle'];
		$seo_url_next	= explode('.',$next_name);
            
        if(empty($prev_id)) {
            $get_prev_fix = $db->prepare("
                SELECT id,imgname FROM photos 
                WHERE gallery_id=:gal_id1 AND id > :img_id1 
                ORDER BY id LIMIT 1");
            $get_prev_array = array(
                ':gal_id1' => $gallery_id[0],
                ':img_id1' => $photo_id[0]
            );
            $get_prev_fix->execute($get_prev_array);
            $prev_fix = $get_prev_fix->fetchAll();
            $prev_id        = $prev_fix[0]['id'];
            $prev_name 		= $prev_fix[0]['imgname'];
            $seo_url_prev	= explode('.',$prev_name);
        }
            
        $thumb_path = ROOT_PATH.'/photos/'.$file_path[0].'/'.$image_name[0];
        list($width, $height) = getimagesize($thumb_path);
                       
		?>
            
        <div class="photo-display">
            <div class="photo-prev">
                <?php if (!empty($prev_id)) { ?>
                    <a href="/<?php echo $file_path[0]; ?>/img/<?php echo $seo_url_prev[0]; ?>"><i class="fas fa-angle-left" aria-hidden="true"></i></a>
                <?php } ?>
            </div>
            <div class="photo-image">
                <img src="https://www.freeroamingphotography.com/photos/<?php echo $file_path[0]; ?>/<?php echo $photo_get; ?>"
                    width="<?php echo $width; ?>" height="<?php echo $height; ?>" alt="<?php echo $photo_title[0]; ?>">
            </div>
            <div class="photo-next">
                <?php if (!empty($next_id) && !($next_id>$photo_id[0])) { ?>
                    <a href="/<?php echo $file_path[0]; ?>/img/<?php echo $seo_url_next[0]; ?>"><i class="fas fa-angle-right" aria-hidden="true"></i></a>
                <?php } ?>
            </div>
        </div><!-- .photo-display -->
     
        <div class="photo-buttons">
            <div>
                <?php
                if ($on_tandem[0] == 1) {
                    echo '<a class="btn" href="https://tandemstock.com/assets/'.$tandem_id[0].'" target="_blank"><img src="/graphics/tandem.png" alt="Tandem Stills and Motion">';
                    echo ' License on Tandem</a>';
                } else {
                    echo '<a class="btn" data-popup-open="popup-1" href="#">License Image</a>';
                }
                ?>
                <div class="popup" data-popup="popup-1">
                    <div class="popup-inner">
                        <div class="license-info">
                            <h3>Licensing Information</h3>
                            <img src="<?php echo '/photos/'.$file_path[0].'/thumbs/'.$photo_get; ?>" class="alignright" alt="<?php echo $img_title[0]; ?>">
                            <p>All photos are available on a rights-managed basis. This means that they can only be used once for the agreed upon terms.
                                Additional usage will require a new agreement.</p>
                            <p>Please note that some images are available under an exclusive license via Tandem Stills &amp; Motion. Any photos
                                currently under contract with them will link directly to the image on their site.</p>
                            <p>To license this image for stock photo use,
                                please <a href="mailto:mike@freeroamingphotography.com" target="_blank">email Mike here</a>. To maximize time, please
                                have expected usage information ready, such as whether the image will be online or in print, audience exposure, etc.</p>
                            <p>Thank you!</p>
                            <p><a data-popup-close="popup-1" href="#">Close</a></p>
                        </div>
                        <a class="popup-close" data-popup-close="popup-1" href="#">x</a>
                    </div>
                </div>
            </div>
            <div>
                <a data-popup-open="popup-2" class="btn" href="#">Buy a Print</a>

                <div class="popup" data-popup="popup-2">
                    <div class="popup-inner">
                        <div class="print-info">
                            <h3>Why Buy from Mike?</h3>
                            <p>Everything's printed on fine art luster papers, guaranteed to last over 100 years.</p>
                            <p>All prints comes signed by the artist.</p>
                            <p>Immediate attention for any customer service needs.</p>
                            <p style="border-bottom: 1px solid #ddd; padding-bottom: 10px; margin-bottom: 10px;">Quality control directly from the artist.</p>
                            <p>Please note that due to the differences in monitor calibration, the final product's color could appear slightly different.</p>
                            <p>To order a size not listed or a different print surface, such as canvas or metal, 
                                please <a href="mailto:mike@freeroamingphotography.com" target="_blank">email the photographer</a>.</p>
                            <p>All prints come shipped via the United States Postal Service.</p>
                        </div>
                        <table class="print-display">
                            <tr>
                                <th>Size</th>
                                <th>Price</th>
                                <th>Add to Cart</th>
                            </tr>
                            <?php
                            $print_id = explode('.',$image_name[0]);
                            foreach ($prices as $key => $value) {
                                echo '<tr>';
                                echo '<td>'.$value['sizes'].'</td>';
                                echo '<td>$'.$value['prices'].'</td>';
                                echo '<td><a rel="nofollow" href="/img.php?g_id='.$file_path[0].'&i_id='.$print_id[0].'&printid='.$value['sizes'].'">Buy</a></td>';
                                echo '</tr>';
                            }
                            ?>
                            <?php if($pricing[0] == 'panorama') { ?>
                            <tr>
                                <td colspan="3">
                                    <small>Panoramic prints can vary in height, but are always over twice as long on the long edge as the shorter edge. For this reason, pricing is determined by the print's 
                                        long edge, with the short edge adjusting accordingly.</small>
                                </td>
                            </tr>
                            <?php } ?>

                        </table>

                        <p><a data-popup-close="popup-2" href="#">Close</a></p>
                        <a class="popup-close" data-popup-close="popup-2" href="#">x</a>
                        
                    </div><!-- .popup-inner -->
                </div><!-- .popup -->
            </div>
        </div><!-- .photo-buttons -->
            
        <div class="photo-info">
            <div class="photo-desc">
                <h2><?php echo $photo_desc[0]; ?></h2>
            </div>
            <div class="photo-meta">
                <ul>
                    <li>Date taken: <strong><?php echo date('F d, Y', strtotime($date_taken[0])); ?></strong></li>
                    <li>Camera model: <strong><?php echo $camera_model[0]; ?></strong></li>
                    <li>Image name: <strong><?php echo $photo_get; ?></strong></li>
                </ul>
            </div>
            <div class="photo-keywords">
                <h3>Photo Tags</h3>
                <ul>
                    <?php include($incdir.'img_keywords.php'); ?>
                </ul>
            </div>
        </div>

        <?php include($incdir.'sharing.php'); ?>

        <hr>
            
        <h3>More Photos in the <a href="/<?php echo $file_path[0]; ?>"><?php echo $gallery_name[0]; ?></a> gallery</h3>
		<div class="photos-more-row">
			<?php include($incdir.'more_gallery_photos.php'); ?>
		</div>
		
        </main>
    </div> <!-- .content-area -->

<?php require($incdir.'footer.php'); ?>
