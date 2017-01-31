
var app = new Vue({
  el: '#app',
  data: {
    pages: pages
  },
  methods:{
    toggle:function(who, cb){
      $.post('index.php', {page:who.page.url}, cb);
    }
  }
})
