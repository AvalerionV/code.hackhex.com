<?php
	require './require/database.php';
	$connection = openCon();
    header("Content-type: text/xml");

    $_type = 'index';
    $page = 0;

    if(isset($_GET["type"]))
	{
        GLOBAL $_type;
		$type = $_GET["type"];
		$type = $connection->real_escape_string($type);
		$_type = mb_convert_encoding($type, 'UTF-8', 'UTF-8');
		$_type = htmlentities($_type, ENT_QUOTES, 'UTF-8');
	}

    if(isset($_GET["page"]))
	{
        GLOBAL $_page;
		$page = $_GET["page"];
		$page = $connection->real_escape_string($page);
		$_page = mb_convert_encoding($page, 'UTF-8', 'UTF-8');
		$_page = htmlentities($_page, ENT_QUOTES, 'UTF-8');
	} 
    
    if($_type == "index") {
        echo "<?xml version=\"1.0\" encoding=\"UTF-8\" ?><sitemapindex xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">";
        $query="SELECT id FROM question";
        if ($result=mysqli_query($connection,$query)) {
            $rowcount=mysqli_num_rows($result);
            $offset = ceil($rowcount/5000);
            for($i = 0; $i < $offset; $i++) {
                echo '<sitemap><loc>https://code.hackhex.com/sitemap-post-' . $i . '.xml</loc></sitemap>';
            }
        }
        echo '</sitemapindex>';
    }


    $end = 5000;

    if($_type == "post") {
        if($_page != 0) {
            $start = $_page * 5000;
        } else {
            $start = 0;
        }
        echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">\n";
        $query="SELECT id,slug,catSlug,modifiedDate FROM question LIMIT $start,$end";
        if ($result=mysqli_query($connection,$query)) {
            while($row = $result->fetch_assoc()) {
                echo "<url>\n<loc>https://code.hackhex.com/q/" . $row["catSlug"] . "/" . $row["slug"] . ".html</loc>\n<lastmod>" . date('c', $row["modifiedDate"]) . "</lastmod>\n</url>\n";
            }
        }
        echo '</urlset>';
    }
