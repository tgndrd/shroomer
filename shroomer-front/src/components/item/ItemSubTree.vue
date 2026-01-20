<script setup lang="ts">
import {onBeforeMount, ref, watch} from "vue";
import SubTreeAsciinator from "@/asciinator/sub.tree.asciinator.ts";
import WeatherAsciinator from "@/asciinator/weather.asciinator.ts";

const props = defineProps({
  subtree: {
    genus: {type: String, required: true},
    size: {type: Number, required: true}
  },
  weather_state: {type: String, required: true}
})

const template = ref('')
watch(() => props.subtree, () => {
  refresh()
})

function refresh() {
  let used_template = SubTreeAsciinator.prepareTemplate(props.subtree.size, props.subtree.genus)
  used_template = WeatherAsciinator.prepareWeather(used_template, props.weather_state)

  template.value = used_template
}

onBeforeMount(() => {
  refresh()
})
</script>

<template><pre class="inline-block text-gray-300" v-html="template"></pre></template>

<style>
</style>
