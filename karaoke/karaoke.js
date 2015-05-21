(function(){
	'use strict';
	
	
	angular.module('clubScreen', []);
	
	
	angular
		.module('clubScreen')
		.controller('QueueController', queueController);
	
	
	queueController.$inject = ['$scope', '$http', '$q', '$timeout'];
	
	function queueController($scope, $http, $q, $timeout){
		
		$scope.refreshQueue = refreshQueue;
		$scope.intervalFunction = intervalFunction;
        $scope.getSongInputStatus = getSongInputStatus;
	        
        function getSongInputStatus() {
            $http.post('json.php?mode=getSongInputStatus').success(function(data){
                if(data.songInputActive) {
                    $scope.songInputDisabled = false;
                }else{
                    $scope.songInputDisabled = true;
                }
            });
        }
	
		function refreshQueue(){
			var deferred = $q.defer();

			$http.get('json.php?mode=getQueue').success(function(data) {
				$scope.data = data;
				deferred.resolve(data);
			});
	
			return deferred.promise;
		}		
		
		
		//periodical page refresh
		function intervalFunction(){
	    	$scope.refreshQueue();
            $scope.getSongInputStatus();
	    	
	    	$timeout(function() {
				$scope.refreshQueue();
                $scope.getSongInputStatus();
				$scope.intervalFunction();
	    	}, 5000)
		};

		//start periodical refresh
		$scope.intervalFunction();
	
	}
	
})();