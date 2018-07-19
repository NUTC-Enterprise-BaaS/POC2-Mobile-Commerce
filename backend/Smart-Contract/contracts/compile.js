var Web3 = require('web3');
var fs = require('fs');
var solc = require('solc');

var ethereumUri = 'http://10.0.0.76:8545'; // authority node
var address = 'ed02bc7fba831f1a451fa9dc75de63349a9a815f'; // transaction account

var web3 = new Web3();
web3.setProvider(new web3.providers.HttpProvider(ethereumUri));

if(!web3.isConnected()){
    throw new Error('unable to connect to ethereum node at ' + ethereumUri);
}else{
    console.log('connected to ehterum node at ' + ethereumUri);
}

var source = fs.readFileSync("./smart-points.sol", 'utf8');
// var source = fs.readFileSync("./smart-count.sol", 'utf8');
console.log('compiling contract...');
var compiledContract = solc.compile(source);
console.log('done');

for (var contractName in compiledContract.contracts) {
    var bytecode = compiledContract.contracts[contractName].bytecode;
    var abi = JSON.parse(compiledContract.contracts[contractName].interface);
    // var abi = compiledContract.contracts[contractName].interface;
}

// console.log(JSON.stringify(abi, undefined, 2));
console.log(compiledContract.contracts[contractName].interface);

/*
* deploy contract
*/
var gasEstimate = web3.eth.estimateGas({data: '0x' + bytecode});
console.log('gasEstimate = ' + gasEstimate);

var MyContract = web3.eth.contract(abi);
console.log('deploying contract...');

var myContractReturned = MyContract.new([], {
    from: address,
    data: '0x'+ bytecode,
    gas: gasEstimate + 5000
}, function (err, myContract) {
    if (!err) {
        if (!myContract.address) {
            console.log(`myContract.transactionHash = ${myContract.transactionHash}`);
        } else {
            console.log(`myContract.address = ${myContract.address}`); // the contract address
            global.contractAddress = myContract.address;
        }
    } else {
        console.log(err);
    }
});

(function wait () {
    setTimeout(wait, 1000);
})();