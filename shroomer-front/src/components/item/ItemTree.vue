<script setup lang="ts">
import {onBeforeMount, onUnmounted, ref, watch} from "vue";
import ItemSubtree from "@/components/item/ItemSubTree.vue";
import treeAsciinator from "@/asciinator/tree.asciinator.ts";
import WeatherAsciinator from '@/asciinator/weather.asciinator.ts'

const props = defineProps({
  tree: {
    genus: {type: String, required: true},
    id: {type: Number, required: true},
    slot: {type: Number, required: true},
    letter: {type: String, required: true},
  },
  weather_state: {type: String, required: true}
})

const empty = { genus: 'empty', size: 10}
const template = ref('')

watch(() => props.tree, () => {
  refresh()
})

function refresh() {
  let used_template = treeAsciinator.prepareTemplate(props.tree.slot, props.tree.id)
  used_template = treeAsciinator.prepareTrunk(used_template)
  used_template = treeAsciinator.prepareLeaf(used_template, props.tree.letter)
  used_template = WeatherAsciinator.prepareWeather(used_template, props.weather_state)

  template.value = used_template
}

onBeforeMount(() => {
  refresh()
})

onUnmounted(() => clearInterval(interval))
const interval = setInterval(function () {
  refresh()
}, 5000)

</script>

<template>
  <div class="inline-block" v-if="props.tree.slot == 0" >
    <pre class="text-gray-200" v-html="template"></pre>
    <item-subtree :subtree="{genus: 'empty', size: 10}" :weather_state="weather_state"/>
    <item-subtree :subtree="{genus: 'trunk', size: 10}" :weather_state="weather_state"/>
    <item-subtree :subtree="{genus: 'empty', size: 10}" :weather_state="weather_state"/>
  </div>

  <div class="inline-block" v-else-if="props.tree.slot <= 2" >
    <pre class="text-gray-200" v-html="template"></pre>
    <item-subtree :subtree="{genus: 'empty', size: 10}" :weather_state="weather_state"/>
    <item-subtree :subtree="props.tree.slot_1 ?? empty" :weather_state="weather_state"/>
    <item-subtree :subtree="{genus: 'trunk', size: 10}" :weather_state="weather_state"/>
    <item-subtree :subtree="props.tree.slot_2 ?? empty" :weather_state="weather_state"/>
    <item-subtree :subtree="{genus: 'empty', size: 10}" :weather_state="weather_state"/>
  </div>

  <div class="box-content inline-block" v-else >
    <pre class="text-gray-200" v-html="template"></pre>
    <item-subtree :subtree="{genus: 'empty', size: 10}" :weather_state="weather_state"/>
    <item-subtree :subtree="props.tree.slot_3 ?? empty" :weather_state="weather_state"/>
    <item-subtree :subtree="props.tree.slot_1 ?? empty" :weather_state="weather_state"/>
    <item-subtree :subtree="{genus: 'trunk', size: 10}" :weather_state="weather_state"/>
    <item-subtree :subtree="props.tree.slot_2 ?? empty" :weather_state="weather_state"/>
    <item-subtree :subtree="props.tree.slot_4 ?? empty" :weather_state="weather_state"/>
    <item-subtree :subtree="{genus: 'empty', size: 10}" :weather_state="weather_state"/>
  </div>
</template>

<style>
pre {
  font-size: 9px;
}
</style>
