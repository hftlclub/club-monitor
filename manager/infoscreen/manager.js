function isTouchSupported() {
	var msTouchEnabled = window.navigator.msMaxTouchPoints;
	var generalTouchEnabled = "ontouchstart" in document.createElement("div");

	if (msTouchEnabled || generalTouchEnabled) {
		return true;
	}

	return false;

}


/* Ja, ich benutze jetzt was ganz anderes. Heul doch. */

angular.module('steckerApp', ['ui.sortable', 'ngRoute', 'angularFileUpload'])

.config(function($routeProvider, $locationProvider) {
	$routeProvider
		.when('/', {
			templateUrl: 'list-tpl.html',
			controller: 'ListController',
		})
		.when('/edit/:id', {
			templateUrl: 'form-tpl.html',
			controller: 'FormController',
		})
})

.controller('MainController', function($scope, $route, $routeParams, $location) {
	$scope.$route = $route;
	$scope.$location = $location;
	$scope.$routeParams = $routeParams;
})


.controller('FormController', function ($scope, $http, $routeParams, $location, $upload) {
	// load data
	$scope.active = {id: $routeParams.id};
	$http.get('json.php?mode=retrieve').success(function (data) {
		data.forEach(function(module){
			if(module.id == $scope.active.id)
				$scope.module = module;
		});
	});

	// --- submit ---
	$scope.submit = function() {
		$http.post('json.php?mode=editItem', $scope.module).success(function(data){
			$location.path('/');
		});
	};

	// --- upload ---
	$scope.upload = function($files) {
		$upload.upload({
			url: 'json.php?mode=fileUpload',
			file: $files[0],
			data: {
				id: $scope.active,
			},
		}).success(function(data){
		$scope.module.settings.url = data.url;
		});
	};

})


.controller('ListController', function ($scope, $http, $q, $location) {
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
			$location.path('/edit/'+data.id);
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
