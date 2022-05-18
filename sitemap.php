<?php
header('Content-type: application/xml');
$baseurl = "https://www.freeroamingphotography.com/";
require('includes/init.php');

$output = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
$output .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">' . "\n";
echo $output;
?>

<url>
	<loc>https://www.freeroamingphotography.com/about.php</loc>
	<changefreq>monthly</changefreq>
</url>
<url>
	<loc>https://www.freeroamingphotography.com/grizzly-bear-399</loc>
	<changefreq>monthly</changefreq>
</url>
<url>
	<loc>https://www.freeroamingphotography.com/night</loc>
	<changefreq>monthly</changefreq>
</url>
<url>
	<loc>https://www.freeroamingphotography.com/panoramas</loc>
	<changefreq>monthly</changefreq>
</url>
<url>
	<loc>https://www.freeroamingphotography.com/black-and-white</loc>
	<changefreq>monthly</changefreq>
</url>
<url>
	<loc>https://www.freeroamingphotography.com/arizona-trail.php</loc>
	<changefreq>monthly</changefreq>
</url>
<url>
	<loc>https://www.freeroamingphotography.com/favorites</loc>
	<changefreq>monthly</changefreq>
</url>
<url>
	<loc>https://www.freeroamingphotography.com/latest.php</loc>
	<changefreq>weekly</changefreq>
</url>
<url>
	<loc>https://www.freeroamingphotography.com/workshops.php</loc>
	<changefreq>weekly</changefreq>
</url>
<?php
$galleryQuery = $db->prepare("SELECT gallery_id,galleryurlname FROM galleries ORDER BY galleryurlname ASC LIMIT 0, 1000");
$galleryQuery->execute();
 
while($galleryRow = $galleryQuery->fetch()) { 
	if(!empty($galleryRow['galleryurlname'])) { ?>	
		<url>
			<loc><?php echo $baseurl.$galleryRow['galleryurlname']; ?></loc>
			<changefreq>monthly</changefreq>
		</url>
	<?php }  
}
/*
$subGalQuery = $db->prepare("SELECT id,subgalleryslug,filepath FROM photos GROUP BY subgalleryslug ORDER BY id ASC LIMIT 0, 1000");
$subGalQuery->execute();
 
while($subGalRow = $subGalQuery->fetch()) { 
	if(!empty($subGalRow['subgallery'])) { ?>	
		<url>
			<loc><?php echo $baseurl.$subGalRow['filepath'].'/'.$subGalRow['subgallery']; ?></loc>
			<changefreq>monthly</changefreq>
		</url>
	<?php }  
}
*/
$photoQuery = $db->prepare("SELECT id,imgname,filepath,exifdesc,exiftitle FROM photos ORDER BY id ASC LIMIT 0, 48000");
$photoQuery->execute();
 
while($photoRow = $photoQuery->fetch()) {
	if(!empty($photoRow['filepath'])) {
		$imgName = explode('.',$photoRow['imgname']); ?>	
		<url>
			<loc><?php echo $baseurl.$photoRow['filepath'].'/img/'.$imgName[0]; ?></loc>
			<changefreq>never</changefreq>
			<image:image>
				<image:loc><?php echo $baseurl.'photos/'.$photoRow['filepath'].'/'.$photoRow['imgname']; ?></image:loc>
				<image:caption><?php echo $photoRow['exifdesc']; ?></image:caption>
				<image:title><?php echo $photoRow['exiftitle']; ?></image:title>
			</image:image>
		</url>
	<?php }  
} ?>
</urlset>