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
	
	
	angular.module('clubScreen', ['ui.bootstrap', 'ui.bootstrap.modal', 'ui.sortable']);
	
	
	angular
		.module('clubScreen')
		.controller('QueueController', queueController);
	
	
	queueController.$inject = ['$scope', '$http', '$q', '$timeout', '$modal'];
	
	function queueController($scope, $http, $q, $timeout, $modal){
		
		$scope.hasTouch = isTouchSupported();
		
		$scope.refreshQueue = refreshQueue;
		$scope.intervalFunction = intervalFunction;
		$scope.setPlayed = setPlayed;
		$scope.truncateQueue = truncateQueue;
		
		$scope.moveDownItem = moveDownItem;
		$scope.moveUpItem = moveUpItem;
		$scope.swapItem = swapItem;
		$scope.submitItemOrder = submitItemOrder;
		
		$scope.timers = []; //timer objects for setPlayed countdown
		
		
		
		function refreshQueue(){
			var deferred = $q.defer();

			$http.get('json.queue.php?mode=getQueue&token=' + getToken()).success(function (data) {
				$scope.queue = data.queue;
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
		
		
		
		
		
		$scope.sortableOptions = {
			stop: function (e, ui) {
				$scope.submitItemOrder();
			},
			handle: '.song-order-handle',
			axis: 'y',
		};

		function moveDownItem(index) {
			$scope.swapItem(index, index + 1);
		};
	
		function moveUpItem(index) {
			$scope.swapItem(index, index - 1);
		};
	
		function swapItem(a, b) {
			var l = $scope.queue;
			l[a] = l.splice(b, 1, l[a])[0];
			$scope.submitItemOrder();
		};
	
		function submitItemOrder() {
			var dout = [];
	
			$scope.queue.forEach(function (obj, i) {
				dout.push({ order: i, id: obj.id });
			});
			
			console.log(JSON.stringify(dout));
	
			$http.post('json.queue.php?mode=reorderQueue&token=' + getToken(), dout).success($scope.refreshQueue).error(logout);
	
		};
		
		
		
		
		
		
		
	
	}
	
})();