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
	});

}(window.Elusive = window.Elusive || {}, jQuery));
