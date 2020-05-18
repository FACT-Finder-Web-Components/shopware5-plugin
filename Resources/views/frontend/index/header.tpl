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
          delete event.detail.type;
          window.location = '{url controller='factfinder' action='result'}' + factfinder.common.dictToParameterString(event.detail);
        }
      });
    </script>
  {/block}
{/block}
