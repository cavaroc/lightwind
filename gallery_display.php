<?php
		// Check if subgallery is passed via a GET request
		if(isset($_GET['subgallery']) && isset($_GET['gname'])) {
			$subGalleryGet = $_GET['subgallery'];
		}
		
		// Check if there are any subgalleries in the database for this gallery
		$subGalCheck = $db->prepare("SELECT subgallery FROM photos WHERE gallery_id=:gallery_id GROUP BY subgallery");
		$subGalArray = array(':gallery_id' => $galId);
		$subGalCheck->execute($subGalArray);
		$subGalleryTest = $subGalCheck->fetchAll();
		
		// If there is something in the first key of the subgallery database check, or if the GET request was true, then proceed
		if(($subGalleryTest[0]['subgallery'] != "") || !empty($subGalleryGet)) {

		// If there was a GET request
			if(!empty($subGalleryGet)) {
				echo '<div class="subGalleryInfo clearFix">';
				
				// Count the photos in the subgallery
				$subGalCount = $db->prepare("SELECT subgallery,subgalleryslug FROM photos WHERE subgalleryslug=:sub_gallery");
				$subGalCountArray = array(':sub_gallery' => $subGalleryGet);
				$subGalCount->execute($subGalCountArray);
				$subGalleryCount = $subGalCount->fetchAll();
				echo '<h2>'.$subGalleryCount[0]['subgallery'].' Sub-Gallery</h2>';
				echo '<p class="totalPhotos">'.count($subGalleryCount).' photos in this sub-gallery</p>';
				echo '</div>';
				
				// Get all photos matching the subgallery request
				$getPhotos = $db->prepare("SELECT imgname,filepath,exiftitle,exifdatetaken,subgallery,subgalleryslug FROM photos 
					WHERE subgalleryslug=:sub_gal ORDER BY exifdatetaken DESC");
				$getPhotoArray = array('sub_gal' => $subGalleryGet);
				$getPhotos->execute($getPhotoArray);
				while ($row = $getPhotos->fetch()) {
					$imgName = $row['imgname'];
					$altText = $row['exiftitle'];
					$seoPath = $row['filepath'];
					$seoURL  = explode('.',$row['imgname']);
					$subGallery[] = $row['subgallery'];
					$subGalleryURL[] = $row['subgalleryslug'];
					$thumbPath = ROOT_PATH.'/photos/'.$seoPath.'/thumbs/'.$imgName;
					list($width, $height) = getimagesize($thumbPath);
					?>
					<div class="galleryThumbContainer clearFix"><div class="galleryThumb">
						<a class="noStyle" href="/<?php echo $galName; ?>/img/<?php echo $seoURL[0]; ?>"><img title="<?php echo $altText; ?>" src="<?php echo "/photos/".$seoPath."/thumbs/".$imgName; ?>"
							alt="<?php echo $altText; ?>" width="<?php echo $width; ?>" height="<?php echo $height; ?>"></a>
					</div></div>
				<?php } ?>
				
				<hr>
				<h2><?php echo $galleryName; ?> Sub-Galleries</h2>
				<?php
				// Same query as below - make it a function
				$getPhotos = $db->prepare("
					SELECT imgname,exiftitle,filepath,subgallery,subgalleryslug,subgallery_pic FROM photos 
					WHERE gallery_id=:gallery_id AND subgallery_pic=:subgal_pic 
					GROUP BY(subgalleryslug) 
					ORDER BY subgalleryslug ASC
					");
				$getArray = array(
					':gallery_id' => $galId,
					':subgal_pic' => 1
					);
				$getPhotos->execute($getArray);

				while ($row = $getPhotos->fetch()) {
					$imgName = $row['imgname'];
					$altText = $row['exiftitle'];
					$seoPath = $row['filepath'];
					$seoURL  = explode('.',$row['imgname']);
					$subGallery = $row['subgallery'];
					$subGalleryURL = $row['subgalleryslug'];
					$thumbPath = ROOT_PATH.'/photos/'.$seoPath.'/thumbs/'.$imgName;
					list($width, $height) = getimagesize($thumbPath);
					?>
					<div class="galleryBox">
						<a class="noStyle" href="/<?php echo $galName; ?>/<?php echo $subGalleryURL; ?>"><img title="<?php echo $altText; ?>" src="<?php echo "/photos/".$seoPath."/thumbs/".$imgName; ?>"
							alt="<?php echo $altText; ?>" width="<?php echo $width; ?>" height="<?php echo $height; ?>"></a><br>
						<a href="/<?php echo $galName; ?>/<?php echo $subGalleryURL; ?>"><?php echo $subGallery; ?></a>
					</div>
				<?php } 

				
			// If there are subgalleries but there was no GET request
			} else {
				$getPhotos = $db->prepare("
					SELECT imgname,exiftitle,filepath,subgallery,subgalleryslug,subgallery_pic FROM photos 
					WHERE gallery_id=:gallery_id AND subgallery_pic=:subgal_pic 
					GROUP BY (subgalleryslug) 
					ORDER BY subgalleryslug ASC
					");
				$getArray = array(
					':gallery_id' => $galId,
					':subgal_pic' => 1
					);
				$getPhotos->execute($getArray);
				
				while ($row = $getPhotos->fetch()) {
					$imgName = $row['imgname'];
					$altText = $row['exiftitle'];
					$seoPath = $row['filepath'];
					$seoURL  = explode('.',$row['imgname']);
					$subGallery = $row['subgallery'];
					$subGalleryURL = $row['subgalleryslug'];
					$thumbPath = ROOT_PATH.'/photos/'.$seoPath.'/thumbs/'.$imgName;
					list($width, $height) = getimagesize($thumbPath);
					?>
					<div class="galleryBox">
						<a class="noStyle" href="/<?php echo $galName; ?>/<?php echo $subGalleryURL; ?>"><img title="<?php echo $altText; ?>" src="<?php echo "/photos/".$seoPath."/thumbs/".$imgName; ?>"
							alt="<?php echo $altText; ?>" width="<?php echo $width; ?>" height="<?php echo $height; ?>"></a><br>
						<a href="/<?php echo $galName; ?>/<?php echo $subGalleryURL; ?>"><?php echo $subGallery; ?></a>
					</div>
				<?php } 
			}
			
		// If there are no subgalleries or no GET request
		} else if ($overallQuery != 0) {
			switch ($overallQuery) {
				case 1:
					$specialQuery = $db->prepare("SELECT imgname,filepath,exiftitle,exifdatetaken FROM photos WHERE favorite=:fav ORDER BY exifdatetaken DESC");
					$specialQuery->execute(array(':fav' => 1));
				break;
				
				case 2:
					$specialQuery = $db->prepare("SELECT imgname,filepath,exiftitle,exifdatetaken FROM photos WHERE exifkeywords LIKE :thetag ORDER BY exifdatetaken DESC");
					$specialQuery->execute(array(':thetag' => '%night%'));
				break;
				
				case 3:
					$specialQuery = $db->prepare("SELECT imgname,filepath,exiftitle,exifdatetaken FROM photos WHERE exifkeywords LIKE '%, panorama,%' ORDER BY exifdatetaken DESC");
					$specialQuery->execute();
				break;
				
				case 4:
					$specialQuery = $db->prepare("SELECT imgname,filepath,exiftitle,exifdatetaken FROM photos WHERE exifkeywords LIKE :thetag ORDER BY exifdatetaken DESC");
					$specialQuery->execute(array(':thetag' => '%black and white%'));
				break;
				
				default:
					$specialQuery = $db->prepare("SELECT imgname,filepath,exiftitle,exifdatetaken FROM photos WHERE exifkeywords LIKE :thetag1 OR exifkeywords LIKE :thetag2 ORDER BY exifdatetaken DESC");
					$specialQuery->execute(array(':thetag1' => '%399%', ':thetag2' => '%610%'));
			}
			while ($row = $specialQuery->fetch()) {
				$imgName = $row['imgname'];
				$altText = $row['exiftitle'];
				$seoPath = $row['filepath'];
				$seoURL  = explode('.',$row['imgname']);
				$thumbPath = ROOT_PATH.'https://www.freeroamingphotography.com/photos/'.$seoPath.'/thumbs/'.$imgName;
				list($width, $height) = getimagesize($thumbPath);
				?>
				<li><a class="noStyle" href="/<?php echo $seoPath; ?>/img/<?php echo $seoURL[0]; ?>"><img title="<?php echo $altText; ?>" src="<?php echo "https://www.freeroamingphotography.com/photos/".$seoPath."/thumbs/".$imgName; ?>"
						alt="<?php echo $altText; ?>" width="<?php echo $width; ?>" height="<?php echo $height; ?>"></a></li>
			<?php }
		} else {
			$getPhotos = $db->prepare("SELECT imgname,filepath,exiftitle,exifdatetaken FROM photos 
				WHERE gallery_id=:gal_id ORDER BY exifdatetaken DESC");
			$photoArray = array(':gal_id' => $galId);
			$getPhotos->execute($photoArray);
			while ($row = $getPhotos->fetch()) {
				$imgName = $row['imgname'];
				$altText = $row['exiftitle'];
				$seoPath = $row['filepath'];
				$seoURL  = explode('.',$row['imgname']);
				$thumbPath = ROOT_PATH.'/photos/'.$seoPath.'/thumbs/'.$imgName;
				list($width, $height) = getimagesize($thumbPath);
				?>
				<div class="galleryThumbContainer clearFix"><div class="galleryThumb">
					<a class="noStyle" href="/<?php echo $galName; ?>/img/<?php echo $seoURL[0]; ?>"><img title="<?php echo $altText; ?>" src="<?php echo "/photos/".$seoPath."/thumbs/".$imgName; ?>"
						alt="<?php echo $altText; ?>" width="<?php echo $width; ?>" height="<?php echo $height; ?>"></a>
				</div></div>
			<?php }
		}
?>	
