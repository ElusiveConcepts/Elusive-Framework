/*==[ ELUSIVE NAMESPACE ]==*/
(function(Elusive, $, undefined)
{
	Elusive.source = Elusive.source || false;

	$(function()
	{
		$("<link/>", {rel:"stylesheet", type:"text/css", href:"http://framework.elusive-concepts.com/css/elusive-framework-icons.css" }).appendTo("head");
		$("<link/>", {rel:"stylesheet", type:"text/css", href:"http://framework.elusive-concepts.com/css/console.css" }).appendTo("head");
		$("<link/>", {rel:"stylesheet", type:"text/css", href:"http://framework.elusive-concepts.com/css/ec_source.css" }).appendTo("head");
		$("<link/>", {rel:"stylesheet", type:"text/css", href:"http://fonts.googleapis.com/css?family=Lato" }).appendTo("head");

		if(typeof(Elusive.renderer) == "undefined")
		{
			$.getScript('http://framework.elusive-concepts.com/js/ec_render_source.js', function()
			{
				if(Elusive.source == false)
				{
					Elusive.render_source();
					Elusive.source = true;
				}
			});
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

		console.log("Elusive Framework: Debugging Console Loaded.");
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
