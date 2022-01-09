<?php

function create_test_user($username)
{
    // Attempt to load our test user, if it exists don't create it
    $sql = sprintf("
        SELECT username
        FROM users
        WHERE username = '%s'
    ", DPDatabase::escape($username));
    $result = DPDatabase::query($sql);
    $row = mysqli_fetch_assoc($result);
    if (!$row) {
        $sql = sprintf("
            INSERT INTO users
            SET id = '%1\$s',
                real_name = '%1\$s',
                username = '%1\$s',
                email = '%1\$s@localhost'
        ", DPDatabase::escape($username));
        $result = DPDatabase::query($sql);
        if (!$result) {
            throw new Exception(sprintf("Unable to create test user %s", $username));
        }
    } else {
        mysqli_free_result($result);
    }
}

function delete_test_user($username)
{
    // remove the test user
    $sql = sprintf("
        DELETE FROM users
        WHERE id = '%s'
    ", DPDatabase::escape($username));
    $result = DPDatabase::query($sql);
    if (!$result) {
        throw new Exception(sprintf("Unable to delete test user %s", $username));
    }
}

function create_test_image_source($image_source)
{
    // Attempt to create the image source
    $sql = sprintf("
        SELECT code_name
        FROM image_sources
        WHERE code_name = '%s'
    ", DPDatabase::escape($image_source));
    $result = DPDatabase::query($sql);
    $row = mysqli_fetch_assoc($result);
    if (!$row) {
        $sql = sprintf("
            INSERT INTO image_sources
            SET code_name = '%1\$s',
                display_name = '%1\$s',
                full_name = '%1\$s',
                info_page_visibility = 1,
                is_active = 1
        ", DPDatabase::escape($image_source));
        $result = DPDatabase::query($sql);
        if (!$result) {
            throw new Exception(sprintf("Unable to create test image source %s", $image_source));
        }
    } else {
        mysqli_free_result($result);
    }
}

function delete_test_image_source($image_source)
{
    // remove the test image source
    $sql = sprintf("
        DELETE FROM image_sources
        WHERE code_name = '%s'
    ", DPDatabase::escape($image_source));
    $result = DPDatabase::query($sql);
    if (!$result) {
        throw new Exception(sprintf("Unable to delete test image source %s", $image_source));
    }
}
