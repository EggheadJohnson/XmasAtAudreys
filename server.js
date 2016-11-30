// node modules
var express = require('express'),
	bodyParser = require('body-parser'),
	MongoClient = require('mongodb').MongoClient,
	// request = require('request'),
	// ioredis = require('ioredis'),


	// app modules
	// config = require('./util/config.js'),

	// initialize
	app = express()
		// .use(bodyParser.urlencoded({extended: false}))
		.use(bodyParser.json())
		// .use(express.query())
		// Allow CORS for dev
		.use(function (req, res, next) {
			res.header('Access-Control-Allow-Origin', '*');
			res.header('Access-Control-Allow-Methods', 'GET,PUT,POST,DELETE,OPTIONS');
			res.header('Access-Control-Allow-Headers', 'Content-Type,Authorization,InstitutionID,userId,redislockkey');
			if ('OPTIONS' == req.method){
                res.sendStatus(200);
            }
            else {
                next();
            }
		})
		// test auth
		.get('/node/status',function(req, res, next){
			res.status(200).json({msg: "Node is running"});
		})
		.post('/login', function(req, res, next){
			console.log(req, req.body);
			MongoClient.connect('mongodb://localhost:27017/xmasAtAudreys', function(err, db){
				db.collection('users').find({name: req.body.name}).toArray(function(err, docs){
					if (err) return res.stats(401).json({err:err});
					else if (docs.length === 0) res.status(400).json({err: "User not found"});
					else {
						var sessionToken = new Date();
						sessionToken = sessionToken.getTime()+'';
						db.collection('sessions').insertOne({userId: docs[0]._id, token: sessionToken}, function(err, r){
							res.status(200).json({token: sessionToken});
						})
					}
				})
			})
		})
		.use('/*', function(req, res, next){
			console.log("in the use");
			console.log(req.headers);
			MongoClient.connect('mongodb://localhost:27017/xmasAtAudreys', function(err, db){
				db.collection('sessions').find({token: req.headers.token}).toArray(function(err, docs){
					if (err) return res.status(401).json({err:err});
					else if (docs.length === 0) res.status(400).json({err: "Not Authorized"});
					else next();
				})
			})
		})
		.get('/users', function(req, res, next){
			MongoClient.connect('mongodb://localhost:27017/xmasAtAudreys', function(err, db) {
				db.collection('users').find().toArray(function(err, docs){
					if (err) res.status(401).json({err: err});
					else res.status(200).json({users: docs});
				});
			});
		});


app.listen(3000);
console.log(Date());
console.log('EE Node running on port 3000')
