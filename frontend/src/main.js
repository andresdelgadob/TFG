// main.js
import { createApp } from 'vue';
import App from './App.vue';
import router from './router'; 
import { createVuetify } from 'vuetify';
import { mdiAccount } from '@mdi/js';
import 'vuetify/styles';
import '@mdi/font/css/materialdesignicons.css';
import * as components from "vuetify/components";
import * as directives from "vuetify/directives";
import { fa } from "vuetify/iconsets/fa";
import { aliases, mdi } from "vuetify/lib/iconsets/mdi";
import { loadFonts } from './plugins/webfontloader'
import 'vuetify/dist/vuetify.min.css';

loadFonts()

const app = createApp(App);

app.use(router);

const vuetify = createVuetify({
    theme: {
      defaultTheme: "light",
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

app.config.globalProperties.$vuetify = vuetify.framework;
app.config.globalProperties.$mdiAccount = mdiAccount;

app.mount('#app');




