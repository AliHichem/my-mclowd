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
        if (typeof $scope.proposals.proposal_history === "undefined") {
            $scope.proposals.proposal_history = [];
        }
        $scope.proposals.proposal_history.push($scope.newProposal);
//        var e = new Proposal($scope.newProposal);
    	$http.post(Mclowd.url('/proposals/'), $.param({form: $scope.newProposal}));
    	$scope.newProposal = {};
    	

    	
    };
    
    
}

