<script setup lang="ts">

import { computed, ref, onMounted } from 'vue';
import { useHttpRequest } from '@/utils/http-request';
import {
  subjectCreateURL,
  subjectItemShowURL,
  updateSubjectURL,
  deleteSubjectURL
} from '@/config/request-urls';
import {useRouter,useRoute} from 'vue-router';
import modalSpiner from '@/components/common/spiner/ModalSpiner.vue';
import { Form, Field } from 'vee-validate';
import { z } from 'zod';
import { toTypedSchema } from '@vee-validate/zod';
import useConfirm from '@/composables/modals/Confirmer';
import { useToast } from 'primevue/usetoast';

type Props = {
   isEdit:boolean,
   itemData :any

};
const props = defineProps<Props>();

const confirm = useConfirm();
const toast = useToast();
const router = useRouter();
const route = useRoute();

const itemId =  route?.params?.subject_id as string;

const emit = defineEmits(['setSpiner','fetchItemSubject' ]);

const {
  loading: isLoading ,

  sendRequest
} = useHttpRequest({
  showSuccessToast:true,
  showErrorToast: true
});


const sendData = async(data:any) => {

  //isSpiner.value = true;
  emit('setSpiner', true);
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

      emit('setSpiner', false);
    }
  } else {

    res.value = await sendRequest({
      url: updateSubjectURL(itemId as string),
      method: 'PATCH',
      data: params
    });

    if (res.value?.isOk) {
      emit('fetchItemSubject');


      emit('setSpiner', false);
    } else {
      emit('setSpiner', false);
    }

  }
};


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

    emit('setSpiner', false);

  } else {
    emit('setSpiner', false);
  }

};


const buttonTitle = (props.isEdit) ? 'Update' : 'Create';
/*
const pageOptions = computed (()=>  {


  const title =ref<string>('Create new Catalog');
  if (props.isEdit) {
    title.value = `Edit item ${props.itemName as string}` ;
  }

  const buttonTitle = (props.isEdit) ? 'Update' : 'Create';

  return{
    title,
    buttonTitle
  };

});
*/

// Схема валидации
const schema = toTypedSchema(
  z.object({
    name: z.string()
      .min(1, '"name" is required')
      .max(32, '"name" is too long')

  })
);

const initialValues = computed(() => ({
  name: props.isEdit ? props.itemData?.name || '' : ''
}));

</script>
<template>

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
                    <Button type="submit"  >
                             {{buttonTitle}}
                   </Button>
                  </Form>
              </div>
                <div v-if="isEdit"
                @click="openDelete"
                  class="text-rose-500 my-6 underline font-bold text-xl cursor-pointer inline-block
                  hover:text-rose-700 hover:no-underline"
                >
                  delete
                </div>

</template>
