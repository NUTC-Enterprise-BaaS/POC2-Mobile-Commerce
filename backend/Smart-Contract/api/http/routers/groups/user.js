const Router = require('koa-router');
const user = require('../../controller/userController');
const router = new Router({prefix: '/user'});

router.get('/', async(ctx)=>{
	return ctx.body = {
    statusCode:200,
    message:'test',
  };
});

router.get('/test',user.testuser);
// account
router.post('/getPoint',user.getPoint);
router.post('/newAccount',user.newAccount);

// origin
router.post('/addOrigin',user.addOrigin);
router.get('/getOriginList',user.getOriginList);
router.post('/getOriginState',user.getOriginState);
router.post('/getOriginRate',user.getOriginRate);


module.exports = router;