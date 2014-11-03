angular.module('fzyskeleton').service('UtilitiesService', [function ($log) {
    // Convert Null Object representation to null for the sake of Select2
    var transformEntityNull = function (entity, field) {
        if (angular.isUndefined(entity[field]) || entity[field] === null || angular.isUndefined(entity[field].id) || entity[field].id === null) {
            entity[field] = null;
        }
        return entity;
    };
    // Convert multiple Null Object representation of data members to null for the sake of Select2
    var transformEntityNulls = function(entity, fields) {
        angular.forEach(fields, function(value, key) {
            entity = transformEntityNull(entity, value);
        });
        return entity;
    };
    return {
        transformEntityNull : transformEntityNull,
        transformEntityNulls : transformEntityNulls
    };
}])