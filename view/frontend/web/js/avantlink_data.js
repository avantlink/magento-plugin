var _AvantMetrics = _AvantMetrics || [];

define([
    "ko"
], function (ko) {
    "use strict";

    return function(config) {
        if (config.orderData.orderId != null && config.orderData.orderId !== '') {
            let orderData = config.orderData;
            _AvantMetrics.push([
                'order', {
                    order_id: orderData.orderId,
                    amount: orderData.subTotal,
                    state: orderData.stateCode,
                    country: orderData.countryCode,
                    ecc: orderData.couponCode,
                    tax: orderData.taxAmount,
                    currency: orderData.currencyCode,
                    new_customer: orderData.customerId ? 'N' : 'Y'
                }
            ]);

            for (let line of orderData.items) {
                _AvantMetrics.push([
                    'item', {
                        order_id: orderData.orderId,
                        variant_sku: line.itemSku,
                        price: line.itemPrice,
                        qty: line.itemQty
                    }
                ]);
            }


        }

        var avm = document.createElement('script');
        avm.type = 'text/javascript';
        avm.async = true;
        avm.src = 'https://avmws-default.avantlink.net/10' + config.merchantId + '/';
        var s = document.getElementsByTagName('script')[0];
        s.parentNode.insertBefore(avm, s);
    }
});
