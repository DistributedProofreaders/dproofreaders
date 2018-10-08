# API

The DP RESTful API is designed to allow programmatic access to various DP data
such as projects, word lists, and statistics.

## Configuring

To enable the API, update the following API-related values in your
`configuration.sh` file and re-configuring your site.

* `API_ENABLE` - if set to `TRUE` the API is enabled.

It is also highly recommended that you update your web browser to rewrite
requests for `/api/` to `api/index.php?url=`. This will enable access to
URLs like:
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
RewriteRule ^api/(.*)$ /c/api/index.php?url=$1 [L,QSA]
```

## Access Control

API access is limited to individuals with API keys. API keys are associated
with users and the API calls are restricted to the same information as the
user who owns it.

To enable an API key for a user, add a 32-character GUID to the user's
`api_key` column in the database.

It is **highly recommended** that you only provide API access over https to
ensure that 3rd parties don't obtain API keys from the requests.

## Rate Limiting

API requests can be rate limited to help prevent the site from being
overwhelemed. To use Rate Limiting, you must have the PHP memcached module
installed and a local memcached process running and accessible via localhost.

Three settings in `configuration.sh` control limiting:

* `_API_RATE_LIMIT` - enables or disables rate limiting.
* `_API_RATE_LIMIT_REQUESTS_PER_WINDOW` - the number of API requests that are
  allowed per given window.
* `_API_RATE_LIMIT_SECONDS_IN_WINDOW` - the number of seconds within a given
  window.
