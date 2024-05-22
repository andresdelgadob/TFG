<template>
    <v-app>

        <div style="position: fixed; top: 0; right: 0; z-index: 1100;">
            
            <v-alert v-if="error" style="background-color:red; color: white;" transition="scale-transition" class="custom-alert">
                <v-row>
                    <v-col>
                        {{ error }}
                    </v-col>
                    <v-col :cols="3">
                        <v-btn v-if="error" @click="borrarError" icon size="x-small"> <v-icon>mdi-close</v-icon> </v-btn>
                    </v-col>
                </v-row>
                
                
                </v-alert>
                
        </div>

        <v-app-bar color="primary">
            <div class="d-flex align-center">
                <v-img contain height="100px" width="200px" src="logo uah blanco.png"></v-img>
                <v-divider vertical ></v-divider>
                <span class="mx-4" style="font-size: 24px;">IA Traductor</span>
            </div>
        </v-app-bar>

        <v-container class="content-container">
            <!-- Primer v-sheet a la izquierda -->
            <v-row v-if="vuetify.display.lgAndUp.value">
                <v-col xl="5">
                    <v-sheet :class="modelo" width="100%" :height="alturaResolucion" style="min-width: 480px; margin-top: 20px;" color="info">
                        <!-- Contenido del primer v-sheet -->
                        <v-combobox clearable label="Idioma" :items="idiomas" v-model="idiomaEntradaSeleccionado" rounded="t-xl"
                            :class="{ 'error--text': campoObligatorio }">
                        </v-combobox>

                        <v-form class="mx-5">
                            <v-textarea clearable label="Ingrese el texto aquí" variant="outlined" hide-details v-model="textoEntrada" no-resize
                            :rows="calcularFilas" :readonly="contarCaracteres() > 2500">
                            </v-textarea>
                        </v-form>

                        <v-row>
                            <v-col :cols="columnasBotonReconocimiento"><v-btn rounded="xl" size="small" style="margin-left: 15px;margin-top: 5px;" @click="recogerTextoVoz"
                            :disabled="!reconocimientoDisponible"><v-icon>{{ reconocimientoVoz ? 'mdi-record' : 'mdi-microphone' }}</v-icon></v-btn>
                            </v-col>
                            <v-col>
                            <v-btn  rounded="xl" size="small" :style="margenBotonLeer" @click="leerTexto(idiomaEntradaSeleccionado,textoEntrada)" :disabled="!textoEntrada">
                                <v-icon>{{ lectura ? 'mdi-stop' : 'mdi-volume-high' }} </v-icon>
                                </v-btn>
                            </v-col>
                            <v-col>
                            <p style="text-align: right; margin-right: 20px;margin-top: 5px;">{{ contarCaracteres(textoEntrada) }}/2500</p>
                            </v-col>
                        </v-row>
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
                        <v-dialog v-model="configuracion" :height="320" :width="480">
                            <v-card  :class="modelo" :height="320" :width="480">
                                <v-container>
                                    <v-row class="mb-0">
                                        <v-col>
                                            <v-card-title class="pa-6">Configuración</v-card-title>
                                        </v-col>
                                        <v-col style="margin-left: 160px;">
                                            <v-card-actions class="d-flex pa-0 mt-4">
                                                <v-btn @click="configuracion = false" icon="mdi-close-circle"></v-btn>
                                            </v-card-actions>
                                        </v-col>
                                    </v-row>
                                    <v-row>
                                        <v-card-text class="pt-0">
                                            <v-row class="d-flex align-center ml-5">
                                                <v-col cols="auto" class="d-flex align-center pa-0">
                                                    <v-switch color="info" v-model="formal" label="Traducir formalmente"></v-switch>
                                                </v-col>
                                                <v-col cols="auto" class="d-flex align-center mb-6 pa-0">
                                                    <v-tooltip bottom>
                                                        <template v-slot:activator="{ props }">
                                                        <v-btn icon variant="text" v-bind="props" v-on="on">
                                                            <v-icon>mdi-information</v-icon>
                                                        </v-btn>
                                                        </template>
                                                        <span>El texto traducido aumentará en medida de lo posible la formalidad respecto al original</span>
                                                    </v-tooltip>
                                                </v-col>
                                            </v-row>
                                            <v-row class="d-flex align-center ml-5">
                                                <v-col cols="auto" class="d-flex align-center pa-0">
                                                    <v-switch color="info" v-model="formato" label="Mantener formato"></v-switch>
                                                </v-col>
                                                <v-col cols="auto" class="d-flex align-center mb-6 pa-0">
                                                    <v-tooltip bottom>
                                                        <template v-slot:activator="{ props }">
                                                        <v-btn icon variant="text" v-bind="props" v-on="on">
                                                            <v-icon>mdi-information</v-icon>
                                                        </v-btn>
                                                        </template>
                                                        <span>El texto traducido mantendrá el mismo formato al original</span>
                                                    </v-tooltip>
                                                </v-col>
                                            </v-row>
                                        </v-card-text>
                                    </v-row>
                                </v-container>
                            </v-card>
                        </v-dialog>           
                    </v-row>
                </v-col>

                <v-col xl="5">
                    <!-- Segundo v-sheet a la derecha -->
                    <v-sheet :class="modelo" color="info" width="100%" :height="alturaResolucion" style="position: relative; min-width: 480px; margin-top: 20px;">
                        <!-- Contenido del segundo v-sheet -->
                            <v-progress-circular v-if="cargando" indeterminate style="z-index: 1; position: absolute; left: 50%; top: 50%; transform: translate(-50%, -50%);"></v-progress-circular>
                            <v-combobox
                                clearable
                                label="Idioma*"
                                :items="idiomas"
                                v-model="idiomaSalidaSeleccionado"
                                rounded="t-xl"
                                :class="{ 'error--text': campoObligatorio}"
                            ></v-combobox>

                            <v-form class="mx-5">
                                <v-textarea 
                                    :class="modeloSalida"
                                    label="Traducción"
                                    variant="outlined"
                                    hide-details
                                    v-model="textoSalida"
                                    no-resize
                                    :rows="calcularFilas"
                                    readonly
                                >
                                </v-textarea>

                            <v-btn  rounded="xl" size="small" style="text-align: center;margin-top: 5px;" @click="leerTexto(idiomaSalidaSeleccionado,textoSalida)" :disabled="!textoSalida">
                                    <v-icon left>{{ lectura ? 'mdi-stop' : 'mdi-volume-high' }}</v-icon>
                                    </v-btn>
                            </v-form>
                    </v-sheet>
                </v-col>
            </v-row>

            <v-col v-if="vuetify.display.mdAndDown.value">
                <v-row>
                    <v-sheet :class="modelo" width="100%" height=270px style="position: relative;min-width: 220px;margin-top: 20px;" color="info">
                        <!-- Contenido del primer v-sheet -->
                        <v-combobox clearable label="Idioma" :items="idiomas" v-model="idiomaEntradaSeleccionado" rounded="t-xl"
                            :class="{ 'error--text': campoObligatorio }">
                        </v-combobox>

                        <v-form class="mx-5">
                            <v-textarea clearable label="Ingrese el texto aquí" variant="outlined" hide-details v-model="textoEntrada" no-resize
                            :rows="calcularFilas" :readonly="contarCaracteres() > 2500">
                            </v-textarea>
                        </v-form>

                        <v-row>
                            <v-col :cols="columnasBotonReconocimiento"><v-btn rounded="xl" size="small" style="margin-left: 15px;margin-top: 5px;" @click="recogerTextoVoz"
                            :disabled="!reconocimientoDisponible"><v-icon>{{ reconocimientoVoz ? 'mdi-record' : 'mdi-microphone' }}</v-icon></v-btn>
                            </v-col>
                            <v-col>
                            <v-btn  rounded="xl" size="small" :style="margenBotonLeer" @click="leerTexto(idiomaEntradaSeleccionado,textoEntrada)" :disabled="!textoEntrada">
                                <v-icon>{{ lectura ? 'mdi-stop' : 'mdi-volume-high' }} </v-icon>
                                </v-btn>
                            </v-col>
                            <v-col>
                            <p style="text-align: right; margin-right: 20px;margin-top: 5px;">{{ contarCaracteres(textoEntrada) }}/2500</p>
                            </v-col>
                        </v-row>
                    </v-sheet>
                </v-row>

                <v-row justify="center">
                        <v-btn style="text-align: right; margin-right: auto; margin-top: 18px;" color="info" rounded="xl" size="x-small" prepend-icon="mdi-arrow-left-right-bold" stacked @click="intercambiarIdiomas"></v-btn>
                        <v-btn style="margin-bottom: 10px; margin-top: 10px;" @click="traducir" color="info" rounded="xl" prepend-icon="mdi-arrow-right-bold" stacked>Traducir</v-btn>
                        <v-btn style="text-align: left; margin-left: auto; margin-top: 18px;" @click="configuracion = true" color="info" rounded="xl" size="x-small" prepend-icon="mdi-cog" stacked></v-btn>
                        <v-dialog v-model="configuracion" :height="300" :width="320">
                            <v-card  :class="modelo" :height="300" :width="320">
                                <v-container>
                                    <v-row class="mb-0">
                                        <v-col cols="8" class="d-flex align-center">
                                            <v-card-title class="pa-0">Configuración</v-card-title>
                                        </v-col>
                                        <v-col cols="4">
                                            <v-card-actions class="d-flex pa-4">
                                                <v-btn @click="configuracion = false" icon="mdi-close-circle"></v-btn>
                                            </v-card-actions>
                                        </v-col>
                                    </v-row>
                                    <v-row>
                                        <v-card-text class="pt-0">
                                            <v-row class="d-flex align-center ml-5">
                                                <v-col cols="auto" class="d-flex align-center pa-0">
                                                    <v-switch color="info" v-model="formal" label="Traducir formalmente"></v-switch>
                                                </v-col>
                                                <v-col cols="auto" class="d-flex align-center mb-6 pa-0">
                                                    <v-tooltip bottom>
                                                        <template v-slot:activator="{ props }">
                                                        <v-btn icon variant="text" v-bind="props" v-on="on">
                                                            <v-icon>mdi-information</v-icon>
                                                        </v-btn>
                                                        </template>
                                                        <span>El texto traducido aumentará en medida de lo posible la formalidad respecto al original</span>
                                                    </v-tooltip>
                                                </v-col>
                                            </v-row>
                                            <v-row class="d-flex align-center ml-5">
                                                <v-col cols="auto" class="d-flex align-center pa-0">
                                                    <v-switch color="info" v-model="formato" label="Mantener formato"></v-switch>
                                                </v-col>
                                                <v-col cols="auto" class="d-flex align-center mb-6 pa-0">
                                                    <v-tooltip bottom>
                                                        <template v-slot:activator="{ props }">
                                                        <v-btn icon variant="text" v-bind="props" v-on="on">
                                                            <v-icon>mdi-information</v-icon>
                                                        </v-btn>
                                                        </template>
                                                        <span>El texto traducido mantendrá el mismo formato al original</span>
                                                    </v-tooltip>
                                                </v-col>
                                            </v-row>
                                        </v-card-text>
                                    </v-row>
                                </v-container>
                            </v-card>
                        </v-dialog>           
                </v-row>

                <v-row >
                    <!-- Segundo v-sheet a la derecha -->
                    <v-sheet :class="modelo" color="info" width="100%" height=270px style="position: relative;min-width: 220px;">
                        <!-- Contenido del segundo v-sheet -->
                            <v-progress-circular v-if="cargando" indeterminate style="z-index: 1; position: absolute; left: 50%; top: 50%; transform: translate(-50%, -50%);"></v-progress-circular>
                            <v-combobox
                                clearable
                                label="Idioma*"
                                :items="idiomas"
                                v-model="idiomaSalidaSeleccionado"
                                rounded="t-xl"
                                :class="{ 'error--text': campoObligatorio}"
                            ></v-combobox>

                            <v-form class="mx-5">
                                <v-textarea 
                                    :class="modeloSalida"
                                    label="Traducción"
                                    variant="outlined"
                                    hide-details
                                    v-model="textoSalida"
                                    no-resize
                                    :rows="calcularFilas"
                                    readonly
                                >
                                </v-textarea>

                            <v-btn  rounded="xl" size="small" style="text-align: center;margin-top: 5px;" @click="leerTexto(idiomaSalidaSeleccionado,textoSalida)" :disabled="!textoSalida">
                                    <v-icon left>{{ lectura ? 'mdi-stop' : 'mdi-volume-high' }}</v-icon>
                                    </v-btn>
                            </v-form>
                    </v-sheet>
                </v-row>
            </v-col>
        </v-container>
    </v-app>
</template>  

<script setup>
import { ref, computed } from "vue";
import vuetify from './plugins/vuetify'
import idiomas from './assets/idiomas.json';
import idiomasClave from './assets/idiomasClave.json';

const modelo='rounded-xl';
const modeloSalida='.rounded-t-xl';
const idiomaEntradaSeleccionado= ref('');
const idiomaSalidaSeleccionado= ref('');
const textoEntrada= ref('');
const textoSalida= ref('');
const configuracion = ref(false);
const cargando = ref(false);
const error= ref('');
const formal = ref(false);
const formato = ref(false);
const reconocimientoDisponible = ref(false);
const reconocimientoVoz = ref(null);
const lectura = ref(false);

if ('SpeechRecognition' in window || 'webkitSpeechRecognition' in window) {
  reconocimientoDisponible.value = true;
}

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

const intercambiarIdiomas = () => {
  const temp = idiomaEntradaSeleccionado.value;
  idiomaEntradaSeleccionado.value = idiomaSalidaSeleccionado.value;
  idiomaSalidaSeleccionado.value = temp;
};

const borrarError = () => {
    error.value = null;
};

const calcularFilas = computed(() => {
  if (vuetify.display.xlAndUp.value) {
    return 21;
  } else if (vuetify.display.lg.value) {
    return 16;
  }else if(vuetify.display.mdAndDown.value){
    return 5;
  }
});

const mostrarAlertaError = (mensaje) => {
    error.value = mensaje;
    setTimeout(borrarError, 4000);
};

const contarCaracteres = function () {
  if (!textoEntrada.value) {
    return 0;
  }
  if (textoEntrada.value.length > 2500) {
    textoEntrada.value = textoEntrada.value.slice(0, 2500);
  }
  return textoEntrada.value.length;
};

const recogerTextoVoz = () => {
  if (!reconocimientoVoz.value) { 
    reconocimientoVoz.value = new (window.SpeechRecognition || window.webkitSpeechRecognition)();
    reconocimientoVoz.value.interimResults = true;
    reconocimientoVoz.value.continious = true;
    reconocimientoVoz.value.start();

    let textoAntiguo=textoEntrada.value;

    reconocimientoVoz.value.onresult = (event) => {
      let resultado = '';

      for (let i = 0; i < event.results.length; i++) {
        resultado += event.results[i][0].transcript + ' ';
      }

      textoEntrada.value = textoAntiguo+resultado;
    }
  };

  reconocimientoVoz.value.onend = () => {
      reconocimientoVoz.value.stop();
      reconocimientoVoz.value = null;
    };
};

const leerTexto = (idioma,texto) => {
  const synthesis = window.speechSynthesis;
  if(lectura.value===false){
    const message = new SpeechSynthesisUtterance(texto);

    message.lang = idiomasClave[idioma];

    synthesis.speak(message);

    lectura.value=true;
    message.onend = () => {
      lectura.value = false;
    };
  }else{
    synthesis.cancel();

    lectura.value=false;
  }
  
};

const margenBotonLeer = computed(() => {
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

const columnasBotonReconocimiento = computed(() => {
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