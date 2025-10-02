<script setup lang="ts">

import { ref, onMounted ,computed} from 'vue';
import {useRoute, useRouter} from 'vue-router';
import {
  topicsURL,
  deleteTopicURL
} from '@/config/request-urls';
import { useHttpRequest } from '@/utils/http-request';
import modalSpiner from '@/components/common/spiner/ModalSpiner.vue';
import PageSpiner from '@/components/common/spiner/PageSpiner.vue';
import useConfirm from '@/composables/modals/Confirmer';
import BreadCrumbs from '@/components/common/navigate/BreadCrumbs.vue';

const router = useRouter();
const route = useRoute();


interface TopicItem {
  id: string;
  name: string;
  subject_id: string;
  created_at: string;
  updated_at: string;

}

interface SubjectData {
  subject: {
    id: string;
    name: string;
  };
  data:TopicItem[];
}

const confirm = useConfirm();
const isSpiner = ref<boolean>(false);
const margYspiner = '24';

const {
  data: topicsData,
  sendRequest: sendData
} = useHttpRequest<SubjectData>({
  showSuccessToast:false,
  showErrorToast: false
});

const tableData = ref<TopicItem[]>([]);

const topicsArray = computed(() => {
  return topicsData.value?.data || [];
});

const isPageSpiner = computed (()=>{
  return !topicsData.value;
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
    url: deleteTopicURL(dataToDelete.value?.id),
    method: 'DELETE'

  });

  if (res?.isOk) {
    await getTopics();
    isSpiner.value = false;
  } else {
    isSpiner.value = false;
  }

};


onMounted(async () => {
  await getTopics();
});

const getTopics = async()=> {
  const response = await sendData({
    url: topicsURL(route.params.subject_id as string)
  });
};


const create = () => {
  router.push({name: 'topic-create'});
};

const words = () => {
  router.push(
    {name: 'words-index',
      params: {subject_id: route.params.subject_id}
    });
};



const onRowSelect =(event)=>{
  //console.log(event.data.id);
  router.push({ name: 'topic-edit' ,
    params: { topic_id : event.data.id }
  }
  );
};

const itemsBreadCrumbs =computed(()=>{
  if (!topicsData.value?.subject) return [];
  return ([
    { label: topicsData.value?.subject?.name ,
      route: {name:'subject-edit' , params : {subject_id: topicsData.value?.subject?.id}
      }
    },
    { label: 'Topics'   }]);
});

</script>
<template>


<BreadCrumbs :items="itemsBreadCrumbs" />
<div class="flex flex-raw justify-start gap-6">
  <Button @click="create" label="Primary" rounded style="display:block">Create </Button>
  <Button @click="words" label="Primary" rounded severity="secondary" style="display:block">Words </Button>
</div>
 <PageSpiner :my="margYspiner"  :isSpiner="isPageSpiner"  />
 <div class="card pt-6 " v-if="topicsData" >
        <DataTable stripedRows
        selectionMode="single" dataKey="id" :metaKeySelection="false"
        @rowSelect="onRowSelect" :value="topicsArray" tableStyle="min-width: 50rem">
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
