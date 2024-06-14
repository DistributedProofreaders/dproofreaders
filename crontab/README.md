# Background Jobs

Files in this directory are used to run regular maintenance jobs. It is
expected that they will be run regularly via cron. The `SETUP/dp.cron`
file provides an example crontab indicating suggested run frequency.

All jobs use a common entrypoint, `run_background_job.php`, that is expected
to be run from the PHP CLI. Most jobs are executed directly from within the
PHP CLI context. Some jobs are best run through the web browser as they
manipulate files on the filesystem owned by the web browser user. These jobs
are automatically proxied through the web browser to the same entrypoint
script.

`run_background_job.php` is hardened to only allow localhost access to ensure
outside users do not execute jobs.
