//Used to pass datatables events through for use in angular
angular.module('fzyskeleton')
    .directive('dtEvents', function () {
        return {
            restrict: 'A',
            link: function ($scope, element, attrs) {
                $(element).on('length.dt', function ( e, settings, len ) {
                    $scope.$emit('event:dataTableLengthChanged', {
                        element: element,
                        length: len,
                        table: $(element).DataTable()
                    });
                } );
            }
        }
    });
