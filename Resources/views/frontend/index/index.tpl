{extends file='parent:frontend/index/index.tpl'}

{* Prevent Shopware AJAX search *}
{block name='frontend_index_shop_navigation'}
  {capture assign='shopNavigation'}{$smarty.block.parent}{/capture}
  {$shopNavigation|replace:' data-search="true"':''}
{/block}

{block name='frontend_index_after_body'}
  {$smarty.block.parent}

  {block name='frontend_factfinder_communication'}
    {action module=frontend controller=factfinder action=communication searchImmediate=$searchImmediate addParams=$ffAddParams categoryPage=$ffCategoryPage}
  {/block}
{/block}
