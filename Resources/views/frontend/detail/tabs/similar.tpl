{extends file='parent:frontend/detail/tabs/similar.tpl'}
{if $ffFeatureFlags.similarProducts}
  {block name="frontend_detail_index_similar_slider_content"}
    <div class="similar--content" data-product-slider="true" data-infiniteslide="false" data-initOnEvent="onShowContent-similar">
      <div class="product-slider">
        <ff-similar-products max-results="5" record-id="{$sArticle.mainVariantNumber}" id-type="productNumber" class="product-slider--container is--horizontal">
          {include file='frontend/factfinder/content/record_list_slider.tpl'}
        </ff-similar-products>
      </div>
    </div>
  {/block}
{/if}
