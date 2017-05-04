/**
 * Created by jedi on 5/3/17.
 */

'use strict';

function AutoAnsweringNode(initParams) {
    this.lastUpdateTime = new Date();
    this.welcomeMessage = initParams.hasOwnProperty('welcomeMessage') ? initParams.welcomeMessage : false;

    this.auto_answering_id = null;
    this.repository_id = null;
    this.start_chating = null;
    this.mail_offline = null;
    this.connect_failed = null;
    this.user_block = null;
    this.auto_answering = null;
    this.repeat_auto_answering = null;
    this.time_off = null;
}

function AutoAnswering(params) {
    this.autoAnswerings = {};
    var a = this.autoAnswerings;

    if (!!params && Array.isArray(params)) {
        params.forEach(function (val) {
            a[val.repository_id] = new AutoAnsweringNode({welcomeMessage: val.start_chating});
        })
    }
}

AutoAnswering.prototype.getValue = function (repositoryId, field, defaultValue) {
    var autoAnswering = this.autoAnswerings.hasOwnProperty(repositoryId) ? this.autoAnswerings[repositoryId] : false;
    if (!autoAnswering) {
        autoAnswering= this.autoAnswerings.hasOwnProperty(0) ? this.autoAnswerings[0] : false;
    }
    if (autoAnswering) {
        return autoAnswering.hasOwnProperty(field)? autoAnswering[field]: defaultValue;
    }
    return defaultValue;
};

AutoAnswering.prototype.getWelcomeMessage = function (repositoryId) {
    return this.getValue(repositoryId, 'welcomeMessage', false);
};

module.exports = AutoAnswering;
