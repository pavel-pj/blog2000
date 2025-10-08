<script setup lang="ts">
import { computed, ref,onMounted ,watch } from 'vue';
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
import { useTopicsStore } from '@/store/topicsStore';
//import Editor from '@/components/Tiptap/Editor.vue';


const router = useRouter();
const route = useRoute();

type Props = {
   isEdit:boolean
};

const {
  topics,
  loadingTopics ,
  loadingErrors ,
  fetchTopics
} = useTopicsStore();


const props = defineProps<Props>();


const itemId =  route?.params?.word_id as string;
const isSpiner = ref<boolean>(false);
const subjectId = ref<string>('');

const {
  data : itemData,
  //loading: isItemLoading ,
  sendRequest: getDataRequest
} = useHttpRequest<{
  id: string,
  name: string,
  translation: string,
  subject_id: string,
  created_at: string,
  updated_at: string
}>();



onMounted(async () => {


  if (props.isEdit) {

    await fetchItemWord();

  }

});

const fetchItemWord = async () => {
  if (props.isEdit) {
    await getDataRequest({ url: wordItemShowURL(itemId) });

  } else {
    subjectId.value = route.params.subject_id as string;
    fetchTopics(subjectId.value);
  }
};

// Исправленный watch для объекта
watch(
  () => itemData.value,
  (newData) => {
    if (newData && newData.subject_id) {
      console.log('CHANGE');
      console.log(newData); // Теперь это объект

      // Получаем subject_id напрямую из объекта
      subjectId.value = newData.subject_id;
      console.log('Subject ID:', subjectId.value);

      fetchTopics(subjectId.value);
    }
  },
  { immediate: true }
);


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
        params: {subject_id: route.params.subject_id}
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
  const name = props.isEdit ? itemData.value?.name || '' : '';
  const translation = props.isEdit ? itemData.value?.translation || '' : '';



  return {
    name,
    translation
  };
});


/*
 <!--<h1 class="text-1xl  font-bold"> {{pageOptions.title}}</h1>-->
  <h1 class="text-2xl  font-bold my-4">{{itemsBreadCrumbs[1].label}}</h1>-->
*/

const selectedCountries = ref();
const countries = ref([
  { name: 'Australia', code: 'AU' },
  { name: 'Brazil', code: 'BR' },
  { name: 'China', code: 'CN' },
  { name: 'Egypt', code: 'EG' },
  { name: 'France', code: 'FR' },
  { name: 'Germany', code: 'DE' },
  { name: 'India', code: 'IN' },
  { name: 'Japan', code: 'JP' },
  { name: 'Spain', code: 'ES' },
  { name: 'United States', code: 'US' }
]);
</script>

<template>
{{ topics  }}
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


                      <MultiSelect v-model="selectedCountries" :options="countries" optionLabel="name" filter placeholder="Select Countries" display="chip" class="w-full md:w-80">
                        <template #option="slotProps">
                            <div class="flex items-center">
                                <img :alt="slotProps.option.name" src="https://primefaces.org/cdn/primevue/images/flag/flag_placeholder.png" :class="`flag flag-${slotProps.option.code.toLowerCase()} mr-2`" style="width: 18px" />
                                <div>{{ slotProps.option.name }}</div>
                            </div>
                        </template>
                        <template #dropdownicon>
                            <i class="pi pi-map" />
                        </template>
                        <template #filtericon>
                            <i class="pi pi-map-marker" />
                        </template>
                        <template #header>
                            <div class="font-medium px-3 py-2">Available Countries</div>
                        </template>
                        <template #footer>
                            <div class="p-3 flex justify-between">
                                <Button label="Add New" severity="secondary" variant="text" size="small" icon="pi pi-plus" />
                                <Button label="Remove All" severity="danger" variant="text" size="small" icon="pi pi-times" />
                            </div>
                        </template>
                    </MultiSelect>





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
