<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center space-x-8">
                @php
                    $role = Auth::user()->role ?? 'user';
                    $dashboardRoute = $role === 'admin' ? 'admin.dashboard' : 'user.dashboard';
                @endphp

                <x-nav-link :href="route($dashboardRoute)" :active="request()->routeIs($dashboardRoute)">
                    {{ $role === 'admin' ? __('Admin Dashboard') : __('Dashboard') }}
                </x-nav-link>

                <x-nav-link :href="route($role . '.books.index')" :active="request()->routeIs($role . '.books.index')">
                    {{ __('Books') }}
                </x-nav-link>

                <x-nav-link :href="route($role . '.categories.index')" :active="request()->routeIs($role . '.categories.*')">
                    {{ $role === 'admin' ? __('Manage Categories') : __('Categories') }}
                </x-nav-link>

                <x-nav-link :href="route($role . '.reservations.index')" :active="request()->routeIs($role . '.reservations.index')">
                    {{ $role === 'admin' ? __('Manage Reservations') : __('My Reservations') }}
                </x-nav-link>

                @if($role === 'admin')
                    <x-nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.index')">
                        {{ __('Manage Users') }}
                    </x-nav-link>
                @endif
            </div>

            <div class="hidden sm:flex items-center space-x-3">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none">
                            <span>{{ Auth::user()->name }}</span>
                            <svg class="fill-current h-4 w-4 ms-1" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </x-slot>
                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>

                </x-dropdown>

            </div>

            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <x-responsive-nav-link :href="route($dashboardRoute)" :active="request()->routeIs($dashboardRoute)">
            {{ __('Dashboard') }}
        </x-responsive-nav-link>
        <x-responsive-nav-link :href="route($role . '.books.index')" :active="request()->routeIs($role . '.books.index')">
            {{ __('Books') }}
        </x-responsive-nav-link>

        <x-responsive-nav-link :href="route($role . '.categories.index')" :active="request()->routeIs($role . '.categories.*')">
            {{ $role === 'admin' ? __('Manage Categories') : __('Categories') }}
        </x-responsive-nav-link>

        <x-responsive-nav-link :href="route($role . '.reservations.index')" :active="request()->routeIs($role . '.reservations.index')">
            {{ $role === 'admin' ? __('Manage Reservations') : __('My Reservations') }}
        </x-responsive-nav-link>

        @if($role === 'admin')
            <x-responsive-nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.index')">
                {{ __('Manage Users') }}
            </x-responsive-nav-link>
        @endif
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>
            <x-responsive-nav-link :href="route('profile.edit')">
                {{ __('Profile') }}
            </x-responsive-nav-link>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <x-responsive-nav-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                    {{ __('Log Out') }}
                </x-responsive-nav-link>
            </form>
        </div>
    </div>
</nav>
