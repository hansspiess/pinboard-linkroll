Pinboard Linkroll
=================

This WordPress plugin showcases a [pinboard.in](https://pinboard.in) linkroll as a widget. The feeds of multiple pinboard.in users can be merged and filtered by their tags.

## Why another WordPress RSS widget?

Yes, WordPress has a core RSS widget, there's a [plethora of plugins](https://wordpress.org/plugins/search.php?q=rss+widget) and there's a bunch of [single purpose tools](https://pinboard.in/resources/) for integrating Pinboard feeds into WordPress. But after checking them (at least, some of them) i found no solution with my desired feature set – so i decided to build one myself.

## Features

* display one or multiple pinboard.in users rss feeds
* filter by their tags
* complete control over markup and styling
* customizable cache lifetime

## Customization

At the moment there are 3 filter hooks for customizing Pinboard Linkroll without having to fork it.

* `pinboard_linkroll_cache_lifetime( $seconds )`
* `pinboard_linkroll_template( $path )`

### Modifying the feed cache lifetime

The plugin uses WordPress' fetch_feed(), which uses the WordPress Transients API with a default lifetime of 12 hours. If, for testing purposes or to face frequent updates, this has to be changed, it can be done from the theme's `functions.php`:

```php
function change_pinboard_linkroll_cache_lifetime( $seconds ) {
  return 1800;
}
add_filter( 'pinboard_linkroll_cache_lifetime', 'change_pinboard_linkroll_cache_lifetime' );
```

### Overriding the plugin template

To override the [default template](https://github.com/hansspiess/pinboard-linkroll/blob/master/widget/partials/pinboard-linkroll-widget-public.php) with a path (which has to be relative to the active theme's path), paste the following into the theme's `functions.php` and save the new template file to the specified location: 

```php
function change_pinboard_linkroll_template( $path ) {
  return 'templates/widget-pinboard-linkroll.php';
}
add_filter( 'pinboard_linkroll_template', 'change_pinboard_linkroll_template' );
```

## Changelog

### 1.0.2

* removed `pinboard_linkroll_css( $path )` and the loading of any css file
* dropped bootstrap classes in favour of smacss classes in default template file

### 1.0.1

* added private method `_is_pinboard_username()` to check against pinboard.in username scheme
* changed admin form 
  * major wording changes
  * made link count option a select
  * form is now 400px wide
* added output escaping
* i10n changes
* README completion

### 1.0.0

Initial Commit.
