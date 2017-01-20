module.exports = {
  entry: './admin/assets/src/app.js',
  output: {
    path: './admin/assets/dist',
    filename: 'bundle.js'
  },

  module: {
  loaders: [
    {
      test: /\.js$/,
      exclude: /(node_modules|bower_components)/,
      loader: 'babel-loader',
      query: {
        presets: ['env']
      }
    }
  ]
}
}
