{* Paging which will be included in the "listing/listing_actions.tpl" *}
{namespace name="frontend/listing/listing_actions"}

{block name='frontend_listing_actions_paging_inner'}
  <div class="listing--paging panel--paging">

    {* Pagination label *}
    {block name='frontend_listing_actions_paging_label'}{/block}

    <ff-paging unresolved>
      {block name="frontend_listing_actions_paging_first"}
        <ff-paging-item type="firstLink" class="paging--link paging--first">
          <i class="icon--arrow-left"></i>
          <i class="icon--arrow-left"></i>
        </ff-paging-item>
      {/block}

      {block name="frontend_listing_actions_paging_previous"}
        <ff-paging-item type="previousLink" class="paging--link paging--prev">
          <i class="icon--arrow-left"></i>
        </ff-paging-item>
      {/block}

      {block name='frontend_listing_actions_paging_numbers'}
        <ff-paging-item type="currentLink" class="paging--link is--active">
          {literal}{{caption}}{/literal}
        </ff-paging-item>
      {/block}

      {block name="frontend_listing_actions_paging_next"}
        <ff-paging-item type="nextLink" class="paging--link paging--next">
          <i class="icon--arrow-right"></i>
        </ff-paging-item>
      {/block}

      {block name="frontend_listing_actions_paging_last"}
        <ff-paging-item type="lastLink" class="paging--link paging--last">
          <i class="icon--arrow-right"></i>
          <i class="icon--arrow-right"></i>
        </ff-paging-item>
      {/block}
    </ff-paging>

    {* Products per page selection *}
    {block name='frontend_listing_actions_items_per_page'}
      <div class="action--per-page action--content block">
        {block name='frontend_listing_actions_items_per_page_label'}
          <label class="per-page--label action--label">{s name='ListingLabelItemsPerPage'}{/s}</label>
          <ff-products-per-page-select class="per-page--select select-field"></ff-products-per-page-select>
        {/block}
      </div>
    {/block}
  </div>
{/block}
