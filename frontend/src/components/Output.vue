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
          label="Traducción"
          variant="outlined"
          hide-details
          v-model="textoSalida"
          no-resize
          rows="20"
          readonly
      >
      </v-textarea>
  </v-form>
</template>

<script setup>
import { ref,watch,defineProps  } from "vue";
import idiomas from "../../public/idiomas.json";

const prop = defineProps({
  idiomaSalidaSeleccionado: {type:String, required:false},
  textoSalida: {type:String, required:false}
});

const emit = defineEmits({
  onChangeIdioma: String
});

const model='rounded-xl';

const IdiomaSeleccionado=ref(prop.idiomaSalidaSeleccionado);

const textoSalida=ref(prop.textoSalida);

watch(IdiomaSeleccionado, (nuevoIdioma) => {
  emit('onChangeIdioma', nuevoIdioma);
})

watch(() => prop.idiomaSalidaSeleccionado, (nuevoValor) => {
  IdiomaSeleccionado.value=nuevoValor;
});

watch(() => prop.textoSalida, (nuevoValor) => {
  textoSalida.value=nuevoValor;
});
</script>

<style>
.content-margin {
  margin: 20px;
}
</style>