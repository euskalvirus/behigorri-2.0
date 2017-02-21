<div class="navbar-default sidebar navbar-inverse navbar-collapse collapse" role="navigation" id="sidebar">
    <div class="nav navbar-nav side-nav">
        <ul class="nav" id="side-menu">
          <li class="{{ Request::path()=='/' ? 'active' : '' }}" id="dashboard">
              <a href="/"><i class="fa fa-fw fa-table"></i> {{ trans('translations.dashboard') }}</a>
          </li>
          <li class="{{ Request::path()=='admin/user' ? 'active' : '' }}" id="useradmin">
              <a href="{{ route('adminUser') }}"><i class="fa fa-fw fa-table"></i> {{ trans('translations.useradministration') }}</a>
          </li>
          <li class="{{Request::path()=='admin/group' ? 'active' : '' }}" id="groupadmin">
              <a href="{{ route('adminGroup') }}"><i class="fa fa-fw fa-table"></i> {{ trans('translations.groupadministration') }}</a>
          </li>
        </ul>
    </div>
    <!-- /.sidebar-collapse -->
</div>
<!-- /.navbar-static-side -->
