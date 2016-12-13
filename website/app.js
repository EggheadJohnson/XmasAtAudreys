var xmasAtAudreys = angular.module('xmasAtAudreys', [

	'ngRoute',
	'ui.router',
	'ngResource',
	'xmasAtAudreys'

]),
	loggedIn = false,
	token;

xmasAtAudreys.controller('xaaCtl', ['$scope','$state', function($scope, $state) {
	console.log($state);
	if (!loggedIn && $state.current.url !== '/login') $state.go('login');
}]);

xmasAtAudreys.controller('xaaLoginCtl', ['$scope','$state', '$rootScope', 'xmasAtAudreysSvc', function($scope, $state, $rootScope, xmasAtAudreysSvc) {
	console.log('xaaLoginCtl');

	$scope.loginButton = function(user){
		console.log(user);
		xmasAtAudreysSvc.login(user)
			.then(function(resp){
				console.log(resp);
				if (resp.token) {
					loggedIn = true;
					$state.go('core.home');
				}
			});
	}
	$scope.signupButton = function(user){
		console.log(user);
		$rootScope.user = user;
		$state.go('signup');
	}
	// setTimeout(function(){
	// 	console.log("attempting login");
	// 	xmasAtAudreysSvc.login({username: "SantosLHalper", password: "password"})
	// 		.then(function(resp){
	// 			loggedIn = true;
	// 			console.log(token, resp.token);
	// 			$state.go('core.home');
	// 		});
	// }, 3000);

}]);

xmasAtAudreys.controller('xaaHomeAboutMeCtl', ['$scope','$state', function($scope, $state) {
	console.log('xaaHomeAboutMeCtl');
}]);

xmasAtAudreys.controller('xaaSignUpCtl', ['$scope','$state', '$stateParams', '$rootScope', 'xmasAtAudreysSvc', function($scope, $state, $stateParams, $rootScope, xmasAtAudreysSvc) {
	console.log('xaaSignUpCtl');
	console.log($rootScope.user);
	$scope.user = $rootScope.user;
	$scope.submitNewUser = function(user){
		console.log(user);
		xmasAtAudreysSvc.signup(user)
			.then(function(response){
				if (response.token) {
					loggedIn = true;
					$state.go('core.home');
					console.log(token);}
			});
	}
	$scope.resetUser = function(){
		$scope.user = {};
	}
}]);

xmasAtAudreys.controller('xaaSantasCtl', ['$scope','$state','xmasAtAudreysSvc', function($scope, $state, xmasAtAudreysSvc) {
	console.log('xaaSantasCtl');
	var users;


	console.log("fetching users");
	xmasAtAudreysSvc.getUsers(token).then(function(response){
		users = xmasAtAudreysSvc.fetchUsers();
		console.log(users);
	});
}]);

xmasAtAudreys.controller('xaaYourGiftGetterCtl', ['$scope','$state', function($scope, $state) {
	console.log('xaaYourGiftGetterCtl');
}]);

// xmasAtAudreys.controller('xaaRightPanelCtl', ['$scope', '$stateParams', 'xmasAtAudreysSvc', function($scope, $stateParams, xmasAtAudreysSvc) {
//
// 	$scope.blogView = $stateParams.id !== undefined;
// 	xmasAtAudreysSvc.getTags($stateParams.id).then(function(response){
// 		$scope.tagsHash = xmasAtAudreysSvc.fetchTags();
// 		$scope.tagsKeys = Object.keys($scope.tagsHash).sort(function(a, b){
// 			return $scope.tagsHash[b]-$scope.tagsHash[a];
// 		});
//
// 	});
// 	$scope.addMoreTags = function(){
// 		$scope.tagsLimit += 5;
// 	}
// }]);
xmasAtAudreys.controller('xaaMiddleOfPageCtl', ['$scope', '$state', '$stateParams', 'xmasAtAudreysSvc', function($scope, $state, $stateParams, xmasAtAudreysSvc) {


	// var blogs,
	// 	filterBlogs;
	// $scope.tagsLimit = 5;
	// $scope.tagSelected = "";
	// xmasAtAudreysSvc.getBlogs().then(function(response){
	// 	blogs = xmasAtAudreysSvc.fetchBlogs();
	// 	$scope.blogsLength = blogs.length;
	// 	$scope.blogs = filterBlogs();
	// });
	// $scope.changeTagSeclect = function(tag) {
	// 	$scope.tagSelected = tag;
	// 	$scope.blogs = filterBlogs();
	// }
	// filterBlogs = function() {
	// 	if ($scope.tagSelected === "") return blogs;
	// 	return blogs.filter(function(blog){
	// 		return blog.tags.indexOf($scope.tagSelected) > -1;
	// 	})
	// }
}]);

xmasAtAudreys.config(['$routeProvider', '$stateProvider', '$urlRouterProvider', function($routeProvider, $stateProvider, $urlRouterProvider){

	$urlRouterProvider.otherwise("/login");
	$stateProvider
		.state('core', {
			views: {
				'@': {
					templateUrl: 'views/coreUI.html',
					controller: 'xaaCtl'
				},
				'middleOfPageStuff@core': {
					templateUrl: 'views/middleOfPageStuff.html',
					controller: 'xaaMiddleOfPageCtl'
				},
				'bottomOfPageStuff@core': {
					templateUrl: 'views/footer.html'
				},
				// 'leftPanel@core': {
				// 	templateUrl: 'views/leftPanel.html'
				// },
				// 'rightPanel@core': {
				// 	templateUrl: 'views/rightPanel.html',
				// 	controller: 'xaaRightPanelCtl'
				// }

			}
		})
		.state('login', {
			url: '/login',
			views: {
				'topOfPageStuff': {

				},
				'@': {
					templateUrl: 'views/loginContent.html',
					controller: 'xaaLoginCtl'
				}
			}
		})
		.state('signup', {
			url: '/signup',
			views: {
				'topOfPageStuff': {

				},
				'@': {
					templateUrl: 'views/signup.html',
					controller: 'xaaSignUpCtl'
				}
			}
		})
		.state('core.home', {
			url: '/home',
			views: {
				'topOfPageStuff@core': {
					templateUrl: 'views/navBarAndBanner.html'
				},
				'centerContent@core': {
					templateUrl: 'views/homeContent.html'
				},
				// 'rightPanel@core': {
				// 	templateUrl: 'views/rightPanel.html',
				// 	controller: 'xaaRightPanelCtl'
				// }

			}
		})
		.state('core.aboutMe', {
			url: '/aboutMe',
			views: {
				'topOfPageStuff@core': {
					templateUrl: 'views/navBar.html'
				},
				'centerContent@core': {
					templateUrl: 'views/aboutMe.html',
					controller: 'xaaHomeAboutMeCtl'
				},
				// 'rightPanel@core': {
				// 	templateUrl: 'views/rightPanel.html',
				// 	controller: 'xaaRightPanelCtl'
				// }
			}
		})
		.state('core.santas', {
			url: '/santas',
			views: {
				'topOfPageStuff@core': {
					templateUrl: 'views/navBar.html'
				},
				'centerContent@core': {
					templateUrl: 'views/santas.html',
					controller: 'xaaSantasCtl'
				}

			}
		})
		.state('core.yourGiftGetter', {
			url: '/yourGiftGetter',
			views: {
				'topOfPageStuff@core': {
					templateUrl: 'views/navBar.html'
				},
				'centerContent@core': {
					templateUrl: 'views/yourGiftGetter.html',
					controller: 'xaaYourGiftGetterCtl'
				}
			}
		})
		// .state('experiment', {
		// 	url: '/experiment',
		// 	templateUrl: 'views/experiment.html',
		// 	controller: 'xaaExperimentCtl'
		// })

}]);

xmasAtAudreys.factory('xmasAtAudreysSvc', ['$resource', '$http', function($resource, $httpProvider){

	var apiUrl = 'http://localhost:3000',
		users,
		yourGiftGetter,
		userSource,
		loginSource,
		signupSource,
		// async` functions
		getMe,
		getUsers,
		getOneUser,
		login,
		signup,
		// sync functions
		fetchUsers,
		fetchOneUser;

	getUsers = function(token){
		console.log(token);
		userSource = apiUrl + '/users';
		userSource = $resource(userSource, {}, {
			get: {
				headers: {token: token},
				method: 'get'
			}
		});
		return userSource.get(function(response){
			users = response.users;
		}).$promise;
	}

	getOneUser = function(id){
		userSource = apiUrl + '/users/' + id;
		userSource = $resource(userSource);
		return userSource.get(function(response){
			yourGiftGetter = reponse.user;
		}).$promise;
	}

	login = function(loginObj) {
		loginSource = apiUrl + '/login';
		loginSource = $resource(loginSource);
		return loginSource.save(loginObj, function(response){
			token = response.token;
		}).$promise;
	}

	signup = function(user) {
		signupSource = apiUrl + '/users';
		signupSource = $resource(signupSource);
		return signupSource.save(user, function(response){
			token = response.token;
		}).$promise;
	}

	fetchUsers = function(){
		return users;
	}

	fetchOneUser = function(){
		return yourGiftGetter;
	}



	// var blogs,
	// 	blogSource,
	// 	tagSource,
	// 	tags,
	// 	//Async functions
	// 	getBlogs,
	// 	getTags,
	// 	getTags2,
	// 	validateRedis,
	// 	//Sync functions
	// 	fetchBlogs,
	// 	fetchTags,
	// 	//Private functions
	// 	parseTags;
	//
	//
	// getBlogs = function(){
	// 	blogSource = $resource('http://162.243.145.82:3000/node/getBlogInfo');
	// 	return blogSource.get(function(response){
	// 		blogs = response.data;
	//
	//
	// 	}).$promise;
	// };
	// getTags = function(filename) {
	// 	filename = filename || '';
	// 	tagSource = $resource('http://162.243.145.82:3000/node/getTags/'+filename);
	// 	return tagSource.get(function(response){
	// 		tags = parseTags(response.data);
	//
	//
	// 	}).$promise;
	// }
	// getTags2 = function(filename) {
	// 	filename = filename || '';
	// 	tagSource = $resource('http://162.243.145.82:3000/node/getTags2/'+filename);
	// 	return tagSource.get(function(response){
	// 		tags = response.data;
	//
	//
	// 	}).$promise;
	// }
	// validateRedis = function(redisKey) {
	// 	$httpProvider.defaults.headers.get = {};
	// 	$httpProvider.defaults.headers.get.redislockkey = redisKey;
	// 	redisSource = $resource('http://162.243.145.82:3000/node/validateRedisLock', {},{
	// 		query: {
	// 			method: 'GET',
	// 			data: false,
	// 			headers: {'redislockkey': redisKey}
	// 		}});
	// 	return redisSource.get(function(response){
	// 		//console.log(response);
	// 	}, function(error){
	// 		//console.log(error);
	// 	}).$promise;
	//
	// }
	// fetchBlogs = function(){
	// 	return blogs;
	// }
	// fetchTags = function(){
	// 	return tags;
	// }
	//
	// parseTags = function(tags) {
	// 	var tagsArray = [],
	// 		tagsObject = {};
	// 	tags.forEach(function(tag){
	// 		tagsObject[tag._id] = tag.total;
	// 	});
	// 	return tagsObject;
	// }
	//
	//
	// return {
	// 	getBlogs: getBlogs,
	// 	getTags: getTags,
	// 	getTags2: getTags2,
	// 	fetchBlogs: fetchBlogs,
	// 	fetchTags: fetchTags,
	// 	validateRedis: validateRedis
	//
	// }
	return {
		getUsers: getUsers,
		fetchUsers: fetchUsers,
		login: login,
		signup: signup
	}


}]);
