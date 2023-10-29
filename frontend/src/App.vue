<template>
    <v-app>
        <v-app-bar color="primary">
            <div class="d-flex align-center">
                <v-img contain height="100px" width="200px" src="/Universidad_de_Alcala_logotipo.png"></v-img>
                <v-divider vertical></v-divider>
                <span class="mx-4" style="font-size: 24px;">Traductor</span>
            </div>
        </v-app-bar>

        <v-container class="content-container">
            <!-- Primer v-sheet a la izquierda -->
            <v-row>
                <v-col>
                    <v-sheet :class="model" :height="650" :width="500" color="info">
                        <!-- Contenido del primer v-sheet -->
                        <Input :selectedIdiomaInput="selectedIdiomaInput" @onChangeIdioma="selectedIdiomaInput = $event"/>
                    </v-sheet>
                </v-col>

                <v-col>
                    <v-row justify="center" style="margin-bottom: 240px; margin-top: 5px;">
                        <v-btn color="info" rounded="xl" size="x-small" prepend-icon="mdi-arrow-left-right-bold" stacked @click="intercambiarIdiomas"></v-btn>
                    </v-row>
                    <v-row justify="center">
                        <v-btn color="info" rounded="xl" prepend-icon="mdi-arrow-right-bold" stacked>Traducir</v-btn>
                    </v-row>
                </v-col>

                <v-col>
                    <!-- Segundo v-sheet a la derecha -->
                    <v-sheet :class="model" :height="650" :width="500" color="info">
                        <!-- Contenido del segundo v-sheet -->
                        <Output :selectedIdiomaOutput="selectedIdiomaOutput" @onChangeIdioma="selectedIdiomaOutput = $event"/>
                    </v-sheet>
                </v-col>
            </v-row>
        </v-container>

    </v-app>
</template>  

<script setup>
import { ref,watch,defineProps  } from "vue";
import Input from "./components/Input.vue";
import Output from "./components/Output.vue";

const model='rounded-xl';

const button='v-btn--x-small';

const selectedIdiomaInput = ref('');
const selectedIdiomaOutput = ref('');

const intercambiarIdiomas = () => {
  const temp = selectedIdiomaInput.value;
  selectedIdiomaInput.value = selectedIdiomaOutput.value;
  selectedIdiomaOutput.value = temp;
};

</script>

<style>
.content-container {
  margin-top: 60px;
}
.content-div {
  margin-right: 20px;
  margin-left: 20px;
}

.content-center {
    align-self: top;
}

.small-button {
  font-size: 12px; /* Ajusta el tamaño de la fuente del botón */
  padding: 8px 16px; /* Ajusta el relleno del botón para cambiar su tamaño */
}

</style>