# API User's Guide

The DP code provides a RESTful API for accessing various bits of data
programmatically using a well-defined structure instead of HTML scraping.

## RESTful API

The DP API is a RESTful API. Among other things, this means that all requests
to the API will be via URL (possibly with JSON request body) and all responses
-- including error messages -- will be JSON objects. Additional metadata may
be included in the HTML response headers, such as pagination and rate limiting
details.

## Documentation

The [`dp-openapi.yaml`](dp-openapi.yaml) file should always contain the
definitive OpenAPI specification on what is currently implemented. This file
can be viewed with the [Swagger Editor](https://editor.swagger.io/)
(File -> Import URL and paste in [the URL from github](https://raw.githubusercontent.com/DistributedProofreaders/dproofreaders/master/api/dp-openapi.yaml))
and allows trying out endpoints. The Swagger Editor doesn't show HTTP response
headers or allow the inclusion of pagination details on applicable endpoints
and is not a complete solution for an API client.

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
will include three HTTP headers to let the client know how many more requests
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

Some requests, like project searches, may return paginated results in the
format of [RFC-8288](https://datatracker.ietf.org/doc/html/rfc8288) -- the same
format used by GitHub APIs. The GitHub REST API docs include a great guide on
[traversing with pagination](https://docs.github.com/en/rest/guides/traversing-with-pagination)
using this format.

Paginated responses will include the following HTTP headers:

* `X-Results-Total` - total number of entries available in the result set
* `Link` - links to walk through the paginated results

The `Link` header includes URLs labeled with metadata that can be used by
clients to navigate the result set. The format looks like (lines wrapped here
for readability):
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

Here are some examples using `curl` against the `pgdp.org` _test server_. Data
on the test server changes often and the examples below may not always return
results but the queries should always succeed and respond with a 200.

See the [`dp-openapi.yaml`](dp-openapi.yaml) file for all available endpoints
and their parameters and return values.

All examples assume your API key is set in `$API_KEY`, eg:

```bash
export API_KEY=xxxxxxxx
```

### Project searches

Use URL parameters to search for projects against the `v1/projects` endpoint.
To send multiple values of the same parameter to OR together, append the name
with `[]` (e.g. `projectid[]=`). Unique parameters are ANDed together.

Search for all projects with genre `Other`:

```bash
curl -i -H "Accept: application/json" -H "X-API-KEY: $API_KEY" \
    -X GET "https://www.pgdp.org/api/v1/projects?genre=Other"
```

Search for all projects with genre `Science` or `Sports`:

```bash
curl -i -H "Accept: application/json" -H "X-API-KEY: $API_KEY" \
    -X GET "https://www.pgdp.org/api/v1/projects?genre[]=Science&genre[]=Sports"
```

Search for all projects with the word `Monster` in their title:

```bash
curl -i -H "Accept: application/json" -H "X-API-KEY: $API_KEY" \
    -X GET "https://www.pgdp.org/api/v1/projects?title=Monster"
```

Search for all projects with genre `Science` or `Sports` and with `Monster`
in their title, ie `(genre = Science OR genre = Sports) AND (title like %Monster%)`:

```bash
curl -i -H "Accept: application/json" -H "X-API-KEY: $API_KEY" \
    -X GET "https://www.pgdp.org/api/v1/projects?genre[]=Science&genre[]=Sports&title=Monster"
```

To limit which project fields are included, use the `field` parameter. Request
multiple fields by appending `[]` to the parameter name and passing multiple
of them.

To return only the `projectid` and `title` fields:
```bash
curl -i -H "Accept: application/json" -H "X-API-KEY: $API_KEY" \
    -X GET "https://www.pgdp.org/api/v1/projects?field[]=projectid&field[]=title"
```

### Project details

Get details about a specific project:

```bash
curl -i -H "Accept: application/json" -H "X-API-KEY: $API_KEY" \
    -X GET "https://www.pgdp.org/api/v1/projects/projectID44de3936807f1"
```

See all pages in a project:

```bash
curl -i -H "Accept: application/json" -H "X-API-KEY: $API_KEY" \
    -X GET "https://www.pgdp.org/api/v1/projects/projectID44de3936807f1/pages"
```

Get the OCR text for a project page:

```bash
curl -i -H "Accept: application/json" -H "X-API-KEY: $API_KEY" \
    -X GET "https://www.pgdp.org/api/v1/projects/projectID44de3936807f1/pages/001.png/pagerounds/OCR"
```

Get the good wordlist for a project:

```bash
curl -i -H "Accept: application/json" -H "X-API-KEY: $API_KEY" \
    -X GET "https://www.pgdp.org/api/v1/projects/projectID44de3936807f1/wordlists/good"
```

Update the project's good wordlist with an entirely new set:

```bash
curl -i -H "Accept: application/json" -H "X-API-KEY: $API_KEY" \
    -X PUT "https://www.pgdp.org/api/v1/projects/projectID44de3936807f1/wordlists/good" \
    -d @data.json
```

Where `data.json` contains the new word list:
```json
[
    "word1",
    "word2"
]
```

### Project updates

Update details about a specific project:
```bash
curl -i -H "Accept: application/json" -H "X-API-KEY: $API_KEY" \
    -X PUT "https://www.pgdp.org/api/v1/projects/projectID44de3936807f1" \
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
curl -i -H "Accept: application/json" -H "X-API-KEY: $API_KEY" \
    -X POST "https://www.pgdp.org/api/v1/projects" \
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
curl -i -H "Accept: application/json" -H "X-API-KEY: $API_KEY" \
    -X GET "https://www.pgdp.org/api/v1/projects/holdstates"
```

Get the current hold states for a project:
```bash
curl -i -H "Accept: application/json" -H "X-API-KEY: $API_KEY" \
    -X GET "https://www.pgdp.org/api/v1/projects/projectID44de3936807f1/holdstates"
```

Set a project's hold states:
```bash
curl -i -H "Accept: application/json" -H "X-API-KEY: $API_KEY" \
    -X PUT "https://www.pgdp.org/api/v1/projects/projectID44de3936807f1/holdstates" \
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