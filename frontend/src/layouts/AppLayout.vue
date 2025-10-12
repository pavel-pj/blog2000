<script setup lang="ts">
import LeftSideBar from '@/components/layouts/appLayout/LeftSideBar.vue';
import TopPanel from '@/components/layouts/appLayout/TopPanel.vue';
</script>
<template>
<body class="h-screen 0bg-slate-10">
    <div class="border-b-1 border-gray-300">
      <TopPanel />
    </div>
    <div class="blokc     lg:grid lg:grid-cols-9 h-full  ">
      <div class="col-span-2 bg-white  ">
        <div class="hidden lg:block text-left font-bold p-4  ">
           <LeftSideBar />
        </div>
      </div>
      <div class="lg:col-span-7 ">
        <div class="bg-white h-full      lg:border-l-1 lg:border-gray-300">
          <main >
          <section class="py-4 px-8 h-screen">
              <router-view></router-view>
          </section>
          </main>
        </div>
      </div>
    </div>
    <Toast />
   <ConfirmDialog></ConfirmDialog>
</body>
</template>
