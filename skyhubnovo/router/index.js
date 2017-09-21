import Vue from 'vue'
import Router from 'vue-router'
import Login from '@/pages/Login'
import Registro from '@/pages/Registro'
import Tchat from '@/pages/Tchat'
import Clientes from '@/pages/Clientes'
import Home from '@/pages/Home'
Vue.use(Router)

export default new Router({
  routes: [
    { path: '/login', name: 'Login', component: Login },
    { path: '/registro', name: 'Registro', component: Registro },
    { path: '/tchat', name: 'Tchat', component: Tchat,
        beforeEnter: (to, from, next) => {
        if(!firebase.auth().currentUser){
          next('/login')
        }else{
          next()
        }
      }
    },
    { path: '/', name: 'Home', component: Home,
        beforeEnter: (to, from, next) => {
        if(!firebase.auth().currentUser){
          next('/login')
        }else{
          next()
        }
      }
    },
    { path: '/clientes', name: 'Clientes', component: Clientes,
        beforeEnter: (to, from, next) => {
        if(!firebase.auth().currentUser){
          next('/login')
        }else{
          next()
        }
      }
    }
  ]
})
