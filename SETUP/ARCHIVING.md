# Project Archiving

After projects are completed, they can be archived. This includes moving the
project files into a separate archive directory, moving the project tables into
a separate archive database, and moving a project's page_events and
wordcheck_events into the archive database.

The database instructions in this file resemble those for the main database. See
[INSTALL.md](INSTALL.md) for more information on how to run database commands.

To enable archiving you must set up the archive database and the archive
project directory.

1. Set the two archive settings in `configuration.sh`:

   * `_ARCHIVE_DB_NAME` sets the name of the archive database. In the rest of
     this document we use the name `dp_archive` for this value.

   * `_ARCHIVE_PROJECTS_DIR` sets the directory where project filesystem
     artifacts are moved.

2. Run the `configure` script to update the site configuration with the new
   archive settings. See [INSTALL.md](INSTALL.md) for more information on how
   to do this.

3. Create the database and grant access to it using the **same credentials used
   for the primary database**. For example, from within a `mysql` client
   session:
   ```sql
   CREATE DATABASE dp_archive CHARACTER SET utf8mb4;
   GRANT ALL  ON dp_archive.* TO dp_user@localhost IDENTIFIED BY 'dp_password';
   ```

4. Create the `page_events` and `wordcheck_events` table in the archive
   database. Find the 'CREATE TABLE' commands for those 2 tables in
   `db_schema.sql`. These commands create those tables in the main database;
   the corresponding tables in the archive database have exactly the same
   structure. So to create them, make the archive db the current database
   (e.g. `use dp_archive`), and then run those 2 'CREATE TABLE' commands.

5. Create the archive directory referenced in `_ARCHIVE_PROJECTS_DIR`. A common
   location for this is in the document root at the same level as the `projects`
   directory. (It doesn't have to be in the document root; the DP code doesn't
   assume that it's web-accessible. But you might choose to make it so [for
   some reason].) Ensure that this directory is owned and writable by the web
   server. For example:
   ```bash
   mkdir /var/www/htdocs/project.archives
   chown -R www-data:www:data /var/www/htdocs/project.archives
   ```

Now that the archive database and filesystem directory are set up, you can
enable periodic archiving by calling `crontab/archive_projects.php` via cron.
The `dp.cron` crontab file includes a commented-out example.

[`crontab/archive_projects.php`](../crontab/archive_projects.php) controls
which projects are archived (and when), and the logic to do the archiving is in
[`pinc/archiving.inc`](../pinc/archiving.inc).
