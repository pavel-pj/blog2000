<script setup lang="ts">

import ProgressSpinner from 'primevue/progressspinner';

type Props = {
   isSpiner:boolean,
   color?:string,
   my?:string
};
const props = withDefaults(defineProps<Props>(),{
  color: '#10b981',
  my:'12'
} );

</script>
<template>
    <div class="page-spinner"
    v-if="props.isSpiner"

    :style="{ marginTop: `${my}px`,marginBottom: `${my}px` }">
        <ProgressSpinner

            style="width: 35px; height:35px;"  strokeWidth="4"    fill="transparent"
            animationDuration="1s" aria-label="Custom ProgressSpinner" />
    </div>
 </template>

 <style scoped>

.page-spinner :deep(.p-progressspinner-circle) {
  stroke: v-bind(props.color) !important;
  animation: none !important;

}

</style>
