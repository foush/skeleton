/**
 * Using angularjs service inheritance
 *
 *
 * The way you use this service:
 *
 *
 * Methods:
 *
 * alert.add
 *  if first parameter is a string, message is displayed in default namespace, second parameter is treated as an options
 *      hash where you may specify 'expires' to set the seconds until it disappears (defaults to 5) and 'type' where you can specify
 *      if this message is 'success','warning','info','error' (defaults to 'error') and 'namespace' where you can specify
 *      what namespace the message should be stored in (uses default page namespace)
 *  if first parameter is an array, second parameter is treated as an options array as before, and these options are applied
 *      to each message in the array
 *  if first parameter is an object, second parameter is considered the 'namespace' value (uses default page namespace) if not specified
 *      Object is iterated over with each top level key being treated as the 'message type' string and the value being passed
 *      to alert.add
 *
 * alert.remove
 *  if passed an alert ID, removes that alert
 *  else removes all alerts within this namespace
 *
 *  alert.getAlerts
 *      if no arguments passed, returns all alerts in default namespace
 *      if 1 argument passed, treated as the namespace to query for alerts
 *
 *
 * alert.add("Error message") will display an error message
 *
 * alert.add("Success message", {type: "success"}) will display a success message
 *
 * alert.add("Warning message that does not fade out", {type:"warning", expires:0}) will display a warning message that does not fade away
 *
 * alert.add("Brief error", {expires:1}) will flash an error message for 1 second
 *
 *
 */
angular.module('fzyskeleton').factory('alertFactory', function($timeout, $filter) {
    var baseService = function() {
        this._alertCount = 1;
        this._alerts = {};
        this._defaultNs = 'default';
    };
    baseService.prototype.getAlerts = function () {
        var self = this;
        var namespace = arguments[0] || this._defaultNs;
        var alerts = [];
        angular.forEach(self._alerts, function(alert, id) {
            if (alert.namespace == namespace) {
                alerts.push(alert);
            }
        });
        return alerts; //this.$filter('filter')(this._alerts, {namespace: namespace}, true);
    };
    baseService.prototype.add = function (message) {
        // needed because 'this' changes scope reference within angular.forEach
        var self = this;
        // if message is an object, key is the type, value is the array of messages
        if (typeof message == 'object' && !$.isArray(message)) {
            var namespace = arguments[1] || self._defaultNs;
            self.addObject(message, namespace);
            return;
        }
        var options = $.extend({
            type: 'error',
            expires: 5,
            namespace: self._defaultNs
        },arguments[1]||{});
        // if messages are an array, iterate over each one
        if ($.isArray(message)) {
            self.addArray(message, options);
            return;
        }
        // standard case: message is string, options in second parameter
        self.addString(message, options);
    };

    baseService.prototype.addObject = function (message, namespace) {
        // needed because 'this' changes scope reference within angular.forEach
        var self = this;
        angular.forEach(message, function(messageArray, messageType) {
            self.add(messageArray, {type: messageType, namespace: namespace});
        });
    };
    baseService.prototype.addArray = function (message, options) {
        // needed because 'this' changes scope reference within angular.forEach
        var self = this;
        angular.forEach(message, function(msg, index) {
            self.add(msg, options);
        });
    };
    baseService.prototype.addString = function (message, options) {
        var self = this;
        $timeout(function(){
            var alertId = self._alertCount++;
            self._alerts[alertId] = self.createAlertObject(alertId, message, options);
            if (options.expires) {
                $timeout(function(){
                    self.remove(alertId);
                },options.expires * 1000);
            }
        },0);
    };
    baseService.prototype.createAlertObject = function(alertId, message, options){
        return {
            id: alertId,
            message: message,
            type: options.type,
            namespace: options.namespace
        };
    };
    baseService.prototype.removeAlertsFromElement = function () {
        var self = this;
        // if id provided, just remove that alert id
        var namespace = arguments[0] || null;
        var element   = arguments[1] || null;
        angular.forEach(self._alerts, function(alert, id) {
            if (alert.namespace == namespace && alert.element == element) {
                self.remove(id);
            }
        });
    };
    baseService.prototype.remove = function () {
        var self = this;
        // if id provided, just remove that alert id
        var alertId = arguments[0] || null;
        $timeout(function(){
            if (alertId === null) {
                // clear all alerts
                self._alerts = {};
                self._alertCount = 1;
            } else if (self._alerts[alertId]) {
                delete self._alerts[alertId];
            }
        },0)
    };
    baseService.prototype.cls = function (type) {
        var cls = '';
        switch (type) {
            case 'success':
                cls = 'success';
                break;
            case 'info':
            case 'warning':
                cls = 'info';
                break;
            case 'error':
            default:
                cls = 'error';
                break;
        }
        return cls;
    };
    return baseService;
});
angular.module('fzyskeleton').service('alert', function(alertFactory) {
    var alerts = new alertFactory();
    return alerts;
});

angular.module('fzyskeleton').factory('formAlertFactory', function($timeout, $filter, alertFactory) {

    var formService = function() {
        alertFactory.apply(this, arguments);
    };

    formService.prototype = new alertFactory();

    formService.prototype.addObject = function(mObj, namespace) {
        var self = this;
        angular.forEach(mObj, function(elementMessages, namespace) {
            angular.forEach(elementMessages, function(message, elementName) {
                    self.addString(message, {namespace: namespace, element: elementName});
            });
        });
    }
    formService.prototype.createAlertObject = function(alertId, message, options){
        var alert = alertFactory.prototype.createAlertObject(alertId, message, options);
        alert.element = options.element;
        return alert;
    };

    formService.prototype.getAlerts = function (namespace, elementName) {
        var self = this;
        var alerts = [];
        angular.forEach(self._alerts, function(alert, id) {
            if (alert.namespace == namespace && alert.element == elementName) {
                alerts.push(alert);
            }
        });
        return alerts; //this.$filter('filter')(this._alerts, {namespace: namespace}, true);
    };
    return formService;
});
angular.module('fzyskeleton').service('formAlert', function(formAlertFactory) {
    var alerts = new formAlertFactory();
    return alerts;
});
