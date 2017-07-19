<?php

/**
 * @file plugins/pubIds/urndnb/classes/form/URNdnbSettingsForm.inc.php
 *
 * Copyright (c) 2014-2017 Simon Fraser University
 * Copyright (c) 2003-2017 John Willinsky John Willinsky
 * DNB-Mod 2017 by Philipp Franck / DAI
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * @class URNdnbSettingsForm
 * @ingroup plugins_pubIds_urndnb
 *
 * @brief Form for Journal managers to setup URNdnb plugin
 */


import('lib.pkp.classes.form.Form');

class URNdnbSettingsForm extends Form {

	//
	// Private properties
	//
	/** @var integer */
	var $_contextId;

	/**
	 * Get the context ID.
	 * @return integer
	 */
	function _getContextId() {
		return $this->_contextId;
	}

	/** @var URNdnbPubIdPlugin */
	var $_plugin;

	/**
	 * Get the plugin.
	 * @return URNdnbPubIdPlugin
	 */
	function _getPlugin() {
		return $this->_plugin;
	}

	//
	// Constructor
	//
	/**
	 * Constructor
	 * @param $plugin URNdnbPubIdPlugin
	 * @param $contextId integer
	 */
	function __construct($plugin, $contextId) {
		$this->_contextId = $contextId;
		$this->_plugin = $plugin;

		parent::__construct($plugin->getTemplatePath() . 'settingsForm.tpl');

		//$this->addCheck(new FormValidatorCustom($this, 'urndnbObjects', 'required', 'plugins.pubIds.urndnb.manager.settings.urndnbObjectsRequired', create_function('$enableIssueURNdnb,$form', 'return $form->getData(\'enableIssueURNdnb\') || $form->getData(\'enableSubmissionURNdnb\') || $form->getData(\'enableRepresentationURNdnb\');'), array($this)));
		$this->addCheck(new FormValidatorRegExp($this, 'urndnbPrefix', 'required', 'plugins.pubIds.urndnb.manager.settings.form.urndnbPrefixPattern', '/^urn:[a-zA-Z0-9-]*:.*/'));
		//$this->addCheck(new FormValidatorCustom($this, 'urndnbRepresentationSuffixPattern', 'required', 'plugins.pubIds.urndnb.manager.settings.form.urndnbRepresentationSuffixPatternRequired', create_function('$urndnbRepresentationSuffixPattern,$form', 'if ($form->getData(\'urndnbSuffix\') == \'pattern\' && $form->getData(\'enableRepresentationURNdnb\')) return $urndnbRepresentationSuffixPattern != \'\';return true;'), array($this)));
		$this->addCheck(new FormValidatorUrl($this, 'urndnbResolver', 'required', 'plugins.pubIds.urndnb.manager.settings.form.urndnbResolverRequired'));
		$this->addCheck(new FormValidatorPost($this));
		$this->addCheck(new FormValidatorCSRF($this));

		// for URNdnb reset requests
		import('lib.pkp.classes.linkAction.request.RemoteActionConfirmationModal');
		$application = PKPApplication::getApplication();
		$request = $application->getRequest();
		$this->setData('clearPubIdsLinkAction', new LinkAction(
			'reassignURNdnbs',
			new RemoteActionConfirmationModal(
				$request->getSession(),
				__('plugins.pubIds.urndnb.manager.settings.urndnbReassign.confirm'),
				__('common.delete'),
				$request->url(null, null, 'manage', null, array('verb' => 'clearPubIds', 'plugin' => $plugin->getName(), 'category' => 'pubIds')),
				'modal_delete'
			),
			__('plugins.pubIds.urndnb.manager.settings.urndnbReassign'),
			'delete'
		));
		$this->setData('pluginName', $plugin->getName());
	}


	//
	// Implement template methods from Form
	//
	/**
	 * @copydoc Form::fetch()
	 */
	function fetch($request) {
		$urndnbNamespaces = array(
			'' => '',
			'urn:nbn:de' => 'urn:nbn:de',
			'urn:nbn:at' => 'urn:nbn:at',
			'urn:nbn:ch' => 'urn:nbn:ch',
			'urn:nbn' => 'urn:nbn',
			'urn' => 'urn'
		);
		$templateMgr = TemplateManager::getManager($request);
		$templateMgr->assign('urndnbNamespaces', $urndnbNamespaces);
		return parent::fetch($request);
	}

	/**
	 * @copydoc Form::initData()
	 */
	function initData() {
		$contextId = $this->_getContextId();
		$plugin = $this->_getPlugin();
		foreach($this->_getFormFields() as $fieldName => $fieldType) {
			$this->setData($fieldName, $plugin->getSetting($contextId, $fieldName));
		}
	}

	/**
	 * @copydoc Form::readInputData()
	 */
	function readInputData() {
		$this->readUserVars(array_keys($this->_getFormFields()));
	}

	/**
	 * @copydoc Form::validate()
	 */
	function execute() {
		$contextId = $this->_getContextId();
		$plugin = $this->_getPlugin();
		foreach($this->_getFormFields() as $fieldName => $fieldType) {
			$plugin->updateSetting($contextId, $fieldName, $this->getData($fieldName), $fieldType);
		}
	}

	//
	// Private helper methods
	//
	function _getFormFields() {
		return array(
			'enableRepresentationURNdnb' => 'bool',
			'urndnbPrefix' => 'string',
			'urndnbSuffix' => 'string',
			'urndnbRepresentationSuffixPattern' => 'string',
			'urndnbCheckNo' => 'bool',
			'urndnbNamespace' => 'string',
			'urndnbResolver' => 'string',
		);
	}
}

?>
