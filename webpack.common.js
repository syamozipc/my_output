const path = require('path');

const MiniCssExtractPlugin = require('mini-css-extract-plugin');

module.exports = {
	entry: './Resources/js/user/home/index.js',
	output: {
		path: path.resolve(__dirname, 'Public'),
		filename: 'js/user/home/index.js',
	},
	module: {
		rules: [
			{
				test: /\.js$/,
				include: path.resolve(__dirname, 'Resources/js/user/home'),
				use: 'babel-loader',
			},
			{
				test: /\.scss$/,
				include: path.resolve(__dirname, 'Resources/scss/user/home'),
				use: [MiniCssExtractPlugin.loader, 'css-loader', 'sass-loader'],
			},
		],
	},
	plugins: [
		new MiniCssExtractPlugin({
			filename: './css/user/home/index.css',
		}),
	],
};
