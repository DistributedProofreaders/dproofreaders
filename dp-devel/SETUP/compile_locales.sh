#!/bin/sh
#A script to compile all the .po locale files
#Run it with directory where they are located
#($dyn_locales_dir from pinc/site_vars.php) as the argument

find $1 -name messages.po |
sed "s/^\(.*\).po$/msgfmt \1.po -o \1.mo/" |
sh
