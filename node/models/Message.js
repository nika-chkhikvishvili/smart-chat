/**
 * Created by jedi on 2017-04-21
 */

function Message(initParams) {
    if (!(this instanceof Message)) return new Message(initParams);

    this.chatId = null;
    this.chatUniqId = null;
    this.messageId = null;
    this.message = null;
    this.guestId = null;
    this.userId = null;
    this.messageDate = null;
    this.messageUniqId = null;
}


module.exports = Message;