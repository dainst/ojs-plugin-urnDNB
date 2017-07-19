{**
 * @file plugins/pubIds/urndnb/templates/urndnbAssign.tpl
 *
 * Copyright (c) 2014-2017 Simon Fraser University
 * Copyright (c) 2003-2017 John Willinsky
 * DNB-Mod 2017 by Philipp Franck / DAI
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * Assign URNdnb to an object option.
 *}

{assign var=pubObjectType value=$pubIdPlugin->getPubObjectType($pubObject)}
{assign var=enableObjectURNdnb value=$pubIdPlugin->isObjectTypeEnabled($pubIdPlugin->getPubObjectType($pubObject), $currentContext->getId())}

{if $enableObjectURNdnb}
	{fbvFormArea id="pubIdURNdnbFormArea" class="border" title="plugins.pubIds.urndnb.editor.urndnb"}
	{if $pubObject->getStoredPubId($pubIdPlugin->getPubIdType())}
		{fbvFormSection}
			<p class="pkp_help">{translate key="plugins.pubIds.urndnb.editor.assignURNdnb.assigned" pubId=$pubObject->getStoredPubId($pubIdPlugin->getPubIdType())}</p>
		{/fbvFormSection}
	{else}
		{assign var=pubId value=$pubIdPlugin->getPubId($pubObject)}
		{if !$canBeAssigned}
			{fbvFormSection}
				{if !$pubId}
					<p class="pkp_help">{translate key="plugins.pubIds.urndnb.editor.assignURNdnb.emptySuffix"}</p>
				{else}
					<p class="pkp_help">{translate key="plugins.pubIds.urndnb.editor.assignURNdnb.pattern" pubId=$pubId}</p>
				{/if}
			{/fbvFormSection}
		{else}
			{assign var=templatePath value=$pubIdPlugin->getTemplatePath()}
			{include file="`$templatePath`urndnbAssignCheckBox.tpl" pubId=$pubId pubObjectType=$pubObjectType}
		{/if}
	{/if}
	{/fbvFormArea}
{/if}
