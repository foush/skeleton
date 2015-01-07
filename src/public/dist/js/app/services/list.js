angular.module('fzyskeleton').service('listService', ["DTOptionsBuilder", "DTColumnBuilder", function(DTOptionsBuilder, DTColumnBuilder) {
    return {
        /**
         * Takes an angular scope object, a Restangular endpoint object and an optional options array
         * Sets up the basic environment for a datatable request.
         * @param scope
         * @param restObj
         * @param options (optional)
         */
        attachTo: function(scope, restObj) {
            var options = angular.extend({}, {
                // TODO: add options as needed
                fnData: function(dtData) {
                    return dtData;
                }
            }, arguments[2] || {});
            scope.dtOptions = DTOptionsBuilder.newOptions()
                .withOption('ajax', {
                    // Either you specify the AjaxDataProp here
                    // dataSrc: 'data',
                    url: restObj.getRequestedUrl(),
                    type: 'GET',
                    data: options.fnData
                })
                // or here
                .withDataProp('data')
                .withOption('serverSide', true)
                .withOption('processing', true)
                .withOption('order', options.order || [0, 'asc'])
                .withPaginationType('full_numbers');
            if (options.columns && angular.isArray(options.columns)) {
                var cols = [];
                angular.forEach(options.columns, function(colData, index) {
                    cols.push(DTColumnBuilder.newColumn(colData.property).withTitle(colData.label))
                });
                scope.dtColumns = cols;
            }
        },
        refresh: function(scope) {
            scope.dtOptions.reloadData();
        }
    }
}]);
