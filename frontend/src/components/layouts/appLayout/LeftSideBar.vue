<script setup lang="ts">
import { ref } from 'vue';


const items = ref([
  {
    label: 'Catalog',
    icon: 'pi pi-address-book',
    route: '/dashboard/catalog-index'
  },
  {
    label: 'Article',
    icon: 'pi pi-book',
    route: '/dashboard/article-index'
  },

  {
    label: 'tiptap',
    icon: 'pi pi-book',
    route: '/dashboard/tiptap'
  }


]);

</script>

<template>

    <Menu :model="items"  style="border:none;font-weight: normal">
    <template #item="{ item, props }">
        <router-link v-if="item.route" v-slot="{ href, navigate }" :to="item.route" custom>
            <a v-ripple :href="href" v-bind="props.action" @click="navigate">
                <span :class="item.icon" />
                <span class="ml-2">{{ item.label }}</span>
            </a>
        </router-link>
        <a v-else v-ripple :href="item.url" :target="item.target" v-bind="props.action">
            <span :class="item.icon" />
            <span class="ml-2">{{ item.label }}</span>
        </a>
    </template>

    </Menu>

</template>
