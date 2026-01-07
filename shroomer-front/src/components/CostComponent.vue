<script setup lang="ts">
defineProps({
  cost: {
    resourceFlora: Number,
    resourceFauna: Number,
    resourceEntomofauna: Number,
  }
})
</script>

<template>
  <div>
    [Flora {{ cost.resourceFlora }} / Fauna {{ cost.resourceFauna }} / Entomofauna {{ cost.resourceEntomofauna }}]
  </div>
</template>

<style scoped>

</style>
