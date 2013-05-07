function ContractorEditCtrl($scope, $http, Qualification, Employment, Education, ContractorTask, ContractorSetting) {
    $scope.data = {};
    
    $scope.newSetting = {};

    $scope.addTask = function () {
        var t = new ContractorTask($scope.newTask);
        t.$save(function(data) {
            $scope.data.contractorTasks.push(t);  
            $scope.newTask = {};
        });
    };


    $scope.addQualification = function () {
        var q = new Qualification($scope.newQualification);
        q.$save(function(data) {
            $scope.data.qualifications.push(q);  
            $scope.newQualification = {};
        });
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
        task.$remove();
        $scope.data.contractorTasks.splice($scope.profile.tasks.indexOf(task), 1);
    };

    $scope.removeQualification = function (qualification) {
        qualification.$remove();
        $scope.data.qualifications.splice($scope.data.qualifications.indexOf(qualification), 1);
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
        hideFields('fullname');
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

    $scope.initTasks = function() {
        $scope.data.contractorTasks = [];
        for (i in $scope.profile.contractorTasks) {
            $scope.data.contractorTasks.push(new ContractorTask($scope.profile.contractorTasks[i]));
        }
    };

    $scope.initQualifications = function() {
        $scope.data.qualifications = [];
        for (i in $scope.profile.qualifications) {
            $scope.data.qualifications.push(new Qualification($scope.profile.qualifications[i]));
        }
    };
    
    $scope.savePassword = function(profile) {
        $http.post(Mclowd.path('contractor_update_password'), {form: { password: profile.password }});
        hideFields('password');
    };
    
    $scope.saveEmail = function(profile) {
        $http.post(Mclowd.path('contractor_update_email'), {form: { email: profile.email }});
        hideFields('email');
    };
    
    $scope.savePhone = function(profile) {
        $http.post(Mclowd.path('contractor_update_phone'), {form: { phone: profile.phone }});
        hideFields('phone');
    };
    
    $scope.addSettings = function () {
        var cs = new ContractorSetting($scope.newSetting);
        cs.$save(function(data) {

        });
    };
}

function showFields(elementName) {
	
	jQuery('#edit-profile-' + elementName).hide();
	jQuery('#profile-' + elementName + '-span').hide();
	jQuery('#profile-' + elementName + '-container').show();

}

function hideFields(elementName) {
	
	jQuery('#profile-' + elementName + '-container').hide();
	jQuery('#profile-' + elementName + '-span').show();
	jQuery('#edit-profile-' + elementName).show();
		
}































