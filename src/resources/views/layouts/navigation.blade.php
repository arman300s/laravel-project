<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ auth()->user()->role === 'admin' ? route('admin.dashboard') : route('user.dashboard') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="auth()->user()->role === 'admin' ? route('admin.dashboard') : route('user.dashboard')"
                                :active="auth()->user()->role === 'admin' ? request()->routeIs('admin.dashboard') : request()->routeIs('user.dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>

                    <!-- Books Link -->
                    <x-nav-link :href="auth()->user()->role === 'admin' ? route('admin.books.index') : route('user.books.index')"
                                :active="auth()->user()->role === 'admin' ? request()->routeIs('admin.books.*') : request()->routeIs('user.books.*')">
                        {{ __('Books') }}
                    </x-nav-link>

                    <!-- Borrowings Link -->
                    <x-nav-link :href="auth()->user()->role === 'admin' ? route('admin.borrowings.index') : route('user.borrowings.index')"
                                :active="auth()->user()->role === 'admin' ? request()->routeIs('admin.borrowings.*') : request()->routeIs('user.borrowings.*')">
                        {{ __('Borrowings') }}
                    </x-nav-link>

                    <!-- Categories Link -->
                    <x-nav-link :href="auth()->user()->role === 'admin' ? route('admin.categories.index') : route('user.categories.index')"
                                :active="auth()->user()->role === 'admin' ? request()->routeIs('admin.categories.*') : request()->routeIs('user.categories.*')">
                        {{ __('Categories') }}
                    </x-nav-link>

                    <!-- Authors Link -->
                    <x-nav-link :href="auth()->user()->role === 'admin' ? route('admin.authors.index') : route('user.authors.index')"
                                :active="auth()->user()->role === 'admin' ? request()->routeIs('admin.authors.*') : request()->routeIs('user.authors.*')">
                        {{ __('Authors') }}
                    </x-nav-link>

                    @if(auth()->user()->role === 'admin')
                        <x-nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.*')">
                            {{ __('Manage Users') }}
                        </x-nav-link>
                    @endif
                    @if(auth()->user()->role === 'admin')
                        <x-nav-link :href="route('admin.users.search')" :active="request()->routeIs('admin.users.search')">
                            {{ __('User Search') }}
                        </x-nav-link>
                    @else
                        <x-nav-link :href="route('user.users.search')" :active="request()->routeIs('user.users.search')">
                            {{ __('Find Users') }}
                        </x-nav-link>
                    @endif
                    <x-nav-link :href="route('profile.edit')" :active="request()->routeIs('profile.edit')">
                        {{ __('Profile') }}
                    </x-nav-link>
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                            <div class="flex items-center">
                                <div class="relative w-8 h-8 overflow-hidden rounded-full bg-gray-200 flex items-center justify-center text-gray-600 font-bold mr-2">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                </div>
                                <div>{{ Auth::user()->name }}</div>
                            </div>
                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Edit Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                             onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="auth()->user()->role === 'admin' ? route('admin.dashboard') : route('user.dashboard')"
                                   :active="auth()->user()->role === 'admin' ? request()->routeIs('admin.dashboard') : request()->routeIs('user.dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>

            <!-- Books Link (Mobile) -->
            <x-responsive-nav-link :href="auth()->user()->role === 'admin' ? route('admin.books.index') : route('user.books.index')"
                                   :active="auth()->user()->role === 'admin' ? request()->routeIs('admin.books.*') : request()->routeIs('user.books.*')">
                {{ __('Books') }}
            </x-responsive-nav-link>

            <!-- Borrowings Link (Mobile) -->
            <x-responsive-nav-link :href="auth()->user()->role === 'admin' ? route('admin.borrowings.index') : route('user.borrowings.index')"
                                   :active="auth()->user()->role === 'admin' ? request()->routeIs('admin.borrowings.*') : request()->routeIs('user.borrowings.*')">
                {{ __('Borrowings') }}
            </x-responsive-nav-link>

            <!-- Categories Link (Mobile) -->
            <x-responsive-nav-link :href="auth()->user()->role === 'admin' ? route('admin.categories.index') : route('user.categories.index')"
                                   :active="auth()->user()->role === 'admin' ? request()->routeIs('admin.categories.*') : request()->routeIs('user.categories.*')">
                {{ __('Categories') }}
            </x-responsive-nav-link>

            <!-- Authors Link (Mobile) -->
            <x-responsive-nav-link :href="auth()->user()->role === 'admin' ? route('admin.authors.index') : route('user.authors.index')"
                                   :active="auth()->user()->role === 'admin' ? request()->routeIs('admin.authors.*') : request()->routeIs('user.authors.*')">
                {{ __('Authors') }}
            </x-responsive-nav-link>

            @if(auth()->user()->role === 'admin')
                <x-responsive-nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.*')">
                    {{ __('Manage Users') }}
                </x-responsive-nav-link>
            @endif
            @if(auth()->user()->role === 'admin')
                <x-responsive-nav-link :href="route('admin.users.search')" :active="request()->routeIs('admin.users.search')">
                    {{ __('User Search') }}
                </x-responsive-nav-link>
            @else
                <x-responsive-nav-link :href="route('user.users.search')" :active="request()->routeIs('user.users.search')">
                    {{ __('Find Users') }}
                </x-responsive-nav-link>
            @endif
            <x-responsive-nav-link :href="route('profile.edit')" :active="request()->routeIs('profile.edit')">
                {{ __('Profile') }}
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="flex items-center px-4">
                <div class="relative w-10 h-10 overflow-hidden rounded-full bg-gray-200 flex items-center justify-center text-gray-600 font-bold mr-3">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
                <div>
                    <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                </div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Edit Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')"
                                           onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
