<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/2000/REC-xhtml1-20000126/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml"><head>





	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<META NAME="description" CONTENT="This event is a simulation of the product development life cycle in the Open Source World. Since there are no offices, neither are there employ- ees, development takes place online. People round the world download the source tarballs, add their flavour to it and submit it back. Some one else downloads his code, add his touch to the product and re-submit along with due credits to the original author. In return what do they get..? - INSTANT PUBLICITY. Your name propagates to every nook and corner of this world. You become instantly popular. There's no money gambling here, there's no secrecy, every thing is wide open and it the individual's talent which makes them a hero or a zero.">
<META NAME="keywords" CONTENT="Kurukshetra , K++, kplusplus, Open Source, Software Development Event, Open Source Software Development, Open Source Event, Programming Contest, Bug Report, Bug, Coding Event, Linux">

<META NAME="robots" CONTENT="index,nofollow">
<META NAME="GOOGLEBOT" CONTENT="INDEX, NOFOLLOW">




<title>K++ Think Open!! - Kurukshetra Open Source Software Development Event</title> 



	<link href="menu.css" rel="stylesheet" type="text/css" media="screen" />

	<link href="kstyle.css" rel="stylesheet" type="text/css" media="screen" />

<script type="text/javascript" src="script/mootools.js"></script>

<script type="text/javascript" src="script/ajaxScripts.js"></script>

<script type="text/javascript" src="script/menu.js"></script>

<script type="text/javascript" src="script/ubr_file_upload.js"></script>

	<!-- START Fx.Slide -->

	<!-- The CSS -->

  	<link rel="stylesheet" href="fx_slide.css" type="text/css" media="screen" />

	<!-- END Fx.Slide -->

  	<link rel="stylesheet" href="leftmenu.css" type="text/css" media="screen" />

 	<link rel="stylesheet" href="comments.css" type="text/css" media="screen" />

  	<link rel="stylesheet" href="tablecloth.css" type="text/css" media="screen" />

  	<script type="text/javascript" src="script/tablecloth.js"></script>

<script type="text/javascript">

var current='home';
var ktimer_h=0,ktimer_m=0,ktimer_s=0;
var start_h=0;
var KScore=0;
function getKserverTime()
{

var req = new Ajax('time_score.php',{method:'post',
			onComplete: function(ResTime){
			 times=ResTime.split(":");
			 ktimer_h=parseInt(times[0]);
			 ktimer_m=parseInt(times[1]);
			 ktimer_s=parseInt(times[2]);
			 KScore=parseInt(times[3]);
			 //alert(times);
			 start_h=ktimer_h;
			 $('KScore').innerHTML=KScore;
			 //alert(ktimer_h+" : "+ktimer_m+" : "+ktimer_s);
			 },
			evalScripts: true}).request();
updateTime();
}
function checkTime(i)
{
if (i<10)
  {
  i="0" + i;
  }
  
return i;
}


function updateTime()
{
ktimer_s++;
if(ktimer_s==60)
{
ktimer_s=0;
ktimer_m++;
	if(ktimer_m==60)
	{
	ktimer_m=0;
	ktimer_h=(ktimer_h+1)%24;
	}
}

//$('KTime').innerHTML="";
//$('KTime').innerHTML=checkTime(ktimer_h)+" : "+checkTime(ktimer_m)+" : "+checkTime(ktimer_s);
if(Math.abs(ktimer_h-start_h)==2)
	getKserverTime();
else
	t=setTimeout("updateTime()",1000);

}



 window.addEvent('domready', function() {

	 

	if($('fancymenu'))

    FancyExample = new SlideList($E('ul', 'fancymenu'), {

          transition: Fx.Transitions.Back.easeOut, 

          duration: 800, 

          onClick: function(ev, item) { /*ev.stop();document.location.href=item.href;*/ }});

	getKserverTime();

}); /*end dom-ready*/



</script>



</head>



<body onLoad="document.getElementById('spinner').style.visibility='hidden';">



     <div id="container">

		<script type="text/javascript">loadLogin()</script>

</div>

            <div id="menu">

              <div id="nav">

               <div id="fancymenu">

                  <ul>

	<li class="current menuItem"><a href="#" class="active" onClick="goHome()">-- Home --</a></li>
	<li class="current menuItem"><a href="#" class="active" onClick="problemStatement()">-- Problem Statement --</a></li>

	<li class="menuItem"><a href="http://www.kurukshetra.org.in" >-- K! Home -</a></li>

	<li class="menuItem"><a href="#" onClick="displayAllScores();" >-- Score Card --</a></li>

<!--	<li class="menuItem"><a href="#" onClick="contactUs();" >-- Contact Us --</a></li> -->
	<li class="menuItem"><a href="#" onClick="aboutUs();" >-- Contact Us --</a></li>

                 </ul>

                 </div>

             </div>

</div>



<p class="alignright">
    <div style="height: 111px; background: transparent url('images/k_logo_latest.png') no-repeat left center;"><img align="right" style="float: right;" src="images/logo.png"/></div><br>
</p> 

<h1>Kurukshetra Open Source Software Development Event</h1>
<p>
<span style="text-align:center; letter-spacing: 4px; color: #089DEA; font-weight: bold; font-size: 17px;">
<!-- K++ Time : &nbsp;
	<span  id="KTime"></span>&nbsp;&nbsp;-->
K++ Score : &nbsp;
	<span id="KScore"></span>
</span>	
</p>
<div id="KMessageContainer" style="background: transparent; border: 1px solid #089DEA; margin: 20px; padding: 15px; padding-left: 10px; padding-right: 10px;">
<span id="KMessage" style="font-size: 14px; font-weight: 600; font-family:  Sans-Serif, SunSans-Regular, Verdana; letter-spacing: 2px; color: #FAFAFA; text-align: center;">
Phase II Started. Download the updated based code and start working on it!!
</span>
</div>
<div id="error" style="background-color: #ced; color: black; margin: 20px; padding-left: 10px; padding-right: 10px;">
</div>

<br>
<div id='spinner' style="height: 20px; background: transparent url('images/activity.gif') no-repeat center;"></div>

<div id="right">

<div id="NewsTicker">

  <h1>Updates</h1>

	<div id="NewsVertical">

	  <ul id="TickerVertical">
 <li>

            <span class="NewsTitle">

            <a href="#" onClick="downloadBaseCode()">Phase II Is ON</a>

            </span>
	* Phase I of K++ ends.<br>* Check out the Updated Base Code!!<br>(with Bug Patches submitted in PhaseI)<br>* Start working on it.<br>* Phase II of K++ is ON!!<br>
    <span class="NewsFooter"><strong>Published Jan 02</strong></span>
    </li>

	  <li>

         <span class="NewsTitle">

            <a href="#" onClick="problemStatement()">Problem Statement</a>

            </span>
            A Music Player that takes care of the common man's needs. <br>That is, in addition to playing common formats it can have a Music Library, Playlists, Keyboard Hotkeys, Web Plugins and think. 

           <span class="NewsFooter"><strong>Published Dec 15</strong></span>

        </li>
	  

        <li>

            <span class="NewsTitle">

            <a href="#" onClick="howToPlay()">How To Play</a>
            </span>
                * Register at the above portal and get your unique login.<br>
    * Download the base application we provide you with (unstable).<br>
    * Play, tinker with it.<br>* Read and Develop the code on your own...
    <span class="NewsFooter"><strong>Published Dec 15</strong></span>
    </li>

        <li>

         <span class="NewsTitle">

            <a href="#" onClick="scoringSystem()">Scoring System</a>
            </span>
           The Scoring System is Time based.<br>
At every phase the current K Score is:<br>
* Initially, 300 points are given.<br>
* Every two hours one point gets reduced.<br>
* Next phase starts again with 300 points.

           <span class="NewsFooter"><strong>Published Dec 15</strong></span>

        </li>

       

    </ul>

    </div>



</div>

</div>

		<script language="javascript" type="text/javascript">



			var Ticker = new Class({

				setOptions: function(options) {

					this.options = Object.extend({

						speed: 1500,

						delay: 5000,

						direction: 'vertical',

						onComplete: Class.empty,

						onStart: Class.empty

					}, options || {});

				},

				initialize: function(el,options){

					this.setOptions(options);

					this.el = $(el);

					this.items = this.el.getElements('li');

					var w = 0;

					var h = 0;

					if(this.options.direction.toLowerCase()=='horizontal') {

						h = this.el.getSize().size.y;

						this.items.each(function(li,index) {

							w += li.getSize().size.x;

						});

					} else {

						w = this.el.getSize().size.x;

						this.items.each(function(li,index) {

							h += li.getSize().size.y;

						});

					}

					this.el.setStyles({

						position: 'absolute',

						top: 0,

						left: 0,

						width: w,

						height: h

					});

					this.fx = new Fx.Styles(this.el,{duration:this.options.speed,onComplete:function() {

						var i = (this.current==0)?this.items.length:this.current;

						this.items[i-1].injectInside(this.el);

						this.el.setStyles({

							left:0,

							top:0

						});

					}.bind(this)});

					this.current = 0;

					this.next.bind(this).delay(this.options.delay+this.options.speed);

				},

				next: function() {

					this.current++;

					if (this.current >= this.items.length) this.current = 0;

					var pos = this.items[this.current];

					this.fx.start({

						top: -pos.offsetTop,

						left: -pos.offsetLeft

					});

					this.next.bind(this).delay(this.options.delay+this.options.speed);

				}

			});



			var vert = new Ticker('TickerVertical',{speed:2500,delay:6000,direction:'vertical'});

		</script>

<div id="left">

<script type="text/javascript"> loadLeftPannel()</script>

</div>



<div id="content">

<p>

<span class="blue">K++ is Kurukshetra Open Source Software Development Event</span><br><br>



<span class="white">Come, let us give life to software!!</span><br>



This event is a simulation of the product development life cycle in the

Open Source World. Since there are no offices, neither are there employ-

ees, development takes place online. People round the world download the

source tarballs, add their avor to it and submit it back. Some one else

downloads his code, add his touch to the product and re-submit along with

due credits to the original author. In return what do they get..? - INSTANT

PUBLICITY. Your name propagates to every nook and corner of this world.

You become instantly popular. There's no money gambling here, there's no

secrecy, every thing is wide open and it the individual's talent which makes

them a hero or a zero.</p>



<p>It is an online event and spans 40 time-crunching days where people from all over the world will be developing the software, the Open way!</p>

<p>The event consists of 3 phases.</p>

<p>At the start phase 1, participants will be given a buggy, unfinished base code along with a Problem Statement and at the start of other phases, an upgraded version of the base application will be released. This encourages new participants to start afresh even in the middle of the event.

</p>

</div>

<br>

</body>

</html>