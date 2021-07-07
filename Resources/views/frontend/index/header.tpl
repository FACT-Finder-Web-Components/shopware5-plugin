{extends file="parent:frontend/index/header.tpl"}

{block name="frontend_index_header_javascript_tracking"}
  {$smarty.block.parent}
  <script type="text/javascript" src="{link file='frontend/_public/ff-web-components/vendor/custom-elements-es5-adapter.js'}"></script>
  <script type="text/javascript" src="{link file='frontend/_public/ff-web-components/vendor/webcomponents-loader.js'}"></script>
  <script type="text/javascript" src="{link file='frontend/_public/ff-web-components/bundle.js'}" defer></script>
  <style>[unresolved] { display:none !important; }</style>

  {block name="frontend_factfinder_search_redirect"}
    <script>
      document.addEventListener('before-search', function (event) {
        if (['productDetail', 'getRecords'].lastIndexOf(event.detail.type) === -1) {
          event.preventDefault();
          window.location = '{url controller='factfinder' action='result'}' + factfinder.common.dictToParameterString(factfinder.common.encodeDict(event.detail));
        }
      });
    </script>
  {/block}

  <script type="text/javascript">
    const activeCurrency = `{$activeCurrencyField}`;
    document.addEventListener('ffReady', function (e) {
      e.factfinder.communication.fieldRoles = {$ffFieldRoles|@json_encode};
      e.factfinder.communication.ResultDispatcher.addCallback('result', function (result) {
        result.groups = result.groups.filter(function (group) {
          const associatedFieldName = group.elements.concat(group.selectedElements)[0].associatedFieldName;
          return associatedFieldName === activeCurrency || !/Price/.test(associatedFieldName);
        });
      });

      e.factfinder.communication.ResultDispatcher.addCallback('similarProducts', function (similarData) {
        if (similarData.records && !similarData.records.length) {
          const similarProductsTab = document.querySelector('a[href="#content--similar-products"]');
            if (similarProductsTab) {
                similarProductsTab.classList.add('ffw-hidden');
            }
        }
      });
    });
  </script>
{/block}
