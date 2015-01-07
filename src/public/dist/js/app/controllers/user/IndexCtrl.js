angular.module('fzyskeleton').controller('IndexCtrl', ["$scope", "Restangular", "listService", function($scope, Restangular, listService) {
    listService.attachTo($scope, Restangular.one('users'), {
        columns: [{
            property: 'id',
            label: 'ID'
        },{
            property: 'email',
            label: 'Email'
        },{
            property: 'firstName',
            label: 'First Name'
        },{
            property: 'lastName',
            label: 'Last Name'
        }]
    })

}]);
