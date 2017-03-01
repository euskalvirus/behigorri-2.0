<!-- Sidebar Menu Items - These collapse to the responsive navigation menu on small screens -->
  <div class="navbar-default sidebar navbar-inverse navbar-collapse collapse" role="navigation" id="sidebar">
      <div class="nav navbar-nav side-nav">
        <ul class="nav" id="side-menu">
          <li class="{{ Request::path()=='/' ? 'active' : '' }}" id="dashboard">
            <a href="/"><i class="fa fa-fw fa-dashboard"></i> {{ trans('translations.dashboard') }}</a>
          </li>
        </ul>
      </div>
      <!-- /.sidebar-collapse -->
  </div>
  <!-- /.navbar-static-side -->
