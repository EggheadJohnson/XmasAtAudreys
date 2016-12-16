var MongoClient = require('mongodb').MongoClient;
var crypto = require('crypto');

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
]

MongoClient.connect('mongodb://localhost:27017/xmasAtAudreys', function(err, db){
    var users = [];
    participants.forEach(function(person){
        var date = new Date();
        date = date.getTime();


        users.push({
            firstName: person,
            tempToken: hash(person+date)
        })
        // users.push(user);
        // console.log(user);

    })
    db.collection('users').insertMany(users, function(err, res){db.close();})

});
