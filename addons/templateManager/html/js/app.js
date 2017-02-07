

var app = new Vue({
  el: '#app',
  template:'<packagelist :packages.sync="packages"></packagelist>',
  data: {
    packages:packages
  }
})
