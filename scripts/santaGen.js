var MongoClient = require('mongodb').MongoClient;
var config = require('../server/util/config.js');

var mongoURL = config.mongoURL;

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

var restrictions = {
    'Amy': ['Jackie'],
    'Jackie': ['Amy'],
    'Paul': ['Christina'],
    'Christina': ['Paul'],
    'Elizabeth': ['Amy', 'Christina', 'David'],
    'David': ['Divah'],
    'Divah': ['David'],
    'Lee': ['Wendy'],
    'Wendy': ['Lee'],
    'Lynda': []
}

function checkList(list){
    for (var k in list){
        if (k === list[k] || restrictions[k].indexOf(list[k]) > -1) return false;
    }
    return true;
}

function genList(list){
    list = list || participants;
    var santas = {};
    var remaining = list.slice();
    list.forEach(function(person) {
        var r = Math.floor(Math.random()*remaining.length);
        santas[person] = remaining[r];
        remaining.splice(r, 1);
    });
    return santas;
}

function runSecretSantaGen(){
    var santas = genList();
    while (!checkList(santas)){
        santas = genList();
    }
    return santas;
}

var santas = runSecretSantaGen();
// console.log(runSecretSantaGen());
MongoClient.connect(mongoURL, function(err, db){
    db.collection('users').find().toArray(function(err, docs){
        docs.forEach(function(doc){
            // console.log(doc, docs.length, santas);
            doc.recipientId = docs.filter(function(d){
                // console.log(d.firstName, santas[doc.firstName], d.firstName === santas[d.firstName]);
                return d.firstName === santas[doc.firstName];
            })[0]._id;
            db.collection('users').updateOne({firstName: doc.firstName}, {$set: {recipientId: doc.recipientId}}, function(){})
        });
        // console.log(docs);
        db.close();
    });

})
