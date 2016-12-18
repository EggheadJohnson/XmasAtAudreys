var MongoClient = require('mongodb').MongoClient;
var config = require('../server/util/config.js');
var crypto = require('crypto');
var mongoURL = config.mongoURL;

function hash(inp){
    var md5sum = crypto.createHash('md5');
    md5sum.update(inp);
    return md5sum.digest('hex');
}


var participants = [
    'Amy',
    'Jackie',
    'Paul',
    'Christina',
    'Elizabeth',
    'David',
    'Divah',
    'Lee',
    'Wendy',
    'Lynda'
];




MongoClient.connect(mongoURL, function(err, db){
    if (err) console.log(err);
    else {
        console.log('here');
        participants.forEach(function(user){
            var date = new Date();
            var tempToken = hash(user+date.getTime());
            var insert = {
                firstName: user,
                tempToken: tempToken
            }
            db.collection('users')
                .insertOne(insert, function(err, doc){
                    if (err) console.log(err);
                    else console.log(doc.ops);
                })
        })
    }


})
