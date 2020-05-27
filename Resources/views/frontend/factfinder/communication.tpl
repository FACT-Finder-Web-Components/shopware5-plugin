<ff-communication url="{config name=ffServerUrl}"
                  channel="{config name=ffChannel}"
                  version="{config name=ffVersion}"
                  {if {config name=ffVersion} eq 'ng'}api="v3"{/if}
                  {if $addParams}add-params="{$addParams|escape:'html'}"{/if}
                  {if $searchImmediate}search-immediate="true"{/if}
                  sid="{$sid|truncate:30:''}"
                  {if $uid}user-id="{$uid}"{/if}
                  use-url-parameter="true"
                  only-search-params="true"
                  currency-code="{0|currency:use_shortname:left|substr:0:3}"
                  currency-country-code="{$Locale|replace:"_":"-"}"></ff-communication>
