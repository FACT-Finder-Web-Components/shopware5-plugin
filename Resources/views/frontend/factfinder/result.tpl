{extends file='frontend/listing/index.tpl'}

{block name='frontend_index_start' append}
  {$facets = ['FACT-Finder ASN']}
  {$searchImmediate = true}
  {$showListing = true}

  {s name='SearchResultTitle' assign='snippetSearchResultTitle' namespace='frontend/omikron/factfinder'}Search Result{/s}
  {$sBreadcrumb = [['name' => $snippetSearchResultTitle]]}
{/block}

{block name='frontend_factfinder_search_redirect'}
  {if $Controller neq 'factfinder'}{$smarty.block.parent}{/if}
{/block}

{block name='frontend_index_body_classes' append} is--ctl-search{/block}

{block name='frontend_listing_top_actions'}
  {include file='frontend/factfinder/toolbar.tpl'}
{/block}

{block name="frontend_listing_actions_filter_form"}
  <div data-filter-form="true">
    {include file='frontend/factfinder/asn.tpl'}
    {include file='frontend/factfinder/filter_cloud.tpl'}
  </div>
{/block}

{block name='frontend_listing_index_text'}
  {block name='frontend_search_headline'}
    <ff-template scope="result" unresolved>
      <h1 class="search--headline">{s name='SearchHeadline' namespace='frontend/omikron/factfinder'}{/s}</h1>
    </ff-template>
  {/block}
{/block}

{block name='frontend_listing_index_topseller'}
  {include file='frontend/factfinder/campaigns.tpl'}
{/block}

{block name='frontend_listing_list_inline'}
  {include file='frontend/factfinder/record_list.tpl' subscribe=true}
{/block}

{block name="frontend_listing_bottom_paging"}
  <div class="listing--bottom-paging">
    {include file="frontend/factfinder/paging.tpl"}
  </div>
{/block}
