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
//import Repetition from '@/components/Subject/Create/Repetition';
import EditForm from '@/components/Subject/Create/EditForm.vue';

//const toast = useToast();


const router = useRouter();
const route = useRoute();

type Props = {
   isEdit:boolean
};
const props = defineProps<Props>();

const itemId =  route?.params?.subject_id as string;
const isSpiner = ref<boolean>(false);


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



const itemName = computed(() => itemData.value?.[0]?.name || '');

const isPageSpiner = computed(()=>{
  if (!props.isEdit) {
    return false;
  }
  return (!itemData.value) ? true : false;
});





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
        <Tab value="0">Repeate</Tab>
        <Tab value="1">Download</Tab>
        <Tab value="2">Options</Tab>
        <Tab value="3">Edit</Tab>
    </TabList>
    <TabPanels>
      <TabPanel value="0">

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
           <TabPanel value="3">
            <EditForm
              :isEdit="props.isEdit"
            >
          </EditForm>
        </TabPanel>
    </TabPanels>
</Tabs>



















 <modalSpiner :isSpiner="isLoading" ></modalSpiner>



</template>
