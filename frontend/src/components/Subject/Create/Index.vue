<script setup lang="ts">
import { computed, ref,onMounted } from 'vue';
import { useHttpRequest } from '@/utils/http-request';
import {
  subjectItemShowURL
} from '@/config/request-urls';
import {useRouter,useRoute} from 'vue-router';
import modalSpiner from '@/components/common/spiner/ModalSpiner.vue';
import PageSpiner from '@/components/common/spiner/PageSpiner.vue';
import BreadCrumbs from '@/components/common/navigate/BreadCrumbs.vue';
//import { Form, Field } from 'vee-validate';
//import { z } from 'zod';
//import { toTypedSchema } from '@vee-validate/zod';
//import useConfirm from '@/composables/modals/Confirmer';
import { useToast } from 'primevue/usetoast';
import Repetition from '@/components/Subject/Create/Repetition.vue';
import EditForm from '@/components/Subject/Create/EditForm.vue';

const toast = useToast();


const router = useRouter();
const route = useRoute();

type Props = {
   isEdit:boolean
};
const props = defineProps<Props>();


const itemId =  route?.params?.subject_id as string;
const isSpiner = ref<boolean>(false);

const setSpiner =(value : boolean) => {
  isSpiner.value = value;
};


const {
  loading: isLoading,
  data : itemData,
  //loading: isItemLoading ,
  sendRequest: getDataRequest
} = useHttpRequest<{
  id:string,
  name:string
}>();

onMounted(async () => {

  if (props.isEdit) {

    await fetchItemSubject();
  }
});


const fetchItemSubject = async () => {

  if (itemId) {
    await getDataRequest({ url: subjectItemShowURL(itemId) });
  }
};



const isPageSpiner = computed(()=>{
  if (!props.isEdit) {
    return false;
  }
  return (!itemData.value) ? true : false;
});



const itemName = computed(() => itemData.value?.name || '');


const itemsBreadCrumbs =computed(()=>{

  return ([
    { label: 'Subjects' ,route: { name: 'subjects-index' }  },
    { label: itemName.value }
  ]) ;
});




</script>

<template>


<BreadCrumbs :items="itemsBreadCrumbs" />
<PageSpiner :isSpiner="isPageSpiner" />


<EditForm v-if="!isPageSpiner && !isEdit"
    :isEdit = "isEdit"
    :itemData ="itemData"
    @setSpiner="setSpiner"
    @fetchItemSubject="fetchItemSubject"
></EditForm>


<Tabs value="0" v-if="!isPageSpiner && isEdit">
    <TabList>
        <Tab value="0">Repeate</Tab>
        <Tab value="1">Download</Tab>
        <Tab value="2">Options</Tab>
        <Tab value="3">Edit</Tab>
    </TabList>
    <TabPanels>
      <TabPanel value="0">
        <Repetition>

        </Repetition>

      </TabPanel>

        <TabPanel value="1">
            <Download
               @setSpiner="setSpiner"
            >
            </Download>
        </TabPanel>
        <TabPanel value="2">
            <p class="m-0">
                At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium voluptatum deleniti atque corrupti quos dolores et quas molestias excepturi sint occaecati cupiditate non provident, similique sunt in culpa
                qui officia deserunt mollitia animi, id est laborum et dolorum fuga. Et harum quidem rerum facilis est et expedita distinctio. Nam libero tempore, cum soluta nobis est eligendi optio cumque nihil impedit quo minus.
            </p>
        </TabPanel>
           <TabPanel value="3">
            <EditForm
            :isEdit = "isEdit"
            :itemData ="itemData"
            @setSpiner="setSpiner"
            @fetchItemSubject="fetchItemSubject"
            ></EditForm>
        </TabPanel>
    </TabPanels>
</Tabs>

 <modalSpiner :isSpiner="isSpiner" ></modalSpiner>

</template>
