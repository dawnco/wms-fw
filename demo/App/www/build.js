/**
 * @author Dawnc
 * @date   2020-12-27
 */

// 读取文件生成路由
var path = require('path')
var fs = require('fs')
var jsFiles = []
var routeFiles = []
var pathName = './static/page/'
fs.readdir(pathName, function (err, files) {

  for (var i = 0; i < files.length; i++) {
    jsFiles.push('<script src="/static/page/' + files[i] + '"></script>')
    let name = files[i].replace('.js', '')
    if (name != 'NotFound') {

      routeFiles.push('{ path: \'/' + (name == 'Home' ? '' : name) + '\', component: ' + name + ' }')
    }
  }
  routeFiles.push('{ path: \'*\', component: NotFound }')

  let html = fs.readFileSync('index.template.html', 'utf8')

  let str = html.replace('<!--page-->', '<!--page-->\n' + jsFiles.join('\n') + '\n<!--page-->')

  fs.writeFileSync('index.html', str)


  let str2 = 'const routes = [\n  ' +
             routeFiles.join(',\n  ') +
             '\n' +
             ']'

  fs.writeFileSync('./static/router.js', str2)
})
