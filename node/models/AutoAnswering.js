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
    let a = this.autoAnswerings;

    if (!!params && Array.isArray(params)) {
        params.forEach(function (val) {

// auto_answering_id, repository_id, start_chating_geo, start_chating_rus, start_chating_eng, mail_offline, waiting_message_geo, waiting_message_rus, waiting_message_eng, connect_failed_geo, connect_failed_rus, connect_failed_eng, user_block_geo, user_block_rus, user_block_eng, auto_answering_geo, auto_answering_rus, auto_answering_eng, repeat_auto_answering, time_off_geo, time_off_rus, time_off_eng
            a[val.repository_id] = new AutoAnsweringNode({
                welcomeMessage: val.start_chating_geo,
                banMessage: val.user_block_geo
            });
        });
    }
}

AutoAnswering.prototype.getValue = function (repositoryId, field, defaultValue) {
    let autoAnswering = this.autoAnswerings.hasOwnProperty(repositoryId) ? this.autoAnswerings[repositoryId] : false;
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
