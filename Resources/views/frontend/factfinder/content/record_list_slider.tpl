{extends file='frontend/factfinder/content/record_list.tpl'}
{block name="frontend_factfinder_record"}
  <ff-record class="product-slider--item product-slider--item">
    <div class="product--box box--slider">
      <div class="box--content is--rounded">
        <a class="product--image"
           title="{'{{record.Name}}'}"
           data-redirect="{'{{record.Deeplink}}'}"
           data-anchor="{'{{record.Deeplink}}'}"
           data-redirect-target="_self">
              <span class="image--element">
                <span class="image--media">
                  <img data-image="{'{{record.ImageURL}}'}" alt="{'{{record.Name}}'}" title="{'{{record.Name}}'}"/>
                </span>
              </span>
        </a>
        <a class="product--title"
           title="{'{{record.Name}}'}"
           data-redirect="{'{{record.Deeplink}}'}"
           data-anchor="{'{{record.Deeplink}}'}"
           data-redirect-target="_self">{'{{record.Name}}'}
        </a>
      </div>
    </div>
  </ff-record>
{/block}
