<template>
    <v-combobox
      clearable
      label="Idioma"
      :items="idiomas"
      v-model="IdiomaSeleccionado"
      rounded="t-xl"
      :class="{ 'error--text': campoObligatorio}"
    ></v-combobox>

  <v-form class="mx-5">
      <v-textarea 
          clearable
          label="Ingrese el texto aquí"
          variant="outlined"
          hide-details
          v-model="textoEntrada"
          no-resize
          :rows="calcularFilas"
      >
      </v-textarea>
  </v-form>
</template>

<script setup>
import { ref,watch, defineProps, defineEmits,computed  } from "vue";
import idiomas from "../../public/idiomas.json";
import vuetify from '../plugins/vuetify';

const prop = defineProps({
  idiomaEntradaSeleccionado: {type:String, required:false}
});

const emit = defineEmits({
  onChangeIdioma: String,
  onChangeTexto: String
});

const IdiomaSeleccionado=ref(prop.idiomaEntradaSeleccionado);

const textoEntrada=ref('');

watch(textoEntrada, (nuevoTexto) => {
  emit('onChangeTexto', nuevoTexto);
})

watch(IdiomaSeleccionado, (nuevoIdioma) => {
  emit('onChangeIdioma', nuevoIdioma);
})

watch(() => prop.idiomaEntradaSeleccionado, (nuevoValor) => {
  IdiomaSeleccionado.value=nuevoValor;
});

const calcularFilas = computed(() => {
  if (vuetify.display.xlAndUp.value) {
    return 30;
  } else if (vuetify.display.lg.value) {
    return 17;
  }else if(vuetify.display.mdAndDown.value){
    return 5;
  }
});

</script>

<style>
</style>