desc "Shortcut for assets-task"
task default: %w[update]

desc "Compile sass and publish all assets"
task assets: [:sass, :js, :fonts]

desc "Compile and publish sass automatically (watch)"
task :watch do
  puts "Night gathers, and now my watch begins."
  trap('SIGINT') { puts " My watch has ended."; exit }
  sh "sass --watch resources/assets/sass/:public/css/"
end

desc "Compile and publish sass"
task :sass do
  puts `sass --update resources/assets/sass/:public/css/`
end

desc "Publish all javascript assets"
task :js do
  `mkdir -p public/js/`
  `mkdir -p public/js/i18n`
  `cp -r resources/assets/js/. public/js/`
  `cp bower_components/bootstrap-validator/dist/validator.min.js public/js/`
  `cp bower_components/bootstrap-sass/assets/javascripts/bootstrap.min.js public/js/`
  `cp bower_components/jquery/dist/jquery.min.js public/js/`
  `cp bower_components/parsleyjs/dist/parsley.min.js public/js/`
  `cp bower_components/parsleyjs/dist/i18n/de.js public/js/i18n/`
end

desc "Publish all font assets"
task :fonts do
  `mkdir -p public/fonts/`
  `cp -r bower_components/bootstrap-sass/assets/fonts/bootstrap/. public/fonts`
  `cp -r bower_components/bootstrap-sass/assets/fonts/bootstrap/. public/fonts/bootstrap` # For bootstrap-validator
  `cp -r bower_components/font-awesome/fonts/. public/fonts`
end

desc "Delete published assets"
task :clean do
  `rm -rf public/css/`
  `rm -rf public/fonts/`
  `rm -rf public/js/`
end

task update: [:assets] do
  sh "composer install"
  sh "php artisan migrate"
  sh "bower install"
end
