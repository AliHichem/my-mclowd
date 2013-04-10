

var mcApp = angular.module('Marketplace', ['ngResource'], function($provide, $httpProvider) {

})

mcApp.config(function($httpProvider) {
    //$httpProvider.defaults.headers.post  = {'Content-Type': 'application/x-www-form-urlencoded'};
});

mcApp.factory('Employment', function($resource) {
    return $resource(Mclowd.url('/contractor/employment/:id'), {id: '@id'}, {

    });
});

mcApp.factory('Education', function($resource) {
    return $resource(Mclowd.url('/contractor/education/:id'), {id: '@id'}, {

    });
});

mcApp.factory('Qualification', function($resource) {
    return $resource(Mclowd.url('/contractor/qualification/:id'), {id: '@id'}, {

    });
});

mcApp.factory('ContractorTask', function($resource) {
    return $resource(Mclowd.url('/contractor/contractor-tasks/:id'), {id: '@id'}, {

    });
});

mcApp.factory('AcceptProposal', function($resource) {
    return $resource(Mclowd.url('/task/accept_proposal'), {}, {

    });
});


window.mcApp = mcApp;