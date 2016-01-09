task default: %w[assets]

task assets: [:sass, :js, :fonts]

task :watch do
  puts "Night gathers, and now my watch begins."
  `sass --watch resources/assets/sass/:public/css/`
  puts "My watch has ended."
end

task :sass do
  `sass --update resources/assets/sass/:public/css/`
end

task :js do
  `mkdir -p public/js/`
  `cp bower_components/bootstrap-sass/assets/javascripts/bootstrap.min.js public/js/`
  `cp bower_components/jquery/dist/jquery.min.js public/js/`
end

task :fonts do
  `mkdir -p public/fonts/`
  `cp -r bower_components/bootstrap-sass/assets/fonts/bootstrap/. public/fonts`
end

task :clean do
  `rm -rf public/css/`
  `rm -rf public/fonts/`
  `rm -rf public/js/`
end
