# API User's Guide

The DP code provides a RESTful API for accessing various bits of data
programmatically using a well-defined structure instead of HTML scraping.

## RESTful API

The DP API is a RESTful API. Among other things, this means that all requests
to the API will be via URL (possibly with JSON request body) and all responses
-- including error messages -- will be JSON objects.

## Documentation

The `dp-openapi.yaml` file should always contain the definitive OpenAPI on what
is currently implemented. This file is best edited via
[Swagger Editor](https://editor.swagger.io/). Indeed, that's a great place
to test out the APIs as well.

## Access

The API can be enabled or disabled independently of the main UI. If the API is
not enabled, the server will return a `400 Bad Request` HTTP status code with
the following message:

```json
{
    "error": "API is not enabled"
}
```

Similarly, if the site is in maintenance mode, the server will return a
`400 Bad Request` HTTP status code with the message:

```json
{
    "error": "Site is in maintenance mode"
}
```

## Authentication

API authentication and authorization is provided via an API key. The API key
is associated with your user account and restricts the data you can get from
the API to the same data you would have access to via the web UI.

To request an API key, contact a site administrator.

Attempts to access the API with a missing or invalid API key will return a
`401 Unauthorized` HTTP status code with the JSON body:

```json
{
    "error": "Unauthorized"
}
```

## Rate Limiting

Your site may have API rate limiting enabled. This prevents overwhelming the
system by limiting the number of API requests per unit of time. API responses
will include three HTTP headers to let the caller know how many more requests
they have remaining in the limit window.

* `X-Rate-Limit-Limit` - total number of requests allowed within the window
* `X-Rate-Limit-Remaining` - number of requests remaining in the window
* `X-Rate-Limit-Reset` - number of seconds before the limit window restarts

When the rate limit is reached, the server will return a `429 Too Many Requests`
HTTP status code with the JSON body:

```json
{
    "error": "Rate limit exceeded, resets in 2383 seconds"
}
```

## Pagination

Some requests, like project searches, may return paginated results. Paginated
responses will include the following HTTP headers to assist with navigating the
result set:

* `X-Results-Total` - total number of entries available in the query set
* `Link` - links to walk through the paginated results.

The `Link` format looks like (lines wrapped here for readability):
```
Link: <https://www.pgdp.org/api/v1/projects?per_page=20&page=1>; rel="first",
      <https://www.pgdp.org/api/v1/projects?per_page=20&page=2>; rel="next",
      <https://www.pgdp.org/api/v1/projects?per_page=20&page=20>; rel="last"
```

You should use the links provided to navigate through the pages.

Some endpoints allow increasing the page size by passing in the `per_page`
parameter on the URL. These endpoints have a maximum number of entries per page,
usually 100.

## Examples

Here are some examples using `curl`. See the `dp-openapi.yaml` file for all
available endpoints and their parameters and return values.

### Project searches

Search for all projects with genre `Other`:

```bash
curl -i -X GET "https://www.pgdp.org/api/v1/projects?genre=Other" \
    -H "Accept: application/json" \
    -H "X-API-KEY: $API_KEY"
```

Search for all projects with the word `Monster` in their title:

```bash
curl -i -X GET "https://www.pgdp.org/api/v1/projects?title=Monster" \
    -H "Accept: application/json" \
    -H "X-API-KEY: $API_KEY"
```

### Project details

Get details about a specific project:

```bash
curl -i -X GET "https://www.pgdp.org/api/v1/projects/projectID44de3936807f1" \
    -H "Accept: application/json" \
    -H "X-API-KEY: $API_KEY"
```

See all pages in a project:

```bash
curl -i -X GET "https://www.pgdp.org/api/v1/projects/projectID44de3936807f1/pages" \
    -H "Accept: application/json" \
    -H "X-API-KEY: $API_KEY"
```

Get the P1 text for a project page:

```bash
curl -i -X GET "https://www.pgdp.org/api/v1/projects/projectID44de3936807f1/pages/001.png/pagerounds/P1" \
    -H "Accept: application/json" \
    -H "X-API-KEY: $API_KEY"
```

Get the good wordlist for a project:

```bash
curl -i -X GET "https://www.pgdp.org/api/v1/projects/projectID44de3936807f1/wordlists/good" \
    -H "Accept: application/json" \
    -H "X-API-KEY: $API_KEY"
```

### Project updates

Update details about a specific project:
```bash
curl -i -X PUT "https://www.pgdp.org/api/v1/projects/projectID44de3936807f1" \
    -H "Content-Type: application/json" \
    -H "X-API-KEY: $API_KEY" \
    -d @data.json
```

Where `data.json` contains the fields you want to modify:
```json
{
    "title": "Updated project title",
    "difficulty": "hard"
}
```

### Project creates

Create a project:
```bash
curl -i -X POST "https://www.pgdp.org/api/v1/projects" \
    -H "Content-Type: application/json" \
    -H "X-API-KEY: $API_KEY" \
    -d @data.json
```

Where `data.json` contains at least all the required fields:
```json
{
    "title": "Project created from API",
    "author": "API Joe",
    "project_manager": "project_manager_name",
    "languages": [ "English" ],
    "difficulty": "average",
    "genre": "Other",
    "image_source": "_internal",
    "character_suites": ["basic-latin"]
}
```

### Project holds

Get a list of all holdable project states:
```bash
curl -i -X GET "https://www.pgdp.org/api/v1/projects/holdstates" \
    -H "Accept: application/json" \
    -H "X-API-KEY: $API_KEY"
```

Get the current hold states for a project:
```bash
curl -i -X GET "https://www.pgdp.org/api/v1/projects/projectID44de3936807f1/holdstates" \
    -H "Accept: application/json" \
    -H "X-API-KEY: $API_KEY"
```

Set a project's hold states:
```bash
curl -i -X PUT "https://www.pgdp.org/api/v1/projects/projectID44de3936807f1/holdstates" \
    -H "Accept: application/json" \
    -H "X-API-KEY: $API_KEY" \
    -d @data.json
```

Where `data.json` contains a list of new hold states which will override all
current holds for the project:
```json
[
    "P1.proj_waiting",
    "P1.proj_avail"
]
```