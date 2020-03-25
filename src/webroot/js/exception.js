/*==[ ELUSIVE NAMESPACE ]==*/
(function(Elusive)
{
	/** @type {Boolean} true if source code rendering is active */
	Elusive.source = Elusive.source || false;

	/**
	 * Console Initialization
	 *
	 * @return {Void}
	 */
	const _init = function()
	{
		if(Elusive.source == false)
		{
			Elusive.render_source();
			Elusive.source = true;
		}
	}

	// Initialize the module on DOM ready
	document.addEventListener("DOMContentLoaded", _init);

}(window.Elusive = window.Elusive || {}));
