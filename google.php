<head>
<title>Testing Google XSS</title>
</head>
<body>
	<div class="xss" style="display:none;"><?php echo $_GET['q']; ?></div>
</body>
