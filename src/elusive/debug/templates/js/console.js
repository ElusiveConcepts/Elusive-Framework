/*==[ ELUSIVE NAMESPACE ]==*/
(function(Elusive, $, undefined)
{
	Elusive.source = Elusive.source || false;

	$(function()
	{
		if(Elusive.source == false)
		{
			Elusive.render_source();
			Elusive.source = true;
		}

		$('#debug_console ul.debug_nav a').click(function(event)
		{
		    event.preventDefault();
		    Elusive.console.open();
			$('#debug_console ul.debug_nav a').removeClass('active');
			$(this).addClass('active');
			$('#debug_console_panels .debug_panel').hide();
			$('#debug_panel_' + $(this).attr('rel')).show();
		});
	});

	Elusive.console = {
		'open': function()
		{
			$('#debug_console').addClass('open');
		},

		'close': function()
		{
			$('#debug_console ul.debug_nav a').removeClass('active');
			$('#debug_console').removeClass('open');
		}
	}

}(window.Elusive = window.Elusive || {}, jQuery));
