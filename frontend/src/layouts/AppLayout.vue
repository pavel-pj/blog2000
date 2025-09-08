<script setup lang="ts">
import LeftSideBar from '@/components/layouts/appLayout/LeftSideBar.vue';
import TopPanel from '@/components/layouts/appLayout/TopPanel.vue';
</script>
<template>
<body class="h-screen bg-slate-100">
    <div class="border-b-1 border-gray-300">
      <TopPanel />
    </div>
    <div class="grid grid-cols-9 h-full gap-6 p-6">
      <div class="col-span-2 bg-white rounded-xl">
        <div class="text-left font-bold p-4 ">
           <LeftSideBar />
        </div>
      </div>
      <div class="col-span-7 ">
        <div class="bg-white rounded-xl h-full shadow-md border-bottom-1">
          <main >
          <section class="py-4 px-8">
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
