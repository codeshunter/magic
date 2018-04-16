const scanBack = (res, app) => {
  app.setData({motto: JSON.stringify(res)})
}
const saveLocal = (key, value) => {
  
}
module.exports = {
  scanBack: scanBack
}