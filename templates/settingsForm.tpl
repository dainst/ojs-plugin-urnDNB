{**
 * plugins/pubIds/urndnb/templates/settingsForm.tpl
 *
 * Copyright (c) 2014-2017 Simon Fraser University
 * Copyright (c) 2003-2017 John Willinsky
 * DNB-Mod 2017 by Philipp Franck / DAI
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * URNdnb plugin settings
 *
 *}

<div id="description">{translate key="plugins.pubIds.urndnb.manager.settings.description"}</div>

<script src="{$baseUrl}/plugins/pubIds/urnDNB/js/URNdnbSettingsFormHandler.js"></script>
<script>
	$(function() {ldelim}
		// Attach the form handler.
		$('#urndnbSettingsForm').pkpHandler('$.pkp.plugins.pubIds.urndnb.js.URNdnbSettingsFormHandler');
	{rdelim});
</script>
<form class="pkp_form" id="urndnbSettingsForm" method="post" action="{url router=$smarty.const.ROUTE_COMPONENT op="manage" category="pubIds" plugin=$pluginName verb="save"}">
	{csrf}
	{include file="common/formErrors.tpl"}

	{fbvFormArea id="urndnbPrefixFormArea" title="plugins.pubIds.urndnb.manager.settings.urndnbPrefix"}
		{fbvFormSection}
			<p class="pkp_help">{translate key="plugins.pubIds.urndnb.manager.settings.urndnbPrefix.description"}</p>
			{fbvElement type="text" id="urndnbPrefix" value=$urndnbPrefix required="true" label="plugins.pubIds.urndnb.manager.settings.urndnbPrefix" maxlength="40" size=$fbvStyles.size.MEDIUM}
		{/fbvFormSection}
	{/fbvFormArea}
	{fbvFormArea id="urndnbSuffixFormArea" title="plugins.pubIds.urndnb.manager.settings.urndnbSuffix"}
		<p class="pkp_help">{translate key="plugins.pubIds.urndnb.manager.settings.urndnbSuffix.description"}</p>
		{fbvFormSection list="true"}
			{if !in_array($urndnbSuffix, array("pattern", "customId"))}
				{assign var="checked" value=true}
			{else}
				{assign var="checked" value=false}
			{/if}
			{fbvElement type="radio" id="urndnbSuffixDefault" name="urndnbSuffix" value="default" label="plugins.pubIds.urndnb.manager.settings.urndnbSuffixDefault" checked=$checked}
			<span class="instruct">{translate key="plugins.pubIds.urndnb.manager.settings.urndnbSuffixDefault.description"}</span>
		{/fbvFormSection}
		{fbvFormSection list="true"}
			{fbvElement type="radio" id="urndnbSuffixCustomId" name="urndnbSuffix" value="customId" label="plugins.pubIds.urndnb.manager.settings.urndnbSuffixCustomIdentifier" checked=$urndnbSuffix|compare:"customId"}
		{/fbvFormSection}
		{fbvFormSection list="true"}
			{fbvElement type="radio" id="urndnbSuffixPattern" name="urndnbSuffix" value="pattern" label="plugins.pubIds.urndnb.manager.settings.urndnbSuffixPattern" checked=$urndnbSuffix|compare:"pattern"}
			<p class="pkp_help">{translate key="plugins.pubIds.urndnb.manager.settings.urndnbSuffixPattern.example"}</p>
			{fbvElement type="text" id="urndnbRepresentationSuffixPattern" value=$urndnbRepresentationSuffixPattern maxlength="40" inline=true size=$fbvStyles.size.MEDIUM}
		{/fbvFormSection}
	{/fbvFormArea}
	{fbvFormArea id="urndnbCheckNoFormArea" title="plugins.pubIds.urndnb.manager.settings.checkNo"}
		{fbvFormSection list="true" }
			{fbvElement type="checkbox" id="urndnbCheckNo" name="urndnbCheckNo" label="plugins.pubIds.urndnb.manager.settings.checkNo.label" checked=$urndnbCheckNo|compare:true}
		{/fbvFormSection}
	{/fbvFormArea}
	{fbvFormArea id="urndnbNamespaceFormArea" title="plugins.pubIds.urndnb.manager.settings.namespace"}
		{fbvFormSection}
			<p class="pkp_help">{translate key="plugins.pubIds.urndnb.manager.settings.namespace.description"}</p>
			{fbvElement type="select" id="urndnbNamespace" required="true" from=$urndnbNamespaces selected=$urndnbNamespace translate=false size=$fbvStyles.size.MEDIUM label="plugins.pubIds.urndnb.manager.settings.namespace"}
		{/fbvFormSection}
	{/fbvFormArea}
	{fbvFormArea id="urndnbResolverFormArea" title="plugins.pubIds.urndnb.manager.settings.urndnbResolver"}
		{fbvFormSection}
			<p class="pkp_help">{translate key="plugins.pubIds.urndnb.manager.settings.urndnbResolver.description"}</p>
			{fbvElement type="text" id="urndnbResolver" value=$urndnbResolver required="true" label="plugins.pubIds.urndnb.manager.settings.urndnbResolver"}
		{/fbvFormSection}
	{/fbvFormArea}
	{fbvFormArea id="urndnbReassignFormArea" title="plugins.pubIds.urndnb.manager.settings.urndnbReassign"}
		{fbvFormSection}
			<span class="instruct">{translate key="plugins.pubIds.urndnb.manager.settings.urndnbReassign.description"}</span><br/>
			{include file="linkAction/linkAction.tpl" action=$clearPubIdsLinkAction contextId="urndnbSettingsForm"}
		{/fbvFormSection}
	{/fbvFormArea}
	{fbvFormButtons submitText="common.save"}
</form>
<p><span class="formRequired">{translate key="common.requiredField"}</span></p>
