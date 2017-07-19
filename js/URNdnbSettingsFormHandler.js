/**
 * @defgroup plugins_pubIds_urndnb_js
 */
/**
 * @file plugins/pubIds/urndnb/js/URNdnbSettingsFormHandler.js
 *
 * Copyright (c) 2014-2017 Simon Fraser University
 * Copyright (c) 2000-2017 John Willinsky
 * DNB-Mod 2017 by Philipp Franck / DAI
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * @class URNdnbSettingsFormHandler.js
 * @ingroup plugins_pubIds_urndnb_js
 *
 * @brief Handle the URNdnb Settings form.
 */
(function($) {

	/** @type {Object} */
	$.pkp.plugins.pubIds.urndnb = $.pkp.plugins.pubIds.urndnb || { js: { } };


	/**
	 * @constructor
	 *
	 * @extends $.pkp.controllers.form.AjaxFormHandler
	 *
	 * @param {jQueryObject} $form the wrapped HTML form element.
	 * @param {Object} options form options.
	 */
	$.pkp.plugins.pubIds.urndnb.js.URNdnbSettingsFormHandler = function($form, options) {
		this.parent($form, options);

	};


	$.pkp.classes.Helper.inherits(
		$.pkp.plugins.pubIds.urndnb.js.URNdnbSettingsFormHandler,
		$.pkp.controllers.form.AjaxFormHandler
	);



	/** @param {jQuery} $ jQuery closure. */
}(jQuery))
