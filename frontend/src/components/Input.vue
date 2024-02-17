<template>
  <v-combobox clearable label="Idioma" :items="idiomas" v-model="IdiomaSeleccionado" rounded="t-xl"
    :class="{ 'error--text': campoObligatorio }"></v-combobox>

  <v-form class="mx-5">
    <v-textarea clearable label="Ingrese el texto aquí" variant="outlined" hide-details v-model="textoEntrada" no-resize
      :rows="calcularFilas" :readonly="contarPalabras() > 500">
    </v-textarea>
  </v-form>

  <v-row>
    <v-col :cols="tamañoResolucion"><v-btn rounded="xl" size="small" style="margin-left: 15px;margin-top: 5px;" @click="recogerTextoVoz"
      :disabled="!reconocimientoDisponible"><v-icon left>mdi-microphone</v-icon></v-btn>
    </v-col>
    <v-col>
      <v-btn  rounded="xl" size="small" :style="estiloBotonResolucion" @click="leerTexto" :disabled="!textoEntrada">
          <v-icon left>mdi-volume-high</v-icon>
        </v-btn>
    </v-col>
    <v-col>
      <p style="text-align: right; margin-right: 20px;margin-top: 5px;">{{ contarPalabras(textoEntrada) }}/500</p>
    </v-col>

  </v-row>
</template>

<script setup>
import { ref, watch, defineProps, defineEmits, computed } from "vue";
import idiomas from "../../public/idiomas.json";
import idiomasClave from "../../public/idiomasClave.json";
import vuetify from '../plugins/vuetify';

const prop = defineProps({
  idiomaEntradaSeleccionado: { type: String, required: false }
});

const emit = defineEmits({
  onChangeIdioma: String,
  onChangeTexto: String
});

const IdiomaSeleccionado = ref(prop.idiomaEntradaSeleccionado);

const textoEntrada = ref('');

const reconocimientoDisponible = ref(false);

if ('SpeechRecognition' in window || 'webkitSpeechRecognition' in window) {
  reconocimientoDisponible.value = true;
}

watch(textoEntrada, (nuevoTexto) => {
  emit('onChangeTexto', nuevoTexto);
})

watch(IdiomaSeleccionado, (nuevoIdioma) => {
  emit('onChangeIdioma', nuevoIdioma);
})

watch(() => prop.idiomaEntradaSeleccionado, (nuevoValor) => {
  IdiomaSeleccionado.value = nuevoValor;
});

const calcularFilas = computed(() => {
  if (vuetify.display.xlAndUp.value) {
    return 21;
  } else if (vuetify.display.lg.value) {
    return 16;
  } else if (vuetify.display.mdAndDown.value) {
    return 5;
  }
});

const contarPalabras = function () {
  if (!textoEntrada.value) {
    return 0;
  }
  if (textoEntrada.value.trim().split(/\s+/).length > 500) {
    textoEntrada.value = textoEntrada.value.trim().split(/\s+/).slice(0, 500).join(' ');
  }
  return textoEntrada.value.trim().split(/\s+/).length;
};

const recogerTextoVoz = () => {
  const reconocimientoVoz = new (window.SpeechRecognition || window.webkitSpeechRecognition)();

  reconocimientoVoz.start();

  reconocimientoVoz.onresult = (event) => {
    const resultado = event.results[0][0].transcript;
    textoEntrada.value += resultado;
  };

  reconocimientoVoz.onerror = (event) => {
    console.error('Error en el reconocimiento de voz:', event.error);
  };
};

const leerTexto = () => {
  const synthesis = window.speechSynthesis;

  const message = new SpeechSynthesisUtterance(textoEntrada.value);

  message.lang = idiomasClave[IdiomaSeleccionado.value];

  synthesis.speak(message);
};

const estiloBotonResolucion = computed(() => {
  if (vuetify.display.xlAndUp.value) {
    return "margin-left: 15px;margin-top: 5px";
  } else if (vuetify.display.lg.value) {
    return "margin-left: 30px;margin-top: 5px";
  } else if (vuetify.display.md.value) {
    return "margin-top: 5px";
  }else if (vuetify.display.sm.value) {
    return "margin-left: 20px;margin-top: 5px";
  }else if (vuetify.display.xs.value) {
    return "margin-top: 5px";
  }
});

const tamañoResolucion = computed(() => {
  if (vuetify.display.xlAndUp.value) {
    return 1;
  } else if (vuetify.display.lg.value) {
    return 1;
  } else if (vuetify.display.md.value) {
    return 1;
  }else if (vuetify.display.sm.value) {
    return 1;
  }
  else if (vuetify.display.xs.value) {
    return 2;
  }
});
</script>

<style></style>