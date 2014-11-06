angular.module('ncsolar').service('listService', function(DTOptionsBuilder, DTColumnBuilder) {
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
            }, arguments[2] || {});

            scope.dtOptions = DTOptionsBuilder.newOptions()
                .withOption('ajax', {
                    // Either you specify the AjaxDataProp here
                    // dataSrc: 'data',
                    url: restObj.getRequestedUrl(),
                    type: 'GET'
                })
                // or here
                .withDataProp('data')
                .withOption('serverSide', true)
                .withOption('processing', true)
                .withPaginationType('full_numbers');
            if (options.columns && angular.isArray(options.columns)) {
                var cols = [];
                angular.forEach(options.columns, function(colData, index) {
                    cols.push(DTColumnBuilder.newColumn(colData.property).withTitle(colData.label))
                });
                scope.dtColumns = cols;
            }
        }
    }
});
