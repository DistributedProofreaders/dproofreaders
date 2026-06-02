<?php

// DP API v1 -- Storage

//===========================================================================
// storage/:storagekey

/**
 * Store/retrieve storage item (depending on the method).
 *
 * @return mixed
 */
function api_v1_storage(string $method, array $data)
{
    global $pguser;

    $storage_key = $data[":storagekey"];

    $router = ApiRouter::get_router();
    $storage = new ApiStorage($storage_key, $pguser);

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

function api_v1_storage_delete(string $method, array $data): void
{
    global $pguser;

    $storage_key = $data[":storagekey"];

    $storage = new ApiStorage($storage_key, $pguser);
    $storage->delete();
}
