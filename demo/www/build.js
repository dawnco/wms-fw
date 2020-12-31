/**
 * @author Dawnc
 * @date   2020-12-27
 */

/**
 * 正则
 * https://developer.mozilla.org/zh-cn/docs/web/javascript/guide/regular_expressions
 */

// 读取文件生成路由
var path = require('path')
var fs = require('fs')

var dir = __dirname.replace(/\\/g, '/')

function msg (msg) {
  console.log(msg)
}

// 生成路由和index.html
function tplAndRoute (dir) {

  let jsFiles = []
  let routeFiles = []

  let file = ''
  let pathName = dir + '/static/page'

  let files = fs.readdirSync(pathName)

  for (let i = 0; i < files.length; i++) {
    jsFiles.push('<script src="/static/page/' + files[i] + '"></script>')
    let name = files[i].replace('.js', '')
    if (name != 'NotFound') {

      routeFiles.push('{ path: \'/' + (name == 'Home' ? '' : name) + '\', component: ' + name + ' }')
    }
  }
  routeFiles.push('{ path: \'*\', component: NotFound }')

  let html = fs.readFileSync(dir + '/index.template.html', 'utf8')

  let str = html.replace('<!--page-->', '<!--page-->\n' + jsFiles.join('\n') + '\n<!--page-->')

  file = dir + '/index.html'
  fs.writeFileSync(file, str)
  msg('生成文件 ' + file)
  let str2 = 'const routes = [\n  ' +
             routeFiles.join(',\n  ') +
             '\n' +
             ']'
  file = dir + '/static/router.js'
  fs.writeFileSync(file, str2)
  msg('生成路由 ' + file)
}

// vue 转js
function vue2js (dir) {
  let dirname = dir + '/static/vue'
  let files = fs.readdirSync(dirname)


  for (let i = 0; i < files.length; i++) {
    let file = dirname + '/' + files[i]
    let content = fs.readFileSync(file, 'utf8')

    let match1 = /<template>(.+)<\/template>/gs.exec(content)
    let tpl = match1[1]
    console.log(tpl)

    let match2 = /<script>(.+)<\/script>/gs.exec(content)
    let js = match2[1]
    let o = eval(js)
    console.log(o)




  }

}

// tplAndRoute(dir)
vue2js(dir)
