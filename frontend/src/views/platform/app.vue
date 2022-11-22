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
      <el-button
        slot="buttonGroup"
        type="success"
        plain
        size="small"
        icon="el-icon-plus"
        @click="addAppModal"
        title="添加应用">添加应用
      </el-button>
    </search-box>
    <el-row>
      <el-col :span="24">
        <el-table
          border
          size="mini"
          default-expand-all
          :data="tableData"
          style="width: 100%"
          align="center"
        >
          <el-table-column
            align="center"
            width="60px"
            label="ID"
            prop="id">
            <el-tooltip
              slot-scope="scope"
              class="item"
              effect="dark"
              content="点击编辑"
              placement="top">
              <el-link
                @click="edit(scope.row)"
                type="primary"
              >{{ scope.row.id }}
              </el-link>
            </el-tooltip>
          </el-table-column>
          <el-table-column
            align="center"
            label="应用名称"
            width="100px"
            prop="app_name">
          </el-table-column>
          <el-table-column
            align="center"
            label="平台"
            width="70px"
            prop="platform_name">
          </el-table-column>
          <el-table-column
            align="center"
            label="应用首页"
            prop="redirect_url">
          </el-table-column>
          <el-table-column
            align="center"
            label="App Key"
            prop="app_key">
          </el-table-column>
          <el-table-column
            align="center"
            label="App Secret"
            prop="app_secret">
          </el-table-column>
          <el-table-column
            align="center"
            width="70px"
            label="拥有者"
            prop="username">
          </el-table-column>
          <el-table-column
            align="center"
            width="140px"
            label="创建时间"
            prop="created_at">
          </el-table-column>
          <el-table-column
            align="center"
            label="操作">
            <template slot-scope="scope">
              <el-button
                type="warning"
                size="mini"
                round
                @click="authApp(scope.row)"
                plain
                :disabled="!scope.row.can_authorize"
                :title="scope.row.can_authorize ? '' : '请在后台配置该应用'"
              ><i class="el-icon-connection"></i>商家授权
              </el-button>
            </template>
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
    <el-dialog title="添加应用" :visible.sync="addModalVisible">
      <el-form :model="addForm" ref="addForm" status-icon :label-width="'120px'" :rules="addAppRules">
        <el-form-item label="平台:" prop="platform">
          <el-select v-model="addForm.platform" placeholder="请选择" style="width: 100%;">
            <el-option
              v-for="item in platformOptions"
              :key="item.value"
              :value="item.value"
              :label="item.label"
            >
            </el-option>
          </el-select>
        </el-form-item>
        <el-form-item label="应用名称:" prop="app_name">
          <el-input v-model="addForm.app_name" autocomplete="off"></el-input>
        </el-form-item>
        <el-form-item label="App Key:" prop="app_key">
          <el-input v-model="addForm.app_key" autocomplete="off"></el-input>
        </el-form-item>
        <el-form-item label="App Secret:" prop="app_secret">
          <el-input v-model="addForm.app_secret" autocomplete="off"></el-input>
        </el-form-item>
        <el-form-item label="首页入口:" prop="redirect_url">
          <el-input v-model="addForm.redirect_url" autocomplete="off"></el-input>
        </el-form-item>
      </el-form>
      <div slot="footer" class="dialog-footer">
        <el-button @click="addModalVisible = false">取 消</el-button>
        <el-button type="primary" @click="add">提 交</el-button>
      </div>
    </el-dialog>
  </div>
</template>

<script>
import SearchBox from '@/components/SearchBox/SearchBox'
import { addPlatformApp, getAuthorizeUrl, getPlatformList } from '@/api/platform'
import { AppConfig } from '@/utils/config'

export default {
  components: { SearchBox },
  name: 'PlatformApp',
  data() {
    const platformOptions = [{ value: '', label: '全部' }]
    for (const k in AppConfig.platform) {
      platformOptions.push({ value: k, label: AppConfig.platform[k] })
    }
    return {
      filters: [
        {
          type: 'input',
          name: 'app_name',
          placeholder: '',
          labelText: '应用名称:',
          options: []
        },
        {
          type: 'select',
          name: 'platform',
          placeholder: '',
          labelText: '平台名称:',
          options: platformOptions
        }
      ],
      platformOptions: platformOptions,
      searchModel: {
        app_name: '',
        platform: '',
        limit: 20
      },
      addForm: {
        id: 0,
        app_name: '',
        app_key: '',
        app_secret: '',
        platform: '',
        redirect_url: ''
      },
      win: null,
      defaultAddForm: null,
      tableData: [],
      total: 0,
      addModalVisible: false,
      addAppRules: {
        platform: [
          { required: true, trigger: 'blur', message: '请选择平台' }
        ],
        app_name: [
          { required: true, trigger: 'blur', message: '请填写应用名称' }
        ],
        app_key: [
          { required: true, trigger: 'blur', message: '请填写App Key' }
        ],
        app_secret: [
          { required: true, trigger: 'blur', message: '请填写App Secret' }
        ],
        redirect_url: [
          { required: true, trigger: 'blur', message: '请填写应用入口地址' }
        ]
      }
    }
  },
  watch: {
    addModalVisible(val) {
      if (!val) {
        // 关闭
        this.addForm = this.defaultAddForm
      }
    }
  },
  created() {
    this.defaultSearch = Object.assign({}, this.searchModel)
    this.defaultAddForm = Object.assign({}, this.addForm)
    this.search()
  },
  methods: {
    search() {
      getPlatformList(this.searchModel).then(res => {
        this.tableData = res.data.data
        this.total = res.data.total
      })
    },
    reset() {
      this.searchModel = Object.assign({}, this.defaultSearch)
      this.search()
    },
    pageChange(val) {
      this.searchModel.page = val
      this.search()
    },
    addAppModal() {
      this.addModalVisible = true
    },
    add() {
      // 提交数据
      this.$refs.addForm.validate(valid => {
        if (valid) {
          addPlatformApp(this.addForm).then(res => {
            this.addModalVisible = false
            this.search()
          })
        }
      })
    },
    edit(row) {
      // this.addForm = Object.assign(this.addForm, row)
      const keys = Object.keys(this.addForm)
      keys.forEach(k => {
        this.addForm[k] = row[k]
      })
      this.addModalVisible = true
    },
    authApp(row) {
      // 授权应用
      const width = 800
      const height = 500
      const left = Math.ceil((window.outerWidth - width) / 2)
      const top = Math.ceil((window.outerHeight - height) / 2)
      console.log(left, top, window.outerWidth, window.outerHeight)
      // let loop = 0
      getAuthorizeUrl({ id: row.id }).then(res => {
        if (this.win) {
          this.win.close()
        }
        const features = 'height=' + height + ',width=' + width + ',toolbar=no,left=' + left + ',top=' + top + ',menubar=no,scrollbars=yes,resizable=yes'
        this.win = window.open(res.data.url, '_blank', features, true)
      }).catch(() => {
      })
    }
  }
}
</script>

<style scoped>

</style>
