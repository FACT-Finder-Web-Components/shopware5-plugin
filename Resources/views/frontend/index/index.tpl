{extends file='parent:frontend/index/index.tpl'}

{block name='frontend_index_after_body'}
  {$smarty.block.parent}

  {block name='frontend_factfinder_communication'}
    {action module=frontend controller=factfinder action=communication searchImmediate=$searchImmediate addParams=$ffAddParams categoryPage=$ffCategoryPage}
  {/block}
{/block}
