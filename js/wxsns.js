/**
 * Created by Dong on 2015-08-01.
 */
//onerror = function (m) {alert(m)};

wx = window.wx || {}, wx.config = wx.config || {}, wx.user = wx.user || {};
wx.snsConfig = {
    codeUrl: 'https://open.weixin.qq.com/connect/oauth2/authorize?',
    tokenUrl: 'https://api.weixin.qq.com/sns/oauth2/access_token?&code=CODE&grant_type=authorization_code',
    refreshUrl: 'https://api.weixin.qq.com/sns/oauth2/refresh_token?appid=APPID&grant_type=refresh_token&refresh_token=REFRESH_TOKEN',
    userUrl: 'https://api.weixin.qq.com/sns/userinfo?lang=zh_CN'
};
wx.goCode = function (appId, redUrl) {
    //'https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx8082be7a0e0efd9e&redirect_uri=http%3A%2F%2Fnba.bluewebgame.com%2Fwx%2fdb.php&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect'//
    var url = wx.snsConfig.codeUrl + $.param({
            appid: appId,
            redirect_uri: redUrl
        }) + "&response_type=code&scope=snsapi_base&state=STATE#wechat_redirect";//appid="+appId+"&redirect_uri="+encodeURIComponent(redUrl);
    //alert(url)
    if (navigator.userAgent.match(/micromessenger/ig)) location.replace(url);
};
wx.getToken = function (appId, secret, fn) {
    var url = wx.snsConfig.tokenUrl + "&" + $.param({code: getParam("code"), appid: appId, secret: secret});
    $.getJSON(url, function (data) {
        if (data.access_token) {
            wx.config.token = data.access_token;
            wx.user.openid = data.openid;
            document.cookie = 'token=' + data.access_token + '&openid=' + data.openid;
        }
        fn.call(this, data);
    });
};
wx.getUser = function (fn) {
    var url = wx.snsConfig.userUrl + "&" + $.param({access_token: wx.config.token, openid: wx.user.openid});
    $.getJSON(url, function (data) {
        if (data.nickname)$.extend(wx.user, data);
        fn.call(this, data);
    });
};