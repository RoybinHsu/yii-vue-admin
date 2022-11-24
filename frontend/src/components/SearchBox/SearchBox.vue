<template>
  <div>
    <el-form :model="model" ref="searchBox" status-icon :label-width="labelWidth">
      <el-row>
        <el-col :span="rowSpan" v-for="filter in filters" :key="filter.key">
          <el-form-item :label="filter.labelText">
            <el-select v-if="filter.type === 'select'" v-model="model[filter.name]" :size="itemSize" placeholder="请选择" style="width:100%;">
              <el-option
                v-for="item in filter.options"
                :key="item.value"
                :value="item.value"
                :label="item.label"
              >
              </el-option>
            </el-select>
            <el-input
              v-if="filter.type === 'input'"
              v-model="model[filter.name]"
              :size="itemSize"
              :placeholder="filter.placeholder"
              autocomplete="off"></el-input>
          </el-form-item>
        </el-col>
      </el-row>
      <el-row>
        <el-col :span="11" :offset="1">
          <small>
            <slot name="extraLeft"><div class="default-slot"></div></slot>
          </small>
        </el-col>
        <el-col :span="12" class="search-box-btn-group">
          <slot name="buttonGroup"></slot>
          <el-button type="danger" plain size="small" icon="el-icon-delete" @click="resetForm">重置</el-button>
          <el-button type="success" plain size="small" icon="el-icon-search" @click="onSubmit">搜索</el-button>
        </el-col>
      </el-row>
      <div class="clearfix"></div>
    </el-form>
    <div class="clearfix"></div>
    <hr class="search-box-divider">
<!--    <el-divider></el-divider>-->
  </div>
</template>

<script>
export default {
  name: 'SearchBox',
  props: {
    // route object
    /**
     * filters: [{
     *  type: 'input',
     *  name: 'phone',
     *  placeholder: '',
     *  labelText: '',
     *  options: []
     *}]
     */
    filters: {
      type: Array,
      required: true
    },
    labelWidth: {
      type: String,
      default: '80px'
    },
    rowSpan: {
      type: Number,
      default: 6
    },
    itemSize: {
      type: String,
      default: 'small'
    },
    model: {
      type: Object,
      required: true
    }
  },
  computed: {},
  data() {
    return {}
  },
  created() {
  },
  methods: {
    onSubmit() {
      this.$emit('search')
    },
    resetForm() {
      this.$emit('reset')
    }
  }
}
</script>

<style scoped>
.search-box-form {
  border-bottom: 1px solid #ddd;
}

.clearfix {
  clear: both;
}

.search-box-btn-group {
  text-align: right;
}
.default-slot {
  width: 10px;
  height: 10px;
}
.search-box-divider {
  border-top: 0px;
  border-bottom: 1px solid #EBEEF5;
  margin-top: 10px;
  margin-bottom: 15px;
}
</style>
