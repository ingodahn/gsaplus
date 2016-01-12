desc "Shortcut for assets-task"
task default: %w[assets]

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
  `cp bower_components/bootstrap-sass/assets/javascripts/bootstrap.min.js public/js/`
  `cp bower_components/jquery/dist/jquery.min.js public/js/`
end

desc "Publish all font assets"
task :fonts do
  `mkdir -p public/fonts/`
  `cp -r bower_components/bootstrap-sass/assets/fonts/bootstrap/. public/fonts`
  `cp -r bower_components/font-awesome/fonts/. public/fonts`
end

desc "Delete published assets"
task :clean do
  `rm -rf public/css/`
  `rm -rf public/fonts/`
  `rm -rf public/js/`
end
