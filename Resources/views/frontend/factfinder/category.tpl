{block name='frontend_index_start'}
  {$smarty.block.parent}
  {$facets = ['FACT-Finder ASN']}
  {$ffAddParams = {','|implode:$ffCategoryPath|cat:',navigation=true'}}
  {$searchImmediate = true}
  {$showListing = true}
{/block}

{block name='frontend_listing_actions_filter_form'}
  <div data-filter-form="true">
    {include file='frontend/factfinder/content/asn.tpl'}
    {include file='frontend/factfinder/content/filter_cloud.tpl'}
  </div>
{/block}

{block name='frontend_listing_index_listing'}
  {include file='frontend/factfinder/listing.tpl'}
{/block}
