/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

define([
    'jquery',
    'ko'
], function ($, ko) {
    'use strict';

    return {
        id: ko.observable(0),
        tickets: ko.observable([]),
        totalTicket: ko.observable(0),
        totalTicketSold: ko.observable(0),
        status: []
    };
});
