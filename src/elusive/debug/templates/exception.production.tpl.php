<!DOCTYPE html>
<html lang="en">
<head>
	<title>Server Error (500)</title>
	<meta charset="UTF-8">

	<link rel="stylesheet" type="text/css" href="https://framework.elusive-concepts.com/css/elusive-framework-icons.css">
	<link rel="stylesheet" type="text/css" href="https://framework.elusive-concepts.com/css/ec_source.css">
	<link rel="stylesheet" type="text/css" href="https://framework.elusive-concepts.com/css/exception.css">

</head>
<body>

<div id="wrapper">

	<header>
		<hgroup>
			<h1><?= $this->request->data['ENV']['SERVER_NAME'] ?></h1>
		</hgroup>
	</header>

	<div id="content">

		<h1 class="icon error">Server Error (500)</h1>

		<div class="message">
			<p>A server error has occurred on <strong><?= $this->request->data['ENV']['SERVER_NAME'] ?></strong> while trying to access the following url:</p>
			<blockquote><a href="http://<?php echo $this->request->data['ENV']['SERVER_NAME'] . $this->request->data['ENV']['REQUEST_URI'] ?>">http://<?php echo $this->request->data['ENV']['SERVER_NAME'] . $this->request->data['ENV']['REQUEST_URI'] ?></a></blockquote>
			<p>If the issue persists, please contact an administrator.</p>
		</div>

	</div><!-- #content -->

	<footer>
		&nbsp;
	</footer>

</div>

<script type="text/javascript" src="https://framework.elusive-concepts.com/js/ec_render_source.js"></script>
<script type="text/javascript" src="https://framework.elusive-concepts.com/js/exception.js"></script>

</body>
</html>
