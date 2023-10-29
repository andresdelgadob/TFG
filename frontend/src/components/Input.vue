<template>
    <v-combobox
      clearable
      label="Idioma"
      :items="idiomas"
      v-model="IdiomaSeleccionado"
    ></v-combobox>

  <v-form class="w-85 content-margin">
      <v-textarea 
          :class="model"
          clearable
          label="Ingrese el texto aquí"
          variant="outlined"
          hide-details
          v-model="textoEntrada"
          no-resize
          rows="20"
      >
      </v-textarea>
  </v-form>
</template>

<script setup>
import { ref,watch, defineProps, defineEmits  } from "vue";
import idiomas from "../../public/idiomas.json";

const prop = defineProps({
  idiomaEntradaSeleccionado: {type:String, required:false}
});

const emit = defineEmits({
  onChangeIdioma: String,
  onChangeTexto: String
});

const model='rounded-xl';

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

</script>

<style>
.content-margin {
  margin: 20px;
}
</style>