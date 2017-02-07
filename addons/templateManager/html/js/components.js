Vue.component('packagelist', {
  props: {
    packages: {required: true}
  },
  template: `<ul class="list-group">
  <packageitem v-for="package in packages" :package.sync="package"></packageitem>
  </ul>`,

});


Vue.component('packageitem', {
  props: ['package'],
  template: `<li class="list-group-item justify-content-between">
  {{ package.name }}
  <span class="badge badge-primary badge-pill">
  v{{ package.version }}
  </span>
  </li>`,
  data: function(){
    return {};
  },
  created: function() {

  },
  methods:{

  }
});
