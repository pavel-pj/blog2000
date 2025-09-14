
<script setup>

import { ref, watch, onMounted, onBeforeUnmount } from 'vue';
import StarterKit from '@tiptap/starter-kit';
import { Editor, EditorContent } from '@tiptap/vue-3';
import Document from '@tiptap/extension-document';
import Paragraph from '@tiptap/extension-paragraph';
import Blockquote from '@tiptap/extension-blockquote';
import Text from '@tiptap/extension-text';
import Heading from '@tiptap/extension-heading';
import CodeBlockLowlight from '@tiptap/extension-code-block-lowlight';
import css from 'highlight.js/lib/languages/css';
import js from 'highlight.js/lib/languages/javascript';
import ts from 'highlight.js/lib/languages/typescript';
import html from 'highlight.js/lib/languages/xml';
import { createLowlight } from 'lowlight';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { library } from '@fortawesome/fontawesome-svg-core';
//https://fontawesome.com/search?q=quote&o=r ИКОНКИ
import {
  faBold,
  faItalic,
  faCode,
  faQuoteRight
} from '@fortawesome/free-solid-svg-icons';

library.add(
  faBold,
  faItalic,
  faCode,
  faQuoteRight
);



const props = defineProps({
  modelValue: {
    type: String,
    default: ''
  }
});

const emit = defineEmits(['update:modelValue']);

// Инициализация lowlight с зарегистрированными языками
const lowlight = createLowlight();

// Регистрация языков подсветки
lowlight.register('html', html);
lowlight.register('css', css);
lowlight.register('js', js);
lowlight.register('ts', ts);

const editor = ref(null);

watch(() => props.modelValue, (value) => {
  // HTML
  const isSame = editor.value.getHTML() === value;

  // JSON
  // const isSame = JSON.stringify(editor.value.getJSON()) === JSON.stringify(value)

  if (isSame) return;

  editor.value.commands.setContent(value, false);
});

onMounted(() => {
  editor.value = new Editor({
    extensions: [
      StarterKit,Document, Paragraph, Text, Blockquote,
      Heading.configure({
        levels: [1, 2, 3]
      }),
      CodeBlockLowlight.configure({
        lowlight
      })
    ],
    content: props.modelValue,

    //element: document.querySelector('.element'),
    // place the cursor in the editor after initialization
    autofocus: true,
    // make the text editable (default is true)
    editable: true,
    // prevent loading the default CSS (which isn't much anyway)
    injectCSS: false,


    onUpdate: () => {
      // HTML
      emit('update:modelValue', editor.value.getHTML());

      // JSON
      // emit('update:modelValue', editor.value.getJSON())
    }
  });
});

onBeforeUnmount(() => {
  editor.value?.destroy();
});



</script>


<template>

   <div v-if="editor" class="container " >
    <div class="control-group">
      <div class="button-group">
        <button class="menubar__button"
          @click="editor.chain().focus().toggleBold().run()"
          :class="{ 'is-active': editor.isActive('bold') }"
          title="Bold"
        >
          <FontAwesomeIcon icon="bold" />
        </button>

        <button class="menubar__button"
          @click="editor.chain().focus().toggleItalic().run()"
          :class="{ 'is-active': editor.isActive('italic') }"
          title="Italic"
        >
          <FontAwesomeIcon icon="italic" />
        </button>

        <button class="menubar__button"
        @click="editor.chain().focus().toggleBlockquote().run()"
        :class="{ 'is-active': editor.isActive('blockquote') }">
          <FontAwesomeIcon icon="quote-right" />
        </button>


         <button class="menubar__button"

          @click="editor.chain().focus().toggleCodeBlock().run()"
          :class="{ 'is-active': editor.isActive('codeBlock') }"
         >
          <FontAwesomeIcon icon="code" />
        </button>



      </div>
    </div>
  </div>
   <editor-content :editor="editor" />

</template>


<style lang="css" scoped>
.editor {
  border: 1px solid #ddd;
  border-radius: 4px;
  overflow: hidden;
  background-color:red;
}

.menubar {
  padding: 0.5rem;
  display: flex;
  flex-wrap: wrap;
  gap: 0.25rem;
  border-bottom: 1px solid #ddd;
  background: #f8f8f8;
}

.menubar__button {
  background: none;
  border: none;
  cursor: pointer;
  padding: 0.5rem;
  border-radius: 3px;
  color: #666;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  transition: all 0.2s ease;
}

.menubar__button:hover {
  background-color: #f0f0f0;
  color: #333;
}

.menubar__button.is-active {
  background-color: #e0e0e0;
  color: #000;
}

.editor__content {
  padding: 1rem;
  min-height: 200px;
}

</style>
