<!DOCTYPE html>
<html lang="en">
<head>
	<title>Server Error (500)</title>
	<meta charset="UTF-8">

	<link rel="stylesheet" type="text/css" href="/elusive/debug/templates/css/ec_source.css">
	<link rel="stylesheet" type="text/css" href="/elusive/debug/templates/css/exception.css">
	<link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Lato">

	<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
	<script type="text/javascript" src="/elusive/debug/templates/js/ec_render_source.js"></script>
	<script type="text/javascript" src="/elusive/debug/templates/js/exception.js"></script>

	<!--[if lt IE 9]>
	<script src="//html5shiv.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
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
			<p>If this problem persists, please notify the site administrator at: <a href="mailto:<?php echo $this->request->data['ENV']['SERVER_ADMIN'] ?>"><?php echo $this->request->data['ENV']['SERVER_ADMIN'] ?></a>.</p>
		</div>

	</div><!-- #content -->

	<footer>
	</footer>

</div>

</body>
</html>