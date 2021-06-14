;(function ($) {
    $.subscribe('onShowContent-similar', function (ev, me) {
        if (!document.querySelector('ff-similar-products ff-record-list').childElementCount) {
            me.changeTab($('.tab-menu--cross-selling .has--content:not(.ffw-hidden)').index());
        }
    });
})(jQuery);
