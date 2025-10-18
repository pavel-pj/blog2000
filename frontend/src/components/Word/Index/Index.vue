<script setup lang="ts">
//import Filters from '@/components/Word/Index/Index-filter-test.vue';

import { ref, onMounted ,computed, watch} from 'vue';
import {useRoute, useRouter} from 'vue-router';
import {
  //wordsURL,
  deleteWordURL
} from '@/config/request-urls';
import { useHttpRequest } from '@/utils/http-request';
import modalSpiner from '@/components/common/spiner/ModalSpiner.vue';
import PageSpiner from '@/components/common/spiner/PageSpiner.vue';
import useConfirm from '@/composables/modals/Confirmer';
import BreadCrumbs from '@/components/common/navigate/BreadCrumbs.vue';
import { useWordsStore } from '@/store/wordsStore';
import { storeToRefs } from 'pinia';
import { FilterMatchMode } from '@primevue/core/api';




const router = useRouter();
const route = useRoute();

const wordsStore = useWordsStore();

const {
  data: words,
  subject,
  loading: wordsLoading,
  error: wordsError
} = storeToRefs(wordsStore);

const {
  fetchData,
  deleteWord
} = wordsStore;


const confirm = useConfirm();
const isSpiner = ref<boolean>(false);
const margYspiner = '24';



const wordsArray = computed(() => {
  return words.value || [];
});

const isPageSpiner = computed (()=>{
  return  (wordsLoading.value) ? true : false;

});

const {
  sendRequest: sendDelete
} = useHttpRequest( );


const dataToDelete = ref<any>('');

const openDelete =(data:any)=>{
  dataToDelete.value = data;
  confirm({
    message: 'Do you want to delete this record?',
    accept: deleteItem,
    successMessage: 'Record successefully deleted'

  });
};


const deleteItem = async () =>{

  isSpiner.value = true;

  const res = await sendDelete({
    url: deleteWordURL(dataToDelete.value?.id),
    method: 'DELETE'

  });

  if (res?.isOk) {
    //await getWords();
    isSpiner.value = false;
    deleteWord(dataToDelete.value?.id);
  } else {
    isSpiner.value = false;
  }

};


onMounted(async () => {

  if (words.value.length === 0){
    fetchData(route.params.subject_id as string);
  } else if
  (subject.value   && subject.value.id !== route.params.subject_id as string) {
    fetchData(route.params.subject_id as string);
  }

});



const create = () => {
  router.push({name: 'word-create'});
};

const topics = () => {
  router.push({
    name: 'topics-index',
    params: {subject_id: route.params.subject_id}
  });
};



const onRowSelect =(event)=>{
  //console.log(event.data.id);
  router.push({ name: 'word-edit' ,
    params: { word_id : event.data.id }
  }
  );
};


const filters = ref({
  name: { value: null, matchMode: FilterMatchMode.CONTAINS }
});


const itemsBreadCrumbs =computed(()=>{

  return ([
    { label: subject.value?.name || '',
      route: {name:'subject-edit' , params : {subject_id: subject.value?.id}
      }
    },
    { label: 'Words'   }]);
});


</script>
<template>


<BreadCrumbs :items="itemsBreadCrumbs" />
<div class="flex flex-raw justify-start gap-6">
  <Button @click="create" label="Primary" rounded style="display:block">Create </Button>
  <Button @click="topics" label="Primary" rounded severity="secondary" style="display:block">Topics </Button>
</div>

  <PageSpiner :my="margYspiner"  :isSpiner="isPageSpiner"  />

 <div class="card pt-6 " v-if="!wordsLoading" >
        <DataTable
        v-model:filters="filters"
        :value="words"
        filterDisplay="row"
        stripedRows
        paginator
        :rows="5"
        :rowsPerPageOptions="[5, 10, 20, 50]"
        selectionMode="single"
        dataKey="id"
        :metaKeySelection="false"
        @rowSelect="onRowSelect"
        tableStyle=" "
        >

          <Column field="name" header="Name">
                <template #body="{ data }">
                    {{ data.name }}
                </template>
                 <template #filter="{ filterModel, filterCallback }">
                    <InputText v-model="filterModel.value" type="text" @input="filterCallback()" placeholder="Search by name" />
                </template>


          </Column>
           <Column field="id" header="Id"  ></Column>
           <Column class="w-16 h-16 !text-end">
                <template #body="{ data }"  >
                    <Button icon="pi pi-times" @click="openDelete(data)" severity="danger" class="p-button-sm w-2rem h-2rem"></Button>
                </template>
            </Column>

        </DataTable>
        <Toast />
    </div>

  <modalSpiner :isSpiner="isSpiner" ></modalSpiner>

</template>

