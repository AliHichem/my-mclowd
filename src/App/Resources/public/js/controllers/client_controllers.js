function ClientEditCtrl($scope, $http, ClientSetting) {
    $scope.data = {};
    
    $scope.newSetting = {};

    $scope.saveFullname = function(profile) {
        $http.post(Mclowd.path('client_update_fullname'), {form: {fullName: profile.fullName}});
        hideFields('fullname');
    };
    
    $scope.savePassword = function(profile) {
        $http.post(Mclowd.path('client_update_password'), {form: { password: profile.password }});
        hideFields('password');
    };
    
    $scope.saveEmail = function(profile) {
        $http.post(Mclowd.path('client_update_email'), {form: { email: profile.email }});
        hideFields('email');
    };
    
    $scope.savePhone = function(profile) {
        $http.post(Mclowd.path('client_update_phone'), {form: { phone: profile.phone }});
        hideFields('phone');
    };
    
    $scope.addSettings = function () {
        var cs = new ClientSetting($scope.newSetting);
        cs.$save(function(data) {

        });
    };
}
































