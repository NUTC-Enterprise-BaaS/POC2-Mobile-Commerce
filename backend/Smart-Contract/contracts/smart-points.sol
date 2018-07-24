pragma solidity ^0.4.11;

contract Test {

  struct Account {
    string origin;
    uint points;
  }

  struct Origin {
    string name;
    uint rate;
  }

  struct Ledger {
    string tx;
  }

  mapping(address => Account) public accounts;
  mapping(uint => Origin) public origins;
  mapping(address => Ledger) public ledgers;

  uint originCount;

  event AddAccount(address addr);
  event AddOrigin(uint index);
  event TransformPoint(address indexed fromAddr, uint indexed fromPoint, uint indexed toPoint);

  function Test () {
    originCount = 0;
  }

  function addAccount (string originName, uint points) returns (string, uint) {
    accounts[msg.sender].origin = originName;
    accounts[msg.sender].points = points;
    AddAccount(msg.sender);
    return (
      accounts[msg.sender].origin,
      accounts[msg.sender].points
    );
  }

  function getPoint () returns (string, uint) {
    return (
      accounts[msg.sender].origin,
      accounts[msg.sender].points
    );
  }

  function getToAccountPoint ( address addr) returns (string, uint) {
    return (
      accounts[addr].origin,
      accounts[addr].points
    );
  }

  function addOrigin (uint index, string originName, uint rate) returns (string, uint) {
    origins[index].name = originName;
    origins[index].rate = rate;
    originCount += 1;
    AddOrigin(index);
    return (
      origins[index].name,
      origins[index].rate
    );
  }

  function getOrigin (uint index) returns (string, uint) {
    return (origins[index].name, origins[index].rate);
  }

  function getOriginCount () returns (uint) {
    return originCount;
  }

  function transformPoint (uint point, uint fromRate, uint toRate, address addr) returns (uint, uint) {
    accounts[msg.sender].points -= point;
    accounts[addr].points += (point/fromRate*toRate);
    TransformPoint(addr, point, point/fromRate*toRate);
    return (
      accounts[msg.sender].points,
      accounts[addr].points
    );
  }

  function addLedgers (string txHash) {
    ledgers[msg.sender].tx = txHash;
  }

  function getLedgers () returns (string) {
    return (ledgers[msg.sender].tx);
  }
}