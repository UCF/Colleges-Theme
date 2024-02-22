# Colleges Theme

A responsive WordPress theme for UCF's college websites, built off of the [Athena Framework](https://ucf.github.io/Athena-Framework/).


## Installation Requirements

This theme is developed and tested against WordPress 4.9.8+ and PHP 5.3.x+.

### Required Plugins
These plugins *must* be activated for the theme to function properly.
* [Advanced Custom Fields PRO](https://www.advancedcustomfields.com/pro/)

### Supported Plugins
The plugins listed below are extended upon in this theme--this may include custom layouts for feeds, style modifications, etc. These plugins are not technically required on sites running this theme, and shouldn't be activated on sites that don't require their features. A plugin does not have to be listed here to be compatible with this theme.
* [Athena Shortcodes](https://github.com/UCF/Athena-Shortcodes-Plugin)
* [Automatic Sections Menus Shortcode](https://github.com/UCF/Section-Menus-Shortcode)
* [Gravity Forms](https://www.gravityforms.com/)
* [Page Links To](https://wordpress.org/plugins/page-links-to/)
* [UCF Degree Custom Post Type](https://github.com/UCF/UCF-Degree-CPT-Plugin)
* [UCF Departments Taxonomy](https://github.com/UCF/UCF-Departments-Tax-Plugin)
* [UCF Employee Type Taxonomy](https://github.com/UCF/UCF-Employee-Type-Tax-Plugin)
* [UCF Events](https://github.com/UCF/UCF-Events-Plugin)
* [UCF News](https://github.com/UCF/UCF-News-Plugin)
* [UCF Page Assets](https://github.com/UCF/UCF-Page-Assets-Plugin)
* [UCF Post List Shortcode (2.0.0+)](https://github.com/UCF/UCF-Post-List-Shortcode)
* [UCF People Custom Post Type](https://github.com/UCF/UCF-People-CPT)
* [UCF Section](https://github.com/UCF/UCF-Section-Plugin)
* [UCF Social](https://github.com/UCF/UCF-Social-Plugin)
* [UCF Spotlights](https://github.com/UCF/UCF-Spotlights-Plugin)
* [WP Allowed Hosts](https://github.com/UCF/WP-Allowed-Hosts)
* [WP Mail SMTP](https://wordpress.org/plugins/wp-mail-smtp/)
* [WP Shortcode Interface](https://github.com/UCF/WP-Shortcode-Interface)
* [Yoast SEO](https://wordpress.org/plugins/wordpress-seo/)


## Configuration
* Ensure that menus have been created and assigned to the Header Menu and Footer Menu locations.
* Import field groups (`dev/acf-fields.json`) using the ACF importer under Custom Fields > Tools.
* Set the homepage to a static page in Settings > Reading


## Development

Note that compiled, minified css and js files are included within the repo.  Changes to these files should be tracked via git (so that users installing the theme using traditional installation methods will have a working theme out-of-the-box.)

[Enabling debug mode](https://codex.wordpress.org/Debugging_in_WordPress) in your `wp-config.php` file is recommended during development to help catch warnings and bugs.

### Requirements
* node
* gulp-cli

### Instructions
1. Clone the Colleges-Theme repo into your development environment, within your WordPress installation's `themes/` directory: `git clone https://github.com/UCF/Colleges-Theme.git`
2. `cd` into the Colleges-Theme directory, and run `npm install` to install required packages for development into `node_modules/` within the repo
3. Copy `gulp-config.template.json`, make any desired changes, and save as `gulp-config.json`.
3. Run `gulp default` to process front-end assets.
4. If you haven't already done so, create a new WordPress site on your development environment, install the required plugins listed above, and set the Colleges Theme as the active theme.
5. Make sure you've done all the steps listed under "Configuration" above.
6. Run `gulp watch` to continuously watch changes to scss and js files.  If you enabled BrowserSync in `gulp-config.json`, it will also reload your browser when scss or js files change.
