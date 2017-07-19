<?php

/**
 * @file plugins/pubIds/urndnb/URNdnbPubIdPlugin.inc.php
 *
 * Copyright (c) 2014-2017 Simon Fraser University
 * Copyright (c) 2003-2017 John Willinsky \n * DNB-Mod 2017 by Philipp Franck / DAI
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * @class URNdnbPubIdPlugin
 * @ingroup plugins_pubIds_urndnb
 *
 * @brief URNdnb plugin class
 */


import('classes.plugins.PubIdPlugin');

class URNdnbPubIdPlugin extends PubIdPlugin {

	//
	// Implement template methods from Plugin.
	//
	/**
	 * @copydoc Plugin::getDisplayName()
	 */
	function getDisplayName() {
		return __('plugins.pubIds.urndnb.displayName');
	}

	/**
	 * @copydoc Plugin::getDescription()
	 */
	function getDescription() {
		return __('plugins.pubIds.urndnb.description');
	}

	/**
	 * @copydoc Plugin::getTemplatePath()
	 */
	function getTemplatePath($inCore = false) {
		return parent::getTemplatePath($inCore) . 'templates/';
	}


	//
	// Implement template methods from PubIdPlugin.
	//
	/**
	 * @copydoc PKPPubIdPlugin::constructPubId()
	 */
	function constructPubId($pubIdPrefix, $pubIdSuffix, $contextId) {
		//error_log("[constructPubId]" . implode('|', array($pubIdPrefix, $pubIdSuffix)));
		$urndnb = $pubIdPrefix . $pubIdSuffix;
		$suffixFieldName = $this->getSuffixFieldName();
		$suffixGenerationStrategy = $this->getSetting($contextId, $suffixFieldName);
		// checkNo is already calculated for custom suffixes
		if ($suffixGenerationStrategy != 'customId' && $this->getSetting($contextId, 'urndnbCheckNo')) {
			$urndnb .= $this->_calculateCheckNo($urndnb);
		}
		error_log("[constructPubId]$urndnb");
		return $urndnb;
	}

	function getPubId($pubObject) {

		// double check if this is a galley
		if ($this->getPubObjectType($pubObject) !== 'Representation') {
			error_log('[getPubId] not Representation');
			return null;
		}

		// concerning DNB policy URNs can only be assigned to galleys
		// in the article main language
		$ArticleDAO = DAORegistry::getDAO('ArticleDAO');
		$article = $ArticleDAO->getById($pubObject->getSubmissionId(), null, true);
		if (!$article) {
			error_log('[getPubId] no article found');
			return null;
		}
		$galleyLocale = $pubObject->getLocale();
		$articleLocale  = $article->getLocale();
		if ($galleyLocale !== $articleLocale) {
			error_log('[getPubId] galley locale error');
			return null;
		}

		// continue
		return parent::getPubId($pubObject);
	}



	/**
	 * @copydoc PKPPubIdPlugin::getPubIdType()
	 */
	function getPubIdType() {
		return 'other::urndnb';
	}

	/**
	 * @copydoc PKPPubIdPlugin::getPubIdDisplayType()
	 */
	function getPubIdDisplayType() {
		return 'URN (dnb)';
	}

	/**
	 * @copydoc PKPPubIdPlugin::getPubIdFullName()
	 */
	function getPubIdFullName() {
		return 'Uniform Resource Name';
	}

	/**
	 * @copydoc PKPPubIdPlugin::getResolvingURL()
	 */
	function getResolvingURL($contextId, $pubId) {
		$resolverURL = $this->getSetting($contextId, 'urndnbResolver');
		return $resolverURL . $pubId;
	}

	/**
	 * @copydoc PKPPubIdPlugin::getPubIdMetadataFile()
	 */
	function getPubIdMetadataFile() {
		return $this->getTemplatePath().'urndnbSuffixEdit.tpl';
	}

	/**
	 * @copydoc PKPPubIdPlugin::addJavaScript()
	 */
	function addJavaScript($request, $templateMgr) {
		$templateMgr->addJavaScript(
			'urndnbCheckNo',
			$request->getBaseUrl() . DIRECTORY_SEPARATOR . $this->getPluginPath() . DIRECTORY_SEPARATOR . 'js' . DIRECTORY_SEPARATOR . 'checkNumber.js',
			array(
				'inline' => false,
				'contexts' => 'publicIdentifiersForm',
			)
		);
	}

	/**
	 * @copydoc PKPPubIdPlugin::getPubIdAssignFile()
	 */
	function getPubIdAssignFile() {
		return $this->getTemplatePath().'urndnbAssign.tpl';
	}

	/**
	 * @copydoc PKPPubIdPlugin::instantiateSettingsForm()
	 */
	function instantiateSettingsForm($contextId) {
		$this->import('classes.form.URNdnbSettingsForm');
		return new URNdnbSettingsForm($this, $contextId);
	}

	/**
	 * @copydoc PKPPubIdPlugin::getFormFieldNames()
	 */
	function getFormFieldNames() {
		return array('urndnbSuffix');
	}

	/**
	 * @copydoc PKPPubIdPlugin::getAssignFormFieldName()
	 */
	function getAssignFormFieldName() {
		return 'assignURNdnb';
	}

	/**
	 * @copydoc PKPPubIdPlugin::getPrefixFieldName()
	 */
	function getPrefixFieldName() {
		return 'urndnbPrefix';
	}

	/**
	 * @copydoc PKPPubIdPlugin::getSuffixFieldName()
	 */
	function getSuffixFieldName() {
		return 'urndnbSuffix';
	}

	/**
	 * @copydoc PKPPubIdPlugin::getLinkActions()
	 */
	function getLinkActions($pubObject) {
		$linkActions = array();
		import('lib.pkp.classes.linkAction.request.RemoteActionConfirmationModal');
		$application = PKPApplication::getApplication();
		$request = $application->getRequest();
		$userVars = $request->getUserVars();
		$userVars['pubIdPlugIn'] = get_class($this);
		// Clear object pub id
		$linkActions['clearPubIdLinkActionURNdnb'] = new LinkAction(
			'clearPubId',
			new RemoteActionConfirmationModal(
				$request->getSession(),
				__('plugins.pubIds.urndnb.editor.clearObjectsURNdnb.confirm'),
				__('common.delete'),
				$request->url(null, null, 'clearPubId', null, $userVars),
				'modal_delete'
			),
			__('plugins.pubIds.urndnb.editor.clearObjectsURNdnb'),
			'delete',
			__('plugins.pubIds.urndnb.editor.clearObjectsURNdnb')
		);

		if (is_a($pubObject, 'Issue')) {
			// Clear issue objects pub ids
			$linkActions['clearIssueObjectsPubIdsLinkActionURNdnb'] = new LinkAction(
				'clearObjectsPubIds',
				new RemoteActionConfirmationModal(
					$request->getSession(),
					__('plugins.pubIds.urndnb.editor.clearIssueObjectsURNdnb.confirm'),
					__('common.delete'),
					$request->url(null, null, 'clearIssueObjectsPubIds', null, $userVars),
					'modal_delete'
				),
				__('plugins.pubIds.urndnb.editor.clearIssueObjectsURNdnb'),
				'delete',
				__('plugins.pubIds.urndnb.editor.clearIssueObjectsURNdnb')
			);
		}

		return $linkActions;
	}

	/**
	 * @copydoc PKPPubIdPlugin::getSuffixPatternsFieldName()
	 */
	function getSuffixPatternsFieldNames() {
		return  array(
			'Representation' => 'urndnbRepresentationSuffixPattern'
		);
	}

	/**
	 * @copydoc PKPPubIdPlugin::getDAOFieldNames()
	 */
	function getDAOFieldNames() {
		return array('pub-id::other::urndnb');
	}

	/**
	 * @copydoc PKPPubIdPlugin::isObjectTypeEnabled()
	 */
	function isObjectTypeEnabled($pubObjectType, $contextId) {
		return ($pubObjectType === "Representation");
	}

	/**
	 * @copydoc PKPPubIdPlugin::isObjectTypeEnabled()
	 */
	function getNotUniqueErrorMsg() {
		return __('plugins.pubIds.urndnb.editor.urndnbSuffixCustomIdentifierNotUnique');
	}

	//
	// Private helper methods
	//
	/**
	 * Get the last, check number.
	 * Algorithm (s. http://www.persistent-identifier.de/?link=316):
	 *  every URNdnb character is replaced with a number according to the conversion table,
	 *  every number is multiplied by it's position/index (beginning with 1),
	 *  the numbers' sum is calculated,
	 *  the sum is divided by the last number,
	 *  the last number of the quotient before the decimal point is the check number.
	 */
	function _calculateCheckNo($urndnb) {
	    $urndnbLower = strtolower_codesafe($urndnb);

	    $conversionTable = array('9' => '41', '8' => '9', '7' => '8', '6' => '7', '5' => '6', '4' => '5', '3' => '4', '2' => '3', '1' => '2', '0' => '1', 'a' => '18', 'b' => '14', 'c' => '19', 'd' => '15', 'e' => '16', 'f' => '21', 'g' => '22', 'h' => '23', 'i' => '24', 'j' => '25', 'k' => '42', 'l' => '26', 'm' => '27', 'n' => '13', 'o' => '28', 'p' => '29', 'q' => '31', 'r' => '12', 's' => '32', 't' => '33', 'u' => '11', 'v' => '34', 'w' => '35', 'x' => '36', 'y' => '37', 'z' => '38', '-' => '39', ':' => '17', '_' => '43', '/' => '45', '.' => '47', '+' => '49');

	    $newURNdnb = '';
	    for ($i = 0; $i < strlen($urndnbLower); $i++) {
	    	$char = $urndnbLower[$i];
	    	$newURNdnb .= $conversionTable[$char];
	    }
	    $sum = 0;
	    for ($j = 1; $j <= strlen($newURNdnb); $j++) {
		    $sum = $sum + ($newURNdnb[$j-1] * $j);
	    }

	    $lastNumber = $newURNdnb[strlen($newURNdnb)-1];
	    $quot = $sum / $lastNumber;
	    $quotRound = floor($quot);
	    $quotString = (string)$quotRound;

	    return $quotString[strlen($quotString)-1];
	}
}

?>
