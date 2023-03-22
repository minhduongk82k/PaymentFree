define(
    [
        'AHT_PaymentFee/js/view/checkout/summary/fee'
    ],
    function (Component) {
        'use strict';

        return Component.extend({

            /**
             * @override
             */
            isDisplayed: function () {
                return true;
            },
            getFeeName: function () {
                const isTaxDisplayedInGrandTotal = window.checkoutConfig.fee_name || false;
                return this.isFullMode() && isTaxDisplayedInGrandTotal;
            }
        });
    }
);
