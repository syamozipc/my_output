// Node.jsに組み込まれているモジュール。出力先などの指定をするために利用する。
const path = require('path');

// scssのcompileに使用。個別のcssファイルへの出力を可能にする。
const MiniCssExtractPlugin = require('mini-css-extract-plugin');

// eslint-loaderが非推奨になる & failOnErrorが機能しないので、こちらを使用
// https://github.com/webpack-contrib/eslint-loader/issues/334
const EslintWebpackPlugin = require('eslint-webpack-plugin');

// glob
const glob = require('glob');

// globを使用して配下の任意のJS fileを取得し、compileする処理
const srcDir = './resources';
const entries = {};

glob.sync('**/*.js', {
    ignore: '**/_share/*.js',
    cwd: srcDir,
}).forEach((file) => (entries[file] = path.resolve(srcDir, file)));

module.exports = {
    mode: 'development',
    devtool: 'eval-cheap-mひとつnodule-source-map',
    entry: entries,
    output: {
        path: path.resolve(__dirname, 'public'),
        // filename: 'js/[name].js',
        filename: '[name].js',
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
