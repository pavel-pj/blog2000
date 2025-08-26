

<script setup  >
/*
import { useEditor, EditorContent } from '@tiptap/vue-3';
import StarterKit from '@tiptap/starter-kit';

const editor = useEditor({
  content: '<p>I\'m running Tiptap with Vue.js. 🎉</p>',
  extensions: [StarterKit]
});
*/

import { Editor } from '@tiptap/core';
import StarterKit from '@tiptap/starter-kit';

new Editor({
  element: document.querySelector('.element'),
  extensions: [StarterKit]
});
</script>


<template>
  <editor-content :editor="editor" />
</template>
