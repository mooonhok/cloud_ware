wx.ready(function () { 
// 9 ΢��ԭ���ӿ�  
  // 9.1.1 ɨ���ά�벢���ؽ��  
  document.querySelector('#scanQRCode0').onclick = function () {  
    wx.scanQRCode();  
  };  
  // 9.1.2 ɨ���ά�벢���ؽ��  
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