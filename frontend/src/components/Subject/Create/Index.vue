<script setup lang="ts">
import { computed, ref,onMounted } from 'vue';
import { useHttpRequest } from '@/utils/http-request';
import {
  subjectCreateURL,
  subjectItemShowURL,
  updateSubjectURL,
  deleteSubjectURL
} from '@/config/request-urls';
import {useRouter,useRoute} from 'vue-router';
import modalSpiner from '@/components/common/spiner/ModalSpiner.vue';
import PageSpiner from '@/components/common/spiner/PageSpiner.vue';
import BreadCrumbs from '@/components/common/navigate/BreadCrumbs.vue';
import { Form, Field } from 'vee-validate';
import { z } from 'zod';
import { toTypedSchema } from '@vee-validate/zod';
import useConfirm from '@/composables/modals/Confirmer';

const router = useRouter();
const route = useRoute();

type Props = {
   isEdit:boolean
};
const props = defineProps<Props>();

const itemId =  route?.params?.subject_id as string;
const isSpiner = ref<boolean>(false);
const confirm = useConfirm();

const {
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


const {
  loading: isLoading ,
  // error ,
  sendRequest
} = useHttpRequest({
  showSuccessToast:true,
  showErrorToast: true
});

const sendData = async(data:any) => {

  isSpiner.value = true;
  const params = data;

  let res = ref<any>();

  if (!props.isEdit) {

    res.value = await sendRequest({
      url: subjectCreateURL(),
      method: 'POST',
      data: params
    });


    if (res.value?.isOk) {
      await router.push({name:'subjects-index'});

    } else {
      isSpiner.value = false;
    }
  } else {

    res.value = await sendRequest({
      url: updateSubjectURL(itemId),
      method: 'PATCH',
      data: params
    });

    if (res.value?.isOk) {
      await fetchItemSubject();
      isSpiner.value = false;
    } else {
      isSpiner.value = false;
    }

  }
};

const itemName = computed(() => itemData.value?.[0]?.name || '');

const isPageSpiner = computed(()=>{
  if (!props.isEdit) {
    return false;
  }
  return (!itemData.value) ? true : false;
});

const pageOptions = computed (()=>  {

  const title =ref<string>('Create new Catalog');
  if (props.isEdit) {
    title.value = `Edit item ${itemName.value}` ;
  }

  const buttonTitle = (props.isEdit) ? 'Update' : 'Create';

  return{
    title,
    buttonTitle
  };

});



const dataToDelete = ref<any>('');

const openDelete =( )=>{
  dataToDelete.value = route.params.subject_id;

  confirm({
    message: 'Do you want to delete this record?',
    accept: deleteItem,
    successMessage: 'Record successefully deleted'

  });
};

const {
  sendRequest: sendDelete
} = useHttpRequest( );


const deleteItem = async () =>{



  const res = await sendDelete({
    url: deleteSubjectURL(dataToDelete.value),
    method: 'DELETE'

  });

  if (res?.isOk) {
    //await getCatalog();
    router.push({name:'subjects-index'});

    isSpiner.value = false;
  } else {
    isSpiner.value = false;
  }

};


const itemsBreadCrumbs =computed(()=>{

  return ([
    { label: 'Subjects' ,route: { name: 'subjects-index' }  },
    { label: itemName.value }
  ]) ;
});


// Схема валидации
const schema = toTypedSchema(
  z.object({
    name: z.string()
      .min(1, '"name" is required')
      .max(32, '"name" is too long')

  })
);

const initialValues = computed(() => ({
  name: props.isEdit ? itemData.value?.[0]?.name || '' : ''
}));

const importWords =()=>{

};

</script>

<template>


<BreadCrumbs :items="itemsBreadCrumbs" />
<PageSpiner :isSpiner="isPageSpiner" />



<Tabs value="0" v-if="!isPageSpiner">
    <TabList>
        <Tab value="0">Edit</Tab>
        <Tab value="1">Downloads</Tab>
        <Tab value="2">Options</Tab>
    </TabList>
    <TabPanels>
        <TabPanel value="0">
                <div class="w-[350px] py-6"  >
                  <Form @submit="sendData"
                  :validation-schema="schema"
                  :initial-values="initialValues"
                  class="flex flex-col gap-4 w-full ">
                    <div class="flex flex-col gap-1">
                      <Field name="name" v-slot="{ field, errors }">
                        <InputText
                          v-bind="field"
                          placeholder="name"
                          :class="{ 'p-invalid': errors.length }"
                        />
                        <Message v-if="errors.length" severity="error" size="small" variant="simple">
                          {{ errors[0] }}
                        </Message>
                      </Field>
                    </div>
                    <Button type="submit"  label="Submit" />
                  </Form>
              </div>
                <div v-if="isEdit"
                @click="openDelete"
                  class="text-rose-500 my-6 underline font-bold text-xl cursor-pointer inline-block
                  hover:text-rose-700 hover:no-underline"
                >
                  delete
                </div>
        </TabPanel>
        <TabPanel value="1">
            <div class="flex flex-raw justify-start gap-6">
              <Button @click="importWords" label="Primary" rounded style="display:block">Import</Button>

            </div>
        </TabPanel>
        <TabPanel value="2">
            <p class="m-0">
                At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium voluptatum deleniti atque corrupti quos dolores et quas molestias excepturi sint occaecati cupiditate non provident, similique sunt in culpa
                qui officia deserunt mollitia animi, id est laborum et dolorum fuga. Et harum quidem rerum facilis est et expedita distinctio. Nam libero tempore, cum soluta nobis est eligendi optio cumque nihil impedit quo minus.
            </p>
        </TabPanel>
    </TabPanels>
</Tabs>



















 <modalSpiner :isSpiner="isLoading" ></modalSpiner>



</template>
