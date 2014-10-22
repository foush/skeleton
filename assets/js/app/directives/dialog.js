angular.module('votr').directive('dialog', function(dialogService, $log) {
    return {
        restrict:'A',
        link: function(scope, element, attrs) {
            // TODO: Either generate a unique id or throw an exception if an id isn't passed
            var id = attrs.dialogId || false;
            // dialog options
            var options = $.extend({
                width: 1000,
                draggable: false,
                autoOpen: false,
                modal: true,
                resizable: false
            }, scope.$eval(attrs.dialogOptions) || {});

            dialogService.init(id, options, element);
        }
    };
});