import Vue from 'vue'
import App from './App.vue'
import router from './router'
import store from './store'
import vuetify from './plugins/vuetify'

Vue.config.productionTip = false

Vue.prototype.$myServerPath = "https://mytestwww.tode.cz/astronauts"
Vue.prototype.$dialog_msg_color = "rgb(603, 650, 200)"
Vue.prototype.$msg_color = "rgb(203, 650, 200)"
Vue.prototype.$warning_msg_color = "rgb(255, 153, 0)" 

new Vue({
  router,
  store,
  vuetify,
  render: h => h(App)
}).$mount('#app')
