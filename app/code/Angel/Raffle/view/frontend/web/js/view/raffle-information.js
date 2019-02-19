/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

define([
    'jquery',
    'ko',
    'uiComponent',
    'Angel_Raffle/js/model/raffle',
    'Magento_Customer/js/customer-data',
    'Magento_Catalog/js/price-utils'
], function ($, ko, Component, raffle, customerData, priceUtils) {
    'use strict';

    return Component.extend({
        defaults: {
            template: 'Angel_Raffle/raffle-information'
        },
        earnMoneyClass: ko.observable(''),
        tickets: raffle.tickets,
        priceFormat : window.checkoutConfig?window.checkoutConfig.priceFormat:
            {"pattern":"$%s","precision":2,"requiredPrecision":2,"decimalSymbol":".","groupSymbol":",","groupLength":3,"integerRequired":false},
        customer: customerData.get('customer'),
        formatPrice: function(price){
            return priceUtils.formatPrice(price, this.priceFormat);
        },

        /** @inheritdoc */
        initialize: function () {
            var self = this;
            var currentBalance = self.customer().creditBalance;
            this._super();
            this.customerCreditFormated = ko.computed(function(){
                return priceUtils.formatPrice(self.customer().creditBalance, self.priceFormat);
            });
            this.hasTickets = ko.computed(function(){
                return raffle.tickets().length;
            });
            this.checkEarnMoney = ko.computed(function () {
                var earn = self.customer().creditBalance > currentBalance?'earn-money':'lose-money';
                self.earnMoneyClass(earn);
                setTimeout(function () {
                    self.earnMoneyClass('');
                },5000);
            });
        },
    });
});
