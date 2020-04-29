<?php
	require '../require/database.php';
	$connection = openCon();
	
	function strWordCut($string,$length,$end='...')
	{
		$string = strip_tags($string);

		if (strlen($string) > $length) {

			// truncate string
			$stringCut = substr($string, 0, $length);

			// make sure it ends in a word so assassinate doesn't become ass...
			$string = substr($stringCut, 0, strrpos($stringCut, ' ')).$end;
		}
		return $string;
	}
	
	if(!isset($_GET["cat"])) {
		http_response_code(404); 
		// include('my_404.php'); 
		die();
	}
	
	$category = $_GET["cat"];
	$category = $connection->real_escape_string($category);
	$_category = mb_convert_encoding($category, 'UTF-8', 'UTF-8');
	$_category = htmlentities($_category, ENT_QUOTES, 'UTF-8');
	
	if(isset($_GET["page"]))
	{
		$page = $_GET["page"];
		$page = $connection->real_escape_string($page);
		$_page = mb_convert_encoding($page, 'UTF-8', 'UTF-8');
		$_page = htmlentities($_page, ENT_QUOTES, 'UTF-8');
	}

	$query = "SELECT * from category WHERE slug = '$_category'";
	$result = $connection->query($query);
    if ($result->num_rows > 0) { 
		while($row = $result->fetch_assoc()) {
            $_category = $row["slug"];
            $_description = $row["description"];
    ?>
<!DOCTYPE html>
<html lang="en" data-theme="light">
	<head>
        <meta charset="utf-8" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge" />
		<title><?php echo $row["title"]; ?> (<?php echo $_category ?>)<?php if(isset($_GET["page"])) {echo " - Page: $_page"; } ?> | Hack Hex</title>
		<meta name="description" content="<?php echo $_description; ?>">
		<meta name="HandheldFriendly" content="True" />
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link href="https://fonts.googleapis.com/css?family=Roboto&display=swap" rel="stylesheet">
		<link rel="stylesheet" type="text/css" href="https://<?php echo $_SERVER['HTTP_HOST'] ?>/assets/style/main.css" />
		<link rel="icon" href="https://<?php echo $_SERVER['HTTP_HOST'] ?>/assets/images/favicon.png" type="image/png" />
		<meta name="referrer" content="no-referrer-when-downgrade" />
		<link crossorigin='anonymous' href='https://ajax.cloudflare.com' rel='preconnect' />
		<link crossorigin='anonymous' href='https://www.google-analytics.com' rel='preconnect' />
		<link crossorigin='anonymous' href='https://pagead2.googlesyndication.com' rel='preconnect' />
		<link crossorigin='anonymous' href='https://www.googletagservices.com' rel='preconnect' />
		<link crossorigin='anonymous' href='https://hackhex.com' rel='preconnect' />
		<link crossorigin='anonymous' href='https://adservice.google.com' rel='preconnect' />
		<link href='https://tpc.googlesyndication.com' rel='dns-prefetch' />
		<link href='https://cdnjs.cloudflare.com' rel='dns-prefetch' />
		<link href='https://googleads.g.doubleclick.net' rel='dns-prefetch' />
		<link href='https://storage.googleapis.com' rel='dns-prefetch' />
		<link href='https://fonts.googleapis.com' rel='dns-prefetch' />
		<link href='https://fonts.gstatic.com' rel='dns-prefetch' />
		<meta name="theme-color" content="#000">
		<link rel="apple-touch-icon" href="https://<?php echo $_SERVER['HTTP_HOST'] ?>/assets/images/favicon.png">
        <script type='application/ld+json'>
                {
                    "@context": "https://schema.org",
                    "@type": "WebSite",
                    "name": "Hack Hex",
                    "url": "https://code.hackhex.com/",
                    "potentialAction": {
                        "@type": "SearchAction",
                        "target": "https://code.hackhex.com/search?q={search_term_string}",
                        "query-input": "required name=search_term_string"
                    }
                }
        </script>   
	</head>
	
	<body>
		<?php require '../partials/header.php'; ?>
		<div class="container maxWidth1132 margin30" style="display: flex;">
			<div class="items">
				<?php 
							echo "<h1 style=\"font-size:1.8rem;\">" . html_entity_decode($row["title"]) . "</h1>";
							echo "<div class=\"desc\" style=\"font-size:12px;\">" . html_entity_decode($row["description"]) . "</div>";
						}
					}
					
					if(isset($_GET["page"])) {
						$pStart = $_page * 10;
					} else {
						$pStart = 0;
					}
					
					$query = "SELECT * from question WHERE catSlug = '$_category' ORDER BY id DESC LIMIT $pStart, 10";
					$result = $connection->query($query);
					
					if ($result->num_rows > 0) { 
						while($row = $result->fetch_assoc()) {
							$title = html_entity_decode($row["title"]);
							$body = html_entity_decode($row["body"]);
							$slug = html_entity_decode($row["slug"]);
							$catSlug = html_entity_decode($row["catSlug"]);
                            $votes = html_entity_decode($row["score"]);
				?>
							<div class="questions">
								<a href="https://<?php echo $_SERVER['HTTP_HOST'] ?>/q/<?php echo $catSlug ?>/<?php echo $slug ?>.html" class="item">
                                    <div class="votes">
                                        <?php echo $votes; ?>
                                    </div>
									<div class="title">
										<?php echo $title ?>
									</div>
									<div class="desc">
										<?php $stripped = preg_replace('/\s+/', ' ', $body); echo strWordCut($stripped, 70); ?>
									</div>
								</a>
							</div>
				
				<?php
						}
						
						if(isset($_GET["page"]))
						{
							echo '<div class="nav-link">';
							if($_page==1) {
								echo '<a href="https://' . $_SERVER['HTTP_HOST'] . '"><- Previous Page</a>';
							} else {
								echo '<a href="https://' . $_SERVER['HTTP_HOST'] . '/c/' . $_category . '/' . ($_page-1) . '"><- Previous Page</a>';
							}
							
							echo '<a href="https://' . $_SERVER['HTTP_HOST'] . '/c/' . $_category . '/' . ($_page+1) . '" class="next">Next Page -></a>';
                            echo '</div>';
						} else {
                            echo '<div class="nav-link">';
							echo '<a href="https://' . $_SERVER['HTTP_HOST'] . '/c/' . $_category . '/1" class="next">Next Page -></a>';
                            echo '</div>';
						}
					
					} else {
						http_response_code(404); 
						echo 'No more items!';
					}// END IF
					
				?>
			</div>
			<div class="sidebar">
				<?php require '../partials/sidebar.php'; ?>
			</div>
		</div>
		<?php require '../partials/footer.php'; ?>
	</body>
</html>
