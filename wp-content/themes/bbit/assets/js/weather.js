(function(e,t,n){e(document).ready(function(){numItems=e(".bonobo_widget .days").length;next=parseInt(e(".bonobo_widget .right_navigation a").attr("rel"));prev=next-2;if(prev>=numItems)e(".right_navigation").addClass("invisible");if(prev<=1)e(".left_navigation").addClass("invisible");for(var t=1;t<=numItems;t++){e(".bonobo_widget .day_"+t).attr("style","min-height:"+e(".bonobo_widget .day_1").height()+"px;")}e(".bonobo_widget .right_navigation a").click(function(t){t.preventDefault();id=e(this).parent().parent().attr("id");numItems=e("#"+id+" .days").length;next=parseInt(e("#"+id+" .right_navigation a").attr("rel"));prev=parseInt(e("#"+id+" .left_navigation a").attr("rel"));current=next-1;e("#"+id+" .day_"+next).removeClass("invisible");e("#"+id+" .day_"+current).addClass("invisible");next=next+1;prev=prev+1;e("#"+id+" .right_navigation a").attr("rel",next);e("#"+id+" .left_navigation a").attr("rel",prev);if(prev>=1)e("#"+id+" .left_navigation").removeClass("invisible");if(next>numItems)e("#"+id+" .right_navigation").addClass("invisible")});e(".bonobo_widget .left_navigation a").click(function(t){t.preventDefault();id=e(this).parent().parent().attr("id");numItems=e("#"+id+" .days").length;next=parseInt(e("#"+id+" .right_navigation a").attr("rel"));prev=parseInt(e("#"+id+" .left_navigation a").attr("rel"));current=prev+1;e("#"+id+" .day_"+current).addClass("invisible");e("#"+id+" .day_"+prev).removeClass("invisible");next=next-1;prev=prev-1;e("#"+id+" .right_navigation a").attr("rel",next);e("#"+id+" .left_navigation a").attr("rel",prev);if(prev<1)e("#"+id+" .left_navigation").addClass("invisible");if(next<=numItems)e("#"+id+" .right_navigation").removeClass("invisible")})})})(jQuery,window)