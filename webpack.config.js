var path = require('path');
var webpack = require('webpack');
var ExtractTextPlugin = require('extract-text-webpack-plugin');
var CleanWebpackPlugin = require('clean-webpack-plugin');
var BrowserSyncPlugin = require('browser-sync-webpack-plugin');

var config = require('./config.json');

var extractPlugin = new ExtractTextPlugin({
	filename: 'app.css'
});

module.exports = {
	entry: {
		app: [
			'./resources/assets/scripts/app.js',
			'./resources/assets/styles/app.scss'
		]
	},
	output: {
		path: path.resolve(__dirname, 'dist'),
		filename: 'app.js',
		publicPath: '/dist'
	},
	module: {
		rules: [
			{
				test: /\.js$/,
				exclude: [
					'node_modules',
					'bower_components'
				],
				use: [
					{
						loader: 'babel-loader',
						options: {
							presets: ['es2015']
						} 
					}
				]
			},
			{
				test: /\.css$/,
				use: extractPlugin.extract({
					use: ['css-loader']
				})
			},
			{
				test: /\.scss$/,
				use: extractPlugin.extract({
					use: [
						'css-loader',
						'sass-loader'
					]
				})
			},
			{
				test: /\.(jpg|png|gif|jpeg|svg|bmp)$/,
				use: [
					{
						loader: 'file-loader',
						options: {
							name: '[name].[ext]',
							outputPath: 'images/',
							publicPath: ''
						}
					}
				]
			},
			{
				test: /\.(ttf|eot|otf|woff|svg|woff2)$/,
				use: [
					{
						loader: 'file-loader',
						options: {
							name: '[name].[ext]',
							outputPath: 'fonts/',
							publicPath: ''
						}
					}
				]
			}
		]
	},
	resolve: {
		modules: [
			'node_modules',
			'bower_components'
		]
	},
	watch: {
		ignored: /node_modules/
	}
	plugins: [
		new webpack.ProvidePlugin({
			$: 'jquery',
			jQuery:'jquery',
			Popper: ['popper.js', 'default']
		}),
		extractPlugin,
		new CleanWebpackPlugin(['dist']),
	    new BrowserSyncPlugin({
	    		port: 6996,
	            proxy: config.devUrl,
	            files: config.files,
	            reloadDelay: 0
	        }
	    )
	]
}