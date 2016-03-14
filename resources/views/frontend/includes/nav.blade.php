<nav class="navbar navbar-default">
    <div class="container">
        <div class="navbar-header">

            <!-- Collapsed Hamburger -->
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#frontend-navbar-collapse">
                <span class="sr-only">{{ trans('labels.general.toggle_navigation') }}</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>

            <!-- Branding Image -->
            <a class="navbar-brand" href="{!! route('frontend.index') !!}">
                Droview
            </a>
        </div><!--navbar-header-->

        <div class="collapse navbar-collapse" id="frontend-navbar-collapse">
            <!-- Right Side Of Navbar -->
            <ul class="nav navbar-nav navbar-right">

                <!-- Authentication Links -->
                @if (Auth::guest())
                    <li id="login">{!! link_to('login', trans('navs.frontend.login')) !!}</li>
                    <li id="register">{!! link_to('register', trans('navs.frontend.register')) !!}</li>
                @else
                    
                    <li class="dropdown">
                        <a href="#" id="dropdown-toggle-user" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                            {{ Auth::user()->name }} <span class="caret"></span>
                        </a>

                        <ul class="dropdown-menu" role="menu">
                            <li><a href="droview/reserved" id="myPage">My Page</a></li>
                            @permission('view-backend')
                                <li id="toAdminDashboard">{!! link_to_route('admin.single.dashboard', trans('navs.frontend.user.administration')) !!}</li>
                            @endauth

                            <li>{!! link_to_route('auth.logout', trans('navs.general.logout')) !!}</li>
                        </ul>
                    </li>
                @endif

            </ul>
        </div><!--navbar-collapse-->
    </div><!--container-->
</nav>