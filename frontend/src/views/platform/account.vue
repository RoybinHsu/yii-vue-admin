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
          v-loading="loading"
          border
          size="mini"
          default-expand-all
          :data="accountList"
          style="width: 100%"
          align="center"
        >
          <el-table-column
            align="center"
            width="80px"
            label="平台"
            prop="platform_name">
          </el-table-column>
          <el-table-column
            align="center"
            label="应用"
            prop="app_name">
          </el-table-column>
          <el-table-column
            align="center"
            label="商家ID"
            prop="owner_id">
          </el-table-column>
          <el-table-column
            align="center"
            label="商家名称"
            prop="owner_name">
          </el-table-column>
          <el-table-column
            align="center"
            label="access_token过期时间"
            prop="access_token_expire_at">
            <el-tag
              effect="plain"
              slot-scope="scope"
              :type="scope.row.access_token_type">{{ scope.row.access_token_expire_at }}</el-tag>
          </el-table-column>
          <el-table-column
            align="center"
            label="refresh_token过期时间"
            prop="refresh_token_expire_at">
            <el-tag slot-scope="scope" :type="scope.row.refresh_token_type">{{ scope.row.refresh_token_expire_at }}</el-tag>
          </el-table-column>
          <el-table-column
            align="center"
            label="首次授权时间"
            prop="created_at">
          </el-table-column>
          <el-table-column
            align="center"
            label="状态"
            prop="status_desc">
          </el-table-column>
        </el-table>
      </el-col>
    </el-row>
    <el-row>
      <el-col :span="24" class="pagination-box">
        <el-pagination
          @current-change="pageChange"
          background
          :page-size="searchModel.limit"
          layout="prev, pager, next"
          :total="total">
        </el-pagination>
      </el-col>
    </el-row>
  </div>
</template>

<script>
import SearchBox from '@/components/SearchBox/SearchBox'
import { AppConfig } from '@/utils/config'
import { getAuthorizeAccountList } from '@/api/platform'

export default {
  components: { SearchBox },
  name: 'PlatformAccount',
  data() {
    const platformOptions = [{ value: '', label: '全部' }]
    for (const k in AppConfig.platform) {
      platformOptions.push({ value: k, label: AppConfig.platform[k] })
    }
    AppConfig.app_count_status.unshift({ value: '', label: '全部' })
    return {
      filters: [
        {
          type: 'select',
          name: 'platform',
          placeholder: '',
          labelText: '平台:',
          options: platformOptions
        },
        {
          type: 'input',
          name: 'app_name',
          placeholder: '',
          labelText: '应用:',
          options: []
        },
        {
          type: 'select',
          name: 'status',
          placeholder: '',
          labelText: '状态:',
          options: AppConfig.app_count_status
        }
      ],
      loading: false,
      searchModel: {
        platform: '',
        app_name: '',
        status: '',
        page: 1,
        limit: 20
      },
      total: 0,
      accountList: []
    }
  },
  created() {
    this.defaultSearch = Object.assign({}, this.searchModel)
    this.search()
  },
  methods: {
    search() {
      this.loading = true
      getAuthorizeAccountList(this.searchModel).then(res => {
        this.accountList = res.data.data
      }).catch(err => {
        console.log(err)
      }).finally(() => {
        this.loading = false
      })
    },
    reset() {
      this.searchModel = Object.assign({}, this.defaultSearch)
      this.search()
    },
    pageChange() {
    }
  }
}
</script>

<style scoped>

</style>
