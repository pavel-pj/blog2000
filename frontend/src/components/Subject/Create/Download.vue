<script setup lang="ts">
import { useHttpRequest } from '@/utils/http-request';
import { computed, ref,onMounted } from 'vue';
import { useToast } from 'primevue/usetoast';
import {useRouter,useRoute} from 'vue-router';
import {

  ExportWordsToRepeateURL,
  ImportWordsToRepeateURL,
  ImportNewWordsURL
} from '@/config/request-urls';

const selectedFile = ref(null);
const fileupload = ref();

const router = useRouter();
const route = useRoute();

const toast = useToast();

const emit = defineEmits(['setSpiner']);

const upload = () => {
  fileupload.value.upload();
};
const onFileSelect = (event) => {
  selectedFile.value = event.files[0];
};

const { sendRequest } = useHttpRequest({
  showSuccessToast: true,
  showErrorToast: true
});

const importRepetition =async ()=>{

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
    emit('setSpiner', true);

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
  emit('setSpiner', false);

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
    emit('setSpiner', true);

    const formData = new FormData();
    formData.append('excel_file', selectedFile.value);

    const response = await sendRequest({
      url: ImportNewWordsURL(route.params.subject_id as string),
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
  emit('setSpiner', false);

};

const exporttWords =async ()=>{

  try {
    emit('setSpiner', true);
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

  emit('setSpiner', false);

};
</script>
<template>

<div class="flex flex-col gap-4">
  <div class="border-1 border-stone-300 rounded-2xl p-6 ">
    <div class="py-2" >Export words for repetition</div>
    <Button @click="exporttWords" label="Primary" rounded style="display:block">Export</Button>

    <Toast />
    <div class="py-2 mt-2">Import task</div>
    <div class="card flex flex-wrap gap-6 items-center justify-start ">
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
        <Button label="Upload" @click="importRepetition" severity="secondary" />
    </div>
  </div>
  <div class="border-1 border-stone-300 rounded-2xl p-6 ">
      <div  class="pb-2">Import words</div>
      <div class="card flex flex-wrap gap-6 items-center justify-start ">
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
  </div>
 </div>
</template>
