<?php

// DP API v1 -- Storage

//===========================================================================
// storage/clients/:clientid

/** @return mixed */
function api_v1_storage_clients(string $method, array $data, array $query_params)
{
    global $pguser;

    $clientid = $data[":clientid"];

    $router = ApiRouter::get_router();
    $storage = new ApiClientStorage($clientid, $pguser);

    if ($method == "GET") {
        // return it without conversion
        $router->set_raw_response();
        return $storage->get();
    } elseif ($method == "PUT") {
        // we store the raw JSON input we were passed in the request
        $storage->set(api_get_request_body(true));

        // and return it without conversion
        $router->set_raw_response();
        return $storage->get();
    }
}

function api_v1_storage_clients_delete(string $method, array $data, array $query_params): void
{
    global $pguser;

    $clientid = $data[":clientid"];

    $storage = new ApiClientStorage($clientid, $pguser);
    $storage->delete();
}
