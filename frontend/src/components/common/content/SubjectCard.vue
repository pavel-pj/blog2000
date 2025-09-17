<script setup lang="ts">
import { subjectCreateURL } from '@/config/request-urls';
import {useRouter} from 'vue-router';


    type Props = {
        subject:any,

    };
const router = useRouter();


const props = defineProps<Props>();


const navigateToSubject = () => {
  router.push({
    name: 'subject-edit', params: { subject_id: props.subject.id }
  });
};

const openCard = ()=>{
  router.push({
    name: 'words-index',
    params : { subject_id : props.subject.id}
  });
};

</script>
<template>

<div
@click="openCard"
class="w-full sm:w-1/2 lg:w-1/3 xl:w-1/4 max-w-sm  p-6 bg-white border border-gray-200 rounded-lg
hover:bg-fuchsia-700 hover:text-white hover:scale-105
  transition
  duration-300
  ease-out cursor-pointer
shadow-sm dark:bg-gray-800 dark:border-gray-700 text-gray-600 dark:text-white">
    <span >
        <h5 class="mb-2 text-2xl font-bold tracking-tight  ">
         {{props.subject.name}}
        </h5>
      </span>
    <!--
    <p class="mb-3 font-normal text-gray-700 dark:text-gray-400">
      Here are the biggest enterprise technology acquisitions of 2021 so far, in reverse chronological order.</p>
    -->
    <div
    @click="navigateToSubject"
    class="inline-flex max-w-sm items-center px-3 py-2 mt-6 text-sm font-medium text-center  cursor-pointer
    text-stone-700
    bg-stone-300 rounded-lg
    hover:bg-white focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600
    dark:hover:bg-blue-700 dark:focus:ring-blue-800 hover:text-fuchsia-700">
        Edit
        <svg class="rtl:rotate-180 w-3.5 h-3.5 ms-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5h12m0 0L9 1m4 4L9 9"/>
        </svg>
      </div>
</div>
</template>
