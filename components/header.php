<header class="bg-white">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex h-16 items-center justify-between">
            <div class="flex-1 md:flex md:items-center md:gap-12">
                <a class="block text-teal-600" href="#">

                    Dashboard
                </a>
            </div>

            <div class="md:flex md:items-center md:gap-12">
                <nav aria-label="Global" class="hidden md:block">
                    <ul class="flex items-center gap-6 text-sm">
                        <li>
                            <a class="text-gray-500 transition hover:text-gray-500/75" href="<?php echo $_SERVER['SRVROOT']; ?>/dashboard/shifts"> Shifts </a>
                        </li>

                        <li>
                            <a class="text-gray-500 transition hover:text-gray-500/75" href="<?php echo $_SERVER['SRVROOT']; ?>/dashboard/payperiods"> Payperiods </a>
                        </li>

                        <li>
                            <a class="text-gray-500 transition hover:text-gray-500/75" href="#"> Calendar </a>
                        </li>

                        <li>
                            <a class="text-gray-500 transition hover:text-gray-500/75" href="#"> Plans </a>
                        </li>
                    </ul>
                </nav>

                <div class="hidden md:relative md:block">
                    <button type="button" class="overflow-hidden rounded-full border border-gray-300 shadow-inner" onclick="javascript:;" id="profDropDown">
                        <span class="sr-only">Toggle dashboard menu</span>

                        <img src="<?php echo $_SESSION['profileIMG']; ?>" alt="" class="size-10 object-cover">
                    </button>

                    <div id="profDropDownMenu" class="hidden absolute end-0 z-10 mt-0.5 w-56 divide-y divide-gray-100 rounded-md border border-gray-100 bg-white shadow-lg" role="menu">
                        <div class="p-2">
                            <a href="#" class="block rounded-lg px-4 py-2 text-sm text-gray-500 hover:bg-gray-50 hover:text-gray-700" role="menuitem">
                                My profile
                            </a>

                            <a href="#" class="block rounded-lg px-4 py-2 text-sm text-gray-500 hover:bg-gray-50 hover:text-gray-700" role="menuitem">
                                Settings
                            </a>
                            <a href="/auth/logout.php" class="block rounded-lg px-4 py-2 text-sm text-gray-500 hover:bg-gray-50 text-red-700 hover:text-gray-700" role="menuitem">
                                <i class="fa-solid fa-arrow-right-from-bracket "></i> Logout
                            </a>

                        </div>


                    </div>
                </div>

                <div class="block md:hidden">
                    <button class="rounded-sm bg-gray-100 p-2 text-gray-600 transition hover:text-gray-600/75">
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.getElementById("profDropDown").addEventListener("click", function() {
            document.getElementById("profDropDownMenu").classList.toggle("hidden");
        });
    </script>
</header>