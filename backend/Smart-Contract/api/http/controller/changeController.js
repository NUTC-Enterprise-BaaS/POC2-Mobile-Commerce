const env = require('../../../config/env.json');

const Web3 = require("web3");
const web3 = new Web3();
web3.setProvider(new Web3.providers.HttpProvider(env.rpcUrl));
const contract = web3.eth.contract(env.abi).at(env.address);

/***
 取得轉換帳戶點數
 */
const getPoint =  async(account) =>{
  return new Promise(function(resolve, reject) {
    const result = {
      'originName': contract.getPoint.call({from: account})[0],
      'point': contract.getPoint.call({from: account})[1].toNumber()
    }
    console.log(result);
    resolve(result);
  });
}

/***
 取得轉換商家比率
 */
const getRate = async(store) =>{
  return new Promise(function(resolve, reject) {
    let result = [];
    for (var i = 0; i < contract.getOriginCount.call().toNumber(); i++) {
      const origin = contract.getOrigin.call(i);
      if(origin[0] == store){
        // console.log({
        // 	'originName': origin[0],
        // 	'rate': origin[1].toNumber()
        // });
        result.push(origin);
      }
      if(i==contract.getOriginCount.call().toNumber()-1){
        // console.log(result[0][1].toNumber());
        resolve(result[0][1].toNumber());
      }
    }
  });
}

/***
  change point event
 */
const transformPointEvent = async(transformPointEvent,txHash,fromAccount,toAccount)=>{
  return new Promise(function(resolve, reject) {
    transformPointEvent.watch(function(err, result) {
      if (!err && result.transactionHash == txHash) {
        console.log({
                'changeTxRecord':txHash,
          			'fromOriginName': contract.getPoint.call({from: fromAccount})[0],
          			'fromPoint': contract.getPoint.call({from: fromAccount})[1].toNumber(),
          			'toOriginName': contract.getPoint.call({from: toAccount})[0],
          			'toPoint': contract.getPoint.call({from: toAccount})[1].toNumber()
          		});
        resolve({
          statusCode:200,
          message:{
            'changeTxRecord':txHash,
            'fromOriginName': contract.getPoint.call({from: fromAccount})[0],
            'fromPoint': contract.getPoint.call({from: fromAccount})[1].toNumber(),
            'toOriginName': contract.getPoint.call({from: toAccount})[0],
            'toPoint': contract.getPoint.call({from: toAccount})[1].toNumber()
          }
        }); 
      } else {
        console.log(err);
        reject(err);
      }
      transformPointEvent.stopWatching();
    });
  });
}

/***
 帳本列表
 */
function getLedgers (account) {
	var result = contract.getLedgers.call({from: account});
	console.log(result);
	return result;
}

/***
 新增帳本列表
 */
function addLedgers (changeTxHash, account) {
  // console.log("changeTxHash: "+changeTxHash);
	var txHash = contract.addLedgers.sendTransaction(changeTxHash, {from: account});
	console.log('addLedgersTxHash: '+txHash);
}


/***
 交換點數
 */
exports.changePoint = async(ctx)=>{
  const txPoint = ctx.request.body.txPoint;
  const fromAccountAddress = ctx.request.body.fromAccount;
  const toAccountAddress = ctx.request.body.toAccount;
  // const fromRate = ctx.request.body.fromRate;
  // const toRate = ctx.request.body.toRate;

  console.log(fromAccountAddress);
  console.log(toAccountAddress);

  const fromAccount = await getPoint(fromAccountAddress);
	console.log(fromAccount['originName']);
	const toAccount = await getPoint(toAccountAddress);
  console.log(toAccount['originName']);
  
	const fromStoreRate = await getRate(fromAccount['originName']);
  console.log(fromStoreRate);
	const toStoreRate = await getRate(toAccount['originName']);
	console.log(toStoreRate);

	if(fromAccount['point']<txPoint){
		console.log("The Account point not enough , only have "+fromAccount['point']+" must be have "+txPoint);
    return ctx.body = {
      statusCode:200,
      message:"The Account point not enough , only have "+fromAccount['point']+" must be have "+txPoint
    };
	}

  var txHash = contract.transformPoint.sendTransaction(txPoint, fromStoreRate, toStoreRate, toAccountAddress, {from: fromAccountAddress});
  console.log('changeTxHash: '+txHash);
  addLedgers(txHash.substring(2,txHash.length), fromAccountAddress);
  addLedgers(txHash.substring(2,txHash.length), toAccountAddress);
  return ctx.body = await transformPointEvent(contract.TransformPoint(),txHash,fromAccountAddress,toAccountAddress);
}

/***
 取得交換紀錄
 */
exports.getRecord = async(ctx)=>{
  // const accountAddress = ctx.request.body.account;
  
  // const record = '0x' + getLedgers(accountAddress);
  // console.log('changeTxRecord: '+record);
  
  // if(record=='0x') return ctx.body = { message: 'The account have not any Tx in changeBouns Platform.'};

  const changeTxHash = ctx.request.body.changeTxHash;
  // eth_getTransactionReceipt
  const receipt = web3.eth.getTransactionReceipt(changeTxHash);
  console.log('changeTxReceipt: '+JSON.stringify(receipt));
  return ctx.body = {
    changeTxRecord:changeTxHash,
    fromAccount:receipt['from'],
    fromPoint:parseInt(receipt['logs'][0]['topics'][2],16),
    toAccount:'0x'+receipt['logs'][0]['topics'][1].substr(26,40),
    toPoint:parseInt(receipt['logs'][0]['topics'][3],16)
  };
}

exports.testindex = async(ctx)=>{
  return ctx.body = {
    statusCode:200,
    message:'testindex'
  };
}