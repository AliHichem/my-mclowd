
var mcApp = angular.module('Marketplace', ['ngResource']);
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
    $scope.newTask = {name: '', amount: ''};
    $scope.newQualification = {name: ''};
    $scope.changed = {city: 0};
    //$http.defaults.headers.post["Content-Type"] = "application/x-www-form-urlencoded";

    $scope.$watch('profile.city', function(newValue, oldValue){
        $scope.changed.city++;
    });

    $scope.addTask = function () {
        if (!$scope.newTask.name.length) {
            return;
        }
        if (typeof $scope.profile.tasks === "undefined") {
            $scope.profile.tasks = [];
        }        
        $scope.profile.tasks.push($scope.newTask);

        $scope.newTask = {name: '', amount: ''};
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

        $scope.newQualification = {name: ''};
    };

    $scope.addEducation = function () {        
        if (!$scope.newEducation.institution_name.length) {
            return;
        }
        if (typeof $scope.profile.educations === "undefined") {
            $scope.profile.educations = [];
        }        

        $scope.profile.educations.push($scope.newEducation);        
        var e = new Education($scope.newEducation);
        e.$save();
        $scope.newEducation = {institution_name: '', degree: ''};
    };

    $scope.addEmployment = function () {    
        if (typeof $scope.profile.employment_history === "undefined") {
            $scope.profile.employmentHistory = [];
        }        
        $scope.profile.employmentHistory.push($scope.newEducation);        
        var e = new Employment($scope.newEmployment);
        e.$save();
        $scope.newEmployment = {};
    };

    $scope.removeTask = function (task) {
        $scope.profile.tasks.splice($scope.profile.tasks.indexOf(task), 1);
    };

    $scope.removeQualification = function (qualification) {
        $scope.profile.qualifications.splice($scope.profile.qualifications.indexOf(qualification), 1);
    };

    $scope.removeEducation = function (education) {
        $scope.profile.educations.splice($scope.profile.educations.indexOf(education), 1);
    };

    $scope.removeEmployment = function (employmet) {
        $scope.profile.employmentHistory.splice($scope.profile.employmentHistory.indexOf(employmet), 1);
    };

    $scope.saveCity = function(task) {
        $http.post('/app_dev.php/contractor/update-city', {form: {city: task.city}});
    };

    $scope.saveTagLine = function(task) {
        $http.post('/app_dev.php/contractor/update-tag-line', {form: {tagLine: task.tagLine}});
    };

    $scope.saveFullname = function(task) {
        $http.post('/app_dev.php/contractor/update-fullname', {form: {fullName: task.fullName}});
    };

    $scope.saveOverview = function(task) {
        $http.post('/app_dev.php/contractor/update-overview', {form: {overview: task.overview}});
    };
}
