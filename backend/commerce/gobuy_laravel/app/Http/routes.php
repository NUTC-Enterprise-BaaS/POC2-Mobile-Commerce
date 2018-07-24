<?php
/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('adminLogin');
});
Route::get('logout', 'Auth\AuthController@logout');
Route::post('adminLogin', ['as' => 'adminLogin', 'uses' => 'AdminController@adminLogin']);
Route::group(['middleware' => 'AdminAuth'], function() {
	Route::get('gobuynotification', 'HomeController@gobuyPush');
	Route::get('push/lucky/record', 'HomeController@LuckyRecord');
});

Route::group(['prefix' => '/api'], function() {
	Route::get('v1/stor/rate', 'UsersController@getStorRate');
	Route::post('v1/register', 'UsersController@postRegister');
	Route::get('v1/receive/LdapPoint/{username}', 'UsersController@receiveLdapPointChange');
	Route::post('v1/login',	'UsersController@postLogin');
	Route::post('v1/user/password/forgot/verify_code/send', 'UsersController@postSend');
	Route::post('v1/user/password/forgot/verify_code', 'UsersController@checkVerifyCode');
	Route::post('v1/user/rescue/password', 'UsersController@rescuePassword');
	Route::post('v1/user/check/version', 'UsersController@checkVersion');
	Route::post('v1/special/check/version', 'SpecialUsersController@checkVersion');
	Route::post('v1/preferential/check/version', 'PreferentialController@checkVersion');
	Route::get('v1/advertise/show', 'CommunityController@getAdvertise');
	Route::get('v1/testMap', 'StoresController@showMap');
	Route::get('v1/store/allSpeShop', 'StoresController@shopAllSpeStore');
	Route::get('v1/store/allPreShop', 'StoresController@shopAllPreStore');
	Route::post('v1/id/phone', 'UsersController@getPhone');
	Route::post('v1/test/push', 'CommunityController@testPushLucky');
});
Route::group(['prefix' => '/api', 'middleware' => 'token.auth'], function() {
	Route::post('v1/buy/voucher','UsersController@getShopVoucher');
	Route::get('v1/voucher/list','UsersController@getVoucherList');
	Route::post('v1/use/voucher','UsersController@disableVoucherList');
	Route::get('v1/get/getverifycode','UsersController@getVerifyCode');
	Route::get('v1/token/stor', 'UsersController@getStor');
	Route::post('v1/user/point/change', 'UsersController@ldapUserPointChange');
	Route::post('v1/user/ldapadd', 'UsersController@ldapUserAdd');
	Route::post('v1/user/ldapadd/token', 'UsersController@ldapUserTokenAdd');
	Route::get('v1/user/ldap/binding/clear', 'UsersController@cleanBinding');
	Route::get('v1/user/point', 'UsersController@getUserPoint');
	Route::get('v1/shop/pay', 'PaymentController@testShopAllPay');
	Route::get('v1/all/pay', 'PaymentController@testAllPay');
	Route::post('v1/all/return', 'PaymentController@testAllPayReturn');
	Route::post('v1/all/payment/info', 'PaymentController@testAllPayInfo');

	Route::post('v1/user/special/csv/check', 'SpecialUsersController@checkCsvPassword');
	Route::post('v1/special/csv/download', 'SpecialUsersController@downloadCsv');
	Route::post('v1/user/special/csv/reset/password', 'SpecialUsersController@csvResetPwd');
	Route::post('v1/special/advertise/publish', 'SpecialUsersController@publishAds');
	Route::get('v1/special/activity', 'SpecialUsersController@speActivity');
	Route::get('v1/special/qrcode/show', 'SpecialUsersController@getspeUserQRcode');

	Route::post('v1/user/preferential/csv/check', 'PreferentialController@checkCsvPassword');
	Route::get('v1/preferential/user/detail', 'PreferentialController@preUserDetail');
	Route::post('v1/preferential/csv/download', 'PreferentialController@downloadCsv');
	Route::post('v1/user/preferential/csv/reset/password', 'PreferentialController@csvResetPwd');
	Route::get('v1/preferential/activity', 'PreferentialController@preActivity');
	Route::get('v1/preferential/qrcode/show', 'PreferentialController@getpreUserQRcode');

	Route::get('v1/user/detail', 'UsersController@userDetail');
	Route::post('v1/user/special/register', 'UsersController@specialRegister');
	Route::post('v1/user/special/verify_code', 'UsersController@specialVerifyCode');
	Route::post('v1/feedback', 'UsersController@feedBack');
	Route::post('v1/user/reset/password', 'UsersController@resetPassword');
	Route::get('v1/special/user/detail', 'UsersController@specialUserDetail');
	Route::post('v1/user/detail', 'UsersController@modifyDetail');
	Route::post('v1/user/preferential/register', 'UsersController@preRegister');
	Route::post('v1/push/register', 'UsersController@deviceRegister');
	Route::get('v1/push/unregister', 'UsersController@deviceLogout');
	Route::get('v1/general/push/receive/payment', 'UsersController@pay2goPayment');
	Route::get('v1/qrcode/show', 'UsersController@getUserQRcode');
	Route::get('v1/recommend/show', 'UsersController@getRecommendList');
	Route::post('v1/general/recommend/set', 'UsersController@setUserRecommend');
	Route::post('v1/special/recommend/set', 'UsersController@setSpeRecommend');
	Route::post('v1/premium/recommend/set', 'UsersController@setPreRecommend');
	Route::get('v1/berecommend', 'UsersController@pushBeRecommend');
	Route::get('v1/recommend', 'UsersController@pushRecommend');
	Route::get('v1/check/user/identity', 'UsersController@checkUserIdentity');
	Route::post('v1/validate/identity/card', 'UsersController@validateIdCard');

	Route::post('v1/special/point', 'PointsController@specialBrowserPoint');
	Route::post('v1/general/bonus_point', 'PointsController@browsePointRecord');
	Route::post('v1/general/point/send', 'PointsController@sendPoints');
	Route::post('v1/general/point/receive', 'PointsController@receivePoints');
	Route::post('v1/special/point/send', 'PointsController@sendSpecialPoints');
	Route::post('v1/bounds/send', 'PointsController@waitPoints');
	Route::get('v1/special/point/check', 'PointsController@checkSepcialPoints');
	Route::post('v1/special/point/send/record', 'PointsController@browseSpecialSendPoints');
	Route::post('v1/special/point/phone/send', 'PointsController@spePhoneSendPoints');
	Route::post('v1/preferential/point',  'PointsController@preBrowserPoint');
	Route::post('v1/preferential/point/phone/send', 'PointsController@prePhoneSendPoints');
	Route::post('v1/preferential/point/send/record', 'PointsController@browsePreSendPoints');
	Route::post('v1/preferential/point/deduct', 'PointsController@sendPrePoints');

	Route::get('v1/store', 'StoresController@browseStore');
	Route::post('v1/general/shop/like', 'StoresController@userLikeShop');
	Route::post('v1/general/shop/like/cancel', 'StoresController@userCancelShop');
	Route::get('v1/store/save', 'StoresController@browseUserLikeShop');
	Route::get('v1/store/region', 'StoresController@browseStoreRegion');

	Route::post('v1/push/lucky', 'CommunityController@luckyGet');
	Route::get('v1/lucky/money', 'CommunityController@luckyMoney');
	Route::post('v1/lucky/send', 'CommunityController@luckySend');
	Route::post('v1/push/activity', 'CommunityController@activityPush');
	Route::post('v1/store/push/send', 'CommunityController@storeNotification');
	Route::get('v1/user/instruction', 'CommunityController@showInstruction');
	Route::get('v1/user/customer/service', 'CommunityController@showService');
	Route::get('v1/general/news', 'CommunityController@browseNews');
	Route::get('v1/general/news/{id}', 'CommunityController@readDetail')->where(['id' => '[0-9]+']);
	Route::get('v1/general/newsSpe', 'CommunityController@browseNewsSpe');
	Route::get('v1/general/newsSpe/{id}', 'CommunityController@readSpeDetail')->where(['id' => '[0-9]+']);
	Route::get('v1/general/newsPre', 'CommunityController@browseNewsPre');
	Route::get('v1/general/newsPre/{id}', 'CommunityController@readPreDetail')->where(['id' => '[0-9]+']);

});


