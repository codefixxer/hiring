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
                <a href="{{ route('employer.dashboard.index') }}">
                    <div class="parent-icon"><i class="material-icons-outlined">home</i></div>
                    <div class="menu-title">Employer Dashboard</div>
                </a>
            </li>

            <li class="menu-label">Job Management</li>

            <li>
                <a href="javascript:;" class="has-arrow active">
                    <div class="parent-icon"><i class="material-icons-outlined">home</i></div>
                    <div class="menu-title">Jobs</div>
                </a>
                <ul class="mm-show">
                    <li>
                        <a href="{{ route('employer.jobs.create') }}">
                            <i class="material-icons-outlined">arrow_right</i>Create
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('employer.jobs.index') }}">
                            <i class="material-icons-outlined">arrow_right</i>List
                        </a>
                    </li>
                </ul>
            </li>

            <li>
                <a href="{{ route('employer.applications.shortlisted') }}">
                    <div class="parent-icon"><i class="material-icons-outlined">how_to_reg</i></div>
                    <div class="menu-title">Shortlisted Candidates</div>
                </a>
            </li>

            <li class="menu-label">Others</li>

            <li>
                <a href="javascript:;" class="has-arrow active">
                    <div class="parent-icon"><i class="material-icons-outlined">support</i></div>
                    <div class="menu-title">Support</div>
                </a>
                <ul class="mm-show">
                    <li>
                        <a href="#"><i class="material-icons-outlined">arrow_right</i>Contact</a>
                    </li>
                    <li>
                        <a href="#"><i class="material-icons-outlined">arrow_right</i>FAQs</a>
                    </li>
                </ul>
            </li>

        </ul>
        <!--end navigation-->
    </div>
</aside>
