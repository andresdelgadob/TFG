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
</style>