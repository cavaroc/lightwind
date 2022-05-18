<?php
session_start();
$incdir = $_SERVER['DOCUMENT_ROOT'].'/includes/';
require($incdir.'opening.php');

if(isset($_GET['tag'])){
	if(preg_match("/[A-Z  | a-z]+/", $_GET['tag'])){
		$origTag=$_GET['tag'];
		$theTag=str_replace("-"," ",$origTag);
	}
} else {
	echo  "<p>No tag given</p>";
}

$page_title = '';
$page_desc = '';
require($incdir.'header.php');
?>

<body class="tags">

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

		<?php
		
		$perPage = 50;
		$tagCountQuery = $db->prepare("SELECT id FROM photos WHERE exifkeywords LIKE :tag");
		$tagArray = array(':tag' => '%'.$theTag.'%');
		$tagCountQuery->execute($tagArray);
		if ($tagCountQuery === false) {
			var_dump($tagCountQuery->errorInfo());
		}
		
		$tagCount = count($tagCountQuery->fetchAll());
		$rowCount = ceil($tagCount/$perPage);

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

		$tagQuery = $db->prepare("SELECT imgname,filepath,exiftitle,exifdatetaken FROM photos 
			WHERE exifkeywords LIKE :theTag 
			ORDER BY exifdatetaken DESC LIMIT :page_num,:per_page");
		$queryArray = array(
			':theTag' => '%'.$theTag.'%',
			':page_num' => $page1,
			':per_page' => $perPage
			);
		$tagQuery->execute($queryArray);
		$tagRow = $tagQuery->fetchAll();
		?>

		<h1>Tag: <span class="search-string"><?php echo $theTag; ?></span></h1>
		<p><?php echo $tagCount; ?> photos found</p>

		<div class="photo-list">

		<?php 
		foreach ($tagRow as $row) {
			$imgName = $row['imgname'];
			$altText = $row['exiftitle'];
			$seoPath = $row['filepath'];
			$seoURL  = explode('.',$row['imgname']);
			$thumbPath = ROOT_PATH.'/photos/'.$seoPath.'/thumbs/'.$imgName;
			list($width, $height) = getimagesize($thumbPath);
			?>
				<div>
					<a href="<?php echo $seoPath.'/img/'.$seoURL[0]; ?>"><img title="<?php echo $altText; ?>" src="https://www.freeroamingphotography.com/photos/<?php echo $seoPath."/thumbnails/".$imgName; ?>"
						width="<?php echo $width; ?>" alt="<?php echo $altText; ?>"></a>
				</div>
			
		<?php
		}

		echo '</div> <!-- .photo-list -->';
            
            if ($rowCount > 1) { ?>
			<ul class="pagey">

			<?php
			
			for ($i=1;$i<=$rowCount;$i++) {
				if ($i == $pageGet) {
					echo '<li><a href="tag.php?tag=',$origTag,'&page=',$i,'" class="activeLink">',$i,'</a></li>';
				} else {
					echo '<li><a href="tag.php?tag=',$origTag,'&page=',$i,'">',$i,'</a></li>';
				}
			}
			?>

			</ul>
		<?php } ?>
		
        </main>
	</div> <!-- #pageContent -->

<?php require($incdir.'footer.php'); ?>
