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

const Util = {
  trim (str) {
    return str.replace(/(^\s*)|(\s*$)/sg, '')
  },
  // 驼峰转连字符
  camel2line (str) {
    return str.replace(/([A-Z])/g, '-$1').toLowerCase()
  },
  // 连字符转驼峰
  line2camel (str) {
    return str.replace(/^\-/, '').replace(/\-(\w)(\w+)/g, function (a, b, c) {
      return b.toUpperCase() + c.toLowerCase()
    })
  }
}

function msg (msg) {
  let date = new Date()
  console.log(date.getHours() + ':' + date.getMinutes() + ':' + date.getMinutes() + ' ' + msg)
}

// 生成路由和index.html
function tplAndRoute (dir) {

  let jsFiles = []
  let routeFiles = []

  let file = ''

  let pathName
  let files
  // 组件
  pathName = dir + '/static/component'
  files = fs.readdirSync(pathName)
  for (let i = 0; i < files.length; i++) {
    jsFiles.push('<script src="/static/component/' + files[i] + '"></script>')
  }

  // 路由
  pathName = dir + '/static/page'
  files = fs.readdirSync(pathName)
  for (let i = 0; i < files.length; i++) {
    jsFiles.push('<script src="/static/page/' + files[i] + '"></script>')
  }

  let html = fs.readFileSync(dir + '/index.template.html', 'utf8')
  let str = html.replace('<!--page-->', '<!--page-->\n' + jsFiles.join('\n') + '\n<!--page-->')
  file = dir + '/index.html'
  fs.writeFileSync(file, str)
  msg('生成文件 ' + file)

  // 路由

  let tpl = `const routes = [
  { path: '/Login', component: LoginPage },
  {
    path: '/', component: LayoutPage,
    children: [
{children}
    ]
  },
  { path: '*', component: NotFoundPage }
]`

  for (let i = 0; i < files.length; i++) {
    let name = files[i].replace('.js', '')
    if (name != 'Layout' || name != 'Login' || name != 'NotFound') {
      routeFiles.push('      { path: \'/' + (name == 'Home' ? '' : name) + '\', component: ' + name + 'Page }')
    }
  }

  tpl = tpl.replace('{children}', routeFiles.join(',\n  '))
  file = dir + '/static/router.js'
  fs.writeFileSync(file, tpl)
  msg('生成路由 ' + file)
}

// vuePage 转js
function vuePage (dir) {
  let dirname = dir + '/static/vue/page'
  let files = fs.readdirSync(dirname)

  for (let i = 0; i < files.length; i++) {
    let fileMame = files[i].replace('.vue', '')
    let file = dirname + '/' + fileMame + '.vue'
    let content = fs.readFileSync(file, 'utf8')

    let match1 = /<template>(.+)<\/template>/gs.exec(content)
    if (match1 == null) {
      continue
    }

    let tpl = Util.trim(match1[1])

    let match2 = /<script>(.+)<\/script>/gs.exec(content)
    if (match2 == null) {
      console.log(file + ' has  no script')
    }
    let js = match2[1]

    let jsFile = dir + '/static/page/' + fileMame + '.js'

    js = Util.trim(js)
    js = js.replace('export default {', `const ${fileMame}Page = {\n  template: \`${tpl}\`,`)

    fs.writeFileSync(jsFile, js)
    msg('生成页面 ' + jsFile)

  }

}

// vueComponent 转js
function vueComponent (dir) {
  let dirname = dir + '/static/vue/component'
  let files = fs.readdirSync(dirname)

  for (let i = 0; i < files.length; i++) {
    let fileMame = files[i].replace('.vue', '')
    let file = dirname + '/' + fileMame + '.vue'
    let content = fs.readFileSync(file, 'utf8')

    let match1 = /<template>(.+)<\/template>/gs.exec(content)
    let tpl = Util.trim(match1[1])

    let match2 = /<script>(.+)<\/script>/gs.exec(content)
    if (match2 == null) {
      console.log(file + ' has  no script')
    }
    let js = match2[1]

    let jsFile = dir + '/static/component/' + fileMame + '.js'

    js = Util.trim(js)
    let name = Util.camel2line(fileMame.toLocaleLowerCase())
    js = js.replace('export default {', `Vue.component('custom-${name}', {\n  template: \`${tpl}\`,`)

    fs.writeFileSync(jsFile, js + ')')
    msg('生成组件 ' + jsFile)

  }

}

function check (dir) {
  let stat = fs.existsSync(dir)
  if (!stat) {
    fs.mkdirSync(dir, 0755)
  }

}

check(dir + '/static/component')
check(dir + '/static/page')

vuePage(dir)
vueComponent(dir)
tplAndRoute(dir)

fs.watch(dir + '/static/vue/page', (event, filename) => {
  vuePage(dir)
  tplAndRoute(dir)
})

fs.watch(dir + '/static/vue/component', (event, filename) => {
  vueComponent(dir)
})
