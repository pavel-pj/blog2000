<template>
    <html class="h-full bg-white">

    <body class="flex items-center justify-content-ceenter min-h-screen h-full">
    <main class="mx-auto  w-[400px] px-6 py-6"><slot></slot>

      <router-view></router-view>
    </main>

  </body>
  </html>
</template>
