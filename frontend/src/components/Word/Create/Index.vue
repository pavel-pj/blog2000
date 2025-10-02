<script setup lang="ts">
import { computed, ref,onMounted  } from 'vue';
import { useHttpRequest } from '@/utils/http-request';
import {
  wordCreateURL,
  wordItemShowURL,
  updateWordURL
} from '@/config/request-urls';
import {useRouter,useRoute} from 'vue-router';
import modalSpiner from '@/components/common/spiner/ModalSpiner.vue';
import PageSpiner from '@/components/common/spiner/PageSpiner.vue';
//import BreadCrumbs from '@/components/common/navigate/BreadCrumbs.vue';
import { Form, Field } from 'vee-validate';
import { z } from 'zod';
import { toTypedSchema } from '@vee-validate/zod';
///import Select from 'primevue/select';
//import { useDictionariesStore } from '@/store/dictionaries';
//import Editor from '@/components/Tiptap/Editor.vue';

const router = useRouter();
const route = useRoute();

type Props = {
   isEdit:boolean
};

//const {
//  dictionaries,
//  getDictionaryByType,
//  fetchDictionary
//} = useDictionariesStore();



// Или альтернативный вариант:
//const wordsOptions = computed(() => getDictionaryByType('topics'));

const props = defineProps<Props>();

const itemId =  route?.params?.word_id as string;
const isSpiner = ref<boolean>(false);

const {
  data : itemData,
  //loading: isItemLoading ,
  sendRequest: getDataRequest
} = useHttpRequest<{
  id:string,
  name:string,
  translation:string

}>();



onMounted(async () => {
  //if (!dictionaries.value) {
  //  await fetchDictionary('topics');
  //}
  console.log('onMounted');
  if (props.isEdit) {
    console.log('props.isEdit');
    await fetchItemWord();
  }

});

const fetchItemWord = async () => {
  if (itemId) {
    await getDataRequest({ url: wordItemShowURL(itemId) });
  }
};

//const html_content = ref<string>('');

/*
watch (itemData ,
  (newValue)=> {
    if (newValue){
      html_content.value = props.isEdit ? newValue[0]?.html_content || '' : '';
    }
  }
);
*/


const {
  loading: isLoading ,
  // error ,
  sendRequest
} = useHttpRequest({
  showErrorToast:true,
  showSuccessToast:true
});




const sendData = async(data:any) => {

  isSpiner.value = true;
  const params = data;
  if (!props.isEdit) {
    params.subject_id = route.params.subject_id;
  }

  let res = ref<any>();

  if (!props.isEdit) {

    res.value = await sendRequest({
      url: wordCreateURL(),
      method: 'POST',
      data: params
    });


    if (res.value?.isOk) {
      await router.push({
        name: 'words-index',
        params: {catalog_id: route.params.catalog_id}
      });

    } else {
      isSpiner.value = false;
    }
  } else {


    res.value = await sendRequest({
      url: updateWordURL(itemId),
      method: 'PATCH',
      data: params
    });

    if (res.value?.isOk) {
      await fetchItemWord();
      isSpiner.value = false;
    } else {
      isSpiner.value = false;
    }

  }
};




//const itemName = computed(() => itemData.value?.[0]?.name || '');

const isPageSpiner = computed(()=>{
  if (!props.isEdit) {
    return false;
  }


  return (!itemData.value
  //&& !dictionaries.value
  ) ? true : false;
});




/*
const itemsBreadCrumbs =computed(()=>{

  let subject_id = '';
  if (props.isEdit ){
    if (!itemData.value){
      return '';
    }
  }

  return ([
    { label: 'Words' ,route: {
      name: 'words-index',
      params: {subject_id: route.params.subject_id}
    }
    },
    { label: itemName.value }
  ]) ;
});*/

// Схема валидации
const schema = toTypedSchema(
  z.object({
    name: z.string()
      .min(2, '"name" is required')
      .max(255, '"name" is too long'),

    translation: z.string()
      .min(2, '"translation" is required')
      .max(255, '"translation" is too long')

  })

);



const initialValues = computed(() => {
  const name = props.isEdit ? itemData.value?.[0]?.name || '' : '';
  const translation = props.isEdit ? itemData.value?.[0]?.translation || '' : '';



  return {
    name,
    translation
  };
});


/*
 <!--<h1 class="text-1xl  font-bold"> {{pageOptions.title}}</h1>-->
  <h1 class="text-2xl  font-bold my-4">{{itemsBreadCrumbs[1].label}}</h1>-->
*/

</script>

<template>

<!--
<BreadCrumbs :items="itemsBreadCrumbs" />-->
<PageSpiner :isSpiner="isPageSpiner" />
  <div  v-if="!isPageSpiner">



      <div class="card">
                <div class="w-[700px] my-6"  >
                  <Form @submit="sendData"
                    :validation-schema="schema"
                    :initial-values="initialValues"
                      class="flex flex-col gap-4 w-full ">
                      <div class="flex gap-2 flex-col">
                        <label for="name" class="font-medium">Name <span class="px-2 font-bold text-red-700"> * </span></label>
                        <Field name="name" v-slot="{ field, errors }" >
                          <InputText
                            v-bind="field"
                            placeholder="Name"
                            :class="{ 'p-invalid': errors.length }"
                          />
                          <Message v-if="errors.length" severity="error" size="small" variant="simple">
                            {{ errors[0] }}
                          </Message>
                        </Field>
                      </div>
                    <div class="flex gap-2 flex-col">
                        <label for="Translation" class="font-medium">Translation <span class="px-2 font-bold text-red-700"> * </span></label>
                        <Field name="translation" v-slot="{ field, errors }">
                          <InputText
                            v-bind="field"
                            placeholder="Translation"
                            :class="{ 'p-invalid': errors.length }"
                          />
                          <Message v-if="errors.length" severity="error" size="small" variant="simple">
                            {{ errors[0] }}
                          </Message>
                        </Field>
                      </div>



                      <Button type="submit"  label="Submit" class="custom-button"/>
                    </Form>
                  </div>

    </div>

</div>


 <modalSpiner :isSpiner="isLoading" ></modalSpiner>
</template>
<style scoped>
.custom-button{
  width:250px;
}
</style>
