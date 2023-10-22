<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <link rel="shortcut icon" href="{{asset('favicon.png')}}">

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=source-sans-pro:200,200i,300,300i,400,400i,700,700i,900,900i"
          rel="stylesheet"/>
    <title>Login To Dashboard | CodeWithKyrian</title>

    <!-- Styles and Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script>
        localStorage.theme === 'darkMode_on' ||
        (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)
            ? document.documentElement.classList.add('dark')
            : document.documentElement.classList.remove('dark');
    </script>
</head>
<body x-cloak x-data x-bind:class="{'dark' : $store.darkMode.on }"
      class="antialiased selection:bg-blue-500 selection:text-white min-h-screen flex flex-col">

<header>
    <nav class="bg-zinc-50 border-b border-zinc-200 px-4 lg:px-6 py-2.5 dark:bg-zinc-900 dark:border-zinc-700">
        <div class="flex flex-wrap justify-between items-center mx-auto max-w-screen-xl">
            <a href="" class="flex items-center">
                <img src="{{asset('logo-icon.png')}}" class="mr-3 h-6 sm:h-9" alt="CodeWithKyrian Logo"/>
                <span class="self-center text-xl font-semibold whitespace-nowrap dark:text-white">CodeWithKyrian</span>
            </a>
            <div class="flex items-center lg:order-2">
                <a href="{{ config('app.frontend_url')}}" target="_blank"
                   class="inline-flex items-center p-2 ml-1 text-sm text-zinc-500 rounded-lg hover:bg-zinc-100 focus:outline-none focus:ring-2 focus:ring-zinc-200 dark:text-zinc-400 dark:hover:bg-zinc-700 dark:focus:ring-zinc-600"
                   aria-controls="mobile-menu-2" aria-expanded="false">
                    <span class="sr-only">To Frontend</span>
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"
                         xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M13.19 8.688a4.5 4.5 0 011.242 7.244l-4.5 4.5a4.5 4.5 0 01-6.364-6.364l1.757-1.757m13.35-.622l1.757-1.757a4.5 4.5 0 00-6.364-6.364l-4.5 4.5a4.5 4.5 0 001.242 7.244"></path>
                    </svg>
                </a>
                <button data-collapse-toggle="mobile-menu-2" type="button" @click="$store.darkMode.toggle()"
                        class="inline-flex items-center p-2 ml-1 text-sm text-zinc-500 rounded-lg hover:bg-zinc-100 focus:outline-none focus:ring-2 focus:ring-zinc-200 dark:text-zinc-400 dark:hover:bg-zinc-700 dark:focus:ring-zinc-600"
                        aria-controls="mobile-menu-2" aria-expanded="false">
                    <span class="sr-only">Toggle dark mode</span>
                    <svg x-show="!$store.darkMode.on" class="w-6 h-6" fill="none" stroke="currentColor"
                         stroke-width="1.5" viewBox="0 0 24 24"
                         xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M12 3v2.25m6.364.386l-1.591 1.591M21 12h-2.25m-.386 6.364l-1.591-1.591M12 18.75V21m-4.773-4.227l-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z"
                            stroke-linecap="round" stroke-linejoin="round"></path>
                    </svg>
                    <svg x-show="$store.darkMode.on" class="w-6 h-6" fill="none" stroke="currentColor"
                         stroke-width="1.5" viewBox="0 0 24 24"
                         xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M21.752 15.002A9.718 9.718 0 0118 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 003 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 009.002-5.998z"
                            stroke-linecap="round" stroke-linejoin="round"></path>
                    </svg>
                </button>
            </div>
        </div>
    </nav>
</header>

<main
    class="flex-grow w-full flex items-center justify-center px-4 py-8 mx-auto lg:py-0 bg-dots-darker bg-center bg-zinc-50 dark:bg-dots-lighter dark:bg-zinc-900">
    <section
        class="w-full sm:bg-white sm:dark:bg-zinc-800/50 sm:dark:bg-gradient-to-bl sm:from-zinc-700/50 sm:via-transparent
         rounded-lg sm:border sm:border-zinc-200 sm:shadow-md sm:dark:border-zinc-700 md:mt-0 sm:max-w-md xl:p-0">
        <div class="p-4 space-y-4 md:space-y-6 sm:p-6">
            <h1 class="text-xl font-bold leading-tight tracking-tight text-zinc-900 md:text-2xl dark:text-white">
                Sign in to your account
            </h1>
            <form class="space-y-4 md:space-y-6" method="post" action="{{route('auth.login')}}">

                @csrf

                <div class="relative w-full">
                    <label for="email" class="hidden mb-2 text-sm font-medium text-zinc-900 dark:text-zinc-300">Email
                        address</label>
                    <div class="flex absolute inset-y-0 left-0 items-center pl-3 pointer-events-none">
                        <svg class="w-5 h-5 text-zinc-500 dark:text-zinc-400" fill="currentColor"
                             viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"></path>
                            <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"></path>
                        </svg>
                    </div>
                    <input
                        class="block p-3 pl-10 w-full text-sm text-zinc-900 bg-white rounded-md border border-zinc-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-zinc-700 dark:border-zinc-600 dark:placeholder-zinc-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        placeholder="Enter your email" type="email" name="email" id="email" required>
                </div>

                <div class="relative w-full">
                    <label for="password" class="hidden mb-2 text-sm font-medium text-zinc-900 dark:text-zinc-300">Password</label>
                    <div class="flex absolute inset-y-0 left-0 items-center pl-3 pointer-events-none">
                        <svg class="w-5 h-5 text-zinc-500 dark:text-zinc-400" fill="none" stroke="currentColor"
                             stroke-width="1.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"
                                stroke-linecap="round" stroke-linejoin="round"></path>
                        </svg>
                    </div>
                    <input
                        class="block p-3 pl-10 w-full text-sm text-zinc-900 bg-white rounded-md border border-zinc-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-zinc-700 dark:border-zinc-600 dark:placeholder-zinc-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        placeholder="**********" type="password" name="password" id="password" required>

                    <button type="button" class="flex absolute inset-y-0 right-0 items-center pr-3">
                        <svg class="w-5 h-5 text-zinc-500 dark:text-zinc-400" fill="none" stroke="currentColor"
                             stroke-width="1.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"
                                stroke-linecap="round" stroke-linejoin="round"></path>
                            <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" stroke-linecap="round"
                                  stroke-linejoin="round"></path>
                        </svg>
                        <svg class="hidden w-5 h-5 text-zinc-500 dark:text-zinc-400" fill="none"
                             stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"
                             xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88"
                                stroke-linecap="round" stroke-linejoin="round"></path>
                        </svg>
                    </button>
                </div>

                <div class="flex items-center justify-between">
                    <div class="flex items-start">
                        <div class="flex items-center h-5">
                            <input id="remember" aria-describedby="remember" type="checkbox"
                                   class="w-4 h-4 border border-zinc-300 rounded bg-zinc-50 focus:ring-3 focus:ring-blue-300 dark:bg-zinc-700 dark:border-zinc-600 dark:focus:ring-blue-600 dark:ring-offset-zinc-800">
                        </div>
                        <div class="ml-3 text-sm">
                            <label for="remember" class="text-zinc-500 dark:text-zinc-300">Remember me</label>
                        </div>
                    </div>
                    <a href="#" class="text-sm font-medium text-blue-600 hover:underline dark:text-blue-500">Forgot
                        password?</a>
                </div>
                <button type="submit"
                        class="w-full text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-400 font-medium rounded-md text-sm px-5 py-3 text-center dark:bg-blue-800 dark:hover:bg-blue-900 dark:focus:ring-blue-900">
                    Sign in
                </button>
                {{--                    <p class="text-sm font-light text-zinc-500 dark:text-zinc-400">--}}
                {{--                        Don’t have an account yet? <a href="#"--}}
                {{--                                                      class="font-medium text-blue-600 hover:underline dark:text-blue-500">Sign--}}
                {{--                            up</a>--}}
                {{--                    </p>--}}
            </form>
        </div>
    </section>
</main>

</body>
</html>
