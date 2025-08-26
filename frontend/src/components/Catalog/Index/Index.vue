<script setup lang="ts">

import { ref, onMounted ,computed} from 'vue';
import {useRouter} from 'vue-router';
import {
  catalogURL,
  deleteCatalogURL
} from '@/config/request-urls';
import { useHttpRequest } from '@/utils/http-request';
import modalSpiner from '@/components/common/spiner/ModalSpiner.vue';
import PageSpiner from '@/components/common/spiner/PageSpiner.vue';
import useConfirm from '@/composables/modals/Confirmer';
import BreadCrumbs from '@/components/common/navigate/BreadCrumbs.vue';

const router = useRouter();

interface CatalogItem {
  id: string,
  name: string,

}

const confirm = useConfirm();
const isSpiner = ref<boolean>(false);
const margYspiner = '24';

const {
  data: catalog,
  sendRequest: sendData
} = useHttpRequest<CatalogItem[]>( );

const tableData = ref<CatalogItem[]>([]);

const isPageSpiner = computed (()=>{
  const data = catalog.value || null;
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
    url: deleteCatalogURL(dataToDelete.value?.id),
    method: 'DELETE'

  });

  if (res?.isOk) {
    await getCatalog();
    isSpiner.value = false;
  } else {
    isSpiner.value = false;
  }

};


onMounted(async () => {
  await getCatalog();
});

const getCatalog = async()=> {
  const response = await sendData({ url: catalogURL() });

  if (response?.data) {
    tableData.value = Array.isArray(response.data)
      ? response.data
      : [response.data];
  }
};


const create = () => {
  router.push('catalog-create');
};

const onRowSelect =(event)=>{
  console.log(event.data.id);
  router.push(`catalog/edit/${event.data.id}`);
};

const itemsBreadCrumbs =computed(()=>{

  return ([
    { label: 'Catalog'   }]);
});

</script>
<template>


<BreadCrumbs :items="itemsBreadCrumbs" />
<Button @click="create" label="Primary" rounded style="display:block">Create </Button>


  <PageSpiner :my="margYspiner"  :isSpiner="isPageSpiner"  />


 <div class="card pt-6 " v-if="catalog" >
        <DataTable stripedRows
        selectionMode="single" dataKey="id" :metaKeySelection="false"
        @rowSelect="onRowSelect" :value="catalog" tableStyle="min-width: 50rem">

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
