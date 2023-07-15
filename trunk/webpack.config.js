const defaultConfig = require('@wordpress/scripts/config/webpack.config');
const MiniCssExtractPlugin = require("mini-css-extract-plugin");
const LiveReloadPlugin = require('webpack-livereload-plugin');
const DependencyExtractionWebpackPlugin = require('@wordpress/dependency-extraction-webpack-plugin');
const { CleanWebpackPlugin } = require('clean-webpack-plugin');
const path = require('path');
const globAll = require('glob-all');
const FixStyleOnlyEntriesPlugin = require('webpack-fix-style-only-entries');

const isProduction = process.env.NODE_ENV === 'development';

const getLiveReloadPort = (inputPort) => {
	const parsedPort = parseInt(inputPort, 10);
	return Number.isInteger(parsedPort) ? parsedPort : 35729;
};

function getEntries() {
    const out = {};
    const baseDirs = ['./assets/javascript/', './assets/scss/'];
    baseDirs.forEach(baseDir => {
        globAll.sync(path.join(baseDir, "**/*.@(js|scss)")).forEach(entry => {
            let relativePath = path.relative(baseDir, entry);
            let key = relativePath.replace(path.extname(entry), '');
            out[key] = '.' + path.sep + entry;
        });
    });
	return out;
}

module.exports = {
	...defaultConfig,
	entry: {
		...getEntries()
	},
	output: {
		filename: 'js/[name].js',
		path: path.resolve(process.cwd(), 'build')
	},
	module: {
		rules: [
			{
				test: /\.jsx?$/,
				exclude: /node_modules/,
				use: {
					loader: 'babel-loader',
					options: {
						presets: ['@babel/preset-react']
					}
				}
			},
			{
				test: /\.scss$/,
				use: [
					MiniCssExtractPlugin.loader,
					'css-loader',
					'sass-loader',
				],
			},
		],
	},
	plugins: [
		new CleanWebpackPlugin({
			cleanAfterEveryBuildPatterns: ['!fonts/**', '!images/**'],
		}),
		new FixStyleOnlyEntriesPlugin(),
		new MiniCssExtractPlugin({
			filename: ({ chunk }) => `css/${chunk.name.replace(/\\/g, '/')}.css`,
		}),
		process.env.WP_BUNDLE_ANALYZER && new BundleAnalyzerPlugin(),
		!isProduction &&
		new LiveReloadPlugin({
			port: getLiveReloadPort(process.env.WP_LIVE_RELOAD_PORT),
		}),
		!process.env.WP_NO_EXTERNALS &&
		new DependencyExtractionWebpackPlugin(),
	].filter(Boolean)
};
