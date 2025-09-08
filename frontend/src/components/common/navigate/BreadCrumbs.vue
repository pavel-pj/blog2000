<script setup lang="ts">
import { ref } from 'vue';
import type { RouteLocationRaw } from 'vue-router';

const home = ref({
  icon: 'pi pi-home',
  route: '/introduction'
});

interface BreadcrumbItem {
  label: string;
  route?: RouteLocationRaw;
  icon?: string;
  url?: string;
  target?: string;
}


interface Props {
  items: BreadcrumbItem[];
}

const props = defineProps<Props>();

</script>


<template>
<div class="flex justify-start">
    <Breadcrumb :home="home" :model="items" style="padding-left:0px;">
      <template #item="{ item, props: breadcrumbProps }">
        <router-link
          v-if="item.route"
          v-slot="{ href, navigate }"
          :to="item.route"
          custom
        >
          <a :href="href" v-bind="breadcrumbProps.action" @click="navigate">
            <span :class="[item.icon, 'text-color']" />
            <span class="text-primary font-semibold">{{ item.label }}</span>
          </a>
        </router-link>
        <a v-else-if="item.url" :href="item.url" :target="item.target" v-bind="breadcrumbProps.action">
          <span class="text-surface-700 dark:text-surface-0">{{ item.label }}</span>
        </a>
        <span v-else class="text-surface-700 text-primary dark:text-surface-0">
          {{ item.label }}
        </span>
      </template>
    </Breadcrumb>
  </div>
</template>
