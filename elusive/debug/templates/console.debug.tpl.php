<?php use elusive\debug\Debug; ?>

<?php

$total_execution = Debug::get_benchmark('execution_time', 'Debug console render output...');
$timer           = (isset($total_execution['TIMER_STOP']['TIME'])) ? $total_execution['TIMER_STOP']['TIME'] : "Timers";
$error_count     = (count($this->errors['general']) == 1) ? '1 Error' : count($this->errors['general']).' Errors';

?>

<div id="debug_console">
	<ul class="debug_nav">
		<li class="title">Elusive Debug Console</li>
		<li><a class="icon request" href="#" rel="request">Request</a></li>
		<li><a class="icon log"     href="#" rel="logs"   >Logs</a></li>
		<li><a class="icon event"   href="#" rel="events" >Events</a></li>
		<li><a class="icon warning" href="#" rel="errors" ><?php echo $error_count ?></a></li>
		<li><a class="icon time"    href="#" rel="timers" ><?php echo $timer ?></a></li>
		<li class="icon close" onclick="Elusive.console.close()">Close</li>
	</ul>

	<div id="debug_console_panels">
		<div id="debug_panel_request" class="debug_panel">

			<h3>Application Data:</h3>
			<table class="debug_table">
			<?php foreach($this->request->app as $key => $value) : ?>
			<tr><td><?php echo $key ?>&nbsp;</td><td><?php echo $value ?>&nbsp;</td></tr>
			<?php endforeach; ?>
			</table>

			<h3>Request Data:</h3>
			<?php $this->create_debug_table($this->request->data['GET'],    NULL, '$_GET'); ?>
			<?php $this->create_debug_table($this->request->data['POST'],   NULL, '$_POST'); ?>
			<?php $this->create_debug_table($this->request->data['COOKIE'], NULL, '$_COOKIE'); ?>
			<?php $this->create_debug_table($this->request->data['FILES'],  NULL, '$_FILES'); ?>
			<?php $this->create_debug_table($this->request->data['ENV'],    NULL, '$_SERVER'); ?>

		</div><!-- #debug_panel_request -->

		<div id="debug_panel_logs" class="debug_panel">

			<h3>Logs:</h3>
			<table class="debug_table">
			<tr>
				<th>Time</th>
				<th>Data</th>
			</tr>
			<?php foreach($this->logs as $log) : ?>
			<tr>
				<td><?php echo $log['time'] ?></td>
				<td><pre><?php print_r($log['data']) ?></pre></td>
			</tr>
			<?php endforeach; ?>
			</table>

		</div><!-- #debug_panel_logs -->

		<div id="debug_panel_events" class="debug_panel">

			<h3>Events:</h3>
			<table class="debug_table">
			<tr>
				<th>Event</th>
				<th>Data</th>
				<th>Listeners</th>
			</tr>
			<?php foreach($this->events as $event) : ?>
			<tr>
				<td><?php echo $event['type'] ?> &gt;&gt; <?php echo $event['name'] ?></td>
				<td><?php echo $event['data'] ?></td>
				<td><?php echo $event['listeners'] ?></td>
			</tr>
			<?php endforeach; ?>
			</table>

		</div><!-- #debug_panel_events -->

		<div id="debug_panel_errors" class="debug_panel">

			<h3>Errors:</h3>
			<table class='debug_table'>
			<?php foreach($this->errors['general'] as $error) : ?>
				<?php $this->format_error($error, 'tr'); ?>
			<?php endforeach; ?>
			</table>

		</div><!-- #debug_panel_errors -->

		<div id="debug_panel_timers" class="debug_panel">

			<h3>Benchmarks:</h3>
			<table class="debug_table">
			<tr>
				<th>Benchmark</th>
				<th>Tag</th>
				<th>Time</th>
				<th>Comment</th>
			</tr>
			<?php foreach(Debug::get_benchmarks() as $benchmark => $marks) : ?>
			<?php foreach($marks as $mark => $data) : ?>
			<tr>
				<td><?php echo $benchmark ?></td>
				<td><?php echo $mark ?></td>
				<td><?php echo $data['TIME'] ?></td>
				<td><?php echo $data['COMMENT'] ?></td>
			</tr>
			<?php endforeach; ?>
			<?php endforeach; ?>
			</table>

		</div><!-- #debug_panel_timers -->

	</div>
</div>

<script type="text/javascript">
	if(typeof(jQuery) == 'undefined')
	{
		document.write("<scr" + "ipt type=\"text/javascript\" src=\"//ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js\"></scr" + "ipt>");
		//var script_node = document.createElement('script');
		//script_node.setAttribute("type","text/javascript");
		//script_node.setAttribute("src", "//ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js");
		//document.body.appendChild(script_node);
	}

	//$(function() { });
</script>
<!--script type="text/javascript" src="/elusive/debug/templates/js/console.js"-->
<script type="text/javascript">
	<?php echo @file_get_contents(PATH_ROOT . '/elusive/debug/templates/js/console.js'); ?>
</script>

<style type="text/css">
	<?php echo @file_get_contents(PATH_ROOT . '/elusive/debug/templates/css/elusive-debug-icons.css'); ?>
	<?php echo @file_get_contents(PATH_ROOT . '/elusive/debug/templates/css/console.css'); ?>
	<?php echo @file_get_contents(PATH_ROOT . '/elusive/debug/templates/css/ec_source.css'); ?>
</style>
