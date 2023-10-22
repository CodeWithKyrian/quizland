const globalComponents = {
  'q-pane': require('components/Global/QPane.vue').default,
  'q-pane-header': require('components/Global/QPaneHeader.vue').default,
  'q-pane-body': require('components/Global/QPaneBody.vue').default,
  'q-pane-footer': require('components/Global/QPaneFooter.vue').default,
}

export default async ({app}) => {
  Object.entries(globalComponents).forEach(({name, component}) => {
    app.component(name, component)
  });
}
