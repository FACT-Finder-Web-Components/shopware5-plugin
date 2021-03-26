{extends file='frontend/factfinder/content/record_list.tpl'}
{block name="frontend_factfinder_record"}
  <ff-record class="product-slider--item product--box box--slider" style="width:20%">
      <div class="product--info">
        <a class="last-seen-products-item--image product--image"
           title="{'{{record.Name}}'}"
           data-redirect="{'{{record.Deeplink}}'}"
           data-anchor="{'{{record.Deeplink}}'}"
           data-redirect-target="_self">
              <span class="image--element">
                <span class="image--media">
                  <img data-image="{'{{record.ImageURL}}'}" alt="{'{{record.Name}}'}" title="{'{{record.Name}}'}" />
                </span>
              </span>
        </a>
        <a class="product--title"
           title="{'{{record.Name}}'}"
           data-redirect="{'{{record.Deeplink}}'}"
           data-anchor="{'{{record.Deeplink}}'}"
           data-redirect-target="_self">{'{{record.Name}}'}</a>
      </div>
  </ff-record>
{/block}
