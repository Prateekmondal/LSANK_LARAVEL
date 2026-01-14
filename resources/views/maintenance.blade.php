        <!DOCTYPE html>
        <html>
        <head>
        <title>Website Down for Maintenance</title>
	<style>
@font-face {
    font-family:'Open Sans';font-style:normal;font-weight:300;
    src: local('Open Sans Light'), local('OpenSans-Light'),
    url('fonts/open-sans-300.woff2') format('woff2'),
    url('fonts/open-sans-300.woff') format('woff');
}
@font-face {
    font-family:'Open Sans';font-style:normal;font-weight:400;
    src: local('Open Sans'), local('OpenSans'),
    url('fonts/open-sans-regular.woff2') format('woff2'),
    url('fonts/open-sans-regular.woff') format('woff');
}
a,body,div,footer,h1,h2,h3,header,html,img,span{margin:0;padding:0;border:0;font:inherit;vertical-align:baseline}
article,aside,footer,header,nav,section{display:block}
html,body{height:100%;min-height:100%}
html { font-size:62.5%; }
body{
    font-family: "Open Sans", "Helvetica Neue", Helvetica, sans-serif;
    font-size: 14px;
    font-weight: 300;
    color: #fff;
    background: url(https://i.imgur.com/9jfUwKV.jpg) no-repeat;
    background-size: cover;
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    -webkit-box-orient: vertical;
    -webkit-box-direction: normal;
    -ms-flex-direction: column;
    flex-direction: column;
    -webkit-box-align: center;
    -ms-flex-align: center;
    align-items: center;
}
body:after {
    content: "";
    position: fixed;
    top: 0;
    right: 0;
    bottom: 0;
    left: 0;
    background: rgba(0,0,0,.4);
    z-index: -1;
}
h1, h2 { margin: 16px 0; }
h1 { font-size: 24px; }
h2 { color: rgba(255,255,255,.85); }
.container {
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    -ms-flex-flow: column;
    flex-flow: column;
    -webkit-box-flex: 1;
    -ms-flex: 1 0 auto;
    flex: 1 0 auto;
    max-width: 640px;
    padding: 0 20px;
}
.header { margin: auto 0 16px; }
.timer {
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    margin: 0 0 auto;
    font-size: 50px;
    line-height: 1;
}
.timer__type {
    margin-top: 10px;
    font-size: 14px;
    text-align: center;
    color: rgba(255,255,255,.5);
}
.social-links {
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    margin: auto 0 50px;
}
.social-links__link { margin: 50px 24px 0 0; }
.icon {
    display: block;
    width: 32px;
    height: 32px;
    opacity: .7;
}
.social-links__link:hover .icon { opacity: 1; }
.icon > img {
    display: block;
    width: 100%;
    height: 100%;
}
.footer {
    width: 100%;
    background: rgba(0,0,0,.5);
}
.footer__content {
    margin: auto;
    padding: 0 40px;
    max-width: 1200px;
    text-align: right;
    color: rgba(255,255,255,.5);
}
.footer .logo {
    height: 50px;
    vertical-align: middle;
    margin: 0 0 0 10px;
}
@media (min-width: 500px) {
    .timer { font-size: 80px; }
    .social-links { margin-bottom: 110px; }
}
@media (min-width: 600px) {
    .timer { font-size: 100px; }
}
@media (min-width: 740px) {
    .timer { font-size: 120px; }
}
</style>
        </head>
        <body>

    <div class="container">

    <header class="header">
      
    <h1>The website is migrated to <a href='http://10.205.64.4:8080'>10.205.64.4:8080</a></h1>

    </header>

    <!--START_TIMER_BLOCK-->
      
         <div>
        <p>Days: <span id="timerResultDays"></span></p>
        <p>Hours: <span id="timerResultHours"></span></p>
        <p>Minutes: <span id="timerResultMinutes"></span></p>
        <p>Seconds: <span id="timerResultSeconds"></span></p>
    </div>
        <!--END_TIMER_BLOCK-->
</div>

<footer class="footer">
    <div class="footer__content">
        giorgioGTelian    </div>
</footer>

<script>
// a timer thingy
var startTimer = function(days, hours, minutes, startTime, timeShift) {
    var currentDateInSeconds = Math.floor(Date.now() / 1000);
    var endTime = startTime + (days * 86400) + (hours * 3600) + (minutes * 60);
    var diff = Math.max(0, endTime - currentDateInSeconds - timeShift);

    displayTimerValues(diff);

    if (0 < diff) {
        setTimeout(function() {
            startTimer(days, hours, minutes, startTime, timeShift);
        }, 1000);
    }
};

var displayTimerValues = function (diff) {
    var currentDays = Math.floor(diff / 86400);
    diff -= currentDays * 86400;

    var currentHours = Math.floor(diff / 3600) % 24;
    diff -= currentHours * 3600;

    var currentMinutes = Math.floor(diff / 60) % 60;
    diff -= currentMinutes * 60;

    var seconds = diff % 60;
    fillTimerValue("timerResultDays", currentDays);
    fillTimerValue("timerResultHours", currentHours);
    fillTimerValue("timerResultMinutes", currentMinutes);
    fillTimerValue("timerResultSeconds", seconds);
};

var fillTimerValue = function(elementName, value) {
    var element = document.getElementById(elementName);
    if (element) {
        element.innerHTML = (value < 10) ? "0" + value : value;
    }
};

const starttime=new Date("2025-09-24")/1000;

 startTimer(1, 0, 0, starttime, 10); 
</script>

</body>
        </html>