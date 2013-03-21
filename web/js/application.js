var mcApp = angular.module('Marketplace', []);

function ContractorEditCtrl($scope) {
    $scope.newTask = {name: '', amount: ''};

    $scope.addTask = function () {
        if (!$scope.newTask.name.length) {
            return;
        }
        if (typeof $scope.profile.tasks === "undefined") {
            $scope.profile.tasks = []
        }        
        $scope.profile.tasks.push($scope.newTask);

        $scope.newTask = {name: '', amount: ''};
    };

    $scope.removeTask = function (task) {
        $scope.profile.tasks.splice($scope.profile.tasks.indexOf(task), 1);
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