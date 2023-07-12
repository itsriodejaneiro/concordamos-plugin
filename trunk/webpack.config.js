const defaultConfig = require('@wordpress/scripts/config/webpack.config');
const MiniCssExtractPlugin = require("mini-css-extract-plugin");
const LiveReloadPlugin = require('webpack-livereload-plugin');
const DependencyExtractionWebpackPlugin = require('@wordpress/dependency-extraction-webpack-plugin');
const { CleanWebpackPlugin } = require('clean-webpack-plugin');
const path = require('path');
const globAll = require('glob-all');

const isProduction = process.env.NODE_ENV === 'development';

const getLiveReloadPort = (inputPort) => {
	const parsedPort = parseInt(inputPort, 10);
	return Number.isInteger(parsedPort) ? parsedPort : 35729;
};

function getEntries() {
	const out = {};
	globAll.sync("./blocks/**/{index,script,front}.js").forEach(entry => {
		out[entry.split('/')[2] + "-" + entry.split('/')[3].split(".")[0]] = entry;
	});
	return out;
}

function getScssEntries() {
	const scssFiles = globAll.sync(['./assets/scss/**/*.scss']);
	const entries = {};

	scssFiles.forEach(file => {
		const entryName = path.basename(file, path.extname(file));
		entries[entryName] = file;
	});

	return entries;
}

module.exports = {
	...defaultConfig,
	entry: {
		...getEntries(),
		...getScssEntries()
	},
	output: {
		filename: 'js/[name]/[name].js',
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
		new MiniCssExtractPlugin({
			filename: 'css/[name].css'
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
