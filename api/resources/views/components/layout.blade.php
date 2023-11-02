@props([ 'title' => 'Dashboard'])

    <!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">

    <title>{{$title}} | CodeWithKyrian</title>
    <link rel="shortcut icon" href="{{asset('favicon.png')}}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=source-sans-pro:200,200i,300,300i,400,400i,700,700i,900,900i"
          rel="stylesheet"/>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('stylesheets')
</head>

<body x-cloak x-data="{ sideMenu: false}" x-bind:class="{ 'dark' : $store.darkMode.on }">
<div class="flex h-screen bg-zinc-50 dark:bg-zinc-900">

    <!-- Sidebar -->
    <aside x-show="sideMenu  || $store.screen.lg" x-transition:enter="transition ease-in-out duration-300"
           x-transition:enter-start="opacity-0 transform -translate-x-20"
           x-transition:enter-end="opacity-100 transform translate-x-0"
           x-transition:leave="transition ease-in-out duration-300"
           x-transition:leave-start="opacity-100 transform translate-x-0"
           x-transition:leave-end="opacity-0 transform -translate-x-20" @click.away="sideMenu = false"
           @keydown.escape="sideMenu = false"
           class="z-20 w-64 border-r border-zinc-100 dark:border-zinc-700 overflow-y-auto bg-white dark:bg-zinc-800 flex-shrink-0
        fixed md:relative inset-y-0 shadow-lg">
        <a
            class="flex items-center justify-center h-[70px] border-b border-zinc-100 text-zinc-900 dark:text-white dark:border-zinc-700"
            href="">
            <img src="{{asset('logo-icon.png')}}" class="mr-3 h-6 sm:h-9" alt="CodeWithKyrian Logo"/>
            <span class="self-center text-xl font-semibold whitespace-nowrap dark:text-white">CodeWithKyrian</span>
        </a>
        <div class="py-4 text-zinc-500 dark:text-zinc-400">
            <ul class="mt-6">

                <x-nav-link title="Dashboard" :href="route('dashboard')"
                            :active="request()->route()->named('dashboard')">
                    <x-slot:icon>
                        <svg class="w-5 h-5" aria-hidden="true" fill="none" stroke-linecap="round"
                             stroke-linejoin="round"
                             stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
                            <path
                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                    </x-slot:icon>
                </x-nav-link>

                <x-nav-link title="Posts" :href="route('posts.index')" :active="request()->route()->named('posts.*')">
                    <x-slot:icon>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"
                             xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M8.25 6.75h12M8.25 12h12m-12 5.25h12M3.75 6.75h.007v.008H3.75V6.75zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zM3.75 12h.007v.008H3.75V12zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm-.375 5.25h.007v.008H3.75v-.008zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"
                                stroke-linecap="round" stroke-linejoin="round"></path>
                        </svg>
                    </x-slot:icon>
                </x-nav-link>

                <x-nav-link title="Categories" :href="route('categories.index')"
                            :active="request()->route()->named('categories.*')">
                    <x-slot:icon>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"
                             xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M2.25 7.125C2.25 6.504 2.754 6 3.375 6h6c.621 0 1.125.504 1.125 1.125v3.75c0 .621-.504 1.125-1.125 1.125h-6a1.125 1.125 0 01-1.125-1.125v-3.75zM14.25 8.625c0-.621.504-1.125 1.125-1.125h5.25c.621 0 1.125.504 1.125 1.125v8.25c0 .621-.504 1.125-1.125 1.125h-5.25a1.125 1.125 0 01-1.125-1.125v-8.25zM3.75 16.125c0-.621.504-1.125 1.125-1.125h5.25c.621 0 1.125.504 1.125 1.125v2.25c0 .621-.504 1.125-1.125 1.125h-5.25a1.125 1.125 0 01-1.125-1.125v-2.25z"
                                stroke-linecap="round" stroke-linejoin="round"></path>
                        </svg>
                    </x-slot:icon>
                </x-nav-link>

                <x-nav-link title="Tags" :href="route('tags.index')" :active="request()->route()->named('tags.*')">
                    <x-slot:icon>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"
                             xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 005.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 009.568 3z"
                                stroke-linecap="round" stroke-linejoin="round"></path>
                            <path d="M6 6h.008v.008H6V6z" stroke-linecap="round" stroke-linejoin="round"></path>
                        </svg>
                    </x-slot:icon>
                </x-nav-link>

                <x-nav-link title="Settings" :href="route('dashboard')">
                    <x-slot name="icon">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"
                             xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M4.5 12a7.5 7.5 0 0015 0m-15 0a7.5 7.5 0 1115 0m-15 0H3m16.5 0H21m-1.5 0H12m-8.457 3.077l1.41-.513m14.095-5.13l1.41-.513M5.106 17.785l1.15-.964m11.49-9.642l1.149-.964M7.501 19.795l.75-1.3m7.5-12.99l.75-1.3m-6.063 16.658l.26-1.477m2.605-14.772l.26-1.477m0 17.726l-.26-1.477M10.698 4.614l-.26-1.477M16.5 19.794l-.75-1.299M7.5 4.205L12 12m6.894 5.785l-1.149-.964M6.256 7.178l-1.15-.964m15.352 8.864l-1.41-.513M4.954 9.435l-1.41-.514M12.002 12l-3.75 6.495"
                                stroke-linecap="round" stroke-linejoin="round"></path>
                        </svg>
                    </x-slot>
                </x-nav-link>
            </ul>

            <div class="px-6 my-6">
                <form id="logout-form" action="{{ route('auth.logout') }}" method="POST">
                    @csrf
                    <button type="submit"
                            class="w-full bg-blue-800 text-white font-bold text-sm uppercase rounded hover:bg-blue-700 flex items-center justify-center px-2 py-3 mt-4">
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <!-- Backdrop -->
    <div class="md:hidden fixed inset-0 z-10 flex items-end bg-black bg-opacity-50 sm:items-center sm:justify-center"
         @click="sideMenu = false" x-show="sideMenu === true"></div>

    <div class="flex flex-col flex-1 w-full bg-gray-50 dark:bg-zinc-900">
        <header
            class="h-[75px] border-b border-zinc-100 dark:border-zinc-700 z-10 py-4 bg-white shadow-md dark:bg-zinc-800">
            <div
                class="container flex items-center justify-between md:justify-end h-full px-6 mx-auto text-blue-900 dark:text-blue-300">
                <!-- Mobile hamburger -->
                <button class="p-1 mr-5 -ml-1 rounded-md md:hidden focus:outline-none focus:shadow-outline-orange"
                        @click="sideMenu = true" aria-label="Menu">
                    <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                              d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z"
                              clip-rule="evenodd"/>
                    </svg>
                </button>

                <ul class="flex items-center justify-self-end flex-shrink-0 space-x-6">

                    <!-- Theme toggle -->
                    <li class="flex">
                        <button class="rounded-md focus:outline-none" @click="$store.darkMode.toggle()"
                                aria-label="Toggle color mode">
                            <svg x-show="!$store.darkMode.on" class="w-5 h-5" aria-hidden="true" fill="currentColor"
                                 viewBox="0 0 20 20">
                                <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"/>
                            </svg>
                            <svg x-show="$store.darkMode.on" class="w-5 h-5" aria-hidden="true" fill="currentColor"
                                 viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                      d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z"
                                      clip-rule="evenodd"/>
                            </svg>
                        </button>
                    </li>

                    <!-- Profile menu -->
                    <li class="relative">
                        <button class="flex items-center rounded-full focus:outline-none" @click="toggleProfileMenu"
                                @keydown.escape="closeProfileMenu" aria-label="Account" aria-haspopup="true">
                            <img class="object-cover w-8 h-8 rounded-full mr-2"
                                 src="https://ui-avatars.com/api/?background=1e40af&color=ffffff&name=Kyrian+Obikwelu"
                                 alt aria-hidden="true"/>
                            <span
                                class="font-josefin text-zinc-700 dark:text-zinc-100">{{ auth()->user()->name}}</span>
                        </button>
                        <template v-if="isProfileMenuOpen">
                            <ul x-transition:leave="transition ease-in duration-150"
                                x-transition:leave-start="opacity-100"
                                x-transition:leave-end="opacity-0" @click.away="closeProfileMenu"
                                @keydown.escape="closeProfileMenu"
                                class="absolute right-0 w-56 p-2 mt-2 space-y-2 text-zinc-600 bg-white border border-zinc-100 rounded-md shadow-md dark:border-zinc-700 dark:text-zinc-300 dark:bg-zinc-700"
                                aria-label="submenu">
                                <li class="flex">
                                    <a
                                        class="inline-flex items-center w-full px-2 py-1 text-sm font-semibold transition-colors duration-150 rounded-md hover:bg-zinc-100 hover:text-zinc-800 dark:hover:bg-zinc-800 dark:hover:text-zinc-200"
                                        href="#">
                                        <svg class="w-4 h-4 mr-3" aria-hidden="true" fill="none" stroke-linecap="round"
                                             stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24"
                                             stroke="currentColor">
                                            <path
                                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                        </svg>
                                        <span>Profile</span>
                                    </a>
                                </li>
                                <li class="flex">
                                    <button
                                        class="inline-flex items-center w-full px-2 py-1 text-sm font-semibold transition-colors duration-150 rounded-md hover:bg-zinc-100 hover:text-zinc-800 dark:hover:bg-zinc-800 dark:hover:text-zinc-200"
                                        href="#" @click="">
                                        <svg class="w-4 h-4 mr-3" aria-hidden="true" fill="none" stroke-linecap="round"
                                             stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24"
                                             stroke="currentColor">
                                            <path
                                                d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                                        </svg>
                                        <span>Log out</span>
                                    </button>
                                </li>
                            </ul>
                        </template>
                    </li>
                </ul>
            </div>
        </header>

        <main class="bg-zinc-50 dark:bg-zinc-900 h-full overflow-y-auto">
            <div class="container px-6 py-4 mx-auto h-full">
                {{ $slot }}

                <footer class="w-full mt-8 border-t border-zinc-200 dark:border-zinc-700">
                    <div class="p-4  mx-auto max-w-screen-xl lg:p-8">
                        <div class="text-center">
                            <span class="block text-sm text-center text-zinc-500 dark:text-zinc-400">© 2022-2023 <a
                                    href="/" class="hover:underline">CodeWithKyrian™</a>. All Rights Reserved.</span>
                        </div>
                    </div>
                </footer>
            </div>

        </main>


    </div>

    @stack('scripts')
</div>

{{--<x-toast type="info" position="bottom-center">--}}
{{--    <div class="ml-3 text-sm font-normal">Item moved successfully.</div>--}}
{{--</x-toast>--}}

</body>
</html>
