const koa = require('koa2');
const bodyParser = require('koa-bodyparser');
const Router = require('koa-router');
const json = require('koa-json');

const app = new koa();

app.use(json());
app.use(bodyParser());

app.use(require('./http/router').middleware());

app.listen(8000,()=>{
  console.log('Already start bonus points server!!!');
});