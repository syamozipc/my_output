// このファイルはTypeScript導入以前のvanilla JS環境用の設定ファイルです

// Node.jsに組み込まれているモジュール。出力先などの指定をするために利用する。
const path = require('path');

// scssのcompileに使用。個別のcssファイルへの出力を可能にする。
const MiniCssExtractPlugin = require('mini-css-extract-plugin');

// eslint-loaderが非推奨になる & failOnErrorが機能しないので、こちらを使用
// https://github.com/webpack-contrib/eslint-loader/issues/334
const EslintWebpackPlugin = require('eslint-webpack-plugin');

// glob
const glob = require('glob');

// globを使用して任意のJS fileを取得し、multi entry point用のobjectを生成
const srcDir = './resources/js';
const entries = {};

glob.sync('**/*.js', {
    ignore: '**/_*/*.js',
    cwd: srcDir,
}).forEach((jsFileName) => {
    const fileNameExceptExt = jsFileName.replace(/\.js$/, '');
    entries[fileNameExceptExt] = path.resolve(srcDir, jsFileName);
});

module.exports = {
    mode: 'development',
    devtool: 'eval-cheap-module-source-map',
    entry: entries,
    output: {
        path: path.resolve(__dirname, 'public'),
        filename: 'js/[name].js',
    },
    resolve: {
        alias: {
            '@scss': path.resolve(__dirname, 'resources/scss'),
            '@js': path.resolve(__dirname, 'resources/js'),
        },
    },
    module: {
        rules: [
            {
                test: /\.js$/,
                include: path.resolve(__dirname, 'resources/js'),
                use: ['babel-loader'],
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
        new EslintWebpackPlugin({
            fix: false,
            failOnError: false, // 必要に応じて切り替え
        }),
    ],
};
