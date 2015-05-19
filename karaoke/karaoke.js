(function(){
	'use strict';


	angular.module('clubScreen', []);


	angular
		.module('clubScreen')
		.controller('QueueController', queueController);


	queueController.$inject = ['$scope', '$http', '$q', '$location', '$timeout', '$window'];

	function queueController($scope, $http, $q, $location, $timeout, $window){

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
