function _(x){
	return document.getElementById(x);
}

function toggleElement(x){
	var x = _(x);
	if(x.style.display == 'block'){
		x.style.display = 'none';
	}else{
		x.style.display = 'block';
	}
}

function tempAlert(msg,duration)
{
    var el = document.createElement("div");
    el.setAttribute("style","position:inharite;top:0;left:0;color:#3c763d;background-color:#dff0d8;border-color:#d6e9c6;padding:15px;margin-bottom:20px;border:1px solid transparent;border-radius:4px;text-align:center;");
    el.innerHTML = msg;
    setTimeout(function(){
        el.parentNode.removeChild(el);
    },duration);
    //window.scrollTo(0,0);
    document.getElementById('operationAlert').appendChild(el);
}

//Change mouse icon 
function setCursorByID(id,cursorStyle) {
    var elem;
    if (document.getElementById && (elem = document.getElementById(id)) ){
        if (elem.style) elem.style.cursor=cursorStyle;
    }
}
$(document).ready(function() {

});


$(document).ready(function() {
    
    var contestId= $("#jsonContestId").val();
    var currentDate, targetDate, timeDif;
	
	$.getJSON("time.php?contestId="+contestId,function(data){
		currentDate = data.currentTime;
		targetDate = data.targetTime;
		
		init();
	})
			
	function init(){
		
		var Days, Hours, Minutes, Seconds;
		timeDif = targetDate - currentDate;

		function updateTime(){
			Seconds = timeDif;
			
			Days = Math.floor(Seconds / 86400);
			Seconds -= Days * 86400;
			
			Hours = Math.floor(Seconds / 3600);
			Seconds -= Hours * 3600;
			
			Minutes = Math.floor(Seconds / 60);
			Seconds -= Minutes * 60;
			
			Seconds =Math.floor(Seconds);
		}
		
		function tick(){
			clearTimeout(timer);
			updateTime();
			displayTime();
			if (timeDif>0){
				timeDif--;
				timer = setTimeout(tick, 1 * 1000);
			}else{
				$("#timeDisplay").html("Timer Done !");
			}
		}
		
		function displayTime(){
			var out;
			out =   Days+" Days "+
					Hours+" Hours "+
					Minutes+" Minutes "+
					Seconds+" Seconds ";
			$("#timeDisplay").html(out);		
		}
		
		var timer = setTimeout(tick, 1 * 1000);
	}
	
});

