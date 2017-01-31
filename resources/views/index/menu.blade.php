    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">

        <a class="navbar-brand" href="/">BEHIGORRI PASSWORD MANAGER</a>
    </div>
    <font color="white">
        <?php $langs = ['en','eu','es'];
                $locale =App::getLocale(); ?>
        @foreach ($langs as $lang)
            @if($locale !== $lang)
                <a href="/{{$lang}}/">{{$lang}} </a>
            @else
                {{$lang}}
            @endif
        @endforeach
    </font>
    <!-- Top Menu Items -->
    <ul class="nav navbar-right top-nav">
        <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user"></i> {{ $user->getEmail() }} <b class="caret"></b></a>
            <ul class="dropdown-menu">
                <li>
                    <a href="/edit/profile"><i class="fa fa-fw fa-user"></i> {{ trans('translations.editprofile') }}</a>
                </li>
                <!--<li>
                    <a href="#"><i class="fa fa-fw fa-envelope"></i> Inbox</a>
                </li>
                <li>
                    <a href="#"><i class="fa fa-fw fa-gear"></i> Settings</a>
                </li>-->
                <li class="divider"></li>
                <li>
                    <a href="{{ route('logout') }}"><i class="fa fa-fw fa-power-off"></i> {{ trans('translations.logout') }}</a>
                </li>
            </ul>
        </li>
    </ul>

<!--
          <nav class="navbar navbar-default navbar-fixed-top">
          <div class="container">-->
          <!-- Brand and toggle get grouped for better mobile display -->
<!--
          <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed"
              data-toggle="collapse" data-target="#bs-example-navbar-collapse-1"
                  aria-expanded="false">
                  <span class="sr-only">Toggle navigation</span> <span
                  class="icon-bar"></span> <span class="icon-bar"></span> <span
                  class="icon-bar"></span>
                  </button>
                  <a class="navbar-brand" href="/">BEHIGORRI GOD MENU ({{ $user->getEmail() }})</a>
                  </div>
-->
                  <!-- Collect the nav links, forms, and other content for toggling -->
<!--
                  <div class="collapse navbar-collapse"
                      id="bs-example-navbar-collapse-1">
                      <ul class="nav navbar-nav">
                      <li><a href="{{ route('adminUser') }}">USER ADMINISTRATION<span class="sr-only">(current)</span></a></li>
                      <li><a href="{{ route('adminGroup') }}">GROUP ADMINISTRATION</a></li>
-->
                      <!-- <li class="dropdown"><a href="#" class="dropdown-toggle"
                          data-toggle="dropdown" role="button" aria-haspopup="true"
                              aria-expanded="false">Dropdown <span class="caret"></span></a>
                              <ul class="dropdown-menu">
                              <li><a href="#">Action</a></li>
                              <li><a href="#">Another action</a></li>
                              <li><a href="#">Something else here</a></li>
                              <li role="separator" class="divider"></li>
                              <li><a href="#">Separated link</a></li>
                              <li role="separator" class="divider"></li>
                              <li><a href="#">One more separated link</a></li>
                              </ul></li> -->
<!--
                              </ul>
-->
                              <!--  <form class="navbar-form navbar-left" role="search">
                              <div class="form-group">
                              <input type="text" class="form-control" placeholder="Search">
                              </div>
                              <button type="submit" class="btn btn-default">Submit</button>
                              </form>  -->
<!--
                              <ul class="nav navbar-nav navbar-right">
                              <li class="dropdown"><a href="#" class="dropdown-toggle"
                                  data-toggle="dropdown" role="button" aria-haspopup="true"
                                      aria-expanded="false">OPTIONS<span class="caret"></span></a>
                                      <ul class="dropdown-menu">
                                      <li><a href="/edit/profile">EDIT PROFILE</a></li>
                                      <li><a href="#">Another action</a></li>
                                      <li><a href="#">Something else here</a></li>
                                      <li role="separator" class="divider"></li>
                                      <li><a href="{{ route('logout') }}">Logout</a></li>
                                      </ul></li>
                                      </ul>
                                      </div>
-->
                                      <!-- /.navbar-collapse -->
<!--
                                      </div>
-->
                                      <!-- /.container-fluid -->
<!--
                                      </nav>

-->
