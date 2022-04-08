{namespace name='frontend/omikron/factfinder'}

{block name='frontend_factfinder_asn'}
  <ff-asn class="filter--facet-container" unresolved>
    {block name='frontend_factfinder_asn_group'}
      {if $isNavigationPage}
          <ff-asn-group for-group="{$categoryPathFieldName}ROOT" style="display:none" class="hidden-group" disable-auto-expand>
          </ff-asn-group>
      {/if}
      <ff-asn-group class="filter-panel filter--multi-selection" disable-auto-expand>
        <div slot="groupCaption" class="filter-panel--title">
          {'{{group.name}}'}
          <span class="filter-panel--icon"></span>
        </div>

        <ff-asn-group-element class="filter-panel--option">
          <div slot="unselected" class="option--container">
            <span class="filter-panel--input filter-panel--checkbox">
              <input type="checkbox" />
              <span class="input--state checkbox--state">&nbsp;</span>
            </span>
            <span class="filter-panel--label">{'{{element.name}}'}</span>
          </div>

          <div slot="selected" class="option--container">
            <span class="filter-panel--input filter-panel--checkbox">
              <input type="checkbox" checked />
              <span class="input--state checkbox--state">&nbsp;</span>
            </span>
            <span class="filter-panel--label">{'{{element.name}}'}</span>
          </div>
        </ff-asn-group-element>

        <div data-container="showMore">{s name='ListingFilterShowMore'}Show More{/s}</div>
        <div data-container="showLess">{s name='ListingFilterShowLess'}Show Less{/s}</div>
        <div data-container="removeFilter">{s name='ListingFilterReset'}Reset Filter{/s}</div>
      </ff-asn-group>
    {/block}

    {block name='frontend_factfinder_asn_group_slider'}
      <ff-asn-group-slider class="filter-panel filter--range facet--price" disable-auto-expand>
        <div slot="groupCaption" class="filter-panel--title">
          {'{{group.name}}'}
          <span class="filter-panel--icon"></span>
        </div>

        <ff-slider-control>
          <ff-slider class="range-slider--container">
            <div slot="slider1" class="range-slider--handle is--min"></div>
            <div slot="slider2" class="range-slider--handle is--max"></div>
          </ff-slider>

          <div class="filter-panel--range-info">
            <div><input data-control="1" type="text" /></div>
            <div><input data-control="2" type="text" /></div>
          </div>

          <div data-container="removeFilter">{s name='ListingFilterReset'}Reset Filter{/s}</div>
        </ff-slider-control>
      </ff-asn-group-slider>
    {/block}

    {block name='frontend_factfinder_asn_group_custom'}{/block}
  </ff-asn>
{/block}
