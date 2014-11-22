function isTouchSupported() {
	var msTouchEnabled = window.navigator.msMaxTouchPoints;
	var generalTouchEnabled = "ontouchstart" in document.createElement("div");

	if (msTouchEnabled || generalTouchEnabled) {
		return true;
	}

	return false;

}

function getToken() {
	return localStorage.getItem('accessToken');
}

function logout(data, status, headers, config) {
	if(status == 401)
	{
		localStorage.removeItem('accessToken');
		window.location.href = '../?ref=' + encodeURIComponent(window.location.href);
	}
}



(function(){
	'use strict';
	
	
	angular.module('karaokeManager', []);
	
	
	angular
		.module('karaokeManager')
		.controller('QueueController', queueController);
	
	
	queueController.$inject = ['$scope','$http','$q','$timeout'];
	
	function queueController($scope, $http, $q, $timeout){
		
		$scope.refreshQueue = refreshQueue;
		$scope.intervalFunction = intervalFunction;
		$scope.setPlayed = setPlayed;
		
		$scope.timers = [];
		
		
		function refreshQueue(){
			var deferred = $q.defer();

			$http.get('json.queue.php?mode=getActive&token=' + getToken()).success(function (data) {
				$scope.queue = data.queue;
				$scope.count = data.count;
				deferred.resolve(data);
			}).error(logout);
	
			return deferred.promise;
		}		
		
		
		
		function setPlayed(id) {
			//init timer
			$scope.timers[id] = {
				timer: 5,
				stopped: null,
				countdown: function(){
					this.stopped = $timeout(function() {
						
						//count to 0
						if($scope.timers[id].timer > 1){
							$scope.timers[id].timer--;
							$scope.timers[id].countdown();
						}else{
							//if counted to 0, make HTTP request
							$http.post('json.queue.php?mode=setPlayed&token=' + getToken(), [{ id: id }]).success($scope.refreshQueue).error(logout);
						}   
						   
					}, 1000)
				},
				stop: function(){
					$timeout.cancel(this.stopped);
					$scope.timers[id] = {};
    			}

    		};
    		
    		$scope.timers[id].countdown();
		}
		
		
		
		//Function to replicate setInterval using $timeout service.
		function intervalFunction(){
	    	$scope.refreshQueue();
	    	
	    	$timeout(function() {
				$scope.refreshQueue();
				$scope.intervalFunction();
	    	}, 30000)
		};

		//Kick off the interval
		$scope.intervalFunction();
	
	}
	
})();