var mcApp = angular.module('Marketplace', ['ngResource']);

mcApp.factory('Proposal', function($resource) {
    return $resource(Mclowd.url('/proposals/'), {}, {

    });
});

function ProposalCtrl($scope, $http, Proposal) {
    $http.defaults.headers.post["Content-Type"] = "application/x-www-form-urlencoded";
	
	$scope.durationOptions = [
	    {"value": "1",
		"name": "1-2 days"},
		{"value": "2",
		"name": "3-4 days"}
	];
	
    $scope.addProposal = function () {
        if (typeof $scope.proposals === "undefined") {
            $scope.proposals = [];
        }
        
//        var e = new Proposal($scope.newProposal);
    	$http.post(Mclowd.url('/proposals/'), $.param({form: $scope.newProposal})).success(function(data, status) {
            $scope.status = status;
            $scope.data = data;
            $scope.newProposal.id = data.id
            $scope.proposals.push($scope.newProposal);
            
            var taskId = $scope.newProposal.task;
            $scope.newProposal = {};
            $scope.newProposal.task = taskId;
            
        }).
        error(function(data, status) {
            $scope.data = data || "Request failed";
            $scope.status = status;         
        });;

    	

    	
    };
    
    
}

