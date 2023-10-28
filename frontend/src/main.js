// main.js
import { createApp } from 'vue';
import App from './components/App.vue';
import router from './router'; 
import store from './store';
import { createVuetify } from 'vuetify';
import { mdiAccount } from '@mdi/js';
import 'vuetify/styles';
import '@mdi/font/css/materialdesignicons.css';
import "vuetify/styles";
import * as components from "vuetify/components";
import * as directives from "vuetify/directives";
import { fa } from "vuetify/iconsets/fa";
import { aliases, mdi } from "vuetify/lib/iconsets/mdi";

const app = createApp(App);

app.use(router);
app.use(store);

const vuetify = createVuetify({
    theme: {
      defaultTheme: "dark",
    },
    icons: {
      defaultSet: "mdi",
      aliases,
      sets: {
        mdi,
        fa,
      },
    },
    components,
    directives,
  })
app.use(vuetify);
app.config.globalProperties.$mdiAccount = mdiAccount;

app.mount('#app');




