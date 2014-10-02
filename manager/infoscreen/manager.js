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
		window.location.href = '../'
	}
}

/* Ja, ich benutze jetzt was ganz anderes. Heul doch. */

angular.module('steckerApp', ['ui.sortable', 'ngRoute', 'angularFileUpload'])

.config(function ($routeProvider, $locationProvider) {
	$routeProvider
		.when('/', {
			templateUrl: 'timeline-tpl.html',
			controller: 'TimelineController',
		})
		.when('/timeline/', {
			templateUrl: 'timeline-tpl.html',
			controller: 'TimelineController',
		})
		.when('/timeline/edit/:id', {
			templateUrl: 'timeline-edit-tpl.html',
			controller: 'TimelineEditController',
		})
		.when('/messages/', {
			templateUrl: 'messages-tpl.html',
			controller: 'MessagesController',
		})
})

.controller('MainController', function ($scope, $route, $routeParams, $location) {
	$scope.$route = $route;
	$scope.$location = $location;
	$scope.$routeParams = $routeParams;
})


.controller('MessagesController', function ($scope, $http, $q, $location) {
	// load data
	$scope.refreshMessages = function () {
		var deferred = $q.defer();

		$http.get('json.messages.php?mode=retrieve&token=' + getToken()).success(function (data) {
			$scope.messages = data.messages;
			$scope.meta = {msgcount:data.msgcount, pagecount:data.pagecount};
			deferred.resolve(data);
		}).error(logout);

		return deferred.promise;

	};

	$scope.refreshMessages();
})


.controller('TimelineEditController', function ($scope, $http, $routeParams, $location, $upload) {
	// load data
	$scope.active = { id: $routeParams.id };
	$http.get('json.timeline.php?mode=retrieve&token=' + getToken()).success(function (data) {
		data.forEach(function (module) {
			if (module.id == $scope.active.id)
				$scope.module = module;
		});
	}).error(logout);

	// --- submit ---
	$scope.submit = function () {
		$http.post('json.timeline.php?mode=editItem&token=' + getToken(), $scope.module).success(function (data) {
			$location.path('/');
		}).error(logout);
	};

	// --- upload ---
	$scope.upload = function ($files) {
		$upload.upload({
			url: 'json.timeline.php?mode=fileUpload&token=' + getToken(),
			file: $files[0],
		}).success(function (data) {
			$scope.module.settings.url = data.url;
			$http.post('json.timeline.php?mode=editItem&token=' + getToken(), $scope.module);
		}).error(logout);
	};

})


.controller('TimelineController', function ($scope, $http, $q, $location) {
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
		$http.post('json.timeline.php?mode=addItem&token=' + getToken(), {
			type: type,
			order: $scope.timeline.length
		}).success(function (data) {
			$location.path('/edit/' + data.id);
		}).error(logout);
	};

	$scope.submitOrder = function () {
		var dout = [];

		$scope.timeline.forEach(function (obj, i) {
			dout.push({ order: i, id: obj.id });
		});

		$http.post('json.timeline.php?mode=reorderTimeline&token=' + getToken(), dout).success($scope.refresh).error(logout);

	};

	$scope.disable = function (id) {
		$http.post('json.timeline.php?mode=disableItem&token=' + getToken(), [{ id: id }]).success($scope.refresh).error(logout);
	};

	$scope.activate = function (id) {
		$http.post('json.timeline.php?mode=activateItem&token=' + getToken(), [{ id: id }]).success($scope.refresh).error(logout);
	};

	$scope.remove = function (id) {
        var txt;
        var r = confirm("Wirklich löschen?");
        if (r == true) {
            $http.post('json.timeline.php?mode=deleteItem&token=' + getToken(), [{ id: id }]).success($scope.refresh).error(logout);
        } else {
            alert("Aktion abgebrochen!");
        }

	};

	$scope.refresh = function () {
		var deferred = $q.defer();

		$http.get('json.timeline.php?mode=retrieve&token=' + getToken()).success(function (data) {
			$scope.timeline = data;
			deferred.resolve(data);
		}).error(logout);

		return deferred.promise;

	};

	$scope.refresh();


});
