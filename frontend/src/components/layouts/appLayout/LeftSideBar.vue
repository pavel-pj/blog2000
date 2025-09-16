<script setup lang="ts">
import { ref } from 'vue';
import { useAuthStore } from '@/store/auth';

const auth = useAuthStore();

const items = ref<object[]  >([]);

if (auth.hasRole('Admin')){
  items.value.push({
    label: 'Catalog',
    icon: 'pi pi-address-book',
    to: { name: 'catalogs-index' }
  });
  items.value.push({
    label: 'Article',
    icon: 'pi pi-book',
    to: { name: 'articles-index' }
  });
}

if (auth.hasRole('User')){
  items.value.push({
    label: 'Subject',
    icon: 'pi pi-address-book',
    to: { name: 'subjects-index' }
  });

}







</script>

<template>

  <Menu :model="items"  style="border:none;font-weight: normal">
  <template #item="{ item, props }">
      <router-link v-if="item.to" v-slot="{ href, navigate }" :to="item.to" custom>
        <a v-ripple :href="href" v-bind="props.action" @click="navigate">
          <span :class="item.icon" />
          <span class="ml-2">{{ item.label }}</span>
        </a>
      </router-link>
      <a v-else-if="item.url" v-ripple :href="item.url" :target="item.target" v-bind="props.action">
        <span :class="item.icon" />
        <span class="ml-2">{{ item.label }}</span>
      </a>
      <a v-else v-ripple v-bind="props.action">
        <span :class="item.icon" />
        <span class="ml-2">{{ item.label }}</span>
      </a>
    </template>

    </Menu>

</template>
