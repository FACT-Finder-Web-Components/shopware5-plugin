{extends file='parent:frontend/index/index.tpl'}

{block name='frontend_index_start'}
  {$smarty.block.parent}
  {$facets = ['FACT-Finder ASN']}
  {$searchImmediate = true}
  {$showListing = true}

  {s name='SearchResultTitle' assign='snippetSearchResultTitle' namespace='frontend/omikron/factfinder'}Search Result{/s}
  {$sBreadcrumb = [['name' => $snippetSearchResultTitle]]}
{/block}

{block name='frontend_factfinder_search_redirect'}{/block}

{block name='frontend_index_body_classes'}{$smarty.block.parent} is--ctl-search{/block}

{block name='frontend_listing_actions_filter_form'}
  <div data-filter-form="true">
    {include file='frontend/factfinder/content/asn.tpl'}
    {include file='frontend/factfinder/content/filter_cloud.tpl'}
  </div>
{/block}

{block name='frontend_index_content'}
  <div class="content search--content">
    {block name='frontend_factfinder_headline'}
      <ff-template scope="result" unresolved>
        <h1 class="search--headline">{s name='SearchHeadline' namespace='frontend/omikron/factfinder'}{/s}</h1>
      </ff-template>
    {/block}

    {block name='frontend_search_sidebar'}
      {include file='frontend/listing/sidebar.tpl'}
    {/block}

    {block name='frontend_search_results'}
      <div class="search--results">
        {include file='frontend/factfinder/listing.tpl'}
      </div>
    {/block}
  </div>
{/block}
