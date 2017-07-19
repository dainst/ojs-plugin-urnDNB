{**
 * plugins/pubIds/urndnb/templates/urndnbSuffixEdit.tpl
 *
 * Copyright (c) 2014-2017 Simon Fraser University
 * Copyright (c) 2003-2017 John Willinsky
 * DNB-Mod 2017 by Philipp Franck / DAI
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * Edit custom URNdnb suffix for an object (issue, submission, file)
 *
 *}
{load_script context="publicIdentifiersForm" scripts=$scripts}

{assign var=pubObjectType value=$pubIdPlugin->getPubObjectType($pubObject)}
{assign var=enableObjectURNdnb value=$pubIdPlugin->isObjectTypeEnabled($pubIdPlugin->getPubObjectType($pubObject), $currentContext->getId())}
{if $enableObjectURNdnb}
	{assign var=storedPubId value=$pubObject->getStoredPubId($pubIdPlugin->getPubIdType())}
	{fbvFormArea id="pubIdURNdnbFormArea" class="border" title="plugins.pubIds.urndnb.editor.urndnb"}
		{assign var=formArea value=true}
		{if $pubIdPlugin->getSetting($currentJournal->getId(), 'urndnbSuffix') == 'customId' || $storedPubId}
			{if empty($storedPubId)} {* edit custom suffix *}
				{fbvFormSection}
					{assign var=checkNo value=$pubIdPlugin->getSetting($currentContext->getId(), 'urndnbCheckNo')}
					<p class="pkp_help">{translate key="plugins.pubIds.urndnb.manager.settings.urndnbSuffix.description"}</p>
					{fbvElement type="text" label="plugins.pubIds.urndnb.manager.settings.urndnbPrefix" id="urndnbPrefix" disabled=true value=$pubIdPlugin->getSetting($currentContext->getId(), 'urndnbPrefix') size=$fbvStyles.size.SMALL inline=true }
					{fbvElement type="text" label="plugins.pubIds.urndnb.manager.settings.urndnbSuffix" id="urndnbSuffix" value=$urndnbSuffix size=$fbvStyles.size.MEDIUM inline=true }
					{if $checkNo}{fbvElement type="button" label="plugins.pubIds.urndnb.editor.addCheckNo" id="checkNo" inline=true}{/if}
				{/fbvFormSection}
				{if $canBeAssigned}
					<p class="pkp_help">{translate key="plugins.pubIds.urndnb.editor.canBeAssigned"}</p>
					{assign var=templatePath value=$pubIdPlugin->getTemplatePath()}
					{include file="`$templatePath`urndnbAssignCheckBox.tpl" pubId="" pubObjectType=$pubObjectType}
				{else}
					<p class="pkp_help">{translate key="plugins.pubIds.urndnb.editor.customSuffixMissing"}</p>
				{/if}
			{else} {* stored pub id and clear option *}
				<p>
					{$storedPubId|escape}<br />
					{capture assign=translatedObjectType}{translate key="plugins.pubIds.urndnb.editor.urndnbObjectType"|cat:$pubObjectType}{/capture}
					{capture assign=assignedMessage}{translate key="plugins.pubIds.urndnb.editor.assigned" pubObjectType=$translatedObjectType}{/capture}
					<p class="pkp_help">{$assignedMessage}</p>
					{include file="linkAction/linkAction.tpl" action=$clearPubIdLinkActionURNdnb contextId="publicIdentifiersForm"}
				</p>
			{/if}
		{else} {* pub id preview *}
			<p>{$pubIdPlugin->getPubId($pubObject)|escape}</p>
			{if $canBeAssigned}
				<p class="pkp_help">{translate key="plugins.pubIds.urndnb.editor.canBeAssigned"}</p>
				{assign var=templatePath value=$pubIdPlugin->getTemplatePath()}
				{include file="`$templatePath`urndnbAssignCheckBox.tpl" pubId="" pubObjectType=$pubObjectType}
			{else}
				<p class="pkp_help">{translate key="plugins.pubIds.urndnb.editor.patternNotResolved"}</p>
			{/if}
		{/if}
	{/fbvFormArea}
{/if}
{* issue pub object *}
{if $pubObjectType == 'Issue'}
	{assign var=enableSubmissionURNdnb value=$pubIdPlugin->getSetting($currentContext->getId(), "enableSubmissionURNdnb")}
	{assign var=enableRepresentationURNdnb value=$pubIdPlugin->getSetting($currentContext->getId(), "enableRepresentationURNdnb")}
	{if $enableSubmissionURNdnb || $enableRepresentationURNdnb}
		{if !$formArea}
			{assign var="formAreaTitle" value="plugins.pubIds.urndnb.editor.urndnb"}
		{else}
			{assign var="formAreaTitle" value=""}
		{/if}
		{fbvFormArea id="pubIdURNdnbIssueobjectsFormArea" class="border" title=$formAreaTitle}
			{fbvFormSection list="true" description="plugins.pubIds.urndnb.editor.clearIssueObjectsURNdnb.description"}
				{include file="linkAction/linkAction.tpl" action=$clearIssueObjectsPubIdsLinkActionURNdnb contextId="publicIdentifiersForm"}
			{/fbvFormSection}
		{/fbvFormArea}
	{/if}
{/if}
