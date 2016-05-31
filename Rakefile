def bcjs(component, target=File.basename(component))
  sh "cp bower_components/#{component} public/js/#{target}"
end



desc "Shortcut for assets-task"
task default: %w[update]

desc "Compile sass and publish all assets"
task assets: [:sass, :js, :fonts, :images, :css]

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

  bcjs("bootstrap-sass/assets/javascripts/bootstrap.min.js")
  bcjs("jquery/dist/jquery.min.js")

  bcjs("parallax.js/parallax.min.js")

  bcjs("parsleyjs/dist/parsley.min.js")
  bcjs("parsleyjs/dist/i18n/de.js", "i18n/parsley-de.js")

  bcjs("datatables.net/js/jquery.dataTables.min.js", "dataTables.min.js")
  bcjs("datatables.net-bs/js/dataTables.bootstrap.min.js")
  bcjs("datatables.net-responsive/js/dataTables.responsive.min.js")
  bcjs("datatables.net-responsive-bs/js/responsive.bootstrap.js", "dataTables.responsive.bootstrap.js")

  bcjs("moment/min/moment.min.js")
  bcjs("moment/locale/de.js", "i18n/moment-de.js")
  bcjs("eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js")

  bcjs("sweetalert/dist/sweetalert.min.js")

  bcjs("zxcvbn/dist/zxcvbn.js")

  bcjs("textarea-autosize/dist/jquery.textarea_autosize.min.js")

  bcjs("Chart.js/dist/Chart.min.js")
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

desc "Publish all precompiled css assets"
task :css do
  sh "cp bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css public/css/"
  sh "cp bower_components/datatables.net-responsive-bs/css/responsive.bootstrap.min.css public/css/datatables.responsive.bootstrap.min.css"
end

desc "Delete published assets"
task :clean_assets do
  `rm -rf public/css/`
  `rm -rf public/fonts/`
  `rm -rf public/js/`
  `rm -rf public/img/`
end

desc "Delete everything automated except the VM"
task clean: [:clean_assets] do
  sh "rm -rf vendor"
  sh "rm -rf bower_components"
  sh "rm -rf .sass-cache"
  sh "rm -f storage/framework/sessions/*"
  sh "rm -f storage/framework/views/*"
  sh "rm -f storage/logs/*"
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
task db_reset: [:dotenv] do
  require 'dotenv/tasks'
  sh "mysql "\
    "-u#{ENV['DB_USERNAME']} "\
    "-p#{ENV['DB_PASSWORD']} "\
    "-e 'SET @db_database=\"\`#{ENV['DB_DATABASE']}\`\"; source database/sql/reset_database.sql;'"
  sh "composer dump-autoload"
  sh "php artisan migrate"
end

desc "seed codes, week days, settings and test data"
task :db_seed do
  sh "php artisan db:seed"
end

desc "apply minor db changes and seed test data - all data will be removed"
task db_refresh_and_seed: [:db_refresh, :db_seed]

desc "apply major db changes and seed test data - all data will be removed"
task db_reset_and_seed: [:db_reset, :db_seed]

desc "seed codes, week days and settings"
task :db_seed_base do
  sh "php artisan db:seed --class=CodesTableSeeder"
  sh "php artisan db:seed --class=ConstantWeekDaysTableSeeder"
  sh "php artisan db:seed --class=TestSettingsTableSeeder"
  sh "php artisan db:seed --class=DefaultAdminTableSeeder"
end

desc "apply minor db changes and seed base data - all data will be removed"
task db_refresh_and_seed_base: [:db_refresh, :db_seed_base]

desc "apply major db changes and seed base data - all data will be removed"
task db_reset_and_seed_base: [:db_reset, :db_seed_base]
