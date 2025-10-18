<script setup lang="ts">
import { computed, ref,onMounted } from 'vue';
import { useHttpRequest } from '@/utils/http-request';
import {
  subjectCreateURL,
  subjectItemShowURL,
  updateSubjectURL,
  deleteSubjectURL,
  ExportWordsToRepeateURL,
  ImportWordsToRepeateURL
} from '@/config/request-urls';
import {useRouter,useRoute} from 'vue-router';
import modalSpiner from '@/components/common/spiner/ModalSpiner.vue';
import PageSpiner from '@/components/common/spiner/PageSpiner.vue';
import BreadCrumbs from '@/components/common/navigate/BreadCrumbs.vue';
import { Form, Field } from 'vee-validate';
import { z } from 'zod';
import { toTypedSchema } from '@vee-validate/zod';
import useConfirm from '@/composables/modals/Confirmer';
import { useToast } from 'primevue/usetoast';


const toast = useToast();


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

const {
  loading: isLoading ,

  sendRequest
} = useHttpRequest({
  showSuccessToast:true,
  showErrorToast: true
});

const fetchItemSubject = async () => {

  if (itemId) {
    await getDataRequest({ url: subjectItemShowURL(itemId) });
  }
};


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

const selectedFile = ref(null);
const fileupload = ref();

const upload = () => {
  fileupload.value.upload();
};
const onFileSelect = (event) => {
  selectedFile.value = event.files[0];
};


const importWords =async ()=>{

  if (!selectedFile.value) {
    toast.add({
      severity: 'warn',
      summary: 'No File',
      detail: 'Please select an Excel file first',
      life: 3000
    });
    return;
  }

  try {
    const formData = new FormData();
    formData.append('excel_file', selectedFile.value);

    const response = await sendRequest({
      url: ImportWordsToRepeateURL(route.params.subject_id as string),
      method: 'POST',
      data: formData
      // Remove Content-Type header - let browser set it automatically
    });

    if (response && response.isOk) {
      toast.add({
        severity: 'success',
        summary: 'Success',
        detail: 'File imported successfully',
        life: 3000
      });

      // Clear the selected file
      selectedFile.value = null;
      fileupload.value.clear();
    }
  } catch (error) {
    console.error('Import failed:', error);
  }

};

const exporttWords =async ()=>{

  const { sendRequest } = useHttpRequest({
    showSuccessToast: true,
    showErrorToast: true
  });

  try {
    // Make the request with responseType: 'blob'
    const response = await sendRequest<Blob>({
      url: ExportWordsToRepeateURL(route.params.subject_id as string),
      responseType: 'blob' // This tells axios to return Blob data
    });

    // Check if response exists and has data
    if (response && response.data) {

      // TypeScript now knows response.data is a Blob
      const blob = new Blob([response.data], {
        type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
      });

      // Create a temporary URL for the blob
      const url = window.URL.createObjectURL(blob);

      // Create a hidden link element
      const link = document.createElement('a');
      link.href = url;

      // Set the filename for download
      link.download = `words_export_${new Date().toISOString().split('T')[0]}.xlsx`;

      // Append to body, click, and remove
      document.body.appendChild(link);
      link.click();
      document.body.removeChild(link);

      // Clean up the URL
      window.URL.revokeObjectURL(url);

      console.log('File downloaded successfully!');
    }
  } catch (error) {
    console.error('Export failed:', error);
  }



};


</script>

<template>


<BreadCrumbs :items="itemsBreadCrumbs" />
<PageSpiner :isSpiner="isPageSpiner" />

<Tabs value="0" v-if="!isPageSpiner">
    <TabList>
        <Tab value="0">Edit</Tab>
        <Tab value="1">Repeate</Tab>
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

              <Button @click="exporttWords" label="Primary" rounded style="display:block">Export</Button>

              <Toast />
              <div class="card flex flex-wrap gap-6 items-center justify-start my-6">
                  <FileUpload
                  ref="fileupload"
                  mode="basic"
                  name="demo[]"
                  url="/api/upload"
                  accept=".xlsx,.xls,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.ms-excel"
                  :maxFileSize="1000000"
                   @select="onFileSelect"
                  :auto="false"
                   chooseLabel="Select Excel File"
                   />
                  <Button label="Upload" @click="importWords" severity="secondary" />
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
