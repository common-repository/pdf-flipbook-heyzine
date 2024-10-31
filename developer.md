# Gutenberg development
[Development Environment](https://developer.wordpress.org/block-editor/getting-started/devenv/)

* Install Node.js v14. `nvm install 14`

Start scaffolding block `npx @wordpress/create-block heyzine-block` or better run `npx @wordpress/create-block` to create structure asking for all the data.

* `$ npm start` Starts the build for development.
* `$ npm run build` Builds the code for production.
* `$ npm run format` Formats files.
* `$ npm run lint:css` Lints CSS files.
* `$ npm run lint:js` Lints JavaScript files.
* `$ npm run plugin-zip` Creates a zip file for a WordPress plugin.
* `$ npm run packages-update` Updates WordPress packages to the latest version.

Install [WordPress componentes](https://wordpress.github.io/gutenberg/)

`npm install @wordpress/components --save`

# Publish plugin to WordPress repository

* [Github action](https://github.com/10up/action-wordpress-plugin-deploy) by 10up
* [Add new plugin to repository](https://wordpress.org/plugins/developers/add/)
