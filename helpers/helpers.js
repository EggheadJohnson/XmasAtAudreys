var crypto = require('crypto');

module.exports = {
    hash: function(inp){
        var md5sum = crypto.createHash('md5');
        md5sum.update(inp);
        return md5sum.digest('hex');

    }
}
