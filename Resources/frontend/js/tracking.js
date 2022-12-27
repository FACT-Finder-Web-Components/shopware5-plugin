;(function ($) {
    function getRecordId(data)
    {
        const params = new RegExp('[\?&]sAdd=([^&#]*)').exec(data);

        if (!params || !params[1]) {
            return '';
        }

        return params[1];
    }

    function getQuantity(data)
    {
        if (ffTrackingSettings.addToCart.count === 'count_as_one') {
            return 1;
        }

        const params = new RegExp('[\?&]sQuantity=([^&#]*)').exec(data);

        if (!params || !params[1]) {
            return 1;
        }

        return parseInt(params[1]);
    }

    $.subscribe('plugin/swAddArticle/onBeforeAddArticle', function (ev, me, data) {
        const recordId = getRecordId(data);
        const quantity = getQuantity(data);

        if (recordId === '') {
            return;
        }

        waitForFactFinder().then(function (factfinder) {
            const trackingHelper = factfinder.communication.Util.trackingHelper;
            factfinder.communication.EventAggregator.addFFEvent({
                type: 'getRecords',
                recordId: recordId,
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
                        count: quantity,
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
