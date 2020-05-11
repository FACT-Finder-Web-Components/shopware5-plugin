{block name="frontend_factfinder_record_list"}
  <ff-record-list subscribe="{($subscribe) ? 'true' : 'false'}" unresolved>
    <ff-record class="product--box box--basic">
      {literal}
        <div class="box--content is--rounded">
          <div class="product--info">
            <a class="product--image"
               title="{{record.Title}}"
               data-redirect="{{record.Deeplink}}"
               data-anchor="{{record.Deeplink}}"
               data-redirect-target="_self">
              <span class="image--element">
                <span class="image--media">
                  <img data-image="{{record.ImageURL}}" alt="{{record.Title}}" title="{{record.Title}}" />
                </span>
              </span>
            </a>
            <a class="product--title"
               title="{{record.Title}}"
               data-redirect="{{record.Deeplink}}"
               data-anchor="{{record.Deeplink}}"
               data-redirect-target="_self">{{record.Title}}</a>
            <div class="product--description">{{record.VarColor}}</div>
            <div class="product--price-info">
              <div class="price--unit" title="Inhalt 1 Stück">
                <span class="price--label label--purchase-unit is--bold is--nowrap">Inhalt</span>
                <span class="is--nowrap">1 Stück</span>
              </div>
              <div class="product--price">
                <span class="price--default is--nowrap">{{record.Price}}</span>
              </div>
            </div>
          </div>
        </div>
      {/literal}
    </ff-record>
  </ff-record-list>
{/block}
