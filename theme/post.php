<?php
	require '../require/database.php';
    	$connection = openCon();
	
	function replaceTitle($string) {
		$_newString = str_replace("PHP: ", "", $string);
		$_newString = str_replace("PHP 5: ", "", $_newString);
		$_newString = str_replace("PHP 7: ", "", $_newString);
		$_newString = str_replace("PHP - ", "", $_newString);
		$_newString = str_replace(" in PHP", "", $_newString);
		$_newString = str_replace(" using PHP?", "?", $_newString);
		$_newString = str_replace(" with PHP?", "?", $_newString);
		$_newString = str_replace(" with PHP 5?", "?", $_newString);
		$_newString = str_replace(" with PHP 7?", "?", $_newString);
		$_newString = str_replace("Javascript: ", "", $_newString);
		$_newString = str_replace("Javascript - ", "", $_newString);
		$_newString = str_replace(" in JavaScript", "", $_newString);
		$_newString = str_replace(" using JavaScript?", "?", $_newString);
		$_newString = str_replace(" with Javascript?", "?", $_newString);
		return $_newString;
	}

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
	
	if(!isset($_GET["cat"]) || !isset($_GET["slug"])) {
		die();
	};
	
	$category = $_GET["cat"];
	$category = $connection->real_escape_string($category);
	$_category = mb_convert_encoding($category, 'UTF-8', 'UTF-8');
	$_category = htmlentities($_category, ENT_QUOTES, 'UTF-8');
	
	$slug = $_GET["slug"];
	$slug = $connection->real_escape_string($slug);
	$_slug = mb_convert_encoding($slug, 'UTF-8', 'UTF-8');
	$_slug = htmlentities($_slug, ENT_QUOTES, 'UTF-8');
	
	$query = "SELECT * FROM question WHERE catSlug = '$_category' AND slug = '$_slug'";
	$result = $connection->query($query);
	
if ($result->num_rows > 0) {
	
	if ($_category == 'php') {
		$_cleanSlug = ' (PHP)';
	} elseif ($_category == 'javascript') {
		$_cleanSlug = ' (JavaScript)';
	} else {
		$_cleanSlug = '';
	}
	
		while($row = $result->fetch_assoc()) {
			$title = html_entity_decode($row["title"]);
			$body = html_entity_decode($row["body"]);
			$qId = html_entity_decode($row["id"]);
			$owner = html_entity_decode($row["owner"]);
			$date = html_entity_decode($row["creationDate"]);
			$mdate = html_entity_decode($row["modifiedDate"]);
			$score = html_entity_decode($row["score"]);
	                $qSlug = html_entity_decode($row["slug"]);
        	        $cSlug = html_entity_decode($row["catSlug"]);
?>
			<!DOCTYPE html>
			<html itemscope itemtype="http://schema.org/QAPage">
			<head>
				<meta charset="utf-8" />
				<meta http-equiv="X-UA-Compatible" content="IE=edge" />
				<title><?php echo replaceTitle($title) . $_cleanSlug; ?> | Hack Hex</title>
				<meta name="HandheldFriendly" content="True" />
				<meta name="viewport" content="width=device-width, initial-scale=1">
				<link href="https://fonts.googleapis.com/css?family=Roboto&display=swap" rel="stylesheet">
				<link rel="stylesheet" type="text/css" href="https://<?php echo $_SERVER['HTTP_HOST'] ?>/assets/style/main.css" />
				<link rel="icon" href="https://<?php echo $_SERVER['HTTP_HOST'] ?>/assets/images/favicon.png" type="image/png" />
				<meta name="referrer" content="no-referrer-when-downgrade" />
				<meta property="og:title" content="Solved: <?php echo replaceTitle($title) . $_cleanSlug; ?> | Hack Hex" />
				<meta property="og:image" itemprop="image primaryImageOfPage" content="https://<?php echo $_SERVER['HTTP_HOST'] ?>/assets/images/favicon.png" />				
				<meta property="og:url" content="https://learn.hackhex.com/q/<?php echo $_category; ?>/<?php echo $_slug; ?>.html" />
				<meta property="og:site_name" content="Hack Hex" />
				<meta name="twitter:title" property="og:title" itemprop="name" content="<?php echo $title ?>" />
				<meta name="twitter:description" property="og:description" itemprop="description" content="<?php $stripped = preg_replace('/\s+/', ' ', $body); echo strWordCut($stripped, 140); ?>" />
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
				
                <div class="maxWidth1132" style="margin-left: auto; margin-right: auto;">
                <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
                <!-- Top Page @ Learn.HackHex -->
                <ins class="adsbygoogle"
                    style="display:block;margin-top:30px;margin-bottom:30px;"
                    data-ad-client="ca-pub-5711925349380003"
                    data-ad-slot="1891380913"
                    data-ad-format="auto"
                    data-full-width-responsive="true"></ins>
                <script>
                    (adsbygoogle = window.adsbygoogle || []).push({});
                </script>
                </div>

				<div class="container maxWidth1132 margin30" style="display: flex;">
					<div class="post">
						<div itemprop="mainEntity" itemscope itemtype="http://schema.org/Question">
						<link itemprop="image" href="https://<?php echo $_SERVER['HTTP_HOST'] ?>/assets/images/favicon.png">
						<?php 
						$query = "SELECT * FROM category WHERE slug = '$_category'";
						$result = $connection->query($query);
						
						if ($result->num_rows > 0) { 
							while($row = $result->fetch_assoc()) {
								$cTitle = html_entity_decode($row["title"]);
								?>
									<ul class="breadcrumb" itemscope itemtype="http://schema.org/BreadcrumbList">
									  <li itemprop="itemListElement" itemscope
										  itemtype="http://schema.org/ListItem">
										<a itemprop="item" href="https://<?php echo $_SERVER['HTTP_HOST'] ?>">
										<span itemprop="name">Hack Hex</span></a>
										<meta itemprop="position" content="1" />
									  </li>
									  <li itemprop="itemListElement" itemscope
										  itemtype="http://schema.org/ListItem">
										<a itemprop="item" href="https://<?php echo $_SERVER['HTTP_HOST'] ?>/c/<?php echo $_category ?>">
										<span itemprop="name"><?php echo $cTitle ?></span></a>
										<meta itemprop="position" content="2" />
									  </li>
									</ul>
								<?php
							}
						}						
						?>						
						<div class="question" id="q-<?php echo $qId; ?>">
							<h1 class="postTitle"  itemprop="name"><?php echo replaceTitle($title); ?></h1><span id="votes" style="display:none;">(<span itemprop="upvoteCount"><?php echo $score ?></span> Votes)</span>
							<?php 
							$count = 0;
							
							?>
							
							<div class="userInfo">
								<div class="qDate">
									<span itemprop="dateCreated"><b><?php echo date('m/d/Y H:i:s', $mdate); ?></b></span>
								</div>
								<div class="qUser" itemprop="author" itemscope itemtype="http://schema.org/Person">
										<span itemprop="name"><b><?php echo $owner; ?></b></span>
								</div>
							</div>
							
							<?php
							
							echo "<div itemprop=\"text\">" . $body . "</div>";
														
							?>
                            <div style="display:none;">
								<a href="https://<?php echo $_SERVER['HTTP_HOST'] ?>/q/<?php echo $_category ?>/<?php echo $_slug ?>.html#q-<?php echo $qId; ?>" itemprop="url">Link</a> 
							</div>
						</div>
						<?php
						
						$query = "SELECT * FROM answer WHERE questionId = '$qId' AND isAccepted = 1 LIMIT 1";
						$result = $connection->query($query);
						
						if ($result->num_rows > 0) { 
						while($row = $result->fetch_assoc()) {
                            $aId = html_entity_decode($row["id"]);
							$body = html_entity_decode($row["body"]);
							$score = html_entity_decode($row["score"]);
							$owner = html_entity_decode($row["owner"]);
							$creationDate = html_entity_decode($row["creationDate"]);
							$count = $count + 1;
			?>
								<div class="ansContainer" itemprop="acceptedAnswer" itemscope itemtype="http://schema.org/Answer">
									<div class="answer" id="a-<?php echo $row["id"] ?>">
										<h1 class="solutionTitle">Verified Answer (<span itemprop="upvoteCount"><?php echo $score ?></span> Votes) <span id="tick">&#10003;</span></h1>
										<div class="userInfo">
											<div class="aDate">
												<b><?php echo date('m/d/Y H:i:s', $creationDate); ?></b>
											</div>
											<div class="aUser" itemprop="author" itemscope itemtype="http://schema.org/Person">
												<b itemprop="name"><?php echo $owner; ?></b>
											</div>
										</div>
										<span itemprop="text"><?php echo $body; ?></span>
                                        <span style="display:none;" itemprop="upvoteCount"><?php echo $score ?></span>
                                        <time itemprop="dateCreated" datetime="<?php echo date('m/d/YTH:i:s', $creationDate); ?>"></time>
                                        <div style="display:none;">
                                            <a href="https://<?php echo $_SERVER['HTTP_HOST'] ?>/q/<?php echo $_category ?>/<?php echo $_slug ?>.html#a-<?php echo $aId; ?>" itemprop="url">Link</a> 
                                        </div>
									</div>
								</div>
								<?php
							}
						}
						
						$query = "SELECT * FROM answer WHERE questionId = '$qId' AND isAccepted = 0 ORDER BY score DESC LIMIT 2";
						$result = $connection->query($query);
						
						if ($result->num_rows > 0) { 
						while($row = $result->fetch_assoc()) {
                            $aId = html_entity_decode($row["id"]);
							$body = html_entity_decode($row["body"]);
							$score = html_entity_decode($row["score"]);
							$owner = html_entity_decode($row["owner"]);
							$creationDate = html_entity_decode($row["creationDate"]);
							if($score >= 1) {
                                $count = $count + 1;
						?>
								<div class="ansContainer" itemprop="suggestedAnswer" itemscope itemtype="http://schema.org/Answer">
									<div class="answer" id="a-<?php echo $row["id"] ?>">
										<h1 class="solutionTitle">Answer #<?php echo $count; ?> (<span itemprop="upvoteCount"><?php echo $score ?></span> Votes)</h1>
										<div class="userInfo">
											<div class="aDate">
												<b><?php echo date('m/d/Y H:i:s', $creationDate); ?></b>
											</div>
											<div class="aUser" itemprop="author" itemscope itemtype="http://schema.org/Person">
												<b itemprop="name"><?php echo $owner; ?></b>
											</div>
										</div>
										<span itemprop="text"><?php echo $body; ?></span>
                                        <span style="display:none;" itemprop="upvoteCount"><?php echo $score ?></span>
                                        <time itemprop="dateCreated" datetime="<?php echo date('m/d/YTH:i:s', $creationDate); ?>"></time>
                                        <div style="display:none;">
                                            <a href="https://<?php echo $_SERVER['HTTP_HOST'] ?>/q/<?php echo $_category ?>/<?php echo $_slug ?>.html#a-<?php echo $aId; ?>" itemprop="url">Link</a> 
                                        </div>
									</div>
								</div>
								<?php
								}
							}
						}
						?>
                        <span style="display:none;" itemprop="answerCount"><?php echo $count; ?></span>	
					</div>
				</div>
					<div class="sidebar">
						<div class="recent-list" style="margin-bottom:30px;">
						<div class="title" style="font-size:20px;font-weight:600;margin-bottom:20px;">Most Recent</div>
							<ul style="padding-inline-start: 15px;">
<?php

	$query = "SELECT * FROM question ORDER BY id DESC LIMIT 5";
	$result = $connection->query($query);
	if ($result->num_rows > 0) {
		while($row = $result->fetch_assoc()) {
			$i_slug = html_entity_decode($row["slug"]);
			$i_cat = html_entity_decode($row["catSlug"]);
			$i_title = html_entity_decode($row["title"]);
			$_i_title = replaceTitle($i_title);			
?>
			<li class="recent-item" style="margin-bottom:10px;list-style-type: square;">
				<a href="https://code.hackhex.com/q/<?php echo $i_cat; ?>/<?php echo $i_slug; ?>.html"><?php echo $_i_title; ?></a>
			</li>				
<?php 
		}
	}
?>
</ul>
</div>
						<?php require '../partials/sidebar.php'; ?>
					</div>
				</div>
<div class="maxWidth1132" style="margin-left: auto; margin-right: auto; margin-bottom:30px;text-align:center;">
Hack Hex uses <a href="https://api.stackexchange.com/" rel="nofollow" target="_blank">Stack Exchance API</a> by the <b>Stack Exchange Inc.</b> to scrape questions/answers under <a href="https://stackoverflow.blog/2009/06/04/stack-overflow-creative-commons-data-dump/" rel="nofollow" target="_blank">Creative Commons license</a>.
</div>
				<?php require '../partials/footer.php'; ?>
			</body>
            </html>
<?php	
		}
	} else {
		echo 'Error! 404.';
		http_response_code(404); 
		// include('my_404.php'); 
		die();
	}
?>
