<?php
$request = $_SERVER['REQUEST_URI'];
$page = str_replace("/".APP_FOLDER."/", "", $request);
?>
<ul class="navbar-nav">
  <li class="nav-item <?= $page == "" || $page == "homepage" || $page == null ? 'active' : '' ?>">
    <a class="nav-link" href="./">
      <span class="nav-link-icon d-md-none d-lg-inline-block"><!-- Download SVG icon from http://tabler-icons.io/i/home -->
        <i style="font-size: 20px;" class="ti ti-home"></i>
      </span>
      <span class="nav-link-title">
        Home
      </span>
    </a>
  </li>
  
  <li class="nav-item <?= $page == 'job-types' ? 'active' : '' ?>">
    <a class="nav-link" href="./job-types">
      <span class="nav-link-icon d-md-none d-lg-inline-block">
        <i style="font-size: 20px;" class="ti ti-clipboard-list"></i>
      </span>
      <span class="nav-link-title">
        Job Types
      </span>
    </a>
  </li>

  <li class="nav-item <?= $page == 'job-posting' ? 'active' : '' ?>">
    <a class="nav-link" href="./job-posting">
      <span class="nav-link-icon d-md-none d-lg-inline-block">
        <i style="font-size: 20px;" class="ti ti-search"></i>
      </span>
      <span class="nav-link-title">
        Job Posting
      </span>
    </a>
  </li>

  <li class="nav-item <?= $page == 'users' ? 'active' : '' ?>">
    <a class="nav-link" href="./users">
      <span class="nav-link-icon d-md-none d-lg-inline-block">
        <i style="font-size: 20px;" class="ti ti-users"></i>
      </span>
      <span class="nav-link-title">
        Users
      </span>
    </a>
  </li>
</ul>