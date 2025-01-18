# API

The DP RESTful API is designed to allow programmatic access to various DP data
such as projects, word lists, and statistics.

## Configuring

The API is enabled by default because some UI features, like the Page Browser,
require it.

While disabling the API will break these features, you can still do so by
updating the `API_ENABLE` setting in your `configuration.sh` file and
re-configuring your site.

* `API_ENABLE` - if set to `FALSE` the API is disabled.

While not required for the DP UI, for external users it is also highly
recommended that you update your web server to rewrite requests for `/api/`
to `api/index.php?url=`. This will enable access to URLs like:
```
https://www.example.com/api/v1/projects/
```

Instead of:
```
https://www.example.com/c/api/index.php?url=v1/projects/
```

This can be achieved by adding a stanza such as this one to your Apache
config:
```
RewriteEngine On
RewriteRule ^/api/(.*)$ %{DOCUMENT_ROOT}/c/api/index.php?url=$1 [L,QSA]
```

## Access Control

API access is limited to individuals with API keys or valid PHP session keys.
Both are associated with individual users and the API calls are restricted to
the same information as the user presenting the keys. Said another way, a user
can only access and change information via the API that they can via the UI.

To enable an API key for a user, add a 32-character GUID to the user's
`api_key` column in the database. The format of this field is not enforced, any
32-character string will do.

PHP session keys allow the DP UI to make API calls for loading data. Any
logged-in user can therefore access the API using their PHP session information.
No configuration change is required to enable API access over PHP sessions.

It is **highly recommended** that you only provide API access over https to
ensure that 3rd parties don't obtain API keys from the requests.

## Rate Limiting

API requests can be rate limited to help prevent the site from being
overwhelmed. To use Rate Limiting, you must have the PHP memcached module
installed and a local memcached process running and accessible via localhost.
If rate limiting is enabled and memcached is not accessible, API requests will
fail and an error will be logged to `php_errors`.

Three settings in `configuration.sh` control limiting:

* `_API_RATE_LIMIT` - enables or disables rate limiting.
* `_API_RATE_LIMIT_REQUESTS_PER_WINDOW` - the number of API requests that are
  allowed per given window.
* `_API_RATE_LIMIT_SECONDS_IN_WINDOW` - the number of seconds within a given
  window.

## Storage

To facilitate javascript UI clients persisting data across browsers and devices,
the API includes an optional endpoint for clients to store and fetch JSON blobs.
To enable this feature, add a storage key to the `_API_STORAGE_KEYS`
configuration setting and have the client use that string with the endpoint
as the `storagekey`.

Some important notes about this feature:
* API storage is one blob per user per storage key. Said another way: API users are
  only able to store one blob per `storagekey` and that blob can only be
  set and retrieved by the user authenticated with the API.
* Beyond validating these are valid JSON objects, they are treated as opaque
  blobs server-side. It is up to the client to manage the object, including
  the schema and the possibility that the object will not match an expected
  schema.
* When used inside javascript in the browser, the `storagekey` is visible to
  the browser user and is therefore not a secret. Nothing prevents users with
  API keys (or valid PHP session keys) from using this endpoint with a valid
  storage key to change the contents of this blob for their user. API users
  should treat this blob as unvalidated user input and act accordingly.
