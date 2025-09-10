<script setup lang="ts">
import { ref, computed } from 'vue';
import { useRouter } from 'vue-router';
import { useAuthStore } from '@/store/auth';
import InputText from 'primevue/inputtext';
import Password from 'primevue/password';


const props = defineProps({
  isReg: {  // registration = true
    type: Boolean,
    default: false
  }
});

const title = computed(()=>{
  if (props.isReg){
    return 'Create new account';
  } else {
    return 'Sign in to your account';
  }
});
const buttonTitle = computed(()=>{
  if (props.isReg){
    return 'Sign Up';
  } else {
    return 'Sign In';
  }
});

const router = useRouter();
const authStore = useAuthStore();
const apiUrl = computed(() => import.meta.env.VITE_API_URL);
interface Data {
  email:string,
  password:string,
  password_confirmation?:string
}

const form  = ref<Data>({
  email: '',
  password: '',
  password_confirmation:''
});

const isLoading = ref(false);

const send = async () => {

  if (!props.isReg) {
    try {
      isLoading.value = true;
      await authStore.login(form.value);
      // Перенаправление после успешного входа
      const redirect = router.currentRoute.value.query.redirect || '/dashboard';
      router.push(redirect as string);
    } catch (error) {
      console.error('Login failed:', error);
      // Обработка ошибки (показать сообщение пользователю)
    }
  } else {
    if (props.isReg) {
      try {
        isLoading.value = true;
        await authStore.createUser(form.value);
        // Перенаправление после успешного входа
        const redirect = router.currentRoute.value.query.redirect || '/dashboard';
        router.push(redirect as string);
      } catch (error) {
        console.error('Registration failed:', error);
        // Обработка ошибки (показать сообщение пользователю)
      }
    }
  }
};


</script>

<template>

  <form @submit.prevent="">
    <div class="flex flex-col gap-6">
      <div class="block text-2xl font-bold text-center my-6">{{title}}</div>
      <div>
        <label for="email" class="block pb-2">Email</label>
        <InputText id="email" v-model="form.email"  class="w-full" />
      </div>
      <div>
        <label for="password" class="block pb-2">Password</label>
        <div class="card flex justify-between">
          <Password id="password"
                    class="w-full "
                    inputClass="w-full"
                    v-model="form.password" :feedback="false" toggleMask   />
        </div>

      </div>
      <div v-if="isReg">
        <label for="password_confirmation" class="block pb-2">Confirm password</label>
          <div class="card flex justify-center">
           <Password id="password_confirmation" v-model="form.password_confirmation"
                     class="w-full "
                     inputClass="w-full"
                     toggleMask :feedback="false" />
          </div>
      </div>

      <Button type="button" :label="buttonTitle"  :loading="isLoading"
              class="bg-blue-500 hover:bg-blue-600 text-white" @click="send" />

    </div>
  </form>


</template>
