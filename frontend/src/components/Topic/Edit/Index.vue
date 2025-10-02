<script setup lang="ts">
import { computed, ref,onMounted  } from 'vue';
import { useHttpRequest } from '@/utils/http-request';
import {
  topicCreateURL,
  topicItemShowURL,
  updateTopicURL
} from '@/config/request-urls';
import {useRouter,useRoute} from 'vue-router';
import modalSpiner from '@/components/common/spiner/ModalSpiner.vue';
import PageSpiner from '@/components/common/spiner/PageSpiner.vue';
import { Form, Field } from 'vee-validate';
import { z } from 'zod';
import { toTypedSchema } from '@vee-validate/zod';


const router = useRouter();
const route = useRoute();

type Props = {
   isEdit:boolean
};


// Или альтернативный вариант:
//const wordsOptions = computed(() => getDictionaryByType('topics'));

const props = defineProps<Props>();

const itemId =  route?.params?.topic_id as string;
const isSpiner = ref<boolean>(false);

const {
  data : itemData,
  //loading: isItemLoading ,
  sendRequest: getDataRequest
} = useHttpRequest<{
  id:string,
  name:string,
  subject_id:string,

}>();



onMounted(async () => {


  if (props.isEdit) {

    await fetchItem();
  }

});

const fetchItem= async () => {
  if (itemId) {
    await getDataRequest({ url: topicItemShowURL(itemId) });
  }
};



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
      url: topicCreateURL(),
      method: 'POST',
      data: params
    });


    if (res.value?.isOk) {
      await router.push({
        name: 'topics-index',
        params: {subject_id: route.params.subject_id}
      });

    } else {
      isSpiner.value = false;
    }
  } else {


    res.value = await sendRequest({
      url: updateTopicURL(itemId),
      method: 'PATCH',
      data: params
    });

    if (res.value?.isOk) {
      await fetchItem();
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



// Схема валидации
const schema = toTypedSchema(
  z.object({
    name: z.string()
      .min(2, '"name" is required')
      .max(255, '"name" is too long')


  })

);


const initialValues = computed(() => {
  const name = props.isEdit ? itemData.value?.name || '' : '';

  return {
    name
  };
});

const itemsBreadCrumbs =computed(()=>{
  if (!itemId) {
    return ([
      { label: 'Topics' ,
        route: {name:'topics-index' , params : {subject_id: route.params.subject_id}
        }
      },
      { label: 'Topic'   }
    ]);
  } else {
    // Редактирование - проверяем что данные загружены
    const subjectId = itemData.value?.subject_id;

    // Если subjectId ещё не загружен, возвращаем breadcrumbs без route
    if (!subjectId) {
      return [];
    }

    return [
      {
        label: 'Topics',
        route: {
          name: 'topics-index',
          params: { subject_id: subjectId }
        }
      },
      { label: itemData.value?.name }
    ];
  }
});





</script>

<template>


<BreadCrumbs :items="itemsBreadCrumbs" />
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
