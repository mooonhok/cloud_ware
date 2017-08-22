wx.ready(function () { 
// 9 微信原生接口  
  // 9.1.1 扫描二维码并返回结果  
  document.querySelector('#scanQRCode0').onclick = function () {  
    wx.scanQRCode();  
  };  
  // 9.1.2 扫描二维码并返回结果  
  document.querySelector('#scanQRCode1').onclick = function () {  
    wx.scanQRCode({  
      needResult: 1,  
      desc: 'scanQRCode desc',  
      success: function (res) {  
        alert(JSON.stringify(res));  
      }  
    });  
  };  
});  
  
wx.error(function (res) {  
  alert(res.errMsg);  
});  