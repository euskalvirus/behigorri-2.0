<!-- Brand and toggle get grouped for better mobile display -->
<div class="navbar-header">
  <a class="navbar-brand" href="/">BEHIGORRI PASSWORD MANAGER</a>
</div>
<?php $langs =Config::get('app.locales');
$locale =App::getLocale(); ?>
<!-- Top Menu Items -->
<div>
<ul class="nav navbar-right top-nav">
  <li class="dropdown">
    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-letter"></i> {{ trans('translations.selectLanguage') }} <b class="caret"></b></a>
    <ul class="dropdown-menu">

        @foreach ($langs as $shortLang => $lang)
        @if($locale !== $shortLang)
        <li>
          <a href="/{{$shortLang}}/"><i class="fa fa-fw fa-language "></i> {{trans('translations.'.$lang )}}</a>
        </li>
        @else
        <li class="disabled">
            <a> <i class="fa fa-fw fa-language "></i> {{trans('translations.'.$lang )}}</a>
        </li>
        @endif
        @endforeach
  </ul>
  </li>
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
<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
    <span class="sr-only">Toggle navigation</span>
    <span class="icon-bar"></span>
    <span class="icon-bar"></span>
    <span class="icon-bar"></span>
  </button>
</ul>
</div>
