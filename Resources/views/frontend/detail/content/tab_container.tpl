{extends file='parent:frontend/detail/content/tab_container.tpl'}
{block name='frontend_detail_index_outer_tabs'}
  {block name='frontend_detail_index_tabs_similar_inner'}
    {$sArticle.sSimilarArticles = true}
  {/block}
  {$smarty.block.parent}
{/block}
