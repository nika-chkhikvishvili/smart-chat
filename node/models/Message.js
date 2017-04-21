/**
 * Created by jedi on 2017-04-21
 */

function Message(initParams) {
    if (!(this instanceof Message)) return new Message(initParams);
    if (!initParams) initParams = {};

    this.chatId        = initParams.chatId        || initParams.chat_id          || null;
    this.chatUniqId    = initParams.chatUniqId    || initParams.chat_uniq_id     || null;
    this.messageId     = initParams.messageId     || initParams.message_id       || null;
    this.message       = initParams.message       || initParams.chat_message     || null;
    this.guestId       = initParams.guestId       || null;
    this.userId        = initParams.userId        || null;
    this.messageDate   = initParams.messageDate   || null;
    this.messageUniqId = initParams.messageUniqId || null;
    this.sender        = null;

}


module.exports = Message;