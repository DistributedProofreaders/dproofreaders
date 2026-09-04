<?php

// forum_interface_json.inc

// This is a _test only_ mock forum used for testing only. This should not
// be used in production! It just reads and writes to two JSON files.

// Confirm we are only testing
if (!SiteConfig::get()->testing) {
    throw new Error("JSON forum type can only be used during testing!");
}

class ForumJson
{
    protected string $json_file;

    protected function configure(string $json_file): void
    {
        $this->json_file = $json_file;
    }

    protected function save_to_json(mixed $data): bool
    {
        return file_put_contents(
            $this->json_file,
            json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)
        ) !== false;
    }

    protected function load_from_json(): mixed
    {
        if (! is_file($this->json_file)) {
            return [];
        }

        return json_decode(file_get_contents($this->json_file), true);
    }
}

class ForumJsonUser extends ForumJson
{
    public function __construct(?string $json_file = null)
    {
        if ($json_file) {
            $this->configure($json_file);
        } else {
            $this->configure(SiteConfig::get()->forums_json_users);
        }
    }

    public function add(string $username, string $password, string $email): bool
    {
        $users = $this->load_from_json();

        // confirm the user doesn't already exist
        if (isset($users[$username])) {
            return false;
        }

        // calculate the next numeric ID
        $id = 0;
        foreach (array_keys($users) as $key) {
            if ($users[$key]["id"] > $id) {
                $id = $users[$key]["id"];
            }
        }

        $users[$username] = [
            "id" => $id + 1,
            "password" => $password,
            "email" => $email,
        ];

        return $this->save_to_json($users);
    }

    /**
     * @return ?array<string, mixed>
     */
    public function load(string $username): ?array
    {
        $users = $this->load_from_json();
        return $users[$username] ?? null;
    }
}

class ForumJsonPost extends ForumJson
{
    public function __construct(?string $json_file = null)
    {
        if ($json_file) {
            $this->configure($json_file);
        } else {
            $this->configure(SiteConfig::get()->forums_json_posts);
        }
    }

    /**
     * @return array<string, mixed>
     */
    public function add_topic(string $subject, string $text, string $poster): array
    {
        return $this->add_post(null, $subject, $text, $poster);
    }

    /**
     * @return array<string, mixed>
     */
    public function add_post(?int $topic_id, string $subject, string $text, string $poster): array
    {
        $posts = $this->load_from_json();

        // calculate the next numeric post ID
        $id = max(array_keys($posts)) + 1;

        // if topic_id is NULL, calculate the next topic_id as we're
        // adding a new topic
        if ($topic_id === null) {
            $topic_id = 0;
            foreach ($posts as $post) {
                if ($post["topic_id"] > $topic_id) {
                    $topic_id = $post["topic_id"];
                }
            }
            $topic_id += 1;
        }

        $posts[$id] = [
            "id" => $id,
            "topic_id" => $topic_id,
            "poster" => $poster,
            "subject" => $subject,
            "text" => $text,
            "created" => time(),
        ];

        $this->save_to_json($posts);

        return $posts[$id];
    }

    /**
     * @return ?mixed[]
     */
    public function load_topic(int $topic_id): ?array
    {
        $posts = $this->load_from_json();

        $topic_posts = [];
        foreach ($posts as $post) {
            if ($post["topic_id"] == $topic_id) {
                $topic_posts[] = $post;
            }
        }

        if ($topic_posts) {
            return $topic_posts;
        } else {
            return null;
        }
    }

    public function load_post(int $post_id): mixed
    {
        $posts = $this->load_from_json();
        return $posts[$post_id] ?? [];
    }
}

function forum_password_hash(string $password): string
{
    return password_hash($password, PASSWORD_DEFAULT);
}

function create_forum_user(string $username, string $password, string $email, bool $password_is_digested = false): string|true
{
    if (!$password_is_digested) {
        $password = forum_password_hash($password);
    }
    $userManager = new ForumJsonUser();
    if ($userManager->add($username, $password, $email) !== true) {
        return "user creation error";
    } else {
        return true;
    }
}

/**
 * @return list{bool, string}
 */
function login_forum_user(string $username, string $password): array
{
    $userManager = new ForumJsonUser();
    $user = $userManager->load($username);
    if ($user !== null) {
        if (password_verify($password, $user["password"])) {
            return [true, ''];
        } else {
            return [false, 'incorrect_password'];
        }
    } else {
        return [false, 'incorrect_username'];
    }
}

function logout_forum_user(): void
{
}

function get_reset_password_url(): string
{
    return "";
}

/**
 * @return ?array<string, mixed>
 */
function get_forum_user_details(string $username): ?array
// Given a username, return details about the user.
// Returns an associative array. See $interested_columns for the keys.
// If the user isn't found, the function returns NULL.
{
    $interested_columns = [
        "id",         // forum user id
        "username",   // forum username
        "lastvisit",  // timestamp the user last visited
        "from",       // "location" in phpBB 3.1
        "occ",        // "occupation" in phpBB 3.1
        "interests",  // interests
        "viewemail",  // if their email address is viewable
        "email",      // email
        "avatar",     // filename of their avatar, if defined
        "icq",        // ICQ address
        "website",    // website URL
        "aim",        // AIM address ("aol" in phpBB 3.1)
        "yim",        // Yahoo address ("yahoo" in 3.1)
        "msnm",       // MS Messenger address
        "jabber",     // Jabber address
        "rank",       // forum rank (index)
        "posts",      // number of forum posts
        // phpBB 3.1 and later return these keys as well
        "location",   // location (same as from)
        "occupation", // occupation (same as occ)
        "yahoo",      // Yahoo address (same as yim)
        "aol",        // AIM address (same as aim)
        "facebook",   // Facebook username
        "googleplus", // Google+ username
        "skype",      // Skype username
        "twitter",    // Twitter username
        "youtube",    // YouTube username
        "avatar_type",   // what type of avatar is this? (upload, gravatar, remote)
        "avatar_width",  // width of avatar
        "avatar_height", // height of avatar
    ];

    $userManager = new ForumJsonUser();
    $user = $userManager->load($username);
    if (is_null($user)) {
        return null;
    }

    $return_data = [];

    foreach ($interested_columns as $column) {
        $return_data[$column] = $user[$column] ?? null;
    }

    return $return_data;
}

/**
 * Given a forum username, return the forum user ID.
 */
function get_forum_user_id(string $username): ?int
{
    // Use a local in-memory cache so we don't pummel the database
    // for pages that end up calling this for the same small set
    // of users over and over. Cache is local to this function.
    static $uidCache = [];
    // if it's in the cache, return it
    if (isset($uidCache[$username])) {
        return $uidCache[$username];
    }

    $userManager = new ForumJsonUser();
    $user = $userManager->load($username);

    if (!$user) {
        return null;
    }

    // update the cache
    $uidCache[$username] = $user["id"];

    return $user["id"];
}

// @phpstan-ignore return.unusedType
function get_forum_rank_title(int $rank): ?string
// Given a forum rank number, return the text title of that rank.
{
    return "Tester";
}

function get_forum_email_address(string $username): string
// Given a forum username, return the email address.
{
    $user_details = get_forum_user_details($username);

    return $user_details["email"] ?? "";
}

function get_url_for_forum(): string
// Return a URL to the base of the forum
{
    return "";
}

function get_url_for_forum_user_login(): string
// Return a URL that a user can use to manually log in to the forums.
{
    return "";
}

function get_url_to_compose_message_to_user(string $username): string
// Given a forum username, return a URL that can be used to
// access a form to send the user a message.
{
    return "";
}

function get_url_to_view_forum(int $forum_id): string
// Given a forum id, return a URL that can be used to view the forum.
{
    return "";
}

function get_url_to_view_topic(int $topic_id): string
// Given a topic id, return a URL that can be used to view the topic.
{
    return "";
}

function get_url_to_view_post(int $post_id): string
// Given a post id, return a URL that can be used to view the post.
{
    return "";
}

// @phpstan-ignore return.unusedType
function get_url_for_user_avatar(string $username): ?string
// Given a forum username, return a URL that can be used to load the user's
// avatar. If no avatar is defined, this function returns NULL.
{
    return null;
}

function get_url_to_edit_profile(): string
// Return a URL that can be used to edit the current user's profile.
{
    return "";
}

function get_url_to_view_profile(int $user_id = 0): string
// Return a URL that can be used to view a given user's profile.
{
    return "";
}

function get_url_for_inbox(): string
// Return the URL for accessing the current user's inbox.
{
    return "";
}

/**
 * Return the URL for searching the forums
 */
function get_url_for_search(string $query): string
{
    return "";
}

// * @phpstan-ignore return.unusedType
function get_number_of_unread_messages(string $username): ?int
// Given a forum username, return the number of unread messages.
{
    return null;
}

/**
 * Confirm a specific topic ID exists.
 * Returns:
 *     FALSE - doesn't exist
 *     TRUE  - does exist
 */
function does_topic_exist(int $topic_id): bool
{
    $postManager = new ForumJsonPost();
    $posts = $postManager->load_topic($topic_id);

    if ($posts) {
        return true;
    } else {
        return false;
    }
}

/**
 * Given a forum topic, return the time of the last post
 * in UNIX time format (seconds since UNIX epoch).
 * If no topic is found, function returns NULL.
 */
function get_last_post_time_in_topic(int $topic_id): ?int
{
    $postManager = new ForumJsonPost();
    $posts = $postManager->load_topic($topic_id);

    if (!$posts) {
        return null;
    }

    $max_post_time = 0;
    foreach ($posts as $post) {
        if ($post["created"] > $max_post_time) {
            $max_post_time = $post["created"];
        }
    }

    return $max_post_time;
}

/**
 * @return ?array<string, mixed>
 *    Returns the following details about a topic as an associative array.
 *        topic_id         - the ID of the topic (for completeness)
 *        title            - the title of the topic
 *        num_replies      - the number of replies in the topic
 *        forum_name       - name of the forum the topic is in
 *        forum_id         - the id of the forum the topic is in
 *        creator_username - the username of the topic creator
 */
function get_topic_details(int $topic_id): ?array
{
    $postManager = new ForumJsonPost();
    $posts = $postManager->load_topic($topic_id);
    if (!$posts) {
        return null;
    }

    return [
        "topic_id" => $topic_id,
        "title" => $posts[0]["subject"],
        "num_replies" => count($posts) - 1,  // first post isn't a reply
        "forum_name" => "A Forum",
        "forum_id" => 0,
        "creator_username" => $posts[0]["poster"],
    ];
}

// ---------------------------------------------------------------------------

// functions from the original pinc/topic.inc

// Parameters for topic_create() and topic_add_post()
// are in the order Where, What, Who:
//     Where: $forum_id or $topic_id
//     What:  $post_subject, $post_text
//     Who:   $poster_name, $poster_is_real

// -----------------------------------------------------------------------------

function topic_create(
    int $forum_id,
    string $post_subject,
    string $post_text,
    string $poster_name,
    bool $poster_is_real,
    ?bool $make_poster_watch_topic
): ?int {
    $postManager = new ForumJsonPost();
    $topic = $postManager->add_topic($post_subject, $post_text, $poster_name);
    return $topic["topic_id"];
}

// -----------------------------------------------------------------------------

function topic_add_post(
    int $topic_id,
    string $post_subject,
    string $post_text,
    string $poster_name,
    bool $poster_is_real
): void {
    $postManager = new ForumJsonPost();
    $postManager->add_post($topic_id, $post_subject, $post_text, $poster_name);
}

// -----------------------------------------------------------------------------

function topic_change_forum(int $topic_id, int $to_forum_id): void
{
}

// -----------------------------------------------------------------------------

function forum_resynch(int $forum_id): void
{
}
