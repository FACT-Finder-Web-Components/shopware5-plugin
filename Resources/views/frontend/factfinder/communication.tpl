<ff-communication url="{config name=ffServerUrl}"
                  channel="{config name=ffChannel}"
                  version="{config name=ffVersion}"
                  {if {config name=ffVersion} eq 'ng'}api="v4"{/if}
                  {if $addParams}add-params="{$addParams|escape:'html'}"{/if}
                  {if $searchImmediate}search-immediate="true"{/if}
                  sid="{$sid|md5|truncate:30:''}"
                  {if $uid}user-id="{$uid}"{/if}
                  use-url-parameters="true"
                  only-search-params="true"
                  disable-single-hit-redirect="true"
                  currency-fields="{$currencyFields}"
                  currency-code="{0|currency:use_shortname:left|substr:0:3}"
                  currency-country-code="{$Locale|replace:"_":"-"}"></ff-communication>
