=== TailPress – Tailwind for WordPress ===
Contributors: freshbrewedweb
Donate link: https://greghunt.dev/donate
Tags: tailwind, blocks, gutenberg, utility classes, css
Requires at least: 5.2
Requires PHP: 7.0
Tested up to: 6.1
Stable tag: 0.4.4
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

* Extracts classes from pages and compiles them server-side into Tailwind CSS
* Caches the dynamically generated CSS to avoid performance issues on the frontend of your production site. 

https://youtu.be/qDZ3_Z7MXPM


== Frequently Asked Questions ==

=== Are you affiliated with tailpress.io the theme? ===
No, we are not! Unfortunately the same name was used for both out of coincidence, but we are not affiliated and are two different solutions. [Tailpress.io](https://tailpress.io) is a great starting point for developing custom themes with deep integration of Tailwind. I would recommend it for any developer that wants to build a custom theme that depends heavily on Tailwind. 

Tailpress, this plugin, adds Tailwind support to any project as an add-on. It can be used as much or as little as you need, added to custom themes or pre-existing ones.

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

### Device Preview Mode in Block Editor

The non-desktop preview modes in the block editor utilize an iframe and [don't load assets properly](https://github.com/WordPress/gutenberg/issues/38673). Therefore your Tailwind styles won't be visible here.

### Page Caching

If you have any page caching on your site, you'll have to clear your cache for the best experience. 

== Changelog ==

= 0.4.4 =
* Removed Hashing from Twind config
* Better autoload with __DIR__

= 0.4.3 =
* Compatibility with PHP 7.0

= 0.4.1 =
* Fixed syntax error in Twind init
* Upgraded Twind CDN library to 1.0.8

= 0.4.0 =
* Added a cleanup plugin data option
* Better JSON editor
* Clear the cache when config is updated.

= 0.3.2 =
* Updated Twind script not to include presets

= 0.3.1 =
* Fixed trailing comma in function for better PHP support

= 0.3.0 =
* Added a clear cache button.
* Use a remote service for compiling CSS.
* Added disclaimer about tailpress.io

= 0.2.0 =
* Added a settings page to configure Tailwind.

= 0.1.2 =
* Fixed interferance in admin panel

= 0.1.1 =
* Fixed buffer notice

= 0.1.0 =
* First version. 