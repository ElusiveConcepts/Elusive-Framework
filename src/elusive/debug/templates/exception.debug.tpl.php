<!DOCTYPE html>
<html lang="en">
<head>
	<title>Server Error: Application Exception</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">

	<link rel="stylesheet" type="text/css" href="https://framework.elusive-concepts.com/css/elusive-framework-icons.css">
	<link rel="stylesheet" type="text/css" href="https://framework.elusive-concepts.com/css/ec_source.css">
	<link rel="stylesheet" type="text/css" href="https://framework.elusive-concepts.com/css/exception.css">

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
				<dt><h3 class="icon error"><?= $this->errors['exception']['type'] ?> (<?= $this->errors['exception']['code'] ?>)</h3></dt>
				<dd>
					<p class="err_msg"><?= $this->errors['exception']['msg'] ?></p>
					<?php $this->create_snippet($this->errors['exception']['file'], $this->errors['exception']['line']) ?>
					<p class="fileinfo">
						<span class="file"><strong>File:</strong> <?= str_replace(PATH_ROOT, '', $this->errors['exception']['file']) ?></span>
						<span class="line"><strong>Line:</strong> <?= $this->errors['exception']['line'] ?></span>
					</p>
				</dd>
			</dl>
		</div>

		<div class="stack_trace">

			<h2>Stack Trace:</h2>
			<ul>
			<?php foreach($this->traces['stack'] as $k => $trace) : ?>
				<li>
					<h3><?= $trace['class'] . $trace['type'] . $trace['function'] ?></h3>
					<?= $this->create_snippet($trace['file'], $trace['line']) ?>
					<div class="args">
						<div class="button args_toggle" onclick="Elusive.toggle_code('args_<?=$k?>')">Arguments</div>
						<pre id="args_<?=$k?>" style="display:none;"><?= print_r($trace['args'],1) ?></pre>
					</div>
					<p class="fileinfo">
						<span class="file"><strong>File:</strong> <?= str_replace(PATH_ROOT, '', $trace['file']) ?></span>
						<span class="line"><strong>Line:</strong> <?= $trace['line'] ?></span>
					</p>
				</li>
			<?php endforeach; ?>
			</ul>
		</div>

	</div><!-- #content -->

	<footer>
		<p id="elusive" class="credits">The Elusive Framework was created by <a class="icon logo" href="https://elusive-concepts.com" target="_blank">Elusive Concepts</a></p>
	</footer>

</div>

<script type="text/javascript" src="https://framework.elusive-concepts.com/js/ec_render_source.js"></script>
<script type="text/javascript" src="https://framework.elusive-concepts.com/js/exception.js"></script>

<?php echo \elusive\lib\Events::dispatch('TEMPLATE', 'BEFORE_HTML_END', ''); ?>

</body>
</html>
