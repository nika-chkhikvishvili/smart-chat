/**
 * Created by jedi on 2017-04-21
 */

'use strict';

function Message(initParams) {
    if (!(this instanceof Message)) return new Message(initParams);
    if (!initParams) initParams = {};

    this.messageId     = initParams.hasOwnProperty('messageId')     ?  initParams.messageId  : null;
    this.chatId        = initParams.hasOwnProperty('chatId')        ?  initParams.chatId  : null;
    this.chatUniqId    = initParams.hasOwnProperty('chatUniqId')    ?  initParams.chatUniqId  : null;
    this.message       = initParams.hasOwnProperty('message')       ?  initParams.message  : null;
    this.guestUserId   = initParams.hasOwnProperty('guestUserId')   ?  initParams.guestUserId  : null;
    this.userId        = initParams.hasOwnProperty('userId')        ?  initParams.userId  : null;
    this.messageDate   = initParams.hasOwnProperty('messageDate')   ?  initParams.messageDate  : null;
    this.messageUniqId = initParams.hasOwnProperty('messageUniqId') ?  initParams.messageUniqId  : null;
    this.sender        = null;
    this.messageType   = null;

}

Message.prototype.getInsertObject = function () {
    return {
        chat_id       : this.chatId,
        person_id     : this.userId,
        online_user_id: this.guestUserId,
        chat_message  : this.message
    }
};

module.exports = Message;
