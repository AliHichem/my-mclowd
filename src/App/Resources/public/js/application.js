

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


mcApp.factory('ResponseHandler', function() {
    return {
        enabled: true,

        disable: function() {
            this.enabled = false;
        },

        enable: function() {
            this.enabled = true;
        },

        handle: function (response) {
            if (this['handle' + response.status]) {
                this['handle' + response.status](response);
            }
        },

        handle201: function() {
        },

        handle404: function() {
            alert('Opps, we cannot process your request.');
        },

        handle422: function(response) {
            if (this.enabled) {                
                //Modal.showMessages('Fix the following errors', response.data.errors);
            }
        },

        handle500: function() {
            alert('Opps, an error appeared. Pls refresh page and try again.');
        }
    }
})


mcApp.config(function($httpProvider) {
    var interceptor = function(ResponseHandler, $rootScope, $q) {

        function intercept(response) {
            // handle response
            ResponseHandler.handle(response);

            return response;
        }

        return function(promise) {
            return promise.then(intercept, function(response) {
                return $q.reject(response)
            });
        }

    };
    $httpProvider.responseInterceptors.push(interceptor);
});


window.mcApp = mcApp;