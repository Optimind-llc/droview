<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="_token" content="{!! csrf_token() !!}" />
    <title>@yield('title', app_name())</title>
    <meta name="description" content="@yield('meta_description', 'Default Description')">
    <link rel="stylesheet" href="/css/video-js.min.css"/>
    <script src="/js/videojs.bundle.js"></script>
    <style type="text/css">

#left{
    position: absolute;
    top: 480px;
    left: 280px;
    width: 50px;
    height: 50px;
    -webkit-border-radius: 25px;/* width,heightの半分 */
    -moz-border-radius: 25px;
    border-radius: 25px;
}
#right{
    position: absolute;
    top: 480px;
    left: 680px;
    width: 50px;
    height: 50px;
    -webkit-border-radius: 25px;/* width,heightの半分 */
    -moz-border-radius: 25px;
    border-radius: 25px;
}
img{
    transform: rotate(-90deg);
    height: 70px;
    position: absolute;
    top: 470px;
    left: 480px;
}

.active{
    background-color: red;
}

.sleep{
    background-color: #999;/* 円の色 */

}
</style>


  </head>


  <body>

    <div>
        <div id="left" class="sleep"></div>
        <img src="/img/car.jpg">
        <div id="right" class="sleep"></div>
    </div>

<script src="https://cdn.socket.io/socket.io-1.3.7.js"></script>



<video id="player" class="video-js vjs-default-skin" style="text-align:center;"></video>



<script>
    (function(w) {
        var videojs = w.videojs;
        var player, sendBeacon;


        videojs('player', {width: 640, height: 400,controls: preload="auto", techOrder: ['hlsJs', 'hls', 'flash', 'html5']}, function() {
            player = this;
            player.controls(true);
            player.src({
                type: 'application/x-mpegURL',
                src: 'http://188.166.211.50:8080/hls/stream.m3u8'
            });
            player.on('loadedmetadata', function() {
                player.ima.requestAds(); // Play ads when loaded
                player.play();
            })
        });
    })(window);
</script>



<script>

var token = "rpi-customer"
console.log("************************* restart client2**********************")

var socket = io.connect("http://188.166.211.50:3000", {
    'force new connection': true,
    'reconnection': true,
    'reconnectionDelay': 1000,
    'reconnectionDelayMax' : 5000,
    'reconnectionAttempts': 5
})



socket.on('disconnect', function() {
    console.info('SOCKET [%s] DISCONNECTED', socket.id);
});

socket.on('connect', function() {
    console.log('connect');
    socket.on(token, function(data) {
        jwtToken = data
    })
    setTimeout(function() {
        socket.emit('authenticate', {
            token: jwtToken
        });
    }, 300);
});









var newMessage = {
id: Date.now(),
control: "right",
led: "on",
time: "strftime('%H:%M %p', new Date())"
};
socket.emit('c send cmsg to rpi at c', newMessage);




    document.onkeydown = function (e){
console.log(e.which);

        if (e.which == 37) {
                                    document.getElementById("left").className="active";
                                    var newMessage = {
                                    id: Date.now(),
                                    control: "right",
                                    led: "on",
                                    time: "strftime('%H:%M %p', new Date())"
                                    };
                                    socket.emit('c send cmsg to rpi at c', newMessage);
        }

        if (e.which == 39) {
                                    document.getElementById("right").className="active";
                                    var newMessage = {
                                    id: Date.now(),
                                    control: "left",
                                    led: "on",
                                    time: "strftime('%H:%M %p', new Date())"
                                    };
                                    socket.emit('c send cmsg to rpi at c', newMessage);
        }

    };

    document.onkeyup = function (e){
        if (e.which == 37) {
            document.getElementById("left").className="sleep";

                                    var newMessage = {
                                    id: Date.now(),
                                    control: "right",
                                    led: "off",
                                    time: "strftime('%H:%M %p', new Date())"
                                    };
                                    socket.emit('c send cmsg to rpi at c', newMessage);
        }

        if (e.which == 39) {
            document.getElementById("right").className="sleep";

                                    var newMessage = {
                                    id: Date.now(),
                                    control: "left",
                                    led: "off",
                                    time: "strftime('%H:%M %p', new Date())"
                                    };
                                    socket.emit('c send cmsg to rpi at c', newMessage);

        }
    };
    </script>

  </body>
</html>

