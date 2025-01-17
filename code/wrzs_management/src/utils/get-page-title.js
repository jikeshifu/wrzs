import defaultSettings from '@/settings'

const title = defaultSettings.title || '无人值守系统控制中心'

export default function getPageTitle(pageTitle) {
  if (pageTitle) {
    return `${pageTitle} - ${title}`
  }
  return `${title}`
}
