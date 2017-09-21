'use strict';
Vue.use(VueMaterial);
var Home = { template: '<div class="well"><h1>Home</h1></div>' };
var About = { template: '<div class="well"><h1>About</h1></div>' };
var Contact = { template: '<div class="well"><h1>Contact</h1></div>' };
Vue.use(VueRouter);
var routes = [{ path: '/', component: Home },
              { path: '/about', component: About },
              { path: '/contact', component: Contact }];
var router = new VueRouter({
  routes: routes
});
new Vue({
  router: router
}).$mount('#app');