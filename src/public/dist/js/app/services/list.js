angular.module('fzyskeleton').service('listService', ["Restangular", "$q", "$log", "debounce", function(Restangular, $q, $log, debounce) {
    var getListItems = function($scope, dataVar, route, params, dtCallback, options) {

        if ($scope.abort) {
            $scope.abort.resolve();
        }

        $scope.abort = $q.defer();

        // Prevent duplicate requests
        var newParams = angular.copy(params);
        newParams.route = route;
        newParams.dataVar = dataVar;
        if(typeof $scope.prevParams == "object" && params != $scope.prevParams) {
            var hasDiffParams = true;
            for(var key in params) {
                if($scope.prevParams.hasOwnProperty(key) || $scope.prevParams[key] !== params[key]) {
                    hasDiffParams = false;
                    break;
                }
            }
        }

        $scope.prevParams = newParams;

        var options = $.extend({
            resource: Restangular.all(route)
        }, arguments[5] || {});

        $log.debug(arguments);

        if (!options.resourceFn) {
            options.resourceFn = function() {
                return options.resource;
            }
        }

        // Prep API Parameters
        if(params.search && params.search.value)
            params.q = params.search.value;

        // Don't send unneeded DataTables crazy arrays of objects
        delete params.order;
        delete params.columns;
        delete params.search;

        // define post-request callback
        var postCb = function(response) {
            $scope[dataVar] = response.data.length ? response.data : [];
            if(params.serverSide) {
                response.draw = parseInt(params.draw);
                response.recordsTotal = response.meta.total;
                response.recordsFiltered = response.meta.total;
                dtCallback(response);
                $scope.prevResponse = response;
            }
        };

        // fire the list request
        return options.resourceFn().withHttpConfig({timeout: $scope.abort.promise}).getList(params).then(function(restangResp) {
            return { data: restangResp.data, meta: restangResp.meta }; // Cleanup Restangular's bizarre response
        }).then(postCb);
    };

    return {
        /**
         * Prep parameters and callback, then send request to API for list
         * @param $scope object Controller's scope
         * @param dataVar string Data storage variable (eg. "claims" for $scope.claims)
         * @param route string API route to hand to restangular
         * @param params object Params for GET request
         * @param dtCallback function Datatables own callback function
         * @returns {ng.IPromise<TResult>|*}
         */
        getList: function($scope, dataVar, route, params, dtCallback, options) {
            return getListItems($scope, dataVar, route, params, dtCallback, options);
        },
        getDebouncedList:debounce(function($scope, dataVar, route, params, dtCallback) {
                return getListItems($scope, dataVar, route, params, dtCallback);
        },500),
        buildOptions: function(isServerSide) {
            if(isServerSide) {
                return {
                    dom: 'tipr',
                    serverSide: true,
                    processing: true,
                    drawCallback: function(){ jQuery(this).find('.dataTables_empty').remove() }, // "no data" row fix,
                    initComplete: function(){
                        var table = jQuery(this);
                        var dTable = table.DataTable();
                        var searchbar = table.closest(".ng-scope").find(".search-bar-dt");
                        if(!searchbar || searchbar.css("visibility") == "visible")
                            return;

                        // Rebuild DT's Page Length Control
                        var lengthChanger = searchbar.find('.dataTables_length');
                        if(lengthChanger) {
                            lengthChanger.find("select").change(function() {
                                var curLength = dTable.page.len();
                                var newLength = parseInt(jQuery(this).val());
                                if(curLength != newLength)
                                    dTable.page.len(newLength).draw();
                            } );
                        }

                        // Rebuild DT's Search Input
                        var searchField = searchbar.find('.dataTables_filter input');
                        if(searchField) {
                            window.searchTimeoutId = false;
                            searchField.on('keyup paste cut', function(e) {
                                var value = jQuery(this).val();
                                var searchFn = function(dTable, value) {
                                    dTable.search(value).draw();
                                };
                                clearTimeout(window.searchTimeoutId);
                                window.searchTimeoutId = false;
                                if(e && e.keyCode && e.keyCode==13) {
                                    searchFn(dTable, value);
                                } else {
                                    window.searchTimeoutId = setTimeout(function() { searchFn(dTable, value); }, 1000);
                                }
                            }).keypress(function(e){return e.keyCode!=13});
                        }

                        searchbar.css("visibility", "visible");
                    }
                };
            }
            return { };
        },

        buildColumns: function(numOfCols) {
            var columnDefs = [];
            for(var i=0; i < numOfCols; i++) {
                columnDefs.push({ targets: i });
            }
            columnDefs[numOfCols-1].orderable  = false;
            columnDefs[numOfCols-1].searchable = false;
            return columnDefs;
        },


        addToScope: function($scope) {
            var options = $.extend({
                initFn: function() {},
                processEntriesFn: function(entries) {return entries;},
                scopeModel: 'entries',
                pageLoadingModel: 'loading',
                pageMetaModel: 'meta',
                pageParamsModel: 'params',
                startParams: {}
            }, arguments[1] || {});

            if (!options.resource) {
                if (options.resourceName) {
                    options.resource = Restangular.one(options.resourceName)
                } else {
                    throw "Please specify a resourceName or a resource in the configuration.";
                }
            }


            $scope.init = function() {
                options.initFn.apply(this, arguments);
            };

            $scope.paginationLast = function(last, total) {
                return Math.min(last, total);
            };

            $scope.loadOffset = function(offset, limit) {
                offset = parseInt(offset);
                if (offset < 0) {
                    offset = 0;
                } else if (offset >= $scope[options.pageMetaModel].total) {
                    offset = $scope[options.pageMetaModel].total - limit;
                }
                var p = $scope[options.pageParamsModel];
                p.offset = offset;
                p.limit = limit;
                $scope[options.pageParamsModel] = p;
                return true;
            };

            var loadEntities = function(params) {
                if ($scope[options.pageLoadingModel]) {
                    return;
                }
                $scope[options.pageLoadingModel] = true;
                $scope[options.scopeModel] = [];
                options.resource.get(params).then(function(response) {
                    $scope[options.pageLoadingModel] = false;
                    $scope[options.scopeModel] = options.processEntriesFn(response.data);
                    $scope[options.pageMetaModel] = response.meta;
                }, function(response) {
                    $scope[options.pageLoadingModel] = false;
                });
            };
            $scope[options.pageParamsModel] = options.startParams;

            $scope.$watch(options.pageParamsModel, function(newParams, oldParams) {
                loadEntities(newParams);
            }, true);

        }

    };
}]);