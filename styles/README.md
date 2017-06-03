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
```
sudo npm install -g less
```

## File layout
CSS is broken down into three major sections:
* `global.less` primarily includes mix-ins and is unlikely to change much
* `layout.less` governs page layout details
* `themes/theme.less` governs colors, fonts, and other things impacted by themes

## Changing CSS
To update CSS, only update the .less files in the styles/ and styles/themes
directories. The .css files are automatically generated from the .less files.

To create the .css files, use the Makefile in the SETUP directory:
```
cd SETUP
make less
```

Check in both the .css and .less files. While it isn't ideal to have generated
code checked into source control, doing so means that consumers of the DP code
don't also have to install and use less to generate the CSS.
