;(function ($) {
    $.subscribe('plugin/swAddArticle/onBeforeAddArticle', function (ev, me, data) {
        const params = new RegExp('[\?&]sAdd=([^&#]*)').exec(data);

        if (!params || !params[1]) {
            return;
        }
        waitForFactFinder().then(function (factfinder) {
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
                        id: getProductNumber(product),
                        masterId: getMasterArticleNumber(product),
                        price: factfinder.communication.Util.trackingHelper.getPrice(product),
                        count: 1,
                    });
                },
            });
        });

    });
})(jQuery);

function getMasterArticleNumber(product) {
    return factfinder.communication.fieldRoles && factfinder.communication.fieldRoles.masterId
           ? product.record[factfinder.communication.fieldRoles.masterId]
           : product.record && product.record.Master
             ? product.record.Master
             : '';
};

function getProductNumber(product) {
    return factfinder.communication.fieldRoles && factfinder.communication.fieldRoles.productNumber
           ? product.record[factfinder.communication.fieldRoles.productNumber]
           : product.record && product.record.ProductNumber
             ? product.record.ProductNumber
             : '';
};

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
