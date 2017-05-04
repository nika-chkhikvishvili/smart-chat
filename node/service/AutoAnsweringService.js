/**
 * Created by jedi on 5/3/17.
 */

'use strict';

var AutoAnswering = require('../models/AutoAnswering');

function AutoAnsweringService(params) {
    this.autoAnswerings = {};
    var a = this.autoAnswerings;

    if (!!params && Array.isArray(params)) {
        params.forEach(function (val) {
            a[val.repository_id] = new AutoAnswering({welcomeMessage: val.start_chating});
        })
    }
}

AutoAnsweringService.prototype.getValue = function (repositoryId, field, defaultValue) {
    var autoAnswering = this.autoAnswerings.hasOwnProperty(repositoryId) ? this.autoAnswerings[repositoryId] : false;
    if (!autoAnswering) {
        autoAnswering= this.autoAnswerings.hasOwnProperty(0) ? this.autoAnswerings[0] : false;
    }
    if (autoAnswering) {
        return autoAnswering.hasOwnProperty(field)? autoAnswering[field]: defaultValue;
    }
    return defaultValue;
};

AutoAnsweringService.prototype.getWelcomeMessage = function (repositoryId) {
    return this.getValue(repositoryId, 'welcomeMessage', false);
};

module.exports = AutoAnsweringService;
