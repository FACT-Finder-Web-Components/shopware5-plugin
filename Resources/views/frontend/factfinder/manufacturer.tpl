{extends file='frontend/factfinder/category.tpl'}

{block name='frontend_factfinder_category_history_push'}
  <script>
    document.addEventListener('ffReady', function (e) {
      e.eventAggregator.addBeforeHistoryPushCallback(function (res, event, url) {
        debugger;
        const pattern = 'filter=' + factfinder.communication.fieldRoles.brand + '[^&]+&?';
        url = url.replace(new RegExp(pattern), '');
        e.factfinder.communication.Util.pushParameterToHistory(res, url, event);
        return false;
      });
    });
  </script>
{/block}
