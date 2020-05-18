{extends file="parent:frontend/index/search.tpl"}

{block name='frontend_index_search_container'}
  <div class="main-search--form">
    <ff-searchbox suggest-onfocus="true" use-suggest="true" select-onclick="true">
      {block name='frontend_index_search_field'}
        <input type="search" class="main-search--field" aria-label="{s name="IndexSearchFieldPlaceholder"}{/s}" placeholder="{s name="IndexSearchFieldPlaceholder"}{/s}" />
      {/block}
    </ff-searchbox>

    <ff-searchbutton>
      <button type="submit" class="main-search--button" aria-label="{s name="IndexSearchFieldSubmit"}{/s}">
        {block name='frontend_index_search_field_submit_icon'}
          <i class="icon--search"></i>
        {/block}

        {block name='frontend_index_search_field_submit_text'}
          <span class="main-search--text">{s name="IndexSearchFieldSubmit"}{/s}</span>
        {/block}
      </button>
    </ff-searchbutton>
  </div>

  {block name='frontend_factfinder_suggest_overlay'}
    <ff-suggest class="main-search--results" unresolved>
      {include file='frontend/factfinder/content/suggest.tpl'}
    </ff-suggest>
  {/block}
{/block}
