/**
 * Created by jedi on 2017-04-20
 */


function User(user) {
    if (!(this instanceof User)) return new User(user);
    this.personId  = user && user.hasOwnProperty('person_id')  ? user.person_id : null;
    this.firstName = user && user.hasOwnProperty('first_name') ? user.first_name : null;
    this.lastName  = user && user.hasOwnProperty('last_name')  ? user.last_name : null;
    this.photo     = user && user.hasOwnProperty('photo')      ? user.photo : null;
    this.isAdmin   = user && user.hasOwnProperty('is_admin')   ? user.is_admin : null;
    this.statusId  = user && user.hasOwnProperty('status_id')  ? user.status_id : null;
    this.sockets = {};
    this.tokens = {};
}

User.prototype.addSocket = function (socketId) {
    if (!socketId) return ;
    if (!this.sockets.hasOwnProperty(socketId)) this.sockets[socketId] = null;
};

User.prototype.addToken = function (token) {
    if (!token) return ;
    if (!this.tokens.hasOwnProperty(token)) this.tokens[token] = null;
};

User.prototype.getLimited = function () {
    return {
        personId  : this.personId ,
        firstName  : this.firstName ,
        lastName  : this.lastName
    }
};

module.exports = User;