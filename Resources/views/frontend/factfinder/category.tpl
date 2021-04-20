{block name='frontend_index_start'}
  {$smarty.block.parent}
  {$facets = ['FACT-Finder ASN']}
  {$ffAddParams = {','|implode:$ffCategoryPath|cat:$ffManufacturerFilter|cat:',navigation=true'}}
  {$searchImmediate = true}
  {$showListing = true}
{/block}

{block name='frontend_index_header_javascript_tracking'}
  {$smarty.block.parent}

  {block name='frontend_factfinder_category_history_push'}
    <script>
      document.addEventListener('ffReady', function (e) {
        e.eventAggregator.addBeforeHistoryPushCallback(function (res, event, url) {
          url = url.replace(/filter=CategoryPath[^&]+&?/, '');
          e.factfinder.communication.Util.pushParameterToHistory(res, url, event);
          return false;
        });
      });
    </script>
  {/block}
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
