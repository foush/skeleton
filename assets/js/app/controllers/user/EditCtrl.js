angular.module('fzyskeleton').controller('EditCtrl', function($scope, Restangular, editService, alert, formAlert, select2Role) {

    editService.init($scope, {
        objectScopeName: 'user',
        entityIdName: 'user',
        updateR: Restangular.one('users'),
        tag: 'user',
        postInitFn: function(user) {
            $scope.roleOptions = select2Role.config({}, {}, {
                getInitSelection: function(value, data, element, callback) {
                    callback(user.roleObject ? user.roleObject : {roleId: 'guest',displayName: 'Guest'});
                }
            });
        },
        entityPostDataFn: function(entity) {
            var data = angular.copy(entity);
            data.role = entity.role && entity.role.roleId ? entity.role.roleId : null;
            return data;
        }
    });

});
