

var mcApp = angular.module('Marketplace', ['ngResource'], function($provide, $httpProvider) {
   /*$provide.factory('myHttpInterceptor', function($q) {
        return function(promise) {
        
            return promise.then(
                function(response) {
                    
                    response.data = jQuery.parseJSON(response.data);
                    console.log(response.data);
                    return response;
                }, function(response) {
                    response.data = jQuery.parseJSON(response.data);
                    return $q.reject(response.data);
                }
            );
        }
   });
   $httpProvider.responseInterceptors.push('myHttpInterceptor');*/
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

function ContractorEditCtrl($scope, $http, Qualification, Employment, Education) {
    $scope.data = {};

    $scope.addTask = function () {
        if (!$scope.newTask.name.length) {
            return;
        }
        if (typeof $scope.profile.tasks === "undefined") {
            $scope.profile.tasks = [];
        }        
        $scope.profile.tasks.push($scope.newTask);

        $scope.newTask = {};
    };


    $scope.addQualification = function () {
        if (!$scope.newQualification.name.length) {
            return;
        }
        if (typeof $scope.profile.qualifications === "undefined") {
            $scope.profile.qualifications = [];
        }        
        $scope.profile.qualifications.push($scope.newQualification);
        var qualification = new Qualification($scope.newQualification);
        qualification.$save();

        $scope.newQualification = {};
    };

    $scope.addEducation = function () {                
        var e = new Education($scope.newEducation);
        e.$save(function(data) {
            $scope.data.educationHistory.push(e);  
            $scope.newEducation = {};
        });
    };

    $scope.addEmployment = function () {   
        var e = new Employment($scope.newEmployment);
        e.$save(function(data) {
            $scope.data.employmentHistory.push(e);  
            $scope.newEmployment = {};
        });         
    };

    $scope.removeTask = function (task) {
        $scope.profile.tasks.splice($scope.profile.tasks.indexOf(task), 1);
    };

    $scope.removeQualification = function (qualification) {
        $scope.profile.qualifications.splice($scope.profile.qualifications.indexOf(qualification), 1);
    };

    $scope.removeEducation = function (education) {
        education.$remove();
        $scope.data.educationHistory.splice($scope.data.educationHistory.indexOf(education), 1);
    };

    $scope.removeEmployment = function (employmet) {
        employmet.$remove();
        $scope.data.employmentHistory.splice($scope.data.employmentHistory.indexOf(employmet), 1);
    };

    $scope.saveCity = function(task) {
        $http.post(Mclowd.path('contractor_update_city'), {form: {city: task.city}});
    };

    $scope.saveTagLine = function(task) {
        $http.post(Mclowd.path('contractor_update_tag_line'), {form: {tagLine: task.tagLine}});
    };

    $scope.saveFullname = function(task) {
        $http.post(Mclowd.path('contractor_update_fullname'), {form: {fullName: task.fullName}});
    };

    $scope.saveOverview = function(task) {
        $http.post(Mclowd.path('contractor_update_overview'), {form: {overview: task.overview}});
    };

    $scope.initEmployments = function() {
        $scope.data.employmentHistory = [];
        for (i in $scope.profile.employmentHistory) {
            $scope.data.employmentHistory.push(new Employment($scope.profile.employmentHistory[i]));
        }
    };

    $scope.initEducations = function() {
        $scope.data.educationHistory = [];
        for (i in $scope.profile.educationHistory) {
            $scope.data.educationHistory.push(new Education($scope.profile.educationHistory[i]));
        }
    };
}

window.mcApp = mcApp;