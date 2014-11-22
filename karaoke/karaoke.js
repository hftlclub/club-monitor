(function(){
	'use strict';
	
	
	angular.module('karaokeApp', []);
	
	
	angular
		.module('karaokeApp')
		.controller('QueueController', queueController);
	
	
	queueController.$inject = ['$scope', '$http', '$q', '$timeout'];
	
	function queueController($scope, $http, $q, $timeout){
		
		$scope.refreshQueue = refreshQueue;
		$scope.intervalFunction = intervalFunction;
	
		
		function refreshQueue(){
			var deferred = $q.defer();

			$http.get('json.php?mode=getQueue').success(function(data) {
				$scope.data = data;
				deferred.resolve(data);
			});
	
			return deferred.promise;
		}		
		
		
		//periodical queue refresh
		function intervalFunction(){
	    	$scope.refreshQueue();
	    	
	    	$timeout(function() {
				$scope.refreshQueue();
				$scope.intervalFunction();
	    	}, 5000)
		};

		//start periodical refresh
		$scope.intervalFunction();
	
	}
	
})();