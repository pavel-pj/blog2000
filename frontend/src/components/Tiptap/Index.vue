<script setup lang="ts">
import { ref } from 'vue';
import Editor from './Editor.vue';

const content = ref('<p>A Vue.js wrapper component for Tiptap to use <code>v-model</code>.</p>');
/*
import Editor from './Editor.vue';

export default {
  components: {
    Editor
  },

  data() {
    return {
      content: '<p>A Vue.js wrapper component for Tiptap to use <code>v-model</code>.</p>'
    };
  }
};
*/

</script>
<template>
  <div>
    <editor v-model="content" />

    <div class="output-group">
      <label>Content</label>
      <code>{{ content }}</code>
    </div>
  </div>
</template>
