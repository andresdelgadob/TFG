<template>
  <v-combobox
      clearable
      label="Idioma*"
      :items="idiomas"
      v-model="IdiomaSeleccionado"
      rounded="t-xl"
      :class="{ 'error--text': campoObligatorio}"
    ></v-combobox>

    <v-form class="mx-5">
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

<style scoped>
/* Establecer el border-radius en el v-input__control */
.v-input__control {
  border-radius: 15px; /* Puedes ajustar el valor según tu preferencia */
}
</style>

<script setup>
import { ref,watch,defineProps  } from "vue";
import idiomas from "../../public/idiomas.json";

const prop = defineProps({
  idiomaSalidaSeleccionado: {type:String, required:false},
  textoSalida: {type:String, required:false},
  campoObligatorio: {type:Boolean,required:false}
});

const emit = defineEmits({
  onChangeIdioma: String
});

const model='.rounded-t-xl';

const IdiomaSeleccionado=ref(prop.idiomaSalidaSeleccionado);

const textoSalida=ref(prop.textoSalida);

const campoObligatorio = ref(false);

watch(IdiomaSeleccionado, (nuevoIdioma) => {
  campoObligatorio.value=!nuevoIdioma;
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
.error--text {
  color:red;
}

</style>