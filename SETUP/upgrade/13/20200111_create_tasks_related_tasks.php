<?php
$relPath='../../../pinc/';
include_once($relPath.'base.inc');

header('Content-type: text/plain');

// ------------------------------------------------------------

echo "Creating new table tasks_related_tasks\n";

$sql = "
    CREATE TABLE `tasks_related_tasks` (
        `task_id_1` mediumint(9) NOT NULL,
        `task_id_2` mediumint(9) NOT NULL,
        PRIMARY KEY (`task_id_1`,`task_id_2`),
        KEY `task_id_2` (`task_id_2`)
    ) CHARSET=latin1;
";

echo "$sql\n";

mysqli_query(DPDatabase::get_connection(), $sql) or die( mysqli_error(DPDatabase::get_connection()) );

// ------------------------------------------------------------

$sql = "
    SELECT task_id, related_tasks
    FROM tasks
    ORDER BY task_id
";

$result = mysqli_query(DPDatabase::get_connection(), $sql)
    or die( mysqli_error(DPDatabase::get_connection()) );

while($row = mysqli_fetch_assoc($result))
{
    $task_id = $row['task_id'];

    // decode related_tasks and populate tasks_related_tasks
    $related_tasks = unserialize(base64_decode($row['related_tasks']));
    if(!$related_tasks)
        continue;

    foreach($related_tasks as $related_task_id)
    {
        insert_related_task($task_id, $related_task_id);
    }
}

// ------------------------------------------------------------

echo "Dropping column tasks.related_tasks\n";

$sql = "
    ALTER TABLE tasks
        DROP COLUMN related_tasks
";

echo "$sql\n";

mysqli_query(DPDatabase::get_connection(), $sql) or die( mysqli_error(DPDatabase::get_connection()) );

// ------------------------------------------------------------

echo "\nDone!\n";

// ------------------------------------------------------------

function insert_related_task($task1, $task2)
{
    $task_id_1 = min($task1, $task2);
    $task_id_2 = max($task1, $task2);

    // See if the association already exists
    $sql = "
        SELECT COUNT(*) AS count
        FROM tasks_related_tasks
        WHERE task_id_1 = $task_id_1
            AND task_id_2 = $task_id_2
    ";

    $result = mysqli_query(DPDatabase::get_connection(), $sql)
        or die( mysqli_error(DPDatabase::get_connection()) );
    $row = mysqli_fetch_assoc($result);
    if($row['count'] > 0)
        return;

    // Now do the insertion
    echo "Inserting pair ($task_id_1, $task_id_2)...\n";
    $sql = "
        INSERT INTO tasks_related_tasks
        SET task_id_1 = $task_id_1, task_id_2 = $task_id_2
    ";
    $result = mysqli_query(DPDatabase::get_connection(), $sql)
        or die( mysqli_error(DPDatabase::get_connection()) );
}

// vim: sw=4 ts=4 expandtab
