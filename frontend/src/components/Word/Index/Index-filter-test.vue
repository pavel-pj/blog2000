<script setup lang="ts">


import { ref, onMounted } from 'vue';
import { CustomerService } from '@/service/CustomerService';
import { FilterMatchMode, FilterOperator } from '@primevue/core/api';


interface Customer {
    id: number;
    name: string;
    country: {
        name: string;
        code: string;
    };
    company: string;
    date: string;
    status: string;
    verified: boolean;
    activity: number;
    representative: {
        name: string;
        image: string;
    };
    balance: number;
}
// Define the valid severity types based on PrimeVue documentation
type Severity = 'success' | 'info' | 'warn' | 'danger' | 'secondary' | 'contrast' | undefined;

const customers = ref();
const selectedCustomer = ref();
const filters = ref(
  {
    global: { value: null, matchMode: FilterMatchMode.CONTAINS },
    name: { operator: FilterOperator.AND, constraints: [{ value: null, matchMode: FilterMatchMode.STARTS_WITH }] },
    'country.name': { operator: FilterOperator.AND, constraints: [{ value: null, matchMode: FilterMatchMode.STARTS_WITH }] },
    representative: { value: null, matchMode: FilterMatchMode.IN },
    status: { operator: FilterOperator.OR, constraints: [{ value: null, matchMode: FilterMatchMode.EQUALS }] }
  }
);
const representatives = ref([
  { name: 'Amy Elsner', image: 'amyelsner.png' },
  { name: 'Anna Fali', image: 'annafali.png' },
  { name: 'Asiya Javayant', image: 'asiyajavayant.png' },
  { name: 'Bernardo Dominic', image: 'bernardodominic.png' },
  { name: 'Elwin Sharvill', image: 'elwinsharvill.png' },
  { name: 'Ioni Bowcher', image: 'ionibowcher.png' },
  { name: 'Ivan Magalhaes', image: 'ivanmagalhaes.png' },
  { name: 'Onyama Limba', image: 'onyamalimba.png' },
  { name: 'Stephen Shaw', image: 'stephenshaw.png' },
  { name: 'XuXue Feng', image: 'xuxuefeng.png' }
]);
const statuses = ref(['unqualified', 'qualified', 'new', 'negotiation', 'renewal', 'proposal']);

onMounted(() => {
  CustomerService.getCustomersSmall().then((data) => (customers.value = data));
});


const getSeverity = (status: string): Severity => {
  switch (status) {
  case 'unqualified':
    return 'danger';
  case 'qualified':
    return 'success';
  case 'new':
    return 'info';
  case 'negotiation':
    return 'warn';
  case 'renewal':
    return 'secondary';
  case 'proposal':
    return 'contrast';
  default:
    return undefined;
  }
};



</script>
<template>

    <div class="card">
        <DataTable
            v-model:filters="filters"
            v-model:selection="selectedCustomer"
            :value="customers"
            responsiveLayout="stack"
            breakpoint="960px"
            paginator
            :rows="5"
            selectionMode="single"
            dataKey="id"
            tableStyle="min-width: 50rem"
        >
            <Column field="name" header="Name">
                <template #body="{ data }">
                    <div class="font-bold text-lg">{{ data.name }}</div>
                    <div class="text-sm text-gray-600">{{ data.company }}</div>
                </template>
            </Column>

            <Column field="country.name" header="Country">
                <template #body="{ data }">
                    <div class="flex items-center gap-2">
                        <img alt="flag" src="https://primefaces.org/cdn/primevue/images/flag/flag_placeholder.png"
                             :class="`flag flag-${data.country.code}`" style="width: 20px" />
                        <span class="hidden lg:inline">{{ data.country.name }}</span>
                    </div>
                </template>
            </Column>

            <Column field="status" header="Status">
                <template #body="{ data }">
                    <Tag :value="data.status" :severity="getSeverity(data.status)" />
                </template>
            </Column>

            <Column header="Details" class="hidden lg:table-cell">
                <template #body="{ data }">
                    <div class="p-4 bg-gray-50 rounded">
                        <div><strong>Representative:</strong> {{ data.representative.name }}</div>
                        <div><strong>Activity:</strong> {{ data.activity }}</div>
                        <div><strong>Balance:</strong> ${{ data.balance }}</div>
                    </div>
                </template>
            </Column>
        </DataTable>
    </div>

</template>
