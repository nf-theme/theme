var path = require('path');
var webpack = require('webpack');
var ExtractTextPlugin = require('extract-text-webpack-plugin');
var CleanWebpackPlugin = require('clean-webpack-plugin');

var extractPlugin = new ExtractTextPlugin({
    filename: 'styles/[name].css'
});

module.exports = {
    entry: {
        'main': [
            './assets/scripts/main.js',
            './assets/styles/main.scss'
        ],
        'vendor': [
            './bower_components/bootstrap/dist/css/bootstrap.min.css',
            './bower_components/font-awesome/css/font-awesome.min.css'
        ]
    },
    output: {
        path: path.resolve(__dirname, '../../dist'),
        filename: 'scripts/[name].js',
        publicPath: '../../dist'
    },
    module: {
        rules: [
            {
                test: /\.js$/,
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
                test: /\.(png|jpe?g|gif|svg|ico)$/,
                use: [
                    {
                        loader: 'file-loader',
                        options: {
                            name: '[name].[ext]',
                            outputPath: 'images/',
                            publicPath: 'images/'
                        }
                    }
                ]
            },
            {
                test: /\.(woff2?|ttf|eot)$/,
                use: [
                    {
                        loader: 'url-loader',
                        options: {
                            name: '[name].[ext]',
                            outputPath: 'fonts/',
                            publicPath: 'fonts/'
                        }
                    }
                ]
            }
        ]
    },
    plugins: [
        extractPlugin
    ]
}
