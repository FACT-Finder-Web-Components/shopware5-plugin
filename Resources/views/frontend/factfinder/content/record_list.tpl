{block name="frontend_factfinder_record_list"}
  <ff-record-list class="container" subscribe="{($subscribe) ? 'true' : 'false'}" unresolved {($isNavigationPage) ? 'is-navigation' : ''}>
    {block name="frontend_factfinder_record"}
      <ff-record class="product--box box--basic">
        <div class="box--content is--rounded">
          <div class="product--info">
            <a class="product--image"
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
            <div class="product--description">{'{{record.Brand}}'}</div>
            <div class="product--price-info">
              <div class="price--unit">
                <span class="price--label label--purchase-unit is--bold is--nowrap">
                  {s name="DetailDataInfoContent" namespace="frontend/detail/data"}{/s}
                </span>
                <span class="is--nowrap">1 pc</span>
              </div>
              <div class="product--price">
                <span class="price--default is--nowrap">{$activeCurrency}</span>
              </div>
            </div>
          </div>
        </div>
      </ff-record>
    {/block}
  </ff-record-list>
{/block}
