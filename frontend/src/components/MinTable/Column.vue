<template>
  <section class="">
    <p class="min-table-list-card"><span class="min-table-list-title">{{ label }}: </span>
      <span class="min-table-list-content"><slot>{{ row[prop] || '' }}</slot></span>
    </p>
  </section>
</template>

<script>
export default {
  name: 'MinTableColumn',
  props: {
    prop: {
      type: String,
      required: true
    },
    row: {
      type: Object
    },
    label: {
      type: String,
      required: true
    }
  }
}
</script>

<style scoped>
.min-table-list-title {
  font-weight: bold;
}
.min-table-list-card {
  font-size: 12px;
  padding: 2px;
  margin: 0;
  line-height: 18px;
}
.min-table-list-content {
  color: #666666;
  margin-left: 8px;
}
</style>
