<!-- Sidebar Menu Items - These collapse to the responsive navigation menu on small screens -->
<div class="navbar-default sidebar" role="navigation">
    <div class="sidebar-nav navbar-collapse">
        <ul class="nav" id="side-menu">
          <li class="{{ Request::path()=='/' ? 'active' : '' }}" id="dashboard">
            <a href="/"><i class="fa fa-fw fa-dashboard"></i> {{ trans('translations.dashboard') }}</a>
          </li>
        </ul>
      </div>
  </div>
