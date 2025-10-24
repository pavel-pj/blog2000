<script setup lang="ts">
import { ref, onMounted ,computed, watch} from 'vue';
import {useRoute, useRouter} from 'vue-router';
import { TaskItem } from '@/types/tasks';
import {
//wordsURL,
  //updateWordURL,
  updateWordStatusTaskWordStatusURL,
  updateTaskURL
} from '@/config/request-urls';
import { useHttpRequest } from '@/utils/http-request';
import modalSpiner from '@/components/common/spiner/ModalSpiner.vue';
import PageSpiner from '@/components/common/spiner/PageSpiner.vue';
import useConfirm from '@/composables/modals/Confirmer';
import BreadCrumbs from '@/components/common/navigate/BreadCrumbs.vue';
import { useRepetitionStore } from '@/store/repetitionStore';
import { storeToRefs } from 'pinia';
import { FilterMatchMode } from '@primevue/core/api';




const router = useRouter();
const route = useRoute();
const isNext = ref<boolean>(false);



const {
  data: repetition,
  tasks,

  loading: repetitionLoading,
  error: repetitionError
} = storeToRefs(useRepetitionStore());

const {
  fetchData ,
  getNewTask,
  deleteWordFromTask,
  deleteTask,
  isTaskEmpty
} = useRepetitionStore();

onMounted(async () => {
  if(!repetition.value || route.params.repetition_id !== repetition.value?.id){
    fetchData(route.params.repetition_id as string);
  }
});

const card = ref<TaskItem>();

watch (
  ()=>tasks.value,
  (newValue)=>{

    card.value = getNewTask();
  },
  { deep: true, immediate: true }
);





interface WordResponse {
  id: string;
  name: string;
  translation: string;
  subject_id: string;
  created_at: string;
  updated_at: string;
  status: string;
  repeated_at: string;
}

interface TaskWordResponse {
  id: string;
  word_id: string;
  task_id: string;
  status: string;
}

interface UpdateResponse {
  word: WordResponse;
  task_word: TaskWordResponse;
  task_status: 'NEW' | 'DONE';
}

const {
  data: updatedWord,
  sendRequest: updateWordRequest
} = useHttpRequest({
  showErrorToast: false,
  showSuccessToast: false
});



const {
  data: updatedTask,
  sendRequest: updateTaskdRequest
} = useHttpRequest({
  showErrorToast: false,
  showSuccessToast: false
});


const toRepeate = async ({
  word_id,
  task_word_id,
  word_status,
  task_word_status
}: {
  word_id: string;
  task_word_id?: string;
  word_status: string;
  task_word_status: string;
}) => {
  //console.log(`id : ${word_id}; status: ${task_word_id}`);

  const params = {
    word_id: word_id,
    task_word_id: task_word_id,
    word_status: word_status,
    task_word_status: task_word_status
  };


  deleteWordFromTask( params?.task_word_id );

  //if empty task - delete task from storage
  if (isTaskEmpty(card.value?.id)){
    deleteTask(card.value?.id);
    isNext.value = false;
  }

  const res = await updateWordRequest({
    url: updateWordStatusTaskWordStatusURL(),
    method: 'PATCH',
    data: params
  });
  if (res?.isOk) {
    const responseData = res.data as unknown as UpdateResponse;
  }

};



const showNext =()=>{

  if (isNext.value === false) {
    isNext.value = true;
  } else {
    console.log('1 isNext: ' +  isNext.value);
    isNext.value = false;
    deleteTask(card.value?.id);
    console.log('2 isNext: ' +  isNext.value);

  }
  //Обновляем карточку
};

const skipTask  = async (task_id : string) =>
{
  //if there is undone words - just delete from store ( but not from database)
  tasks.value = tasks.value?.filter(task => task.id !== task_id);
  isNext.value = false;
  deleteTask(card.value?.id);

};

</script>
<template>

<div class="max-w-sm  bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700">

    <div >
        <h5 class="p-6 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">
            {{ card?.task }}
        </h5>
         <div v-if="!isNext" class="flex justify-between items-center  border-t-1 border-stone-200   "  >

            <div></div>
            <div
            @click="showNext"
            class="cursor-pointer inline-flex items-center mr-6 my-6 px-3 py-2 text-sm font-medium text-center
            text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                next
                <svg class="rtl:rotate-180 w-3.5 h-3.5 ms-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5h12m0 0L9 1m4 4L9 9"/>
                </svg>
            </div>
        </div>

    </div>

    <div v-if="isNext">
        <div class=" p-6 mb-3 font-normal text-gray-700 dark:text-gray-400">
            {{ card?.answer }}</div>
        <div v-if="card?.words?.length"
        class=" border-t-1 border-stone-200 px-6 py-3 flex flex-col  divide-y divide-gray-300 " >

        <div v-for="word in card?.words " :key="word?.id"
            class="py-2 " >
                <div class="py-4 first:pt-0 last:pb-0">
                    <p class="text-stone-900 font-bold">{{ word.name }}</p>
                    <span class="italic"> {{ word.translation }} </span>

                    <div class="flex flex-row justify-evenly py-4">
                        <button type="button"
                        @click="toRepeate({
                            word_id: word.id,
                            task_word_id: word?.pivot?.id,
                            word_status: 'REPEATED',
                            task_word_status: 'DONE'
                        })"
                        class="text-blue-700 cursor-pointer py-2.5 px-5 me-2 mb-2 text-sm font-medium
                        focus:outline-none bg-gray-100 rounded-full border border-gray-200
                        hover:bg-white   focus:z-10 focus:ring-4 focus:ring-gray-100
                        dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600
                        dark:hover:text-white dark:hover:bg-gray-700">
                            I know</button>

                        <button type="button"
                        @click="toRepeate({
                            word_id: word.id,
                            task_word_id: word?.pivot?.id,
                            word_status: 'IMPORTANT',
                            task_word_status: 'DONE'
                        })"
                        class="text-blue-700 cursor-pointer py-2.5 px-5 me-2 mb-2 text-sm font-medium
                        focus:outline-none bg-gray-100 rounded-full border border-gray-200
                        hover:bg-white   focus:z-10 focus:ring-4 focus:ring-gray-100
                        dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600
                        dark:hover:text-white dark:hover:bg-gray-700">
                        Repeat</button>
                    </div>

                </div>
            </div>

        </div>
        <div class="flex justify-between items-center  border-t-1 border-stone-200   "  >

            <div></div>
            <div
            @click="skipTask(card?.id as string)"
            class="cursor-pointer inline-flex items-center mr-6 my-6 px-3 py-2 text-sm font-medium text-center
            text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                skip
                <svg class="rtl:rotate-180 w-3.5 h-3.5 ms-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5h12m0 0L9 1m4 4L9 9"/>
                </svg>
            </div>
        </div>
    </div>
</div>

</template>
