
<template>
  <v-app>
  <svg-icon type="mdi" :path="path"></svg-icon>
  </v-app>
</template>

<script>
import SvgIcon from '@jamescoyle/vue-icon';
import { mdiBellCogOutline } from '@mdi/js';

export default {
  name: "my-component",
  components: {
    SvgIcon
  },
  data() {
    return {
       path: mdiBellCogOutline,
    }
  }
}
</script>