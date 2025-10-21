<script setup lang="ts">
import { computed, ref, onMounted,watch } from 'vue';
import { useHttpRequest } from '@/utils/http-request';
import {
  updateSubjectOptionsURL

} from '@/config/request-urls';
import {useRouter,useRoute} from 'vue-router';
import modalSpiner from '@/components/common/spiner/ModalSpiner.vue';
import { Form, Field } from 'vee-validate';
import { z } from 'zod';
import { toTypedSchema } from '@vee-validate/zod';
import useConfirm from '@/composables/modals/Confirmer';
import { useToast } from 'primevue/usetoast';

type Props = {

   itemData :any

};
const props = defineProps<Props>();
const emit = defineEmits(['setSpiner','fetchItemSubject' ]);


const total_rows = ref(props.itemData.options.total_rows);
const new_words = ref(props.itemData.options.new_words);
const important_words = ref(props.itemData.options.important_words);
const types = ref([
  { name: 'New words', code: 'NEW' },
  { name: 'Important words', code: 'IMPORTANT' },
  { name: 'Repeated words', code: 'REPEATED' }

]);
const repetition_type = ref(
  types.value.find(type => type.code === props.itemData.options.repetition_type) || types.value[0]
);
watch(() => props.itemData.options.repetition_type, (newValue) => {
  repetition_type.value = types.value.find(type => type.code === newValue) || types.value[0];
});
const repetition_theme =   ref(props.itemData.options.repetition_theme);
const row_length = ref(props.itemData.options.row_length);

// Схема валидации
const schema = toTypedSchema(
  z.object({
    repetition_theme: z.string()
      .min(3, '"name" is required')
      .max(255, '"name" is too long')

  })
);

const initialValues = computed(() => ({
  repetition_theme: props.itemData.options.repetition_theme || ''
}));



const {
  loading: isLoading ,
  sendRequest
} = useHttpRequest({
  showSuccessToast:true,
  showErrorToast: true
});


const sendData = async(data:any) => {

  emit('setSpiner', true);
  const params = {
    total_rows : total_rows.value,
    new_words: new_words.value,
    important_words: important_words.value,
    repetition_theme : data.repetition_theme,
    repetition_type: repetition_type.value.code,
    row_length:row_length.value

  };

  const res = await sendRequest({
    url: updateSubjectOptionsURL(props.itemData.options.id as string),
    method: 'PATCH',
    data: params
  });

  if (res?.isOk) {
    emit('fetchItemSubject');

  }
  emit('setSpiner', false);


};


</script>
<template>

  <div class="flex flex-col gap-2">

  <Form @submit="sendData"
      :validation-schema="schema"
      :initial-values="initialValues"
        >
    <div>
      <div class="text-stone-500 font-bold py-2">Total rows</div>
    <InputNumber v-model="total_rows" showButtons buttonLayout="horizontal" style="width: 3rem" :min="30" :max="140">
    <template #incrementbuttonicon>
        <span class="pi pi-plus" />
    </template>
    <template #decrementbuttonicon>
        <span class="pi pi-minus" />
    </template>
    </InputNumber>
   </div>

   <div>
      <div class="text-stone-500 font-bold py-2">Repetition type</div>
      <div class="card flex justify-start">
            <Select v-model="repetition_type" :options="types" optionLabel="name" placeholder="Select type" class="w-full md:w-56" />
      </div>
    </div>

  <div>
      <div class="text-stone-500 font-bold py-2">New words</div>
    <InputNumber v-model="new_words" showButtons buttonLayout="horizontal" style="width: 3rem" :min="5" :max="20">
    <template #incrementbuttonicon>
        <span class="pi pi-plus" />
    </template>
    <template #decrementbuttonicon>
        <span class="pi pi-minus" />
    </template>
    </InputNumber>
   </div>

     <div>
      <div class="text-stone-500 font-bold py-2">Important words</div>
    <InputNumber v-model="important_words" showButtons buttonLayout="horizontal" style="width: 3rem" :min="5" :max="20">
    <template #incrementbuttonicon>
        <span class="pi pi-plus" />
    </template>
    <template #decrementbuttonicon>
        <span class="pi pi-minus" />
    </template>
    </InputNumber>
   </div>

   <div>
      <div class="text-stone-500 font-bold py-2">Theme of task</div>
        <Field name="repetition_theme" v-slot="{ field, errors }">
          <Textarea

            v-bind="field"
            type="text"
            :class="{ 'p-invalid': errors.length }" rows="3" cols="50" />
          <Message v-if="errors.length" severity="error" size="small" variant="simple">
            {{ errors[0] }}
          </Message>
        </Field>
   </div>

   <div>
      <div class="text-stone-500 font-bold py-2">Words in a row</div>
    <InputNumber v-model="row_length" showButtons buttonLayout="horizontal" style="width: 3rem" :min="10" :max="25">
    <template #incrementbuttonicon>
        <span class="pi pi-plus" />
    </template>
    <template #decrementbuttonicon>
        <span class="pi pi-minus" />
    </template>
    </InputNumber>
   </div>
   <button type="submit" class="text-white bg-blue-700 cursor-pointer hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 my-4 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
   Update
  </button>

  </Form>



  </div>
</template>
