<?php
session_start();
$incdir = $_SERVER['DOCUMENT_ROOT'].'/includes/';
require($incdir.'opening.php');

if (isset($_GET['g_id'])) {
    $gallery_get = htmlspecialchars($_GET['g_id']);
    
    switch ($gallery_get) {
        case 'night':
            $get_photos = $db->prepare("
                SELECT id,gallery,gallery_id,imgname,filepath,exifdatetaken,exiftitle,exiffilename FROM photos 
                WHERE exifkeywords LIKE :thetag ORDER BY exifdatetaken DESC");
            $get_photos_array = array(':thetag' => '%night%');
            $themed = 1;
            $gallery_exists = 1;
            $page_title = 'Dark Sky and Night Photography - Free Roaming Photography';
            $page_desc = 'A photography gallery of dark night sky photography, as well as moonlit photos, northern lights photography, and photos of the Milky Way Galaxy.';
            $themed_gallery_name = 'Dark Sky and Night';
            $themed_gallery_desc = "The photos in this gallery are the photos that have stood out to me above all the others and have also stood the test of time. While I come away with a lot of photos that I like enough to put on this website, only a handful of those are shots that I would call my favorite. Photos such as the black wolf in Yellowstone's Lamar Valley, a mustang in Wyoming's desert at sunset, or even just a peaceful sunrise at Delta Lake that I had to myself all mark a style of photography that I strive to capture. A wildness that's shrinking, but still accessible for those willing to look. Likewise, I also have a number of black and white photos whose composition speaks more loudly than color. A clearing storm over the Teton Mountains in black and white for example can make for a powerful subject, or even the northern lights over the Grand Teton. All these and more make up the world as I like to see it.";
            break;
        case 'favorites':
            $get_photos = $db->prepare("
                SELECT id,gallery,gallery_id,imgname,filepath,exifdatetaken,exiftitle,exiffilename FROM photos 
                WHERE favorite = 1 ORDER BY exifdatetaken DESC");
            $themed = 1;
            $gallery_exists = 1;
            $page_title = 'Favorite Photos by Mike Cavaroc - Free Roaming Photography';
            $page_desc = 'A photography gallery of nature photos that are the favorite of the photographer, Mike Cavaroc. Each has a different or unique quality that presents a particular artistic vision.';
            $themed_gallery_name = 'Artist\'s Favorites';
            $themed_gallery_desc = "Each photo in this gallery is one of my favorite photos out of the tens of thousands I have taken. Whether it's a particular composition, a play on color, or a story that's told within the photo, each photo here resonates with something I successfully wanted to convey. Many of these photos have stood the test of time and have aged well regardless of the original equipment they may have been shot on.";
            break;
        case 'black-and-white':
            $get_photos = $db->prepare("
                SELECT id,gallery,gallery_id,imgname,filepath,exifdatetaken,exiftitle,exiffilename FROM photos 
                WHERE exifkeywords LIKE :thetag ORDER BY exifdatetaken DESC");
            $get_photos_array = array(':thetag' => '%black and white%');
            $themed = 1;
            $gallery_exists = 1;
            $page_title = 'Black and White Photography - Free Roaming Photography';
            $page_desc = 'A collection of beautiful and dramatic black and white nature photography taken by Mike Cavaroc of Free Roaming Photography.';
            $themed_gallery_name = 'Black and White';
            $themed_gallery_desc = "Some images simply speak beyond the color that so frequently encompasses a photo. In this case, whether through a carefully crafted composition, or through a remarkable simplicity in a scene, eliminating the color in favor of a monochrome photo can speak more powerfully than the color that was present at the scene. Black and white photos of nature provide a powerful statement on frequently explored areas and terrain. The right composition can bring out a completely different vantage point, or can capture an expression on wildlife where color would otherwise distract from. The images contained in this gallery were carefully chosen and meticulously processed to eximplify fine art black and white photography.";
            break;
        case 'grizzly-bear-399':
            $get_photos = $db->prepare("
                SELECT id,gallery,gallery_id,imgname,filepath,exifdatetaken,exiftitle,exiffilename FROM photos 
                WHERE exifkeywords LIKE :thetag1 OR exifkeywords LIKE :thetag2 OR exifkeywords LIKE :thetag3 ORDER BY exifdatetaken DESC");
            $get_photos_array = array(':thetag1' => '%399%',':thetag2' => '%610%', ':thetag3' => '%760%');
            $themed = 1;
            $gallery_exists = 1;
            $page_title = 'Grizzly Bear #399 and Family - Free Roaming Photography';
            $page_desc = 'An expansive collection of beautiful images featuring the world famous, Grizzly Bear #399, as well as her descendants and family, including Grizzly Bears #610, along with the cubs born to each.';
            $themed_gallery_name = 'Grizzly Bear #399 and Family';
            $themed_gallery_desc = "Grizzly Bear #399 rose to fame between 2006 and 2008 when she emerged with her first (known) set of cubs. The triplets and Bear 399 captured the hearts of locals and the few tourists visiting the area at the time. Bear 399 kept her cubs relatively close to the roadsides to help keep them safe from male bears who would otherwise be anxious to put the females back into heat to mate with them. One of the cubs would go on to also win the hearts of both locals and tourists. Grizzly Bear #610 took after her mother and in the following years, frequented the same areas as her mother, such as Oxbow Bend, Willow Flats, and the Pilgrim Creek area. Within just a few short years, April and May in Grand Teton National Park went from being virtually emtpy to a bear watching season where crowds would gather to watch these gorgeous grizzly bears exhibit natural behavior literally just off of the roadsides. Sadly, many of Bear 399's cubs have suffered tragic early deaths, whether from poachers, vehicle collisions, or the Game and Fish Department. Several cubs of the year to date have been struck by cars, other offspring such as Bear #615 were shot by hunters who were uneducated about bear behavior as well as poachers who simply don't appreciate the value of a grizzly bear. Questions still remain as to why the Game and Fish Department put down Grizzly Bear #760, another of 399's offspring. Regardless, the grizzlies of Grand Teton National Park have proven to be a huge economic force to the region, and no one can deny that.";
            break;
	/*
        case 'panoramas':
            $get_photos = $db->prepare("
                SELECT id,gallery,gallery_id,imgname,filepath,exifdatetaken,exiftitle,exiffilename FROM photos 
                WHERE exifkeywords LIKE :thetag ORDER BY exifdatetaken DESC");
            $get_photos_array = array(':thetag' => '%, panorama,%');
            $themed = 1;
            $gallery_exists = 1;
            $page_title = 'Panoramic Nature Photography - Free Roaming Photography';
            $page_desc = 'A collection of panoramic nature photography taken by Mike Cavaroc of Free Roaming Photography.';
            $themed_gallery_name = 'Panoramic';
            $themed_gallery_desc = "Panoramic photography creates a unique dimension on the traditional photo format by expanding the print to more than twice the height of the image. This creates a dynamic take on often familiar subjects, allowing the viewer to read the photo from left to right, exploring the content in a different way than usual. Some scenes and images are simply too expansive to be squeezed into a standard ratio photo, and so photography shot as a panorama allows for more room to fit the contents in at a natural scale.";
            break;
	*/
        default:
            $fourohfour_query = $db->prepare("SELECT galleryurlname FROM galleries");
            $fourohfour_query->execute();
            $missing_gal = $fourohfour_query->fetchAll();

            foreach ($missing_gal as $gallery_array => $gallery_url_name) {
                if($gallery_url_name['galleryurlname'] === $gallery_get) {
                    $gallery_exists = 1;
                }
            }
    }
    
    if(!$gallery_exists) {
        header('HTTP/1.0 404 Not Found');
        include "404.php";
    } else {
        if(!$themed) {
            /*
            $get_photos = $db->prepare("SELECT 
                id,gallery,gallery_id,imgname,filepath,exifdatetaken,exiftitle,exiffilename FROM photos WHERE
                filepath=:file_path ORDER BY id DESC LIMIT 50");
            $get_photo_array = array(':file_path' => $gallery_get);
            */
            
            
            
            $perPage = 48;
            $imageCountQuery = $db->prepare("SELECT id FROM photos WHERE filepath=:file_path");
            $imageArray = array(':file_path' => $gallery_get);
            $imageCountQuery->execute($imageArray);
            if ($imageCountQuery === false) {
                var_dump($imageCountQuery->errorInfo());
            }

            $imageCount = count($imageCountQuery->fetchAll());
            $rowCount = ceil($imageCount/$perPage);

            if(isset($_GET['page'])) {
                $pageGet = $_GET['page'];
            } else {
                $pageGet = "";
            }

            if($pageGet == "" || $pageGet == 1) {
                $page1 = 0;
            } else {
                $page1 = ($pageGet * $perPage) - $perPage;
            }

            $get_photos = $db->prepare("SELECT id,gallery,gallery_id,imgname,filepath,exiftitle,exifdatetaken,exiffilename FROM photos 
                WHERE filepath=:file_path ORDER BY exifdatetaken DESC LIMIT :page_num,:per_page");
            $get_photos_array = array(
                ':file_path' => $gallery_get,
                ':page_num' => $page1,
                ':per_page' => $perPage
                );
            
            
            
            
        }
        
        $get_photos->execute($get_photos_array);
        while ($photo_row = $get_photos->fetch()) {
            $photo_id[] = $photo_row['id'];
            $gallery_name[] = $photo_row['gallery'];
            $gallery_id[] = $photo_row['gallery_id'];
            $img_name[] = $photo_row['imgname'];
            $file_path[] = $photo_row['filepath'];
            $photo_title[] = $photo_row['exiftitle'];
            $file_name[] = $photo_row['exiffilename'];
        }

        $get_gallery = $db->prepare("SELECT gallerydesc,album_id,lat,lng FROM galleries WHERE galleryurlname=:gallery_url_name");
        $get_gallery_array = array(':gallery_url_name' => $gallery_get);
        $get_gallery->execute($get_gallery_array);
        while ($gallery_row = $get_gallery->fetch()) {
            $gallery_desc[] = $gallery_row['gallerydesc'];
            $gallery_lat[] = $gallery_row['lat'];
            $gallery_lng[] = $gallery_row['lng'];
            $album_id[] = $gallery_row['album_id'];
        }

        if(!$themed) {
            $page_title = $gallery_name[0].' - Free Roaming Photography';
            $page_desc = $gallery_desc[0];
        }
        
        $get_album = $db->prepare("SELECT album_id,albumname FROM albums WHERE album_id=:albumid");
        $get_album_array = array(':albumid' => $album_id[0]);
        $get_album->execute($get_album_array);
        while ($album_row = $get_album->fetch()) {
            $album_name[] = $album_name_row['albumname'];
        }
    }
}
require($incdir.'header.php');
if($gallery_exists) {
?>

<body class="gallery-page">

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
            
		<h1><?php echo ($themed ? $themed_gallery_name : $gallery_name[0]); ?> Photography Gallery</h1>
		    
        <div class="photo-list">
            <?php 
            $x = 0;
            foreach ($gallery_id as $photo) {
                $seo_photo = explode('.',$img_name[$x]);
                $thumb_path = ROOT_PATH.'/photos/'.$file_path[$x].'/thumbnails/'.$img_name[$x];
                list($width, $height) = getimagesize($thumb_path);
                
                echo '<div>';
                echo '<a class="photo-image" id="'.$photo_id[$x].'" href="/'.$file_path[$x].'/img/'.$seo_photo[0].'"><img 
                    src="https://www.freeroamingphotography.com/photos/'.$file_path[$x].'/thumbnails/'.$img_name[$x].'"
                    width="'.$width.'" height="'.$height.'" alt="'.$photo_title[$x].'"></a>';
                echo '</div>';
                $x++;
            }
            ?>
		</div><!-- .photo-list -->

        <?php if ($rowCount > 1) { ?>
		<ul class="pagey">
            <?php
			for ($i=1;$i<=$rowCount;$i++) {
				if ($i == $pageGet) {
					echo '<li><a href="/'.$file_path[$i].'/'.$i.'" class="activeLink">'.$i.'</a></li>';
				} else {
					echo '<li><a href="/'.$file_path[$i].'/'.$i.'">'.$i.'</a></li>';
				}
			}
			?>
        </ul>
		<?php
        }

        echo '<h2>'.($themed ? $themed_gallery_name : $gallery_name[0]).' Information</h2>';
        echo '<p>'.($themed ? $themed_gallery_desc : $gallery_desc[0]).'</p>';
            
        $photo_count = $db->prepare("SELECT id FROM photos WHERE gallery_id=:gallery_id");
		$photo_count->execute(array(':gallery_id' => $gallery_id[0]));
        $num_photos = count($photo_count->fetchAll());
        ?>
        <p class="total-photos"><?php echo ($themed ? $x : $num_photos); ?> total photos in this gallery</p>
            
        <?php
        if (!$themed) {
            echo '<h4 class="loc-h4"><a href="https://www.google.com/maps/search/?api=1&query='.$gallery_lat[0].','.$gallery_lng[0].'" target="_blank">Where is this location? &gt;</a></h4>';
		}
        ?>
		
        <?php include($incdir.'sharing.php'); ?>
		            
		</main>
	</div> <!-- .content-area -->

<?php
}
require($incdir.'footer.php');
?>
