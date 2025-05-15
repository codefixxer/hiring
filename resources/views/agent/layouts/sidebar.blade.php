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

            <li class="menu-item">
                <a href="javascript:;" class="has-arrow active">
                    <div class="parent-icon">
                        <i class="material-icons-outlined">description</i>
                    </div>
                    <div class="menu-title">Applications</div>
                </a>
                <ul class="submenu mm-show">
                    <li>
                        <a href="{{ route('agent.applications.index') }}">
                            <div class="parent-icon">
                                <i class="material-icons-outlined md-18">fact_check</i>
                            </div>
                            <div class="menu-title">Manage Applications</div>
                        </a>
                    </li>
                </ul>
            </li>

            <li>
                <a href="#" class="has-arrow">
                    <div class="parent-icon"><i class="material-icons-outlined">event_note</i></div>
                    <div class="menu-title">Interview Management</div>
                </a>
                <ul class="mm-show">
                    <li>
                        <a href="{{ route('agent.interviews.index', ['tab' => 'pending']) }}">
                            <div class="parent-icon"><i class="material-icons-outlined md-18">hourglass_empty</i></div>
                            <div class="menu-title">Pending</div>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('agent.interviews.index', ['tab' => 'accepted']) }}">
                            <div class="parent-icon"><i class="material-icons-outlined md-18">check_circle_outline</i></div>
                            <div class="menu-title">Accepted</div>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('agent.interviews.index', ['tab' => 'postponed']) }}">
                            <div class="parent-icon"><i class="material-icons-outlined md-18">schedule</i></div>
                            <div class="menu-title">Postponed</div>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('agent.interviews.index', ['tab' => 'rejected']) }}">
                            <div class="parent-icon"><i class="material-icons-outlined md-18">cancel</i></div>
                            <div class="menu-title">Rejected</div>
                        </a>
                    </li>
                </ul>
            </li>

            <li>
                <a href="javascript:;" class="has-arrow active">
                    <div class="parent-icon"><i class="material-icons-outlined">description</i></div>
                    <div class="menu-title">Visa Documents</div>
                </a>
                <ul class="mm-show">
                    <li>
                        <a href="javascript:;">
                            <div class="parent-icon"><i class="material-icons-outlined md-18">add_circle_outline</i></div>
                            <div class="menu-title">Create</div>
                        </a>
                    </li>
                    <li>
                        <a href="javascript:;">
                            <div class="parent-icon"><i class="material-icons-outlined md-18">list_alt</i></div>
                            <div class="menu-title">Index</div>
                        </a>
                    </li>
                </ul>
            </li>

            <li class="menu-label">Others</li>

            <li>
                <a href="javascript:;" class="has-arrow active">
                    <div class="parent-icon"><i class="material-icons-outlined">support</i></div>
                    <div class="menu-title">Support</div>
                </a>
                <ul class="mm-show">
                    <li>
                        <a href="#"><div class="parent-icon"><i class="material-icons-outlined md-18">help_outline</i></div>FAQs</a>
                    </li>
                    <li>
                        <a href="#"><div class="parent-icon"><i class="material-icons-outlined md-18">support_agent</i></div>Contact</a>
                    </li>
                </ul>
            </li>

        </ul>
        <!--end navigation-->
    </div>
</aside>
