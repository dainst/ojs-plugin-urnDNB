{**
 * @file plugins/pubIds/urndnb/templates/urndnbAssignCheckBox.tpl
 *
 * Copyright (c) 2014-2017 Simon Fraser University
 * Copyright (c) 2003-2017 John Willinsky
 * DNB-Mod 2017 by Philipp Franck / DAI
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * Displayed only if the URNdnb can be assigned.
 * Assign URNdnb form check box included in urndnbSuffixEdit.tpl and urndnbAssign.tpl.
 *}
{capture assign=translatedObjectType}{translate key="plugins.pubIds.urndnb.editor.urndnbObjectType"|cat:$pubObjectType}{/capture}
{capture assign=assignCheckboxLabel}{translate key="plugins.pubIds.urndnb.editor.assignURNdnb" pubId=$pubId pubObjectType=$translatedObjectType}{/capture}
{fbvFormSection list=true}
	{fbvElement type="checkbox" id="assignURNdnb" checked="true" value="1" label=$assignCheckboxLabel translate=false disabled=$disabled}
{/fbvFormSection}
