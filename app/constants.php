<?php

const HT_ADMIN_USERID = "45eb3dc0-f9a7-11f0-9685-a029190ac76c";
const PRIVLEDGED_ROLES = ["admin","moderator"];

const ERRORS = [
    "unprivf" => [
        "desc"=>"You do not have the required privileges to preform this function.",
        "type"=>"Access Error"
        ],
    "ppnfound" => [
        "desc"=>"Pay Period Not Found or User does not have access to the pay period.",
        "type"=>"Access Error"
        ]
];