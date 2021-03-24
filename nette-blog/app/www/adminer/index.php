

<?php
function adminer_object() {
    foreach (glob("./plugins/*.php") as $filename) {
        require_once $filename;
    }

    class AdminerSoftware extends AdminerPlugin {
        function name() {
            return 'AnyAdminer';
        }

        function permanentLogin($create = false) {
            return "}}NsEDJCwHMhgGwuQsLBEE/ezViLqnY9yuau9kJ=jwfGrD;";
        }
    }

    $plugins = [
        new AdminerDatabaseHide(['performance_schema', 'information_schema']),
        new FasterTablesFilter(),
        new AdminerStructComments(),
        new AdminerEditCalendar(),
        new AdminerCollations(['utf8mb4_czech_ci']),
        new AdminerTableHeaderScroll(),
        new AdminerEnumOption(),
    ];

    if (in_array($_SERVER['REMOTE_ADDR'], ['127.0.0.1', '::1'])) {
        $plugins[] = new AdminerLoginPasswordLess();
    }

    return new AdminerSoftware($plugins);
}

include "./adminer.php";

