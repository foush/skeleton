var votr = angular.module('votr', [
    'ngSanitize',
    'textAngular',
    'restangular',
    'angularFileUpload',
    'datatables',
    'ui.select2',
    'ui.sortable',
    'ui.date',
    'dialogService',
    'debounce',
    'checklist-model'
]);

votr.factory('_', function() {
    return window._;
});

votr.config(function(RestangularProvider) {
    RestangularProvider.setBaseUrl('/api/v1/');
    RestangularProvider.addResponseInterceptor(function(response, operation) {
        if (operation === "getList" && response && response.data) {
            var restangResp  = angular.copy(response.data);
            restangResp.data = angular.copy(response.data);
            restangResp.meta = angular.copy(response.meta);
            return restangResp;
        }
        return response;
    });
}).config(['$provide', function($provide) {
    $provide.decorator('taOptions', ['$delegate', function(taOptions) {
        taOptions.toolbar = [
            ['h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'p', 'pre', 'quote'],
            ['bold', 'italics', 'underline', 'ul', 'ol', 'redo', 'undo', 'clear'],
            ['justifyLeft','justifyCenter','justifyRight'], ['indent', 'outdent', 'html', 'insertLink']
        ];
        return taOptions;
    }]);
}]);
