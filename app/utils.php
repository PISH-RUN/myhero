<?php

if (function_exists("iran_mobile")) {
    function iran_mobile($phone) {
        return '0' . substr($phone, -10, 10);
    }
}
