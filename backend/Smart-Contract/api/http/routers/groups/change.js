const Router = require('koa-router');
const change = require('../../controller/changeController');
const router = new Router({prefix: '/change'});

router.get('/', async(ctx)=>{
	return ctx.body = {
    statusCode:200,
    message:'test',
  };
});

router.get('/test',change.testindex);
// change point
router.post('/changePoint',change.changePoint);
router.post('/getRecord',change.getRecord);

module.exports = router;