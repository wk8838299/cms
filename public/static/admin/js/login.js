layui.use('layer', function(){ 
	window.layer = layui.layer;
});
$(document).ready(function(){
	if(getCookie('username')){
		$('input[name="username"]').val(getCookie('username'));
		$("#save_me").prop('checked',true);
	}
})

$(document).ajaxStart(function(){
	$("#button").val('登录中...').attr('disabled', true);
}).ajaxStop(function(){
	$("#button").val('登录').attr('disabled', false);
})
function success(data){
	if(data.code){
		layer.msg(data.msg,{time:1000});
		window.location.href = data.url;
	}else{
		$(".errors").text(data.msg);
	}
}
$("#save").click(function(){
	if($('#save_me').prop('checked')){
		$('#save_me').prop('checked',false);
		delCookie('username');
	}else{
		$('#save_me').prop('checked',true);
		addCookie('username', $('input[name="username"]').val(),168);
	}
})
$("#button").click(function () {
	var self = $('form');
	$.post(self.attr("action"), self.serialize(),success, "json");
	return false;
});
