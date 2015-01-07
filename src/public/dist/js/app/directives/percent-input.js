angular.module('fzyskeleton').directive('percentFilter', ["$filter", function($filter) {
    return {
        restrict: 'A',
        require: 'ngModel',
        link: function(scope, element, attrs, ngModelController) {
            ngModelController.$parsers.push(function(data) {
                //convert data from view format to model format
                var converted = parseFloat((data+'').replace(/[%]/g, ''))/100;
                return isNaN(converted) ? 0.0 : converted;
            });

            ngModelController.$formatters.push(function(data) {
                if (data === null || isNaN(data)) {
                    return data;
                }
                return $filter('number')((data * 100), 2) + '%';
            });
        }
    }
}]);