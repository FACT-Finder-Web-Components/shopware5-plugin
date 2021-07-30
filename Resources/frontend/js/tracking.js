;(function ($) {
    $.subscribe('plugin/swAddArticle/onBeforeAddArticle', function (ev, me, data) {
        const params = new RegExp('[\?&]sAdd=([^&#]*)').exec(data);

        if (!params || !params[1]) {
            return;
        }
        waitForFactFinder().then(function (factfinder) {
            const trackingHelper = factfinder.communication.Util.trackingHelper;
            factfinder.communication.EventAggregator.addFFEvent({
                type: 'getRecords',
                recordId: params[1],
                idType: 'productNumber',
                success: function (response) {
                    if (!response || !response[0]) {
                        return;
                    }
                    const product = response[0];
                    factfinder.communication.Tracking.cart({
                        id: trackingHelper.getTrackingProductId(product),
                        masterId: trackingHelper.getMasterArticleNumber(product),
                        price: factfinder.communication.Util.trackingHelper.getPrice(product),
                        count: 1,
                    });
                },
            });
        });

    });
})(jQuery);

function waitForFactFinder() {
    return new Promise(function (resolve) {
        if (typeof window.factfinder !== 'undefined') {
            resolve(window.factfinder);
        } else {
            document.addEventListener('ffReady', function (event) {
                resolve(event.factfinder);
            });
        }
    });
}
