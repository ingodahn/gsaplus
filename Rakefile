desc "Shortcut for assets-task"
task default: %w[update]

desc "Compile sass and publish all assets"
task assets: [:sass, :js, :fonts, :images]

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
  `cp bower_components/bootstrap-sass/assets/javascripts/bootstrap.min.js public/js/`
  `cp bower_components/jquery/dist/jquery.min.js public/js/`
  `cp bower_components/parallax.js/parallax.min.js public/js/`
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

desc "Publish all image assets"
task :images do
  `mkdir -p public/img/`
  `cp -r resources/assets/img/. public/img`
end

desc "Delete published assets"
task :clean do
  `rm -rf public/css/`
  `rm -rf public/fonts/`
  `rm -rf public/js/`
  `rm -rf public/img/`
end

desc "Executes bower install"
task :bower_install do
  sh "bower install"
end

task update: [:bower_install, :assets] do
  sh "composer install"
  sh "php artisan migrate"
end

desc "apply minor db changes - all data will be removed - run if tables weren't removed or added"
task :db_refresh do
  sh "php artisan migrate:refresh"
end

desc "apply major db changes - all data will be removed - run if tables were added or removed"
task :db_reset do
  sh "mysql scotchbox < ./database/sql/drop_all_tables.sql"
  sh "composer dump-autoload"
  sh "php artisan migrate"
end

desc "seed codes, week days and test data"
task :db_seed do
  sh "php artisan db:seed"
end

desc "apply minor db changes and seed test data - all data will be removed"
task db_refresh_and_seed: [:db_refresh, :db_seed]

desc "apply major db changes and seed test data - all data will be removed"
task db_reset_and_seed: [:db_reset, :db_seed]
