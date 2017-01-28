<!-- Sidebar Menu Items - These collapse to the responsive navigation menu on small screens -->
<div class="collapse navbar-collapse navbar-ex1-collapse">
    <ul class="nav navbar-nav side-nav">
        <li class="{{ Request::path()=='/' ? 'active' : '' }}" id="dashboard">
            <a href="/"><i class="fa fa-fw fa-dashboard"></i> Dashboard</a>
        </li>
        <li class="{{ Request::path()=='admin/user' ? 'active' : '' }}" id="useradmin">
            <a href="{{ route('adminUser') }}"><i class="fa fa-fw fa-dashboard"></i> User Administration</a>
        </li>
        <li class="{{Request::path()=='admin/group' ? 'active' : '' }}" id="groupadmin">
            <a href="{{ route('adminGroup') }}"><i class="fa fa-fw fa-table"></i> Group Administration</a>
        </li>
        <!--<li>
            <a href="forms.html"><i class="fa fa-fw fa-edit"></i> Forms</a>
        </li>
        <li>
            <a href="bootstrap-elements.html"><i class="fa fa-fw fa-desktop"></i> Bootstrap Elements</a>
        </li>
        <li>
            <a href="bootstrap-grid.html"><i class="fa fa-fw fa-wrench"></i> Bootstrap Grid</a>
        </li>
        <li>
            <a href="javascript:;" data-toggle="collapse" data-target="#demo"><i class="fa fa-fw fa-arrows-v"></i> Dropdown <i class="fa fa-fw fa-caret-down"></i></a>
            <ul id="demo" class="collapse">
                <li>
                    <a href="#">Dropdown Item</a>
                </li>
                <li>
                    <a href="#">Dropdown Item</a>
                </li>
            </ul>
        </li>
        <li>
            <a href="blank-page.html"><i class="fa fa-fw fa-file"></i> Blank Page</a>
        </li>
        <li>
            <a href="index-rtl.html"><i class="fa fa-fw fa-dashboard"></i> RTL Dashboard</a>
        </li>-->
    </ul>
</div>
