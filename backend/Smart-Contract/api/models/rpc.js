const rp = require('request-promise');

exports.rpcAPI = async(url,method,array)=>{
  return new Promise((resolve, reject) => {
    const options = {
      method: 'POST',
      url: url,
      body: {
        'jsonrpc': '2.0',
        'method': method,
        'params': array,
        'id': 74
      },
      json: true
    };
    rp(options)
      .then((data) => { resolve(data) })
      .catch((err) => { resolve(err) })
  });
};