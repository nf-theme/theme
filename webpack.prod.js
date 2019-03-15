const merge = require('webpack-merge');
const UglifyJSPlugin = require('uglifyjs-webpack-plugin');
const common = require('./webpack.common.js');
const webpack = require('webpack');
var OptimizeCssAssetsPlugin = require('optimize-css-assets-webpack-plugin');
var ExtractTextPlugin = require('extract-text-webpack-plugin');

var extractPlugin = new ExtractTextPlugin({
    filename: 'app.css'
});

module.exports = merge(common, {
    plugins: [
        new OptimizeCssAssetsPlugin({
            assetNameRegExp: /\.css$/g,
            cssProcessor: require('cssnano'),
            cssProcessorOptions: {
                discardComments: {
                    removeAll: true
                }
            },
            canPrint: true
        }),
        new UglifyJSPlugin({
            uglifyOptions: {
                output: {
                    comments: false
                }
            }
        }),
        // new webpack.NormalModuleReplacementPlugin(/environment\.ts/, './environment.prod.ts'),
        // new webpack.NormalModuleReplacementPlugin(/_font\.scss/, './_font_prod.scss')
    ]
});
