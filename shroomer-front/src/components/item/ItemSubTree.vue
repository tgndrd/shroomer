<script setup lang="ts">
import {onBeforeMount, ref, watch} from "vue";
import SubTreeAsciinator from "@/asciinator/sub.tree.asciinator.ts";

const props = defineProps({
  subtree: {
    genus: {type: String, required: true},
    size: {type: Number, required: true}
  },
})

const template = ref('')
watch(() => props.subtree, () => {
  refresh()
})

function refresh() {
  template.value = SubTreeAsciinator.prepareTemplate(props.subtree.size, props.subtree.genus)
}

onBeforeMount(() => {
  refresh()
})
</script>

<template><pre class="inline-block text-gray-300" v-html="template"></pre></template>

<style>
</style>
