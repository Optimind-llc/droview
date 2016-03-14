@extends('frontend.layouts.master')

@section('content')
<!--header section -->
<section class="banner" role="banner">
  <div class="banner-area"> 
    <!-- overlay -->
    <div class="banner-area-gradient"></div>
    <div class="inner-bg">
      <div class="container">
        <div class="col-md-10 col-md-offset-1">
          <div class="banner-text text-center">
          	<div class="main_logo"></div>
            <!--Countdown --> 
            <a href="#subscribes" class="btn">DEMO</a>
            </p>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<!--header section --> 
<!--intro section -->
<section id="intro" class="section intro">
  <div class="container">
    <div class="row">
        <div class="intro-content center-box">
		    <div id="conept">
				<h3>家族みんなが集まりたくなる<br>「家族ポータルメディア」を創造する</h3>
		    </div>
        </div>        
    </div>
    <div class="row">
        <div class="concept_img_wrap">
		    <img class="concept_img" src="/img/concept.svg"/>
		    <p id="owner">観光資源の保有者</p>
		    <p id="user">ユーザー</p>
        </div>        
    </div>
    <div class="row">
		<iframe
			width="560"
			height="315"
			src="https://www.youtube.com/embed/Tt5s2iqDHY4?rel=0&showinfo=0&modestbranding=0"
			frameborder="0"
			allowfullscreen>
		</iframe>
    </div>
  </div>
</section>
<!--intro section --> 
<!--subscribe section -->
<section id="subscribes" class="subscribe">
  <div class="container">
    <div class="row">
      <div class="col-sm-12 col-md-5 subscribe-title">
        <h2>Subscribe now.</h2>
      </div>
      
      <!-- subscribe form -->
      <div class="col-sm-12 col-md-7 subscribe-form"> 
               
        <form method="post" action="php/subscribe.php" name="subscribeform" id="subscribeform">
          <input type="text" name="email" placeholder="Enter your email address to get notified" id="subemail" />
          <input type="submit" name="send" value="Notify me" id="subsubmit" class="btn2" />
        </form>
        
        <!-- subscribe message -->
        <div id="mesaj"></div>
        <!-- subscribe message --> 
      </div>
      <!-- subscribe form --> 
    </div>
  </div>
</section>
<!--subscribe section --> 

<!-- Footer section -->
<footer class="footer">
  <div class="container">
    <div class="row">
      <div class="footer-col col-md-5">
        <p>Copyright © 2015 Orly Inc. All Rights Reserved.</p>
        <p> Made with <i class="fa fa-heart pulse"></i> by <a href="http://www.designstub.com/">Designstub</a></p>
      </div>
      <div class="footer-col col-md-7">
        <ul class="footer-share">
          <li><a href="#"><i class="fa fa-facebook"></i></a></li>
          <li><a href="#"><i class="fa fa-twitter"></i></a></li>
          <li><a href="#"><i class="fa fa-linkedin"></i></a></li>
          <li><a href="#"><i class="fa fa-google-plus"></i></a></li>
        </ul>
      </div>
    </div>
  </div>
  <!-- footer top --> 
  
</footer>
<!-- Footer section -->

@endsection