# URL Loader

Btw: the url-loader has never used rework. Remember, the url-loader is not responsible for detecting url() references in CSS-files. The url-loader does not know about CSS, HTML or JavaScript. It just takes a file, checks its file size and either emits it to the output folder and returns the final url or just returns a data url.

# CSS Loader

The CSS loader takes a CSS file and returns the CSS with imports and url(...) resolved via webpack's require functionality.

Webpack's css loader is responsible for handling sourcemaps as well as resolving urls and paths in @import statement.

# Style Loader

The style loader takes CSS and actually inserts it into the page so that the styles are active on the page.

# Resolve URL Loader

Webpack loader that resolves relative paths in url() statements based on the original source file.


# Provide Plugin

The ProvidePlugin replaces a symbol in another source through the respective import, but does not expose the symbol on the global namespace.