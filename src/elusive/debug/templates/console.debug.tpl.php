<?php use elusive\debug\Debug; ?>

<?php

$total_execution = Debug::get_benchmark('execution_time', 'Debug console render output...');
$timer           = (isset($total_execution['TIMER_STOP']['TIME'])) ? $total_execution['TIMER_STOP']['TIME'] : "Timers";
$error_count     = (count($this->errors['general']) == 1) ? '1 Error' : count($this->errors['general']).' Errors';

?>

<div id="debug_console">
	<ul class="debug_nav">
		<li class="logo">
			<span id="ec_logo" class="elusive-icon-logo"></span>
			<?php if($error_count > 0) : ?> <span class="error-flag elusive-icon-warning"></span><?php endif; ?>
		</li>
		<li class="title">Elusive Debug Console</li>
		<li class="request" data-panel="request"><span class="elusive-icon-sphere"></span> Request</li>
		<li class="logs"    data-panel="logs"   ><span class="elusive-icon-stack"></span> Logs</li>
		<li class="events"  data-panel="events" ><span class="elusive-icon-flag"></span> Events</li>
		<li class="errors"  data-panel="errors" ><span class="elusive-icon-warning"></span> <?= $error_count ?></li>
		<li class="timeers" data-panel="timers" ><span class="elusive-icon-stopwatch"></span> <?= $timer ?></li>
		<li class="close" onclick="Elusive.console.close()">
			<span class="elusive-icon-cancel"></span><span class="text"> Close</span>
		</li>
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
				<td><?= $log['time'] ?><br></td>
				<td>
					<?php echo str_replace(PATH_ROOT, '', $log['file']); ?>
					(L:<?= $log['line'] ?>)
				</td>
			</tr>
			<tr>
				<td colspan="2"><pre><?php print_r($log['data']) ?></pre></td>
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
<script src="https://framework.elusive-concepts.com/js/console.js"></script>
