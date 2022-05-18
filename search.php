<?php
session_start();
$incdir = $_SERVER['DOCUMENT_ROOT'].'/includes/';
require($incdir.'opening.php');

if(isset($_GET['search'])){
	if(preg_match("/[A-Z  | a-z]+/", $_GET['search'])){
		$seoSearch=str_replace("-"," ",$_GET['search']);
		$theSearch=strtolower($_GET['search']);
	}
} else {
	echo  "<p>Please enter a search query</p>";
}

$page_title = 'Search Results: '.$theSearch.' - Free Roaming Photography';
$page_desc = 'A page of photography search results for images matching '.$theSearch.'.';
require($incdir.'header.php');
?>

<body class="search">

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
		$searchCountQuery = $db->prepare("SELECT id FROM photos WHERE exifkeywords LIKE :thesearch");
		$searchArray = array(':thesearch' => '%'.$theSearch.'%');
		$searchCountQuery->execute($searchArray);
		if ($searchCountQuery === false) {
			var_dump($searchCountQuery->errorInfo());
		}
		
		$searchCount = count($searchCountQuery->fetchAll());
		$rowCount = ceil($searchCount/$perPage);

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
		
		$searchQuery = $db->prepare("SELECT imgname,filepath,exiftitle,exifdatetaken FROM photos 
			WHERE exifkeywords LIKE :thesearch ORDER BY exifdatetaken DESC LIMIT :page_num,:per_page");
		$qArray = array(
			':thesearch' => '%'.$theSearch.'%',
			':page_num' => $page1,
			':per_page' => $perPage
			);
		$searchQuery->execute($qArray);
		$searchRow = $searchQuery->fetchAll();
		
		?>

		<h1>Search: <span class="searchString"><?php echo $theSearch; ?></span></h1>
		<p><?php echo $searchCount; ?> photos found</p>

		<div class="photo-list">

		<?php 
		foreach ($searchRow as $row) {
			$imgName = $row['imgname'];
			$altText = $row['exiftitle'];
			$seoPath = $row['filepath'];
			$seoURL  = explode('.',$row['imgname']);
			$thumbPath = ROOT_PATH.'https://www.freeroamingphotography.com/photos/'.$seoPath.'/thumbnails/'.$imgName;
			list($width, $height) = getimagesize($thumbPath);
			?>
				<div>
				<a href="<?php echo $seoPath.'/img/'.$seoURL[0]; ?>"><img title="<?php echo $altText; ?>" src="<?php echo "https://www.freeroamingphotography.com/photos/".$seoPath."/thumbnails/".$imgName; ?>"
							width="<?php echo $width; ?>" alt="<?php echo $altText; ?>"></a>
					
				</div>
			
		<?php }

		echo '</div> <!-- .photo-list -->';
            
		if ($rowCount > 1) { ?>
		<ul class="pagey">
            <?php
			for ($i=1;$i<=$rowCount;$i++) {
				if ($i == $pageGet) {
					echo '<li><a href="search.php?search=',$seoSearch,'&page=',$i,'" class="activeLink">',$i,'</a></li>';
				} else {
					echo '<li><a href="search.php?search=',$seoSearch,'&page=',$i,'">',$i,'</a></li>';
				}
			}
			?>
        </ul>
		<?php } ?>
        </main>
	</div> <!-- #pageContent -->

<?php require($incdir.'footer.php'); ?>
