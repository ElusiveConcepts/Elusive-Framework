<!DOCTYPE html>
<html lang="en">
<head>
	<title>Server Error (500)</title>
	<meta charset="UTF-8">

	<link rel="stylesheet" type="text/css" href="http://framework.elusive-concepts.com/css/ec_source.css">
	<link rel="stylesheet" type="text/css" href="http://framework.elusive-concepts.com/css/exception.css">

</head>
<body>

<div id="wrapper">

	<header>
		<hgroup>
			<h1><?php echo $this->request->data['ENV']['SERVER_NAME'] ?></h1>
			<h2>Server Error</h2>
		</hgroup>
	</header>

	<div id="content">

		<h1 class="icon error">Server Error (500)</h1>

		<div class="message">
			<p>An internal server error has occurred on <strong><?php echo $this->request->data['ENV']['SERVER_NAME'] ?></strong> while trying to access the following url:</p>
			<blockquote><a href="http://<?php echo $this->request->data['ENV']['SERVER_NAME'] . $this->request->data['ENV']['REQUEST_URI'] ?>">http://<?php echo $this->request->data['ENV']['SERVER_NAME'] . $this->request->data['ENV']['REQUEST_URI'] ?></a></blockquote>
		</div>

	</div><!-- #content -->

	<footer>
	</footer>

</div>

<script type="text/javascript" src="https://code.jquery.com/jquery-3.2.1.min.js" crossorigin="anonymous"></script>
<script type="text/javascript" src="http://framework.elusive-concepts.com/js/ec_render_source.js"></script>
<script type="text/javascript" src="http://framework.elusive-concepts.com/js/exception.js"</script>

</body>
</html>