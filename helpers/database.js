var MongoClient = require('mongodb').MongoClient;

module.exports = (function(){
    MongoClient.connect('mongodb://localhost:27017/xmasAtAudreys', function(err, db){
        // console.log(db);
        return db;
    });
    // return db;
});
