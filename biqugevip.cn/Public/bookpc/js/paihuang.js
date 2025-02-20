$(function(){
$(".weekly-list li").bind("mouseenter",weekly_ani).bind("mouseleave",function(){clearTimeout($(this).data("setTime"));});
function weekly_ani(e){
var me=$(e.target).closest("li");
if(me.hasClass("current")) return;
var orili=me.parent().find(".current");
$(this).data("setTime",setTimeout(function(){weekly_move(me,orili,100,30)},150));
}
function weekly_move(me,orili,h,h2){
me.addClass("current");
$(".weekly-list li").unbind("mouseenter",weekly_ani);
setTimeout(function(){
var cur_h=me.height();
if(cur_h < h-2){
var cur_orih=orili.height();
var dh=Math.round((h-cur_h)/2.5);
me.css("height",cur_h+dh);
orili.css("height",cur_orih-dh);
setTimeout(arguments.callee,25);
}
else{
me.addClass("current").css("height",h);
orili.css("height",h2);
$(".weekly-list li").bind("mouseenter",weekly_ani);
orili.removeClass("current");
}
},25);
}
$(".weekly-list").find("li:first").addClass("current").animate({height:100}, 300);
});