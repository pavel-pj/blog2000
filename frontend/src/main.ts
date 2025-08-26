import "./assets/styles/main.css";
import 'primeicons/primeicons.css';
import { createApp, type App as VueApp } from "vue"; // Указание типа для app
import App from "./App.vue";
import router from './router';
import { createPinia, type Pinia } from "pinia"; // Тип для pinia
import http from './http'; // Убедитесь, что http тоже имеет типы
import Aura from "@primeuix/themes/aura";
import PrimeVue from "primevue/config";
import ConfirmationService from 'primevue/confirmationservice';
import ToastService from 'primevue/toastservice';

 


const app: VueApp = createApp(App); // Явная типизация
const pinia: Pinia = createPinia();

app.use(pinia);
app.use(router);
app.use(PrimeVue, {
	theme: {
		preset: Aura,
		options: {
			darkModeSelector: ".p-dark",
		},
		 
	},
});
 
app.use(ConfirmationService);
app.use(ToastService);


// Типизация для http (если используется, например, axios)
declare module '@vue/runtime-core' {
  interface ComponentCustomProperties {
    $http: typeof http; // Добавляем $http в глобальную область Vue
  }
}
app.config.globalProperties.$http = http;

app.mount('#app');
