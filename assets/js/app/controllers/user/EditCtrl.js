angular.module('fzyskeleton').controller('EditCtrl', function($scope, Restangular, editService, alert, formAlert) {
    editService.init($scope, {
        objectScopeName: 'user',
        entityIdName: 'user',
        updateR: Restangular.one('user'),
        tag: 'mainEntityTag'
    });

});