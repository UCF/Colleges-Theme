# Colleges Theme

A responsive WordPress theme for UCF's college websites, built off of the [Athena Framework](https://github.com/UCF/Athena-Framework).


## Installation Requirements

This theme is developed and tested against WordPress 4.7.3+ and PHP 5.3.x+.

### Required Plugins
* Advanced Custom Fields PRO

### Recommended Plugins
* Athena Shortcodes
* Automatic Sections Menu Shortcode
* Gravity Forms
* Page Links To
* UCF Degree Custom Post Type
* UCF Departments Taxonomy
* UCF Employee Types Taxonomy
* UCF Events
* UCF News
* UCF Page Assets
* UCF Post List Shortcode (2.0.0+)
* UCF People Custom Post Type
* UCF Section
* UCF Social
* UCF Spotlight
* Varnish Dependency Purger or UCF WordPress Varnish as a Service
* WP Allowed Hosts
* WP Mail SMTP
* WP Shortcode Interface
* Yoast SEO


## Configuration
* Ensure that menus have been created and assigned to the Header Menu and Footer Menu locations.
* Import field groups (`dev/acf-fields.json`) using the ACF importer under Custom Fields > Tools.
* If you have a [Cloud.Typography](https://www.typography.com/cloud/welcome/) account, ensure that webfonts have been properly configured to a CSS Key that [allows access to your environment](https://dashboard.typography.com/user-guide/managing-domains).


## Development

Note that compiled, minified css and js files are included within the repo.  Changes to these files should be tracked via git (so that users installing the theme using traditional installation methods will have a working theme out-of-the-box.)

[Enabling debug mode](https://codex.wordpress.org/Debugging_in_WordPress) in your `wp-config.php` file is recommended during development to help catch warnings and bugs.

### Requirements
* node
* gulp

### Instructions
1. Clone the Colleges-Theme repo into your development environment, within your WordPress installation's `themes/` directory: `git clone https://github.com/UCF/Colleges-Theme.git`
2. `cd` into the Colleges-Theme directory, and run `npm install` to install required packages for development into `node_modules/` within the repo
3. Copy `gulp-config.template.json`, make any desired changes, and save as `gulp-config.json`.
3. Run `gulp default` to process front-end assets.
4. If you haven't already done so, create a new WordPress site on your development environment, install the required plugins listed above, and set the Colleges Theme as the active theme.
5. Make sure you've done all the steps listed under "Configuration" above.
6. Run `gulp watch` to continuously watch changes to scss and js files.  If you enabled BrowserSync in `gulp-config.json`, it will also reload your browser when scss or js files change.
