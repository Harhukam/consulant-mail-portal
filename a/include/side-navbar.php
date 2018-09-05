<nav class="navbar-side" role="navigation">
<div class="navbar-collapse sidebar-collapse collapse">
<ul id="side" class="nav navbar-nav side-nav">
<!-- begin SIDE NAV USER PANEL -->
<li class="side-user hidden-xs">
	<div style="float: left;margin-left: -20px !important;  width: 35px;">
<img class="img-circle" height="32" width="32" src="images/<?php echo $row['adminPhoto'];?>" alt="<?= $row['adminName']; ?>">
</div>
<!-- <p class="welcome">
<i class="fa fa-key"></i> Logged in as
</p> -->
<div class="">
<p class="name tooltip-sidebar-logout">
<span style="color: #fff; font-size: 14px; margin-left: 0px;" class="last-name" ><?php echo $row['adminName']; ?></span> <a style="color: inherit; font-size: 16px;" class="logout_open" href="logout" data-toggle="tooltip" data-placement="top" title="Logout"><i class="fa fa-sign-out"></i></a>
</p>
</div>
<div class="clearfix"></div>
</li>

<li>
<a href="dashboard">
<i class="fa fa-dashboard"></i>  Dashboard
</a>
</li>


<li>
<a href="inbox">
<i class="fa fa-inbox"></i>Inbox (<?php echo $countnotify; ?>)
</a>
</li>


<li>
<a href="sent">
<i class="fa fa-bullhorn"></i>  Sent
</a>
</li>


<li>
<a href="adduser">
<i class="fa fa-user"></i>  Add User
</a>
</li>



<li>
<a href="complete">
<i class="fa fa-list"></i> Complete Files
</a>
</li>

<!-- begin CHARTS DROPDOWN -->
<li class="panel">
<a href="javascript:;" data-parent="#side" data-toggle="collapse" class="accordion-toggle" data-target="#usersettings">
<i class="fa fa-cogs"></i> Settings <i class="fa fa-caret-down"></i>
</a>
<ul class="collapse nav" id="usersettings">

<li>
<a href="category">
<i class="fa fa-check-square-o"></i> Manage Category(s)
</a>
</li>


<li>
<a href="allusers">
<i class="fa fa-users"></i> Manage Users
</a>
</li>

<li>
<a href="settings">
<i class="fa fa-wrench"></i> Change Password
</a>
</li>


</ul>
</li> 
<!-- end CHARTS DROPDOWN -->



<li>
<a href="logout">
<i class="fa fa-power-off"></i> LogOut
</a>
</li>

</ul><!-- /.side-nav -->
</div><!-- /.navbar-collapse -->
</nav><!-- /.navbar-side -->