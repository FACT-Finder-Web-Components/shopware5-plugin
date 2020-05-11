{extends file="parent:frontend/index/index.tpl"}

{* Prevent Shopware AJAX search *}
{block name='frontend_index_shop_navigation'}
  {capture assign='shopNavigation'}{$smarty.block.parent}{/capture}
  {$shopNavigation|replace:' data-search="true"':''}
{/block}

{block name="frontend_index_after_body" append}
  <ff-communication url="{config name=ffServerUrl}"
                    channel="{config name=ffChannel}"
                    version="{config name=ffVersion}"
                    {if {config name=ffVersion} eq 'ng'}api="v3"{/if}
                    {if $searchImmediate}search-immediate="true"{/if}
                    use-url-parameter="true"
                    only-search-params="true"
                    currency-code="{0|currency:use_shortname:left|substr:0:3}"
                    currency-country-code="{$Locale|replace:"_":"-"}"></ff-communication>
{/block}
