# Distributed Proofreaders

_Preserving history, one page at a time._

This repository contains the code that powers https://www.pgdp.net and other
sister DP sites world-wide.

## About

Distributed Proofreaders is a web application (written in PHP and backed by a
MySQL database) that is intended to ease the process of converting public
domain books and other printed materials into e-texts.
The main site is at https://www.pgdp.net

By breaking the work into individual pages, many proofreaders can be working
on the same book at the same time. This significantly speeds up the
proofreading/E-Text creation process.

When a proofer elects to proofread a page for a particular project, the text
and image file are displayed on a single webpage. This allows the text file
to be easily reviewed and compared to the image file, thus assisting the
proofreading of the text file. The edited text is then submitted back to the
site via the same webpage that it was edited on.

Once all pages for a particular book have been processed, a concatenated text
file is made available for final clean-up and submitted to a
[Project Gutenberg](https://en.wikipedia.org/wiki/Project_Gutenberg#Affiliated_projects)
site.

## Installation

See the [installation guide](SETUP/INSTALL.md) for information on system
pre-requisites, installation instructions, and upgrading from an earlier release
of the code. [Additional documentation](SETUP/README.md) is available in the
`SETUP` directory.

If you need assistance with the code, inquire within the
[DP Site Code](https://www.pgdp.net/phpBB3/viewforum.php?f=32) forum at pgdp.net.

## Code development

To get involved with development on this code base, see
[DP Code Development](https://www.pgdp.net/wiki/DP_Code_Development) in the
pgdp.net wiki.

See also our [coding standards](SETUP/CODE_STYLE.md) and
[coding documentation](SETUP/CODE_DOCS.md).

## License

All source code published here is available under the terms of the
[GNU General Public License, version 2](license.txt).
