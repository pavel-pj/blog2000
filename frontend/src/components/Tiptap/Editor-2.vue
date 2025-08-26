
<script setup lang="js">

import { ref, onMounted, onBeforeUnmount } from 'vue';
import Icon from './Icon.vue';
import { Editor, EditorContent } from '@tiptap/vue-3';
import StarterKit from '@tiptap/starter-kit';
import Underline from '@tiptap/extension-underline';

const props = defineProps({
  initialContent: {
    type: String,
    required: true,
    default: '<em>editable text</em>'
  },
  activeButtons: {
    type: Array,
    validator: (list) => {
      const validButtons = [
        'bold',
        'italic',
        'strike',
        'underline',
        'code',
        'h1',
        'h2',
        'h3',
        'bulletList',
        'orderedList',
        'blockquote',
        'codeBlock',
        'horizontalRule',
        'undo',
        'redo'
      ];
      return list.every(el => validButtons.includes(el));
    },
    default: () => ['bold', 'italic']
  }
});

const emit = defineEmits(['update']);

const editor = ref(null);
const html = ref('');
const json = ref('');

onMounted(() => {
  editor.value = new Editor({
    content: props.initialContent,
    extensions: [StarterKit, Underline],
    onUpdate: () => {
      html.value = editor.value.getHTML();
      json.value = editor.value.getJSON();
      emit('update', html.value);
    }
  });

  html.value = editor.value.getHTML();
  json.value = editor.value.getJSON();
});

onBeforeUnmount(() => {
  editor.value?.destroy();
});


</script>


<template>


 <div class="editor">
    <div class="menubar">
      <span v-for="actionName in activeButtons" :key="actionName">
        <button
          v-if="actionName === 'bold'"
          class="menubar__button"
          :class="{ 'is-active': editor.isActive('bold') }"
          @click="editor.chain().focus().toggleBold().run()"
        >
          <icon name="bold" />
        </button>
        <button
          v-if="actionName === 'italic'"
          class="menubar__button"
          :class="{ 'is-active': editor.isActive('italic') }"
          @click="editor.chain().focus().toggleItalic().run()"
        >
          <icon name="italic" />
        </button>

        <!-- Остальные кнопки остаются без изменений -->
        <!-- ... -->
      </span>
    </div>

    <editor-content class="editor__content" :editor="editor" />
  </div>

</template>


<style lang="scss">
/* Basic editor styles */
.tiptap {
  :first-child {
    margin-top: 0;
  }
  p {
    color:red;
  }

  /* List styles */
  ul,
  ol {
    padding: 0 1rem;
    margin: 1.25rem 1rem 1.25rem 0.4rem;

    li p {
      margin-top: 0.25em;
      margin-bottom: 0.25em;
    }
  }

  /* Heading styles */
  h1,
  h2,
  h3,
  h4,
  h5,
  h6 {
    line-height: 1.1;
    margin-top: 2.5rem;
    text-wrap: pretty;
  }

  h1,
  h2 {
    margin-top: 3.5rem;
    margin-bottom: 1.5rem;
  }

  h1 {
    font-size: 1.4rem;
  }

  h2 {
    font-size: 1.2rem;
  }

  h3 {
    font-size: 1.1rem;
  }

  h4,
  h5,
  h6 {
    font-size: 1rem;
  }

  /* Code and preformatted text styles */
  code {
    background-color: var(--purple-light);
    border-radius: 0.4rem;
    color: var(--black);
    font-size: 0.85rem;
    padding: 0.25em 0.3em;
  }

  pre {
    background: var(--black);
    border-radius: 0.5rem;
    color: var(--white);
    font-family: 'JetBrainsMono', monospace;
    margin: 1.5rem 0;
    padding: 0.75rem 1rem;

    code {
      background: none;
      color: inherit;
      font-size: 0.8rem;
      padding: 0;
    }
  }

  blockquote {
    border-left: 3px solid var(--gray-3);
    margin: 1.5rem 0;
    padding-left: 1rem;
  }

  hr {
    border: none;
    border-top: 1px solid var(--gray-2);
    margin: 2rem 0;
  }
}
</style>

*/
