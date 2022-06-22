=== TailPress – Tailwind for WordPress ===
Contributors: blockpress
Donate link: https://blockpress.dev
Tags: tailwind, blocks, gutenberg, utility classes, css
Requires at least: 5.2
Requires PHP: 5.6
Tested up to: 6.0
Stable tag: 0.2.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Seamless integration of [Tailwind](https://tailwindcss.com/) for WordPress.

== Description ==

[Tailwind CSS](https://tailwindcss.com/) is a great companion to building block based sites in WordPress. Unfortunately, it's been pretty complicated to add Tailwind CSS to WordPress since it needs to be compiled with Node.js in order to be production ready. 

This plugin takes care of that by adding Tailwind CSS to WordPress in one easy step (activate the plugin) and at the same time takes care of performance and caching so your site remains fast and production ready!

=== Who It's For ===
This is primarily for developers and users that are familiar with Tailwind CSS and comfortable styling their site using their utility class framework. 

It's also for those who have struggled to add Tailwind to their workflow without having to constantly compile their CSS every time the classes in their page content changes.

=== How it Works ===

* Uses the official Tailwind CDN to dynamically add any class on the fly
* Caches the dynamically generated CSS to avoid performance issues on the frontend of your production site. 

https://youtu.be/SPYmJfExn-U


== Frequently Asked Questions ==

=== Can this be used in production? ===
Yes! Although the styles in the backend get generated on the fly, the CSS on the frontend gets cached for every page. Changing any classes on the page will bust the cache automatically so it'll still stay up to date with any changes.

=== Can this be used outside the block editor? ===
Yes! This will work anywhere on the backend or front end of your site that uses classes to style things. 

== Installation ==

This section describes how to install the plugin and get it working.

1. Upload `tailpress` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Simply add classes anywhere you would like in your pages, for example under the "Additional CSS class(es)" of any of your blocks.
4. That's it! It should just work.

Optionally configure your version of Tailwind under Settings > TailPress.

== Known Issues ==

The non-desktop preview modes in the block editor utilize an iframe and [don't load assets properly](https://github.com/WordPress/gutenberg/issues/38673). Therefore your Tailwind styles won't be visible here.

== Changelog ==

= 0.2.0 =
* Added a settings page to configure Tailwind.

= 0.1.2 =
* Fixed interferance in admin panel

= 0.1.1 =
* Fixed buffer notice

= 0.1.0 =
* First version. 