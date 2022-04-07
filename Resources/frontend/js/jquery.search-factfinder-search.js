$.overridePlugin('swSearch', {
    //silence the default suggestions
    onKeyUp: function(response) {
        return true;
    }
});
