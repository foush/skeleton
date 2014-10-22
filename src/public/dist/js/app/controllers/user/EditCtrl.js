angular.module('votr').controller('EditCtrl', ["$scope", "Restangular", "editService", "alert", "formAlert", function($scope, Restangular, editService, alert, formAlert) {
    editService.init($scope, {
        objectScopeName: 'user',
        entityIdName: 'user',
        updateR: Restangular.one('user'),
        tag: 'mainEntityTag'
    });

}]);