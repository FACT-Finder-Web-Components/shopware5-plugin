{extends file='parent:frontend/listing/listing.tpl'}

{block name='frontend_listing_top_actions'}
  {include file='frontend/factfinder/content/campaigns.tpl'}
  {include file='frontend/factfinder/content/toolbar.tpl'}
{/block}

{block name='frontend_listing_list_inline'}
  {include file='frontend/factfinder/content/record_list.tpl' subscribe=true}
{/block}

{block name='frontend_listing_bottom_paging'}
  <div class="listing--bottom-paging">
    {include file='frontend/factfinder/content/paging.tpl'}
  </div>
{/block}
