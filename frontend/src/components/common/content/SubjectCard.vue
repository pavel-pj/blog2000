<script setup lang="ts">
import {useRouter} from 'vue-router';


    type Props = {
        subject:any,

    };
const router = useRouter();


const props = defineProps<Props>();


const navigateToSubject = () => {
  router.push({
    name: 'asubject-edit', params: { subject_id: props.subject.id }
  });
};

</script>
<template>

  <div
  @click="navigateToSubject"
  class="grow max-w-sm rounded-2xl overflow-hidden  my-8 py-12 text-center border border-purple-500 cursor-pointer hover:bg-purple-200 hover:shadow-lg hover:text-purple-800 transition duration-600">
  <img class="w-full" src="/images/img201.jpg"  >
  <div class="px-6 py-4  ">

    <div class="font-bold text-xl mb-2 ">{{props.subject.name}}</div>
    <p class="text-gray-700 text-base">

    </p>
  </div>

</div>
</template>
