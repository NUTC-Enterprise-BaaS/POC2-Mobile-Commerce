var Web3 = require("web3");
var web3 = new Web3();
web3.setProvider(new Web3.providers.HttpProvider("http://10.0.0.76:8545"));

var abi = [{"constant":true,"inputs":[{"name":"","type":"uint256"}],"name":"origins","outputs":[{"name":"name","type":"string"},{"name":"rate","type":"uint256"}],"payable":false,"stateMutability":"view","type":"function"},{"constant":false,"inputs":[{"name":"addr","type":"address"}],"name":"getToAccountPoint","outputs":[{"name":"","type":"string"},{"name":"","type":"uint256"}],"payable":false,"stateMutability":"nonpayable","type":"function"},{"constant":false,"inputs":[{"name":"index","type":"uint256"}],"name":"getOrigin","outputs":[{"name":"","type":"string"},{"name":"","type":"uint256"}],"payable":false,"stateMutability":"nonpayable","type":"function"},{"constant":false,"inputs":[],"name":"getOriginCount","outputs":[{"name":"","type":"uint256"}],"payable":false,"stateMutability":"nonpayable","type":"function"},{"constant":false,"inputs":[{"name":"point","type":"uint256"},{"name":"fromRate","type":"uint256"},{"name":"toRate","type":"uint256"},{"name":"addr","type":"address"}],"name":"transformPoint","outputs":[{"name":"","type":"uint256"},{"name":"","type":"uint256"}],"payable":false,"stateMutability":"nonpayable","type":"function"},{"constant":false,"inputs":[],"name":"getPoint","outputs":[{"name":"","type":"string"},{"name":"","type":"uint256"}],"payable":false,"stateMutability":"nonpayable","type":"function"},{"constant":true,"inputs":[{"name":"","type":"address"}],"name":"accounts","outputs":[{"name":"origin","type":"string"},{"name":"points","type":"uint256"}],"payable":false,"stateMutability":"view","type":"function"},{"constant":false,"inputs":[{"name":"originName","type":"string"},{"name":"points","type":"uint256"}],"name":"addAccount","outputs":[{"name":"","type":"string"},{"name":"","type":"uint256"}],"payable":false,"stateMutability":"nonpayable","type":"function"},{"constant":false,"inputs":[{"name":"index","type":"uint256"},{"name":"originName","type":"string"},{"name":"rate","type":"uint256"}],"name":"addOrigin","outputs":[{"name":"","type":"string"},{"name":"","type":"uint256"}],"payable":false,"stateMutability":"nonpayable","type":"function"},{"inputs":[],"payable":false,"stateMutability":"nonpayable","type":"constructor"},{"anonymous":false,"inputs":[{"indexed":false,"name":"addr","type":"address"}],"name":"AddAccount","type":"event"},{"anonymous":false,"inputs":[{"indexed":false,"name":"index","type":"uint256"}],"name":"AddOrigin","type":"event"},{"anonymous":false,"inputs":[{"indexed":false,"name":"addr","type":"address"}],"name":"TransformPoint","type":"event"}];
var address = "0xb357c3122a5f818ead8b245d66338d1d9093a980";

const test = web3.eth.contract(abi).at(address);
var account = web3.eth.accounts[1];
// var account = "0x873c3a226f40e02a91688ddd9992ccdb32c40c65";
var toAccount = "0xebf1c2b06337933c909269837e76776ff109b2bf";

// console.log(account);
// addOrigin('測試2', 2);
// getOrigin(0);
// getOriginCount();
// addAccount('測試2',100);
// getPoint();
// getToAccount(toAccount);
// getRate('測試2')
changePoint(10, toAccount);



// sendBalance('0xed02bc7fba831f1a451fa9dc75de63349a9a815f', '0xebf1c2b06337933c909269837e76776ff109b2bf', 999999999999999999999999);
// getBalance();


/***
 新增帳戶
 */
function addAccount (originName, point) {
	var txHash = test.addAccount.sendTransaction(originName, point, {from: account});
	var addAccountEvent = test.AddAccount();
	addAccountEvent.watch(function(err, result) {
		if (!err && result.transactionHash == txHash) {
			console.log({
				'originName': test.getPoint.call({from: account})[0],
				'point': test.getPoint.call({from: account})[1].toNumber()
			});
		} else {
			console.log(err);
		}
		addAccountEvent.stopWatching();
	});
}
 
/***
 取得帳戶點數
 */
function getPoint () {
	var result = {
		'originName': test.getPoint.call({from: account})[0],
		'point': test.getPoint.call({from: account})[1].toNumber()
	}
	console.log(result);
	return result;
}

/***
 取得轉換帳戶點數
 */
function getToAccount (account) {
	var result = {
		'originName': test.getToAccountPoint.call(account,{from: account})[0],
		'point': test.getToAccountPoint.call(account,{from: account})[1].toNumber()
	}
	// console.log(result);
	return result;
}

/***
 取得轉換商家比率
 */
function getRate (store) {
	var result = [];
	for (var i = 0; i < test.getOriginCount.call().toNumber(); i++) {
		var origin = test.getOrigin.call(i);
		if(origin[0] == store){
			// console.log({
			// 	'originName': origin[0],
			// 	'rate': origin[1].toNumber()
			// });
			result.push(origin);
		}
		if(i==test.getOriginCount.call().toNumber()-1){
			console.log(result[0][1].toNumber());
			return result[0][1].toNumber();
		}
	}
}

/***
 交換點數
 */
function changePoint (txPoint, toAccount) {
	var acc = getPoint(account);
	console.log(acc['originName']);
	var toAcc = getToAccount(toAccount);
	console.log(toAcc['originName']);
	var storeRate = getRate(acc['originName']);
	console.log(storeRate);
	var toStoreRate = getRate(toAcc['originName']);
	console.log(toStoreRate);

	if(acc['point']<txPoint){
		console.log("The Account point not enough , only have "+acc['point']+" must be have "+txPoint);
		return "The Account point not enough , only have "+acc['point']+" must be have "+txPoint;
	}

	var txHash = test.transformPoint.sendTransaction(txPoint, storeRate, toStoreRate, toAccount, {from: account});
	var transformPointEvent = test.TransformPoint();
	transformPointEvent.watch(function(err, result) {
		if (!err && result.transactionHash == txHash) {
			console.log({
				'from,originName': test.getPoint.call({from: account})[0],
				'from,point': test.getPoint.call({from: account})[1].toNumber(),
				'to,originName': test.getPoint.call({from: toAccount})[0],
				'to,point': test.getPoint.call({from: toAccount})[1].toNumber()
			});
		} else {
			console.log(err);
		}
		transformPointEvent.stopWatching();
	});
}



/***
 新增商家
 */
function addOrigin (name, rate) {
	var originCount = test.getOriginCount.call().toNumber();
	for (var i = 0; i < originCount; i++) {
		if(test.getOrigin.call(i)[0] == name) {
			console.log('This name is already exist.');
			return;
		}
	}
	var txHash = test.addOrigin.sendTransaction(originCount, name, rate, {from: account});
	var addOriginEvent = test.AddOrigin();
	addOriginEvent.watch(function(err, result) {
		if (!err && result.transactionHash == txHash) {
			console.log({
				'originName': test.getOrigin.call(originCount,{from: account})[0],
				'rate': test.getOrigin.call(originCount,{from: account})[1].toNumber()
			});
		} else {
			console.log(err);
		}
		addOriginEvent.stopWatching();
	});
}

/***
 商家列表
 */
function getOriginCount () {
	for (var i = 0; i < test.getOriginCount.call().toNumber(); i++) {
		var origin = test.getOrigin.call(i)
		console.log({
			'originName': origin[0],
			'rate': origin[1].toNumber()
		});
	}
}

/***
 取得商家狀況
 */
function getOrigin (index) {
	var result = {
		'originName': test.getOrigin.call(index,{from: account})[0],
		'rate': test.getOrigin.call(index,{from: account})[1].toNumber()
	}
	console.log(result);
	return result;
}

/***
 取得帳號的餘額
 */
function getBalance () {
	for (var index = 0; index < web3.eth.accounts.length; index++) {
		console.log({
			'account': web3.eth.accounts[index],
			'balance': web3.eth.getBalance(web3.eth.accounts[index]).toNumber()
		});
	}
}

/***
 發送乙太幣
 */
function sendBalance (from, to, value) {
	var data = {
		'from': from,
		'to': to,
		'value': value
	};
	web3.eth.sendTransaction(data, function(err, transactionHash) {
		if (!err) console.log(transactionHash);
		else console.log(err);
	});
}



