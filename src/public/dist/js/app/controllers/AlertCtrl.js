angular.module('votr').controller('AlertCtrl',["$scope", "alert", function ($scope,alert) {

    $scope.init = function(messages) {
        alert.add(messages);
    }

    $scope._alert = alert;
}]);