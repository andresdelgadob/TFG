<template>
    <v-app>
        <v-app-bar color="primary">
            <div class="d-flex align-center">
                <v-img contain height="100px" width="200px" src="/Universidad_de_Alcala_logotipo.png"></v-img>
                <v-divider vertical></v-divider>
                <span class="mx-4" style="font-size: 24px;">IA Traductor</span>
            </div>
        </v-app-bar>

        <v-container class="content-container">
            <!-- Primer v-sheet a la izquierda -->
            <v-row>
                <v-col>
                    <v-sheet :class="model" :height="650" :width="500" color="info">
                        <!-- Contenido del primer v-sheet -->
                        <Input :idiomaEntradaSeleccionado="idiomaEntradaSeleccionado" @onChangeIdioma="idiomaEntradaSeleccionado = $event"
                        @onChangeTexto="textoEntrada = $event"/>
                    </v-sheet>
                </v-col>

                <v-col>
                    <v-row justify="center" style="margin-bottom: 240px; margin-top: 5px;">
                        <v-btn color="info" rounded="xl" size="x-small" prepend-icon="mdi-arrow-left-right-bold" stacked @click="intercambiarIdiomas"></v-btn>
                    </v-row>
                    <v-row justify="center">
                        <v-btn @click="traducir" color="info" rounded="xl" prepend-icon="mdi-arrow-right-bold" stacked>Traducir</v-btn>
                    </v-row>
                    <v-row justify="center" style="margin-top: 230px;">
                        <v-btn @click="configuracion = true" color="info" rounded="xl" size="x-small" prepend-icon="mdi-cog" stacked></v-btn>
                        <v-dialog v-model="configuracion" width="500" height="400">
                            <v-card  :class="model" :height="650" :width="500">
                                <v-container>
                                    <v-row class="mb-0">
                                        <v-col>
                                            <v-card-title class="pa-6">Configuración</v-card-title>
                                        </v-col>
                                        <v-col style="margin-left: 180px;">
                                            <v-card-actions class="d-flex pa-4">
                                                <v-btn @click="configuracion = false" icon="mdi-close-circle"></v-btn>
                                            </v-card-actions>
                                        </v-col>
                                    </v-row>
                                    <v-row>
                                        <v-card-text class="pt-0">
                                            <v-checkbox label="Resumir y traducir"></v-checkbox>
                                            <v-checkbox label="Traducir formalmente"></v-checkbox>
                                            <v-checkbox label="Eliminar contenido explícito"></v-checkbox>
                                        </v-card-text>
                                    </v-row>
                                </v-container>
                            </v-card>
                        </v-dialog>           
                    </v-row>
                </v-col>

                <v-col>
                    <!-- Segundo v-sheet a la derecha -->
                    <v-sheet :class="model" :height="650" :width="500" color="info" style="position: relative;">
                        <!-- Contenido del segundo v-sheet -->
                            <v-progress-circular v-if="cargando" indeterminate style="z-index: 1; position: absolute; left: 50%; top: 50%; transform: translate(-50%, -50%);"></v-progress-circular>
                            <Output :idiomaSalidaSeleccionado="idiomaSalidaSeleccionado" @onChangeIdioma="idiomaSalidaSeleccionado = $event"
                                :textoSalida="textoSalida"/>
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

const idiomaEntradaSeleccionado= ref('');
const idiomaSalidaSeleccionado= ref('');
const textoEntrada= ref('');
const textoSalida= ref('');
const configuracion = ref(false);
const cargando = ref(false);

const intercambiarIdiomas = () => {
  const temp = idiomaEntradaSeleccionado.value;
  idiomaEntradaSeleccionado.value = idiomaSalidaSeleccionado.value;
  idiomaSalidaSeleccionado.value = temp;
};

const traducir = () => {
    cargando.value=true;
    console.log("idioma entrada",idiomaEntradaSeleccionado.value);
    console.log("idioma salida",idiomaSalidaSeleccionado.value);
    console.log("texto entrada",textoEntrada.value);
    fetch('http://localhost:8001/public.php', {
        method: 'POST',
        headers: {
        'Content-Type': 'application/json', // Tipo de contenido del cuerpo (JSON en este caso)
        },
        body: JSON.stringify({
            "texto":textoEntrada.value,
            "idiomaEntrada":idiomaEntradaSeleccionado.value,
            "idiomaSalida":idiomaSalidaSeleccionado.value
        }),})
    .then(response => {
        cargando.value=false;
        if (!response.ok) {
        throw new Error('Error en la solicitud')
        }
        return response.json(); 
    })
    .then(data => {
        textoSalida.value=data;
        console.log(data);
    })
    .catch(error => {
        cargando.value=false;
        console.error(error);
    });
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
.no-scroll {
  overflow-x: hidden;
}
</style>