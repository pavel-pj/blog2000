<script setup lang="ts">

import { ref, onMounted ,computed} from 'vue';
import {useRouter} from 'vue-router';
import {
  subjectsURL,
  deleteSubjectURL
} from '@/config/request-urls';
import { useHttpRequest } from '@/utils/http-request';
import modalSpiner from '@/components/common/spiner/ModalSpiner.vue';
import  SubjectCard  from '@/components/common/content/SubjectCard.vue';
import PageSpiner from '@/components/common/spiner/PageSpiner.vue';
import useConfirm from '@/composables/modals/Confirmer';
import BreadCrumbs from '@/components/common/navigate/BreadCrumbs.vue';
import { useAuthStore } from '@/store/auth';

const router = useRouter();

interface SubjectItem {
  id: string,
  name: string,

}

const confirm = useConfirm();
const isSpiner = ref<boolean>(false);
const margYspiner = '24';
//const auth = useAuthStore();


const {
  data: subjects,
  sendRequest: sendData
} = useHttpRequest<SubjectItem[]>( );

const tableData = ref<SubjectItem[]>([]);

const isPageSpiner = computed (()=>{
  const data = subjects.value || null;
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
    url: deleteSubjectURL(dataToDelete.value?.id),
    method: 'DELETE'

  });

  if (res?.isOk) {
    await getSubject();
    isSpiner.value = false;
  } else {
    isSpiner.value = false;
  }

};


onMounted(async () => {
  await getSubject();
});

const getSubject = async()=> {
  const response = await sendData({ url: subjectsURL() });

  if (response?.data) {
    tableData.value = Array.isArray(response.data)
      ? response.data
      : [response.data];
  }
};


const create = () => {
  router.push('create');
};

const onRowSelect =(event)=>{
  console.log(event.data.id);
  router.push(`edit/${event.data.id}`);
};

const itemsBreadCrumbs =computed(()=>{

  return ([
    { label: 'Subject'   }]);
});

</script>
<template>

<BreadCrumbs :items="itemsBreadCrumbs" />
<Button @click="create" label="Primary" rounded style="display:block">Create </Button>
<PageSpiner :my="margYspiner"  :isSpiner="isPageSpiner"  />

<div class="flex flex-row flex-wrap justify-between" v-if="subjects">
<SubjectCard v-for="(subject, index) in subjects"
      :key="index"
      :subject="subject"


      > </SubjectCard>
</div>


<!--
 <div class="card pt-6 " v-if="subject" >
        <DataTable stripedRows
        selectionMode="single" dataKey="id" :metaKeySelection="false"
        @rowSelect="onRowSelect" :value="subject" tableStyle="min-width: 50rem">

           <Column field="name" header="Name"></Column>
           <Column field="id" header="Id"></Column>
           <Column class="w-24 !text-end">
                <template #body="{ data }"  >

                    <Button icon="pi pi-times " @click="openDelete(data)" severity="danger"  ></Button>

                </template>
            </Column>

        </DataTable>
        <Toast />
    </div>-->
  <modalSpiner :isSpiner="isSpiner" ></modalSpiner>


</template>
