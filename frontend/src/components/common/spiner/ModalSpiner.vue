<script setup lang="ts">
import ProgressSpinner from 'primevue/progressspinner';


type Props = {
   isSpiner:boolean,
   color?:string
};
const props = withDefaults(defineProps<Props>(),{
  color:'white'
} );


</script>
<template>
 <div  v-if="isSpiner"   class="fixed inset-0 bg-black/50 flex items-center justify-center p-4 z-50 backdrop-blur-m">
  <div class="  max-w-full max-h-[90vh] overflow-auto p-8">
    <div class="custom-spinner" >
    <ProgressSpinner
        style="width: 60px; height:60px; margin-top:2rem" strokeWidth="4"    fill="transparent"
        animationDuration="1s" aria-label="Custom ProgressSpinner" />
    </div>
  </div>
</div>
</template>

<style scoped>
/* Для Vue 3 + PrimeVue */
.custom-spinner :deep(.p-progressspinner-circle) {
  stroke: v-bind(props.color) !important; /* Синий цвет */
  animation: none !important; /* Отключаем анимацию смены цветов */
}

</style>
