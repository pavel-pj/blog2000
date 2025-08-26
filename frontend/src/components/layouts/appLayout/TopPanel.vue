<script setup lang="ts">
//import {computed} from 'vue';
import {useRouter} from 'vue-router';
import {useAuthStore} from '@/store/auth';



const router = useRouter();
const authStore = useAuthStore();

//const user = computed(() => authStore.user);

const logout = async () => {
  await authStore.logout();
  router.push({name: 'login'});
};

</script  >
<template>
  <div class="flex items-center justify-between gap-2 py-3 px-6 bg-white">
    <div class="flex-grow">

    </div>
    <div class="whitespace-nowrap">

    </div>
    <div>
      <Button @click="logout" label="Primary" rounded>Logout</Button>
    </div>
  </div>

</template>


