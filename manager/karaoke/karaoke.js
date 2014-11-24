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
	
	
	angular.module('clubScreen', ['ui.bootstrap', 'ui.bootstrap.modal']);
	
	
	angular
		.module('clubScreen')
		.controller('QueueController', queueController);
	
	
	queueController.$inject = ['$scope', '$http', '$q', '$timeout', '$modal'];
	
	function queueController($scope, $http, $q, $timeout, $modal){
		
		$scope.refreshQueue = refreshQueue;
		$scope.intervalFunction = intervalFunction;
		$scope.setPlayed = setPlayed;
		$scope.truncateQueue = truncateQueue;
		
		$scope.timers = []; //timer objects for setPlayed countdown
		
		
		
		function refreshQueue(){
			var deferred = $q.defer();

			$http.get('json.queue.php?mode=getQueue&token=' + getToken()).success(function (data) {
				$scope.data = data;
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
					$timeout.cancel(this.stopped); //stop timer
					$scope.timers[id] = {}; //delete this timer object
    			}

    		};
    		
    		//start timer
    		$scope.timers[id].countdown();
		}
		
		
		
		function truncateQueue() {
	        var modal = $modal.open({
				templateUrl: 'tpl-modal-truncatequeue.html'
			});

			modal.result.then(function(){
				$http
					.post('json.queue.php?mode=truncateQueue&token=' + getToken())
					.success($scope.refreshQueue)
					.error(logout);
			});
	        
		}
		
		
		
		//periodical queue refresh
		function intervalFunction(){
	    	$scope.refreshQueue();
	    	
	    	$timeout(function() {
				$scope.refreshQueue();
				$scope.intervalFunction();
	    	}, 30000)
		};

		//start periodical refresh
		$scope.intervalFunction();
	
	}
	
})();