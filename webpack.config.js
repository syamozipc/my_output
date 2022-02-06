// Node.jsに組み込まれているモジュール。出力先などの指定をするために利用する。
const path = require('path');

// scssのcompileに使用
const MiniCssExtractPlugin = require('mini-css-extract-plugin');

// compile対象の全JS file
const entry = {
	'user/home/index': './resources/js/user/home/index.js',
	'user/post/index': './resources/js/user/post/index.js',
	'user/post/confirm': './resources/js/user/post/confirm.js',
	'user/post/create': './resources/js/user/post/create.js',
	'user/post/edit': './resources/js/user/post/edit.js',
	'user/post/editConfirm': './resources/js/user/post/editConfirm.js',
	'user/post/show': './resources/js/user/post/show.js',
};

// modeは各環境用webpack config fileに記述しているので、ここでは共通処理のみ
module.exports = {
	mode: 'development',
	devtool: 'eval-cheap-module-source-map',
	// entry: entry の省略
	entry,
	output: {
		path: path.resolve(__dirname, 'public'),
		filename: 'js/[name].js',
	},
	module: {
		rules: [
			{
				test: /\.js$/,
				include: path.resolve(__dirname, 'resources/js'),
				use: [
					// babelをeslintより後に書く（先に実行する）と、const/letながvarに変換されeslintがerrorを出してしまう
					'babel-loader',
					{
						loader: 'eslint-loader',
						options: {
							fix: false,
							failOnError: true,
						},
					},
				],
			},
			{
				test: /\.scss$/,
				include: path.resolve(__dirname, 'resources/scss'),
				use: [MiniCssExtractPlugin.loader, 'css-loader', 'sass-loader'],
			},
		],
	},
	plugins: [
		new MiniCssExtractPlugin({
			filename: 'css/[name].css',
		}),
	],
};
