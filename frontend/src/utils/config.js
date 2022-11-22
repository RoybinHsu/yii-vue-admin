/**
 * app全局配置
 * @type {{platform: {}}}
 */
export const AppConfig = {
  platform: {
    '1688': '阿里巴巴',
    'PDD': '拼多多',
    'TAOBAO': '淘宝'
  },
  app_count_status: [
    { value: 0, label: '未授权' },
    { value: 1, label: '已授权' },
    { value: 3, label: '授权失败' },
    { value: 10, label: '授权过期' }
  ]
}
