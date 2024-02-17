<template>
    <v-app>

        <div style="position: fixed; top: 0; right: 0; z-index: 1100;">
            
            <v-alert v-if="error" style="background-color:red; color: white;" transition="scale-transition" class="custom-alert">
                <v-row>
                    <v-col>
                        {{ error }}
                    </v-col>
                    <v-col :cols="3">
                        <v-btn v-if="error" @click="clearError" icon size="x-small"> <v-icon>mdi-close</v-icon> </v-btn>
                    </v-col>
                </v-row>
                
                
                </v-alert>
                
        </div>

        <v-app-bar color="primary">
            <div class="d-flex align-center">
                <v-img contain height="100px" width="200px" src="/logo uah blanco.png"></v-img>
                <v-divider vertical ></v-divider>
                <span class="mx-4" style="font-size: 24px;">IA Traductor</span>
            </div>
        </v-app-bar>

        <v-container class="content-container">
            <!-- Primer v-sheet a la izquierda -->
            <v-row v-if="vuetify.display.lgAndUp.value">
                <v-col xl="5">
                    <v-sheet :class="model" width="100%" :height="alturaResolucion" style="min-width: 480px; margin-top: 20px;" color="info">
                        <!-- Contenido del primer v-sheet -->
                        <Input :idiomaEntradaSeleccionado="idiomaEntradaSeleccionado" @onChangeIdioma="idiomaEntradaSeleccionado = $event"
                        @onChangeTexto="textoEntrada = $event"/>
                    </v-sheet>
                </v-col>

                <v-col xl="2">
                    <v-row justify="center" style="height: 40%; margin-top: 20px;">
                        <v-btn color="info" rounded="xl" :size="tamañoBotonesResolucion" prepend-icon="mdi-arrow-left-right-bold" stacked @click="intercambiarIdiomas"></v-btn>
                    </v-row>
                    <v-row justify="center" style="height: 40%;">
                        <v-btn @click="traducir" color="info" rounded="xl" :size="tamañoBotonTraducirResolucion" prepend-icon="mdi-arrow-right-bold" stacked>Traducir</v-btn>
                    </v-row>
                    <v-row justify="center" style="height: 40%;">
                        <v-btn @click="configuracion = true" color="info" rounded="xl" :size="tamañoBotonesResolucion" prepend-icon="mdi-cog" stacked></v-btn>
                        <v-dialog v-model="configuracion" :height="300" :width="500">
                            <v-card  :class="model" :height="300" :width="500">
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
                                            <v-switch color="info" v-model="formal" label="Traducir formalmente"></v-switch>
                                            <v-switch color="info" v-model="formato" label="Mantener formato"></v-switch>
                                        </v-card-text>
                                    </v-row>
                                </v-container>
                            </v-card>
                        </v-dialog>           
                    </v-row>
                </v-col>

                <v-col xl="5">
                    <!-- Segundo v-sheet a la derecha -->
                    <v-sheet :class="model" color="info" width="100%" :height="alturaResolucion" style="position: relative; min-width: 480px; margin-top: 20px;">
                        <!-- Contenido del segundo v-sheet -->
                            <v-progress-circular v-if="cargando" indeterminate style="z-index: 1; position: absolute; left: 50%; top: 50%; transform: translate(-50%, -50%);"></v-progress-circular>
                            <Output :idiomaSalidaSeleccionado="idiomaSalidaSeleccionado" @onChangeIdioma="idiomaSalidaSeleccionado = $event"
                                :textoSalida="textoSalida"/>
                    </v-sheet>
                </v-col>
            </v-row>

            <v-col v-if="vuetify.display.mdAndDown.value">
                <v-row>
                    <v-sheet :class="model" width="100%" height=270px style="position: relative;min-width: 220px;margin-top: 20px;" color="info">
                        <!-- Contenido del primer v-sheet -->
                        <Input :idiomaEntradaSeleccionado="idiomaEntradaSeleccionado" @onChangeIdioma="idiomaEntradaSeleccionado = $event"
                        @onChangeTexto="textoEntrada = $event"/>
                    </v-sheet>
                </v-row>

                <v-row justify="center">
                        <v-btn style="text-align: right; margin-right: auto; margin-top: 18px;" color="info" rounded="xl" size="x-small" prepend-icon="mdi-arrow-left-right-bold" stacked @click="intercambiarIdiomas"></v-btn>
                        <v-btn style="margin-bottom: 10px; margin-top: 10px;" @click="traducir" color="info" rounded="xl" prepend-icon="mdi-arrow-right-bold" stacked>Traducir</v-btn>
                        <v-btn style="text-align: left; margin-left: auto; margin-top: 18px;" @click="configuracion = true" color="info" rounded="xl" size="x-small" prepend-icon="mdi-cog" stacked></v-btn>
                        <v-dialog v-model="configuracion" :height="300" :width="400">
                            <v-card  :class="model" :height="300" :width="400">
                                <v-container>
                                    <v-row class="mb-0">
                                        <v-col>
                                            <v-card-title class="pa-6">Configuración</v-card-title>
                                        </v-col>
                                        <v-col style="margin-left: 80px;">
                                            <v-card-actions class="d-flex pa-4">
                                                <v-btn @click="configuracion = false" icon="mdi-close-circle"></v-btn>
                                            </v-card-actions>
                                        </v-col>
                                    </v-row>
                                    <v-row>
                                        <v-card-text class="pt-0">
                                            <v-switch color="info" v-model="formal" label="Traducir formalmente"></v-switch>
                                            <v-switch color="info" v-model="formato" label="Mantener formato"></v-switch>
                                        </v-card-text>
                                    </v-row>
                                </v-container>
                            </v-card>
                        </v-dialog>           
                </v-row>

                <v-row >
                    <!-- Segundo v-sheet a la derecha -->
                    <v-sheet :class="model" color="info" width="100%" height=270px style="position: relative;min-width: 220px;">
                        <!-- Contenido del segundo v-sheet -->
                            <v-progress-circular v-if="cargando" indeterminate style="z-index: 1; position: absolute; left: 50%; top: 50%; transform: translate(-50%, -50%);"></v-progress-circular>
                            <Output :idiomaSalidaSeleccionado="idiomaSalidaSeleccionado" @onChangeIdioma="idiomaSalidaSeleccionado = $event"
                                :textoSalida="textoSalida"/>
                    </v-sheet>
                </v-row>
            </v-col>
        </v-container>
    </v-app>
</template>  

<script setup>
import { ref, computed } from "vue";
import Input from "./components/Input.vue";
import Output from "./components/Output.vue";
import vuetify from './plugins/vuetify'

const model='rounded-xl';
const idiomaEntradaSeleccionado= ref('');
const idiomaSalidaSeleccionado= ref('');
const textoEntrada= ref('');
const textoSalida= ref('');
const configuracion = ref(false);
const cargando = ref(false);
const error= ref('');
const formal = ref(false);
const formato = ref(false);

const intercambiarIdiomas = () => {
  const temp = idiomaEntradaSeleccionado.value;
  idiomaEntradaSeleccionado.value = idiomaSalidaSeleccionado.value;
  idiomaSalidaSeleccionado.value = temp;
};

const clearError = () => {
    error.value = null;
};

const traducir = () => {
    cargando.value=true;
    
    if ((!idiomaSalidaSeleccionado.value || idiomaSalidaSeleccionado.value === '')) {
        if(!error.value)  mostrarAlertaError('Error: El idioma de salida está vacío. Por favor, seleccione un idioma de salida válido.');
        cargando.value = false;
        return;
    }

    fetch('http://localhost:8001/public.php', {
        method: 'POST',
        headers: {
        'Content-Type': 'application/json', // Tipo de contenido del cuerpo (JSON en este caso)
        },
        body: JSON.stringify({
            "texto":textoEntrada.value,
            "idiomaEntrada":idiomaEntradaSeleccionado.value,
            "idiomaSalida":idiomaSalidaSeleccionado.value,
            "opciones":{
                "formal":formal.value,
                "formato":formato.value
            }
        }),})
    .then(response => {
        cargando.value=false;
        if (!response.ok) {
            return response.json().then(error => {
                throw new Error(error);
            });
        }
        return response.json(); 
    })
    .then(data => {
        textoSalida.value=data;
    })
    .catch(error => {
        cargando.value=false;
        mostrarAlertaError(error);
    });
};

const mostrarAlertaError = (mensaje) => {
    error.value = mensaje;
    setTimeout(clearError, 4000);
};

const alturaResolucion = computed(() => {
  if (vuetify.display.xlAndUp.value) {
    return "650px";
  } else if (vuetify.display.lg.value) {
    return "530px";
  }
});

const tamañoBotonesResolucion = computed(() => {
  if (vuetify.display.xlAndUp.value) {
    return "small";
  } else if (vuetify.display.lg.value) {
    return "x-small";
  }
});

const tamañoBotonTraducirResolucion = computed(() => {
  if (vuetify.display.xlAndUp.value) {
    return "x-large";
  } else if (vuetify.display.lg.value) {
    return "large";
  }
});
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
.custom-alert {
  max-width: 300px; 
  font-size: 14px;
}

body {
  overflow: auto;
}

body::-webkit-scrollbar {
  width: 0em;
}

body::-webkit-scrollbar-thumb {
  background-color: transparent;
}
</style>