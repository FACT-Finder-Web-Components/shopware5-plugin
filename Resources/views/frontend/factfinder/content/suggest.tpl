<section class="results--list" data-container="productName">
  <ff-suggest-item type="productName">
    <div class="list--entry block-group result--item">
      <div class="search-result--link">
        <span class="entry--media block">
          <img data-image="{'{{image}}'}" alt="{'{{name}}'}" class="media--image" />
        </span>
        <span class="entry--name block">{'{{{name}}}'}</span>
        <div class="entry--price block">
          <div class="product--price">
            {'{{#attributes.Price}}'}<span class="price--default is--nowrap">{'{{.}}'} *</span>{'{{/attributes.Price}}'}
          </div>
          <div class="price--unit">
            <span class="price--label label--purchase-unit">
              {s name="DetailDataInfoContent" namespace="frontend/detail/data"}{/s}
            </span>
            <span class="is--nowrap">1 pc</span>
          </div>
        </div>
      </div>
    </div>
  </ff-suggest-item>
</section>
