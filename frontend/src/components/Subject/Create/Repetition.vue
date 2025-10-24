<script setup lang="ts">
//import Filters from '@/components/Word/Index/Index-filter-test.vue';

import { ref, onMounted ,computed, watch} from 'vue';
import {useRoute, useRouter} from 'vue-router';
import {
  repetitionIndexURL
} from '@/config/request-urls';
import {
  formatDate
} from '@/utils/formatDate';

import { useHttpRequest } from '@/utils/http-request';
import modalSpiner from '@/components/common/spiner/ModalSpiner.vue';
import useConfirm from '@/composables/modals/Confirmer';
import { storeToRefs } from 'pinia';
import { FilterMatchMode } from '@primevue/core/api';

type Props = {
   isImportLoaded:boolean,
};

const props = defineProps<Props>();
const emit = defineEmits(['setImportLoader' ]);


const router = useRouter();
const route = useRoute();

// types/repetition.ts или в начале файла
interface RepetitionItem {
  id: string;
  subject_id: string;
  created_at: string;
  updated_at: string;
}

interface RepetitionResponse {
  subject: {
    id: string;
    name: string;
  };
  data: RepetitionItem[];
}
const {
  loading: repetitionsLoading,
  data: repetititonData,
  //loading: isItemLoading ,
  sendRequest: repetitionsRequest
} = useHttpRequest<RepetitionResponse>();



const confirm = useConfirm();
const isSpiner = ref<boolean>(false);
const margYspiner = '24';



const wordsArray = computed(() => {
  return repetititonData.value?.data || [];
});

const isPageSpiner = computed (()=>{
  return repetitionsLoading.value;
});


onMounted(async () => {
  await fectchRepetition();
});

const fectchRepetition = async () => {
  await repetitionsRequest({ url: repetitionIndexURL(route.params.subject_id as string) });
};

watch (()=>props.isImportLoaded,
  async (newValue)=>{
    if (newValue === true) {
      await fectchRepetition();
      emit('setImportLoader', false);
    }
  });




const onRowSelect =(event)=>{
  //console.log(event.data.id);
  router.push({
    name:'repetition',
    params:{
      repetition_id:event.data.id
    }
  });
};
</script>
<template>


  <PageSpiner :my="margYspiner"  :isSpiner="isPageSpiner"  />

 <div class="card " v-if="!repetitionsLoading" >
        <DataTable

        :value="wordsArray"

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

          <Column field="name" header="Test date">
                <template #body="{ data }">
                    {{ formatDate(data.created_at)}}
                </template>


          </Column>
          <!--
           <Column field="id" header="Id"  ></Column>
           <Column class="w-16 h-16 !text-end">

            </Column>
        -->
        </DataTable>
        <Toast />
    </div>

  <modalSpiner :isSpiner="isSpiner" ></modalSpiner>

</template>

