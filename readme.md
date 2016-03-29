# Set up for Server Administrator

## Required Applications

- apache 2
- mysql
- php >= 5.5.9
- composer
- bower
- rake
- git
- laravel (siehe https://laravel.com/docs/5.2)

Clone Repository:

``` bash
# cd /var/www
# git clone https://gitlab.uni-koblenz.de/iwm/gsa-online-plus.git
```

Configure Apache:
```
DocumentRoot /var/www/gsa-online-plus/public
```

Create Database and edit the the .env file:
```
DB_HOST=
DB_DATABASE=
DB_USERNAME=
DB_PASSWORD=
```

Initialize the project:

``` bash
cd /var/www # Change to the project dir inside the VM
composer install # Fetch php-dependencies
php artisan migrate # Migrate the database
bower install # Fetch frontend-dependencies
rake # Compile/copy frontend-dependencies into public
```

## Cron
If you are not using Vagrant, add the following cron job:

``` bash
* * * * * php /var/www/artisan schedule:run >> /dev/null 2>&1
```

You may need to change the path for the `artisan`-script (it's in this project's root). See <https://laravel.com/docs/master/scheduling>. The schedule at `app/Console/Kernel.php` has a test-command that should log a message to `storage/logs/laravel.log` every day. You can change the rate to `->everyMinute()` for testing.

# Set up for Developer
We use Vagrant for setting up a virtual machine in development. Clone this repo and run `vagrant up` to get a production-like environment with all dependencies installed. Our base box is [Scotch Box](https://box.scotch.io/). The project directory is symlinked into `/var/www`; changes made on the host will also be made on the client and vice versa. `/var/www/public` is the root of the webserver.

SSH into the VM (`vagrant ssh` or use vagrant@192.168.33.10 with password *vagrant* with your own client). Execute the following commands sequentially for the first-time setup. After that, run individual commands when needed (eg. if dependencies change).

``` bash
cd /var/www # Change to the project dir inside the VM
composer install # Fetch php-dependencies
php artisan migrate # Migrate the database
bower install # Fetch frontend-dependencies
rake # Compile/copy frontend-dependencies into public
```

## Common Errors
* *Permission denied*, *File does not exist* and other File-Errors: Sometimes the gitignore excludes directories that need to exist for the application. This is a configuration error and should be reported to <mbrack@uni-koblenz.de>.
* *You don't have permission to access / on this server.* The VM is not running and you are once again pestering some random server on the internet. Use `vagrant up` in the project dir to start the VM. On some occasions you'll have do reboot the host machine prior to that (not sure why, but it seems to help).
* VM not accessible at all, or loading infinitely: Reload VM. This seems to happen after suspending the host.

## Depenedencies
For the VM (on host system)

* Virtual Box (https://www.virtualbox.org/)
* Vagrant (https://www.vagrantup.com/)

For the Application (on guest system)

* The usual laravel dependencies
* sass (gem)
* phpunit (*optional*, see [provision.sh](provision.sh) for install instructions)


# Running
The application is automatically served by an Apache Server. It should be available to the host at <http://192.168.33.10/>. The Scotch Box testpage (containing some very useful information about the VM's configuration) is available at <http://192.168.33.10/scotchbox.php>.

## For non-developers
Follow these steps to update to the current development version (provided you did an install before, see above if not):

``` bash
git checkout development # Change to development branch
git pull origin development # Update project code
vagrant reload --provision # Update VM
vagrant ssh # ssh into VM
cd /var/www # Change to the project dir inside the VM
rake # Update all dependencies and assets
rake db_refresh_and_seed # if some database tables have changed (old data will be removed)
# or
rake db_reset_and_seed # if tables were removed or added (old data will be lost)
```

Note that this is only a slight modification of the install process. Also, it is very likely that all of the steps are neccessary, but this is a surefire way to get you up to date. Feel free to drop commands once you get more comfortable with the process.

## For developers
This is actually the same process for developers and non-developers, but developers are expected to know which of the individual commands are actually required and which not, thus saving time ;-).

Use `composer install` after modifying backend dependencies, `php artisan migrate` after making modifications to the databse or models, `bower install` + `rake` after modifying frontend-dependencies and just `rake` after modifying frontend-code. You may have to include scss-fragments in the [main app.scss](resources/assets/sass/_main.scss) and set up the [Rakefile](Rakefile) to `cp` scripts and fonts from the bower-directory to the [public](public)-directory. Also remember to include JavaScript-files in the [main template head](resources/views/layouts/head.blade.php).

Please make sure to run everything but editing and git inside the VM, to ensure compatibility. Even if you have laravel etc. installed on the host, running it's scaffolding-functions may be harmful if the host- and the target-version differ.

Please use the git branching model described here: <http://nvie.com/posts/a-successful-git-branching-model/>. Our main branch is `development`, accordingly. Adhere to [Semantic versioning](http://semver.org/) for version numbers.


# Glossary
* **User** Abstract term for a person using the system
* **Patient** Primary users of the system
* **Therapist** Supervise patients. There will be very few in the system.
* **Administrator** Administer the system
* **Assignment** Weekly writing-tasks for patients
* **Assignment-Template** Contain the task description for an assignment and may be reused
* **Code** Registration-codes, provide patients with a convenient way for registration
* **Response** Response of a therapist to a completed assignment

# People and Communications
* **Marco Brack** <mbrack@uni-koblenz.de> Developer
* **Sascha Zimmermann** <zimsa@uni-koblenz.de> Developer

* **Peter Ferdinand** <ferdinand@uni-koblenz.de> Head of IWM
* **Sergei Pachtchenko** <gektor@uni-koblenz.de>
* **Ingo Dahn** <dahn@uni-koblenz.de>
* **Astrid Wirth** <astrid.wirth@unimedizin-mainz.de>
* **Katje Böhme** <katja.boehme@unimedizin-mainz.de>
* **Rüdiger Zwerenz** <ruediger.zwerenz@unimedizin-mainz.de>

Please use our [Slack](https://iwm-unimedmainz.slack.com/messages/general/) or email for communication and [Trello](https://trello.com/b/NhCAw37H/gsa-softwareentwicklung) for Development-related Task-Tracking. There is also a [Trello Board](https://trello.com/b/GNS8jOrk/gsa-allgemein) for more general purposes.

# Links
* GitLab https://gitlab.uni-koblenz.de/iwm/gsa-online-plus
* Project-Slack https://iwm-unimedmainz.slack.com/messages/general/
* Software-Development Trello-Board https://trello.com/b/NhCAw37H/gsa-softwareentwicklung
* General Trello-Board https://trello.com/b/GNS8jOrk/gsa-allgemein
* BSCW https://bscw.uni-koblenz.de/bscw/bscw.cgi/3262854?client_size=1855x971
