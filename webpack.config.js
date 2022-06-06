// Node.jsに組み込まれているモジュール。出力先などの指定をするために利用する。
const path = require('path');

// scssのcompileに使用。個別のcssファイルへの出力を可能にする。
const MiniCssExtractPlugin = require('mini-css-extract-plugin');

// eslint-loaderが非推奨になる & failOnErrorが機能しないので、こちらを使用
// https://github.com/webpack-contrib/eslint-loader/issues/334
// const EslintWebpackPlugin = require('eslint-webpack-plugin');

/**
 * globを使用し、compile対象のfileを{ ext無しfilePath: ext有りfilePath }のobjectでreturn
 * fileの指定があった場合は、そのfileのみcompile対象とする
 *
 * @param {string} filePath
 * @returns {object} entries
 */
function getEntries(filePath) {
    const glob = require('glob');
    const srcDir = './resources/ts';
    const entries = {};
    let files = [filePath];

    if (!filePath) {
        files = glob.sync('**/*.ts', {
            ignore: '**/_*/*.ts',
            cwd: srcDir,
        });
    }

    files.forEach((filePath) => {
        const fileNameExceptExt = filePath.replace(/\.ts$/, '');
        entries[fileNameExceptExt] = path.resolve(srcDir, filePath);
    });

    return entries;
}

module.exports = (env) => {
    return {
        mode: 'development',
        devtool: 'eval-cheap-module-source-map',
        entry: getEntries(env.file),
        output: {
            path: path.resolve(__dirname, 'public'),
            filename: 'js/[name].js',
        },
        resolve: {
            alias: {
                '@scss': path.resolve(__dirname, 'resources/scss'),
                // tsconfig.jsonと一致させる必要がある
                '@ts': path.resolve(__dirname, 'resources/ts'),
            },
            extensions: ['.ts', '.js'],
        },
        module: {
            rules: [
                {
                    test: /\.ts$/,
                    use: ['ts-loader'],
                    exclude: /node_modules/,
                },
                {
                    test: /\.scss$/,
                    include: path.resolve(__dirname, 'resources/scss'),
                    use: [
                        MiniCssExtractPlugin.loader,
                        'css-loader',
                        'sass-loader',
                    ],
                },
            ],
        },
        plugins: [
            new MiniCssExtractPlugin({
                filename: 'css/[name].css',
            }),
            // new EslintWebpackPlugin({
            //     fix: false,
            //     failOnError: false, // 必要に応じて切り替え
            // }),
        ],
    };
};
