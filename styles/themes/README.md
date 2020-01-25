# Themes
The code supports a purely-CSS-based theming system.

## Creating a new theme
To create a new theme, add a new `.less` file in this directory with
`@import "theme.less";` at the end of it. See `project_gutenberg.less` for
an example.

To generate the corresponding `.css` file:
```bash
cd ../../SETUP
make less
```

## Adding a theme to the system
To add a new theme to the system, insert a row into the `themes` table.
The `unixname` field must match the basename of your CSS filename.

```sql
INSERT INTO themes SET
    name='Theme Name',
    unixname='css_base_filename',
    created_by='Your Username';
```

## Adding a theme to the code
To add the new theme as part of the DP code, you need to commit _both_ the
`.less` and the `.css` file. This ensures that other users of the code can
use the code as-is without generating the CSS from the LESS themselves.

In addition, update the `SETUP/db_schema.sql` file and add the INSERT
statement alongside the other default themes.

## Changing site's default theme
The default theme is `project_gutenberg`. If you want to change this, update
the default column value of the `users.i_theme` field to the desired theme's
unixname:

```sql
ALTER TABLE users ALTER i_theme SET DEFAULT 'royal_blues';
```

