let mix=require('laravel-mix')

mix.js('resources/js/app.js','public/js').react()
  .sass('resources/scss/admin.scss','public/css',[])
    .postCss('resources/css/app.css','public/css',[])

