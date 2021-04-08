var webpack = require("webpack");
import MiniCssExtractPlugin from "mini-css-extract-plugin";

const isDev = process.argv.indexOf('-d') !== -1;

module.exports = {
	watch       : isDev,
	cache       : isDev,
	mode        : isDev ? "development" : "production",
	watchOptions: {
		ignored: /node_modules\/(?!(@anything-studio)\/).*/
	},
	devtool     : "source-map",
	stats       : "minimal",
	entry       : async function () {
		return __dirname + "/js/nette.config.js";
	},
	output      : {
		path    : __dirname + "/www/bundle",
		filename: isDev ? "dev.bundle.[name].js" : "bundle.[name].js"
	},
	plugins     : [
		new MiniCssExtractPlugin({
			filename: isDev ? "dev.bundle.[name].css" : "bundle.[name].css"
		}),
		new webpack.SourceMapDevToolPlugin({
			filename: "[file].map",
		}),
	],

	resolve      : {
		// Add '.ts' and '.tsx' as resolvable extensions.
		extensions: [".ts", ".tsx", ".js", ".json"]
	},
	resolveLoader: {
		modules: [
			'node_modules',
		]
	},

	module: {
		rules: [
			{
				test: /\.(jpe?g|png|gif|webp|eot|ttf|woff|woff2|svg|)$/i,
				use : [
					{loader: 'url-loader', options: {limit: 1000, name: 'assets/[name].[ext]'}} // assets/[name]-[hash].[ext] // přidá na konec názvu hash, Není pak nutné invalidovat obrázky
				]
			},
			{
				test: /\.css$/,
				use : [MiniCssExtractPlugin.loader, 'css-loader', 'resolve-url-loader']
			},
			{
				test: /\.(sass|scss)$/,
				use : [
					MiniCssExtractPlugin.loader,
					{
						loader : 'css-loader',
						options: {
							sourceMap: true
						}
					},
					'resolve-url-loader',
					{
						loader : 'sass-loader',
						options: {
							sourceMap: true
						}
					}
				]
			},
			{ // promenná Nette je globální i mimo bundle
				test: require.resolve('nette-forms'),
				use : [{
					loader : 'expose-loader',
					options: 'Nette'
				}]
			},
			{
				test   : /\.js$/,
				exclude: /node_modules\/(?!(@anything-studio|@google\/.*)\/).*/,
				use    : [
					{
						loader : "babel-loader",
						options: {
							presets     : [
								"@babel/preset-env",
							],
							"sourceType": "unambiguous",
							plugins     : [
								"@babel/plugin-proposal-class-properties",
								[
									"@babel/plugin-proposal-decorators",
									{
										legacy: true
									}
								],
								"@babel/plugin-transform-async-to-generator",
								"@babel/plugin-transform-classes",
								"@babel/plugin-transform-runtime",
								"@babel/plugin-transform-object-assign"
							]
						}
					}
				]
			},
			// build NeroComponent a následně ostatního typescriptu
			{
				test: /\.tsx?$/, use: [
					{loader: "awesome-typescript-loader"},
				]
			},

			// spracování source mapy
			{enforce: "pre", test: /\.js$/, loader: "source-map-loader"}
		]
	}
};
