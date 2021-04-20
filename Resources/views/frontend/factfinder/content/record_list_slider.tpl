{extends file='frontend/factfinder/content/record_list.tpl'}
{block name="frontend_factfinder_record"}
  <ff-record class="product-slider--item recommendation--item product--box box--slider">
    <a class="recommendation-item--image product--image"
       title="{'{{record.Name}}'}"
       data-redirect="{'{{record.Deeplink}}'}"
       data-anchor="{'{{record.Deeplink}}'}"
       data-redirect-target="_self">
              <span class="image--element">
                <span class="image--media">
                  <img data-image="{'{{record.ImageURL}}'}" alt="{'{{record.Title}}'}" title="{'{{record.Title}}'}"/>
                </span>
              </span>
    </a>
    <a class="product--title"
       title="{'{{record.Title}}'}"
       data-redirect="{'{{record.Deeplink}}'}"
       data-anchor="{'{{record.Deeplink}}'}"
       data-redirect-target="_self">{'{{record.Title}}'}
    </a>
  </ff-record>
{/block}
