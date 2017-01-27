

Vue.component('pagelist', {
  props: ['pages'],
  template: `<ul class="list-group">
  <pageitem v-for="page in pages" v-bind:page='page'></pageitem>
  </ul>`
});


Vue.component('pageitem', {
  props: ['page'],
  template: `<li v-on:click="toggle()" class="list-group-item justify-content-between">
  {{ xpage.url }}
  <span class="badge badge-primary badge-pill" :class="(xpage.locked) ? 'locked' : ''">
  <i v-if='xpage.locked' class="fa fa-lock" aria-hidden="true"></i>
  <i v-else class="fa fa-unlock" aria-hidden="true"></i>
  </span>
  </li>`,
  data: function(){
    return{'xpage':''}
  },
  created: function() {
    this.xpage = Object.assign({}, this.page);
  },
  methods:{
    toggle: function(){
      app.toggle(this, (res) => {
        if(res == "0" || res == "1"){
          let newState = (res == "1") ? true : false;
          this.$set(this.xpage,'locked',newState);
        }else{
          oxy.notify(oxy.NOTIFY_ERROR, res);
        }
      });
    }
  }
});
