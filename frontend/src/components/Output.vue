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
          label="Traducción"
          variant="outlined"
          hide-details
          v-model="textoSalida"
          no-resize
          :rows="calcularFilas"
          readonly
      >
      </v-textarea>

      <v-btn  rounded="xl" size="small" style="text-align: center;margin-top: 5px;" @click="leerTexto" :disabled="!textoSalida">
          <v-icon left>mdi-volume-high</v-icon>
        </v-btn>
  </v-form>
</template>

<style scoped>
/* Establecer el border-radius en el v-input__control */
.v-input__control {
  border-radius: 15px; /* Puedes ajustar el valor según tu preferencia */
}
</style>

<script setup>
import { ref,watch,defineProps,computed  } from "vue";
import idiomas from "../../public/idiomas.json";
import idiomasClave from "../../public/idiomasClave.json";
import vuetify from '../plugins/vuetify';
import { mdiConsoleLine } from "@mdi/js";

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

const calcularFilas = computed(() => {
  if (vuetify.display.xlAndUp.value) {
    return 21;
  } else if (vuetify.display.lg.value) {
    return 16;
  }else if(vuetify.display.mdAndDown.value){
    return 5;
  }
});

const leerTexto = () => {
  const synthesis = window.speechSynthesis;

  const message = new SpeechSynthesisUtterance(textoSalida.value);

  message.lang = idiomasClave[IdiomaSeleccionado.value];

  synthesis.speak(message);
};

</script>

<style>
.content-margin {
  margin: 20px;
}
.error--text {
  color:red;
}

</style>