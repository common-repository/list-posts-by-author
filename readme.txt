=== Plugin Name ===
Contributors: rudydrimar
Donate link: http://www.escritoenelagua.com/
Tags: posts, authors, lists
Requires at least: 2.7.1
Tested up to: 2.7.1
Stable tag: 1.0

Lists all the posts or the pages (or both of them) group by their authors 

== Description ==

Lists all the posts or the pages (of both), grouping them by their authors.

It permits to list the posts of all the authors of the blog, or only the author you choose.

It can be inserted into a post or a page, used in the sidebar or wherever you want in your template.


== Installation ==

* Upload `list-posts-by-auhtor` folder to the `/wp-content/plugins/` directory
* Activate the plugin through the 'Plugins' menu in WordPress
* Place `<?php list_posts_by_author('', '', '', '', '', '', '', '', ''); ?>` in your template
* Or type in a post or page (in HTML view) `[autores]`
* Configure the plugin through the "Options" section in you admin panell

== Frequently Asked Questions ==



== Screenshots ==

1. List of posts by one author, ordered by ascending date
2. List of posts by severall authors, ordered alphabetically ascending by title
3. Administration Panell



== A brief Markdown Example ==

If you want to view the list in a post or a page, you only have to type (in the HTML view) `[autores]`. Thus, you'll see the list exactly as you have configured it in the Options.

If you want yo use it somewhere else (p.e., your sidebar) just place `<?php list_posts_by_author('', '', '', '', '', '', '', '', '', ''); ?>` wherever you can see the results.

You may wanto to see severall lists: one for your posts, another for the pages of one of the other authors of the blog... You can parametrize the plugin:

Typing: `<?php list_posts_by_author('all', ''all', 'page', 'date', 'asc', 'ul', 'h3', '#000000', 'italic', '_self'); ?>` will show a list of pages by all authors, ordered by date, ascending, in a list, with Header 3 for the name of each author, and in black. Every page will be as a link in the same window.

Here is a complete list of the parameters and the values you can use:

* $elautor (the authors you want to see) = ""all" or the name-to-display of one of the authors of the blog
* $elnoautor (The authors yo do not want to see) = "all", "none" or the name-to-display of one of the authors of the blog
* $elquever (pages or posts) = "page", "post", "postpage"
* $elordenar (order criteria) = "date" "name"
* $elasc (order kind: ascending or descending) = "ASC", "DESC"
* $eltipolista (view the data in a list, a numbered list, or a table) = "ul", "ol", "tabla"
* $elcabest (header format for the name of each author) = "H1", "H2", "H3" (or, if you gave more headers in your template, you can use one of them)
* $elcabcolor (font color for the name of each author) = any color you like, in hexadecimal: #000000 for black and #FFFFFF for white
* $eldetest (text style for each post) = "em", "strong" or nothing
* $eldettipo (plain text or a link, for each post) = "tit", "`_self`", "`_blank`"