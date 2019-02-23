/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
define([
    'jquery',
    'ko',
    'mage/mage',
    'Magento_Catalog/product/view/validation',
    'Angel_Raffle/js/action/purchase-tickets',
    'Angel_Raffle/js/model/raffle',
    'Magento_Customer/js/customer-data',
    'Magento_Ui/js/modal/confirm',
    'mage/validation'
], function ($, ko, mage, validation, purchaseAction, raffle, customerData, confirmation) {
    'use strict';

    $.widget('raffle.purchaseTicket', {
        isLoading: ko.observable(false),
        options: {
            bindSubmit: false,
            radioCheckboxClosest: '.nested'
        },
        isLoggedIn : function () {
            var customer = customerData.get('customer');
            return customer && customer().firstname;
        },
        submitPurchaseRequest : function (form) {
            var self = this;
            if (!self.isLoggedIn()){
                window.location.href = self.options.loginUrl;
                return false;
            }
            if (self.isLoading()){
                return false;
            }
            var formElement = $('#'+form.id),
                formDataArray = formElement.serializeArray();
            var purchaseData = {};
            formDataArray.forEach(function (entry) {
                if (entry.value)
                    purchaseData[entry.name] = entry.value;
            });

            if (formElement.validation() &&
                formElement.validation('isValid')
            ) {
                self.isLoading(true);
                $('#product-addtocart-button').addClass('disabled');
                purchaseAction(purchaseData);
            }
            return false;
        },

        /**
         * Uses Magento's validation widget for the form object.
         * @private
         */
        _create: function () {
            var self = this;
            var bindSubmit = this.options.bindSubmit;
            raffle.status = this.options.status;
            raffle.totalTicket(Number.parseFloat(this.options.totalTicket));
            raffle.totalTicketSold(Number.parseFloat(this.options.totalTicketSold));

            this.element.validation({
                radioCheckboxClosest: this.options.radioCheckboxClosest,

                /**
                 * Uses catalogAddToCart widget as submit handler.
                 * @param {Object} form
                 * @returns {Boolean}
                 */
                submitHandler: function (form) {
                    confirmation({
                        title: 'Accept Purchase',
                        content: 'Are you sure to purchase '+ $('#qty').val() +' tickets?',
                        actions: {
                            confirm: function () {
                                self.submitPurchaseRequest(form);
                                return false;
                            },
                            cancel: function () {
                                return false;
                            }
                        }
                    });
                }
            });
            purchaseAction.registerPurchaseCallback(function () {
                self.isLoading(false);
                $('#product-addtocart-button').removeClass('disabled')
            });
        }
    });

    return $.raffle.purchaseTicket;
});
