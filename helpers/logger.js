module.exports = logger;

function logger (db, res, obj, httpCode, jsonResponse){
    // console.log(obj);
    obj.res.httpCode = httpCode;
    obj.res.jsonResponse = jsonResponse;
    // console.log(obj);
    if (!obj.res.httpCode || !obj.res.jsonResponse || !db) {
        console.log(!obj.res.httpCode, !obj.res.jsonResponse, !db);
        res.status(500).json({err: "critical system error"});
    }
    else {
        db.collection('analytics').insertOne(obj, function(err, r){});
        res.status(obj.res.httpCode).json(obj.res.jsonResponse);
    }
}
