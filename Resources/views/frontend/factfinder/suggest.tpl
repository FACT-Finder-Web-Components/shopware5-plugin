<section class="results--list" data-container="productName">
  <ff-suggest-item type="productName">
    <div class="list--entry block-group result--item">
      <div class="search-result--link">
        {literal}
          <span class="entry--media block">
              <img data-image="{{suggestions.image}}" alt="{{name}}" class="media--image" />
            </span>
          <span class="entry--name block">{{{name}}}</span>
          <div class="entry--price block">
            <div class="product--price">
              <span class="price--default is--nowrap">{{suggestions.price}} *</span>
            </div>
          </div>
        {/literal}
      </div>
    </div>
  </ff-suggest-item>
</section>
