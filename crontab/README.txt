Scripts in this directory are used for regular maintenance operations. It is
expected that they will be run regularly via cron. The SETUP/dp.cron
script provides an example crontab indicating suggested run frequency.

Scripts are designed to be run through the web browser to reduce file
permission problems, so they should remain in the web space and accessed
via URL, not from the PHP command line interpreter. Scripts check that they
are only being requested by localhost to ensure outside users do not execute
them.

---------------------------------------
Script development notes

The function require_localhost_request(), located in misc.inc, should be used
at the top of each script to enforce that they are only run locally.

Usually, the output of the scripts are collected by crontab and emailed to the
user. With this in mind they should only output upon error unless you think the
system user receiving crontab notifications will want to know about it. When
outputting strings, use plain text, not HTML.
