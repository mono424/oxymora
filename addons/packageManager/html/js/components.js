

Vue.component('packagelist', {
  props: ['pages'],
  template: `<ul class="list-group">
  <packageitem v-for="package in packages"></packageitem>
  </ul>`
});


Vue.component('packageitem', {
  props: ['package'],
  template: `<li class="list-group-item justify-content-between">
  {{ package.name }}
  <span class="badge badge-primary badge-pill">
  {{ package.version }}
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
