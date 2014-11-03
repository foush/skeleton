angular.module('fzyskeleton').service('editService', ["Restangular", "alert", "formAlert", "$window", "$log", function(Restangular, alert, formAlert, $window, $log) {
    return {
        init: function($scope) {
            var options = $.extend({
                objectScopeName: 'entity',
                entityIdName: 'id',
                updateEndPoint : 'update',
                tag: 'mainEntityTag',
                /*
                 * If you need to do some pre-processing on the entity object, pass in a callback
                 * .... for instance, we can convert null object data members to null so they
                 * work with Select2. Just implement a callback function and be sure to return
                 * the modified entity.
                 */
                entityCallback : function(entity) {
                    return entity;
                },
                /*
                 * If you need to inject custom functionality upon successful submission ...
                 * then implement and pass a callback here
                 * By default, on success the window should redirect
                 * But, you may want custom functionality, for instance if saving in a dialog, it
                 * may not make sense to redirect, presumably.
                 *
                 */
                successCallback : function(data) {
                    window.location.href = data.redirect;
                },
                errorCallback : function(data) {
                    formAlert.add(data.messages);
                    alert.add('There was a problem with your submission. Please correct the issues detailed below.');
                    $window.scrollTo(0,0);
                },
                failCallback : function(data) {
                    alert.add('The server encountered an unknown error. Please try again in a few moments.');
                    $window.scrollTo(0,0);
                },
                /*
                 * If you need to do some data manipulation on the entity before posting to the respective edit
                 * api, you can use this callback function to perform the manipulations.
                 *
                 * For instance, this function is called just prior to posting the data.
                 */
                entityPostDataFn : function(entity) {
                    return entity;
                }
            }, arguments[1] || {});

            if (!options.fnUpdateR) {
                options.fnUpdateR = function(entity) {
                    if (!options.updateR) {
                        throw "Please set an update resource function (fnUpdateR) or define a resource (updateR)";
                    }
                    return options.updateR;
                }
            }

            $scope[options.objectScopeName] = {};
            $scope['_'+options.objectScopeName] = {}
            $scope.dirty = false;

            // inits necessary services for scope
            $scope.init = function(entity) {
                if (options.entityCallback && typeof(options.entityCallback) === "function") {
                    entity = options.entityCallback(entity);
                }
                $scope[options.objectScopeName] = entity;
                $scope['_'+options.objectScopeName] = angular.copy($scope[options.objectScopeName]);

                if (options.postInitFn) {
                    options.postInitFn.apply(this, arguments);
                }

                // This provides an additional level of extensibility
                // as a 3rd controller argument, you can pass in an options
                // array (key,value) pairs, that will be used as a final
                // means of extending the edit service options
                if(!angular.isUndefined(arguments[2]) && arguments[2] !== null) {
                    options = $.extend(options, arguments[2] || {});
                }
            }

            $scope.save = function(entity) {
                $scope.saving = true;
                if(!angular.isUndefined(entity.id) && entity.id !== null) {
                    entity[options.entityIdName] = entity.id;
                }

                formAlert.remove();

                var entityData = entity;

                if (options.entityPostDataFn) {
                    entityData = options.entityPostDataFn(entity);
                }

                options.fnUpdateR(entity).post(options.updateEndPoint,entityData).then(function(data) {
                    // Handle Errors
                    if (!data || !data.success) {
                        // re-enable input for correcting.
                        $scope.saving = false;
                        // Handle Errors
                        if (options.errorCallback) {
                            options.errorCallback(data);
                        }
                    } else {
                        // Handle Success
                        if (options.successCallback) {
                            // leave inputs disabled
                            options.successCallback(data);
                        } else {
                            $scope.saving = false;
                        }
                    }
                    return;
                }, function(data) {
                    // Failure
                    if (options.failCallback) {
                        options.failCallback(data);
                    }

                    $scope.saving = false;
                })
            }
        }
    };
}])