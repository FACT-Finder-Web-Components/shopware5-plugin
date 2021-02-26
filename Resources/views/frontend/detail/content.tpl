{extends file='parent:frontend/detail/content.tpl'}

{block name="frontend_detail_index_header_container"}
  {$smarty.block.parent}

  {block name="frontend_factfinder_product_campaign_feedbacktext"}
    <ff-campaign-feedbacktext is-product-campaign unresolved>{'{{{text}}}'}</ff-campaign-feedbacktext>
  {/block}
{/block}

{block name="frontend_detail_index_tabs_cross_selling"}
  {$smarty.block.parent}

  <ff-campaign-product record-id="{$sArticle.mainVariantNumber}"></ff-campaign-product>
{/block}
