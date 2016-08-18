<!DOCTYPE html>
<html lang="en">
<head>
	<title>Server Error: Application Exception</title>
	<meta charset="UTF-8">

	<link rel="stylesheet" type="text/css" href="/elusive/debug/templates/css/ec_source.css">
	<link rel="stylesheet" type="text/css" href="/elusive/debug/templates/css/exception.css">
	<link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Lato">

	<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
	<script type="text/javascript" src="/elusive/debug/templates/js/ec_render_source.js"></script>
	<script type="text/javascript" src="/elusive/debug/templates/js/exception.js"></script>
</head>
<body>

<div id="wrapper">

	<header>
		<hgroup>
			<h1>Elusive Framework</h1>
			<h2>Server Error</h2>
		</hgroup>
	</header>

	<div id="content">

		<h1 class="icon error">Server Error: Application Exception</h1>

		<div class="exception">
			<dl>
				<dt><h4 class="icon error"><?php echo $this->errors['exception']['type'] ?> (<?php echo $this->errors['exception']['code'] ?>)</h4></dt>
				<dd>
					<p class="err_msg"><?php echo $this->errors['exception']['msg'] ?></p>
					<?php $this->create_snippet($this->errors['exception']['file'], $this->errors['exception']['line']) ?>
					<p class="fileinfo">
						<strong>File:</strong> <?php echo $this->errors['exception']['file'] ?> |
						<strong>Line:</strong> <?php echo $this->errors['exception']['line'] ?>
					</p>
				</dd>
			</dl>
		</div>

		<div class="stack_trace">

			<div class="stack_trace">
				<h3>Stack Trace:</h3>
				<ul>
				<?php foreach($this->traces['stack'] as $trace) : ?>
					<li>
						<h4><?php echo $trace['class'] . $trace['type'] . $trace['function'] ?></h4>
						<p><em>Args:</em> &nbsp; <?php print_r($trace['args']) ?></p>
						<?php echo $this->create_snippet($trace['file'], $trace['line']) ?>
						<p class="fileinfo">
							<strong>File:</strong> <?php echo $trace['file'] ?> |
							<strong>Line:</strong> <?php echo $trace['line'] ?>
						</p>
					</li>
				<?php endforeach; ?>
				</ul>
			</div>

		</div>

	</div><!-- #content -->

	<footer>
		<p id="elusive" class="credits">The Elusive Framework was created by <a href="http://www.elusive-concepts.com" target="_blank">Elusive Concepts</a></p>
	</footer>

</div>

<?php /*$this->render('console');*/ ?>
<?php echo \elusive\lib\Events::dispatch('TEMPLATE', 'BEFORE_HTML_END', ''); ?>

</body>
</html>
