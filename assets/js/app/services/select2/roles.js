angular.module('votr').service('select2Role', function(Restangular, select2) {
    var resource = 'role';
    var roles = Restangular.all(resource).all('index');
    var loadingInit = false;
    return {
        config: function() {
            return select2.generateConfig(roles.getRestangularUrl(), {}, {
                ajax: {
                    url: '/api/v1/role',
                    dataType: 'json',
                    data: function(term, page) {
                        var limit = 10;
                        return {
                            name:term,
                            limit: limit,
                            offset: (page-1) * limit
                        }
                    },
                    results: function(result, page) {
                        return {results: result.data, more: (result.meta.offset + result.meta.limit < result.meta.total)}
                    }
                },
                id: function(result) {
                    return result.roleId;
                },
                placeholder: 'Select A Role',
                formatResult: function(result, ev, context, defaultFn) {
                    return result.displayName;
                },
                formatSelection: function(result) {
                    return result.displayName;
                }
            }, {
                resource: resource
            });
        }
    }
});