function ProposalCtrl($scope, $http, AcceptProposal) {
    $http.defaults.headers.post["Content-Type"] = "application/x-www-form-urlencoded";
    
    $scope.newProposal = {};
//	$scope.durationOptions = [
//	    {"value": "1",
//		"name": "1-2 days"},
//		{"value": "2",
//		"name": "3-4 days"}
//	];

	$scope.ngObjFixHack = function(ngObj) {
	    var output;

	    output = angular.toJson(ngObj);
	    output = angular.fromJson(output);

	    return output;
	}
	
	$scope.newProposal.milestones = [{ name: ''}];
	
	$scope.addMilestone = function() {
		$scope.newProposal.milestones.push( { name: '' } ); //-- i can use either $scope or this to reference the array and works.
	}
	
    $scope.removeMilestone = function(elem) {
    	console.log('tu');
    	$scope.newProposal.milestones.splice($scope.newProposal.milestones.indexOf(elem), 1);
    };
	
    $scope.addProposal = function () {
        if (typeof $scope.proposals === "undefined") {
            $scope.proposals = [];
        }
        
        $scope.newProposal.finishDate = jQuery('#finishDate').val();
        
        //var prop = new Proposal($scope.newProposal);
        //prop.$save();
        
        var postData = $scope.ngObjFixHack($scope.newProposal);
        
        console.log(postData);
        
    	$http.post(Mclowd.url('/proposals/'), $.param({ form: postData })).success(function(data, status) {
            $scope.status = status;
            $scope.data = data;
            //console.log(data.id);
            if (typeof data.error != 'undefined') {
            	$scope.errors = data.error;
            }
            else {
	            $scope.newProposal.id = data.id;
	            
	            //var _duration = data.duration
	            //$scope.newProposal.duration = data.duration_options[_duration];
	            $scope.newProposal.username = data.user.username;
	            
	            console.log($scope.newProposal);
	            
	            $scope.proposals.push($scope.newProposal);
	            
	            var taskId = $scope.newProposal.task;
	            var taskType = $scope.newProposal.taskType;
	            $scope.newProposal = {};
	            $scope.newProposal.task = taskId;
	            $scope.newProposal.taskType = taskType;
	            $scope.newProposal.milestones = [{ name: ''}];
	            jQuery('.milestones-inputs-list li:first-child a.remove').remove();
            }
        }).
        error(function(data, status) {
            $scope.data = data || "Request failed";
            $scope.status = status;         
        });
    };
    
    $scope.acceptProposal = function(taskId, proposalId, $event) {   
        var ap = new AcceptProposal(); 
        ap.task_id = taskId;
        ap.proposal_id = proposalId;
        
        var clickedEl = jQuery($event.target);
        clickedEl.text('accepted');
        
        jQuery('a.btn-success').each(function(){
        	if (jQuery(this).text() == 'accept') {
        		jQuery(this).fadeOut();
        	}
        })
        
        ap.$save(function(data) {
        	
        });
        return false;
    };
    
}


jQuery('input, select, textarea').focus(function() {
	$('div.' + $(this).attr('id')).fadeOut('slow');
})

jQuery(document).ready(function() {
	
	var flag = false;
	
    jQuery('#datetimepicker1').datetimepicker({
    });
	
	jQuery('.proposals-list a.btn-success').each(function(){
		if (jQuery(this).attr('data') == '1') {
			flag = true;
			jQuery(this).text('accepted');
		}
		else {
			jQuery(this).addClass('hide');
		}
	});
	
	setTimeout(function() {
		if (flag) {
			jQuery('.proposals-list a.hide').hide();
		}
	}, 500)
	
	jQuery('.milestones-inputs-list li:first-child a.remove').remove();
})


