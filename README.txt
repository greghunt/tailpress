=== TailPress – Tailwind for WordPress ===
Contributors: blockpress
Donate link: https://blockpress.dev
Tags: tailwind, blocks, gutenberg, utility classes, css
Requires at least: 5.2
Tested up to: 6.0
Stable tag: 0.1.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Seamless integration of [Tailwind](https://tailwindcss.com/) for WordPress.

== Description ==

Tailwind is the perfect companion framework to elevate your WordPress development, especially in the new block editor. It's utility-first model encourages using just the right amount of classes that you may need. 

Unfortunately, up until now, integrating Tailwind with WordPress has been rather difficult due to having to compile the framework with Node.js.

This plugin allows solves this problem by:

* Using the official Tailwind CDN to dynamically add any class on the fly
* Caching the dynamically generated CSS to avoid performance issues on the frontend of your production site. 

== Installation ==

This section describes how to install the plugin and get it working.

1. Upload `tailpress` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Simply add classes anywhere you would like in your pages, for example under the "Additional CSS class(es)" of any of your blocks.
4. That's it! It should just work.

== Known Issues ==

The non-desktop preview modes in the block editor utilize an iframe and therefore your Tailwind styles won't be visible here.

== Changelog ==

= 0.1.0 =
* First version. 