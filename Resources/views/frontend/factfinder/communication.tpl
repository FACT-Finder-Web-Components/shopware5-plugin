<ff-communication url="{config name=ffServerUrl}"
                  channel="{config name=ffChannel}"
                  version="{config name=ffVersion}"
                  {if {config name=ffVersion} eq 'ng'}api="v5"{/if}
                  {if $addParams}add-params="{$addParams|escape:'html'}"{/if}
                  use-url-parameters="true"
                  only-search-params="true"
                  {if $categoryPage}category-page="{$categoryPage}"{/if}
                  disable-single-hit-redirect="true"
                  currency-fields="{$currencyFields}"
                  currency-code="{0|currency:use_shortname:left|substr:0:3}"
                  currency-country-code="{$Locale|replace:"_":"-"}"></ff-communication>

<script>
  document.addEventListener('ffCommunicationReady', ({ factfinder, searchImmediate }) => {
    const cookies = document.cookie.split('; ').reduce((acc, cookie) => {
      const cookieData = cookie.split('=');
      const [key, value] = cookieData;
      acc[key] = value;

      return acc;
    }, {});

    const clearCookie = (name) => {
      document.cookie = name+'=; Max-Age=-1;';
    }

    if (cookies['ff_user_id']) {
      factfinder.communication.sessionManager.setLoginData(cookies['ff_user_id'])

      if (cookies['ff_has_just_logged_in']) {
        clearCookie('ff_has_just_logged_in');
        factfinder.communication.Tracking.loginWithConfig();
      }
    } else {
      factfinder.communication.sessionManager.clearLoginData();

      if (cookies['ff_has_just_logged_out']) {
        clearCookie('ff_has_just_logged_out');
        factfinder.communication.sessionManager.clearAllSessionData();
      }
    }

    if ('{$searchImmediate}') {
      searchImmediate();
    }
  });
</script>
