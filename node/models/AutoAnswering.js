/**
 * Created by jedi on 5/3/17.
 */

'use strict';

function AutoAnsweringNode(initParams) {
    this.lastUpdateTime = new Date();
    this.welcomeMessage = initParams.hasOwnProperty('welcomeMessage') ? initParams.welcomeMessage : false;
    this.banMessage = initParams.banMessage || false;

    this.auto_answering_id = null;
    this.repository_id = null;
    this.start_chating = null;
    this.mail_offline = null;
    this.connect_failed = null;
    this.auto_answering = null;
    this.repeat_auto_answering = null;
    this.time_off = null;
}

function AutoAnswering(params) {
    this.autoAnswerings = {};
    var a = this.autoAnswerings;

    if (!!params && Array.isArray(params)) {
        params.forEach(function (val) {
            a[val.repository_id] = new AutoAnsweringNode({
                welcomeMessage: val.start_chating,
                banMessage: val.user_block
            });
        });
    }
}

AutoAnswering.prototype.getValue = function (repositoryId, field, defaultValue) {
    var autoAnswering = this.autoAnswerings.hasOwnProperty(repositoryId) ? this.autoAnswerings[repositoryId] : false;
    if (!autoAnswering) {
        autoAnswering = this.autoAnswerings.hasOwnProperty(0) ? this.autoAnswerings[0] : false;
    }
    if (autoAnswering) {
        return autoAnswering.hasOwnProperty(field) ? autoAnswering[field]: defaultValue;
    }
    return defaultValue;
};

AutoAnswering.prototype.getWelcomeMessage = function (repositoryId) {
    return this.getValue(repositoryId, 'welcomeMessage', false);
};

AutoAnswering.prototype.getBanMessage = function (repositoryId) {
    return this.getValue(repositoryId, 'banMessage', false);
};

AutoAnswering.prototype.getDefaultBanMessage = function () {
    return 'თქენი მისამართი დაბლოკილია, გთხოვთ მიმართოთ იუსტიციის ცხელ ხაზზე';
};

module.exports = AutoAnswering;
