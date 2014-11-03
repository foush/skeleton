/**
 * Service which returns an object that has a single method: generateConfig
 *
 * generateConfig
 *  @param url (required) URL to be used for searching the autocomplete
 *  @param ajaxOptions (optional) Object of ajax options to be used/override defaultConfig's ajax entry
 *      common overrides: data function to return params for the search request
 *  @param select2Options (optional) Object of general options to be used/override defaultConfig
 *      common overrides:
 *          formatResult: function to return formatted result to be listed in result set
 *          formatSelection: function to return formatted result when selected
 *  @param options (optional) Object of configuration values
 *      resource: string value of the Restangular request for loading a single instance of this entity
 *
 *
 */
angular.module('fzyskeleton').service('select2', function(Restangular) {
    var defaultConfig = {
        allowClear:true,
        placeholder: '',
        id: function(result) {
            return result.id;
        },
        ajax: {
            dataType: 'json',
            data: function(term, page) {
                var limit = 10;
                return {
                    query:term,
                    limit: limit,
                    offset: (page-1) * limit
                }
            },
            results: function(result, page) {
                return {results: result.data, more: (result.meta.offset + result.meta.limit < result.meta.total)}
            }
        },
        formatResult: function(result, ev, context, defaultFn) {
            return result.name;
        },
        formatSelection: function(result) {
            return result.name;
        }
    };

    return {
        generateConfig: function(url) {
            var ajax = arguments[1] || {};
            var topLevel = arguments[2] || {};
            var options = $.extend({
                resource: '',
                resourceParams: {},
                getInitDataFromElement: function(element, value) {
                    return angular.fromJson(value);
                }
            }, arguments[3] || {});

            if (!options.getInitSelection) {
                options.getInitSelection = function(value, data, element, callback) {
                    Restangular.one(options.resource, data.id).get(options.resourceParams).then(function(response) {
                        if (response && response.data && response.data.length) {
                            callback(response.data[0]);
                            return;
                        }
                        console.log('Failed to lookup', value);
                        callback({name: value});

                    }, function(response) {
                        console.log('unable to locate office', value);
                        callback({name: value});
                    })
                }
            }

            var result = $.extend({}, defaultConfig, {
                initSelection: function(element, callback) {
                    var value = element.val();
                    if (!value.length) {
                        return;
                    }
                    options.getInitSelection(value, options.getInitDataFromElement(element, value), element, callback);
                }
            }, topLevel);
            result.ajax = $.extend({}, result.ajax, ajax, {url: url});
            return result;
        }
    };
})