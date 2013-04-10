

var mcApp = angular.module('Marketplace', ['ngResource'], function($provide, $httpProvider) {

})

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

mcApp.controller('RootController', function ($rootScope) {

    /* ajax spinner flag */
    $rootScope.show_spinner = false;

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
                $mbody = $('#error-modal .modal-body');
                $mbody.html('');
                $.each(response.data, function( field, error ) {
                    var i = 0;
                    var tranformedField = '';
                    while (i <= field.length){
                        character = field.charAt(i);                        
                        if (character === character.toUpperCase()) {
                            tranformedField += ' '+ character.toLowerCase();
                        } else if(i === 0) {
                            tranformedField += character.toUpperCase();
                        } else {
                            tranformedField += character;
                        }
                        i++;
                    }
                                        
                    $mbody.append('<div class="modal-error-field"><strong>'+ tranformedField + '</strong>: ' + error+'</div>');                    
                });                
                $('#error-modal').modal('show');
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
            $('body').removeClass('wait');
            ResponseHandler.handle(response);
            return response;
        }

        return function(promise) {
            $rootScope.show_spinner = true;
            $('body').addClass('wait');
            return promise.then(intercept, function(response) {
                intercept(response)
                return $q.reject(response)
            });
        }

    };
    $httpProvider.responseInterceptors.push(interceptor);
});


window.mcApp = mcApp;