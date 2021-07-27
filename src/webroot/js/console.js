/*==[ ELUSIVE NAMESPACE ]==*/
(function(Elusive)
{
	/** @type {Object} console element */
	let _console = null;

	/** @type {Object} console panel container element */
	let _panels = null;

	/** @type {Object} console nav container element */
	let _nav = null;

	/** @type {Boolean} true if source code rendering is active */
	Elusive.source = Elusive.source || false;

	/**
	 * Elusive Console Object
	 *
	 * @type {Object}
	 *
	 * @property {Boolean} active true if the console is active
	 *
	 * @method close close the console panel
	 * @method open  open the console panel
	 */
	Elusive.console = {
		'active' : false,
		'open'   : function() { _console.classList.add('open'); },
		'close'  : function()
		{
			_console.classList.remove('open');

			let links = _nav.querySelectorAll('.active');

			links.forEach(function(link) { link.classList.remove('active'); });
		}
	};


	/**
	 * Console Initialization
	 *
	 * @return {Void}
	 */
	const _init = function()
	{
		_import('https://framework.elusive-concepts.com/css/elusive-framework-icons.css', 'css');
		_import('https://framework.elusive-concepts.com/css/console.css', 'css');
		_import('https://framework.elusive-concepts.com/css/ec_source.css', 'css');
		_import('//fonts.googleapis.com/css?family=Lato', 'css');

		if(typeof(Elusive.renderer) == "undefined")
		{
			_import('https://framework.elusive-concepts.com/js/ec_render_source.js', 'script')
			.then(function()
			{
				if(Elusive.source == false)
				{
					Elusive.render_source();
					Elusive.source = true;
				}
			});
		}

		_console = document.getElementById('debug_console');

		if(_console)
		{
			Elusive.console.active = true;

			_nav    = _console.querySelector('ul.debug_nav');
			_panels = _console.querySelector('#debug_console_panels');

			let links = _nav.querySelectorAll('[data-panel]');

			if(links)
			{
				links.forEach(function(link)
				{
					link.addEventListener('click', _navItem, false);
				});
			}

			console.log("Elusive Framework: Debugging Console Loaded.");
		}
	}


	/**
	 * Handle clicking a nav link
	 *
	 * @param  {Event} evt event object
	 *
	 * @return {Void}
	 */
	const _navItem = function(evt)
	{
	    evt.preventDefault();

		Elusive.console.open();

		let links  = _nav.querySelectorAll('.active');
		let panels = _panels.querySelectorAll('.active');

		links.forEach(function(l)  { l.classList.remove('active'); });
		panels.forEach(function(p) { p.classList.remove('active'); });

		this.classList.add('active');

		let panel = _panels.querySelector('#debug_panel_' + this.dataset.panel);

		if(panel) { panel.classList.add('active'); }
	}


	/**
	 * Import a script or css file
	 *
	 * @param  {String} url file url
	 * @param  {String} type file type (default=script)
	 *
	 * @return {Promise}
	 */
	const _import = function(url, type)
	{
		type = type || 'script';

		let el = null;

		return new Promise(function(resolve, reject)
		{
			switch(type)
			{
				case 'script':
					el = document.createElement('script');
					el.onload  = resolve;
					el.onerror = reject;
					el.async   = true;
					el.src     = url;
					document.body.appendChild(el);
					break;

				case 'css':
					el = document.createElement('link');
					el.onload  = resolve;
					el.onerror = reject;
					el.rel     = 'stylesheet';
					el.type    = 'text/css';
					el.href    = url;
					document.head.appendChild(el);
					break;

				default:
					reject('Unknown file type for import.');
			}
		});
	}

	// Initialize the module on DOM ready
	document.addEventListener("DOMContentLoaded", _init);

}(window.Elusive = window.Elusive || {}));
