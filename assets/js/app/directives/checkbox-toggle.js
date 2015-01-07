angular.module('fzyskeleton').directive('fzyCheckboxToggle', function() {
    return {
        controller: function($scope, $element, $attrs) {
            var args = angular.fromJson(attrs.fzyCheckboxToggle);
            $scope.fzyCheckboxToggle = function() {
                if ($scope[args.disabled]) {
                    return;
                }
                $scope[args.model] = ($scope[args.model] == args.value ? false : args.value);
            }
        }
    };
})