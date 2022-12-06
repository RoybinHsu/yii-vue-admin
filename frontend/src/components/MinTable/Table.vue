<template>
  <section>
    <div class="min-screen-list-container" v-for="(item, index) in tableList" :key="index">
      <slot v-for="key in getItemKeys(item)" v-bind:index="key" :name="key" v-bind:row="item"></slot>
    </div>
    <el-divider
      v-if="loading_"
      content-position="center">
      <span class="text-holder"><i class="el-icon-loading"></i>&nbsp;正在加载...</span>
    </el-divider>
    <el-divider
      v-if="loadMoreBtn_"
      @click="loadMoreInternal"
      content-position="center"><span
      class="text-holder">点击加载更多</span>
    </el-divider>
    <el-divider
      v-if="noMore_"
      content-position="center">
      <span class="text-holder">暂无更多数据</span>
    </el-divider>
  </section>
</template>

<script>
export default {
  name: 'MinTable',
  props: {
    data: {
      type: Array,
      required: true
    },
    loading: {
      type: Boolean,
      default: false
    },
    loadMore: {
      type: Function,
      required: true
    },
    loadMoreBtn: {
      type: Boolean,
      default: false
    },
    noMore: {
      type: Boolean,
      default: false
    }
  },
  data() {
    return {
      tableList: [],
      loading_: false,
      noMore_: false,
      loadMoreBtn_: true
    }
  },
  created() {
  },
  watch: {
    noMore(val) {
      this.noMore_ = val
      if (val) {
        this.loading_ = false
        this.loadMoreBtn_ = false
      }
    },
    loading(val) {
      this.loading_ = val
      if (val) {
        this.loadMoreBtn_ = false
        this.noMore_ = false
      }
    },
    loadMoreBtn(val) {
      this.loadMoreBtn_ = val
      if (val) {
        this.loading_ = false
        this.noMore_ = false
      }
    },
    data(val) {
      if (val.length > 0) {
        this.tableList = this.tableList.concat(...val)
      } else {
        this.noMore_ = true
      }
    }
  },
  mounted() {
    addEventListener('scroll', this.handleScroll)
  },
  methods: {
    handleScroll() {
      let scrollTop = document.documentElement.scrollTop || document.body.scrollTop
      scrollTop = Math.floor(scrollTop)
      let windowHeight = document.documentElement.clientHeight || document.body.clientHeight
      windowHeight = Math.floor(windowHeight)
      let scrollHeight = document.documentElement.scrollHeight || document.body.scrollHeight
      scrollHeight = Math.floor(scrollHeight)
      if (scrollTop + windowHeight === scrollHeight) {
        // 你想做的事情
      }
    },
    loadMoreInternal() {
      this.loadMore()
    },
    getItemKeys(item) {
      const keys = Object.keys(item)
      keys.push('append')
      return keys
    }
  }
}
</script>

<style scoped>
.min-screen-list-container {
  border: 1px solid #dddddd;
  border-radius: 5px;
  margin-top: 5px;
  padding: 5px 10px;
}

</style>
