<!-- Brand and toggle get grouped for better mobile display -->
<div class="navbar-header">
  <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
    <span class="sr-only">Toggle navigation</span>
    <span class="icon-bar"></span>
    <span class="icon-bar"></span>
    <span class="icon-bar"></span>
  </button>
  <a class="navbar-brand" href="/">BEHIGORRI PASSWORD MANAGER</a>
</div>
<?php $langs =Config::get('app.locales');
$locale =App::getLocale(); ?>
<!-- Top Menu Items -->
<ul class="nav navbar-right top-nav">
  <li class="dropdown">
    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user"></i> {{ $user->getEmail() }} <b class="caret"></b></a>
    <ul class="dropdown-menu">
      <li>
        <a href="/edit/profile"><i class="fa fa-fw fa-user"></i> {{ trans('translations.editprofile') }}</a>
      </li>
  <li class="divider"></li>
  <li>
    <a href="{{ route('logout') }}"><i class="fa fa-fw fa-power-off"></i> {{ trans('translations.logout') }}</a>
  </li>
</ul>
</li>
</ul>
<ul class="nav navbar-right top-nav">
  <li class="dropdown">
    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user"></i> {{ trans('translations.selectLanguage') }} <b class="caret"></b></a>
    <ul class="dropdown-menu">

        @foreach ($langs as $shortLang => $lang)
        @if($locale !== $lang)
        <li>
          <a href="/{{$shortLang}}/"><i class="fa fa-fw fa-language "></i> {{trans('translations.'.$lang )}}</a>
        </li>
        @else
        <li>
            <a> <i class="fa fa-fw fa-language "></i> {{$lang}}</a>
        </li>
        @endif
        @endforeach
</ul>
</li>
</ul>
