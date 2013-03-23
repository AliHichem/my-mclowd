
var mcApp = angular.module('Marketplace', []);

function ContractorEditCtrl($scope, $http) {
    $scope.newTask = {name: '', amount: ''};
    $scope.changed = {city: 0};
    $http.defaults.headers.post["Content-Type"] = "application/x-www-form-urlencoded";

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

    $scope.removeTask = function (task) {
        $scope.profile.tasks.splice($scope.profile.tasks.indexOf(task), 1);
    };

    $scope.saveCity = function(task) {
        $http.post('/app_dev.php/contractor/update-city', $.param({form: {city: task.city}}));
    };

    $scope.saveTagLine = function(task) {
        $http.post('/app_dev.php/contractor/update-tag-line', $.param({form: {tagLine: task.tag_line}}));
    };

    $scope.saveFullname = function(task) {
        $http.post('/app_dev.php/contractor/update-fullname', $.param({form: {fullName: task.full_name}}));
    };
}

mcApp.directive('placeholder', function() {
    return {
        restrict: 'A',
        require: 'ngModel',
        link: function(scope, element, attr, ctrl) {

            var value;

            var placehold = function () {
                element.val(attr.placeholder)
            };
            var unplacehold = function () {
                element.val('');
            };

            scope.$watch(attr.ngModel, function (val) {
                value = val || '';
            });

            element.bind('focus', function () {
                if(value == '') unplacehold();
            });

            element.bind('blur', function () {
                if (element.val() == '') placehold();
            });

            ctrl.$formatters.unshift(function (val) {
                if (!val) {
                    placehold();
                    value = '';
                    return attr.placeholder;
                }
                return val;
            });
        }
    };
});