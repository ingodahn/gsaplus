GSA Online Plus is a Laravel-based Webapplication for psychological post-rehab support. Over the course of 12 weeks, patients complete serveral writing-tasks, which are assigned and reviewed by therapists.

This document describes how to set up the required server-stack and how to update to new versions.



# Setup
The following is a description of the *recommended* server setup. It may be technically possible to exchange some of the used components (e.g. **nginx** instead of **apache**), but is discouraged. We need to differentiate two different environments: *Development* and *production*. The *production*-environment is going to be the server in Mainz, which will eventually be made publicly accessible and thus needs to be strictly secured. It must therefore be set up by hand. The *development*-environment is a Virtual Machine, closely resembling the production-environment. It can be set up almost entirely automatically. This guide will focus on *describing* the required environment, rather then how to *set up* such an environment.

It is assumend that you retreive the project's source code via git. Unless specified otherwise, only the `master`-branch is save for production.


## Production
Basically, you'll need a standard LAMP-stack with some additional dependencies and configurations. Currently, we are targeting ubuntu 14.04 LTS, but this may change to the new LTS in april 2016. You'll have to install the following:

* apache2
* A mysql server
* php (>= 5.6, unlike as stated in laravel installation)
* composer
* The laravel-dependencies (<https://laravel.com/docs/5.2>)
* phpunit (*optional*, see [provision.sh](provision.sh) for installation reference)
* ruby
  * rake
  * sass
* nodejs
  * bower

Make the `public`-directory of this repo the root of your webserver and enforce HTTPS-connections. The project is set up to read certain environment-specific configuration-options from a file called `.env`. Copy the `.env.example` to `.env` to receive an empty configuration-file. Create a database and enter host, databasename, username and password into `.env`. Enter the mailing-configuration as well. You can now run `rake` to automatically install some local project-dependencies, have the database set up and migrated, and compile and copy assets.

Next, add the following cron job:

``` bash
* * * * * php /var/www/artisan schedule:run >> /dev/null 2>&1
```

You may need to change the path for the `artisan`-script (it's in this project's root). See <https://laravel.com/docs/master/scheduling>. The schedule at `app/Console/Kernel.php` has a test-command that should log a message to `storage/logs/laravel.log` every day. You can change the rate to `->everyMinute()` for testing.


## Development
We use [Vagrant](https://www.vagrantup.com/) with a [VirtualBox](https://www.virtualbox.org/)-Provider (please install both) for setting up a virtual machine for development. Clone this repo and run `vagrant up` to get a production-like environment with all dependencies installed. Our base box is [Scotch Box](https://box.scotch.io/). The project directory is symlinked into `/var/www`; changes made on the host will also be made on the client and vice versa. `/var/www/public` is the root of the webserver.

SSH into the VM (`vagrant ssh` or use `vagrant@192.168.33.10` with password `vagrant` with your own ssh-client). Execute the following commands sequentially for the first-time setup.

``` bash
cd /var/www # Change to the project dir inside the VM
rake # Automated task to update dependencies, compile assets, etc.
```

The application should now be accessible at <http://192.168.33.10/>.



# Update
To update an existing installation, there is a standard procedure (described below) that should suffice in 90% of all cases. If additional steps must be taken, the developers communicate the need for this.

Generally, you just have to `pull` the new version of the source code with git and run `rake` again.

Rake is a task-runner that we use to automate repetetive tasks, like for example invoking bower to download jquery and then copy it to it's appropriate place in the `public`-directory. Calling `rake` invokes the default task (which is `update` at the time of writing this), which in turn invokes a bunch of other tasks that compose the update-task. A full list of all available tasks can be displayed by running `rake -T`. If you know what you are doing, you can run single tasks directly (`rake taskname`) to save some time.

There are also some tasks which are not called by the update-task, mainly the `watch`-task (which watches the scss-scources for changes and compiles them live) or the `db_reset_`-tasks (which delete the database before migrating them). The latter need to be used manually if database-tables need to be added or removed. The developers should notify about this, but if you see related errors, they propably forgot to. Note that all `db_`-tasks *delete all data* and should be used with extreme caution on the production environment.



# Glossary
This may be out of date.

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
* **Ingo Dahn** <dahn@uni-koblenz.de> Developer
* **Sergei Pachtchenko** <gektor@uni-koblenz.de> Developer

* **Peter Ferdinand** <ferdinand@uni-koblenz.de> Head of IWM
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
