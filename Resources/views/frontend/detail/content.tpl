{extends file='parent:frontend/detail/content.tpl'}

{block name="frontend_detail_index_header_container"}
  {$smarty.block.parent}

  {if $ffFeatureFlags.campaign}
    {block name="frontend_factfinder_product_campaign_feedbacktext"}
      <ff-campaign-feedbacktext is-product-campaign unresolved>{'{{{text}}}'}</ff-campaign-feedbacktext>
    {/block}
  {/if}
{/block}

{block name="frontend_detail_index_tabs_cross_selling"}
  {$smarty.block.parent}
  {if $ffFeatureFlags.campaign}
    <ff-campaign-product record-id="{$sArticle.mainVariantNumber}"></ff-campaign-product>
  {/if}
  {if $ffFeatureFlags.recommendation}
    {block name="frontend_factfinder_detail_recommendation"}
      {include file='frontend/factfinder/content/recommendation.tpl'}
    {/block}
  {/if}
{/block}
