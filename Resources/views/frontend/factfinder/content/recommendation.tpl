<div class="recommendation">
  <div class="product-slider recommendation--slider" data-product-slider="true" data-infiniteSlide="false">
    <div class="recommendation--container">
      <ff-recommendation max-results="5" record-id="{$sArticle.mainVariantNumber}" class="product-slider--container is--horizontal">
        <div class="recommendation--title">{s name='Recommendation' namespace='frontend/omikron/factfinder'}Recommendation{/s}</div>
        {include file='frontend/factfinder/content/record_list_slider.tpl'}
      </ff-recommendation>
    </div>
  </div>
</div>
