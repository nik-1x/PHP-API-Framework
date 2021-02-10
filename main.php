<?php

function api_log($arr)
{
    die(json_encode($arr));
}

$config = [
    "ts" => time(),
    "available_method" => [
        "get_profile"
    ],
    "static_params_required" => [
        "method", "hs_enc"
    ],
    "encoding" => [
        "hs" => "yt8bv7trev76cbyt8ngrte76wvc324q90kih7r3g76t8"
    ],
    "error_descriptions" => [
        0 => "No method given.",
        1 => "Incorrect method given.",
        2 => "Not all required params given.",
        3 => "Encoding is incorrect."
    ]
];

$valid_static = true;
$incorrect_static = [];
foreach ($config["static_params_required"] as $check) {
    if (!isset($_GET[$check])) {
        $valid = false;
        $incorrect[] = $check;
    }
}

if ($valid_static) {
    if (isset($_GET['method'])) {
        $method = mb_strtolower($_GET['method']);
        if (
        in_array($method, $config["available_method"]) # check valid method
        ) {
            $query_str = $_GET;
            unset($query_str["hs_enc"]);
            if ($_GET['hs_enc'] == md5(http_build_query($query_str) . $config["encoding"]["hs"])) {

                # hash string is valid

                api_log([
                    "method" => $method,
                    "ts" => $config["ts"],
                    "is_error" => false,
                    "response" => []
                ]);


            } else {
                api_log([
                    "method" => $method,
                    "ts" => $config["ts"],
                    "is_error" => true,
                    "response" => [
                        "error_code" => 3,
                        "error" => $config["error_descriptions"][3]
                    ]
                ]);
            }
        } else {
            api_log([
                "method" => null,
                "ts" => $config["ts"],
                "is_error" => true,
                "response" => [
                    "error_code" => 1,
                    "error" => $config["error_descriptions"][1]
                ]
            ]);
        }
    } else {
        api_log([
            "method" => null,
            "ts" => $config["ts"],
            "is_error" => true,
            "response" => [
                "error_code" => 0,
                "error" => $config["error_descriptions"][0]
            ]
        ]);
    }
} else {
    api_log([
        "method" => null,
        "ts" => $config["ts"],
        "is_error" => true,
        "response" => [
            "error_code" => 2,
            "error" => $config["error_descriptions"][2],
            "error_attach" => $incorrect_static
        ]
    ]);
}
