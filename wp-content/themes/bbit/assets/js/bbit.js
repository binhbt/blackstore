function progress(e,t){t.find("span").animate({width:e+"%"},700)}jQuery(document).ready(function(){jQuery(".review-percentage .review-item span").each(function(){$g=jQuery(this).find("span").attr("data-width");progress($g,jQuery(this))});jQuery(document).on("mousemove",".user-rate-active",function(e){var t=jQuery(this);if(t.hasClass("rated-done")){return false}if(!e.offsetX){e.offsetX=e.clientX-jQuery(e.target).offset().left}var n=e.offsetX+4;if(n>100){n=100}t.find(".user-rate-image span").css("width",n+"%");var r=Math.floor(n/10*5)/10;if(r>5){r=5}});jQuery(document).on("click",".user-rate-active",function(){var e=jQuery(this);if(e.hasClass("rated-done")){return false}var t=e.find(".user-rate-image span").width();e.find(".user-rate-image").hide();e.append('<span class="taq-load"></span>');if(t>100){t=100}ngg=t*5/100;var n=e.attr("data-id");var r=e.parent().find(".taq-count").text();jQuery.post(taqyeem.ajaxurl,{action:"taqyeem_rate_post",post:n,value:ngg},function(n){e.addClass("rated-done").attr("data-rate",t);e.find(".user-rate-image span").width(t+"%");jQuery(".taq-load").fadeOut(function(){e.parent().find(".taq-score").html(ngg);if(jQuery(e.parent().find(".taq-count")).length>0){r=parseInt(r)+1;e.parent().find(".taq-count").html(r)}else{e.parent().find("small").hide()}e.parent().find("strong").html(taqyeem.your_rating);e.find(".user-rate-image").fadeIn()})},"html");return false});jQuery(document).on("mouseleave",".user-rate-active",function(){var e=jQuery(this);if(e.hasClass("rated-done")){return false}var t=e.attr("data-rate");e.find(".user-rate-image span").css("width",t+"%")})})