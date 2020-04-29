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
	
?>
<!DOCTYPE html>
<html lang="en" data-theme="light">
	<head>
		<meta charset="utf-8" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge" />
		<title><?php if(!isset($_GET["page"])) {echo "Search results | Hack Hex"; } ?></title>
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
				<script async src='https://cse.google.com/cse.js?cx=partner-pub-5711925349380003:2032418504'></script><div class="gcse-searchresults-only"></div>
			</div>
			<div class="sidebar">
				<?php require '../partials/sidebar.php'; ?>
			</div>
		</div>
		<?php require '../partials/footer.php'; ?>
	</body>
</html>
