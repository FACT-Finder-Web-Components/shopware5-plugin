{block name='frontend_factfinder_campaign'}
  {block name='frontend_factfinder_campaign_redirect'}
    <ff-campaign-redirect></ff-campaign-redirect>
  {/block}

  {block name='frontend_factfinder_campaign_advisor'}
    <ff-campaign-advisor unresolved>
      <ff-campaign-advisor-question>
        <h1 data-question>{'{{{text}}}'}</h1>
        <ff-campaign-advisor-answer>
          <div>{'{{{text}}}'}</div>
        </ff-campaign-advisor-answer>
      </ff-campaign-advisor-question>
    </ff-campaign-advisor>
  {/block}

  {block name='frontend_factfinder_campaign_pushed_products'}
    <ff-campaign-pushed-products unresolved>
      {include file='frontend/factfinder/content/record_list.tpl'}
    </ff-campaign-pushed-products>
  {/block}
{/block}
