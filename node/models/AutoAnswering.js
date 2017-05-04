/**
 * Created by jedi on 5/3/17.
 */

'use strict';

function AutoAnswering(initParams) {
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

module.exports = AutoAnswering;
