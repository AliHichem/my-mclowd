function ProposalCtrl($scope, $http, AcceptProposal) {
    $http.defaults.headers.post["Content-Type"] = "application/x-www-form-urlencoded";
    
	$scope.durationOptions = [
	    {"value": "1",
		"name": "1-2 days"},
		{"value": "2",
		"name": "3-4 days"}
	];

	$scope.ngObjFixHack = function(ngObj) {
	    var output;

	    output = angular.toJson(ngObj);
	    output = angular.fromJson(output);

	    return output;
	}
	
    $scope.addProposal = function () {
        if (typeof $scope.proposals === "undefined") {
            $scope.proposals = [];
        }
        
        //var prop = new Proposal($scope.newProposal);
        //prop.$save();
        
        var postData = $scope.ngObjFixHack($scope.newProposal);
        
    	$http.post(Mclowd.url('/proposals/'), $.param({ form: postData })).success(function(data, status) {
            $scope.status = status;
            $scope.data = data;
            //console.log(data.id);
            if (typeof data.error != 'undefined') {
            	$scope.errors = data.error;
            }
            else {
	            $scope.newProposal.id = data.id;
	            
	            var _duration = data.duration
	            $scope.newProposal.duration = data.duration_options[_duration];
	            $scope.proposals.push($scope.newProposal);
	            
	            var taskId = $scope.newProposal.task;
	            $scope.newProposal = {};
	            $scope.newProposal.task = taskId;
            }
        }).
        error(function(data, status) {
            $scope.data = data || "Request failed";
            $scope.status = status;         
        });
    };
    
    $scope.acceptProposal = function(taskId, proposalId) {   
        var ap = new AcceptProposal(); 
        ap.task_id = taskId;
        ap.proposal_id = proposalId;
        
        console.log(ap);
        ap.$save(function(data) {
        	
        });
        return false;
    };
    
}


jQuery('input, select, textarea').focus(function() {
	$('div.' + $(this).attr('id')).fadeOut('slow');
})