<?php
const DDCLASS = "block px-4 py-2 text-sm text-slate-800 hover:bg-slate-200 rounded-md";
const DDCLASSA= "block px-4 py-2 text-sm text-red-500 hover:bg-red-100 rounded-md";
const DDCLASSB= "block px-4 py-2 text-sm text-blue-500 hover:bg-blue-100 rounded-md";
?>

<header class="bg-white">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex h-16 items-center justify-between">
            <div class="flex-1 md:flex md:items-center md:gap-12">
                <a class="block text-teal-600" href="/dashboard">

                    Dashboard
                </a>
            </div>

            <div class="dropdown" data-placement="bottom-start">
                <button data-toggle="dropdown" aria-expanded="false" class="inline-flex select-none items-center justify-center rounded-md border border-slate-800 bg-slate-800 px-3.5 py-2.5 text-center align-middle font-sans text-sm font-medium leading-none text-slate-50 transition-all duration-300 ease-in hover:border-slate-700 hover:bg-slate-700 disabled:cursor-not-allowed disabled:opacity-50 disabled:shadow-none">
                    Open
                </button>
                <div data-role="menu" class="hidden mt-2 bg-white border border-slate-200 rounded-lg shadow-xl shadow-slate-950/[0.025] p-1 z-10">
                    <a href="<?php echo $_SERVER['SRVROOT']; ?>/dashboard/shifts" class="block px-4 py-2 text-sm text-slate-600 hover:text-slate-800 hover:bg-slate-200 rounded-md">Shifts</a>
                    <a href="<?php echo $_SERVER['SRVROOT']; ?>/dashboard/payperiods" class="block px-4 py-2 text-sm text-slate-600 hover:text-slate-800 hover:bg-slate-200 rounded-md">Payperiods</a>
                    <a href="<?php echo $_SERVER['SRVROOT']; ?>/dashboard/paychecks" class="block px-4 py-2 text-sm text-slate-600 hover:text-slate-800 hover:bg-slate-200 rounded-md">Paychecks</a>
                    <a href="<?php echo $_SERVER['SRVROOT']; ?>/dashboard/paychecks" class="block px-4 py-2 text-sm text-slate-600 hover:text-slate-800 hover:bg-slate-200 rounded-md">Tasks</a>
                    <div class="h-px bg-slate-200 my-1"></div>
                    <a class="<?= DDCLASSA ?>" href="<?= $_SERVER['SRVROOT'] . "/logout.php" ?>"> Logout </a>
                    <a class="<?= DDCLASS ?>" href="<?= $_SERVER['SRVROOT'] . "/settings" ?>"> Settings </a>
                    <?php if ($_SESSION['role'] == "admin") : ?>
                        <div class="h-px bg-blue-200 my-1"></div>
                        <a class="<?= DDCLASSB ?>" href="<?= $_SERVER['SRVROOT'] . "/admin" ?>"> Admin Portal </a>

                    <?php endif; ?>

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