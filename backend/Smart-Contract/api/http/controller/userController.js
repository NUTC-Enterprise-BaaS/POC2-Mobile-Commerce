const env = require('../../../config/env.json');
const serverUrl = env['rpcUrl'];
const rpcAPI = require('../../models/rpc').rpcAPI;
const Web3 = require("web3");
const web3 = new Web3();
web3.setProvider(new Web3.providers.HttpProvider(env.rpcUrl));

const contract = web3.eth.contract(env.abi).at(env.address);
const admin = '0xed02bc7fba831f1a451fa9dc75de63349a9a815f';
// const account = web3.eth.accounts[1];

/***
 取得帳號的餘額
 */
const getBalance = async() =>{
  return new Promise(function(resolve, reject) {
    if(web3.eth.accounts.length>0){
      const result = {
        'account': web3.eth.accounts[web3.eth.accounts.length-1],
        'balance': web3.eth.getBalance(web3.eth.accounts[web3.eth.accounts.length-1]).toNumber()
      };
      console.log(result);
      if(result['balance']!=0) resolve(result);
      else getBalance;
    }else{
      console.log('The Ethereum node have not any account.');
    }
  });
}

/***
 發送乙太幣
 */
const sendBalance = async(from, to, value)=>{
  return new Promise(function(resolve, reject) {
    const data = {
      'from': from,
      'to': to,
      'value': value
    };
    web3.eth.sendTransaction(data, function(err, transactionHash) {
      if (!err){
        console.log(transactionHash);
        resolve(transactionHash)
      }else{
        console.log(err);
        reject(err);
      } 
    });
  });
}

/***
 加入商家event
 */
const addOriginEvent = async(addOriginEvent,txHash,originCount)=>{
  return new Promise(function(resolve, reject) {
    addOriginEvent.watch(function(err, result) {
      if (!err && result.transactionHash == txHash) {
        console.log({
          'originName': contract.getOrigin.call(originCount,{from: admin})[0],
          'rate': contract.getOrigin.call(originCount,{from: admin})[1].toNumber()
        });
        resolve({
          statusCode:200,
          message:{
            'originName': contract.getOrigin.call(originCount,{from: admin})[0],
            'rate': contract.getOrigin.call(originCount,{from: admin})[1].toNumber()
          }
        }); 
      } else {
        console.log(err);
        reject(err);
      }
      addOriginEvent.stopWatching();          
    });
  });
}

/***
 加入account event
 */
const addAccountEvent = async(addAccountEvent,smartContractTxHash,accountAddr)=>{
  return new Promise(function(resolve, reject) {
    addAccountEvent.watch(function(err, result) {
      if (!err && result.transactionHash == smartContractTxHash) {
        console.log({
          'originName': contract.getPoint.call({from: accountAddr['result']})[0],
          'point': contract.getPoint.call({from: accountAddr['result']})[1].toNumber(),
          'account':accountAddr['result']
        });
        resolve({
          statusCode:200,
          message:{
            'originName': contract.getPoint.call({from: accountAddr['result']})[0],
            'point': contract.getPoint.call({from: accountAddr['result']})[1].toNumber(),
            'account':accountAddr['result']
          }
        }); 
      } else {
        console.log(err);
        reject(err);
      }
      addAccountEvent.stopWatching();
    });
  });
}



exports.testuser = async(ctx)=>{
  return ctx.body = {
    statusCode:200,
    message:'testuser',
  };
}

/***
 新增商家
 */
exports.addOrigin = async(ctx)=>{
  const name = ctx.request.body.name;
  const rate = ctx.request.body.rate;
  const originCount = contract.getOriginCount.call().toNumber();
  if(originCount==0){
    const txHash = contract.addOrigin.sendTransaction(originCount, name, rate, {from: admin});
    return ctx.body = await addOriginEvent(contract.AddOrigin(),txHash,originCount);
  }
	for (let i = 0; i < originCount; i++) {
		if(contract.getOrigin.call(i)[0] == name) {
			console.log('This name is already exist.');
			return ctx.body = {
        statusCode:400,
        message:'This name is already exist.'
      };
    };
    if(i==originCount-1){
      const txHash = contract.addOrigin.sendTransaction(originCount, name, rate, {from: admin});
      return ctx.body = await addOriginEvent(contract.AddOrigin(),txHash,originCount);
    };
  };
}

/***
 商家列表
 */
exports.getOriginList = async(ctx)=>{
  let result = [];
  const originCount = contract.getOriginCount.call().toNumber();
  if(originCount==0) {
    return ctx.body = {
      statusCode:200,
      message:[]
    }
  };
	for (let i = 0; i < originCount; i++) {
    const origin = contract.getOrigin.call(i);
    result.push({
     	'originName': origin[0],
    	'rate': origin[1].toNumber()
    });
		console.log({
			'originName': origin[0],
			'rate': origin[1].toNumber()
    });
    if(i==originCount-1){
      return ctx.body = {
        statusCode:200,
        message:result
      };
    };
	};
}

/***
 取得特定商家狀態
 */
exports.getOriginState = async(ctx)=>{
  const index = ctx.request.body.index;
	const result = {
		'originName': contract.getOrigin.call(index)[0],
		'rate': contract.getOrigin.call(index)[1].toNumber()
	};
	console.log(result);
  return ctx.body = {
    statusCode:200,
    message:result
  };
}

/***
 取得特定商家匯率
 */
exports.getOriginRate = async(ctx)=>{
  const store = ctx.request.body.store;
  let result = [];
  const originCount = contract.getOriginCount.call().toNumber();
	for (let i = 0; i < originCount; i++) {
		let origin = contract.getOrigin.call(i);
		if(origin[0] == store){
			console.log({
				'originName': origin[0],
				'rate': origin[1].toNumber()
			});
			result.push(origin);
		}
		if(i==originCount-1){
      if(result.length==0){
        console.log("Invalid store name.");
        return ctx.body = {
          statusCode:400,
          message:"Invalid store name."	
        };
      };
			console.log(result[0][1].toNumber());
      return ctx.body = {
        statusCode:200,
        message:{
          "rate":result[0][1].toNumber()
        }
      };
		}
	}
}





/***
 新增區塊鏈帳戶
 */
exports.newAccount = async(ctx)=>{
  const point = ctx.request.body.point;
  const store = ctx.request.body.store;
  console.log(point,store);
  const accountAddr = await rpcAPI(serverUrl,'personal_newAccount',['123456']);
  console.log('accountAddr: '+JSON.stringify(accountAddr));
  console.log(web3.eth.accounts);
  await setTimeout(function(){
    return new Promise(function(resolve, reject) {
      resolve();
    });
  },1000);
  const rpcTxHash = await sendBalance(admin, accountAddr['result'], 999999999999999999999999);

  getBalance();
  await rpcAPI(serverUrl,'personal_unlockAccount',[accountAddr['result'],'123456',0]);
  const smartContractTxHash = contract.addAccount.sendTransaction(store, point, {from: accountAddr['result']});
  return ctx.body = await addAccountEvent(contract.AddAccount(),smartContractTxHash,accountAddr);
}


/***
 取得帳戶點數
 */
exports.getPoint = async(ctx)=>{
  const account = ctx.request.body.account;
	const result = {
		'originName': contract.getPoint.call({from: account})[0],
		'point': contract.getPoint.call({from: account})[1].toNumber()
	}
	console.log('getPoint: '+JSON.stringify(result));
	return ctx.body = {
    statusCode:200,
    message:result
  };
}



