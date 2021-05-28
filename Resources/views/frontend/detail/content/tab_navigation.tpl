{extends file='parent:frontend/detail/content/tab_navigation.tpl'}
{block name="frontend_detail_index_tabs_navigation"}
  {$sArticle.sSimilarArticles = [1]}
  {$smarty.block.parent}
{/block}
