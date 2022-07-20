{block name='frontend_factfinder_campaign'}
  {block name='frontend_factfinder_campaign_redirect'}
    {if $ffFeatureFlags.campaign}
      <ff-campaign-redirect></ff-campaign-redirect>
    {/if}
  {/block}

  {block name='frontend_factfinder_campaign_advisor'}
    {if $ffFeatureFlags.campaign}
      <ff-campaign-advisor unresolved>
        <ff-campaign-advisor-question>
          <h1 data-question>{'{{{text}}}'}</h1>
          <ff-campaign-advisor-answer>
            <div>{'{{{text}}}'}</div>
          </ff-campaign-advisor-answer>
        </ff-campaign-advisor-question>
      </ff-campaign-advisor>
    {/if}
  {/block}

  {block name='frontend_factfinder_campaign_pushed_products'}
    {if $ffFeatureFlags.campaign}
      <ff-campaign-pushed-products unresolved>
          {include file='frontend/factfinder/content/record_list.tpl'}
      </ff-campaign-pushed-products>
    {/if}
  {/block}
{/block}
