<aside class="sidebar-wrapper" data-simplebar="true">
    <div class="sidebar-header">
      <div class="logo-icon">
        <img src="{{ asset('assets/images/logo-icon.png') }}" class="logo-img" alt="">
      </div>
      <div class="logo-name flex-grow-1">
        <h5 class="mb-0">Maxton</h5>
      </div>
      <div class="sidebar-close">
        <span class="material-icons-outlined">close</span>
      </div>
    </div>
    <div class="sidebar-nav">
        <!--navigation-->
        <ul class="metismenu" id="sidenav">










            <li>
                <a href="{{ route('admin.dashboard.index') }}">
                  <div class="parent-icon"><i class="material-icons-outlined">home</i>
                  </div>
                  <div class="menu-title">Admin Dashboard</div>
                </a>
              </li>
    

          <li class="menu-label">User Management</li>

          <li>
            <a href="javascript:;" class="has-arrow">
              <div class="parent-icon"><i class="material-icons-outlined">home</i>
              </div>
              <div class="menu-title">Users</div>
            </a>
            <ul>
              <li><a href="{{ route('admin.users.create') }}"><i class="material-icons-outlined">arrow_right</i>Create</a>
              </li>
              <li><a href="{{ route('admin.users.index') }}"><i class="material-icons-outlined">arrow_right</i>List</a>
              </li>
            </ul>
          </li>






          <li>
  <a href="javascript:;">
    <div class="parent-icon"><i class="material-icons-outlined">work_outline</i></div>
    <div class="menu-title">View Jobs</div>
  </a>
</li>

<li>
  <a href="javascript:;">
    <div class="parent-icon"><i class="material-icons-outlined">event_note</i></div>
    <div class="menu-title">View Interviews</div>
  </a>
</li>


           
        
        














     
          <li class="menu-label">Others</li>

          <li>
            <a href="javascrpt:;">
              <div class="parent-icon"><i class="material-icons-outlined">support</i>
              </div>
              <div class="menu-title">Support</div>
            </a>
          </li>


     

      





         </ul>
        <!--end navigation-->
    </div>
  </aside>