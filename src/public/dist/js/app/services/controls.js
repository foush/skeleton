angular.module('votr').service('ControlsService', [function () {
    return {
        editUser: function (user) {
            if (user.id) {
                return '<a class="button primary" href="/settings/edit-user/' + user.id + '">Edit</a>';
            } else {
                return null;
            }
        },
        impersonateUser : function (user) {
            if (user.id) {
                return '<a class="button secondary" href="/settings/impersonate/' + user.id + '">Login As This User</a>';
            } else {
                return null;
            }
        }
    };
}])