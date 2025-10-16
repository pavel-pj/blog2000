<script setup lang="ts">

import { ref, onMounted ,computed} from 'vue';
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

const router = useRouter();
const route = useRoute();

const wordsStore = useWordsStore();

/*
interface WordItem {
  id: string;
  name: string;
  translation:string;
  subject_id: string;
  created_at: string;
  updated_at: string;

}
  */

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

/*
interface SubjectData {
  subject: any;
  data: WordItem[];
}
*/

const confirm = useConfirm();
const isSpiner = ref<boolean>(false);
const margYspiner = '24';

/*
const {
  data: wordsData,
  sendRequest: sendData
} = useHttpRequest<SubjectData>({
  showSuccessToast:false,
  showErrorToast: false
});*/

//const tableData = ref<WordItem[]>([]);


const wordsArray = computed(() => {
  return words.value || [];
});

const isPageSpiner = computed (()=>{
  if (words.value.length > 0 ) {
    return false;
  };
  return true;
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
  if (words.value.length===0) {
    fetchData(route.params.subject_id as string);
  }
});

/*
const getWords = async()=> {

  const response = await sendData({
    url: wordsURL(route.params.subject_id as string)
  });

};
*/


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

 <div class="card pt-6 " v-if="wordsArray.length>0" >
        <DataTable stripedRows paginator :rows="5" :rowsPerPageOptions="[5, 10, 20, 50]"
        selectionMode="single" dataKey="id" :metaKeySelection="false"
        @rowSelect="onRowSelect" :value="wordsArray" tableStyle=" ">

           <Column field="name" header="Name">

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

