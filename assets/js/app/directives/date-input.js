angular.module('fzyskeleton').directive('dateFilter', function($filter) {
    return {
        restrict: 'A',
        require: 'ngModel',
        link: function(scope, element, attrs, ngModelController) {
            ngModelController.$parsers.push(function(data) {
                //convert data from view format to model format
                var converted = data.getTime();
                return isNaN(converted) ? null : converted / 1000;
            });

            ngModelController.$formatters.push(function(data) {
                if (!data) {
                    return data;
                }
                //convert data from model format to view format
                return new Date(data * 1000);
            });
        }
    }
});