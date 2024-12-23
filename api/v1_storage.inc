<?php

// DP API v1 -- Storage

//===========================================================================
// storage/clients/:clientid

/** @return mixed */
function api_v1_storage_clients(string $method, array $data, array $query_params)
{
    global $pguser;

    $clientid = $data[":clientid"];

    $storage = new ApiClientStorage($clientid, $pguser);
    if ($method == "GET") {
        return json_decode($storage->get());
    } elseif ($method == "PUT") {
        $storage->set(json_encode(api_get_request_body()));
        return json_decode($storage->get());
    }
}

function api_v1_storage_clients_delete(string $method, array $data, array $query_params): void
{
    global $pguser;

    $clientid = $data[":clientid"];

    $storage = new ApiClientStorage($clientid, $pguser);
    $storage->delete();
}
