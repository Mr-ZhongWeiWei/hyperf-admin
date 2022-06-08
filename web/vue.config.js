const path = require('path')
const webpack = require('webpack')
const createThemeColorReplacerPlugin = require("./config/plugin.config");
const buildDate = JSON.stringify(new Date().toLocaleString())

function resolve(dir) {
    return path.join(__dirname, dir)
}

const Timestamp = new Date().getTime()
// vue.config.js
const vueConfig = {
    runtimeCompiler: true,
    css: {
        loaderOptions: {
            less: {
                modifyVars: {
                    // less vars，customize ant design theme

                    // 'primary-color': '#F5222D',
                    // 'link-color': '#F5222D',
                    // 'border-radius-base': '2px'
                },
                // DO NOT REMOVE THIS LINE
                javascriptEnabled: true
            }
        },
        requireModuleExtension: true
    },

    devServer: {
        // development server port 8000
        hot: true, //热加载
        open: false,
        port: 9090,

    },
    productionSourceMap: false,
    lintOnSave: false,
    chainWebpack: (config) => {
        config.resolve.alias.set('@$', resolve('src'))
        config.plugin('html').tap(args => {
            args[0].title = '后台管理系统' //这个是网站标题
            return args
        })
    },
    configureWebpack: {
        resolve: {
            extensions: [".js", ".vue", ".json", ".ts", ".tsx"] // 加入ts 和 tsx
        },
        // webpack plugins
        plugins: [
            // Ignore all locale files of moment.js
            new webpack.IgnorePlugin(/^\.\/locale$/, /moment$/),
            new webpack.DefinePlugin({
                APP_VERSION: `"${require('./package.json').version}"`,
                BUILD_DATE: buildDate
            })
        ],
        //每次打包后生成的js携带时间戳
        output: {
            filename: `[name].${Timestamp}.js`,
            chunkFilename: `[name].${Timestamp}.js`,
        },
        performance: {
            maxEntrypointSize: 50000000,
            maxAssetSize: 30000000,
        },
    },
}
vueConfig.configureWebpack.plugins.push(createThemeColorReplacerPlugin())
module.exports = vueConfig


