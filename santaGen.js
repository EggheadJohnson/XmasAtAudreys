var MongoClient = require('mongodb').MongoClient;


var participants = [
    'Amy',
    'Jackie',
    'Paul',
    'Christina',
    'Elizabeth',
    'David',
    'Divah',
    'Lee',
    'Wendy'
];



// console.log(runSecretSantaGen());
MongoClient.connect('mongodb://localhost:27017/xmasAtAudreys', function(err, db){
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
