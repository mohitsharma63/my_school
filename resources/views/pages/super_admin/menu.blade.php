{{--Manage Settings--}}
<li class="nav-item">
    <a href="{{ route('settings') }}" class="nav-link {{ in_array(Route::currentRouteName(), ['settings',]) ? 'active' : '' }}"><i class="icon-gear"></i> <span>Settings</span></a>
</li>

{{--Pins--}}
<li class="nav-item nav-item-submenu {{ in_array(Route::currentRouteName(), ['pins.create', 'pins.index']) ? 'nav-item-expanded nav-item-open' : '' }} ">
    <a href="#" class="nav-link"><i class="icon-lock2"></i> <span> Pins</span></a>

    <ul class="nav nav-group-sub" data-submenu-title="Manage Pins">
        {{--Generate Pins--}}
            <li class="nav-item">
                <a href="{{ route('pins.create') }}"
                   class="nav-link {{ (Route::is('pins.create')) ? 'active' : '' }}">Generate Pins</a>
            </li>

        {{--    Valid/Invalid Pins  --}}
        <li class="nav-item">
            <a href="{{ route('pins.index') }}"
               class="nav-link {{ (Route::is('pins.index')) ? 'active' : '' }}">View Pins</a>
        </li>
    </ul>
</li>

<!-- Branch Management -->
        <li class="nav-item">
            <a href="{{ route('branches.index') }}" class="nav-link {{ in_array(Route::currentRouteName(), ['branches.index', 'branches.show', 'branches.edit']) ? 'active' : '' }}">
                <i class="icon-office"></i>
                <span>Branch Management</span>
            </a>
        </li>

        <!-- Multi-Branch Benefits -->
        <li class="nav-item">
            <a href="{{ route('benefits.dashboard') }}" class="nav-link {{ in_array(Route::currentRouteName(), ['benefits.dashboard', 'benefits.cost-analysis', 'benefits.performance-comparison']) ? 'active' : '' }}">
                <i class="icon-stats-growth"></i>
                <span>Benefits Dashboard</span>
            </a>
        </li>