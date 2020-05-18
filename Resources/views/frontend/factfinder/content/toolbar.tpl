{extends file='frontend/listing/listing_actions.tpl'}
{namespace name="frontend/listing/listing_actions"}

{block name='frontend_listing_actions_top_hide_detection'}
  {$smarty.block.parent}
  {if !$theme.sidebarFilter}{$class = str_replace(' without-facets', '', $class)}{/if}
  {$class = str_replace(' without-sortings', '', $class)}
{/block}

{block name='frontend_listing_actions_sort_inner'}
  <div class="action--sort action--content block">
    <label class="sort--label action--label">{s name='ListingLabelSort'}{/s}</label>
    <ff-sortbox-select class="sort--select select-field">
      <select class="sort--field action--field"></select>
    </ff-sortbox-select>
  </div>
{/block}

{block name='frontend_listing_actions_paging'}
  {include file="frontend/factfinder/content/paging.tpl"}
{/block}
