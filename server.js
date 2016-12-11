// node modules
var express = require('express'),
	bodyParser = require('body-parser'),
	MongoClient = require('mongodb').MongoClient,
	ObjectId = require('mongodb').ObjectID,
	helpers = require('./helpers/helpers'),
	logger = require('./helpers/logger'),
	acceptedEmails = require('./secrets/acceptedEmails'),
	_ = require('lodash'),
	db;

MongoClient.connect('mongodb://localhost:27017/xmasAtAudreys', function(err, dbRes){
	db = dbRes;
});

process.on('exit', function(){
	console.log("exiting");
	db.close();
});

process.on('SIGINT', function(){
	console.log("SIGINTing");
	// db.close();
	process.exit();
})


	// db = require('./helpers/database')();
// console.log(db);
	// request = require('request'),
	// ioredis = require('ioredis'),


	// app modules
	// config = require('./util/config.js'),

	// initialize
var app = express()
		// .use(bodyParser.urlencoded({extended: false}))
		.use(bodyParser.json())
		// .use(express.query())
		// Allow CORS for dev
		.use(function (req, res, next) {
			res.header('Access-Control-Allow-Origin', '*');
			res.header('Access-Control-Allow-Methods', 'GET,PUT,POST,DELETE,OPTIONS');
			res.header('Access-Control-Allow-Headers', 'Content-Type,Authorization,InstitutionID,userId,token');
			if ('OPTIONS' == req.method){
                res.sendStatus(200);
            }
            else {
                next();
            }
		})
		.use(function(req, res, next){
			//analytics
			req.resObj = {
				path: req._parsedUrl.pathname,
				headers: _.cloneDeep(req.headers),
				body: _.cloneDeep(req.body),
				timeStamp: new Date(),
				res: {}
			}
			if (req.resObj.body && req.resObj.body.password) delete req.resObj.body.password;
			next();
		})
		// test auth
		.get('/node/status',function(req, res, next){
			logger(db, res, req.resObj, 200, {msg: "Node is running"});

		})
		.post('/login', function(req, res, next){
			db.collection('users').find({username: req.body.username, password: req.body.password}).toArray(function(err, docs){
				if (err) {
					logger(db, res, req.resObj, 401, {err:err});
				}
				else if (docs.length === 0) logger(db, res, req.resObj, 400, {err:"User not found"});
				else {
					var date = new Date();
					date = date.getTime();
					var sessionToken = helpers.hash(req.body.username+date);
					db.collection('sessions').insertOne({userId: docs[0]._id, token: sessionToken}, function(err, r){
						logger(db, res, req.resObj, 200, {token: sessionToken});
					})
				}
			})
		})
		.post('/users', function(req, res, next){
			var okKeys = ['firstName', 'lastName', 'email', 'password', 'wishlist'];
			if (!req.body.email || !req.body.username || !req.body.password) logger(db, res, req.resObj, 400, {err: "Missing username, email, or password"})
			else {
				db.collection('users').find({
					username: req.body.username
				}).toArray(function(err, docs){
					if (docs.length > 0) logger(db, res, req.resObj, 409, {err: "user exists"});
					if (acceptedEmails.indexOf(req.body.email.toLowerCase()) < 0) logger(db, res, req.resObj, 403, {err: "Email not on approved list"});
					else {
						Object.keys(req.body).forEach(function(k){
							if (okKeys.indexOf(k) < 0) delete req.body[k];
						});
						db.collection('users').insertOne(req.body, function(err, doc){
							if (err) {
								logger(db, res, req.resObj, 401, {err:err});
							}
							else {
								var date = new Date();
								date = date.getTime();
								var sessionToken = helpers.hash(req.body.username+date);
								db.collection('sessions').insertOne({userId: doc.insertedId, token: sessionToken}, function(err, r){
									if (err) {
										logger(db, res, req.resObj, 401, {err:err});
									}
									else logger(db, res, req.resObj, 200, {token: sessionToken});
									// db.close();
								})
							}
						})
					}
				})
			}
		})
		.use('/*', function(req, res, next){
			// MongoClient.connect('mongodb://localhost:27017/xmasAtAudreys', function(err, db){
				db.collection('sessions').findOne({token: req.headers.token},function(err, doc){
					// db.close();
					if (err) {
						logger(db, res, req.resObj, 401, {err: err});
					}
					else if (!doc) logger(db, res, req.resObj, 400, {err: "Not Authorized"});
					else {
						req.userId = doc.userId;
						next();
					}
				})
			// })
		})
		.get('/users', function(req, res, next){
			// MongoClient.connect('mongodb://localhost:27017/xmasAtAudreys', function(err, db) {
				db.collection('users').find().toArray(function(err, docs){
					// db.close();
					if (err) {
						logger(db, res, req.resObj, 401, {err: err});
					}
					else logger(db, res, req.resObj, 200, {users: docs});
				});
			// });
		})
		.get('/users/:userId',function(req, res, next){
			db.collection('users').findOne({_id: ObjectId(req.params.userId)}, function(err, doc){
				if (err) logger(db, res, req.resObj, 401, {err: err});
				else logger(db, res, req.resObj, 200, {user: doc});
			})
		})
		.put('/users/:userId', function(req, res, next){
			var updates = req.body,
				okKeys = ['firstName', 'lastName', 'email', 'password', 'wishlist'],
				_id = ObjectId(req.params.userId);
			updates.updatedAt = new Date();

			if (updates.username) logger(db, res, req.resObj, 403, {err: "Cannot update username"});
			if (acceptedEmails.indexOf(updates.email.toLowerCase()) < 0) logger(db, res, req.resObj, 403, {err: "Email not on approved list"});
			Object.keys(updates).forEach(function(k){
				if (okKeys.indexOf(k) < 0) delete updates[k];
			});

			db.collection('users').update({_id: _id}, updates, function(err, r){
				if (err) {
					logger(db, res, req.resObj, 401, {err: err});
				}
				else logger(db, res, req.resObj, 201, {ok: true});
			})
		});


app.listen(3000);
console.log(Date());
console.log('EE Node running on port 3000')
