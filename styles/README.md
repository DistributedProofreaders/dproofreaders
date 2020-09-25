# DP Styles and Themes

CSS for the DP code is done using the [less CSS pre-processor](http://lesscss.org/).
This easily allows the creation of different themes with a common set of CSS
code.

## Installing less
less is a node.js application, so start by making sure you have a somewhat
recent version of [node.js](https://nodejs.org). They have
[packages](https://nodejs.org/en/download/package-manager) for different
operating systems, including Ubuntu.

After you have a recent version of node.js installed, use npm to install less:
```bash
sudo npm install -g less
```

## File layout
CSS is broken down into three major sections:
* `global.less` primarily includes mix-ins and is unlikely to change much
* `layout.less` governs page layout details
* `page_interfaces.less` controls styling of the various page interfaces

See the [Themes README](themes/README.md) for more information.

## Changing CSS
To update CSS, only update the .less files in the `styles/` and `styles/themes/`
directories. The `.css` files are automatically generated from the `.less` files.

To create the `.css` files, use the Makefile in the SETUP directory:
```bash
cd SETUP
make less
```

Check in both the `.css` and `.less` files. While it isn't ideal to have generated
code checked into source control, doing so means that consumers of the DP code
don't also have to install and use less to generate the CSS.

## Style design philosophy and demo
The `design_philosophy.php` and `style_demo.php` pages discuss some of the
thinking behind the site's design. You can view these pages rendered at pgdp.net
at:

* [Design Philosophy](https://www.pgdp.net/c/styles/design_philosophy.php)
* [Style Demo](https://www.pgdp.net/c/styles/style_demo.php)

