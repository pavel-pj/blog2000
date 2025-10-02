<script setup lang="ts">
import { subjectCreateURL } from '@/config/request-urls';
import {useRouter} from 'vue-router';


    type Props = {
        subject:any,

    };
const router = useRouter();


const props = defineProps<Props>();


const navigateToSubject = () => {
  console.log('to subject');
  router.push({
    name: 'subject-edit', params: { subject_id: props.subject.id }
  });
};

const openWords = ()=>{
  router.push({
    name: 'words-index',
    params : { subject_id : props.subject.id}
  });
};

const openTopics = ()=>{
  router.push({
    name: 'topics-index',
    params : { subject_id : props.subject.id}
  });
};

</script>
<template>

<div

class="w-full sm:w-1/2 lg:w-1/3 xl:w-1/4 max-w-sm  bg-white border border-gray-200 rounded-lg

  transition
  duration-300
  ease-out
  shadow-sm dark:bg-gray-800 dark:border-gray-700 text-gray-600 dark:text-white hover:scale-105 ">
    <div @click="navigateToSubject" class="hover:bg-fuchsia-700 hover:text-white rounded-tr-lg rounded-tl-lg" >
        <h5 class="p-6 pb-12 text-2xl font-bold tracking-tight cursor-pointer     ">
         {{props.subject.name}}
        </h5>
    </div>
  <div class="flex flex-row justify-between px-4 py-8">
    <div  >
      <div
      @click="openWords"
      class="inline-flex max-w-sm items-center px-6 py-2 text-sm font-medium text-center  cursor-pointer
      text-stone-700
      bg-stone-300 rounded-lg
      hover:bg-stone-200 focus:ring-4 focus:outline-none
      dark:hover:bg-blue-700 dark:focus:ring-blue-800 hover:text-fuchsia-700">
          Words
          <svg class="rtl:rotate-180 w-3.5 h-3.5 ms-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
              <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5h12m0 0L9 1m4 4L9 9"/>
          </svg>
        </div>
    </div>

    <div >
      <div
      @click="openTopics"
      class="inline-flex max-w-sm items-center px-6 py-2 text-sm font-medium text-center  cursor-pointer
      text-stone-700
      bg-stone-300 rounded-lg
      hover:bg-stone-200 focus:ring-4 focus:outline-none
      dark:hover:bg-blue-700 dark:focus:ring-blue-800 hover:text-fuchsia-700">
          Topics
          <svg class="rtl:rotate-180 w-3.5 h-3.5 ms-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
              <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5h12m0 0L9 1m4 4L9 9"/>
          </svg>
        </div>
    </div>
   </div>
</div>
</template>
