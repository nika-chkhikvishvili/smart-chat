/**
 * Created by jedi on 2017-04-25
 */

'use strict';

function GuestUser(user) {
    user = user || {};
    if (!(this instanceof GuestUser)) return new GuestUser(user);
    this.guestUserId = user.hasOwnProperty('guestUserId') ? user.guestUserId : null;
    this.firstName   = user.hasOwnProperty('firstName')   ? user.firstName   : null;
    this.lastName    = user.hasOwnProperty('lastName')    ? user.lastName    : null;
    this.mail        = user.hasOwnProperty('mail')        ? user.mail        : null;
    this.ip_string   = user.hasOwnProperty('ip')          ? user.ip          : null;
    this.sockets     = {};
}

GuestUser.prototype.getInsertObject = function (socketId) {
    return {
        online_users_name       : this.firstName,
        online_users_lastname   : this.lastName,
        ip_string               : this.ip_string
    };
};

GuestUser.prototype.getLimited = function () {
    return {
        guestUserId : this.guestUserId ,
        firstName   : this.firstName ,
        lastName    : this.lastName
    }
};

module.exports = GuestUser;
