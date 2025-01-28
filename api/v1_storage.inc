<?php

// DP API v1 -- Storage

//===========================================================================
// storage/:storagekey

/**
 * Store/retrieve storage item (depending on the method).
 *
 * @param array<string,string|string[]> $query_params
 * @return mixed
 */
function api_v1_storage(string $method, array $data, array $query_params)
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

/** @param array<string,string|string[]> $query_params */
function api_v1_storage_delete(string $method, array $data, array $query_params): void
{
    global $pguser;

    $storage_key = $data[":storagekey"];

    $storage = new ApiStorage($storage_key, $pguser);
    $storage->delete();
}
