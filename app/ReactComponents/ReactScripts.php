<?php

namespace App\ReactComponents;

class ReactScripts
{
    /**
     * Check if current page contains any react components
     */
    public static function pageHasReactComponents()
    {
        $page_components = get_field_object('page_components', get_the_ID());

        return !empty(array_column($page_components['layouts'], 'include_react'));
    }

    /**
     * Is this a development environment?
     *
     * @return bool
     */
    private static function isDevelopment()
    {
        return 'development' === WP_ENV;
    }

    /**
     * Attempt to load a file at the specified path and parse its contents as JSON.
     *
     * @param string $path The path to the JSON file to load.
     * @return array|null;
     */
    private static function loadAssetFile($path)
    {
        if (!file_exists($path)) {
            return null;
        }
        $contents = file_get_contents($path);
        if (empty($contents)) {
            return null;
        }
        return json_decode($contents, true);
    }

    private static function getAssetsList()
    {
        $assetsDir = sprintf('%s/react-components', get_stylesheet_directory());

        if (self::isDevelopment()) {
            $dev_assets = self::loadAssetFile(sprintf('%s/asset-manifest.json', $assetsDir));
            // Fall back to build directory if there is any error loading the development manifest.
            if (!empty($dev_assets)) {
                return array_values($dev_assets);
            }
        }

        $production_assets = self::loadAssetFile(sprintf('%s/build/asset-manifest.json', $assetsDir));

        if (!empty($production_assets)) {
            // Prepend "build/" to all build-directory array paths.
            return array_map(
                function ($asset_path) {
                    return 'build/' . $asset_path;
                },
                array_values($production_assets)
            );
        }

        return null;
    }

    /**
     * Infer a base web URL for a file system path.
     *
     * @param string $path Filesystem path for which to return a URL.
     * @return string|null
     */
    private static function inferBaseUrl(string $path)
    {
        $path = wp_normalize_path($path);

        $stylesheet_directory = wp_normalize_path(get_stylesheet_directory());
        if (strpos($path, $stylesheet_directory) === 0) {
            return get_theme_file_uri(substr($path, strlen($stylesheet_directory)));
        }

        $template_directory = wp_normalize_path(get_template_directory());
        if (strpos($path, $template_directory) === 0) {
            return get_theme_file_uri(substr($path, strlen($template_directory)));
        }

        return '';
    }

    /**
     * Return web URIs or convert relative filesystem paths to absolute paths.
     *
     * @param string $asset_path A relative filesystem path or full resource URI.
     * @param string $base_url   A base URL to prepend to relative bundle URIs.
     * @return string
     */
    private static function getAssetUri(string $asset_path, string $base_url)
    {
        if (strpos($asset_path, '://') !== false) {
            return $asset_path;
        }

        return trailingslashit($base_url) . $asset_path;
    }

    /**
     * @param string $directory Root directory containing `src` and `build` directory.
     * @param array $opts {
     *     @type string $base_url Root URL containing `src` and `build` directory. Only needed for production.
     *     @type string $handle   Style/script handle. (Default is last part of directory name.)
     *     @type array  $scripts  Script dependencies.
     *     @type array  $styles   Style dependencies.
     * }
     */
    public static function enqueueAssets($directory, $opts = [])
    {
        $defaults = [
            'base_url' => '',
            'handle'   => basename($directory),
            'scripts'  => [],
            'styles'   => [],
        ];

        $opts = wp_parse_args($opts, $defaults);

        $assets = self::getAssetsList();

        $base_url = $opts['base_url'];
        if (empty($base_url)) {
            $base_url = self::inferBaseUrl($directory);
        }

        if (empty($assets)) {
            // TODO: This should be an error condition.
            return;
        }

        // There will be at most one JS and one CSS file in vanilla Create React App manifests.
        $has_css = false;
        foreach ($assets as $asset_path) {
            $is_js = preg_match('/\.js$/', $asset_path);
            $is_css = preg_match('/\.css$/', $asset_path);

            if (!$is_js && !$is_css) {
                // Assets such as source maps and images are also listed; ignore these.
                continue;
            }

            if ($is_js) {
                wp_enqueue_script(
                    $opts['handle'],
                    self::getAssetUri($asset_path, $base_url),
                    $opts['scripts'],
                    null,
                    true
                );
            } elseif ($is_css) {
                $has_css = true;
                wp_enqueue_style(
                    $opts['handle'],
                    self::getAssetUri($asset_path, $base_url),
                    $opts['styles']
                );
            }
        }

        // Ensure CSS dependencies are always loaded, even when using CSS-in-JS in
        // development.
        if (!$has_css) {
            \wp_register_style(
                $opts['handle'],
                null,
                $opts['styles']
            );
            \wp_enqueue_style($opts['handle']);
        }
    }
}
