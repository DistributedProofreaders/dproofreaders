# Random Rules

The `rules` table contains a set of rules from the Proofreading and Formatting
Guidelines. One rule is randomly shown to users on the P and F round pages at
every page load.

The rules are populated directly from the rendered HTML versions of the
guidelines via the Manage Random Rules page off the Site Admin UI. There
are two versions of HTML supported:

* HTML from the `faq/proofreading_guidelines.php` and `faq/formatting-guidelines.php`
  pages within the code.
* HTML from the [Proofreading Guidelines](https://www.pgdp.net/wiki/DP_Official_Documentation:Proofreading/Proofreading_Guidelines)
  and [Formatting Guidelines](https://www.pgdp.net/wiki/DP_Official_Documentation:Formatting/Formatting_Guidelines)
  from the pgdp.net Official Documentation in the DP wiki.

The Manage Random Rules page doesn't care which of the two it is, or where they
are located, as long as they are accessible by URL.

## Valid rule formats

Rules are parsed out of the raw HTML pages of the guildelines. In the HTML the
parser looks for and pulls out the following pieces of information:

* anchor - an HTML anchor for jumping to this rule on the page
* subject - name of the rule to present to the user
* rule - the contents of the rule itself

### Code-based guidelines

Rules in the code-based guidelines are assumed to be in the following format:

```html
<h3><a name="ANCHOR">SUBJECT</a></h3>
RULE (multiple lines) ...
<!-- END RR -->
```

The end of the rule is either the start of another rule, or the appearance of
`<!-- END RR -->`, whichever comes first.

`SUBJECT` should not contain formatting or markup of any kind, just text.

### Wiki-based guidelines

Rules in the wiki-based guidelines are assumed to be in the following wiki format:

```
<div id="ANCHOR"></div>
===SUBJECT===
RULE (multiple lines) ...
<div class="END RR"></div>
```

which is rendered into the following format which is the part that is parsed:

```html
<div id="ANCHOR"></div>
<h3><span class="mw-headline" id="...">SUBJECT</span> ... </h3>
RULE (multiple lines) ...
<div class="END RR"></div>
```

The end of the rule is either the start of another rule, or the appearance of
`<div class="END RR"></div>`, whichever comes first.

`SUBJECT` should not contain formatting or markup of any kind, just text.

