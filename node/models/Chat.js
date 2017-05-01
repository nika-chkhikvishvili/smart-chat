/**
 * Created by jedi on 2017-04-21
 */

var randomStringGenerator = require("randomstring");


function Chat(initParams) {
    if (!(this instanceof Chat)) return new Chat(initParams);

    initParams = initParams || {};

    this.chatUniqId    = initParams.hasOwnProperty('chatUniqId')  ? initParams.chatUniqId      : randomStringGenerator.generate();
    this.chatId        = initParams.hasOwnProperty('chatId')      ? initParams.chatId          : null;
    this.guestUserId   = initParams.hasOwnProperty('guestUserId') ? initParams.guestUserId     : null;
    this.guestUser     = initParams.hasOwnProperty('guestUser')   ? initParams.guestUser       : null;
    this.serviceId     = initParams.hasOwnProperty('serviceId')   ? parseInt(initParams.serviceId)    : null;
}

Chat.prototype.getInsertObject = function () {
   return {
       online_user_id: this.guestUserId,
       service_id    : this.serviceId,
       chat_uniq_id  : this.chatUniqId
   };
};



module.exports = Chat;
