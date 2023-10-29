<template>
    <v-combobox
      clearable
      label="Idioma"
      :items="idiomas"
      v-model="prop.selectedIdiomaInput"
      v-if="prop.selectedIdiomaInput !== undefined && prop.selectedIdiomaInput !== ''"
    ></v-combobox>
    <v-combobox
      clearable
      label="Idioma"
      :items="idiomas"
      v-model="selectedIdioma"
      v-else
    ></v-combobox>

  <v-form class="w-85 content-margin">
      <v-textarea 
          :class="model"
          clearable
          label="Ingrese el texto aquí"
          variant="outlined"
          hide-details
          v-model="selectedText"
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
  selectedIdiomaInput: {type:String, required:false}
});

const emit = defineEmits({
  onChangeIdioma: String
});

const model='rounded-xl';

const selectedIdioma=ref(prop.selectedIdiomaInput);

const selectedText=ref('');

watch(selectedIdioma, (newIdioma) => {
  emit('onChangeIdioma', newIdioma);
})
</script>

<style>
.content-margin {
  margin: 20px;
}
</style>