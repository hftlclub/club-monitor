function isTouchSupported() {
	var msTouchEnabled = window.navigator.msMaxTouchPoints;
	var generalTouchEnabled = "ontouchstart" in document.createElement("div");

	if (msTouchEnabled || generalTouchEnabled) {
		return true;
	}

	return false;

}


/* Ja, ich benutze jetzt was ganz anderes. Heul doch. */

angular.module('steckerApp', ['ui.sortable'])


.controller('InfoscreenMngrCtrl', function ($scope) {
})


.controller('InfoscreenMngrCtrl', function ($scope, $http, $q) {
	$scope.active = { id: 0 };
	$scope.hasTouch = isTouchSupported();

	$scope.sortableOptions = {
		stop: function (e, ui) {
			$scope.submitOrder();
		},
		handle: '.module-order-handle',
		axis: 'y',
	};

	$scope.remove = function (index) {
		$scope.timeline.splice(index, 1);

	};

	$scope.moveDown = function (index) {
		$scope.swap(index, index + 1);
	};

	$scope.moveUp = function (index) {
		$scope.swap(index, index - 1);
	};

	$scope.swap = function (a, b) {
		var l = $scope.timeline;
		l[a] = l.splice(b, 1, l[a])[0];
		$scope.submitOrder();
	};

	$scope.add = function (type) {
		$http.post('json.php?mode=addItem', {
			type: type,
			order: $scope.timeline.length
		}).success(function (data) {
			$scope.refresh().then(function (data) {
			});
		});
	};

	$scope.submitOrder = function () {
		var dout = [];

		$scope.timeline.forEach(function (obj, i) {
			dout.push({ order: i, id: obj.id });
		});

		$http.post('json.php?mode=reorderTimeline', dout).success($scope.refresh);

	};

	$scope.disable = function (id) {
		$http.post('json.php?mode=disableItem', [{ id: id }]).success($scope.refresh);
	};

	$scope.activate = function (id) {
		$http.post('json.php?mode=activateItem', [{ id: id }]).success($scope.refresh);
	};

	$scope.remove = function (id) {
		$http.post('json.php?mode=deleteItem', [{ id: id }]).success($scope.refresh);
	};

	$scope.refresh = function () {
		var deferred = $q.defer();

		$http.get('json.php?mode=retrieve').success(function (data) {
			$scope.timeline = data;
			deferred.resolve(data);
		});

		return deferred.promise;

	};

	$scope.refresh();


});
