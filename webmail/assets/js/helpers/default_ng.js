var defApp = angular.module('defApp',  ['rtModule']);

defApp.controller('defCtrl', function($scope, $http, RTModule) {
	RTModule.initRTModule($scope);
});