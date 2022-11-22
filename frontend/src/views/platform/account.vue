<template>
  <div class="app-container">
    <search-box
      :filters="filters"
      :model="searchModel"
      :row-span="6"
      label-width="80px"
      @search="search"
      @reset="reset"
    >
    </search-box>
    <el-row>
      <el-col :span="24">
        <el-table
          border
          size="mini"
          default-expand-all
          :data="[]"
          style="width: 100%"
          align="center"
        >
          <el-table-column
            align="center"
            width="60px"
            label="Id"
            prop="id">
          </el-table-column>
          <el-table-column
            align="center"
            label="Id"
            prop="id">
          </el-table-column>
          <el-table-column
            align="center"
            label="Id"
            prop="id">
          </el-table-column>
        </el-table>
      </el-col>
    </el-row>
  </div>
</template>

<script>
import SearchBox from '@/components/SearchBox/SearchBox'

export default {
  components: { SearchBox },
  name: 'PlatformAccount',
  data() {
    return {
      filters: [
        {
          type: 'input',
          name: 'account',
          placeholder: '',
          labelText: '账户:',
          options: []
        }
      ],
      searchModel: {
        account: ''
      }
    }
  },
  created() {
    this.defaultSearch = Object.assign({}, this.searchModel)
  },
  methods: {
    search() {
      console.log('search')
    },
    reset() {
      this.searchModel = Object.assign({}, this.defaultSearch)
      this.search()
    }
  }
}
</script>

<style scoped>

</style>
