<script setup lang="ts">

import { ref, onMounted ,computed} from 'vue';
import {useRoute, useRouter} from 'vue-router';
import {
  wordsURL,
  deleteWordURL
} from '@/config/request-urls';
import { useHttpRequest } from '@/utils/http-request';
import modalSpiner from '@/components/common/spiner/ModalSpiner.vue';
import PageSpiner from '@/components/common/spiner/PageSpiner.vue';
import useConfirm from '@/composables/modals/Confirmer';
import BreadCrumbs from '@/components/common/navigate/BreadCrumbs.vue';

const router = useRouter();
const route = useRoute();


interface WordItem {
  id: string,
  name: string,

}

const confirm = useConfirm();
const isSpiner = ref<boolean>(false);
const margYspiner = '24';

const {
  data: words,
  sendRequest: sendData
} = useHttpRequest<WordItem[]>({
  showSuccessToast:false,
  showErrorToast: false
});

const tableData = ref<WordItem[]>([]);

const isPageSpiner = computed (()=>{
  const data = words.value || null;
  return (!data) ? true : false;
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
    await getWords();
    isSpiner.value = false;
  } else {
    isSpiner.value = false;
  }

};


onMounted(async () => {
  await getWords();
});

const getWords = async()=> {
  const response = await sendData({ url: wordsURL(route.params.subject_id as string) });

  if (response?.data) {
    tableData.value = Array.isArray(response.data)
      ? response.data
      : [response.data];
  }
};


const create = () => {
  router.push({name: 'word-create'});
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
    { label: 'Words'   }]);
});

</script>
<template>


<BreadCrumbs :items="itemsBreadCrumbs" />
<Button @click="create" label="Primary" rounded style="display:block">Create </Button>

  <PageSpiner :my="margYspiner"  :isSpiner="isPageSpiner"  />
 <div class="card pt-6 " v-if="words" >
        <DataTable stripedRows
        selectionMode="single" dataKey="id" :metaKeySelection="false"
        @rowSelect="onRowSelect" :value="words" tableStyle="min-width: 50rem">

           <Column field="name" header="Name"></Column>
           <Column field="id" header="Id"></Column>
           <Column class="w-24 !text-end">
                <template #body="{ data }"  >

                    <Button icon="pi pi-times " @click="openDelete(data)" severity="danger"  ></Button>

                </template>
            </Column>

        </DataTable>
        <Toast />
    </div>
  <modalSpiner :isSpiner="isSpiner" ></modalSpiner>
</template>
